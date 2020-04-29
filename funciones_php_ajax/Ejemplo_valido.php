<?php
include '../funciones_php/conexiones.php';

function getData($nombre, $direccion)
{
	$conexion = conexion_hit();
	$resultado =$conexion->query("SELECT o.clave, concat(m.nombre,' - ',o.localidad,' - ',o.direccion,' - ',o.oficina,' - ',o.telefono) nombre
	FROM hit_oficinas o, hit_minoristas m where m.id = o.id and m.nombre like '%".$nombre."%' 
	and o.direccion like '%".$direccion."%' ORDER BY m.nombre, o.localidad, o.direccion,o.oficina");

	return $resultado;
		
}

$results = getData($_POST['nombre_agencia'],$_POST['direccion_agencia']);
//print_r($results);
if(sizeof($results) == 0){
	echo "No resultados";
}else{

		for ($i = 0; $i <= $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			echo $fila['clave']." X ".$fila['nombre']."<br/><br/>";
			//array_push($reservas,$fila);
		}
}
?>
