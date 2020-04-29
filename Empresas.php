<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/empresas.cls.php';


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
	$insertaEmpresas = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacodigo = '';
	$recuperanombre = '';
	$recuperatipo = '';
	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonEmpresas = new clsEmpresas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
				$botonselec = $botonEmpresas->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oEmpresas = new clsEmpresas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
			$sEmpresas = $oEmpresas->Cargar();
			//lineas visualizadas
			$vueltas = count($sEmpresas);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'EMPRESAS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas -1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mEmpresas = new clsEmpresas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
							$modificaEmpresas = $mEmpresas->Modificar($parametros['codigo'.$num_fila], $parametros['nombre'.$num_fila], $parametros['tipo'.$num_fila]);
							if($modificaEmpresas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaEmpresas;
								
							}
						}else{

							$mEmpresas = new clsEmpresas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
							$borraEmpresas = $mEmpresas->Borrar($parametros['codigo'.$num_fila]);
							if($borraEmpresas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraEmpresas;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'EMPRESAS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iEmpresas = new clsEmpresas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
						$insertaEmpresas = $iEmpresas->Insertar($parametros['Nuevocodigo'.$num_fila], $parametros['Nuevonombre'.$num_fila], $parametros['Nuevotipo'.$num_fila]);
						if($insertaEmpresas == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaEmpresas;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacodigo = $parametros['Nuevocodigo'.$num_fila]; 
							$recuperanombre = $parametros['Nuevonombre'.$num_fila];
							$recuperatipo = $parametros['Nuevotipo'.$num_fila];

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
			$sEmpresas = $oEmpresas->Cargar();
			$sEmpresasnuevos = $oEmpresas->Cargar_lineas_nuevas();
			$sComboSelectEmpresas = $oEmpresas->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboTipo = $oSino->Cargar_combo_Tipo_Empresa();			
			
			/*echo('<pre>');
			print_r($comboEmpresas);
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
			$smarty->assign('subgrupo', '» INTERNAS');
			$smarty->assign('formulario', '» EMPRESAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectEmpresas);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('empresas', $sEmpresas);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTipo', $comboTipo);			

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('empresas_nuevos', $sEmpresasnuevos);	
			$smarty->assign('recuperacodigo', $recuperacodigo);
			$smarty->assign('recuperanombre', $recuperanombre);
			$smarty->assign('recuperatipo', $recuperatipo);


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);

			//Visualizar plantilla
			$smarty->display('plantillas/Empresas.html');


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
		$oEmpresas = new clsEmpresas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
		$sEmpresas = $oEmpresas->Cargar();
		$sEmpresasnuevos = $oEmpresas->Cargar_lineas_nuevas();
		$sComboSelectEmpresas = $oEmpresas->Cargar_combo_selector();

		//Llamada a la clase general de combos
		$oSino = new clsGeneral($conexion);
		$comboTipo = $oSino->Cargar_combo_Tipo_Empresa();			
		
		/*echo('<pre>');
		print_r($comboEmpresas);
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
		$smarty->assign('subgrupo', '» INTERNAS');
		$smarty->assign('formulario', '» EMPRESAS');


		//Nombre del usuario de la sesion
		$smarty->assign('nombre', $nombre);

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades', $filadesde);

		//Cargar combo selector
		$smarty->assign('combo', $sComboSelectEmpresas);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('empresas', $sEmpresas);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboTipo', $comboTipo);
		
		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('empresas_nuevos', $sEmpresasnuevos);			
		$smarty->assign('recuperacodigo', $recuperacodigo);	
		$smarty->assign('recuperanombre', $recuperanombre);	
		$smarty->assign('recuperatipo', $recuperatipo);


		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1', $mensaje1);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_codigo', '');
		$smarty->assign('buscar_nombre', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Empresas.html');
	}

	$conexion->close();


?>

