<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');

function getData($ciudad, $destino, $producto, $fecha, $noches)
{
	$conexion = conexion_hit();
	if($producto != 'SVO' and $producto != 'OSV' and $producto != 'SSV'){
			$resultado =$conexion->query("select distinct a.OPCION codigo, concat('Opcion ',a.OPCION,': ',DATE_FORMAT(tc.FECHA, '%d/%m'),'&nbsp;&nbsp;&nbsp;',tc.ORIGEN,'-',tc.DESTINO,'&nbsp;&nbsp;&nbsp;',tc.CIA,'-',
										  tc.VUELO,'&nbsp;&nbsp;&nbsp;',time_format(tc.HORA_SALIDA, '%H:%i'),'-',time_format(tc.HORA_LLEGADA, '%H:%i'),'&nbsp;&nbsp;Clase:',tc.CLASE,'&nbsp; Disponibles:',(tc.CUPO - tc.PLAZAS_OK),'&nbsp;&nbsp;En espera:',tc.PLAZAS_WL) nombre
											from hit_producto_cuadros c,
															  hit_producto_cuadros_aereos a,
															  hit_producto_cuadros_aereos_precios p,
															  hit_producto_cuadros_salidas s,
															  hit_destinos d,
															  hit_productos pr,
															  hit_transportes_acuerdos ta,
															  hit_transportes_cupos tc,
															  hit_producto_cuadros_alojamientos al
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
												and a.CIA = ta.CIA
												and a.ACUERDO = ta.ACUERDO
												and a.SUBACUERDO = ta.SUBACUERDO
												and ta.CIA = tc.CIA
												and ta.ACUERDO = tc.ACUERDO
												and ta.SUBACUERDO = tc.SUBACUERDO
												and a.ORIGEN = tc.ORIGEN
												and a.DESTINO = tc.DESTINO
												and c.CLAVE = al.CLAVE_CUADRO
												and ((s.FECHA = tc.FECHA and tc.ORIGEN = a.CIUDAD)
													 or (DATE_ADD(s.FECHA,INTERVAL a.DIA - 1 DAY) = tc.FECHA and tc.DESTINO= a.CIUDAD))
												and ta.TIPO in ('CHR','RCU', 'BUS')
												and a.CIUDAD = '".$ciudad."' 
												and c.DESTINO = '".$destino."' 
												and c.PRODUCTO = '".$producto."'
												and s.FECHA = '".$fecha."' 
												and al.NOCHES = '".$noches."' order by a.OPCION, tc.FECHA");
	}elseif($producto == 'SVO' or $producto == 'OSV'){
			$resultado =$conexion->query("select distinct a.OPCION codigo, concat('Opcion ',a.OPCION,': ',DATE_FORMAT(tc.FECHA, '%d/%m'),'&nbsp;&nbsp;&nbsp;',tc.ORIGEN,'-',tc.DESTINO,'&nbsp;&nbsp;&nbsp;',tc.CIA,'-',
								  tc.VUELO,'&nbsp;&nbsp;&nbsp;',time_format(tc.HORA_SALIDA, '%H:%i'),'-',time_format(tc.HORA_LLEGADA, '%H:%i'),'&nbsp;&nbsp;Clase:',tc.CLASE,'&nbsp; Disponibles:',(tc.CUPO - tc.PLAZAS_OK),'&nbsp;&nbsp;En espera:',tc.PLAZAS_WL) nombre
									from hit_producto_cuadros c,
													  hit_producto_cuadros_aereos a,
													  hit_producto_cuadros_aereos_precios p,
													  hit_producto_cuadros_salidas s,
													  hit_destinos d,
													  hit_productos pr,
													  hit_transportes_acuerdos ta,
													  hit_transportes_cupos tc
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
										and a.CIA = ta.CIA
										and a.ACUERDO = ta.ACUERDO
										and a.SUBACUERDO = ta.SUBACUERDO
										and ta.CIA = tc.CIA
										and ta.ACUERDO = tc.ACUERDO
										and ta.SUBACUERDO = tc.SUBACUERDO
										and a.ORIGEN = tc.ORIGEN
										and a.DESTINO = tc.DESTINO
										and ((s.FECHA = tc.FECHA and tc.ORIGEN = a.CIUDAD)
											 or (DATE_ADD(s.FECHA,INTERVAL a.DIA - 1 DAY) = tc.FECHA and tc.DESTINO= a.CIUDAD))
										and ta.TIPO in ('CHR','RCU', 'BUS')
										and a.CIUDAD = '".$ciudad."' 
										and c.DESTINO = '".$destino."' 
										and c.PRODUCTO = '".$producto."'
										and s.FECHA = '".$fecha."' 
										and '".$noches."' in (select pae.DIA from hit_producto_cuadros_aereos pae where pae.CLAVE_CUADRO = c.CLAVE and pae.ciudad = a.CIUDAD)
										order by a.OPCION, tc.FECHA");
	}

	return $resultado;
}

$results = getData($_POST['ciudad'], $_POST['destino'], $_POST['producto'], $_POST['fecha'], $_POST['noches']);
//print_r($results); Esto no se puede hacer con json
$opcion = array();
if(sizeof($results) == 0){
	
}else{

		for ($i = 0; $i < $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			$opcion[$i]=$fila;

		}

}
header('Content-type: application/json');
		echo json_encode($opcion);
?>
