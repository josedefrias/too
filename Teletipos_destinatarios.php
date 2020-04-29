<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/teletipos.cls.php';
	require 'clases/teletipos_destinatarios.cls.php';

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
			$recuperacolores = '';
			$recuperatitulo = '';
			$recuperadias = '';
			$recuperacabecera_imagen = '';
			$recuperalogo_dcto_1 = '';
			$recuperalogo_dcto_2 = '';
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
			$recuperacabecera_3 = '';
			$recuperatexto_libre_1 = '';
			$recuperatexto_libre_2 = '';
			$recuperatexto_libre_3 = '';
		
			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			//$insertaFolleto = '';

			//VARIABLES PARA LOS DESTINATARIOS
			$recuperaemail = ''; 
			$recuperaestado = ''; 
			$mensaje1_destinatarios = '';
			$mensaje2_destinatarios = '';
			$error_destinatarios = '';

//echo($parametrosg['codigo']."-".$parametrosg['clave']."-".$parametrosg['display_cabecera']);

//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//CONTROLAMOS LAS VARIABLES NECESARIAS PARA LLAMAR A LA CLASE RESERVAS DEPENDIENDO DE SI ACABAMOS DE ENTRAR EN LA PANTALLA
	//O YA HEMOS REALIZADO ALGUNA PETICION Y/O VOLVEMOS DE OTRA PANTALLA
//--------------------------------------------------------------------------------------------------------------------------------
	if(count($_POST) != 0){
		$filadesde = $parametros['filadesde'];
		$filadesde_destinatarios = $parametros['filadesde_destinatarios'];

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
		$filadesde_destinatarios = 1;
		if(count($_GET) != 0 and ($parametrosg['id'] != null or $parametrosg['nombre'] != null or $parametrosg['destino'] != null)){
				$parametros['buscar_id'] = $parametrosg['id'];
				$parametros['buscar_nombre'] = $parametrosg['nombre'];
				$parametros['buscar_destino'] = $parametrosg['destino'];
				$parametros['seccion_cabecera_teletipo_display'] = $parametrosg['display_cabecera'];
				$parametros['buscar_provincia'] = '';
				$parametros['buscar_grupo_gestion'] = '';
				$parametros['buscar_minorista'] = '';
				$parametros['buscar_ciudad'] = $parametrosg['ciudad'];
				$parametros['buscar_estado'] = '';
		}else{
				$parametros['buscar_id'] = 0;
				$parametros['buscar_nombre'] = '';
				$parametros['buscar_destino'] = '';
				$parametros['buscar_provincia'] = '';
				$parametros['buscar_grupo_gestion'] = '';
				$parametros['buscar_minorista'] = '';
				$parametros['buscar_estado'] = '';
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
	$ClaseDestinatarios = new clsTeletipos_destinatarios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_destino']);

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

							$modificaTeletipos = $ClaseTeletipos->Modificar($parametros['id'.$num_fila],$parametros['nombre'.$num_fila],$parametros['cuadro'.$num_fila],$parametros['fecha_lanzamiento'.$num_fila],$parametros['destino'.$num_fila], $parametros['formato'.$num_fila], $parametros['colores'.$num_fila],$parametros['titulo'.$num_fila],$parametros['dias'.$num_fila],$parametros['cabecera_imagen'.$num_fila],$parametros['logo_dcto_1'.$num_fila],$parametros['logo_dcto_2'.$num_fila],$parametros['texto_pie'.$num_fila],$parametros['fecha_desde_1'.$num_fila],$parametros['fecha_hasta_1'.$num_fila],$parametros['cabecera_1'.$num_fila],$parametros['fecha_desde_2'.$num_fila],$parametros['fecha_hasta_2'.$num_fila],$parametros['cabecera_2'.$num_fila],$parametros['fecha_desde_3'.$num_fila],$parametros['fecha_hasta_3'.$num_fila],$parametros['cabecera_3'.$num_fila],$parametros['texto_libre_1'.$num_fila],$parametros['texto_libre_2'.$num_fila],$parametros['texto_libre_3'.$num_fila]);
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

				$insertaTeletipos = $ClaseTeletipos->Insertar($parametros['nombre0'],$parametros['fecha_lanzamiento0'],$parametros['cuadro0'],$parametros['destino0'],$parametros['formato0'],$parametros['colores0'],$parametros['titulo0'],$parametros['dias0'],$parametros['cabecera_imagen0'],$parametros['logo_dcto_10'],$parametros['logo_dcto_20'],$parametros['texto_pie0'],$parametros['fecha_desde_10'],$parametros['fecha_hasta_10'],$parametros['cabecera_10'],$parametros['fecha_desde_20'],$parametros['fecha_hasta_20'],$parametros['cabecera_20'],$parametros['fecha_desde_30'],$parametros['fecha_hasta_30'],$parametros['cabecera_30'],$parametros['texto_libre_10'],$parametros['texto_libre_20'],$parametros['texto_libre_30']);

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
					$recuperacolores = $parametros['colores0'];
					$recuperatitulo = $parametros['titulo0'];
					$recuperadias = $parametros['dias0'];
					$recuperacabecera_imagen = $parametros['cabecera_imagen0'];
					$recuperalogo_dcto_1 = $parametros['logo_dcto_10'];
					$recuperalogo_dcto_2 = $parametros['logo_dcto_20'];
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
					$recuperatexto_libre_1 = $parametros['texto_libre_10'];
					$recuperatexto_libre_2 = $parametros['texto_libre_20'];
					$recuperatexto_libre_3 = $parametros['texto_libre_30'];

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
			$sTeletipos = $ClaseTeletipos->Cargar($recuperaid,$recuperanombre,$recuperacuadro,$recuperafecha_lanzamiento,$recuperadestino,$recuperaformato,$recuperacolores,$recuperatitulo,$recuperadias,$recuperacabecera_imagen,$recuperalogo_dcto_1,$recuperalogo_dcto_2,$recuperatexto_pie, $recuperafecha_desde_1, $recuperafecha_hasta_1,$recuperacabecera_1,$recuperafecha_desde_2,$recuperafecha_hasta_2,$recuperacabecera_2,$recuperafecha_desde_3,$recuperafecha_hasta_3,$recuperacabecera_3,$recuperatexto_libre_1,$recuperatexto_libre_2,$recuperatexto_libre_3);
			$sComboSelectTeletipos = $ClaseTeletipos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$comboTeletipos = $combos->Cargar_combo_Teletipos();
			$comboDestinos = $combos->Cargar_combo_Destinos();
			$comboFormato = $combos->Cargar_combo_Teletipos_Formato();
			$comboCuadros = $combos->Cargar_combo_Cuadros_Teletipo();
			$comboLogos = $combos->Cargar_combo_Logos();
			$comboColores = $combos->Cargar_combo_Teletipos_Colores();

//----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS DESTINATARIOS -----------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_destinatarios = $parametros['filadesde_destinatarios'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonselec_destinatarios = $ClaseDestinatarios->Botones_selector_destinatarios($filadesde_destinatarios, $parametros['botonSelector_2nivel']);
				$filadesde_destinatarios = $botonselec_destinatarios;
			}

			//Llamada a la clase especifica de la pantalla
			$sDestinatarios = $ClaseDestinatarios->Cargar_destinatarios($sTeletipos[0]['id'], $filadesde_destinatarios, @$parametros['buscar_provincia'], $parametros['buscar_grupo_gestion'], $parametros['buscar_minorista'], $parametros['buscar_estado']);
			//lineas visualizadas
			$vueltas = count($sDestinatarios);

			if($parametros['actuar_2nivel'] == 'S'){
				
				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_DESTINATARIOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones_destinatarios = 0;
				
				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){

							$modificaDestinatarios = $ClaseDestinatarios->Modificar_destinatarios($parametros['id_envio'.$num_fila], $parametros['email'.$num_fila], $parametros['estado'.$num_fila]);
							if($modificaDestinatarios == 'OK'){
								$Ntransacciones_destinatarios++;
							}else{
								$error_destinatarios = $modificaDestinatarios;
							}

						}else{

							$borraDestinatarios = $ClaseDestinatarios->Borrar_destinatarios($parametros['id_envio'.$num_fila]);
							if($borraDestinatarios == 'OK'){
								$Ntransacciones_destinatarios++;
							}else{
								$error_destinatarios = $borraDestinatarios;
							}
						}
					}
				}

				//AÑADIR REGISTROS
				/*$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_DESTINATARIOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {

					if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){

						$insertaDestinatarios = $ClaseDestinatarios->Insertar_destinatarios($sTeletipos[0]['id'], $parametros['Nuevohotel'.$num_fila], $parametros['Nuevoregimen'.$num_fila], $parametros['Nuevoprecio'.$num_fila]);
						if($insertaDestinatarios == 'OK'){
							$Ntransacciones_destinatarios++;
						}else{
							$error_destinatarios = $insertaDestinatarios;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.

							$recuperahotel = $parametros['Nuevohotel'.$num_fila]; 
							$recuperaregimen = $parametros['Nuevoregimen'.$num_fila];  
							$recuperaprecio = $parametros['Nuevoprecio'.$num_fila]; 

						}
					}
				}*/

				//Mostramos mensajes y posibles errores
				$mensaje1_destinatarios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_destinatarios."</b></font></div>";
				if($error_destinatarios != ''){
					$mensaje2_destinatarios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_destinatarios."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();

			//AÑADIMOS DESTINATARIOS
			}elseif($parametros['actuar_2nivel'] == 'A'){
			$Ntransacciones_destinatarios = '';
			
				$AnadeDestinatarios = $ClaseDestinatarios->Anadir_Grupo_destinatarios($sTeletipos[0]['id'], @$parametros['buscar_provincia'], $parametros['buscar_grupo_gestion'], $parametros['buscar_minorista'], $parametros['ciudad_salida']);
				if($AnadeDestinatarios == 'OK'){
					$Ntransacciones_destinatarios = 'Los destinatarios seleccionados se han añadido correctamente';
				}else{
					$error_destinatarios = $AnadeDestinatarios;
				}
			
				//Mostramos mensajes y posibles errores
				$mensaje1_destinatarios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_destinatarios."</b></font></div>";
				if($error_destinatarios != ''){
					$mensaje2_destinatarios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_destinatarios."</b></font></div>";
				}
		
			}elseif($parametros['actuar_2nivel'] == 'B'){
			$Ntransacciones_destinatarios = '';
			
				$BorraDestinatarios = $ClaseDestinatarios->Borrar_Grupo_destinatarios($sTeletipos[0]['id'], @$parametros['buscar_provincia'], $parametros['buscar_grupo_gestion'], $parametros['buscar_minorista']);
				if($BorraDestinatarios == 'OK'){
					$Ntransacciones_destinatarios = 'Los destinatarios seleccionados se han borrado correctamente';
				}else{
					$error_destinatarios = $BorraDestinatarios;
				}
			
				//Mostramos mensajes y posibles errores
				$mensaje1_destinatarios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_destinatarios."</b></font></div>";
				if($error_destinatarios != ''){
					$mensaje2_destinatarios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_destinatarios."</b></font></div>";
				}
		
			}elseif($parametros['actuar_2nivel'] == 'V'){
			$Ntransacciones_destinatarios = '';
			
				$ActivaDestinatarios = $ClaseDestinatarios->Activar_Grupo_destinatarios($sTeletipos[0]['id'], @$parametros['buscar_provincia'], $parametros['buscar_grupo_gestion'], $parametros['buscar_minorista']);
				if($ActivaDestinatarios == 'OK'){
					$Ntransacciones_destinatarios = 'Los envios seleccionados se han activado correctamente';
				}else{
					$error_destinatarios = $ActivaDestinatarios;
				}
			
				//Mostramos mensajes y posibles errores
				$mensaje1_destinatarios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_destinatarios."</b></font></div>";
				if($error_destinatarios != ''){
					$mensaje2_destinatarios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_destinatarios."</b></font></div>";
				}
		
			}elseif($parametros['actuar_2nivel'] == 'D'){
			$Ntransacciones_destinatarios = '';
			
				$ActivaDestinatarios = $ClaseDestinatarios->Desctivar_Grupo_destinatarios($sTeletipos[0]['id'], @$parametros['buscar_provincia'], $parametros['buscar_grupo_gestion'], $parametros['buscar_minorista']);
				if($ActivaDestinatarios == 'OK'){
					$Ntransacciones_destinatarios = 'Los envios seleccionados se han desactivado correctamente';
				}else{
					$error_destinatarios = $ActivaDestinatarios;
				}
			
				//Mostramos mensajes y posibles errores
				$mensaje1_destinatarios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_destinatarios."</b></font></div>";
				if($error_destinatarios != ''){
					$mensaje2_destinatarios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_destinatarios."</b></font></div>";
				}
		
			}

			//Llamada a la clase especifica de la pantalla
			$sDestinatarios = $ClaseDestinatarios->Cargar_destinatarios($sTeletipos[0]['id'], $filadesde_destinatarios, @$parametros['buscar_provincia'], $parametros['buscar_grupo_gestion'], $parametros['buscar_minorista'], $parametros['buscar_estado']);	
			$sComboSelectDestinatarios = $ClaseDestinatarios->Cargar_combo_selector_destinatarios($sTeletipos[0]['id'], $filadesde_destinatarios, @$parametros['buscar_provincia'], $parametros['buscar_grupo_gestion'], $parametros['buscar_minorista'], $parametros['buscar_estado']);
			//$sDestinatarios_nuevos = $ClaseDestinatarios->Cargar_lineas_nuevas_destinatarios();	

			//Llamada a la clase general de combos
			$comboProvincias = $combos->Cargar_combo_Provincias_Seleccion1();
			$combogrupos_gestion = $combos->Cargar_combo_Grupos_gestion();
			$comboEstadoEnvio = $combos->Cargar_Combo_Estado_Envios();
			$comboEstadoEnvio_Todos = $combos->Cargar_Combo_Estado_Envios_Todos();
			$comboCiudades_Salida = $combos->Cargar_combo_Cuadros_Aereos_Ciudades_Teletipos($sTeletipos[0]['id']);

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
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#027767');
			$smarty->assign('grupo', '» MARKETING');
			$smarty->assign('subgrupo', '» TELETIPOS');
			$smarty->assign('formulario', '» CREACION » DESTINATARIOS');

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
			$smarty->assign('comboColores', $comboColores);

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
			//----VISUALIZAR PARTE DE LAS DESTINATARIOS----------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_destinatarios', $filadesde_destinatarios);

			//Cargar combo selector
			$smarty->assign('combo_destinatarios', $sComboSelectDestinatarios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('destinatarios', $sDestinatarios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('combogrupos_gestion', $combogrupos_gestion);
			$smarty->assign('comboEstadoEnvio', $comboEstadoEnvio);
			$smarty->assign('comboEstadoEnvio_Todos', $comboEstadoEnvio_Todos);
			$smarty->assign('comboCiudades_Salida', $comboCiudades_Salida);
			

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('destinatariosnuevos', $sDestinatarios_nuevos);	

			$smarty->assign('recuperaemail', $recuperaemail);	
			$smarty->assign('recuperaestado', $recuperaestado);


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_destinatarios', $mensaje1_destinatarios);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_destinatarios', $mensaje2_destinatarios);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_provincia', @$parametros['buscar_provincia']);
			$smarty->assign('buscar_grupo_gestion', $parametros['buscar_grupo_gestion']);
			$smarty->assign('buscar_minorista', $parametros['buscar_minorista']);
			$smarty->assign('buscar_ciudad', $parametros['buscar_ciudad']);
			$smarty->assign('buscar_estado', $parametros['buscar_estado']);
			$smarty->assign('ciudad_salida', '');


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Teletipos_destinatarios.html');

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
			$sTeletipos = $ClaseTeletipos->Cargar($recuperaid,$recuperanombre,$recuperacuadro,$recuperafecha_lanzamiento,$recuperadestino,$recuperaformato,$recuperacolores,$recuperatitulo,$recuperadias,$recuperacabecera_imagen,$recuperalogo_dcto_1,$recuperalogo_dcto_2,$recuperatexto_pie, $recuperafecha_desde_1, $recuperafecha_hasta_1,$recuperacabecera_1,$recuperafecha_desde_2,$recuperafecha_hasta_2,$recuperacabecera_2,$recuperafecha_desde_3,$recuperafecha_hasta_3,$recuperacabecera_3,$recuperatexto_libre_1,$recuperatexto_libre_2,$recuperatexto_libre_3);
			$sComboSelectTeletipos = $ClaseTeletipos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$comboTeletipos = $combos->Cargar_combo_Teletipos();
			$comboDestinos = $combos->Cargar_combo_Destinos();
			$comboFormato = $combos->Cargar_combo_Teletipos_Formato();
			$comboCuadros = $combos->Cargar_combo_Cuadros_Teletipo();
			$comboLogos = $combos->Cargar_combo_Logos();
			$comboColores = $combos->Cargar_combo_Teletipos_Colores();


			//Establecer plantilla smarty
			$smarty = new Smarty;

			//--------------------------------------------------------
			//----VISUALIZAR PARTE DE TELETIPOS -----------------------
			//--------------------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('cabecera_teletipo', 'plantillas/Teletipos_cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#027767');
			$smarty->assign('grupo', '» MARKETING');
			$smarty->assign('subgrupo', '» TELETIPOS');
			$smarty->assign('formulario', '» CREACION » DESTINATARIOS');

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
			$smarty->assign('comboColores', $comboColores);

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
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE DESTINATARIOS

			//Llamada a la clase especifica de la pantalla
			$sDestinatarios = $ClaseDestinatarios->Cargar_destinatarios($sTeletipos[0]['id'], $filadesde_destinatarios, $parametros['buscar_provincia'], $parametros['buscar_grupo_gestion'], $parametros['buscar_minorista'], $parametros['buscar_estado']);	
			$sComboSelectDestinatarios = $ClaseDestinatarios->Cargar_combo_selector_destinatarios($sTeletipos[0]['id'], $filadesde_destinatarios, @$parametros['buscar_provincia'], $parametros['buscar_grupo_gestion'], $parametros['buscar_minorista'], $parametros['buscar_estado']);
			//$sDestinatarios_nuevos = $ClaseDestinatarios->Cargar_lineas_nuevas_destinatarios();	

			//Llamada a la clase general de combos
			$comboProvincias = $combos->Cargar_combo_Provincias_Seleccion1();
			$combogrupos_gestion = $combos->Cargar_combo_Grupos_gestion();
			$comboEstadoEnvio = $combos->Cargar_Combo_Estado_Envios();
			$comboEstadoEnvio_Todos = $combos->Cargar_Combo_Estado_Envios_Todos();
			$comboCiudades_Salida = $combos->Cargar_combo_Cuadros_Aereos_Ciudades_Teletipos($sTeletipos[0]['id']);

			
			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_destinatarios', $filadesde_destinatarios);

			//Cargar combo selector
			$smarty->assign('combo_destinatarios', $sComboSelectDestinatarios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('destinatarios', $sDestinatarios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('combogrupos_gestion', $combogrupos_gestion);
			$smarty->assign('comboEstadoEnvio', $comboEstadoEnvio);
			$smarty->assign('comboEstadoEnvio_Todos', $comboEstadoEnvio_Todos);
			$smarty->assign('comboCiudades_Salida', $comboCiudades_Salida);
			

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('destinatariosnuevos', $sDestinatarios_nuevos);	

			$smarty->assign('recuperaemail', $recuperaemail);
			$smarty->assign('recuperaestado', $recuperaestado);


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_destinatarios', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_destinatarios', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_provincia', '');
			$smarty->assign('buscar_grupo_gestion', '');
			$smarty->assign('buscar_minorista', '');
			$smarty->assign('buscar_estado', '');
			$smarty->assign('ciudad_salida', '');
			if(count($_GET) != 0 and ($parametrosg['ciudad'] != null)){
				$smarty->assign('buscar_ciudad', $parametrosg['ciudad']);
			}else{
				$smarty->assign('buscar_ciudad', '');
			}

			
		//Visualizar plantilla
		$smarty->display('plantillas/Teletipos_destinatarios.html');
	}

	$conexion->close();


?>

