<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/productos.cls.php';


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
	$insertaProductos = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacodigo = '';
	$recuperanombre = '';
	$recuperavisible = '';
	$recuperaaplica_impuesto = '';
	$recuperaaplica_impuesto_fuera = '';
	
	if(count($_POST) != 0){
		if($parametros['buscar_producto'] != null){
			$producto = $parametros['buscar_producto'];
		}else{
			$producto = $parametros['buscar_codigo'];
		}

		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonProductos = new clsProductos($conexion, $filadesde, $usuario, $producto, $parametros['buscar_nombre']);
				$botonselec = $botonProductos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oProductos = new clsProductos($conexion, $filadesde, $usuario, $producto, $parametros['buscar_nombre']);
			$sProductos = $oProductos->Cargar();
			//lineas visualizadas
			$vueltas = count($sProductos);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mProductos = new clsProductos($conexion, $filadesde, $usuario, $producto, $parametros['buscar_nombre']);
							$modificaProductos = $mProductos->Modificar($parametros['codigo'.$num_fila], $parametros['nombre'.$num_fila], $parametros['visible'.$num_fila], $parametros['aplica_impuesto'.$num_fila], $parametros['aplica_impuesto_fuera'.$num_fila]);
							if($modificaProductos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaProductos;
								
							}
						}else{

							$mProductos = new clsProductos($conexion, $filadesde, $usuario, $producto, $parametros['buscar_nombre']);
							$borraProductos = $mProductos->Borrar($parametros['codigo'.$num_fila]);
							if($borraProductos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraProductos;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iProductos = new clsProductos($conexion, $filadesde, $usuario, $producto, $parametros['buscar_nombre']);
						$insertaProductos = $iProductos->Insertar($parametros['Nuevocodigo'.$num_fila], $parametros['Nuevonombre'.$num_fila], $parametros['Nuevovisible'.$num_fila], $parametros['Nuevoaplica_impuesto'.$num_fila], $parametros['Nuevoaplica_impuesto_fuera'.$num_fila]);
						if($insertaProductos == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaProductos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacodigo = $parametros['Nuevocodigo'.$num_fila]; 
							$recuperanombre = $parametros['Nuevonombre'.$num_fila];
							$recuperavisible = $parametros['Nuevovisible'.$num_fila];
							$recuperaaplica_impuesto = $parametros['Nuevoaplica_impuesto'.$num_fila];
							$recuperaaplica_impuesto_fuera = $parametros['Nuevoaplica_impuesto_fuera'.$num_fila];
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

			$sProductos = $oProductos->Cargar();
			$sProductosnuevos = $oProductos->Cargar_lineas_nuevas();
			$sComboSelectProductos = $oProductos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboProductos = $oSino->Cargar_combo_Productos();

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
			$smarty->assign('formulario', '» PRODUCTOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectProductos);

			//Cargar combos de los campos de busqueda
			$smarty->assign('comboProductos', $comboProductos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('productos', $sProductos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('productos_nuevos', $sProductosnuevos);			
			$smarty->assign('recuperacodigo', $recuperacodigo);	
			$smarty->assign('recuperanombre', $recuperanombre);	
			$smarty->assign('recuperavisible', $recuperavisible);	
			$smarty->assign('recuperaaplica_impuesto', $recuperaaplica_impuesto);
			$smarty->assign('recuperaaplica_impuesto_fuera', $recuperaaplica_impuesto_fuera);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_producto', $parametros['buscar_producto']);

			//Visualizar plantilla
			$smarty->display('plantillas/Productos.html');


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
		$producto = "";

			//Llamada a la clase especifica de la pantalla
			$oProductos = new clsProductos($conexion, $filadesde, $usuario, $producto, $parametros['buscar_nombre']);
			$sProductos = $oProductos->Cargar();
			$sProductosnuevos = $oProductos->Cargar_lineas_nuevas();
			$sComboSelectProductos = $oProductos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboProductos = $oSino->Cargar_combo_Productos();

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
			$smarty->assign('formulario', '» PRODUCTOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectProductos);

			//Cargar combos de los campos de busqueda
			$smarty->assign('comboProductos', $comboProductos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('productos', $sProductos);


			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('productos_nuevos', $sProductosnuevos);			
			$smarty->assign('recuperacodigo', $recuperacodigo);	
			$smarty->assign('recuperanombre', $recuperanombre);	
			$smarty->assign('recuperavisible', $recuperavisible);	
			$smarty->assign('recuperaaplica_impuesto', $recuperaaplica_impuesto);
			$smarty->assign('recuperaaplica_impuesto_fuera', $recuperaaplica_impuesto_fuera);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_codigo', '');
		$smarty->assign('buscar_nombre', '');
		$smarty->assign('buscar_producto', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Productos.html');
	}

	$conexion->close();


?>

