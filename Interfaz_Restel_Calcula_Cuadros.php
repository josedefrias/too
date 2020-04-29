<?php

	include 'funciones_php/conexiones.php';
	require 'clases/alojamientos_acuerdos.cls.php';


	$conexion = conexion_hit();

	$calendarios_cargados = 0;
	$salidas_cargadas = 0;
	$salidas_no_cargadas = 0;
	$alojamientos_cargados = 0;
	$alojamientos_no_cargados = 0;
	$precios_alojamientos_cargados = 0;
	$precios_alojamientos_no_cargados = 0;


	$Usuario = 'jfrias';
	$buscar_interfaz = 'RESTEL';

	//AQUI NECESITAMSO LA QUERY PARA IR LLAMANDADO A TODO EL PROCEDIMIENTO CON CADA LINEA


	$resultado =$conexion->query("select folleto, cuadro, margen_alojamientos, redondeo from hit_producto_cuadros 
						where folleto = 'ESP16'");

	for ($k = 0; $k <= $resultado->num_rows; $k++) {
		$resultado->data_seek($k);
		$fila = $resultado->fetch_assoc();


		$folleto = $fila['folleto'];
		$cuadro = $fila['cuadro'];
		$margen_alojamientos = $fila['margen_alojamientos'];
		$redondeo = $fila['redondeo'];

		//INSERTA CALENDARIOS
		$query = "INSERT hit_producto_cuadros_calendarios (CLAVE_CUADRO, FOLLETO, CUADRO, FECHA_DESDE, FECHA_HASTA, NOMBRE, COLOR)
				select CLAVE, FOLLETO, CUADRO, PRIMERA_SALIDA, ULTIMA_SALIDA, NULL, NULL
				from hit_producto_cuadros where folleto = '".$folleto."' and cuadro = '".$cuadro."'";
		$resultado_cod=$conexion->query($query);


		//INSERTA CALENDARIOS CIUDADES
		$query = "INSERT hit_producto_cuadros_calendarios_ciudades (CLAVE_CUADRO, FOLLETO, CUADRO, CIUDAD, FECHA_DESDE, FECHA_HASTA, DIAS_SEMANA, DURACIONES)
				select CLAVE, FOLLETO, CUADRO, 'BLN', PRIMERA_SALIDA, ULTIMA_SALIDA, 'LMXJVSD', '2,3,4,5,6,7,8,9,10,11,12,13,14,15'
				from hit_producto_cuadros where folleto = '".$folleto."' and cuadro = '".$cuadro."'";
		$resultado_cod=$conexion->query($query);


		//EXPANDE SALIDAS
		$actualizar = "CALL `PRODUCTO_EXPANDE_SALIDAS`('".$folleto."', '".$cuadro."')";
		$resultadoactualizar =$conexion->query($actualizar);
			//echo($expandir);
		if ($resultadoactualizar == FALSE){
			$salidas_no_cargadas++;
		}else{
			$salidas_cargadas++;
		}

		//CALCULA ALOJAMIENTOS
		$calcular_alojamientos = "CALL `PRODUCTO_CALCULA_ALOJAMIENTOS`('".$folleto."', '".$cuadro."')";
		$resultadocalcular_alojamientos =$conexion->query($calcular_alojamientos);
			//echo($expandir);
		if ($resultadocalcular_alojamientos == FALSE){
			$alojamientos_no_cargados++;
		}else{
			$alojamientos_cargados++;
		}


		//CALCULA PRECIOS DE ALOJAMIENTOS
		$calcular_precios_alojamientos = "CALL `PRODUCTO_CALCULA_PVP_ALOJAMIENTOS_INTERFAZ`('".$folleto."', '".$cuadro."', '".$margen_alojamientos."', '".$redondeo."')";
		$resultadocalcular_precios_alojamientos =$conexion->query($calcular_precios_alojamientos);
			//echo($expandir);
		if ($resultadocalcular_precios_alojamientos == FALSE){
			$precios_alojamientos_no_cargados++;
		}else{
			$precios_alojamientos_cargados++;
		}
	

									
	}

	print("<br>Cuadros Calculados:".$k);
	print("<br>Salidas Cargadas:".$salidas_cargadas." - Salidas no Cargadas: ".$salidas_no_cargadas);
	print("<br>Alojamientos Cargados:".$alojamientos_cargados." - Alojamientos no Cargados: ".$alojamientos_no_cargados);
	print("<br>Precios de alojamientos Cargados:".$precios_alojamientos_cargados." - Precios de alojamientos no Cargados: ".$precios_alojamientos_no_cargados);


?>