<?php

	include 'funciones_php/conexiones.php';

	$conexion = conexion_hit();

	$cantidad = 0;

	$resultado =$conexion->query("select id, nombre from hit_busqueda_general where interfaz = 'RESTEL' and NOMBRE IS NOT NULL");

	for ($k = 0; $k <= $resultado->num_rows; $k++) {

		$resultado->data_seek($k);
		$fila = $resultado->fetch_assoc();

		$id = $fila['id'];

		$nombre =   ucwords(strtolower($fila['nombre']));

		$query = "UPDATE hit_busqueda_general SET ";
		$query .= " NOMBRE = '".$nombre."'";
		$query .= " WHERE ID = '".$id."'";
		$resultado_cod=$conexion->query($query);

		$query = "UPDATE hit_busqueda_general SET ";
		$query .= " NOMBRE = replace(nombre, 'Ñ','ñ')";
		$query .= " WHERE ID = '".$id."'";
		$resultado_cod=$conexion->query($query);		

		$cantidad++;

	}
	
	echo($cantidad);

	$resultado->close();

?>


