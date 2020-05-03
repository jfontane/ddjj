<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Jubilaciones\DeclaracionesBundle\Entity\Organismo;
use Jubilaciones\DeclaracionesBundle\Form\DeclaracionjuradaType;
use Jubilaciones\DeclaracionesBundle\Classes\Util;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;


//use Symfony\Component\Validator\Constraints\Length; 

class ContralorDeclaracionjuradaController extends Controller {

    public function indexAction() {
        return $this->render('JubilacionesDeclaracionesBundle:Default:index.html.twig');
    }

    public function listarAction() {
        try {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', User, 'Unable to access this page - Javier!');
        } catch (\Exception $e)
        {
          die('noooooooooooooooo');  
        }
        $em = $this->getDoctrine()->getManager();
        $declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/ContralorDeclaracionjurada/declaracionesjuradas.html.twig', array(
                    'declaraciones' => $declaraciones
        ));
    }

    public function declaracionAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findOneBy(array('id' => $id));
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/ContralorDeclaracionjurada/declaracionjurada.html.twig', array(
                    'declaracion' => $declaracion
        ));
    }

    public function aprobarDeclaracionAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findOneBy(array('id' => $id));
        //dump($declaraciones);die;
        $declaracion->setEstado('Correcto');
        $declaracion->setFechaIngreso(new \DateTime('now'));
        // aca borrar los archivos
        $fileNamejubidat = $declaracion->getJubidat();
        $fileNamejubi1ind = $declaracion->getJubi1ind();
        // Para borrar el archivo
        $fs = new Filesystem();
        $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileNamejubidat);
        $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileNamejubi1ind);
        //Por ultimo hacer el redirect a Listar
        $em->persist($declaracion);
        $em->flush();

        AbstractBaseController::addWarnMessage("La Declaracion Jurada de: '"
                . $declaracion->getOrganismo()->getNombre()
                . "' en el periodo: " . $declaracion->getPeriodoAnio()
                . '/' . $declaracion->getPeriodoMes() . "' se ha APROBADO correctamente.");
        return $this->redirect($this->generateUrl('contralor_declaraciones_listar'));
    }

    public function rechazarDeclaracionAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findOneBy(array('id' => $id));
        $declaracion->setEstado('Rechazada');
        // aca borrar los archivos
        $fileNamejubidat = $declaracion->getJubidat();
        $fileNamejubi1ind = $declaracion->getJubi1ind();
        $declaracion->setJubidat(NULL);
        $declaracion->setJubi1ind(NULL);
        // Para borrar el archivo
        $fs = new Filesystem();
        $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileNamejubidat);
        $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileNamejubi1ind);
        //Por ultimo hacer el redirect a Listar
        $em->persist($declaracion);
        $em->flush();

        AbstractBaseController::addWarnMessage("La Declaracion Jurada de: '"
                . $declaracion->getOrganismo()->getNombre()
                . "' en el periodo: " . $declaracion->getPeriodoAnio()
                . '/' . $declaracion->getPeriodoMes() . "' se ha RECHAZADO !!!.");
        return $this->redirect($this->generateUrl('contralor_declaraciones_listar'));
    }

    public function getJubidatAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        $fileName = $declaracion->getJubidat();
        // Para borrar el archivo
        //    $fs = new Filesystem(); 
        //    $fs->remove($this->get('kernel')->getRootDir().'/../web/uploads/'.$file_name);
        $arch = new File($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);

        return $this->file($arch, 'jubi.dat');

        /* $file = stream_get_contents($declaracion->getJubidat(), -1, 0);
          //dump(strlen($file));die;
          $size = strlen($file);

          $response = new Response($file, 200, array(
          'Content-Type' => 'application/octet-stream',
          'Content-Length' => $size,
          'Content-Disposition' => 'attachment; filename="jubi.dat"',
          ));
          return $response; */
    }

    public function getJubi1indAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        $fileName = $declaracion->getJubi1ind();
        $arch = new File($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        return $this->file($arch, 'jubi1.ind');
    }

    public function mostrarTotalesAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        $declaracion->setEstado('Procesando');
        $em->persist($declaracion);
        $em->flush();
        $fileName = $declaracion->getJubidat();

        //$file = fopen($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName, 'r');
        //Output lines until EOF is reached
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);


        //dump(Util::totaliza($archivo));
        //die;
        $valores = Util::totaliza($archivo);
        return $this->render('@JubilacionesDeclaraciones/ContralorDeclaracionjurada/valoresTotales.html.twig', array(
                    'valores' => $valores, 'declaracion' => $declaracion
        ));
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
