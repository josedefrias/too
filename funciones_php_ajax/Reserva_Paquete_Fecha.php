<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');

function getData($ciudad, $destino, $producto)
{
	$conexion = conexion_hit();

	$resultado =$conexion->query("select distinct s.FECHA codigo, DATE_FORMAT(s.fecha, '%d-%m-%Y') nombre
									from hit_producto_cuadros c,
													  hit_producto_cuadros_aereos a,
													  hit_producto_cuadros_aereos_precios p,
													  hit_producto_cuadros_salidas s,
													  hit_destinos d,
													  hit_productos pr
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
										and s.FECHA > CURDATE()
										and c.PRODUCTO = pr.CODIGO
										and pr.VISIBLE = 'S'
										and s.estado = 'A'
										and a.CIUDAD = '".$ciudad."' 
										and c.DESTINO = '".$destino."' 
										and c.PRODUCTO = '".$producto."' order by s.FECHA");
	return $resultado;
}

$results = getData($_POST['ciudad'], $_POST['destino'], $_POST['producto']);
//print_r($results); Esto no se puede hacer con json
$fechas = array();
if(sizeof($results) == 0){
	
}else{

		for ($i = 0; $i < $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			$fechas[$i]=$fila;

		}

}
header('Content-type: application/json');
		echo json_encode($fechas);
?>
