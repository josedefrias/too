<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/logos.cls.php';


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
	print_r($_FILES);
	echo('</pre>');*/


	$parametros = $_POST;
	$parametrosg = $_GET;
	$mensaje1 = '';
	$mensaje2 = '';
	$error = '';
	$insertaLogos = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperanombre = '';
	$recuperacondicion = '';
	//$recuperafichero = '';
	
	if(count($_POST) != 0){

		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonLogos = new clsLogos($conexion, $filadesde, $usuario, $parametros['buscar_nombre']);
				$botonselec = $botonLogos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'LOGOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if(@$parametros['selec'.$num_fila] == 'S' || @$parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mLogos = new clsLogos($conexion, $filadesde, $usuario, $parametros['buscar_nombre']);
							$modificaLogos = $mLogos->Modificar($parametros['id'.$num_fila], $parametros['nombre'.$num_fila], $parametros['condicion'.$num_fila],$parametros['fichero'.$num_fila]);
							if($modificaLogos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaLogos;
								
							}
						}else{

							$mLogos = new clsLogos($conexion, $filadesde, $usuario, $parametros['buscar_nombre']);
							$borraLogos = $mLogos->Borrar($parametros['id'.$num_fila]);
							if($borraLogos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraLogos;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'LOGOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						//SUBIMOS LOS ARCHIVOS DE IMAGEN

						$max=1000000; //Establece el tamaño maximo del archivo
						$filesize = $_FILES['upfile'.$num_fila]['size']; //obtiene el tamaño del archivo
						$filename = trim($_FILES['upfile'.$num_fila]['name']); // obtiene el nombre del archivo
						$type = $_FILES['upfile'.$num_fila]['type']; //obtiene el tipo del archivo


						if($filesize < $max){
							if($filesize > 0){
								if($type == 'image/jpeg' || $type == 'image/JPEG' || $type == 'image/jpg' || $type == 'image/JPG' 
									|| $type == 'image/gif' || $type == 'image/GIF' || $type == 'image/png' || $type == 'image/PNG' ){
									$uploadfile = "imagenes/logos/".str_replace(' ','_',$parametros['Nuevonombre'.$num_fila]).".jpg";
									if (move_uploaded_file($_FILES['upfile'.$num_fila]['tmp_name'], $uploadfile)) {
										$error = "<div><font color='#003399' size='3' ><b> Archivo subido correctamente.";
										
										//SI LA IMAGEN A SUBIDO CORRECTAMENTE GRABAMOS EL REGISTRO
										$iLogos = new clsLogos($conexion, $filadesde, $usuario, $parametros['buscar_nombre']);
										$insertaLogos = $iLogos->Insertar($parametros['Nuevonombre'.$num_fila], $parametros['Nuevocondicion'.$num_fila],$parametros['Nuevonombre'.$num_fila].".jpg");
										if($insertaLogos == 'OK'){
											$Ntransacciones++;
										}else{
											$error = "<div><font color='#993300' size='3' ><b> Se ha producido el siguiente error: ".$insertaLogos;
											//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
											$recuperacodigo = $parametros['Nuevonombre'.$num_fila]; 
											/*$recuperanombre = $parametros['Nuevofichero'.$num_fila];*/
										}

									}else{
										$error = "<div><font color='#003399' size='3' ><b> Error de conexión con el servidor.";
									}
								}else{
									$error = "<div><font color='#003399' size='3' ><b> Sólo se permiten imágenes en formato jpg. y gif., no se ha podido adjuntar.";
								}
							}else{
								$error = "<div><font color='#003399' size='3' ><b> No ha seleccionado ninguna imagen. Seleccione una y vuelva a intentarlo";
							}
						}else{
							$error = "<div><font color='#003399' size='3' ><b> La imagen que ha intentado adjuntar es mayor de 1000 KB, si lo desea cambie el tamaño del archivo y vuelva a intentarlo.";
						}


						/*$iLogos = new clsLogos($conexion, $filadesde, $usuario, $parametros['buscar_nombre']);
						$insertaLogos = $iLogos->Insertar($parametros['Nuevoalojamiento'.$num_fila], $parametros['Nuevotipo'.$num_fila], $parametros['Nuevonumero'.$num_fila]);
						if($insertaLogos == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaLogos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacodigo = $parametros['Nuevoalojamiento'.$num_fila]; 
							$recuperanombre = $parametros['Nuevotipo'.$num_fila];
							$recuperanumero = $parametros['Nuevonumero'.$num_fila];
						}*/
					}
				}


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = $error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				$num_filas->close();
			}

			//Llamada a la clase especifica de la pantalla
			$oLogos = new clsLogos($conexion, $filadesde, $usuario, $parametros['buscar_nombre']);
			$sLogos = $oLogos->Cargar();
			$sLogosnuevos = $oLogos->Cargar_lineas_nuevas();
			$sComboSelectLogos = $oLogos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboLogos = $oCombo->Cargar_combo_Logos();
			$comboTipo_Condiciones = $oCombo->Cargar_combo_Tipo_Condiciones();


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
			$smarty->assign('color_opcion', '#027767');
			$smarty->assign('grupo', '» MARKETING');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» IMAGENES Y LOGOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectLogos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('logos', $sLogos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboLogos', $comboLogos);
			$smarty->assign('comboTipo_Condiciones', $comboTipo_Condiciones);


			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('logos_nuevos', $sLogosnuevos);			
			$smarty->assign('recuperanombre', $recuperanombre);	
			$smarty->assign('recuperacondicion', $recuperacondicion);


			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);

			//Visualizar plantilla
			$smarty->display('plantillas/Logos.html');


		}else{
			session_destroy();
			exit;
			/*$smarty = new Smarty;
			$smarty->assign('mensaje', ' ');
			$smarty->assign('pie', 'plantillas/Pie.html');
			$smarty->display('plantillas/Index.html');*/
		}


	}else{

		$filadesde = 1;

			$parametros['buscar_nombre'] = "";

			//Llamada a la clase especifica de la pantalla
			$oLogos = new clsLogos($conexion, $filadesde, $usuario, $parametros['buscar_nombre']);
			$sLogos = $oLogos->Cargar();
			$sLogosnuevos = $oLogos->Cargar_lineas_nuevas();
			$sComboSelectLogos = $oLogos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboLogos = $oCombo->Cargar_combo_Logos();
			$comboTipo_Condiciones = $oCombo->Cargar_combo_Tipo_Condiciones();

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
			$smarty->assign('color_opcion', '#027767');
			$smarty->assign('grupo', '» MARKETING');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» IMAGENES Y LOGOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectLogos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('logos', $sLogos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboLogos', $comboLogos);
			$smarty->assign('comboTipo_Condiciones', $comboTipo_Condiciones);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('logos_nuevos', $sLogosnuevos);			
			$smarty->assign('recuperanombre', $recuperanombre);	
			$smarty->assign('recuperacondicion', $recuperacondicion);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);

		//Visualizar plantilla
		$smarty->display('plantillas/Logos.html');
	}

	$conexion->close();


?>

