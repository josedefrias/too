<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/reservas_duplicadas.cls.php';


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
	$conexion = conexion_hit();

	
	if(count($_POST) != 0){

		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonReservas_duplicadas = new clsReservas_duplicadas($conexion, $filadesde, $usuario);
				$botonselec = $botonReservas_duplicadas->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oReservas_duplicadas = new clsReservas_duplicadas($conexion, $filadesde, $usuario);
			$sReservas_duplicadas = $oReservas_duplicadas->Cargar();
			//lineas visualizadas
			$vueltas = count($sReservas_duplicadas);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mReservas_duplicadas = new clsReservas_duplicadas($conexion, $filadesde, $usuario);
							$modificaReservas_duplicadas = $mReservas_duplicadas->Modificar($parametros['localizador2'.$num_fila]);
							if($modificaReservas_duplicadas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaReservas_duplicadas;
								
							}
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
			}
		
			
			//Llamada a la clase especifica de la pantalla
			$oReservas_duplicadas = new clsReservas_duplicadas($conexion, $filadesde, $usuario);
			$sReservas_duplicadas = $oReservas_duplicadas->Cargar();
			$sComboSelectReservas_duplicadas = $oReservas_duplicadas->Cargar_combo_selector();
				
			//Llamada a la clase general de combos
			//$oSino = new clsGeneral($conexion);
			//$comboSino = $oSino->Cargar_combo_SiNo();

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
			$smarty->assign('formulario', '» CONTROL DE RESERVAS DUPLICADAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectReservas_duplicadas);

			//Cargar combos

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('reservas_duplicadas', $sReservas_duplicadas);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			
			//Visualizar plantilla
			$smarty->display('plantillas/Reservas_duplicadas.html');

		}
		else{
			session_destroy();
			exit;
		}


	}else{

		$filadesde = 1;

			//Llamada a la clase especifica de la pantalla
			$oReservas_duplicadas = new clsReservas_duplicadas($conexion, $filadesde, $usuario);
			$sReservas_duplicadas = $oReservas_duplicadas->Cargar();
			$sComboSelectReservas_duplicadas = $oReservas_duplicadas->Cargar_combo_selector();
			
			//Llamada a la clase general de combos
			//$oSino = new clsGeneral($conexion);
			//$comboSino = $oSino->Cargar_combo_SiNo();

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
			$smarty->assign('formulario', '» CONTROL DE RESERVAS DUPLICADAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectReservas_duplicadas);

			//Cargar combos

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('reservas_duplicadas', $sReservas_duplicadas);

			//(Recuprando valores tecleados por el usuario en caso de error al insertar registro)

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario

		//Visualizar plantilla
		$smarty->display('plantillas/Reservas_duplicadas.html');
	}

	$conexion->close();


?>

