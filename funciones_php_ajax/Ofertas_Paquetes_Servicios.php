<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');


function getData($folleto, $cuadro, $tipo)
{
	$conexion = conexion_hit();
		

	if($tipo == 'S'){


		$resultado =$conexion->query("select cs.CLAVE codigo,  concat(ts.NOMBRE, ' - ', s.NOMBRE) nombre
							from hit_producto_cuadros_servicios cs, hit_servicios s, hit_tipos_servicio ts
							where
								cs.ID_PROVEEDOR = s.ID_PROVEEDOR
								and cs.codigo_servicio = s.CODIGO
								and s.TIPO = ts.CODIGO
								and cs.folleto = '".$folleto."'
								and cs.cuadro = '".$cuadro."'
							order by ts.NOMBRE,s.NOMBRE");

	}else{

		$resultado =$conexion->query("select ca.CLAVE codigo,  concat(a.NOMBRE, ' - ', hc.NOMBRE, ' - ', ca.REGIMEN) nombre
							from hit_producto_cuadros_alojamientos ca, hit_alojamientos a, hit_habitaciones_car hc
							where
								ca.ALOJAMIENTO = a.ID
								and ca.CARACTERISTICA = hc.CODIGO
								and ca.CARACTERISTICA <> 'XXX'
								and folleto = '".$folleto."'
								and cuadro = '".$cuadro."'
							order by a.NOMBRE, ca.NUMERO");
	}

	return $resultado;

}

$results = getData($_POST['folleto'], $_POST['cuadro'], $_POST['tipo']);
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
