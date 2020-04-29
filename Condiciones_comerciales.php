<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/condiciones_comerciales.cls.php';


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
	$insertaCondiciones_comerciales = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacodigo = '';
	$recuperanombre = '';
	$recuperavisible = '';
	$recuperatipo = '';
	$recuperagrupo = '';
	$recuperaorden_calculo = '';
	$recuperadescripcion = '';
	
	if(count($_POST) != 0){

		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonCondiciones_comerciales = new clsCondiciones_comerciales($conexion, $filadesde, $usuario);
				$botonselec = $botonCondiciones_comerciales->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oCondiciones_comerciales = new clsCondiciones_comerciales($conexion, $filadesde, $usuario);
			$sCondiciones_comerciales = $oCondiciones_comerciales->Cargar();
			//lineas visualizadas
			$vueltas = count($sCondiciones_comerciales);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONDICIONES_COMERCIALES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mCondiciones_comerciales = new clsCondiciones_comerciales($conexion, $filadesde, $usuario);
							$modificaCondiciones_comerciales = $mCondiciones_comerciales->Modificar($parametros['codigo'.$num_fila], $parametros['nombre'.$num_fila], $parametros['visible'.$num_fila], $parametros['tipo'.$num_fila], $parametros['grupo'.$num_fila], $parametros['orden_calculo'.$num_fila], $parametros['descripcion'.$num_fila]);
							if($modificaCondiciones_comerciales == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaCondiciones_comerciales;
								
							}
						}else{

							$mCondiciones_comerciales = new clsCondiciones_comerciales($conexion, $filadesde, $usuario);
							$borraCondiciones_comerciales = $mCondiciones_comerciales->Borrar($parametros['codigo'.$num_fila]);
							if($borraCondiciones_comerciales == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraCondiciones_comerciales;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'CONDICIONES_COMERCIALES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){

						ECHO($parametros['Nuevocodigo'.$num_fila].'-'.$parametros['Nuevonombre'.$num_fila].'-'.$parametros['Nuevovisible'.$num_fila].'-'. $parametros['descripcion'.$num_fila]);

						$iCondiciones_comerciales = new clsCondiciones_comerciales($conexion, $filadesde, $usuario);
						$insertaCondiciones_comerciales = $iCondiciones_comerciales->Insertar($parametros['Nuevocodigo'.$num_fila], $parametros['Nuevonombre'.$num_fila], $parametros['Nuevovisible'.$num_fila], $parametros['Nuevotipo'.$num_fila], $parametros['Nuevogrupo'.$num_fila], $parametros['Nuevoorden_calculo'.$num_fila], $parametros['Nuevodescripcion'.$num_fila]);
						if($insertaCondiciones_comerciales == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaCondiciones_comerciales;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacodigo = $parametros['Nuevocodigo'.$num_fila]; 
							$recuperanombre = $parametros['Nuevonombre'.$num_fila];
							$recuperavisible = $parametros['Nuevovisible'.$num_fila];
							$recuperatipo = $parametros['Nuevotipo'.$num_fila];
							$recuperavisible = $parametros['Nuevogrupo'.$num_fila];
							$recuperaorden_calculo = $parametros['Nuevoorden_calculo'.$num_fila];
							$recuperadescripcion = $parametros['Nuevodescripcion'.$num_fila];
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

			$sCondiciones_comerciales = $oCondiciones_comerciales->Cargar();
			$sCondiciones_comercialesnuevos = $oCondiciones_comerciales->Cargar_lineas_nuevas();
			$sComboSelectCondiciones_comerciales = $oCondiciones_comerciales->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboTipo = $oSino->Cargar_Combo_Tipo_Condiciones_Comerciales();
			$comboGrupo = $oSino->Cargar_Combo_Grupo_Condiciones_Comerciales();

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#30A1C7');
			$smarty->assign('grupo', '» PRODUCTO');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» CONDICIONES COMERCIALES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectCondiciones_comerciales);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('condiciones_comerciales', $sCondiciones_comerciales);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboTipo', $comboTipo);
			$smarty->assign('comboGrupo', $comboGrupo);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('condiciones_comerciales_nuevos', $sCondiciones_comercialesnuevos);			
			$smarty->assign('recuperacodigo', $recuperacodigo);	
			$smarty->assign('recuperanombre', $recuperanombre);	
			$smarty->assign('recuperavisible', $recuperavisible);	
			$smarty->assign('recuperatipo', $recuperatipo);
			$smarty->assign('recuperagrupo', $recuperagrupo);
			$smarty->assign('recuperaorden_calculo', $recuperaorden_calculo);
			$smarty->assign('recuperadescripcion', $recuperadescripcion);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);


			//Visualizar plantilla
			$smarty->display('plantillas/Condiciones_comerciales.html');


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
		$producto = "";

			//Llamada a la clase especifica de la pantalla
			$oCondiciones_comerciales = new clsCondiciones_comerciales($conexion, $filadesde, $usuario);
			$sCondiciones_comerciales = $oCondiciones_comerciales->Cargar();
			$sCondiciones_comercialesnuevos = $oCondiciones_comerciales->Cargar_lineas_nuevas();
			$sComboSelectCondiciones_comerciales = $oCondiciones_comerciales->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboTipo = $oSino->Cargar_Combo_Tipo_Condiciones_Comerciales();
			$comboGrupo = $oSino->Cargar_Combo_Grupo_Condiciones_Comerciales();

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#30A1C7');
			$smarty->assign('grupo', '» PRODUCTO');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» CONDICIONES COMERCIALES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectCondiciones_comerciales);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('condiciones_comerciales', $sCondiciones_comerciales);


			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboTipo', $comboTipo);
			$smarty->assign('comboGrupo', $comboGrupo);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('condiciones_comerciales_nuevos', $sCondiciones_comercialesnuevos);			
			$smarty->assign('recuperacodigo', $recuperacodigo);	
			$smarty->assign('recuperanombre', $recuperanombre);	
			$smarty->assign('recuperavisible', $recuperavisible);	
			$smarty->assign('recuperatipo', $recuperatipo);
			$smarty->assign('recuperagrupo', $recuperagrupo);
			$smarty->assign('recuperaorden_calculo', $recuperaorden_calculo);
			$smarty->assign('recuperadescripcion', $recuperadescripcion);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);


		//Visualizar plantilla
		$smarty->display('plantillas/Condiciones_comerciales.html');
	}

	$conexion->close();


?>

