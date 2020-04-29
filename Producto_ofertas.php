<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/producto_ofertas.cls.php';


	session_start();

	//$usuario = $_SESSION['usuario'];
	//$nombre =  $_SESSION['nombre'];

	if(count($_SESSION) != 0){
		$usuario = $_SESSION['usuario'];
		$nombre =  $_SESSION['nombre'];
	}else{
		echo('Se ha cerrado la sesion');
		$usuario = '';
		$nombre =  '';
	}



    /* echo(session_name());
	ECHO(session_id());

	echo('<pre>');
	print_r($_SESSION);
	echo('</pre>');*/

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
	$insertaProducto_ofertas = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperaorden = '';
	$recuperatipo = '';
	$recuperadescripcion = '';	
	$recuperadesde = '';
	$recuperahasta = '';
	$recuperaestado = '';

	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonProducto_ofertas = new clsProducto_ofertas($conexion, $filadesde, $usuario, $parametros['buscar_tipo'], $parametros['buscar_estado']);
				$botonselec = $botonProducto_ofertas->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oProducto_ofertas = new clsProducto_ofertas($conexion, $filadesde, $usuario, $parametros['buscar_tipo'], $parametros['buscar_estado']);
			$sProducto_ofertas = $oProducto_ofertas->Cargar();
			//lineas visualizadas
			$vueltas = count($sProducto_ofertas);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_OFERTAS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mProducto_ofertas = new clsProducto_ofertas($conexion, $filadesde, $usuario, $parametros['buscar_tipo'], $parametros['buscar_estado']);
							$modificaProducto_ofertas = $mProducto_ofertas->Modificar($parametros['id'.$num_fila], $parametros['orden'.$num_fila], $parametros['desde'.$num_fila], $parametros['hasta'.$num_fila],$parametros['estado'.$num_fila]);
							if($modificaProducto_ofertas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaProducto_ofertas;
								
							}
						}else{

							$mProducto_ofertas = new clsProducto_ofertas($conexion, $filadesde, $usuario, $parametros['buscar_tipo'], $parametros['buscar_estado']);
							$borraProducto_ofertas = $mProducto_ofertas->Borrar($parametros['id'.$num_fila]);
							if($borraProducto_ofertas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraProducto_ofertas;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_OFERTAS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						



						$iProducto_ofertas = new clsProducto_ofertas($conexion, $filadesde, $usuario, $parametros['buscar_tipo'], $parametros['buscar_estado']);


						$insertaProducto_ofertas = $iProducto_ofertas->Insertar($parametros['Nuevoorden'.$num_fila], $parametros['Nuevotipo'.$num_fila], $parametros['Nuevodescripcion'.$num_fila],$parametros['Nuevodesde'.$num_fila],$parametros['Nuevohasta'.$num_fila],$parametros['Nuevoestado'.$num_fila]);
						if($insertaProducto_ofertas == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaProducto_ofertas;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperaorden = $parametros['Nuevoorden'.$num_fila]; 
							$recuperatipo = $parametros['Nuevotipo'.$num_fila]; 
							$recuperafolleto = $parametros['Nuevodescripcion'.$num_fila]; 
							$recuperadesde = $parametros['Nuevodesde'.$num_fila];
							$recuperahasta = $parametros['Nuevohasta'.$num_fila];
							$recuperaestado= $parametros['Nuevoestado'.$num_fila];

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
			$sProducto_ofertas = $oProducto_ofertas->Cargar();
			$sProducto_ofertasnuevos = $oProducto_ofertas->Cargar_lineas_nuevas();
			$sComboSelectProducto_ofertas = $oProducto_ofertas->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboActividad= $oSino->Cargar_combo_Actividad();
			$comboTipo= $oSino->Cargar_combo_Tipo_Cuadros();
			$comboFolletos = $oSino->Cargar_combo_Folletos();



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
			$smarty->assign('color_opcion', '#027767');
			$smarty->assign('grupo', '» MARKETING');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» OFERTAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectProducto_ofertas);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('producto_ofertas', $sProducto_ofertas);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboActividad', $comboActividad);
			$smarty->assign('comboTipo', $comboTipo);
			$smarty->assign('comboFolletos', $comboFolletos);


			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('sProducto_ofertasnuevos', $sProducto_ofertasnuevos);	
			$smarty->assign('recuperaorden', $recuperaorden);	
			$smarty->assign('recuperatipo', $recuperatipo);
			$smarty->assign('recuperadescripcion', $recuperadescripcion);		
			$smarty->assign('recuperadesde', $recuperadesde);	
			$smarty->assign('recuperahasta', $recuperahasta);	
			$smarty->assign('recuperaestado', $recuperaestado);


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_estado', $parametros['buscar_estado']);
			$smarty->assign('buscar_tipo', $parametros['buscar_tipo']);

			//Visualizar plantilla
			$smarty->display('plantillas/Producto_ofertas.html');

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
		$parametros['buscar_estado'] = "";
		$parametros['buscar_tipo'] = "";

		//Llamada a la clase especifica de la pantalla
		$oProducto_ofertas = new clsProducto_ofertas($conexion, $filadesde, $usuario, $parametros['buscar_tipo'], $parametros['buscar_estado']);
		$sProducto_ofertas = $oProducto_ofertas->Cargar();
		$sProducto_ofertasnuevos = $oProducto_ofertas->Cargar_lineas_nuevas();
		$sComboSelectProducto_ofertas = $oProducto_ofertas->Cargar_combo_selector();

		//Llamada a la clase general de combos
		$oSino = new clsGeneral($conexion);
		$comboActividad= $oSino->Cargar_combo_Actividad();
		$comboTipo= $oSino->Cargar_combo_Tipo_Cuadros();
		$comboFolletos = $oSino->Cargar_combo_Folletos();


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
		$smarty->assign('color_opcion', '#027767');
		$smarty->assign('grupo', '» MARKETING');
		$smarty->assign('subgrupo', '');
		$smarty->assign('formulario', '» OFERTAS');

		//Nombre del usuario de la sesion
		$smarty->assign('nombre', $nombre);

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades', $filadesde);

		//Cargar combo selector
		$smarty->assign('combo', $sComboSelectProducto_ofertas);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('producto_ofertas', $sProducto_ofertas);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboActividad', $comboActividad);
		$smarty->assign('comboTipo', $comboTipo);
		$smarty->assign('comboFolletos', $comboFolletos);


		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('sProducto_ofertasnuevos', $sProducto_ofertasnuevos);
		$smarty->assign('recuperaorden', $recuperaorden);	
		$smarty->assign('recuperatipo', $recuperatipo);
		$smarty->assign('recuperadescripcion', $recuperadescripcion);		
		$smarty->assign('recuperadesde', $recuperadesde);	
		$smarty->assign('recuperahasta', $recuperahasta);	
		$smarty->assign('recuperaestado', $recuperaestado);

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1', $mensaje1);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_estado', $parametros['buscar_estado']);
		$smarty->assign('buscar_tipo', $parametros['buscar_tipo']);


		//Visualizar plantilla
		$smarty->display('plantillas/Producto_ofertas.html');
	}

	$conexion->close();


?>

