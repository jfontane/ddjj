<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Jubilaciones\DeclaracionesBundle\Form\DeclaracionjuradaType;
use Jubilaciones\DeclaracionesBundle\Form\RepresentanteType;

use Jubilaciones\DeclaracionesBundle\Entity\Representante;
use Jubilaciones\DeclaracionesBundle\Classes\Util;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\User\UserInterface;

//use Symfony\Component\Validator\Constraints\Length;

class OrganismoRepresentanteController extends Controller {

  public function verAction($id) {
    $em = $this->getDoctrine()->getManager();
    $representante = $em->getRepository('JubilacionesDeclaracionesBundle:Representante')->find($id);
    return $this->render('@JubilacionesDeclaraciones/OrganismoRepresentante/ver.html.twig', array(
      'representante' => $representante
    ));
  }



  public function editarAction(Request $request, UserInterface $user) {
    $user = $this->getUser();
    $organismo_codigo = $user->getUsername();
    $em = $this->getDoctrine()->getManager();
    $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array("codigo"=>$organismo_codigo));

    $representante=$organismo->getRepresentante();

    //dump($representante);die;
    /*if (null == $organismo = $em->find('JubilacionesDeclaracionesBundle:User', $id)) {
    throw $this->createNotFoundException('No existe el Usuario solicitado.');
  }*/


    $form = $this->createForm(RepresentanteType::class, $representante)
    ->add('Guardar', SubmitType::class);

    $form->remove('cuil');$form->remove('apellido');$form->remove('nombres');
    $form->remove('sexo');$form->remove('fechaActualizacion');$form->remove('confirmoDatos');
    $form->remove('organismo');

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      //$evento->setDescripcion($this->get('eventos.util')->autoLinkText($evento->getDescripcion()));
      $em->persist($representante);
      $em->flush();
      AbstractBaseController::addWarnMessage('El Email del Representante "' . $representante
      . '" se ha modificado correctamente.');
      //  $this->get('eventos.notificacion')->sendToAll('Symfony 2020!', 'Se ha actualizado el evento '.$organismo->getNombre().'.');
      return $this->redirect($this->generateUrl('principal_logueado'));
    }
    return $this->render('@JubilacionesDeclaraciones/OrganismoRepresentante/editar.html.twig'
    , array('form' => $form->createView(), 'representante' => $representante
  ));
}


public function borrarAction($id) {

}

}
