<?php
	require ('Smarty.class.php');
	//include 'funciones_php/conexiones.php';

	session_start();
	
	$usuario = $_SESSION['usuario'];
	$nombre =  $_SESSION['nombre'];

	/*echo('<pre>');
	print_r($usuario);
	echo('-');
	print_r($nombre);
	echo('</pre>');*/



	$smarty = new Smarty;
	$smarty->assign('pie', 'plantillas/Pie.html');
	$smarty->assign('nombre', $nombre);
	$smarty->display('plantillas/Menu.html');

	/*if(count($_POST) != 0){
		$usuario = $parametros['usuario'];
		$password =  $parametros['password'];



		$conexion = conexion_hit();
		$datos =$conexion->query("SELECT USUARIO, PASSWORD, NOMBRE FROM HIT_USUARIOS WHERE USUARIO = '".$usuario."'");
		if ($datos == FALSE){
			echo('Error en la consulta');
			$datos->close();
			$conexion->close();
			exit;
		}
		$datos_usu	 = $datos->fetch_assoc();

			$smarty = new Smarty;
			$smarty->assign('pie', 'plantillas/Pie.html');
			$smarty->display('Menu.html');

	}*/
?>
</table>
