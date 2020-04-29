<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/producto_tarifarios.cls.php';


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

	$parametros = $_POST;
	$parametrosg = $_GET;

			//VARIABLES PARA LOS DATOS GENERALES
			$recuperaclave = '';
			$recuperaentidad = '';
			$recuperaanno = '';
			$recuperadestino = '';
			$recuperanoches = '';
			$recuperanombre = '';
			$recuperafolleto_base = '';
			$recuperacuadro_base = '';
			$recuperafecha_desde = '';
			$recuperafecha_hasta = '';
			$recuperadias_semana = '';
			$recuperadivisa = '';
			$recuperaredondeo = '';
			$recuperamargen_transportes = '';
			$recuperaocupacion_transportes = '';
			$recuperamargen_alojamientos = '';
			$recuperamargen_servicios = '';
			$recuperaaplicar_descuento_antelacion = '';
			$recuperaciudad_salida = '';


			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';


			$mensaje1_precios = '';
			$mensaje2_precios = '';
			$error_precios = '';

			
	if(count($_POST) != 0){


		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'C'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE TARIFARIOS--------------
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonTarifarios = new clsTarifarios($conexion, $filadesde, $usuario, $parametros['buscar_entidad'], $parametros['buscar_anno'], $parametros['buscar_destino'], $parametros['buscar_noches'], $parametros['buscar_nombre']);
				$botonselec = $botonTarifarios->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TARIFARIOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mTarifarios = new clsTarifarios($conexion, $filadesde, $usuario, $parametros['buscar_entidad'], $parametros['buscar_anno'], $parametros['buscar_destino'], $parametros['buscar_noches'], $parametros['buscar_nombre']);
							$modificaTarifarios = $mTarifarios->Modificar($parametros['clave'.$num_fila],$parametros['entidad'.$num_fila],$parametros['anno'.$num_fila],$parametros['destino'.$num_fila],$parametros['noches'.$num_fila],$parametros['nombre'.$num_fila],$parametros['folleto_base'.$num_fila],$parametros['cuadro_base'.$num_fila],$parametros['fecha_desde'.$num_fila],$parametros['fecha_hasta'.$num_fila],$parametros['dias_semana'.$num_fila],$parametros['divisa'.$num_fila],$parametros['redondeo'.$num_fila],$parametros['margen_transportes'.$num_fila],$parametros['ocupacion_transportes'.$num_fila],$parametros['margen_alojamientos'.$num_fila],$parametros['margen_servicios'.$num_fila],$parametros['aplicar_descuento_antelacion'.$num_fila],$parametros['ciudad_salida'.$num_fila]);
							if($modificaTarifarios == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaTarifarios;
								
							}
						}else{

							$mTarifarios = new clsTarifarios($conexion, $filadesde, $usuario, $parametros['buscar_entidad'], $parametros['buscar_anno'], $parametros['buscar_destino'], $parametros['buscar_noches'], $parametros['buscar_nombre']);
							$borraTarifarios = $mTarifarios->Borrar($parametros['clave'.$num_fila]);
							if($borraTarifarios == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraTarifarios;
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

				$iTarifarios = new clsTarifarios($conexion, $filadesde, $usuario, $parametros['buscar_entidad'], $parametros['buscar_anno'], $parametros['buscar_destino'], $parametros['buscar_noches'], $parametros['buscar_nombre']);
				$insertaTarifario = $iTarifarios->Insertar($parametros['entidad0'],$parametros['anno0'],$parametros['destino0'],$parametros['noches0'],$parametros['nombre0'],$parametros['folleto_base0'],$parametros['cuadro_base0'],$parametros['fecha_desde0'],$parametros['fecha_hasta0'],$parametros['dias_semana0'],$parametros['divisa0'],$parametros['redondeo0'],$parametros['margen_transportes0'],$parametros['ocupacion_transportes0'],$parametros['margen_alojamientos0'],$parametros['margen_servicios0'],$parametros['aplicar_descuento_antelacion0'],$parametros['ciudad_salida0']);

				if($insertaTarifario == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_entidad'] = $parametros['entidad0'];
					$parametros['buscar_anno'] = $parametros['anno0'];
					$parametros['buscar_destino'] = $parametros['destino0'];
					$parametros['buscar_noches'] = $parametros['noches0'];
					$parametros['buscar_nombre'] = $parametros['nombre0'];

				}else{
					$error = $insertaTarifario;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					$recuperaentidad = $parametros['entidad0'];
					$recuperaanno = $parametros['anno0'];
					$recuperadestino = $parametros['destino0'];
					$recuperanoches = $parametros['noches0'];
					$recuperanombre = $parametros['nombre0'];
					$recuperafolleto_base = $parametros['folleto_base0'];
					$recuperacuadro_base = $parametros['cuadro_base0'];
					$recuperafecha_desde = $parametros['fecha_desde0'];
					$recuperafecha_hasta = $parametros['fecha_hasta0'];
					$recuperadias_semana = $parametros['dias_semana0'];
					$recuperadivisa = $parametros['divisa0'];
					$recuperaredondeo = $parametros['redondeo0'];
					$recuperamargen_transportes = $parametros['margen_transportes0'];
					$recuperaocupacion_transportes = $parametros['ocupacion_transportes0'];
					$recuperamargen_alojamientos = $parametros['margen_alojamientos0'];
					$recuperamargen_servicios = $parametros['margen_servicios0'];
					$recuperaaplicar_descuento_antelacion = $parametros['aplicar_descuento_antelacion0'];
					$recuperaciudad_salida = $parametros['ciudad_salida0'];

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
			$oTarifarios = new clsTarifarios($conexion, $filadesde, $usuario, $parametros['buscar_entidad'], $parametros['buscar_anno'], $parametros['buscar_destino'], $parametros['buscar_noches'], $parametros['buscar_nombre']);
			$sTarifarios = $oTarifarios->Cargar($recuperaentidad ,$recuperaanno,$recuperadestino,$recuperanoches,$recuperanombre,$recuperafolleto_base,$recuperacuadro_base,$recuperafecha_desde,$recuperafecha_hasta,$recuperadias_semana,$recuperadivisa,$recuperaredondeo,$recuperamargen_transportes,$recuperaocupacion_transportes,$recuperamargen_alojamientos,$recuperamargen_servicios,$recuperaaplicar_descuento_antelacion,$recuperaciudad_salida);
			$sComboSelectTarifarios = $oTarifarios->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo_No();
			$comboEntidades = $oSino->Cargar_combo_Entidades();
			$comboAnnos = $oSino->Cargar_Combo_Annos();
			$comboDestinos = $oSino->Cargar_combo_Destinos();
			$comboFolletos = $oSino->Cargar_combo_Folletos();
			$comboCuadros = $oSino->Cargar_combo_Cuadros($parametros['folleto_base0']);
			$comboDivisas = $oSino->Cargar_combo_Divisas();

	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS PRECIOS ----------------
	//--------------------------------------------------------

				if($parametros['actuar_2nivel'] == 'R'){

					//ECHO($sTarifarios[0]['clave']);

					$recalcula_precios = "CALL `PRODUCTO_RECALCULA_COSTES_TARIFARIO`('".$sTarifarios[0]['clave']."')";
					$resultadorecalcula_precios =$conexion->query($recalcula_precios);
							
					if ($resultadorecalcula_precios == FALSE){
						$error_precios = 'No se han podido calcular los costes del tarifario. '.$conexion->error;
					}else{
						$Ntransacciones_precios = 'Costes y precios calculados correctamente';
					}
					//Mostramos mensajes y posibles errores
					$mensaje1_precios = "<div><font color='#003399' size='3' ><b>".$Ntransacciones_precios."</b></font></div>";
					if($error_precios != ''){
						$mensaje2_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_precios."</b></font></div>";
					}

				}elseif($parametros['actuar_2nivel'] == 'P'){

					$recalcula_precios = "CALL `PRODUCTO_RECALCULA_PVP_TARIFARIO`('".$sTarifarios[0]['clave']."')";
					$resultadorecalcula_precios =$conexion->query($recalcula_precios);
							
					if ($resultadorecalcula_precios == FALSE){
						$error_precios = 'No se han podido recalcular los precios del tarifario. '.$conexion->error;
					}else{
						$Ntransacciones_precios = 'Precios recalculados correctamente';
					}
					//Mostramos mensajes y posibles errores
					$mensaje1_precios = "<div><font color='#003399' size='3' ><b>".$Ntransacciones_precios."</b></font></div>";
					if($error_precios != ''){
						$mensaje2_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_precios."</b></font></div>";
					}

				}


				//Llamada a la clase especifica de la pantalla
				$sCabecera_precios = $oTarifarios->Cargar_precios_cabecera($parametros['clave0'],$parametros['buscar_fecha_desde_precios'],$parametros['buscar_fecha_hasta_precios']);

				$sCabecera_nombres = $oTarifarios->Cargar_nombres_cabecera($parametros['clave0'],$parametros['buscar_fecha_desde_precios'],$parametros['buscar_fecha_hasta_precios']);

				$sPrecios = $oTarifarios->Cargar_precios($parametros['clave0'],$parametros['buscar_fecha_desde_precios'],$parametros['buscar_fecha_hasta_precios'],$parametros['redondeo0']);
				//Llamada a la clase general de combos
				/*$comboTipo_unidad = $oSino->Cargar_combo_Tipo_Unidad();
				$comboCalculo = $oSino->Cargar_combo_Calculo();*/

				/*echo('<pre>');
				print_r($sPrecios);
				echo('</pre>');*/




	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS TARIFARIOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#30A1C7');
			$smarty->assign('grupo', '» PRODUCTO');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» TARIFARIOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTarifarios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('tarifarios', $sTarifarios);


			/*$comboSino = $oSino->Cargar_combo_SiNo_No();
			$comboEntidades = $oSino->Cargar_combo_Entidades();
			$comboAnnos = $oSino->Cargar_Combo_Annos();
			$comboDestinos = $oSino->Cargar_combo_Destinos();
			$comboFolletos = $oSino->Cargar_combo_Folletos();
			$comboCuadros = $oSino->Cargar_combo_Cuadros();
			$comboDivisas = $oSino->Cargar_combo_Divisas();*/


			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboEntidades', $comboEntidades);
			$smarty->assign('comboAnnos', $comboAnnos);
			$smarty->assign('comboDestinos', $comboDestinos);
			$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboCuadros', $comboCuadros);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboSino', $comboSino);

			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_entidad', $parametros['buscar_entidad']);
			$smarty->assign('buscar_anno', $parametros['buscar_anno']);
			$smarty->assign('buscar_destino', $parametros['buscar_destino']);
			$smarty->assign('buscar_noches', $parametros['buscar_noches']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS PRECIOS---------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			/*$smarty->assign('filades_precios', $filadesde_precios);
		

			//Cargar combo selector
			$smarty->assign('combo_precios', $sComboSelectPrecios);*/

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('cabecera_precios', $sCabecera_precios);
			$smarty->assign('cabecera_nombres', $sCabecera_nombres);
			$smarty->assign('precios', $sPrecios);

			


			//Cargar combos de las lineas de la tabla
			/*$smarty->assign('comboTipo_unidad', $comboTipo_unidad);
			$smarty->assign('comboCalculo', $comboCalculo);

			if($sTarifarios[0]['tipo'] == 'PRO'){
				$smarty->assign('comboHabitaciones', $comboHabitaciones);
				$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car);
			}

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('preciosnuevos', $sPrecios_nuevos);	
			
			$smarty->assign('recuperafecha_desde_precios', $recuperafecha_desde_precios);	
			$smarty->assign('recuperafecha_hasta_precios', $recuperafecha_hasta_precios);	
			$smarty->assign('recuperatipo_unidad', $recuperatipo_unidad);	
			$smarty->assign('recuperaunidad_desde', $recuperaunidad_desde);
			$smarty->assign('recuperaunidad_hasta', $recuperaunidad_hasta);
			if($sTarifarios[0]['tipo'] == 'PRO'){
				$smarty->assign('recuperapaquete', $recuperapaquete);
				$smarty->assign('recuperahabitacion', $recuperahabitacion);
				$smarty->assign('recuperacaracteristica', $recuperacaracteristica);
				$smarty->assign('recuperauso', $recuperauso);
			}
			$smarty->assign('recuperacoste', $recuperacoste);
			$smarty->assign('recuperacalculo', $recuperacalculo);
			$smarty->assign('recuperaporcentaje_neto', $recuperaporcentaje_neto);
			$smarty->assign('recuperaneto', $recuperaneto);
			$smarty->assign('recuperaporcentaje_com', $recuperaporcentaje_com);
			$smarty->assign('recuperapvp', $recuperapvp);*/

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_precios', $mensaje1_precios);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_precios', $mensaje2_precios);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_fecha_desde_precios', $parametros['buscar_fecha_desde_precios']);
			$smarty->assign('buscar_fecha_hasta_precios', $parametros['buscar_fecha_hasta_precios']);

			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla

			$smarty->display('plantillas/Producto_tarifarios.html');


		}elseif($parametros['ir_a'] == 'E'){

			//Llamar a al metodo de expandir cupos;

		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS TARIFARIOS

		$filadesde = 1;

		$parametros['buscar_entidad'] = "";
		$parametros['buscar_anno'] = " ";
		$parametros['buscar_destino'] = "";
		$parametros['buscar_noches'] = "";
		$parametros['buscar_nombre'] = "";
		$parametros['folleto_base0'] = "";

		//Llamada a la clase especifica de la pantalla
		$oTarifarios = new clsTarifarios($conexion, $filadesde, $usuario, $parametros['buscar_entidad'], $parametros['buscar_anno'], $parametros['buscar_destino'], $parametros['buscar_noches'], $parametros['buscar_nombre']);
		$sTarifarios = $oTarifarios->Cargar($recuperaentidad ,$recuperaanno,$recuperadestino,$recuperanoches,$recuperanombre,$recuperafolleto_base,$recuperacuadro_base,$recuperafecha_desde,$recuperafecha_hasta,$recuperadias_semana,$recuperadivisa,$recuperaredondeo,$recuperamargen_transportes,$recuperaocupacion_transportes,$recuperamargen_alojamientos,$recuperamargen_servicios,$recuperaaplicar_descuento_antelacion,$recuperaciudad_salida);
		$sComboSelectTarifarios = $oTarifarios->Cargar_combo_selector();

		//Llamada a la clase general de combos
		$oSino = new clsGeneral($conexion);
		$comboSino = $oSino->Cargar_combo_SiNo_No();
		$comboEntidades = $oSino->Cargar_combo_Entidades();
		$comboAnnos = $oSino->Cargar_Combo_Annos();
		$comboDestinos = $oSino->Cargar_combo_Destinos();
		$comboFolletos = $oSino->Cargar_combo_Folletos();
		$comboCuadros = $oSino->Cargar_combo_Cuadros($parametros['folleto_base0']);
		$comboDivisas = $oSino->Cargar_combo_Divisas();

			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS TARIFARIOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#30A1C7');
			$smarty->assign('grupo', '» PRODUCTO');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» TARIFARIOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTarifarios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('tarifarios', $sTarifarios);

			/*$comboEntidades = $oSino->Cargar_combo_Entidades();
			$comboAnnos = $oSino->Cargar_Combo_Annos();
			$comboDestinos = $oSino->Cargar_combo_Destinos();
			$comboFolletos = $oSino->Cargar_combo_Folletos();
			$comboCuadros = $oSino->Cargar_combo_Cuadros();
			$comboDivisas = $oSino->Cargar_combo_Divisas();*/

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboEntidades', $comboEntidades);
			$smarty->assign('comboAnnos', $comboAnnos);
			$smarty->assign('comboDestinos', $comboDestinos);
			$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboCuadros', $comboCuadros);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboSino', $comboSino);


			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_entidad', '');
			$smarty->assign('buscar_anno', '');
			$smarty->assign('buscar_destino', '');
			$smarty->assign('buscar_noches', '');
			$smarty->assign('buscar_nombre', '');

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE PRECIOS
		$parametros['clave0'] = '';
		$parametros['buscar_fecha_desde_precios'] = '';
		$parametros['buscar_fecha_hasta_precios'] = '';
		$parametros['redondeo0'] = 0;

		$sCabecera_precios = $oTarifarios->Cargar_precios_cabecera($parametros['clave0'],$parametros['buscar_fecha_desde_precios'],$parametros['buscar_fecha_hasta_precios']);	
		$sCabecera_nombres = $oTarifarios->Cargar_nombres_cabecera($parametros['clave0'],$parametros['buscar_fecha_desde_precios'],$parametros['buscar_fecha_hasta_precios']);
		$sPrecios = $oTarifarios->Cargar_precios($parametros['clave0'],$parametros['buscar_fecha_desde_precios'],$parametros['buscar_fecha_hasta_precios'],$parametros['redondeo0']);

			//Llamada a la clase especifica de la pantalla
			$smarty->assign('cabecera_precios', $sCabecera_precios);
			$smarty->assign('cabecera_nombres', $sCabecera_nombres);
			$smarty->assign('precios', $sPrecios);


			//Cargar combos de las lineas de la tabla
			/*$smarty->assign('comboTipo_unidad', $comboTipo_unidad);*/

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_precios', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_precios', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_fecha_desde_precios', '');
			$smarty->assign('buscar_fecha_hasta_precios', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Producto_tarifarios.html');
	}

	$conexion->close();

?>

