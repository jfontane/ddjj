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

class OrganismoOrganismoController extends Controller {

    public function listarAction(UserInterface $user) {
        $user = $this->getUser();
        $organismo_codigo = $user->getUsername();
        $em = $this->getDoctrine()->getManager();
        $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array("codigo"=>$organismo_codigo));
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/OrganismoOrganismo/listar.html.twig', array(
                    'organismo' => $organismo
        ));
    }

    public function verAction($id) {
        $em = $this->getDoctrine()->getManager();
        $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->find($id);
        return $this->render('@JubilacionesDeclaraciones/OrganismoOrganismo/ver.html.twig', array(
                    'organismo' => $organismo
        ));
    }
    
    public function editarAction(Request $request, $id) {
        
    }

}
