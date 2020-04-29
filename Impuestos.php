<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/impuestos.cls.php';


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
			$insertaPeriodos = '';

			//VARIABLES PARA LAS COMISIONES
			$recuperafecha_desde = '';
			$recuperafecha_hasta = '';
			$recuperavalor = '';
			$mensaje1_periodos = '';
			$mensaje2_periodos = '';
	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'I'){

			//----------------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LOS IMPUESTOS----------
			//-------------------------------------------------------------------------------------
			
			//Cuando para buscar solo hay un combo necesitaos un hidden para modificar el valor de usqueda en caso de nuevo registro
			if($parametros['buscar_codigo2']== 'NUEVO'){
				$parametros['buscar_codigo'] = 'XX';
			}


			$filadesde = $parametros['filadesde'];

			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonImpuestos = new clsImpuestos($conexion, $filadesde, $usuario, $parametros['buscar_codigo']);
				$botonselec = $botonImpuestos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'IMPUESTOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$mImpuestos = new clsImpuestos($conexion, $filadesde, $usuario, $parametros['buscar_codigo']);
							$modificaImpuestos = $mImpuestos->Modificar($parametros['codigo'.$num_fila],$parametros['nombre'.$num_fila]);
							if($modificaImpuestos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaImpuestos;
								
							}
						}else{

							$mImpuestos = new clsImpuestos($conexion, $filadesde, $usuario, $parametros['buscar_codigo']);
							$borraImpuestos = $mImpuestos->Borrar($parametros['codigo'.$num_fila]);
							if($borraImpuestos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraImpuestos;
								
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

				$iImpuestos = new clsImpuestos($conexion, $filadesde, $usuario, $parametros['buscar_codigo']);
				$insertaImpuestos = $iImpuestos->Insertar($parametros['codigo0'], $parametros['nombre0']);

				if($insertaImpuestos == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_nombre'] = $parametros['nombre0'];
				}else{
					$error = $insertaImpuestos;
					//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
					$recuperacodigo = $parametros['codigo0'];
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
			$oImpuestos = new clsImpuestos($conexion, $filadesde, $usuario, $parametros['buscar_codigo']);
			$sImpuestos = $oImpuestos->Cargar($recuperacodigo,$recuperanombre);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectImpuestos = $oImpuestos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboImpuestos = $oSino->Cargar_combo_Impuestos();
			//$comboProvincias = $oSino->Cargar_combo_Provincias();*/


	//----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA LOS PERIODOS DE LOS IMPUESTOS--------------------------
	//----------------------------------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_periodos = $parametros['filadesde_periodos'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonImpuestos_periodos = new clsImpuestos($conexion, $filadesde, $usuario, $parametros['buscar_codigo']);
				$botonselec_periodos = $botonImpuestos_periodos->Botones_selector_periodos($filadesde_periodos, $parametros['botonSelector_2nivel'],$sImpuestos[0]['codigo']);
				$filadesde_periodos = $botonselec_periodos;
			}

			//Llamada a la clase especifica de la pantalla
			$sImpuestos_periodos = $oImpuestos->Cargar_periodos($sImpuestos[0]['codigo'],$filadesde_periodos);		
			//lineas visualizadas
			$vueltas = count($sImpuestos_periodos);

			if($parametros['actuar_2nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'IMPUESTOS_PERIODOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_periodos = 0;
				$error_periodos = '';

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							
							$mImpuestos_periodos = new clsImpuestos($conexion, $filadesde, $usuario, $parametros['buscar_codigo']);
							$modificaImpuestos_periodos = $mImpuestos_periodos->Modificar_periodos($sImpuestos[0]['codigo'], $parametros['fecha_desde'.$num_fila],$parametros['fecha_hasta'.$num_fila],$parametros['valor'.$num_fila]);
							if($modificaImpuestos_periodos == 'OK'){
								$Ntransacciones_periodos++;
							}else{
								$error_periodos = $modificaImpuestos_periodos;
							}

							/*echo ($sGrupos_gestion[0]['id']." - ".$parametros['fecha_desde'.$num_fila]." - ".$parametros['fecha_hasta'.$num_fila]." - ".$parametros['producto'.$num_fila]." - ".$parametros['folleto'.$num_fila]." - ".$parametros['cuadro'.$num_fila]." - ".$parametros['paquete'.$num_fila]." - ".$parametros['comision_paquetes'.$num_fila]." - ".$parametros['comision_alojamientos'.$num_fila]." - ".$parametros['comision_transportes'.$num_fila]." - ".$parametros['comision_servicios'.$num_fila]);*/



						}else{

							$mImpuestos_periodos = new clsImpuestos($conexion, $filadesde, $usuario, $parametros['buscar_codigo']);
							$borraImpuestos_periodos = $mImpuestos_periodos->Borrar_periodos($sImpuestos[0]['codigo'], $parametros['fecha_desde'.$num_fila],$parametros['fecha_hasta'.$num_fila]);
							if($borraImpuestos_periodos == 'OK'){
								$Ntransacciones_periodos++;
							}else{
								$error_periodos = $borraImpuestos_periodos;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'IMPUESTOS_PERIODOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){
						
						$iImpuestos_periodos = new clsImpuestos($conexion, $filadesde, $usuario, $parametros['buscar_codigo']);
						$insertaImpuestos_periodos = $iImpuestos_periodos->Insertar_periodos($sImpuestos[0]['codigo'], $parametros['Nuevofecha_desde'.$num_fila], $parametros['Nuevofecha_hasta'.$num_fila],$parametros['Nuevovalor'.$num_fila]);
						if($insertaImpuestos_periodos == 'OK'){
							$Ntransacciones_periodos++;
						}else{
							$error_periodos = $insertaImpuestos_periodos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperafecha_desde = $parametros['Nuevofecha_desde'.$num_fila]; 
							$recuperafecha_hasta = $parametros['Nuevofecha_hasta'.$num_fila]; 
							$recuperaproducto = $parametros['valor'.$num_fila]; 
						}
					}
				}


				//Mostramos mensajes y posibles errores
				$mensaje1_periodos = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_periodos."</b></font></div>";
				if($error_periodos != ''){
					$mensaje2_periodos = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_periodos."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}


			//Llamada a la clase especifica de la pantalla

			$sComboSelectImpuestos_periodos = $oImpuestos->Cargar_combo_selector_periodos($sImpuestos[0]['codigo']);
			$sImpuestos_periodos_nuevos = $oImpuestos->Cargar_lineas_nuevas_periodos();	
			//Llamada a la clase general de combos
			//$comboProductos = $oSino->Cargar_combo_Productos();

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
			//----VISUALIZAR PARTE DE LOS IMPUESTOS-------
			//-----------------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#1450A9');
			$smarty->assign('grupo', '» CLIENTES');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» IMPUESTOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectImpuestos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('impuestos', $sImpuestos);

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

			//---------------------------------------------
			//----VISUALIZAR PARTE DE PERIODOS-----------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_periodos', $filadesde_periodos);
		

			//Cargar combo selector
			$smarty->assign('combo_periodos', $sComboSelectImpuestos_periodos);
			//Cargar combo de busqueda
			$smarty->assign('comboImpuestos', $comboImpuestos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('impuestos_periodos', $sImpuestos_periodos);

			//Cargar combos de las lineas de la tabla
			//$smarty->assign('comboProductos', $comboProductos);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('impuestos_periodosnuevos', $sImpuestos_periodos_nuevos);			
			$smarty->assign('recuperafecha_desde', $recuperafecha_desde);	
			$smarty->assign('recuperafecha_hasta', $recuperafecha_hasta);	
			$smarty->assign('recuperavalor', $recuperavalor);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_periodos', $mensaje1_periodos);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_periodos', $mensaje2_periodos);

			//Cargar campos de busqueda de la tabla tecleados por el usuario


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Impuestos.html');


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
			$parametros['buscar_codigo'] = $parametrosg['codigo'];
			$parametrosg['grupo'] = "";
		}else{
			$parametros['buscar_codigo'] = "";
		}
		$parametros['buscar_nombre'] = "";

			//Llamada a la clase especifica de la pantalla
			$oImpuestos = new clsImpuestos($conexion, $filadesde, $usuario, $parametros['buscar_codigo']);
			$sImpuestos = $oImpuestos->Cargar($recuperacodigo,$recuperanombre);
			//$sGrupos_gestionnuevos = $oGrupos_gestion->Cargar_lineas_nuevas();
			$sComboSelectImpuestos = $oImpuestos->Cargar_combo_selector();


			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboImpuestos = $oSino->Cargar_combo_Impuestos();
			//$comboProvincias = $oSino->Cargar_combo_Provincias();*/


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
			$smarty->assign('formulario', '» IMPUESTOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectImpuestos);

			//Cargar combo de busqueda
			$smarty->assign('comboImpuestos', $comboImpuestos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('impuestos', $sImpuestos);

			//Cargar combos de las lineas de la tabla
			/*$smarty->assign('comboProvincias', $comboProvincias);
			$smarty->assign('comboCargos', $comboCargos);*/

			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_codigo', '');

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE periodos

		$filadesde_periodos = 1;
		//$parametros['buscar_producto'] = "";


		//Llamada a la clase especifica de la pantalla
			$sImpuestos_periodos = $oImpuestos->Cargar_periodos($sImpuestos[0]['codigo'],$filadesde_periodos);			
			$sComboSelectImpuestos_periodos = $oImpuestos->Cargar_combo_selector_periodos($sImpuestos[0]['codigo']);
			$sImpuestos_periodos_nuevos = $oImpuestos->Cargar_lineas_nuevas_periodos();		

			//Llamada a la clase general de combos
			//$comboProductos = $oSino->Cargar_combo_Productos();

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_periodos', $filadesde_periodos);
		

			//Cargar combo selector
			$smarty->assign('combo_periodos', $sComboSelectImpuestos_periodos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('impuestos_periodos', $sImpuestos_periodos);

			//Cargar combos de las lineas de la tabla
			//$smarty->assign('comboProductos', $comboProductos);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('impuestos_periodosnuevos', $sImpuestos_periodos_nuevos);			
			$smarty->assign('recuperafecha_desde', $recuperafecha_desde);	
			$smarty->assign('recuperafecha_hasta', $recuperafecha_hasta);	
			$smarty->assign('recuperavalor', $recuperavalor);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_periodos', $mensaje1_periodos);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_periodos', $mensaje2_periodos);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			//$smarty->assign('buscar_producto', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Impuestos.html');
	}

	$conexion->close();


?>

