<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/consultas_busquedas_web.cls.php';


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
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$estado = '';
	
	if(count($_POST) != 0){

		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonPresupuestos= new clsConsulta_busquedas_web($conexion, $filadesde, $usuario, $parametros['buscar_desde'], $parametros['buscar_hasta'], $parametros['buscar_pais'], $parametros['buscar_producto']);
				$botonselec = $botonPresupuestos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			
			//Llamada a la clase especifica de la pantalla
			$oBusquedas_web = new clsConsulta_busquedas_web($conexion, $filadesde, $usuario, $parametros['buscar_desde'], $parametros['buscar_hasta'], $parametros['buscar_pais'], $parametros['buscar_producto']);
			$sBusquedas_web = $oBusquedas_web->Cargar();
			$sComboSelectBusquedas_web = $oBusquedas_web->Cargar_combo_selector();

				
			
			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboPaises = $oSino->Cargar_combo_Paises();
			$comboProductos = $oSino->Cargar_combo_Productos();

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#027767');
			$smarty->assign('grupo', '» MARKETING');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» CONSULTA DE BUSQUEDAS WEB');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectBusquedas_web);

			//Cargar combos
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboProductos', $comboProductos);



			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('busquedas_web', $sBusquedas_web);


			//(Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('recuperaestado', $recuperaestado);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_desde', $parametros['buscar_desde']);
			$smarty->assign('buscar_hasta', $parametros['buscar_hasta']);
			$smarty->assign('buscar_pais', $parametros['buscar_pais']);
			$smarty->assign('buscar_producto', $parametros['buscar_producto']);

			
			
			//Visualizar plantilla
			$smarty->display('plantillas/Consultas_busquedas_web.html');


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
		$parametros['buscar_desde'] = "";
		$parametros['buscar_hasta'] = "";
		$parametros['buscar_pais'] = "";
		$parametros['buscar_producto'] = "";


			//Llamada a la clase especifica de la pantalla
			$oBusquedas_web = new clsConsulta_busquedas_web($conexion, $filadesde, $usuario, $parametros['buscar_desde'], $parametros['buscar_hasta'], $parametros['buscar_pais'], $parametros['buscar_producto']);
			$sBusquedas_web = $oBusquedas_web->Cargar();
			$sComboSelectBusquedas_web = $oBusquedas_web->Cargar_combo_selector();
			
			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboPaises = $oSino->Cargar_combo_Paises();
			$comboProductos = $oSino->Cargar_combo_Productos();

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#027767');
			$smarty->assign('grupo', '» MARKETING');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» CONSULTA DE BUSQUEDAS WEB');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectBusquedas_web);

			//Cargar combos
			$smarty->assign('comboPaises', $comboPaises);
			$smarty->assign('comboProductos', $comboProductos);
			

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('busquedas_web', $sBusquedas_web);



			//(Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('recuperaestado', $recuperaestado);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_desde', '');
			$smarty->assign('buscar_hasta', '');
			$smarty->assign('buscar_pais', '');
			$smarty->assign('buscar_producto', '');


		//Visualizar plantilla
		$smarty->display('plantillas/Consultas_busquedas_web.html');
	}

	$conexion->close();


?>

