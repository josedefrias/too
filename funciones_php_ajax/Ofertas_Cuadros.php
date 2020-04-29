<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');


function getData($folleto, $tipo)
{
	$conexion = conexion_hit();
		

		$resultado =$conexion->query("select cuadro codigo, nombre nombre
										from hit_producto_cuadros
										where
											folleto = '".$folleto."'
											and tipo = '".$tipo."' 
										order by nombre");

	return $resultado;

}

$results = getData($_POST['folleto'], $_POST['tipo']);
//print_r($results);

$cuadros = array();
if(sizeof($results) == 0){
	echo "No resultados";
}else{

		for ($i = 0; $i < $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			$cuadros[$i]=$fila;

		}		
		header('Content-type: application/json');
		echo json_encode($cuadros);		
		
}
?>
