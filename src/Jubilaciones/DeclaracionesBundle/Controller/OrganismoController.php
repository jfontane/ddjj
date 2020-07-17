<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Jubilaciones\DeclaracionesBundle\Form\DeclaracionjuradaType;
use Jubilaciones\DeclaracionesBundle\Entity\Representante;
use Jubilaciones\DeclaracionesBundle\Classes\Util;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Jubilaciones\DeclaracionesBundle\Form\OrganismoType;
use Symfony\Component\Security\Core\User\UserInterface;



//use Symfony\Component\Validator\Constraints\Length;

class OrganismoController extends Controller {

  public function listarAction() {
    $em = $this->getDoctrine()->getManager();
    $organismos = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findAllOrganismosAlfabeticamente();
    //dump($declaraciones);die;
    return $this->render('@JubilacionesDeclaraciones/ContralorOrganismo/listar.html.twig', array(
      'organismos' => $organismos
    ));
  }

public function detallesAction(UserInterface $user) {
      $user = $this->getUser();
      $organismo_codigo = $user->getUsername();
      $em = $this->getDoctrine()->getManager();
      $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array("codigo"=>$organismo_codigo));
      //dump($declaraciones);die;
      return $this->render('@JubilacionesDeclaraciones/OrganismoOrganismo/detalle.html.twig', array(
                  'organismo' => $organismo
      ));
  }

  public function verAction($id) {
    $user = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->find($id);

    //$declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();
    if($user->hasRole('ROLE_USER')){
        return $this->render('@JubilacionesDeclaraciones/OrganismoOrganismo/ver.html.twig', array(
                    'organismo' => $organismo
        ));
        //filtrar declaraciones para este usuario
        //dump($user);exit;
    } else {
      return $this->render('@JubilacionesDeclaraciones/ContralorOrganismo/ver.html.twig', array(
        'organismo' => $organismo
      ));
    };
  }


  public function listarDeclaracionesPorOrganismoAction($id) {
    $em = $this->getDoctrine()->getManager();
    $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->find($id);
    return $this->render('@JubilacionesDeclaraciones/ContralorOrganismo/declaracionesPorOrganismo.html.twig', array(
      'organismo' => $organismo
    ));
  }

  public function nuevoAction(Request $request) {

  }

  public function editarAction(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();
    if (null == $organismo = $em->find('JubilacionesDeclaracionesBundle:Organismo', $id)) {
      throw $this->createNotFoundException('No existe el organismo solicitado.');
    }
    $form = $this->createForm(OrganismoType::class, $organismo)
    ->add('Guardar', SubmitType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      //$evento->setDescripcion($this->get('eventos.util')->autoLinkText($evento->getDescripcion()));
      $em->persist($organismo);
      $em->flush();
      AbstractBaseController::addWarnMessage('El Organismo "' . $organismo->getNombre()
      . '" se ha modificado correctamente.');
    //  $this->get('eventos.notificacion')->sendToAll('Symfony 2020!', 'Se ha actualizado el evento '.$organismo->getNombre().'.');
      return $this->redirect($this->generateUrl('contralor_organismo_listar'));
    }
    return $this->render('@JubilacionesDeclaraciones/ContralorOrganismo/editar.html.twig'
    , array('form' => $form->createView(), 'organismo' => $organismo
  ));
}

public function modificarAction(Request $request, UserInterface $user) {
  $user = $this->getUser();
  $organismo_codigo = $user->getUsername();
  $em = $this->getDoctrine()->getManager();
  $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array("codigo"=>$organismo_codigo));

  /*if (null == $organismo = $em->find('JubilacionesDeclaracionesBundle:User', $id)) {
    throw $this->createNotFoundException('No existe el Usuario solicitado.');
  }*/
  $form = $this->createForm(OrganismoType::class, $organismo)
  ->add('Guardar', SubmitType::class);
  $form->remove('codigo');$form->remove('nombre');$form->remove('entregoFormulario');
  $form->remove('zona');$form->remove('amparo');$form->remove('departamento');
  $form->remove('habilitado');
  $form->handleRequest($request);
  if ($form->isSubmitted() && $form->isValid()) {
    //$evento->setDescripcion($this->get('eventos.util')->autoLinkText($evento->getDescripcion()));
    $em->persist($organismo);
    $em->flush();
    AbstractBaseController::addWarnMessage('El Usuario "' . $organismo->getNombre()
    . '" se ha modificado correctamente.');
    //  $this->get('eventos.notificacion')->sendToAll('Symfony 2020!', 'Se ha actualizado el evento '.$organismo->getNombre().'.');
    return $this->redirect($this->generateUrl('principal_logueado'));
  }
  return $this->render('@JubilacionesDeclaraciones/OrganismoOrganismo/editar.html.twig'
  , array('form' => $form->createView(), 'organismo' => $organismo
));
}

public function borrarAction($id) {

}

}
