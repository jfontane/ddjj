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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;

//use Symfony\Component\Validator\Constraints\Length; 

class AdminDeclaracionjuradaController extends Controller {

    public function indexAction() {
        return $this->render('JubilacionesDeclaracionesBundle:Default:index.html.twig');
    }

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $declaraciones = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findAllDeclaracionesPorPeriodo();
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/AdminDeclaracionjurada/declaracionesjuradas.html.twig', array(
                    'declaraciones' => $declaraciones
        ));
    }

    public function declaracionAction($id) {
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->findOneBy(array('id' => $id));
        //dump($declaraciones);die;
        return $this->render('@JubilacionesDeclaraciones/AdminDeclaracionjurada/declaracionjurada.html.twig', array(
                    'declaracion' => $declaracion
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

    public function mostrarTotalesAction($id) {
        $totalRemunerativo = $totalNoRemunerativo = $totalApPersonal = $totalApPatronal = 0;
        $totalApIAPOS = $totalApIAPOSsolidario = $totalAdicional = $totalComputoPrivilegio =0;
        $totalRecCPriv = $totalRecSer = $totalDispPol = $totalPasividad = $totalLicEnf = 0; 
        $totalLicSinSueldo = $totalLicMayor30Dias = $totalInasisSusp = $totalMultasTardanzas = 0;
        $totalOrgDeficit = $totalTareasRiesgoza = $totalOtrosAp = $totalUnifAportes = $totalCompDif = 0;
        $cantidad_empleados = 0;
        $em = $this->getDoctrine()->getManager();
        $declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        $fileName = $declaracion->getJubidat();
        //$file = fopen($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName, 'r');
        //Output lines until EOF is reached
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);

        $primerLinea = substr($archivo[0], 0, 255);
        $codigoOrgArch = substr($archivo[0], 1, 10);
        $periodoOrgArch = substr($archivo[0], 30, 6);
        $tipoLiqOrgArch = substr($archivo[0], 82, 3);
        //echo "$codigoOrg**$codigoOrgArch**$periodoOrg**$periodoOrgArch**$tipoLiq**$tipoLiqOrgArch";
            for ($i = 1; $i < $lineas; $i++) {
                $totalRemunerativo = $totalRemunerativo + (float) ($this->convierte(substr($archivo[$i], 106, 11)));
                $totalNoRemunerativo = $totalNoRemunerativo + (float) ($this->convierte(substr($archivo[$i], 117, 11)));
                $totalApIAPOS = $totalApIAPOS + (float) ($this->convierte(substr($archivo[$i], 139, 11)));
                $totalApIAPOSsolidario = $totalApIAPOSsolidario + (float) ($this->convierte(substr($archivo[$i], 150, 11)));
                $totalApPersonal = $totalApPersonal + (float) ($this->convierte(substr($archivo[$i], 161, 11)));
                $totalApPatronal = $totalApPatronal + (float) ($this->convierte(substr($archivo[$i], 172, 11)));
                $totalAdicional = $totalAdicional + (float) ($this->convierte(substr($archivo[$i], 183, 11)));
                $totalComputoPrivilegio = $totalComputoPrivilegio + (float) ($this->convierte(substr($archivo[$i], 194, 8)));
                $totalRecCPriv = $totalRecCPriv + (float) ($this->convierte(substr($archivo[$i], 202, 8)));
                $totalRecSer = $totalRecSer + (float) ($this->convierte(substr($archivo[$i], 210, 8)));
                $totalDispPol = $totalDispPol + (float) ($this->convierte(substr($archivo[$i], 218, 8)));
                $totalPasividad = $totalPasividad + (float) ($this->convierte(substr($archivo[$i], 226, 8)));
                $totalLicEnf = $totalLicEnf + (float) ($this->convierte(substr($archivo[$i], 234, 8)));
                $totalLicSinSueldo = $totalLicSinSueldo + (float) ($this->convierte(substr($archivo[$i], 242, 8)));
                $totalLicMayor30Dias = $totalLicMayor30Dias + (float) ($this->convierte(substr($archivo[$i], 250, 8)));
                $totalInasisSusp = $totalInasisSusp + (float) ($this->convierte(substr($archivo[$i], 258, 8)));
                $totalMultasTardanzas = $totalMultasTardanzas + (float) ($this->convierte(substr($archivo[$i], 266, 8)));
                $totalOrgDeficit = $totalOrgDeficit + (float) ($this->convierte(substr($archivo[$i], 274, 8)));
                $totalTareasRiesgoza = $totalTareasRiesgoza + (float) ($this->convierte(substr($archivo[$i], 282, 8)));
                $totalOtrosAp = $totalOtrosAp + (float) ($this->convierte(substr($archivo[$i], 290, 8)));
                $totalUnifAportes = $totalUnifAportes + (float) ($this->convierte(substr($archivo[$i], 311, 8)));
                $totalCompDif = $totalCompDif + (float) ($this->convierte(substr($archivo[$i], 320, 8)));
            }; // end for
            $cantidad_empleados = $i - 1;
$arreglo_total=Array('totalRemunerativo' => $totalRemunerativo, 'totalNoRemunerativo' => $totalNoRemunerativo,'totalApPersonal' => $totalApPersonal,
                     'totalApPatronal' => $totalApPatronal, 'totalApIAPOS' => $totalApIAPOS, 'totalApIAPOSsolidario' => $totalApIAPOSsolidario, 
                     'totalAdicional' => $totalAdicional, 'totalComputoPrivilegio' => $totalComputoPrivilegio, 'totalRecCPriv' => $totalRecCPriv, 
                     'totalRecSer' => $totalRecSer, 'totalDispPol' => $totalDispPol, 'totalPasividad' => $totalPasividad, 'totalLicEnf' => $totalLicEnf, 
                     'totalLicSinSueldo' => $totalLicSinSueldo, 'totalLicMayor30Dias' => $totalLicMayor30Dias, 'totalInasisSusp' => $totalInasisSusp, 'totalLicEnf' => $totalLicEnf, 
                     'totalMultasTardanzas' => $totalMultasTardanzas, 'totalOrgDeficit' => $totalOrgDeficit, 'totalTareasRiesgoza' => $totalTareasRiesgoza, 'totalOtrosAp' => $totalOtrosAp, 
                     'totalUnifAportes' => $totalCompDif, 'cantidad_empleados' => $cantidad_empleados);
        
        dump($arreglo_total);die;
        
    }

    public function borraJubidatAction($id) {
        //$em = $this->getDoctrine()->getManager();
        //$declaracion = $em->getRepository('JubilacionesDeclaracionesBundle:Declaracionjurada')->find($id);
        // Para borrar el archivo
        //    $fs = new Filesystem(); 
        //    $fs->remove($this->get('kernel')->getRootDir().'/../web/uploads/'.$file_name);
//Hacer redirect        
    }

    private function convierte($valor) {
        $parteDecimalPrimerDigito = substr($valor, strlen($valor) - 2, 1);
        $parteDecimalUltimoDigito = substr($valor, strlen($valor) - 1, 1);
        switch ($parteDecimalUltimoDigito) {
            case '{': $ultimoDigito = 0;
                $signo = '';
                break;
            case 'A': $ultimoDigito = 1;
                $signo = '';
                break;
            case 'B': $ultimoDigito = 2;
                $signo = '';
                break;
            case 'C': $ultimoDigito = 3;
                $signo = '';
                break;
            case 'D': $ultimoDigito = 4;
                $signo = '';
                break;
            case 'E': $ultimoDigito = 5;
                $signo = '';
                break;
            case 'F': $ultimoDigito = 6;
                $signo = '';
                break;
            case 'G': $ultimoDigito = 7;
                $signo = '';
                break;
            case 'H': $ultimoDigito = 8;
                $signo = '';
                break;
            case 'I': $ultimoDigito = 9;
                $signo = '';
                break;
            case '0': $ultimoDigito = 0;
                $signo = '';
                break;
            case '1': $ultimoDigito = 1;
                $signo = '';
                break;
            case '2': $ultimoDigito = 2;
                $signo = '';
                break;
            case '3': $ultimoDigito = 3;
                $signo = '';
                break;
            case '4': $ultimoDigito = 4;
                $signo = '';
                break;
            case '5': $ultimoDigito = 5;
                $signo = '';
                break;
            case '6': $ultimoDigito = 6;
                $signo = '';
                break;
            case '7': $ultimoDigito = 7;
                $signo = '';
                break;
            case '8': $ultimoDigito = 8;
                $signo = '';
                break;
            case '9': $ultimoDigito = 9;
                $signo = '';
                break;
            case '}': $ultimoDigito = 0;
                $signo = '-';
                break;
            case 'J': $ultimoDigito = 1;
                $signo = '-';
                break;
            case 'K': $ultimoDigito = 2;
                $signo = '-';
                break;
            case 'L': $ultimoDigito = 3;
                $signo = '-';
                break;
            case 'M': $ultimoDigito = 4;
                $signo = '-';
                break;
            case 'N': $ultimoDigito = 5;
                $signo = '-';
                break;
            case 'O': $ultimoDigito = 6;
                $signo = '-';
                break;
            case 'P': $ultimoDigito = 7;
                $signo = '-';
                break;
            case 'Q': $ultimoDigito = 8;
                $signo = '-';
                break;
            case 'R': $ultimoDigito = 9;
                $signo = '-';
                break;
        };
        $parteEntera = substr($valor, 0, strlen($valor) - 2);
        return $signo . $parteEntera . '.' . $parteDecimalPrimerDigito . $ultimoDigito;
    }

}
