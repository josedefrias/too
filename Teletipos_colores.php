<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/teletipos_colores.cls.php';
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

	$parametros = $_POST;
	$parametrosg = $_GET;


			//VARIABLES PARA LOS DATOS GENERALES
		$recuperaid = '';
		$recuperanombre = '';
		$recuperacolor_borde_logo = '';
		$recuperacolor_fondo_logo = '';
		$recuperacolor_cabecera_fondo_logo_izquierda = '';
		$recuperacolor_cabecera_fondo_destino = '';
		$recuperacolor_cabecera_fondo_logo_derecha = '';
		$recuperacolor_cabecera_fuente_destino = '';
		$recuperacolor_cabecera_imagen_fondo = '';
		$recuperacolor_tabla_fondo = '';
		$recuperacolor_cabecera_contenido_fondo = '';
		$recuperacolor_titulo = '';
		$recuperacolor_ciudad_salida = '';
		$recuperacolor_texto_vuelos = '';
		$recuperacolor_bloque_fondo_cabecera = '';
		$recuperacolor_bloque_fondo_imagen = '';
		$recuperacolor_bloque_fondo_precios = '';
		$recuperacolor_cabecera_precios_nombre_hotel = '';
		$recuperacolor_cabecera_precios_categoria_hotel = '';
		$recuperacolor_cabecera_precios_regimen_hotel = '';
		$recuperacolor_cabecera_precios_precio1_hotel = '';
		$recuperacolor_cabecera_precios_precio2_hotel = '';
		$recuperacolor_cabecera_precios_precio3_hotel = '';
		$recuperacolor_precios_nombre_hotel = '';
		$recuperacolor_precios_categoria_hotel = '';
		$recuperacolor_precios_localidad_hotel = '';
		$recuperacolor_precios_regimen_hotel_tabla = '';
		$recuperacolor_precios_regimen_hotel_bloques = '';
		$recuperacolor_precios_precio1_hotel = '';
		$recuperacolor_precios_precio2_hotel = '';
		$recuperacolor_precios_precio3_hotel = '';
		$recuperacolor_pie_fondo = '';
		$recuperacolor_pie_borde = '';
		$recuperacolor_pie_texto = '';

		$mensaje1 = '';
		$mensaje2 = '';
		$error = '';
		$insertaTeletipos_colores = '';

	if(count($_POST) != 0){


		if($parametros['ir_a'] != 'S'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LOS TELETIPOS_COLORES----------
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonTeletipos_colores = new clsTeletipos_colores($conexion, $filadesde, $usuario, $parametros['buscar_id']);
				$botonselec = $botonTeletipos_colores->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){
				//Echo('hola');
				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_COLORES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mTeletipos_colores = new clsTeletipos_colores($conexion, $filadesde, $usuario, $parametros['buscar_id']);

							$modificaTeletipos_colores = $mTeletipos_colores->Modificar($parametros['id'.$num_fila],
																					$parametros['nombre'.$num_fila],
																					$parametros['color_borde_logo'.$num_fila],
																					$parametros['color_fondo_logo'.$num_fila],
																					$parametros['color_cabecera_fondo_logo_izquierda'.$num_fila],
																					$parametros['color_cabecera_fondo_destino'.$num_fila],
																					$parametros['color_cabecera_fondo_logo_derecha'.$num_fila],
																					$parametros['color_cabecera_fuente_destino'.$num_fila],
																					$parametros['color_cabecera_imagen_fondo'.$num_fila],
																					$parametros['color_tabla_fondo'.$num_fila],
																					$parametros['color_cabecera_contenido_fondo'.$num_fila],
																					$parametros['color_titulo'.$num_fila],
																					$parametros['color_ciudad_salida'.$num_fila],
																					$parametros['color_texto_vuelos'.$num_fila],
																					$parametros['color_bloque_fondo_cabecera'.$num_fila],
																					$parametros['color_bloque_fondo_imagen'.$num_fila],
																					$parametros['color_bloque_fondo_precios'.$num_fila],
																					$parametros['color_cabecera_precios_nombre_hotel'.$num_fila],
																					$parametros['color_cabecera_precios_categoria_hotel'.$num_fila],
																					$parametros['color_cabecera_precios_regimen_hotel'.$num_fila],
																					$parametros['color_cabecera_precios_precio1_hotel'.$num_fila],
																					$parametros['color_cabecera_precios_precio2_hotel'.$num_fila],
																					$parametros['color_cabecera_precios_precio3_hotel'.$num_fila],
																					$parametros['color_precios_nombre_hotel'.$num_fila],
																					$parametros['color_precios_categoria_hotel'.$num_fila],
																					$parametros['color_precios_localidad_hotel'.$num_fila],
																					$parametros['color_precios_regimen_hotel_tabla'.$num_fila],
																					$parametros['color_precios_regimen_hotel_bloques'.$num_fila],
																					$parametros['color_precios_precio1_hotel'.$num_fila],
																					$parametros['color_precios_precio2_hotel'.$num_fila],
																					$parametros['color_precios_precio3_hotel'.$num_fila],
																					$parametros['color_pie_fondo'.$num_fila],
																					$parametros['color_pie_borde'.$num_fila],
																					$parametros['color_pie_texto'.$num_fila]);

							if($modificaTeletipos_colores == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaTeletipos_colores;
							}
						}else{

							$mTeletipos_colores = new clsTeletipos_colores($conexion, $filadesde, $usuario, $parametros['buscar_id']);
							$borraTeletipos_colores = $mTeletipos_colores->Borrar($parametros['id'.$num_fila]);
							if($borraTeletipos_colores == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraTeletipos_colores;
								
							}
						}
					}
				}
				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();
			}

			if($parametros['grabar_registro'] == 'S'){
				//Echo('hola_grabar_registro');
				//AÑADIR REGISTROS
				$Ntransacciones = 0;				

				$iTeletipos_colores = new clsTeletipos_colores($conexion, $filadesde, $usuario, $parametros['buscar_id']);

				$insertaTeletipos_colores = $iTeletipos_colores->Insertar($parametros['nombre0'],
																			$parametros['color_borde_logo0'],
																			$parametros['color_fondo_logo0'],
																			$parametros['color_cabecera_fondo_logo_izquierda0'],
																			$parametros['color_cabecera_fondo_destino0'],
																			$parametros['color_cabecera_fondo_logo_derecha0'],
																			$parametros['color_cabecera_fuente_destino0'],
																			$parametros['color_cabecera_imagen_fondo0'],
																			$parametros['color_tabla_fondo0'],
																			$parametros['color_cabecera_contenido_fondo0'],
																			$parametros['color_titulo0'],
																			$parametros['color_ciudad_salida0'],
																			$parametros['color_texto_vuelos0'],
																			$parametros['color_bloque_fondo_cabecera0'],
																			$parametros['color_bloque_fondo_imagen0'],
																			$parametros['color_bloque_fondo_precios0'],
																			$parametros['color_cabecera_precios_nombre_hotel0'],
																			$parametros['color_cabecera_precios_categoria_hotel0'],
																			$parametros['color_cabecera_precios_regimen_hotel0'],
																			$parametros['color_cabecera_precios_precio1_hotel0'],
																			$parametros['color_cabecera_precios_precio2_hotel0'],
																			$parametros['color_cabecera_precios_precio3_hotel0'],
																			$parametros['color_precios_nombre_hotel0'],
																			$parametros['color_precios_categoria_hotel0'],
																			$parametros['color_precios_localidad_hotel0'],
																			$parametros['color_precios_regimen_hotel_tabla0'],
																			$parametros['color_precios_regimen_hotel_bloques0'],
																			$parametros['color_precios_precio1_hotel0'],
																			$parametros['color_precios_precio2_hotel0'],
																			$parametros['color_precios_precio3_hotel0'],
																			$parametros['color_pie_fondo0'],
																			$parametros['color_pie_borde0'],
																			$parametros['color_pie_texto0']);

				if($insertaTeletipos_colores == 'OK'){
					echo('registro insretado');
					$Ntransacciones++;
					$parametros['nuevo_registro'] = 'N';
					$parametros['buscar_id'] = $parametros['id0'];
				}else{
					$error = $insertaTeletipos_colores;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					$recuperanombre = $parametros['nombre0'];
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
			$oTeletipos_colores = new clsTeletipos_colores($conexion, $filadesde, $usuario, $parametros['buscar_id']);
			$sTeletipos_colores = $oTeletipos_colores->Cargar($recuperaid,
																$recuperanombre,
																$recuperacolor_borde_logo,
																$recuperacolor_fondo_logo,
																$recuperacolor_cabecera_fondo_logo_izquierda,
																$recuperacolor_cabecera_fondo_destino,
																$recuperacolor_cabecera_fondo_logo_derecha,
																$recuperacolor_cabecera_fuente_destino,
																$recuperacolor_cabecera_imagen_fondo,
																$recuperacolor_tabla_fondo,
																$recuperacolor_cabecera_contenido_fondo,
																$recuperacolor_titulo,
																$recuperacolor_ciudad_salida,
																$recuperacolor_texto_vuelos,
																$recuperacolor_bloque_fondo_cabecera,
																$recuperacolor_bloque_fondo_imagen,
																$recuperacolor_bloque_fondo_precios,
																$recuperacolor_cabecera_precios_nombre_hotel,
																$recuperacolor_cabecera_precios_categoria_hotel,
																$recuperacolor_cabecera_precios_regimen_hotel,
																$recuperacolor_cabecera_precios_precio1_hotel,
																$recuperacolor_cabecera_precios_precio2_hotel,
																$recuperacolor_cabecera_precios_precio3_hotel,
																$recuperacolor_precios_nombre_hotel,
																$recuperacolor_precios_categoria_hotel,
																$recuperacolor_precios_localidad_hotel,
																$recuperacolor_precios_regimen_hotel_tabla,
																$recuperacolor_precios_regimen_hotel_bloques,
																$recuperacolor_precios_precio1_hotel,
																$recuperacolor_precios_precio2_hotel,
																$recuperacolor_precios_precio3_hotel,
																$recuperacolor_pie_fondo,
																$recuperacolor_pie_borde,
																$recuperacolor_pie_texto);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectTeletipos_colores = $oTeletipos_colores->Cargar_combo_selector();

			$ClaseVisualizar = new clsTeletipos_visualizar($conexion, $filadesde, $usuario, $parametros['buscar_teletipo'], '', $parametros['buscar_id']);
			$html_oferta = $ClaseVisualizar->carga_html_oferta($parametros['buscar_teletipo'], $parametros['buscar_formato'], '', $parametros['buscar_id']);

			//Llamada a la clase general de combos
			$combos = new clsGeneral($conexion);
			$comboTeletipos_colores = $combos->Cargar_combo_Teletipos_Colores();
			$comboTeletipos = $combos->Cargar_combo_Teletipos_SinNull();
			$comboFormato = $combos->Cargar_combo_Teletipos_Formato();



	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS TELETIPOS_COLORES-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#027767');
			$smarty->assign('grupo', '» MARKETING');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» COLORES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTeletipos_colores);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('teletipos_colores', $sTeletipos_colores);

			//Cargar ejemplo de oferta para comprobar los cambios en la combinación
			$smarty->assign('html_oferta', $html_oferta);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTeletipos_colores', $comboTeletipos_colores);
			$smarty->assign('comboTeletipos', $comboTeletipos);
			$smarty->assign('comboFormato', $comboFormato);

			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_teletipo', $parametros['buscar_teletipo']);
			$smarty->assign('buscar_formato', $parametros['buscar_formato']);



			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Teletipos_colores.html');


		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS TELETIPOS_COLORES

		$filadesde = 1;
		if(count($_GET) != 0 and $parametrosg['id'] != ''){
			$parametros['buscar_id'] = $parametrosg['id'];
		}else{
			$parametros['buscar_id'] = "";
		}
		$parametros['buscar_teletipo'] = "";
		$parametros['buscar_formato'] = "";


			//Llamada a la clase especifica de la pantalla
			$oTeletipos_colores = new clsTeletipos_colores($conexion, $filadesde, $usuario, $parametros['buscar_id']);
			$sTeletipos_colores = $oTeletipos_colores->Cargar($recuperaid,
																$recuperanombre,
																$recuperacolor_borde_logo,
																$recuperacolor_fondo_logo,
																$recuperacolor_cabecera_fondo_logo_izquierda,
																$recuperacolor_cabecera_fondo_destino,
																$recuperacolor_cabecera_fondo_logo_derecha,
																$recuperacolor_cabecera_fuente_destino,
																$recuperacolor_cabecera_imagen_fondo,
																$recuperacolor_tabla_fondo,
																$recuperacolor_cabecera_contenido_fondo,
																$recuperacolor_titulo,
																$recuperacolor_ciudad_salida,
																$recuperacolor_texto_vuelos,
																$recuperacolor_bloque_fondo_cabecera,
																$recuperacolor_bloque_fondo_imagen,
																$recuperacolor_bloque_fondo_precios,
																$recuperacolor_cabecera_precios_nombre_hotel,
																$recuperacolor_cabecera_precios_categoria_hotel,
																$recuperacolor_cabecera_precios_regimen_hotel,
																$recuperacolor_cabecera_precios_precio1_hotel,
																$recuperacolor_cabecera_precios_precio2_hotel,
																$recuperacolor_cabecera_precios_precio3_hotel,
																$recuperacolor_precios_nombre_hotel,
																$recuperacolor_precios_categoria_hotel,
																$recuperacolor_precios_localidad_hotel,
																$recuperacolor_precios_regimen_hotel_tabla,
																$recuperacolor_precios_regimen_hotel_bloques,
																$recuperacolor_precios_precio1_hotel,
																$recuperacolor_precios_precio2_hotel,
																$recuperacolor_precios_precio3_hotel,
																$recuperacolor_pie_fondo,
																$recuperacolor_pie_borde,
																$recuperacolor_pie_texto);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectTeletipos_colores = $oTeletipos_colores->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$combos = new clsGeneral($conexion);
			$comboTeletipos_colores = $combos->Cargar_combo_Teletipos_Colores();
			$comboTeletipos = $combos->Cargar_combo_Teletipos_SinNull();
			$comboFormato = $combos->Cargar_combo_Teletipos_Formato();

	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS TELETIPOS_COLORES-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#027767');
			$smarty->assign('grupo', '» MARKETING');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» COLORES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTeletipos_colores);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('teletipos_colores', $sTeletipos_colores);

			//Cargar ejemplo de oferta para comprobar los cambios en la combinación
			$smarty->assign('html_oferta', '');

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTeletipos_colores', $comboTeletipos_colores);
			$smarty->assign('comboTeletipos', $comboTeletipos);
			$smarty->assign('comboFormato', $comboFormato);

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_id', '');
		$smarty->assign('buscar_teletipo', '');
		$smarty->assign('buscar_formato', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Teletipos_colores.html');
	}

	$conexion->close();


?>

