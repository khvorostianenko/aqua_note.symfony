<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Genus;
use AppBundle\Form\GenusFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * // вариант №4 контроля доступа всего контроллера
 * @Security("is_granted('ROLE_MANAGE_GENUS')")
 * @Route("/admin")
 */
class GenusAdminController extends Controller
{
    /**
     * @Route("/genus", name="admin_genus_list")
     * // вариант №3 контроля доступа роута
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        # универсальный способ контролировать права доступа части метода (вариант №1). Если нет, то accessDeniedException
//        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            throw $this->createAccessDeniedException('GET OUT!');
//        }
        
        # вариант №2 контроля доступа части метода
//        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $genuses = $this->getDoctrine()
            ->getRepository('AppBundle:Genus')
            ->findAll();

        return $this->render('admin/genus/list.html.twig', array(
            'genuses' => $genuses
        ));
    }

    /**
     * @Route("/genus/new", name="admin_genus_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(GenusFormType::class);
     
        // only handles data on POST
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $genus = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($genus);
            $em->flush();
            
            $this->addFlash(
                'success',
                sprintf('Genus created - you: %s!', $this->getUser()->getEmail())
            );
            
            return $this->redirectToRoute('admin_genus_list');
        }
        
        return $this->render('admin/genus/new.html.twig', [
            'genusForm' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/genus/{id}/edit", name="admin_genus_edit")
     */
    public function editAction(Request $request, Genus $genus)
    {
        $form = $this->createForm(GenusFormType::class, $genus);
        
        // only handles data on POST
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $genus = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($genus);
            $em->flush();
            
            $this->addFlash('success', 'Genus updated - you are amazing!');
            
            return $this->redirectToRoute('admin_genus_list');
        }
        
        return $this->render('admin/genus/edit.html.twig', [
            'genusForm' => $form->createView()
        ]);
    }
}