<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/transportes_vacios_solicitudes.cls.php';

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
	$insertaTransportes_vacios_solicitudes = '';
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



			$oTransportes_vacios_solicitudes = new clsTransportes_vacios_solicitudes($conexion, $filadesde, $usuario, 
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
													$parametros['buscar_id'],
													$parametros['buscar_localizador'],
													$parametros['buscar_estado_solicitud']);



			if($parametros['botonSelector'] != 0){

				$botonselec = $oTransportes_vacios_solicitudes->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			$sTransportes_vacios_solicitudes = $oTransportes_vacios_solicitudes->Cargar();
			//lineas visualizadas
			$vueltas = count($sTransportes_vacios_solicitudes);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_VACIOS_SOLICITUDES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							

							$modificaTransportes_vacios_solicitudes = $oTransportes_vacios_solicitudes->Modificar($parametros['sol_id_solicitud'.$num_fila], 
																         $parametros['sol_estado_solicitud'.$num_fila]);
							if($modificaTransportes_vacios_solicitudes == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaTransportes_vacios_solicitudes;
								
							}
						}else{

							$borraTransportes_vacios_solicitudes = $oTransportes_vacios_solicitudes->Borrar($parametros['sol_id_solicitud'.$num_fila]);
							if($borraTransportes_vacios_solicitudes == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraTransportes_vacios_solicitudes;
								
							}
						}
					}
				}

				$mensaje1 = "<div class='mensaje-informacion' style='width:1400px; margin:0px 0px 0px 20px;'><font><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div class='mensaje-error' style='width:1400px; margin:0px 0px 0px 20px;''><font><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}

			}

			//Llamada a la clase especifica de la pantalla
			$sTransportes_vacios_solicitudes = $oTransportes_vacios_solicitudes->Cargar();
			//$sTransportes_vacios_solicitudesnuevos = $oTransportes_vacios_solicitudes->Cargar_lineas_nuevas();
			$sComboSelectTransportes_vacios_solicitudes = $oTransportes_vacios_solicitudes->Cargar_combo_selector();


			//Llamada a la clase general de combos

			$oCombo = new clsGeneral($conexion);
			$comboSino = $oCombo->Cargar_combo_SiNo_Si();
			$comboTransportes = $oCombo->Cargar_combo_Transportes_Id();
			$comboDias_semana = $oCombo->Cargar_Combo_Dias_Semana();
			$comboTipo = $oCombo->Cargar_Combo_tipos_vacios();
			$comboTipo_linea = $oCombo->Cargar_Combo_tipos_vacios_linea();
			$comboEstado = $oCombo->Cargar_Combo_estado_trayecto();
			$comboEstado_linea = $oCombo->Cargar_Combo_estado_trayecto_linea();

			$comboEstado_Solicitud = $oCombo->Cargar_Combo_estado_solicitud();
			$comboEstado_Solicitud_linea = $oCombo->Cargar_Combo_estado_solicitud_linea();


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
			$smarty->assign('formulario', '» SOLICITUDES DE VACIOS');
			//$smarty->assign('formulario', '» TRAYECTOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTransportes_vacios_solicitudes);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('transportes_vacios_solicitudes', $sTransportes_vacios_solicitudes);


			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTransportes', $comboTransportes);
			$smarty->assign('comboDias_semana', $comboDias_semana);
			$smarty->assign('comboTipo', $comboTipo);
			$smarty->assign('comboTipo_linea', $comboTipo_linea);
			$smarty->assign('comboEstado', $comboEstado);
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboEstado_linea', $comboEstado_linea);

			$smarty->assign('comboEstado_Solicitud', $comboEstado_Solicitud);
			$smarty->assign('comboEstado_Solicitud_linea', $comboEstado_Solicitud_linea);

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
			$smarty->assign('buscar_localizador', $parametros['buscar_localizador']);
			$smarty->assign('buscar_estado_solicitud', $parametros['buscar_estado_solicitud']);

			//Visualizar plantilla
			$smarty->display('plantillas/Transportes_vacios_solicitudes.html');

		}elseif($parametros['ir_a'] == 'V'){

			header("Location: Transportes_vacios.php?id_vacio=".$parametros['buscar_id']);

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
		$parametros['buscar_localizador'] = "";
		$parametros['buscar_estado_solicitud'] = "";



			//Llamada a la clase especifica de la pantalla
			$oTransportes_vacios_solicitudes = new clsTransportes_vacios_solicitudes($conexion, $filadesde, $usuario, 
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
													$parametros['buscar_id'],
													$parametros['buscar_localizador'],
													$parametros['buscar_estado_solicitud']);
			$sTransportes_vacios_solicitudes = $oTransportes_vacios_solicitudes->Cargar();
			$sTransportes_vacios_solicitudes = $oTransportes_vacios_solicitudes->Cargar();
			//$sTransportes_vacios_solicitudesnuevos = $oTransportes_vacios_solicitudes->Cargar_lineas_nuevas();
			$sComboSelectTransportes_vacios_solicitudes = $oTransportes_vacios_solicitudes->Cargar_combo_selector();

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

			$comboEstado_Solicitud = $oCombo->Cargar_Combo_estado_solicitud();
			$comboEstado_Solicitud_linea = $oCombo->Cargar_Combo_estado_solicitud_linea();

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
			$smarty->assign('formulario', '» SOLICITUDES DE VACIOS');
			//$smarty->assign('formulario', '» TRAYECTOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTransportes_vacios_solicitudes);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('transportes_vacios_solicitudes', $sTransportes_vacios_solicitudes);

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

			$smarty->assign('comboEstado_Solicitud', $comboEstado_Solicitud);
			$smarty->assign('comboEstado_Solicitud_linea', $comboEstado_Solicitud_linea);

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
			$smarty->assign('buscar_localizador', $parametros['buscar_localizador']);
			$smarty->assign('buscar_estado_solicitud', $parametros['buscar_estado_solicitud']);

		//Visualizar plantilla
		$smarty->display('plantillas/Transportes_vacios_solicitudes.html');
	}

	$conexion->close();


?>

