<?php
include '../funciones_php/conexiones.php';
header('Content-Type: text/html; charset=utf-8');;

function getData($fecha_desde, $fecha_hasta, /*$pais, $provincia, $ciudad, $categoria, $situacion,*/ $alojamiento, $consulta)
{
	//print($fecha_desde." - ".$fecha_hasta." - ".$pais." - ".$provincia." - ".$ciudad." - ".$categoria." - ".$situacion." - ".$alojamiento." - ".$consulta);

	$conexion = conexion_hit();
	if($fecha_desde != null and $fecha_hasta != null){

		if($consulta == 'C'){

			/*if($pais != null){

				$provi = " AND provincias.CODIGO = '".$provincia."'";
				$ciud = " and ciudades.CODIGO = '".$ciudad."'";
				$categ = " and alojamientos.CATEGORIA = '".$categoria."'";
				$situa = " alojamientos.SITUACION = '".$situacion."'";
				$aloja = " and cupos.ID = '".$alojamiento."'";
				
				$CADENA_BUSCAR = " and paises.CODIGO = '".$pais."'";

				if($provincia != null){
					$CADENA_BUSCAR .= $provi;	
				}
				if($ciudad != null){
					$CADENA_BUSCAR .= $ciud;	
				}
				if($categoria != null){
					$CADENA_BUSCAR .= $categ;	
				}
				if($situacion != null){
					$CADENA_BUSCAR .= $situa;	
				}
				if($alojamiento != null){
					$CADENA_BUSCAR .= $aloja;	
				}

			}elseif($provincia != null){

				$ciud = " and ciudades.CODIGO = '".$ciudad."'";
				$categ = " and alojamientos.CATEGORIA = '".$categoria."'";
				$situa = " alojamientos.SITUACION = '".$situacion."'";
				$aloja = " and cupos.ID = '".$alojamiento."'";
				
				$CADENA_BUSCAR = " AND provincias.CODIGO = '".$provincia."'";

				if($ciudad != null){
					$CADENA_BUSCAR .= $ciud;	
				}
				if($categoria != null){
					$CADENA_BUSCAR .= $categ;	
				}
				if($situacion != null){
					$CADENA_BUSCAR .= $situa;	
				}
				if($alojamiento != null){
					$CADENA_BUSCAR .= $aloja;	
				}
			}elseif($ciudad != null){

				$categ = " and alojamientos.CATEGORIA = '".$categoria."'";
				$situa = " alojamientos.SITUACION = '".$situacion."'";
				$aloja = " and cupos.ID = '".$alojamiento."'";
				
				$CADENA_BUSCAR = " and ciudades.CODIGO = '".$ciudad."'";

				if($categoria != null){
					$CADENA_BUSCAR .= $categ;	
				}
				if($situacion != null){
					$CADENA_BUSCAR .= $situa;	
				}
				if($alojamiento != null){
					$CADENA_BUSCAR .= $aloja;	
				}
			}elseif($categoria != null){

				$situa = " alojamientos.SITUACION = '".$situacion."'";
				$aloja = " and cupos.ID = '".$alojamiento."'";
				
				$CADENA_BUSCAR = " and alojamientos.CATEGORIA = '".$categoria."'";

				if($situacion != null){
					$CADENA_BUSCAR .= $situa;	
				}
				if($alojamiento != null){
					$CADENA_BUSCAR .= $aloja;	
				}
			}elseif($situacion != null){

				$aloja = " and cupos.ID = '".$alojamiento."'";
				
				$CADENA_BUSCAR = " alojamientos.SITUACION = '".$situacion."'";

				if($alojamiento != null){
					$CADENA_BUSCAR .= $aloja;	
				}
			}else*/if($alojamiento != null){

				//$CADENA_BUSCAR = " and alojamientos.ID = '".$alojamiento."'";

				$CADENA_BUSCAR = " and alojamientos.NOMBRE like '%".$alojamiento."%'";

			}else{
				//$CADENA_BUSCAR = " and cupos.clave = 0"; 
				$CADENA_BUSCAR = "";    
			}

			$resultado =$conexion->query("select resumen.clave, resumen.nombre
					from
					(
					SELECT cupos.CLAVE, 
					concat(RPAD(rtrim(paises.NOMBRE), 10, '_') ,'&#124;',
							RPAD(rtrim(provincias.NOMBRE), 10, '_') ,'&#124;',
							RPAD(rtrim(ciudades.NOMBRE), 10, '_') ,'&#124;',
							RPAD(rtrim(alojamientos.NOMBRE), 15, '_') ,'&#124;', 
							RPAD(rtrim(categorias.NOMBRE), 5, '_') ,'&#124;', 
							RPAD(rtrim(habitaciones.NOMBRE), 8, '_') ,'&#124;', 
							RPAD(rtrim(habitaciones_car.NOMBRE), 10, '_') ,'&#124;&nbsp;', 
							concat(min(precios.uso),'-',max(precios.USO)),'&#124;&nbsp;', 
						   LPAD(min(cupos.CUPO - cupos.OCUPADAS), 3, '0'),'&#124;&nbsp;', 
						   DATE_FORMAT(min(cupos.FECHA), '%d-%m-%Y'),'&#124;&nbsp;',
							LPAD(max(cupos.FECHA) - min(cupos.FECHA) + 1, 2, '0'),'&#124;&nbsp;',		
							LPAD(min(CASE
							 	 WHEN precios.CALCULO = 'A' and precios.USO = 1 THEN round((precios.COSTE_PAX / (1 - (precios.PORCENTAJE_NETO / 100))) / (1 - (precios.PORCENTAJE_COM / 100)),2)
								 WHEN precios.CALCULO = 'M' and precios.USO = 1 THEN precios.PVP_PAX
								 ELSE 'sin opcion'
							END), 7, 0),'&#124;&nbsp;',
							
							LPAD(min(CASE
							 	 WHEN precios.CALCULO = 'A' and precios.USO = 2 THEN round((precios.COSTE_PAX / (1 - (precios.PORCENTAJE_NETO / 100))) / (1 - (precios.PORCENTAJE_COM / 100)),2)
								 WHEN precios.CALCULO = 'M' and precios.USO = 2 THEN precios.PVP_PAX
								 ELSE 'sin opcion'
							END), 7, 0),'&#124;&nbsp;',
							
							LPAD(min(CASE
							 	 WHEN precios.CALCULO = 'A' and precios.USO = 3 THEN round((precios.COSTE_PAX / (1 - (precios.PORCENTAJE_NETO / 100))) / (1 - (precios.PORCENTAJE_COM / 100)),2)
								 WHEN precios.CALCULO = 'M' and precios.USO = 3 THEN precios.PVP_PAX
								 ELSE 'sin opcion'
							END), 7, 0),'&#124;&nbsp;',
							
							LPAD(min(CASE
							 	 WHEN precios.CALCULO = 'A' and precios.USO = 4 THEN round((precios.COSTE_PAX / (1 - (precios.PORCENTAJE_NETO / 100))) / (1 - (precios.PORCENTAJE_COM / 100)),2)
								 WHEN precios.CALCULO = 'M' and precios.USO = 4 THEN precios.PVP_PAX
								 ELSE 'sin opcion'
							END), 7, 0),'&#124;&nbsp;',
						  LPAD(min(temporadas.SPTO_AD), 3, 0),'&#124;&nbsp;',
						  LPAD(min(temporadas.SPTO_MP), 3, 0),'&#124;&nbsp;', 
						  LPAD(min(temporadas.SPTO_PC), 3, 0),'&#124;&nbsp;', 
						  LPAD(min(temporadas.SPTO_TI), 3, 0),'&#124;') nombre
					FROM
					  hit_alojamientos_cupos cupos,
					  hit_alojamientos_acuerdos acuerdos,
					  hit_alojamientos_periodos periodos,
					  hit_alojamientos_temporadas temporadas,
					  hit_alojamientos_precios precios,
					  hit_alojamientos alojamientos,
					  hit_provincias provincias,
					  hit_ciudades ciudades,
					  hit_paises paises,
					  hit_categorias categorias,
					  hit_situacion situacion,
					  hit_habitaciones habitaciones,
					  hit_habitaciones_car habitaciones_car
					where
					  cupos.ID = alojamientos.ID
					  and cupos.ID = acuerdos.ID
					  and cupos.ACUERDO = acuerdos.ACUERDO
					  and cupos.ID = periodos.ID
					  and cupos.ACUERDO = periodos.ACUERDO
					  and cupos.FECHA between periodos.FECHA_DESDE and periodos.FECHA_HASTA
					  and periodos.ID = temporadas.ID
					  and periodos.ACUERDO = temporadas.ACUERDO
					  and periodos.TEMPORADA = temporadas.TEMPORADA
					  and cupos.ID = precios.ID
					  and cupos.ACUERDO = precios.ACUERDO
					  and temporadas.TEMPORADA = precios.TEMPORADA
					  and cupos.HABITACION = precios.HABITACION
					  and cupos.CARACTERISTICA = precios.CARACTERISTICA
					  and alojamientos.CIUDAD = ciudades.CODIGO
					  and ciudades.PROVINCIA = provincias.CODIGO
					  and ciudades.PAIS = paises.CODIGO
					  and alojamientos.CATEGORIA = categorias.CODIGO
					  and alojamientos.SITUACION = situacion.CODIGO
					  and cupos.HABITACION = habitaciones.CODIGO
					  and cupos.CARACTERISTICA = habitaciones_car.CODIGO
					  and acuerdos.tipo = 'C'
					  and cupos.FECHA between '".date("Y-m-d",strtotime($fecha_desde))."' and DATE_SUB('".date("Y-m-d",strtotime($fecha_hasta))."',INTERVAL 1 DAY) ".$CADENA_BUSCAR.
					  " group by paises.NOMBRE, provincias.NOMBRE, ciudades.NOMBRE, alojamientos.NOMBRE, 
						 habitaciones.NOMBRE, habitaciones_car.NOMBRE) resumen");


					/*
					  //cadena buscar para pruebas
					  and paises.CODIGO = 'ESP'
					  AND provincias.CODIGO = 'SI'
					  and ciudades.CODIGO = 'BCN'
					  and alojamientos.CATEGORIA = '3'
					  and alojamientos.SITUACION = 'SM'
					  and cupos.ID = '896'
					  and cupos.FECHA between '2013-08-01' and '2013-08-7'

					   */

		}else{
			//$resultado =$conexion->query("select '' clave, '' nombre from dual");

			/*if($pais != null){

				$provi = " AND provincias.CODIGO = '".$provincia."'";
				$ciud = " and ciudades.CODIGO = '".$ciudad."'";
				$categ = " and alojamientos.CATEGORIA = '".$categoria."'";
				$situa = " alojamientos.SITUACION = '".$situacion."'";
				$aloja = " and alojamientos.ID = '".$alojamiento."'";
				
				$CADENA_BUSCAR = " and paises.CODIGO = '".$pais."'";

				if($provincia != null){
					$CADENA_BUSCAR .= $provi;	
				}
				if($ciudad != null){
					$CADENA_BUSCAR .= $ciud;	
				}
				if($categoria != null){
					$CADENA_BUSCAR .= $categ;	
				}
				if($situacion != null){
					$CADENA_BUSCAR .= $situa;	
				}
				if($alojamiento != null){
					$CADENA_BUSCAR .= $aloja;	
				}

			}elseif($provincia != null){

				$ciud = " and ciudades.CODIGO = '".$ciudad."'";
				$categ = " and alojamientos.CATEGORIA = '".$categoria."'";
				$situa = " alojamientos.SITUACION = '".$situacion."'";
				$aloja = " and alojamientos.ID = '".$alojamiento."'";
				
				$CADENA_BUSCAR = " AND provincias.CODIGO = '".$provincia."'";

				if($ciudad != null){
					$CADENA_BUSCAR .= $ciud;	
				}
				if($categoria != null){
					$CADENA_BUSCAR .= $categ;	
				}
				if($situacion != null){
					$CADENA_BUSCAR .= $situa;	
				}
				if($alojamiento != null){
					$CADENA_BUSCAR .= $aloja;	
				}
			}elseif($ciudad != null){

				$categ = " and alojamientos.CATEGORIA = '".$categoria."'";
				$situa = " alojamientos.SITUACION = '".$situacion."'";
				$aloja = " and alojamientos.ID = '".$alojamiento."'";
				
				$CADENA_BUSCAR = " and ciudades.CODIGO = '".$ciudad."'";

				if($categoria != null){
					$CADENA_BUSCAR .= $categ;	
				}
				if($situacion != null){
					$CADENA_BUSCAR .= $situa;	
				}
				if($alojamiento != null){
					$CADENA_BUSCAR .= $aloja;	
				}
			}elseif($categoria != null){

				$situa = " alojamientos.SITUACION = '".$situacion."'";
				$aloja = " and alojamientos.ID = '".$alojamiento."'";
				
				$CADENA_BUSCAR = " and alojamientos.CATEGORIA = '".$categoria."'";

				if($situacion != null){
					$CADENA_BUSCAR .= $situa;	
				}
				if($alojamiento != null){
					$CADENA_BUSCAR .= $aloja;	
				}
			}elseif($situacion != null){

				$aloja = " and alojamientos.ID = '".$alojamiento."'";
				
				$CADENA_BUSCAR = " alojamientos.SITUACION = '".$situacion."'";

				if($alojamiento != null){
					$CADENA_BUSCAR .= $aloja;	
				}
			}else*/if($alojamiento != null){

				//$CADENA_BUSCAR = " and alojamientos.ID = '".$alojamiento."'";
				$CADENA_BUSCAR = " and alojamientos.NOMBRE like '%".$alojamiento."%'";
			}else{
				//$CADENA_BUSCAR = " and cupos.clave = 0"; 
				$CADENA_BUSCAR = "";    
			}


			$resultado =$conexion->query("select resumen.clave, resumen.nombre
					from
					(
					SELECT precios.CLAVE, 
					concat(RPAD(rtrim(paises.NOMBRE), 10, '_') ,'&#124;',
							RPAD(rtrim(provincias.NOMBRE), 10, '_') ,'&#124;',
							RPAD(rtrim(ciudades.NOMBRE), 10, '_') ,'&#124;',
							RPAD(rtrim(alojamientos.NOMBRE), 15, '_') ,'&#124;', 
							RPAD(rtrim(categorias.NOMBRE), 5, '_') ,'&#124;', 
							RPAD(rtrim(habitaciones.NOMBRE), 8, '_') ,'&#124;', 
							RPAD(rtrim(habitaciones_car.NOMBRE), 10, '_') ,'&#124;&nbsp;', 
							concat(min(precios.uso),'-',max(precios.USO)),'&#124;&nbsp;', 
						   '000','&#124;&nbsp;', 
						   '".$fecha_desde."','&#124;&nbsp;',
							LPAD('".$fecha_hasta."' - '".$fecha_desde."', 2, '0'),'&#124;&nbsp;',		
							LPAD(min(CASE
							 	 WHEN precios.CALCULO = 'A' and precios.USO = 1 THEN round((precios.COSTE_PAX / (1 - (precios.PORCENTAJE_NETO / 100))) / (1 - (precios.PORCENTAJE_COM / 100)),2)
								 WHEN precios.CALCULO = 'M' and precios.USO = 1 THEN precios.PVP_PAX
								 ELSE 'sin opcion'
							END), 7, 0),'&#124;&nbsp;',
							
							LPAD(min(CASE
							 	 WHEN precios.CALCULO = 'A' and precios.USO = 2 THEN round((precios.COSTE_PAX / (1 - (precios.PORCENTAJE_NETO / 100))) / (1 - (precios.PORCENTAJE_COM / 100)),2)
								 WHEN precios.CALCULO = 'M' and precios.USO = 2 THEN precios.PVP_PAX
								 ELSE 'sin opcion'
							END), 7, 0),'&#124;&nbsp;',
							
							LPAD(min(CASE
							 	 WHEN precios.CALCULO = 'A' and precios.USO = 3 THEN round((precios.COSTE_PAX / (1 - (precios.PORCENTAJE_NETO / 100))) / (1 - (precios.PORCENTAJE_COM / 100)),2)
								 WHEN precios.CALCULO = 'M' and precios.USO = 3 THEN precios.PVP_PAX
								 ELSE 'sin opcion'
							END), 7, 0),'&#124;&nbsp;',
							
							LPAD(min(CASE
							 	 WHEN precios.CALCULO = 'A' and precios.USO = 4 THEN round((precios.COSTE_PAX / (1 - (precios.PORCENTAJE_NETO / 100))) / (1 - (precios.PORCENTAJE_COM / 100)),2)
								 WHEN precios.CALCULO = 'M' and precios.USO = 4 THEN precios.PVP_PAX
								 ELSE 'sin opcion'
							END), 7, 0),'&#124;&nbsp;',
						  LPAD(min(temporadas.SPTO_AD), 3, 0),'&#124;&nbsp;',
						  LPAD(min(temporadas.SPTO_MP), 3, 0),'&#124;&nbsp;', 
						  LPAD(min(temporadas.SPTO_PC), 3, 0),'&#124;&nbsp;', 
						  LPAD(min(temporadas.SPTO_TI), 3, 0),'&#124;') nombre
					FROM
					  hit_alojamientos_acuerdos acuerdos,
					  hit_alojamientos_periodos periodos,
					  hit_alojamientos_temporadas temporadas,
					  hit_alojamientos_precios precios,
					  hit_alojamientos alojamientos,
					  hit_provincias provincias,
					  hit_ciudades ciudades,
					  hit_paises paises,
					  hit_categorias categorias,
					  hit_situacion situacion,
					  hit_habitaciones habitaciones,
					  hit_habitaciones_car habitaciones_car
					where
					  acuerdos.ID = alojamientos.ID
					  and acuerdos.ID = periodos.ID
					  and acuerdos.ACUERDO = periodos.ACUERDO
					  and '2013-08-01' between periodos.FECHA_DESDE and periodos.FECHA_HASTA
					  and periodos.ID = temporadas.ID
					  and periodos.ACUERDO = temporadas.ACUERDO
					  and periodos.TEMPORADA = temporadas.TEMPORADA
					  and acuerdos.ID = precios.ID
					  and acuerdos.ACUERDO = precios.ACUERDO
					  and temporadas.TEMPORADA = precios.TEMPORADA
					  and alojamientos.CIUDAD = ciudades.CODIGO
					  and ciudades.PROVINCIA = provincias.CODIGO
					  and ciudades.PAIS = paises.CODIGO
					  and alojamientos.CATEGORIA = categorias.CODIGO
					  and alojamientos.SITUACION = situacion.CODIGO
					  and precios.HABITACION = habitaciones.CODIGO
					  and precios.CARACTERISTICA = habitaciones_car.CODIGO
					  and acuerdos.tipo = 'O'
					  and '".date("Y-m-d",strtotime($fecha_desde))."' between periodos.fecha_desde and periodos.fecha_hasta
					  ".$CADENA_BUSCAR.
					  " group by paises.NOMBRE, provincias.NOMBRE, ciudades.NOMBRE, alojamientos.NOMBRE, 
						 habitaciones.NOMBRE, habitaciones_car.NOMBRE) resumen");

			
		}		
	}else{

			$resultado =$conexion->query("select '' clave, '' nombre from dual");

	}

	return $resultado;
		
}

$results = getData($_POST['fecha_desde'],$_POST['fecha_hasta'],$_POST['alojamiento'],$_POST['consulta']);
//print_r($results);
$agencias = array();
if(sizeof($results) == 0){
	echo "No resultados";
}else{
		echo "<select name='busca_alojamiento' style=' width:785px; font-family: monospace;' onChange='seleccionar(0)'>";

		echo "<option value=''>Seleccione alojamiento, teclee noches y uso y pulse 'Grabar Alojamiento'</option>";

		echo "<option value=''>&nbsp;</option>";

		echo "<option value=''>PAIS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PROV&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CIUDAD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ALOJAMIENTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CAT&nbsp;&nbsp;&nbsp;HABIT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CARACT&nbsp;&nbsp;&nbsp;&nbsp;USO&nbsp;DISP&nbsp;&nbsp;FECHA IN&nbsp;&nbsp;NOCHES&nbsp;&nbsp;IND&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DBL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TPL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;QPL&nbsp;&nbsp;&nbsp;&nbsp;AD&nbsp;&nbsp;&nbsp;MP&nbsp;&nbsp;&nbsp;PC&nbsp;&nbsp;&nbsp;TI</option>";

		echo "<option value=''>&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;&#9135;</option>";

		for ($i = 0; $i <= $results->num_rows; $i++) {
			$results->data_seek($i);
			$fila = $results->fetch_assoc();
			//echo $fila['clave']." X ".$fila['nombre']."<br/><br/>";
			echo "<option value='".$fila['clave']."'>".$fila['nombre']."</option>";
		}

		echo "</select>";

		echo "&nbsp;Uso<input type= 'text' name='nuevo_uso' size='2'/>";

		echo "&nbsp;Regimen<input type= 'text' name='nuevo_regimen' size='2'/>";

		echo "&nbsp;Noches<input type= 'text' name='nuevo_numero_noches' size='2'/>";

		echo "&nbsp;N.Hab<input type= 'text' name='nuevo_cantidad_habitaciones' size='2'/>";

		echo "&nbsp;<input type='button' value='Grabar' onClick='NuevoReserva_Alojamientos()'/>";

}
?>
