<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/alojamientos_cupos.cls.php';

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
	$insertaAlojamientos_cupos = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperaid = '';
	$recuperaacuerdo = '';
	$recuperahabitacion= '';
	$recuperacaracteristica= '';
	$recuperafecha= '';
	$recuperacupo= '';
	$recuperarelease= '';
	

	if(count($_POST) != 0){

		//esto es por si venimos de la pantalla de Alojamientos
		if($parametros['buscar_id'] == null and $parametros['buscar_acuerdo'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_id'] = $parametrosg['id'];
				$parametros['buscar_nombre'] = $parametrosg['nombre'];
				$parametros['buscar_acuerdo'] = $parametrosg['acuerdo'];
			}
		}

		
		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'V'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonAlojamientos_cupos = new clsAlojamientos_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo'], $parametros['buscar_habitacion'], $parametros['buscar_caracteristica'], $parametros['buscar_fecha'], $parametros['buscar_release']);
				$botonselec = $botonAlojamientos_cupos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Predeterminamos la habitacion a valor 'DBL' para esta
			if($parametros['buscar_habitacion'] == ""){
				$parametros['buscar_habitacion'] = "DBL";
			}
			//Predeterminamos la habitacion a valor 'DBL' para esta
			if($parametros['actualizar_habitacion'] == ""){
				$parametros['actualizar_habitacion'] = "DBL";
			}

			//Llamada a la clase especifica de la pantalla
			$oAlojamientos_cupos = new clsAlojamientos_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo'], $parametros['buscar_habitacion'], $parametros['buscar_caracteristica'], $parametros['buscar_fecha'], $parametros['buscar_release']);
			$sAlojamientos_cupos = $oAlojamientos_cupos->Cargar();
			//lineas visualizadas
			$vueltas = count($sAlojamientos_cupos);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_CUPOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mAlojamientos_cupos = new clsAlojamientos_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo'], $parametros['buscar_habitacion'], $parametros['buscar_caracteristica'], $parametros['buscar_fecha'], $parametros['buscar_release']);
							$modificaAlojamientos_cupos = $mAlojamientos_cupos->Modificar($parametros['id'.$num_fila], $parametros['acuerdo'.$num_fila], $parametros['habitacion'.$num_fila],$parametros['caracteristica'.$num_fila],$parametros['fecha'.$num_fila], $parametros['cupo'.$num_fila],$parametros['release_cupo'.$num_fila]);
							if($modificaAlojamientos_cupos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaAlojamientos_cupos;
							}
						}else{

							$mAlojamientos_cupos = new clsAlojamientos_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo'], $parametros['buscar_habitacion'], $parametros['buscar_caracteristica'], $parametros['buscar_fecha'], $parametros['buscar_release']);
							$borraAlojamientos_cupos = $mAlojamientos_cupos->Borrar($parametros['id'.$num_fila], $parametros['acuerdo'.$num_fila], $parametros['habitacion'.$num_fila],$parametros['caracteristica'.$num_fila],$parametros['fecha'.$num_fila]);
							if($borraAlojamientos_cupos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraAlojamientos_cupos;
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_CUPOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iAlojamientos_cupos = new clsAlojamientos_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo'], $parametros['buscar_habitacion'], $parametros['buscar_caracteristica'], $parametros['buscar_fecha'], $parametros['buscar_release']);

						$insertaAlojamientos_cupos = $iAlojamientos_cupos->Insertar($parametros['Nuevoid'.$num_fila], $parametros['Nuevoacuerdo'.$num_fila], $parametros['Nuevohabitacion'.$num_fila],$parametros['Nuevocaracteristica'.$num_fila],$parametros['Nuevofecha'.$num_fila], $parametros['Nuevocupo'.$num_fila],$parametros['Nuevorelease'.$num_fila]);

						if($insertaAlojamientos_cupos == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaAlojamientos_cupos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperaid = $parametros['Nuevoid'.$num_fila]; 
							$recuperaacuerdo = $parametros['Nuevoacuerdo'.$num_fila]; 
							$recuperahabitacion= $parametros['Nuevohabitacion'.$num_fila]; 
							$recuperacaracteristica= $parametros['Nuevocaracteristica'.$num_fila]; 
							$recuperafecha= $parametros['Nuevofecha'.$num_fila]; 
							$recuperacupo= $parametros['Nuevocupo'.$num_fila]; 
							$recuperarelease= $parametros['Nuevorelease'.$num_fila]; 
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
				$mAlojamientos_cupos = new clsAlojamientos_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo'], $parametros['buscar_habitacion'], $parametros['buscar_caracteristica'], $parametros['buscar_fecha'], $parametros['buscar_release']);

				$actualizaAlojamientos_cupos = $mAlojamientos_cupos->Actualizar($parametros['buscar_id'], $parametros['buscar_acuerdo'], $parametros['actualizar_habitacion'], @$parametros['actualizar_caracteristica'], $parametros['actualizar_fecha_desde'], $parametros['actualizar_fecha_hasta'], $parametros['actualizar_numero_habitaciones'], $parametros['actualizar_release']);
					if($actualizaAlojamientos_cupos == 'OK'){
						$Ntransacciones++;
					}else{
						$error = $actualizaAlojamientos_cupos;
					}
				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}

			}

			//Llamada a la clase especifica de la pantalla

			$sAlojamientos_cupos = $oAlojamientos_cupos->Cargar();
			$sAlojamientos_cuposnuevos = $oAlojamientos_cupos->Cargar_lineas_nuevas();
			$sComboSelectAlojamientos_cupos = $oAlojamientos_cupos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboAlojamientos = $oCombo->Cargar_combo_Alojamientos_like($parametros['buscar_nombre']);
			$comboHabitaciones = $oCombo->Cargar_combo_Habitaciones();
			$comboHabitaciones_car = $oCombo->Cargar_combo_Habitaciones_car();

			$comboHabitaciones_car_contrato = $oCombo->Cargar_combo_Habitaciones_car_contrato($parametros['buscar_id'], $parametros['buscar_acuerdo']);

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
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
			$smarty->assign('formulario', '» CUPOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAlojamientos_cupos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos_cupos', $sAlojamientos_cupos);

			//Indicamos si hay que visualizar o no la seccion de aACTUALIZAR CUPOS
			$smarty->assign('seccion_actualizar_cupos_display', $parametros['seccion_actualizar_cupos_display']);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboHabitaciones', $comboHabitaciones);
			$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car_contrato);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('alojamientos_cupos_nuevos', $sAlojamientos_cuposnuevos);			
			$smarty->assign('recuperaid', $recuperaid);	
			$smarty->assign('recuperaacuerdo', $recuperaacuerdo);	
			$smarty->assign('recuperahabitacion', $recuperahabitacion);	
			$smarty->assign('recuperacaracteristica', $recuperacaracteristica);	
			$smarty->assign('recuperafecha', $recuperafecha);	
			$smarty->assign('recuperacupo', $recuperacupo);	
			$smarty->assign('recuperarelease', $recuperarelease);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_acuerdo', $parametros['buscar_acuerdo']);
			$smarty->assign('buscar_habitacion', $parametros['buscar_habitacion']);
			$smarty->assign('buscar_caracteristica', $parametros['buscar_caracteristica']);
			$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);
			$smarty->assign('buscar_release', $parametros['buscar_release']);
			$smarty->assign('actualizar_habitacion', $parametros['actualizar_habitacion']);

			//Visualizar plantilla
			$smarty->display('plantillas/Alojamientos_cupos.html');

		}elseif($parametros['ir_a'] == 'V'){

			header("Location: Alojamientos_acuerdos.php?id=".$parametros['buscar_id']."&nombre=".$parametros['buscar_nombre']."&acuerdo=".$parametros['buscar_acuerdo']);

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

		if(count($_GET) != 0 and $parametrosg['id'] != null and $parametrosg['acuerdo'] != null){
			$parametros['buscar_id'] = $parametrosg['id'];
			$parametros['buscar_nombre'] = $parametrosg['nombre'];
			$parametros['buscar_acuerdo'] = $parametrosg['acuerdo'];
			$parametros['buscar_habitacion'] = "DBL";
			$parametros['actualizar_habitacion'] = "DBL";
		}else{
			$parametros['buscar_id'] = "";
			$parametros['buscar_nombre'] = "";
			$parametros['buscar_acuerdo'] = "";
			$parametros['buscar_habitacion'] = "";
			$parametros['actualizar_habitacion'] = "";
		}
			//$parametros['buscar_habitacion'] = "";
			$parametros['buscar_caracteristica'] = "";
			$parametros['buscar_fecha'] = "";
			$parametros['buscar_release'] = "";

			//Llamada a la clase especifica de la pantalla
			$oAlojamientos_cupos = new clsAlojamientos_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre'], $parametros['buscar_acuerdo'], $parametros['buscar_habitacion'], $parametros['buscar_caracteristica'], $parametros['buscar_fecha'], $parametros['buscar_release']);
			$sAlojamientos_cupos = $oAlojamientos_cupos->Cargar();
			$sAlojamientos_cuposnuevos = $oAlojamientos_cupos->Cargar_lineas_nuevas();
			$sComboSelectAlojamientos_cupos = $oAlojamientos_cupos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboAlojamientos = $oCombo->Cargar_combo_Alojamientos_like($parametros['buscar_nombre']);
			$comboHabitaciones = $oCombo->Cargar_combo_Habitaciones();
			$comboHabitaciones_car = $oCombo->Cargar_combo_Habitaciones_car();
			$comboHabitaciones_car_contrato = $oCombo->Cargar_combo_Habitaciones_car_contrato($parametros['buscar_id'], $parametros['buscar_acuerdo']);

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
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
			$smarty->assign('formulario', '» CUPOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAlojamientos_cupos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos_cupos', $sAlojamientos_cupos);

			//Indicamos si hay que visualizar o no la seccion DE ACTUALIZAR CUPOS
			$smarty->assign('seccion_actualizar_cupos_display', 'none');

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboHabitaciones', $comboHabitaciones);
			$smarty->assign('comboHabitaciones_car', $comboHabitaciones_car_contrato);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('alojamientos_cupos_nuevos', $sAlojamientos_cuposnuevos);			
			$smarty->assign('recuperaid', $recuperaid);	
			$smarty->assign('recuperaacuerdo', $recuperaacuerdo);	
			$smarty->assign('recuperahabitacion', $recuperahabitacion);	
			$smarty->assign('recuperacaracteristica', $recuperacaracteristica);	
			$smarty->assign('recuperafecha', $recuperafecha);	
			$smarty->assign('recuperacupo', $recuperacupo);	
			$smarty->assign('recuperarelease', $recuperarelease);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_acuerdo', $parametros['buscar_acuerdo']);
			$smarty->assign('buscar_habitacion', $parametros['buscar_habitacion']);
			$smarty->assign('buscar_caracteristica', '');
			$smarty->assign('buscar_fecha', '');
			$smarty->assign('buscar_release', '');
			$smarty->assign('actualizar_habitacion', $parametros['actualizar_habitacion']);

		//Visualizar plantilla
		$smarty->display('plantillas/Alojamientos_cupos.html');
	}

	$conexion->close();


?>

