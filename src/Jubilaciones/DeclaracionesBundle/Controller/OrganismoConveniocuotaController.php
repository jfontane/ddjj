<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Jubilaciones\DeclaracionesBundle\Form\DeclaracionjuradaType;
use Jubilaciones\DeclaracionesBundle\Entity\Conveniocuota;
use Jubilaciones\DeclaracionesBundle\Classes\Util;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\User\UserInterface;

class OrganismoConveniocuotaController extends Controller {

    public function listarAction(UserInterface $user) {
        $user = $this->getUser();
        $organismo_codigo = $user->getUsername();
        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();

        $query = "SELECT distinct codigo_convenio FROM conveniocuota WHERE codigo_organismo= :codigo_organismo ";

        $stmt = $db->prepare($query);
        $params = array('codigo_organismo' => $organismo_codigo);
        $stmt->execute($params);
        $convenios = $stmt->fetchAll();

        //dump($convenios);die;
        //if (null != $convenios)
        //$representante = $organismo->getRepresentante();
        //else
        // $representante = null;
        //$convenios=array("saludo"=>"hola");
        //return new Response("sasdasdasd");
        return $this->render('@JubilacionesDeclaraciones/OrganismoConveniocuota/listar.html.twig', array('convenios' => $convenios));
    }

    public function verAction($codigo, UserInterface $user) {
        $user = $this->getUser();
        $organismo_codigo = $user->getUsername();
        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();

        $queryCuotasVencidas = "SELECT concat(nroconvenio,'&',organismo,'&',tramo,'&',cuota,'&', importe1,'&', vencimiento1,'&', importe2,'&', vencimiento2,'&', importe3,'&',vencimiento3,'&','Vencida'), cuota
                      FROM convecuotas
                      WHERE (organismo = '{$codigo}') AND
                            (nroconvenio = '{$nroConvenio}') AND
                            (tramo='{$tramo}') AND
			(vencimiento2 < CURDATE()) AND
			(concat( nroconvenio, organismo, tramo, cuota ) NOT IN (
               SELECT concat( nroconvenio, organismo, tramo, cuota )
			   FROM convectacte))";

        $stmt = $db->prepare($queryCuotasVencidas);
        $params = array('codigo_organismo' => $organismo_codigo, 'codigo_convenio' => $codigo);
        $stmt->execute($params);
        $boletas = $stmt->fetchAll();

        dump($boletas);
        die;
        //if (null != $convenios)
        //$representante = $organismo->getRepresentante();
        //else
        // $representante = null;
        //$convenios=array("saludo"=>"hola");
        //return new Response("sasdasdasd");
        return $this->render('@JubilacionesDeclaraciones/OrganismoConveniocuota/ver.html.twig', array('boletas' => $boletas));
    }

}
