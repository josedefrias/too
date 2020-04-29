<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/alojamientos_acuerdos.cls.php';


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
			$recuperaacuerdo = '';
			$recuperatipo = '';
			$recuperasituacion = '';
			$recuperafecha_desde = '';
			$recuperafecha_hasta = '';
			$recuperadias_entrada = '';
			$recuperaregimen_sa = '';
			$recuperaregimen_ad = '';
			$recuperaregimen_mp = '';
			$recuperaregimen_pc = '';
			$recuperaregimen_ti = '';
			$recuperagrat_tipo = '';
			$recuperagrat_uso = '';
			$recuperagrat_cada = '';
			$recuperagrat_max = '';
			$recuperadivisa = '';
			$recuperapago_tipo = '';
			$recuperapago_plazo = '';
			$recuperaforma_pago = '';
			$recuperacorresponsal = '';
			$recuperadescripcion = '';
			$recuperaenvio_1 = '';
			$recuperaenvio_2 = '';
			$recuperaenvio_3 = '';
			$recuperaenvio_rooming = '';
			$recuperacaracteristica_base = '';
			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			$insertaAcuerdo = '';

			//VARIABLES PARA LOS PERIODOS
			$recuperatemporada = '';
			$recuperafecha_desde_periodo = '';
			$recuperafecha_hasta_periodo = '';
			$recuperadias_release = '';
			$mensaje1_periodos = '';
			$mensaje2_periodos = '';
			$error_periodos = '';

			//VARIABLES PARA LAS TEMPORADAS
			$recuperatemporada_temp = '';
			$recuperaspto_ad = '';
			$recuperaspto_mp = '';
			$recuperaspto_pc = '';
			$recuperaspto_ti = '';
			$mensaje1_temporadas = '';
			$mensaje2_temporadas = '';
			$error_temporadas = '';	

			//VARIABLES PARA LAS TEMPORADAS
			$recuperatemporada_temp = '';
			$recuperaspto_ad = '';
			$recuperaspto_mp = '';
			$recuperaspto_pc = '';
			$recuperaspto_ti = '';
			$recuperapermite_sa = '';
			$recuperapermite_ad = '';
			$recuperapermite_mp = '';
			$recuperapermite_pc = '';
			$recuperapermite_ti = '';
			$mensaje1_temporadas = '';
			$mensaje2_temporadas = '';
			$error_temporadas = '';


			//VARIABLES PARA LAS CONDICIONES
			$recuperafecha_desde_condiciones = '';
			$recuperafecha_hasta_condiciones = '';
			$recuperareserva_desde_condiciones = '';
			$recuperareserva_hasta_condiciones = '';
			$recuperauso_condiciones = ''; 
			$recuperatipo_condiciones = ''; 
			$recuperaedad_desde_condiciones = '';
			$recuperaedad_hasta_condiciones = ''; 
			$recuperaregimen_condiciones = ''; 
			$recuperacalculo_condiciones = '';
			$recuperavalor_condiciones = '';
			$recuperavalor_pvp_condiciones = '';
			$mensaje1_condiciones = '';
			$mensaje2_condiciones = '';
			$error_condiciones = '';

			//VARIABLES PARA LOS USOS
			$recuperacaracteristica_usos= ''; 
			$recuperaminimo_detalle_usos = ''; 
			$recuperauso_minimo_usos = '';
			$recuperauso_minimo_adultos_usos = '';
			$recuperauso_minimo_ninos_usos = '';
			$recuperauso_minimo_bebes_usos = '';
			$recuperamaximo_detalle_usos = ''; 
			$recuperauso_maximo_pax_usos = '';
			$recuperauso_maximo_adultos_usos = '';
			$recuperauso_maximo_ninos_usos = '';
			$recuperauso_maximo_bebes_usos = '';
			$mensaje1_usos = '';
			$mensaje2_usos = '';
			$error_usos = '';


			//VARIABLES PARA LOS PRECIOS
			$recuperatemporada_prec = '';
			$recuperahabitacion = '';
			$recuperacaracteristica = '';
			$recuperauso = '';
			$recuperacoste_pax = '';	
			$recuperacoste_habitacion = '';
			$recuperacalculo = '';
			$recuperaporcentaje_neto = '';
			$recuperaneto_pax = '';
			$recuperaneto_habitacion = '';
			$recuperaporcentaje_com = '';
			$recuperapvp_pax = '';
			$recuperapvp_habitacion = '';
			$mensaje1_precios = '';
			$mensaje2_precios = '';
			$error_precios = '';	

	if(count($_POST) != 0){
		//esto es por si venimos de la pantalla de listado de cupos
		if($parametros['buscar_id'] == null and $parametros['buscar_acuerdo'] == null and $parametros['buscar_nombre'] == null){
			
			if(count($_GET) != 0){
				$parametros['buscar_id'] = $parametrosg['id'];
				$parametros['buscar_nombre'] = $parametrosg['nombre'];
				$parametros['buscar_acuerdo'] = $parametrosg['acuerdo'];
			}
		//esto es por si hemos seleccionado un acuerdo del combo de acuerdos del alojamiento
		}elseif($parametros['buscar_id'] != null and $parametros['acuerdos'] != null and $parametros['acuerdos'] != null){

				$parametros['buscar_acuerdo'] = $parametros['acuerdos'];

		}


		
		//Si no se ha pulsado el boton Salir ni el de Cupos
		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'C'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LAS ALOJAMIENTOS_ACUERDOS----------
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
				$botonselec = $botonAcuerdos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_ACUERDOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$modificaAcuerdos = $mAcuerdos->Modificar($parametros['id'.$num_fila],$parametros['acuerdo'.$num_fila],$parametros['tipo'.$num_fila],$parametros['situacion'.$num_fila],$parametros['fecha_desde'.$num_fila],$parametros['fecha_hasta'.$num_fila],$parametros['dias_entrada'.$num_fila],$parametros['regimen_sa'.$num_fila],$parametros['regimen_ad'.$num_fila],$parametros['regimen_mp'.$num_fila],$parametros['regimen_pc'.$num_fila],$parametros['regimen_ti'.$num_fila],$parametros['grat_tipo'.$num_fila],$parametros['grat_uso'.$num_fila],$parametros['grat_cada'.$num_fila],$parametros['grat_max'.$num_fila],$parametros['divisa'.$num_fila],$parametros['pago_tipo'.$num_fila],$parametros['pago_plazo'.$num_fila],$parametros['forma_pago'.$num_fila],$parametros['corresponsal'.$num_fila],$parametros['descripcion'.$num_fila],$parametros['envio_1'.$num_fila],$parametros['envio_2'.$num_fila],$parametros['envio_3'.$num_fila],$parametros['envio_rooming'.$num_fila],$parametros['caracteristica_base'.$num_fila]);
							if($modificaAcuerdos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaAcuerdos;

							}
						}else{

							$mAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$borraAcuerdos = $mAcuerdos->Borrar($parametros['id'.$num_fila],$parametros['acuerdo'.$num_fila]);
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
				
				if($parametros['tipo0']	== 'C'){	
					$eAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
					$expandirCupos = $eAcuerdos->Expandir_cupos($parametros['id0'],$parametros['acuerdo0']);
					if($expandirCupos == 'OK'){
						$Mensaje = "Se han expandido todos los cupos que no existieran ya.";
					}else{
						$error = $expandirCupos;
					}
				}else{
					$error = "El contrato es On-Request. No es posible expandir cupos.";
				}
				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//$eAcuerdos->close();

			}elseif($parametros['actuar'] == 'I'){

				//INCLUIR ACUERDO EN CONSTRUCCION
				$Mensaje = '';

				$datos_hotel =$conexion->query("select destino_producto from hit_alojamientos a where a.ID = '".$parametros['id0']."'");
					$odestino_hotel = $datos_hotel->fetch_assoc();
					$destino_hotel = $odestino_hotel['destino_producto'];

				
				$incluye_acuerdo = "CALL `PRODUCTO_INCLUYE_NUEVO_ALOJAMIENTO`('".$parametros['id0']."', '".$parametros['acuerdo0']."', '".date("Y-m-d",strtotime($parametros['fecha_desde0']))."', '".date("Y-m-d",strtotime($parametros['fecha_hasta0']))."', '".$destino_hotel."')";
				$resultadoincluye_acuerdo =$conexion->query($incluye_acuerdo);
						
				if ($resultadoincluye_acuerdo == FALSE){
					$error = 'No se han podido incluir el acuerdo en el producto. '.$conexion->error;
				}else{
					$Mensaje = 'El Acuerdo se ha incluido en todos los cuadros del destino con fechas vigentes';
				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}

			}

			if($parametros['grabar_registro'] == 'S'){

				//AÑADIR REGISTROS
				//$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_GESTION' AND USUARIO = '".$usuario."'");
				//$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				$Ntransacciones = 0;				

				$iAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
				$insertaAcuerdos = $iAcuerdos->Insertar($parametros['id0'],$parametros['acuerdo0'],$parametros['tipo0'],$parametros['situacion0'],$parametros['fecha_desde0'],$parametros['fecha_hasta0'],$parametros['dias_entrada0'],$parametros['regimen_sa0'],$parametros['regimen_ad0'],$parametros['regimen_mp0'],$parametros['regimen_pc0'],$parametros['regimen_ti0'],$parametros['grat_tipo0'],$parametros['grat_uso0'],$parametros['grat_cada0'],$parametros['grat_max0'],$parametros['divisa0'],$parametros['pago_tipo0'],$parametros['pago_plazo0'],$parametros['forma_pago0'],$parametros['corresponsal0'],$parametros['descripcion0'],$parametros['envio_10'],$parametros['envio_20'],$parametros['envio_30'],$parametros['envio_rooming0'],$parametros['caracteristica_base0']);

				if($insertaAcuerdos == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_id'] = $parametros['id0'];
					$parametros['buscar_acuerdo'] = $parametros['acuerdo0'];
				}else{
					$error = $insertaAcuerdos;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					//$recuperaid = $parametros['id'.$num_fila];
					$recuperaid = $parametros['id0'];
					$recuperaacuerdo = $parametros['acuerdo0'];
					$recuperatipo = $parametros['tipo0'];
					$recuperasituacion = $parametros['situacion0'];
					$recuperafecha_desde = $parametros['fecha_desde0'];
					$recuperafecha_hasta = $parametros['fecha_hasta0'];
					$recuperadias_entrada = $parametros['dias_entrada0'];
					$recuperaregimen_sa = $parametros['regimen_sa0'];
					$recuperaregimen_ad = $parametros['regimen_ad0'];
					$recuperaregimen_mp = $parametros['regimen_mp0'];
					$recuperaregimen_pc = $parametros['regimen_pc0'];
					$recuperaregimen_ti = $parametros['regimen_ti0'];
					$recuperagrat_tipo = $parametros['grat_tipo0'];
					$recuperagrat_uso = $parametros['grat_uso0'];
					$recuperagrat_cada = $parametros['grat_cada0'];
					$recuperagrat_max = $parametros['grat_max0'];
					$recuperadivisa = $parametros['divisa0'];
					$recuperapago_tipo = $parametros['pago_tipo0'];
					$recuperapago_plazo = $parametros['pago_plazo0'];
					$recuperaforma_pago = $parametros['forma_pago0'];
					$recuperacorresponsal = $parametros['corresponsal0'];
					$recuperadescripcion = $parametros['descripcion0'];
					$recuperaenvio_1 = $parametros['envio_10'];
					$recuperaenvio_2 = $parametros['envio_20'];
					$recuperaenvio_3 = $parametros['envio_30'];
					$recuperaenvio_rooming = $parametros['envio_rooming0'];
					$recuperacaracteristica_base = $parametros['caracteristica_base0'];
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
			$oAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
			$sAcuerdos = $oAcuerdos->Cargar($recuperaid,$recuperaacuerdo,$recuperatipo,$recuperasituacion,$recuperafecha_desde,$recuperafecha_hasta,$recuperadias_entrada,$recuperaregimen_sa, $recuperaregimen_ad,$recuperaregimen_mp,$recuperaregimen_pc,$recuperaregimen_ti,$recuperagrat_tipo,$recuperagrat_uso,$recuperagrat_cada,$recuperagrat_max,$recuperadivisa,$recuperapago_tipo,$recuperapago_plazo,$recuperaforma_pago,$recuperacorresponsal,$recuperadescripcion,$recuperaenvio_1,$recuperaenvio_2,$recuperaenvio_3,$recuperaenvio_rooming,$recuperacaracteristica_base);
			$sComboSelectAcuerdos = $oAcuerdos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboAlojamientos = $oSino->Cargar_combo_Alojamientos_like($parametros['buscar_nombre']);
			//$comboAlojamientos = $oSino->Cargar_combo_Alojamientos();
			$comboTipos_Acuerdo = $oSino->Cargar_combo_Tipos_acuerdo();
			$comboSituacion_Acuerdo = $oSino->Cargar_combo_Situacion_acuerdo();
			$comboDivisas = $oSino->Cargar_combo_Divisas();
			$comboTipo_pago = $oSino->Cargar_combo_Tipo_Pago();
			$comboForma_Pago = $oSino->Cargar_combo_Forma_pago();
			$comboTipo_Gratuidad = $oSino->Cargar_combo_Tipos_Gratuidad();
			$comboCorresponsales = $oSino->Cargar_combo_Corresponsales();
			$comboAcuerdos = $oSino->Cargar_combo_Acuerdos_UnAlojamiento($parametros['buscar_id']);

	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS PERIODOS ----------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_periodos = $parametros['filadesde_periodos'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonPeriodos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
				$botonselec_periodos = $botonPeriodos->Botones_selector_periodos($filadesde_periodos, $parametros['botonSelector_2nivel'],$sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['buscar_fecha']);
				$filadesde_periodos = $botonselec_periodos;
			}

			//Llamada a la clase especifica de la pantalla
			$sPeriodos = $oAcuerdos->Cargar_periodos($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_periodos,$parametros['buscar_fecha']);	
			//lineas visualizadas
			$vueltas = count($sPeriodos);



			if($parametros['actuar_2nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PERIODOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_periodos = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							
							$mPeriodos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$modificaPeriodos = $mPeriodos->Modificar_periodos($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['temporada'.$num_fila], $parametros['fecha_desde_periodo'.$num_fila], $parametros['fecha_hasta_periodo'.$num_fila],$parametros['dias_release'.$num_fila], $parametros['temporada_old'.$num_fila], $parametros['fecha_desde_periodo_old'.$num_fila], $parametros['fecha_hasta_periodo_old'.$num_fila]);
							if($modificaPeriodos == 'OK'){
								$Ntransacciones_periodos++;
							}else{
								$error_periodos = $modificaPeriodos;
							}

						}else{

							$mPeriodos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$borraPeriodos = $mPeriodos->Borrar_periodos($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['temporada'.$num_fila], $parametros['fecha_desde_periodo'.$num_fila],$parametros['fecha_hasta_periodo'.$num_fila]);
							if($borraPeriodos == 'OK'){
								$Ntransacciones_periodos++;
							}else{
								$error_periodos = $borraPeriodos;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PERIODOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){

						$iPeriodos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
						$insertaPeriodos = $iPeriodos->Insertar_periodos($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['Nuevotemporada'.$num_fila], $parametros['Nuevofecha_desde_periodo'.$num_fila], $parametros['Nuevofecha_hasta_periodo'.$num_fila],$parametros['Nuevodias_release'.$num_fila]);
						if($insertaPeriodos == 'OK'){
							$Ntransacciones_periodos++;
						}else{
							$error_periodos = $insertaPeriodos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperatemporada = $parametros['Nuevotemporada'.$num_fila]; 
							$recuperafecha_desde_periodo = $parametros['Nuevofecha_desde_periodo'.$num_fila]; 
							$recuperafecha_hasta_periodo = $parametros['Nuevofecha_hasta_periodo'.$num_fila]; 
							$recuperadias_release = $parametros['Nuevodias_release'.$num_fila]; 
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_periodos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_periodos."</b></font></div>";
				if($error_periodos != ''){
					$mensaje2_periodos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_periodos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();

			}elseif($parametros['actuar_2nivel'] == 'B'){
						
				//BORRAR CUPOS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PERIODOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_periodos = 0;
				$Mensaje = '';

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {

					if($parametros['borra_2nivel'.$num_fila] == 'S'){

						$bCupos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
						$borraCupos = $bCupos->Borrar_cupos($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['fecha_desde_periodo'.$num_fila],	$parametros['fecha_hasta_periodo'.$num_fila]);
						if($borraCupos == 'OK'){
							$Ntransacciones_periodos++;
							$Mensaje = "Se han borrado todos los cupos que no tuvieran plazas ocupadas";
						}else{
							$error_periodos = $borraCupos;

						}
					}
				}
				//Mostramos mensajes y posibles errores
				$mensaje1_periodos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Mensaje."</b></font></div>";
				if($error_periodos != ''){
					$mensaje2_periodos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_periodos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();

			}


			//Llamada a la clase especifica de la pantalla
			$sPeriodos = $oAcuerdos->Cargar_periodos($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_periodos,$parametros['buscar_fecha']);	
			$sComboSelectPeriodos = $oAcuerdos->Cargar_combo_selector_periodos($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['buscar_fecha']);
			$sPeriodos_nuevos = $oAcuerdos->Cargar_lineas_nuevas_periodos();	
			//Llamada a la clase general de combos
			//$comboTemporadas = $oSino->Cargar_combo_Temporadas();

			/*echo('<pre>');
			print_r($sComboSelectGrupos_gestion_comisiones);
			echo('</pre>');*/


	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LAS TEMPORADAS --------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_temporadas = $parametros['filadesde_temporadas'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_3nivel'] != 0){
				$botonTemporadas = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
				$botonselec_temporadas = $botonTemporadas->Botones_selector_temporadas($filadesde_temporadas, $parametros['botonSelector_3nivel'],$sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['buscar_temporada']);
				$filadesde_temporadas = $botonselec_temporadas;
			}


			//Llamada a la clase especifica de la pantalla
			$sTemporadas = $oAcuerdos->Cargar_temporadas($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_temporadas,$parametros['buscar_temporada']);	
			//lineas visualizadas
			$vueltas = count($sTemporadas);


			if($parametros['actuar_3nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_TEMPORADAS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_temporadas = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_3nivel'.$num_fila] == 'S' || $parametros['borra_3nivel'.$num_fila] == 'S'){
						if($parametros['selec_3nivel'.$num_fila] == 'S'){
							
							$mTemporadas = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$modificaTemporadas = $mTemporadas->Modificar_temporadas($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['temporada_temp'.$num_fila], $parametros['spto_ad'.$num_fila], $parametros['spto_mp'.$num_fila],$parametros['spto_pc'.$num_fila], $parametros['spto_ti'.$num_fila], $parametros['permite_sa'.$num_fila], $parametros['permite_ad'.$num_fila], $parametros['permite_mp'.$num_fila], $parametros['permite_pc'.$num_fila], $parametros['permite_ti'.$num_fila], $parametros['temporada_temp_old'.$num_fila]);
							if($modificaTemporadas == 'OK'){
								$Ntransacciones_temporadas++;
							}else{
								$error_temporadas = $modificaTemporadas;
							}

						}else{

							$mTemporadas = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$borraTemporadas = $mTemporadas->Borrar_temporadas($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['temporada_temp'.$num_fila]);
							if($borraTemporadas == 'OK'){
								$Ntransacciones_temporadas++;
							}else{
								$error_temporadas = $borraTemporadas;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_TEMPORADAS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_3nivel'.$num_fila] == 'S'){

						$iTemporadas = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
						$insertaTemporadas = $iTemporadas->Insertar_temporadas($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['Nuevotemporada_temp'.$num_fila], $parametros['Nuevospto_ad'.$num_fila], $parametros['Nuevospto_mp'.$num_fila],$parametros['Nuevospto_pc'.$num_fila],$parametros['Nuevospto_ti'.$num_fila],$parametros['Nuevopermite_sa'.$num_fila],$parametros['Nuevopermite_ad'.$num_fila],$parametros['Nuevopermite_mp'.$num_fila],$parametros['Nuevopermite_pc'.$num_fila],$parametros['Nuevopermite_ti'.$num_fila]);
						if($insertaTemporadas == 'OK'){
							$Ntransacciones_temporadas++;
						}else{
							$error_temporadas = $insertaTemporadas;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperatemporada_temp = $parametros['Nuevotemporada_temp'.$num_fila]; 
							$recuperaspto_ad = $parametros['Nuevospto_ad'.$num_fila]; 
							$recuperaspto_mp = $parametros['Nuevospto_mp'.$num_fila]; 
							$recuperaspto_pc = $parametros['Nuevospto_pc'.$num_fila]; 
							$recuperaspto_ti = $parametros['Nuevospto_ti'.$num_fila]; 
							$recuperapermite_sa = $parametros['Nuevopermite_sa'.$num_fila];
							$recuperapermite_ad = $parametros['Nuevopermite_ad'.$num_fila];
							$recuperapermite_mp = $parametros['Nuevopermite_mp'.$num_fila];
							$recuperapermite_pc = $parametros['Nuevopermite_pc'.$num_fila];
							$recuperapermite_ti = $parametros['Nuevopermite_ti'.$num_fila];
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_temporadas = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_temporadas."</b></font></div>";
				if($error_temporadas != ''){
					$mensaje2_temporadas = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_temporadas."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}

			//Llamada a la clase especifica de la pantalla
			$sTemporadas = $oAcuerdos->Cargar_temporadas($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_temporadas,$parametros['buscar_temporada']);	
			$sComboSelectTemporadas = $oAcuerdos->Cargar_combo_selector_temporadas($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['buscar_temporada']);
			$sTemporadas_nuevos = $oAcuerdos->Cargar_lineas_nuevas_temporadas();	
			//Llamada a la clase general de combos
			$comboSinoSi = $oSino->Cargar_combo_SiNo_Si();

			/*echo('<pre>');
			print_r($sComboSelectGrupos_gestion_comisiones);
			echo('</pre>');*/



	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LAS CONDICIONES --------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_condiciones = $parametros['filadesde_condiciones'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_5nivel'] != 0){
				$botonCondiciones = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
				$botonselec_condiciones = $botonCondiciones->Botones_selector_condiciones($filadesde_condiciones, $parametros['botonSelector_5nivel'],$sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$$parametros['buscar_tipo']);
				$filadesde_condiciones = $botonselec_condiciones;
			}


			//Llamada a la clase especifica de la pantalla
			$sCondiciones = $oAcuerdos->Cargar_condiciones($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_condiciones,$parametros['buscar_tipo']);	
			//lineas visualizadas
			$vueltas = count($sCondiciones);


			if($parametros['actuar_5nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_TEMPORADAS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_condiciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_5nivel'.$num_fila] == 'S' || $parametros['borra_5nivel'.$num_fila] == 'S'){
						if($parametros['selec_5nivel'.$num_fila] == 'S'){
							
							$mCondiciones = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$modificaCondiciones = $mCondiciones->Modificar_condiciones($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['fecha_desde_condiciones'.$num_fila], $parametros['fecha_hasta_condiciones'.$num_fila], $parametros['reserva_desde_condiciones'.$num_fila], $parametros['reserva_hasta_condiciones'.$num_fila], $parametros['uso_condiciones'.$num_fila], $parametros['tipo_condiciones'.$num_fila], $parametros['edad_desde_condiciones'.$num_fila],$parametros['edad_hasta_condiciones'.$num_fila], $parametros['regimen_condiciones'.$num_fila], $parametros['calculo_condiciones'.$num_fila], $parametros['valor_condiciones'.$num_fila], $parametros['valor_pvp_condiciones'.$num_fila], $parametros['fecha_desde_condiciones_old'.$num_fila], $parametros['fecha_hasta_condiciones_old'.$num_fila], $parametros['reserva_desde_condiciones_old'.$num_fila], $parametros['reserva_hasta_condiciones_old'.$num_fila], $parametros['uso_condiciones_old'.$num_fila], $parametros['tipo_condiciones_old'.$num_fila], $parametros['edad_desde_condiciones_old'.$num_fila]);
							if($modificaCondiciones == 'OK'){
								$Ntransacciones_condiciones++;
							}else{
								$error_condiciones = $modificaCondiciones;
							}

						}else{
							$mCondiciones = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$borraCondiciones = $mCondiciones->Borrar_condiciones($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['fecha_desde_condiciones'.$num_fila],$parametros['fecha_hasta_condiciones'.$num_fila],$parametros['reserva_desde_condiciones'.$num_fila],$parametros['reserva_hasta_condiciones'.$num_fila],$parametros['uso_condiciones'.$num_fila],$parametros['tipo_condiciones'.$num_fila],$parametros['edad_desde_condiciones'.$num_fila]);

							if($borraCondiciones == 'OK'){
								$Ntransacciones_condiciones++;
							}else{
								$error_condiciones = $borrCondiciones;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_CONDICIONES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_5nivel'.$num_fila] == 'S'){

						$iCondiciones = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
						$insertaCondiciones = $iCondiciones->Insertar_condiciones($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['Nuevofecha_desde_condiciones'.$num_fila], $parametros['Nuevofecha_hasta_condiciones'.$num_fila], $parametros['Nuevoreserva_desde_condiciones'.$num_fila], $parametros['Nuevoreserva_hasta_condiciones'.$num_fila], $parametros['Nuevouso_condiciones'.$num_fila], $parametros['Nuevotipo_condiciones'.$num_fila], $parametros['Nuevoedad_desde_condiciones'.$num_fila],$parametros['Nuevoedad_hasta_condiciones'.$num_fila],$parametros['Nuevoregimen_condiciones'.$num_fila],  $parametros['Nuevocalculo_condiciones'.$num_fila], $parametros['Nuevovalor_condiciones'.$num_fila], $parametros['Nuevovalor_pvp_condiciones'.$num_fila], $sAcuerdos[0]['fecha_desde'], $sAcuerdos[0]['fecha_hasta']);
						if($insertaCondiciones == 'OK'){
							$Ntransacciones_condiciones++;
						}else{
							$error_condiciones = $insertaCondiciones;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperafecha_desde_condiciones = $parametros['Nuevofecha_desde_condiciones'.$num_fila]; 
							$recuperafecha_hasta_condiciones = $parametros['Nuevofecha_hasta_condiciones'.$num_fila];
							$recuperareserva_desde_condiciones = $parametros['Nuevoreserva_desde_condiciones'.$num_fila]; 
							$recuperareserva_hasta_condiciones = $parametros['Nuevoreserva_hasta_condiciones'.$num_fila];							
							$recuperauso_condiciones = $parametros['Nuevouso_condiciones'.$num_fila]; 
							$recuperatipo_condiciones = $parametros['Nuevotipo_condiciones'.$num_fila]; 
							$recuperaedad_desde_condiciones = $parametros['Nuevoedad_desde_condiciones'.$num_fila]; 
							$recuperaedad_hasta_condiciones = $parametros['Nuevoedad_hasta_condiciones'.$num_fila]; 
							$recuperaregimen_condiciones = $parametros['Nuevoregimen_condiciones'.$num_fila]; 
							$recuperacalculo_condiciones = $parametros['Nuevocalculo_condiciones'.$num_fila]; 
							$recuperavalor_condiciones = $parametros['Nuevovalor_condiciones'.$num_fila]; 
							$recuperavalor_pvp_condiciones = $parametros['Nuevovalor_pvp_condiciones'.$num_fila]; 
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_condiciones = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_condiciones."</b></font></div>";
				if($error_condiciones != ''){
					$mensaje2_condiciones = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_condiciones."</b></font></div>";
				}
			}elseif($parametros['actuar_5nivel'] == 'C'){

				$actualizar_producto = "CALL `PROVEEDORES_CARGA_NUEVAS_CONDICIONES_CUADROS`('".$sAcuerdos[0]['id']."', '".$sAcuerdos[0]['acuerdo']."')";
				$resultadoactualizarproducto =$conexion->query($actualizar_producto);
						
				if ($resultadoactualizarproducto == FALSE){
					$error_condiciones = 'No se han podido calcular las nuevas condiciones del producto. '.$conexion->error;
				}else{
					$Ntransacciones_condiciones = 'OK';
				}
				//Mostramos mensajes y posibles errores
				$mensaje1_condiciones = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_condiciones."</b></font></div>";
				if($error_condiciones != ''){
					$mensaje2_condiciones = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_condiciones."</b></font></div>";
				}

			}elseif($parametros['actuar_5nivel'] == 'R'){

				$actualizar_producto = "CALL `PROVEEDORES_RECALCULA_CONDICIONES_CUADROS`('".$sAcuerdos[0]['id']."', '".$sAcuerdos[0]['acuerdo']."')";
				$resultadoactualizarproducto =$conexion->query($actualizar_producto);
						
				if ($resultadoactualizarproducto == FALSE){
					$error_condiciones = 'No se han podido recalcular las nuevas condiciones del producto. '.$conexion->error;
				}else{
					$Ntransacciones_condiciones = 'OK';
				}
				//Mostramos mensajes y posibles errores
				$mensaje1_condiciones = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_condiciones."</b></font></div>";
				if($error_condiciones != ''){
					$mensaje2_condiciones = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_condiciones."</b></font></div>";
				}

			}



			//Llamada a la clase especifica de la pantalla
			$sCondiciones = $oAcuerdos->Cargar_condiciones($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_condiciones,$parametros['buscar_tipo']);	
			$sComboSelectCondiciones = $oAcuerdos->Cargar_combo_selector_condiciones($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_condiciones,$parametros['buscar_tipo']);
			$sCondiciones_nuevos = $oAcuerdos->Cargar_lineas_nuevas_condiciones();	
			//Llamada a la clase general de combos
			$comboTipos_pasajeros = $oSino->Cargar_combo_Pasajeros_condiciones_acuerdos();
			$comboForma_calculo = $oSino->Cargar_combo_Forma_Calculo_acuerdos_alojamientos();
			$comboRegimen_condiciones = $oSino->Cargar_combo_Regimen_Producto();



	//----------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS USOS MAXIMO Y MINIMO ------
	//----------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_usos = $parametros['filadesde_usos'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_6nivel'] != 0){
				$botonUsos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
				$botonselec_usos = $botonUsos->Botones_selector_usos($filadesde_usos, $parametros['botonSelector_6nivel'],$sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['buscar_caracteristica_usos']);
				$filadesde_usos = $botonselec_usos;
			}


			//Llamada a la clase especifica de la pantalla
			$sUsos = $oAcuerdos->Cargar_usos($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_usos,$parametros['buscar_caracteristica_usos']);	
			//lineas visualizadas
			$vueltas = count($sUsos);


			if($parametros['actuar_6nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$Ntransacciones_usos = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_6nivel'.$num_fila] == 'S' || $parametros['borra_6nivel'.$num_fila] == 'S'){
						if($parametros['selec_6nivel'.$num_fila] == 'S'){
							
							$mUsos = new clsAcuerdos($conexion, $filadesde_usos, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$modificaUsos = $mUsos->Modificar_usos($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['caracteristica_usos'.$num_fila], $parametros['minimo_detalle_usos'.$num_fila], $parametros['uso_minimo_usos'.$num_fila], $parametros['uso_minimo_adultos_usos'.$num_fila], $parametros['uso_minimo_ninos_usos'.$num_fila], $parametros['uso_minimo_bebes_usos'.$num_fila], $parametros['maximo_detalle_usos'.$num_fila], $parametros['uso_maximo_pax_usos'.$num_fila], $parametros['uso_maximo_adultos_usos'.$num_fila], $parametros['uso_maximo_ninos_usos'.$num_fila], $parametros['uso_maximo_bebes_usos'.$num_fila], $parametros['caracteristica_usos_old'.$num_fila]);
							if($modificaUsos == 'OK'){
								$Ntransacciones_usos++;
							}else{
								$error_usos = $modificaUsos;
							}

						}else{
							$mUsos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$borraUsos = $mUsos->Borrar_usos($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['caracteristica_usos'.$num_fila]);

							if($borraUsos == 'OK'){
								$Ntransacciones_usos++;
							}else{
								$error_usos = $borrUsos;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_USOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_6nivel'.$num_fila] == 'S'){

						$iUsos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
						$insertaUsos = $iUsos->Insertar_usos($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['Nuevocaracteristica_usos'.$num_fila], $parametros['Nuevominimo_detalle_usos'.$num_fila], $parametros['Nuevouso_minimo_usos'.$num_fila], 
							$parametros['Nuevouso_minimo_adultos_usos'.$num_fila], $parametros['Nuevouso_minimo_ninos_usos'.$num_fila], $parametros['Nuevouso_minimo_bebes_usos'.$num_fila],
							$parametros['Nuevomaximo_detalle_usos'.$num_fila], $parametros['Nuevouso_maximo_pax_usos'.$num_fila],$parametros['Nuevouso_maximo_adultos_usos'.$num_fila], $parametros['Nuevouso_maximo_ninos_usos'.$num_fila], $parametros['Nuevouso_maximo_bebes_usos'.$num_fila]);
						if($insertaUsos == 'OK'){
							$Ntransacciones_usos++;
						}else{
							$error_usos = $insertaUsos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacaracteristica_usos = $parametros['Nuevocaracteristica_usos'.$num_fila]; 
							$recuperaminimo_detalle_usos = $parametros['Nuevominimo_detalle_usos'.$num_fila]; 
							$recuperauso_minimo_usos = $parametros['Nuevouso_minimo_usos'.$num_fila]; 
							$recuperauso_minimo_adultos_usos = $parametros['Nuevouso_minimo_adultos_usos'.$num_fila]; 
							$recuperauso_minimo_ninos_usos = $parametros['Nuevouso_minimo_ninos_usos'.$num_fila]; 
							$recuperauso_minimo_bebes_usos = $parametros['Nuevouso_minimo_bebes_usos'.$num_fila]; 
							$recuperamaximo_detalle_usos = $parametros['Nuevomaximo_detalle_usos'.$num_fila]; 
							$recuperauso_maximo_pax_usos = $parametros['Nuevouso_maximo_pax_usos'.$num_fila]; 
							$recuperauso_maximo_adultos_usos = $parametros['Nuevouso_maximo_adultos_usos'.$num_fila]; 
							$recuperauso_maximo_ninos_usos = $parametros['Nuevouso_maximo_ninos_usos'.$num_fila];
							$recuperauso_maximo_bebes_usos = $parametros['Nuevouso_maximo_bebes_usos'.$num_fila];
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_usos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_usos."</b></font></div>";
				if($error_usos != ''){
					$mensaje2_usos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_usos."</b></font></div>";
				}
			}

			//Llamada a la clase especifica de la pantalla
			$sUsos = $oAcuerdos->Cargar_usos($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_usos,$parametros['buscar_caracteristica_usos']);	
			$sComboSelectUsos = $oAcuerdos->Cargar_combo_selector_usos($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['buscar_caracteristica_usos']);
			$sUsos_nuevos = $oAcuerdos->Cargar_lineas_nuevas_usos();	
			//Llamada a la clase general de combos
			/*$comboTipos_pasajeros = $oSino->Cargar_combo_Pasajeros_condiciones_acuerdos();*/






	//-----------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS PRECIOS --------------
	//-----------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_precios = $parametros['filadesde_precios'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_4nivel'] != 0){
				$botonPrecios = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
				$botonselec_precios = $botonPrecios->Botones_selector_precios($filadesde_precios, $parametros['botonSelector_4nivel'], $sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['buscar_temporada_prec'],$parametros['buscar_habitacion'],$parametros['buscar_caracteristica']);
				$filadesde_precios = $botonselec_precios;
			}

			//Llamada a la clase especifica de la pantalla
			$sPrecios = $oAcuerdos->Cargar_precios($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_precios,$parametros['buscar_temporada_prec'],$parametros['buscar_habitacion'],$parametros['buscar_caracteristica']);	
			//lineas visualizadas
			$vueltas = count($sPrecios);


			if($parametros['actuar_4nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_precios = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_4nivel'.$num_fila] == 'S' || $parametros['borra_4nivel'.$num_fila] == 'S'){
						if($parametros['selec_4nivel'.$num_fila] == 'S'){
							
							$mPrecios = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$modificaPrecios = $mPrecios->Modificar_precios($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['temporada_prec'.$num_fila], $parametros['habitacion'.$num_fila], $parametros['caracteristica'.$num_fila],$parametros['uso'.$num_fila], $parametros['coste_pax'.$num_fila], $parametros['coste_habitacion'.$num_fila], $parametros['calculo'.$num_fila], $parametros['porcentaje_neto'.$num_fila], $parametros['neto_pax'.$num_fila], $parametros['neto_habitacion'.$num_fila], $parametros['porcentaje_com'.$num_fila], $parametros['pvp_pax'.$num_fila], $parametros['pvp_habitacion'.$num_fila], $parametros['temporada_prec_old'.$num_fila], $parametros['habitacion_old'.$num_fila], $parametros['caracteristica_old'.$num_fila],$parametros['uso_old'.$num_fila]);
							if($modificaPrecios == 'OK'){
								$Ntransacciones_precios++;
							}else{
								$error_precios = $modificaPrecios;
							}

						}else{

							$mPrecios = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
							$borraPrecios = $mPrecios->Borrar_precios($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['temporada_prec'.$num_fila], $parametros['habitacion'.$num_fila], $parametros['caracteristica'.$num_fila],$parametros['uso'.$num_fila]);
							if($borraPrecios == 'OK'){
								$Ntransacciones_precios++;
							}else{
								$error_precios = $borraPrecios;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_PRECIOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_4nivel'.$num_fila] == 'S'){

						$iPrecios = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
						$insertaPrecios = $iPrecios->Insertar_precios($sAcuerdos[0]['id'], $sAcuerdos[0]['acuerdo'], $parametros['Nuevotemporada_prec'.$num_fila], $parametros['Nuevohabitacion'.$num_fila], $parametros['Nuevocaracteristica'.$num_fila],$parametros['Nuevouso'.$num_fila],$parametros['Nuevocoste_pax'.$num_fila],$parametros['Nuevocoste_habitacion'.$num_fila],$parametros['Nuevocalculo'.$num_fila],$parametros['Nuevoporcentaje_neto'.$num_fila],$parametros['Nuevoneto_pax'.$num_fila],$parametros['Nuevoneto_habitacion'.$num_fila],$parametros['Nuevoporcentaje_com'.$num_fila],$parametros['Nuevopvp_pax'.$num_fila],$parametros['Nuevopvp_habitacion'.$num_fila]);
						if($insertaPrecios == 'OK'){
							$Ntransacciones_precios++;
						}else{
							$error_precios = $insertaPrecios;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperatemporada_prec = $parametros['Nuevotemporada_temp'.$num_fila]; 
							$recuperahabitacion = $parametros['Nuevohabitacion'.$num_fila]; 
							$recuperacaracteristica = $parametros['Nuevocaracteristica'.$num_fila]; 
							$recuperauso = $parametros['Nuevouso'.$num_fila]; 
							$recuperacoste_pax = $parametros['Nuevocoste_pax'.$num_fila]; 
							$recuperacoste_habitacion = $parametros['Nuevocoste_habitacion'.$num_fila]; 
							$recuperacalculo = $parametros['Nuevocalculo'.$num_fila]; 
							$recuperaporcentaje_com = $parametros['Nuevoporcentaje_neto'.$num_fila]; 
							$recuperapvp_pax = $parametros['Nuevoneto_pax'.$num_fila]; 	
							$recuperapvp_habitacion = $parametros['Nuevoneto_habitacion'.$num_fila];
							$recuperaporcentaje_com = $parametros['Nuevoporcentaje_com'.$num_fila]; 
							$recuperapvp_pax = $parametros['Nuevopvp_pax'.$num_fila]; 	
							$recuperapvp_habitacion = $parametros['Nuevopvp_habitacion'.$num_fila];
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_precios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_precios."</b></font></div>";
				if($error_precios != ''){
					$mensaje2_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_precios."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}


			//Llamada a la clase especifica de la pantalla
			$sPrecios = $oAcuerdos->Cargar_precios($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_precios,$parametros['buscar_temporada_prec'],$parametros['buscar_habitacion'],$parametros['buscar_caracteristica']);	
			$sComboSelectPrecios = $oAcuerdos->Cargar_combo_selector_precios($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_precios,$parametros['buscar_temporada_prec'],$parametros['buscar_habitacion'],$parametros['buscar_caracteristica']);
			$sPrecios_nuevos = $oAcuerdos->Cargar_lineas_nuevas_precios();	

			//Llamada a la clase general de combos
			$comboHabitaciones = $oSino->Cargar_combo_Habitaciones();
			$comboHabitaciones_car = $oSino->Cargar_combo_Habitaciones_car();
			$comboCalculo = $oSino->Cargar_combo_Calculo();

			/*echo('<pre>');
			print_r($sComboSelectGrupos_gestion_comisiones);
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
			//----VISUALIZAR PARTE DE LAS ALOJAMIENTOS_ACUERDOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
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
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboTipos_Acuerdo', $comboTipos_Acuerdo);
			$smarty->assign('comboSituacion_Acuerdo', $comboSituacion_Acuerdo);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboTipo_pago', $comboTipo_pago);
			$smarty->assign('comboForma_Pago', $comboForma_Pago);
			$smarty->assign('comboTipo_Gratuidad', $comboTipo_Gratuidad);
			$smarty->assign('comboCorresponsales', $comboCorresponsales);
			$smarty->assign('comboAcuerdos', $comboAcuerdos);




			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_acuerdo', $parametros['buscar_acuerdo']);

			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS PERIODOS---------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_periodos', $filadesde_periodos);
		

			//Cargar combo selector
			$smarty->assign('combo_periodos', $sComboSelectPeriodos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('periodos', $sPeriodos);

			//Cargar combos de las lineas de la tabla
			//$smarty->assign('comboTemporadas', $comboTemporadas);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('periodosnuevos', $sPeriodos_nuevos);	
			
			$smarty->assign('recuperatemporada', $recuperatemporada);	
			$smarty->assign('recuperafecha_desde_periodo', $recuperafecha_desde_periodo);	
			$smarty->assign('recuperafecha_hasta_periodo', $recuperafecha_hasta_periodo);	
			$smarty->assign('recuperadias_release', $recuperadias_release);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_periodos', $mensaje1_periodos);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_periodos', $mensaje2_periodos);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);

			//---------------------------------------------
			//----VISUALIZAR PARTE DE LAS TEMPORADAS ------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_temporadas', $filadesde_temporadas);
		

			//Cargar combo selector
			$smarty->assign('combo_temporadas', $sComboSelectTemporadas);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('temporadas', $sTemporadas);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSinoSi', $comboSinoSi);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('temporadasnuevos', $sTemporadas_nuevos);	
			
			$smarty->assign('recuperatemporada_temp', $recuperatemporada_temp);	
			$smarty->assign('recuperaspto_ad', $recuperaspto_ad);	
			$smarty->assign('recuperaspto_mp', $recuperaspto_mp);	
			$smarty->assign('recuperaspto_pc', $recuperaspto_pc);	
			$smarty->assign('recuperaspto_ti', $recuperaspto_ti);	
			$smarty->assign('recuperapermite_sa', $recuperapermite_sa);
			$smarty->assign('recuperapermite_ad', $recuperapermite_ad);
			$smarty->assign('recuperapermite_mp', $recuperapermite_mp);
			$smarty->assign('recuperapermite_pc', $recuperapermite_pc);
			$smarty->assign('recuperapermite_ti', $recuperapermite_ti);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_temporadas', $mensaje1_temporadas);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_temporadas', $mensaje2_temporadas);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_temporada', $parametros['buscar_temporada']);

			
			//---------------------------------------------
			//----VISUALIZAR PARTE DE LAS CONDICIONES ------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_condiciones', $filadesde_condiciones);
		

			//Cargar combo selector
			$smarty->assign('combo_condiciones', $sComboSelectCondiciones);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('condiciones', $sCondiciones);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTipos_pasajeros', $comboTipos_pasajeros);
			$smarty->assign('comboForma_calculo', $comboForma_calculo);
			$smarty->assign('comboRegimen_condiciones', $comboRegimen_condiciones);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('condicionesnuevos', $sCondiciones_nuevos);	
			
			$smarty->assign('recuperafecha_desde_condiciones', $recuperafecha_desde_condiciones);
			$smarty->assign('recuperafecha_hasta_condiciones', $recuperafecha_hasta_condiciones);
			$smarty->assign('recuperareserva_desde_condiciones', $recuperareserva_desde_condiciones);
			$smarty->assign('recuperareserva_hasta_condiciones', $recuperareserva_hasta_condiciones);
			$smarty->assign('recuperauso_condiciones', $recuperauso_condiciones);	
			$smarty->assign('recuperatipo_condiciones', $recuperatipo_condiciones);	
			$smarty->assign('recuperaedad_desde_condiciones', $recuperaedad_desde_condiciones);	
			$smarty->assign('recuperaedad_hasta_condiciones', $recuperaedad_hasta_condiciones);	
			$smarty->assign('recuperaregimen_condiciones', $recuperaregimen_condiciones);
			$smarty->assign('recuperacalculo_condiciones', $recuperacalculo_condiciones);	
			$smarty->assign('recuperavalor_condiciones', $recuperavalor_condiciones);
			$smarty->assign('recuperavalor_pvp_condiciones', $recuperavalor_pvp_condiciones);
			
			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_condiciones', $mensaje1_condiciones);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_condiciones', $mensaje2_condiciones);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_tipo', $parametros['buscar_tipo']);


			//-------------------------------------------------------
			//----VISUALIZAR PARTE DE LOS USOS MINIMO Y MAXIMO ------
			//-------------------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_usos', $filadesde_usos);
		

			//Cargar combo selector
			$smarty->assign('combo_usos', $sComboSelectUsos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('usos', $sUsos);

			//Cargar combos de las lineas de la tabla
			/*$smarty->assign('comboTipos_pasajeros', $comboTipos_pasajeros);
			$smarty->assign('comboForma_calculo', $comboForma_calculo);*/

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('usosnuevos', $sUsos_nuevos);	
			
			$smarty->assign('recuperacaracteristica_usos', $recuperacaracteristica_usos);	
			$smarty->assign('recuperaminimo_detalle_usos', $recuperaminimo_detalle_usos);	
			$smarty->assign('recuperauso_minimo_usos', $recuperauso_minimo_usos);	
			$smarty->assign('recuperauso_minimo_adultos_usos', $recuperauso_minimo_adultos_usos);	
			$smarty->assign('recuperauso_minimo_ninos_usos', $recuperauso_minimo_ninos_usos);
			$smarty->assign('recuperauso_minimo_bebes_usos', $recuperauso_minimo_bebes_usos);
			$smarty->assign('recuperamaximo_detalle_usos', $recuperamaximo_detalle_usos);	
			$smarty->assign('recuperauso_maximo_pax_usos', $recuperauso_maximo_pax_usos);
			$smarty->assign('recuperauso_maximo_adultos_usos', $recuperauso_maximo_adultos_usos);	
			$smarty->assign('recuperauso_maximo_ninos_usos', $recuperauso_maximo_ninos_usos);
			$smarty->assign('recuperauso_maximo_bebes_usos', $recuperauso_maximo_bebes_usos);


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_usos', $mensaje1_usos);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_usos', $mensaje2_usos);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_caracteristica_usos', $parametros['buscar_caracteristica_usos']);



			//----------------------------------------------------
			//----VISUALIZAR PARTE DE LOS PRECIOS ---------
			//----------------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_precios', $filadesde_precios);

			//Cargar combo selector
			$smarty->assign('combo_precios', $sComboSelectPrecios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('precios', $sPrecios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboHabitaciones', $comboHabitaciones);
			$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car);
			$smarty->assign('comboCalculo', $comboCalculo);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('preciosnuevos', $sPrecios_nuevos);	
			
			$smarty->assign('recuperatemporada_prec', $recuperatemporada_prec);	
			$smarty->assign('recuperahabitacion', $recuperahabitacion);	
			$smarty->assign('recuperacaracteristica', $recuperacaracteristica);	
			$smarty->assign('recuperauso', $recuperauso);	
			$smarty->assign('recuperacoste_pax', $recuperacoste_pax);	
			$smarty->assign('recuperacoste_habitacion', $recuperacoste_habitacion);	
			$smarty->assign('recuperacalculo', $recuperacalculo);	
			$smarty->assign('recuperaporcentaje_neto', $recuperaporcentaje_neto);	
			$smarty->assign('recuperaneto_pax', $recuperaneto_pax);	
			$smarty->assign('recuperaneto_habitacion', $recuperaneto_habitacion);
			$smarty->assign('recuperaporcentaje_com', $recuperaporcentaje_com);	
			$smarty->assign('recuperapvp_pax', $recuperapvp_pax);	
			$smarty->assign('recuperapvp_habitacion', $recuperapvp_habitacion);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_precios', $mensaje1_precios);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_precios', $mensaje2_precios);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_temporada_prec', $parametros['buscar_temporada_prec']);
			$smarty->assign('buscar_habitacion', $parametros['buscar_habitacion']);
			$smarty->assign('buscar_caracteristica', $parametros['buscar_caracteristica']);

			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

						//Visualizar plantilla
			if($parametros['manda_posicion']){
				$smarty->assign('posicion', $parametros['manda_posicion']);
			}else{
				$smarty->assign('posicion', 'buscar_localizador');
			}
			
			//Visualizar plantilla
			$smarty->display('plantillas/Alojamientos_Acuerdos.html');


		}elseif($parametros['ir_a'] == 'E'){

			//Llamar a al metodo de expandir cupos;

		}elseif($parametros['ir_a'] == 'B'){

			//Llamar a al metodo de borrar cupos;

		}elseif($parametros['ir_a'] == 'C'){

			header("Location: Alojamientos_cupos.php?id=".$parametros['id0']."&nombre=".$parametros['nombre0']."&acuerdo=".$parametros['acuerdo0']);

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
		if(count($_GET) != 0 and $parametrosg['id'] != '' and $parametrosg['acuerdo'] != ''){
			$parametros['buscar_id'] = $parametrosg['id'];
			$parametros['buscar_nombre'] = $parametrosg['nombre'];
			$parametros['buscar_acuerdo'] = $parametrosg['acuerdo'];
			
		}else{
			$parametros['buscar_id'] = "";
			$parametros['buscar_nombre'] = "";
			$parametros['buscar_acuerdo'] = "";
		}

			//Llamada a la clase especifica de la pantalla
			$oAcuerdos = new clsAcuerdos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo']);
			$sAcuerdos = $oAcuerdos->Cargar($recuperaid,$recuperaacuerdo,$recuperatipo,$recuperasituacion,$recuperafecha_desde,$recuperafecha_hasta,$recuperadias_entrada,$recuperaregimen_sa, $recuperaregimen_ad,$recuperaregimen_mp,$recuperaregimen_pc,$recuperaregimen_ti,$recuperagrat_tipo,$recuperagrat_uso,$recuperagrat_cada,$recuperagrat_max,$recuperadivisa,$recuperaforma_pago,$recuperacorresponsal,$recuperadescripcion,$recuperaenvio_1,$recuperaenvio_2,$recuperaenvio_3,$recuperaenvio_rooming,$recuperacaracteristica_base);
			$sComboSelectAcuerdos = $oAcuerdos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboAlojamientos = $oSino->Cargar_combo_Alojamientos_like($parametros['buscar_nombre']);
			//$comboAlojamientos = $oSino->Cargar_combo_Alojamientos();
			$comboTipos_Acuerdo = $oSino->Cargar_combo_Tipos_acuerdo();
			$comboSituacion_Acuerdo = $oSino->Cargar_combo_Situacion_acuerdo();
			$comboDivisas = $oSino->Cargar_combo_Divisas();
			$comboTipo_pago = $oSino->Cargar_combo_Tipo_Pago();
			$comboForma_Pago = $oSino->Cargar_combo_Forma_pago();
			$comboTipo_Gratuidad = $oSino->Cargar_combo_Tipos_Gratuidad();
			$comboCorresponsales = $oSino->Cargar_combo_Corresponsales();
			$comboAcuerdos = $oSino->Cargar_combo_Acuerdos_UnAlojamiento($parametros['buscar_id']);

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
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
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
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboTipos_Acuerdo', $comboTipos_Acuerdo);
			$smarty->assign('comboSituacion_Acuerdo', $comboSituacion_Acuerdo);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboTipo_pago', $comboTipo_pago);
			$smarty->assign('comboForma_Pago', $comboForma_Pago);
			$smarty->assign('comboTipo_Gratuidad', $comboTipo_Gratuidad);
			$smarty->assign('comboCorresponsales', $comboCorresponsales);
			$smarty->assign('comboAcuerdos', $comboAcuerdos);

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_acuerdo', $parametros['buscar_acuerdo']);

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE PERIODOS

		$filadesde_periodos = 1;
		$parametros['buscar_fecha'] = "";

		//Llamada a la clase especifica de la pantalla
		$sPeriodos = $oAcuerdos->Cargar_periodos($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_periodos,$parametros['buscar_fecha']);		
		$sComboSelectPeriodos = $oAcuerdos->Cargar_combo_selector_periodos($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['buscar_fecha']);
		$sPeriodos_nuevos = $oAcuerdos->Cargar_lineas_nuevas_periodos();	
		//Llamada a la clase general de combos
		//$comboTemporadas = $oSino->Cargar_combo_Temporadas();

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades_periodos', $filadesde_periodos);
		
		//Cargar combo selector
		$smarty->assign('combo_periodos', $sComboSelectPeriodos);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('periodos', $sPeriodos);

		//Cargar combos de las lineas de la tabla
		//$smarty->assign('comboTemporadas', $comboTemporadas);

		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('periodosnuevos', $sPeriodos_nuevos);	
			
		$smarty->assign('recuperatemporada', $recuperatemporada);	
		$smarty->assign('recuperafecha_desde_periodo', $recuperafecha_desde_periodo);	
			$smarty->assign('recuperafecha_hasta_periodo', $recuperafecha_hasta_periodo);	
			$smarty->assign('recuperadias_release', $recuperadias_release);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_periodos', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_periodos', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_fecha', '');

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LAS TEMPORADAS

		$filadesde_temporadas = 1;
		$parametros['buscar_temporada'] = "";

		//Llamada a la clase especifica de la pantalla
		$sTemporadas = $oAcuerdos->Cargar_temporadas($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_periodos,$parametros['buscar_temporada']);		
		$sComboSelectTemporadas = $oAcuerdos->Cargar_combo_selector_temporadas($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['buscar_temporada']);
		$sTemporadas_nuevos = $oAcuerdos->Cargar_lineas_nuevas_temporadas();	
		//Llamada a la clase general de combos
		$comboSinoSi = $oSino->Cargar_combo_SiNo_Si();

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades_temporadas', $filadesde_temporadas);

		//Cargar combo selector
		$smarty->assign('combo_temporadas', $sComboSelectTemporadas);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('temporadas', $sTemporadas);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboSinoSi', $comboSinoSi);

		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('temporadasnuevos', $sTemporadas_nuevos);	
			
		$smarty->assign('recuperatemporada_temp', $recuperatemporada_temp);	
		$smarty->assign('recuperaspto_ad', $recuperaspto_ad);	
		$smarty->assign('recuperaspto_mp', $recuperaspto_mp);	
		$smarty->assign('recuperaspto_pc', $recuperaspto_pc);	
		$smarty->assign('recuperaspto_ti', $recuperaspto_ti);	
		$smarty->assign('recuperapermite_sa', $recuperapermite_sa);
		$smarty->assign('recuperapermite_ad', $recuperapermite_ad);
		$smarty->assign('recuperapermite_mp', $recuperapermite_mp);
		$smarty->assign('recuperapermite_pc', $recuperapermite_pc);
		$smarty->assign('recuperapermite_ti', $recuperapermite_ti);

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1_temporadas', '');

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2_temporadas', '');

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_temporada', '');


		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LAS CONDICIONES

		$filadesde_condiciones = 1;
		$parametros['buscar_tipo'] = "";

		//Llamada a la clase especifica de la pantalla
		$sCondiciones = $oAcuerdos->Cargar_condiciones($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_temporadas,$parametros['buscar_tipo']);	
		$sComboSelectCondiciones = $oAcuerdos->Cargar_combo_selector_condiciones($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_condiciones,$parametros['buscar_tipo']);;
		$sCondiciones_nuevos = $oAcuerdos->Cargar_lineas_nuevas_condiciones();	
		//Llamada a la clase general de combos
		$comboTipos_pasajeros = $oSino->Cargar_combo_Pasajeros_condiciones_acuerdos();
		$comboForma_calculo = $oSino->Cargar_combo_Forma_Calculo_acuerdos_alojamientos();
		$comboRegimen_condiciones = $oSino->Cargar_combo_Regimen_Producto();

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades_condiciones', $filadesde_condiciones);

		//Cargar combo selector
		$smarty->assign('combo_condiciones', $sComboSelectCondiciones);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('condiciones', $sCondiciones);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboTipos_pasajeros', $comboTipos_pasajeros);
		$smarty->assign('comboForma_calculo', $comboForma_calculo);
		$smarty->assign('comboRegimen_condiciones', $comboRegimen_condiciones);

		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('condicionesnuevos', $sCondiciones_nuevos);	
			
		$smarty->assign('recuperafecha_desde_condiciones', $recuperafecha_desde_condiciones);
		$smarty->assign('recuperafecha_hasta_condiciones', $recuperafecha_hasta_condiciones);	
		$smarty->assign('recuperareserva_desde_condiciones', $recuperareserva_desde_condiciones);
		$smarty->assign('recuperareserva_hasta_condiciones', $recuperareserva_hasta_condiciones);
		$smarty->assign('recuperauso_condiciones', $recuperauso_condiciones);	
		$smarty->assign('recuperatipo_condiciones', $recuperatipo_condiciones);	
		$smarty->assign('recuperaedad_desde_condiciones', $recuperaedad_desde_condiciones);	
		$smarty->assign('recuperaedad_hasta_condiciones', $recuperaedad_hasta_condiciones);	
		$smarty->assign('recuperaregimen_condiciones', $recuperaregimen_condiciones);	
		$smarty->assign('recuperacalculo_condiciones', $recuperacalculo_condiciones);	
		$smarty->assign('recuperavalor_condiciones', $recuperavalor_condiciones);
		$smarty->assign('recuperavalor_pvp_condiciones', $recuperavalor_pvp_condiciones);

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1_condiciones', '');

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2_condiciones', '');

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_tipo', '');

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS USOS

		$filadesde_usos = 1;
		$parametros['buscar_caracteristica_usos'] = "";

		//Llamada a la clase especifica de la pantalla
		$sUsos = $oAcuerdos->Cargar_usos($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_usos,$parametros['buscar_caracteristica_usos']);	
		$sComboSelectUsos = $oAcuerdos->Cargar_combo_selector_usos($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$parametros['buscar_caracteristica_usos']);
		$sUsos_nuevos = $oAcuerdos->Cargar_lineas_nuevas_usos();	

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades_usos', $filadesde_usos);
		

		//Cargar combo selector
		$smarty->assign('combo_usos', $sComboSelectUsos);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('usos', $sUsos);

		//Cargar combos de las lineas de la tabla
		/*$smarty->assign('comboTipos_pasajeros', $comboTipos_pasajeros);
		$smarty->assign('comboForma_calculo', $comboForma_calculo);*/

		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('usosnuevos', $sUsos_nuevos);	
		
		$smarty->assign('recuperacaracteristica_usos', $recuperacaracteristica_usos);	
		$smarty->assign('recuperaminimo_detalle_usos', $recuperaminimo_detalle_usos);	
		$smarty->assign('recuperauso_minimo_usos', $recuperauso_minimo_usos);	
		$smarty->assign('recuperauso_minimo_adultos_usos', $recuperauso_minimo_adultos_usos);	
		$smarty->assign('recuperauso_minimo_ninos_usos', $recuperauso_minimo_ninos_usos);
		$smarty->assign('recuperauso_minimo_bebes_usos', $recuperauso_minimo_bebes_usos);		
		$smarty->assign('recuperamaximo_detalle_usos', $recuperamaximo_detalle_usos);	
		$smarty->assign('recuperauso_maximo_pax_usos', $recuperauso_maximo_pax_usos);
		$smarty->assign('recuperauso_maximo_adultos_usos', $recuperauso_maximo_adultos_usos);	
		$smarty->assign('recuperauso_maximo_ninos_usos', $recuperauso_maximo_ninos_usos);
		$smarty->assign('recuperauso_maximo_bebes_usos', $recuperauso_maximo_bebes_usos);


		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1_usos', $mensaje1_usos);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2_usos', $mensaje2_usos);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_caracteristica_usos', '');

		//-----------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS PRECIOS

		$filadesde_precios = 1;
		$parametros['buscar_temporada_prec'] = "";
		$parametros['buscar_habitacion'] = "";
		$parametros['buscar_caracteristica'] = "";

		//Llamada a la clase especifica de la pantalla
		$sPrecios = $oAcuerdos->Cargar_precios($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_precios,$parametros['buscar_temporada_prec'],$parametros['buscar_habitacion'],$parametros['buscar_caracteristica']);	
		$sComboSelectPrecios = $oAcuerdos->Cargar_combo_selector_precios($sAcuerdos[0]['id'],$sAcuerdos[0]['acuerdo'],$filadesde_precios,$parametros['buscar_temporada_prec'],$parametros['buscar_habitacion'],$parametros['buscar_caracteristica']);
		$sPrecios_nuevos = $oAcuerdos->Cargar_lineas_nuevas_precios();	

		//Llamada a la clase general de combos
		$comboHabitaciones = $oSino->Cargar_combo_Habitaciones();
		$comboHabitaciones_car = $oSino->Cargar_combo_Habitaciones_car();
		$comboCalculo = $oSino->Cargar_combo_Calculo();

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades_precios', $filadesde_precios);
		
		//Cargar combo selector
		$smarty->assign('combo_precios', $sComboSelectPrecios);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('precios', $sPrecios);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboHabitaciones', $comboHabitaciones);
		$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car);
		$smarty->assign('comboCalculo', $comboCalculo);

		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('preciosnuevos', $sPrecios_nuevos);	
		
		$smarty->assign('recuperatemporada_prec', $recuperatemporada_prec);	
		$smarty->assign('recuperahabitacion', $recuperahabitacion);	
		$smarty->assign('recuperacaracteristica', $recuperacaracteristica);	
		$smarty->assign('recuperauso', $recuperauso);	
		$smarty->assign('recuperacoste_pax', $recuperacoste_pax);	
		$smarty->assign('recuperacoste_habitacion', $recuperacoste_habitacion);	
		$smarty->assign('recuperacalculo', $recuperacalculo);
		$smarty->assign('recuperaporcentaje_neto', $recuperaporcentaje_neto);	
		$smarty->assign('recuperaneto_pax', $recuperaneto_pax);	
		$smarty->assign('recuperaneto_habitacion', $recuperaneto_habitacion);
		$smarty->assign('recuperaporcentaje_com', $recuperaporcentaje_com);	
		$smarty->assign('recuperapvp_pax', $recuperapvp_pax);	
		$smarty->assign('recuperapvp_habitacion', $recuperapvp_habitacion);	

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1_precios', '');

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2_precios', '');
	
		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_temporada_prec', '');
		$smarty->assign('buscar_habitacion', '');
		$smarty->assign('buscar_caracteristica', '');

		//Posicionar plantilla
		$smarty->assign('posicion', 'buscar_acuerdo');
		
		//Visualizar plantilla
		$smarty->display('plantillas/Alojamientos_acuerdos.html');
	}
	
	$conexion->close();


?>

