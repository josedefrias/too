<?php

class clsAlojamientos_servicios{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_alojamiento;

	//--------------------------------------------------

	function Cargar($recuperaid_alojamiento, $recuperaacceso_inernet,$recuperaaire_acondicionado_comunes,$recuperaaparcamiento,$recuperaarea_juegos,$recuperaascensores,
  $recuperabares,$recuperacambio_moneda,$recuperacobertura_moviles,$recuperaminiclub,$recuperapiscinas_numero,$recuperapeluqueria,$recuperapiscina_aire_libre,
  $recuperapiscina_agua_dulce,$recuperapiscina_ninos,$recuperarestaurantes, $recuperarestaurante_climatizado,$recuperasala_conferencias,$recuperaservicio_facturacion_24,
  $recuperaservicio_recepcion_24,$recuperasombrillas,$recuperaterraza_solarium,$recuperatiendas,$recuperatronas,$recuperatumbonas,$recuperavetibulo_recepcion,
  $recuperaaireacondicionado_central,$recuperabalcon,$recuperabano,$recuperacaja_seguridad,$recuperaminibar,$recuperasecador,$recuperatelefono_linea_directa,
  $recuperatv_satelite_cable,$recuperaninos_gratis, $recuperaceliacos, $recuperadiscapacitados,$recuperagolf, $recuperagimnasio, $recuperasolo_adultos,
  $recuperaspa, $recuperatodo_incluido, $recuperavista_mar, $recuperawifi){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_alojamiento = $this ->Buscar_alojamiento;
	
		if($buscar_alojamiento != null){
				$CADENA_BUSCAR = " AND ase.ID_ALOJAMIENTO = '".$buscar_alojamiento."' ";
		}else{
			$CADENA_BUSCAR = " AND ase.ID_ALOJAMIENTO = '' ";
		}

		$resultado =$conexion->query("SELECT 
										ase.id_alojamiento,
										a.nombre nombre_alojamiento, 
										ase.ACCESO_INTERNET acceso_internet, 
										ase.AIRE_ACONDICIONADO_COMUNES aire_acondicionado_comunes,
										ase.APARCAMIENTO aparcamiento,
										ase.AREA_JUEGOS area_juegos,
										ase.ASCENSORES ascensores,
										ase.BARES bares,
										ase.CAMBIO_MONEDA cambio_moneda, 
										ase.COBERTURA_MOVILES cobertura_moviles,
										ase.MINICLUB miniclub,
										ase.PISCINAS_NUMERO piscinas_numero,
										ase.PELUQUERIA peluqueria,
										ase.PISCINA_AIRE_LIBRE piscina_aire_libre,
										ase.PISCINA_AGUA_DULCE piscina_agua_dulce,
										ase.PISCINA_NINOS piscina_ninos,
										ase.RESTAURANTES restaurantes,
										ase.RESTAURANTE_CLIMATIZADO restaurante_climatizado,
										ase.SALA_CONFERENCIAS sala_conferencias,
										ase.SERVICIO_FACTURACION_24 servicio_facturacion_24,
										ase.SERVICIO_RECEPCION_24 servicio_recepcion_24,
										ase.SOMBRILLAS sombrillas,
										ase.TERRAZA_SOLARIUM terraza_solarium,
										ase.TIENDAS tiendas,
										ase.TRONAS tronas,
										ase.TUMBONAS tumbonas,
										ase.VESTIBULO_RECEPCION vestibulo_recepcion,
										ase.AIRE_ACONDICIONADO_CENTRAL aire_acondicionado_central,
										ase.BALCON balcon,
										ase.BANO bano,
										ase.CAJA_SEGURIDAD caja_seguridad,
										ase.MINIBAR minibar,
										ase.SECADOR secador,
										ase.TELEFONO_LINEA_DIRECTA telefono_linea_directa,
										ase.TV_SATELITE_CABLE tv_satelite_cable,
										ase.NINOS_GRATIS ninos_gratis,
										ase.CELIACOS celiacos,
										ase.DISCAPACITADOS discapacitados,
										ase.GOLF golf,
										ase.GIMNASIO gimnasio,
										ase.SOLO_ADULTOS solo_adultos,
										ase.SPA spa,
										ase.TODO_INCLUIDO todo_incluido,
										ase.VISTA_MAR vista_mar,
										ase.WIFI wifi
									  FROM hit_alojamientos_servicios ase, hit_alojamientos a
										where
											ase.ID_ALOJAMIENTO = a.ID ".$CADENA_BUSCAR);


		//echo($CADENA_BUSCAR);

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_SERVICIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$alojamientos_servicios = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($alojamientos_servicios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $alojamientos_servicios;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_SERVICIOS' AND USUARIO = '".$Usuario."'");
		if ($num_filas == FALSE){
			echo('error al leer lineas nuevas');
			$Nfilasnuevas = 2;
		}
		$Nfilasnuevas	 = $num_filas->fetch_assoc();																	  //------
		$combo_nuevas = array();
		for ($num_fila = 0; $num_fila <= $Nfilasnuevas['LINEAS_NUEVAS']-1; $num_fila++) {
			$combo_nuevas[$num_fila] = array ("linea" => $num_fila);
		}

		$num_filas->close();
		return $combo_nuevas;											
	}


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
	
		$buscar_alojamiento = $this ->Buscar_alojamiento;
	
		if($buscar_alojamiento != null){
				$CADENA_BUSCAR = " AND ase.ID_ALOJAMIENTO = '".$buscar_alojamiento."' ";
		}else{
			$CADENA_BUSCAR = " AND ase.ID_ALOJAMIENTO = '' ";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_alojamientos_servicios ase, hit_alojamientos a
										where ase.ID_ALOJAMIENTO = a.ID ".$CADENA_BUSCAR);

		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/
		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		if($numero_filas > 0){
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_SERVICIOS' AND USUARIO = '".$Usuario."'");
			$Nfilas	 = $num_filas->fetch_assoc();																	  //------
			$cada = $Nfilas['LINEAS_MODIFICACION'] + 1;
			$combo_select = array();
			for ($num_fila = 1; $num_fila <= $numero_filas; $num_fila++) {
				
				if($num_fila == $cada){
					$combo_select[$cada - $Nfilas['LINEAS_MODIFICACION']] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $cada - 1);
					$cada = $cada + $Nfilas['LINEAS_MODIFICACION'];
				}
				if($num_fila == $numero_filas){
					$combo_select[$cada - $Nfilas['LINEAS_MODIFICACION']] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $numero_filas);
				}
			}
			$num_filas->close();
		}else{
			$combo_select[1] = array ( "inicio" => 1, "fin" => 0);
			$resultadoc->close();
		}
		return $combo_select;											
	}

	function Botones_selector($filadesde, $boton){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		$buscar_alojamiento = $this ->Buscar_alojamiento;
	
		if($buscar_alojamiento != null){
				$CADENA_BUSCAR = " AND ase.ID_ALOJAMIENTO = '".$buscar_alojamiento."' ";
		}else{
			$CADENA_BUSCAR = " AND ase.ID_ALOJAMIENTO = '' ";
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_alojamientos_servicios ase, hit_alojamientos a
										where ase.ID_ALOJAMIENTO = a.ID ".$CADENA_BUSCAR);

		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_SERVICIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $num_filas->fetch_assoc();
		
		if($boton == 1){
			$selector = 1;
		}elseif($boton == 2){
			$selector = $filadesde - $Nfilas['LINEAS_MODIFICACION'];
		}elseif($boton == 3){
			$selector = $filadesde + $Nfilas['LINEAS_MODIFICACION'];		
		}else{

			$cada = $Nfilas['LINEAS_MODIFICACION'] + 1;
			for ($num_fila = 1; $num_fila <= $numero_filas; $num_fila++) {
				
				if($num_fila == $cada){
					$cada = $cada + $Nfilas['LINEAS_MODIFICACION'];
				}
				if($num_fila == $numero_filas){
					//$combo_select[$num_fila] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $numero_filas);
					$selector = $cada - $Nfilas['LINEAS_MODIFICACION'];
				}
			}
		}

		$resultadoc->close();
		$num_filas->close();
		return $selector;											
	}

	function Modificar($id_alojamiento,$acceso_inernet,$aire_acondicionado_comunes,$aparcamiento,$area_juegos,$ascensores,$bares,$cambio_moneda,$cobertura_moviles,$miniclub,
  $piscinas_numero,$peluqueria,$piscina_aire_libre,$piscina_agua_dulce,$piscina_ninos,$restaurantes,$restaurante_climatizado,$sala_conferencias,$servicio_facturacion_24,
  $servicio_recepcion_24,$sombrillas,$terraza_solarium,$tiendas,$tronas,$tumbonas,$vetibulo_recepcion,$aire_acondicionado_central,$balcon,$bano,$caja_seguridad,$minibar,
  $secador,$telefono_linea_directa,$tv_satelite_cable  ,$ninos_gratis,$celiacos,$discapacitados,$golf,$gimnasio,$solo_adultos,$spa,$todo_incluido,$vista_mar,$wifi){

		  $conexion = $this ->Conexion;
		  $query = "UPDATE hit_alojamientos_servicios SET ";
		  $query .= " ACCESO_INTERNET = '".$acceso_inernet."'";
		  $query .= ", AIRE_ACONDICIONADO_COMUNES = '".$aire_acondicionado_comunes."'";
		  $query .= ", APARCAMIENTO = '".$aparcamiento."'";
		  $query .= ", AREA_JUEGOS = '".$area_juegos."'";
		  $query .= ", ASCENSORES = '".$ascensores."'";
		  $query .= ", BARES = '".$bares."'";
		  $query .= ", CAMBIO_MONEDA = '".$cambio_moneda."'";
		  $query .= ", COBERTURA_MOVILES = '".$cobertura_moviles."'";
		  $query .= ", MINICLUB = '".$miniclub."'";
		  $query .= ", PISCINAS_NUMERO = '".$piscinas_numero."'";
		  $query .= ", PELUQUERIA = '".$peluqueria."'";
		  $query .= ", PISCINA_AIRE_LIBRE = '".$piscina_aire_libre."'";
		  $query .= ", PISCINA_AGUA_DULCE = '".$piscina_agua_dulce."'";
		  $query .= ", PISCINA_NINOS = '".$piscina_ninos."'";
		  $query .= ", RESTAURANTES = '".$restaurantes."'";
		  $query .= ", RESTAURANTE_CLIMATIZADO = '".$restaurante_climatizado."'";
		  $query .= ", SALA_CONFERENCIAS = '".$sala_conferencias."'";
		  $query .= ", SERVICIO_FACTURACION_24 = '".$servicio_facturacion_24."'";
		  $query .= ", SERVICIO_RECEPCION_24 = '".$servicio_recepcion_24."'";
		  $query .= ", SOMBRILLAS = '".$sombrillas."'";
		  $query .= ", TERRAZA_SOLARIUM = '".$terraza_solarium."'";
		  $query .= ", TIENDAS = '".$tiendas."'";
		  $query .= ", TRONAS = '".$tronas."'";
		  $query .= ", TUMBONAS = '".$tumbonas."'";
		  $query .= ", VESTIBULO_RECEPCION = '".$vetibulo_recepcion."'";
		  $query .= ", AIRE_ACONDICIONADO_CENTRAL = '".$aire_acondicionado_central."'";
		  $query .= ", BALCON = '".$balcon."'";
		  $query .= ", BANO = '".$bano."'";
		  $query .= ", CAJA_SEGURIDAD = '".$caja_seguridad."'";
		  $query .= ", MINIBAR = '".$minibar."'";
		  $query .= ", SECADOR = '".$secador."'";
		  $query .= ", TELEFONO_LINEA_DIRECTA = '".$telefono_linea_directa."'";
		  $query .= ", TV_SATELITE_CABLE = '".$tv_satelite_cable."'";
		  $query .= ", NINOS_GRATIS = '".$ninos_gratis."'";
		  $query .= ", CELIACOS = '".$celiacos."'";
		  $query .= ", DISCAPACITADOS = '".$discapacitados."'";
		  $query .= ", GOLF = '".$golf."'";
		  $query .= ", GIMNASIO = '".$gimnasio."'";
		  $query .= ", SOLO_ADULTOS = '".$solo_adultos."'";
		  $query .= ", SPA = '".$spa."'";
		  $query .= ", TODO_INCLUIDO = '".$todo_incluido."'";
		  $query .= ", VISTA_MAR = '".$vista_mar."'";
		  $query .= ", WIFI = '".$wifi."'";

		$query .= " WHERE ID_ALOJAMIENTO = '".$id_alojamiento."'";
		

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($id_alojamiento,$nombre){

		$conexion = $this ->Conexion;


		$query = "DELETE FROM hit_alojamientos_servicios WHERE ID_ALOJAMIENTO = '".$id_alojamiento."'";

		//echo($query);

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($id_alojamiento,$acceso_inernet,$aire_acondicionado_comunes,$aparcamiento,$area_juegos,$ascensores,$bares,$cambio_moneda,$cobertura_moviles,$miniclub,
  $piscinas_numero,$peluqueria,$piscina_aire_libre,$piscina_agua_dulce,$piscina_ninos,$restaurantes,$restaurante_climatizado,$sala_conferencias,$servicio_facturacion_24,
  $servicio_recepcion_24,$sombrillas,$terraza_solarium,$tiendas,$tronas,$tumbonas,$vetibulo_recepcion,$aire_acondicionado_central,$balcon,$bano,$caja_seguridad,$minibar,
  $secador,$telefono_linea_directa,$tv_satelite_cable,$ninos_gratis,$celiacos,$discapacitados,$golf,$gimnasio,$solo_adultos,$spa,$todo_incluido,$vista_mar,$wifi){

		//echo('hola insertar');

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_alojamientos_servicios (ID_ALOJAMIENTO, ACCESO_INTERNET,AIRE_ACONDICIONADO_COMUNES,APARCAMIENTO,AREA_JUEGOS,ASCENSORES,BARES,CAMBIO_MONEDA,
		  COBERTURA_MOVILES,MINICLUB,PISCINAS_NUMERO,PELUQUERIA,PISCINA_AIRE_LIBRE,PISCINA_AGUA_DULCE,PISCINA_NINOS,RESTAURANTES,RESTAURANTE_CLIMATIZADO,SALA_CONFERENCIAS,  SERVICIO_FACTURACION_24, SERVICIO_RECEPCION_24,SOMBRILLAS,TERRAZA_SOLARIUM,TIENDAS,TRONAS,TUMBONAS,VESTIBULO_RECEPCION,AIRE_ACONDICIONADO_CENTRAL,BALCON,BANO,
		  CAJA_SEGURIDAD,MINIBAR,SECADOR,TELEFONO_LINEA_DIRECTA,TV_SATELITE_CABLE, NINOS_GRATIS,CELIACOS,DISCAPACITADOS,GOLF,GIMNASIO,SOLO_ADULTOS,SPA,TODO_INCLUIDO,VISTA_MAR,WIFI) VALUES (";
		$query .= "'".$id_alojamiento."',";
		  $query .= "'".$acceso_inernet."',";
		  $query .= "'".$aire_acondicionado_comunes."',";
		  $query .= "'".$aparcamiento."',";
		  $query .= "'".$area_juegos."',";
		  $query .= "'".$ascensores."',";
		  $query .= "'".$bares."',";
		  $query .= "'".$cambio_moneda."',";
		  $query .= "'".$cobertura_moviles."',";
		  $query .= "'".$miniclub."',";
		  $query .= "'".$piscinas_numero."',";
		  $query .= "'".$peluqueria."',";
		  $query .= "'".$piscina_aire_libre."',";
		  $query .= "'".$piscina_agua_dulce."',";
		  $query .= "'".$piscina_ninos."',";
		  $query .= "'".$restaurantes."',";
		  $query .= "'".$restaurante_climatizado."',";
		  $query .= "'".$sala_conferencias."',";
		  $query .= "'".$servicio_facturacion_24."',";
		  $query .= "'".$servicio_recepcion_24."',";
		  $query .= "'".$sombrillas."',";
		  $query .= "'".$terraza_solarium."',";
		  $query .= "'".$tiendas."',";
		  $query .= "'".$tronas."',";
		  $query .= "'".$tumbonas."',";
		  $query .= "'".$vetibulo_recepcion."',";
		  $query .= "'".$aire_acondicionado_central."',";
		  $query .= "'".$balcon."',";
		  $query .= "'".$bano."',";
		  $query .= "'".$caja_seguridad."',";
		  $query .= "'".$minibar."',";
		  $query .= "'".$secador."',";
		  $query .= "'".$telefono_linea_directa."',";
		  $query .= "'".$telefono_linea_directa."',";

		  $query .= "'".$ninos_gratis."',";
		  $query .= "'".$celiacos."',";
		  $query .= "'".$discapacitados."',";
		  $query .= "'".$golf."',";
		  $query .= "'".$gimnasio."',";
		  $query .= "'".$solo_adultos."',";
		  $query .= "'".$spa."',";
		  $query .= "'".$todo_incluido."',";
		  $query .= "'".$vista_mar."',";
		  $query .= "'".$wifi."')";

		//ECHO($query);

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}


	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsAlojamientos_servicios($conexion, $filadesde, $usuario, $buscar_alojamiento){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_alojamiento = $buscar_alojamiento;
	}
}

?>