<?php
	require ('Smarty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/interfaz_disponibilidad.cls.php';


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
	$insertaInterfaz_disponibilidad = '';
	$conexion = conexion_hit();

	//Variables para recuperar nuevo registro en caso de error
	/*$recuperacodigo = '';
	$recuperanombre = '';
	$recuperaarea = '';
	$recuperapais = '';
	$recuperadepartamento = '';
	$recuperaprovincia = '';*/

	
	if(count($_POST) != 0){
		
		if($parametros['ir_a'] != 'S'){
			//$filadesde = $parametros['filadesde'];
			$filadesde = 1;



			//Llamada a la clase especifica de la pantalla
			$oInterfaz_disponibilidad = new clsInterfaz_disponibilidad($conexion, $filadesde, $usuario, $parametros['buscar_interfaz'], $parametros['buscar_provincia'], $parametros['buscar_poblacion'], $parametros['buscar_categoria'], $parametros['buscar_fecha_entrada'], $parametros['buscar_fecha_salida'], $parametros['buscar_hotel'], $parametros['buscar_habitaciones'], $parametros['buscar_adultos'], $parametros['buscar_ninos']);
			$sInterfaz_disponibilidad = $oInterfaz_disponibilidad->Cargar();
			//lineas visualizadas
			$vueltas = count($sInterfaz_disponibilidad);
			//echo($vueltas);

			/*if($parametros['actuar'] == 'S'){

				//AÑADIR HOTELES DE INTERFAZ
				//$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'CIUDADES' AND USUARIO = '".$usuario."'");
				//$Nfilas	 = $num_filas->fetch_assoc(); ANTIGUO CONTROL DE LINEAS
				$Ntransacciones = 0;

				//for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) { ANTIGUO CONTRO,L DE LINEAS
				for ($num_fila = 0; $num_fila <= $vueltas-1; $num_fila++) {
					if($parametros['selec'.$num_fila] == 'S' ){

						//ECHO($num_fila." - ".$parametros['numero'.$num_fila]." - ". $parametros['codigo'.$num_fila]." - ". $parametros['nombre'.$num_fila]." - ".$parametros['poblacion'.$num_fila]." <BR>");

						$Interfaz_disponibilidad = new clsInterfaz_disponibilidad($conexion, $filadesde, $usuario, $parametros['buscar_interfaz'], $parametros['buscar_provincia'], $parametros['buscar_poblacion'], $parametros['buscar_categoria'], $parametros['buscar_fecha_entrada'], $parametros['buscar_fecha_salida'], $parametros['buscar_hotel']);

						$insertaInterfaz_disponibilidad = $Interfaz_disponibilidad->Insertar($parametros['codigo'.$num_fila],$parametros['ciudad_provincia'.$num_fila]);

						if($insertaInterfaz_disponibilidad == 'OK'){
							$Ntransacciones++;
						}else{
							$error = $insertaInterfaz_disponibilidad;
						}
					}
				}


				//Mostramos mensajes y posibles errores
				$mensaje1 = "<div><font color='#003399' size='3' ><b>Transacciones realizadas: ".$Ntransacciones."</b></font></div>";
				if($error != ''){
					$mensaje2 = "<div><font color='#993300' size='3' ><b>Se ha producido el siguiente error: ".$error."</b></font></div>";
				}
				//echo ($Ntransacciones.' transacciones realizadas correctamente');
				//$num_filas->close(); ANTIGUO CONTROL DE LINEAS
			}*/


			//$sInterfaz_disponibilidad = $oInterfaz_disponibilidad->Cargar();

			$oCombos = new clsGeneral($conexion);
			$comboPronvincias = $oCombos->Cargar_combo_Provincias();
			$comboInterfaces = $oCombos->Cargar_combo_Interfaces();
			$comboCiudades_Provincia = $oCombos->Cargar_combo_Ciudades_Provincia($parametros['buscar_provincia']);
			$comboAlojamientos_Provincia = $oCombos->Cargar_combo_AlojamientosProvincias($parametros['buscar_provincia']);



			/*echo('<pre>');
			print_r($comboContinentes);
			echo('</pre>');*/

			$smarty = new Smarty;

			//Aquí enviamos la cabecera y el pie con el Menu general y la linea del final a la plantilla
			$smarty->assign('cabecera', 'plantillas/Cabecera.html');
			$smarty->assign('menu', 'plantillas/Menu.html');
			$smarty->assign('pie', 'plantillas/Pie.html');

			//Nombre del formulario
			$smarty->assign('color_opcion', '#118FD8');
			$smarty->assign('grupo', '» INTERFACES');
			$smarty->assign('subgrupo', '');
			$smarty->assign('formulario', '» DISPONIBILIDAD');	
			
			$smarty->assign('filades', $filadesde);
			$smarty->assign('interfaz_disponibilidad', $sInterfaz_disponibilidad);
			$smarty->assign('cantidad_hoteles', $vueltas);

			//$smarty->assign('combo', $sComboSelectInterfaz_disponibilidad);
			$smarty->assign('comboProvincias', $comboPronvincias);
			$smarty->assign('comboInterfaces', $comboInterfaces);
			$smarty->assign('comboCiudades_Provincia', $comboCiudades_Provincia);
			$smarty->assign('comboAlojamientos_Provincia', $comboAlojamientos_Provincia);



			$smarty->assign('nombre', $nombre);
			$smarty->assign('mensaje1', $mensaje1);
			$smarty->assign('mensaje2', $mensaje2);

			$smarty->assign('buscar_interfaz', $parametros['buscar_interfaz']);
			$smarty->assign('buscar_provincia', $parametros['buscar_provincia']);
			$smarty->assign('buscar_poblacion', $parametros['buscar_poblacion']);
			$smarty->assign('buscar_categoria', $parametros['buscar_categoria']);
			$smarty->assign('buscar_fecha_entrada', $parametros['buscar_fecha_entrada']);
			$smarty->assign('buscar_fecha_salida', $parametros['buscar_fecha_salida']);
			$smarty->assign('buscar_hotel', $parametros['buscar_hotel']);
			$smarty->assign('buscar_habitaciones', $parametros['buscar_habitaciones']);
			$smarty->assign('buscar_adultos', $parametros['buscar_adultos']);
			$smarty->assign('buscar_ninos', $parametros['buscar_ninos']);
			$smarty->assign('alojamientos_sistema', $parametros['alojamientos_sistema']);

			$smarty->display('plantillas/Interfaz_disponibilidad.html');

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
		$parametros['buscar_interfaz'] = "";
		$parametros['buscar_provincia'] = "";
		$parametros['buscar_poblacion'] = "";
		$parametros['buscar_categoria'] = "";
		$parametros['buscar_fecha_entrada'] = "";
		$parametros['buscar_fecha_salida'] = "";
		$parametros['buscar_hotel'] = "";
		$parametros['buscar_habitaciones'] = "";
		$parametros['buscar_adultos'] = "";
		$parametros['buscar_ninos'] = "";
		$parametros['alojamientos_sistema'] = "";

		$oInterfaz_disponibilidad = new clsInterfaz_disponibilidad($conexion, $filadesde, $usuario, $parametros['buscar_interfaz'], $parametros['buscar_provincia'], $parametros['buscar_poblacion'], $parametros['buscar_categoria'], $parametros['buscar_fecha_entrada'], $parametros['buscar_fecha_salida'], $parametros['buscar_hotel'], $parametros['buscar_habitaciones'], $parametros['buscar_adultos'], $parametros['buscar_ninos']);
		$sInterfaz_disponibilidad = $oInterfaz_disponibilidad->Cargar();
		//$sInterfaz_disponibilidadnuevos = $oInterfaz_disponibilidad->Cargar_lineas_nuevas();
		//$sComboSelectInterfaz_disponibilidad = $oInterfaz_disponibilidad->Cargar_combo_selector();

		$oCombos = new clsGeneral($conexion);
		$comboPronvincias = $oCombos->Cargar_combo_Provincias();
		$comboInterfaces = $oCombos->Cargar_combo_Interfaces();
		$comboCiudades_Provincia = $oCombos->Cargar_combo_Ciudades_Provincia($parametros['buscar_provincia']);
		$comboAlojamientos_Provincia = $oCombos->Cargar_combo_AlojamientosProvincias($parametros['buscar_provincia']);

		/*echo('<pre>');
		print_r($comboContinentes);
		echo('</pre>');*/

		$smarty = new Smarty;

		$smarty->assign('cabecera', 'plantillas/Cabecera.html');
		$smarty->assign('menu', 'plantillas/Menu.html');
		$smarty->assign('pie', 'plantillas/Pie.html');

		//Nombre del formulario
		$smarty->assign('color_opcion', '#118FD8');
		$smarty->assign('grupo', '» INTERFACES');
		$smarty->assign('subgrupo', '');
		$smarty->assign('formulario', '» DISPONIBILIDAD');
		//$smarty->assign('filades', $filadesde);
		$smarty->assign('interfaz_disponibilidad', $sInterfaz_disponibilidad);
		$smarty->assign('cantidad_hoteles', 0);

		//Cargar lineas nuevas (Recuperando valores tecleados por el usuario en caso de error al insertar registro)
		/*$smarty->assign('interfaz_disponibilidad_nuevos', $sInterfaz_disponibilidadnuevos);
		$smarty->assign('recuperacodigo', $recuperacodigo);
		$smarty->assign('recuperanombre', $recuperanombre);
		$smarty->assign('recuperaarea', $recuperaarea);
		$smarty->assign('recuperapais', $recuperapais);
		$smarty->assign('recuperadepartamento', $recuperadepartamento);
		$smarty->assign('recuperaprovincia', $recuperaprovincia);*/

		//$smarty->assign('combo', $sComboSelectInterfaz_disponibilidad);

		$smarty->assign('comboProvincias', $comboPronvincias);
		$smarty->assign('comboInterfaces', $comboInterfaces);
		$smarty->assign('comboCiudades_Provincia', $comboCiudades_Provincia);
		$smarty->assign('comboAlojamientos_Provincia', $comboAlojamientos_Provincia);

		$smarty->assign('nombre', $nombre);
		$smarty->assign('mensaje1', $mensaje1);
		$smarty->assign('mensaje2', $mensaje2);

		$smarty->assign('buscar_interfaz', '');
		$smarty->assign('buscar_provincia', '');
		$smarty->assign('buscar_poblacion', '');
		$smarty->assign('buscar_categoria', '');
		$smarty->assign('buscar_fecha_entrada', '');
		$smarty->assign('buscar_fecha_salida', '');
		$smarty->assign('buscar_hotel', '');
		$smarty->assign('buscar_habitaciones', '');
		$smarty->assign('buscar_adultos', '');
		$smarty->assign('buscar_ninos', '');
		$smarty->assign('alojamientos_sistema', '');


		$smarty->display('plantillas/Interfaz_disponibilidad.html');
	}

	$conexion->close();


?>

