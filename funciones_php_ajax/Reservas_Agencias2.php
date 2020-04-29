<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');

function getData($nombre, $oficina, $localidad, $direccion, $telefono)
{
	$conexion = conexion_hit();


		if($nombre != null){
			$ofic = " AND o.oficina = '".$oficina."'";	
			$local = " AND o.localidad like '%".$localidad."%'";
			$direc = " AND o.direccion like '%".$direccion."%'";
			$telef = " AND o.telefono like '%".$telefono."%'";
			$CADENA_BUSCAR = " and m.nombre like '%".$nombre."%'";
			if($oficina != null){
				$CADENA_BUSCAR .= $ofic;	
			}
			if($localidad != null){
				$CADENA_BUSCAR .= $local;	
			}
			if($direccion != null){
				$CADENA_BUSCAR .= $direc;	
			}
			if($telefono != null){
				$CADENA_BUSCAR .= $telef;	
			}

		}elseif($oficina != null){
			$local = " AND o.localidad like '%".$localidad."%'";
			$direc = " AND o.direccion like '%".$direccion."%'";
			$telef = " AND o.telefono like '%".$telefono."%'";
			$CADENA_BUSCAR = " and o.oficina = '".$oficina."'";
			if($localidad != null){
				$CADENA_BUSCAR .= $local;	
			}
			if($direccion != null){
				$CADENA_BUSCAR .= $direc;	
			}
			if($telefono != null){
				$CADENA_BUSCAR .= $telef;	
			}
		}elseif($localidad != null){
			$direc = " AND o.direccion like '%".$direccion."%'";
			$telef = " AND o.telefono like '%".$telefono."%'";
			$CADENA_BUSCAR = " AND o.localidad like '%".$localidad."%'";
			if($direccion != null){
				$CADENA_BUSCAR .= $direc;	
			}
			if($telefono != null){
				$CADENA_BUSCAR .= $telef;	
			}
		}elseif($direccion != null){
			$telef = " AND o.telefono like '%".$telefono."%'";
			$CADENA_BUSCAR = " and o.direccion like '%".$direccion."%'";
			if($telefono != null){
				$CADENA_BUSCAR .= $telef;	
			}
		}elseif($telefono != null){
			$CADENA_BUSCAR = " and o.telefono like '%".$telefono."%'";
		}else{
			$CADENA_BUSCAR = " and o.clave = 0";    
  		}

		$resultado =$conexion->query("SELECT o.clave, concat(RPAD(rtrim(m.nombre), 25, '_') ,'_',RPAD(rtrim(o.localidad), 25, '_'), '_',RPAD(rtrim(o.direccion), 30, '_'),'_',LPAD(o.oficina, 4, '0'),'_',LPAD(o.telefono, 15, '_'),'_') nombre
		FROM hit_oficinas o, hit_minoristas m where m.id = o.id ".$CADENA_BUSCAR." ORDER BY m.nombre, o.localidad, o.direccion,o.oficina");


	/*$resultado =$conexion->query("SELECT o.clave, concat(m.nombre,' - ',o.localidad,' - ',o.direccion,' - ',o.oficina,' - ',o.telefono) nombre
	FROM hit_oficinas o, hit_minoristas m where m.id = o.id and m.nombre like '%".$nombre."%' 
	and o.oficina = '".$oficina."' and o.direccion like '%".$direccion."%' ORDER BY m.nombre, o.localidad, o.direccion,o.oficina");*/

	return $resultado;
		
}

$results = getData($_POST['nombre_agencia'],$_POST['oficina_agencia'],$_POST['localidad_agencia'],$_POST['direccion_agencia'],$_POST['telefono_agencia']);
//print_r($results);
$agencias = array();
if(sizeof($results) == 0){
	
}else{

		for ($i = 0; $i < $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			$agencias[$i]=$fila;

		}

}
header('Content-type: application/json');
		echo json_encode($agencias);
?>
