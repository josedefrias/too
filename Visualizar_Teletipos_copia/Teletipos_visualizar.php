<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/teletipos.cls.php';
	require 'clases/teletipos_visualizar.cls.php';

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
			$recuperaid = '';
			$recuperanombre = '';
			$recuperacuadro = '';
			$recuperafecha_lanzamiento = '';
			$recuperadestino = '';
			$recuperaformato = '';
			$recuperatitulo = '';
			$recuperacabecera_imagen = '';
			$recuperatexto_pie = '';
			$recuperatexto_pie = '';
			$recuperafecha_desde_1 = '';
			$recuperafecha_hasta_1 = '';
			$recuperacabecera_1 = '';
			$recuperafecha_desde_2 = '';
			$recuperafecha_hasta_2 = '';
			$recuperacabecera_2 = '';
			$recuperafecha_desde_3 = '';
			$recuperafecha_hasta_3 = '';
			$recuperacabecera_3 = '';
		
			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			//$insertaFolleto = '';

			//VARIABLES PARA LOS TEXTOS
			$recuperahotel = ''; 
			$recuperaregimen = ''; 
			$recuperaprecio = '';
			$mensaje1_hoteles = '';
			$mensaje2_hoteles = '';
			$error_hoteles = '';

//echo($parametrosg['codigo']."-".$parametrosg['clave']."-".$parametrosg['display_cabecera']);

//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//CONTROLAMOS LAS VARIABLES NECESARIAS PARA LLAMAR A LA CLASE RESERVAS DEPENDIENDO DE SI ACABAMOS DE ENTRAR EN LA PANTALLA
	//O YA HEMOS REALIZADO ALGUNA PETICION Y/O VOLVEMOS DE OTRA PANTALLA
//--------------------------------------------------------------------------------------------------------------------------------
	if(count($_POST) != 0){
		$filadesde = $parametros['filadesde'];
		//$filadesde_destinatarios = $parametros['filadesde_destinatarios'];

		//esto es por si venimos de otra pantalla
		if($parametros['buscar_id'] == null and $parametros['buscar_nombre'] == null and $parametros['buscar_destino'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_id'] = $parametrosg['id'];
				$parametros['buscar_nombre'] = $parametrosg['nombre'];
				$parametros['buscar_destino'] = $parametrosg['destino'];
				$parametros['buscar_ciudad'] = $parametrosg['ciudad'];
			}
		}
	}else{
		//SI ACABAMOS DE ENTRAR EN LA PANTALLA
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LAS RESERVAS
		//echo($parametrosg['display_cabecera']);
		$filadesde = 1;
		//$filadesde_hoteles = 1;
		if(count($_GET) != 0 and ($parametrosg['id'] != null or $parametrosg['nombre'] != null or $parametrosg['destino'] != null)){
				$parametros['buscar_id'] = $parametrosg['id'];
				$parametros['buscar_nombre'] = $parametrosg['nombre'];
				$parametros['buscar_destino'] = $parametrosg['destino'];
				$parametros['seccion_cabecera_teletipo_display'] = $parametrosg['display_cabecera'];
				$parametros['buscar_ciudad'] = $parametrosg['ciudad'];
		}else{
				$parametros['buscar_id'] = 0;
				$parametros['buscar_nombre'] = '';
				$parametros['buscar_destino'] = '';
				$parametros['buscar_ciudad'] = '';
				$parametros['seccion_cabecera_teletipo_display'] = 'block';
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
	$ClaseTeletipos = new clsTeletipos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_destino']);
	$ClaseVisualizar = new clsTeletipos_visualizar($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_destino']);

   //TAMBIEN LLAMAMOS A LA CLASE GENERAL PARA CARGAR LOS COMBOS DE LA PANTALLA
	$combos = new clsGeneral($conexion);

	if(count($_POST) != 0){


		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'C' and $parametros['ir_a'] != 'HO' and $parametros['ir_a'] != 'VI' and $parametros['ir_a'] != 'DE' and $parametros['ir_a'] != 'AE'){

			//------------------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DEL TELETIPO-------------------------
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
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$modificaTeletipos = $ClaseTeletipos->Modificar($parametros['id'.$num_fila],$parametros['nombre'.$num_fila],$parametros['cuadro'.$num_fila],$parametros['fecha_lanzamiento'.$num_fila],$parametros['destino'.$num_fila], $parametros['formato'.$num_fila],$parametros['titulo'.$num_fila],$parametros['cabecera_imagen'.$num_fila],$parametros['texto_pie'.$num_fila],$parametros['fecha_desde_1'.$num_fila],$parametros['fecha_hasta_1'.$num_fila],$parametros['cabecera_1'.$num_fila],$parametros['fecha_desde_2'.$num_fila],$parametros['fecha_hasta_2'.$num_fila],$parametros['cabecera_2'.$num_fila],$parametros['fecha_desde_3'.$num_fila],$parametros['fecha_hasta_3'.$num_fila],$parametros['cabecera_3'.$num_fila]);
							if($modificaTeletipos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaTeletipos;
							}
						}else{
							$borraTeletipos = $ClaseTeletipos->Borrar($parametros['id'.$num_fila]);
							if($borraTeletipos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraTeletipos;
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

				$insertaTeletipos = $ClaseTeletipos->Insertar($parametros['nombre0'],$parametros['fecha_lanzamiento0'],$parametros['cuadro0'],$parametros['destino0'],$parametros['formato0'],$parametros['titulo0'],$parametros['cabecera_imagen0'],$parametros['texto_pie0'],$parametros['fecha_desde_10'],$parametros['fecha_hasta_10'],$parametros['cabecera_10'],$parametros['fecha_desde_20'],$parametros['fecha_hasta_20'],$parametros['cabecera_20'],$parametros['fecha_desde_30'],$parametros['fecha_hasta_30'],$parametros['cabecera_30']);

				if($insertaTeletipos == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_id'] = $parametros['id0']; 
					$parametros['buscar_nombre'] = $parametros['nombre0']; 
					$parametros['buscar_destino'] = $parametros['destino0'];					
					
					
				}else{
					$error = $insertaTeletipos;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					$recuperaid = $parametros['id0'];
					$recuperanombre = $parametros['nombre0'];
					$recuperacuadro = $parametros['cuadro0'];
					$recuperafecha_lanzamiento = $parametros['fecha_lanzamiento0'];
					$recuperadestino = $parametros['destino0'];
					$recuperaformato = $parametros['formato0'];
					$recuperatitulo_solo_vuelo = $parametros['titulo0'];
					$recuperacabecera_imagen = $parametros['cabecera_imagen0'];
					$recuperatexto_pie = $parametros['texto_pie0'];
					$recuperafecha_desde_1 = $parametros['fecha_desde_10'];
					$recuperafecha_hasta_1 = $parametros['fecha_hasta_10'];
					$recuperacabecera_1 = $parametros['cabecera_10'];
					$recuperafecha_desde_2 = $parametros['fecha_desde_20'];
					$recuperafecha_hasta_2 = $parametros['fecha_hasta_20'];
					$recuperacabecera_2 = $parametros['cabecera_20'];
					$recuperafecha_desde_3 = $parametros['fecha_desde_30'];
					$recuperafecha_hasta_3 = $parametros['fecha_hasta_30'];
					$recuperacabecera_3 = $parametros['cabecera_30'];

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
			$sTeletipos = $ClaseTeletipos->Cargar($recuperaid,$recuperanombre,$recuperacuadro,$recuperafecha_lanzamiento,$recuperadestino,$recuperaformato,$recuperatitulo,$recuperacabecera_imagen,$recuperatexto_pie, $recuperafecha_desde_1, $recuperafecha_hasta_1,$recuperacabecera_1,$recuperafecha_desde_2,$recuperafecha_hasta_2,$recuperacabecera_2,$recuperafecha_desde_3,$recuperafecha_hasta_3,$recuperacabecera_3);
			$sComboSelectTeletipos = $ClaseTeletipos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$comboTeletipos = $combos->Cargar_combo_Teletipos();
			$comboDestinos = $combos->Cargar_combo_Destinos();
			$comboFormato = $combos->Cargar_combo_Teletipos_Formato();
			$comboCuadros = $combos->Cargar_combo_Cuadros();
			$comboLogos = $combos->Cargar_combo_Logos();

//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR -----------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			if($parametros['actuar_2nivel'] == 'E'){
				//ENVIAR MAIL DE PRUEBA
				$envio_oferta = $ClaseVisualizar->Enviar_Oferta_Mail_Especifico($sTeletipos[0]['id'],$parametros['direccion_correo_prueba'],$parametros['buscar_ciudad']);	
			}

			//Llamada a la clase especifica de la pantalla
			$sHoteles = $ClaseVisualizar->Cargar_hoteles($sTeletipos[0]['id']);	

			$html_oferta = $ClaseVisualizar->carga_html_oferta($sTeletipos[0]['id'], $sTeletipos[0]['formato'], $parametros['buscar_ciudad']);	

			//Llamada a la clase general de combos
			$comboCiudades = $combos->Cargar_combo_Cuadros_Aereos_Ciudades($sTeletipos[0]['cuadro']);


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
			//----VISUALIZAR PARTE DE LOS TELETIPOS--------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('cabecera_teletipo', 'plantillas/Teletipos_cabecera.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('formulario', 'TELETIPOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTeletipos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('teletipos', $sTeletipos);

			//Indicamos si hay que visualizar o no la seccion LA CABECERA
			$smarty->assign('seccion_cabecera_teletipo_display', $parametros['seccion_cabecera_teletipo_display']);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTeletipos', $comboTeletipos);
			$smarty->assign('comboDestinos', $comboDestinos);
			$smarty->assign('comboFormato', $comboFormato);
			$smarty->assign('comboCuadros', $comboCuadros);
			$smarty->assign('comboLogos', $comboLogos);

			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_destino', $parametros['buscar_destino']);
//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------

			//---------------------------------------------
			//----VISUALIZAR PARTE DE VISUALIZAR----------
			//---------------------------------------------

			//Cargar datos
			//echo($parametros['formato0']);

			//$smarty->assign('formato', $parametros['formato0']);
			$smarty->assign('formato', $sTeletipos[0]['formato']);

			/*if($sTeletipos[0]['formato'] == '0'){

				$smarty->assign('destino_visualizar', $sTeletipos[0]['destino']);	
				$smarty->assign('titulo_solo_vuelo', $sTeletipos[0]['titulo_solo_vuelo']);
				$smarty->assign('texto_vuelo_ida', $sTeletipos[0]['texto_vuelo_ida']);
				$smarty->assign('texto_vuelo_regreso', $sTeletipos[0]['texto_vuelo_regreso']);
				$smarty->assign('precio_solo_vuelo', $sTeletipos[0]['precio_solo_vuelo']);
				$smarty->assign('titulo_paquetes', $sTeletipos[0]['titulo_paquetes']);
				$smarty->assign('cabecera_hotel', $sTeletipos[0]['cabecera_hotel']);
				$smarty->assign('cabecera_imagen', $sTeletipos[0]['cabecera_imagen']);
				$smarty->assign('texto_pie', $sTeletipos[0]['texto_pie']);
				
				//Cargar lineas de los hoteles
				$smarty->assign('hoteles', $sHoteles);
			}*/

			//if($sTeletipos[0]['formato'] == '1'){

				$smarty->assign('html_oferta', $html_oferta);	

			//}

			$smarty->assign('comboCiudades', $comboCiudades);

			//Cargar mensaje de numero de transacciones realizadas
			//$smarty->assign('mensaje1_hoteles', $mensaje1_visualizar);

			//Cargar mensaje de error si se da error
			//$smarty->assign('mensaje2_hoteles', $mensaje2_visualizar);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_ciudad', $parametros['buscar_ciudad']);
			$smarty->assign('direccion_correo_prueba', $parametros['direccion_correo_prueba']);


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Teletipos_visualizar.html');

				/*$parametros['buscar_codigo'] = $parametrosg['codigo'];
				$parametros['buscar_cuadro'] = $parametrosg['clave'];
				$parametros['seccion_cabecera_cuadro_display'] = $parametrosg['display_cabecera'];*/

		}elseif($parametros['ir_a'] == 'HO'){
			//echo($parametros['ir_a']);
			header("Location: Teletipos_hoteles.php?id=".$parametros['buscar_id']."&nombre=".$parametros['buscar_nombre']."&fecha_salida=".$parametros['buscar_fecha_salida']."&destino=".$parametros['buscar_destino']."&display_cabecera=".$parametros['seccion_cabecera_teletipo_display']."&ciudad=".$parametros['buscar_ciudad']);

		}elseif($parametros['ir_a'] == 'AE'){

			header("Location: Teletipos_aereos.php?id=".$parametros['buscar_id']."&nombre=".$parametros['buscar_nombre']."&fecha_salida=".$parametros['buscar_fecha_salida']."&destino=".$parametros['buscar_destino']."&display_cabecera=".$parametros['seccion_cabecera_teletipo_display']."&ciudad=".$parametros['buscar_ciudad']);

		}elseif($parametros['ir_a'] == 'VI'){

			header("Location: Teletipos_visualizar.php?id=".$parametros['buscar_id']."&nombre=".$parametros['buscar_nombre']."&fecha_salida=".$parametros['buscar_fecha_salida']."&destino=".$parametros['buscar_destino']."&display_cabecera=".$parametros['seccion_cabecera_teletipo_display']."&ciudad=".$parametros['buscar_ciudad']);

		}elseif($parametros['ir_a'] == 'DE'){

			header("Location: Teletipos_destinatarios.php?id=".$parametros['buscar_id']."&nombre=".$parametros['buscar_nombre']."&fecha_salida=".$parametros['buscar_fecha_salida']."&destino=".$parametros['buscar_destino']."&display_cabecera=".$parametros['seccion_cabecera_teletipo_display']."&ciudad=".$parametros['buscar_ciudad']);

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
			$sTeletipos = $ClaseTeletipos->Cargar($recuperaid,$recuperanombre,$recuperacuadro,$recuperafecha_lanzamiento,$recuperadestino,$recuperaformato,$recuperatitulo,$recuperacabecera_imagen,$recuperatexto_pie, $recuperafecha_desde_1, $recuperafecha_hasta_1,$recuperacabecera_1,$recuperafecha_desde_2,$recuperafecha_hasta_2,$recuperacabecera_2,$recuperafecha_desde_3,$recuperafecha_hasta_3,$recuperacabecera_3);
			$sComboSelectTeletipos = $ClaseTeletipos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$comboTeletipos = $combos->Cargar_combo_Teletipos();
			$comboDestinos = $combos->Cargar_combo_Destinos();
			$comboFormato = $combos->Cargar_combo_Teletipos_Formato();
			$comboCuadros = $combos->Cargar_combo_Cuadros();
			$comboLogos = $combos->Cargar_combo_Logos();

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//--------------------------------------------------------
			//----VISUALIZAR PARTE DE CUADROS -----------------------
			//--------------------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('cabecera_teletipo', 'plantillas/Teletipos_cabecera.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('formulario', 'TELETIPOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTeletipos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('teletipos', $sTeletipos);

			//Indicamos si hay que visualizar o no la seccion LA CABECERA
			$smarty->assign('seccion_cabecera_teletipo_display', $parametros['seccion_cabecera_teletipo_display']);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTeletipos', $comboTeletipos);
			$smarty->assign('comboDestinos', $comboDestinos);
			$smarty->assign('comboFormato', $comboFormato);
			$smarty->assign('comboCuadros', $comboCuadros);
			$smarty->assign('comboLogos', $comboLogos);

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			if(count($_GET) != 0 and ($parametrosg['id'] != null or $parametrosg['nombre'] != null or $parametrosg['destino'] != null)){
				$smarty->assign('buscar_id', $parametrosg['id']);
				$smarty->assign('buscar_nombre', $parametrosg['nombre']);
				$smarty->assign('buscar_destino', $parametrosg['destino']);			
			}else{
				$smarty->assign('buscar_id', '');
				$smarty->assign('buscar_nombre', '');
				$smarty->assign('buscar_destino', '');	
				$smarty->assign('seccion_cabecera_teletipo_display', 'block');
			}			


//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------


		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE HOTELES

			//Llamada a la clase especifica de la pantalla
			

			$comboCiudades = $combos->Cargar_combo_Cuadros_Aereos_Ciudades($sTeletipos[0]['cuadro']);

			$smarty->assign('formato', $sTeletipos[0]['formato']);

			/*if($sTeletipos[0]['formato'] == '0'){

				$sHoteles = $ClaseVisualizar->Cargar_hoteles($sTeletipos[0]['id']);					
				//Cargar datos
				$smarty->assign('destino_visualizar', $sTeletipos[0]['destino']);	

				$smarty->assign('titulo_solo_vuelo', $sTeletipos[0]['titulo_solo_vuelo']);
				$smarty->assign('texto_vuelo_ida', $sTeletipos[0]['texto_vuelo_ida']);
				$smarty->assign('texto_vuelo_regreso', $sTeletipos[0]['texto_vuelo_regreso']);
				$smarty->assign('precio_solo_vuelo', $sTeletipos[0]['precio_solo_vuelo']);
				$smarty->assign('titulo_paquetes', $sTeletipos[0]['titulo_paquetes']);
				$smarty->assign('cabecera_hotel', $sTeletipos[0]['cabecera_hotel']);
				$smarty->assign('cabecera_imagen', $sTeletipos[0]['cabecera_imagen']);
				$smarty->assign('texto_pie', $sTeletipos[0]['texto_pie']);

				//Cargar lineas de la tabla para visualizar modificar o borrar
				//$smarty->assign('hoteles', $sHoteles);


			}*/

			//if($sTeletipos[0]['formato'] == '1'){

				$html_oferta = $ClaseVisualizar->carga_html_oferta($sTeletipos[0]['id'],$sTeletipos[0]['formato'], $parametros['buscar_ciudad']);	

				//Llamada a la clase general de combos
				$smarty->assign('html_oferta', $html_oferta);	
			//}

			$smarty->assign('comboCiudades', $comboCiudades);

			//Cargar mensaje de numero de transacciones realizadas
			//$smarty->assign('mensaje1_hoteles', '');

			//Cargar mensaje de error si se da error
			//$smarty->assign('mensaje2_hoteles', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			if(count($_GET) != 0 and ($parametrosg['ciudad'] != null)){
				$smarty->assign('buscar_ciudad', $parametrosg['ciudad']);
			}else{
				$smarty->assign('buscar_ciudad', '');
			}
			$smarty->assign('direccion_correo_prueba', '');		


			
		//Visualizar plantilla
		$smarty->display('plantillas/Teletipos_visualizar.html');
	}

	$conexion->close();


?>

