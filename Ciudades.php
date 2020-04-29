<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/ciudades.cls.php';


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
	$insertaCiudades = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacodigo = '';
	$recuperanombre = '';
	$recuperapais = '';
	$recuperaprovincia = '';
	$recuperazona = '';
	$recuperavisible_hits = '';
	$recuperavisible_web = '';

	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];

			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonCiudades = new clsCiudades($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_provincia'], $parametros['buscar_zona']);
				$botonselec = $botonCiudades->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oCiudades = new clsCiudades($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_provincia'], $parametros['buscar_zona']);
			$sCiudades = $oCiudades->Cargar();
			//lineas visualizadas
			$vueltas = count($sCiudades);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CIUDADES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc(); ANTIGUO CONTROL DE LINEAS*/
				$Ntransacciones = 0;

				//for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) { ANTIGUO CONTRO,L DE LINEAS
				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mCiudades = new clsCiudades($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_provincia'], $parametros['buscar_zona']);
							$modificaCiudades = $mCiudades->Modificar($parametros['codigo'.$num_fila], $parametros['nombre'.$num_fila],$parametros['pais'.$num_fila],$parametros['provincia'.$num_fila],$parametros['zona'.$num_fila],$parametros['visible_hits'.$num_fila],$parametros['visible_web'.$num_fila]);
							if($modificaCiudades == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaCiudades;
								
							}
						}else{

							$mCiudades = new clsCiudades($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_provincia'], $parametros['buscar_zona']);
							$borraCiudades = $mCiudades->Borrar($parametros['codigo'.$num_fila]);
							if($borraCiudades == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraCiudades;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'CIUDADES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iCiudades = new clsCiudades($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_provincia'], $parametros['buscar_zona']);
						$insertaCiudades = $iCiudades->Insertar($parametros['Nuevocodigo'.$num_fila], $parametros['Nuevonombre'.$num_fila],$parametros['Nuevopais'.$num_fila],$parametros['Nuevoprovincia'.$num_fila],$parametros['Nuevozona'.$num_fila],$parametros['Nuevovisible_hits'.$num_fila],$parametros['Nuevovisible_web'.$num_fila]);
						if($insertaCiudades == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaCiudades;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.	
							$recuperacodigo = $parametros['Nuevocodigo'.$num_fila];
							$recuperanombre = $parametros['Nuevonombre'.$num_fila];
							$recuperapais = $parametros['Nuevopais'.$num_fila];
							$recuperaprovincia = $parametros['Nuevoprovincia'.$num_fila];
							$recuperazona= $parametros['Nuevozona'.$num_fila];
							$recuperavisible_hits= $parametros['Nuevovisible_hits'.$num_fila];
							$recuperavisible_web= $parametros['Nuevovisible_web'.$num_fila];
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close(); ANTIGUO CONTROL DE LINEAS
			}

			$sCiudades = $oCiudades->Cargar();
			$sCiudadesnuevos = $oCiudades->Cargar_lineas_nuevas();
			$sComboSelectCiudades = $oCiudades->Cargar_combo_selector();

			$oCombos = new clsGeneral($conexion);
			$comboPaises = $oCombos->Cargar_combo_Paises();
			$comboPronvincias = $oCombos->Cargar_combo_Provincias();
			$comboZonas= $oCombos->Cargar_combo_Zonas();
			$comboSino = $oCombos->Cargar_combo_SiNo();

			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			$smarty = new Smarty;

			//Aquí enviamos la cabecera y el pie con el Menu general y la linea del final a la plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#010080');
			$smarty->assign('grupo', '» TABLAS GENERALES');
			$smarty->assign('subgrupo', '» GEOGRAFICAS');
			$smarty->assign('formulario', '» CIUDADES');			
			$smarty->assign('filades', $filadesde);
			$smarty->assign('ciudades', $sCiudades);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('ciudades_nuevos', $sCiudadesnuevos);
			$smarty->assign('recuperacodigo', $recuperacodigo);
			$smarty->assign('recuperanombre', $recuperanombre);
			$smarty->assign('recuperapais', $recuperapais);
			$smarty->assign('recuperaprovincia', $recuperaprovincia);
			$smarty->assign('recuperazona', $recuperazona);
			$smarty->assign('recuperavisible_hits', $recuperavisible_hits);
			$smarty->assign('recuperavisible_web', $recuperavisible_web);

			$smarty->assign('combo', $sComboSelectCiudades);
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboProvincias', $comboPronvincias);
			$smarty->assign('comboZonas', $comboZonas);
			$smarty->assign('comboSino', $comboSino);

			$smarty->assign('nombre', $nombre);
			$smarty->assign('mensaje1', $mensaje1);
			$smarty->assign('mensaje2', $mensaje2);
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_provincia', $parametros['buscar_provincia']);
			$smarty->assign('buscar_zona', $parametros['buscar_zona']);

			$smarty->display('plantillas/Ciudades.html');

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
		$parametros['buscar_provincia'] = "";
		$parametros['buscar_zona'] = "";
		$oCiudades = new clsCiudades($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_provincia'], $parametros['buscar_zona']);
		$sCiudades = $oCiudades->Cargar();
		$sCiudadesnuevos = $oCiudades->Cargar_lineas_nuevas();
		$sComboSelectCiudades = $oCiudades->Cargar_combo_selector();

		$oCombos = new clsGeneral($conexion);
		$comboPaises = $oCombos->Cargar_combo_Paises();
		$comboPronvincias = $oCombos->Cargar_combo_Provincias();
		$comboZonas= $oCombos->Cargar_combo_Zonas();
		$comboSino = $oCombos->Cargar_combo_SiNo();

		/*echo('<pre>');
		print_r($comboContinentes);
		echo('</pre>');*/

		$smarty = new Smarty;

		$smarty->assign('cabecera', 'plantillas/Cabecera.html');
		$smarty->assign('menu', 'plantillas/Menu.html');
		$smarty->assign('pie', 'plantillas/Pie.html');

		//Nombre del formulario
		$smarty->assign('color_opcion', '#010080');
		$smarty->assign('grupo', '» TABLAS GENERALES');
		$smarty->assign('subgrupo', '» GEOGRAFICAS');
		$smarty->assign('formulario', '» CIUDADES');	
		$smarty->assign('filades', $filadesde);
		$smarty->assign('ciudades', $sCiudades);

		//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('ciudades_nuevos', $sCiudadesnuevos);
		$smarty->assign('recuperacodigo', $recuperacodigo);
		$smarty->assign('recuperanombre', $recuperanombre);
		$smarty->assign('recuperapais', $recuperapais);
		$smarty->assign('recuperaprovincia', $recuperaprovincia);
		$smarty->assign('recuperazona', $recuperazona);
		$smarty->assign('recuperavisible_hits', $recuperavisible_hits);
		$smarty->assign('recuperavisible_web', $recuperavisible_web);

		$smarty->assign('combo', $sComboSelectCiudades);
		$smarty->assign('comboPaises', $comboPaises);
		$smarty->assign('comboProvincias', $comboPronvincias);
		$smarty->assign('comboZonas', $comboZonas);
		$smarty->assign('comboSino', $comboSino);

		$smarty->assign('nombre', $nombre);
		$smarty->assign('mensaje1', $mensaje1);
		$smarty->assign('mensaje2', $mensaje2);
		$smarty->assign('buscar_codigo', '');
		$smarty->assign('buscar_nombre', '');
		$smarty->assign('buscar_provincia', '');
		$smarty->assign('buscar_zona', '');

		$smarty->display('plantillas/Ciudades.html');
	}

	$conexion->close();


?>

