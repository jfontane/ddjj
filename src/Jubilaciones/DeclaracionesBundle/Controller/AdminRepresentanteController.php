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

//use Symfony\Component\Validator\Constraints\Length; 

class AdminRepresentanteController extends Controller {

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $representantes = $em->getRepository('JubilacionesDeclaracionesBundle:Representante')->findAllRepresentantesAlfabeticamente();
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/AdminRepresentante/listar.html.twig', array(
                    'representantes' => $representantes
        ));
    }

    public function verAction($id) {
        $em = $this->getDoctrine()->getManager();
        $representante = $em->getRepository('JubilacionesDeclaracionesBundle:Representante')->find($id);
        return $this->render('@JubilacionesDeclaraciones/AdminRepresentante/ver.html.twig', array(
                    'representante' => $representante
        ));
    }

    public function nuevoAction(Request $request) {
        
    }

    public function editarAction(Request $request, $id) {
        
    }

    public function borrarAction($id) {
        
    }

}
