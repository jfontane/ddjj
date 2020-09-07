<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Jubilaciones\DeclaracionesBundle\Form\RepresentanteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;

class PagoconbaseController extends Controller {

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $declaraciones_111 = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findBy(array('periodoAnio' => '2019', 'periodoMes' =>'07','estado' => 'Procesando', 'tipoLiquidacion' => '111'));
        $declaraciones_112 = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findBy(array('periodoAnio' => '2019', 'periodoMes' =>'07','estado' => 'Procesando', 'tipoLiquidacion' => '111'));
        dump($declaraciones);die;

        return $this->render('@JubilacionesDeclaraciones\Default\index.html.twig');
    }


}
