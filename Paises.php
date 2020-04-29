<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/paises.cls.php';


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
	$insertaPaises = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacodigo_corto = '';
	$recuperacodigo_numerico = '';
	$recuperacodigo = '';
	$recuperanombre = '';
	$recuperacontinente = '';
	$recuperaarea = '';
	$recuperaidioma = '';
	$recuperadivsa = '';
	$recuperaunion_europea = '';
	$recuperaprefijo = '';
	$recuperavisado = '';
	$recuperavisible_hits = '';
	$recuperavisible_web = '';
	$recuperavisible_web_minorista = '';
	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonPaises = new clsPaises($conexion, 'NOMBRE', 'CONTINENTE', $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
				$botonselec = $botonPaises->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oPaises = new clsPaises($conexion, 'NOMBRE', 'CONTINENTE', $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
			$sPaises = $oPaises->Cargar();
			//lineas visualizadas
			$vueltas = count($sPaises);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PAISES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mPaises = new clsPaises($conexion, 'NOMBRE', 'CONTINENTE', $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
							$modificaPaises = $mPaises->Modificar($parametros['codigo_corto'.$num_fila], $parametros['codigo_numerico'.$num_fila], $parametros['codigo'.$num_fila], $parametros['nombre'.$num_fila],$parametros['continente'.$num_fila],$parametros['area'.$num_fila],$parametros['idioma'.$num_fila],$parametros['divisa'.$num_fila],$parametros['union_europea'.$num_fila],$parametros['prefijo'.$num_fila],$parametros['visado'.$num_fila],$parametros['visible_hits'.$num_fila],$parametros['visible_web'.$num_fila],$parametros['visible_web_minorista'.$num_fila]);
							if($modificaPaises == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaPaises;
								
							}
						}else{

							$mPaises = new clsPaises($conexion, 'NOMBRE', 'CONTINENTE', $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
							$borraPaises = $mPaises->Borrar($parametros['codigo'.$num_fila]);
							if($borraPaises == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraPaises;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PAISES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iPaises = new clsPaises($conexion, 'NOMBRE', 'CONTINENTE', $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
						$insertaPaises = $iPaises->Insertar($parametros['Nuevocodigo_corto'.$num_fila], $parametros['Nuevocodigo_numerico'.$num_fila], $parametros['Nuevocodigo'.$num_fila], $parametros['Nuevonombre'.$num_fila],$parametros['Nuevocontinente'.$num_fila],$parametros['Nuevoarea'.$num_fila], $parametros['Nuevoidioma'.$num_fila],$parametros['Nuevodivisa'.$num_fila],$parametros['Nuevounion_europea'.$num_fila],$parametros['Nuevoprefijo'.$num_fila],$parametros['Nuevovisado'.$num_fila],$parametros['Nuevovisible_hits'.$num_fila],$parametros['Nuevovisible_web'.$num_fila],$parametros['Nuevovisible_web_minorista'.$num_fila]);
						if($insertaPaises == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaPaises;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacodigo_corto = $parametros['Nuevocodigocorto'.$num_fila]; 
							$recuperacodigo_numerico = $parametros['Nuevocodigo_numerico'.$num_fila]; 
							$recuperacodigo = $parametros['Nuevocodigo'.$num_fila]; 
							$recuperanombre = $parametros['Nuevonombre'.$num_fila];
							$recuperacontinente = $parametros['Nuevocontinente'.$num_fila];
							$recuperaarea = $parametros['Nuevoarea'.$num_fila];
							$recuperaidioma = $parametros['Nuevoidioma'.$num_fila];
							$recuperadivisa = $parametros['Nuevodivisa'.$num_fila];
							$recuperaunion_europea = $parametros['Nuevounion_europea'.$num_fila];
							$recuperaprefijo = $parametros['Nuevoprefijo'.$num_fila];
							$recuperavisado = $parametros['Nuevovisado'.$num_fila];
							$recuperavisible_hits= $parametros['Nuevovisible_hits'.$num_fila];
							$recuperavisible_web = $parametros['Nuevovisible_web'.$num_fila];
							$recuperavisible_web_minorista = $parametros['Nuevovisible_web_minorista'.$num_fila];
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
			$sPaises = $oPaises->Cargar();
			$sPaisesnuevos = $oPaises->Cargar_lineas_nuevas();
			$sComboSelectPaises = $oPaises->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboDivisas = $oSino->Cargar_combo_Divisas();
			$comboIdiomas = $oSino->Cargar_combo_Idiomas();
			$comboContinentes = $oSino->Cargar_combo_Continentes();
			$comboAreas = $oSino->Cargar_combo_Areas();

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
			$smarty->assign('formulario', '» PAISES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectPaises);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('paises', $sPaises);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboDivisas', $comboDivisas);
			$smarty->assign('comboIdiomas', $comboIdiomas);
			$smarty->assign('comboContinentes', $comboContinentes);
			$smarty->assign('comboAreas', $comboAreas);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('paises_nuevos', $sPaisesnuevos);	
			$smarty->assign('recuperacodigo_corto', $recuperacodigo_corto);
			$smarty->assign('recuperacodigo_numerico', $recuperacodigo_numerico);		
			$smarty->assign('recuperacodigo', $recuperacodigo);	
			$smarty->assign('recuperanombre', $recuperanombre);	
			$smarty->assign('recuperacontinente', $recuperacontinente);	
			$smarty->assign('recuperaarea', $recuperaarea);
			$smarty->assign('recuperaidioma', $recuperaidioma);	
			$smarty->assign('recuperadivisa', $recuperadivsa);	
			$smarty->assign('recuperaunion_europea', $recuperaunion_europea);	
			$smarty->assign('recuperaprefijo', $recuperaprefijo);	
			$smarty->assign('recuperavisado', $recuperavisado);
			$smarty->assign('recuperavisible_hits', $recuperavisible_hits);	
			$smarty->assign('recuperavisible_web', $recuperavisible_web);
			$smarty->assign('recuperavisible_web_minorista', $recuperavisible_web_minorista);		

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);

			//Visualizar plantilla
			$smarty->display('plantillas/Paises.html');


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
		$oPaises = new clsPaises($conexion, 'NOMBRE', 'CONTINENTE', $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
		$sPaises = $oPaises->Cargar();
		$sPaisesnuevos = $oPaises->Cargar_lineas_nuevas();
		$sComboSelectPaises = $oPaises->Cargar_combo_selector();

		//Llamada a la clase general de combos
		$oSino = new clsGeneral($conexion);
		$comboSino = $oSino->Cargar_combo_SiNo();
		$comboDivisas = $oSino->Cargar_combo_Divisas();
		$comboIdiomas = $oSino->Cargar_combo_Idiomas();
		$comboContinentes = $oSino->Cargar_combo_Continentes();
		$comboAreas = $oSino->Cargar_combo_Areas();

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
		$smarty->assign('formulario', '» PAISES');

		//Nombre del usuario de la sesion
		$smarty->assign('nombre', $nombre);

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades', $filadesde);

		//Cargar combo selector
		$smarty->assign('combo', $sComboSelectPaises);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('paises', $sPaises);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboSino', $comboSino);
		$smarty->assign('comboDivisas', $comboDivisas);
		$smarty->assign('comboIdiomas', $comboIdiomas);
		$smarty->assign('comboContinentes', $comboContinentes);
		$smarty->assign('comboAreas', $comboAreas);

		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('paises_nuevos', $sPaisesnuevos);	
		$smarty->assign('recuperacodigo_corto', $recuperacodigo_corto);
		$smarty->assign('recuperacodigo_numerico', $recuperacodigo_numerico);		
		$smarty->assign('recuperacodigo', $recuperacodigo);	
		$smarty->assign('recuperanombre', $recuperanombre);	
		$smarty->assign('recuperacontinente', $recuperacontinente);	
		$smarty->assign('recuperaarea', $recuperaarea);
		$smarty->assign('recuperaidioma', $recuperaidioma);	
		$smarty->assign('recuperadivisa', $recuperadivsa);	
		$smarty->assign('recuperaunion_europea', $recuperaunion_europea);	
		$smarty->assign('recuperaprefijo', $recuperaprefijo);	
		$smarty->assign('recuperavisado', $recuperavisado);
		$smarty->assign('recuperavisible_hits', $recuperavisible_hits);	
		$smarty->assign('recuperavisible_web', $recuperavisible_web);	
		$smarty->assign('recuperavisible_web_minorista', $recuperavisible_web_minorista);

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1', $mensaje1);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_codigo', '');
		$smarty->assign('buscar_nombre', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Paises.html');
	}

	$conexion->close();


?>

