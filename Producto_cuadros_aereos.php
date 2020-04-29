<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/producto_cuadros.cls.php';
	require 'clases/producto_cuadros_aereos.cls.php';

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
			$recuperaciudad = ''; 
			$recuperaopcion = ''; 
			$recuperanumero = ''; 
			$recuperacia = ''; 
			$recuperaacuerdo = ''; 
			$recuperasubacuerdo = ''; 
			$recuperaorigen = ''; 
			$recuperadestino= ''; 
			$recuperadia = ''; 
			$recuperatasas = ''; 
			$recuperatipo_trayecto = '';
			$mensaje1_aereos = '';
			$mensaje2_aereos = '';
			$error_aereos = '';

			//VARIABLES PARA LOS PRECIOS
			$recuperaclave_calendario = ''; 
			$recuperaclave_aereo = ''; 
			$recuperaprecio_1 = ''; 
			$recuperaclase_1 = ''; 
			$recuperaprecio_2 = ''; 
			$recuperaclase_2 = ''; 
			$recuperasuplemento_3 = ''; 
			$recuperaclase_3 = ''; 
			$recuperasuplemento_4 = ''; 
			$recuperaclase_4 = '';
			$recuperasuplemento_5 = ''; 
			$recuperaclase_5 = '';  
			$mensaje1_aereos_precios = '';
			$mensaje2_aereos_precios = '';
			$error_aereos_precios = '';

//echo($parametrosg['codigo']."-".$parametrosg['clave']."-".$parametrosg['display_cabecera']);


//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//CONTROLAMOS LAS VARIABLES NECESARIAS PARA LLAMAR A LA CLASE RESERVAS DEPENDIENDO DE SI ACABAMOS DE ENTRAR EN LA PANTALLA
	//O YA HEMOS REALIZADO ALGUNA PETICION Y/O VOLVEMOS DE OTRA PANTALLA
//--------------------------------------------------------------------------------------------------------------------------------
	if(count($_POST) != 0){
		$filadesde = $parametros['filadesde'];
		$filadesde_aereos = $parametros['filadesde_aereos'];
		$filadesde_aereos_precios = $parametros['filadesde_aereos_precios'];

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
		$filadesde_aereos = 1;
		$filadesde_aereos_precios = 1;
		if(count($_GET) != 0 and ($parametrosg['codigo'] != null or $parametrosg['clave'] != null)){
				$parametros['buscar_codigo'] = $parametrosg['codigo'];
				$parametros['buscar_cuadro'] = $parametrosg['clave'];
				$parametros['seccion_cabecera_cuadro_display'] = $parametrosg['display_cabecera'];
				$parametros['buscar_ciudad'] = '';
				$parametros['buscar_aereo'] = '';
				$parametros['buscar_fecha'] = '';

		}else{
			$parametros['buscar_codigo'] = 0;
			$parametros['buscar_cuadro'] = 0;
			$parametros['buscar_ciudad'] = '';
			$parametros['buscar_aereo'] = '';
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
	$ClaseAereos = new clsCuadros_aereos($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_cuadro']);

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
			//$comboCuadros = $combos->Cargar_combo_Cuadros($parametros['buscar_codigo']);
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
	//---SECCION DEL CODIGO PARA LOS AEREOS -------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_aereos = $parametros['filadesde_aereos'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonselec_aereos = $ClaseAereos->Botones_selector_aereos($filadesde_aereos, $parametros['botonSelector_2nivel']);
				$filadesde_aereos = $botonselec_aereos;
			}

			//Llamada a la clase especifica de la pantalla
			$sAereos = $ClaseAereos->Cargar_aereos($sCuadros[0]['clave'], $filadesde_aereos, $parametros['buscar_ciudad']);	
			//lineas visualizadas
			$vueltas = count($sAereos);

			if($parametros['actuar_2nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_aereos = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							
							$modificaAereos = $ClaseAereos->Modificar_aereos($sCuadros[0]['clave'], $parametros['ciudad'.$num_fila], $parametros['opcion'.$num_fila], $parametros['numero'.$num_fila], $parametros['cia'.$num_fila],$parametros['acuerdo'.$num_fila],$parametros['subacuerdo'.$num_fila],$parametros['origen'.$num_fila],$parametros['destino'.$num_fila],$parametros['dia'.$num_fila],$parametros['tasas'.$num_fila], $parametros['tipo_trayecto'.$num_fila], $parametros['ciudad_old'.$num_fila], $parametros['opcion_old'.$num_fila], $parametros['numero_old'.$num_fila]);
							if($modificaAereos == 'OK'){
								$Ntransacciones_aereos++;
							}else{
								$error_aereos = $modificaAereos;
							}

						}else{

							$borraAereos = $ClaseAereos->Borrar_aereos($sCuadros[0]['clave'], $parametros['ciudad'.$num_fila], $parametros['opcion'.$num_fila], $parametros['numero'.$num_fila]);
							if($borraAereos == 'OK'){
								$Ntransacciones_aereos++;
							}else{
								$error_aereos = $borraAereos;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){

						$insertaAereos = $ClaseAereos->Insertar_aereos($sCuadros[0]['clave'], $sCuadros[0]['folleto'], $sCuadros[0]['cuadro'], $parametros['Nuevociudad'.$num_fila], $parametros['Nuevoopcion'.$num_fila],$parametros['Nuevonumero'.$num_fila],$parametros['Nuevocia'.$num_fila],$parametros['Nuevoacuerdo'.$num_fila],$parametros['Nuevosubacuerdo'.$num_fila],$parametros['Nuevoorigen'.$num_fila],$parametros['Nuevodestino'.$num_fila],$parametros['Nuevodia'.$num_fila],$parametros['Nuevotasas'.$num_fila], $parametros['Nuevotipo_trayecto'.$num_fila]);
						if($insertaAereos == 'OK'){
							$Ntransacciones_aereos++;
						}else{
							$error_aereos = $insertaAereos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.

							$recuperaciudad = $parametros['Nuevociudad'.$num_fila]; 
							$recuperaopcion = $parametros['Nuevoopcion'.$num_fila];  
							$recuperanumero = $parametros['Nuevonumero'.$num_fila]; 
							$recuperacia = $parametros['Nuevocia'.$num_fila];  
							$recuperaacuerdo = $parametros['Nuevoacuerdo'.$num_fila];  
							$recuperasubacuerdo = $parametros['Nuevosubacuerdo'.$num_fila]; 
							$recuperaorigen = $parametros['Nuevoorigen'.$num_fila]; 
							$recuperadestino = $parametros['Nuevodestino'.$num_fila]; 
							$recuperadia = $parametros['Nuevodia'.$num_fila]; 
							$recuperatasas = $parametros['Nuevotasas'.$num_fila]; 
							$recuperatipo_trayecto = $parametros['Nuevotipo_trayecto'.$num_fila]; 
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_aereos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_aereos."</b></font></div>";
				if($error_aereos != ''){
					$mensaje2_aereos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_aereos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();

			}


			//Llamada a la clase especifica de la pantalla
			$sAereos = $ClaseAereos->Cargar_aereos($sCuadros[0]['clave'], $filadesde_aereos, $parametros['buscar_ciudad']);	
			$sComboSelectAereos = $ClaseAereos->Cargar_combo_selector_aereos($sCuadros[0]['clave'], $filadesde_aereos, $parametros['buscar_ciudad']);
			$sAereos_nuevos = $ClaseAereos->Cargar_lineas_nuevas_aereos();	

			//Llamada a la clase general de combos
			/*$comboSino = $combos->Cargar_combo_SiNo();*/
			$comboCiudades = $combos->Cargar_combo_Cuadros_Aereos_Ciudades($sCuadros[0]['clave']);
			$comboTipos_Trayecto = $combos->Cargar_combo_Tipos_trayecto();
			/*$comboForma_Calculo = $combos->Cargar_combo_Forma_Calculo();
			$comboAplicacion_Condicion = $combos->Cargar_combo_Aplicacion_Condicion();*/




	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS PRECIOS -------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_aereos_precios = $parametros['filadesde_aereos_precios'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_3nivel'] != 0){
				$botonselec_aereos_precios = $ClaseAereos->Botones_selector_aereos_precios($filadesde_aereos_precios, $parametros['botonSelector_3nivel']);
				$filadesde_aereos_precios = $botonselec_aereos_precios;
			}

			//Llamada a la clase especifica de la pantalla
			$sAereos_precios = $ClaseAereos->Cargar_aereos_precios($sCuadros[0]['clave'], $filadesde_aereos_precios, $parametros['buscar_aereo'], $parametros['buscar_fecha']);	
			//lineas visualizadas
			$vueltas = count($sAereos_precios);

			if($parametros['actuar_3nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS_PRECIOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_aereos_precios = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {

					if($parametros['selec_3nivel'.$num_fila] == 'S' || $parametros['borra_3nivel'.$num_fila] == 'S'){
						if($parametros['selec_3nivel'.$num_fila] == 'S'){
							
							$modificaAereos_precios = $ClaseAereos->Modificar_aereos_precios($sCuadros[0]['clave'], $parametros['clave_calendario'.$num_fila], $parametros['clave_aereo'.$num_fila], $parametros['precio_1'.$num_fila], $parametros['clase_1'.$num_fila],$parametros['precio_2'.$num_fila],$parametros['clase_2'.$num_fila],$parametros['suplemento_3'.$num_fila],$parametros['clase_3'.$num_fila],$parametros['suplemento_4'.$num_fila],$parametros['clase_4'.$num_fila],$parametros['suplemento_5'.$num_fila],$parametros['clase_5'.$num_fila],$parametros['clave_calendario_old'.$num_fila],$parametros['clave_aereo_old'.$num_fila]);
							if($modificaAereos_precios == 'OK'){
								$Ntransacciones_aereos_precios++;
							}else{
								$error_aereos_precios = $modificaAereos_precios;
							}

						}else{

							$borraAereos_precios = $ClaseAereos->Borrar_aereos_precios($sCuadros[0]['clave'], $parametros['clave_calendario'.$num_fila], $parametros['clave_aereo'.$num_fila]);
							if($borraAereos_precios == 'OK'){
								$Ntransacciones_aereos_precios++;
							}else{
								$error_aereos_precios = $borraAereos_precios;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_AEREOS_PRECIOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_3nivel'.$num_fila] == 'S'){

						$insertaAereos_precios = $ClaseAereos->Insertar_aereos_precios($sCuadros[0]['clave'], $parametros['Nuevoclave_calendario'.$num_fila], $parametros['Nuevoclave_aereo'.$num_fila], $parametros['Nuevoprecio_1'.$num_fila], $parametros['Nuevoclase_1'.$num_fila], $parametros['Nuevoprecio_2'.$num_fila], $parametros['Nuevoclase_2'.$num_fila], $parametros['Nuevosuplemento_3'.$num_fila], $parametros['Nuevoclase_3'.$num_fila], $parametros['Nuevosuplemento_4'.$num_fila], $parametros['Nuevoclase_4'.$num_fila], $parametros['Nuevosuplemento_5'.$num_fila], $parametros['Nuevoclase_5'.$num_fila]);
						if($insertaAereos_precios == 'OK'){
							$Ntransacciones_aereos_precios++;
						}else{
							$error_aereos_precios = $insertaAereos_precios;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.

							$recuperaclave_calendario = $parametros['Nuevoclave_calendario'.$num_fila]; 
							$recuperaclave_aereo = $parametros['Nuevoclave_aereo'.$num_fila];  
							$recuperaprecio_1 = $parametros['Nuevoprecio_1'.$num_fila]; 
							$recuperaclase_1 = $parametros['Nuevoclase_1'.$num_fila];  
							$recuperaprecio_2 = $parametros['Nuevoprecio_2'.$num_fila];  
							$recuperaclase_2 = $parametros['Nuevoclase_2'.$num_fila]; 
							$recuperasuplemento_3 = $parametros['Nuevosuplemento_3'.$num_fila];  
							$recuperaclase_3 = $parametros['Nuevoclase_3'.$num_fila];
							$recuperasuplemento_4 = $parametros['Nuevosuplemento_4'.$num_fila];  
							$recuperaclase_4 = $parametros['Nuevoclase_4'.$num_fila];
							$recuperasuplemento_5 = $parametros['Nuevosuplemento_5'.$num_fila];  
							$recuperaclase_5 = $parametros['Nuevoclase_5'.$num_fila];
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_aereos_precios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_aereos_precios."</b></font></div>";
				if($error_aereos_precios != ''){
					$mensaje2_aereos_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_aereos_precios."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();

			}elseif($parametros['actuar_3nivel'] == 'C'){
				$respuesta_error = '';
				$calcular = "CALL `PRODUCTO_CALCULA_PVP_TRANSPORTES`('".$parametros['folleto0']."', '".$parametros['cuadro0']."', '".$parametros['margen_transportes0']."', '".$parametros['redondeo0']."', '".$parametros['ocupacion_transportes0']."')";
				$resultadocalcular =$conexion->query($calcular);
					//echo($expandir);
				if ($resultadocalcular == FALSE){
					$respuesta_error = 'No se han podido calcular los precios. '.$conexion->error;
				}else{
					$respuesta = 'Precios calculados';

				}
				//Mostramos mensajes y posibles errores
				$mensaje1_aereos_precios  = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$respuesta."</b></font></div>";
				if($respuesta_error != ''){
					$mensaje2_aereos_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$respuesta_error."</b></font></div>";
				}				
			}elseif($parametros['actuar_3nivel'] == 'R'){
				$respuesta_error = '';
				$calcular = "CALL `PRODUCTO_RECALCULA_PVP_TRANSPORTES`('".$parametros['folleto0']."', '".$parametros['cuadro0']."', '".$parametros['margen_transportes0']."', '".$parametros['redondeo0']."', '".$parametros['ocupacion_transportes0']."')";
				$resultadocalcular =$conexion->query($calcular);
					//echo($expandir);
				if ($resultadocalcular == FALSE){
					$respuesta_error = 'No se han podido recalcular los precios. '.$conexion->error;
				}else{
					$respuesta = 'Precios recalculados';

				}
				//Mostramos mensajes y posibles errores
				$mensaje1_aereos_precios  = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$respuesta."</b></font></div>";
				if($respuesta_error != ''){
					$mensaje2_aereos_precios  = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$respuesta_error."</b></font></div>";
				}				
			}


			//Llamada a la clase especifica de la pantalla
			$sAereos_precios = $ClaseAereos->Cargar_aereos_precios($sCuadros[0]['clave'], $filadesde_aereos_precios, $parametros['buscar_aereo'], $parametros['buscar_fecha']);	
			$sComboSelectAereos_precios = $ClaseAereos->Cargar_combo_selector_aereos_precios($sCuadros[0]['clave'], $filadesde_aereos_precios, $parametros['buscar_aereo'], $parametros['buscar_fecha']);
			$sAereos_nuevos_precios = $ClaseAereos->Cargar_lineas_nuevas_aereos_precios();	

			//Llamada a la clase general de combos
			/*$comboSino = $combos->Cargar_combo_SiNo();*/
			$comboCalendario = $combos->Cargar_combo_Cuadros_Calendario($sCuadros[0]['clave']);
			$comboAereos = $combos->Cargar_combo_Cuadros_Aereos($sCuadros[0]['clave']);
			/*$comboForma_Calculo = $combos->Cargar_combo_Forma_Calculo();
			$comboAplicacion_Condicion = $combos->Cargar_combo_Aplicacion_Condicion();*/



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
			$smarty->assign('formulario', '» AEREOS');

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
			//----VISUALIZAR PARTE DE LOS AEREOS------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_aereos', $filadesde_aereos);

			//Cargar combo selector
			$smarty->assign('combo_aereos', $sComboSelectAereos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('aereos', $sAereos);

			//Cargar combos de las lineas de la tabla
			/*$smarty->assign('comboSino', $comboSino);*/
			$smarty->assign('comboCiudades', $comboCiudades);
			$smarty->assign('comboTipos_Trayecto', $comboTipos_Trayecto);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('aereosnuevos', $sAereos_nuevos);	

			$smarty->assign('recuperaciudad', $recuperaciudad);	
			$smarty->assign('recuperaopcion', $recuperaopcion);	
			$smarty->assign('recuperanumero', $recuperanumero);	
			$smarty->assign('recuperacia', $recuperacia);	
			$smarty->assign('recuperaacuerdo', $recuperaacuerdo);	
			$smarty->assign('recuperasubacuerdo', $recuperasubacuerdo);	
			$smarty->assign('recuperaorigen', $recuperaorigen);	
			$smarty->assign('recuperadestino', $recuperadestino);	
			$smarty->assign('recuperadia', $recuperadia);	
			$smarty->assign('recuperatasas', $recuperatasas);	
			$smarty->assign('recuperatipo_trayecto', $recuperatipo_trayecto);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_aereos', $mensaje1_aereos);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_aereos', $mensaje2_aereos);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_ciudad', $parametros['buscar_ciudad']);


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS PRECIOS----------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_aereos_precios', $filadesde_aereos_precios);

			//Cargar combo selector
			$smarty->assign('combo_aereos_precios', $sComboSelectAereos_precios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('aereos_precios', $sAereos_precios);

			//Cargar combos de las lineas de la tabla
			/*$smarty->assign('comboSino', $comboSino);*/
			$smarty->assign('comboCalendarios', $comboCalendario);
			$smarty->assign('comboAereos', $comboAereos);
			/*$smarty->assign('comboForma_Calculo', $comboForma_Calculo);
			$smarty->assign('comboAplicacion_Condicion', $comboAplicacion_Condicion);*/

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('aereosnuevos_precios', $sAereos_nuevos_precios);	

			$smarty->assign('recuperaclave_calendario', $recuperaclave_calendario);	
			$smarty->assign('recuperaclave_aereo', $recuperaclave_aereo);	
			$smarty->assign('recuperaprecio_1', $recuperaprecio_1);	
			$smarty->assign('recuperaclase_1', $recuperaclase_1);	
			$smarty->assign('recuperaprecio_2', $recuperaprecio_2);	
			$smarty->assign('recuperaclase_2', $recuperaclase_2);	
			$smarty->assign('recuperasuplemento_3', $recuperasuplemento_3);	
			$smarty->assign('recuperaclase_3', $recuperaclase_3);
			$smarty->assign('recuperasuplemento_4', $recuperasuplemento_4);	
			$smarty->assign('recuperaclase_4', $recuperaclase_4);
			$smarty->assign('recuperasuplemento_5', $recuperasuplemento_5);	
			$smarty->assign('recuperaclase_5', $recuperaclase_5);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_aereos_precios', $mensaje1_aereos_precios);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_aereos_precios', $mensaje2_aereos_precios);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_aereo', $parametros['buscar_aereo']);
			$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);



			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Producto_cuadros_aereos.html');


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
			$smarty->assign('formulario', '» AEREOS');

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
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE AEREOS

			//Llamada a la clase especifica de la pantalla
			$sAereos = $ClaseAereos->Cargar_aereos($sCuadros[0]['clave'], $filadesde_aereos, $parametros['buscar_ciudad']);	
			$sComboSelectAereos = $ClaseAereos->Cargar_combo_selector_aereos($sCuadros[0]['clave'], $filadesde_aereos, $parametros['buscar_ciudad']);
			$sAereos_nuevos = $ClaseAereos->Cargar_lineas_nuevas_aereos();	

			//Llamada a la clase general de combos
			/*$comboSino = $combos->Cargar_combo_SiNo();*/
			$comboCiudades = $combos->Cargar_combo_Cuadros_Aereos_Ciudades($sCuadros[0]['clave']);
			$comboTipos_Trayecto = $combos->Cargar_combo_Tipos_trayecto();
			/*$comboForma_Calculo = $combos->Cargar_combo_Forma_Calculo();
			$comboAplicacion_Condicion = $combos->Cargar_combo_Aplicacion_Condicion();*/


			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_aereos', $filadesde_aereos);

			//Cargar combo selector
			$smarty->assign('combo_aereos', $sComboSelectAereos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('aereos', $sAereos);

			//Cargar combos de las lineas de la tabla
			/*$smarty->assign('comboSino', $comboSino);*/
			$smarty->assign('comboCiudades', $comboCiudades);
			$smarty->assign('comboTipos_Trayecto', $comboTipos_Trayecto);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('aereosnuevos', $sAereos_nuevos);	

			$smarty->assign('recuperaciudad', $recuperaciudad);	
			$smarty->assign('recuperaopcion', $recuperaopcion);	
			$smarty->assign('recuperanumero', $recuperanumero);	
			$smarty->assign('recuperacia', $recuperacia);	
			$smarty->assign('recuperaacuerdo', $recuperaacuerdo);	
			$smarty->assign('recuperasubacuerdo', $recuperasubacuerdo);	
			$smarty->assign('recuperaorigen', $recuperaorigen);	
			$smarty->assign('recuperadestino', $recuperadestino);	
			$smarty->assign('recuperadia', $recuperadia);	
			$smarty->assign('recuperatasas', $recuperatasas);	
			$smarty->assign('comboTipos_Trayecto', $comboTipos_Trayecto);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_aereos', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_aereos', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_ciudad', '');


		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE PRECIOS

			//Llamada a la clase especifica de la pantalla
			$sAereos_precios = $ClaseAereos->Cargar_aereos_precios($sCuadros[0]['clave'], $filadesde_aereos_precios, $parametros['buscar_aereo'], $parametros['buscar_fecha']);	
			$sComboSelectAereos_precios = $ClaseAereos->Cargar_combo_selector_aereos_precios($sCuadros[0]['clave'], $filadesde_aereos_precios, $parametros['buscar_aereo'], $parametros['buscar_fecha']);
			$sAereos_nuevos_precios = $ClaseAereos->Cargar_lineas_nuevas_aereos_precios();	

			//Llamada a la clase general de combos
			/*$comboSino = $combos->Cargar_combo_SiNo();*/
			$comboCalendario = $combos->Cargar_combo_Cuadros_Calendario($sCuadros[0]['clave']);
			$comboAereos = $combos->Cargar_combo_Cuadros_Aereos($sCuadros[0]['clave']);
			/*$comboForma_Calculo = $combos->Cargar_combo_Forma_Calculo();
			$comboAplicacion_Condicion = $combos->Cargar_combo_Aplicacion_Condicion();*/


			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_aereos_precios', $filadesde_aereos_precios);

			//Cargar combo selector
			$smarty->assign('combo_aereos_precios', $sComboSelectAereos_precios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('aereos_precios', $sAereos_precios);

			//Cargar combos de las lineas de la tabla
			/*$smarty->assign('comboSino', $comboSino);*/
			$smarty->assign('comboCalendarios', $comboCalendario);
			$smarty->assign('comboAereos', $comboAereos);
			/*$smarty->assign('comboForma_Calculo', $comboForma_Calculo);
			$smarty->assign('comboAplicacion_Condicion', $comboAplicacion_Condicion);*/

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('aereosnuevos_precios', $sAereos_nuevos_precios);	

			$smarty->assign('recuperaclave_calendario', $recuperaclave_calendario);	
			$smarty->assign('recuperaclave_aereo', $recuperaclave_aereo);	
			$smarty->assign('recuperaprecio_1', $recuperaprecio_1);	
			$smarty->assign('recuperaclase_1', $recuperaclase_1);	
			$smarty->assign('recuperaprecio_2', $recuperaprecio_2);	
			$smarty->assign('recuperaclase_2', $recuperaclase_2);
			$smarty->assign('recuperasuplemento_3', $recuperasuplemento_3);	
			$smarty->assign('recuperaclase_3', $recuperaclase_3);
			$smarty->assign('recuperasuplemento_4', $recuperasuplemento_4);	
			$smarty->assign('recuperaclase_4', $recuperaclase_4);
			$smarty->assign('recuperasuplemento_5', $recuperasuplemento_5);	
			$smarty->assign('recuperaclase_5', $recuperaclase_5);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_aereos_precios', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_aereos_precios', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_aereo', '');
			$smarty->assign('buscar_fecha', '');





		//Visualizar plantilla
		$smarty->display('plantillas/Producto_cuadros_aereos.html');
	}

	$conexion->close();


?>

