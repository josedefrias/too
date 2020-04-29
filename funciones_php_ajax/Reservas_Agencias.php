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
	echo "No resultados";
}else{
		echo "<select name='busca_agencias' style=' width:780px; font-family: monospace;' onChange='seleccionar(0)'>";
		echo "<option value=''>Seleccione agencia y pulse 'Modificar' para cambiar la reserva de agencia</option>";
		echo "<option value=''>&nbsp;</option>";
		echo "<option value=''>AGENCIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LOCALIDAD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DIRECCION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OFIC&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TELEFONO</option>";
		echo "<option value=''>&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;</option>";

		for ($i = 0; $i <= $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			//echo $fila['clave']." X ".$fila['nombre']."<br/><br/>";
			echo "<option value='".$fila['clave']."'>".$fila['nombre']."</option>";
		}
		echo "</select>";
		echo "<input type='button' value='Modificar' onClick='grabar_Tipo_Registro_Reserva()'/>";

}
?>
