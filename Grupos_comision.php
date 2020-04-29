<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/grupos_comision.cls.php';


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
			$recuperacodigo = '';
			$recuperanombre = '';
	
			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			$insertaGrupo_comision = '';

			//VARIABLES PARA LAS COMISIONES
			$recuperafecha_desde = '';
			$recuperafecha_hasta = '';
			$recuperaproducto = '';
			$recuperafolleto = '';
			$recuperacuadro = '';
			$recuperapaquete = '';
			$recuperacomision_paquetes = '';
			$recuperacomision_alojamientos = '';
			$recuperacomision_transportes = '';
			$recuperacomision_servicios = '';
			$mensaje1_comisiones = '';
			$mensaje2_comisiones = '';
			$error_comisiones = '';


	
	if(count($_POST) != 0){

		if($parametros['buscar_grupo_comision'] != null){
			$codigo = $parametros['buscar_grupo_comision'];
		}else{
			$codigo = $parametros['buscar_codigo'];
		}

		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'I'){

			//----------------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LOS GRUPOS DE COMISION----------
			//----------------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonGrupos_gestion = new clsGrupos_comision($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
				$botonselec = $botonGrupos_gestion->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISION' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mGrupos_comision = new clsGrupos_comision($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
							$modificaGrupos_comision = $mGrupos_comision->Modificar($parametros['codigo'.$num_fila],$parametros['nombre'.$num_fila]);
							if($modificaGrupos_comision == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaGrupos_comision;
								
							}
						}else{

							$mGrupos_comision = new clsGrupos_comision($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
							$borraGrupos_comision = $mGrupos_comision->Borrar($parametros['codigo'.$num_fila]);
							if($borraGrupos_comision == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraGrupos_comision;
								
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

				$iGrupos_comision = new clsGrupos_comision($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
				$insertaGrupos_comision = $iGrupos_comision->Insertar($parametros['codigo0'], $parametros['nombre0']);

				if($insertaGrupos_comision == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_nombre'] = $parametros['nombre0'];
				}else{
					$error = $insertaGrupos_comision;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					$recuperacodigo = $parametros['codigo'.$num_fila];
					$recuperanombre = $parametros['nombre0'];
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
			$oGrupos_comision = new clsGrupos_comision($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
			$sGrupos_comision = $oGrupos_comision->Cargar($recuperacodigo,$recuperanombre);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectsGrupos_comision = $oGrupos_comision->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			/*$comboSino = $oSino->Cargar_combo_SiNo();*/
			$comboGrupos_comision = $oSino->Cargar_combo_Grupos_comision();


	//----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA LAS COMISIONES DE LOS GRUPOS DE COMISION---------------
	//----------------------------------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_comisiones = $parametros['filadesde_comisiones'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonGrupos_comision_comisiones = new clsGrupos_comision($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
				$botonselec_comisiones = $botonGrupos_comision_comisiones->Botones_selector_comisiones($filadesde_comisiones, $parametros['botonSelector_2nivel'],$sGrupos_comision[0]['codigo'],$parametros['buscar_producto']);
				$filadesde_comisiones = $botonselec_comisiones;
			}

			//Llamada a la clase especifica de la pantalla
			$sGrupos_comision_comisiones = $oGrupos_comision->Cargar_comisiones($sGrupos_comision[0]['codigo'],$filadesde_comisiones,$parametros['buscar_producto']);	
			//lineas visualizadas
			$vueltas = count($oGrupos_comision);

			if($parametros['actuar_2nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISION_COMISIONES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_comisiones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							
							$mGrupos_comision_comisiones = new clsGrupos_comision($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
							$modificaGrupos_comision_comisiones = $mGrupos_comision_comisiones->Modificar_comisiones($sGrupos_comision[0]['codigo'], $parametros['fecha_desde'.$num_fila],$parametros['fecha_hasta'.$num_fila],$parametros['producto'.$num_fila],$parametros['folleto'.$num_fila],$parametros['cuadro'.$num_fila],$parametros['paquete'.$num_fila],$parametros['comision_paquetes'.$num_fila],$parametros['comision_alojamientos'.$num_fila],$parametros['comision_transportes'.$num_fila],$parametros['comision_servicios'.$num_fila]);
							if($modificaGrupos_comision_comisiones == 'OK'){
								$Ntransacciones_comisiones++;
							}else{
								$error_comisiones = $modificaGrupos_comision_comisiones;
							}

							/*echo ($sGrupos_gestion[0]['id']." - ".$parametros['fecha_desde'.$num_fila]." - ".$parametros['fecha_hasta'.$num_fila]." - ".$parametros['producto'.$num_fila]." - ".$parametros['folleto'.$num_fila]." - ".$parametros['cuadro'.$num_fila]." - ".$parametros['paquete'.$num_fila]." - ".$parametros['comision_paquetes'.$num_fila]." - ".$parametros['comision_alojamientos'.$num_fila]." - ".$parametros['comision_transportes'.$num_fila]." - ".$parametros['comision_servicios'.$num_fila]);*/



						}else{

							$mGrupos_comision_comisiones = new clsGrupos_comision($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
							$borraGrupos_comision_comisiones = $mGrupos_comision_comisiones->Borrar_comisiones($sGrupos_comision[0]['codigo'], $parametros['fecha_desde'.$num_fila],$parametros['fecha_hasta'.$num_fila],$parametros['producto'.$num_fila],$parametros['folleto'.$num_fila],$parametros['cuadro'.$num_fila],$parametros['paquete'.$num_fila]);
							if($borraGrupos_comision_comisiones == 'OK'){
								$Ntransacciones_comisiones++;
							}else{
								$error_comisiones = $borraGrupos_comision_comisiones;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISION_COMISIONES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){
						
						$iGrupos_comision_comisiones = new clsGrupos_comision($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
						$insertaGrupos_gestion_comisiones = $iGrupos_comision_comisiones->Insertar_comisiones($sGrupos_comision[0]['codigo'], $parametros['Nuevofecha_desde'.$num_fila], $parametros['Nuevofecha_hasta'.$num_fila],$parametros['Nuevoproducto'.$num_fila],$parametros['Nuevofolleto'.$num_fila],$parametros['Nuevocuadro'.$num_fila],$parametros['Nuevopaquete'.$num_fila],$parametros['Nuevocomision_paquetes'.$num_fila],$parametros['Nuevocomision_alojamientos'.$num_fila],$parametros['Nuevocomision_transportes'.$num_fila],$parametros['Nuevocomision_servicios'.$num_fila]);
						if($insertaGrupos_gestion_comisiones == 'OK'){
							$Ntransacciones_comisiones++;
						}else{
							$error_comisiones = $insertaGrupos_gestion_comisiones;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperafecha_desde = $parametros['Nuevofecha_desde'.$num_fila]; 
							$recuperafecha_hasta = $parametros['Nuevofecha_hasta'.$num_fila]; 
							$recuperaproducto = $parametros['Nuevoproducto'.$num_fila]; 
							$recuperafolleto = $parametros['Nuevofolleto'.$num_fila]; ;
							$recuperacuadro = $parametros['Nuevocuadro'.$num_fila]; 
							$recuperapaquete = $parametros['Nuevopaquete'.$num_fila]; 
							$recuperacomision_paquetes = $parametros['Nuevocomision_paquetes'.$num_fila]; 
							$recuperacomision_alojamientos = $parametros['Nuevocomision_alojamientos'.$num_fila]; 
							$recuperacomision_transportes = $parametros['Nuevocomision_transportes'.$num_fila]; 
							$recuperacomision_servicios = $parametros['Nuevocomision_servicios'.$num_fila]; 
						}
					}
				}

				//Llamada a la clase especifica de la pantalla
				$sGrupos_comision_comisiones = $oGrupos_comision->Cargar_comisiones($sGrupos_comision[0]['codigo'],$filadesde_comisiones,$parametros['buscar_producto']);

				//Mostramos mensajes y posibles errores
				$mensaje1_comisiones = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_comisiones."</b></font></div>";
				if($error_comisiones != ''){
					$mensaje2_comisiones = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_comisiones."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}


			//Llamada a la clase especifica de la pantalla
	
			$sComboSelectGrupos_comision_comisiones = $oGrupos_comision->Cargar_combo_selector_comisiones($sGrupos_comision[0]['codigo'],$parametros['buscar_producto']);
			$sGrupos_comision_comisiones_nuevos = $oGrupos_comision->Cargar_lineas_nuevas_comisiones();	
			//Llamada a la clase general de combos
			$comboProductos = $oSino->Cargar_combo_Productos();

			/*echo('<pre>');
			print_r($sComboSelectGrupos_gestion_comisiones);
			echo('</pre>');*/



	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//-----------------------------------------------------
			//----VISUALIZAR PARTE DE LOS GRUPOS DE COMISION-------
			//-----------------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#1450A9');
			$smarty->assign('grupo', '» CLIENTES');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» GRUPOS DE COMISION');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectsGrupos_comision);

			//Cargar combos de las linea de busqueda
			$smarty->assign('comboGrupos_comision', $comboGrupos_comision);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('grupos_comision', $sGrupos_comision);

			//Cargar combos de las lineas de la tabla
			/*$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboCargos', $comboCargos);*/

			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_grupo_comision', $parametros['buscar_grupo_comision']);

			//---------------------------------------------
			//----VISUALIZAR PARTE DE COMISIONES-----------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_comisiones', $filadesde_comisiones);
		

			//Cargar combo selector
			$smarty->assign('combo_comisiones', $sComboSelectGrupos_comision_comisiones);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('grupos_Comision_comisiones', $sGrupos_comision_comisiones);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboProductos', $comboProductos);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('grupos_Comision_comisionesnuevos', $sGrupos_comision_comisiones_nuevos);			
			$smarty->assign('recuperafecha_desde', $recuperafecha_desde);	
			$smarty->assign('recuperafecha_hasta', $recuperafecha_hasta);	
			$smarty->assign('recuperaproducto', $recuperaproducto);	
			$smarty->assign('recuperafolleto', $recuperafolleto);	
			$smarty->assign('recuperacuadro', $recuperacuadro);	
			$smarty->assign('recuperapaquete', $recuperapaquete);	
			$smarty->assign('recuperacomision_paquetes', $recuperacomision_paquetes);	
			$smarty->assign('recuperacomision_alojamientos', $recuperacomision_alojamientos);
			$smarty->assign('recuperacomision_transportes', $recuperacomision_transportes);
			$smarty->assign('recuperacomision_servicios', $recuperacomision_servicios);


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_comisiones', $mensaje1_comisiones);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_comisiones', $mensaje2_comisiones);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_producto', $parametros['buscar_producto']);

			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Grupos_comision.html');


		}
		/*elseif($parametros['ir_a'] == 'I'){

			header("Location: Grupos_gestion.php?grupo=".$parametros['grupo_gestion0']);

		}*/
		else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LOS GRUPOS DE COMISION

		$filadesde = 1;
		if(count($_GET) != 0 and $parametrosg['codigo'] != ''){
			$codigo = $parametrosg['id'];
			$parametrosg['grupo'] = "";
		}else{
			$codigo = "";
		}
		$parametros['buscar_nombre'] = "";

			//Llamada a la clase especifica de la pantalla
			$oGrupos_comision = new clsGrupos_comision($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
			$sGrupos_comision = $oGrupos_comision->Cargar($recuperacodigo,$recuperanombre);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectsGrupos_comision = $oGrupos_comision->Cargar_combo_selector();


			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			/*$comboSino = $oSino->Cargar_combo_SiNo();*/
			$comboGrupos_comision = $oSino->Cargar_combo_Grupos_comision();


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
			$smarty->assign('formulario', '» GRUPOS DE COMISION');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectsGrupos_comision);

			//Cargar combos de las linea de busqueda
			$smarty->assign('comboGrupos_comision', $comboGrupos_comision);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('grupos_comision', $sGrupos_comision);

			//Cargar combos de las lineas de la tabla
			/*$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboCargos', $comboCargos);*/

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_codigo', '');
		$smarty->assign('buscar_nombre', '');
		$smarty->assign('buscar_grupo_comision', '');

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE COMISIONES

		$filadesde_comisiones = 1;
		$parametros['buscar_producto'] = "";


		//Llamada a la clase especifica de la pantalla
			$sGrupos_comision_comisiones = $oGrupos_comision->Cargar_comisiones($sGrupos_comision[0]['codigo'],$filadesde_comisiones,$parametros['buscar_producto']);			
			$sComboSelectGrupos_comision_comisiones = $oGrupos_comision->Cargar_combo_selector_comisiones($sGrupos_comision[0]['codigo'],$parametros['buscar_producto']);
			$sGrupos_comision_comisiones_nuevos = $oGrupos_comision->Cargar_lineas_nuevas_comisiones();		

			//Llamada a la clase general de combos
			$comboProductos = $oSino->Cargar_combo_Productos();

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_comisiones', $filadesde_comisiones);
		

			//Cargar combo selector
			$smarty->assign('combo_comisiones', $sComboSelectGrupos_comision_comisiones);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('grupos_Comision_comisiones', $sGrupos_comision_comisiones);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboProductos', $comboProductos);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('grupos_Comision_comisionesnuevos', $sGrupos_comision_comisiones_nuevos);			
			$smarty->assign('recuperafecha_desde', $recuperafecha_desde);	
			$smarty->assign('recuperafecha_hasta', $recuperafecha_hasta);	
			$smarty->assign('recuperaproducto', $recuperaproducto);	
			$smarty->assign('recuperafolleto', $recuperafolleto);	
			$smarty->assign('recuperacuadro', $recuperacuadro);	
			$smarty->assign('recuperapaquete', $recuperapaquete);	
			$smarty->assign('recuperacomision_paquetes', $recuperacomision_paquetes);	
			$smarty->assign('recuperacomision_alojamientos', $recuperacomision_alojamientos);
			$smarty->assign('recuperacomision_transportes', $recuperacomision_transportes);
			$smarty->assign('recuperacomision_servicios', $recuperacomision_servicios);


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_comisiones', $mensaje1_comisiones);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_comisiones', $mensaje2_comisiones);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_producto', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Grupos_comision.html');
	}

	$conexion->close();


?>

