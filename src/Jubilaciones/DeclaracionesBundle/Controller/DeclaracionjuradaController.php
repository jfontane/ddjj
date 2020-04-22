<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Jubilaciones\DeclaracionesBundle\Form\DeclaracionjuradaType;
use Jubilaciones\DeclaracionesBundle\Entity\Organismo;
use Jubilaciones\DeclaracionesBundle\Classes\Util;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

//use Symfony\Component\Validator\Constraints\Length; 

class DeclaracionjuradaController extends Controller {

    public function indexAction() {
        return $this->render('JubilacionesDeclaracionesBundle:Default:index.html.twig');
    }

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/listar.html.twig', array(
                    'declaraciones' => $declaraciones
        ));
    }

    public function verAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        $fileName = $declaracion->getJubidat();
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $valores = Util::totaliza($archivo);
        return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/ver.html.twig', array(
                    'valores' => $valores, 'declaracion' => $declaracion
        ));
    }

    public function nuevoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => '4080010000'));
        //dump($organismo->getNombre());die;
        $declaracionjurada = new Declaracionjurada();
        $form = $this->createForm(DeclaracionjuradaType::class, $declaracionjurada)
                ->add('Guardar', SubmitType::class);
// AGREGAR AL FORM BOTÓN DE SUBMIT CON ETIQUETA “Guardar”
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Recogemos el fichero jubidat
            //$fileJubidat = $form['jubidat']->getData();
            $tipoLiq = Util::getTipoLiquidacion($declaracionjurada->getPeriodoMes(), $declaracionjurada->getTipoLiquidacion());
            $fileJubidat = $form->get('jubidat')->getData();
            $contenidoJubidat = file_get_contents($fileJubidat);
            // Sacamos la extensión del fichero
            $ext = $fileJubidat->guessExtension();
            // Le ponemos un nombre al fichero
            $file_name_jubidat = $organismo->getCodigo() . $declaracionjurada->getPeriodoAnio() . $declaracionjurada->getPeriodoMes() . $tipoLiq . ".dat";
            // Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
            $fileJubidat->move("uploads", $file_name_jubidat);


            $fileJubi1ind = $form->get('jubi1ind')->getData();
            // Sacamos la extensión del fichero
            $ext = $fileJubi1ind->guessExtension();
            // Le ponemos un nombre al fichero
            $file_name_jubi1ind = $organismo->getCodigo() . $declaracionjurada->getPeriodoAnio() . $declaracionjurada->getPeriodoMes() . $tipoLiq . ".ind";
            // Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
            $fileJubi1ind->move("uploads", $file_name_jubi1ind);


            //$this->sacarTotalesJubidat($file_name);
            //$declaracionjurada->setJubidat($file_name);


            /* $fechaEntrega = date('Y-m-d'); */
            //$declaracionjurada->setFechaEntrega($fechaEntrega);
            $declaracionjurada->setJubidat($file_name_jubidat);
            $declaracionjurada->setJubi1ind($file_name_jubi1ind);
            $declaracionjurada->setFechaEntrega(new \DateTime('now'));
            $declaracionjurada->setEstado('Pendiente');
            $declaracionjurada->setOrganismo($organismo);
            $declaracionjurada->setTipoLiquidacion($tipoLiq);
            $em = $this->getDoctrine()->getManager();
            $em->persist($declaracionjurada);
            $em->flush();

            AbstractBaseController::addWarnMessage("La Declaracion Jurada  '" . $declaracionjurada->getPeriodoAnio()
                    . '/' . $declaracionjurada->getPeriodoMes() . "' se ha creado correctamente.");
            return $this->redirect($this->generateUrl('organismo_declaracion_listar'));
        }
        return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/nuevo.html.twig', array('form' => $form->createView(),
        ));
    }

    public function editarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        // El codigo de organismo es para harcodear
        $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => '4080010000'));
        
        if (null == $declaracionjurada = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id)) {
            throw $this->createNotFoundException('No existe la Declaracion solicitada.');
        }
        $form = $this->createForm(DeclaracionjuradaType::class, $declaracionjurada)
                ->add('Guardar', SubmitType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $tipoLiq = Util::getTipoLiquidacion($declaracionjurada->getPeriodoMes(), $declaracionjurada->getTipoLiquidacion());
            $fileJubidat = $form->get('jubidat')->getData();
            $contenidoJubidat = file_get_contents($fileJubidat);
            // Sacamos la extensión del fichero
            $ext = $fileJubidat->guessExtension();
            // Le ponemos un nombre al fichero
            $file_name_jubidat = $organismo->getCodigo() . $declaracionjurada->getPeriodoAnio() . $declaracionjurada->getPeriodoMes() . $tipoLiq . ".dat";
            // Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
            $fileJubidat->move("uploads", $file_name_jubidat);


            $fileJubi1ind = $form->get('jubi1ind')->getData();
            // Sacamos la extensión del fichero
            $ext = $fileJubi1ind->guessExtension();
            // Le ponemos un nombre al fichero
            $file_name_jubi1ind = $organismo->getCodigo() . $declaracionjurada->getPeriodoAnio() . $declaracionjurada->getPeriodoMes() . $tipoLiq . ".ind";
            // Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
            $fileJubi1ind->move("uploads", $file_name_jubi1ind);
            $declaracionjurada->setJubidat($file_name_jubidat);
            $declaracionjurada->setJubi1ind($file_name_jubi1ind);
            $declaracionjurada->setFechaEntrega(new \DateTime('now'));
            $declaracionjurada->setEstado('Pendiente');
            $declaracionjurada->setOrganismo($organismo);
            $declaracionjurada->setTipoLiquidacion($tipoLiq);
            $em = $this->getDoctrine()->getManager();
            $em->persist($declaracionjurada);
            $em->flush();

            AbstractBaseController::addWarnMessage("La Declaracion Jurada  '" . $declaracionjurada->getPeriodoAnio()
                    . '/' . $declaracionjurada->getPeriodoMes() . "' se ha creado correctamente.");
            return $this->redirect($this->generateUrl('organismo_declaracion_listar'));
        }
        return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/editar.html.twig',
                   array('form' => $form->createView(), 'declaracionjurada' => $declaracionjurada
        ));
    }

    public function borrarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        $fileNamejubidat = $declaracion->getJubidat();
        $fileNamejubi1ind = $declaracion->getJubi1ind();
        // Para borrar el archivo
        if ($declaracion->getEstado() == "Pendiente" || $declaracion->getEstado() == "Incorrecto") {
            $fs = new Filesystem();
            $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileNamejubidat);
            $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileNamejubi1ind);

            if (null == $declaracion) {
                throw $this->createNotFoundException('No existe la Declaracion solicitada.');
            }
            $em->remove($declaracion);
            $em->flush();
            AbstractBaseController::addWarnMessage('La Declaracion del Periodo ' .
                    $declaracion->getPeriodoAnio() . '/' .
                    $declaracion->getPeriodoMes() .
                    ' se ha borrado correctamente.');
        } else {
            AbstractBaseController::addWarnMessage("La Declaracion se encuentra en estado 'Procesando' " .
                    "y NO se ha podido Eliminar!!!.");
        }
        return $this->redirect($this->generateUrl('organismo_declaracion_listar'));
    }

}
