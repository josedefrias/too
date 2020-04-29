<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/grupos.cls.php';


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
			$recuperaclave_oficina = '';
			$recuperapersona_contacto = '';
			$recuperaestado = '';
			$recuperatelefono = '';
			$recuperaemail = '';
			$recuperanombre = '';
			$recuperatipo_viaje = '';
			$recuperatipo_grupo = '';
			$recuperaadultos_minimo = '';
			$recuperaadultos_maximo = '';
			$recuperaninos_minimo = '';
			$recuperaninos_maximo = '';
			$recuperaninos_edades = '';
			$recuperabebes_minimo = '';
			$recuperabebes_maximo = '';
			$recuperafecha_salida = '';
			$recuperafecha_regreso = '';
			$recuperanoches_fechas = '';
			$recuperaanno = '';
			$recuperames = '';
			$recuperaperiodo = '';
			$recuperanoches_periodo = '';
			$recuperaorigen = '';
			$recuperadestino = '';
			$recuperacategoria = '';
			$recuperasituacion = '';
			$recuperaregimen = '';
			$recuperaotros_aspectos = '';
			$recuperatraslado_entrada = '';
			$recuperatraslado_salida = '';
			$recuperaseguro_opcional = ''; 
			$recuperaexcursiones = '';
			$recuperaentradas = '';
			$recuperaobservaciones = '';
			$recuperaplazas_pago = '';
			$recuperagratuidades = '';
			$recuperaresponsable_gestion = '';
			$recuperafecha_ultimo_envio = '';
			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			$insertaGrupos = '';

			//VARIABLES PARA LOS VUELOS

			$recuperatipo_vuelo = '';
			$recuperaorden_vuelo = '';
			$recuperacia_vuelo = '';
			$recuperavuelo_vuelo = '';
			$recuperaorigen_vuelo = '';
			$recuperadestino_vuelo = '';
			$recuperahora_salida_vuelo= '';
			$recuperahora_llegada_vuelo = '';
			$mensaje1_vuelos = '';
			$mensaje2_vuelos = '';
			$error_vuelos = '';

			//VARIABLES PARA LOS PRESUPUESTOS

			$recuperaorden_presupuesto = '';
			$recuperaalojamiento_presupuesto = '';
			$recuperadoble_presupuesto = '';
			$recuperasingle_presupuesto = '';
			$recuperatriple_presupuesto = '';
			$recuperamultiple_presupuesto = ''; 
			$recuperaninos_presupuesto = '';
			$recuperabebes_presupuesto = '';
			$recuperabebes_maximo_presupuesto = '';
			$recuperatasas_presupuesto = '';
			$mensaje1_presupuestos = '';
			$mensaje2_presupuestos = '';
			$error_presupuestos = '';




	if(count($_POST) != 0){

		if($parametros['ir_a'] != 'S'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LOS GRUPOS----------
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonGrupos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);
				$botonselec = $botonGrupos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mGrupos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);

							$modificaGrupos = $mGrupos->Modificar($parametros['id'.$num_fila],
																	$parametros['persona_contacto'.$num_fila],
																	$parametros['estado'.$num_fila],				
																	$parametros['telefono'.$num_fila],
																	$parametros['email'.$num_fila],
																	$parametros['nombre'.$num_fila],
																	$parametros['tipo_viaje'.$num_fila],
																	$parametros['tipo_grupo'.$num_fila],
																	$parametros['adultos_minimo'.$num_fila],
																	$parametros['adultos_maximo'.$num_fila],
																	$parametros['ninos_minimo'.$num_fila],
																	$parametros['ninos_maximo'.$num_fila],
																	$parametros['ninos_edades'.$num_fila],
																	$parametros['bebes_minimo'.$num_fila],
																	$parametros['bebes_maximo'.$num_fila],
																	$parametros['fecha_salida'.$num_fila],
																	$parametros['fecha_regreso'.$num_fila],
																	$parametros['noches_fechas'.$num_fila],
																	$parametros['anno'.$num_fila],
																	$parametros['mes'.$num_fila],
																	$parametros['periodo'.$num_fila],
																	$parametros['noches_periodo'.$num_fila],
																	$parametros['origen'.$num_fila],
																	$parametros['destino'.$num_fila],
																	$parametros['categoria'.$num_fila],
																	$parametros['situacion'.$num_fila],
																	$parametros['regimen'.$num_fila],
																	$parametros['otros_aspectos'.$num_fila],
																	$parametros['traslado_entrada'.$num_fila],
																	$parametros['traslado_salida'.$num_fila],
																	$parametros['seguro_opcional'.$num_fila], 
																	$parametros['excursiones'.$num_fila],
																	$parametros['entradas'.$num_fila],
																	$parametros['observaciones'.$num_fila],
																	$parametros['plazas_pago'.$num_fila],
																	$parametros['gratuidades'.$num_fila],
																	$parametros['responsable_gestion'.$num_fila]);


							if($modificaGrupos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaGrupos;
							}
						}else{

							$mGrupos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);
							$borraGrupos = $mGrupos->Borrar($parametros['id'.$num_fila]);
							if($borraGrupos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraGrupos;
								
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

			/*if($parametros['grabar_registro'] == 'S'){

				//AÑADIR REGISTROS
				$Ntransacciones = 0;				

				$iGrupos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_ciudad'], $parametros['buscar_categoria'],$parametros['buscar_situacion']);
				$insertaGrupos = $iGrupos->Insertar($parametros['nombre0'],$parametros['tipo0'],$parametros['ciudad0'],$parametros['categoria0'],$parametros['direccion0'],$parametros['codigo_postal0'],$parametros['provincia0'],$parametros['pais0'],$parametros['cif0'],$parametros['telefono0'],$parametros['fax0'],$parametros['email0'],$parametros['responsable0'],$parametros['cargo_responsable0'],$parametros['nombre_comercial0'],$parametros['observaciones0'],$parametros['localidad0'],$parametros['visible0'],$parametros['descripcion0'],$parametros['situacion0'],$parametros['ubicacion0'],$parametros['url0'],$parametros['descripcion_completa0'],$parametros['como_llegar0'],$parametros['descripcion_habitaciones0'],$parametros['descripcion_actividades0'],$parametros['descripcion_restaurantes0'],$parametros['descripcion_belleza0'],$parametros['latitud0'],$parametros['longitud0']);

				if($insertaGrupos == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_nombre'] = $parametros['nombre0'];
				}else{
					$error = $insertaGrupos;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					$recuperaid = $parametros['id0'];
					$recuperanombre = $parametros['nombre0'];
					$recuperatipo = $parametros['tipo0'];
					$recuperaciudad = $parametros['ciudad0'];
					$recuperacategoria = $parametros['categoria0'];
					$recuperadireccion = $parametros['direccion0'];
					$recuperacodigo_postal= $parametros['codigo_postal0'];
					$recuperaprovincia= $parametros['provincia0'];
					$recuperapais = $parametros['pais0'];
					$recuperacif = $parametros['cif0'];
					$recuperatelefono = $parametros['telefono0'];
					$recuperafax= $parametros['fax0'];
					$recuperaemail= $parametros['email0'];
					$recuperaresponsable = $parametros['responsable0'];
					$recuperacargo_responsable= $parametros['cargo_responsable0'];
					$recuperanombre_comercial = $parametros['nombre_comercial0'];
					$recuperaobservaciones = $parametros['observaciones0'];
					$recuperalocalidad = $parametros['localidad0'];
					$recuperavisible = $parametros['visible0'];
					$recuperadescripcion = $parametros['descripcion0'];
					$recuperasituacion = $parametros['situacion0'];
					$recuperaubicacion = $parametros['ubicacion0'];
					$recuperaurl = $parametros['url0'];
					$recuperadescripcion_completa = $parametros['descripcion_completa0'];
					$recuperacomo_llegar = $parametros['como_llegar0'];
					$recuperadescripcion_habitaciones = $parametros['descripcion_habitaciones0'];
					$recuperadescripcion_actividades = $parametros['descripcion_actividades0'];
					$recuperdescripcion_restaurantes = $parametros['descripcion_restaurantes0'];
					$recuperadescripcion_belleza = $parametros['descripcion_belleza0'];
					$recuperalatitud = $parametros['latitud0'];
					$recuperalongitud = $parametros['longitud0'];
				}


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}*/
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			//}


			//Llamada a la clase especifica de la pantalla
			$oGrupos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);
			$sGrupos = $oGrupos->Cargar($recuperaid,$recuperaclave_oficina,$recuperapersona_contacto,$recuperaestado,$recuperatelefono,$recuperaemail,$recuperanombre,$recuperatipo_viaje,$recuperatipo_grupo,$recuperaadultos_minimo,$recuperaadultos_maximo,$recuperaninos_minimo,$recuperaninos_maximo,$recuperaninos_edades,$recuperabebes_minimo,$recuperabebes_maximo,$recuperafecha_salida,$recuperafecha_regreso,$recuperanoches_fechas,$recuperaanno,$recuperames,$recuperaperiodo,$recuperanoches_periodo,$recuperaorigen,$recuperadestino,$recuperacategoria,$recuperasituacion,$recuperaregimen,$recuperaotros_aspectos,$recuperatraslado_entrada,$recuperatraslado_salida,$recuperaseguro_opcional, $recuperaexcursiones,$recuperaentradas,$recuperaobservaciones,$recuperaplazas_pago,$recuperagratuidades,$recuperaresponsable_gestion,$recuperafecha_ultimo_envio);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectGrupos = $oGrupos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboGrupos = $oSino->Cargar_combo_Grupos();
			$comboTipo_viaje_grupos = $oSino->Cargar_Combo_Tipo_Viaje_Grupos();
			$comboTipo_grupos = $oSino->Cargar_Combo_Tipo_Grupo();
			$comboPeriodos_grupos = $oSino->Cargar_Combo_Periodos_Gupos();
			$comboTraslados_grupos = $oSino->Cargar_Combo_Traslados_Gupos();
			$comboAnnos = $oSino->Cargar_Combo_Annos();
			$comboMeses = $oSino->Cargar_Combo_meses();
			$comboOrigen = $oSino->Cargar_Comboorigen_grupos();
			$comboDestino = $oSino->Cargar_Combodestino_grupos();
			$comboEstado = $oSino->Cargar_Comboestado_grupos();
			$comboRegimen = $oSino->Cargar_combo_Regimen();







	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS VUELOS ------------------
	//--------------------------------------------------------

				$filadesde_vuelos = $parametros['filadesde_vuelos'];

				//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
				if($parametros['botonSelector_2nivel'] != 0){
					$botonVuelos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);

					$botonselec_vuelos = $botonVuelos->Botones_selector_vuelos($filadesde_vuelos, $parametros['botonSelector_2nivel'], $sGrupos[0]['id']);
					$filadesde_vuelos = $botonselec_vuelos;
				}

				//Llamada a la clase especifica de la pantalla
				$sVuelos = $oGrupos->Cargar_vuelos($sGrupos[0]['id'], $filadesde_vuelos);
				//lineas visualizadas
				$vueltas = count($sVuelos);

				if($parametros['actuar_2nivel'] == 'S'){

					//MODIFICAR Y BORRAR REGISTROS
					/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_VUELOS' AND USUARIO = '".$usuario."'");
					$Nfilas	 = $num_filas->fetch_assoc();*/
					$Ntransacciones_vuelos = 0;

					for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {

						if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
							if($parametros['selec_2nivel'.$num_fila] == 'S'){
								
								$mVuelos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);

								$modificaVuelos = $mVuelos->Modificar_vuelos($parametros['id_vuelo'.$num_fila], $parametros['tipo_vuelo'.$num_fila], $parametros['orden_vuelo'.$num_fila],$parametros['cia_vuelo'.$num_fila], $parametros['vuelo_vuelo'.$num_fila], $parametros['origen_vuelo'.$num_fila],$parametros['destino_vuelo'.$num_fila],$parametros['hora_salida_vuelo'.$num_fila],$parametros['hora_llegada_vuelo'.$num_fila]);

								if($modificaVuelos == 'OK'){
									$Ntransacciones_vuelos++;
								}else{
									$error_vuelos = $modificaVuelos;
								}

							}else{

								$mVuelos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);
								$borraVuelos = $mVuelos->Borrar_vuelos($parametros['id_vuelo'.$num_fila]);
								if($borraVuelos == 'OK'){
									$Ntransacciones_vuelos++;
								}else{
									$error_vuelos = $borraVuelos;
								}
							}
						}
					}

					//AÑADIR REGISTROS
					$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_VUELOS' AND USUARIO = '".$usuario."'");
					$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
					

					for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
						
						if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){

							$iVuelos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);

							$insertaVuelos = $iVuelos->Insertar_vuelos($sGrupos[0]['id'], $parametros['Nuevotipo_vuelo'.$num_fila], $parametros['Nuevoorden_vuelo'.$num_fila],$parametros['Nuevocia_vuelo'.$num_fila],$parametros['Nuevovuelo_vuelo'.$num_fila],$parametros['Nuevoorigen_vuelo'.$num_fila],$parametros['Nuevodestino_vuelo'.$num_fila],$parametros['Nuevohora_salida_vuelo'.$num_fila],$parametros['Nuevohora_llegada_vuelo'.$num_fila]);
							if($insertaVuelos == 'OK'){
								$Ntransacciones_vuelos++;
							}else{
								$error_vuelos = $insertaVuelos;
								//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
								$recuperatipo_vuelo = $parametros['Nuevotipo_vuelo'.$num_fila]; 
								$recuperaorden_vuelo = $parametros['Nuevoorden_vuelo'.$num_fila]; 
								$recuperacia_vuelo = $parametros['Nuevocia_vuelo'.$num_fila]; 
								$recuperavuelo_vuelo = $parametros['Nuevovuelo_vuelo'.$num_fila]; 
								$recuperaorigen_vuelo = $parametros['Nuevoorigen_vuelo'.$num_fila]; 
								$recuperadestino_vuelo = $parametros['Nuevodestino_vuelo'.$num_fila]; 
								$recuperahora_salida_vuelo = $parametros['Nuevohora_salida_vuelo'.$num_fila]; 
								$recuperahora_llegada_vuelo = $parametros['Nuevohora_llegada_vuelo'.$num_fila]; 
							}
						}
					}

					//Mostramos mensajes y posibles errores
					$mensaje1_vuelos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_vuelos."</b></font></div>";
					if($error_vuelos != ''){
						$mensaje2_vuelos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_vuelos."</b></font></div>";
					}
					//echo ($Ntransacciones.' transacciones realizadas correctamente');
					//$num_filas->close();

				}


				//Llamada a la clase especifica de la pantalla
				$sVuelos = $oGrupos->Cargar_vuelos($sGrupos[0]['id'], $parametros['filadesde_vuelos']);	
				$sComboSelectVuelos = $oGrupos->Cargar_combo_selector_vuelos($sGrupos[0]['id']);
				$sVuelos_nuevos = $oGrupos->Cargar_lineas_nuevas_vuelos();	
				//Llamada a la clase general de combos
				$comboTipos_trayecto_operando = $oSino->Cargar_combo_Tipos_trayecto_operando();



	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LAS PRESUPUESTOS --------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_presupuestos = $parametros['filadesde_presupuestos'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_3nivel'] != 0){
				$botonPresupuestos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);
				$botonselec_presupuestos = $botonPresupuestos->Botones_selector_vuelos($filadesde_presupuestos, $parametros['botonSelector_2nivel'], $sGrupos[0]['id']);
				$filadesde_presupuestos = $botonselec_presupuestos;
			}



			//Llamada a la clase especifica de la pantalla
			$sPresupuestos = $oGrupos->Cargar_presupuestos($sGrupos[0]['id'], $filadesde_presupuestos);	
			//lineas visualizadas
			$vueltas = count($sPresupuestos);


			if($parametros['actuar_3nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_PRESUPUESTOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_presupuestos = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_3nivel'.$num_fila] == 'S' || $parametros['borra_3nivel'.$num_fila] == 'S'){
						if($parametros['selec_3nivel'.$num_fila] == 'S'){
							
							$mPresupuestos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);

							$modificaPresupuestos = $mPresupuestos->Modificar_presupuestos($parametros['id_presupuesto'.$num_fila], $parametros['orden_presupuesto'.$num_fila], $parametros['alojamiento_presupuesto'.$num_fila], $parametros['doble_presupuesto'.$num_fila],$parametros['single_presupuesto'.$num_fila], $parametros['triple_presupuesto'.$num_fila], $parametros['multiple_presupuesto'.$num_fila], $parametros['ninos_presupuesto'.$num_fila], $parametros['bebes_presupuesto'.$num_fila], $parametros['bebes_maximo_presupuesto'.$num_fila], $parametros['tasas_presupuesto'.$num_fila]);

							if($modificaPresupuestos == 'OK'){
								$Ntransacciones_presupuestos++;
							}else{
								$error_presupuestos = $modificaPresupuestos;
							}

						}else{

							$mPresupuestos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);
							$borraPresupuestos = $mPresupuestos->Borrar_presupuestos($parametros['id_presupuesto'.$num_fila]);
							if($borraPresupuestos == 'OK'){
								$Ntransacciones_presupuestos++;
							}else{
								$error_presupuestos = $borraPresupuestos;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_PRESUPUESTOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_3nivel'.$num_fila] == 'S'){

						$iPresupuestos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);

						$insertaPresupuestos = $iPresupuestos->Insertar_presupuestos($sGrupos[0]['id'], $parametros['Nuevoorden_presupuesto'.$num_fila], $parametros['Nuevoalojamiento_presupuesto'.$num_fila], $parametros['Nuevodoble_presupuesto'.$num_fila],$parametros['Nuevosingle_presupuesto'.$num_fila],$parametros['Nuevotriple_presupuesto'.$num_fila],$parametros['Nuevomultiple_presupuesto'.$num_fila],$parametros['Nuevoninos_presupuesto'.$num_fila],$parametros['Nuevobebes_presupuesto'.$num_fila],$parametros['Nuevobebes_maximo_presupuesto'.$num_fila],$parametros['Nuevotasas_presupuesto'.$num_fila]);

						if($insertaPresupuestos == 'OK'){
							$Ntransacciones_presupuestos++;
						}else{
							$error_presupuestos = $insertaPresupuestos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperaorden_presupuesto = $parametros['Nuevoorden_presupuesto'.$num_fila]; 
							$recuperaalojamiento_presupuesto = $parametros['Nuevoalojamiento_presupuesto'.$num_fila]; 
							$recuperadoble_presupuesto = $parametros['Nuevodoble_presupuesto'.$num_fila]; 
							$recuperatriple_presupuesto = $parametros['Nuevotriple_presupuesto'.$num_fila]; 
							$recuperamultiple_presupuesto = $parametros['Nuevomultiple_presupuesto'.$num_fila]; 
							$recuperaninos_presupuesto = $parametros['Nuevoninos_presupuesto '.$num_fila]; 
							$recuperabebes_presupuesto = $parametros['Nuevobebes_presupuesto'.$num_fila]; 
							$recuperabebes_maximo_presupuesto = $parametros['Nuevobebes_maximo_presupuesto'.$num_fila]; 
							$recuperatasas_presupuesto = $parametros['Nuevotasas_presupuesto'.$num_fila]; 
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_presupuestos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_presupuestos."</b></font></div>";
				if($error_presupuestos != ''){
					$mensaje2_presupuestos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_presupuestos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}

			//Llamada a la clase especifica de la pantalla
			$sPresupuestos = $oGrupos->Cargar_presupuestos($sGrupos[0]['id'], $parametros['filadesde_presupuestos']);	
			$sComboSelectPresupuestos = $oGrupos->Cargar_combo_selector_presupuestos($sGrupos[0]['id']);
			$sPresupuestos_nuevos = $oGrupos->Cargar_lineas_nuevas_presupuestos();	
			//Llamada a la clase general de combos
			$comboAlojamientos = $oSino->Cargar_combo_Alojamientos();




	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS GRUPOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#347135');
			$smarty->assign('grupo', '» OPERATIVA');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» GRUPOS ESPECIALES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectGrupos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('grupos', $sGrupos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboGrupos', $comboGrupos);
			$smarty->assign('comboTipo_viaje_grupos', $comboTipo_viaje_grupos);
			$smarty->assign('comboTipo_grupos', $comboTipo_grupos);
			$smarty->assign('Cargar_Combo_Periodos_Gupos', $comboPeriodos_grupos);
			$smarty->assign('Cargar_Combo_Traslados_Gupos', $comboTraslados_grupos);
			$smarty->assign('comboAnnos', $comboAnnos);
			$smarty->assign('comboMeses', $comboMeses);
			$smarty->assign('comboOrigen', $comboOrigen);
			$smarty->assign('comboDestino', $comboDestino);
			$smarty->assign('comboEstado', $comboEstado);
			$smarty->assign('comboRegimen', $comboRegimen);


			//$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_grupo', $parametros['buscar_grupo']);
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_clave_oficina', $parametros['buscar_clave_oficina']);
			$smarty->assign('buscar_fecha_salida', $parametros['buscar_fecha_salida']);




			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS VUELOS---------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_vuelos', $filadesde_vuelos);
		

			//Cargar combo selector
			$smarty->assign('combo_vuelos', $sComboSelectVuelos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('vuelos', $sVuelos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTipos_trayecto_operando', $comboTipos_trayecto_operando);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('vuelosnuevos', $sVuelos_nuevos);	
			
			$smarty->assign('recuperatipo_vuelo', $recuperatipo_vuelo);	
			$smarty->assign('recuperaorden_vuelo', $recuperaorden_vuelo);	
			$smarty->assign('recuperacia_vuelo', $recuperacia_vuelo);	
			$smarty->assign('recuperavuelo_vuelo', $recuperavuelo_vuelo);
			$smarty->assign('recuperaorigen_vuelo', $recuperaorigen_vuelo);
			$smarty->assign('recuperadestino_vuelo', $recuperadestino_vuelo);
			$smarty->assign('recuperahora_salida_vuelo', $recuperahora_salida_vuelo);
			$smarty->assign('recuperahora_llegada_vuelo', $recuperahora_llegada_vuelo);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_vuelos', $mensaje1_vuelos);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_vuelos', $mensaje2_vuelos);



			//---------------------------------------------
			//----VISUALIZAR PARTE DE LAS PRESUPUESTOS ------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_presupuestos', $filadesde_presupuestos);
		

			//Cargar combo selector
			$smarty->assign('combo_presupuestos', $sComboSelectPresupuestos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('presupuestos', $sPresupuestos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('presupuestosnuevos', $sPresupuestos_nuevos);	
			
			$smarty->assign('recuperaorden_presupuesto', $recuperaorden_presupuesto);	
			$smarty->assign('recuperaalojamiento_presupuesto', $recuperaalojamiento_presupuesto);	
			$smarty->assign('recuperadoble_presupuesto', $recuperadoble_presupuesto);
			$smarty->assign('recuperasingle_presupuesto', $recuperasingle_presupuesto);	
			$smarty->assign('recuperatriple_presupuesto', $recuperatriple_presupuesto);	
			$smarty->assign('recuperamultiple_presupuesto', $recuperamultiple_presupuesto);	
			$smarty->assign('recuperaninos_presupuesto', $recuperaninos_presupuesto);	
			$smarty->assign('recuperabebes_presupuesto', $recuperabebes_presupuesto);	
			$smarty->assign('recuperabebes_maximo_presupuesto', $recuperabebes_maximo_presupuesto);	
			$smarty->assign('recuperatasas_presupuesto', $recuperatasas_presupuesto);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_presupuestos', $mensaje1_presupuestos);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_presupuestos', $mensaje2_presupuestos);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			//$smarty->assign('buscar_temporada', $parametros['buscar_temporada']);




			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			if($parametros['manda_posicion']){
				$smarty->assign('posicion', $parametros['manda_posicion']);
			}else{
				$smarty->assign('posicion', 'boton_buscar');
			}

			//Visualizar plantilla
			$smarty->display('plantillas/Grupos.html');


		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS GRUPOS

		$filadesde = 1;

		$parametros['buscar_grupo'] = "";
		$parametros['buscar_id'] = "";
		$parametros['buscar_nombre'] = "";
		$parametros['buscar_clave_oficina'] = "";
		$parametros['buscar_fecha_salida'] = "";


			//Llamada a la clase especifica de la pantalla
			$oGrupos = new clsGrupos($conexion, $filadesde, $usuario, $parametros['buscar_grupo'], $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_clave_oficina'], $parametros['buscar_fecha_salida']);
			$sGrupos = $oGrupos->Cargar($recuperaid,$recuperaclave_oficina,$recuperapersona_contacto,$recuperaestado,$recuperatelefono,$recuperaemail,$recuperanombre,$recuperatipo_viaje,$recuperatipo_grupo,$recuperaadultos_minimo,$recuperaadultos_maximo,$recuperaninos_minimo,$recuperaninos_maximo,$recuperaninos_edades,$recuperabebes_minimo,$recuperabebes_maximo,$recuperafecha_salida,$recuperafecha_regreso,$recuperanoches_fechas,$recuperaanno,$recuperames,$recuperaperiodo,$recuperanoches_periodo,$recuperaorigen,$recuperadestino,$recuperacategoria,$recuperasituacion,$recuperaregimen,$recuperaotros_aspectos,$recuperatraslado_entrada,$recuperatraslado_salida,$recuperaseguro_opcional, $recuperaexcursiones,$recuperaentradas,$recuperaobservaciones,$recuperaplazas_pago,$recuperagratuidades,$recuperaresponsable_gestion,$recuperafecha_ultimo_envio);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectGrupos = $oGrupos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboGrupos = $oSino->Cargar_combo_Grupos();
			$comboTipo_viaje_grupos = $oSino->Cargar_Combo_Tipo_Viaje_Grupos();
			$comboTipo_grupos = $oSino->Cargar_Combo_Tipo_Grupo();
			$comboPeriodos_grupos = $oSino->Cargar_Combo_Periodos_Gupos();
			$comboTraslados_grupos = $oSino->Cargar_Combo_Traslados_Gupos();
			$comboAnnos = $oSino->Cargar_Combo_Annos();
			$comboMeses = $oSino->Cargar_Combo_meses();
			$comboOrigen = $oSino->Cargar_Comboorigen_grupos();
			$comboDestino = $oSino->Cargar_Combodestino_grupos();
			$comboEstado = $oSino->Cargar_Comboestado_grupos();
			$comboRegimen = $oSino->Cargar_combo_Regimen();

	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS GRUPOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#347135');
			$smarty->assign('grupo', '» OPERATIVA');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» GRUPOS ESPECIALES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectGrupos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('grupos', $sGrupos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboGrupos', $comboGrupos);
			$smarty->assign('comboTipo_viaje_grupos', $comboTipo_viaje_grupos);
			$smarty->assign('comboTipo_grupos', $comboTipo_grupos);
			$smarty->assign('Cargar_Combo_Periodos_Gupos', $comboPeriodos_grupos);
			$smarty->assign('Cargar_Combo_Traslados_Gupos', $comboTraslados_grupos);
			$smarty->assign('comboAnnos', $comboAnnos);
			$smarty->assign('comboMeses', $comboMeses);
			$smarty->assign('comboOrigen', $comboOrigen);
			$smarty->assign('comboDestino', $comboDestino);
			$smarty->assign('comboEstado', $comboEstado);
			$smarty->assign('comboRegimen', $comboRegimen);


			//$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_grupo', '');
			$smarty->assign('buscar_id', '');
			$smarty->assign('buscar_nombre', '');
			$smarty->assign('buscar_clave_oficina', '');
			$smarty->assign('buscar_fecha_salida', '');



			//-----------------------------------------------------------
			//-----------------------------------------------------------
			//INICIALIZAMOS PARAMETROS Y PLANTILLA DE VUELOS

			$filadesde_vuelos = 1;

			//Llamada a la clase especifica de la pantalla
			$sVuelos = $oGrupos->Cargar_vuelos($sGrupos[0]['id'], $filadesde_vuelos);	
			$sComboSelectVuelos = $oGrupos->Cargar_combo_selector_vuelos($sGrupos[0]['id']);
			$sVuelos_nuevos = $oGrupos->Cargar_lineas_nuevas_vuelos();	
			//Llamada a la clase general de combos
			$comboTipos_trayecto_operando = $oSino->Cargar_combo_Tipos_trayecto_operando();


			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_vuelos', $filadesde_vuelos);
		

			//Cargar combo selector
			$smarty->assign('combo_vuelos', $sComboSelectVuelos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('vuelos', $sVuelos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTipos_trayecto_operando', $comboTipos_trayecto_operando);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('vuelosnuevos', $sVuelos_nuevos);	
			
			$smarty->assign('recuperatipo_vuelo', $recuperatipo_vuelo);	
			$smarty->assign('recuperaorden_vuelo', $recuperaorden_vuelo);	
			$smarty->assign('recuperacia_vuelo', $recuperacia_vuelo);	
			$smarty->assign('recuperavuelo_vuelo', $recuperavuelo_vuelo);
			$smarty->assign('recuperaorigen_vuelo', $recuperaorigen_vuelo);
			$smarty->assign('recuperadestino_vuelo', $recuperadestino_vuelo);
			$smarty->assign('recuperahora_salida_vuelo', $recuperahora_salida_vuelo);
			$smarty->assign('recuperahora_llegada_vuelo', $recuperahora_llegada_vuelo);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_vuelos', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_vuelos', '');


		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LAS PRESUPUESTOS

		$filadesde_presupuestos = 1;
		//$parametros['buscar_temporada'] = "";

		//Llamada a la clase especifica de la pantalla
		$sPresupuestos = $oGrupos->Cargar_presupuestos($sGrupos[0]['id'], $filadesde_presupuestos);	
		$sComboSelectPresupuestos = $oGrupos->Cargar_combo_selector_presupuestos($sGrupos[0]['id']);
		$sPresupuestos_nuevos = $oGrupos->Cargar_lineas_nuevas_presupuestos();	
		//Llamada a la clase general de combos
		$comboAlojamientos = $oSino->Cargar_combo_Alojamientos();




		//Numero de fila para situar el selector de registros
		$smarty->assign('filades_presupuestos', $filadesde_presupuestos);
		

		//Cargar combo selector
		$smarty->assign('combo_presupuestos', $sComboSelectPresupuestos);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('presupuestos', $sPresupuestos);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboAlojamientos', $comboAlojamientos);

		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('presupuestosnuevos', $sPresupuestos_nuevos);	
			
		$smarty->assign('recuperaorden_presupuesto', $recuperaorden_presupuesto);	
		$smarty->assign('recuperaalojamiento_presupuesto', $recuperaalojamiento_presupuesto);	
		$smarty->assign('recuperadoble_presupuesto', $recuperadoble_presupuesto);
		$smarty->assign('recuperasingle_presupuesto', $recuperasingle_presupuesto);	
		$smarty->assign('recuperatriple_presupuesto', $recuperatriple_presupuesto);	
		$smarty->assign('recuperamultiple_presupuesto', $recuperamultiple_presupuesto);	
		$smarty->assign('recuperaninos_presupuesto', $recuperaninos_presupuesto);	
		$smarty->assign('recuperabebes_presupuesto', $recuperabebes_presupuesto);	
		$smarty->assign('recuperabebes_maximo_presupuesto', $recuperabebes_maximo_presupuesto);	
		$smarty->assign('recuperatasas_presupuesto', $recuperatasas_presupuesto);	

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1_presupuestos', '');

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2_presupuestos', '');

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		//$smarty->assign('buscar_temporada', $parametros['buscar_temporada']);


		//Visualizar plantilla

		$smarty->assign('posicion', 'boton_buscar');


		$smarty->display('plantillas/Grupos.html');
	}

	$conexion->close();


?>

