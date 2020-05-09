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
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\User\UserInterface;
use Jubilaciones\DeclaracionesBundle\Classes\OrganismoDeclaracionListarPdf;

//use Symfony\Component\Validator\Constraints\Length;

class OrganismoDeclaracionjuradaController extends Controller {

    public function indexAction() {
        return $this->render('JubilacionesDeclaracionesBundle:Default:index.html.twig');
    }

    public function listarAction(Request $request, UserInterface $user) {
        $user = $this->getUser();
        $organismo_codigo = $user->getUsername();
        $em = $this->getDoctrine()->getManager();
        //$declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();
        //$organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => $organismo_codigo));
        $dql = "SELECT o, d
                FROM JubilacionesDeclaracionesBundle:Organismo o
                JOIN o.declaracionesjuradas d
                WHERE o.codigo = :codigo
                ORDER BY d.periodoAnio Desc,d.periodoMes Desc";
        $organismo = $em->createQuery($dql)->setParameter('codigo', $organismo_codigo)->getOneOrNullResult();

        //dump($organismo);die;
        if (null != $organismo) {
            $declaraciones = $organismo->getDeclaracionesjuradas();
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $declaraciones, $request->query->getInt('page', 1), 12
            );
        } else {
        
            $pagination = null;
        
        }

        return $this->render('@JubilacionesDeclaraciones/OrganismoDeclaracionjurada/listar.html.twig', array(
                    'pagination' => $pagination
        ));
    }
    
    
    public function listarPdfAction(UserInterface $user) {
        $user = $this->getUser();
        $organismo_codigo = $user->getUsername();
        
        $user = $this->getUser();
        $organismo_codigo = $user->getUsername();
        $em = $this->getDoctrine()->getManager();
        $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array( 'codigo' => $organismo_codigo ));
        
        $declaraciones = $organismo->getDeclaracionesjuradas();
        //dump(count($declaraciones));die;
        
        $path = $this->get('kernel')->getRootDir() . '/../web/bundles/jubilacionesdeclaraciones/img';
        
        $pdf = new OrganismoDeclaracionListarPdf($path);
        $pdf->setTitle( '$title' );
        
        $pdf->render($declaraciones);
        $pdf->Output( 'DDJJ.pdf', 'I');
        
        
        
    }

    public function verAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        $fileName = $declaracion->getJubidat();
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $valores = Util::totaliza($archivo);
        return $this->render('@JubilacionesDeclaraciones/OrganismoDeclaracionjurada/ver.html.twig', array(
                    'valores' => $valores, 'declaracion' => $declaracion
        ));
    }

    public function nuevoAction(Request $request, UserInterface $user) {
        $organismo_codigo = $user->getUsername();
        $em = $this->getDoctrine()->getManager();
        $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => $organismo_codigo));
        //dump($organismo->getNombre());die;
        $declaracionjurada = new Declaracionjurada();
        $form = $this->createForm(DeclaracionjuradaType::class, $declaracionjurada)
                ->add('Guardar', SubmitType::class);
// AGREGAR AL FORM BOTÓN DE SUBMIT CON ETIQUETA “Guardar”
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Recogemos el fichero jubidat
            $tipoLiq = Util::getTipoLiquidacion($declaracionjurada->getPeriodoMes(), $declaracionjurada->getTipoLiquidacion());
            $fileJubidat = $form->get('jubidat')->getData();
            $contenidoJubidat = file_get_contents($fileJubidat);
            $encabezado = (substr($contenidoJubidat, 0, 85));

            // Sacamos la extensión del fichero
            $ext = $fileJubidat->guessExtension();
            // Le ponemos un nombre al fichero
            $file_name_jubidat = $organismo->getCodigo() . $declaracionjurada->getPeriodoAnio() . $declaracionjurada->getPeriodoMes() . $tipoLiq . ".dat";
            // Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
            //$fileJubidat->move("uploads", $file_name_jubidat);


            $fileJubi1ind = $form->get('jubi1ind')->getData();
            // Sacamos la extensión del fichero
            $ext = $fileJubi1ind->guessExtension();
            // Le ponemos un nombre al fichero
            $file_name_jubi1ind = $organismo->getCodigo() . $declaracionjurada->getPeriodoAnio() . $declaracionjurada->getPeriodoMes() . $tipoLiq . ".ind";
            // Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
            //verificar el encabezado del archivo jubi.dat
            $verificado = Util::verificarEncabezadoJubiDat($organismo_codigo, $declaracionjurada->getPeriodoAnio(), $declaracionjurada->getPeriodoMes(), $tipoLiq, $encabezado);


            if (!$verificado) {
                AbstractBaseController::addWarnMessage("No se puede ingresar. Archivo Jubi.dat No coincide con lo que declara. Revisar Periodo/Tipo de Liquidacion.");
                return $this->redirect($this->generateUrl('organismo_declaracion_listar'));
            };
            //$declaracionjurada->setFechaEntrega($fechaEntrega);
            $declaracionjurada->setJubidat($file_name_jubidat);
            $declaracionjurada->setJubi1ind($file_name_jubi1ind);
            $declaracionjurada->setFechaEntrega(new \DateTime('now'));
            $declaracionjurada->setEstado('Pendiente');
            $declaracionjurada->setOrganismo($organismo);
            $declaracionjurada->setTipoLiquidacion($tipoLiq);
            $em = $this->getDoctrine()->getManager();

            try {
                $em->persist($declaracionjurada);
                $em->flush();
            } catch (\Doctrine\DBAL\DBALException $e) {
                AbstractBaseController::addErrorMessage("No se puede ingresar, clave compuesta Duplicada.");
                return $this->redirect($this->generateUrl('organismo_declaracion_error', array('error' => 'Clave Duplicada')));
            }
            $fileJubidat->move("uploads", $file_name_jubidat);
            $fileJubi1ind->move("uploads", $file_name_jubi1ind);
            AbstractBaseController::addWarnMessage("La Declaracion Jurada  '" . $declaracionjurada->getPeriodoAnio()
                    . '/' . $declaracionjurada->getPeriodoMes() . "' se ha creado correctamente.");
            return $this->redirect($this->generateUrl('organismo_declaracion_listar'));
        }
        return $this->render('@JubilacionesDeclaraciones/OrganismoDeclaracionjurada/nuevo.html.twig', array('form' => $form->createView(),
        ));
    }

    public function editarAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        // El codigo de organismo es para harcodear
        $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => '4080010000'));

        if (null == $declaracionjurada = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id)) {
            throw $this->createNotFoundException('No existe la Declaracion solicitada.');
        }

        $fileNameJubidatOld = $declaracionjurada->getJubidat();
        $fileNameJubi1indOld = $declaracionjurada->getJubi1ind();
        //dump($fileNameJubidatOld.','.$fileNameJubi1indOld);die;


        $form = $this->createForm(DeclaracionjuradaType::class, $declaracionjurada)
                ->add('Guardar', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (($declaracionjurada->getEstado() == 'Pendiente') ||
                    ($declaracionjurada->getEstado() == 'Rechazada')) {
                //Formatemos el Tipo de liquidacion
                $tipoLiq = Util::getTipoLiquidacion($declaracionjurada->getPeriodoMes(), $declaracionjurada->getTipoLiquidacion());
                //Cargamos el nuevo jubi.dat que vienen del Form
                $fileJubidat = $form->get('jubidat')->getData();
                $contenidoJubidat = file_get_contents($fileJubidat);
                $encabezado = (substr($contenidoJubidat, 0, 85));
                // Le ponemos un nombre al fichero
                $file_name_jubidat = $organismo->getCodigo() . $declaracionjurada->getPeriodoAnio() . $declaracionjurada->getPeriodoMes() . $tipoLiq . ".dat";
                // Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
                //Cargamos el nuevo jubi1.ind que vienen del Form
                $fileJubi1ind = $form->get('jubi1ind')->getData();
                // Sacamos la extensión del fichero
                $ext = $fileJubi1ind->guessExtension();
                // Le ponemos un nombre al fichero
                $file_name_jubi1ind = $organismo->getCodigo() . $declaracionjurada->getPeriodoAnio() . $declaracionjurada->getPeriodoMes() . $tipoLiq . ".ind";
                
               // Verificar que coincida el encabezado del Jubi.dat con el formulario
                $verificado = Util::verificarEncabezadoJubiDat($organismo_codigo, $declaracionjurada->getPeriodoAnio(), $declaracionjurada->getPeriodoMes(), $tipoLiq, $encabezado);
                if (!$verificado) {
                    AbstractBaseController::addWarnMessage("No se puede Editar. Archivo Jubi.dat No coincide con lo que declara. Revisar Periodo/Tipo de Liquidacion.");
                    return $this->redirect($this->generateUrl('organismo_declaracion_listar'));
                };
                

                $declaracionjurada->setJubidat($file_name_jubidat);
                $declaracionjurada->setJubi1ind($file_name_jubi1ind);
                $declaracionjurada->setFechaEntrega(new \DateTime('now'));
                $declaracionjurada->setEstado('Pendiente');
                $declaracionjurada->setOrganismo($organismo);
                $declaracionjurada->setTipoLiquidacion($tipoLiq);
                $em = $this->getDoctrine()->getManager();
                try {
                    $em->persist($declaracionjurada);
                    $em->flush();
                } catch (\Doctrine\DBAL\DBALException $e) {
                    AbstractBaseController::addErrorMessage("No se puede Editar Datos de Periodo, clave compuesta Duplicada.");
                    return $this->redirect($this->generateUrl('organismo_declaracion_error', array('error' => 'Clave Duplicada')));
                }
                //Borramos de la carpeta uploads los antiguos jubi.dat y jubi1.ind
                $fs = new Filesystem();
                $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileNameJubidatOld);
                $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileNameJubi1indOld);
                //Cargamos los Nuevos Archivos Jubi.dat y Jubi1.ind
                $fileJubidat->move("uploads", $file_name_jubidat);
                $fileJubi1ind->move("uploads", $file_name_jubi1ind);


                AbstractBaseController::addWarnMessage("La Declaracion Jurada  '" . $declaracionjurada->getPeriodoAnio()
                        . '/' . $declaracionjurada->getPeriodoMes() . "' se ha Actualizado correctamente.");
            } else {
                AbstractBaseController::addWarnMessage("La Declaracion Jurada  No se ha podido Modificar porque su estado es: '" . $declaracionjurada->getEstado() . "'.");
            }
            return $this->redirect($this->generateUrl('organismo_declaracion_listar'));
        }
        return $this->render('@JubilacionesDeclaraciones/OrganismoDeclaracionjurada/editar.html.twig', array('form' => $form->createView(), 'declaracionjurada' => $declaracionjurada
        ));
    }

    public function borrarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        $fileNamejubidat = $declaracion->getJubidat();
        $fileNamejubi1ind = $declaracion->getJubi1ind();
        // Para borrar el archivo
        if ($declaracion->getEstado() == "Pendiente" || $declaracion->getEstado() == "Rechazada") {
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
            AbstractBaseController::addWarnMessage("La Declaracion se encuentra en estado '".$declaracion->getEstado()."' " .
                    "y NO se ha podido Eliminar!!!.");
        }
        return $this->redirect($this->generateUrl('organismo_declaracion_listar'));
    }

    public function errorAction($error) {
        return $this->render('@JubilacionesDeclaraciones/ErrorOrganismo/claveDuplicada.html.twig', array('Error' => $error));
    }

}
