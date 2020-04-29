<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/Minoristas_Oficinas.cls.php';


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
									
	/*echo('<pre>');
	print_r($_GET);
	echo('</pre>');*/

	$parametros = $_POST;
	$parametrosg = $_GET;
	$mensaje1 = '';
	$mensaje2 = '';
	$error = '';
	$insertaOficinas = '';
	$conexion = conexion_hit();


	
	if(count($_POST) == 0){

		$parametros['filadesde'] = 1;
		$parametros['botonSelector'] = 1;
		$parametros['buscar_oficina'] = "";
		$parametros['buscar_telefono'] = "";
		$parametros['buscar_direccion'] = "";
		$parametros['buscar_codigo_postal'] = "";
		$parametros['buscar_provincia'] = "";
		$parametros['ir_a'] = "";
	}

	//ECHO($parametros['botonSelector']);
	
		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'V' and $parametros['ir_a'] != 'O'){



			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonMinoristas_Oficinas = new clsMinoristas_Oficinas($conexion, $filadesde, $usuario, $parametros['buscar_oficina'], $parametros['buscar_telefono'], $parametros['buscar_direccion'], $parametros['buscar_codigo_postal'], $parametros['buscar_provincia']);
				$botonselec = $botonMinoristas_Oficinas->Botones_selector($filadesde, $parametros['botonSelector'], $parametrosg['id']);
				//echo($botonselec);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();


			//Llamada a la clase especifica de la pantalla
			$oMinoristas_Oficinas = new clsMinoristas_Oficinas($conexion, $filadesde, $usuario, $parametros['buscar_oficina'], $parametros['buscar_telefono'], $parametros['buscar_direccion'], $parametros['buscar_codigo_postal'], $parametros['buscar_provincia']);
			$sMinoristas_Oficinas = $oMinoristas_Oficinas->Cargar($parametrosg['id']);
			$sComboSelectMinoristas_Oficinas = $oMinoristas_Oficinas->Cargar_combo_selector($parametrosg['id']);


			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboProvincias = $oSino->Cargar_combo_Provincias();

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
			$smarty->assign('formulario', '» OFICINAS: '.$parametrosg['nombre']);


			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectMinoristas_Oficinas);

			//Cargar combos de las linea de busqueda
			$smarty->assign('comboProvincias', $comboProvincias);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('minoristas_Oficinas', $sMinoristas_Oficinas);


			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_oficina', $parametros['buscar_oficina']);
			$smarty->assign('buscar_telefono', $parametros['buscar_telefono']);
			$smarty->assign('buscar_direccion', $parametros['buscar_direccion']);
			$smarty->assign('buscar_codigo_postal', $parametros['buscar_codigo_postal']);
			$smarty->assign('buscar_provincia', $parametros['buscar_provincia']);

			//Visualizar plantilla
			$smarty->display('plantillas/Minoristas_Oficinas.html');

		}elseif($parametros['ir_a'] == 'V'){
			header("Location: Minoristas.php?id=".$parametrosg['id']);
		}elseif($parametros['ir_a'] == 'O'){

			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'MINORISTAS_OFICINAS' AND USUARIO = '".$usuario."'");
			$Nfilas	 = $num_filas->fetch_assoc();
			$seleccionada= 'N';
			for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
				if($parametros['selec'.$num_fila] == 'S'){
					$seleccionada = 'S';
					header("Location: Oficinas.php?id=".$parametrosg['id']."&oficina=".$parametros['oficina'.$num_fila]);
					//echo('buscando check');
				}
			}
			if($seleccionada == 'N'){
				header("Location: Oficinas.php?id=".$parametrosg['id']."&oficina=".$parametros['oficina0']);
			}
		}else{
			$conexion->close();
			session_destroy();
			exit;
		}


	//}

	$conexion->close();


?>

