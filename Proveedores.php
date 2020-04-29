<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/proveedores.cls.php';


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
		$recuperaid = '';
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
		$recuperacaracteristicas = '';
		$recuperaidioma = '';
		$recuperaresponsable = '';
		$recuperacargo_responsable = '';
		$recuperaurl = '';
		$recuperaswift = '';
		$recuperacc_iban = '';
		$recuperanombre_banco = '';
		$recuperadireccion_banco = '';

		$mensaje1 = '';
		$mensaje2 = '';
		$error = '';
		$insertaProveedores = '';

	if(count($_POST) != 0){
		//esto es por si venimos de la pantalla acuerdos de proveedores, servicios, o acuerdos de transportes
		if($parametros['buscar_id'] == null and $parametros['buscar_nombre'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_id'] = $parametrosg['id'];
			}
		}	


		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'I'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LOS PROVEEDORES----------
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonProveedores = new clsProveedores($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre']);
				$botonselec = $botonProveedores->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PROVEEDORES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mProveedores = new clsProveedores($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre']);

							$modificaProveedores = $mProveedores->Modificar($parametros['id'.$num_fila],$parametros['nombre'.$num_fila],$parametros['visible'.$num_fila],$parametros['cif'.$num_fila],$parametros['localidad'.$num_fila],$parametros['direccion'.$num_fila],$parametros['codigo_postal'.$num_fila],$parametros['provincia'.$num_fila],$parametros['pais'.$num_fila],$parametros['telefono'.$num_fila],$parametros['fax'.$num_fila],$parametros['email'.$num_fila],$parametros['caracteristicas'.$num_fila],$parametros['idioma'.$num_fila],$parametros['responsable'.$num_fila],$parametros['cargo_responsable'.$num_fila],$parametros['url'.$num_fila],$parametros['swift'.$num_fila],$parametros['cc_iban'.$num_fila],$parametros['nombre_banco'.$num_fila],$parametros['direccion_banco'.$num_fila]);

							if($modificaProveedores == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaProveedores;
							}
						}else{

							$mProveedores = new clsProveedores($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre']);
							$borraProveedores = $mProveedores->Borrar($parametros['id'.$num_fila]);
							if($borraProveedores == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraProveedores;
								
							}
						}
					}
				}
				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();
			}

			if($parametros['grabar_registro'] == 'S'){

				//AÑADIR REGISTROS
				$Ntransacciones = 0;				

				$iProveedores = new clsProveedores($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre']);
				$insertaProveedores = $iProveedores->Insertar($parametros['nombre0'],$parametros['visible0'],$parametros['cif0'],$parametros['localidad0'],$parametros['direccion0'],$parametros['codigo_postal0'],$parametros['provincia0'],$parametros['pais0'],$parametros['telefono0'],$parametros['fax0'],$parametros['email0'],$parametros['caracteristicas0'],$parametros['idioma0'],$parametros['responsable0'],$parametros['cargo_responsable0'],$parametros['url0'],$parametros['swift0'],$parametros['cc_iban0'],$parametros['nombre_banco0'],$parametros['direccion_banco0']);

				if($insertaProveedores == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_nombre'] = $parametros['nombre0'];
				}else{
					$error = $insertaProveedores;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.

					$recuperaid = $parametros['id0'];
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
					$recuperacaracteristicas = $parametros['caracteristicas0'];
					$recuperaidioma = $parametros['idioma0'];
					$recuperaresponsable = $parametros['responsable0'];
					$recuperacargo_responsable = $parametros['cargo_responsable0'];
					$recuperaurl = $parametros['url0'];
					$recuperaswift = $parametros['swift0'];
					$recuperacc_iban = $parametros['cc_iban0'];
					$recuperanombre_banco = $parametros['nombre_banco0'];
					$recuperadireccion_banco = $parametros['direccion_banco0'];
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
			$oProveedores = new clsProveedores($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre']);
			$sProveedores = $oProveedores->Cargar($recuperaid,$recuperanombre,$recuperavisible,$recuperacif,$recuperalocalidad,$recuperadireccion,$recuperacodigo_postal,$recuperaprovincia,
				$recuperapais,$recuperatelefono,$recuperafax,$recuperamail,$recuperacaracteristicas,$recuperaidioma,$recuperaresponsable,$recuperacargo_responsable,
				$recuperaurl,$recuperaswift,$recuperacc_iban,$recuperanombre_banco,$recuperadireccion_banco);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectProveedores = $oProveedores->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboProveedores = $oSino->Cargar_combo_Proveedores();
			$comboSino = $oSino->Cargar_combo_SiNo();
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
			//----VISUALIZAR PARTE DE LOS PROVEEDORES-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» SERVICIOS');
			$smarty->assign('formulario', '» PROVEEDORES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectProveedores);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('proveedores', $sProveedores);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboProveedores', $comboProveedores);
			$smarty->assign('comboSino', $comboSino);
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
			$smarty->assign('buscar_id', $parametros['buscar_id']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Proveedores.html');


		}elseif($parametros['ir_a'] == 'I'){

			header("Location: Proveedores_imagenes.php?id=".$parametros['id0']);

		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS PROVEEDORES

		$filadesde = 1;
		if(count($_GET) != 0 and $parametrosg['id'] != ''){
			$parametros['buscar_id'] = $parametrosg['id'];
		}else{
			$parametros['buscar_id'] = "";
		}
		$parametros['buscar_nombre'] = "";


			//Llamada a la clase especifica de la pantalla
			$oProveedores = new clsProveedores($conexion, $filadesde, $usuario, $parametros['buscar_id'], $parametros['buscar_nombre']);
			$sProveedores = $oProveedores->Cargar($recuperaid,$recuperanombre,$recuperavisible,$recuperacif,$recuperalocalidad,$recuperadireccion,$recuperacodigo_postal,$recuperaprovincia,
				$recuperapais,$recuperatelefono,$recuperafax,$recuperamail,$recuperacaracteristicas,$recuperaidioma,$recuperaresponsable,$recuperacargo_responsable,
				$recuperaurl,$recuperaswift,$recuperacc_iban,$recuperanombre_banco,$recuperadireccion_banco);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectProveedores = $oProveedores->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboProveedores = $oSino->Cargar_combo_Proveedores();
			$comboSino = $oSino->Cargar_combo_SiNo();
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
			//----VISUALIZAR PARTE DE LOS PROVEEDORES-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» SERVICIOS');
			$smarty->assign('formulario', '» PROVEEDORES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectProveedores);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('proveedores', $sProveedores);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboProveedores', $comboProveedores);
			$smarty->assign('comboSino', $comboSino);
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
		$smarty->assign('buscar_id', '');
		$smarty->assign('buscar_nombre', '');
		$smarty->assign('buscar_ciudad', '');
		$smarty->assign('buscar_categoria', '');
		$smarty->assign('buscar_situacion', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Proveedores.html');
	}

	$conexion->close();


?>

