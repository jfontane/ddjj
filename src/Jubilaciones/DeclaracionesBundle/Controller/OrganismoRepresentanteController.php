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
use Symfony\Component\Security\Core\User\UserInterface;

//use Symfony\Component\Validator\Constraints\Length; 

class OrganismoRepresentanteController extends Controller {

    public function listarAction(UserInterface $user) {
        $user = $this->getUser();
        $organismo_codigo = $user->getUsername();
        $em = $this->getDoctrine()->getManager();
        $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array("codigo"=>$organismo_codigo));
        $representante=$organismo->getRepresentante();
       
        return $this->render('@JubilacionesDeclaraciones/OrganismoRepresentante/listar.html.twig', array(
                    'representante' => $representante
        ));
    }

    public function verAction($id) {
        $em = $this->getDoctrine()->getManager();
        $representante = $em->getRepository('JubilacionesDeclaracionesBundle:Representante')->find($id);
        return $this->render('@JubilacionesDeclaraciones/OrganismoRepresentante/ver.html.twig', array(
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
