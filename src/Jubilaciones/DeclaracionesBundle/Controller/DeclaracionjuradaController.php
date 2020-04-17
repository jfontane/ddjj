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

class DeclaracionjuradaController extends Controller {

    public function indexAction() {
        return $this->render('JubilacionesDeclaracionesBundle:Default:index.html.twig');
    }

    public function declaracionesAction() {
        $em = $this->getDoctrine()->getManager();
        $declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/declaracionesjuradas.html.twig', array(
                    'declaraciones' => $declaraciones
        ));
    }

    public function declaracionAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findOneBy(array('id' => $id));
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/declaracionjurada.html.twig', array(
                    'declaracion' => $declaracion
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
            //$jubidat = $form->get('jubidat')->getData();

            // Recogemos el fichero
            $file = $form['jubidat']->getData();
            dump($file);die;

// Sacamos la extensión del fichero
            $ext = $file->guessExtension();

// Le ponemos un nombre al fichero
            $file_name = time() . "." . $ext;

// Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
            $file->move("uploads", $file_name);



            //dump($jubidat);
            die;
            //dump($declaracionjurada->getPeriodoAnio().'/'.$declaracionjurada->getPeriodoMes());die;
            $fechaEntrega = date('Y-m-d');
            //$declaracionjurada->setFechaEntrega($fechaEntrega);
            $declaracionjurada->setEstado('Procesando');
            $declaracionjurada->setOrganismo($organismo);

            if (($declaracionjurada->getPeriodoMes() == '13') or ( $declaracionjurada->getPeriodoMes() == '14')) {
                if ($declaracionjurada->getTipoLiquidacion() == '1')
                    $tipoLiq = '211';
                else if ($declaracionjurada->getTipoLiquidacion() == '2')
                    $tipoLiq = '212';
            } else if (($declaracionjurada->getPeriodoMes() == '01') or ( $declaracionjurada->getPeriodoMes() == '02')or ( $declaracionjurada->getPeriodoMes() == '03') or ( $declaracionjurada->getPeriodoMes() == '04')or ( $declaracionjurada->getPeriodoMes() == '05') or ( $declaracionjurada->getPeriodoMes() == '06')or ( $declaracionjurada->getPeriodoMes() == '07') or ( $declaracionjurada->getPeriodoMes() == '08')or ( $declaracionjurada->getPeriodoMes() == '09') or ( $declaracionjurada->getPeriodoMes() == '10')or ( $declaracionjurada->getPeriodoMes() == '11') or ( $declaracionjurada->getPeriodoMes() == '12')) {
                if ($declaracionjurada->getTipoLiquidacion() == '1')
                    $tipoLiq = '111';
                else if ($declaracionjurada->getTipoLiquidacion() == '2')
                    $tipoLiq = '112';
                else if ($declaracionjurada->getTipoLiquidacion() == '3')
                    $tipoLiq = '301';
                else if ($declaracionjurada->getTipoLiquidacion() == '4')
                    $tipoLiq = '302';
                else if ($declaracionjurada->getTipoLiquidacion() == '5')
                    $tipoLiq = '303';
                else if ($declaracionjurada->getTipoLiquidacion() == '6')
                    $tipoLiq = '304';
            }
            $declaracionjurada->setTipoLiquidacion($tipoLiq);
            $em = $this->getDoctrine()->getManager();
            $em->persist($declaracionjurada);
            $em->flush();

            AbstractBaseController::addWarnMessage("La Declaracion Jurada  '" . $declaracionjurada->getPeriodoAnio()
                    . '/' . $declaracionjurada->getPeriodoMes() . "' se ha creado correctamente.");
            //$this->get('eventos.notificacion')->sendToAll('Symfony 2020!', 'Se ha creado el evento '.$evento. '.');
            return $this->redirect($this->generateUrl('organismo_declaraciones_juradas'));
        }
// AGREGAR CÓDIGO FALTANTE
        return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/nuevo.html.twig', array('form' => $form->createView(),
        ));
    }

}
