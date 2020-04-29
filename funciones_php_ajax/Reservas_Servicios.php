<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');

function getData($fecha, $proveedor, $codigo, $tipo, /*$ciudad, */$pax)
{

	$conexion = conexion_hit();
	if($fecha != null and $pax != null){
	
			if($proveedor != null){

				$codi = " AND s.CODIGO = '".$codigo."'";
				$tip = " and s.TIPO = '".$tipo."'";
				//$ciud = " and s.CIUDAD = '".$ciudad."'";
				$CADENA_BUSCAR = " AND s.ID_PROVEEDOR = '".$proveedor."'";

				if($codigo != null){
					$CADENA_BUSCAR .= $codi;	
				}
				if($tipo != null){
					$CADENA_BUSCAR .= $tip;	
				}
				/*if($ciudad != null){
					$CADENA_BUSCAR .= $ciud;	
				}*/

			}elseif($codigo != null){

				$tip = " and s.TIPO = '".$tipo."'";
				//$ciud = " and s.CIUDAD = '".$ciudad."'";
				$CADENA_BUSCAR = " AND s.CODIGO = '".$codigo."'";

				if($tipo != null){
					$CADENA_BUSCAR .= $tip;	
				}
				/*if($ciudad != null){
					$CADENA_BUSCAR .= $ciud;	
				}*/

			}elseif($tipo != null){

				//$ciud = " and s.CIUDAD = '".$ciudad."'";
				$CADENA_BUSCAR = " and s.TIPO = '".$tipo."'";

				/*if($ciudad != null){
					$CADENA_BUSCAR .= $ciud;	
				}*/

			/*}elseif($ciudad != null){
				$CADENA_BUSCAR = " and s.CIUDAD = '".$ciudad."'";*/
			}else{
				$CADENA_BUSCAR = "";    
			}

			$resultado =$conexion->query("select resumen.clave clave, resumen.nombre nombre
					from
					(
					select
							s.ID clave, 
							concat('&nbsp;',RPAD(rtrim(t.NOMBRE), 15, '_') ,'&#124;', 
									 RPAD(rtrim(c.NOMBRE), 15, '_') ,'&#124;', 
									 RPAD(rtrim(p.NOMBRE), 25, '_') ,'&#124;',
									 RPAD(rtrim(s.NOMBRE), 30, '_') ,'&#124;',
							RPAD(CASE s.TIPO_PVP
								WHEN 'C' THEN 'Comisionable'
								WHEN 'N' THEN 'Neto'
								ELSE s.TIPO_PVP
							END, 13, '_'),'&#124;',
							RPAD(CASE sp.TIPO_UNIDAD
								WHEN 'P' THEN 'Por Persona'
								WHEN 'V' THEN 'Por Reserva'
								WHEN 'D' THEN 'Por Dia'
								WHEN 'R' THEN 'Prorrateado'
								ELSE sp.TIPO_UNIDAD
							END, 12, '_'),'&#124;&nbsp;',
							LPAD(CASE sp.CALCULO
								 WHEN sp.CALCULO = 'M' THEN round((sp.COSTE / (1 - (sp.PORCENTAJE_NETO / 100))) / (1 - (sp.PORCENTAJE_COM / 100)),2)
								 ELSE  sp.PVP
							END, 8, 0),'&#124;&nbsp;') nombre
						from
							hit_servicios s,
							hit_servicios_precios sp,
							hit_ciudades c,
							hit_tipos_servicio t,
							hit_proveedores p, 
							hit_fechas f
						where
							s.ID = sp.ID
							and s.CIUDAD = c.CODIGO
							and s.TIPO = t.CODIGO
							and s.ID_PROVEEDOR = p.ID
							and f.fecha = '".date("Y-m-d",strtotime($fecha))."' 
							and f.DIA in (substr(sp.DIAS_SEMANA,1,1), substr(sp.DIAS_SEMANA,2,1), 
									  substr(sp.DIAS_SEMANA,3,1), substr(sp.DIAS_SEMANA,4,1), 
									  substr(sp.DIAS_SEMANA,5,1), substr(sp.DIAS_SEMANA,6,1), 
									  substr(sp.DIAS_SEMANA,7,1))
					  and '".date("Y-m-d",strtotime($fecha))."' between sp.FECHA_DESDE and sp.FECHA_HASTA
					  and '".$pax."' between sp.UNIDAD_DESDE and sp.UNIDAD_HASTA ".$CADENA_BUSCAR." order by t.NOMBRE, c.NOMBRE, 
					  p.NOMBRE, s.NOMBRE) resumen");

		//echo($fecha." - ".$proveedor." - ".$codigo." - ".$tipo." - ".$ciudad." - ".$pax);
		//echo($CADENA_BUSCAR);
	}else{

		$resultado =$conexion->query("select '' clave, '' nombre from dual");

	}

	return $resultado;
		
}

$results = getData($_POST['fecha'],$_POST['proveedor'],$_POST['codigo'],$_POST['tipo'],/*$_POST['ciudad'],*/$_POST['pax']);
//print_r($results);
$agencias = array();
if(sizeof($results) == 0){
	echo "No resultados";
}else{
		echo "<select name='busca_servicio' style=' width:785px; font-family: monospace;' onChange='seleccionar(0)'>";

		echo "<option value=''>&nbsp;Seleccione servicio, teclee pax y pulse 'Grabar Servicio'</option>";

		echo "<option value=''>&nbsp;</option>";

		echo "<option value=''>&nbsp;TIPO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CIUDAD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PROVEEDOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SERVICIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TIPO PVP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UNIDAD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PVP&nbsp;</option>";

		echo "<option value=''>&nbsp;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;</option>";

		for ($i = 0; $i <= $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			//echo "<br/><br/>";
			//echo $fila['clave']." X ".$fila['nombre']."<br/><br/>";
			echo "<option value='".$fila['clave']."'>".$fila['nombre']."</option>";
		}
		echo "</select>";


		echo "&nbsp;Pax<input type= 'text' name='nuevo_pax' size='2'/>";

		echo "&nbsp;<input type='button' value='Grabar Sevicio' onClick='NuevoReserva_Servicios()'/>";

}
?>
