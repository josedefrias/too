<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');


function getData($provincia, $grupo)
{
	$conexion = conexion_hit();
		
		if($provincia != null){
			$grup = " and m.GRUPO_GESTION = '".$grupo."'";	
			$CADENA_BUSCAR = " and o.PROVINCIA = '".$provincia."'";
			if($grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}

		}elseif($grupo != null){
			$CADENA_BUSCAR = " and m.GRUPO_GESTION = '".$grupo."'";

		}else{
			$CADENA_BUSCAR = " and m.GRUPO_GESTION = 0";    
  		}

		$resultado =$conexion->query("select distinct m.ID id, m.NOMBRE nombre
										from hit_minoristas m, hit_oficinas o
										where
											m.ID = o.ID
											".$CADENA_BUSCAR."
										order by m.NOMBRE");
										

	return $resultado;

}

$results = getData($_POST['provincia'],$_POST['grupo_gestion']);
//print_r($results);

$agencias = array();
if(sizeof($results) == 0){
	echo "No resultados";
}else{

		for ($i = 0; $i < $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			$agencias[$i]=$fila;

		}		
		header('Content-type: application/json');
		echo json_encode($agencias);		
		
}
?>
