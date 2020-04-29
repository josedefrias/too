<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/producto_cuadros.cls.php';
	require 'clases/producto_cuadros_condiciones.cls.php';

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
			$recuperadestino_cuadro = '';
			$recuperadestino_cuadro2 = '';
			$recuperasituacion = '';
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

			//VARIABLES PARA LAS CODICIONES
			$recuperatipo = ''; 
			$recuperauso = ''; 
			$recuperasalida_desde = ''; 
			$recuperasalida_hasta = ''; 
			$recuperareserva_desde = ''; 
			$recuperareserva_hasta = ''; 
			$recuperamargen_1 = ''; 
			$recuperamargen_2 = ''; 
			$recuperamaximo = '';
			$recuperaforma_calculo = ''; 
			$recuperavalor_1 = ''; 
			$recuperavalor_2 = ''; 
			$recuperaacumulable = ''; 
			$recuperaprioritario = ''; 
			$recuperaaplicacion = ''; 
			$mensaje1_condiciones = '';
			$mensaje2_condiciones = '';
			$error_condiciones = '';

//echo($parametrosg['codigo']."-".$parametrosg['clave']."-".$parametrosg['display_cabecera']);


//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//CONTROLAMOS LAS VARIABLES NECESARIAS PARA LLAMAR A LA CLASE RESERVAS DEPENDIENDO DE SI ACABAMOS DE ENTRAR EN LA PANTALLA
	//O YA HEMOS REALIZADO ALGUNA PETICION Y/O VOLVEMOS DE OTRA PANTALLA
//--------------------------------------------------------------------------------------------------------------------------------
	if(count($_POST) != 0){
		$filadesde = $parametros['filadesde'];
		$filadesde_condiciones = $parametros['filadesde_condiciones'];

		//esto es por si venimos de la pantalla de listado de cupos
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
		$filadesde_condiciones = 1;
		if(count($_GET) != 0 and ($parametrosg['codigo'] != null or $parametrosg['clave'] != null)){
				$parametros['buscar_codigo'] = $parametrosg['codigo'];
				$parametros['buscar_cuadro'] = $parametrosg['clave'];
				$parametros['seccion_cabecera_cuadro_display'] = $parametrosg['display_cabecera'];
				$parametros['buscar_tipo'] = 0;

		}else{
			$parametros['buscar_codigo'] = 0;
			$parametros['buscar_cuadro'] = 0;
			$parametros['buscar_tipo'] = 0;
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
	$ClaseCondiciones = new clsCuadros_condiciones($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_cuadro']);

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
	//---SECCION DEL CODIGO PARA LAS CONDICIONES -------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_condiciones = $parametros['filadesde_condiciones'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonselec_condiciones = $ClaseCondiciones->Botones_selector_condiciones($filadesde_condiciones, $parametros['botonSelector_2nivel']);
				$filadesde_condiciones = $botonselec_condiciones;
			}

			//Llamada a la clase especifica de la pantalla
			$sCondiciones = $ClaseCondiciones->Cargar_condiciones($sCuadros[0]['clave'], $filadesde_condiciones, $parametros['buscar_tipo']);	
			//lineas visualizadas
			$vueltas = count($sCondiciones);

			if($parametros['actuar_2nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_CONDICIONES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_condiciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							
							$modificaCondiciones = $ClaseCondiciones->Modificar_condiciones($sCuadros[0]['clave'], $parametros['tipo'.$num_fila], $parametros['uso'.$num_fila], $parametros['salida_desde'.$num_fila], $parametros['salida_hasta'.$num_fila], $parametros['reserva_desde'.$num_fila],$parametros['reserva_hasta'.$num_fila],$parametros['margen_1'.$num_fila],$parametros['margen_2'.$num_fila],$parametros['maximo'.$num_fila], $parametros['forma_calculo'.$num_fila],$parametros['valor_1'.$num_fila],$parametros['valor_2'.$num_fila], $parametros['acumulable'.$num_fila], $parametros['prioritario'.$num_fila], $parametros['aplicacion'.$num_fila], $parametros['tipo_old'.$num_fila], $parametros['uso_old'.$num_fila], $parametros['salida_desde_old'.$num_fila], $parametros['salida_hasta_old'.$num_fila], $parametros['reserva_desde_old'.$num_fila],$parametros['reserva_hasta_old'.$num_fila],$parametros['margen_1_old'.$num_fila],$parametros['margen_2_old'.$num_fila]);
							if($modificaCondiciones == 'OK'){
								$Ntransacciones_condiciones++;
							}else{
								$error_condiciones = $modificaCondiciones;
							}

						}else{

							$borraCondiciones = $ClaseCondiciones->Borrar_condiciones($sCuadros[0]['clave'], $parametros['tipo'.$num_fila], $parametros['uso'.$num_fila], $parametros['salida_desde'.$num_fila], $parametros['salida_hasta'.$num_fila], $parametros['reserva_desde'.$num_fila],$parametros['reserva_hasta'.$num_fila],$parametros['margen_1'.$num_fila],$parametros['margen_2'.$num_fila]);
							if($borraCondiciones == 'OK'){
								$Ntransacciones_condiciones++;
							}else{
								$error_condiciones = $borraCondiciones;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_CUADROS_CONDICIONES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){

						$insertaCondiciones = $ClaseCondiciones->Insertar_condiciones($sCuadros[0]['clave'], $sCuadros[0]['folleto'], $sCuadros[0]['cuadro'], $parametros['Nuevotipo'.$num_fila], $parametros['Nuevouso'.$num_fila], $parametros['Nuevosalida_desde'.$num_fila],$parametros['Nuevosalida_hasta'.$num_fila],$parametros['Nuevoreserva_desde'.$num_fila],$parametros['Nuevoreserva_hasta'.$num_fila],$parametros['Nuevomargen_1'.$num_fila],$parametros['Nuevomargen_2'.$num_fila],$parametros['Nuevomaximo'.$num_fila],$parametros['Nuevoforma_calculo'.$num_fila],$parametros['Nuevovalor_1'.$num_fila],$parametros['Nuevovalor_2'.$num_fila],$parametros['Nuevoacumulable'.$num_fila],$parametros['Nuevoprioritario'.$num_fila],$parametros['Nuevoaplicacion'.$num_fila]);
						if($insertaCondiciones == 'OK'){
							$Ntransacciones_condiciones++;
						}else{
							$error_condiciones = $insertaCondiciones;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.

							$recuperatipo = $parametros['Nuevotipo'.$num_fila]; 
							$recuperauso = $parametros['Nuevouso'.$num_fila];
							$recuperasalida_desde = $parametros['Nuevosalida_desde'.$num_fila];  
							$recuperasalida_hasta = $parametros['Nuevosalida_hasta'.$num_fila]; 
							$recuperareserva_desde = $parametros['Nuevoreserva_desde'.$num_fila];  
							$recuperareserva_hasta = $parametros['Nuevoreserva_hasta'.$num_fila];  
							$recuperamargen_1 = $parametros['Nuevomargen_1'.$num_fila]; 
							$recuperamarge_2 = $parametros['Nuevomargen_2'.$num_fila]; 
							$recuperamaximo = $parametros['Nuevomaximo'.$num_fila];
							$recuperaforma_calculo = $parametros['Nuevoforma_calculo'.$num_fila]; 
							$recuperavalor_1 = $parametros['Nuevovalor_1'.$num_fila]; 
							$recuperavalor_2 = $parametros['Nuevovalor_2'.$num_fila]; 
							$recuperaacumulable = $parametros['Nuevoacumulable'.$num_fila];  
							$recuperaprioritario = $parametros['Nuevoprioritario'.$num_fila];  
							$recuperaaplicacion = $parametros['Nuevoaplicacion'.$num_fila];  
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_condiciones = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_condiciones."</b></font></div>";
				if($error_condiciones != ''){
					$mensaje2_condiciones = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_condiciones."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();

			}
			
			

			//Llamada a la clase especifica de la pantalla
			$sCondiciones = $ClaseCondiciones->Cargar_condiciones($sCuadros[0]['clave'], $filadesde_condiciones, $parametros['buscar_tipo']);	
			$sComboSelectCondiciones = $ClaseCondiciones->Cargar_combo_selector_condiciones($sCuadros[0]['clave'], $filadesde_condiciones, $parametros['buscar_tipo']);
			$sCondiciones_nuevos = $ClaseCondiciones->Cargar_lineas_nuevas_condiciones();	

			//Llamada a la clase general de combos
			$comboSino = $combos->Cargar_combo_SiNo();
			$comboTipo_Condiciones = $combos->Cargar_combo_Tipo_Condiciones();
			$comboForma_Calculo = $combos->Cargar_combo_Forma_Calculo();
			$comboAplicacion_Condicion = $combos->Cargar_combo_Aplicacion_Condicion();


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
			$smarty->assign('formulario', '» CONDICIONES');

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
			//----VISUALIZAR PARTE DE LAS CONDICIONES------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_condiciones', $filadesde_condiciones);

			//Cargar combo selector
			$smarty->assign('combo_condiciones', $sComboSelectCondiciones);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('condiciones', $sCondiciones);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboTipo_Condiciones', $comboTipo_Condiciones);
			$smarty->assign('comboForma_Calculo', $comboForma_Calculo);
			$smarty->assign('comboAplicacion_Condicion', $comboAplicacion_Condicion);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('condicionesnuevos', $sCondiciones_nuevos);	

			$smarty->assign('recuperatipo', $recuperatipo);	
			$smarty->assign('recuperauso', $recuperauso);
			$smarty->assign('recuperasalida_desde', $recuperasalida_desde);	
			$smarty->assign('recuperasalida_hasta', $recuperasalida_hasta);	
			$smarty->assign('recuperareserva_desde', $recuperareserva_desde);	
			$smarty->assign('recuperareserva_hasta', $recuperareserva_hasta);	
			$smarty->assign('recuperamargen_1', $recuperamargen_1);	
			$smarty->assign('recuperamargen_2', $recuperamargen_2);	
			$smarty->assign('recuperamaximo', $recuperamaximo);
			$smarty->assign('recuperaforma_calculo', $recuperaforma_calculo);	
			$smarty->assign('recuperavalor_1', $recuperavalor_1);	
			$smarty->assign('recuperavalor_2', $recuperavalor_2);	
			$smarty->assign('recuperaacumulable', $recuperaacumulable);	
			$smarty->assign('recuperaprioritario', $recuperaprioritario);	
			$smarty->assign('recuperaaplicacion', $recuperaaplicacion);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_condiciones', $mensaje1_condiciones);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_condiciones', $mensaje2_condiciones);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_tipo', $parametros['buscar_tipo']);


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Producto_cuadros_condiciones.html');


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
			$smarty->assign('formulario', '» CONDICIONES');

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
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE CONDICIONES

			//Llamada a la clase especifica de la pantalla
			$sCondiciones = $ClaseCondiciones->Cargar_condiciones($sCuadros[0]['clave'], $filadesde_condiciones, $parametros['buscar_tipo']);	
			$sComboSelectCondiciones = $ClaseCondiciones->Cargar_combo_selector_condiciones($sCuadros[0]['clave'], $filadesde_condiciones, $parametros['buscar_tipo']);
			$sCondiciones_nuevos = $ClaseCondiciones->Cargar_lineas_nuevas_condiciones();	

			//Llamada a la clase general de combos
			$comboSino = $combos->Cargar_combo_SiNo();
			$comboTipo_Condiciones = $combos->Cargar_combo_Tipo_Condiciones();
			$comboForma_Calculo = $combos->Cargar_combo_Forma_Calculo();
			$comboAplicacion_Condicion = $combos->Cargar_combo_Aplicacion_Condicion();


			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_condiciones', $filadesde_condiciones);

			//Cargar combo selector
			$smarty->assign('combo_condiciones', $sComboSelectCondiciones);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('condiciones', $sCondiciones);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboTipo_Condiciones', $comboTipo_Condiciones);
			$smarty->assign('comboForma_Calculo', $comboForma_Calculo);
			$smarty->assign('comboAplicacion_Condicion', $comboAplicacion_Condicion);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('condicionesnuevos', $sCondiciones_nuevos);	

			$smarty->assign('recuperatipo', $recuperatipo);	
			$smarty->assign('recuperauso', $recuperauso);
			$smarty->assign('recuperasalida_desde', $recuperasalida_desde);	
			$smarty->assign('recuperasalida_hasta', $recuperasalida_hasta);	
			$smarty->assign('recuperareserva_desde', $recuperareserva_desde);	
			$smarty->assign('recuperareserva_hasta', $recuperareserva_hasta);	
			$smarty->assign('recuperamargen_1', $recuperamargen_1);	
			$smarty->assign('recuperamargen_2', $recuperamargen_2);	
			$smarty->assign('recuperamaximo', $recuperamaximo);
			$smarty->assign('recuperaforma_calculo', $recuperaforma_calculo);	
			$smarty->assign('recuperavalor_1', $recuperavalor_1);	
			$smarty->assign('recuperavalor_2', $recuperavalor_2);	
			$smarty->assign('recuperaacumulable', $recuperaacumulable);	
			$smarty->assign('recuperaprioritario', $recuperaprioritario);	
			$smarty->assign('recuperaaplicacion', $recuperaaplicacion);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_condiciones', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_condiciones', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_tipo', '');



		//Visualizar plantilla
		$smarty->display('plantillas/Producto_cuadros_condiciones.html');
	}

	$conexion->close();


?>

