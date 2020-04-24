<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Jubilaciones\DeclaracionesBundle\Entity\Representante;
use Jubilaciones\DeclaracionesBundle\Form\RepresentanteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('@JubilacionesDeclaraciones\Default\index.html.twig');
    }

    public function estaticaAction($pagina) {
        return $this->render("@JubilacionesDeclaraciones\Default\\" . $pagina . ".html.twig");
    }

    public function nuevoRepresentanteAction(Request $request) {
        $representante = new Representante();
        $form = $this->createForm(RepresentanteType::class, $representante)
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
        return $this->render('@JubilacionesDeclaraciones/Default/nuevoRepresentante.html.twig', array('form' => $form->createView(),
        ));
    }

}
