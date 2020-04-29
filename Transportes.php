<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/transportes.cls.php';


	session_start();

	$usuario = $_SESSION['usuario'];
	$nombre =  $_SESSION['nombre'];
	$conexion = conexion_hit();

	/*echo('<pre>');
	print_r($usuario);
	echo('-');
	print_r($nombre);
	echo('</pre>');*/

	/*echo('<pre>');
	print_r($_POST);
	echo('</pre>');*/

	$parametros = $_POST;
	$parametrosg = $_GET;


			//VARIABLES PARA LOS DATOS GENERALES
		$recuperacia = '';
		$recuperanombre = '';
		$recuperavisible = '';
		$recuperacif = '';
		$recuperalocalidad = '';
		$recuperadireccion = '';
		$recuperacodigo_postal = '';
		$recuperaprovincia = '';
		$recuperapais = '';
		$recuperatelefono = '';
		$recuperafax = '';
		$recuperamail = '';
		$recuperaidioma = '';
		$recuperaresponsable = '';
		$recuperacargo_responsable = '';
		$recuperaurl = '';
		$recuperaswift = '';
		$recuperacc_iban = '';
		$recuperanombre_banco = '';
		$recuperadireccion_banco = '';
		$recuperaemptyroute_asociado = '';
		$recuperaemptyroute_usuario = '';
		$recuperaemptyroute_pass = '';

		$mensaje1 = '';
		$mensaje2 = '';
		$error = '';
		$insertaTransportes = '';

	if(count($_POST) != 0){
		//esto es por si venimos de la pantalla acuerdos de transportes, servicios, o acuerdos de transportes
		if($parametros['buscar_cia'] == null and $parametros['buscar_nombre'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_cia'] = $parametrosg['cia'];
			}
		}	


		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'I'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LOS TRANSPORTES----------
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonTransportes = new clsTransportes($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_nombre']);
				$botonselec = $botonTransportes->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mTransportes = new clsTransportes($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_nombre']);

							$modificaTransportes = $mTransportes->Modificar($parametros['cia'.$num_fila],$parametros['nombre'.$num_fila],$parametros['visible'.$num_fila],$parametros['cif'.$num_fila],$parametros['localidad'.$num_fila],$parametros['direccion'.$num_fila],$parametros['codigo_postal'.$num_fila],$parametros['provincia'.$num_fila],$parametros['pais'.$num_fila],$parametros['telefono'.$num_fila],$parametros['fax'.$num_fila],$parametros['email'.$num_fila],$parametros['idioma'.$num_fila],$parametros['responsable'.$num_fila],$parametros['cargo_responsable'.$num_fila],$parametros['url'.$num_fila],$parametros['swift'.$num_fila],$parametros['cc_iban'.$num_fila],$parametros['nombre_banco'.$num_fila],$parametros['direccion_banco'.$num_fila], $parametros['emptyroute_asociado'.$num_fila], $parametros['emptyroute_usuario'.$num_fila], $parametros['emptyroute_pass'.$num_fila]);

							if($modificaTransportes == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaTransportes;
							}
						}else{

							$mTransportes = new clsTransportes($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_nombre']);
							$borraTransportes = $mTransportes->Borrar($parametros['cia'.$num_fila]);
							if($borraTransportes == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraTransportes;
								
							}
						}
					}
				}
				//Mostramos mensajes y posibles errores
				/*$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producciao el siguiente error: ".$error."</b></font></div>";
				}
				$num_filas->close();*/

				$mensaje1 = "<div class='mensaje-informacion'><font><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div class='mensaje-error'><font><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				$num_filas->close();


			}

			if($parametros['grabar_registro'] == 'S'){

				//AÑADIR REGISTROS
				$Ntransacciones = 0;				

				$iTransportes = new clsTransportes($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_nombre']);
				$insertaTransportes = $iTransportes->Insertar($parametros['cia0'],$parametros['nombre0'],$parametros['visible0'],$parametros['cif0'],$parametros['localidad0'],$parametros['direccion0'],$parametros['codigo_postal0'],$parametros['provincia0'],$parametros['pais0'],$parametros['telefono0'],$parametros['fax0'],$parametros['email0'],$parametros['idioma0'],$parametros['responsable0'],$parametros['cargo_responsable0'],$parametros['url0'],$parametros['swift0'],$parametros['cc_iban0'],$parametros['nombre_banco0'],$parametros['direccion_banco0'], $parametros['emptyroute_asociado0'], $parametros['emptyroute_usuario0'], $parametros['emptyroute_pass0']);

				if($insertaTransportes == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_nombre'] = $parametros['nombre0'];
				}else{
					$error = $insertaTransportes;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.

					$recuperacia = $parametros['cia0'];
					$recuperanombre = $parametros['nombre0'];
					$recuperavisible = $parametros['visible0'];
					$recuperacif = $parametros['cif0'];
					$recuperalocalidad = $parametros['localidad0'];
					$recuperadireccion = $parametros['direccion0'];
					$recuperacodigo_postal = $parametros['codigo_postal0'];
					$recuperaprovincia = $parametros['provincia0'];
					$recuperapais = $parametros['pais0'];
					$recuperatelefono = $parametros['telefono0'];
					$recuperafax = $parametros['fax0'];
					$recuperamail = $parametros['email0'];
					$recuperaidioma = $parametros['idioma0'];
					$recuperaresponsable = $parametros['responsable0'];
					$recuperacargo_responsable = $parametros['cargo_responsable0'];
					$recuperaurl = $parametros['url0'];
					$recuperaswift = $parametros['swift0'];
					$recuperacc_iban = $parametros['cc_iban0'];
					$recuperanombre_banco = $parametros['nombre_banco0'];
					$recuperadireccion_banco = $parametros['direccion_banco0'];
					$recuperaemptyroute_asociado = $parametros['emptyroute_asociado0'];
					$recuperaemptyroute_usuario = $parametros['emptyroute_usuario0'];
					$recuperaemptyroute_pass = $parametros['emptyroute_pass0'];
				}


				//Mostramos mensajes y posibles errores
				/*$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producciao el siguiente error: ".$error."</b></font></div>";
				}*/

				$mensaje1 = "<div class='mensaje-informacion'><font><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div class='mensaje-error'><font><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}

				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}

			if(@$parametros['nuevo_registro'] == 'S'){
				$mensaje1 = "<div class='mensaje-atencion'><font><b>Teclee los datos de la compañía y pulsa grabar.</b></font></div>";
			}

			//Llamada a la clase especifica de la pantalla
			$oTransportes = new clsTransportes($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_nombre']);
			$sTransportes = $oTransportes->Cargar($recuperacia,$recuperanombre,$recuperavisible,$recuperacif,$recuperalocalidad,$recuperadireccion,$recuperacodigo_postal,$recuperaprovincia,
				$recuperapais,$recuperatelefono,$recuperafax,$recuperamail,$recuperaidioma,$recuperaresponsable,$recuperacargo_responsable,
				$recuperaurl,$recuperaswift,$recuperacc_iban,$recuperanombre_banco,$recuperadireccion_banco, $recuperaemptyroute_asociado, $recuperaemptyroute_usuario, $recuperaemptyroute_pass);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectTransportes = $oTransportes->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboTransportes = $oSino->Cargar_combo_Transportes();
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboSino_No = $oSino->Cargar_combo_SiNo_No();
			$comboProvincias = $oSino->Cargar_combo_Provincias();
			$comboPaises = $oSino->Cargar_combo_Paises();
			$comboIdiomas = $oSino->Cargar_combo_Idiomas();
			$comboCargos = $oSino->Cargar_combo_Cargos();

	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS TRANSPORTES-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» TRANSPORTES');
			$smarty->assign('formulario', '» COMPAÑIAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTransportes);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('transportes', $sTransportes);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTransportes', $comboTransportes);
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboSino_No', $comboSino_No);
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboIdiomas', $comboIdiomas);
			$smarty->assign('comboCargos', $comboCargos);

			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_cia', $parametros['buscar_cia']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Transportes.html');


		}elseif($parametros['ir_a'] == 'I'){

			header("Location: Transportes_imagenes.php?cia=".$parametros['cia0']);

		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS TRANSPORTES

		$filadesde = 1;
		if(count($_GET) != 0 and $parametrosg['cia'] != ''){
			$parametros['buscar_cia'] = $parametrosg['cia'];
		}else{
			$parametros['buscar_cia'] = "";
		}
		$parametros['buscar_nombre'] = "";


			//Llamada a la clase especifica de la pantalla
			$oTransportes = new clsTransportes($conexion, $filadesde, $usuario, $parametros['buscar_cia'], $parametros['buscar_nombre']);
			$sTransportes = $oTransportes->Cargar($recuperacia,$recuperanombre,$recuperavisible,$recuperacif,$recuperalocalidad,$recuperadireccion,$recuperacodigo_postal,$recuperaprovincia,
				$recuperapais,$recuperatelefono,$recuperafax,$recuperamail,$recuperaidioma,$recuperaresponsable,$recuperacargo_responsable,
				$recuperaurl,$recuperaswift,$recuperacc_iban,$recuperanombre_banco,$recuperadireccion_banco, $recuperaemptyroute_asociado, $recuperaemptyroute_usuario, $recuperaemptyroute_pass);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectTransportes = $oTransportes->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboTransportes = $oSino->Cargar_combo_Transportes();
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboSino_No = $oSino->Cargar_combo_SiNo_No();
			$comboProvincias = $oSino->Cargar_combo_Provincias();
			$comboPaises = $oSino->Cargar_combo_Paises();
			$comboIdiomas = $oSino->Cargar_combo_Idiomas();
			$comboCargos = $oSino->Cargar_combo_Cargos();;

	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS TRANSPORTES-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» TRANSPORTES');
			$smarty->assign('formulario', '» COMPAÑIAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectTransportes);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('transportes', $sTransportes);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboTransportes', $comboTransportes);
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboSino_No', $comboSino_No);
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboIdiomas', $comboIdiomas);
			$smarty->assign('comboCargos', $comboCargos);

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_cia', '');
		$smarty->assign('buscar_nombre', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Transportes.html');
	}

	$conexion->close();


?>

