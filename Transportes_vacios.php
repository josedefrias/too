<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/transportes_vacios.cls.php';

	session_start();

	$usuario = $_SESSION['usuario'];
	$nombre =  $_SESSION['nombre'];

	/*echo('<pre>');
	print_r($usuario);
	echo('-');
	print_r($nombre);
	echo('</pre>');*/

	/*echo('<pre>');
	print_r($_POST);
	echo('</pre>');*/

	/*echo('<pre>');
	print_r($_FILES);
	echo('</pre>');*/

	$parametros = $_POST;
	$parametrosg = $_GET;
	$mensaje1 = '';
	$mensaje2 = '';
	$error = '';
	$insertaTransportes_vacios = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacia= '';
	$recuperaorigen = '';
	$recuperadestino = '';
	$recuperafecha = '';
	$recuperadia_semana = '';
	$recuperahora = '';
	$recuperatipo = '';
	$recuperaplazas = '';
	$recuperaprecio = '';
	$recuperavalor_aire = '';
	$recuperavalor_equipaje = '';
	$recuperavalor_tv = '';
	$recuperavalor_reclinables = '';
	$recuperavalor_minusvalidos = '';
	$recuperavalor_wc = '';



	if(count($_POST) != 0){
		//esto es por si venimos de la pantalla de Transportes
		/*if($parametros['buscar_cia'] == null and $parametros['buscar_acuerdo'] == null and $parametros['buscar_subacuerdo'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_cia'] = $parametrosg['cia'];
				$parametros['buscar_acuerdo'] = $parametrosg['acuerdo'];
				$parametros['buscar_subacuerdo'] = $parametrosg['subacuerdo'];
			}
		}else{
			if($parametros['buscar_subacuerdo'] == null){
				$parametros['buscar_subacuerdo'] = 1;
			}

		}*/
		
		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'V'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonTransportes_vacios = new clsTransportes_vacios($conexion, $filadesde, $usuario, 
													$parametros['buscar_cia'], 
													$parametros['buscar_tipo'], 
													$parametros['buscar_fecha'], 
													$parametros['buscar_fecha_hasta'],
													$parametros['buscar_origen'], 
													$parametros['buscar_destino'], 
													$parametros['buscar_plazas'], 
													$parametros['buscar_precio'], 
													$parametros['buscar_estado'], 
													$parametros['buscar_dia_semana'],
													$parametros['buscar_id']);
				$botonselec = $botonTransportes_vacios->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oTransportes_vacios = new clsTransportes_vacios($conexion, $filadesde, $usuario, 
													$parametros['buscar_cia'], 
													$parametros['buscar_tipo'], 
													$parametros['buscar_fecha'], 
													$parametros['buscar_fecha_hasta'],
													$parametros['buscar_origen'], 
													$parametros['buscar_destino'], 
													$parametros['buscar_plazas'], 
													$parametros['buscar_precio'], 
													$parametros['buscar_estado'], 
													$parametros['buscar_dia_semana'],
													$parametros['buscar_id']);
			$sTransportes_vacios = $oTransportes_vacios->Cargar();
			//lineas visualizadas
			$vueltas = count($sTransportes_vacios);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_VACIOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mTransportes_vacios = new clsTransportes_vacios($conexion, $filadesde, $usuario, 
													$parametros['buscar_cia'], 
													$parametros['buscar_tipo'], 
													$parametros['buscar_fecha'], 
													$parametros['buscar_fecha_hasta'],
													$parametros['buscar_origen'], 
													$parametros['buscar_destino'], 
													$parametros['buscar_plazas'], 
													$parametros['buscar_precio'], 
													$parametros['buscar_estado'], 
													$parametros['buscar_dia_semana'],
													$parametros['buscar_id']);

							$modificaTransportes_vacios = $mTransportes_vacios->Modificar($parametros['id'.$num_fila], 
																         $parametros['hora'.$num_fila], 
																         $parametros['tipo'.$num_fila],
																         $parametros['plazas'.$num_fila],
																         $parametros['precio'.$num_fila], 
																         $parametros['valor_tv'.$num_fila],
																         $parametros['valor_equipaje'.$num_fila],
																         $parametros['valor_aire'.$num_fila],
																         $parametros['valor_reclinables'.$num_fila],
																         $parametros['valor_minusvalidos'.$num_fila],
																         $parametros['valor_wc'.$num_fila],
																         $parametros['estado'.$num_fila]);
							if($modificaTransportes_vacios == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaTransportes_vacios;
								
							}
						}else{

							$mTransportes_vacios = new clsTransportes_vacios($conexion, $filadesde, $usuario, 
													$parametros['buscar_cia'], 
													$parametros['buscar_tipo'], 
													$parametros['buscar_fecha'], 
													$parametros['buscar_fecha_hasta'],
													$parametros['buscar_origen'], 
													$parametros['buscar_destino'], 
													$parametros['buscar_plazas'], 
													$parametros['buscar_precio'], 
													$parametros['buscar_estado'], 
													$parametros['buscar_dia_semana'],
													$parametros['buscar_id']);
							$borraTransportes_vacios = $mTransportes_vacios->Borrar($parametros['id'.$num_fila]);
							if($borraTransportes_vacios == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraTransportes_vacios;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_VACIOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iTransportes_vacios = new clsTransportes_vacios($conexion, $filadesde, $usuario, 
													$parametros['buscar_cia'], 
													$parametros['buscar_tipo'], 
													$parametros['buscar_fecha'], 
													$parametros['buscar_fecha_hasta'],
													$parametros['buscar_origen'], 
													$parametros['buscar_destino'], 
													$parametros['buscar_plazas'], 
													$parametros['buscar_precio'], 
													$parametros['buscar_estado'], 
													$parametros['buscar_dia_semana'],
													$parametros['buscar_id']);

						$insertaTransportes_vacios = $iTransportes_vacios->Insertar($parametros['Nuevocia'.$num_fila], 
															$parametros['Nuevocod_origen'.$num_fila], 
															$parametros['Nuevocod_destino'.$num_fila],
															$parametros['Nuevofecha'.$num_fila],
															$parametros['Nuevohora'.$num_fila],
															$parametros['Nuevoplazas'.$num_fila],
															$parametros['Nuevoprecio'.$num_fila],
															$parametros['Nuevotipo'.$num_fila],
															$parametros['Nuevovalor_tv'.$num_fila],
															$parametros['Nuevovalor_equipaje'.$num_fila],
															$parametros['Nuevovalor_aire'.$num_fila],
															$parametros['Nuevovvalor_reclinables'.$num_fila],
															$parametros['Nuevovvalor_minusvalidos'.$num_fila],
															$parametros['Nuevovvalor_wc'.$num_fila]);

						if($insertaTransportes_vacios == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaTransportes_vacios;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							/*$recuperacia = $parametros['Nuevocia'.$num_fila]; 
							$recuperaacuerdo = $parametros['Nuevoacuerdo'.$num_fila]; 
							$recuperasubacuerdo = $parametros['Nuevosubacuerdo'.$num_fila]; 
							$recuperaorigen = $parametros['Nuevoorigen'.$num_fila]; 
							$recuperadestino = $parametros['Nuevodestino'.$num_fila]; 
							$recuperafecha = $parametros['Nuevofecha'.$num_fila]; 
							$recuperavuelo = $parametros['Nuevovuelo'.$num_fila]; 
							$recuperahora_salida = $parametros['Nuevohora_salida'.$num_fila]; 
							$recuperahora_llegada = $parametros['Nuevohora_llegada'.$num_fila]; 
							$recuperaclase = $parametros['Nuevoclase'.$num_fila]; 
							$recuperacupo = $parametros['Nuevocupo'.$num_fila]; 
							$recuperalimite_clase_superior = $parametros['Nuevolimite_clase_superior'.$num_fila]; 
							$recuperalocalizador = $parametros['Nuevolocalizador'.$num_fila]; 
							$recuperadepuracion_1 = $parametros['Nuevodepuracion_1'.$num_fila]; 
							$recuperadepuracion_2 = $parametros['Nuevodepuracion_2'.$num_fila]; 
							$recuperadepuracion_final = $parametros['Nuevodepuracion_final'.$num_fila]; 
							$recuperadepuracion_final = $parametros['Nuevoclase_1'.$num_fila]; 
							$recuperadepuracion_final = $parametros['Nuevocupo_1'.$num_fila];
							$recuperadepuracion_final = $parametros['Nuevoclase_2'.$num_fila]; 
							$recuperadepuracion_final = $parametros['Nuevocupo_2'.$num_fila];
							$recuperadepuracion_final = $parametros['Nuevoclase_3'.$num_fila]; 
							$recuperadepuracion_final = $parametros['Nuevocupo_3'.$num_fila];
							$recuperadepuracion_final = $parametros['Nuevoclase_4'.$num_fila]; 
							$recuperadepuracion_final = $parametros['Nuevocupo_4'.$num_fila];
							$recuperadepuracion_final = $parametros['Nuevoclase_5'.$num_fila]; 
							$recuperadepuracion_final = $parametros['Nuevocupo_5'.$num_fila];*/

						}
					}
				}


				$mensaje1 = "<div class='mensaje-informacion' style='width:1400px; margin:0px 0px 0px 20px;'><font><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div class='mensaje-error' style='width:1400px; margin:0px 0px 0px 20px;''><font><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}

				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}/*elseif($parametros['actuar'] == 'A'){

				$Ntransacciones = 0;
				$mTransportes_vacios = new clsTransportes_vacios($conexion, $filadesde, $usuario, 
													$parametros['buscar_cia'], 
													$parametros['buscar_tipo'], 
													$parametros['buscar_fecha'], 
													$parametros['buscar_fecha_hasta'],
													$parametros['buscar_origen'], 
													$parametros['buscar_destino'], 
													$parametros['buscar_plazas'], 
													$parametros['buscar_precio'], 
													$parametros['buscar_estado'], 
													$parametros['buscar_dia_semana'],
													$parametros['buscar_id']);

				$actualizaTransportes_vacios = $mTransportes_vacios->Actualizar($parametros['actualizar_fecha_desde'],$parametros['actualizar_fecha_hasta'],@$parametros['lunes'],@$parametros['martes'],@$parametros['miercoles'],@$parametros['jueves'],@$parametros['viernes'],@$parametros['sabado'],@$parametros['domingo'],$parametros['actualizar_origen'],$parametros['actualizar_destino'],$parametros['actualizar_vuelo'],$parametros['nuevo_cupo'],$parametros['nuevo_vuelo'],$parametros['nuevo_hora_salida'],$parametros['nuevo_hora_llegada']);

					if($actualizaTransportes_vacios == 'OK'){
						$Ntransacciones++;
					}else{
						$error = $actualizaTransportes_vacios;
					}
				//Mostramos mensajes y posibles errores

				$mensaje1 = "<div class='mensaje-informacion'style='width:1400px; margin:0px 0px 0px 20px;'><font><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div class='mensaje-error'style='width:1400px; margin:0px 0px 0px 20px;'><font><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}

			}*/

			//Llamada a la clase especifica de la pantalla
			$sTransportes_vacios = $oTransportes_vacios->Cargar();
			$sTransportes_vaciosnuevos = $oTransportes_vacios->Cargar_lineas_nuevas();
			$sComboSelectTransportes_vacios = $oTransportes_vacios->Cargar_combo_selector();

			//Comprobamos si hay contrato seleccionado en los parametros de busqueda y de que tipo es

			/*if($parametros['buscar_cia'] != '' &&  $parametros['buscar_acuerdo'] != '' && $parametros['buscar_subacuerdo'] != ''){
				$tipo_contrato =$conexion->query("SELECT tran.TIPO tipo_contrato FROM hit_transportes_acuerdos tran WHERE tran.CIA = '".$parametros['buscar_cia']."' and tran.ACUERDO = ".$parametros['buscar_acuerdo']." and tran.SUBACUERDO = ".$parametros['buscar_subacuerdo']);
				$otipo_contrato = $tipo_contrato->fetch_assoc();
				$datos_tipo_contrato = $otipo_contrato['tipo_contrato'];
			}else{
				$datos_tipo_contrato = '';
			}*/
			//echo($datos_tipo_contrato.'-');
			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboSino = $oCombo->Cargar_combo_SiNo_Si();
			$comboTransportes = $oCombo->Cargar_combo_Transportes_Id();
			$comboDias_semana = $oCombo->Cargar_Combo_Dias_Semana();
			$comboTipo = $oCombo->Cargar_Combo_tipos_vacios();
			$comboTipo_linea = $oCombo->Cargar_Combo_tipos_vacios_linea();
			$comboEstado = $oCombo->Cargar_Combo_estado_trayecto();
			$comboEstado_linea = $oCombo->Cargar_Combo_estado_trayecto_linea();



			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» TRANSPORTES');
			$smarty->assign('formulario', '» VACIOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTransportes_vacios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('transportes_vacios', $sTransportes_vacios);

			//Indicamos si hay que visualizar o no la seccion de aACTUALIZAR VACIOS
			//$smarty->assign('seccion_actualizar_vacios_display', $parametros['seccion_actualizar_vacios_display']);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTransportes', $comboTransportes);
			$smarty->assign('comboDias_semana', $comboDias_semana);
			$smarty->assign('comboTipo', $comboTipo);
			$smarty->assign('comboTipo_linea', $comboTipo_linea);
			$smarty->assign('comboEstado', $comboEstado);
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboEstado_linea', $comboEstado_linea);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('transportes_vacios_nuevos', $sTransportes_vaciosnuevos);			
			$smarty->assign('recuperacia', $recuperacia);			
			$smarty->assign('recuperaorigen', $recuperaorigen);	
			$smarty->assign('recuperadestino', $recuperadestino);	
			$smarty->assign('recuperafecha', $recuperafecha);	
			$smarty->assign('recuperadia_semana', $recuperadia_semana);	
			$smarty->assign('recuperahora', $recuperahora);	
			$smarty->assign('recuperatipo', $recuperatipo);	
			$smarty->assign('recuperaplazas', $recuperaplazas);	
			$smarty->assign('recuperaprecio', $recuperaprecio);
			$smarty->assign('recuperavalor_aire', $recuperavalor_aire);	
			$smarty->assign('recuperavalor_equipaje', $recuperavalor_equipaje);	
			$smarty->assign('recuperavalor_tv', $recuperavalor_tv);
			$smarty->assign('recuperavalor_reclinables', $recuperavalor_reclinables);	
			$smarty->assign('recuperavalor_minusvalidos', $recuperavalor_minusvalidos);	
			$smarty->assign('recuperavalor_wc', $recuperavalor_wc);	


			//$smarty->assign('visualiza_cabecera_api', $datos_tipo_contrato);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);


			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_cia', $parametros['buscar_cia']);
			$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);
			$smarty->assign('buscar_fecha_hasta', $parametros['buscar_fecha_hasta']);
			$smarty->assign('buscar_origen', $parametros['buscar_origen']);
			$smarty->assign('buscar_destino', $parametros['buscar_destino']);
			$smarty->assign('buscar_tipo', $parametros['buscar_tipo']);
			$smarty->assign('buscar_plazas', $parametros['buscar_plazas']);
			$smarty->assign('buscar_precio', $parametros['buscar_precio']);
			$smarty->assign('buscar_estado', $parametros['buscar_estado']);
			$smarty->assign('buscar_dia_semana', $parametros['buscar_dia_semana']);
			$smarty->assign('buscar_id', $parametros['buscar_id']);


			//Visualizar plantilla
			$smarty->display('plantillas/Transportes_vacios.html');

		}elseif($parametros['ir_a'] == 'V'){

			header("Location: Transportes_vacios_solicitudes.php?id_vacio=".$parametros['buscar_id']);

		}else{
			session_destroy();
			exit;
			/*$smarty = new Smarty;
			$smarty->assign('mensaje', ' ');
			$smarty->assign('pie', 'plantillas/Pie.html');
			$smarty->display('plantillas/Index.html');*/
		}


	}else{

		$filadesde = 1;

		if(count($_GET) != 0 and $parametrosg['id_vacio']){
			$parametros['buscar_id'] = $parametrosg['id_vacio'];
		}else{
			$parametros['buscar_id'] = "";
		}
		$parametros['buscar_cia'] = "";
		$parametros['buscar_tipo'] = "";
		$parametros['buscar_fecha'] = "";
		$parametros['buscar_fecha_hasta'] = "";
		$parametros['buscar_origen'] = "";
		$parametros['buscar_destino'] = "";
		$parametros['buscar_plazas'] = "";
		$parametros['buscar_precio'] = "";
		$parametros['buscar_estado'] = ""; 
		$parametros['buscar_dia_semana'] = "";

			//Llamada a la clase especifica de la pantalla
			$oTransportes_vacios = new clsTransportes_vacios($conexion, $filadesde, $usuario, 
													$parametros['buscar_cia'], 
													$parametros['buscar_tipo'], 
													$parametros['buscar_fecha'], 
													$parametros['buscar_fecha_hasta'],
													$parametros['buscar_origen'], 
													$parametros['buscar_destino'], 
													$parametros['buscar_plazas'], 
													$parametros['buscar_precio'], 
													$parametros['buscar_estado'], 
													$parametros['buscar_dia_semana'],
													$parametros['buscar_id']);
			$sTransportes_vacios = $oTransportes_vacios->Cargar();
			$sTransportes_vaciosnuevos = $oTransportes_vacios->Cargar_lineas_nuevas();
			$sComboSelectTransportes_vacios = $oTransportes_vacios->Cargar_combo_selector();

			//Comprobamos si hay contrato seleccionado en los parametros de busqueda y de que tipo es

			if($parametros['buscar_cia'] != '' &&  $parametros['buscar_acuerdo'] != '' && $parametros['buscar_subacuerdo'] != ''){
				$tipo_contrato =$conexion->query("SELECT tran.TIPO tipo_contrato FROM hit_transportes_acuerdos tran WHERE tran.CIA = '".$parametros['buscar_cia']."' and tran.ACUERDO = ".$parametros['buscar_acuerdo']." and tran.SUBACUERDO = ".$parametros['buscar_subacuerdo']);
				$otipo_contrato = $tipo_contrato->fetch_assoc();
				$datos_tipo_contrato = $otipo_contrato['tipo_contrato'];
			}else{
				$datos_tipo_contrato = '';
			}
			//echo($datos_tipo_contrato.'-');

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboSino = $oCombo->Cargar_combo_SiNo_Si();
			$comboTransportes = $oCombo->Cargar_combo_Transportes_Id();
			$comboDias_semana = $oCombo->Cargar_Combo_Dias_Semana();
			$comboTipo = $oCombo->Cargar_Combo_tipos_vacios();
			$comboTipo_linea = $oCombo->Cargar_Combo_tipos_vacios_linea();
			$comboEstado = $oCombo->Cargar_Combo_estado_trayecto();
			$comboEstado_linea = $oCombo->Cargar_Combo_estado_trayecto_linea();

			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» TRANSPORTES');
			$smarty->assign('formulario', '» VACIOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTransportes_vacios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('transportes_vacios', $sTransportes_vacios);

			//Indicamos si hay que visualizar o no la seccion DE ACTUALIZAR VACIOS
			//$smarty->assign('seccion_actualizar_vacios_display', 'none');

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTransportes', $comboTransportes);
			$smarty->assign('comboDias_semana', $comboDias_semana);
			$smarty->assign('comboTipo', $comboTipo);
			$smarty->assign('comboTipo_linea', $comboTipo_linea);
			$smarty->assign('comboEstado', $comboEstado);
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboEstado_linea', $comboEstado_linea);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('transportes_vacios_nuevos', $sTransportes_vaciosnuevos);	
			$smarty->assign('recuperacia', $recuperacia);			
			$smarty->assign('recuperaorigen', $recuperaorigen);	
			$smarty->assign('recuperadestino', $recuperadestino);	
			$smarty->assign('recuperafecha', $recuperafecha);	
			$smarty->assign('recuperadia_semana', $recuperadia_semana);	
			$smarty->assign('recuperahora', $recuperahora);	
			$smarty->assign('recuperatipo', $recuperatipo);	
			$smarty->assign('recuperaplazas', $recuperaplazas);	
			$smarty->assign('recuperaprecio', $recuperaprecio);
			$smarty->assign('recuperavalor_aire', $recuperavalor_aire);	
			$smarty->assign('recuperavalor_equipaje', $recuperavalor_equipaje);	
			$smarty->assign('recuperavalor_tv', $recuperavalor_tv);
			$smarty->assign('recuperavalor_reclinables', $recuperavalor_reclinables);	
			$smarty->assign('recuperavalor_minusvalidos', $recuperavalor_minusvalidos);	
			$smarty->assign('recuperavalor_wc', $recuperavalor_wc);	

			//$smarty->assign('visualiza_cabecera_api', $datos_tipo_contrato);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_cia', '');
			$smarty->assign('buscar_fecha', '');
			$smarty->assign('buscar_fecha_hasta', '');
			$smarty->assign('buscar_origen', '');
			$smarty->assign('buscar_destino', '');
			$smarty->assign('buscar_tipo', '');
			$smarty->assign('buscar_plazas', '');
			$smarty->assign('buscar_precio',  '');
			$smarty->assign('buscar_estado', '');
			$smarty->assign('buscar_dia_semana', '');
			$smarty->assign('buscar_id', $parametros['buscar_id']);

		//Visualizar plantilla
		$smarty->display('plantillas/Transportes_vacios.html');
	}

	$conexion->close();


?>

