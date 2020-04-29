<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/operativa_planning.cls.php';


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
	$insertaPlanning = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacodigo = '';
	$recuperanombre = '';
	$recuperavisible = '';
	
	if(count($_POST) != 0){
		/*if($parametros['buscar_producto'] != null){
			$producto = $parametros['buscar_producto'];
		}else{
			$producto = $parametros['buscar_codigo'];
		}*/

		if($parametros['ir_a'] != 'S'){

			//Llamada a la clase especifica de la pantalla
			$oPlanning = new clsPlanning($conexion, $usuario, $parametros['buscar_folleto'], $parametros['buscar_destino']);
			$sPlanning_calendario = $oPlanning->Cargar_calendario_cabecera();
			$sTrayectos = $oPlanning->Cargar_trayectos();
			$sTrayectos_ocupacion = $oPlanning->Cargar_trayectos_ocupacion();
			$sAlojamientos = $oPlanning->Cargar_alojamientos();
			$sAlojamientos_confirmados = $oPlanning->Cargar_ocupacion_alojamientos_confirmados();
			$sTrayectos_ocupacion_no_confirmados = $oPlanning->Cargar_trayectos_ocupacion_no_confirmados();
			$sAlojamientos_no_confirmados = $oPlanning->Cargar_ocupacion_alojamientos_no_confirmados();
			
		
			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboTipo_planning = $oSino->Cargar_Combo_tipo_planning();
			$comboFolletos = $oSino->Cargar_combo_Folletos();
			$comboDestinos = $oSino->Cargar_combo_Destinos();
			
			//Obtenermos el ancho de la tabla
			$resultado_lineas_salidas =$conexion->query("select count(*) salidas
															from(
															SELECT distinct s.FECHA fecha,
															concat(concat(DATE_FORMAT(s.FECHA, '%d '),`GENERAL_TRADUCE_MES`(DATE_FORMAT(s.FECHA, '%m')))) AS fecha_formato
															FROM 
																hit_reservas r,
																hit_producto_cuadros_salidas s,
																hit_producto_cuadros c
															where
																r.FOLLETO = s.FOLLETO
																and r.CUADRO = s.CUADRO
																and s.CLAVE_CUADRO = c.CLAVE
																and r.SITUACION <> 'A'
																and s.ESTADO = 'A'
																and r.FOLLETO = '".$parametros['buscar_folleto']."'
																and c.DESTINO = '".$parametros['buscar_destino']."') salidas");
																
			$oresultado_lineas_salidas = $resultado_lineas_salidas->fetch_assoc();
			$lineas_salidas = $oresultado_lineas_salidas['salidas'];	
			$sAncho_tabla = 400 + ($lineas_salidas * 150);
			//echo($sAncho_tabla);
			
			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#347135');
			$smarty->assign('grupo', '» OPERATIVA');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» PLANNING');


			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Cargar combos de los campos de busqueda
			$smarty->assign('comboTipo_planning', $comboTipo_planning);			
			$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboDestinos', $comboDestinos);

			//Cargar lineas de planning
			$smarty->assign('ancho_tabla', $sAncho_tabla);
			$smarty->assign('calendario', $sPlanning_calendario);
			$smarty->assign('trayectos', $sTrayectos);
			$smarty->assign('trayectos_ocupacion', $sTrayectos_ocupacion);
			$smarty->assign('alojamientos', $sAlojamientos);
			$smarty->assign('alojamientos_confirmados', $sAlojamientos_confirmados);
			$smarty->assign('trayectos_no_confirmados', $sTrayectos_ocupacion_no_confirmados);
			$smarty->assign('alojamientos_no_confirmados', $sAlojamientos_no_confirmados);

			
			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_tipo', $parametros['buscar_tipo']);
			$smarty->assign('buscar_folleto', $parametros['buscar_folleto']);
			$smarty->assign('buscar_destino', $parametros['buscar_destino']);

			//Visualizar plantilla
			$smarty->display('plantillas/Operativa_planning.html');


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

		$parametros['buscar_folleto'] = "";
		$parametros['buscar_destino'] = "";
		$sAncho_tabla = '95%';

			//Llamada a la clase especifica de la pantalla
			$oPlanning = new clsPlanning($conexion, $usuario, $parametros['buscar_folleto'], $parametros['buscar_destino']);
			$sPlanning_calendario = $oPlanning->Cargar_calendario_cabecera();
			$sTrayectos = $oPlanning->Cargar_trayectos();
			$sTrayectos_ocupacion = $oPlanning->Cargar_trayectos_ocupacion();
			$sAlojamientos = $oPlanning->Cargar_alojamientos();
			$sAlojamientos_confirmados = $oPlanning->Cargar_ocupacion_alojamientos_confirmados();
			$sTrayectos_ocupacion_no_confirmados = $oPlanning->Cargar_trayectos_ocupacion_no_confirmados();
			$sAlojamientos_no_confirmados = $oPlanning->Cargar_ocupacion_alojamientos_no_confirmados();
			

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboTipo_planning = $oSino->Cargar_Combo_tipo_planning();
			$comboFolletos = $oSino->Cargar_combo_Folletos();
			$comboDestinos = $oSino->Cargar_combo_Destinos();		
			
			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#347135');
			$smarty->assign('grupo', '» OPERATIVA');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» PLANNING');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);


			//Cargar combos de los campos de busqueda
			$smarty->assign('comboTipo_planning', $comboTipo_planning);			
			$smarty->assign('comboFolletos', $comboFolletos);
			$smarty->assign('comboDestinos', $comboDestinos);


			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('ancho_tabla', $sAncho_tabla);
			$smarty->assign('calendario', $sPlanning_calendario);
			$smarty->assign('trayectos', $sTrayectos);
			$smarty->assign('trayectos_ocupacion', $sTrayectos_ocupacion);
			$smarty->assign('alojamientos', $sAlojamientos);
			$smarty->assign('alojamientos_confirmados', $sAlojamientos_confirmados);
			$smarty->assign('trayectos_no_confirmados', $sTrayectos_ocupacion_no_confirmados);
			$smarty->assign('alojamientos_no_confirmados', $sAlojamientos_no_confirmados);
			
			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_tipo', '');
		$smarty->assign('buscar_folleto', '');
		$smarty->assign('buscar_destino', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Operativa_planning.html');
	}

	$conexion->close();


?>



