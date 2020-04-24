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

    public function estaticaAction($pagina) {
        return $this->render("@JubilacionesDeclaraciones\Default\\" . $pagina . ".html.twig");
    }

    public function nuevoRepresentanteAction(Request $request) {
        $representante = new Representante();
        $form = $this->createForm(RepresentanteType::class, $representante)
                ->add('Guardar', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Saco el organismo del Formulario de Alta de Representante 
            $representanteOrganismo = $form->get('organismo')->getData();
            //Saco el ID del Organismo del Formulario de Alta de Representante
            $idRepresentanteOrganismo = $representanteOrganismo->getId();

            //Localizo el Organisno por ID para ver si tiene o no Representante Vinculado
            $em = $this->getDoctrine()->getManager();
            $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('id' => $idRepresentanteOrganismo));
//            dump($organismo);
//            die;
            $nroDocumento = $representante->getDocumentoNumero();
            $representante->setDocumentoTipo('Dni');
            $representante->setConfirmoDatos('No');
            $representante->setDocumentoNumero(substr($nroDocumento, 2, 8));
            $representante->setFechaSolicitud(new \DateTime('now'));

            $em->persist($representante);
            $em->flush();
            AbstractBaseController::addWarnMessage("El Representante '" . $representante . "' se ha creado correctamente.");

            if (!$organismo->getRepresentante()) {
                $organismo->setRepresentante($representante);
                $em->persist($organismo);
                $em->flush();
                AbstractBaseController::addInfoMessage("El Representante fue vinculado al Organismo '.$organismo.' correctamente.");
            } else {
                AbstractBaseController::addErrorMessage("El Representante NO se ha vinculado al organismo. El Organismo podria ya tener un Representante");
            }
            return $this->redirect($this->generateUrl('inicio'));
        }
        return $this->render('@JubilacionesDeclaraciones/Default/nuevoRepresentante.html.twig', array('form' => $form->createView(),
        ));
    }

}
