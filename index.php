<?php
	require ('Smarty.class.php');
	//include 'smarty/Smarty.class.php';
	include 'funciones_php/conexiones.php';

	/*echo('<pre>');
	print_r($_POST);
	echo('</pre>');*/
	$parametros = $_POST;

	if(count($_POST) != 0){
		$usuario = $parametros['usuario'];
		$password =  $parametros['password'];

		//echo("SELECT USUARIO, PASSWORD, NOMBRE, NIVEL, TIPO FROM hit_usuarios WHERE USUARIO = '".$usuario."'");

		$conexion = conexion_hit();
		$datos =$conexion->query("SELECT USUARIO, PASSWORD, NOMBRE, NIVEL, TIPO FROM hit_usuarios WHERE USUARIO = '".$usuario."'");
		if ($datos == FALSE){
			echo('Error en la consulta');
			//$datos->close();
			$conexion->close();
			exit;
		}
		$datos_usu	 = $datos->fetch_assoc();

		/*echo($datos_usu['USUARIO']);	
		echo('-');
		echo($datos_usu['PASSWORD']);	
		echo('-');
		echo($datos_usu['NOMBRE']);	
		echo('<BR>');*/


		if($usuario == $datos_usu['USUARIO'] and $password == $datos_usu['PASSWORD'] and $datos_usu['USUARIO'] != null and $datos_usu['PASSWORD'] != null){
			//echo('El usuario y la password son correctos');	

			session_start();
			$_SESSION['usuario'] = $datos_usu['USUARIO'];
			$_SESSION['nombre'] = $datos_usu['NOMBRE'];
			$_SESSION['nivel'] = $datos_usu['NIVEL'];
			$_SESSION['tipo'] = $datos_usu['TIPO'];
			//header('Location: Menu.php');

			$query = "INSERT INTO hit_usuarios_control_acceso (USUARIO,DESDE) VALUES (";
			$query .= "'".$usuario."', 'HITS')";

			$resultadoi =$conexion->query($query);

			if ($resultadoi == FALSE){
				echo('No se ha podido insertar el nuevo registro. '.$conexion->error);
			}

			$smarty = new Smarty;
			$smarty->assign('mensaje', '');
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');
			$smarty->assign('grupo', '» Inicio');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '');
			$smarty->assign('nombre', $datos_usu['NOMBRE']);
			$smarty->display('plantillas/Index.html');


		}else{
			//echo('el usuario no existe o la password no es correcta');	
			$smarty = new Smarty;



			$smarty->assign('mensaje', 'El usuario no existe o la password no es correcta');
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu_vacio.html');
			$smarty->assign('pie', 'plantillas/Pie.html');
			$smarty->assign('grupo', '');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '');
			$smarty->assign('nombre', '');
			$smarty->display('plantillas/Index.html');
		}

	}else{
		$smarty = new Smarty;
		$smarty->assign('mensaje', ' ');
		$smarty->assign('cabecera', 'plantillas/Cabecera.html');
		$smarty->assign('menu', 'plantillas/Menu_vacio.html');
		$smarty->assign('pie', 'plantillas/Pie.html');
		$smarty->assign('grupo', '');
		$smarty->assign('subgrupo', '');
		$smarty->assign('formulario', '');
		$smarty->assign('nombre', '');
		$smarty->display('plantillas/Index.html');
	}





	

?>
</table>
