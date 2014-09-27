<?php

namespace Palettes\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Palettes\CoreBundle\Form\Type\PaletteType;
use Palettes\CoreBundle\Model\Palette;
use Palettes\CoreBundle\Model\PaletteQuery;

/**
 * PaletteController
 *
 * @author Adam Wójs <adam@wojs.pl>
 * 
 * @Route("/palette")
 */
class PaletteController extends Controller {
    
    /**
     * Akacja wyświetlająca indeks palet.
     * 
     * @Route("/", name="palette_index")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction() {
        $palettes = PaletteQuery::create()
            ->find();
        
        return [
            'palettes' => $palettes
        ];
    }
    
    /**
     * Tworzenie nowej palety.
     * 
     * @Route("/new", name="palette_new")
     * @Method({"GET"})
     * @Template()
     */
    public function newAction() {
        $palette = new Palette();
        
        $form = $this->createCreateForm($palette);
        
        return [
            'form'    => $form->createView(),
            'palette' => $palette
        ];
    }
    
    /**
     * @Route("/", name="palette_create")
     * @Method("POST")
     * @Template("PalettesCoreBundle:Palette:new.html.twig")
     */
    public function createAction(Request $request) {
        $palette = new Palette();
        
        $form = $this->createCreateForm($palette);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $palette->save();
            
            return $this->redirect($this->generateUrl("palette_index"));
        }
        
        return [
            'form' => $form->createView(),
            'palette' => $palette
        ];
    }

    /**
     * @Route("/{id}", name="palette_show")
     * @Method("GET")
     * @ParamConverter("palette", class="Palettes\CoreBundle\Model\Palette")
     * @Template()
     */
    public function showAction(Palette $palette) {
        $deleteForm = $this->createDeleteForm($palette);
        
        return [
            'palette' => $palette,
            'delete_form' => $deleteForm->createView()
        ];
    }
    
    /**
     * @Route("/{id}/edit", name="palette_edit")
     * @Method("GET")
     * @ParamConverter("palette", class="Palettes\CoreBundle\Model\Palette")
     * @Template()
     */
    public function editAction(Palette $palette) {
        $form = $this->createEditForm($palette);
        
        return [
            'form' => $form->createView(),
            'palette' => $palette
        ];
    }
    
    /**
     * @Route("/{id}", name="palette_update")
     * @Method("PUT")
     * @ParamConverter("palette", class="Palettes\CoreBundle\Model\Palette")
     * @Template("PalettesCoreBundle:Palette:edit.html.twig")
     */
    public function updateAction(Request $request, Palette $palette) {
        $form = $this->createEditForm($palette);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $palette->save();
            
            return $this->redirect($this->generateUrl("palette_index"));
        }
        
        return [
            'form' => $form->createView(),
            'palette' => $palette
        ];
    }
    
    /**
     * Usuwa palete.
     * 
     * @Route("/{id}", name="palette_delete")
     * @Method("DELETE")
     * @ParamConverter("palette", class="Palettes\CoreBundle\Model\Palette")
     */
    public function deleteAction(Request $request, Palette $palette) {
        $form = $this->createDeleteForm($palette);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            // Usuwamy palete
            $palette->delete();            
        }
        
        return $this->redirect($this->generateUrl('palette_index'));
    }
    
    protected function createCreateForm(Palette $palette) {
        $form = $this->createForm(new PaletteType(), $palette, [
            'action' => $this->generateUrl("palette_create"),
            'method' => 'POST'
        ]);
        
        $form->add('submit', 'submit', [
            'label' => 'Utwórz palete'
        ]);
        
        return $form;
    }
    
    protected function createEditForm(Palette $palette) {
        $form = $this->createForm(new PaletteType(), $palette, [
            'action' => $this->generateUrl("palette_update", [
                'id' => $palette->getId()
            ]),
            'method' => 'PUT'
        ]);
        
        $form->add('submit', 'submit', [
            'label' => 'Zapisz zmiany'
        ]);
        
        return $form;
    }
    
    protected function createDeleteForm(Palette $palette) {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl("palette_delete", ['id' => $palette->getId()]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', [
                'label' => 'Usuń'
            ])
            ->getForm();
    }
}
