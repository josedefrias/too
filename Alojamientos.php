<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/alojamientos.cls.php';


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
			$recuperatipo = '';
			$recuperaciudad = '';
			$recuperadestino_producto = '';
			$recuperazona_producto = '';
			$recuperacategoria = '';
			$recuperadireccion = '';
			$recuperacodigo_postal = '';
			$recuperaprovincia = '';
			$recuperapais = '';
			$recuperacif = '';
			$recuperatelefono = '';
			$recuperafax = '';
			$recuperaemail = '';
			$recuperaresponsable = '';
			$recuperacargo_responsable = '';
			$recuperanombre_comercial = '';
			$recuperaobservaciones = '';
			$recuperalocalidad = '';
			$recuperavisible = '';
			$recuperadescripcion = '';
			$recuperasituacion = '';
			$recuperaubicacion = '';
			$recuperaurl = '';
			$recuperadescripcion_completa = '';
			$recuperacomo_llegar = '';
			$recuperadescripcion_habitaciones = '';
			$recuperadescripcion_actividades = '';
			$recuperadescripcion_restaurantes = '';
			$recuperadescripcion_belleza = '';
			$recuperalatitud = '';
			$recuperalongitud = '';
			$recuperamovil_comercial = '';
			$recuperareservas_responsable = '';
			$recuperareservas_telefono = '';
			$recuperareservas_mail = '';
			$recuperaorden = '';

			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			$insertaAlojamientos = '';

			//VARIABLES PARA LAS CONDICIONES
			$recuperainterfaces_id_alojamiento = '';
			$recuperainterfaces_codigo_interfaz= '';
			$recuperainterfaces_salidas_desde= '';
			$recuperainterfaces_salidas_hasta= '';
			$recuperainterfaces_orden_aplicacion = ''; 
			$recuperainterfaces_codigo_externo = ''; 
			$mensaje1_interfaces = '';
			$mensaje2_interfaces = '';
			$error_interfaces = '';


	if(count($_POST) != 0){
		//esto es por si venimos de la pantalla de listado de minoristas desde grupos de gestion
		if($parametros['buscar_id'] == null and $parametros['buscar_nombre'] == null and $parametros['buscar_ciudad'] == null and $parametros['buscar_categoria'] == null and $parametros['buscar_situacion'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_id'] = $parametrosg['id'];
			}
		}	


		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'I' and $parametros['ir_a'] != 'H' and $parametros['ir_a'] != 'R' and $parametros['ir_a'] != 'A' and $parametros['ir_a'] != 'S'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LOS ALOJAMIENTOS----------
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonAlojamientos = new clsAlojamientos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_ciudad'], $parametros['buscar_categoria'],$parametros['buscar_situacion']);
				$botonselec = $botonAlojamientos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mAlojamientos = new clsAlojamientos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_ciudad'], $parametros['buscar_categoria'],$parametros['buscar_situacion']);

							$modificaAlojamientos = $mAlojamientos->Modificar($parametros['id'.$num_fila],$parametros['nombre'.$num_fila],$parametros['tipo'.$num_fila],$parametros['ciudad'.$num_fila],$parametros['destino_producto'.$num_fila],$parametros['zona_producto'.$num_fila],$parametros['categoria'.$num_fila],$parametros['direccion'.$num_fila],$parametros['codigo_postal'.$num_fila],$parametros['provincia'.$num_fila],$parametros['pais'.$num_fila],$parametros['cif'.$num_fila],$parametros['telefono'.$num_fila],$parametros['fax'.$num_fila],$parametros['email'.$num_fila],$parametros['responsable'.$num_fila],$parametros['cargo_responsable'.$num_fila],$parametros['nombre_comercial'.$num_fila],$parametros['observaciones'.$num_fila],$parametros['localidad'.$num_fila],$parametros['visible'.$num_fila],$parametros['descripcion'.$num_fila],$parametros['situacion'.$num_fila],$parametros['ubicacion'.$num_fila],$parametros['url'.$num_fila],$parametros['descripcion_completa'.$num_fila],$parametros['como_llegar'.$num_fila],$parametros['descripcion_habitaciones'.$num_fila],$parametros['descripcion_actividades'.$num_fila],$parametros['descripcion_restaurantes'.$num_fila],$parametros['descripcion_belleza'.$num_fila],$parametros['latitud'.$num_fila],$parametros['longitud'.$num_fila],$parametros['movil_comercial'.$num_fila],$parametros['reservas_responsable'.$num_fila],$parametros['reservas_telefono'.$num_fila],$parametros['reservas_mail'.$num_fila],$parametros['orden'.$num_fila]);

							if($modificaAlojamientos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaAlojamientos;
							}
						}else{

							$mAlojamientos = new clsAlojamientos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_ciudad'], $parametros['buscar_categoria'],$parametros['buscar_situacion']);
							$borraAlojamientos = $mAlojamientos->Borrar($parametros['id'.$num_fila]);
							if($borraAlojamientos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraAlojamientos;
								
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

				//AÑADIR REGISTROS
				$Ntransacciones = 0;				

				$iAlojamientos = new clsAlojamientos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_ciudad'], $parametros['buscar_categoria'],$parametros['buscar_situacion']);
				$insertaAlojamientos = $iAlojamientos->Insertar($parametros['nombre0'],$parametros['tipo0'],$parametros['ciudad0'],$parametros['destino_producto0'],$parametros['zona_producto0'],$parametros['categoria0'],$parametros['direccion0'],$parametros['codigo_postal0'],$parametros['provincia0'],$parametros['pais0'],$parametros['cif0'],$parametros['telefono0'],$parametros['fax0'],$parametros['email0'],$parametros['responsable0'],$parametros['cargo_responsable0'],$parametros['nombre_comercial0'],$parametros['observaciones0'],$parametros['localidad0'],$parametros['visible0'],$parametros['descripcion0'],$parametros['situacion0'],$parametros['ubicacion0'],$parametros['url0'],$parametros['descripcion_completa0'],$parametros['como_llegar0'],$parametros['descripcion_habitaciones0'],$parametros['descripcion_actividades0'],$parametros['descripcion_restaurantes0'],$parametros['descripcion_belleza0'],$parametros['latitud0'],$parametros['longitud0'],$parametros['movil_comercial0'],$parametros['reservas_responsable0'],$parametros['reservas_telefono0'],$parametros['reservas_mail0'],$parametros['orden0']);

				if($insertaAlojamientos == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_nombre'] = $parametros['nombre0'];
				}else{
					$error = $insertaAlojamientos;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					$recuperaid = $parametros['id0'];
					$recuperanombre = $parametros['nombre0'];
					$recuperatipo = $parametros['tipo0'];
					$recuperaciudad = $parametros['ciudad0'];
					$recuperadestino_producto = $parametros['destino_producto0'];
					$recuperazona_producto = $parametros['zona_producto0'];
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
					$recuperamovil_comercial = $parametros['movil_comercial0'];
					$recuperareservas_responsable = $parametros['reservas_responsable0'];
					$recuperareservas_telefono = $parametros['reservas_telefono0'];
					$recuperareservas_mail = $parametros['reservas_mail0'];
					$recuperaorden = $parametros['orden0'];
				}


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}

			if($parametros['nuevo_registro'] == 'S'){
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Teclee los datos del nuevo hotel y pulse grabar.</b></font></div>";
			}

			//Llamada a la clase especifica de la pantalla
			$oAlojamientos = new clsAlojamientos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_ciudad'], $parametros['buscar_categoria'],$parametros['buscar_situacion']);
			$sAlojamientos = $oAlojamientos->Cargar($recuperaid,$recuperanombre,$recuperatipo,$recuperaciudad,$recuperadestino_producto,$recuperazona_producto,$recuperacategoria,$recuperadireccion,$recuperacodigo_postal,$recuperaprovincia,$recuperapais,$recuperacif,$recuperatelefono,$recuperafax,$recuperaemail,$recuperaresponsable,$recuperacargo_responsable,$recuperanombre_comercial,$recuperaobservaciones,$recuperalocalidad,$recuperavisible,$recuperadescripcion,$recuperasituacion,$recuperaubicacion,$recuperaurl,$recuperadescripcion_completa,$recuperacomo_llegar,$recuperadescripcion_habitaciones,$recuperadescripcion_actividades,$recuperadescripcion_restaurantes,$recuperadescripcion_belleza,$recuperalatitud,$recuperalongitud, $recuperamovil_comercial, $recuperareservas_responsable, $recuperareservas_telefono, $recuperareservas_mail, $recuperaorden);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectAlojamientos = $oAlojamientos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboAlojamientos = $oSino->Cargar_combo_Alojamientos_like($parametros['buscar_nombre']);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboCiudades = $oSino->Cargar_combo_Ciudades();
			$comboCategorias = $oSino->Cargar_combo_Categorias();
			$comboProvincias = $oSino->Cargar_combo_Provincias();
			$comboPaises = $oSino->Cargar_combo_Paises();
			$comboCargos = $oSino->Cargar_combo_Cargos();
			$comboSituacion = $oSino->Cargar_combo_Situacion();
			$comboTipos_alojamiento = $oSino->Cargar_combo_Tipos_alojamiento();
			$comboDestinos = $oSino->Cargar_combo_Destinos();
			$comboZonas = $oSino->Cargar_combo_Zonas();





	//-------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS INTERFACES--------------
	//-------------------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_interfaces = $parametros['filadesde_interfaces'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonInterfaces = new clsAlojamientos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_ciudad'], $parametros['buscar_categoria'],$parametros['buscar_situacion']);
				$botonselec_interfaces = $botonInterfaces->Botones_selector_interfaces($filadesde_interfaces, $parametros['botonSelector_2nivel'],$sAcuerdos[0]['id']);
				$filadesde_interfaces = $botonselec_interfaces;
			}


			//Llamada a la clase especifica de la pantalla
			$sInterfaces = $oAlojamientos->Cargar_interfaces($sAlojamientos[0]['id'], $filadesde_interfaces);	
			//lineas visualizadas
			$vueltas = count($sInterfaces);


			if($parametros['actuar_2nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_TEMPORADAS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_interfaces = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							
							$mInterfaces = new clsAlojamientos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_ciudad'], $parametros['buscar_categoria'],$parametros['buscar_situacion']);
							$modificaInterfaces = $mInterfaces->Modificar_interfaces($sAlojamientos[0]['id'], 
											$parametros['interfaces_codigo_interfaz'.$num_fila], 
											$parametros['interfaces_salidas_desde'.$num_fila], 
											$parametros['interfaces_salidas_hasta'.$num_fila],
											$parametros['interfaces_orden_aplicacion'.$num_fila], 
											$parametros['interfaces_codigo_externo'.$num_fila],
											$parametros['interfaces_codigo_interfaz_old'.$num_fila],
											$parametros['interfaces_salidas_desde_old'.$num_fila], 
											$parametros['interfaces_salidas_hasta_old'.$num_fila],
											$parametros['interfaces_orden_aplicacion_old'.$num_fila]
											);
							if($modificaInterfaces == 'OK'){
								$Ntransacciones_interfaces++;
							}else{
								$error_interfaces = $modificaInterfaces;
							}

						}else{
							$mInterfaces = new clsAlojamientos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_ciudad'], $parametros['buscar_categoria'],$parametros['buscar_situacion']);
							$borraInterfaces = $mInterfaces->Borrar_interfaces($sAlojamientos[0]['id'],
											$parametros['interfaces_codigo_interfaz'.$num_fila],
											$parametros['interfaces_salidas_desde'.$num_fila],
											$parametros['interfaces_salidas_hasta'.$num_fila]);

							if($borraInterfaces == 'OK'){
								$Ntransacciones_interfaces++;
							}else{
								$error_interfaces = $borrInterfaces;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_INTERFACES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){

						$iInterfaces = new clsAlojamientos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_ciudad'], $parametros['buscar_categoria'],$parametros['buscar_situacion']);
						$insertaInterfaces = $iInterfaces->Insertar_interfaces($sAlojamientos[0]['id'], 
							$parametros['Nuevointerfaces_codigo_interfaz'.$num_fila], 
							$parametros['Nuevointerfaces_salidas_desde'.$num_fila], 
							$parametros['Nuevointerfaces_salidas_hasta'.$num_fila], 
							$parametros['Nuevointerfaces_orden_aplicacion'.$num_fila], 
							$parametros['Nuevointerfaces_codigo_externo'.$num_fila]);
						if($insertaInterfaces == 'OK'){
							$Ntransacciones_interfaces++;
						}else{
							$error_interfaces = $insertaInterfaces;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperainterfaces_codigo_interfaz = $parametros['Nuevointerfaces_codigo_interfaz'.$num_fila]; 
							$recuperainterfaces_salidas_desde = $parametros['Nuevointerfaces_salidas_desde'.$num_fila];
							$recuperainterfaces_salidas_hasta = $parametros['Nuevointerfaces_salidas_hasta'.$num_fila]; 
							$recuperainterfaces_orden_aplicacion = $parametros['Nuevointerfaces_orden_aplicacion'.$num_fila];
							$recuperainterfaces_codigo_externo = $parametros['Nuevointerfaces_codigo_externo'.$num_fila]; 
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_interfaces = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_interfaces."</b></font></div>";
				if($error_interfaces != ''){
					$mensaje2_interfaces = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_interfaces."</b></font></div>";
				}
			}

			//Llamada a la clase especifica de la pantalla
			$sInterfaces = $oAlojamientos->Cargar_interfaces($sAlojamientos[0]['id'], $filadesde_interfaces);	
			$sComboSelectInterfaces = $oAlojamientos->Cargar_combo_selector_interfaces($sAlojamientos[0]['id'], $filadesde_interfaces);
			$sInterfaces_nuevos = $oAlojamientos->Cargar_lineas_nuevas_interfaces();	
			//Llamada a la clase general de combos
			$comboInterfaces = $oSino->Cargar_combo_Interfaces();



	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS ALOJAMIENTOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
			$smarty->assign('formulario', '» ALOJAMIENTOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAlojamientos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos', $sAlojamientos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboCiudades', $comboCiudades);
			$smarty->assign('comboCategorias', $comboCategorias);
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboCargos', $comboCargos);
			$smarty->assign('comboSituacion', $comboSituacion);
			$smarty->assign('comboTipos_alojamiento', $comboTipos_alojamiento);
			$smarty->assign('comboDestinos', $comboDestinos);
			$smarty->assign('comboZonas', $comboZonas);

			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_ciudad', $parametros['buscar_ciudad']);
			$smarty->assign('buscar_categoria', $parametros['buscar_categoria']);
			$smarty->assign('buscar_situacion', $parametros['buscar_situacion']);



			//----------------------------------------------------
			//----VISUALIZAR PARTE DE LOS INTERFACES------
			//----------------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_interfaces', $filadesde_interfaces);
		

			//Cargar combo selector
			$smarty->assign('combo_interfaces', $sComboSelectInterfaces);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('interfaces', $sInterfaces);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboInterfaces', $comboInterfaces);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('interfacesnuevos', $sInterfaces_nuevos);	
			
			$smarty->assign('recuperainterfaces_codigo_interfaz', $recuperainterfaces_codigo_interfaz);
			$smarty->assign('recuperainterfaces_salidas_desde', $recuperainterfaces_salidas_desde);
			$smarty->assign('recuperainterfaces_salidas_hasta', $recuperainterfaces_salidas_hasta);
			$smarty->assign('recuperainterfaces_orden_aplicacion', $recuperainterfaces_orden_aplicacion);
			$smarty->assign('recuperainterfaces_codigo_externo', $recuperainterfaces_codigo_externo);	
			
			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_interfaces', $mensaje1_interfaces);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_interfaces', $mensaje2_interfaces);



			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Alojamientos.html');


		}elseif($parametros['ir_a'] == 'I'){

			header("Location: Alojamientos_imagenes.php?id=".$parametros['id0']);

		}elseif($parametros['ir_a'] == 'H'){

			header("Location: Alojamientos_habitaciones.php?id_alojamiento=".$parametros['id0']);

		}elseif($parametros['ir_a'] == 'R'){

			header("Location: Alojamientos_restaurantes.php?id_alojamiento=".$parametros['id0']);

		}elseif($parametros['ir_a'] == 'A'){

			header("Location: Alojamientos_actividades.php?id_alojamiento=".$parametros['id0']);

		}elseif($parametros['ir_a'] == 'S'){

			header("Location: Alojamientos_servicios.php?id_alojamiento=".$parametros['id0']);

		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS ALOJAMIENTOS

		$filadesde = 1;
		if(count($_GET) != 0 and $parametrosg['id'] != ''){
			$parametros['buscar_id'] = $parametrosg['id'];
		}else{
			$parametros['buscar_id'] = "";
		}
		$parametros['buscar_nombre'] = "";
		$parametros['buscar_ciudad'] = "";
		$parametros['buscar_categoria'] = "";
		$parametros['buscar_situacion'] = "";


			//Llamada a la clase especifica de la pantalla
			$oAlojamientos = new clsAlojamientos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_ciudad'], $parametros['buscar_categoria'],$parametros['buscar_situacion']);
			$sAlojamientos = $oAlojamientos->Cargar($recuperaid,$recuperanombre,$recuperatipo,$recuperaciudad,$recuperadestino_producto,$recuperazona_producto,$recuperacategoria,$recuperadireccion,$recuperacodigo_postal,$recuperaprovincia,$recuperapais,$recuperacif,$recuperatelefono,$recuperafax,$recuperaemail,$recuperaresponsable,$recuperacargo_responsable,$recuperanombre_comercial,$recuperaobservaciones,$recuperalocalidad,$recuperavisible,$recuperadescripcion,$recuperasituacion,$recuperaubicacion,$recuperaurl,$recuperadescripcion_completa,$recuperacomo_llegar,$recuperadescripcion_habitaciones,$recuperadescripcion_actividades,$recuperadescripcion_restaurantes,$recuperadescripcion_belleza,$recuperalatitud,$recuperalongitud, $recuperamovil_comercial, $recuperareservas_responsable, $recuperareservas_telefono, $recuperareservas_mail, $recuperaorden);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectAlojamientos = $oAlojamientos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboAlojamientos = $oSino->Cargar_combo_Alojamientos_like($parametros['buscar_nombre']);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboCiudades = $oSino->Cargar_combo_Ciudades();
			$comboCategorias = $oSino->Cargar_combo_Categorias();
			$comboProvincias = $oSino->Cargar_combo_Provincias();
			$comboPaises = $oSino->Cargar_combo_Paises();
			$comboCargos = $oSino->Cargar_combo_Cargos();
			$comboSituacion = $oSino->Cargar_combo_Situacion();
			$comboTipos_alojamiento = $oSino->Cargar_combo_Tipos_alojamiento();
			$comboDestinos = $oSino->Cargar_combo_Destinos();
			$comboZonas = $oSino->Cargar_combo_Zonas();

	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS ALOJAMIENTOS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
			$smarty->assign('formulario', '» ALOJAMIENTOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAlojamientos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos', $sAlojamientos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboCiudades', $comboCiudades);
			$smarty->assign('comboCategorias', $comboCategorias);
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboCargos', $comboCargos);
			$smarty->assign('comboSituacion', $comboSituacion);
			$smarty->assign('comboTipos_alojamiento', $comboTipos_alojamiento);
			$smarty->assign('comboDestinos', $comboDestinos);
			$smarty->assign('comboZonas', $comboZonas);

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_id', '');
		$smarty->assign('buscar_nombre', '');
		$smarty->assign('buscar_ciudad', '');
		$smarty->assign('buscar_categoria', '');
		$smarty->assign('buscar_situacion', '');




		//------------------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS ITERFACES

		$filadesde_interfaces = 1;


			//Llamada a la clase especifica de la pantalla
			$sInterfaces = $oAlojamientos->Cargar_interfaces($sAlojamientos[0]['id'], $filadesde_interfaces);	
			$sComboSelectInterfaces = $oAlojamientos->Cargar_combo_selector_interfaces($sAlojamientos[0]['id'], $filadesde_interfaces);
			$sInterfaces_nuevos = $oAlojamientos->Cargar_lineas_nuevas_interfaces();	
			//Llamada a la clase general de combos
			$comboInterfaces = $oSino->Cargar_combo_Interfaces();


			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_interfaces', $filadesde_interfaces);
		

			//Cargar combo selector
			$smarty->assign('combo_interfaces', $sComboSelectInterfaces);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('interfaces', $sInterfaces);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboInterfaces', $comboInterfaces);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('interfacesnuevos', $sInterfaces_nuevos);	
			
			$smarty->assign('recuperainterfaces_codigo_interfaz', $recuperainterfaces_codigo_interfaz);
			$smarty->assign('recuperainterfaces_salidas_desde', $recuperainterfaces_salidas_desde);
			$smarty->assign('recuperainterfaces_salidas_hasta', $recuperainterfaces_salidas_hasta);
			$smarty->assign('recuperainterfaces_orden_aplicacion', $recuperainterfaces_orden_aplicacion);
			$smarty->assign('recuperainterfaces_codigo_externo', $recuperainterfaces_codigo_externo);	
			
			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_interfaces', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_interfaces', '');



		//Visualizar plantilla
		$smarty->display('plantillas/Alojamientos.html');
	}

	$conexion->close();


?>

