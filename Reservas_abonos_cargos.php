<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/reservas_abonos_cargos.cls.php';


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
	$insertaReservas_abonos_cargos = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperalocalizador = '';
	$recuperatipo = '';
	$recuperaimporte = '';
	$recuperadetalle = '';
	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonReservas_abonos_cargos = new clsReservas_abonos_cargos($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_emision_desde'], $parametros['buscar_emision_hasta'], $parametros['buscar_tipo']);
				$botonselec = $botonReservas_abonos_cargos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oReservas_abonos_cargos = new clsReservas_abonos_cargos($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_emision_desde'], $parametros['buscar_emision_hasta'], $parametros['buscar_tipo']);
			$sReservas_abonos_cargos = $oReservas_abonos_cargos->Cargar();
			//lineas visualizadas
			$vueltas = count($sReservas_abonos_cargos);
			
			if($parametros['actuar'] == 'S'){
				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_ABONOS_CARGOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mReservas_abonos_cargos = new clsReservas_abonos_cargos($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_emision_desde'], $parametros['buscar_emision_hasta'], $parametros['buscar_tipo']);
							$modificaReservas_abonos_cargos = $mReservas_abonos_cargos->Modificar($parametros['localizador'.$num_fila], $parametros['numero'.$num_fila],$parametros['tipo'.$num_fila],$parametros['importe'.$num_fila],$parametros['detalle'.$num_fila]);
							if($modificaReservas_abonos_cargos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaReservas_abonos_cargos;
								
							}
						}else{

							$mReservas_abonos_cargos = new clsReservas_abonos_cargos($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_emision_desde'], $parametros['buscar_emision_hasta'], $parametros['buscar_tipo']);
							$borraReservas_abonos_cargos = $mReservas_abonos_cargos->Borrar($parametros['localizador'.$num_fila], $parametros['numero'.$num_fila]);
							if($borraReservas_abonos_cargos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraReservas_abonos_cargos;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_ABONOS_CARGOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iReservas_abonos_cargos = new clsReservas_abonos_cargos($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_emision_desde'], $parametros['buscar_emision_hasta'], $parametros['buscar_tipo']);
						$insertaReservas_abonos_cargos = $iReservas_abonos_cargos->Insertar($parametros['Nuevolocalizador'.$num_fila], $parametros['Nuevotipo'.$num_fila],$parametros['Nuevoimporte'.$num_fila],$parametros['Nuevodetalle'.$num_fila]);
						if($insertaReservas_abonos_cargos == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaReservas_abonos_cargos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperalocalizador = $parametros['Nuevolocalizador'.$num_fila]; 
							$recuperatipo = $parametros['Nuevotipo'.$num_fila];
							$recuperaimporte = $parametros['Nuevoimporte'.$num_fila];
							$recuperadetalle = $parametros['Nuevocdetalle'.$num_fila];
						}
					}
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
			$sReservas_abonos_cargos = $oReservas_abonos_cargos->Cargar();
			$sReservas_abonos_cargosnuevos = $oReservas_abonos_cargos->Cargar_lineas_nuevas();
			$sComboSelectReservas_abonos_cargos = $oReservas_abonos_cargos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboBuscar_Tipo = $oCombo->Cargar_Combo_Buscar_Tipo_Abono_Cargo();
			$comboTipo = $oCombo->Cargar_Combo_Tipo_Abono_Cargo();


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
			$smarty->assign('color_opcion', '#4C4C4C');
			$smarty->assign('grupo', '» ADMINISTRACION');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» CARGOS Y ABONOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectReservas_abonos_cargos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('reservas_abonos_cargos', $sReservas_abonos_cargos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboBuscar_Tipo', $comboBuscar_Tipo);
			$smarty->assign('comboTipo', $comboTipo);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('reservas_abonos_cargos_nuevos', $sReservas_abonos_cargosnuevos);			
			$smarty->assign('recuperalocalizador', $recuperalocalizador);	
			$smarty->assign('recuperatipo', $recuperatipo);	
			$smarty->assign('recuperaimporte', $recuperaimporte);	
			$smarty->assign('recuperadetalle', $recuperadetalle);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_localizador', $parametros['buscar_localizador']);
			$smarty->assign('buscar_salida_desde', $parametros['buscar_salida_desde']);
			$smarty->assign('buscar_salida_hasta', $parametros['buscar_salida_hasta']);
			$smarty->assign('buscar_emision_desde', $parametros['buscar_emision_desde']);
			$smarty->assign('buscar_emision_hasta', $parametros['buscar_emision_hasta']);
			$smarty->assign('buscar_tipo', $parametros['buscar_tipo']);
			
			//Visualizar plantilla
			$smarty->display('plantillas/Reservas_abonos_cargos.html');

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
		$parametros['buscar_localizador'] = "";
		$parametros['buscar_salida_desde'] = "";
		$parametros['buscar_salida_hasta'] = "";
		$parametros['buscar_emision_desde'] = "";
		$parametros['buscar_emision_hasta'] = "";
		$parametros['buscar_tipo'] = "";

		//Llamada a la clase especifica de la pantalla
		$oReservas_abonos_cargos = new clsReservas_abonos_cargos($conexion, $filadesde, $usuario, $parametros['buscar_localizador'], $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_emision_desde'], $parametros['buscar_emision_hasta'], $parametros['buscar_tipo']);
			//Llamada a la clase especifica de la pantalla
			$sReservas_abonos_cargos = $oReservas_abonos_cargos->Cargar();
			$sReservas_abonos_cargosnuevos = $oReservas_abonos_cargos->Cargar_lineas_nuevas();
			$sComboSelectReservas_abonos_cargos = $oReservas_abonos_cargos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboBuscar_Tipo = $oCombo->Cargar_Combo_Buscar_Tipo_Abono_Cargo();
			$comboTipo = $oCombo->Cargar_Combo_Tipo_Abono_Cargo();

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
			$smarty->assign('color_opcion', '#4C4C4C');
			$smarty->assign('grupo', '» ADMINISTRACION');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» CARGOS Y ABONOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectReservas_abonos_cargos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('reservas_abonos_cargos', $sReservas_abonos_cargos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboBuscar_Tipo', $comboBuscar_Tipo);
			$smarty->assign('comboTipo', $comboTipo);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('reservas_abonos_cargos_nuevos', $sReservas_abonos_cargosnuevos);			
			$smarty->assign('recuperalocalizador', $recuperalocalizador);	
			$smarty->assign('recuperatipo', $recuperatipo);	
			$smarty->assign('recuperaimporte', $recuperaimporte);	
			$smarty->assign('recuperadetalle', $recuperadetalle);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_localizador', '');
			$smarty->assign('buscar_salida_desde', '');
			$smarty->assign('buscar_salida_hasta', '');
			$smarty->assign('buscar_emision_desde', '');
			$smarty->assign('buscar_emision_hasta', '');
			$smarty->assign('buscar_tipo', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Reservas_abonos_cargos.html');
	}

	$conexion->close();


?>

