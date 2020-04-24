<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RepresentanteController extends Controller
{
    public function indexAction()
    {
        return $this->render('JubilacionesDeclaracionesBundle:Default:index.html.twig');
    }
}
