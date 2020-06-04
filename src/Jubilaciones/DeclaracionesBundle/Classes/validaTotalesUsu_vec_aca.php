<?php
session_start();
include_once('seguridadUsu.php');
$path="../organismos/".$_GET['codigo_org']."/".$_GET['periodo']."-".$_GET['tipo_liq'];

function convierte($valor) {
  $parteDecimalPrimerDigito=substr($valor,strlen($valor)-2,1);
  $parteDecimalUltimoDigito=substr($valor,strlen($valor)-1,1);
  switch ($parteDecimalUltimoDigito)
     {
		case '{': $ultimoDigito=0;$signo='';break;
		case 'A': $ultimoDigito=1;$signo='';break;
		case 'B': $ultimoDigito=2;$signo='';break;
		case 'C': $ultimoDigito=3;$signo='';break;
		case 'D': $ultimoDigito=4;$signo='';break;
		case 'E': $ultimoDigito=5;$signo='';break;
		case 'F': $ultimoDigito=6;$signo='';break;
		case 'G': $ultimoDigito=7;$signo='';break;
		case 'H': $ultimoDigito=8;$signo='';break;
		case 'I': $ultimoDigito=9;$signo='';break;
		case '0': $ultimoDigito=0;$signo='';break;
		case '1': $ultimoDigito=1;$signo='';break;
		case '2': $ultimoDigito=2;$signo='';break;
		case '3': $ultimoDigito=3;$signo='';break;
		case '4': $ultimoDigito=4;$signo='';break;
		case '5': $ultimoDigito=5;$signo='';break;
		case '6': $ultimoDigito=6;$signo='';break;
		case '7': $ultimoDigito=7;$signo='';break;
		case '8': $ultimoDigito=8;$signo='';break;
		case '9': $ultimoDigito=9;$signo='';break;
		case '}': $ultimoDigito=0;$signo='-';break;
		case 'J': $ultimoDigito=1;$signo='-';break;
		case 'K': $ultimoDigito=2;$signo='-';break;
		case 'L': $ultimoDigito=3;$signo='-';break;
		case 'M': $ultimoDigito=4;$signo='-';break;
		case 'N': $ultimoDigito=5;$signo='-';break;
		case 'O': $ultimoDigito=6;$signo='-';break;
		case 'P': $ultimoDigito=7;$signo='-';break;
		case 'Q': $ultimoDigito=8;$signo='-';break;
		case 'R': $ultimoDigito=9;$signo='-';break;
    }; 
  $parteEntera=substr($valor,0,strlen($valor)-2);
  return $signo.$parteEntera.'.'.$parteDecimalPrimerDigito.$ultimoDigito;
}

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!-- Tags  para motores de busqueda --><!--meta http-equiv="Content-Type" content="text/html; charset=utf-8" /-->
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        <title>Gobierno de Santa Fe - Trámites</title>
        <meta name="description" content="Nombre del sitio - Descripcion del sitio"/>
        <meta name="keywords" content="palabras claves del sitio aqui"/>
        <meta name="language" content="es"/>
        <meta name="rating" content="general"/>
        <meta name="distribution" content="global"/>
        <meta name="abstract" content="Nombre del sitio - Descripcion del sitio."/>
        <meta name="revisit-after" content="7 days"/>
        <meta name="robots" content="index, follow"/>
        <meta name="owner" content="Nombre del sitio"/>
        <meta name="author" content="Nombre del sitio"/>
        <meta name="e-mail" content="info@correo.com.ar"/>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<META HTTP-EQUIV="REFRESH" CONTENT="30;URL=http://www.santa-fe.gov.ar/jubypen/siafca/contralor/validaTotalesUsu.php?codigo_org=<?php echo $_GET['codigo_org'];?>&periodo=<?php echo $_GET['periodo'];?>&tipo_liq=<?php echo $_GET['tipo_liq'];?>"> 

        <link rel="stylesheet" type="text/css" href="http://www.santafe.gov.ar/repositorio/tramites/css/master.css" />
        <link rel="stylesheet" type="text/css" href="http://www.santafe.gov.ar/repositorio/tramites/css/menu.css" />
        <script src="http://www.santafe.gov.ar/repositorio/tramites/js/DD_roundies_0.0.2a-min.js">
        </script>
        <script>
            /*DD_roundies.addRule('.roundify', '10px');*/

            /* EXAMPLES */

            /* IE only */
            DD_roundies.addRule('.top-tramites', '0px 10px 0px 0px');


            // DD_roundies.addRule('.something_else', '10px 4px');
            //DD_roundies.addRule('.yet_another', '5px', true);
        </script>
        <!--
        <script language="javascript" type="text/javascript" src="../js/tools.js">
        </script>
        -->
        <script language="javascript" type="text/javascript">
           function limpiaCampos()
           {
               //document.getElementById('dni').value="";
               //document.getElementById('security_code').value="";
           }
        </script>
		<script src="../js/validar.js"></script>
		<script src="../js/jscal2.js"></script>
        <script src="../js/lang/es.js"></script>
		<script>
		   function confirma(val) {
		   if (confirm("¿Esta seguro que desea Actualizar el Resultado de la carga?")) {
              // Respuesta afirmativa...
              //window.location=".php";
			  organismo=val.substr(0,10);
			  periodo=val.substr(11,6);
			  tliq=val.substr(18,3);
			  //res_carga=val.substr(20,1);
			  //alert(organismo);alert(periodo);alert(tliq);
			  window.location="procesoActualizarUsu.php?org="+organismo+"&per="+periodo+"&liq="+tliq;
			  //alert("procesoActualizarUsu.php?org="+organismo+"&per="+periodo+"&liq="+tliq)
			  }
		   }	  
		</script>
    </head>
    <body class="body-stars" onload="javascript:limpiaCampos();">
        <div class="content">
            <!-- Este es el Header -->
            <div class="header">
                <div class="header-top">
                    <ul>
                        <li style="margin-right:9px;margin-top:5px;">
                           <?PHP
			    $dias = array("domingo","lunes","martes","miércoles","jueves","viernes","sábado");
	                    $mes = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
	                    echo $dias[date(w)].' '.date(d).' de '.$mes[date(n)-1].' de '.date(Y);
			   ?>
                        </li>
                        <li style="margin:0;">
                            <img src="http://www.santafe.gov.ar/repositorio/tramites/images/icon-contacto-on.gif" alt="" class="float-left"/><a onclick="javascript:window.open('http://www.santafe.gov.ar/index.php/web/guia/contactenos','contacto','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,menubar=no,width=830,height=657,top=0,left=50')" href="#" style="text-decoration:none;color:#686868;" title="Contacto" class="float-left">Contacto</a>
                        </li>
                    </ul>
                </div>
                <div class="header-mid">
                    <img src="http://www.santafe.gov.ar/repositorio/tramites/images/header-logo.gif" alt="Gobierno de Santa Fe" class="float-left"/><img src="http://www.santafe.gov.ar/repositorio/tramites/images/header-tramites.gif" alt="Tramites" class="float-right" style="margin-right: 15px;"/>
                </div>
                <div class="menu">
                    <div>
                        <a href="http://www.santafe.gov.ar" style="text-decoration:none;">www.santafe.gov.ar</a>
                    </div>
                </div>
            </div>
            <!-- Este es el contenido central de la web, son dos columnas -->
            <div class="content-center" style="padding-bottom:0;">
                <div id="roundify" class="top-tramites float-left">
                    <h2 style="margin:12px">Declaraciones Juradas Activos</h2>
                </div>
                <div style="padding-bottom:9px;">
                    <a href="#"><img class="float-right" alt="tu_opinion" src="http://www.santafe.gov.ar/repositorio/tramites/images/boton_tuopinion.gif"></a>
                </div>
                <div id="organismo" style="height:32px;background-color: #ECEEED;">
                    <div style="margin-top:12px" class="content-tramites-gray campo_texto_titulo">
                        <span style="margin:12px" class="campo_institucional">Caja de Jubilaciones y Pensiones - Ministerio de Trabajo y Seguridad Social</span>
                    </div>
                </div>
                <!--
                <div id="menu_del_tramite_online" style="height:32px;background:#ffffff;">
                <div style="margin:12px;" class="item_texto_menu"  onclick="this.className='item_texto_menu';" ><a href="#" onclick="this.className='item_texto_menu_down';" style="text-decoration:none;">INICIO</a> | <a href="#"  onclick="this.className='item_texto_menu_down';" style="text-decoration:none;">MAS OPCIONES</a></div>
                </div>
                --><?php 
				    include_once('./includes/menuDDJJ.php');
				   ?>
                <div class="content-tramites-gray" style="background-color: #ECEEED;">
                    <div style="margin:10px;">
                        <span class="campo_texto_titulo"></span>
                        <div class="parrafo">
                           &nbsp;
                        </div>
					<?php
					   
					
					   $totalRemunerativo=0;$totalApPersonal=0;$totalApPatronal=0;$totalApIAPOS=0;$totalApIAPOSsolidario=0;
					   $archivo = file($path."/JUBI.DAT"); 
					   //echo "****".$path."/JUBI.DAT";
					   $lineas = count($archivo); 
					   $primerLinea=substr($archivo[0],0,255);
					   $codigoOrgArch=substr($archivo[0],1,10);
					   $periodoOrgArch=substr($archivo[0],30,6);
					   $tipoLiqOrgArch=substr($archivo[0],82,3);
					   include('config.php');
					   $sql="SELECT nombre_organismo, amparo 
					         FROM organismos_detalle 
							 WHERE id_organismo='{$codigoOrgArch}'";
					   $resultado=mysql_query($sql);
					   $fila=mysql_fetch_row($resultado);	
                                           $amparo="";
					   if ($fila[1]=='Si') $amparo="<div align=\"center\" style=\"color:#FE642E;font-size:14px;font-weight:bold; margin-left:38px;text-decoration: underline;\"><u>ATENCI&Oacute;N: Este Organismo tiene Amparo.</u></div><br><br> ";
					   //echo "<b>Nombre Organismo:&nbsp;</b>$fila[0]<br><b>Codigo Organismo:&nbsp;</b>$codigoOrgArch<br><b>Periodo:&nbsp;</b>$periodoOrgArch<br><b>Tipo Liquidacion:&nbsp;</b>$tipoLiqOrgArch";
					   
  					      for($i=1; $i < $lineas; $i++){ 
						     $totalRemunerativo=$totalRemunerativo+(float)(convierte(substr($archivo[$i],106,11)));
							 $totalNoRemunerativo=$totalNoRemunerativo+(float)(convierte(substr($archivo[$i],117,11)));
						     $totalApIAPOS=$totalApIAPOS+(float)(convierte(substr($archivo[$i],139,11)));
						     $totalApIAPOSsolidario=$totalApIAPOSsolidario+(float)(convierte(substr($archivo[$i],150,11)));
						     $totalApPersonal=$totalApPersonal+(float)(convierte(substr($archivo[$i],161,11)));
						     $totalApPatronal=$totalApPatronal+(float)(convierte(substr($archivo[$i],172,11)));
						     $totalAdicional=$totalAdicional+(float)(convierte(substr($archivo[$i],183,11)));
						     $totalComputoPrivilegio=$totalComputoPrivilegio+(float)(convierte(substr($archivo[$i],194,8)));
						     $totalRecCPriv=$totalRecCPriv+(float)(convierte(substr($archivo[$i],202,8)));
						     $totalRecSer=$totalRecSer+(float)(convierte(substr($archivo[$i],210,8)));
						     $totalDispPol=$totalDispPol+(float)(convierte(substr($archivo[$i],218,8)));
						     $totalPasividad=$totalPasividad+(float)(convierte(substr($archivo[$i],226,8)));
						     $totalLicEnf=$totalLicEnf+(float)(convierte(substr($archivo[$i],234,8)));
						     $totalLicSinSueldo=$totalLicSinSueldo+(float)(convierte(substr($archivo[$i],242,8)));
						     $totalLicMayor30Dias=$totalLicMayor30Dias+(float)(convierte(substr($archivo[$i],250,8)));
						     $totalInasisSusp=$totalInasisSusp+(float)(convierte(substr($archivo[$i],258,8)));
						     $totalMultasTardanzas=$totalMultasTardanzas+(float)(convierte(substr($archivo[$i],266,8)));
						     $totalOrgDeficit=$totalOrgDeficit+(float)(convierte(substr($archivo[$i],274,8)));
						     $totalTareasRiesgoza=$totalTareasRiesgoza+(float)(convierte(substr($archivo[$i],282,8)));
						     $totalOtrosAp=$totalOtrosAp+(float)(convierte(substr($archivo[$i],290,8)));
						     $totalUnifAportes=$totalUnifAportes+(float)(convierte(substr($archivo[$i],311,8)));
						     $totalCompDif=$totalCompDif+(float)(convierte(substr($archivo[$i],320,8)));
						   };
					     $cantidad_empleados=$i-1;	
					     echo $amparo."<div align=\"center\" style=\"color:#000000;font-size:17px;font-weight:bold; margin-left:38px;text-decoration: underline;\"><u>TOTALES SOBRE ".$cantidad_empleados." EMPLEADOS</u></div><br><br> ";
						 
						 $path1 = "../resultados";
                         $dir = opendir($path1);
                         $esta=0;
                         while ($elemento = readdir($dir))
                              {
                                 if (strlen($elemento)>3) {
                                    $res_organismo=substr($elemento,0,10);
									$res_tipo_liq=substr($elemento,16,3);
									/* este if va por culpa de victor que no quiere trabajar */
                                    if (($res_tipo_liq=='212') and (substr($elemento,14,2)=='06')) $res_periodo_mes='13';
                                    else if (($res_tipo_liq=='212') and (substr($elemento,14,2)=='12')) $res_periodo_mes='14';
                                    else $res_periodo_mes=substr($elemento,14,2);
									
									$res_periodo_anio=substr($elemento,10,4);
   									//$res_periodo_mes=substr($elemento,14,2);
   									$res_periodo=$res_periodo_anio.$res_periodo_mes;
   									$res_tipo_liq=substr($elemento,16,3);
									$res_estado_de_carga=substr($elemento,35,1);
						/*			echo $res_organismo."**".$codigoOrgArch."<br>";
									echo $res_periodo."**".$periodoOrgArch."<br>";
									echo $res_tipo_liq."**".$tipoLiqOrgArch."<br>";
									echo $res_estado_de_carga;*/
								    if (($res_organismo==$codigoOrgArch)and($res_periodo==$periodoOrgArch)and($res_tipo_liq==$tipoLiqOrgArch)) $esta=1;
									//echo $elemento;
								} // end IF
                              } //End While
						 
						 //echo "**".$esta."**";	    
						 
						 if ($esta==1) $habilitar="";
						 else $habilitar="disabled=\"disabled\"";
						 include_once('tablaUsu.php');
					   ?>
					 
			        <br>
			<br>
			<div id="error">
			   <span class="informacion"></span>
			</div>
			<br/>
                        <br/>
                        <br/>
                    </div>
                </div>
            </div>
            <!-- Este es el pie de pagina, footer -->
            <div class="footer">
                <p class="float-left" style="width: 380px; margin-left: 11px;">
                    <strong>GOBIERNO DE SANTA FE</strong>
                    3 de Febrero 2649 (S3000DEE) Santa Fe
                    <br/>
                    Teléfono 54 + 342 4506600 | 4506700 | 4506800
                </p>
                <p class="float-right" style="width:190px;">
                    © 2010 - Todos los derechos reservados.
                    <br/>
                    Créditos | Términos y condiciones
                </p>
            </div>
        </div>
    </body>
</html>
