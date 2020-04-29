<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/Grupos_Minoristas.cls.php';


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
	$insertaDepartamentos = '';
	$conexion = conexion_hit();


	
	if(count($_POST) == 0){

		$parametros['filadesde'] = 1;
		$parametros['botonSelector'] = 1;
		$parametros['buscar_codigo'] = "";
		$parametros['buscar_nombre'] = "";
		$parametros['ir_a'] = "";
	}

	//ECHO($parametros['botonSelector']);
	
		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'V' and $parametros['ir_a'] != 'M'){



			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonGrupos_Minoristas = new clsGrupos_Minoristas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
				$botonselec = $botonGrupos_Minoristas->Botones_selector($filadesde, $parametros['botonSelector'],$parametrosg['grupo']);
				//echo($botonselec);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();


			//Llamada a la clase especifica de la pantalla
			$oGrupos_Minoristas = new clsGrupos_Minoristas($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
			$sGrupos_Minoristas = $oGrupos_Minoristas->Cargar($parametrosg['grupo']);
			$sComboSelectGrupos_Minoristas = $oGrupos_Minoristas->Cargar_combo_selector($parametrosg['grupo']);


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
			$smarty->assign('formulario', '» MINORISTAS: '.$parametrosg['nombre']);

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectGrupos_Minoristas);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('grupos_minoristas', $sGrupos_Minoristas);


			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);

			//Visualizar plantilla
			$smarty->display('plantillas/Grupos_Minoristas.html');

		}elseif($parametros['ir_a'] == 'V'){
			header("Location: Grupos_gestion.php?grupo=".$parametrosg['grupo']);
		}elseif($parametros['ir_a'] == 'M'){

			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'GRUPOS_MINORISTAS' AND USUARIO = '".$usuario."'");
			$Nfilas	 = $num_filas->fetch_assoc();
			$seleccionada= 'N';
			for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
				if($parametros['selec'.$num_fila] == 'S'){
					$seleccionada = 'S';
					header("Location: Minoristas.php?id=".$parametros['id'.$num_fila]."&nombre=".$parametros['nombre'.$num_fila]);
				}
			}
			if($seleccionada == 'N'){
				header("Location: Minoristas.php?id=".$parametros['id0']."&nombre=".$parametros['nombre0']);
			}
		}else{
			$conexion->close();
			session_destroy();
			exit;
		}


	//}

	$conexion->close();


?>

