<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/acceso_usuarios.cls.php';


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
	$insertaAcceso_usuarios = '';
	$conexion = conexion_hit();


	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonAcceso_usuarios = new clsAcceso_usuarios($conexion, $filadesde, $usuario, $parametros['buscar_fecha_desde'], $parametros['buscar_fecha_hasta'], $parametros['buscar_desde'], $parametros['buscar_usuario']);
				$botonselec = $botonAcceso_usuarios->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oAcceso_usuarios = new clsAcceso_usuarios($conexion, $filadesde, $usuario, $parametros['buscar_fecha_desde'], $parametros['buscar_fecha_hasta'], $parametros['buscar_desde'], $parametros['buscar_usuario']);
			$sAcceso_usuarios = $oAcceso_usuarios->Cargar();
			//lineas visualizadas
			$vueltas = count($sAcceso_usuarios);

			//Llamada a la clase especifica de la pantalla
			$sAcceso_usuarios = $oAcceso_usuarios->Cargar();
			$sComboSelectAcceso_usuarios = $oAcceso_usuarios->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboSistemas = $oCombo->Cargar_Combo_Sistemas();

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
			$smarty->assign('color_opcion', '#010080');
			$smarty->assign('grupo', '» TABLAS GENERALES');
			$smarty->assign('subgrupo', '» CONFIGURACION');
			$smarty->assign('formulario', '» SESIONES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAcceso_usuarios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('acceso_usuarios', $sAcceso_usuarios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSistemas', $comboSistemas);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_fecha_desde', $parametros['buscar_fecha_desde']);
			$smarty->assign('buscar_fecha_hasta', $parametros['buscar_fecha_hasta']);
			$smarty->assign('buscar_desde', $parametros['buscar_desde']);
			$smarty->assign('buscar_usuario', $parametros['buscar_usuario']);

			//Visualizar plantilla
			$smarty->display('plantillas/Acceso_usuarios.html');


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
		$parametros['buscar_fecha_desde'] = "";
		$parametros['buscar_fecha_hasta'] = "";
		$parametros['buscar_desde'] = "";
		$parametros['buscar_usuario'] = "";

			//Llamada a la clase especifica de la pantalla
			$oAcceso_usuarios = new clsAcceso_usuarios($conexion, $filadesde, $usuario, $parametros['buscar_fecha_desde'], $parametros['buscar_fecha_hasta'], $parametros['buscar_desde'], $parametros['buscar_usuario']);
			$sAcceso_usuarios = $oAcceso_usuarios->Cargar();
			$sComboSelectAcceso_usuarios = $oAcceso_usuarios->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboSistemas = $oCombo->Cargar_Combo_Sistemas();

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
			$smarty->assign('color_opcion', '#010080');
			$smarty->assign('grupo', '» TABLAS GENERALES');
			$smarty->assign('subgrupo', '» CONFIGURACION');
			$smarty->assign('formulario', '» SESIONES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAcceso_usuarios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('acceso_usuarios', $sAcceso_usuarios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSistemas', $comboSistemas);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_fecha_desde', '');
			$smarty->assign('buscar_fecha_hasta', '');
			$smarty->assign('buscar_desde', '');
			$smarty->assign('buscar_usuario', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Acceso_usuarios.html');
	}

	$conexion->close();


?>

