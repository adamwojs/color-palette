<?php

namespace Palettes\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Palettes\CoreBundle\Form\Type\ColorType;
use Palettes\CoreBundle\Model\PaletteQuery;
use Palettes\CoreBundle\Model\Color;

/**
 * ColorController
 *
 * @author Adam Wójs <adam@wojs.pl>
 * 
 * @Route("/palette/{paletteId}/color")
 */
class ColorController extends Controller {
    
    /**
     * Dodaje nowy kolor do palety.
     * 
     * @Route("/new", name="color_new")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('ROLE_USER')")
     */
    public function newAction(Request $request) {
        $color = new Color();
        $color->setPalette($this->getPaletteFromRequest($request));
        
        $form = $this->createCreateForm($color);
        
        return [
            'form' => $form->createView(),
            'color' => $color
        ];
    }
    
    /**
     * @Route("/", name="color_create")
     * @Method("POST")
     * @Template("PalettesCoreBundle:Color:new.html.twig")
     * @Security("is_granted('ROLE_USER')")
     */
    public function createAction(Request $request) {
        $color = new Color();
        $color->setPalette($this->getPaletteFromRequest($request));
        
        $form = $this->createCreateForm($color);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $color->save();
            
            return $this->redirect($this->generateUrl("palette_show", [
                'id' => $color->getPaletteId()
            ]));
        }
        
        return [
            'form' => $form->createView(),
            'color' => $color
        ];
    }

    /**
     * @Route("/{id}/edit", name="color_edit")
     * @Method("GET")
     * @ParamConverter("color", class="Palettes\CoreBundle\Model\Color")
     * @Security("is_granted('ROLE_USER')")
     * @Template()
     */
    public function editAction(Request $request, Color $color) {
        $form = $this->createEditForm($color);
        
        return [
            'form' => $form->createView(),
            'color' => $color
        ];
    }
    
    /**
     * @Route("/{id}", name="color_update")
     * @Method("PUT")
     * @ParamConverter("color", class="Palettes\CoreBundle\Model\Color")
     * @Security("is_granted('ROLE_USER')")
     * @Template("ColorsCoreBundle:Color:edit.html.twig")
     */
    public function updateAction(Request $request, Color $color) {
        $form = $this->createEditForm($color);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $color->save();
            
            return $this->redirect($this->generateUrl("palette_show", [
                'id' => $color->getPaletteId()
            ]));
        }
        
        return [
            'form' => $form->createView(),
            'color' => $color
        ];
    }
    
    /**
     * Usuwamy kolor z palety
     * 
     * @Route("/{id}", name="color_delete")
     * @Method("DELETE")
     * @Security("is_granted('ROLE_USER')")
     */
    public function deleteAction(Request $request, Color $color) {
        $form = $this->createDeleteForm($color);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $color->delete();            
        }
       
        return $this->redirect($this->generateUrl('palette_edit', ['id' => $color->getPaletteId()]));
    }
    
    protected function createCreateForm(Color $color) {
        $form = $this->createForm(new ColorType(), $color, [
            'action' => $this->generateUrl("color_create", [
                'paletteId' => $color->getPaletteId()
            ]),
            'method' => 'POST'
        ]);
        
        $form->add('submit', 'submit', [
            'label' => 'Dodaj kolor'
        ]);
        
        return $form;
    }
    
    protected function createEditForm(Color $color) {
        $form = $this->createForm(new ColorType(), $color, [
            'action' => $this->generateUrl("color_update", [
                'id' => $color->getId(),
                'paletteId' => $color->getPaletteId()
            ]),
            'method' => 'PUT'
        ]);
        
        $form->add('submit', 'submit', [
            'label' => 'Zapisz zmiany'
        ]);
        
        return $form;
    }
    
    protected function createDeleteForm(Color $color) {
        return $this->createFormBuilder(null, [
                'csrf_protection' => false
            ])
            ->setAction($this->generateUrl("color_delete", [
                'id' => $color->getId(),
                'paletteId' => $color->getPaletteId()
            ]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', [
                'label' => 'Usuń'
            ])
            ->getForm();
    }
    
    private function getPaletteFromRequest(Request $request) {
        $id = $request->get('paletteId');
        
        $palette = PaletteQuery::create()
            ->findPk($id);
        if(!$palette) {
            throw $this->createNotFoundException("Palette $id not found!");
        }
        
        return $palette;
    }
}
