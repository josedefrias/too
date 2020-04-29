<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');

function getData($ciudad, $destino)
{
	$conexion = conexion_hit();

	$resultado =$conexion->query("select distinct pr.codigo, pr.nombre
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
										and a.CIUDAD = '".$ciudad."'
										and c.DESTINO = '".$destino."' order by nombre");
	return $resultado;
}

$results = getData($_POST['ciudad'], $_POST['destino']);
//print_r($results); Esto no se uede hacer bcon json
$productos = array();
if(sizeof($results) == 0){
	
}else{

		for ($i = 0; $i < $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			$productos[$i]=$fila;

		}

}
header('Content-type: application/json');
		echo json_encode($productos);
?>
