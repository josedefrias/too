<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/grupos_gestion.cls.php';


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
			$recuperanombre = '';
			$recuperacif = '';
			$recuperavisible = '';
			$recuperacic = '';
			$recuperadomicilio = '';
			$recuperaciudad = '';
			$recuperacodigo_postal = '';
			$recuperaprovincia = '';
			$recuperapais = '';
			$recuperatelefono = '';
			$recuperafax = '';
			$recuperamail = '';
			$recuperaresponsable = '';
			$recuperacargo_responsable = '';
			$recuperagrupo_comision = '';	
			$recuperaenviar_ofertas = '';		

			$mensaje1 = '';
			$mensaje2 = '';
			$error = '';
			$insertaGrupos_gestion = '';

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


		//esto es por si venimos de la pantalla de listado de minoristas desde grupos de gestion
		if($parametros['buscar_grupo_gestion'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_grupo_gestion'] = $parametrosg['grupo'];
			}
		}	

		if($parametros['buscar_grupo_gestion'] != null){
			$codigo = $parametros['buscar_grupo_gestion'];
		}else{
			$codigo = $parametros['buscar_codigo'];
		}




		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'M'){

			//--------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LOS GRUPOS DE GESTION---
			//--------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonGrupos_gestion = new clsGrupos_gestion($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
				$botonselec = $botonGrupos_gestion->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_GESTION' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mGrupos_gestion = new clsGrupos_gestion($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
							$modificaGrupos_gestion = $mGrupos_gestion->Modificar($parametros['id'.$num_fila],$parametros['nombre'.$num_fila], $parametros['cif'.$num_fila],$parametros['visible'.$num_fila],$parametros['cic'.$num_fila],$parametros['domicilio'.$num_fila],$parametros['ciudad'.$num_fila],$parametros['codigo_postal'.$num_fila],$parametros['provincia'.$num_fila],$parametros['pais'.$num_fila],$parametros['telefono'.$num_fila],$parametros['fax'.$num_fila],$parametros['mail'.$num_fila],$parametros['responsable'.$num_fila],$parametros['cargo_responsable'.$num_fila],$parametros['grupo_comision'.$num_fila],$parametros['enviar_ofertas'.$num_fila]);
							if($modificaGrupos_gestion == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaGrupos_gestion;
								
							}
						}else{

							$mGrupos_gestion = new clsGrupos_gestion($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
							$borraGrupos_gestion = $mGrupos_gestion->Borrar($parametros['id'.$num_fila]);
							if($borraGrupos_gestion == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraGrupos_gestion;
								
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
						
				$iGrupos_gestion = new clsGrupos_gestion($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
				$insertaGrupos_gestion = $iGrupos_gestion->Insertar($parametros['nombre0'], $parametros['cif0'],$parametros['visible0'],$parametros['cic0'],$parametros['domicilio0'],$parametros['ciudad0'],$parametros['codigo_postal0'],$parametros['provincia0'],$parametros['pais0'],$parametros['telefono0'],$parametros['fax0'],$parametros['mail0'],$parametros['responsable0'],$parametros['cargo_responsable0'],$parametros['grupo_comision0'],$parametros['enviar_ofertas0']);
				if($insertaGrupos_gestion == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_nombre'] = $parametros['nombre0'];
				}else{
					$error = $insertaGrupos_gestion;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					//$recuperaid = $parametros['id'.$num_fila];
					$recuperanombre = $parametros['nombre0'];
					$recuperacif = $parametros['cif0'];
					$recuperavisible = $parametros['visible0'];
					$recuperacic = $parametros['cic0'];
					$recuperadomicilio = $parametros['domicilio0'];
					$recuperaciudad = $parametros['ciudad0'];
					$recuperacodigo_postal = $parametros['codigo_postal0'];
					$recuperaprovincia = $parametros['provincia0'];
					$recuperapais = $parametros['pais0'];
					$recuperatelefono = $parametros['telefono0'];
					$recuperafax = $parametros['fax0'];
					$recuperamail = $parametros['mail0'];
					$recuperaresponsable = $parametros['responsable0'];
					$recuperacargo_responsable = $parametros['cargo_responsable0'];
					$recuperagrupo_comision = $parametros['grupo_comision0'];
					$recuperaenviar_ofertas = $parametros['enviar_ofertas0'];
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
			$oGrupos_gestion = new clsGrupos_gestion($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
			$sGrupos_gestion = $oGrupos_gestion->Cargar($recuperanombre,$recuperacif,$recuperavisible,$recuperacic,$recuperadomicilio,$recuperaciudad,$recuperacodigo_postal,	$recuperaprovincia,$recuperapais,$recuperatelefono,$recuperafax,$recuperamail,$recuperaresponsable,$recuperacargo_responsable,$recuperagrupo_comision, $recuperaenviar_ofertas);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectGrupos_gestion = $oGrupos_gestion->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboProvincias = $oSino->Cargar_combo_Provincias();
			$comboCargos = $oSino->Cargar_combo_Cargos();
			$comboPaises = $oSino->Cargar_combo_Paises();
			$comboGrupos_comision = $oSino->Cargar_combo_Grupos_comision();
			$comboGrupos_gestion = $oSino->Cargar_combo_Grupos_gestion();

	//--------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA LAS COMISIONES DE LOS GRUPOS DE GESTION--------
	//--------------------------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_comisiones = $parametros['filadesde_comisiones'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonGrupos_gestion_comisiones = new clsGrupos_gestion($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
				$botonselec_comisiones = $botonGrupos_gestion_comisiones->Botones_selector_comisiones($filadesde_comisiones, $parametros['botonSelector_2nivel'],$sGrupos_gestion[0]['id'],$parametros['buscar_producto']);
				$filadesde_comisiones = $botonselec_comisiones;
			}

			//Llamada a la clase especifica de la pantalla
			$sGrupos_gestion_comisiones = $oGrupos_gestion->Cargar_comisiones($sGrupos_gestion[0]['id'],$filadesde_comisiones,$parametros['buscar_producto']);	
			//lineas visualizadas
			$vueltas = count($sGrupos_gestion_comisiones);

			if($parametros['actuar_2nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISIONES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_comisiones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							
							$mGrupos_gestion_comisiones = new clsGrupos_gestion($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
							$modificaGrupos_gestion_comisiones = $mGrupos_gestion_comisiones->Modificar_comisiones($sGrupos_gestion[0]['id'], $parametros['fecha_desde'.$num_fila],$parametros['fecha_hasta'.$num_fila],$parametros['producto'.$num_fila],$parametros['folleto'.$num_fila],$parametros['cuadro'.$num_fila],$parametros['paquete'.$num_fila],$parametros['comision_paquetes'.$num_fila],$parametros['comision_alojamientos'.$num_fila],$parametros['comision_transportes'.$num_fila],$parametros['comision_servicios'.$num_fila]);
							if($modificaGrupos_gestion_comisiones == 'OK'){
								$Ntransacciones_comisiones++;
							}else{
								$error_comisiones = $modificaGrupos_gestion_comisiones;
								
							}

							/*echo ($sGrupos_gestion[0]['id']." - ".$parametros['fecha_desde'.$num_fila]." - ".$parametros['fecha_hasta'.$num_fila]." - ".$parametros['producto'.$num_fila]." - ".$parametros['folleto'.$num_fila]." - ".$parametros['cuadro'.$num_fila]." - ".$parametros['paquete'.$num_fila]." - ".$parametros['comision_paquetes'.$num_fila]." - ".$parametros['comision_alojamientos'.$num_fila]." - ".$parametros['comision_transportes'.$num_fila]." - ".$parametros['comision_servicios'.$num_fila]);*/



						}else{

							$mGrupos_gestion_comisiones = new clsGrupos_gestion($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
							$borraGrupos_gestion_comisiones = $mGrupos_gestion_comisiones->Borrar_comisiones($sGrupos_gestion[0]['id'], $parametros['fecha_desde'.$num_fila],$parametros['fecha_hasta'.$num_fila],$parametros['producto'.$num_fila],$parametros['folleto'.$num_fila],$parametros['cuadro'.$num_fila],$parametros['paquete'.$num_fila]);
							if($borraGrupos_gestion_comisiones == 'OK'){
								$Ntransacciones_comisiones++;
							}else{
								$error_comisiones = $borraGrupos_gestion_comisiones;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_COMISIONES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){
						
						$iGrupos_gestion_comisiones = new clsGrupos_gestion($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
						$insertaGrupos_gestion_comisiones = $iGrupos_gestion_comisiones->Insertar_comisiones($sGrupos_gestion[0]['id'], $parametros['Nuevofecha_desde'.$num_fila], $parametros['Nuevofecha_hasta'.$num_fila],$parametros['Nuevoproducto'.$num_fila],$parametros['Nuevofolleto'.$num_fila],$parametros['Nuevocuadro'.$num_fila],$parametros['Nuevopaquete'.$num_fila],$parametros['Nuevocomision_paquetes'.$num_fila],$parametros['Nuevocomision_alojamientos'.$num_fila],$parametros['Nuevocomision_transportes'.$num_fila],$parametros['Nuevocomision_servicios'.$num_fila]);
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
				$sGrupos_gestion_comisiones = $oGrupos_gestion->Cargar_comisiones($sGrupos_gestion[0]['id'],$filadesde_comisiones,$parametros['buscar_producto']);	


				//Mostramos mensajes y posibles errores
				$mensaje1_comisiones = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_comisiones."</b></font></div>";
				if($error_comisiones != ''){
					$mensaje2_comisiones = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_comisiones."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}


			//Llamada a la clase especifica de la pantalla
		
			$sComboSelectGrupos_gestion_comisiones = $oGrupos_gestion->Cargar_combo_selector_comisiones($sGrupos_gestion[0]['id'],$parametros['buscar_producto']);
			$sGrupos_gestion_comisiones_nuevos = $oGrupos_gestion->Cargar_lineas_nuevas_comisiones();	
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


			//---------------------------------------------
			//----VISUALIZAR PARTE DE GRUPOS DE GESTION----
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#1450A9');
			$smarty->assign('grupo', '» CLIENTES');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» GRUPOS DE GESTION');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectGrupos_gestion);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('grupos_gestion', $sGrupos_gestion);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboCargos', $comboCargos);
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboGrupos_comision', $comboGrupos_comision);
			$smarty->assign('comboGrupos_gestion', $comboGrupos_gestion);


			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_grupo_gestion', $parametros['buscar_grupo_gestion']);

			//---------------------------------------------
			//----VISUALIZAR PARTE DE COMISIONES-----------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_comisiones', $filadesde_comisiones);
		

			//Cargar combo selector
			$smarty->assign('combo_comisiones', $sComboSelectGrupos_gestion_comisiones);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('grupos_gestion_comisiones', $sGrupos_gestion_comisiones);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboProductos', $comboProductos);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('grupos_gestion_comisionesnuevos', $sGrupos_gestion_comisiones_nuevos);			
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
			$smarty->display('plantillas/Grupos_gestion.html');


		}
		elseif($parametros['ir_a'] == 'M'){

			//$smarty = new Smarty;
			//$smarty->assign('mensaje', ' ');
			//$smarty->display('plantillas/Paises.php');
			//$_SESSION['buscar'] = 'ES';
			//header("Location: Grupos_Minoristas.php?grupo='".$parametros['id'.$num_fila]."'");
			header("Location: Grupos_Minoristas.php?grupo=".$parametros['id0']."&nombre=".$parametros['nombre0']);

		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE GRUPOS DE GESTION

		$filadesde = 1;
		if(count($_GET) != 0 and $parametrosg['grupo'] != ''){
			$codigo = $parametrosg['grupo'];
			$parametrosg['grupo'] = "";
		}else{
			$codigo = "";
		}
		$parametros['buscar_nombre'] = "";
		

			//Llamada a la clase especifica de la pantalla
			$oGrupos_gestion = new clsGrupos_gestion($conexion, $filadesde, $usuario, $codigo, $parametros['buscar_nombre']);
			$sGrupos_gestion = $oGrupos_gestion->Cargar($recuperanombre,$recuperacif,$recuperavisible,$recuperacic,$recuperadomicilio,$recuperaciudad,$recuperacodigo_postal,	$recuperaprovincia,$recuperapais,$recuperatelefono,$recuperafax,$recuperamail,$recuperaresponsable,$recuperacargo_responsable,$recuperagrupo_comision, $recuperaenviar_ofertas);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectGrupos_gestion = $oGrupos_gestion->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboProvincias = $oSino->Cargar_combo_Provincias();
			$comboCargos = $oSino->Cargar_combo_Cargos();
			$comboPaises = $oSino->Cargar_combo_Paises();
			$comboGrupos_comision = $oSino->Cargar_combo_Grupos_comision();
			$comboGrupos_gestion = $oSino->Cargar_combo_Grupos_gestion();


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//Cargamos cabecera y pie de plantilla
			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#1450A9');
			$smarty->assign('grupo', '» CLIENTES');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» GRUPOS DE GESTION');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectGrupos_gestion);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('grupos_gestion', $sGrupos_gestion);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboCargos', $comboCargos);
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboGrupos_comision', $comboGrupos_comision);
			$smarty->assign('comboGrupos_gestion', $comboGrupos_gestion);

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_codigo', '');
		$smarty->assign('buscar_nombre', '');
		$smarty->assign('buscar_grupo', '');

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE COMISIONES

		$filadesde_comisiones = 1;
		$parametros['buscar_producto'] = "";

		//Llamada a la clase especifica de la pantalla
		$sGrupos_gestion_comisiones = $oGrupos_gestion->Cargar_comisiones($sGrupos_gestion[0]['id'],$filadesde_comisiones,$parametros['buscar_producto']);			
		$sComboSelectGrupos_gestion_comisiones = $oGrupos_gestion->Cargar_combo_selector_comisiones($sGrupos_gestion[0]['id'],$parametros['buscar_producto']);
		$sGrupos_gestion_comisiones_nuevos = $oGrupos_gestion->Cargar_lineas_nuevas_comisiones();	

			//Llamada a la clase general de combos
			$comboProductos = $oSino->Cargar_combo_Productos();

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_comisiones', $filadesde_comisiones);

			//Cargar combo selector
			$smarty->assign('combo_comisiones', $sComboSelectGrupos_gestion_comisiones);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('grupos_gestion_comisiones', $sGrupos_gestion_comisiones);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboProductos', $comboProductos);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('grupos_gestion_comisionesnuevos', $sGrupos_gestion_comisiones_nuevos);			
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
		$smarty->display('plantillas/Grupos_gestion.html');
	}

	$conexion->close();


?>

