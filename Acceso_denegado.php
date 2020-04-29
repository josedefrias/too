<?php
	require ('Smarty.class.php');

	session_start();

	/*echo('<pre>');
	print_r($_POST);
	echo('</pre>');*/

	$usuario = $_SESSION['usuario'];
	$nombre =  $_SESSION['nombre'];
	$parametros = $_POST;
	if(count($_POST) != 0){
		if($parametros['ir_a'] == 'S'){
			session_destroy();
			exit;
		}
	}
		//Establecer plantilla smarty
		$smarty = new Smarty;

		//Cargamos cabecera y pie de plantilla
		$smarty->assign('cabecera', 'plantillas/Cabecera.html');
		$smarty->assign('menu', 'plantillas/Menu.html');
		$smarty->assign('pie', 'plantillas/Pie.html');

		//Nombre del formulario
		$smarty->assign('formulario', 'ADMINISTRADORES DEL SISTEMA');

		//Nombre del formulario
		$smarty->assign('color_opcion', '#010080');
		$smarty->assign('grupo', '');
		$smarty->assign('subgrupo', '');
		$smarty->assign('formulario', '');

		//Nombre del usuario de la sesion
		$smarty->assign('nombre', $nombre);

		//Visualizar plantilla
		$smarty->display('plantillas/Acceso_denegado.html');

?>

