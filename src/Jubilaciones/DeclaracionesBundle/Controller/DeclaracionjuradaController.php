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
//        dump($zona);exit;
        $query = $ddjjService->filtrar($filtros, $zona, $organismo);
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



//All Files	/home/esangoi/vagrant/www/predeju/src/Jubilaciones/DeclaracionesBundle/Resources/views/DeclaracionJurada/declaraciones-juradas.html.twig

        return $this->render('@JubilacionesDeclaraciones/DeclaracionJurada/declaraciones-juradas.html.twig', array(
                    'pagination' => $pagination,
                    'form_filtro' => $formFiltro->createView()
        ));
    }

}
