<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/aeropuertos_ciudades.cls.php';


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
	$insertaAeropuertos_ciudaes = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperaaeropuerto = '';
	$recuperaciudad = '';
	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonAeropuertos_ciudades = new clsAeropuertos_ciudades($conexion, $filadesde, $usuario, $parametros['buscar_aeropuerto'], $parametros['buscar_ciudad']);
				$botonselec = $botonAeropuertos_ciudades->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}


			//Llamada a la clase especifica de la pantalla
			$oAeropuertos_ciudades = new clsAeropuertos_ciudades($conexion, $filadesde, $usuario, $parametros['buscar_aeropuerto'], $parametros['buscar_ciudad']);
			$sAeropuertos_ciudades = $oAeropuertos_ciudades->Cargar();
			//lineas visualizadas
			$vueltas = count($sAeropuertos_ciudades);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'AEROPUERTOS_CIUDADES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							/*$mAeropuertos = new clsAeropuertos($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
							$modificaAeropuertos = $mAeropuertos->Modificar($parametros['codigo'.$num_fila], $parametros['nombre'.$num_fila],$parametros['ciudad'.$num_fila]);
							if($modificaAeropuertos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaAeropuertos;
								
							}*/
							$error = 'No es posible modificar registros en esta pantalla';
						}else{

							$mAeropuertos_ciudades = new clsAeropuertos_ciudades($conexion, $filadesde, $usuario, $parametros['buscar_aeropuerto'], $parametros['buscar_ciudad']);
							$borraAeropuertos_ciudades = $mAeropuertos_ciudades->Borrar($parametros['aeropuerto'.$num_fila], $parametros['ciudad'.$num_fila]);
							if($borraAeropuertos_ciudades == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraAeropuertos_ciudades;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'AEROPUERTOS_CIUDADES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iAeropuertos_ciudades = new clsAeropuertos_ciudades($conexion, $filadesde, $usuario, $parametros['buscar_aeropuerto'], $parametros['buscar_ciudad']);
						$insertaAeropuertos_ciudades = $iAeropuertos_ciudades->Insertar($parametros['Nuevoaeropuerto'.$num_fila], $parametros['Nuevociudad'.$num_fila]);
						if($insertaAeropuertos_ciudades == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaAeropuertos_ciudades;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacodigo = $parametros['Nuevoaeropuerto'.$num_fila]; 
							$recuperanombre = $parametros['Nuevociudad'.$num_fila];
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
			$sAeropuertos_ciudades = $oAeropuertos_ciudades->Cargar();
			$sAeropuertos_ciudadesnuevos = $oAeropuertos_ciudades->Cargar_lineas_nuevas();
			$sComboSelectAeropuertos_ciudades = $oAeropuertos_ciudades->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboAeropuertos = $oCombo->Cargar_combo_Aeropuertos();
			$comboCiudades = $oCombo->Cargar_combo_Ciudades();


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
			$smarty->assign('subgrupo', '» GEOGRAFICAS');
			$smarty->assign('formulario', '» APTOS - CIUDADES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAeropuertos_ciudades);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('aeropuertos_ciudades', $sAeropuertos_ciudades);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAeropuertos', $comboAeropuertos);
			$smarty->assign('comboCiudades', $comboCiudades);


			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('aeropuertos_ciudades_nuevos', $sAeropuertos_ciudadesnuevos);			
			$smarty->assign('recuperaaeropuerto', $recuperaaeropuerto);	
			$smarty->assign('recuperaciudad', $recuperaciudad);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_aeropuerto', $parametros['buscar_aeropuerto']);
			$smarty->assign('buscar_ciudad', $parametros['buscar_ciudad']);

			//Visualizar plantilla
			$smarty->display('plantillas/Aeropuertos_ciudades.html');


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
		$parametros['buscar_aeropuerto'] = "";
		$parametros['buscar_ciudad'] = "";

			//Llamada a la clase especifica de la pantalla
			$oAeropuertos_ciudades = new clsAeropuertos_ciudades($conexion, $filadesde, $usuario, $parametros['buscar_aeropuerto'], $parametros['buscar_ciudad']);
			$sAeropuertos_ciudades = $oAeropuertos_ciudades->Cargar();
			$sAeropuertos_ciudadesnuevos = $oAeropuertos_ciudades->Cargar_lineas_nuevas();
			$sComboSelectAeropuertos_ciudades = $oAeropuertos_ciudades->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboAeropuertos = $oCombo->Cargar_combo_Aeropuertos();
			$comboCiudades = $oCombo->Cargar_combo_Ciudades();


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
			$smarty->assign('subgrupo', '» GEOGRAFICAS');
			$smarty->assign('formulario', '» APTOS - CIUDADES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAeropuertos_ciudades);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('aeropuertos_ciudades', $sAeropuertos_ciudades);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAeropuertos', $comboAeropuertos);
			$smarty->assign('comboCiudades', $comboCiudades);


			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('aeropuertos_ciudades_nuevos', $sAeropuertos_ciudadesnuevos);			
			$smarty->assign('recuperaaeropuerto', $recuperaaeropuerto);	
			$smarty->assign('recuperaciudad', $recuperaciudad);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_aeropuerto', '');
		$smarty->assign('buscar_ciudad', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Aeropuertos_ciudades.html');
	}

	$conexion->close();


?>

