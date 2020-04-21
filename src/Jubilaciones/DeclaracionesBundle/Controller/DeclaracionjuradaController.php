<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Jubilaciones\DeclaracionesBundle\Form\DeclaracionjuradaType;
use Jubilaciones\DeclaracionesBundle\Entity\Organismo;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;

use Symfony\Component\HttpFoundation\File\UploadedFile;

//use Symfony\Component\Validator\Constraints\Length; 

class DeclaracionjuradaController extends Controller {

    public function indexAction() {
        return $this->render('JubilacionesDeclaracionesBundle:Default:index.html.twig');
    }

    public function declaracionesAction() {

        $em = $this->getDoctrine()->getManager();
        $declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/declaracionesjuradas.html.twig', array(
                    'declaraciones' => $declaraciones
        ));
    }

    public function declaracionAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findOneBy(array('id' => $id));
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/declaracionjurada.html.twig', array(
                    'declaracion' => $declaracion
        ));
    }

    public function nuevoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => '4080010000'));
        //dump($organismo->getNombre());die;
        $declaracionjurada = new Declaracionjurada();
        $form = $this->createForm(DeclaracionjuradaType::class, $declaracionjurada)
                ->add('Guardar', SubmitType::class);
// AGREGAR AL FORM BOTÓN DE SUBMIT CON ETIQUETA “Guardar”
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Recogemos el fichero jubidat
            //$fileJubidat = $form['jubidat']->getData();
            $fileJubidat = $form->get('jubidat')->getData();
            $tipoLiq = $this->sacarTipoLiquidacion($declaracionjurada->getPeriodoMes(), $declaracionjurada->getTipoLiquidacion());
            $contenidoJubidat = file_get_contents($fileJubidat);
            //dump(substr($contenidoJubidat,0,200));die;
            //$declaracionjurada->setJubidat($contenidoJubidat);
            /* $fileJubi1ind = $form['jubidat']->getData();
              $contenidoJubi1ind = file_get_contents($fileJubi1ind);
              $declaracionjurada->setJubidat($contenidoJubi1ind); */

            // Sacamos la extensión del fichero
            $ext = $fileJubidat->guessExtension();
            // Le ponemos un nombre al fichero
            //$file_name = '4080010000'.$declaracionjurada->getPeriodoAnio().$declaracionjurada->getPeriodoMes().$tipoLiq . "." . $ext;
            /* Para borrar el archivo
            $fs = new Filesystem(); 
            $fs->remove($this->get('kernel')->getRootDir().'/../web/uploads/'.$file_name);
             * 
             */
            $file_name="jubi.txt";
            // Guardamos el fichero en el directorio uploads que estará en el directorio /web del framework
            $fileJubidat->move("uploads", $file_name);
            

            //$this->sacarTotalesJubidat($file_name);
            
            //$declaracionjurada->setJubidat($file_name);
            

            /* $fechaEntrega = date('Y-m-d'); */
            //$declaracionjurada->setFechaEntrega($fechaEntrega);
            $tipoLiq = $this->sacarTipoLiquidacion($declaracionjurada->getPeriodoMes(), $declaracionjurada->getTipoLiquidacion());
            $declaracionjurada->setFechaEntrega(new \DateTime('now'));
            $declaracionjurada->setEstado('Procesando');
            $declaracionjurada->setOrganismo($organismo);
            $declaracionjurada->setTipoLiquidacion($tipoLiq);
            $em = $this->getDoctrine()->getManager();
            $em->persist($declaracionjurada);
            $em->flush();

            AbstractBaseController::addWarnMessage("La Declaracion Jurada  '" . $declaracionjurada->getPeriodoAnio()
                    . '/' . $declaracionjurada->getPeriodoMes() . "' se ha creado correctamente.");
            return $this->redirect($this->generateUrl('organismo_declaraciones_juradas'));
        }
        return $this->render('@JubilacionesDeclaraciones/Declaracionjurada/nuevo.html.twig', array('form' => $form->createView(),
        ));
    }

    public function getJubidatAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        $file = stream_get_contents($declaracion->getJubidat(), -1, 0);
        //dump(strlen($file));die;
        $size = strlen($file);

        $response = new Response($file, 200, array(
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => $size,
            'Content-Disposition' => 'attachment; filename="jubi.dat"',
        ));
        return $response;
    }

    private function sacarTipoLiquidacion($periodoMes, $tipoLiquidacion) {
        $tipoLiq = 0;
        if (($periodoMes == '13') or ( $periodoMes == '14')) {
            if ($tipoLiquidacion == '1')
                $tipoLiq = '211';
            else if ($tipoLiquidacion == '2')
                $tipoLiq = '212';
        } else if (( $periodoMes == '01' ) or ( $periodoMes == '02' ) or ( $periodoMes == '03' ) or ( $periodoMes == '04' ) or ( $periodoMes == '05' ) or ( $periodoMes == '06' ) or ( $periodoMes == '07' ) or ( $periodoMes == '08' ) or ( $periodoMes == '09' ) or ( $periodoMes == '10' ) or ( $periodoMes == '11' ) or ( $periodoMes == '12' )) {
            if ($tipoLiquidacion == '1')
                $tipoLiq = '111';
            else if ($tipoLiquidacion == '2')
                $tipoLiq = '112';
            else if ($tipoLiquidacion == '3')
                $tipoLiq = '301';
            else if ($tipoLiquidacion == '4')
                $tipoLiq = '302';
            else if ($tipoLiquidacion == '5')
                $tipoLiq = '303';
            else if ($tipoLiquidacion == '6')
                $tipoLiq = '304';
        }
        return $tipoLiq;
    }

    private function sacarTotalesJubidat($file_name) {
        $fp = fopen("uploads/".$file_name, 'rb');
            while (!feof($fp)) {
                $linea = fgets($fp);
                echo $linea."<br>";
            }
    }

}
