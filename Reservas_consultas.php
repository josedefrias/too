<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/reservas_consultas.cls.php';


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

	$parametros = $_POST;
	$mensaje1 = '';
	$mensaje2 = '';
	$error = '';
	$insertaReservas_consultas = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$estado = '';
	
	if(count($_POST) != 0){

		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonReservas_consultas = new clsReservas_consultas($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_reserva_desde'], $parametros['buscar_reserva_hasta'], $parametros['buscar_localizador'], $parametros['buscar_agencia'], $parametros['orden'], $parametros['buscar_origen'], $parametros['buscar_grupo']);
				$botonselec = $botonReservas_consultas->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oReservas_consultas = new clsReservas_consultas($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_reserva_desde'], $parametros['buscar_reserva_hasta'], $parametros['buscar_localizador'], $parametros['buscar_agencia'], $parametros['orden'], $parametros['buscar_origen'], $parametros['buscar_grupo']);
			$sReservas_consultas = $oReservas_consultas->Cargar();
			//lineas visualizadas
			$vueltas = count($sReservas_consultas);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_CONSULTAS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				/*for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mReservas_consultas = new clsReservas_consultas($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_reserva_desde'], $parametros['buscar_reserva_hasta'], $parametros['buscar_localizador'], $parametros['buscar_agencia'], $parametros['orden'], $parametros['buscar_origen'], $parametros['buscar_grupo']);
							$modificaReservas_consultas = $mReservas_consultas->Modificar($parametros['localizador'.$num_fila], $parametros['estado'.$num_fila]);
							if($modificaReservas_consultas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaReservas_consultas;
								
							}
						}
					}
				}*/

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}

			if($parametros['actuar'] == 'A'){
				
				//ENVIAR MAIL RESERVAS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_CONSULTAS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				/*for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
				
					if($parametros['selec'.$num_fila] == 'S'){
						//echo('hola-');
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mReservas_consultas = new clsReservas_consultas($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_reserva_desde'], $parametros['buscar_reserva_hasta'], $parametros['buscar_localizador'], $parametros['buscar_agencia'], $parametros['orden'], $parametros['buscar_origen'], $parametros['buscar_grupo']);
							$Envio_mail_reserva = $mReservas_consultas->Enviar_mail_reserva($parametros['localizador'.$num_fila]);
							if($Envio_mail_reserva == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaReservas_consultas;
								
							}
						}
					}
				}*/

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}			
			
			/*if($parametros['actuar'] == 'E'){

				//ENVIAR MAIL GRUPO
			
				$Ntransacciones = 0;

				$mEnvioMail = new clsReservas_consultas($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_reserva_desde'], $parametros['buscar_reserva_hasta'], $parametros['buscar_localizador'], $parametros['buscar_agencia'], $parametros['orden'], $parametros['buscar_origen'], $parametros['buscar_grupo']);
				$Envio_mail_grupo = $mEnvioMail->Enviar_mail_grupo();

				$Ntransacciones = $Envio_mail_grupo;


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Agencias Informadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}*/			
			
			//Llamada a la clase especifica de la pantalla
			$oReservas_consultas = new clsReservas_consultas($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_reserva_desde'], $parametros['buscar_reserva_hasta'], $parametros['buscar_localizador'], $parametros['buscar_agencia'], $parametros['orden'], $parametros['buscar_origen'], $parametros['buscar_grupo']);
			$sReservas_consultas = $oReservas_consultas->Cargar();
			$sReservas_consultas_totales = $oReservas_consultas->Cargar_totales();
			$sReservas_consultas_canceladas = $oReservas_consultas->Cargar_Canceladas();
			$sComboSelectReservas_consultas = $oReservas_consultas->Cargar_combo_selector();
			
			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			//$comboSino = $oSino->Cargar_combo_SiNo();
			//$comboReservas_consultas = $oSino->Cargar_Combo_Estado_Precobro();
			$comboOrden_reservas = $oSino->Cargar_Combo_Orden_reservas();
			$comboCiudades = $oSino->Cargar_Combo_Ciudades_Origen();
			$comboBuscarGrupos = $oSino->Cargar_combo_Grupos_gestion();

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#00AF50');
			$smarty->assign('grupo', '» RESERVAS');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» CONSULTA DE RESERVAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectReservas_consultas);

			//Cargar combos
			$smarty->assign('comboOrden_reservas', $comboOrden_reservas);
			$smarty->assign('comboCiudades', $comboCiudades);
			$smarty->assign('comboBuscarGrupos', $comboBuscarGrupos);


			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('reservas_consultas', $sReservas_consultas);
			$smarty->assign('reservas_consultas_totales', $sReservas_consultas_totales);
			$smarty->assign('reservas_consultas_canceladas', $sReservas_consultas_canceladas);

			//(Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('recuperaestado', $recuperaestado);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_salida_desde', $parametros['buscar_salida_desde']);
			$smarty->assign('buscar_salida_hasta', $parametros['buscar_salida_hasta']);
			$smarty->assign('buscar_reserva_desde', $parametros['buscar_reserva_desde']);
			$smarty->assign('buscar_reserva_hasta', $parametros['buscar_reserva_hasta']);
			$smarty->assign('buscar_localizador', $parametros['buscar_localizador']);
			$smarty->assign('buscar_agencia', $parametros['buscar_agencia']);
			$smarty->assign('orden', $parametros['orden']);
			$smarty->assign('buscar_origen', $parametros['buscar_origen']);
			$smarty->assign('buscar_grupo', $parametros['buscar_grupo']);
			
			//Visualizar plantilla
			$smarty->display('plantillas/reservas_consultas.html');


		}
		else{
			session_destroy();
			exit;
			/*$smarty = new Smarty;
			$smarty->assign('mensaje', ' ');
			$smarty->assign('pie', 'plantillas/Pie.html');
			$smarty->display('plantillas/Index.html');*/
		}


	}else{

		$filadesde = 1;
		$parametros['buscar_salida_desde'] = "";
		$parametros['buscar_salida_hasta'] = "";
		$parametros['buscar_reserva_desde'] = "";
		$parametros['buscar_reserva_hasta'] = "";
		$parametros['buscar_localizador'] = "";
		$parametros['buscar_agencia'] = "";
		$parametros['orden'] = "";
		$parametros['buscar_origen'] = "";
		$parametros['buscar_grupo'] = "";

			//Llamada a la clase especifica de la pantalla
			$oReservas_consultas = new clsReservas_consultas($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_reserva_desde'], $parametros['buscar_reserva_hasta'], $parametros['buscar_localizador'], $parametros['buscar_agencia'], $parametros['orden'], $parametros['buscar_origen'], $parametros['buscar_grupo']);
			$sReservas_consultas = $oReservas_consultas->Cargar();
			$sReservas_consultas_totales = $oReservas_consultas->Cargar_totales();
			$sReservas_consultas_canceladas = $oReservas_consultas->Cargar_Canceladas();
			$sComboSelectReservas_consultas = $oReservas_consultas->Cargar_combo_selector();
				
			
			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			//$comboSino = $oSino->Cargar_combo_SiNo();
			//$comboReservas_consultas = $oSino->Cargar_Combo_Estado_Precobro();
			$comboOrden_reservas = $oSino->Cargar_Combo_Orden_reservas();
			$comboCiudades = $oSino->Cargar_Combo_Ciudades_Origen();
			$comboBuscarGrupos = $oSino->Cargar_combo_Grupos_gestion();

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#00AF50');
			$smarty->assign('grupo', '» RESERVAS');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» CONSULTA DE RESERVAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectReservas_consultas);

			//Cargar combos
			$smarty->assign('comboOrden_reservas', $comboOrden_reservas);
			$smarty->assign('comboCiudades', $comboCiudades);
			$smarty->assign('comboBuscarGrupos', $comboBuscarGrupos);


			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('reservas_consultas', $sReservas_consultas);
			$smarty->assign('reservas_consultas_totales', $sReservas_consultas_totales);
			$smarty->assign('reservas_consultas_canceladas', $sReservas_consultas_canceladas);

			//(Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('recuperaestado', $recuperaestado);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_salida_desde', '');
			$smarty->assign('buscar_salida_hasta', '');
			$smarty->assign('buscar_reserva_desde', '');
			$smarty->assign('buscar_reserva_hasta', '');
			$smarty->assign('buscar_localizador', '');
			$smarty->assign('buscar_agencia', '');
			$smarty->assign('orden', '');
			$smarty->assign('buscar_origen', '');
			$smarty->assign('buscar_grupo', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Reservas_consultas.html');
	}

	$conexion->close();


?>

