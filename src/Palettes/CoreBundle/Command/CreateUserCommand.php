<?php

namespace Palettes\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Palettes\CoreBundle\Model\User;

/**
 * CreateUserCommand.
 *
 * @author Adam Wójs <adam@wojs.pl>
 */
class CreateUserCommand extends ContainerAwareCommand {
    
    protected function configure() {
        $this->setName('admin:user:create')
            ->setDescription('Create application user.')
            ->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'User login')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'User password')
            ->addOption('email', 'm', InputOption::VALUE_REQUIRED, 'User e-mail')
            ->addOption('role', 'r', InputOption::VALUE_REQUIRED, 'User role', 'USER')
        ;        
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $username = $input->getOption('username');
        $password = $input->getOption('password');
        $email = $input->getOption('email');
        
        $role = strtoupper($input->getOption('role'));
        if(!in_array($role, ['USER', 'ADMIN'])) {
            throw new \InvalidArgumentException("User role $role is invalid. Available roles: USER, ADMIN.");
        }
        
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setSalt($this->generateSalt());
        $user->setPlainPassword($password); 
        $user->setRole("ROLE_$role");

        if($this->validate($user, $output)) {
            $user->setPassword($this->encodePassword($user));
            $user->save();            
            
            $output->writeln("Utworzono użytkownika $username");
        }
    }
    
    private function generateSalt() {
        return md5(uniqid(null, true));
    }
    
    private function encodePassword(User $user) {
        $encoder = $this->getContainer()
            ->get('security.encoder_factory')
            ->getEncoder($user);
        
        return $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
    }
    
    private function validate(User $user, OutputInterface $output) {
        $validator = $this->getContainer()
            ->get('validator');
        
        $errors = $validator->validate($user);
        if(count($errors) > 0) {
            foreach($errors as $error) {
                $output->writeln("<error>$error</error>");
            }
            
            return false;
        }        
        
        return true;
    }    
}
