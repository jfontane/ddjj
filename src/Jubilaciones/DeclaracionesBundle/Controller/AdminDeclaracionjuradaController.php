<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Jubilaciones\DeclaracionesBundle\Form\DeclaracionjuradaType;
use Jubilaciones\DeclaracionesBundle\Entity\Organismo;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;



//use Symfony\Component\Validator\Constraints\Length; 

class AdminDeclaracionjuradaController extends Controller {

    public function indexAction() {
        return $this->render('JubilacionesDeclaracionesBundle:Default:index.html.twig');
    }

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/AdminDeclaracionjurada/declaracionesjuradas.html.twig', array(
                    'declaraciones' => $declaraciones
        ));
    }

    public function declaracionAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findOneBy(array('id' => $id));
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/AdminDeclaracionjurada/declaracionjurada.html.twig', array(
                    'declaracion' => $declaracion
        ));
    }

    public function getJubidatAction($id) {
        //$em = $this->getDoctrine()->getManager();
        //$declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        
        // Para borrar el archivo
        //    $fs = new Filesystem(); 
        //    $fs->remove($this->get('kernel')->getRootDir().'/../web/uploads/'.$file_name);
        $file = $this->get('kernel')->getRootDir() . '/../web/uploads/4080010000111.txt';
        $arch = new File($file);
        
        return $this->file($arch, 'jubi.dat');
        
        /*$file = stream_get_contents($declaracion->getJubidat(), -1, 0);
        //dump(strlen($file));die;
        $size = strlen($file);

        $response = new Response($file, 200, array(
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => $size,
            'Content-Disposition' => 'attachment; filename="jubi.dat"',
        ));
        return $response;*/
    }

    public function borraJubidatAction($id) {
        //$em = $this->getDoctrine()->getManager();
        //$declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        
        // Para borrar el archivo
        //    $fs = new Filesystem(); 
        //    $fs->remove($this->get('kernel')->getRootDir().'/../web/uploads/'.$file_name);
//Hacer redirect        
        
}

}
