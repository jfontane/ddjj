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

  public function listarAction() {
      $em = $this->getDoctrine()->getManager();
      $representantes = $em->getRepository('JubilacionesDeclaracionesBundle:Representante')->findAllRepresentantesAlfabeticamente();
      //dump($declaraciones);die;
      return $this->render('@JubilacionesDeclaraciones/ContralorRepresentante/listar.html.twig', array(
                  'representantes' => $representantes
      ));
  }


  public function nuevoAction(Request $request) {
      $representante = new Representante();
      $form = $this->createForm(RepresentanteType::class, $representante);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
          //Saco el organismo del Formulario de Alta de Representante
          $representanteOrganismo = $form->get('organismo')->getData();
          //Saco el ID del Organismo del Formulario de Alta de Representante
          $idRepresentanteOrganismo = $representanteOrganismo->getId();

          //Localizo el Organisno por ID para ver si tiene o no Representante Vinculado
          $em = $this->getDoctrine()->getManager();
          $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('id' => $idRepresentanteOrganismo));
  //            dump($organismo);
  //            die;
          //$nroDocumento = $representante->getDocumentoNumero();
          //$representante->setDocumentoTipo('Dni');
          $representante->setConfirmoDatos('No');
          //$representante->setDocumentoNumero(substr($nroDocumento, 2, 8));
          $representante->setFechaSolicitud(new \DateTime('now'));

          $em->persist($representante);
          $em->flush();
          AbstractBaseController::addWarnMessage("El Representante '" . $representante . "' se ha creado correctamente.");

          if (!$organismo->getRepresentante()) {
              $organismo->setRepresentante($representante);
              $em->persist($organismo);
              $em->flush();
              AbstractBaseController::addWarnMessage("El Representante fue vinculado al Organismo '.$organismo.' correctamente.");
          } else {
              AbstractBaseController::addWarnMessage("El Representante NO se ha vinculado al organismo. El Organismo podria ya tener un Representante");
          }
          //return $this->redirect($this->generateUrl('representante_ver'));
         return  $this->get('router')->generate('representante_ver', array('id' => $organismo->getId()));

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

    //dump($representante);die;
    /*if (null == $organismo = $em->find('JubilacionesDeclaracionesBundle:User', $id)) {
    throw $this->createNotFoundException('No existe el Usuario solicitado.');
  }*/


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
