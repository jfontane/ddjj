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
use Jubilaciones\DeclaracionesBundle\Classes\BoletaConvenioPdf;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\User\UserInterface;

class ConveniocuotaController extends Controller {

  public function listarAction(UserInterface $user) {
    $user = $this->getUser();
    $organismo_codigo = $user->getUsername();
    $arr = $this->obtenerConveniosVigentes($organismo_codigo);
    return $this->render('@JubilacionesDeclaraciones/Conveniocuota/listar.html.twig', array('convenios' => $arr));
  }

  function verAction($codigo_convenio, $tramo, UserInterface $user) {
    $user = $this->getUser();
    $organismo_codigo = $user->getUsername();
    $arreglo_cuotas_vencidas = $this->obtenerCuotasVencidas($organismo_codigo, $codigo_convenio, $tramo);
    $arreglo_cuotas_vigentes = $this->obtenerCuotasVigentes($organismo_codigo, $codigo_convenio, $tramo);
    $arreglo_cuotas_pagadas = $this->obtenerCuotasPagadas($organismo_codigo, $codigo_convenio, $tramo);
    $cantidad_boletas_vigentes = count($arreglo_cuotas_vigentes);
    //dump($arreglo_cuotas_pagadas);die;

    $cantidad_vencimientos = 0;
    if (( $codigo_convenio == '000000012018' ) or ( $codigo_convenio == '000000012020' )) {
      $cantidad_vencimientos = 2;
    } else $cantidad_vencimientos = 3;

    $habilitarImprimir = false;
    if ($codigo_convenio == '000000012018') {
      if (count($arreglo_cuotas_vencidas)>2) {
        $habilitarImprimir = false;
      } else {
        $habilitarImprimir = true;
      }
    }
    //dump($arreglo_cuotas_vigentes);
    //dump($arreglo_cuotas_vencidas);
    //die;
    //if (null != $convenios)
    //$representante = $organismo->getRepresentante();
    //else
    // $representante = null;
    //$convenios=array("saludo"=>"hola");
    //return new Response("sasdasdasd");
    return $this->render('@JubilacionesDeclaraciones/Conveniocuota/ver.html.twig', array(
      'cuotas_vencidas' => $arreglo_cuotas_vencidas,
      'cuotas_vigentes' => $arreglo_cuotas_vigentes,
      'cuotas_pagadas' => $arreglo_cuotas_pagadas,
      'cantidad_vencimientos' => $cantidad_vencimientos,
      'cantidad_boletas_vigentes' => $cantidad_boletas_vigentes,
      'convenio' => $codigo_convenio,
      'tramo' => $tramo,
      'habilitarImprimir' => $habilitarImprimir
    ));
  }

  public function boletaPdfAction($codigo_convenio, $tramo, $cuota, $vencimiento, UserInterface $user) {
    $user = $this->getUser();
    $organismo_codigo = $user->getUsername();

    $arreglo_cuotas_vigentes = $this->obtenerCuotasVigentes($organismo_codigo, $codigo_convenio, $tramo);

    //dump($arreglo_cuotas_vigentes);die;

    if ($vencimiento==1) $nombre_vencimiento = 'vencimiento1';
    else if ($vencimiento==2) $nombre_vencimiento = 'vencimiento2';
    else if ($vencimiento==3) $nombre_vencimiento = 'vencimiento3';
    else $nombre_vencimiento = '';

   $determinacion = "";

    if ($nombre_vencimiento!='') {
      foreach ($arreglo_cuotas_vigentes as $cuotaItem) {
        //dump($cuotaItem);die;
        if ($cuotaItem['codigo_convenio']==$codigo_convenio && $cuotaItem['codigo_organismo']==$organismo_codigo &&
        $cuotaItem['tramo']==$tramo && $cuotaItem['cuota']==$cuota ) {
          try {
            $fecha_vencimiento = $cuotaItem[$nombre_vencimiento];
          } catch (\Exception $e) {
            $determinacion = 'No corresponde vencimiento';
          }
          if ($fecha_vencimiento) {
            if ($fecha_vencimiento < date('Y-m-d')) {
              $determinacion = 'vencida';
            } else {
              $determinacion = 'vigente';
              //die('vigente');
            }
          };

        }


      }
    }


    if ($determinacion == 'vigente') {
            $em = $this->getDoctrine()->getManager();
            $db = $em->getConnection();

            if ($codigo_convenio=='000120092009') {
                  $queryCuota = "SELECT a.codigo_convenio, a.codigo_organismo, a.tramo, a.cuota, a.importe1, a.vencimiento1,
                  a.importe2, a.vencimiento2, a.importe3, a.vencimiento3, a.pagado, b.nombre
                  FROM conveniocuota a, organismo b
                  WHERE ( a.codigo_organismo = :codigo_organismo ) AND
                  ( a.codigo_convenio = :codigo_convenio ) AND
                  ( a.tramo = :tramo ) AND
                  ( a.cuota = :cuota ) AND
                  ( a.codigo_organismo = b.codigo )";
            } else {
                  $queryCuota = "SELECT a.codigo_convenio, a.codigo_organismo, a.tramo, a.cuota, a.importe1, a.vencimiento1,
                  a.importe2, a.vencimiento2, a.pagado, b.nombre
                  FROM conveniocuota a, organismo b
                  WHERE ( a.codigo_organismo = :codigo_organismo ) AND
                  ( a.codigo_convenio = :codigo_convenio ) AND
                  ( a.tramo = :tramo ) AND
                  ( a.cuota = :cuota ) AND
                  ( a.codigo_organismo = b.codigo )";
           }
            $stmt = $db->prepare($queryCuota);
            $params = array('codigo_organismo' => $organismo_codigo, 'codigo_convenio' => $codigo_convenio, 'tramo' => $tramo, 'cuota' => $cuota);
            $stmt->execute($params);
            $arreglo_cuota_a_pagar = $stmt->fetchAll();
            //dump($arreglo_cuota_a_pagar);die;


            $cuota = new Conveniocuota;
            foreach ($arreglo_cuota_a_pagar as $item) {
              $cuota->setCodigoOrganismo($item['codigo_organismo']);
              $cuota->setCodigoConvenio($item['codigo_convenio']);
              $cuota->setTramo($item['tramo']);
              $cuota->setCuota($item['cuota']);

              if ($vencimiento == 1) {
                $cuota->setImporte1($item['importe1']);
                $cuota->setVencimiento1($item['vencimiento1']);
              } else if ($vencimiento == 2) {
                $cuota->setImporte2($item['importe2']);
                $cuota->setVencimiento2($item['vencimiento2']);
              } else if ($vencimiento == 3) {
                $cuota->setImporte3($item['importe3']);
                $cuota->setVencimiento3($item['vencimiento3']);
              };
              $organismo_nombre=$item['nombre'];
            };

            //dump($cuota);
            //die;
            $path = $this->get('kernel')->getRootDir() . '/../web/bundles/jubilacionesdeclaraciones/img';
            $pdf = new BoletaConvenioPdf($path);
            $pdf->setTitle('title algoooooo');
            $pdf->render($cuota,$vencimiento,$organismo_nombre);
            $pdf->Output('boletaConvenio.pdf', 'I');
    } else if ($determinacion == 'vencida') die('Boleta Vencida');
     else die('Errorrrrr'); // else hacer render a una twig
  }

  private function obtenerConveniosVigentes($organismo_codigo) {
    $em = $this->getDoctrine()->getManager();
    $db = $em->getConnection();
    // $query = "SELECT distinct codigo_convenio FROM conveniocuota WHERE codigo_organismo= :codigo_organismo ";
    $query = "SELECT DISTINCT codigo_convenio, tramo
    FROM  conveniocuentacorriente
    WHERE codigo_organismo = :codigo_organismo and tramo not like '9%'";

    $stmt = $db->prepare($query);
    $params = array('codigo_organismo' => $organismo_codigo);
    $stmt->execute($params);
    $resultado = $stmt->fetchAll();
    //dump($resultado);
    //die;

    $arreglo_convenios_vigentes = array();

    foreach ($resultado as $fila) {
      $sql2 = "SELECT DISTINCT IF(count(*)=0,\"No Finalizado\", \"Finalizado\") as Estado
      FROM conveniocuentacorriente
      WHERE codigo_organismo = :codigo_organismo AND codigo_convenio = :codigo_convenio AND
      tramo= :tramo AND codigo_movimiento='CC'";
      $stmt2 = $db->prepare($sql2);
      $params2 = array('codigo_organismo' => $organismo_codigo, 'codigo_convenio' => $fila['codigo_convenio'], 'tramo' => $fila['tramo']);
      $stmt2->execute($params2);
      $resultado2 = $stmt2->fetchAll();
      //dump($resultado2);
      if ($resultado2[0]['Estado'] != 'Finalizado') {
        $arreglo_convenio = array();
        $arreglo_convenio['codigo_convenio'] = $fila['codigo_convenio'];
        $arreglo_convenio['tramo'] = $fila['tramo'];
        $arreglo_convenio['tramo'] = $fila['tramo'];
        array_push($arreglo_convenios_vigentes, $arreglo_convenio);
      };
    } //end foreach
    return $arreglo_convenios_vigentes;
  }

  private function obtenerCuotasVencidas($organismo_codigo, $codigo_convenio, $tramo) {
    $em = $this->getDoctrine()->getManager();
    $db = $em->getConnection();

    if (($codigo_convenio == '000000012018') or ( $codigo_convenio == '000000012020')) {
      $queryCuotasVencidas = "SELECT codigo_convenio, codigo_organismo, tramo, cuota, importe1, vencimiento1,importe2, vencimiento2, pagado
      FROM conveniocuota
      WHERE ( codigo_organismo = :codigo_organismo ) AND
      ( codigo_convenio = :codigo_convenio ) AND
      ( tramo = :tramo ) AND
      ( pagado = 0 ) AND
      (vencimiento2 < CURDATE())";
    } else {
      $queryCuotasVencidas = "SELECT codigo_convenio, codigo_organismo, tramo, cuota, importe1, vencimiento1,importe2, vencimiento2, importe3, vencimiento3, pagado
      FROM conveniocuota
      WHERE ( codigo_organismo = :codigo_organismo ) AND
      ( codigo_convenio = :codigo_convenio ) AND
      ( tramo = :tramo ) AND
      ( pagado = 0 ) AND
      (vencimiento3 < CURDATE())";
    }
    $stmt = $db->prepare($queryCuotasVencidas);
    $params = array('codigo_organismo' => $organismo_codigo, 'codigo_convenio' => $codigo_convenio, 'tramo' => $tramo);
    $stmt->execute($params);
    $arreglo_cuotas_vencidas = $stmt->fetchAll();

    //dump($arreglo_cuotas_vencidas);die;
    $arreglo_cuotas_pagadas=$this->obtenerCuotasPagadas($organismo_codigo, $codigo_convenio, $tramo);
    $array_venc=array();
    foreach($arreglo_cuotas_vencidas as $itemCuota) {
        // echo $itemCuota['cuota']."<br>";
         $band=false;
         foreach($arreglo_cuotas_pagadas as $itemPagada) {
            //echo $itemPagada['cuota']."<br>";
            if ($itemCuota['cuota']==$itemPagada['cuota']) {
              $band=true;
            }
         }
         if (!$band) {
           array_push($array_venc,$itemCuota);
         }

    }
  //dump($array_venc);die;
   return $array_venc;
  }

  //completar esteee
  private function obtenerCuotasVigentes($organismo_codigo, $codigo_convenio, $tramo) {
    $em = $this->getDoctrine()->getManager();
    $db = $em->getConnection();

    if (($codigo_convenio == '000000012018') or ( $codigo_convenio == '000000012020')) {
      $QueryCuotasVigentes = "SELECT DISTINCT  codigo_convenio, codigo_organismo ,tramo , cuota, importe1, vencimiento1, importe2, vencimiento2, pagado
      FROM conveniocuota
      WHERE ( codigo_organismo = :codigo_organismo ) AND
      ( codigo_convenio = :codigo_convenio ) AND
      ( pagado = 0 ) AND
      ( tramo = :tramo ) AND
      ( CURDATE() <= vencimiento2 )";
      $stmt = $db->prepare($QueryCuotasVigentes);
      $params = array('codigo_organismo' => $organismo_codigo, 'codigo_convenio' => $codigo_convenio, 'tramo' => $tramo);
    } else {
      $QueryCuotasVigentes = "SELECT DISTINCT  codigo_convenio, codigo_organismo ,tramo , cuota, importe1, vencimiento1, importe2, vencimiento2, importe3, vencimiento3, pagado
      FROM conveniocuota
      WHERE ( codigo_organismo = :codigo_organismo ) AND
      ( codigo_convenio = :codigo_convenio ) AND
      ( pagado = 0 ) AND
      ( tramo = :tramo ) AND
      ( CURDATE() <= vencimiento3 )";
      $stmt = $db->prepare($QueryCuotasVigentes);
      $params = array('codigo_organismo' => $organismo_codigo, 'codigo_convenio' => $codigo_convenio, 'tramo' => $tramo);
    };

    $stmt->execute($params);
    $arreglo_cuotas_vigentes = $stmt->fetchAll();
    return $arreglo_cuotas_vigentes;
  }


  private function obtenerCuotasPagadas($organismo_codigo, $codigo_convenio, $tramo) {
    $em = $this->getDoctrine()->getManager();
    $db = $em->getConnection();
    $QueryCuotasPagadas="SELECT DISTINCT  codigo_convenio, codigo_organismo, tramo, fecha_pago, cuota, importe
            FROM conveniocuentacorriente
            WHERE (codigo_organismo = :codigo_organismo) AND (codigo_convenio = :codigo_convenio) AND (tramo = :tramo) AND (tipo_movimiento = 'H')";
    $stmt = $db->prepare($QueryCuotasPagadas);
    $params = array('codigo_organismo' => $organismo_codigo, 'codigo_convenio' => $codigo_convenio, 'tramo' => $tramo);
    $stmt->execute($params);
    $arreglo_cuotas_pagadas = $stmt->fetchAll();
    //dump($arreglo_cuotas_pagadas);die;
    return $arreglo_cuotas_pagadas;
  }

}
