<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');

function getData($ciudad, $destino, $producto, $fecha, $noches)
{
	$conexion = conexion_hit();

	//if($producto != 'SVO' and $producto != 'OSV' and $producto != 'SSV'){
		$resultado =$conexion->query("select distinct al.ALOJAMIENTO codigo, concat(alo.NOMBRE,'&nbsp;/&nbsp;',cat.NOMBRE) nombre
										from hit_producto_cuadros c,
														  hit_producto_cuadros_aereos a,
														  hit_producto_cuadros_aereos_precios p,
														  hit_producto_cuadros_salidas s,
														  hit_destinos d,
														  hit_productos pr,
														  hit_producto_cuadros_alojamientos al,
														  hit_alojamientos alo,
														  hit_categorias cat
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
											and al.ALOJAMIENTO = alo.ID
											and alo.CATEGORIA = cat.CODIGO
											and a.CIUDAD = '".$ciudad."'
											and c.DESTINO = '".$destino."' 
											and c.PRODUCTO = '".$producto."'
											and s.FECHA = '".$fecha."' 
											and al.NOCHES = '".$noches."' order by cat.NOMBRE, alo.NOMBRE");
	//}
	return $resultado;
}

$results = getData($_POST['ciudad'], $_POST['destino'], $_POST['producto'], $_POST['fecha'], $_POST['noches']);
//print_r($results); Esto no se puede hacer con json
$alojamiento = array();
if(sizeof($results) == 0){
	
}else{

		for ($i = 0; $i < $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			$alojamiento[$i]=$fila;

		}

}
header('Content-type: application/json');
		echo json_encode($alojamiento);
?>
