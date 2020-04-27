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

class ContralorOrganismoController extends Controller {

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $organismos = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findAllOrganismosAlfabeticamente();
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/ContralorOrganismo/listar.html.twig', array(
                    'organismos' => $organismos
        ));
    }

    public function verAction($id) {
        $em = $this->getDoctrine()->getManager();
        $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->find($id);
        return $this->render('@JubilacionesDeclaraciones/ContralorOrganismo/ver.html.twig', array(
                    'organismo' => $organismo
        ));
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
        
    }

    public function borrarAction($id) {
        
    }

}
