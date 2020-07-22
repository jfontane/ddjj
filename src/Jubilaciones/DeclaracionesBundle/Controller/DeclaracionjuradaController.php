<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use DateTime;
use Doctrine\DBAL\DBALException;
use Jubilaciones\DeclaracionesBundle\Classes\OrganismoDeclaracionListarPdf;
use Jubilaciones\DeclaracionesBundle\Classes\Util;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Jubilaciones\DeclaracionesBundle\Form\DeclaracionjuradaType;
use Jubilaciones\DeclaracionesBundle\Form\FiltroDeclaracionjuradaType;
use Jubilaciones\DeclaracionesBundle\Services\DeclaracionesJuradasService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

//use Symfony\Component\Validator\Constraints\Length;

class DeclaracionjuradaController extends Controller {

    public function indexAction(Request $request) {
        $user = $this->getUser();
        $zona = $user->getZona();
        $em = $this->getDoctrine()->getManager();
        //$declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();
        $organismo = null;
        if($user->hasRole('ROLE_USER')){
            $organismo = $user->getOrganismo();
            //filtrar declaraciones para este usuario
            //dump($user);exit;
        }
        $formFiltro = $this->createForm(FiltroDeclaracionjuradaType::class, null, array(
            'method' => 'GET'
        ));
        $formFiltro->handleRequest($request);
        $filtros = array();
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            $filtros = $formFiltro->getData();
        }

        $ddjjService = $this->get(DeclaracionesJuradasService::class);
        $query = $ddjjService->filtrar($filtros, $zona, $organismo);
        $items_por_pagina = $this->getParameter('knp_paginator_items_por_pagina');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), $items_por_pagina
        );
//All Files	/home/esangoi/vagrant/www/predeju/src/Jubilaciones/DeclaracionesBundle/Resources/views/DeclaracionJurada/declaraciones-juradas.html.twig

        return $this->render('@JubilacionesDeclaraciones/DeclaracionJurada/declaraciones-juradas.html.twig', array(
                    'pagination' => $pagination,
                    'organismo' => $organismo,
                    'form_filtro' => $formFiltro->createView()
        ));
    }


    public function listarPdfAction(UserInterface $user) {
        $user = $this->getUser();
        $organismo_codigo = $user->getUsername();
        $em = $this->getDoctrine()->getManager();
        $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => $organismo_codigo));
        $declaraciones = $organismo->getDeclaracionesjuradas();
        $path = $this->get('kernel')->getRootDir() . '/../web/bundles/jubilacionesdeclaraciones/img';
        $pdf = new OrganismoDeclaracionListarPdf($path);
        $pdf->setTitle('$title');
        $pdf->render($declaraciones);
        $pdf->Output('DDJJ.pdf', 'I');
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
                $fileName = $declaracionjurada->getJubidat();
                $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
                $valores = Util::totaliza($archivo);
                //Importes:
                $importePersonal=$valores['totalApPersonal'];
                $importePatronal=$valores['totalApPatronal'];
                $importeRemunerativo=$valores['totalRemunerativo'];
                $importeNoRemunerativo=$valores['totalNoRemunerativo'];
                $importeOtros=$valores['totalImportesOtros']; //CAMBIAR ESTE VALOR COMO CORRESPONDA
                $declaracionjurada->setImportePersonal($importePersonal);
                $declaracionjurada->setImportePatronal($importePatronal);
                $declaracionjurada->setImporteRemunerativo($importeRemunerativo);
                $declaracionjurada->setImporteNoRemunerativo($importeNoRemunerativo);
                $declaracionjurada->setImporteOtros($importeOtros);
                //$em->persist($declaracionjurada);
                $em->flush();

                AbstractBaseController::addWarnMessage("La Declaracion Jurada  '" . $declaracionjurada->getPeriodoAnio()
                        . '/' . $declaracionjurada->getPeriodoMes() . "' se ha creado correctamente.");
                return $this->redirect($this->generateUrl('organismo_declaracion_listar'));
            }
            return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/nuevo.html.twig', array('form' => $form->createView(),
            ));
        }


        public function editarAction(Request $request, $id, UserInterface $user) {
            $organismo_codigo = $user->getUsername();
            $em = $this->getDoctrine()->getManager();
            // El codigo de organismo es para harcodear
            $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => $organismo_codigo));

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
                    $fileName=$declaracionjurada->getJubidat();
                    $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
                    $valores = Util::totaliza($archivo);
                    $importeRemunerativo=$valores['totalRemunerativo'];
                    $importeNoRemunerativo=$valores['totalNoRemunerativo'];
                    $importeOtros=$valores['totalImportesOtros']; //CAMBIAR ESTE VALOR COMO CORRESPONDA
                    $declaracionjurada->setImporteRemunerativo($importeRemunerativo);
                    $declaracionjurada->setImporteNoRemunerativo($importeNoRemunerativo);
                    $declaracionjurada->setImporteOtros($importeOtros);
                    $em->persist($declaracionjurada);
                    $em->flush();
                    AbstractBaseController::addWarnMessage("La Declaracion Jurada  '" . $declaracionjurada->getPeriodoAnio()
                            . '/' . $declaracionjurada->getPeriodoMes() . "' se ha Actualizado correctamente.");
                } else {
                    AbstractBaseController::addWarnMessage("La Declaracion Jurada  No se ha podido Modificar porque su estado es: '" . $declaracionjurada->getEstado() . "'.");
                }
                return $this->redirect($this->generateUrl('organismo_declaracion_listar'));
            }
            return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/editar.html.twig', array('form' => $form->createView(), 'declaracionjurada' => $declaracionjurada
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
                AbstractBaseController::addWarnMessage("La Declaracion se encuentra en estado '" . $declaracion->getEstado() . "' " .
                        "y NO se ha podido Eliminar!!!.");
            }
            return $this->redirect($this->generateUrl('organismo_declaracion_listar'));
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


        public function errorAction($error) {
            return $this->render('@JubilacionesDeclaraciones/ErrorOrganismo/claveDuplicada.html.twig', array('Error' => $error));
        }


        public function pruebaAction(Request $request) {
            $em = $this->getDoctrine()->getManager();
            $ddjjs = $em->getRepository(Declaracionjurada::class)->findAll();

            $ddjjService = $this->get(DeclaracionesJuradasService::class);
            $res = $ddjjService->setTotalizadores();
            dump($ddjjService, $res);exit;
        }


        public function aprobarDeclaracionAction($id) {
            $user = $this->getUser();
            $zona = $user->getZona();
            $em = $this->getDoctrine()->getManager();
            //$declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();
            $organismo = null;
            if($user->hasRole('ROLE_ADMIN')){
                $organismo = $user->getOrganismo();
                //filtrar declaraciones para este usuario
                //dump($user);exit;
            }

            $em = $this->getDoctrine()->getManager();
            $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findOneBy(array('id' => $id));
            //dump($declaraciones);die;
            $declaracion->setEstado('Aprobada');
            $declaracion->setFechaIngreso(new DateTime('now'));
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
            return $this->redirect($this->generateUrl('contralor_declaracion_listar_pendientes'));
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
            return $this->redirect($this->generateUrl('contralor_declaracion_listar_pendientes'));
        }

        public function mostrarTotalesAction($id) {
            $em = $this->getDoctrine()->getManager();
            $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
            //$declaracion->setEstado('Procesando');
            $em->persist($declaracion);
            $em->flush();
            $fileName = $declaracion->getJubidat();

            //$file = fopen($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName, 'r');
            //Output lines until EOF is reached
            $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);


            //dump(Util::totaliza($archivo));
            //die;
            $valores = Util::totaliza($archivo);
            return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/valoresTotalesContralor.html.twig', array(
                        'valores' => $valores, 'declaracion' => $declaracion
            ));
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

        /*public function declaracionAction($id) {
            $em = $this->getDoctrine()->getManager();
            $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findOneBy(array('id' => $id));
            //dump($declaraciones);die;
            return $this->render('@JubilacionesDeclaraciones/ContralorDeclaracionjurada/declaracionjurada.html.twig', array(
                        'declaracion' => $declaracion
            ));
        }*/



}
