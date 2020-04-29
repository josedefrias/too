<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');

function getData($fecha, $origen, $destino, $tipo_trayecto)
{

	$conexion = conexion_hit();
	if($fecha != null){


			if($origen != null){

				$desti = " and c.destino = '".$destino."'";
				
				$CADENA_BUSCAR = " and c.origen = '".$origen."'";

				if($destino != null){
					$CADENA_BUSCAR .= $desti;	
				}

			}elseif($destino != null){

				$CADENA_BUSCAR = " and c.destino = '".$destino."'";

			}else{
				$CADENA_BUSCAR = "";    
			}

			$resultado =$conexion->query("select resumen.clave, resumen.nombre
					from
					(
					select c.clave clave, 
						concat(DATE_FORMAT(c.fecha, '%d-%m-%Y'),'&#124;',
						RPAD(rtrim(c.origen), 3, '_') ,'&#124;',
						RPAD(rtrim(c.destino), 3, '_') ,'&#124;',
						RPAD(rtrim(c.cia), 3, '_') ,'&#124;',
						RPAD(rtrim(c.vuelo), 4, '_') ,'&#124;',
						LPAD(c.hora_salida, 5, 0),'&#124;',
						LPAD(c.hora_llegada, 5, 0),'&#124;',
						LPAD(c.cupo - plazas_ok, 3, 0),'&#124;') nombre
					from hit_transportes_cupos c, hit_transportes_acuerdos a
					where
						c.CIA = a.CIA
						and c.ACUERDO = a.ACUERDO
						and c.SUBACUERDO = a.SUBACUERDO
						and c.fecha = '".date("Y-m-d",strtotime($fecha))."'
						and cupo - plazas_ok > 0 ".$CADENA_BUSCAR." order by a.TIPO, c.fecha, c.origen, c.destino) resumen");

		
	}else{

			$resultado =$conexion->query("select '' clave, '' nombre from dual");

	}

	return $resultado;
		
}

$results_ida = getData($_POST['fecha'],$_POST['origen'],$_POST['destino'],$_POST['tipo_trayecto']);

//print_r($results_ida);
$agencias = array();
if(sizeof($results_ida) == 0){
	echo "No resultados";
}else{
		echo "Ida&nbsp;&nbsp;<select name='busca_trayectos_Cupos_Ida' style=' width:400px; font-family: monospace;' onChange='seleccionar(0)'>";

		echo "<option value=''>Selec, teclee pax y reserva y pulse 'Grabar'</option>";

		echo "<option value=''>&nbsp;</option>";

		echo "<option value=''>&nbsp;&nbsp;&nbsp;FECHA&nbsp;&nbsp;&nbsp;ORG&nbsp;DES&nbsp;CIA&nbsp;&nbsp;VLO&nbsp;&nbsp;SAL&nbsp;&nbsp;&nbsp;LLE&nbsp;&nbsp;DISP</option>";

		echo "<option value=''>&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;</option>";

		for ($i = 0; $i <= $results_ida->num_rows; $i++) {
			$results_ida->data_seek($i);
			$fila = $results_ida->fetch_assoc();
			//echo $fila['clave']." X ".$fila['nombre']."<br/><br/>";
			echo "<option value='".$fila['clave']."'>".$fila['nombre']."</option>";
		}

		echo "</select>&nbsp;";


}

if($_POST['tipo_trayecto'] == 'RT'){
	$results_vuelta = getData($_POST['fecha_vuelta'],$_POST['destino'],$_POST['origen'],$_POST['tipo_trayecto']);

	if(sizeof($results_vuelta) == 0){
		echo "No resultados";
	}else{
			echo "&nbsp;&nbsp;Vuelta&nbsp;&nbsp;<select name='busca_trayectos_Cupos_Vuelta' style=' width:400px; font-family: monospace;' onChange='seleccionar(0)'>";

			echo "<option value=''>Selec, teclee pax y reserva y pulse 'Grabar'</option>";

			echo "<option value=''>&nbsp;</option>";

			echo "<option value=''>&nbsp;&nbsp;&nbsp;FECHA&nbsp;&nbsp;&nbsp;ORG&nbsp;DES&nbsp;CIA&nbsp;&nbsp;VLO&nbsp;&nbsp;SAL&nbsp;&nbsp;&nbsp;LLE&nbsp;&nbsp;DISP</option>";

			echo "<option value=''>&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;</option>";

			for ($i = 0; $i <= $results_vuelta->num_rows; $i++) {
				$results_vuelta->data_seek($i);
				$fila = $results_vuelta->fetch_assoc();
				//echo $fila['clave']." X ".$fila['nombre']."<br/><br/>";
				echo "<option value='".$fila['clave']."'>".$fila['nombre']."</option>";
			}

			echo "</select>";
	}
}else{
			echo "&nbsp;&nbsp;Vuelta&nbsp;&nbsp;<select name='busca_trayectos_Cupos_Vuelta' style=' width:400px; font-family: monospace;' onChange='seleccionar(0)'>";

			echo "<option value=''>No seleccionar</option>";

}


?>
