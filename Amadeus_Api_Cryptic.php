<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/amadeus_api.cls.php';

	session_start();

	$usuario = $_SESSION['usuario'];
	$nombre =  $_SESSION['nombre'];


	$parametros = $_POST;
	$mensaje1 = '';
	$mensaje2 = '';
	$error = '';
	$conexion = conexion_hit();




	/*echo('<pre>');
	print_r($usuario);
	echo('-');
	print_r($nombre);
	echo('</pre>');*/

	/*echo('<pre>');
	print_r($_POST);
	echo('</pre>');*/

	/*$parametros = $_POST;
	$parametrosg = $_GET;*/


	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){


			//Llamada a la clase especifica de la pantalla
			$oAmadeus_api = new clsAmadeus_api($conexion, 1, $usuario, '', '', '');
			$sAmadeus_api = $oAmadeus_api->Cargar();


			if($parametros['actuar'] == 'C'){
				//ECHO('HOLA');
				//EXPANDIMOS CUPOS
				$Mensaje = '';
				

				
				$consultaCrytic = $oAmadeus_api->Cryptic($parametros['comando']);
				/*if($consultaCrytic == 'OK'){
					$Mensaje = "Respuesta recibida";
				}else{
					$error = $consultaCrytic;
				}*/

				//Mostramos mensajes y posibles errores
				/*$mensaje1 = "<div><font color='#003399' size='3' ><b>".$Mensaje."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}*/
				//$eAcuerdos->close();

			}

			//Llamada a la clase especifica de la pantalla

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
			$smarty->assign('formulario', '» AMADEUS CRYPTIC');
	
			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);


			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('respuesta', $consultaCrytic);


			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			/*$smarty->assign('amadeus_api_nuevos', $sAmadeus_apinuevos);			
			$smarty->assign('recuperacodigo', $recuperacodigo);	
			$smarty->assign('recuperanombre', $recuperanombre);	*/

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('comando', $parametros['comando']);

			//Visualizar plantilla
			$smarty->display('plantillas/Amadeus_Api_Cryptic.html');


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
		$parametros['comando'] = "";
		//$parametros['respuesta'] = "";

		//Llamada a la clase especifica de la pantalla
		/*$oAmadeus_api = new clsAmadeus_api($conexion, $filadesde, $usuario, '', '', '');
		$consultaCrytic = $oAmadeus_api->Cryptic($parametros['comando']);*/

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
		$smarty->assign('color_opcion', '#00AF50');
		$smarty->assign('grupo', '» RESERVAS');
		$smarty->assign('subgrupo', '');
		$smarty->assign('formulario', '» AMADEUS CRYPTIC');

		//Nombre del usuario de la sesion
		$smarty->assign('nombre', $nombre);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('respuesta', '');

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1', $mensaje1);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('comando', '');



		//Visualizar plantilla
		$smarty->display('plantillas/Amadeus_Api_Cryptic.html');
	}


?>

