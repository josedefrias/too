<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/facturacion.cls.php';


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
	$insertaFacturacion = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$estado = '';
	
	if(count($_POST) != 0){

		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonFacturacion = new clsFacturacion($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_grupo'], $parametros['buscar_estado'], $parametros['buscar_localizador'], $parametros['buscar_factura']);
				$botonselec = $botonFacturacion->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oFacturacion = new clsFacturacion($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_grupo'], $parametros['buscar_estado'], $parametros['buscar_localizador'], $parametros['buscar_factura']);
			$sFacturacion = $oFacturacion->Cargar();
			//lineas visualizadas
			$vueltas = count($sFacturacion);

			if($parametros['actuar'] == 'S'){
				//MODIFICAR
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'FACTURACION' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
				
					//ECHO('HOLA');
					if($parametros['selec'.$num_fila] == 'S'){
						//ECHO('HOLA');
						$mFacturacion = new clsFacturacion($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_grupo'], $parametros['buscar_estado'], $parametros['buscar_localizador'], $parametros['buscar_factura']);
						$factura_reserva = $mFacturacion->Facturar_reserva($parametros['localizador'.$num_fila]);
						if($factura_reserva == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $factura_reserva;
						}
					}
				}

				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Reservas facturadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}

			if($parametros['actuar'] == 'P'){

				$Ntransacciones = 0;

				$mFacturacion = new clsFacturacion($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_grupo'], $parametros['buscar_estado'], $parametros['buscar_localizador'], $parametros['buscar_factura']);
				$factura_grupo = $mFacturacion->Facturar_grupo();

				$Ntransacciones = $factura_grupo;


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Reservas facturadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close();
			}			
			
			//Llamada a la clase especifica de la pantalla
			$oFacturacion = new clsFacturacion($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_grupo'], $parametros['buscar_estado'], $parametros['buscar_localizador'], $parametros['buscar_factura']);
			$sFacturacion = $oFacturacion->Cargar();
			$sImporteTotal = $oFacturacion->Cargar_Importe_Total();
			$sComboSelectFacturacion = $oFacturacion->Cargar_combo_selector();

				
			
			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			//$comboSino = $oSino->Cargar_combo_SiNo();
			$comboBuscarFacturacion = $oSino->Cargar_Combo_Buscar_Estado_Facturacion();
			$comboBuscarGrupos = $oSino->Cargar_combo_Grupos_gestion();

			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#4C4C4C');
			$smarty->assign('grupo', '» ADMINISTRACION');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» FACTURACION');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectFacturacion);

			//Cargar combos
			$smarty->assign('comboBuscarFacturacion', $comboBuscarFacturacion);
			$smarty->assign('comboBuscarGrupos', $comboBuscarGrupos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('facturacion', $sFacturacion);
			$smarty->assign('importetotal', $sImporteTotal);

			//(Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('recuperaestado', $recuperaestado);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_salida_desde', $parametros['buscar_salida_desde']);
			$smarty->assign('buscar_salida_hasta', $parametros['buscar_salida_hasta']);
			$smarty->assign('buscar_grupo', $parametros['buscar_grupo']);
			$smarty->assign('buscar_estado', $parametros['buscar_estado']);
			$smarty->assign('buscar_localizador', $parametros['buscar_localizador']);
			$smarty->assign('buscar_factura', $parametros['buscar_factura']);
			
			//Visualizar plantilla
			$smarty->display('plantillas/Facturacion.html');


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
		$parametros['buscar_salida_desde'] = "";
		$parametros['buscar_salida_hasta'] = "";
		$parametros['buscar_grupo'] = "";
		$parametros['buscar_estado'] = "";
		$parametros['buscar_localizador'] = "";
		$parametros['buscar_factura'] = "";

			//Llamada a la clase especifica de la pantalla
			$oFacturacion = new clsFacturacion($conexion, $filadesde, $usuario, $parametros['buscar_salida_desde'], $parametros['buscar_salida_hasta'], $parametros['buscar_grupo'], $parametros['buscar_estado'], $parametros['buscar_localizador'], $parametros['buscar_factura']);
			$sFacturacion = $oFacturacion->Cargar();
			$sImporteTotal = $oFacturacion->Cargar_Importe_Total();
			$sComboSelectFacturacion = $oFacturacion->Cargar_combo_selector();
			
			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			//$comboSino = $oSino->Cargar_combo_SiNo();
			$comboBuscarFacturacion = $oSino->Cargar_Combo_Buscar_Estado_Facturacion();
			$comboBuscarGrupos = $oSino->Cargar_combo_Grupos_gestion();
			
			//Establecer plantilla smarty
			$smarty = new Smarty;

			//Cargamos cabecera y pie de plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#4C4C4C');
			$smarty->assign('grupo', '» ADMINISTRACION');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» FACTURACION');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectFacturacion);

			//Cargar combos
			$smarty->assign('comboBuscarFacturacion', $comboBuscarFacturacion);
			$smarty->assign('comboBuscarGrupos', $comboBuscarGrupos);
			

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('facturacion', $sFacturacion);
			$smarty->assign('importetotal', $sImporteTotal);


			//(Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			//$smarty->assign('recuperaestado', $recuperaestado);	


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_salida_desde', '');
			$smarty->assign('buscar_salida_hasta', '');
			$smarty->assign('buscar_grupo', '');
			$smarty->assign('buscar_estador_nombre', '');
			$smarty->assign('buscar_localizador', '');
			$smarty->assign('buscar_factura', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Facturacion.html');
	}

	$conexion->close();


?>

