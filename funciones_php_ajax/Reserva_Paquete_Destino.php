<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');

function getData($ciudad)
{
	$conexion = conexion_hit();

	$resultado =$conexion->query("select distinct d.codigo, d.nombre 
									from hit_producto_cuadros c,
													  hit_producto_cuadros_aereos a,
													  hit_producto_cuadros_aereos_precios p,
													  hit_producto_cuadros_salidas s,
													  hit_destinos d
									where
										c.CLAVE = a.CLAVE_CUADRO
										and a.FOLLETO = p.FOLLETO
										and a.CUADRO = p.CUADRO
										and a.CIUDAD = p.CIUDAD
										and a.OPCION = p.OPCION
										and a.NUMERO = p.NUMERO
										and c.CLAVE = s.CLAVE_CUADRO
										and c.DESTINO = d.CODIGO
										and s.FECHA between p.FECHA_DESDE and p.FECHA_HASTA
										and s.estado = 'A'
										and s.FECHA > CURDATE()
										and a.CIUDAD = '".$ciudad."' order by nombre");
	return $resultado;
}

$results = getData($_POST['ciudad']);
//print_r($results);
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
