<?php
# Cargamos la librería dompdf.
require_once 'dompdf_config.inc.php';
 	include 'funciones_php/conexiones.php';
	//include 'funciones_php/loggin.php';
	require 'clases/general.cls.php';
	require 'clases/clases_web/Reservas_fin.cls.php';
	require 'clases/interfaz_reserva.cls.php';

$parametrosg = $_GET;
$localizador_hits = $parametrosg['loc'];
$conexion = conexion_hit();

				

$datos_interfaz =$conexion->query("select max(interfaz_localizador) loc_interfaz
									from
									hit_reservas_alojamientos r
									where
									r.LOCALIZADOR = '".$localizador_hits."'");

$odatos_interfaz = $datos_interfaz->fetch_assoc();
$localizador_interfaz = $odatos_interfaz['loc_interfaz'];

$oReservas_interfaces = new clsInterfaz_reserva($conexion);
$mensaje_html_a = $oReservas_interfaces->Bono_reserva_pdf('RESTEL', $localizador_interfaz, $parametrosg['loc']);



$mensaje_html  = $mensaje_html_a;
 
# Instanciamos un objeto de la clase DOMPDF.
$mipdf = new DOMPDF();
 
# Definimos el tamaño y orientación del papel que queremos.
# O por defecto cogerá el que está en el fichero de configuración.
$mipdf ->set_paper("A4", "portrait");
  
# Cargamos el contenido HTML.
$mipdf ->load_html($mensaje_html,'UTF-8'); 
 
# Renderizamos el documento PDF.
$mipdf ->render();
 
# Enviamos el fichero PDF al navegador.
$nombre_pdf = "Hitravel_Reserva_".$localizador_hits.".pdf";
$mipdf ->stream($nombre_pdf);



?>


