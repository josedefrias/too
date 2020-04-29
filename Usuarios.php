<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/usuarios.cls.php';


	session_start();

	$usuario = $_SESSION['usuario'];
	$nombre =  $_SESSION['nombre'];
	$nivel =  $_SESSION['nivel'];
	$tipo_usuario_sesion =  $_SESSION['tipo'];

	//echo($usuario.'-'.$nombre.'-'.$nivel.'-'.$tipo_usuario_sesion);
	
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
	$insertaUsuarios = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacodigo = '';
	$recuperapassword = '';
	$recuperanombre = '';
	$recuperaemail = '';
	$recuperatelefono = '';
	$recuperanivel = '';
	$recuperaactivo = '';
	$recuperatipo= '';
	$recuperacia = '';
	
	if($nivel >= 2){
	
		if(count($_POST) != 0){
			
			if($parametros['ir_a'] != 'S'){
				$filadesde = $parametros['filadesde'];
				//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
				if($parametros['botonSelector'] != 0){
					$botonUsuarios = new clsUsuarios($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
					$botonselec = $botonUsuarios->Botones_selector($filadesde, $parametros['botonSelector']);
					$filadesde = $botonselec;
				}

				//Llamada a la clase especifica de la pantalla
				$oUsuarios = new clsUsuarios($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
				$sUsuarios = $oUsuarios->Cargar();
				//lineas visualizadas
				$vueltas = count($sUsuarios);

				if($parametros['actuar'] == 'S'){

					//MODIFICAR Y BORRAR REGISTROS
					/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'USUARIOS' AND USUARIO = '".$usuario."'");
					$Nfilas	 = $num_filas->fetch_assoc();*/
					$Ntransacciones = 0;

					for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
						if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
							if($parametros['selec'.$num_fila] == 'S'){
								
								$mUsuarios = new clsUsuarios($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
								$modificaUsuarios = $mUsuarios->Modificar($parametros['usuario'.$num_fila], $parametros['password'.$num_fila],$parametros['nombre'.$num_fila],$parametros['email'.$num_fila],$parametros['telefono'.$num_fila],$parametros['nivel'.$num_fila],$parametros['activo'.$num_fila],$parametros['tipo'.$num_fila],$parametros['cia'.$num_fila]);
								if($modificaUsuarios == 'OK'){
									$Ntransacciones++;
								}else{
									$error = $modificaUsuarios;
									
								}
							}else{

								$mUsuarios = new clsUsuarios($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
								$borraUsuarios = $mUsuarios->Borrar($parametros['usuario'.$num_fila]);
								if($borraUsuarios == 'OK'){
									$Ntransacciones++;
								}else{
									$error = $borraUsuarios;

								}
							}
						}
					}

					//AÑADIR REGISTROS
					$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'USUARIOS' AND USUARIO = '".$usuario."'");
					$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();

					for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
						
						if($parametros['Nuevoselec'.$num_fila] == 'S'){
							
							$iUsuarios = new clsUsuarios($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
							$insertaUsuarios = $iUsuarios->Insertar($parametros['Nuevocodigo'.$num_fila], $parametros['Nuevopassword'.$num_fila],$parametros['Nuevonombre'.$num_fila],$parametros['Nuevoemail'.$num_fila],$parametros['Nuevotelefono'.$num_fila],$parametros['Nuevonivel'.$num_fila],$parametros['Nuevoactivo'.$num_fila],$parametros['Nuevotipo'.$num_fila],$parametros['Nuevocia'.$num_fila]);
							if($insertaUsuarios == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $insertaUsuarios;
								//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
								$recuperacodigo = $parametros['Nuevocodigo'.$num_fila]; 
								$recuperanombre = $parametros['Nuevopassword'.$num_fila];
								$recuperacontinente = $parametros['Nuevonombre'.$num_fila];
								$recuperaidioma = $parametros['Nuevoemail'.$num_fila];
								$recuperadivisa = $parametros['Nuevotelefono'.$num_fila];
								$recuperaunion_europea = $parametros['Nuevotelefono'.$num_fila];
								$recuperaprefijo = $parametros['Nuevoactivo'.$num_fila];
								$recuperatipo = $parametros['Nuevotipo'.$num_fila];
								$recuperacia = $parametros['Nuevocia'.$num_fila];
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

				}elseif($parametros['actuar'] == 'A'){
					//ACTUALIZAR FORMULARIOS
					/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'USUARIOS' AND USUARIO = '".$usuario."'");
					$Nfilas	 = $num_filas->fetch_assoc();*/
					$Ntransacciones = 0;
					
					for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {

						if($parametros['selec'.$num_fila] == 'S'){

							$aUsuarios = new clsUsuarios($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
							$ActualizaUsuarios = $aUsuarios->Actualizar($parametros['usuario'.$num_fila]);
							if($ActualizaUsuarios == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $ActualizaUsuarios;
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
				$sUsuarios = $oUsuarios->Cargar();
				$sUsuariosnuevos = $oUsuarios->Cargar_lineas_nuevas();
				$sComboSelectUsuarios = $oUsuarios->Cargar_combo_selector();

				//Llamada a la clase general de combos
				$oSino = new clsGeneral($conexion);
				$comboSino = $oSino->Cargar_combo_SiNo();
				$comboTipoUsuario = $oSino->Cargar_combo_Tipo_Usuarios();
				$comboTransportes = $oSino->Cargar_combo_Transportes_id();


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
				$smarty->assign('color_opcion', '#010080');
				$smarty->assign('grupo', '» TABLAS GENERALES');
				$smarty->assign('subgrupo', '» CONFIGURACION');
				$smarty->assign('formulario', '» USUARIOS');

				//Nombre del usuario de la sesion
				$smarty->assign('nombre', $nombre);

				//Numero de fila para situar el selector de registros
				$smarty->assign('filades', $filadesde);

				//Cargar combo selector
				$smarty->assign('combo', $sComboSelectUsuarios);

				//Cargar lineas de la tabla para visualizar modificar o borrar
				$smarty->assign('usuarios', $sUsuarios);

				//Cargar combos de las lineas de la tabla
				$smarty->assign('comboSino', $comboSino);
				$smarty->assign('comboTipoUsuario', $comboTipoUsuario);
				$smarty->assign('comboTransportes', $comboTransportes);

				//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
				$smarty->assign('usuarios_nuevos', $sUsuariosnuevos);			
				$smarty->assign('recuperacodigo', $recuperacodigo);	
				$smarty->assign('recuperapassword', $recuperapassword);	
				$smarty->assign('recuperanombre', $recuperanombre);	
				$smarty->assign('recuperaemail', $recuperaemail);	
				$smarty->assign('recuperatelefono', $recuperatelefono);	
				$smarty->assign('recuperanivel', $recuperanivel);	
				$smarty->assign('recuperaactivo', $recuperaactivo);	

				//Cargar mensaje de numero de transacciones realizadas
				$smarty->assign('mensaje1', $mensaje1);

				//Cargar mensaje de error si se da error
				$smarty->assign('mensaje2', $mensaje2);

				//Cargar campos de busqueda de la tabla tecleados por el usuario
				$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
				$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);

				//Visualizar plantilla
				$smarty->display('plantillas/Usuarios.html');


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
			$oUsuarios = new clsUsuarios($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre']);
			$sUsuarios = $oUsuarios->Cargar();
			$sUsuariosnuevos = $oUsuarios->Cargar_lineas_nuevas();
			$sComboSelectUsuarios = $oUsuarios->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo();
			$comboTipoUsuario = $oSino->Cargar_combo_Tipo_Usuarios();
			$comboTransportes = $oSino->Cargar_combo_Transportes_id();

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
			$smarty->assign('color_opcion', '#010080');
			$smarty->assign('grupo', '» TABLAS GENERALES');
			$smarty->assign('subgrupo', '» CONFIGURACION');
			$smarty->assign('formulario', '» USUARIOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectUsuarios);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('usuarios', $sUsuarios);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboTipoUsuario', $comboTipoUsuario);
			$smarty->assign('comboTransportes', $comboTransportes);

			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('usuarios_nuevos', $sUsuariosnuevos);			
			$smarty->assign('recuperacodigo', $recuperacodigo);	
			$smarty->assign('recuperapassword', $recuperapassword);	
			$smarty->assign('recuperanombre', $recuperanombre);	
			$smarty->assign('recuperaemail', $recuperaemail);	
			$smarty->assign('recuperatelefono', $recuperatelefono);	
			$smarty->assign('recuperanivel', $recuperanivel);	
			$smarty->assign('recuperaactivo', $recuperaactivo);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', '');
			$smarty->assign('buscar_nombre', '');

			//Visualizar plantilla
			$smarty->display('plantillas/Usuarios.html');
		}

		$conexion->close();
	}else{
		header("Location: Acceso_denegado.php");
	}

?>

