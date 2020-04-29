<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/transportes_acuerdos.cls.php';


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
			$recuperacia = '';
			$recuperaacuerdo = '';
			$recuperasubacuerdo = '';
			$recuperatipo = '';
			$recuperasituacion = '';
			$recuperafecha_desde = '';
			$recuperafecha_hasta = '';
			$recuperadivisa = '';
			$recuperapago_tipo = '';
			$recuperapago_plazo = '';
			$recuperapago_forma = '';
			$recuperaemision = '';
			$recuperacorresponsal = '';
			$recuperadescripcion = '';
			$recuperaobservaciones = '';
			$recuperagds = ''; 
			$recuperagds_rf = ''; 
			$recuperagds_os = '';
			$recuperadepuracion_1 = '';
			$recuperadepuracion_2 = '';
			$recuperadepuracion_final = '';

			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			$insertaAcuerdo = '';

			//VARIABLES PARA LOS TRAYECTOS
			$recuperaorigen = ''; 
			$recuperadestino = ''; 
			$recuperavuelo = ''; 
			$recuperahora_salida = ''; 
			$recuperahora_llegada = ''; 
			$recuperadesplazamiento_llegada = ''; 
			$recuperadias = ''; 
			$recuperaclave_coste = ''; 
			$recuperacupo_inicial = ''; 
			$recuperaclase_inicial = ''; 
			$recuperaclase_tray_2= '';
			$recuperaclase_tray_3= '';
			$recuperaclase_tray_4= '';
			$recuperaclase_tray_5= '';
			$mensaje1_trayectos = '';
			$mensaje2_trayectos = '';
			$error_trayectos = '';

			//VARIABLES PARA LOS PRECIOS REGULAR
			$recuperaclave_coste_regular = '';
			$recuperafecha_desde_precios_regular = '';
			$recuperafecha_hasta_precios_regular = '';
			$recuperatipo_precios_regular = '';
			$recuperatasas = '';	
			$recuperaclase_1 = '';
			$recuperacoste_1 = '';
			$recuperacalculo = '';
			$recuperaporcentaje_com = '';
			$recuperapvp_1 = '';
			$recuperaclase_2 = '';
			$recuperasuplemento_coste_2 = '';
			$recuperasuplemento_pvp_2 = '';
			$recuperaclase_3 = '';
			$recuperasuplemento_coste_3 = '';
			$recuperasuplemento_pvp_3 = '';
			$recuperaclase_4 = '';
			$recuperasuplemento_coste_4 = '';
			$recuperasuplemento_pvp_4 = '';
			$recuperaclase_5 = '';
			$recuperasuplemento_coste_5 = '';
			$recuperasuplemento_pvp_5 = '';
			$mensaje1_precios_regular = '';
			$mensaje2_precios_regular = '';
			$error_precios_regular = '';	

			//VARIABLES PARA LOS PRECIOS FIJOS
			$recuperaorigen_fijos = '';
			$recuperadestino_fijos = '';
			$recuperafecha_desde_precios_fijos = '';
			$recuperafecha_hasta_precios_fijos = '';
			//$recuperatasas = '';
			$recuperatipo_coste = '';	
			//$recuperaclase_1 = '';
			//$recuperacoste_1 = '';
			//$recuperacalculo = '';
			//$recuperaporcentaje_com = '';
			//$recuperapvp_1 = '';
			//$recuperaclase_2 = '';
			//$recuperasuplemento_coste_2 = '';
			//$recuperasuplemento_pvp_2 = '';
			$mensaje1_precios_fijos = '';
			$mensaje2_precios_fijos = '';
			$error_precios_fijos = '';

	if(count($_POST) != 0){
		//esto es por si venimos de la pantalla de listado de cupos
		if($parametros['buscar_cia'] == null and $parametros['buscar_acuerdo'] == null and $parametros['buscar_subacuerdo'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_cia'] = $parametrosg['cia'];
				$parametros['buscar_acuerdo'] = $parametrosg['acuerdo'];
				$parametros['buscar_subacuerdo'] = $parametrosg['subacuerdo'];
			}
		//esto es por si hemos seleccionado un acuerdo del combo de acuerdos del alojamiento
		}elseif($parametros['buscar_cia'] != null and $parametros['acuerdos'] != null){
				$parametros['buscar_acuerdo'] = substr($parametros['acuerdos'],0,4); //revisar aqui con substring
				$parametros['buscar_subacuerdo'] = substr($parametros['acuerdos'],4,2); //revisar aqui con substring
		}

		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'C'){

			//------------------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LAS TRANSPORTES_ACUERDOS----------
			//------------------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
				$botonselec = $botonAcuerdos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_ACUERDOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
							$modificaAcuerdos = $mAcuerdos->Modificar($parametros['cia'.$num_fila],$parametros['acuerdo'.$num_fila],$parametros['subacuerdo'.$num_fila],$parametros['tipo'.$num_fila],$parametros['situacion'.$num_fila],$parametros['fecha_desde'.$num_fila],$parametros['fecha_hasta'.$num_fila],$parametros['divisa'.$num_fila],$parametros['pago_tipo'.$num_fila],$parametros['pago_plazo'.$num_fila],$parametros['pago_forma'.$num_fila],$parametros['emision'.$num_fila],$parametros['corresponsal'.$num_fila],$parametros['descripcion'.$num_fila],$parametros['observaciones'.$num_fila],$parametros['gds'.$num_fila],$parametros['gds_rf'.$num_fila],$parametros['gds_os'.$num_fila],$parametros['depuracion_1'.$num_fila],$parametros['depuracion_2'.$num_fila],$parametros['depuracion_final'.$num_fila]);
							if($modificaAcuerdos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaAcuerdos;
							}
						}else{
							$mAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
							$borraAcuerdos = $mAcuerdos->Borrar($parametros['cia'.$num_fila],$parametros['acuerdo'.$num_fila],$parametros['subacuerdo'.$num_fila]);
							if($borraAcuerdos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraAcuerdos;
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

			}elseif($parametros['actuar'] == 'E'){

				//EXPANDIMOS CUPOS
				$Mensaje = '';
							
				$eAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
				$expandirCupos = $eAcuerdos->Expandir_cupos($parametros['cia0'],$parametros['acuerdo0'],$parametros['subacuerdo0']);
				if($expandirCupos == 'OK'){
					$Mensaje = "Se han expandido todos los cupos que no existieran ya";
				}else{
					$error = $expandirCupos;
				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//$eAcuerdos->close();

			}

			if($parametros['grabar_registro'] == 'S'){

				//AÑADIR REGISTROS
				//$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_GESTION' AND USUARIO = '".$usuario."'");
				//$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				$Ntransacciones = 0;				

				$iAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
				$insertaAcuerdos = $iAcuerdos->Insertar($parametros['cia0'],$parametros['acuerdo0'],$parametros['subacuerdo0'],$parametros['tipo0'],$parametros['situacion0'],$parametros['fecha_desde0'],$parametros['fecha_hasta0'],$parametros['divisa0'],$parametros['pago_tipo0'],$parametros['pago_plazo0'],$parametros['pago_forma0'],$parametros['emision0'],$parametros['corresponsal0'],$parametros['descripcion0'],$parametros['observaciones0'],$parametros['gds0'],$parametros['gds_rf0'],$parametros['gds_os0'],$parametros['depuracion_10'],$parametros['depuracion_20'],$parametros['depuracion_final0']);

				if($insertaAcuerdos == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_cia'] = $parametros['cia0'];
					$parametros['buscar_acuerdo'] = $parametros['acuerdo0'];
					$parametros['buscar_subacuerdo'] = $parametros['subacuerdo0'];
				}else{
					$error = $insertaAcuerdos;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					//$recuperacia = $parametros['cia'.$num_fila];
					$recuperacia = $parametros['cia0'];
					$recuperaacuerdo = $parametros['acuerdo0'];
					$recuperaacuerdo = $parametros['subacuerdo0'];
					$recuperatipo = $parametros['tipo0'];
					$recuperasituacion = $parametros['situacion0'];
					$recuperafecha_desde = $parametros['fecha_desde0'];
					$recuperafecha_hasta = $parametros['fecha_hasta0'];
					$recuperadivisa = $parametros['divisa0'];
					$recuperapago_tipo = $parametros['pago_tipo0'];
					$recuperapago_plazo = $parametros['pago_plazo0'];
					$recuperapago_forma = $parametros['pago_forma0'];
					$recuperaemision = $parametros['emision0'];
					$recuperacorresponsal = $parametros['corresponsal0'];
					$recuperadescripcion = $parametros['descripcion0'];
					$recuperaobservaciones = $parametros['observaciones0'];
					$recuperagds = $parametros['gds0'];
					$recuperagds_rf = $parametros['gds_rf0'];
					$recuperagds_os = $parametros['gds_os0'];
					$recuperadepuracion_1 = $parametros['depuracion_10'];
					$recuperadepuracion_2 = $parametros['depuracion_20'];
					$recuperadepuracion_final = $parametros['depuracion_final0'];
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
			$oAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
			$sAcuerdos = $oAcuerdos->Cargar($recuperacia,$recuperaacuerdo,$recuperasubacuerdo,$recuperatipo,$recuperasituacion,$recuperafecha_desde,$recuperafecha_hasta,$recuperadivisa,$recuperapago_tipo,$recuperapago_plazo,$recuperapago_forma,$recuperaemision,$recuperacorresponsal,$recuperadescripcion,$recuperaobservaciones,$recuperagds, $recuperagds_rf, $recuperagds_os, $recuperadepuracion_1, $recuperadepuracion_2, $recuperadepuracion_final);
			$sComboSelectAcuerdos = $oAcuerdos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboTransportes = $oSino->Cargar_combo_Transportes();
			$comboTipos_Acuerdo = $oSino->Cargar_combo_Tipos_acuerdo_transportes();
			$comboSituacion_Acuerdo = $oSino->Cargar_combo_Situacion_acuerdo();
			$comboDivisas = $oSino->Cargar_combo_Divisas();
			$comboTipo_Pago = $oSino->Cargar_combo_Tipo_pago();
			$comboForma_Pago = $oSino->Cargar_combo_Forma_Pago_Transportes();
			$comboCorresponsales = $oSino->Cargar_combo_Corresponsales();
			$comboGds = $oSino->Cargar_combo_Gds();
			$comboAcuerdos = $oSino->Cargar_combo_Acuerdos_UnaCia($parametros['buscar_cia']);

	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS TRAYECTOS ----------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_trayectos = $parametros['filadesde_trayectos'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonTrayectos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
				$botonselec_trayectos = $botonTrayectos->Botones_selector_trayectos($filadesde_trayectos, $parametros['botonSelector_2nivel'],$sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$parametros['buscar_origen'],$parametros['buscar_destino']);
				$filadesde_trayectos = $botonselec_trayectos;
			}

			//Llamada a la clase especifica de la pantalla
			$sTrayectos = $oAcuerdos->Cargar_trayectos($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$filadesde_trayectos,$parametros['buscar_origen'],$parametros['buscar_destino']);	
			//lineas visualizadas
			$vueltas = count($sTrayectos);

			if($parametros['actuar_2nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_TRAYECTOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_trayectos = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							
							$mTrayectos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
							$modificaTrayectos = $mTrayectos->Modificar_trayectos($sAcuerdos[0]['cia'], $sAcuerdos[0]['acuerdo'], $sAcuerdos[0]['subacuerdo'], $parametros['origen'.$num_fila], $parametros['destino'.$num_fila],$parametros['vuelo'.$num_fila],$parametros['hora_salida'.$num_fila],$parametros['hora_llegada'.$num_fila],$parametros['desplazamiento_llegada'.$num_fila],$parametros['dias'.$num_fila],$parametros['clave_coste'.$num_fila], $parametros['cupo_inicial'.$num_fila], $parametros['clase_inicial'.$num_fila], $parametros['clase_tray_2'.$num_fila], $parametros['clase_tray_3'.$num_fila], $parametros['clase_tray_4'.$num_fila], $parametros['clase_tray_5'.$num_fila], $parametros['origen_old'.$num_fila], $parametros['destino_old'.$num_fila]);
							if($modificaTrayectos == 'OK'){
								$Ntransacciones_trayectos++;
							}else{
								$error_trayectos = $modificaTrayectos;
							}

						}else{

							$mTrayectos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
							$borraTrayectos = $mTrayectos->Borrar_trayectos($sAcuerdos[0]['cia'], $sAcuerdos[0]['acuerdo'], $sAcuerdos[0]['subacuerdo'], $parametros['origen'.$num_fila],$parametros['destino'.$num_fila]);
							if($borraTrayectos == 'OK'){
								$Ntransacciones_trayectos++;
							}else{
								$error_trayectos = $borraTrayectos;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_TRAYECTOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){

						$iTrayectos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
						$insertaTrayectos = $iTrayectos->Insertar_trayectos($sAcuerdos[0]['cia'], $sAcuerdos[0]['acuerdo'], $sAcuerdos[0]['subacuerdo'], $parametros['Nuevoorigen'.$num_fila], $parametros['Nuevodestino'.$num_fila],$parametros['Nuevovuelo'.$num_fila],$parametros['Nuevohora_salida'.$num_fila],$parametros['Nuevohora_llegada'.$num_fila],$parametros['Nuevodesplazamiento_llegada'.$num_fila],$parametros['Nuevodias'.$num_fila],$parametros['Nuevoclave_coste'.$num_fila],$parametros['Nuevocupo_inicial'.$num_fila],$parametros['Nuevoclase_inicial'.$num_fila],$parametros['Nuevoclase_tray_2'.$num_fila],$parametros['Nuevoclase_tray_3'.$num_fila],$parametros['Nuevoclase_tray_4'.$num_fila],$parametros['Nuevoclase_tray_5'.$num_fila]);
						if($insertaTrayectos == 'OK'){
							$Ntransacciones_trayectos++;
						}else{
							$error_trayectos = $insertaTrayectos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperaorigen = $parametros['Nuevoorigen'.$num_fila]; 
							$recuperadestino = $parametros['Nuevodestino'.$num_fila]; 
							$recuperavuelo = $parametros['Nuevovuelo'.$num_fila]; 
							$recuperahora_salida = $parametros['Nuevohora_salida'.$num_fila]; 
							$recupehora_llegada = $parametros['Nuevohora_llegada'.$num_fila]; 
							$recuperadesplazamiento_llegada = $parametros['Nuevodesplazamiento_llegada'.$num_fila]; 
							$recuperadias = $parametros['Nuevodias'.$num_fila]; 
							$recuperaclave_coste = $parametros['Nuevoclave_coste'.$num_fila]; 
							$recuperacupo_inicial = $parametros['Nuevocupo_inicial'.$num_fila]; 
							$recuperaclase_inicial = $parametros['Nuevoclase_inicial'.$num_fila]; 
							$recuperaclase_tray_2 = $parametros['Nuevoclase_tray_2'.$num_fila]; 
							$recuperaclase_tray_3 = $parametros['Nuevoclase_tray_3'.$num_fila]; 
							$recuperaclase_tray_4 = $parametros['Nuevoclase_tray_4'.$num_fila]; 
							$recuperaclase_tray_5 = $parametros['Nuevoclase_tray_5'.$num_fila]; 
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_trayectos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_trayectos."</b></font></div>";
				if($error_trayectos != ''){
					$mensaje2_trayectos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_trayectos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();

			}elseif($parametros['actuar_2nivel'] == 'B'){
						
				//BORRAR CUPOS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_TRAYECTOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_trayectos = 0;
				$Mensaje = '';

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {

					if($parametros['borra_2nivel'.$num_fila] == 'S'){

						$bCupos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
						$borraCupos = $bCupos->Borrar_cupos($sAcuerdos[0]['cia'], $sAcuerdos[0]['acuerdo'], $sAcuerdos[0]['subacuerdo'], $parametros['origen'.$num_fila],	$parametros['destino'.$num_fila]);
						if($borraCupos == 'OK'){
							$Ntransacciones_trayectos++;
							$Mensaje = "Se han borrado todos los cupos que no tuvieran plazas ocupadas";
						}else{
							$error_trayectos = $borraCupos;

						}
					}
				}
				//Mostramos mensajes y posibles errores
				$mensaje1_trayectos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error_trayectos != ''){
					$mensaje2_trayectos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_trayectos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();

			}


			//Llamada a la clase especifica de la pantalla
			$sTrayectos = $oAcuerdos->Cargar_trayectos($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$filadesde_trayectos,$parametros['buscar_origen'],$parametros['buscar_destino']);	
			$sComboSelectTrayectos = $oAcuerdos->Cargar_combo_selector_trayectos($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$filadesde_trayectos,$parametros['buscar_origen'],$parametros['buscar_destino']);
			$sTrayectos_nuevos = $oAcuerdos->Cargar_lineas_nuevas_trayectos();	
			//Llamada a la clase general de combos
			//$comboTemporadas = $oSino->Cargar_combo_Temporadas();

			/*echo('<pre>');
			print_r($sComboSelectGrupos_gestion_comisiones);
			echo('</pre>');*/


	//-----------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS PRECIOS --------------
	//-----------------------------------------------------
			//Distinguimos si hay que mostrar los precios de contrato de linea regular o de plazas fijas
			//PRECIOS LINEA REGULAR
			/*if($parametros['filadesde_precios'] == true){
						$parametros['filadesde_precios'] = 1;
						$parametros['buscar_clave_coste_regular'] = '';
						$parametros['buscar_tipo'] = '';
			}*/
			//echo($parametros['tipo0']);
			//ECHO($parametros['filadesde_precios']);
			
			if($sAcuerdos[0]['tipo'] == 'ROR' OR $sAcuerdos[0]['tipo'] == 'RCU' OR $sAcuerdos[0]['tipo'] == 'RAC'){

					//Si venimos de un registro de plazas fijas hay que inicializar las variables de busqueda no comunes
					if($parametros['tipo0'] == 'CHR' or $parametros['tipo0'] == 'BUS'){
						$filadesde_precios = 1;
						$parametros['buscar_clave_coste_regular'] = '';
						$parametros['buscar_tipo'] = '';
					}else{
						$filadesde_precios = $parametros['filadesde_precios'];
					}
					//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
					if($parametros['botonSelector_4nivel'] != 0){
						$botonPrecios_regular = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
						$botonselec_precios_regular = $botonPrecios_regular->Botones_selector_precios_regular($filadesde_precios, $parametros['botonSelector_4nivel'], $sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$parametros['buscar_clave_coste_regular'],$parametros['buscar_fecha'],$parametros['buscar_tipo']);
						$filadesde_precios = $botonselec_precios_regular;
					}

					//Llamada a la clase especifica de la pantalla
					$sPrecios_regular = $oAcuerdos->Cargar_precios_regular($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$filadesde_precios,$parametros['buscar_clave_coste_regular'],$parametros['buscar_fecha'],$parametros['buscar_tipo']);
					//lineas visualizadas
					$vueltas = count($sPrecios_regular);


					if($parametros['actuar_4nivel'] == 'S'){

						//MODIFICAR Y BORRAR REGISTROS
						/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_REGULAR' AND USUARIO = '".$usuario."'");
						$Nfilas	 = $num_filas->fetch_assoc();*/
						$Ntransacciones_precios_regular = 0;

						for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
							if($parametros['selec_4nivel'.$num_fila] == 'S' || $parametros['borra_4nivel'.$num_fila] == 'S'){
								if($parametros['selec_4nivel'.$num_fila] == 'S'){
									
									$mPrecios_regular = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
									$modificaPrecios_regular = $mPrecios_regular->Modificar_precios_regular($sAcuerdos[0]['cia'], $sAcuerdos[0]['acuerdo'], $parametros['clave_coste_regular'.$num_fila], $parametros['fecha_desde_precios_regular'.$num_fila], $parametros['fecha_hasta_precios_regular'.$num_fila],$parametros['tipo_regular'.$num_fila], $parametros['tasas'.$num_fila], $parametros['clase_1'.$num_fila], $parametros['coste_1'.$num_fila], $parametros['calculo'.$num_fila], $parametros['porcentaje_com'.$num_fila], $parametros['pvp_1'.$num_fila], $parametros['clase_2'.$num_fila], $parametros['suplemento_coste_2'.$num_fila], $parametros['suplemento_pvp_2'.$num_fila],$parametros['clase_3'.$num_fila], $parametros['suplemento_coste_3'.$num_fila], $parametros['suplemento_pvp_3'.$num_fila],$parametros['clase_4'.$num_fila], $parametros['suplemento_coste_4'.$num_fila], $parametros['suplemento_pvp_4'.$num_fila],$parametros['clase_5'.$num_fila], $parametros['suplemento_coste_5'.$num_fila], $parametros['suplemento_pvp_5'.$num_fila],
									$parametros['clave_coste_regular_old'.$num_fila], $parametros['fecha_desde_precios_regular_old'.$num_fila], $parametros['fecha_hasta_precios_regular_old'.$num_fila],$parametros['tipo_regular_old'.$num_fila]);
									if($modificaPrecios_regular == 'OK'){
										$Ntransacciones_precios_regular++;
									}else{
										$error_precios_regular = $modificaPrecios_regular;
									}

								}else{

									$mPrecios_regular = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
									$borraPrecios_regular = $mPrecios_regular->Borrar_precios_regular($sAcuerdos[0]['cia'], $sAcuerdos[0]['acuerdo'], $parametros['clave_coste_regular'.$num_fila], $parametros['fecha_desde_precios_regular'.$num_fila], $parametros['fecha_hasta_precios_regular'.$num_fila],$parametros['tipo_regular'.$num_fila]);
									if($borraPrecios_regular == 'OK'){
										$Ntransacciones_precios_regular++;
									}else{
										$error_precios_regular = $borraPrecios_regular;

									}
								}
							}
						}

						//AÑADIR REGISTROS
						$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_REGULAR' AND USUARIO = '".$usuario."'");
						$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
						

						for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
							
							if($parametros['Nuevoselec_4nivel'.$num_fila] == 'S'){

								$iPrecios_regular = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
								$insertaPrecios_regular = $iPrecios_regular->Insertar_precios_regular($sAcuerdos[0]['cia'], $sAcuerdos[0]['acuerdo'], $parametros['Nuevoclave_coste_regular'.$num_fila], $parametros['Nuevofecha_desde_precios_regular'.$num_fila], $parametros['Nuevofecha_hasta_precios_regular'.$num_fila],$parametros['Nuevotipo_regular'.$num_fila],$parametros['Nuevotasas'.$num_fila],$parametros['Nuevoclase_1'.$num_fila],$parametros['Nuevocoste_1'.$num_fila],$parametros['Nuevocalculo'.$num_fila],$parametros['Nuevoporcentaje_com'.$num_fila],$parametros['Nuevopvp_1'.$num_fila],$parametros['Nuevoclase_2'.$num_fila],$parametros['Nuevosuplemento_coste_2'.$num_fila],$parametros['Nuevosuplemento_pvp_2'.$num_fila],$parametros['Nuevoclase_3'.$num_fila],$parametros['Nuevosuplemento_coste_3'.$num_fila],$parametros['Nuevosuplemento_pvp_3'.$num_fila],$parametros['Nuevoclase_4'.$num_fila],$parametros['Nuevosuplemento_coste_4'.$num_fila],$parametros['Nuevosuplemento_pvp_4'.$num_fila],$parametros['Nuevoclase_5'.$num_fila],$parametros['Nuevosuplemento_coste_5'.$num_fila],$parametros['Nuevosuplemento_pvp_5'.$num_fila]);
								if($insertaPrecios_regular == 'OK'){
									$Ntransacciones_precios_regular++;
								}else{
									$error_precios_regular = $insertaPrecios_regular;
									//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.

									$recuperaclave_coste_regular = $parametros['Nuevoclave_coste_regular'.$num_fila]; 
									$recuperafecha_desde_precios_regular = $parametros['Nuevofecha_desde_precios_regular'.$num_fila]; 
									$recuperafecha_hasta_precios_regular = $parametros['Nuevofecha_hasta_precios_regular'.$num_fila]; 
									$recuperatipo_regular = $parametros['Nuevotipo_regular'.$num_fila]; 
									$recuperatasas = $parametros['Nuevotasas'.$num_fila]; 	
									$recuperaclase_1 = $parametros['Nuevoclase_1'.$num_fila]; 
									$recuperacoste_1 = $parametros['Nuevocoste_1'.$num_fila]; 
									$recuperacalculo = $parametros['Nuevocalculo'.$num_fila]; 
									$recuperaporcentaje_com = $parametros['Nuevoporcentaje_com'.$num_fila]; 
									$recuperapvp_1 = $parametros['Nuevopvp_1'.$num_fila]; 
									$recuperaclase_2 = $parametros['Nuevoclase_2'.$num_fila]; 
									$recuperasuplemento_coste_2 = $parametros['Nuevosuplemento_coste_2'.$num_fila]; 
									$recuperasuplemento_pvp_2 = $parametros['Nuevosuplemento_pvp_2'.$num_fila]; 
									$recuperaclase_3 = $parametros['Nuevoclase_3'.$num_fila]; 
									$recuperasuplemento_coste_3 = $parametros['Nuevosuplemento_coste_3'.$num_fila]; 
									$recuperasuplemento_pvp_3 = $parametros['Nuevosuplemento_pvp_3'.$num_fila]; 
									$recuperaclase_4 = $parametros['Nuevoclase_4'.$num_fila]; 
									$recuperasuplemento_coste_4 = $parametros['Nuevosuplemento_coste_4'.$num_fila]; 
									$recuperasuplemento_pvp_4 = $parametros['Nuevosuplemento_pvp_4'.$num_fila]; 
									$recuperaclase_5 = $parametros['Nuevoclase_5'.$num_fila]; 
									$recuperasuplemento_coste_5 = $parametros['Nuevosuplemento_coste_5'.$num_fila]; 
									$recuperasuplemento_pvp_5 = $parametros['Nuevosuplemento_pvp_5'.$num_fila]; 
								}
							}
						}

						//Mostramos mensajes y posibles errores
						$mensaje1_precios_regular = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_precios_regular."</b></font></div>";
						if($error_precios_regular != ''){
							$mensaje2_precios_regular = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_precios_regular."</b></font></div>";
						}
						//echo ($Ntransacciones.' transacciones realizadas correctamente');
						//$num_filas->close();
					}


					//Llamada a la clase especifica de la pantalla
					$sPrecios_regular = $oAcuerdos->Cargar_precios_regular($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$filadesde_precios,$parametros['buscar_clave_coste_regular'],$parametros['buscar_fecha'],$parametros['buscar_tipo']);	
					$sComboSelectPrecios_regular = $oAcuerdos->Cargar_combo_selector_precios_regular($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$filadesde_precios,$parametros['buscar_clave_coste_regular'],$parametros['buscar_fecha'],$parametros['buscar_tipo']);
					$sPrecios_nuevos_regular = $oAcuerdos->Cargar_lineas_nuevas_precios_regular();	

					//Llamada a la clase general de combos
					$comboTipos_Trayecto = $oSino->Cargar_combo_Tipos_trayecto();
					$comboCalculo = $oSino->Cargar_combo_Calculo();

			//PRECIOS FIJOS
			}else{

					//Si venimos de un registro de linea regular hay que inicializar as variables de busqueda no comunes
					if($parametros['tipo0'] == 'ROR' or $parametros['tipo0'] == 'RCU' OR $sAcuerdos[0]['tipo'] == 'RAC'){
						$filadesde_precios = 1;
						$parametros['buscar_origen_fijos'] = '';
						$parametros['buscar_destino_fijos'] = '';
					}else{
						$filadesde_precios = $parametros['filadesde_precios'];
					}

					//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
					if($parametros['botonSelector_4nivel'] != 0){
						$botonPrecios_fijos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
						$botonselec_precios_fijos = $botonPrecios_fijos->Botones_selector_precios_fijos($filadesde_precios, $parametros['botonSelector_4nivel'],$sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$parametros['buscar_origen_fijos'],$parametros['buscar_destino_fijos'],$parametros['buscar_fecha']);
						$filadesde_precios = $botonselec_precios_fijos;
					}

					//Llamada a la clase especifica de la pantalla
					$sPrecios_fijos = $oAcuerdos->Cargar_precios_fijos($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$filadesde_precios,$parametros['buscar_origen_fijos'],$parametros['buscar_destino_fijos'],$parametros['buscar_fecha']);
					//lineas visualizadas
					$vueltas = count($sPrecios_fijos);

					if($parametros['actuar_4nivel'] == 'S'){

						//MODIFICAR Y BORRAR REGISTROS
						/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_FIJOS' AND USUARIO = '".$usuario."'");
						$Nfilas	 = $num_filas->fetch_assoc();*/
						$Ntransacciones_precios_fijos = 0;

						for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
							if($parametros['selec_4nivel'.$num_fila] == 'S' || $parametros['borra_4nivel'.$num_fila] == 'S'){
								if($parametros['selec_4nivel'.$num_fila] == 'S'){
									
									$mPrecios_fijos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
									$modificaPrecios_fijos = $mPrecios_fijos->Modificar_precios_fijos($sAcuerdos[0]['cia'], $sAcuerdos[0]['acuerdo'], $sAcuerdos[0]['subacuerdo'],$parametros['origen_fijos'.$num_fila],$parametros['destino_fijos'.$num_fila], $parametros['fecha_desde_precios_fijos'.$num_fila], $parametros['fecha_hasta_precios_fijos'.$num_fila],$parametros['tasas'.$num_fila], $parametros['tipo_coste'.$num_fila], $parametros['clase_1'.$num_fila], $parametros['coste_1'.$num_fila], $parametros['calculo'.$num_fila], $parametros['porcentaje_com'.$num_fila], $parametros['pvp_1'.$num_fila], $parametros['clase_2'.$num_fila], $parametros['suplemento_coste_2'.$num_fila], $parametros['suplemento_pvp_2'.$num_fila],
									$parametros['origen_fijos_old'.$num_fila], $parametros['destino_fijos_old'.$num_fila], $parametros['fecha_desde_precios_fijos_old'.$num_fila], $parametros['fecha_hasta_precios_fijos_old'.$num_fila]);
									if($modificaPrecios_fijos == 'OK'){
										$Ntransacciones_precios_fijos++;
									}else{
										$error_precios_fijos = $modificaPrecios_fijos;
									}

								}else{

									$mPrecios_fijos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
									$borraPrecios_fijos = $mPrecios_fijos->Borrar_precios_fijos($sAcuerdos[0]['cia'], $sAcuerdos[0]['acuerdo'], $sAcuerdos[0]['subacuerdo'], $parametros['origen_fijos'.$num_fila],$parametros['destino_fijos'.$num_fila], $parametros['fecha_desde_precios_fijos'.$num_fila], $parametros['fecha_hasta_precios_fijos'.$num_fila]);
									if($borraPrecios_fijos == 'OK'){
										$Ntransacciones_precios_fijos++;
									}else{
										$error_precios_fijos = $borraPrecios_fijos;

									}
								}
							}
						}

						//AÑADIR REGISTROS
						$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_COSTES_FIJOS' AND USUARIO = '".$usuario."'");
						$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
						

						for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
							
							if($parametros['Nuevoselec_4nivel'.$num_fila] == 'S'){

								$iPrecios_fijos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
								$insertaPrecios_fijos = $iPrecios_fijos->Insertar_precios_fijos($sAcuerdos[0]['cia'], $sAcuerdos[0]['acuerdo'], $sAcuerdos[0]['subacuerdo'], $parametros['Nuevoorigen_fijos'.$num_fila],$parametros['Nuevodestino_fijos'.$num_fila], $parametros['Nuevofecha_desde_precios_fijos'.$num_fila], $parametros['Nuevofecha_hasta_precios_fijos'.$num_fila],$parametros['Nuevotasas'.$num_fila],$parametros['Nuevotipo_coste'.$num_fila],$parametros['Nuevoclase_1'.$num_fila],$parametros['Nuevocoste_1'.$num_fila],$parametros['Nuevocalculo'.$num_fila],$parametros['Nuevoporcentaje_com'.$num_fila],$parametros['Nuevopvp_1'.$num_fila],$parametros['Nuevoclase_2'.$num_fila],$parametros['Nuevosuplemento_coste_2'.$num_fila],$parametros['Nuevosuplemento_pvp_2'.$num_fila]);
								if($insertaPrecios_fijos == 'OK'){
									$Ntransacciones_precios_fijos++;
								}else{
									$error_precios_fijos = $insertaPrecios_fijos;
									//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.

									$recuperaorigen_fijos = $parametros['Nuevoorigen_fijos'.$num_fila]; 
									$recuperadestino_fijos = $parametros['Nuevodestino_fijos'.$num_fila]; 
									$recuperafecha_desde_precios_fijos = $parametros['Nuevofecha_desde_precios_fijos'.$num_fila]; 
									$recuperafecha_hasta_precios_fijos = $parametros['Nuevofecha_hasta_precios_fijos'.$num_fila]; 
									$recuperatasas = $parametros['Nuevotasas'.$num_fila]; 
									$recuperatipo_coste = $parametros['Nuevotipo_coste'.$num_fila]; 	
									$recuperaclase_1 = $parametros['Nuevoclase_1'.$num_fila]; 
									$recuperacoste_1 = $parametros['Nuevocoste_1'.$num_fila]; 
									$recuperacalculo = $parametros['Nuevocalculo'.$num_fila]; 
									$recuperaporcentaje_com = $parametros['Nuevoporcentaje_com'.$num_fila]; 
									$recuperapvp_1 = $parametros['Nuevopvp_1'.$num_fila]; 
									$recuperaclase_2 = $parametros['Nuevoclase_2'.$num_fila]; 
									$recuperasuplemento_coste_2 = $parametros['Nuevosuplemento_coste_2'.$num_fila]; 
									$recuperasuplemento_pvp_2 = $parametros['Nuevosuplemento_pvp_2'.$num_fila]; 
								}
							}
						}

						//Mostramos mensajes y posibles errores
						$mensaje1_precios_fijos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_precios_fijos."</b></font></div>";
						if($error_precios_fijos != ''){
							$mensaje2_precios_fijos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_precios_fijos."</b></font></div>";
						}
						//echo ($Ntransacciones.' transacciones realizadas correctamente');
						//$num_filas->close();
					}


					//Llamada a la clase especifica de la pantalla
					$sPrecios_fijos = $oAcuerdos->Cargar_precios_fijos($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$filadesde_precios,$parametros['buscar_origen_fijos'],$parametros['buscar_destino_fijos'],$parametros['buscar_fecha']);	
					$sComboSelectPrecios_fijos = $oAcuerdos->Cargar_combo_selector_precios_fijos($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$filadesde_precios,$parametros['buscar_origen_fijos'],$parametros['buscar_destino_fijos'],$parametros['buscar_fecha']);
					$sPrecios_nuevos_fijos = $oAcuerdos->Cargar_lineas_nuevas_precios_fijos();	

					//Llamada a la clase general de combos
					$comboTipo_coste = $oSino->Cargar_combo_Tipo_coste_plazas_fijas();
					$comboCalculo = $oSino->Cargar_combo_Calculo();

			}

	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LAS TRANSPORTES_ACUERDOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» TRANSPORTES');
			$smarty->assign('formulario', '» ACUERDOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAcuerdos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('acuerdos', $sAcuerdos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboTransportes', $comboTransportes);
			$smarty->assign('comboTipos_Acuerdo', $comboTipos_Acuerdo);
			$smarty->assign('comboSituacion_Acuerdo', $comboSituacion_Acuerdo);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboTipo_Pago', $comboTipo_Pago);
			$smarty->assign('comboForma_Pago', $comboForma_Pago);
			$smarty->assign('comboCorresponsales', $comboCorresponsales);
			$smarty->assign('comboGds', $comboGds);
			$smarty->assign('comboAcuerdos', $comboAcuerdos);

			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_cia', $parametros['buscar_cia']);
			$smarty->assign('buscar_acuerdo', $parametros['buscar_acuerdo']);
			$smarty->assign('buscar_subacuerdo', $parametros['buscar_subacuerdo']);

			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS TRAYECTOS---------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_trayectos', $filadesde_trayectos);

			//Cargar combo selector
			$smarty->assign('combo_trayectos', $sComboSelectTrayectos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('trayectos', $sTrayectos);

			//Cargar combos de las lineas de la tabla
			//$smarty->assign('comboTemporadas', $comboTemporadas);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('trayectosnuevos', $sTrayectos_nuevos);	

			$smarty->assign('recuperaorigen', $recuperaorigen);	
			$smarty->assign('recuperadestino', $recuperadestino);	
			$smarty->assign('recuperavuelo', $recuperavuelo);	
			$smarty->assign('recuperahora_salida', $recuperahora_salida);	
			$smarty->assign('recuperahora_llegada', $recuperahora_llegada);	
			$smarty->assign('recuperadesplazamiento_llegada', $recuperadesplazamiento_llegada);	
			$smarty->assign('recuperadias', $recuperadias);	
			$smarty->assign('recuperaclave_coste', $recuperaclave_coste);	
			$smarty->assign('recuperacupo_inicial', $recuperacupo_inicial);	
			$smarty->assign('recuperaclase_inicial', $recuperaclase_inicial);	
			$smarty->assign('recuperaclase_tray_2', $recuperaclase_tray_2);
			$smarty->assign('recuperaclase_tray_3', $recuperaclase_tray_3);
			$smarty->assign('recuperaclase_tray_4', $recuperaclase_tray_4);
			$smarty->assign('recuperaclase_tray_5', $recuperaclase_tray_5);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_trayectos', $mensaje1_trayectos);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_trayectos', $mensaje2_trayectos);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_origen', $parametros['buscar_origen']);
			$smarty->assign('buscar_destino', $parametros['buscar_destino']);

			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS PRECIOS ---------
			//---------------------------------------------

			//PRECIOS LINEA REGULAR
			if($sAcuerdos[0]['tipo'] == 'ROR' OR $sAcuerdos[0]['tipo'] == 'RCU' OR $sAcuerdos[0]['tipo'] == 'RAC'){

					//Numero de fila para situar el selector de registros
					$smarty->assign('filades_precios_regular', $filadesde_precios);
				

					//Cargar combo selector
					$smarty->assign('combo_precios_regular', $sComboSelectPrecios_regular);

					//Cargar lineas de la tabla para visualizar modificar o borrar
					$smarty->assign('precios_regular', $sPrecios_regular);

					//Cargar combos de las lineas de la tabla
					$smarty->assign('comboTipos_Trayecto', $comboTipos_Trayecto);
					$smarty->assign('comboCalculo', $comboCalculo);

					//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
					$smarty->assign('preciosnuevos_regular', $sPrecios_nuevos_regular);	
					
					$smarty->assign('recuperaclave_coste_regular', $recuperaclave_coste_regular);	
					$smarty->assign('recuperafecha_desde_precios_regular', $recuperafecha_desde_precios_regular);	
					$smarty->assign('recuperafecha_hasta_precios_regular', $recuperafecha_hasta_precios_regular);	
					$smarty->assign('recuperatipo_precios_regular', $recuperatipo_precios_regular);	
					$smarty->assign('recuperatasas', $recuperatasas);	
					$smarty->assign('recuperaclase_1', $recuperaclase_1);	
					$smarty->assign('recuperacoste_1', $recuperacoste_1);	
					$smarty->assign('recuperacalculo', $recuperacalculo);	
					$smarty->assign('recuperaporcentaje_com', $recuperaporcentaje_com);	
					$smarty->assign('recuperapvp_1', $recuperapvp_1);	
					$smarty->assign('recuperaclase_2', $recuperaclase_2);	
					$smarty->assign('recuperasuplemento_coste_2', $recuperasuplemento_coste_2);	
					$smarty->assign('recuperasuplemento_pvp_2', $recuperasuplemento_pvp_2);	
					$smarty->assign('recuperaclase_3', $recuperaclase_3);	
					$smarty->assign('recuperasuplemento_coste_3', $recuperasuplemento_coste_3);	
					$smarty->assign('recuperasuplemento_pvp_3', $recuperasuplemento_pvp_3);
					$smarty->assign('recuperaclase_4', $recuperaclase_4);	
					$smarty->assign('recuperasuplemento_coste_4', $recuperasuplemento_coste_4);	
					$smarty->assign('recuperasuplemento_pvp_4', $recuperasuplemento_pvp_4);
					$smarty->assign('recuperaclase_5', $recuperaclase_5);	
					$smarty->assign('recuperasuplemento_coste_5', $recuperasuplemento_coste_5);	
					$smarty->assign('recuperasuplemento_pvp_5', $recuperasuplemento_pvp_5);


					//Cargar mensaje de numero de transacciones realizadas
					$smarty->assign('mensaje1_precios_regular', $mensaje1_precios_regular);

					//Cargar mensaje de error si se da error
					$smarty->assign('mensaje2_precios_regular', $mensaje2_precios_regular);

					//Cargar campos de busqueda de la tabla tecleados por el usuario
					$smarty->assign('buscar_clave_coste_regular', $parametros['buscar_clave_coste_regular']);
					$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);
					$smarty->assign('buscar_tipo', $parametros['buscar_tipo']);

			//PRECIOS FIJOS
			}else{

					//Numero de fila para situar el selector de registros
					$smarty->assign('filades_precios_fijos', $filadesde_precios);
				

					//Cargar combo selector
					$smarty->assign('combo_precios_fijos', $sComboSelectPrecios_fijos);

					//Cargar lineas de la tabla para visualizar modificar o borrar
					$smarty->assign('precios_fijos', $sPrecios_fijos);

					//Cargar combos de las lineas de la tabla
					$smarty->assign('comboTipo_coste', $comboTipo_coste);
					$smarty->assign('comboCalculo', $comboCalculo);

					//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
					$smarty->assign('preciosnuevos_fijos', $sPrecios_nuevos_fijos);	
					
					$smarty->assign('recuperaorigen_fijos', $recuperaorigen_fijos);
					$smarty->assign('recuperadestino_fijos', $recuperadestino_fijos);
					$smarty->assign('recuperafecha_desde_precios_fijos', $recuperafecha_desde_precios_fijos);	
					$smarty->assign('recuperafecha_hasta_precios_fijos', $recuperafecha_hasta_precios_fijos);	
					$smarty->assign('recuperatasas', $recuperatasas);	
					$smarty->assign('recuperatipo_coste', $recuperatipo_coste);	
					$smarty->assign('recuperaclase_1', $recuperaclase_1);	
					$smarty->assign('recuperacoste_1', $recuperacoste_1);	
					$smarty->assign('recuperacalculo', $recuperacalculo);	
					$smarty->assign('recuperaporcentaje_com', $recuperaporcentaje_com);	
					$smarty->assign('recuperapvp_1', $recuperapvp_1);	
					$smarty->assign('recuperaclase_2', $recuperaclase_2);	
					$smarty->assign('recuperasuplemento_coste_2', $recuperasuplemento_coste_2);	
					$smarty->assign('recuperasuplemento_pvp_2', $recuperasuplemento_pvp_2);	

					//Cargar mensaje de numero de transacciones realizadas
					$smarty->assign('mensaje1_precios_fijos', $mensaje1_precios_fijos);

					//Cargar mensaje de error si se da error
					$smarty->assign('mensaje2_precios_fijos', $mensaje2_precios_fijos);

					//Cargar campos de busqueda de la tabla tecleados por el usuario
					$smarty->assign('buscar_origen_fijos', $parametros['buscar_origen_fijos']);
					$smarty->assign('buscar_destino_fijos', $parametros['buscar_destino_fijos']);
					$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);

			}


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------
			
			//Posicionar plantilla			
			if($parametros['manda_posicion']){
				$smarty->assign('posicion', $parametros['manda_posicion']);
			}else{
				$smarty->assign('posicion', 'buscar_localizador');
			}
			
			//Visualizar plantilla
			$smarty->display('plantillas/Transportes_Acuerdos.html');


		}elseif($parametros['ir_a'] == 'E'){

			//Llamar a al metodo de expandir cupos;

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

		$filadesde = 1;
		if(count($_GET) != 0 and $parametrosg['cia'] != '' and $parametrosg['acuerdo'] != '' and $parametrosg['subacuerdo'] != ''){
			$parametros['buscar_cia'] = $parametrosg['cia'];
			$parametros['buscar_acuerdo'] = $parametrosg['acuerdo'];
			$parametros['buscar_subacuerdo'] = $parametrosg['subacuerdo'];
			
		}else{
			$parametros['buscar_cia'] = "";
			$parametros['buscar_acuerdo'] = "";
			$parametros['buscar_subacuerdo'] = "";
		}

			//Llamada a la clase especifica de la pantalla
			$oAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_acuerdo'], $parametros['buscar_subacuerdo']);
			$sAcuerdos = $oAcuerdos->Cargar($recuperacia,$recuperaacuerdo,$recuperasubacuerdo,$recuperatipo,$recuperasituacion,$recuperafecha_desde,$recuperafecha_hasta,$recuperadivisa,$recuperapago_tipo,$recuperapago_plazo,$recuperapago_forma,$recuperaemision,$recuperacorresponsal,$recuperadescripcion,$recuperaobservaciones,$recuperagds, $recuperagds_rf, $recuperagds_os, $recuperadepuracion_1, $recuperadepuracion_2, $recuperadepuracion_final);
			$sComboSelectAcuerdos = $oAcuerdos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboTransportes = $oSino->Cargar_combo_Transportes();
			$comboTipos_Acuerdo = $oSino->Cargar_combo_Tipos_acuerdo_transportes();
			$comboSituacion_Acuerdo = $oSino->Cargar_combo_Situacion_acuerdo();
			$comboDivisas = $oSino->Cargar_combo_Divisas();
			$comboTipo_Pago = $oSino->Cargar_combo_Tipo_pago();
			$comboForma_Pago = $oSino->Cargar_combo_Forma_Pago_Transportes();
			$comboCorresponsales = $oSino->Cargar_combo_Corresponsales();
			$comboGds = $oSino->Cargar_combo_Gds();
			$comboAcuerdos = $oSino->Cargar_combo_Acuerdos_UnaCia($parametros['buscar_cia']);

			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//--------------------------------------------------------
			//----VISUALIZAR PARTE DE LAS ALOJAMIENTOS_ACUERDOS-------
			//--------------------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» TRANSPORTES');
			$smarty->assign('formulario', '» ACUERDOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAcuerdos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('acuerdos', $sAcuerdos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboTransportes', $comboTransportes);
			$smarty->assign('comboTipos_Acuerdo', $comboTipos_Acuerdo);
			$smarty->assign('comboSituacion_Acuerdo', $comboSituacion_Acuerdo);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboTipo_Pago', $comboTipo_Pago);
			$smarty->assign('comboForma_Pago', $comboForma_Pago);
			$smarty->assign('comboCorresponsales', $comboCorresponsales);
			$smarty->assign('comboGds', $comboGds);
			$smarty->assign('comboAcuerdos', $comboAcuerdos);

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_cia', '');
		$smarty->assign('buscar_acuerdo', '');
		$smarty->assign('buscar_subacuerdo', '');


		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE TRAYECTOS

		$filadesde_trayectos = 1;
		$parametros['buscar_origen'] = "";
		$parametros['buscar_destino'] = "";

			//Llamada a la clase especifica de la pantalla
			$sTrayectos = $oAcuerdos->Cargar_trayectos($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$filadesde_trayectos,$parametros['buscar_origen'],$parametros['buscar_destino']);
	
			$sComboSelectTrayectos = $oAcuerdos->Cargar_combo_selector_trayectos($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$filadesde_trayectos,$parametros['buscar_origen'],$parametros['buscar_destino']);
	
			$sTrayectos_nuevos = $oAcuerdos->Cargar_lineas_nuevas_trayectos();	
			//Llamada a la clase general de combos
			//$comboTemporadas = $oSino->Cargar_combo_Temporadas();


			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_trayectos', $filadesde_trayectos);
		

			//Cargar combo selector
			$smarty->assign('combo_trayectos', $sComboSelectTrayectos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('trayectos', $sTrayectos);

			//Cargar combos de las lineas de la tabla
			//$smarty->assign('comboTemporadas', $comboTemporadas);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('trayectosnuevos', $sTrayectos_nuevos);	
			
			$smarty->assign('recuperaorigen', $recuperaorigen);	
			$smarty->assign('recuperadestino', $recuperadestino);	
			$smarty->assign('recuperavuelo', $recuperavuelo);	
			$smarty->assign('recuperahora_salida', $recuperahora_salida);	
			$smarty->assign('recuperahora_llegada', $recuperahora_llegada);	
			$smarty->assign('recuperadesplazamiento_llegada', $recuperadesplazamiento_llegada);	
			$smarty->assign('recuperadias', $recuperadias);	
			$smarty->assign('recuperaclave_coste', $recuperaclave_coste);	
			$smarty->assign('recuperacupo_inicial', $recuperacupo_inicial);	
			$smarty->assign('recuperaclase_inicial', $recuperaclase_inicial);	
			$smarty->assign('recuperaclase_tray_2', $recuperaclase_tray_2);
			$smarty->assign('recuperaclase_tray_3', $recuperaclase_tray_3);
			$smarty->assign('recuperaclase_tray_4', $recuperaclase_tray_4);
			$smarty->assign('recuperaclase_tray_5', $recuperaclase_tray_5);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_trayectos', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_trayectos', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_origen', '');
			$smarty->assign('buscar_destino', '');

		//-----------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS PRECIOS


			//PRECIOS LINEA REGULAR
			if($sAcuerdos[0]['tipo'] == 'ROR' OR $sAcuerdos[0]['tipo'] == 'RCU' OR $sAcuerdos[0]['tipo'] == 'RAC'){

				$filadesde_precios = 1;
				$parametros['buscar_clave_coste_regular'] = "";
				$parametros['buscar_fecha'] = "";
				$parametros['buscar_tipo'] = "";


				//Llamada a la clase especifica de la pantalla
				$sPrecios_regular = $oAcuerdos->Cargar_precios_regular($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$filadesde_precios,$parametros['buscar_clave_coste_regular'],$parametros['buscar_fecha'],$parametros['buscar_tipo']);	
				$sComboSelectPrecios_regular = $oAcuerdos->Cargar_combo_selector_precios_regular($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$filadesde_precios,$parametros['buscar_clave_coste_regular'],$parametros['buscar_fecha'],$parametros['buscar_tipo']);
				$sPrecios_nuevos_regular = $oAcuerdos->Cargar_lineas_nuevas_precios_regular();	

				//Llamada a la clase general de combos
				$comboTipos_Trayecto = $oSino->Cargar_combo_Tipos_trayecto();
				$comboCalculo = $oSino->Cargar_combo_Calculo();

				//Numero de fila para situar el selector de registros
				$smarty->assign('filades_precios_regular', $filadesde_precios);

				//Cargar combo selector
				$smarty->assign('combo_precios_regular', $sComboSelectPrecios_regular);

				//Cargar lineas de la tabla para visualizar modificar o borrar
				$smarty->assign('precios_regular', $sPrecios_regular);

				//Cargar combos de las lineas de la tabla
				$smarty->assign('comboTipos_Trayecto', $comboTipos_Trayecto);
				$smarty->assign('comboCalculo', $comboCalculo);

				//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
				$smarty->assign('preciosnuevos_regular', $sPrecios_nuevos_regular);	
				
				$smarty->assign('recuperaclave_coste_regular', $recuperaclave_coste_regular);	
				$smarty->assign('recuperafecha_desde_precios_regular', $recuperafecha_desde_precios_regular);	
				$smarty->assign('recuperafecha_hasta_precios_regular', $recuperafecha_hasta_precios_regular);	
				$smarty->assign('recuperatipo_precios_regular', $recuperatipo_precios_regular);	
				$smarty->assign('recuperatasas', $recuperatasas);	
				$smarty->assign('recuperaclase_1', $recuperaclase_1);	
				$smarty->assign('recuperacoste_1', $recuperacoste_1);	
				$smarty->assign('recuperacalculo', $recuperacalculo);	
				$smarty->assign('recuperaporcentaje_com', $recuperaporcentaje_com);	
				$smarty->assign('recuperapvp_1', $recuperapvp_1);	
				$smarty->assign('recuperaclase_2', $recuperaclase_2);	
				$smarty->assign('recuperasuplemento_coste_2', $recuperasuplemento_coste_2);	
				$smarty->assign('recuperasuplemento_pvp_2', $recuperasuplemento_pvp_2);	
				$smarty->assign('recuperaclase_3', $recuperaclase_3);	
				$smarty->assign('recuperasuplemento_coste_3', $recuperasuplemento_coste_3);	
				$smarty->assign('recuperasuplemento_pvp_3', $recuperasuplemento_pvp_3);
				$smarty->assign('recuperaclase_4', $recuperaclase_4);	
				$smarty->assign('recuperasuplemento_coste_4', $recuperasuplemento_coste_4);	
				$smarty->assign('recuperasuplemento_pvp_4', $recuperasuplemento_pvp_4);
				$smarty->assign('recuperaclase_5', $recuperaclase_5);	
				$smarty->assign('recuperasuplemento_coste_5', $recuperasuplemento_coste_5);	
				$smarty->assign('recuperasuplemento_pvp_5', $recuperasuplemento_pvp_5);


				//Cargar mensaje de numero de transacciones realizadas
				$smarty->assign('mensaje1_precios_regular', '');

				//Cargar mensaje de error si se da error
				$smarty->assign('mensaje2_precios_regular', '');

				//Cargar campos de busqueda de la tabla tecleados por el usuario
				$smarty->assign('buscar_clave_coste_regular', '');
				$smarty->assign('buscar_fecha', '');
				$smarty->assign('buscar_tipo', '');


			//PRECIOS FIJOS
			}else{

				$filadesde_precios = 1;
				$parametros['buscar_origen_fijos'] = "";
				$parametros['buscar_destino_fijos'] = "";
				$parametros['buscar_fecha'] = "";



				//Llamada a la clase especifica de la pantalla
				$sPrecios_fijos = $oAcuerdos->Cargar_precios_fijos($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$filadesde_precios,$parametros['buscar_origen_fijos'],$parametros['buscar_destino_fijos'],$parametros['buscar_fecha']);	
				$sComboSelectPrecios_fijos = $oAcuerdos->Cargar_combo_selector_precios_fijos($sAcuerdos[0]['cia'],$sAcuerdos[0]['acuerdo'],$sAcuerdos[0]['subacuerdo'],$filadesde_precios,$parametros['buscar_origen_fijos'],$parametros['buscar_destino_fijos'],$parametros['buscar_fecha']);
				$sPrecios_nuevos_fijos = $oAcuerdos->Cargar_lineas_nuevas_precios_fijos();	

				//Llamada a la clase general de combos
				$comboTipo_coste = $oSino->Cargar_combo_Tipo_coste_plazas_fijas();
				$comboCalculo = $oSino->Cargar_combo_Calculo();



					//Numero de fila para situar el selector de registros
					$smarty->assign('filades_precios_fijos', $filadesde_precios);
				

					//Cargar combo selector
					$smarty->assign('combo_precios_fijos', $sComboSelectPrecios_fijos);

					//Cargar lineas de la tabla para visualizar modificar o borrar
					$smarty->assign('precios_fijos', $sPrecios_fijos);

					//Cargar combos de las lineas de la tabla
					$smarty->assign('comboTipo_coste', $comboTipo_coste);
					$smarty->assign('comboCalculo', $comboCalculo);

					//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
					$smarty->assign('preciosnuevos_fijos', $sPrecios_nuevos_fijos);	
					
					$smarty->assign('recuperaorigen_fijos', $recuperaorigen_fijos);
					$smarty->assign('recuperadestino_fijos', $recuperadestino_fijos);
					$smarty->assign('recuperafecha_desde_precios_fijos', $recuperafecha_desde_precios_fijos);	
					$smarty->assign('recuperafecha_hasta_precios_fijos', $recuperafecha_hasta_precios_fijos);	
					$smarty->assign('recuperatasas', $recuperatasas);	
					$smarty->assign('recuperatipo_coste', $recuperatipo_coste);	
					$smarty->assign('recuperaclase_1', $recuperaclase_1);	
					$smarty->assign('recuperacoste_1', $recuperacoste_1);	
					$smarty->assign('recuperacalculo', $recuperacalculo);	
					$smarty->assign('recuperaporcentaje_com', $recuperaporcentaje_com);	
					$smarty->assign('recuperapvp_1', $recuperapvp_1);	
					$smarty->assign('recuperaclase_2', $recuperaclase_2);	
					$smarty->assign('recuperasuplemento_coste_2', $recuperasuplemento_coste_2);	
					$smarty->assign('recuperasuplemento_pvp_2', $recuperasuplemento_pvp_2);	

					//Cargar mensaje de numero de transacciones realizadas
					$smarty->assign('mensaje1_precios_fijos', '');

					//Cargar mensaje de error si se da error
					$smarty->assign('mensaje2_precios_fijos', '');

					//Cargar campos de busqueda de la tabla tecleados por el usuario
					$smarty->assign('buscar_origen_fijos', '');
					$smarty->assign('buscar_destino_fijos', '');
					$smarty->assign('buscar_fecha', '');

			}

		//Posicionar plantilla
		$smarty->assign('posicion', 'buscar_acuerdo');			
			
		//Visualizar plantilla
		$smarty->display('plantillas/Transportes_acuerdos.html');
	}

	$conexion->close();


?>

