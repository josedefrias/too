<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/formularios.cls.php';


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

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS

				$num_filas =$conexion->query("select u.formulario
											  from hit_usuarios_formularios u, hit_formularios f 
											  where u.formulario = f.formulario and f.permitido_cambio = 'S' 
													and u.usuario = '".$usuario."' order by f.id");
				$Nfilas = $num_filas->num_rows;

				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila < $Nfilas; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S'){
							
						$mFormularios = new clsFormularios($conexion, $usuario);
						$modificaFormularios = $mFormularios->Modificar($parametros['formulario'.$num_fila], $parametros['lineas_modificacion'.$num_fila], $parametros['lineas_nuevas'.$num_fila]);
						if($modificaFormularios == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $modificaFormularios;
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();
			}

			//Llamada a la clase especifica de la pantalla
			$oFormularios = new clsFormularios($conexion, $usuario);
			$sFormularios = $oFormularios->Cargar();

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
			$smarty->assign('formulario', '» FORMULARIOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Cargar lineas de la tabla para visualizar o modificar
			$smarty->assign('formularios', $sFormularios);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Visualizar plantilla
			$smarty->display('plantillas/Formularios.html');


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

			//Llamada a la clase especifica de la pantalla
			$oFormularios = new clsFormularios($conexion, $usuario);
			$sFormularios = $oFormularios->Cargar();

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
			$smarty->assign('formulario', '» FORMULARIOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);


			//Cargar lineas de la tabla para visualizar o modificar
			$smarty->assign('formularios', $sFormularios);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Visualizar plantilla
			$smarty->display('plantillas/Formularios.html');
	}

	$conexion->close();


?>

