<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/producto_cuadros.cls.php';
	require 'clases/producto_cuadros_alojamientos.cls.php';

	session_start();

	$usuario = $_SESSION['usuario'];
	$nombre =  $_SESSION['nombre'];
	$conexion = conexion_hit();

	/*echo('<pre>');
	print_r($usuario);
	echo('-');
	print_r($nombre);
	echo('</pre>');*/

	/*echo('<pre>');
	print_r($_POST);
	echo('</pre>');*/

	/*echo('<pre>');
	print_r($_GET);
	echo('</pre>');*/

	$parametros = $_POST;
	$parametrosg = $_GET;



			//VARIABLES PARA LOS DATOS GENERALES
			$recuperacodigo = '';
			$recuperacuadro = '';
			$recuperaproducto = '';
			$recuperatipo = '';
			$recuperanombre_cuadro = '';
			$recuperasituacion = '';
			$recuperadestino_cuadro = '';
			$recuperadestino_cuadro2 = '';
			$recuperaduracion = '';
			$recuperadias_operacion = '';
			$recuperafrecuencia = '';
			$recuperaprimera_salida = '';
			$recuperaultima_salida = '';
			$recuperadivisa = '';
			$recuperaredondeo = '';
			$recuperacodigo_administrativo = '';
			$recuperamargen_transportes = '';
			$recuperaocupacion_transportes = '';
			$recuperamargen_alojamientos = '';
			$recuperamargen_alojamientos_interfaz_1 = '';
			$recuperamargen_servicios = '';
			$recuperaventa = '';
			$recuperaminorista_margen_transportes = '';
			$recuperaminorista_ocupacion_transportes = '';
			$recuperaminorista_margen_alojamientos = '';
			$recuperaminorista_margen_alojamientos_interfaz_1 = '';
			$recuperaminorista_margen_servicios = '';

			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			$insertaFolleto = '';

			//VARIABLES PARA LAS CONDICIONES
			$recuperapaquete = ''; 
			$recuperanumero = ''; 
			$recuperaalojamiento = ''; 
			$recuperaregimen = ''; 
			$recuperaacuerdo = ''; 
			$recuperahabitacion = ''; 
			$recuperacaracteristica = ''; 
			$recuperauso = ''; 
			$recuperadia = ''; 
			$recuperanoches = ''; 
			$recuperasituacion = ''; 
			$mensaje1_alojamientos = '';
			$mensaje2_alojamientos = '';
			$error_alojamientos = '';

			//VARIABLES PARA LOS PRECIOS
			$recuperaclave_calendario = ''; 
			$recuperaclave_paquete = ''; 
			$recuperaprecio = ''; 
			$recuperaspto_individual = ''; 
			$recuperaspto_noche_extra = ''; 
			$mensaje1_alojamientos_precios = '';
			$mensaje2_alojamientos_precios = '';
			$error_alojamientos_precios = '';

//echo($parametrosg['codigo']."-".$parametrosg['clave']."-".$parametrosg['display_cabecera']);


//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//CONTROLAMOS LAS VARIABLES NECESARIAS PARA LLAMAR A LA CLASE RESERVAS DEPENDIENDO DE SI ACABAMOS DE ENTRAR EN LA PANTALLA
	//O YA HEMOS REALIZADO ALGUNA PETICION Y/O VOLVEMOS DE OTRA PANTALLA
//--------------------------------------------------------------------------------------------------------------------------------
	if(count($_POST) != 0){
		$filadesde = $parametros['filadesde'];
		$filadesde_alojamientos = $parametros['filadesde_alojamientos'];
		$filadesde_alojamientos_precios = $parametros['filadesde_alojamientos_precios'];

		//esto es por si venimos de otra pantalla
		if($parametros['buscar_codigo'] == null and $parametros['buscar_cuadro'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_codigo'] = $parametrosg['codigo'];
				$parametros['buscar_cuadro'] = $parametrosg['clave'];

			}
		}
	}else{
		//SI ACABAMOS DE ENTRAR EN LA PANTALLA
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LAS RESERVAS
		//echo($parametrosg['display_cabecera']);
		$filadesde = 1;
		$filadesde_alojamientos = 1;
		$filadesde_alojamientos_precios = 1;
		if(count($_GET) != 0 and ($parametrosg['codigo'] != null or $parametrosg['clave'] != null)){
				$parametros['buscar_codigo'] = $parametrosg['codigo'];
				$parametros['buscar_cuadro'] = $parametrosg['clave'];
				$parametros['seccion_cabecera_cuadro_display'] = $parametrosg['display_cabecera'];
				$parametros['buscar_alojamiento'] = '';
				$parametros['buscar_paquete'] = '';
				$parametros['buscar_paquete_detalle'] = '';
				$parametros['buscar_fecha'] = '';

		}else{
			$parametros['buscar_codigo'] = 0;
			$parametros['buscar_cuadro'] = 0;
			$parametros['buscar_alojamiento'] = '';
			$parametros['buscar_paquete'] = '';
			$parametros['buscar_paquete_detalle'] = '';
			$parametros['buscar_fecha'] = '';
			$parametros['seccion_cabecera_cuadro_display'] = 'block';
		}
	}

//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//LLAMADA A LA CLASE GENERAL PARA TODAS LAS ACCIONES
//--------------------------------------------------------------------------------------------------------------------------------
	$ClaseCuadros = new clsCuadros($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_cuadro']);
	$ClaseAlojamientos = new clsCuadros_alojamientos($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_cuadro']);

   //TAMBIEN LLAMAMOS A LA CLASE GENERAL PARA CARGAR LOS COMBOS DE LA PANTALLA
	$combos = new clsGeneral($conexion);

	if(count($_POST) != 0){


		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'C' and $parametros['ir_a'] != 'CA' and $parametros['ir_a'] != 'CO' and $parametros['ir_a'] != 'AE' and $parametros['ir_a'] != 'AL' and $parametros['ir_a'] != 'AC' and $parametros['ir_a'] != 'SE' and $parametros['ir_a'] != 'PR' and $parametros['ir_a'] != 'TE' and $parametros['ir_a'] != 'IM'){

			//------------------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DEL CUADRO---------------------------
			//------------------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonselec = $ClaseCuadros->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$modificaCuadros = $ClaseCuadros->Modificar($parametros['clave'.$num_fila],$parametros['folleto'.$num_fila],$parametros['cuadro'.$num_fila],$parametros['producto'.$num_fila], $parametros['tipo_cuadro'.$num_fila], $parametros['nombre_cuadro'.$num_fila],$parametros['destino_cuadro'.$num_fila],$parametros['destino_cuadro2'.$num_fila],$parametros['situacion'.$num_fila],$parametros['duracion'.$num_fila],$parametros['dias_operacion'.$num_fila],$parametros['frecuencia'.$num_fila],$parametros['primera_salida'.$num_fila],$parametros['ultima_salida'.$num_fila],$parametros['divisa'.$num_fila],$parametros['redondeo'.$num_fila],$parametros['codigo_administrativo'.$num_fila],$parametros['margen_transportes'.$num_fila],$parametros['ocupacion_transportes'.$num_fila],$parametros['margen_alojamientos'.$num_fila],$parametros['margen_alojamientos_interfaz_1'.$num_fila],$parametros['margen_servicios'.$num_fila],$parametros['venta'.$num_fila],$parametros['minorista_margen_transportes'.$num_fila],$parametros['minorista_ocupacion_transportes'.$num_fila],$parametros['minorista_margen_alojamientos'.$num_fila],$parametros['minorista_margen_alojamientos_interfaz_1'.$num_fila],$parametros['minorista_margen_servicios'.$num_fila]);
							if($modificaCuadros == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaCuadros;
							}
						}else{
							$borraCuadros = $ClaseCuadros->Borrar($parametros['clave'.$num_fila]);
							if($borraCuadros == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraCuadros;
							}
						}
					}
				}
				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				$num_filas->close();

			}

			if($parametros['grabar_registro'] == 'S'){

				//AÑADIR REGISTROS
				$Ntransacciones = 0;				

				$insertaFolletos = $ClaseCuadros->Insertar($parametros['folleto0'],$parametros['cuadro0'],$parametros['producto0'],$parametros['tipo_cuadro0'],$parametros['nombre_cuadro0'],$parametros['destino_cuadro0'],$parametros['destino_cuadro20'],$parametros['situacion0'],$parametros['duracion0'],$parametros['dias_operacion0'],$parametros['frecuencia0'],$parametros['primera_salida0'],$parametros['ultima_salida0'],$parametros['divisa0'],$parametros['redondeo0'],$parametros['codigo_administrativo0'],$parametros['margen_transportes0'],$parametros['ocupacion_transportes0'],$parametros['margen_alojamientos0'],$parametros['margen_alojamientos_interfaz_10'],$parametros['margen_servicios0'], $parametros['venta0'], $parametros['minorista_margen_transportes0'], $parametros['minorista_ocupacion_transportes0'],$parametros['minorista_margen_alojamientos0'],$parametros['minorista_margen_alojamientos_interfaz_10'],$parametros['minorista_margen_servicios0']);

				if($insertaFolletos == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_codigo'] = $parametros['folleto0'];
					$parametros['buscar_cuadro'] = $parametros['clave0'];
				}else{
					$error = $insertaFolletos;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					$recuperafolleto = $parametros['folleto0'];
					$recuperacuadro = $parametros['cuadro0'];
					$recuperaproducto = $parametros['producto0'];
					$recuperatipo = $parametros['tipo0'];
					$recuperanombre_cuadro = $parametros['nombre_cuadro0'];
					$recuperadestino_cuadro = $parametros['destino_cuadro0'];
					$recuperadestino_cuadro2 = $parametros['destino_cuadro20'];
					$recuperasituacion = $parametros['situacion0'];
					$recuperaduracion = $parametros['duracion0'];
					$recuperadias_operacion = $parametros['dias_operacion0'];
					$recuperafrecuencia = $parametros['frecuencia0'];
					$recuperaprimera_salida = $parametros['primera_salida0'];
					$recuperaultima_salida = $parametros['ultima_salida0'];
					$recuperadivisa = $parametros['divisa0'];
					$recuperaredondeo = $parametros['redondeo0'];
					$recuperacodigo_administrativo = $parametros['codigo_administrativo0'];
					$recuperamargen_transportes = $parametros['margen_transportes0'];
					$recuperaocupacion_transportes = $parametros['ocupacion_transportes0'];
					$recuperamargen_alojamientos = $parametros['margen_alojamientos0'];
					$recuperamargen_alojamientos_interfaz_1 = $parametros['margen_alojamientos_interfaz_10'];
					$recuperamargen_servicios = $parametros['margen_servicios0'];
					$recuperaventa = $parametros['venta0'];
					$recuperaminorista_margen_transportes = $parametros['minorista_margen_transportes0'];
					$recuperaminorista_ocupacion_transportes = $parametros['minorista_ocupacion_transportes0'];
					$recuperaminorista_margen_alojamientos = $parametros['minorista_margen_alojamientos0'];
					$recuperaminorista_margen_alojamientos_interfaz_1 = $parametros['minorista_margen_alojamientos_interfaz_10'];
					$recuperaminorista_margen_servicios = $parametros['minorista_margen_servicios0'];

				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}


			//Llamada a la clase especifica de la pantalla
			$sCuadros = $ClaseCuadros->Cargar($recuperacodigo,$recuperacuadro,$recuperaproducto,$recuperatipo,$recuperanombre_cuadro,$recuperadestino_cuadro, $recuperadestino_cuadro2,$recuperasituacion,$recuperaduracion,$recuperadias_operacion,$recuperafrecuencia,$recuperaprimera_salida,$recuperaultima_salida,$recuperadivisa,$recuperaredondeo,$recuperacodigo_administrativo,$recuperamargen_transportes,$recuperaocupacion_transportes,$recuperamargen_alojamientos,$recuperamargen_alojamientos_interfaz_1,$recuperamargen_servicios, $recuperaventa,$recuperaminorista_margen_transportes,$recuperaminorista_ocupacion_transportes,$recuperaminorista_margen_alojamientos,$recuperaminorista_margen_alojamientos_interfaz_1,$recuperaminorista_margen_servicios);
			$sComboSelectCuadros = $ClaseCuadros->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$comboFolletos = $combos->Cargar_combo_Folletos();
			$comboCuadros = $combos->Cargar_combo_Cuadros($parametros['buscar_codigo']);
			$comboProductos = $combos->Cargar_combo_Productos();
			$comboTipoCuadros = $combos->Cargar_combo_Tipo_Cuadros();
			$comboSituacionCuadros = $combos->Cargar_combo_Situacion_Cuadros();
			$comboFrecuenciaSalidas = $combos->Cargar_combo_Frecuencia_Salidas();
			$comboDivisas = $combos->Cargar_combo_Divisas();
			$comboDestinos = $combos->Cargar_combo_Destinos();
			$comboVenta = $combos->Cargar_Combo_Tipo_Venta();
//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------



	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS ALOJAMIENTOS -------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_alojamientos = $parametros['filadesde_alojamientos'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonselec_alojamientos = $ClaseAlojamientos->Botones_selector_alojamientos($filadesde_alojamientos, $parametros['botonSelector_2nivel']);
				$filadesde_alojamientos = $botonselec_alojamientos;
			}

			//Llamada a la clase especifica de la pantalla
			$sAlojamientos = $ClaseAlojamientos->Cargar_alojamientos($sCuadros[0]['clave'], $filadesde_alojamientos, $parametros['buscar_alojamiento']);
			//lineas visualizadas
			$vueltas = count($sAlojamientos);

			if($parametros['actuar_2nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_alojamientos = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							
							$modificaAlojamientos = $ClaseAlojamientos->Modificar_alojamientos($sCuadros[0]['clave'], $parametros['paquete'.$num_fila], $parametros['numero'.$num_fila], $parametros['alojamiento'.$num_fila], $parametros['regimen'.$num_fila], $parametros['acuerdo'.$num_fila], $parametros['habitacion'.$num_fila], $parametros['caracteristica'.$num_fila], $parametros['uso'.$num_fila], $parametros['dia'.$num_fila],$parametros['noches'.$num_fila], $parametros['situacion'.$num_fila],
							$parametros['paquete_old'.$num_fila], $parametros['numero_old'.$num_fila], $parametros['alojamiento_old'.$num_fila], $parametros['regimen_old'.$num_fila]);
							if($modificaAlojamientos == 'OK'){
								$Ntransacciones_alojamientos++;
							}else{
								$error_alojamientos = $modificaAlojamientos;
							}

						}else{

							$borraAlojamientos = $ClaseAlojamientos->Borrar_alojamientos($sCuadros[0]['clave'], $parametros['paquete'.$num_fila], $parametros['numero'.$num_fila], $parametros['alojamiento'.$num_fila], $parametros['regimen'.$num_fila]);
							if($borraAlojamientos == 'OK'){
								$Ntransacciones_alojamientos++;
							}else{
								$error_alojamientos = $borraAlojamientos;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				/*$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){

						$insertaAlojamientos = $ClaseAlojamientos->Insertar_alojamientos($sCuadros[0]['clave'], $sCuadros[0]['folleto'], $sCuadros[0]['cuadro'], $parametros['Nuevopaquete'.$num_fila],$parametros['Nuevonumero'.$num_fila],$parametros['Nuevoalojamiento'.$num_fila],$parametros['Nuevoregimen'.$num_fila],$parametros['Nuevoacuerdo'.$num_fila], $parametros['Nuevohabitacion'.$num_fila], $parametros['Nuevocaracteristica'.$num_fila], $parametros['Nuevouso'.$num_fila], $parametros['Nuevodia'.$num_fila], $parametros['Nuevonoches'.$num_fila], $parametros['Nuevosituacion'.$num_fila]);
						if($insertaAlojamientos == 'OK'){
							$Ntransacciones_alojamientos++;
						}else{
							$error_alojamientos = $insertaAlojamientos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.

							$recuperapaquete = $parametros['Nuevopaquete'.$num_fila];  
							$recuperanumero = $parametros['Nuevonumero'.$num_fila]; 
							$recuperaalojamiento = $parametros['Nuevoalojamiento'.$num_fila];  
							$recuperaregimen = $parametros['Nuevoregimen'.$num_fila];  
							$recuperaacuerdo = $parametros['Nuevoacuerdo'.$num_fila]; 
							$recuperahabitacion = $parametros['Nuevohabitacion'.$num_fila]; 
							$recuperacaracteristica = $parametros['Nuevocaracteristica'.$num_fila]; 
							$recuperauso = $parametros['Nuevouso'.$num_fila]; 
							$recuperadia = $parametros['Nuevodia'.$num_fila]; 
							$recuperanoches = $parametros['Nuevonoches'.$num_fila]; 
							$recuperasituacion = $parametros['Nuevosituacion'.$num_fila]; 

						}
					}
				}*/

				//Mostramos mensajes y posibles errores
				$mensaje1_alojamientos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_alojamientos."</b></font></div>";
				if($error_alojamientos != ''){
					$mensaje2_alojamientos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_alojamientos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();

			}elseif($parametros['actuar_2nivel'] == 'C'){
				$respuesta_error = '';
				$calcular = "CALL `PRODUCTO_CALCULA_ALOJAMIENTOS`('".$parametros['folleto0']."', '".$parametros['cuadro0']."')";
				$resultadocalcular =$conexion->query($calcular);
					//echo($expandir);
				if ($resultadocalcular == FALSE){
					$respuesta_error = 'No se han podido calcular los alojamientos. '.$conexion->error;
				}else{
					$respuesta = 'Precios calculados';

				}
				//Mostramos mensajes y posibles errores
				$mensaje1_alojamientos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$respuesta."</b></font></div>";
				if($respuesta_error != ''){
					$mensaje2_alojamientos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$respuesta_error."</b></font></div>";
				}				
			}elseif($parametros['actuar_2nivel'] == 'R'){
				$respuesta_error = '';
				$respuesta = '';
				$calcular = "CALL `PRODUCTO_RECALCULA_ALOJAMIENTOS`('".$parametros['folleto0']."', '".$parametros['cuadro0']."')";
				$resultadocalcular =$conexion->query($calcular);
					//echo($expandir);
				if ($resultadocalcular == FALSE){
					$respuesta_error = 'No se han podido calcular los alojamientos. '.$conexion->error;
				}else{
					$respuesta = 'Precios calculados';

				}
				//Mostramos mensajes y posibles errores
				$mensaje1_alojamientos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$respuesta."</b></font></div>";
				if($respuesta_error != ''){
					$mensaje2_alojamientos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$respuesta_error."</b></font></div>";
				}				
			}elseif($parametros['actuar_2nivel'] == 'B'){
				$respuesta_error = '';
				$respuesta = '';
				$calcular = "CALL `PRODUCTO_BORRA_ALOJAMIENTOS_CUADRO`('".$parametros['folleto0']."', '".$parametros['cuadro0']."')";
				$resultadocalcular =$conexion->query($calcular);
					//echo($expandir);
				if ($resultadocalcular == FALSE){
					$respuesta_error = 'No se han podido borrar los alojamientos. '.$conexion->error;
				}else{
					$respuesta = 'Alojamientos borrados';

				}
				//Mostramos mensajes y posibles errores
				$mensaje1_alojamientos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$respuesta."</b></font></div>";
				if($respuesta_error != ''){
					$mensaje2_alojamientos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$respuesta_error."</b></font></div>";
				}				
			}

			//Llamada a la clase especifica de la pantalla
			$sAlojamientos = $ClaseAlojamientos->Cargar_alojamientos($sCuadros[0]['clave'], $filadesde_alojamientos, $parametros['buscar_alojamiento']);	
			$sComboSelectAlojamientos = $ClaseAlojamientos->Cargar_combo_selector_alojamientos($sCuadros[0]['clave'], $filadesde_alojamientos, $parametros['buscar_alojamiento']);
			//$sAlojamientos_nuevos = $ClaseAlojamientos->Cargar_lineas_nuevas_alojamientos();	

			//Llamada a la clase general de combos
			$comboAlojamientos = $combos->Cargar_combo_Alojamientos_Destino($sCuadros[0]['destino_cuadro']);
			//$comboRegimen = $combos->Cargar_combo_Regimen();
			//$comboHabitaciones = $combos->Cargar_combo_Habitaciones();
			//$comboHabitaciones_car = $combos->Cargar_combo_Habitaciones_car();
			$comboSituacion_cuadros = $combos->Cargar_combo_Situacion_Cuadros();


	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS PRECIOS -------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_alojamientos_precios = $parametros['filadesde_alojamientos_precios'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_3nivel'] != 0){
				$botonselec_alojamientos_precios = $ClaseAlojamientos->Botones_selector_alojamientos_precios($filadesde_alojamientos, $parametros['botonSelector_3nivel']);
				$filadesde_alojamientos_precios = $botonselec_alojamientos_precios;
			}

			//Llamada a la clase especifica de la pantalla
			$sAlojamientos_precios = $ClaseAlojamientos->Cargar_alojamientos_precios($sCuadros[0]['clave'], $filadesde_alojamientos_precios, $parametros['buscar_paquete'], $parametros['buscar_fecha'],$parametros['buscar_paquete_detalle']);
			//lineas visualizadas
			$vueltas = count($sAlojamientos_precios);

			if($parametros['actuar_3nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_alojamientos_precios = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {

					if($parametros['selec_3nivel'.$num_fila] == 'S' || $parametros['borra_3nivel'.$num_fila] == 'S'){
						if($parametros['selec_3nivel'.$num_fila] == 'S'){
							
							$modificaAlojamientos_precios = $ClaseAlojamientos->Modificar_alojamientos_precios($sCuadros[0]['clave'], $parametros['clave_calendario'.$num_fila], $parametros['clave_paquete'.$num_fila], $parametros['precio'.$num_fila], $parametros['spto_individual'.$num_fila], $parametros['spto_noche_extra'.$num_fila], $parametros['clave_calendario_old'.$num_fila],$parametros['clave_paquete_old'.$num_fila]);
							if($modificaAlojamientos_precios == 'OK'){
								$Ntransacciones_alojamientos_precios++;
							}else{
								$error_alojamientos_precios = $modificaAlojamientos_precios;
							}

						}else{

							$borraAlojamientos_precios = $ClaseAlojamientos->Borrar_alojamientos_precios($sCuadros[0]['clave'], $parametros['clave_calendario'.$num_fila], $parametros['clave_paquete'.$num_fila]);
							if($borraAlojamientos_precios == 'OK'){
								$Ntransacciones_alojamientos_precios++;
							}else{
								$error_alojamientos_precios = $borraAlojamientos_precios;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				/*$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_3nivel'.$num_fila] == 'S'){

						$insertaAlojamientos_precios = $ClaseAlojamientos->Insertar_alojamientos_precios($sCuadros[0]['clave'], $parametros['Nuevoclave_calendario'.$num_fila], $parametros['Nuevoclave_paquete'.$num_fila], $parametros['Nuevoprecio'.$num_fila], $parametros['Nuevospto_individual'.$num_fila], $parametros['Nuevospto_noche_extra'.$num_fila]);
						if($insertaAlojamientos_precios == 'OK'){
							$Ntransacciones_alojamientos_precios++;
						}else{
							$error_alojamientos_precios = $insertaAlojamientos_precios;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.

							$recuperaclave_calendario = $parametros['Nuevoclave_calendario'.$num_fila]; 
							$recuperaclave_paquete = $parametros['Nuevoclave_paquete'.$num_fila];  
							$recuperaprecio = $parametros['Nuevoprecio'.$num_fila]; 
							$recuperaspto_individual = $parametros['Nuevospto_individual'.$num_fila];
							$recuperaspto_noche_extra = $parametros['Nuevospto_noche_extra'.$num_fila];
						}
					}
				}*/

				//Mostramos mensajes y posibles errores
				$mensaje1_alojamientos_precios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_alojamientos_precios."</b></font></div>";
				if($error_alojamientos_precios != ''){
					$mensaje2_alojamientos_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_alojamientos_precios."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();

			}elseif($parametros['actuar_3nivel'] == 'C'){
				$respuesta_error = '';
				$calcular = "CALL `PRODUCTO_CALCULA_PVP_ALOJAMIENTOS`('".$parametros['folleto0']."', '".$parametros['cuadro0']."', '".$parametros['margen_alojamientos0']."', '".$parametros['redondeo0']."')";
				$resultadocalcular =$conexion->query($calcular);
					//echo($expandir);
				if ($resultadocalcular == FALSE){
					$respuesta_error = 'No se han podido calcular los precios. '.$conexion->error;
				}else{
					$respuesta = 'Precios calculados';

				}
				//Mostramos mensajes y posibles errores
				$mensaje1_alojamientos_precios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$respuesta."</b></font></div>";
				if($respuesta_error != ''){
					$mensaje2_alojamientos_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$respuesta_error."</b></font></div>";
				}				
			}elseif($parametros['actuar_3nivel'] == 'R'){
				$respuesta_error = '';
				$calcular = "CALL `PRODUCTO_RECALCULA_PVP_ALOJAMIENTOS`('".$parametros['folleto0']."', '".$parametros['cuadro0']."', '".$parametros['margen_alojamientos0']."', '".$parametros['redondeo0']."')";
				$resultadocalcular =$conexion->query($calcular);
					//echo($expandir);
				if ($resultadocalcular == FALSE){
					$respuesta_error = 'No se han podido calcular los precios. '.$conexion->error;
				}else{
					$respuesta = 'Precios calculados';

				}
				//Mostramos mensajes y posibles errores
				$mensaje1_alojamientos_precios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$respuesta."</b></font></div>";
				if($respuesta_error != ''){
					$mensaje2_alojamientos_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$respuesta_error."</b></font></div>";
				}				
			}


			//Llamada a la clase especifica de la pantalla
			$sAlojamientos_precios = $ClaseAlojamientos->Cargar_alojamientos_precios($sCuadros[0]['clave'], $filadesde_alojamientos_precios, $parametros['buscar_paquete'], $parametros['buscar_fecha'],$parametros['buscar_paquete_detalle']);	
			$sComboSelectAlojamientos_precios = $ClaseAlojamientos->Cargar_combo_selector_alojamientos_precios($sCuadros[0]['clave'], $filadesde_alojamientos_precios, $parametros['buscar_paquete'], $parametros['buscar_fecha'],$parametros['buscar_paquete_detalle']);
			//$sAlojamientos_nuevos_precios = $ClaseAlojamientos->Cargar_lineas_nuevas_alojamientos_precios();	

			//Llamada a la clase general de combos
			//$comboCalendario = $combos->Cargar_combo_Cuadros_Calendario($sCuadros[0]['clave']);
			$comboPaquetes = $combos->Cargar_combo_Cuadros_Paquetes($sCuadros[0]['clave'], $parametros['buscar_paquete'], $parametros['buscar_paquete_detalle']);
			$comboPaquetes_agrupados = $combos->Cargar_combo_Cuadros_Paquetes_Agrupados($sCuadros[0]['clave']);

	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;



//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------
			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS CUADROS----------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('cabecera_cuadro', 'plantillas/Producto_cuadros_cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#30A1C7');
			$smarty->assign('grupo', '» PRODUCTO');
			$smarty->assign('subgrupo', '» CUADROS');
			$smarty->assign('formulario', '» ALOJAMIENTOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectCuadros);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('cuadros', $sCuadros);

			//Indicamos si hay que visualizar o no la seccion LA CABECERA
			$smarty->assign('seccion_cabecera_cuadro_display', $parametros['seccion_cabecera_cuadro_display']);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboCuadros', $comboCuadros);
			$smarty->assign('comboProductos', $comboProductos);
			$smarty->assign('comboTipoCuadros', $comboTipoCuadros);
			$smarty->assign('comboSituacionCuadros', $comboSituacionCuadros);
			$smarty->assign('comboFrecuenciaSalidas', $comboFrecuenciaSalidas);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboDestinos', $comboDestinos);
			$smarty->assign('comboVenta', $comboVenta);

			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_cuadro', $parametros['buscar_cuadro']);
//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS ALOJAMIENTOS------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_alojamientos', $filadesde_alojamientos);

			//Cargar combo selector
			$smarty->assign('combo_alojamientos', $sComboSelectAlojamientos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos', $sAlojamientos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			//$smarty->assign('comboRegimen', $comboRegimen);
			//$smarty->assign('comboHabitaciones', $comboHabitaciones);
			//$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car);
			$smarty->assign('comboSituacion_cuadros', $comboSituacion_cuadros);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('alojamientosnuevos', $sAlojamientos_nuevos);	


			$smarty->assign('recuperapaquete', $recuperapaquete);	
			$smarty->assign('recuperanumero', $recuperanumero);	
			$smarty->assign('recuperaalojamiento', $recuperaalojamiento);	
			$smarty->assign('recuperaregimen', $recuperaregimen);	
			$smarty->assign('recuperaacuerdo', $recuperaacuerdo);
			$smarty->assign('recuperahabitacion', $recuperahabitacion);
			$smarty->assign('recuperacaracteristica', $recuperacaracteristica);
			$smarty->assign('recuperauso', $recuperauso);
			$smarty->assign('recuperadia', $recuperadia);
			$smarty->assign('recuperanoches', $recuperanoches);
			$smarty->assign('recuperasituacion', $recuperasituacion);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_alojamientos', $mensaje1_alojamientos);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_alojamientos', $mensaje2_alojamientos);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_alojamiento', $parametros['buscar_alojamiento']);


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS PRECIOS----------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_alojamientos_precios', $filadesde_alojamientos_precios);

			//Cargar combo selector
			$smarty->assign('combo_alojamientos_precios', $sComboSelectAlojamientos_precios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos_precios', $sAlojamientos_precios);

			//Cargar combos de las lineas de la tabla
			//$smarty->assign('comboCalendarios', $comboCalendario);
			$smarty->assign('comboPaquetes', $comboPaquetes);
			$smarty->assign('comboPaquetes_agrupados', $comboPaquetes_agrupados);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('alojamientosnuevos_precios', $sAlojamientos_nuevos_precios);	

			$smarty->assign('recuperaclave_calendario', $recuperaclave_calendario);	
			$smarty->assign('recuperaclave_paquete', $recuperaclave_paquete);	
			$smarty->assign('recuperaprecio', $recuperaprecio);	
			$smarty->assign('recuperaspto_individual', $recuperaspto_individual);	
			$smarty->assign('recuperaspto_noche_extra', $recuperaspto_noche_extra);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_alojamientos_precios', $mensaje1_alojamientos_precios);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_alojamientos_precios', $mensaje2_alojamientos_precios);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_paquete', $parametros['buscar_paquete']);
			$smarty->assign('buscar_paquete_detalle', $parametros['buscar_paquete_detalle']);
			$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);

			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Producto_cuadros_alojamientos.html');


				/*$parametros['buscar_codigo'] = $parametrosg['codigo'];
				$parametros['buscar_cuadro'] = $parametrosg['clave'];
				$parametros['seccion_cabecera_cuadro_display'] = $parametrosg['display_cabecera'];*/


		}elseif($parametros['ir_a'] == 'CA'){
			echo($parametros['ir_a']);
			header("Location: Producto_cuadros_calendarios.php?codigo=".$parametros['buscar_codigo']."&clave=".$parametros['buscar_cuadro']."&display_cabecera=".$parametros['seccion_cabecera_cuadro_display']);

		}elseif($parametros['ir_a'] == 'CO'){

			header("Location: Producto_cuadros_condiciones.php?codigo=".$parametros['buscar_codigo']."&clave=".$parametros['buscar_cuadro']."&display_cabecera=".$parametros['seccion_cabecera_cuadro_display']);

		}elseif($parametros['ir_a'] == 'AE'){

			header("Location: Producto_cuadros_aereos.php?codigo=".$parametros['buscar_codigo']."&clave=".$parametros['buscar_cuadro']."&display_cabecera=".$parametros['seccion_cabecera_cuadro_display']);

		}elseif($parametros['ir_a'] == 'AL'){

			header("Location: Producto_cuadros_alojamientos.php?codigo=".$parametros['buscar_codigo']."&clave=".$parametros['buscar_cuadro']."&display_cabecera=".$parametros['seccion_cabecera_cuadro_display']);

		}elseif($parametros['ir_a'] == 'AC'){

			header("Location: Producto_cuadros_alojamientos_condiciones.php?codigo=".$parametros['buscar_codigo']."&clave=".$parametros['buscar_cuadro']."&display_cabecera=".$parametros['seccion_cabecera_cuadro_display']);

		}elseif($parametros['ir_a'] == 'SE'){

			header("Location: Producto_cuadros_servicios.php?codigo=".$parametros['buscar_codigo']."&clave=".$parametros['buscar_cuadro']."&display_cabecera=".$parametros['seccion_cabecera_cuadro_display']);

		}elseif($parametros['ir_a'] == 'PR'){

			header("Location: Producto_cuadros_precios.php?codigo=".$parametros['buscar_codigo']."&clave=".$parametros['buscar_cuadro']."&display_cabecera=".$parametros['seccion_cabecera_cuadro_display']);

		}elseif($parametros['ir_a'] == 'TE'){

			header("Location: Producto_cuadros_textos.php?codigo=".$parametros['buscar_codigo']."&clave=".$parametros['buscar_cuadro']."&display_cabecera=".$parametros['seccion_cabecera_cuadro_display']);

		}elseif($parametros['ir_a'] == 'IM'){

			header("Location: Producto_cuadros_imagenes.php?codigo=".$parametros['buscar_codigo']."&clave=".$parametros['buscar_cuadro']."&display_cabecera=".$parametros['seccion_cabecera_cuadro_display']);

		}elseif($parametros['ir_a'] == 'B'){

			//Llamar a al metodo de borrar cupos;

		}elseif($parametros['ir_a'] == 'C'){

			header("Location: Transportes_cupos.php?cia=".$parametros['cia0']."&acuerdo=".$parametros['acuerdo0']."&subacuerdo=".$parametros['subacuerdo0']);

		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LAS ALOJAMIENTOS_ACUERDOS


//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------

			//Llamada a la clase especifica de la pantalla
			$sCuadros = $ClaseCuadros->Cargar($recuperacodigo,$recuperacuadro,$recuperaproducto,$recuperatipo,$recuperanombre_cuadro,$recuperadestino_cuadro, $recuperadestino_cuadro2,$recuperasituacion,$recuperaduracion,$recuperadias_operacion,$recuperafrecuencia,$recuperaprimera_salida,$recuperaultima_salida,$recuperadivisa,$recuperaredondeo,$recuperacodigo_administrativo,$recuperamargen_transportes,$recuperaocupacion_transportes,$recuperamargen_alojamientos,$recuperamargen_alojamientos_interfaz_1,$recuperamargen_servicios, $recuperaventa,$recuperaminorista_margen_transportes,$recuperaminorista_ocupacion_transportes,$recuperaminorista_margen_alojamientos,$recuperaminorista_margen_alojamientos_interfaz_1,$recuperaminorista_margen_servicios);
			$sComboSelectCuadros = $ClaseCuadros->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$comboFolletos = $combos->Cargar_combo_Folletos();
			$comboCuadros = $combos->Cargar_combo_Cuadros($parametros['buscar_codigo']);
			$comboProductos = $combos->Cargar_combo_Productos();
			$comboTipoCuadros = $combos->Cargar_combo_Tipo_Cuadros();
			$comboSituacionCuadros = $combos->Cargar_combo_Situacion_Cuadros();
			$comboFrecuenciaSalidas = $combos->Cargar_combo_Frecuencia_Salidas();
			$comboDivisas = $combos->Cargar_combo_Divisas();
			$comboDestinos = $combos->Cargar_combo_Destinos();
			$comboVenta = $combos->Cargar_Combo_Tipo_Venta();


			//Establecer plantilla smarty
			$smarty = new Smarty;

			//--------------------------------------------------------
			//----VISUALIZAR PARTE DE CUADROS -----------------------
			//--------------------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('cabecera_cuadro', 'plantillas/Producto_cuadros_cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#30A1C7');
			$smarty->assign('grupo', '» PRODUCTO');
			$smarty->assign('subgrupo', '» CUADROS');
			$smarty->assign('formulario', '» ALOJAMIENTOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectCuadros);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('cuadros', $sCuadros);

			//Indicamos si hay que visualizar o no la seccion LA CABECERA
			//$smarty->assign('seccion_cabecera_cuadro_display', $parametros['seccion_cabecera_cuadro_display']);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboCuadros', $comboCuadros);
			$smarty->assign('comboProductos', $comboProductos);
			$smarty->assign('comboTipoCuadros', $comboTipoCuadros);
			$smarty->assign('comboSituacionCuadros', $comboSituacionCuadros);
			$smarty->assign('comboFrecuenciaSalidas', $comboFrecuenciaSalidas);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboDestinos', $comboDestinos);
			$smarty->assign('comboVenta', $comboVenta);

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', '');

			//Si venimos de otra pntalla del mantenimiejto de cuadros
			//Cargar campos de busqueda de la tabla tecleados por el usuario
			if(count($_GET) != 0 and ($parametrosg['codigo'] != null or $parametrosg['clave'] != null)){
				$smarty->assign('buscar_codigo', $parametrosg['codigo']);
				$smarty->assign('buscar_cuadro', $parametrosg['clave']);
				$smarty->assign('seccion_cabecera_cuadro_display', $parametros['seccion_cabecera_cuadro_display']);
			}else{
				$smarty->assign('buscar_codigo', '');
				$smarty->assign('buscar_cuadro', '');
				$smarty->assign('seccion_cabecera_cuadro_display', 'block');

			}

//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------


		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE ALOJAMIENTOS

			//Llamada a la clase especifica de la pantalla
			$sAlojamientos = $ClaseAlojamientos->Cargar_alojamientos($sCuadros[0]['clave'], $filadesde_alojamientos, $parametros['buscar_alojamiento']);	
			$sComboSelectAlojamientos = $ClaseAlojamientos->Cargar_combo_selector_alojamientos($sCuadros[0]['clave'], $filadesde_alojamientos, $parametros['buscar_alojamiento']);
			//$sAlojamientos_nuevos = $ClaseAlojamientos->Cargar_lineas_nuevas_alojamientos();	

			//Llamada a la clase general de combos
			$comboAlojamientos = $combos->Cargar_combo_Alojamientos_Destino($sCuadros[0]['destino_cuadro']);
			//$comboRegimen = $combos->Cargar_combo_Regimen();
			//$comboHabitaciones = $combos->Cargar_combo_Habitaciones();
			//$comboHabitaciones_car = $combos->Cargar_combo_Habitaciones_car();
			$comboSituacion_cuadros = $combos->Cargar_combo_Situacion_Cuadros();


			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_alojamientos', $filadesde_alojamientos);

			//Cargar combo selector
			$smarty->assign('combo_alojamientos', $sComboSelectAlojamientos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos', $sAlojamientos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			//$smarty->assign('comboRegimen', $comboRegimen);
			//$smarty->assign('comboHabitaciones', $comboHabitaciones);
			//$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car);
			$smarty->assign('comboSituacion_cuadros', $comboSituacion_cuadros);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('alojamientosnuevos', $sAlojamientos_nuevos);	


			$smarty->assign('recuperapaquete', $recuperapaquete);	
			$smarty->assign('recuperanumero', $recuperanumero);	
			$smarty->assign('recuperaalojamiento', $recuperaalojamiento);	
			$smarty->assign('recuperaregimen', $recuperaregimen);	
			$smarty->assign('recuperaacuerdo', $recuperaacuerdo);
			$smarty->assign('recuperahabitacion', $recuperahabitacion);
			$smarty->assign('recuperacaracteristica', $recuperacaracteristica);
			$smarty->assign('recuperauso', $recuperauso);
			$smarty->assign('recuperadia', $recuperadia);
			$smarty->assign('recuperanoches', $recuperanoches);
			$smarty->assign('recuperasituacion', $recuperasituacion);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_alojamientos', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_alojamientos', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_alojamiento', '');


		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE PRECIOS

			//Llamada a la clase especifica de la pantalla
			$sAlojamientos_precios = $ClaseAlojamientos->Cargar_alojamientos_precios($sCuadros[0]['clave'], $filadesde_alojamientos_precios, $parametros['buscar_paquete'], $parametros['buscar_fecha'],$parametros['buscar_paquete_detalle']);	
			$sComboSelectAlojamientos_precios = $ClaseAlojamientos->Cargar_combo_selector_alojamientos_precios($sCuadros[0]['clave'], $filadesde_alojamientos_precios, $parametros['buscar_paquete'], $parametros['buscar_fecha'],$parametros['buscar_paquete_detalle']);
			//$sAlojamientos_nuevos_precios = $ClaseAlojamientos->Cargar_lineas_nuevas_alojamientos_precios();	

			//Llamada a la clase general de combos
			//$comboCalendario = $combos->Cargar_combo_Cuadros_Calendario($sCuadros[0]['clave']);
			$comboPaquetes = $combos->Cargar_combo_Cuadros_Paquetes($sCuadros[0]['clave'], $parametros['buscar_paquete'], $parametros['buscar_paquete_detalle']);
			$comboPaquetes_agrupados = $combos->Cargar_combo_Cuadros_Paquetes_Agrupados($sCuadros[0]['clave']);


			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_alojamientos_precios', $filadesde_alojamientos_precios);

			//Cargar combo selector
			$smarty->assign('combo_alojamientos_precios', $sComboSelectAlojamientos_precios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos_precios', $sAlojamientos_precios);

			//Cargar combos de las lineas de la tabla
			//$smarty->assign('comboCalendarios', $comboCalendario);
			$smarty->assign('comboPaquetes', $comboPaquetes);
			$smarty->assign('comboPaquetes_agrupados', $comboPaquetes_agrupados);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('alojamientosnuevos_precios', $sAlojamientos_nuevos_precios);	

			$smarty->assign('recuperaclave_calendario', $recuperaclave_calendario);	
			$smarty->assign('recuperaclave_paquete', $recuperaclave_paquete);	
			$smarty->assign('recuperaprecio', $recuperaprecio);	
			$smarty->assign('recuperaspto_individual', $recuperaspto_individual);	
			$smarty->assign('recuperaspto_noche_extra', $recuperaspto_noche_extra);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_alojamientos_precios', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_alojamientos_precios', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_paquete', '');
			$smarty->assign('buscar_paquete_detalle', '');
			$smarty->assign('buscar_fecha', '');



		//Visualizar plantilla
		$smarty->display('plantillas/Producto_cuadros_alojamientos.html');
	}

	$conexion->close();


?>

