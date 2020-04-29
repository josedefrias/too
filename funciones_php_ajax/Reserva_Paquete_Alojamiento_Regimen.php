<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');

function getData($ciudad, $destino, $producto, $fecha, $noches, $alojamiento, $caracteristica)
{
	$conexion = conexion_hit();

	$resultado =$conexion->query("select distinct al.REGIMEN codigo, al.REGIMEN  nombre
									from hit_producto_cuadros c,
													  hit_producto_cuadros_aereos a,
													  hit_producto_cuadros_aereos_precios p,
													  hit_producto_cuadros_salidas s,
													  hit_destinos d,
													  hit_productos pr,
													  hit_producto_cuadros_alojamientos al,
													  hit_habitaciones_car car
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
										and al.SITUACION in ('W','V')
										and c.CLAVE = al.CLAVE_CUADRO
										and al.CARACTERISTICA = car.CODIGO
										and a.CIUDAD = '".$ciudad."'
										and c.DESTINO = '".$destino."' 
										and c.PRODUCTO = '".$producto."'
										and s.FECHA = '".$fecha."' 
										and al.NOCHES = '".$noches."' 
										and al.ALOJAMIENTO = '".$alojamiento."' 
										and al.CARACTERISTICA = '".$caracteristica."' order by car.NOMBRE");
	return $resultado;
}

$results = getData($_POST['ciudad'], $_POST['destino'], $_POST['producto'], $_POST['fecha'], $_POST['noches'], $_POST['alojamiento'], $_POST['caracteristica']);
//print_r($results); Esto no se puede hacer con json
$regimen = array();
if(sizeof($results) == 0){
	
}else{

		for ($i = 0; $i < $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			$regimen[$i]=$fila;

		}

}
header('Content-type: application/json');
		echo json_encode($regimen);
?>
