<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/provincias.cls.php';


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
	$insertaProvincias = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacodigo = '';
	$recuperanombre = '';
	$recuperacodigo_postal = '';
	$recuperacomunidad = '';
	$recuperapais = '';
	$recuperaimpuesto = '';
	$recuperavisible_hits = '';
	$recuperavisible_web = '';
	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];

			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonProvincias = new clsProvincias($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_visible_web']);
				$botonselec = $botonProvincias->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oProvincias = new clsProvincias($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_visible_web']);
			$sProvincias = $oProvincias->Cargar();
			//lineas visualizadas
			$vueltas = count($sProvincias);;

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PROVINCIAS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mProvincias = new clsProvincias($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_visible_web']);
							$modificaProvincias = $mProvincias->Modificar($parametros['codigo'.$num_fila], $parametros['nombre'.$num_fila],$parametros['codigo_postal'.$num_fila],$parametros['comunidad'.$num_fila],$parametros['pais'.$num_fila],$parametros['impuesto'.$num_fila],$parametros['visible_hits'.$num_fila],$parametros['visible_web'.$num_fila]);
							if($modificaProvincias == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaProvincias;
								
							}
						}else{

							$mProvincias = new clsProvincias($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_visible_web']);
							$borraProvincias = $mProvincias->Borrar($parametros['codigo'.$num_fila]);
							if($borraProvincias == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraProvincias;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PROVINCIAS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iProvincias = new clsProvincias($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_visible_web']);
						$insertaProvincias = $iProvincias->Insertar($parametros['Nuevocodigo'.$num_fila], $parametros['Nuevonombre'.$num_fila],$parametros['Nuevocodigo_postal'.$num_fila],$parametros['Nuevocomunidad'.$num_fila],$parametros['Nuevopais'.$num_fila],$parametros['Nuevoimpuesto'.$num_fila], $parametros['Nuevovisible_hits'.$num_fila],$parametros['Nuevovisible_web'.$num_fila]);
						if($insertaProvincias == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaProvincias;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacodigo = $parametros['Nuevocodigo'.$num_fila];
							$recuperanombre = $parametros['Nuevonombre'.$num_fila];
							$recuperacodigo_postal = $parametros['Nuevocodigo_postal'.$num_fila];
							$recuperacomunidad = $parametros['Nuevocomunidad'.$num_fila];
							$recuperapais = $parametros['Nuevopais'.$num_fila];
							$recuperaimpuesto = $parametros['Nuevoimpuesto'.$num_fila];
							$recuperavisible_hits = $parametros['Nuevovisible_hits'.$num_fila];
							$recuperavisible_web = $parametros['Nuevovisible_web'.$num_fila];
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


			$sProvincias = $oProvincias->Cargar();
			$sProvinciasnuevos = $oProvincias->Cargar_lineas_nuevas();
			$sComboSelectProvincias = $oProvincias->Cargar_combo_selector();

			$oCombo = new clsGeneral($conexion);
			$comboComunidades = $oCombo->Cargar_combo_Comunidades();
			$comboPaises = $oCombo->Cargar_combo_Paises();
			$comboImpuestos = $oCombo->Cargar_combo_Impuestos();
			$comboSino = $oCombo->Cargar_combo_SiNo();


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
			$smarty->assign('formulario', '» PROVINCIAS');		
			$smarty->assign('filades', $filadesde);
			$smarty->assign('provincias', $sProvincias);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('provincias_nuevos', $sProvinciasnuevos);
			$smarty->assign('recuperacodigo', $recuperacodigo);
			$smarty->assign('recuperanombre', $recuperanombre);
			$smarty->assign('recuperacodigo_postal', $recuperacodigo_postal);
			$smarty->assign('recuperacomunidad', $recuperacomunidad);
			$smarty->assign('recuperapais', $recuperapais);
			$smarty->assign('recuperaimpuesto', $recuperaimpuesto);
			$smarty->assign('recuperavisible_hits', $recuperavisible_hits);
			$smarty->assign('recuperavisible_web', $recuperavisible_web);

			$smarty->assign('combo', $sComboSelectProvincias);

			$smarty->assign('comboComunidades', $comboComunidades);
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboImpuestos', $comboImpuestos);
			$smarty->assign('comboSino', $comboSino);

			$smarty->assign('nombre', $nombre);
			$smarty->assign('mensaje1', $mensaje1);
			$smarty->assign('mensaje2', $mensaje2);
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_pais', $parametros['buscar_pais']);
			$smarty->assign('buscar_visible_web', $parametros['buscar_visible_web']);

			$smarty->display('plantillas/Provincias.html');

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
		$parametros['buscar_pais'] = "";
		$parametros['buscar_visible_web'] = "";
		$oProvincias = new clsProvincias($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_visible_web']);
		$sProvincias = $oProvincias->Cargar();
		$sProvinciasnuevos = $oProvincias->Cargar_lineas_nuevas();
		$sComboSelectProvincias = $oProvincias->Cargar_combo_selector();

		$oCombo = new clsGeneral($conexion);
		$comboComunidades = $oCombo->Cargar_combo_Comunidades();
		$comboPaises = $oCombo->Cargar_combo_Paises();
		$comboImpuestos = $oCombo->Cargar_combo_Impuestos();
		$comboSino = $oCombo->Cargar_combo_SiNo();

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
		$smarty->assign('formulario', '» PROVINCIAS');	
		$smarty->assign('filades', $filadesde);
		$smarty->assign('provincias', $sProvincias);

		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('provincias_nuevos', $sProvinciasnuevos);
		$smarty->assign('recuperacodigo', $recuperacodigo);
		$smarty->assign('recuperanombre', $recuperanombre);
		$smarty->assign('recuperacodigo_postal', $recuperacodigo_postal);
		$smarty->assign('recuperacomunidad', $recuperacomunidad);
		$smarty->assign('recuperapais', $recuperapais);
		$smarty->assign('recuperaimpuesto', $recuperaimpuesto);
		$smarty->assign('recuperavisible_hits', $recuperavisible_hits);
		$smarty->assign('recuperavisible_web', $recuperavisible_web);

		$smarty->assign('combo', $sComboSelectProvincias);
		$smarty->assign('comboComunidades', $comboComunidades);
		$smarty->assign('comboPaises', $comboPaises);
		$smarty->assign('comboImpuestos', $comboImpuestos);
		$smarty->assign('comboSino', $comboSino);

		$smarty->assign('nombre', $nombre);
		$smarty->assign('mensaje1', $mensaje1);
		$smarty->assign('mensaje2', $mensaje2);
		$smarty->assign('buscar_codigo', '');
		$smarty->assign('buscar_nombre', '');
		$smarty->assign('buscar_pais', '');
		$smarty->assign('buscar_visible_web', '');


		$smarty->display('plantillas/Provincias.html');
	}

	$conexion->close();


?>

