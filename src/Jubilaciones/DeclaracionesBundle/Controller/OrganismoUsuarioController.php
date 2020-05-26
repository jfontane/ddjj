<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Jubilaciones\DeclaracionesBundle\Form\UserType;
use Jubilaciones\DeclaracionesBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class OrganismoUsuarioController extends Controller {

  public function listarAction(Request $request) {
    $em = $this->getDoctrine()->getManager();
    //$usuarios = $em->getRepository('JubilacionesDeclaracionesBundle:User')->findAll();

    $dql = "SELECT u
    FROM JubilacionesDeclaracionesBundle:User u
    ORDER BY u.username Desc";
    $usuarios = $em->createQuery($dql)->getResult();


    $paginator = $this->get('knp_paginator');
    $pagination = $paginator->paginate(
      $usuarios,
      $request->query->getInt('page', 1),
      10
    );

    //dump($pagination);die;
    return $this->render('@JubilacionesDeclaraciones/ContralorUsuario/listar.html.twig', array(
      'pagination' => $pagination
    ));
  }


public function editarAction(Request $request, UserInterface $user) {
  //dump($user);die;

  $passwordEncoder = $this->get('security.password_encoder');

  $form = $this->createForm(UserType::class, $user)
  ->add('Guardar', SubmitType::class);
  $form->remove('roles');$form->remove('zona');$form->remove('username');
  $form->handleRequest($request);
  if ($form->isSubmitted() && $form->isValid()) {
    //$evento->setDescripcion($this->get('eventos.util')->autoLinkText($evento->getDescripcion()));
    $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
    $user->setPassword($password);
    $em = $this->getDoctrine()->getManager();
    $em->persist($user);
    $em->flush();
    AbstractBaseController::addWarnMessage('El Usuario "' . $user->getUsername()
    . '" se ha modificado correctamente.');
    //  $this->get('eventos.notificacion')->sendToAll('Symfony 2020!', 'Se ha actualizado el evento '.$organismo->getNombre().'.');
    return $this->redirect($this->generateUrl('principal_logueado'));
  }
  return $this->render('@JubilacionesDeclaraciones/OrganismoUsuario/editar.html.twig'
  , array('form' => $form->createView(), 'usuario' => $user
));
}

}
