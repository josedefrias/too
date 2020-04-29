<?php
	header('Content-type: text/html; charset=utf-8');
	//include_once("analyticstracking.php");
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';


	$conexion = conexion_hit();

	$parametrosg = $_GET;


	/*echo('<pre>');
	print_r($_GET);
	echo('</pre>');*/



	$localizador = @$parametrosg['localizador'];


			//Establecer plantilla smarty
			$smarty = new Smarty;

			$smarty->assign('localizador', $localizador);


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Ver_transferencia.html');

	



	$conexion->close();

?>


