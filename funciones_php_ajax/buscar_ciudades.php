<?php
	include '../funciones_php/conexiones.php';
	header('Content-Type: text/html; charset=utf-8');

	function getData($nombre)
	{
		$conexion = conexion_hit();

		$resultado =$conexion->query("SELECT codigo, nombre
									FROM hit_ciudades
									where pais = 'ESP'
										and nombre LIKE '".$nombre."%' 
									order by nombre 
									LIMIT 15");
									
		return $resultado;	
	}

	$results = getData($_POST['nombre']);

	$ciudades = array();
	if(sizeof($results) == 0){
		
	}else{

			for ($i = 0; $i < $results->num_rows; $i++) {
				$results->data_seek($i);
				$fila = $results->fetch_assoc();
				$ciudades[$i]=$fila;

			}

	}
	header('Content-type: application/json');
	echo json_encode($ciudades);
?>