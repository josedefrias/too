<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/zonas.cls.php';


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
	$insertaZonas = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacodigo = '';
	$recuperanombre = '';
	$recuperadestino = '';
	$recuperavisible = '';
	$recuperavisible_grupos = '';
	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonZonas = new clsZonas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
				$botonselec = $botonZonas->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oZonas = new clsZonas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
			$sZonas = $oZonas->Cargar();
			//lineas visualizadas
			$vueltas = count($sZonas);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ZONAS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas -1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mZonas = new clsZonas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
							$modificaZonas = $mZonas->Modificar($parametros['codigo'.$num_fila], $parametros['nombre'.$num_fila], $parametros['destino'.$num_fila], $parametros['visible'.$num_fila], $parametros['visible_grupos'.$num_fila]);
							if($modificaZonas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaZonas;
								
							}
						}else{

							$mZonas = new clsZonas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
							$borraZonas = $mZonas->Borrar($parametros['codigo'.$num_fila]);
							if($borraZonas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraZonas;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ZONAS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iZonas = new clsZonas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
						$insertaZonas = $iZonas->Insertar($parametros['Nuevocodigo'.$num_fila], $parametros['Nuevonombre'.$num_fila], $parametros['Nuevodestino'.$num_fila], $parametros['Nuevovisible'.$num_fila], $parametros['Nuevovisible_grupos'.$num_fila]);
						if($insertaZonas == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaZonas;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacodigo = $parametros['Nuevocodigo'.$num_fila]; 
							$recuperanombre = $parametros['Nuevonombre'.$num_fila];
							$recuperadestino = $parametros['Nuevodestino'.$num_fila];
							$recuperavisible = $parametros['Nuevovisible'.$num_fila];
							$recuperavisible_grupos = $parametros['Nuevovisible_grupos'.$num_fila];
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
			$sZonas = $oZonas->Cargar();
			$sZonasnuevos = $oZonas->Cargar_lineas_nuevas();
			$sComboSelectZonas = $oZonas->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo_Si();	
			$comboDestinos = $oSino->Cargar_combo_Destinos();		
			
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
			$smarty->assign('subgrupo', '» ESPECIFICO WEB');
			$smarty->assign('formulario', '» ZONAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectZonas);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('zonas', $sZonas);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);	
			$smarty->assign('comboDestinos', $comboDestinos);			

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('zonas_nuevos', $sZonasnuevos);			
			$smarty->assign('recuperacodigo', $recuperacodigo);	
			$smarty->assign('recuperanombre', $recuperanombre);	
			$smarty->assign('recuperadestino', $recuperadestino);	
			$smarty->assign('recuperavisible', $recuperavisible);
			$smarty->assign('recuperavisible_grupos', $recuperavisible_grupos);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);

			//Visualizar plantilla
			$smarty->display('plantillas/Zonas.html');


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
		$parametros['buscar_codigo'] = "";
		$parametros['buscar_nombre'] = "";

		//Llamada a la clase especifica de la pantalla
		$oZonas = new clsZonas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
		$sZonas = $oZonas->Cargar();
		$sZonasnuevos = $oZonas->Cargar_lineas_nuevas();
		$sComboSelectZonas = $oZonas->Cargar_combo_selector();

		//Llamada a la clase general de combos
		$oSino = new clsGeneral($conexion);
		$comboSino = $oSino->Cargar_combo_SiNo_Si();
		$comboDestinos = $oSino->Cargar_combo_Destinos();			
		
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
		$smarty->assign('subgrupo', '» ESPECIFICO WEB');
		$smarty->assign('formulario', '» ZONAS');

		//Nombre del usuario de la sesion
		$smarty->assign('nombre', $nombre);

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades', $filadesde);

		//Cargar combo selector
		$smarty->assign('combo', $sComboSelectZonas);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('zonas', $sZonas);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboSino', $comboSino);
		$smarty->assign('comboDestinos', $comboDestinos);
		
		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('zonas_nuevos', $sZonasnuevos);			
		$smarty->assign('recuperacodigo', $recuperacodigo);	
		$smarty->assign('recuperanombre', $recuperanombre);	
		$smarty->assign('recuperadestino', $recuperadestino);
		$smarty->assign('recuperavisible', $recuperavisible);
		$smarty->assign('recuperavisible_grupos', $recuperavisible_grupos);	

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1', $mensaje1);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_codigo', '');
		$smarty->assign('buscar_nombre', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Zonas.html');
	}

	$conexion->close();


?>

