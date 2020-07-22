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

class RepresentanteController extends Controller {

  public function listarContralorAction() {
      $em = $this->getDoctrine()->getManager();
      $representantes = $em->getRepository('JubilacionesDeclaracionesBundle:Representante')->findAllRepresentantesAlfabeticamente();
      return $this->render('@JubilacionesDeclaraciones/Representante/listarContralor.html.twig', array(
                  'representantes' => $representantes
      ));
  }


  public function nuevoAction(Request $request) {
      $user = $this->getUser();
      $codigo_organismo = $user->getUsername();
      $em = $this->getDoctrine()->getManager();
      $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => $codigo_organismo));
      $representante = new Representante();
      $form = $this->createForm(RepresentanteType::class, $representante);
      $form->remove('organismo');
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
          $representante->addOrganismo($organismo);
          $representante->setConfirmoDatos('No');
          $representante->setFechaActualizacion(new \DateTime('now'));

          try {
              $em->persist($representante);
              $em->flush();
          } catch (\Doctrine\DBAL\DBALException $e) {
              AbstractBaseController::addWarnMessage("Atencion!!!: El Representante ya estaba creado con anterioridad.");
              //return $this->redirect($this->generateUrl('organismo_declaracion_error', array('error' => 'Clave Duplicada')));
              return $this->redirect($this->generateUrl('organismo_representante_existente', array('cuil' => $representante->getCuil())));
          }


          AbstractBaseController::addWarnMessage("El Representante '" . $representante . "' se ha creado correctamente.");
          if ( !$organismo->getRepresentante() ) {
              $organismo->setRepresentante($representante);
              $organismo->setEntregoFormulario('No');
              $em->persist($organismo);
              $em->flush();
              AbstractBaseController::addWarnMessage("El Representante fue vinculado al Organismo '.$organismo.' correctamente.");
          } else {
              AbstractBaseController::addWarnMessage("El Representante NO se ha vinculado al organismo. El Organismo podria ya tener un Representante");
          }
          return $this->redirect($this->generateUrl('organismo_organismo_listar'));
      }
      return $this->render('@JubilacionesDeclaraciones/Representante/nuevoRepresentanteConOrganismo.html.twig', array('form' => $form->createView(),
      ));
  }

  public function verAction($id) {
    $em = $this->getDoctrine()->getManager();
    $representante = $em->getRepository('JubilacionesDeclaracionesBundle:Representante')->find($id);
    return $this->render('@JubilacionesDeclaraciones/Representante/ver.html.twig', array(
      'representante' => $representante
    ));
  }

  public function editarAction(Request $request, UserInterface $user) {
    $user = $this->getUser();
    $organismo_codigo = $user->getUsername();
    $em = $this->getDoctrine()->getManager();
    $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array("codigo"=>$organismo_codigo));
    $representante=$organismo->getRepresentante();
    $form = $this->createForm(RepresentanteType::class, $representante);
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
      return $this->redirect($this->generateUrl('organismo_organismo_listar'));
    }
    return $this->render('@JubilacionesDeclaraciones/Representante/editar.html.twig'
    , array('form' => $form->createView(), 'representante' => $representante
  ));
}

public function desvincularAction() {
    $user = $this->getUser();
    $organismo_codigo = $user->getUsername();
    $em = $this->getDoctrine()->getManager();
    $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array("codigo"=>$organismo_codigo));
    $organismo->setRepresentante(Null);
    $organismo->setEntregoFormulario('');
    $em->persist($organismo);
    $em->flush();
    AbstractBaseController::addWarnMessage("El Representante se ha Desvinculado correctamente.");
    return $this->redirect($this->generateUrl('organismo_organismo_listar'));
}

public function vincularAction($cuil) {
    $user = $this->getUser();
    $organismo_codigo = $user->getUsername();
    $em = $this->getDoctrine()->getManager();
    $representante = $em->getRepository('JubilacionesDeclaracionesBundle:Representante')->findOneBy(array("cuil"=>$cuil));
    $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array("codigo"=>$organismo_codigo));
    $organismo->setRepresentante($representante);
    $organismo->setEntregoFormulario('No');
    $em->persist($organismo);
    $em->flush();
    AbstractBaseController::addWarnMessage("El Representante se ha Vinculado correctamente.");
    return $this->redirect($this->generateUrl('organismo_organismo_listar'));
}


public function mostrarRepresentanteExistenteAction($cuil) {
    $user = $this->getUser();
    $organismo_codigo = $user->getUsername();
    $em = $this->getDoctrine()->getManager();
    $representante = $em->getRepository('JubilacionesDeclaracionesBundle:Representante')->findOneBy(array("cuil"=>$cuil));
    return $this->render('@JubilacionesDeclaraciones/Representante/RepresentanteExistente.html.twig'
      , array('representante' => $representante
  ));
    //AbstractBaseController::addWarnMessage("El Representante se ha Desvinculado correctamente.");
    //return $this->redirect($this->generateUrl('vincularRepresentanteConOrganismo'));
}




}
