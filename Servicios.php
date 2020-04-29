<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/servicios.cls.php';


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
			$recuperaid_proveedor = '';
			$recuperacodigo = '';
			$recuperatipo = '';
			$recuperanombre = '';
			$recuperaciudad = '';
			$recuperatipo_cupo = '';
			$recuperadias_semana = '';
			$recuperahora_desde = '';
			$recuperahora_hasta = '';
			$recuperasituacion = '';
			$recuperapago_tipo = '';
			$recuperapago_plazo = '';
			$recuperapago_forma = '';
			$recuperadivisa = '';
			$recuperagratuidades_cantidad = '';
			$recuperagratuidades_cada = '';
			$recuperagratuidades_maximo = '';
			$recuperacorresponsal = '';
			$recuperaen_reserva = '';
			$recuperaimprimir_bono = '';
			$recuperatipo_pvp = '';
			$recuperadescripcion = '';

			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			$insertaServicio = '';

			//VARIABLES PARA LOS PRECIOS

			$recuperafecha_desde_precios = '';
			$recuperafecha_hasta_precios = '';
			$recuperaprecios_dias_semana = '';
			$recuperatipo_unidad = '';
			$recuperaunidad_desde = '';
			$recuperaunidad_hasta = '';
			$recuperacoste = '';
			$recuperacoste_ninos = '';
			$recuperacalculo= '';
			$recuperaporcentaje_neto = '';
			$recuperaneto = '';
			$recuperaporcentaje_com = '';
			$recuperapvp = '';


			$recuperatipo_cupo_precios = '';
			$recuperadias_release_precios = '';
			$recuperaexpandido = '';


			//solo para precios de programa
			$recuperapaquete = '';
			$recuperahabitacion = '';
			$recuperacaracteristica = '';
			$recuperauso = '';

			$mensaje1_precios = '';
			$mensaje2_precios = '';
			$error_precios = '';


	if(count($_POST) != 0){
		//esto es por si venimos de la pantalla de listado de cupos
		if($parametros['buscar_id'] == null and $parametros['buscar_id_prove'] == null and $parametros['buscar_codigo'] == null and $parametros['buscar_tipo'] == null and $parametros['buscar_ciudad'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_id_prove'] = $parametrosg['id_prove'];
				$parametros['buscar_codigo'] = $parametrosg['servicio'];
			}
		//esto es por si hemos seleccionado un servicio del combo de servicios del proveedor
		}elseif($parametros['servicios'] != null){
				$parametros['buscar_codigo'] = $parametros['servicios'];
		}

		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'C'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE SERVICIOS----------
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonServicios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
				$botonselec = $botonServicios->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mServicios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
							$modificaServicios = $mServicios->Modificar($parametros['id'.$num_fila],$parametros['id_proveedor'.$num_fila],$parametros['codigo'.$num_fila],$parametros['tipo'.$num_fila],$parametros['nombre'.$num_fila],$parametros['ciudad'.$num_fila],$parametros['tipo_cupo'.$num_fila],$parametros['dias_semana'.$num_fila],$parametros['hora_desde'.$num_fila],$parametros['hora_hasta'.$num_fila],$parametros['situacion'.$num_fila],$parametros['pago_tipo'.$num_fila],$parametros['pago_plazo'.$num_fila],$parametros['pago_forma'.$num_fila],$parametros['divisa'.$num_fila],$parametros['gratuidades_cantidad'.$num_fila],$parametros['gratuidades_cada'.$num_fila],$parametros['gratuidades_maximo'.$num_fila],$parametros['corresponsal'.$num_fila],$parametros['en_reserva'.$num_fila],$parametros['imprimir_bono'.$num_fila],$parametros['tipo_pvp'.$num_fila],$parametros['descripcion'.$num_fila]);
							if($modificaServicios == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaServicios;
							}





							//echo($_FILES['upfile'.$num_fila]['size']);
							$resultado = '';
							if($_FILES['upfile'.$num_fila]['size'] > 0){
								//SUBIMOS LOS ARCHIVOS DE IMAGEN

								$max=1000000; //Establece el tamaño maximo del archivo
								$filesize = $_FILES['upfile'.$num_fila]['size']; //obtiene el tamaño del archivo
								$filename = trim($_FILES['upfile'.$num_fila]['name']); // obtiene el nombre del archivo
								$type = $_FILES['upfile'.$num_fila]['type']; //obtiene el tipo del archivo


								if($filesize < $max){
									if($filesize > 0){
										if($type == 'image/jpeg' || $type == 'image/JPEG' || $type == 'image/jpg' || $type == 'image/JPG' 
											|| $type == 'image/gif' || $type == 'image/GIF' ){
											$uploadfile = "imagenes/servicios/".$parametros['id'.$num_fila].".jpg";
											if (move_uploaded_file($_FILES['upfile'.$num_fila]['tmp_name'], $uploadfile)) {
												$resultado = "<div><font color='#003399' size='3' ><b> Archivo subido correctamente.";
												
												//SI LA IMAGEN A SUBIDO CORRECTAMENTE GRABAMOS EL REGISTRO
												/*$iAlojamientos_imagenes = new clsAlojamientos_imagenes($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento'], $parametros['buscar_tipo']);
												$insertaAlojamientos_imagenes = $iAlojamientos_imagenes->Insertar($parametros['Nuevoalojamiento'.$num_fila], $parametros['Nuevotipo'.$num_fila], $parametros['Nuevonumero'.$num_fila]);
												if($insertaAlojamientos_imagenes == 'OK'){
													$Ntransacciones++;
												}else{
													$error = "<div><font color='#993300' size='3' ><b> Se ha producido el siguiente error: ".$insertaAlojamientos_imagenes;
													//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
													$recuperacodigo = $parametros['Nuevoalojamiento'.$num_fila]; 
													$recuperanombre = $parametros['Nuevotipo'.$num_fila];
													$recuperanumero = $parametros['Nuevonumero'.$num_fila];
												}*/

											}else{
												$error = "<div><font color='#003399' size='3' ><b> Error de conexión con el servidor.";
											}
										}else{
											$error = "<div><font color='#003399' size='3' ><b> Sólo se permiten imágenes en formato jpg. y gif., no se ha podido adjuntar.";
										}
									}else{
										$error = "<div><font color='#003399' size='3' ><b> No ha seleccionado ninguna imagen. Seleccione una y vuelva a intentarlo";
									}
								}else{
									$error = "<div><font color='#003399' size='3' ><b> La imagen que ha intentado adjuntar es mayor de 1000 KB, si lo desea cambie el tamaño del archivo y vuelva a intentarlo.";
								}

							}




						}else{

							$mServicios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
							$borraServicios = $mServicios->Borrar($parametros['id'.$num_fila]);
							if($borraServicios == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraServicios;
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
				//$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_GESTION' AND USUARIO = '".$usuario."'");
				//$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				$Ntransacciones = 0;				

				$iServicios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
				$insertaServicio = $iServicios->Insertar($parametros['id_proveedor0'],$parametros['codigo0'],$parametros['tipo0'],$parametros['nombre0'],$parametros['ciudad0'],$parametros['tipo_cupo0'],$parametros['dias_semana0'],$parametros['hora_desde0'],$parametros['hora_hasta0'],$parametros['situacion0'],$parametros['pago_tipo0'],$parametros['pago_plazo0'],$parametros['pago_forma0'],$parametros['divisa0'],$parametros['gratuidades_cantidad0'],$parametros['gratuidades_cada0'],$parametros['gratuidades_maximo0'],$parametros['corresponsal0'],$parametros['en_reserva0'],$parametros['imprimir_bono0'],$parametros['tipo_pvp0'],$parametros['descripcion0']);

				if($insertaServicio == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_id'] = '';
					$parametros['buscar_id_prove'] = $parametros['id_proveedor0'];
					$parametros['buscar_codigo'] = $parametros['codigo0'];
					$parametros['buscar_tipo'] = $parametros['tipo0'];
					$parametros['buscar_ciudad'] = $parametros['ciudad0'];

				}else{
					$error = $insertaServicio;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					//$recuperaid = $parametros['id'.$num_fila];
					$recuperaid = $parametros['id0'];
					$recuperaid_proveedor = $parametros['id_proveedor0'];
					$recuperacodigo = $parametros['codigo0'];
					$recuperatipo = $parametros['tipo0'];
					$recuperanombre = $parametros['nombre0'];
					$recuperaciudad = $parametros['ciudad0'];
					$recuperatipo_cupo = $parametros['tipo_cupo0'];
					$recuperadias_semana = $parametros['dias_semana0'];
					$recuperahora_desde = $parametros['hora_desde0'];
					$recuperahora_hasta = $parametros['hora_hasta0'];
					$recuperasituacion = $parametros['situacion0'];
					$recuperapago_tipo = $parametros['pago_tipo0'];
					$recuperapago_plaza = $parametros['pago_plazo0'];
					$recuperapago_forma = $parametros['pago_forma0'];
					$recuperadivisa = $parametros['divisa0'];
					$recuperagratuidades_cantidad = $parametros['gratuidades_cantidad0'];
					$recuperagratuidades_cada = $parametros['gratuidades_cada0'];
					$recuperagratuidades_maximo = $parametros['gratuidades_maximo0'];
					$recuperacorresponsal = $parametros['corresponsal0'];
					$recuperaen_reserva = $parametros['en_reserva0'];
					$recuperaimprimir_bono = $parametros['imprimir_bono0'];
					$recuperatipo_pvp = $parametros['tipo_pvp0'];
					$recuperadescripcion = $parametros['descripcion0'];
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
			$oServicios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
			$sServicios = $oServicios->Cargar($recuperaid_proveedor,$recuperacodigo,$recuperatipo,$recuperanombre,$recuperaciudad,$recuperatipo_cupo,$recuperadias_semana,$recuperahora_desde,$recuperahora_hasta,$recuperasituacion,$recuperapago_tipo,$recuperapago_plazo,$recuperapago_forma,$recuperadivisa,$recuperagratuidades_cantidad,$recuperagratuidades_cada,$recuperagratuidades_maximo,$recuperacorresponsal,$recuperaen_reserva,$recuperaimprimir_bono,$recuperatipo_pvp,$recuperadescripcion);
			$sComboSelectServicios = $oServicios->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboProveedores = $oSino->Cargar_combo_Proveedores();
			$comboTipos_servicio = $oSino->Cargar_combo_Tipos_servicio();
			$comboCiudades = $oSino->Cargar_combo_Ciudades();
			$comboTipos_cupo = $oSino->Cargar_combo_Tipos_cupo();
			$comboSituacion_servicio = $oSino->Cargar_combo_Situacion_servicio();
			$comboTipo_pago = $oSino->Cargar_combo_Tipo_Pago();
			$comboForma_Pago = $oSino->Cargar_combo_Forma_Pago();
			$comboDivisas = $oSino->Cargar_combo_Divisas();
			$comboCorresponsales = $oSino->Cargar_combo_Corresponsales();
			$comboImprimir_Bono = $oSino->Cargar_combo_Imprimir_Bono();
			$comboTipo_Pvp = $oSino->Cargar_combo_Tipo_Pvp();
			$comboServicios = $oSino->Cargar_combo_Servicios_UnProveedor($parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);

	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS PRECIOS ----------------
	//--------------------------------------------------------
			//Distinguimos si hay que mostrar los precios para programas completos o para el resto de servicios
			//PRECIOS RESTO DE SERVICIOS
			//if($parametros['tipo0'] != 'PRO'){
			if($sServicios[0]['tipo'] != 'PRO'){

				$filadesde_precios = $parametros['filadesde_precios'];

				//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
				if($parametros['botonSelector_2nivel'] != 0){
					$botonPrecios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
					$botonselec_precios = $botonPrecios->Botones_selector_precios($filadesde_precios, $parametros['botonSelector_2nivel'],$sServicios[0]['id'],$parametros['buscar_fecha']);
					$filadesde_precios = $botonselec_precios;
				}

				//Llamada a la clase especifica de la pantalla
				$sPrecios = $oServicios->Cargar_precios($sServicios[0]['id'],$filadesde_precios,$parametros['buscar_fecha']);
				//lineas visualizadas
				$vueltas = count($sPrecios);

				if($parametros['actuar_2nivel'] == 'S'){

					//MODIFICAR Y BORRAR REGISTROS
					/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS' AND USUARIO = '".$usuario."'");
					$Nfilas	 = $num_filas->fetch_assoc();*/
					$Ntransacciones_precios = 0;

					for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {

						if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
							if($parametros['selec_2nivel'.$num_fila] == 'S'){
								
								$mPrecios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
								$modificaPrecios = $mPrecios->Modificar_precios($sServicios[0]['id'], $parametros['fecha_desde_precios'.$num_fila], $parametros['fecha_hasta_precios'.$num_fila],$parametros['precios_dias_semana'.$num_fila],$parametros['tipo_unidad'.$num_fila], $parametros['unidad_desde'.$num_fila], $parametros['unidad_hasta'.$num_fila],$parametros['coste'.$num_fila],$parametros['coste_ninos'.$num_fila],
									$parametros['tipo_cupo_precios'.$num_fila],
									$parametros['dias_release_precios'.$num_fila],
									$parametros['expandido'.$num_fila],
									$parametros['fecha_desde_precios_old'.$num_fila], $parametros['fecha_hasta_precios_old'.$num_fila],$parametros['tipo_unidad_old'.$num_fila], $parametros['unidad_desde_old'.$num_fila], $parametros['unidad_hasta_old'.$num_fila], $parametros['precios_dias_semana_old'.$num_fila]);
								if($modificaPrecios == 'OK'){
									$Ntransacciones_precios++;
								}else{
									$error_precios = $modificaPrecios;
								}

							}else{

								$mPrecios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
								$borraPrecios = $mPrecios->Borrar_precios($sServicios[0]['id'], $parametros['fecha_desde_precios'.$num_fila],$parametros['fecha_hasta_precios'.$num_fila], $parametros['tipo_unidad'.$num_fila],$parametros['unidad_desde'.$num_fila], $parametros['unidad_hasta'.$num_fila],$parametros['precios_dias_semana'.$num_fila]);
								if($borraPrecios == 'OK'){
									$Ntransacciones_precios++;
								}else{
									$error_precios = $borraPrecios;
								}
							}
						}
					}

					//AÑADIR REGISTROS
					$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS' AND USUARIO = '".$usuario."'");
					$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
					

					for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
						
						if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){

							$iPrecios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
							$insertaPrecios = $iPrecios->Insertar_precios($sServicios[0]['id'], $parametros['Nuevofecha_desde_precios'.$num_fila], $parametros['Nuevofecha_hasta_precios'.$num_fila],$parametros['Nuevoprecios_dias_semana'.$num_fila],$parametros['Nuevotipo_unidad'.$num_fila],$parametros['Nuevounidad_desde'.$num_fila],$parametros['Nuevounidad_hasta'.$num_fila],$parametros['Nuevocoste'.$num_fila],$parametros['Nuevocoste_ninos'.$num_fila],$parametros['Nuevotipo_cupo_precios'.$num_fila],$parametros['Nuevodias_release_precios'.$num_fila],$parametros['Nuevoexpandido'.$num_fila]);
							if($insertaPrecios == 'OK'){
								$Ntransacciones_precios++;
							}else{
								$error_precios = $insertaPrecios;
								//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
								$recuperafecha_desde_precios = $parametros['Nuevofecha_desde_precios'.$num_fila]; 
								$recuperafecha_hasta_precios = $parametros['Nuevofecha_hasta_precios'.$num_fila]; 
								$recuperaprecios_dias_semana = $parametros['Nuevoprecios_dias_semana'.$num_fila]; 
								$recuperatipo_unidad = $parametros['Nuevotipo_unidad'.$num_fila]; 
								$recuperaunidad_desde = $parametros['Nuevounidad_desde'.$num_fila]; 
								$recuperaunidad_hasta = $parametros['Nuevounidad_hasta'.$num_fila]; 
								$recuperacoste = $parametros['Nuevocoste'.$num_fila]; 
								$recuperacoste_ninos = $parametros['Nuevocoste_ninos'.$num_fila]; 

								$recuperatipo_cupo_precios = $parametros['Nuevotipo_cupo_precios'.$num_fila]; 
								$recuperadias_release_precios = $parametros['Nuevodias_release_precios'.$num_fila]; 
								$recuperaexpandido = $parametros['Nuevoexpandido'.$num_fila]; 

								//$recuperacalculo = $parametros['Nuevocalculo'.$num_fila]; 
								//$recuperaporcentaje_neto = $parametros['Nuevoporcentaje_neto'.$num_fila]; 
								//$recuperaneto = $parametros['Nuevoneto'.$num_fila]; 
								//$recuperaporcentaje_com = $parametros['Nuevoporcentaje_com'.$num_fila]; 
								//$recuperapvp = $parametros['Nuevopvp'.$num_fila]; 
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

				}elseif($parametros['actuar_2nivel'] == 'R'){

					$recalcula_precios = "CALL `PROVEEDORES_RECALCULA_PVP_SERVICIO_CUADROS`('".$sServicios[0]['id_proveedor']."', '".$sServicios[0]['codigo']."')";

					$resultadorecalcula_precios =$conexion->query($recalcula_precios);
							
					if ($resultadorecalcula_precios == FALSE){
						$error_precios = 'No se han podido recalcular las nuevas condiciones del producto. '.$conexion->error;
					}else{
						$Ntransacciones_precios = 'OK';
					}
					//Mostramos mensajes y posibles errores
					$mensaje1_precios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_precios."</b></font></div>";
					if($error_precios != ''){
						$mensaje2_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_precios."</b></font></div>";
					}

				}elseif($parametros['actuar_2nivel'] == 'E'){


					$Ntransacciones_precios = 0;
					$error_cupos = '';

					for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {

						//echo($parametros['selec_2nivel'.$num_fila].'<br>');

						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							

							$expande_cupos = "CALL `PROVEEDORES_EXPANDE_CUPOS_SERVICIOS`('".$sServicios[0]['id']."', '".date("Y-m-d",strtotime($parametros['fecha_desde_precios'.$num_fila]))."', '".date("Y-m-d",strtotime($parametros['fecha_hasta_precios'.$num_fila]))."', '".$parametros['dias_release_precios'.$num_fila]."', '".$parametros['precios_dias_semana'.$num_fila]."')";

							//echo($expande_cupos);

							$resultadoexpande_cupos =$conexion->query($expande_cupos);
									
							if ($resultadoexpande_cupos == FALSE){
								$error_cupos = 'No se han podido expandir los cupos. '.$conexion->error;
							}else{

								//Ponemos el campo de 'Expandido' a Si.



								$query = "UPDATE hit_servicios_precios SET ";

								$query .= " EXPANDIDO = 'S'";		
								$query .= " WHERE ID = '".$sServicios[0]['id']."'";
								$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($parametros['fecha_desde_precios'.$num_fila]))."'";
								$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($parametros['fecha_hasta_precios'.$num_fila]))."'";
								$query .= " AND TIPO_UNIDAD = '".$parametros['tipo_unidad'.$num_fila]."'";
								$query .= " AND UNIDAD_DESDE = '".$parametros['unidad_desde'.$num_fila]."'";
								$query .= " AND UNIDAD_HASTA = '".$parametros['unidad_hasta'.$num_fila]."'";
								$query .= " AND DIAS_SEMANA = '".$parametros['precios_dias_semana'.$num_fila]."'";

								$resultadom =$conexion->query($query);

								if ($resultadom == FALSE){
									$error_cupos = $conexion->error;
									//echo($query);
								}else{
									$Ntransacciones_cupo = 'OK';
									//echo($query);
								}

							}
							//Mostramos mensajes y posibles errores
							$mensaje1_precios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_cupo."</b></font></div>";
							if($error_cupos != ''){
								$mensaje2_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_cupos."</b></font></div>";
							}

						}

					}

				}elseif($parametros['actuar_2nivel'] == 'B'){


					$Ntransacciones_precios = 0;
					$error_cupos = '';

					for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {

						//echo($parametros['selec_2nivel'.$num_fila].'<br>');

						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							

							$expande_cupos = "CALL `PROVEEDORES_BORRA_CUPOS_SERVICIOS`('".$sServicios[0]['id']."', '".date("Y-m-d",strtotime($parametros['fecha_desde_precios'.$num_fila]))."', '".date("Y-m-d",strtotime($parametros['fecha_hasta_precios'.$num_fila]))."', '".$parametros['precios_dias_semana'.$num_fila]."')";

							//echo($expande_cupos);

							$resultadoexpande_cupos =$conexion->query($expande_cupos);
									
							if ($resultadoexpande_cupos == FALSE){
								$error_cupos = 'No se han podido expandir los cupos. '.$conexion->error;
							}else{

								//Ponemos el campo de 'Expandido' a Si.

								$query = "UPDATE hit_servicios_precios SET ";

								$query .= " EXPANDIDO = 'N'";		
								$query .= " WHERE ID = '".$sServicios[0]['id']."'";
								$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($parametros['fecha_desde_precios'.$num_fila]))."'";
								$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($parametros['fecha_hasta_precios'.$num_fila]))."'";
								$query .= " AND TIPO_UNIDAD = '".$parametros['tipo_unidad'.$num_fila]."'";
								$query .= " AND UNIDAD_DESDE = '".$parametros['unidad_desde'.$num_fila]."'";
								$query .= " AND UNIDAD_HASTA = '".$parametros['unidad_hasta'.$num_fila]."'";
								$query .= " AND DIAS_SEMANA = '".$parametros['precios_dias_semana'.$num_fila]."'";

								$resultadom =$conexion->query($query);

								if ($resultadom == FALSE){
									$error_cupos = $conexion->error;
									//echo($query);
								}else{
									$Ntransacciones_cupo = 'OK';
									//echo($query);
								}

							}
							//Mostramos mensajes y posibles errores
							$mensaje1_precios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_cupo."</b></font></div>";
							if($error_cupos != ''){
								$mensaje2_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_cupos."</b></font></div>";
							}

						}

					}

				}


				//Llamada a la clase especifica de la pantalla
				$sPrecios = $oServicios->Cargar_precios($sServicios[0]['id'],$filadesde_precios,$parametros['buscar_fecha']);	
				$sComboSelectPrecios = $oServicios->Cargar_combo_selector_precios($sServicios[0]['id'],$parametros['buscar_fecha']);
				$sPrecios_nuevos = $oServicios->Cargar_lineas_nuevas_precios();	
				//Llamada a la clase general de combos
				$comboTipo_unidad = $oSino->Cargar_combo_Tipo_Unidad();
				$comboCalculo = $oSino->Cargar_combo_Calculo();
				$comboSino_No = $oSino->Cargar_combo_SiNo_No();

			//PRECIOS PROGRAMAS COMPLETOS
			}else{

				$filadesde_precios = $parametros['filadesde_precios'];

				//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
				if($parametros['botonSelector_2nivel'] != 0){
					$botonPrecios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
					$botonselec_precios = $botonPrecios->Botones_selector_precios_programas($filadesde_precios, $parametros['botonSelector_2nivel'],$sServicios[0]['id'],$parametros['buscar_fecha']);
					$filadesde_precios = $botonselec_precios;
				}

				//Llamada a la clase especifica de la pantalla
				$sPrecios = $oServicios->Cargar_precios_programas($sServicios[0]['id'],$filadesde_precios,$parametros['buscar_fecha']);	
				//lineas visualizadas
				$vueltas = count($sPrecios);


				if($parametros['actuar_2nivel'] == 'S'){

					//MODIFICAR Y BORRAR REGISTROS
					/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS_PROGRAMAS' AND USUARIO = '".$usuario."'");
					$Nfilas	 = $num_filas->fetch_assoc();*/
					$Ntransacciones_precios = 0;

					for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
						if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
							if($parametros['selec_2nivel'.$num_fila] == 'S'){
								
								$mPrecios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
								$modificaPrecios = $mPrecios->Modificar_precios_programas($sServicios[0]['id'], $parametros['fecha_desde_precios'.$num_fila], $parametros['fecha_hasta_precios'.$num_fila],$parametros['precios_dias_semana'.$num_fila],$parametros['tipo_unidad'.$num_fila], $parametros['unidad_desde'.$num_fila], $parametros['unidad_hasta'.$num_fila], $parametros['paquete'.$num_fila], $parametros['habitacion'.$num_fila], $parametros['caracteristica'.$num_fila],$parametros['uso'.$num_fila],$parametros['coste'.$num_fila],$parametros['coste_ninos'.$num_fila],$parametros['tipo_cupo_precios'.$num_fila],
									$parametros['dias_release_precios'.$num_fila],
									$parametros['expandido'.$num_fila],$parametros['fecha_desde_precios_old'.$num_fila], $parametros['fecha_hasta_precios_old'.$num_fila],$parametros['tipo_unidad_old'.$num_fila], $parametros['unidad_desde_old'.$num_fila], $parametros['unidad_hasta_old'.$num_fila],$parametros['paquete_old'.$num_fila],$parametros['habitacion_old'.$num_fila],$parametros['caracteristica_old'.$num_fila],$parametros['uso_old'.$num_fila], $parametros['precios_dias_semana_old'.$num_fila]);
								if($modificaPrecios == 'OK'){
									$Ntransacciones_precios++;
								}else{
									$error_precios = $modificaPrecios;
								}

							}else{

								$mPrecios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
								$borraPrecios = $mPrecios->Borrar_precios_programas($sServicios[0]['id'], $parametros['fecha_desde_precios'.$num_fila],$parametros['fecha_hasta_precios'.$num_fila], $parametros['tipo_unidad'.$num_fila],$parametros['unidad_desde'.$num_fila], $parametros['unidad_hasta'.$num_fila], $parametros['paquete'.$num_fila], $parametros['habitacion'.$num_fila], $parametros['caracteristica'.$num_fila], $parametros['uso'.$num_fila],$parametros['precios_dias_semana'.$num_fila]);
								if($borraPrecios == 'OK'){
									$Ntransacciones_precios++;
								}else{
									$error_precios = $borraPrecios;
								}
							}
						}
					}

					//AÑADIR REGISTROS
					$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS_PROGRAMAS' AND USUARIO = '".$usuario."'");
					$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
					

					for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
						
						if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){

							$iPrecios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
							$insertaPrecios = $iPrecios->Insertar_precios_programas($sServicios[0]['id'], $parametros['Nuevofecha_desde_precios'.$num_fila], $parametros['Nuevofecha_hasta_precios'.$num_fila], $parametros['Nuevoprecios_dias_semana'.$num_fila],$parametros['Nuevotipo_unidad'.$num_fila],$parametros['Nuevounidad_desde'.$num_fila],$parametros['Nuevounidad_hasta'.$num_fila],$parametros['Nuevopaquete'.$num_fila],$parametros['Nuevohabitacion'.$num_fila],$parametros['Nuevocaracteristica'.$num_fila],$parametros['Nuevouso'.$num_fila],$parametros['Nuevocoste'.$num_fila],$parametros['Nuevocoste_ninos'.$num_fila],$parametros['Nuevotipo_cupo_precios'.$num_fila],$parametros['Nuevodias_release_precios'.$num_fila],$parametros['Nuevoexpandido'.$num_fila]);
							if($insertaPrecios == 'OK'){
								$Ntransacciones_precios++;
							}else{
								$error_precios = $insertaPrecios;
								//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
								$recuperafecha_desde_precios = $parametros['Nuevofecha_desde_precios'.$num_fila]; 
								$recuperafecha_hasta_precios = $parametros['Nuevofecha_hasta_precios'.$num_fila]; 
								$recuperaprecios_dias_semana = $parametros['Nuevoprecios_dias_semana'.$num_fila]; 
								$recuperatipo_unidad = $parametros['Nuevotipo_unidad'.$num_fila]; 
								$recuperaunidad_desde = $parametros['Nuevounidad_desde'.$num_fila]; 
								$recuperaunidad_hasta = $parametros['Nuevounidad_hasta'.$num_fila]; 
								$recuperapaquete = $parametros['Nuevopaquete'.$num_fila]; 
								$recuperahabitacion = $parametros['Nuevohabitacion'.$num_fila]; 
								$recuperacaracteristica = $parametros['Nuevocaracteristica'.$num_fila]; 
								$recuperauso = $parametros['Nuevouso'.$num_fila]; 
								$recuperacoste = $parametros['Nuevocoste'.$num_fila]; 
								$recuperacoste_ninos = $parametros['Nuevocoste_ninos'.$num_fila]; 

								$recuperatipo_cupo_precios = $parametros['Nuevotipo_cupo_precios'.$num_fila]; 
								$recuperadias_release_precios = $parametros['Nuevodias_release_precios'.$num_fila]; 
								$recuperaexpandido = $parametros['Nuevoexpandido'.$num_fila]; 
								//$recuperacalculo = $parametros['Nuevocalculo'.$num_fila]; 
								//$recuperaporcentaje_neto = $parametros['Nuevoporcentaje_neto'.$num_fila]; 
								//$recuperaneto = $parametros['Nuevoneto'.$num_fila]; 
								//$recuperaporcentaje_com = $parametros['Nuevoporcentaje_com'.$num_fila]; 
								//$recuperapvp = $parametros['Nuevopvp'.$num_fila]; 
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

				}elseif($parametros['actuar_2nivel'] == 'R'){

					$recalcula_precios = "CALL `PROVEEDORES_RECALCULA_PVP_SERVICIO_CUADROS`('".$sServicios[0]['id_proveedor']."', '".$sServicios[0]['codigo']."')";
					$resultadorecalcula_precios =$conexion->query($recalcula_precios);
							
					if ($resultadorecalcula_precios == FALSE){
						$error_precios = 'No se han podido recalcular las nuevas condiciones del producto. '.$conexion->error;
					}else{
						$Ntransacciones_precios = 'OK';
					}
					//Mostramos mensajes y posibles errores
					$mensaje1_precios = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_precios."</b></font></div>";
					if($error_precios != ''){
						$mensaje2_precios = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_precios."</b></font></div>";
					}

				}


				//Llamada a la clase especifica de la pantalla
				$sPrecios = $oServicios->Cargar_precios_programas($sServicios[0]['id'],$filadesde_precios,$parametros['buscar_fecha']);	
				$sComboSelectPrecios = $oServicios->Cargar_combo_selector_precios_programas($sServicios[0]['id'],$parametros['buscar_fecha']);
				$sPrecios_nuevos = $oServicios->Cargar_lineas_nuevas_precios_programas();	
				//Llamada a la clase general de combos
				$comboTipo_unidad = $oSino->Cargar_combo_Tipo_Unidad();
				$comboCalculo = $oSino->Cargar_combo_Calculo();
				$comboHabitaciones = $oSino->Cargar_combo_Habitaciones();
				$comboHabitaciones_car = $oSino->Cargar_combo_Habitaciones_car();
				$comboSino_No = $oSino->Cargar_combo_SiNo_No();

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
			//----VISUALIZAR PARTE DE LOS SERVICIOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» SERVICIOS');
			$smarty->assign('formulario', '» SERVICIOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectServicios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('servicios', $sServicios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboProveedores', $comboProveedores);
			$smarty->assign('comboTipos_servicio', $comboTipos_servicio);
			$smarty->assign('comboCiudades', $comboCiudades);
			$smarty->assign('comboTipos_cupo', $comboTipos_cupo);
			$smarty->assign('comboSituacion_servicio', $comboSituacion_servicio);
			$smarty->assign('comboTipo_pago', $comboTipo_pago);
			$smarty->assign('comboForma_Pago', $comboForma_Pago);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboCorresponsales', $comboCorresponsales);
			$smarty->assign('comboImprimir_Bono', $comboImprimir_Bono);
			$smarty->assign('comboTipo_Pvp', $comboTipo_Pvp);
			$smarty->assign('comboServicios', $comboServicios);

			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_id_prove', $parametros['buscar_id_prove']);
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_tipo', $parametros['buscar_tipo']);
			$smarty->assign('buscar_ciudad', $parametros['buscar_ciudad']);


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS PRECIOS---------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_precios', $filadesde_precios);
		

			//Cargar combo selector
			$smarty->assign('combo_precios', $sComboSelectPrecios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('precios', $sPrecios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTipo_unidad', $comboTipo_unidad);
			$smarty->assign('comboCalculo', $comboCalculo);

			if($sServicios[0]['tipo'] == 'PRO'){
				$smarty->assign('comboHabitaciones', $comboHabitaciones);
				$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car);
			}

			$smarty->assign('comboSino_No', $comboSino_No);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('preciosnuevos', $sPrecios_nuevos);	
			
			$smarty->assign('recuperafecha_desde_precios', $recuperafecha_desde_precios);	
			$smarty->assign('recuperafecha_hasta_precios', $recuperafecha_hasta_precios);	
			$smarty->assign('recuperaprecios_dias_semana', $recuperaprecios_dias_semana);
			$smarty->assign('recuperatipo_unidad', $recuperatipo_unidad);	
			$smarty->assign('recuperaunidad_desde', $recuperaunidad_desde);
			$smarty->assign('recuperaunidad_hasta', $recuperaunidad_hasta);
			if($sServicios[0]['tipo'] == 'PRO'){
				$smarty->assign('recuperapaquete', $recuperapaquete);
				$smarty->assign('recuperahabitacion', $recuperahabitacion);
				$smarty->assign('recuperacaracteristica', $recuperacaracteristica);
				$smarty->assign('recuperauso', $recuperauso);
			}
			$smarty->assign('recuperacoste', $recuperacoste);
			$smarty->assign('recuperacoste_ninos', $recuperacoste_ninos);
			$smarty->assign('recuperatipo_cupo_precios', $recuperatipo_cupo_precios);
			$smarty->assign('recuperadias_release_precios', $recuperadias_release_precios);
			$smarty->assign('recuperaexpandido', $recuperaexpandido);

			//$smarty->assign('recuperacalculo', $recuperacalculo);
			//$smarty->assign('recuperaporcentaje_neto', $recuperaporcentaje_neto);
			//$smarty->assign('recuperaneto', $recuperaneto);
			//$smarty->assign('recuperaporcentaje_com', $recuperaporcentaje_com);
			//$smarty->assign('recuperapvp', $recuperapvp);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_precios', $mensaje1_precios);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_precios', $mensaje2_precios);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);

			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla

			$smarty->display('plantillas/Servicios.html');


		}elseif($parametros['ir_a'] == 'C'){

			header("Location: Servicios_cupos.php?id=".$parametros['id_proveedor0']."&servicio=".$parametros['codigo0']."&fecha=".$parametros['salir_confecha_fecha']);

		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS SERVICIOS

		$filadesde = 1;
		if(count($_GET) != 0 and $parametrosg['id_prove'] != '' and $parametrosg['servicio'] != ''){
			//$parametros['buscar_id'] = $parametrosg['id'];
			$parametros['buscar_id_prove'] = $parametrosg['id_prove'];
			$parametros['buscar_codigo'] = $parametrosg['servicio'];

			$parametros['buscar_id'] = "";
			$parametros['buscar_tipo'] = "";
			$parametros['buscar_ciudad'] = "";			
			
		}else{
			$parametros['buscar_id'] = "";
			$parametros['buscar_id_prove'] = " ";
			$parametros['buscar_codigo'] = "";
			$parametros['buscar_tipo'] = "";
			$parametros['buscar_ciudad'] = "";
		}

			//Llamada a la clase especifica de la pantalla
			$oServicios = new clsServicios($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);
			$sServicios = $oServicios->Cargar($recuperaid_proveedor,$recuperacodigo,$recuperatipo,$recuperanombre,$recuperaciudad,$recuperatipo_cupo,$recuperadias_semana,$recuperahora_desde,$recuperahora_hasta,$recuperasituacion,$recuperapago_tipo,$recuperapago_plazo,$recuperapago_forma,$recuperadivisa,$recuperagratuidades_cantidad,$recuperagratuidades_cada,$recuperagratuidades_maximo,$recuperacorresponsal,$recuperaen_reserva,$recuperaimprimir_bono,$recuperatipo_pvp,$recuperadescripcion);
			$sComboSelectServicios = $oServicios->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboProveedores = $oSino->Cargar_combo_Proveedores();
			$comboTipos_servicio = $oSino->Cargar_combo_Tipos_servicio();
			$comboCiudades = $oSino->Cargar_combo_Ciudades();
			$comboTipos_cupo = $oSino->Cargar_combo_Tipos_cupo();
			$comboSituacion_servicio = $oSino->Cargar_combo_Situacion_servicio();
			$comboTipo_pago = $oSino->Cargar_combo_Tipo_Pago();
			$comboForma_Pago = $oSino->Cargar_combo_Forma_Pago();
			$comboDivisas = $oSino->Cargar_combo_Divisas();
			$comboCorresponsales = $oSino->Cargar_combo_Corresponsales();
			$comboImprimir_Bono = $oSino->Cargar_combo_Imprimir_Bono();
			$comboTipo_Pvp = $oSino->Cargar_combo_Tipo_Pvp();
			$comboServicios = $oSino->Cargar_combo_Servicios_UnProveedor($parametros['buscar_id'], $parametros['buscar_id_prove'], $parametros['buscar_codigo'], $parametros['buscar_tipo'], $parametros['buscar_ciudad']);

			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//--------------------------------------------------------
			//----VISUALIZAR PARTE DE LOS SERVICIOS-------
			//--------------------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» SERVICIOS');
			$smarty->assign('formulario', '» SERVICIOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectServicios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('servicios', $sServicios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboProveedores', $comboProveedores);
			$smarty->assign('comboTipos_servicio', $comboTipos_servicio);
			$smarty->assign('comboCiudades', $comboCiudades);
			$smarty->assign('comboTipos_cupo', $comboTipos_cupo);
			$smarty->assign('comboSituacion_servicio', $comboSituacion_servicio);
			$smarty->assign('comboTipo_pago', $comboTipo_pago);
			$smarty->assign('comboForma_Pago', $comboForma_Pago);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboCorresponsales', $comboCorresponsales);
			$smarty->assign('comboImprimir_Bono', $comboImprimir_Bono);
			$smarty->assign('comboTipo_Pvp', $comboTipo_Pvp);
			$smarty->assign('comboServicios', $comboServicios);

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_id', '');
			$smarty->assign('buscar_id_prove', '');
			$smarty->assign('buscar_codigo', '');
			$smarty->assign('buscar_tipo', '');
			$smarty->assign('buscar_ciudad', '');



		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE PRECIOS

		$filadesde_precios = 1;
		$parametros['buscar_fecha'] = "";

			//Llamada a la clase especifica de la pantalla
			$sPrecios = $oServicios->Cargar_precios($sServicios[0]['id'],$filadesde_precios,$parametros['buscar_fecha']);	
			$sComboSelectPrecios = $oServicios->Cargar_combo_selector_precios($sServicios[0]['id'],$parametros['buscar_fecha']);
			$sPrecios_nuevos = $oServicios->Cargar_lineas_nuevas_precios();	
			//Llamada a la clase general de combos
			$comboTipo_unidad = $oSino->Cargar_combo_Tipo_Unidad();
			$comboCalculo = $oSino->Cargar_combo_Calculo();
			$comboSino_No = $oSino->Cargar_combo_SiNo_No();


			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_precios', $filadesde_precios);
		

			//Cargar combo selector
			$smarty->assign('combo_precios', $sComboSelectPrecios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('precios', $sPrecios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTipo_unidad', $comboTipo_unidad);
			$smarty->assign('comboCalculo', $comboCalculo);
			if($sServicios[0]['tipo'] == 'PRO'){
				$smarty->assign('comboHabitaciones', $comboHabitaciones);
				$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car);
			}
			$smarty->assign('comboSino_No', $comboSino_No);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('preciosnuevos', $sPrecios_nuevos);	
			
			$smarty->assign('recuperafecha_desde_precios', $recuperafecha_desde_precios);	
			$smarty->assign('recuperafecha_hasta_precios', $recuperafecha_hasta_precios);
			$smarty->assign('recuperaprecios_dias_semana', $recuperaprecios_dias_semana);	
			$smarty->assign('recuperatipo_unidad', $recuperatipo_unidad);	
			$smarty->assign('recuperaunidad_desde', $recuperaunidad_desde);
			$smarty->assign('recuperaunidad_hasta', $recuperaunidad_hasta);
			if($sServicios[0]['tipo'] == 'PRO'){
				$smarty->assign('recuperapaquete', $recuperapaquete);
				$smarty->assign('recuperahabitacion', $recuperahabitacion);
				$smarty->assign('recuperacaracteristica', $recuperacaracteristica);
				$smarty->assign('recuperauso', $recuperauso);
			}
			$smarty->assign('recuperacoste', $recuperacoste);
			$smarty->assign('recuperacoste_ninos', $recuperacoste_ninos);
			$smarty->assign('recuperatipo_cupo_precios', $recuperatipo_cupo_precios);
			$smarty->assign('recuperadias_release_precios', $recuperadias_release_precios);
			$smarty->assign('recuperaexpandido', $recuperaexpandido);

			//$smarty->assign('recuperacalculo', $recuperacalculo);
			//$smarty->assign('recuperaporcentaje_neto', $recuperaporcentaje_neto);
			//$smarty->assign('recuperaneto', $recuperaneto);
			//$smarty->assign('recuperaporcentaje_com', $recuperaporcentaje_com);
			//$smarty->assign('recuperapvp', $recuperapvp);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_precios', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_precios', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_fecha', '');


		//Visualizar plantilla
		$smarty->display('plantillas/Servicios.html');
	}

	$conexion->close();

?>

