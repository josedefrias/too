<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/alojamientos_imagenes.cls.php';


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
	$insertaAlojamientos_imagenes = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperaalojamiento = '';
	$recuperatipo = '';
	$recuperanumero = '';
	
	if(count($_POST) != 0){
		//esto es por si venimos de la pantalla de Alojamientos
		if($parametros['buscar_alojamiento'] == null and $parametros['buscar_tipo'] == null){
			if(count($_GET) != 0){
				$parametros['buscar_alojamiento'] = $parametrosg['id'];
			}
		}

		
		if($parametros['ir_a'] != 'S' and $parametros['ir_a'] != 'V'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonAlojamientos_imagenes = new clsAlojamientos_imagenes($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento'], $parametros['buscar_tipo']);
				$botonselec = $botonAlojamientos_imagenes->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}
			/*echo($filadesde);
			echo('<BR>');*/
			//$conexion = conexion_hit();

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_IMAGENES' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mAlojamientos = new clsAlojamientos_imagenes($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento'], $parametros['buscar_tipo']);
							$modificaAlojamientos = $mAlojamientos->Modificar($parametros['id'.$num_fila], $parametros['alojamiento'.$num_fila],$parametros['tipo'.$num_fila],$parametros['numero'.$num_fila],$parametros['url'.$num_fila]);
							if($modificaAlojamientos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaAlojamientos;
								
							}
						}else{

							$mAlojamientos_imagenes = new clsAlojamientos_imagenes($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento'], $parametros['buscar_tipo']);
							$borraAlojamientos_imagenes = $mAlojamientos_imagenes->Borrar($parametros['id'.$num_fila]);
							if($borraAlojamientos_imagenes == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraAlojamientos_imagenes;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_IMAGENES' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						//SUBIMOS LOS ARCHIVOS DE IMAGEN

						$max=2500000; //Establece el tamaño maximo del archivo
						$filesize = $_FILES['upfile'.$num_fila]['size']; //obtiene el tamaño del archivo
						$filename = trim($_FILES['upfile'.$num_fila]['name']); // obtiene el nombre del archivo
						$type = $_FILES['upfile'.$num_fila]['type']; //obtiene el tipo del archivo


						if($filesize < $max){
							if($filesize > 0){
								if($type == 'image/jpeg' || $type == 'image/JPEG' || $type == 'image/jpg' || $type == 'image/JPG' 
									|| $type == 'image/gif' || $type == 'image/GIF' ){
									$uploadfile = "imagenes/alojamientos/".$parametros['Nuevoalojamiento'.$num_fila]."_".$parametros['Nuevotipo'.$num_fila]."_".$parametros['Nuevonumero'.$num_fila].".jpg";
									if (move_uploaded_file($_FILES['upfile'.$num_fila]['tmp_name'], $uploadfile)) {
										$error = "<div><font color='#003399' size='3' ><b> Archivo subido correctamente.";
										
										//SI LA IMAGEN A SUBIDO CORRECTAMENTE GRABAMOS EL REGISTRO
										$iAlojamientos_imagenes = new clsAlojamientos_imagenes($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento'], $parametros['buscar_tipo']);
										$insertaAlojamientos_imagenes = $iAlojamientos_imagenes->Insertar($parametros['Nuevoalojamiento'.$num_fila], $parametros['Nuevotipo'.$num_fila], $parametros['Nuevonumero'.$num_fila]);
										if($insertaAlojamientos_imagenes == 'OK'){
											$Ntransacciones++;
										}else{
											$error = "<div><font color='#993300' size='3' ><b> Se ha producido el siguiente error: ".$insertaAlojamientos_imagenes;
											//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
											$recuperacodigo = $parametros['Nuevoalojamiento'.$num_fila]; 
											$recuperanombre = $parametros['Nuevotipo'.$num_fila];
											$recuperanumero = $parametros['Nuevonumero'.$num_fila];
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




						/*$iAlojamientos_imagenes = new clsAlojamientos_imagenes($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento'], $parametros['buscar_tipo']);
						$insertaAlojamientos_imagenes = $iAlojamientos_imagenes->Insertar($parametros['Nuevoalojamiento'.$num_fila], $parametros['Nuevotipo'.$num_fila], $parametros['Nuevonumero'.$num_fila]);
						if($insertaAlojamientos_imagenes == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaAlojamientos_imagenes;
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
			$oAlojamientos_imagenes = new clsAlojamientos_imagenes($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento'], $parametros['buscar_tipo']);
			$sAlojamientos_imagenes = $oAlojamientos_imagenes->Cargar();
			$sAlojamientos_imagenesnuevos = $oAlojamientos_imagenes->Cargar_lineas_nuevas();
			$sComboSelectAlojamientos_imagenes = $oAlojamientos_imagenes->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboAlojamientos = $oCombo->Cargar_combo_Alojamientos();
			$comboTipos = $oCombo->Cargar_combo_Tipo_imagenes();
			$comboNumeros = $oCombo->Cargar_combo_Numeros_imagenes();

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
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
			$smarty->assign('formulario', '» IMAGENES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAlojamientos_imagenes);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos_imagenes', $sAlojamientos_imagenes);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboTipos', $comboTipos);
			$smarty->assign('comboNumeros', $comboNumeros);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('alojamientos_imagenes_nuevos', $sAlojamientos_imagenesnuevos);			
			$smarty->assign('recuperaalojamiento', $recuperaalojamiento);	
			$smarty->assign('recuperatipo', $recuperatipo);	
			$smarty->assign('recuperanumero', $recuperanumero);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_alojamiento', $parametros['buscar_alojamiento']);
			$smarty->assign('buscar_tipo', $parametros['buscar_tipo']);

			//Visualizar plantilla
			$smarty->display('plantillas/Alojamientos_imagenes.html');


		}elseif($parametros['ir_a'] == 'V'){

			header("Location: Alojamientos.php?id=".$parametros['buscar_alojamiento']);

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

		if(count($_GET) != 0 and $parametrosg['id'] != null){
			$parametros['buscar_alojamiento'] = $parametrosg['id'];
		}else{
			$parametros['buscar_alojamiento'] = "";
		}
			$parametros['buscar_tipo'] = "";

			//Llamada a la clase especifica de la pantalla
			$oAlojamientos_imagenes = new clsAlojamientos_imagenes($conexion, $filadesde, $usuario, $parametros['buscar_alojamiento'], $parametros['buscar_tipo']);
			$sAlojamientos_imagenes = $oAlojamientos_imagenes->Cargar();
			$sAlojamientos_imagenesnuevos = $oAlojamientos_imagenes->Cargar_lineas_nuevas();
			$sComboSelectAlojamientos_imagenes = $oAlojamientos_imagenes->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oCombo = new clsGeneral($conexion);
			$comboAlojamientos = $oCombo->Cargar_combo_Alojamientos();
			$comboTipos = $oCombo->Cargar_combo_Tipo_imagenes();
			$comboNumeros = $oCombo->Cargar_combo_Numeros_imagenes();

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
			$smarty->assign('color_opcion', '#0A73B0');
			$smarty->assign('grupo', '» PROVEEDORES');
			$smarty->assign('subgrupo', '» ALOJAMIENTOS');
			$smarty->assign('formulario', '» IMAGENES');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectAlojamientos_imagenes);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('alojamientos_imagenes', $sAlojamientos_imagenes);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboAlojamientos', $comboAlojamientos);
			$smarty->assign('comboTipos', $comboTipos);
			$smarty->assign('comboNumeros', $comboNumeros);

			//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('alojamientos_imagenes_nuevos', $sAlojamientos_imagenesnuevos);			
			$smarty->assign('recuperaalojamiento', $recuperaalojamiento);	
			$smarty->assign('recuperatipo', $recuperatipo);	
			$smarty->assign('recuperanumero', $recuperanumero);	

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_alojamiento', $parametros['buscar_alojamiento']);
		$smarty->assign('buscar_tipo', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Alojamientos_imagenes.html');
	}

	$conexion->close();


?>

