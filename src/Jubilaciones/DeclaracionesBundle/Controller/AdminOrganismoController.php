<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Jubilaciones\DeclaracionesBundle\Form\UserType;
use Jubilaciones\DeclaracionesBundle\Entity\User;

class AdminOrganismoController extends Controller {

    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $organismos = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                       $organismos,
                       $request->query->getInt('page', 1),
                       10
               );

        //dump($pagination);die;
        return $this->render('@JubilacionesDeclaraciones/AdminOrganismo/listar.html.twig', array(
                    'pagination' => $pagination
        ));
    }

  }
