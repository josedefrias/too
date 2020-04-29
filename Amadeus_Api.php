<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/amadeus_api.cls.php';


	session_start();

	$usuario = $_SESSION['usuario'];
	$nombre =  $_SESSION['nombre'];

	/*echo('<pre>');
	print_r($usuario);ir_air_air_air_air_air_air_air_air_a
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
	$insertaAmadeus_api = '';
	$conexion = conexion_hit();


	
	//Prueba Api
	//$oDispo = new clsAmadeus_Api($conexion, $usuario, "", "");
	//$Dispo = $oDispo->Cargar_Disponibilidad();

	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonAmadeus_api = new clsAmadeus_api($conexion, $filadesde, $usuario, $parametros['buscar_fecha'], $parametros['buscar_origen'], $parametros['buscar_destino']);
				$botonselec = $botonAmadeus_api->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oAmadeus_api = new clsAmadeus_api($conexion, $filadesde, $usuario, $parametros['buscar_fecha'], $parametros['buscar_origen'], $parametros['buscar_destino']);
			$sAmadeus_api = $oAmadeus_api->Cargar();

			//$vueltas = count($sAmadeus_api);

			/*if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS

				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mAmadeus_api = new clsAmadeus_api($conexion, $filadesde, $usuario, $parametros['buscar_fecha'], $parametros['buscar_origen'], $parametros['buscar_origen']);
							$modificaAmadeus_api = $mAmadeus_api->Modificar($parametros['codigo'.$num_fila], $parametros['nombre'.$num_fila]);
							if($modificaAmadeus_api == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaAmadeus_api;
								
							}
						}else{

							$mAmadeus_api = new clsAmadeus_api($conexion, $filadesde, $usuario, $parametros['buscar_fecha'], $parametros['buscar_origen'], $parametros['buscar_origen']);
							$borraAmadeus_api = $mAmadeus_api->Borrar($parametros['codigo'.$num_fila]);
							if($borraAmadeus_api == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraAmadeus_api;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'AMADEUS_API' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iAmadeus_api = new clsAmadeus_api($conexion, $filadesde, $usuario, $parametros['buscar_fecha'], $parametros['buscar_origen'], $parametros['buscar_origen']);
						$insertaAmadeus_api = $iAmadeus_api->Insertar($parametros['Nuevocodigo'.$num_fila], $parametros['Nuevonombre'.$num_fila]);
						if($insertaAmadeus_api == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaAmadeus_api;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacodigo = $parametros['Nuevocodigo'.$num_fila]; 
							$recuperanombre = $parametros['Nuevonombre'.$num_fila];
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
			}*/

			if($parametros['actuar'] == 'D'){

				//EXPANDIMOS CUPOS
				$Mensaje = '';
				

				
				$actualizarDisponibilidad = $oAmadeus_api->Cargar_Disponibilidad();
				if($actualizarDisponibilidad == 'OK'){
					$Mensaje = "Se Ha actualizado la disponibilidad.";
				}else{
					$error = $actualizarDisponibilidad;
				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>".$Mensaje."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//$eAcuerdos->close();

			}

			//Llamada a la clase especifica de la pantalla
			$sAmadeus_api = $oAmadeus_api->Cargar();
			//$sAmadeus_apinuevos = $oAmadeus_api->Cargar_lineas_nuevas();
			$sComboSelectAmadeus_api = $oAmadeus_api->Cargar_combo_selector();

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
			$smarty->assign('formulario', '» AMADEUS API');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAmadeus_api);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('amadeus_api', $sAmadeus_api);


			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			/*$smarty->assign('amadeus_api_nuevos', $sAmadeus_apinuevos);			
			$smarty->assign('recuperacodigo', $recuperacodigo);	
			$smarty->assign('recuperanombre', $recuperanombre);	*/

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_fecha', $parametros['buscar_fecha']);
			$smarty->assign('buscar_origen', $parametros['buscar_origen']);
			$smarty->assign('buscar_destino', $parametros['buscar_destino']);

			//Visualizar plantilla
			$smarty->display('plantillas/Amadeus_api.html');


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
		$parametros['buscar_fecha'] = "";
		$parametros['buscar_origen'] = "";
		$parametros['buscar_destino'] = "";

		//Llamada a la clase especifica de la pantalla
		$oAmadeus_api = new clsAmadeus_api($conexion, $filadesde, $usuario, $parametros['buscar_fecha'], $parametros['buscar_origen'], $parametros['buscar_destino']);
		$sAmadeus_api = $oAmadeus_api->Cargar();
		//$sAmadeus_apinuevos = $oAmadeus_api->Cargar_lineas_nuevas();
		$sComboSelectAmadeus_api = $oAmadeus_api->Cargar_combo_selector();

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
		$smarty->assign('formulario', '» AMADEUS API');

		//Nombre del usuario de la sesion
		$smarty->assign('nombre', $nombre);

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades', $filadesde);

		//Cargar combo selector
		$smarty->assign('combo', $sComboSelectAmadeus_api);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('amadeus_api', $sAmadeus_api);


		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		/*$smarty->assign('amadeus_api_nuevos', $sAmadeus_apinuevos);			
		$smarty->assign('recuperacodigo', $recuperacodigo);	
		$smarty->assign('recuperanombre', $recuperanombre);	*/

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1', $mensaje1);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_fecha', '');
		$smarty->assign('buscar_origen', '');
		$smarty->assign('buscar_destino', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Amadeus_api.html');
	}

	$conexion->close();


?>

