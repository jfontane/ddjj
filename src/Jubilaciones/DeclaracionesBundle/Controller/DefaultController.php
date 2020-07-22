<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Jubilaciones\DeclaracionesBundle\Entity\Representante;
use Jubilaciones\DeclaracionesBundle\Form\RepresentanteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('@JubilacionesDeclaraciones\Default\index.html.twig');
    }

     public function principalAction() {
        return $this->render('@JubilacionesDeclaraciones\Default\principal.html.twig');
    }

    public function estaticaAction() {
        return $this->render("@JubilacionesDeclaraciones\Default\politicas.html.twig");
    }

}
