<?php

namespace Jubilaciones\DeclaracionesBundle\Classes;

use Jubilaciones\DeclaracionesBundle\Entity\Conveniocuota;

//use FPDI;
//use setasign\Fpdi\Fpdi as FPDI;
//class ReciboPDF extends FPDI	{
class BoletaConvenioPdf extends \TCPDF {

    private $ruta;

    public function setRuta($ruta) {
        $this->ruta = $ruta;
    }

    public function getRuta() {
        return $this->ruta;
    }

    /**
     * include a background template for every page
     */
    function Header() {
        // set bacground image
        $img_file = $this->getRuta() . '/logo.png';
        $this->Image($img_file, 10, 12, 190, 16, '', '', '', false, 300, '', false, false, 0);
    }

    function __construct($ruta, $orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

        $this->setCreator(PDF_CREATOR);
        $this->setAuthor('Caja de Jubilaciones y Pensiones de la Provincia de Santa Fe');
        $this->setSubject('Declaraciones Juradas');
        $this->setKeywords('');
        $this->setAutoPageBreak(0); // cantidad de paginas del listado
        $this->setRuta($ruta);
    }

    public function render($cuota, $vencimiento) {
        $mes = 12;
        $this->setTitle("Listado Declaraciones Juradas");
        /* TODO: Una vez que este todo listo, contemplar el caso de que haya mas
          concetos que filas */

        $this->setMargins(10, 10, 10, 10);
        $this->addPage();

        $this->setFontSize(12);
        $this->SetFont('helvetica', '');

        //Seccion renderizado de los conceptos                        
        $primerFilaY = 30.65;
        $altoCeldaEncabezado = 4.60;
        $pseudoMargen = 12;
        //Periodo Liq                                    
        //$cantidad_ddjj = count($declaraciones);
        //$this->SetFont('Arial', 'I', 8);
        //dump($cantidad_ddjj);
        $this->SetY(37);
        $this->setFontSize(12);
        $this->SetFont('helvetica', 'B');
        $this->cell(190, $altoCeldaEncabezado + 0.16, "LISTADO DE DECLARACIONES JURADAS ENTREGADAS", 0, 1, 'C');
        $this->cell(190, $altoCeldaEncabezado + 0.16, "", 0, 1, 'C');
        $this->cell(30, $altoCeldaEncabezado + 0.16, "Periodo", 1, 0, 'C');
        $this->cell(40, $altoCeldaEncabezado + 0.16, "T.Liquidacion", 1, 0, 'C');
        $this->cell(40, $altoCeldaEncabezado + 0.16, "F.Entrega", 1, 0, 'C');
        $this->cell(40, $altoCeldaEncabezado + 0.16, "F.Dictaminacion", 1, 0, 'C');
        $this->cell(40, $altoCeldaEncabezado + 0.16, "Resultado", 1, 1, 'C');
        $this->setFontSize(10);
        $this->SetFont('helvetica', '');
        /*
          for ($i = 0; $i < $cantidad_ddjj; ++$i) {
          $dmyFE = $declaraciones[$i]->getFechaEntrega()->format('Y-m-d');
          $dmyFD = $declaraciones[$i]->getFechaIngreso() != null ? $declaraciones[$i]->getFechaIngreso()->format('Y-m-d') : "";

          $this->cell(30, $altoCeldaEncabezado + 0.16, $declaraciones[$i]->getPeriodoAnio() . "/" . $declaraciones[$i]->getPeriodoMes(), 1, 0, 'C');
          $this->cell(40, $altoCeldaEncabezado + 0.16, $declaraciones[$i]->getTipoLiquidacion(), 1, 0, 'C');
          $this->cell(40, $altoCeldaEncabezado + 0.16, $dmyFE, 1, 0, 'C');
          $this->cell(40, $altoCeldaEncabezado + 0.16, $dmyFD, 1, 0, 'C');
          $this->cell(40, $altoCeldaEncabezado + 0.16, $declaraciones[$i]->getEstado(), 1, 1, 'C');
          if (($i > 0) && ($i % 45) == 0) {
          $this->setMargins(10, 10, 10, 10);
          $this->AddPage();
          $this->SetY(37);
          $this->setFontSize(12);
          $this->SetFont('helvetica', 'B');
          $this->cell(190, $altoCeldaEncabezado + 0.16, "LISTADO DE DECLARACIONES JURADAS ENTREGADAS", 0, 1, 'C');
          $this->cell(190, $altoCeldaEncabezado + 0.16, "", 0, 1, 'C');
          $this->cell(30, $altoCeldaEncabezado + 0.16, "Periodo", 1, 0, 'C');
          $this->cell(40, $altoCeldaEncabezado + 0.16, "T.Liquidacion", 1, 0, 'C');
          $this->cell(40, $altoCeldaEncabezado + 0.16, "F.Entrega", 1, 0, 'C');
          $this->cell(40, $altoCeldaEncabezado + 0.16, "F.Dictaminacion", 1, 0, 'C');
          $this->cell(40, $altoCeldaEncabezado + 0.16, "Resultado", 1, 1, 'C');
          $this->setFontSize(10);
          $this->SetFont('helvetica', '');
          }
          } */

        $x0 = 20;


        $cod_banco = '920'; // Tipo de Boleta de Pagos para "Aportes no vencidos"
        $codOrganismo8Caracteres = $cuota->getCodigoOrganismo();
        $codConvenio = $cuota->getCodigoConvenio();
        $codCuota = $cuota->getCuota();
        $codTramo = $cuota->getTramo();
        $codRelleno = '000000';
        if ($vencimiento == 1) {
            $codFechaVencimiento = substr($cuota->getVencimiento1(), 0, 2) . substr($cuota->getVencimiento1(), 5, 2) . substr($cuota->getVencimiento1(), 8, 2);
            $codImporte = $this->rellenar_con_ceros($cuota->getImporte1(), 9);
        } else if ($vencimiento == 2) {
            $codFechaVencimiento = substr($cuota->getVencimiento2(), 0, 2) . substr($cuota->getVencimiento2(), 5, 2) . substr($cuota->getVencimiento2(), 8, 2);
            $codImporte = $this->rellenar_con_ceros($cuota->getImporte2(), 9);
        } else if ($vencimiento == 3) {
            $codFechaVencimiento = substr($cuota->getVencimiento3(), 0, 2) . substr($cuota->getVencimiento3(), 5, 2) . substr($cuota->getVencimiento3(), 8, 2);
            $codImporte = $this->rellenar_con_ceros($cuota->getImporte3(), 9);
        };

        $codNumeroVencimiento = '000' . $vencimiento;

        $codificacion = $cod_banco . $codOrganismo8Caracteres . $codConvenio . $codCuota . $codTramo . $codRelleno . $codFechaVencimiento . $codImporte . $codNumeroVencimiento;
        $cant = strlen($codificacion);
        $digito = $this->digito_ctrl($codificacion, $cant);
        $codificacion = $codificacion . $digito;
        $codificacion_agrupada = $this->agrupar_de_4($codificacion);
        

//        $codificacion = $cod_banco . $codOrganismo . $cod_periodo . $aporte_personal . $aporte_patronal . $aporte_otros . $aporte_total;
//        $codificacion = $codificacion . $digito;
        // Interleaved 2 of 5
        $this->write1DBarcode($codificacion, 'I25', $x0 + 5, 126, '', 16, 0.4, $style = array('border' => false, 'padding' => 0, 'text' => false, 'align' => 'C'), 'N');
        $this->cell(190, $altoCeldaEncabezado + 0.16, $codificacion_agrupada,0, 0, 'C');

    }

    function rellenar_con_ceros($valor, $cant_ceros) {
        $valor1 = $valor * 100;
        $string_valor = $valor1;
        $cant = $cant_ceros - strlen($string_valor);

        for ($i = 0; $i <= $cant - 1; $i++) {
            $string_valor = '0' . $string_valor;
        };

        return $string_valor;
    }

    function digito_ctrl($str, $tam) {
        $i1 = 1;
        $i = $tam - 1;
        $suma = 0;
        while ($i >= 0) {
            $i1++;
            if ($i1 > 9)
                $i1 = 2;
            $suma = $suma + ((int) (substr($str, $i, 1) * $i1));
            $i--;
        }
        $resto = $suma % 11;
        $dv = 11 - $resto;
        if (($dv == 0)or ( $dv == 1)or ( $dv > 9))
            $dv = 1;
        return $dv;
    }

    function agrupar_de_4($str) {
        $str_agrupado = "";
        $i = 0;
        while ($i < 56) {
            if ($i == 0) {
                $str_agrupado = $str_agrupado . substr($str, $i, 1);
            } elseif ($i % 4 == 0) {
                $str_agrupado = $str_agrupado . ' ';
                $str_agrupado = $str_agrupado . substr($str, $i, 1);
            } else {
                $str_agrupado = $str_agrupado . substr($str, $i, 1);
            };
            $i++;
        };

        return $str_agrupado;
    }

    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Page number
        $this->cell(0, 10, 'Pag. ' . $this->PageNo() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }

}
