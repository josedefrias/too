<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/transportes_cupos.cls.php';


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
	$insertaTransportes_cupos = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacia = '';
	$recuperaacuerdo = '';
	$recuperasubacuerdo = '';
	$recuperaorigen = '';
	$recuperadestino = '';
	$recuperafecha = '';
	$recuperavuelo = '';
	$recuperahora_salida = '';
	$recuperahora_llegada = '';
	$recuperacupo = '';
	$recuperalimite_clase_superior = '';
	$recuperaclase = '';
	$recuperalocalizador = '';
	$recuperadepuracion_1 = '';
	$recuperadepuracion_2 = '';
	$recuperadepuracion_final = '';
	$recuperaclase_1 = '';
	$recuperacupo_1 = '';
	$recuperaclase_2 = '';
	$recuperacupo_2 = '';
	$recuperaclase_3 = '';
	$recuperacupo_3 = '';
	$recuperaclase_4 = '';
	$recuperacupo_4 = '';
	$recuperaclase_5 = '';
	$recuperacupo_5 = '';
	
	if(count($_POST) != 0){
		//esto es por si venimos de la pantalla de Transportes
		if($parametros['buscar_cia'] == null and $parametros['buscar_acuerdo'] == null and $parametros['buscar_subacuerdo'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_cia'] = $parametrosg['cia'];
				$parametros['buscar_acuerdo'] = $parametrosg['acuerdo'];
				$parametros['buscar_subacuerdo'] = $parametrosg['subacuerdo'];
			}
		}else{
			if($parametros['buscar_subacuerdo'] == null){
				$parametros['buscar_subacuerdo'] = 1;
			}

		}
		
		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'V'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonTransportes_cupos = new clsTransportes_cupos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo'], $parametros['buscar_origen'], $parametros['buscar_destino'], $parametros['buscar_fecha'], $parametros['buscar_localizador'], $parametros['buscar_depuracion_1'], $parametros['buscar_depuracion_2'], $parametros['buscar_depuracion_final'], $parametros['buscar_dia_semana']);
				$botonselec = $botonTransportes_cupos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oTransportes_cupos = new clsTransportes_cupos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo'], $parametros['buscar_origen'], $parametros['buscar_destino'], $parametros['buscar_fecha'], $parametros['buscar_localizador'], $parametros['buscar_depuracion_1'], $parametros['buscar_depuracion_2'], $parametros['buscar_depuracion_final'], $parametros['buscar_dia_semana']);
			$sTransportes_cupos = $oTransportes_cupos->Cargar();
			//lineas visualizadas
			$vueltas = count($sTransportes_cupos);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_CUPOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mTransportes_cupos = new clsTransportes_cupos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo'], $parametros['buscar_origen'], $parametros['buscar_destino'], $parametros['buscar_fecha'], $parametros['buscar_localizador'], $parametros['buscar_depuracion_1'], $parametros['buscar_depuracion_2'], $parametros['buscar_depuracion_final'], $parametros['buscar_dia_semana']);
							$modificaTransportes_cupos = $mTransportes_cupos->Modificar($parametros['cia'.$num_fila], $parametros['acuerdo'.$num_fila], $parametros['subacuerdo'.$num_fila],$parametros['origen'.$num_fila],$parametros['destino'.$num_fila], $parametros['fecha'.$num_fila],$parametros['vuelo'.$num_fila],$parametros['hora_salida'.$num_fila],$parametros['hora_llegada'.$num_fila],@$parametros['clase'.$num_fila],@$parametros['cupo'.$num_fila],@$parametros['limite_clase_superior'.$num_fila],@$parametros['localizador_cia'.$num_fila],@$parametros['depuracion_1'.$num_fila],@$parametros['depuracion_2'.$num_fila],@$parametros['depuracion_final'.$num_fila],@$parametros['maximo_bebes'.$num_fila],@$parametros['clase_1'.$num_fila],@$parametros['cupo_1'.$num_fila],@$parametros['clase_2'.$num_fila],@$parametros['cupo_2'.$num_fila],@$parametros['clase_3'.$num_fila],@$parametros['cupo_3'.$num_fila],@$parametros['clase_4'.$num_fila],@$parametros['cupo_4'.$num_fila],@$parametros['clase_5'.$num_fila],@$parametros['cupo_5'.$num_fila]);
							if($modificaTransportes_cupos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaTransportes_cupos;
								
							}
						}else{

							$mTransportes_cupos = new clsTransportes_cupos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo'], $parametros['buscar_origen'], $parametros['buscar_destino'], $parametros['buscar_fecha'], $parametros['buscar_localizador'], $parametros['buscar_depuracion_1'], $parametros['buscar_depuracion_2'], $parametros['buscar_depuracion_final'], $parametros['buscar_dia_semana']);
							$borraTransportes_cupos = $mTransportes_cupos->Borrar($parametros['cia'.$num_fila], $parametros['acuerdo'.$num_fila], $parametros['subacuerdo'.$num_fila],$parametros['origen'.$num_fila],$parametros['destino'.$num_fila],$parametros['fecha'.$num_fila]);
							if($borraTransportes_cupos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraTransportes_cupos;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_CUPOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iTransportes_cupos = new clsTransportes_cupos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo'], $parametros['buscar_origen'], $parametros['buscar_destino'], $parametros['buscar_fecha'], $parametros['buscar_localizador'], $parametros['buscar_depuracion_1'], $parametros['buscar_depuracion_2'], $parametros['buscar_depuracion_final'], $parametros['buscar_dia_semana']);

						$insertaTransportes_cupos = $iTransportes_cupos->Insertar($parametros['Nuevocia'.$num_fila], $parametros['Nuevoacuerdo'.$num_fila], $parametros['Nuevosubacuerdo'.$num_fila],$parametros['Nuevoorigen'.$num_fila],$parametros['Nuevodestino'.$num_fila],$parametros['Nuevofecha'.$num_fila],$parametros['Nuevovuelo'.$num_fila],$parametros['Nuevohora_salida'.$num_fila],$parametros['Nuevohora_llegada'.$num_fila],$parametros['Nuevoclase'.$num_fila],$parametros['Nuevocupo'.$num_fila],$parametros['Nuevolimite_clase_superior'.$num_fila],$parametros['Nuevolocalizador'.$num_fila],$parametros['Nuevodepuracion_1'.$num_fila],$parametros['Nuevodepuracion_2'.$num_fila],$parametros['Nuevodepuracion_final'.$num_fila],$parametros['Nuevoclase_1'.$num_fila],$parametros['Nuevocupo_1'.$num_fila],$parametros['Nuevoclase_2'.$num_fila],$parametros['Nuevocupo_2'.$num_fila],$parametros['Nuevoclase_3'.$num_fila],$parametros['Nuevocupo_3'.$num_fila],$parametros['Nuevoclase_4'.$num_fila],$parametros['Nuevocupo_4'.$num_fila],$parametros['Nuevoclase_5'.$num_fila],$parametros['Nuevocupo_5'.$num_fila]);

						if($insertaTransportes_cupos == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaTransportes_cupos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacia = $parametros['Nuevocia'.$num_fila]; 
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
							$recuperadepuracion_final = $parametros['Nuevocupo_5'.$num_fila];

						}
					}
				}


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = $error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}elseif($parametros['actuar'] == 'A'){

				$Ntransacciones = 0;
				$mTransportes_cupos = new clsTransportes_cupos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo'], $parametros['buscar_origen'], $parametros['buscar_destino'], $parametros['buscar_fecha'], $parametros['buscar_localizador'], $parametros['buscar_depuracion_1'], $parametros['buscar_depuracion_2'], $parametros['buscar_depuracion_final'], $parametros['buscar_dia_semana']);

				$actualizaTransportes_cupos = $mTransportes_cupos->Actualizar($parametros['actualizar_fecha_desde'],$parametros['actualizar_fecha_hasta'],@$parametros['lunes'],@$parametros['martes'],@$parametros['miercoles'],@$parametros['jueves'],@$parametros['viernes'],@$parametros['sabado'],@$parametros['domingo'],$parametros['actualizar_origen'],$parametros['actualizar_destino'],$parametros['actualizar_vuelo'],$parametros['nuevo_cupo'],$parametros['nuevo_vuelo'],$parametros['nuevo_hora_salida'],$parametros['nuevo_hora_llegada']);

					if($actualizaTransportes_cupos == 'OK'){
						$Ntransacciones++;
					}else{
						$error = $actualizaTransportes_cupos;
					}
				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}

			}

			//Llamada a la clase especifica de la pantalla
			$sTransportes_cupos = $oTransportes_cupos->Cargar();
			$sTransportes_cuposnuevos = $oTransportes_cupos->Cargar_lineas_nuevas();
			$sComboSelectTransportes_cupos = $oTransportes_cupos->Cargar_combo_selector();

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
			$comboTransportes = $oCombo->Cargar_combo_Transportes();
			$comboDias_semana = $oCombo->Cargar_Combo_Dias_Semana();




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
			$smarty->assign('formulario', '» CUPOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTransportes_cupos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('transportes_cupos', $sTransportes_cupos);

			//Indicamos si hay que visualizar o no la seccion de aACTUALIZAR CUPOS
			$smarty->assign('seccion_actualizar_cupos_display', $parametros['seccion_actualizar_cupos_display']);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTransportes', $comboTransportes);
			$smarty->assign('comboDias_semana', $comboDias_semana);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('transportes_cupos_nuevos', $sTransportes_cuposnuevos);			
			$smarty->assign('recuperacia', $recuperacia);	
			$smarty->assign('recuperaacuerdo', $recuperaacuerdo);	
			$smarty->assign('recuperasubacuerdo', $recuperasubacuerdo);	
			$smarty->assign('recuperaorigen', $recuperaorigen);	
			$smarty->assign('recuperadestino', $recuperadestino);	
			$smarty->assign('recuperafecha', $recuperafecha);	
			$smarty->assign('recuperavuelo', $recuperavuelo);	
			$smarty->assign('recuperahora_salida', $recuperahora_salida);	
			$smarty->assign('recuperahora_llegada', $recuperahora_llegada);
			$smarty->assign('recuperaclase', $recuperaclase);	
			$smarty->assign('recuperacupo', $recuperacupo);	
			$smarty->assign('recuperalimite_clase_superior', $recuperalimite_clase_superior);
			$smarty->assign('recuperalocalizador', $recuperalocalizador);	
			$smarty->assign('recuperadepuracion_1', $recuperadepuracion_1);	
			$smarty->assign('recuperadepuracion_2', $recuperadepuracion_2);	
			$smarty->assign('recuperadepuracion_final', $recuperadepuracion_final);	
			$smarty->assign('recuperaclase_1', $recuperaclase_2);	
			$smarty->assign('recuperacupo_1', $recuperacupo_2);
			$smarty->assign('recuperaclase_2', $recuperaclase_2);	
			$smarty->assign('recuperacupo_2', $recuperacupo_2);	
			$smarty->assign('recuperaclase_3', $recuperaclase_3);	
			$smarty->assign('recuperacupo_3', $recuperacupo_3);	
			$smarty->assign('recuperaclase_4', $recuperaclase_4);	
			$smarty->assign('recuperacupo_4', $recuperacupo_4);	
			$smarty->assign('recuperaclase_5', $recuperaclase_5);	
			$smarty->assign('recuperacupo_5', $recuperacupo_5);	

			$smarty->assign('visualiza_cabecera_api', $datos_tipo_contrato);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_cia', $parametros['buscar_cia']);
			$smarty->assign('buscar_acuerdo', $parametros['buscar_acuerdo']);
			$smarty->assign('buscar_subacuerdo', $parametros['buscar_subacuerdo']);
			$smarty->assign('buscar_origen', $parametros['buscar_origen']);
			$smarty->assign('buscar_destino', $parametros['buscar_destino']);
			$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);
			$smarty->assign('buscar_localizador', $parametros['buscar_localizador']);
			$smarty->assign('buscar_depuracion_1', $parametros['buscar_depuracion_1']);
			$smarty->assign('buscar_depuracion_2', $parametros['buscar_depuracion_2']);
			$smarty->assign('buscar_depuracion_final', $parametros['buscar_depuracion_final']);
			$smarty->assign('buscar_dia_semana', $parametros['buscar_dia_semana']);

			$smarty->assign('actualizar_fecha_desde', $parametros['actualizar_fecha_desde']);
			$smarty->assign('actualizar_fecha_hasta', $parametros['actualizar_fecha_hasta']);
			$smarty->assign('actualizar_origen', $parametros['actualizar_origen']);
			$smarty->assign('actualizar_destino', $parametros['actualizar_destino']);
			$smarty->assign('actualizar_vuelo', $parametros['actualizar_vuelo']);
			$smarty->assign('nuevo_cupo', $parametros['nuevo_cupo']);
			$smarty->assign('nuevo_vuelo', $parametros['nuevo_vuelo']);
			$smarty->assign('nuevo_hora_salida', $parametros['nuevo_hora_salida']);
			$smarty->assign('nuevo_hora_llegada', $parametros['nuevo_hora_llegada']);


			//Visualizar plantilla
			$smarty->display('plantillas/Transportes_cupos.html');

		}elseif($parametros['ir_a'] == 'V'){

			header("Location: Transportes_acuerdos.php?cia=".$parametros['buscar_cia']."&acuerdo=".$parametros['buscar_acuerdo']."&subacuerdo=".$parametros['buscar_subacuerdo']);

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

		if(count($_GET) != 0 and $parametrosg['cia'] != null and $parametrosg['acuerdo'] != null and $parametrosg['subacuerdo'] != null){
			$parametros['buscar_cia'] = $parametrosg['cia'];
			$parametros['buscar_acuerdo'] = $parametrosg['acuerdo'];
			$parametros['buscar_subacuerdo'] = $parametrosg['subacuerdo'];
			$parametros['buscar_fecha'] = date("d-m-Y");
		}else{
			$parametros['buscar_cia'] = "";
			$parametros['buscar_acuerdo'] = "";
			$parametros['buscar_subacuerdo'] = "";
			$parametros['buscar_fecha'] = "";
		}
			$parametros['buscar_origen'] = "";
			$parametros['buscar_destino'] = "";
			$parametros['buscar_localizador'] = "";
			$parametros['buscar_depuracion_1'] = "";
			$parametros['buscar_depuracion_2'] = "";
			$parametros['buscar_depuracion_final'] = "";
			$parametros['buscar_dia_semana'] = "";

			//Llamada a la clase especifica de la pantalla
			$oTransportes_cupos = new clsTransportes_cupos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo'], $parametros['buscar_origen'], $parametros['buscar_destino'], $parametros['buscar_fecha'], $parametros['buscar_localizador'], $parametros['buscar_depuracion_1'], $parametros['buscar_depuracion_2'], $parametros['buscar_depuracion_final'], $parametros['buscar_dia_semana']);
			$sTransportes_cupos = $oTransportes_cupos->Cargar();
			$sTransportes_cuposnuevos = $oTransportes_cupos->Cargar_lineas_nuevas();
			$sComboSelectTransportes_cupos = $oTransportes_cupos->Cargar_combo_selector();

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
			$comboTransportes = $oCombo->Cargar_combo_Transportes();
			$comboDias_semana = $oCombo->Cargar_Combo_Dias_Semana();

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
			$smarty->assign('formulario', '» CUPOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTransportes_cupos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('transportes_cupos', $sTransportes_cupos);

			//Indicamos si hay que visualizar o no la seccion DE ACTUALIZAR CUPOS
			$smarty->assign('seccion_actualizar_cupos_display', 'none');

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTransportes', $comboTransportes);
			$smarty->assign('comboDias_semana', $comboDias_semana);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('transportes_cupos_nuevos', $sTransportes_cuposnuevos);			
			$smarty->assign('recuperacia', $recuperacia);	
			$smarty->assign('recuperaacuerdo', $recuperaacuerdo);	
			$smarty->assign('recuperasubacuerdo', $recuperasubacuerdo);	
			$smarty->assign('recuperaorigen', $recuperaorigen);	
			$smarty->assign('recuperadestino', $recuperadestino);	
			$smarty->assign('recuperafecha', $recuperafecha);	
			$smarty->assign('recuperavuelo', $recuperavuelo);	
			$smarty->assign('recuperahora_salida', $recuperahora_salida);	
			$smarty->assign('recuperahora_llegada', $recuperahora_llegada);	
			$smarty->assign('recuperaclase', $recuperaclase);	
			$smarty->assign('recuperacupo', $recuperacupo);	
			$smarty->assign('recuperalimite_clase_superior', $recuperalimite_clase_superior);
			$smarty->assign('recuperalocalizador', $recuperalocalizador);	
			$smarty->assign('recuperadepuracion_1', $recuperadepuracion_1);	
			$smarty->assign('recuperadepuracion_2', $recuperadepuracion_2);	
			$smarty->assign('recuperadepuracion_final', $recuperadepuracion_final);	
			$smarty->assign('recuperaclase_1', $recuperaclase_2);	
			$smarty->assign('recuperacupo_1', $recuperacupo_2);
			$smarty->assign('recuperaclase_2', $recuperaclase_2);	
			$smarty->assign('recuperacupo_2', $recuperacupo_2);	
			$smarty->assign('recuperaclase_3', $recuperaclase_3);	
			$smarty->assign('recuperacupo_3', $recuperacupo_3);	
			$smarty->assign('recuperaclase_4', $recuperaclase_4);	
			$smarty->assign('recuperacupo_4', $recuperacupo_4);	
			$smarty->assign('recuperaclase_5', $recuperaclase_5);	
			$smarty->assign('recuperacupo_5', $recuperacupo_5);

			$smarty->assign('visualiza_cabecera_api', $datos_tipo_contrato);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_cia', '');
			$smarty->assign('buscar_acuerdo', '');
			$smarty->assign('buscar_subacuerdo', '');
			$smarty->assign('buscar_origen', '');
			$smarty->assign('buscar_destino', '');
			$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);
			$smarty->assign('buscar_localizador', '');
			$smarty->assign('buscar_depuracion_1', '');
			$smarty->assign('buscar_depuracion_2', '');
			$smarty->assign('buscar_depuracion_final', '');
			$smarty->assign('buscar_dia_semana', '');

			$smarty->assign('actualizar_fecha_desde', '');
			$smarty->assign('actualizar_fecha_hasta', '');
			$smarty->assign('actualizar_origen', '');
			$smarty->assign('actualizar_destino', '');
			$smarty->assign('actualizar_vuelo', '');
			$smarty->assign('nuevo_cupo', '');
			$smarty->assign('nuevo_vuelo', '');
			$smarty->assign('nuevo_hora_salida', '');
			$smarty->assign('nuevo_hora_llegada', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Transportes_cupos.html');
	}

	$conexion->close();


?>

