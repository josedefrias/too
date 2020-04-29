<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/destinos.cls.php';


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
	$insertaDestinos = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	$recuperacodigo = '';
	$recuperanombre = '';
	$recuperagrupo = '';
	$recuperapais = '';
	$recuperavisible = '';
	$recuperavisible_grupos = '';
	$recuperapaginacion_web_con_aereos = '';
	$recuperapaginacion_web_resto = '';
	$recuperatelefono_contacto = '';
	$recuperaprovincia = '';
	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			$filadesde = $parametros['filadesde'];
			//Si se ha pulsado algun boton del selector de registros situamos el combo segun el boton pulsado
			if($parametros['botonSelector'] != 0){
				$botonDestinos = new clsDestinos($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_grupo']);
				$botonselec = $botonDestinos->Botones_selector($filadesde, $parametros['botonSelector']);
				$filadesde = $botonselec;
			}

			//Llamada a la clase especifica de la pantalla
			$oDestinos = new clsDestinos($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_grupo']);
			$sDestinos = $oDestinos->Cargar();
			//lineas visualizadas
			$vueltas = count($sDestinos);

			if($parametros['actuar'] == 'S'){

				//MODIFICAR Y BORRAR REGISTROS
				/*$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'DESTINOS' AND USUARIO = '".$usuario."'");
				$Nfilas	 = $num_filas->fetch_assoc();*/
				$Ntransacciones = 0;

				for ($num_fila = 0; $num_fila <= $vueltas -1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' || $parametros['borra'.$num_fila] == 'S'){
						if($parametros['selec'.$num_fila] == 'S'){
							
							$mDestinos = new clsDestinos($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_grupo']);
							$modificaDestinos = $mDestinos->Modificar($parametros['codigo'.$num_fila], $parametros['nombre'.$num_fila], $parametros['grupo'.$num_fila], $parametros['pais'.$num_fila], $parametros['visible'.$num_fila], $parametros['visible_grupos'.$num_fila], $parametros['paginacion_web_con_aereos'.$num_fila], $parametros['paginacion_web_resto'.$num_fila], $parametros['telefono_contacto'.$num_fila], $parametros['provincia'.$num_fila]);
							if($modificaDestinos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $modificaDestinos;
								
							}
						}else{

							$mDestinos = new clsDestinos($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_grupo']);
							$borraDestinos = $mDestinos->Borrar($parametros['codigo'.$num_fila]);
							if($borraDestinos == 'OK'){
								$Ntransacciones++;
							}else{
								$error = $borraDestinos;
								
							}
						}
					}
				}

				//AÑADIR REGISTROS
				$num_filas_nuevas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'DESTINOS' AND USUARIO = '".$usuario."'");
				$Nfilas_nuevas	 = $num_filas_nuevas->fetch_assoc();
				

				for ($num_fila = 0; $num_fila <= $Nfilas_nuevas['LINEAS_NUEVAS']-1; $num_fila++) {
					
					if($parametros['Nuevoselec'.$num_fila] == 'S'){
						
						$iDestinos = new clsDestinos($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_grupo']);
						$insertaDestinos = $iDestinos->Insertar($parametros['Nuevocodigo'.$num_fila], $parametros['Nuevonombre'.$num_fila], $parametros['Nuevogrupo'.$num_fila], $parametros['Nuevopais'.$num_fila], $parametros['Nuevovisible'.$num_fila], $parametros['Nuevovisible_grupos'.$num_fila], $parametros['Nuevopaginacion_web_con_aereos'.$num_fila], $parametros['Nuevopaginacion_web_resto'.$num_fila], $parametros['Nuevotelefono_contacto'.$num_fila], $parametros['Nuevoprovincia'.$num_fila]);
						if($insertaDestinos == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaDestinos;
							//Aqui guardamos los valores para pasarlos a la pantalla para que el usuario no tenga que volver a escribirlos en caso de error.
							$recuperacodigo = $parametros['Nuevocodigo'.$num_fila]; 
							$recuperanombre = $parametros['Nuevonombre'.$num_fila];
							$recuperagrupo = $parametros['Nuevogrupo'.$num_fila];
							$recuperapais = $parametros['Nuevopais'.$num_fila];
							$recuperavisible = $parametros['Nuevovisible'.$num_fila];
							$recuperavisible_grupos = $parametros['Nuevovisible_grupos'.$num_fila];
							$recuperapaginacion_web_con_aereos= $parametros['Nuevopaginacion_web_con_aereos'.$num_fila];
							$recuperapaginacion_web_resto = $parametros['Nuevopaginacion_web_resto'.$num_fila];
							$recuperatelefono_contacto = $parametros['Nuevotelefono_contacto'.$num_fila];
							$recuperaprovincia = $parametros['Nuevoprovincia'.$num_fila];
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
			$sDestinos = $oDestinos->Cargar();
			$sDestinosnuevos = $oDestinos->Cargar_lineas_nuevas();
			$sComboSelectDestinos = $oDestinos->Cargar_combo_selector();

			//Llamada a la clase general de combos
			$oSino = new clsGeneral($conexion);
			$comboSino = $oSino->Cargar_combo_SiNo_Si();			
			$comboPaises = $oSino->Cargar_combo_Paises();
			$comboDestinos_grupos = $oSino->Cargar_combo_Destinos_Grupos();	
			$comboProvincias_pais = $oSino->Cargar_combo_Provincias_Seleccion1();	

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
			$smarty->assign('subgrupo', '» ESPECIFICO WEB');
			$smarty->assign('formulario', '» DESTINOS');

			//Nombre del usuario de la sesion
			$smarty->assign('nombre', $nombre);

			//Numero de fila para situar el selector de registros
			$smarty->assign('filades', $filadesde);

			//Cargar combo selector
			$smarty->assign('combo', $sComboSelectDestinos);

			//Cargar lineas de la tabla para visualizar modificar o borrar
			$smarty->assign('destinos', $sDestinos);

			//Cargar combos de las lineas de la tabla
			$smarty->assign('comboSino', $comboSino);
			$smarty->assign('comboPaises', $comboPaises);	
			$smarty->assign('comboDestinos_grupos', $comboDestinos_grupos);
			$smarty->assign('comboProvincias_pais', $comboProvincias_pais);


			//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
			$smarty->assign('destinos_nuevos', $sDestinosnuevos);			
			$smarty->assign('recuperacodigo', $recuperacodigo);	
			$smarty->assign('recuperanombre', $recuperanombre);
			$smarty->assign('recuperagrupo', $recuperagrupo);
			$smarty->assign('recuperapais', $recuperapais);	
			$smarty->assign('recuperavisible', $recuperavisible);
			$smarty->assign('recuperavisible_grupos', $recuperavisible_grupos);
			$smarty->assign('recuperapaginacion_web_con_aereos', $recuperapaginacion_web_con_aereos);
			$smarty->assign('recuperapaginacion_web_resto', $recuperapaginacion_web_resto);
			$smarty->assign('recuperatelefono_contacto', $recuperatelefono_contacto);
			$smarty->assign('recuperaprovincia', $recuperaprovincia);

			//Cargar mensaje de numero de transacciones realizadas
			$smarty->assign('mensaje1', $mensaje1);

			//Cargar mensaje de error si se da error
			$smarty->assign('mensaje2', $mensaje2);

			//Cargar campos de busqueda de la tabla tecleados por el usuario
			$smarty->assign('buscar_codigo', $parametros['buscar_codigo']);
			$smarty->assign('buscar_nombre', $parametros['buscar_nombre']);
			$smarty->assign('buscar_pais', $parametros['buscar_pais']);
			$smarty->assign('buscar_grupo', $parametros['buscar_grupo']);

			//Visualizar plantilla
			$smarty->display('plantillas/Destinos.html');


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
		$parametros['buscar_pais'] = "";
		$parametros['buscar_grupo'] = "";

		//Llamada a la clase especifica de la pantalla
		$oDestinos = new clsDestinos($conexion, $filadesde, $usuario, $parametros['buscar_codigo'], $parametros['buscar_nombre'], $parametros['buscar_pais'], $parametros['buscar_grupo']);
		$sDestinos = $oDestinos->Cargar();
		$sDestinosnuevos = $oDestinos->Cargar_lineas_nuevas();
		$sComboSelectDestinos = $oDestinos->Cargar_combo_selector();

		//Llamada a la clase general de combos
		$oSino = new clsGeneral($conexion);
		$comboSino = $oSino->Cargar_combo_SiNo_Si();
		$comboPaises = $oSino->Cargar_combo_Paises();	
		$comboDestinos_grupos = $oSino->Cargar_combo_Destinos_Grupos();	
		$comboProvincias_pais = $oSino->Cargar_combo_Provincias_Seleccion1();	
		
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
		$smarty->assign('subgrupo', '» ESPECIFICO WEB');
		$smarty->assign('formulario', '» DESTINOS');


		//Nombre del usuario de la sesion
		$smarty->assign('nombre', $nombre);

		//Numero de fila para situar el selector de registros
		$smarty->assign('filades', $filadesde);

		//Cargar combo selector
		$smarty->assign('combo', $sComboSelectDestinos);

		//Cargar lineas de la tabla para visualizar modificar o borrar
		$smarty->assign('destinos', $sDestinos);

		//Cargar combos de las lineas de la tabla
		$smarty->assign('comboSino', $comboSino);
		$smarty->assign('comboPaises', $comboPaises);
		$smarty->assign('comboDestinos_grupos', $comboDestinos_grupos);	
		$smarty->assign('comboProvincias_pais', $comboProvincias_pais);		

		
		//Cargar lineas nuevas (Recuprando valores tecleados por el usuario en caso de error al insertar registro)
		$smarty->assign('destinos_nuevos', $sDestinosnuevos);			
		$smarty->assign('recuperacodigo', $recuperacodigo);	
		$smarty->assign('recuperanombre', $recuperanombre);
		$smarty->assign('recuperagrupo', $recuperagrupo);
		$smarty->assign('recuperapais', $recuperapais);		
		$smarty->assign('recuperavisible', $recuperavisible);
		$smarty->assign('recuperavisible_grupos', $recuperavisible_grupos);
		$smarty->assign('recuperapaginacion_web_con_aereos', $recuperapaginacion_web_con_aereos);
		$smarty->assign('recuperapaginacion_web_resto', $recuperapaginacion_web_resto);	
		$smarty->assign('recuperatelefono_contacto', $recuperatelefono_contacto);
		$smarty->assign('recuperaprovincia', $recuperaprovincia);

		//Cargar mensaje de numero de transacciones realizadas
		$smarty->assign('mensaje1', $mensaje1);

		//Cargar mensaje de error si se da error
		$smarty->assign('mensaje2', $mensaje2);

		//Cargar campos de busqueda de la tabla tecleados por el usuario
		$smarty->assign('buscar_codigo', '');
		$smarty->assign('buscar_nombre', '');
		$smarty->assign('buscar_pais', '');
		$smarty->assign('buscar_grupo', '');

		//Visualizar plantilla
		$smarty->display('plantillas/Destinos.html');
	}

	$conexion->close();


?>

