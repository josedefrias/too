<?php

	include 'funciones_php/conexiones.php';

	$parametrosg = $_GET;
	$id = $parametrosg['id_contenido'];

	$conexion = conexion_hit();

	$datos_cuadro =$conexion->query("select contenido from hit_envios_contenido where id = '".$id."'");
	$odatos_cuadro = $datos_cuadro->fetch_assoc();
	$contenido = $odatos_cuadro['contenido'];

	echo($contenido);

?>


