<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/oficinas.cls.php';


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
			$recuperaoficina = '';
			$recuperacif = '';
			$recuperacic = '';
			$recuperavisible = '';
			$recuperadireccion = '';
			$recuperalocalidad = '';
			$recuperacodigo_postal = '';
			$recuperaprovincia = '';
			$recuperapais = '';
			$recuperatelefono = '';
			$recuperafax = '';
			$recuperamail = '';
			$recuperadelegacion = '';
			$recuperaresponsable = '';
			$recuperacargo_responsable = '';
			$recuperausuario_web = '';
			$recuperapassword_web = '';
			$recuperasituacion = '';
			$recuperaobservaciones = '';
			$recuperanombre_fiscal = '';
			$recuperadireccion_fiscal = '';
			$recuperalocalidad_fiscal = '';
			$recuperacodigo_postal_fiscal = '';
			$recuperaprovincia_fiscal = '';
			$recuperapais_fiscal = '';
			$recuperaswift = '';
			$recuperacc_iban = '';
			$recuperanombre_banco = '';
			$recuperadireccion_banco = '';
			$recuperamail_contabilidad = '';
			$recuperafacturacion = '';

			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			$insertaOficinas = '';

	
	if(count($_POST) != 0){
		//esto es por si venimos de la pantalla de listado de minoristas desde grupos de gestion
		if($parametros['buscar_codigo'] == null and $parametros['buscar_nombre'] == null and $parametros['buscar_oficina'] == null and $parametros['buscar_telefono'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_codigo'] = $parametrosg['id'];
				$parametros['buscar_oficina'] = $parametrosg['oficina'];
			}
		}	


		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'I'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LAS MINORISTAS----------
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonOficinas = new clsOficinas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_oficina'], $parametros['buscar_telefono'], $parametros['buscar_email']);
				$botonselec = $botonOficinas->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'OFICINAS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mOficinas = new clsOficinas($conexion, $filadesde, $usuario,$parametros['buscar_codigo'],$parametros['buscar_nombre'],$parametros['buscar_oficina'], $parametros['buscar_telefono'], $parametros['buscar_email']);
							$modificaOficinas = $mOficinas->Modificar($parametros['id'.$num_fila],$parametros['oficina'.$num_fila],$parametros['cif'.$num_fila],$parametros['cic'.$num_fila],$parametros['visible'.$num_fila],$parametros['direccion'.$num_fila],$parametros['localidad'.$num_fila],$parametros['codigo_postal'.$num_fila],$parametros['provincia'.$num_fila],$parametros['pais'.$num_fila],$parametros['telefono'.$num_fila],$parametros['fax'.$num_fila],$parametros['mail'.$num_fila],$parametros['delegacion'.$num_fila],$parametros['responsable'.$num_fila],$parametros['cargo_responsable'.$num_fila],$parametros['usuario_web'.$num_fila],$parametros['password_web'.$num_fila],$parametros['situacion'.$num_fila],$parametros['observaciones'.$num_fila],$parametros['nombre_fiscal'.$num_fila],$parametros['direccion_fiscal'.$num_fila],$parametros['localidad_fiscal'.$num_fila],$parametros['codigo_postal_fiscal'.$num_fila],$parametros['provincia_fiscal'.$num_fila],$parametros['pais_fiscal'.$num_fila],$parametros['swift'.$num_fila],$parametros['cc_iban'.$num_fila],$parametros['nombre_banco'.$num_fila],$parametros['direccion_banco'.$num_fila],$parametros['mail_contabilidad'.$num_fila],$parametros['facturacion'.$num_fila]);

							if($modificaOficinas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaOficinas;
							}
						}else{

							$mOficinas = new clsOficinas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_oficina'], $parametros['buscar_telefono'], $parametros['buscar_email']);
							$borraOficinas = $mOficinas->Borrar($parametros['id'.$num_fila],$parametros['oficina'.$num_fila]);
							if($borraOficinas == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraOficinas;
								
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
				//$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_GESTION' AND USUARIO = '".$usuario."'");
				//$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				$Ntransacciones = 0;				

				$iOficinas = new clsOficinas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_oficina'], $parametros['buscar_telefono'], $parametros['buscar_email']);
				$insertaOficinas = $iOficinas->Insertar($parametros['id0'],$parametros['oficina0'],$parametros['cif0'],$parametros['cic0'],$parametros['visible0'],$parametros['direccion0'],$parametros['localidad0'],$parametros['codigo_postal0'],$parametros['provincia0'],$parametros['pais0'],$parametros['telefono0'],$parametros['fax0'],$parametros['mail0'],$parametros['delegacion0'],$parametros['responsable0'],$parametros['cargo_responsable0'],$parametros['usuario_web0'],$parametros['password_web0'],$parametros['situacion0'],$parametros['observaciones0'],$parametros['nombre_fiscal0'],$parametros['direccion_fiscal0'],$parametros['localidad_fiscal0'],$parametros['codigo_postal_fiscal0'],$parametros['provincia_fiscal0'],$parametros['pais_fiscal0'],$parametros['swift0'],$parametros['cc_iban0'],$parametros['nombre_banco0'],$parametros['direccion_banco0'],$parametros['mail_contabilidad0'],$parametros['facturacion0']);

				if($insertaOficinas == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_nombre'] = $parametros['nombre0'];
				}else{
					$error = $insertaOficinas;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					//$recuperaid = $parametros['id'.$num_fila];
					$recuperaid = $parametros['id0'];
					$recuperanombre = $parametros['nombre0'];
					$recuperaoficina = $parametros['oficina0'];
					$recuperacif = $parametros['cif0'];
					$recuperacic = $parametros['cic0'];
					$recuperavisible = $parametros['visible0'];
					$recuperadireccion = $parametros['direccion0'];
					$recuperalocalidad = $parametros['localidad0'];
					$recuperacodigo_postal = $parametros['codigo_postal0'];
					$recuperaprovincia = $parametros['provincia0'];
					$recuperapais = $parametros['pais0'];
					$recuperatelefono = $parametros['telefono0'];
					$recuperafax = $parametros['fax0'];
					$recuperamail = $parametros['mail0'];
					$recuperadelegacion = $parametros['delegacion0'];
					$recuperaresponsable = $parametros['responsable0'];
					$recuperacargo_responsable = $parametros['cargo_responsable0'];
					$recuperausuario_web = $parametros['usuario_web0'];
					$recuperapassword_web = $parametros['password_web0'];
					$recuperasituacion = $parametros['situacion0'];
					$recuperaobservaciones = $parametros['observaciones0'];
					$recuperanombre_fiscal = $parametros['nombre_fiscal0'];
					$recuperadireccion_fiscal = $parametros['direccion_fiscal0'];
					$recuperalocalidad_fiscal = $parametros['localidad_fiscal0'];
					$recuperacodigo_postal_fiscal = $parametros['codigo_postal_fiscal0'];
					$recuperaprovincia_fiscal = $parametros['provincia_fiscal0'];
					$recuperapais_fiscal = $parametros['pais_fiscal0'];
					$recuperaswift = $parametros['swift0'];
					$recuperacc_iban = $parametros['cc_iban0'];
					$recuperanombre_banco = $parametros['nombre_banco0'];
					$recuperadireccion_banco = $parametros['direccion_banco0'];
					$recuperamail_contabilidad = $parametros['mail_contabilidad0'];
					$recuperafacturacion = $parametros['facturacion0'];
					//$nuevoregistro = 'S';
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
			$oOficinas = new clsOficinas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_oficina'], $parametros['buscar_telefono'], $parametros['buscar_email']);
			$sOficinas = $oOficinas->Cargar($recuperaid,$recuperanombre,$recuperaoficina,$recuperacif,$recuperacic,$recuperavisible,$recuperadireccion,$recuperalocalidad,$recuperacodigo_postal,$recuperaprovincia,$recuperapais,$recuperatelefono,$recuperafax,$recuperamail,$recuperadelegacion,$recuperaresponsable,$recuperacargo_responsable,$recuperausuario_web,$recuperapassword_web,$recuperasituacion,$recuperaobservaciones,$recuperanombre_fiscal,$recuperadireccion_fiscal,$recuperalocalidad_fiscal,$recuperacodigo_postal_fiscal,$recuperaprovincia_fiscal,$recuperapais_fiscal,$recuperaswift,$recuperacc_iban,$recuperanombre_banco,$recuperadireccion_banco,$recuperamail_contabilidad,$recuperafacturacion);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectOficinas = $oOficinas->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboProvincias = $oSino->Cargar_combo_Provincias();
			$comboCargos = $oSino->Cargar_combo_Cargos();
			$comboPaises = $oSino->Cargar_combo_Paises();
			$comboSituacion_venta = $oSino->Cargar_combo_Situacion_venta();
			$comboDelegaciones = $oSino->Cargar_combo_Delegaciones();
			$comboModo_facturacion = $oSino->Cargar_Combo_Buscar_Modo_Facturacion();

	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LAS MINORISTAS-------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#1450A9');
			$smarty->assign('grupo', '» CLIENTES');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» OFICINAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectOficinas);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('oficinas', $sOficinas);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboCargos', $comboCargos);
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboSituacion_venta', $comboSituacion_venta);
			$smarty->assign('comboDelegaciones', $comboDelegaciones);
			$smarty->assign('comboModo_facturacion', $comboModo_facturacion);


			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_oficina', $parametros['buscar_oficina']);
			$smarty->assign('buscar_telefono', $parametros['buscar_telefono']);
			$smarty->assign('buscar_email', $parametros['buscar_email']);

			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Oficinas.html');


		}
		elseif($parametros['ir_a'] == 'M'){

			header("Location: Paises.php?buscar='ES'");

		}elseif($parametros['ir_a'] == 'I'){

			header("Location: Minoristas.php?id=".$parametros['id0']);

		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LAS MINORISTAS

		$filadesde = 1;
		if(count($_GET) != 0 and $parametrosg['id'] != '' and $parametrosg['oficina'] != ''){
			$parametros['buscar_codigo'] = $parametrosg['id'];
			$parametros['buscar_oficina'] = $parametrosg['oficina'];
		}else{
			$parametros['buscar_codigo'] = "";
			$parametros['buscar_oficina'] = "";
		}
		$parametros['buscar_nombre'] = "";
		$parametros['buscar_telefono'] = "";
		$parametros['buscar_email'] = "";


			//Llamada a la clase especifica de la pantalla
			$oOficinas = new clsOficinas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_oficina'], $parametros['buscar_telefono'], $parametros['buscar_email']);
			$sOficinas = $oOficinas->Cargar($recuperaid,$recuperanombre,$recuperaoficina,$recuperacif,$recuperacic,$recuperavisible,$recuperadireccion,$recuperalocalidad,$recuperacodigo_postal,$recuperaprovincia,$recuperapais,$recuperatelefono,$recuperafax,$recuperamail,$recuperadelegacion,$recuperaresponsable,$recuperacargo_responsable,$recuperausuario_web,$recuperapassword_web,$recuperasituacion,$recuperaobservaciones,$recuperanombre_fiscal,$recuperadireccion_fiscal,$recuperalocalidad_fiscal,$recuperacodigo_postal_fiscal,$recuperaprovincia_fiscal,$recuperapais_fiscal,$recuperaswift,$recuperacc_iban,$recuperanombre_banco,$recuperadireccion_banco,$recuperamail_contabilidad,$recuperafacturacion);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectOficinas = $oOficinas->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboProvincias = $oSino->Cargar_combo_Provincias();
			$comboCargos = $oSino->Cargar_combo_Cargos();
			$comboPaises = $oSino->Cargar_combo_Paises();
			$comboSituacion_venta = $oSino->Cargar_combo_Situacion_venta();
			$comboDelegaciones = $oSino->Cargar_combo_Delegaciones();
			$comboModo_facturacion = $oSino->Cargar_Combo_Buscar_Modo_Facturacion();

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
			$smarty->assign('color_opcion', '#1450A9');
			$smarty->assign('grupo', '» CLIENTES');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» OFICINAS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectOficinas);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('oficinas', $sOficinas);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboCargos', $comboCargos);
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboSituacion_venta', $comboSituacion_venta);
			$smarty->assign('comboDelegaciones', $comboDelegaciones);
			$smarty->assign('comboModo_facturacion', $comboModo_facturacion);

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_codigo', '');
		$smarty->assign('buscar_nombre', '');
		$smarty->assign('buscar_oficina', '');
		$smarty->assign('buscar_telefono', '');
		$smarty->assign('buscar_email', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Oficinas.html');
	}

	$conexion->close();


?>

