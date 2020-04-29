<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/producto_folletos.cls.php';

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
			$insertaFolleto = '';

			//VARIABLES PARA LOS TRAYECTOS
			$recuperatipo = ''; 
			$recuperasalida_desde = ''; 
			$recuperasalida_hasta = ''; 
			$recuperareserva_desde = ''; 
			$recuperareserva_hasta = ''; 
			$recuperamargen_1 = ''; 
			$recuperamargen_2 = ''; 
			$recuperamaximo = '';
			$recuperaforma_calculo = ''; 
			$recuperavalor_1 = ''; 
			$recuperavalor_2 = ''; 
			$recuperaacumulable = ''; 
			$recuperaprioritario = ''; 
			$recuperaaplicacion = ''; 
			$mensaje1_condiciones = '';
			$mensaje2_condiciones = '';
			$error_condiciones = '';




//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//CONTROLAMOS LAS VARIABLES NECESARIAS PARA LLAMAR A LA CLASE RESERVAS DEPENDIENDO DE SI ACABAMOS DE ENTRAR EN LA PANTALLA
	//O YA HEMOS REALIZADO ALGUNA PETICION Y/O VOLVEMOS DE OTRA PANTALLA
//--------------------------------------------------------------------------------------------------------------------------------
	if(count($_POST) != 0){
		$filadesde = $parametros['filadesde'];

		//esto es por si venimos de la pantalla de listado de cupos
		if($parametros['buscar_codigo'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_codigo'] = $parametrosg['folleto'];
			}
		}
	}else{
		//SI ACABAMOS DE ENTRAR EN LA PANTALLA
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LAS RESERVAS
		$filadesde = 1;
		$filadesde_condiciones = 1;
		if(count($_GET) != 0 and $parametrosg['folleto']){
			$parametros['buscar_codigo'] = $parametrosg['folleto'];
		}else{
			$parametros['buscar_codigo'] = 0;
			$parametros['buscar_tipo'] = 0;
		}
	}

//--------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------------
	//LLAMADA A LA CLASE GENERAL PARA TODAS LAS ACCIONES
//--------------------------------------------------------------------------------------------------------------------------------
	$ClaseFolletos = new clsFolletos($conexion, $filadesde, $usuario, $parametros['buscar_codigo']);

   //TAMBIEN LLAMAMOS A LA CLASE GENERAL PARA CARGAR LOS COMBOS DE LA PANTALLA
	$combos = new clsGeneral($conexion);

	if(count($_POST) != 0){


		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'C'){

			//------------------------------------------------------------------------------------
			//---SECCION DEL CODIGO PARA LOS DATOS GENERALES DE LAS TRANSPORTES_ACUERDOS----------
			//------------------------------------------------------------------------------------

			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonselec = $ClaseFolletos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_FOLLETOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){

							$modificaFolletos = $ClaseFolletos->Modificar($parametros['codigo'.$num_fila],$parametros['nombre'.$num_fila]);
							if($modificaFolletos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaFolletos;
							}
						}else{
							$borraFolletos = $ClaseFolletos->Borrar($parametros['codigo'.$num_fila]);
							if($borraFolletos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraFolletos;
							}
						}
					}
				}
				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				$num_filas->close();

			}

			if($parametros['grabar_registro'] == 'S'){

				//AÑADIR REGISTROS
				$Ntransacciones = 0;				

				$insertaFolletos = $ClaseFolletos->Insertar($parametros['codigo0'],$parametros['nombre0']);

				if($insertaFolletos == 'OK'){
					$Ntransacciones++;
					$nuevoregistro = 'N';
					$parametros['buscar_codigo'] = $parametros['codigo0'];

				}else{
					$error = $insertaFolletos;
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
			$sFolletos = $ClaseFolletos->Cargar($recuperacodigo,$recuperanombre);
			$sComboSelectFolletos = $ClaseFolletos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$comboFolletos = $combos->Cargar_combo_Folletos();

	//--------------------------------------------------------
	//---SECCION DEL CODIGO PARA LAS CONDICIONES -------------
	//--------------------------------------------------------
			/*echo('<pre>');
			print_r($sGrupos_gestion);
			echo('</pre>');*/

			$filadesde_condiciones = $parametros['filadesde_condiciones'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector_2nivel'] != 0){
				$botonselec_condiciones = $ClaseFolletos->Botones_selector_condiciones($filadesde_condiciones, $parametros['botonSelector_2nivel']);
				$filadesde_condiciones = $botonselec_condiciones;
			}

			//Llamada a la clase especifica de la pantalla
			$sCondiciones = $ClaseFolletos->Cargar_condiciones($sFolletos[0]['codigo'], $filadesde_condiciones, $parametros['buscar_tipo']);	
			//lineas visualizadas
			$vueltas = count($sCondiciones);

			if($parametros['actuar_2nivel'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_FOLLETOS_CONDICIONES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones_condiciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec_2nivel'.$num_fila] == 'S' || $parametros['borra_2nivel'.$num_fila] == 'S'){
						if($parametros['selec_2nivel'.$num_fila] == 'S'){
							
							$modificaCondiciones = $ClaseFolletos->Modificar_condiciones($sFolletos[0]['codigo'], $parametros['tipo'.$num_fila], $parametros['salida_desde'.$num_fila], $parametros['salida_hasta'.$num_fila], $parametros['reserva_desde'.$num_fila],$parametros['reserva_hasta'.$num_fila],$parametros['margen_1'.$num_fila],$parametros['margen_2'.$num_fila],$parametros['maximo'.$num_fila],$parametros['forma_calculo'.$num_fila],$parametros['valor_1'.$num_fila],$parametros['valor_2'.$num_fila], $parametros['acumulable'.$num_fila], $parametros['prioritario'.$num_fila], $parametros['aplicacion'.$num_fila], 
							$parametros['tipo_old'.$num_fila], $parametros['salida_desde_old'.$num_fila], $parametros['salida_hasta_old'.$num_fila], $parametros['reserva_desde_old'.$num_fila],$parametros['reserva_hasta_old'.$num_fila],$parametros['margen_1_old'.$num_fila],$parametros['margen_2_old'.$num_fila]);
							if($modificaCondiciones == 'OK'){
								$Ntransacciones_condiciones++;
							}else{
								$error_condiciones = $modificaCondiciones;
							}

						}else{

							$borraCondiciones = $ClaseFolletos->Borrar_condiciones($sFolletos[0]['codigo'], $parametros['tipo'.$num_fila], $parametros['salida_desde'.$num_fila], $parametros['salida_hasta'.$num_fila], $parametros['reserva_desde'.$num_fila],$parametros['reserva_hasta'.$num_fila],$parametros['margen_1'.$num_fila],$parametros['margen_2'.$num_fila]);
							if($borraCondiciones == 'OK'){
								$Ntransacciones_condiciones++;
							}else{
								$error_condiciones = $borraCondiciones;

							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_FOLLETOS_CONDICIONES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec_2nivel'.$num_fila] == 'S'){

						$insertaCondiciones = $ClaseFolletos->Insertar_condiciones($sFolletos[0]['codigo'], $parametros['Nuevotipo'.$num_fila], $parametros['Nuevosalida_desde'.$num_fila],$parametros['Nuevosalida_hasta'.$num_fila],$parametros['Nuevoreserva_desde'.$num_fila],$parametros['Nuevoreserva_hasta'.$num_fila],$parametros['Nuevomargen_1'.$num_fila],$parametros['Nuevomargen_2'.$num_fila],$parametros['Nuevomaximo'.$num_fila],$parametros['Nuevoforma_calculo'.$num_fila],$parametros['Nuevovalor_1'.$num_fila],$parametros['Nuevovalor_2'.$num_fila],$parametros['Nuevoacumulable'.$num_fila],$parametros['Nuevoprioritario'.$num_fila],$parametros['Nuevoaplicacion'.$num_fila]);
						if($insertaCondiciones == 'OK'){
							$Ntransacciones_condiciones++;
						}else{
							$error_condiciones = $insertaCondiciones;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.

							$recuperatipo = $parametros['Nuevotipo'.$num_fila]; 
							$recuperasalida_desde = $parametros['Nuevosalida_desde'.$num_fila];  
							$recuperasalida_hasta = $parametros['Nuevosalida_hasta'.$num_fila]; 
							$recuperareserva_desde = $parametros['Nuevoreserva_desde'.$num_fila];  
							$recuperareserva_hasta = $parametros['Nuevoreserva_hasta'.$num_fila];  
							$recuperamargen_1 = $parametros['Nuevomargen_1'.$num_fila]; 
							$recuperamarge_2 = $parametros['Nuevomargen_2'.$num_fila]; 
							$recuperamaximo = $parametros['Nuevomaximo'.$num_fila]; 
							$recuperaforma_calculo = $parametros['Nuevoforma_calculo'.$num_fila]; 
							$recuperavalor_1 = $parametros['Nuevovalor_1'.$num_fila]; 
							$recuperavalor_2 = $parametros['Nuevovalor_2'.$num_fila]; 
							$recuperaacumulable = $parametros['Nuevoacumulable'.$num_fila];  
							$recuperaprioritario = $parametros['Nuevoprioritario'.$num_fila];  
							$recuperaaplicacion = $parametros['Nuevoaplicacion'.$num_fila];  
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1_condiciones = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones_condiciones."</b></font></div>";
				if($error_condiciones != ''){
					$mensaje2_condiciones = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error_condiciones."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();

			}


			//Llamada a la clase especifica de la pantalla
			$sCondiciones = $ClaseFolletos->Cargar_condiciones($sFolletos[0]['codigo'], $filadesde_condiciones, $parametros['buscar_tipo']);	
			$sComboSelectCondiciones = $ClaseFolletos->Cargar_combo_selector_condiciones($sFolletos[0]['codigo'], $filadesde_condiciones, $parametros['buscar_tipo']);
			$sCondiciones_nuevos = $ClaseFolletos->Cargar_lineas_nuevas_condiciones();	

			//Llamada a la clase general de combos
			$comboSino = $combos->Cargar_combo_SiNo();
			$comboTipo_Condiciones = $combos->Cargar_combo_Tipo_Condiciones();
			$comboForma_Calculo = $combos->Cargar_combo_Forma_Calculo();
			$comboAplicacion_Condicion = $combos->Cargar_combo_Aplicacion_Condicion();


	//-----------------------------------------------------------------------------------
	//---SECCION DEL CODIGO PARA VISUALIZAR LA INFORMACION EN LA PLANTILLA SMARTY--------
	//-----------------------------------------------------------------------------------


			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			//Establecer plantilla smarty
			$smarty = new Smarty;


			//---------------------------------------------
			//----VISUALIZAR PARTE DE LOS FOLLETOS---------
			//---------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#30A1C7');
			$smarty->assign('grupo', '» PRODUCTO');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» FOLLETOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectFolletos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('folletos', $sFolletos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboFolletos', $comboFolletos);


			$smarty->assign('nuevo_registro', $parametros['nuevo_registro']);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);

			//---------------------------------------------
			//----VISUALIZAR PARTE DE LAS CONDICIONES------
			//---------------------------------------------

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_condiciones', $filadesde_condiciones);

			//Cargar combo selector
			$smarty->assign('combo_condiciones', $sComboSelectCondiciones);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('condiciones', $sCondiciones);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboTipo_Condiciones', $comboTipo_Condiciones);
			$smarty->assign('comboForma_Calculo', $comboForma_Calculo);
			$smarty->assign('comboAplicacion_Condicion', $comboAplicacion_Condicion);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('condicionesnuevos', $sCondiciones_nuevos);	

			$smarty->assign('recuperatipo', $recuperatipo);	
			$smarty->assign('recuperasalida_desde', $recuperasalida_desde);	
			$smarty->assign('recuperasalida_hasta', $recuperasalida_hasta);	
			$smarty->assign('recuperareserva_desde', $recuperareserva_desde);	
			$smarty->assign('recuperareserva_hasta', $recuperareserva_hasta);	
			$smarty->assign('recuperamargen_1', $recuperamargen_1);	
			$smarty->assign('recuperamargen_2', $recuperamargen_2);	
			$smarty->assign('recuperamaximo', $recuperamaximo);	
			$smarty->assign('recuperaforma_calculo', $recuperaforma_calculo);	
			$smarty->assign('recuperavalor_1', $recuperavalor_1);	
			$smarty->assign('recuperavalor_2', $recuperavalor_2);	
			$smarty->assign('recuperaacumulable', $recuperaacumulable);	
			$smarty->assign('recuperaprioritario', $recuperaprioritario);	
			$smarty->assign('recuperaaplicacion', $recuperaaplicacion);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_condiciones', $mensaje1_condiciones);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_condiciones', $mensaje2_condiciones);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_tipo', $parametros['buscar_tipo']);


			//---------------------------------------------
			//----ENVIA TODOS LOS DATOS A LA PLANTILLA-----
			//---------------------------------------------

			//Visualizar plantilla
			$smarty->display('plantillas/Producto_folletos.html');


		}elseif($parametros['ir_a'] == 'E'){

			//Llamar a al metodo de expandir cupos;

		}elseif($parametros['ir_a'] == 'B'){

			//Llamar a al metodo de borrar cupos;

		}elseif($parametros['ir_a'] == 'C'){

			header("Location: Transportes_cupos.php?cia=".$parametros['cia0']."&acuerdo=".$parametros['acuerdo0']."&subacuerdo=".$parametros['subacuerdo0']);

		}else{
			session_destroy();
			exit;
		}


	}else{
		//----------------------------------------------------------------
		//--SI NO HAY PARAMETROS PORQUE SE ACABA DE ENTRAR EN LA PANTALLA--
		//----------------------------------------------------------------

		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE LAS ALOJAMIENTOS_ACUERDOS


			//Llamada a la clase especifica de la pantalla
			$sFolletos = $ClaseFolletos->Cargar($recuperacodigo,$recuperanombre);
			$sComboSelectFolletos = $ClaseFolletos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$comboSino = $combos->Cargar_combo_SiNo();
			$comboFolletos = $combos->Cargar_combo_Folletos();

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//--------------------------------------------------------
			//----VISUALIZAR PARTE DE FOLLETOS -----------------------
			//--------------------------------------------------------

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#30A1C7');
			$smarty->assign('grupo', '» PRODUCTO');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» FOLLETOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectFolletos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('folletos', $sFolletos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboFolletos', $comboFolletos);


			$smarty->assign('nuevo_registro', 'N');

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', '');


		//-----------------------------------------------------------
		//INICIALIZAMOS PARAMETROS Y PLANTILLA DE CONDICIONES

			//Llamada a la clase especifica de la pantalla
			$sCondiciones = $ClaseFolletos->Cargar_condiciones($sFolletos[0]['codigo'],$filadesde, $parametros['buscar_tipo']);	
			$sComboSelectCondiciones = $ClaseFolletos->Cargar_combo_selector_condiciones($sFolletos[0]['codigo'],$filadesde, $parametros['buscar_tipo']);
			$sCondiciones_nuevos = $ClaseFolletos->Cargar_lineas_nuevas_condiciones();	

			//Llamada a la clase general de combos
			$comboSino = $combos->Cargar_combo_SiNo();
			$comboTipo_Condiciones = $combos->Cargar_combo_Tipo_Condiciones();
			$comboForma_Calculo = $combos->Cargar_combo_Forma_Calculo();
			$comboAplicacion_Condicion = $combos->Cargar_combo_Aplicacion_Condicion();


			//Numero de fila para situar el selector de registros
			$smarty->assign('filades_condiciones', $filadesde_condiciones);

			//Cargar combo selector
			$smarty->assign('combo_condiciones', $sComboSelectCondiciones);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('condiciones', $sCondiciones);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboTipo_Condiciones', $comboTipo_Condiciones);
			$smarty->assign('comboForma_Calculo', $comboForma_Calculo);
			$smarty->assign('comboAplicacion_Condicion', $comboAplicacion_Condicion);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('condicionesnuevos', $sCondiciones_nuevos);	

			$smarty->assign('recuperatipo', $recuperatipo);	
			$smarty->assign('recuperasalida_desde', $recuperasalida_desde);	
			$smarty->assign('recuperasalida_hasta', $recuperasalida_hasta);	
			$smarty->assign('recuperareserva_desde', $recuperareserva_desde);	
			$smarty->assign('recuperareserva_hasta', $recuperareserva_hasta);	
			$smarty->assign('recuperamargen_1', $recuperamargen_1);	
			$smarty->assign('recuperamargen_2', $recuperamargen_2);	
			$smarty->assign('recuperamaximo', $recuperamaximo);	
			$smarty->assign('recuperaforma_calculo', $recuperaforma_calculo);	
			$smarty->assign('recuperavalor_1', $recuperavalor_1);	
			$smarty->assign('recuperavalor_2', $recuperavalor_2);	
			$smarty->assign('recuperaacumulable', $recuperaacumulable);	
			$smarty->assign('recuperaprioritario', $recuperaprioritario);	
			$smarty->assign('recuperaaplicacion', $recuperaaplicacion);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1_condiciones', '');

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2_condiciones', '');

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_tipo', '');



		//Visualizar plantilla
		$smarty->display('plantillas/Producto_folletos.html');
	}

	$conexion->close();


?>

