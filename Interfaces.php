<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/interfaces.cls.php';


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
	$insertaInterfaces = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperaid = '';
	$recuperanombre = '';
	$recuperacodigo = '';
	$recuperatipo = '';
	$recuperavisible = '';
	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonInterfaces = new clsInterfaces($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
				$botonselec = $botonInterfaces->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oInterfaces = new clsInterfaces($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
			$sInterfaces = $oInterfaces->Cargar();
			//lineas visualizadas
			$vueltas = count($sInterfaces);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'INTERFACES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas -1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mInterfaces = new clsInterfaces($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
							$modificaInterfaces = $mInterfaces->Modificar($parametros['id'.$num_fila], $parametros['nombre'.$num_fila], $parametros['tipo'.$num_fila], $parametros['visible'.$num_fila]);
							if($modificaInterfaces == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaInterfaces;
								
							}
						}else{

							$mInterfaces = new clsInterfaces($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
							$borraInterfaces = $mInterfaces->Borrar($parametros['id'.$num_fila]);
							if($borraInterfaces == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraInterfaces;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'INTERFACES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iInterfaces = new clsInterfaces($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
						$insertaInterfaces = $iInterfaces->Insertar($parametros['Nuevonombre'.$num_fila],$parametros['Nuevocodigo'.$num_fila], $parametros['Nuevotipo'.$num_fila], $parametros['Nuevovisible'.$num_fila]);
						if($insertaInterfaces == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaInterfaces;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperanombre = $parametros['Nuevonombre'.$num_fila]; 
							$recuperacodigo = $parametros['Nuevocodigo'.$num_fila]; 
							$recuperatipo = $parametros['Nuevotipo'.$num_fila];
							$recuperavisible = $parametros['Nuevovisible'.$num_fila];
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
			$sInterfaces = $oInterfaces->Cargar();
			$sInterfacesnuevos = $oInterfaces->Cargar_lineas_nuevas();
			$sComboSelectInterfaces = $oInterfaces->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo_Si();	
			$comboTipoInterfaz = $oSino->Cargar_Combo_TipoInterfaz();		
			
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
			$smarty->assign('color_opcion', '#118FD8');
			$smarty->assign('grupo', '» INTERFACES');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» INTERFACES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectInterfaces);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('interfaces', $sInterfaces);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboTipoInterfaz', $comboTipoInterfaz);			

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('interfaces_nuevos', $sInterfacesnuevos);			
			$smarty->assign('recuperaid', $recuperaid);	
			$smarty->assign('recuperanombre', $recuperanombre);	
			$smarty->assign('recuperacodigo', $recuperacodigo);
			$smarty->assign('recuperatipo', $recuperatipo);	
			$smarty->assign('recuperavisible', $recuperavisible);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);

			//Visualizar plantilla
			$smarty->display('plantillas/Interfaces.html');


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
		$oInterfaces = new clsInterfaces($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
		$sInterfaces = $oInterfaces->Cargar();
		$sInterfacesnuevos = $oInterfaces->Cargar_lineas_nuevas();
		$sComboSelectInterfaces = $oInterfaces->Cargar_combo_selector();

		//Llamada a la clase general de combos
		$oSino = new clsGeneral($conexion);
		$comboSino = $oSino->Cargar_combo_SiNo_Si();
		$comboTipoInterfaz = $oSino->Cargar_Combo_TipoInterfaz();			
		
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
		$smarty->assign('color_opcion', '#118FD8');
		$smarty->assign('grupo', '» INTERFACES');
		$smarty->assign('subgrupo', '');
		$smarty->assign('formulario', '» INTERFACES');

		//Nombre del usuario de la sesion
		$smarty->assign('nombre', $nombre);

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades', $filadesde);

		//Cargar combo selector
		$smarty->assign('combo', $sComboSelectInterfaces);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('interfaces', $sInterfaces);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboSino', $comboSino);
		$smarty->assign('comboTipoInterfaz', $comboTipoInterfaz);
		
		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('interfaces_nuevos', $sInterfacesnuevos);			
		$smarty->assign('recuperaid', $recuperaid);	
		$smarty->assign('recuperanombre', $recuperanombre);	
		$smarty->assign('recuperacodigo', $recuperacodigo);
		$smarty->assign('recuperatipo', $recuperatipo);	
		$smarty->assign('recuperavisible', $recuperavisible);	

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1', $mensaje1);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_codigo', '');
		$smarty->assign('buscar_nombre', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Interfaces.html');
	}

	$conexion->close();


?>

