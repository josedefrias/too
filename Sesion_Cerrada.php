<?php
	require ('Smarty.class.php');

	session_start();

	session_destroy();

	//Establecer plantilla smarty
	$smarty = new Smarty;

	//Visualizar plantilla
	$smarty->display('plantillas/Sesion_cerrada.html');

	exit;

?>

