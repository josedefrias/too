<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/servicios_cupos.cls.php';

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
	$insertaServicios_cupos = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	/*$recuperaiclave = '';
	$recuperaid = '';
	$recuperaacuerdo = '';
	$recuperahabitacion= '';
	$recuperacaracteristica= '';
	$recuperafecha= '';
	$recuperacupo= '';
	$recuperarelease= '';*/
	

	if(count($_POST) != 0){

		//esto es por si venimos de la pantalla de Alojamientos
		if($parametros['buscar_id'] == null and $parametros['buscar_servicio'] == null){
			if(count($_GET) != 0){
				//$parametros['buscar_id'] = $parametrosg['id'];
				$parametros['buscar_id'] = $parametrosg['id'];
				$parametros['buscar_servicio'] = $parametrosg['servicio'];
			}
		}

		
		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'V'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonServicios_cupos = new clsServicios_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_servicio'], $parametros['buscar_fecha'], $parametros['buscar_release']);
				$botonselec = $botonServicios_cupos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}


			//Llamada a la clase especifica de la pantalla
			$oServicios_cupos = new clsServicios_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_servicio'], $parametros['buscar_fecha'], $parametros['buscar_release']);
			$sServicios_cupos = $oServicios_cupos->Cargar();
			//lineas visualizadas
			$vueltas = count($sServicios_cupos);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_CUPOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mServicios_cupos = new clsServicios_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_servicio'], $parametros['buscar_fecha'], $parametros['buscar_release']);
							$modificaServicios_cupos = $mServicios_cupos->Modificar($parametros['clave'.$num_fila], $parametros['fecha'.$num_fila], $parametros['cupo'.$num_fila],$parametros['release_cupo'.$num_fila]);
							if($modificaServicios_cupos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaServicios_cupos;
							}
						}else{

							$mServicios_cupos = new clsServicios_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_servicio'], $parametros['buscar_fecha'], $parametros['buscar_release']);
							$borraServicios_cupos = $mServicios_cupos->Borrar($parametros['clave'.$num_fila]);
							if($borraServicios_cupos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraServicios_cupos;
							}
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
				$mServicios_cupos = new clsServicios_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_servicio'], $parametros['buscar_fecha'], $parametros['buscar_release']);

				$actualizaServicios_cupos = $mServicios_cupos->Actualizar($parametros['buscar_id'], $parametros['buscar_servicio'], $parametros['actualizar_fecha_desde'], $parametros['actualizar_fecha_hasta'], $parametros['actualizar_cupo'], $parametros['actualizar_dias_semana'], $parametros['actualizar_release']);
					if($actualizaServicios_cupos == 'OK'){
						$Ntransacciones++;
					}else{
						$error = $actualizaServicios_cupos;
					}
				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}

			}

			//Llamada a la clase especifica de la pantalla

			$sServicios_cupos = $oServicios_cupos->Cargar();
			//$sServicios_cuposnuevos = $oServicios_cupos->Cargar_lineas_nuevas();
			$sComboSelectServicios_cupos = $oServicios_cupos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboProveedores = $oCombo->Cargar_combo_Proveedores();
			$comboServicios = $oCombo->Cargar_combo_Servicios_UnsoloProveedor($parametros['buscar_id']);

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» SERVICIOS');
			$smarty->assign('formulario', '» CUPOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectServicios_cupos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('servicios_cupos', $sServicios_cupos);

			//Indicamos si hay que visualizar o no la seccion de aACTUALIZAR CUPOS
			$smarty->assign('seccion_actualizar_cupos_display', $parametros['seccion_actualizar_cupos_display']);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboProveedores', $comboProveedores);
			$smarty->assign('comboServicios', $comboServicios);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_servicio', $parametros['buscar_servicio']);
			$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);
			$smarty->assign('buscar_release', $parametros['buscar_release']);


			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('actualizar_fecha_desde', $parametros['actualizar_fecha_desde']);
			$smarty->assign('actualizar_fecha_hasta', $parametros['actualizar_fecha_hasta']);
			$smarty->assign('actualizar_cupo', $parametros['actualizar_cupo']);
			$smarty->assign('actualizar_dias_semana', $parametros['actualizar_dias_semana']);
			$smarty->assign('actualizar_release', $parametros['actualizar_release']);


			//Visualizar plantilla
			$smarty->display('plantillas/Servicios_cupos.html');

		}elseif($parametros['ir_a'] == 'V'){

			header("Location: Servicios.php?id_prove=".$parametros['buscar_id']."&servicio=".$parametros['buscar_servicio']);

		}else{
			session_destroy();
			exit;

		}

	}else{

		$filadesde = 1;


		if(count($_GET) != 0 and $parametrosg['id'] != null and $parametrosg['servicio'] != null){
			$parametros['buscar_id'] = $parametrosg['id'];
			$parametros['buscar_servicio'] = $parametrosg['servicio'];
			$parametros['buscar_fecha'] = $parametrosg['fecha'];;

		}else{
			$parametros['buscar_id'] = "";
			$parametros['buscar_servicio'] = "";
			$parametros['buscar_fecha'] = "";
		}
			
			$parametros['buscar_release'] = "";

			//Llamada a la clase especifica de la pantalla
			$oServicios_cupos = new clsServicios_cupos($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_servicio'], $parametros['buscar_fecha'], $parametros['buscar_release']);
			$sServicios_cupos = $oServicios_cupos->Cargar();
			//$sServicios_cuposnuevos = $oServicios_cupos->Cargar_lineas_nuevas();
			$sComboSelectServicios_cupos = $oServicios_cupos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboProveedores = $oCombo->Cargar_combo_Proveedores();
			$comboServicios = $oCombo->Cargar_combo_Servicios_UnsoloProveedor($parametros['buscar_id']);


			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» SERVICIOS');
			$smarty->assign('formulario', '» CUPOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectServicios_cupos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('servicios_cupos', $sServicios_cupos);

			//Indicamos si hay que visualizar o no la seccion DE ACTUALIZAR CUPOS
			$smarty->assign('seccion_actualizar_cupos_display', 'none');

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboProveedores', $comboProveedores);
			$smarty->assign('comboServicios', $comboServicios);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_servicio', $parametros['buscar_servicio']);
			$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);
			$smarty->assign('buscar_release', $parametros['buscar_release']);

			$smarty->assign('actualizar_fecha_desde', $parametros['buscar_fecha']);
			$smarty->assign('actualizar_fecha_hasta', '');
			$smarty->assign('actualizar_cupo', '');
			$smarty->assign('actualizar_dias_semana', 'LMXJVSD');
			$smarty->assign('actualizar_release', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Servicios_cupos.html');
	}

	$conexion->close();


?>

