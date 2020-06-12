<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use DateTime;
use Doctrine\DBAL\DBALException;
use Jubilaciones\DeclaracionesBundle\Classes\Util;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Jubilaciones\DeclaracionesBundle\Form\FiltroDeclaracionjuradaType;
use Jubilaciones\DeclaracionesBundle\Services\DeclaracionesJuradasService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

//use Symfony\Component\Validator\Constraints\Length;

class ContralorDeclaracionjuradaController extends Controller {

    public function indexAction() {
        return $this->render('JubilacionesDeclaracionesBundle:Default:index.html.twig');
    }

    public function opcionesAction(Request $request) {

        $user = $this->getUser();
        $zona = $user->getZona();

        $em = $this->getDoctrine()->getManager();
        //$declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();

        $formFiltro = $this->createForm(FiltroDeclaracionjuradaType::class, null);
        $formFiltro->handleRequest($request);
        $filtros = array();
        if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
            $filtros = $formFiltro->getData();
        }

        $ddjjService = $this->get(DeclaracionesJuradasService::class);
//        dump($zona);exit;
        $query = $ddjjService->filtrar($filtros, $zona);
//            dump($filtros);exit;
//        $dql = "SELECT d, o
//                FROM JubilacionesDeclaracionesBundle:Declaracionjurada d
//                JOIN d.organismo o
//                WHERE o.zona = :zona
//                ORDER BY d.fechaEntrega Desc";
//        $declaraciones = $em->createQuery($dql)->setParameter('zona', $zona)->getResult();

        $items_por_pagina = $this->getParameter('knp_paginator_items_por_pagina');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), $items_por_pagina
        );





        return $this->render('@JubilacionesDeclaraciones/ContralorDeclaracionjurada/declaracionesjuradas.html.twig', array(
                    'pagination' => $pagination,
                    'form_filtro' => $formFiltro->createView()
        ));
    }

    public function listarAction(Request $request, UserInterface $user) {
//        try {
//        $this->denyAccessUnlessGranted('ROLE_ADMIN', User, 'Unable to access this page - Javier!');
//        } catch (\Exception $e)
//        {
//          die('noooooooooooooooo');
//        }

        $user = $this->getUser();
        $zona = $user->getZona();
        //die($zona);
        $em = $this->getDoctrine()->getManager();
        //$declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();

        $dql = "SELECT d, o
                FROM JubilacionesDeclaracionesBundle:Declaracionjurada d
                JOIN d.organismo o
                WHERE (d.estado = 'Pendiente' or d.estado = 'Procesando') and o.zona = :zona
                ORDER BY d.fechaEntrega Desc";
        $declaraciones = $em->createQuery($dql)->setParameter('zona', $zona)->getResult();
        //dump($declaraciones);die;

        $items_por_pagina = $this->getParameter('knp_paginator_items_por_pagina');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $declaraciones, $request->query->getInt('page', 1), $items_por_pagina
        );
        //$organismo = $em->createQuery($dql)->setParameter('codigo', $organismo_codigo)->getOneOrNullResult();


        return $this->render('@JubilacionesDeclaraciones/ContralorDeclaracionjurada/declaracionesjuradas.html.twig', array(
                    'pagination' => $pagination
        ));
    }

    public function listarPendientesAction(Request $request, UserInterface $user) {
        //        try {
        //        $this->denyAccessUnlessGranted('ROLE_ADMIN', User, 'Unable to access this page - Javier!');
        //        } catch (\Exception $e)
        //        {
        //          die('noooooooooooooooo');
        //        }

        $user = $this->getUser();
        $zona = $user->getZona();
        //die($zona);
        $em = $this->getDoctrine()->getManager();
        //$declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();

        $dql = "SELECT d, o
                FROM JubilacionesDeclaracionesBundle:Declaracionjurada d
                JOIN d.organismo o
                WHERE (d.estado = 'Pendiente' or d.estado = 'Procesando') and o.zona = :zona
                ORDER BY d.fechaEntrega Desc";
        $declaraciones = $em->createQuery($dql)->setParameter('zona', $zona)->getResult();
        //dump($declaraciones);die;

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $declaraciones, $request->query->getInt('page', 1), 10
        );
        //$organismo = $em->createQuery($dql)->setParameter('codigo', $organismo_codigo)->getOneOrNullResult();


        return $this->render('@JubilacionesDeclaraciones/ContralorDeclaracionjurada/listar.html.twig', array(
                    'pagination' => $pagination, 'tipo' => 'Pendientes'
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

    public function verAction($id) {

        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        $organismo = $declaracion->getOrganismo();

        $fileName = $declaracion->getJubidat();



        //$file = fopen($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName, 'r');
        //Output lines until EOF is reached
        try {
            $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        } catch (DBALException $e) {
            AbstractBaseController::addErrorMessage("No se puede Ver DDJJ pq no hay archivo Asociado.");
            return $this->redirect($this->generateUrl('contralor_declaracion_listar_pendientes'));
        };
        //dump(Util::totaliza($archivo));
        //die;
        $valores = Util::totaliza($archivo);
        return $this->render('@JubilacionesDeclaraciones/ContralorDeclaracionjurada/verTotales.html.twig', array(
                    'valores' => $valores, 'declaracion' => $declaracion, 'organismo' => $organismo
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
