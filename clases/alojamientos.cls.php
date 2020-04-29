<?php

require 'clases/alojamientos_acuerdos.cls.php';

class clsAlojamientos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	var	$buscar_nombre;
	var	$buscar_ciudad;
	var	$buscar_categoria;
	var	$buscar_situacion;
	//--------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------
	function Cargar($recuperaid,$recuperanombre,$recuperatipo,$recuperaciudad,$recuperadestino_producto,$recuperazona_producto,$recuperacategoria,$recuperadireccion,$recuperacodigo_postal,$recuperaprovincia,$recuperapais,$recuperacif,$recuperatelefono,$recuperafax,$recuperaemail,$recuperaresponsable,$recuperacargo_responsable,$recuperanombre_comercial,$recuperaobservaciones,$recuperalocalidad,$recuperavisible,$recuperadescripcion,$recuperasituacion,$recuperaubicacion,$recuperaurl,$recuperadescripcion_completa,$recuperacomo_llegar,$recuperadescripcion_habitaciones,$recuperadescripcion_actividades,$recuperdescripcion_restaurantes,$recuperadescripcion_belleza,$recuperalatitud, $recuperalongitud, $recuperamovil_comercial, $recuperareservas_reponsable, $recuperareservas_telefono, $recuperareservas, $recuperaorden){


		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_ciudad = $this ->Buscar_ciudad;
		$buscar_categoria = $this ->Buscar_categoria;
		$buscar_situacion = $this ->Buscar_situacion;
		

		if($buscar_id != null){
			$CADENA_BUSCAR = " and a.ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " and a.NOMBRE LIKE '%".$buscar_nombre."%'";				
		}elseif($buscar_ciudad != null){
			$cat = " AND a.CATEGORIA = '".$buscar_categoria."'";
			$sit = " AND a.SITUACION = '".$buscar_situacion."'";
			$CADENA_BUSCAR = " and a.CIUDAD = '".$buscar_ciudad."'";
			if($buscar_categoria != null){
				$CADENA_BUSCAR .= $cat;	
			}
			if($buscar_situacion != null){
				$CADENA_BUSCAR .= $sit;	
			}
		}elseif($buscar_categoria != null){
			$sit = " AND a.SITUACION = '".$buscar_situacion."'";
			$CADENA_BUSCAR = " and a.CATEGORIA = '".$buscar_categoria."'";
			if($buscar_situacion != null){
				$CADENA_BUSCAR .= $sit;	
			}
		}elseif($buscar_situacion != null){
			$CADENA_BUSCAR = " and a.situacion = '".$buscar_situacion."'";
			//$CADENA_BUSCAR = "";
		}else{
			//$CADENA_BUSCAR = " WHERE ID = '0' ";
			$CADENA_BUSCAR = " and a.ID = '0'";
		}	

		$resultado =$conexion->query("SELECT a.id,a.nombre,a.tipo,a.ciudad,a.destino_producto,a.zona_producto,a.categoria,a.direccion,a.codigo_postal,a.provincia,a.pais,a.cif,a.telefono,a.fax,a.email,a.responsable,a.cargo_responsable,a.nombre_comercial,a.observaciones,a.localidad,a.visible,a.descripcion,a.situacion,a.ubicacion,a.url,a.descripcion_completa,a.como_llegar,a.descripcion_habitaciones,a.descripcion_actividades,a.descripcion_restaurantes,a.descripcion_belleza,a.latitud,a.longitud, a.movil_comercial, a.reservas_responsable, a.reservas_telefono, a.reservas_mail, a.orden,
			c.NOMBRE ciudad_nombre, p.NOMBRE provincia_nombre, pa.NOMBRE pais_nombre, co.NOMBRE continente_nombre,
			ar.NOMBRE area_nombre, z.NOMBRE zona_nombre, d.NOMBRE destino_nombre FROM hit_alojamientos a, hit_ciudades c, hit_provincias p, hit_paises pa, hit_zonas z, hit_destinos d, hit_continentes co, hit_areas ar
			where 
				a.CIUDAD = c.CODIGO
				and c.PROVINCIA = p.CODIGO
				and p.PAIS = pa.CODIGO
				and pa.CONTINENTE = co.CODIGO
				and pa.AREA = ar.CODIGO
				and c.ZONA = z.CODIGO
				and z.DESTINO = d.CODIGO ".$CADENA_BUSCAR." ORDER BY nombre");
	

		//echo($CADENA_BUSCAR);
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$alojamientos = array();
		if($recuperanombre != null){
			$alojamientos[0] = array ("id" => $recuperaid,"nombre" => $recuperanombre,"tipo" => $recuperatipo,"ciudad" => $recuperaciudad,"destino_producto" => $recuperadestino_producto,"zona_producto" => $recuperazona_producto,"categoria" => $recuperacategoria,"direccion" => $recuperadireccion,"codigo_postal" => $recuperacodigo_postal,"provincia" => $recuperaprovincia,"pais" => $recuperapais,"cif" => $recuperacif,"telefono" => $recuperatelefono,"fax" => $recuperafax,"email" => $recuperaemail,"responsable" => $recuperaresponsable,"cargo_responsable" => $recuperacargo_responsable,"nombre_comercial" => $recuperanombre_comercial,"observaciones" => $recuperaobservaciones,"localidad" => $recuperalocalidad,"visible" => $recuperavisible,"descripcion" => $recuperadescripcion,"situacion" => $recuperasituacion,"ubicacion" => $recuperaubicacion,"url" => $recuperaurl,"orden" => $recuperaorden);
		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($alojamientos,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $alojamientos;											
	}


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_ciudad = $this ->Buscar_ciudad;
		$buscar_categoria = $this ->Buscar_categoria;
		$buscar_situacion = $this ->Buscar_situacion;

		if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";				
		}elseif($buscar_ciudad != null){
			$cat = " AND CATEGORIA = '".$buscar_categoria."'";
			$sit = " AND SITUACION = '".$buscar_situacion."'";
			$CADENA_BUSCAR = " WHERE CIUDAD = '".$buscar_ciudad."'";
			if($buscar_categoria != null){
				$CADENA_BUSCAR .= $cat;	
			}
			if($buscar_situacion != null){
				$CADENA_BUSCAR .= $sit;	
			}
		}elseif($buscar_categoria != null){
			$sit = " AND SITUACION = '".$buscar_situacion."'";
			$CADENA_BUSCAR = " WHERE CATEGORIA = '".$buscar_categoria."'";
			if($buscar_situacion != null){
				$CADENA_BUSCAR .= $sit;	
			}
		}elseif($buscar_situacion != null){
			$CADENA_BUSCAR = " WHERE situacion = '".$buscar_situacion."'";
			//$CADENA_BUSCAR = "";
		}else{
			//$CADENA_BUSCAR = " WHERE ID = '0' ";
			$CADENA_BUSCAR = " WHERE ID = '0'";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS' AND USUARIO = '".$Usuario."'");
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
		
		$buscar_id = $this ->Buscar_id;
		$buscar_nombre = $this ->Buscar_nombre;
		$buscar_ciudad = $this ->Buscar_ciudad;
		$buscar_categoria = $this ->Buscar_categoria;
		$buscar_situacion = $this ->Buscar_situacion;

		if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";				
		}elseif($buscar_ciudad != null){
			$cat = " AND CATEGORIA = '".$buscar_categoria."'";
			$sit = " AND SITUACION = '".$buscar_situacion."'";
			$CADENA_BUSCAR = " WHERE CIUDAD = '".$buscar_ciudad."'";
			if($buscar_categoria != null){
				$CADENA_BUSCAR .= $cat;	
			}
			if($buscar_situacion != null){
				$CADENA_BUSCAR .= $sit;	
			}
		}elseif($buscar_categoria != null){
			$sit = " AND SITUACION = '".$buscar_situacion."'";
			$CADENA_BUSCAR = " WHERE CATEGORIA = '".$buscar_categoria."'";
			if($buscar_situacion != null){
				$CADENA_BUSCAR .= $sit;	
			}
		}elseif($buscar_situacion != null){
			$CADENA_BUSCAR = " WHERE situacion = '".$buscar_situacion."'";
			//$CADENA_BUSCAR = "";
		}else{
			//$CADENA_BUSCAR = " WHERE ID = '0' ";
			$CADENA_BUSCAR = " WHERE ID a.ID = '0'";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_alojamientos'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id,$nombre,$tipo,$ciudad,$destino_producto,$zona_producto,$categoria,$direccion,$codigo_postal,$provincia,$pais,$cif,$telefono,$fax,$email,$responsable,$cargo_responsable,$nombre_comercial,$observaciones,$localidad,$visible,$descripcion,$situacion,$ubicacion,$url,$descripcion_completa,$como_llegar,$descripcion_habitaciones,$descripcion_actividades,$descripcion_restaurantes,$descripcion_belleza,$latitud, $longitud, $movil_comercial, $reservas_responsable, $reservas_telefono, $reservas_mail, $orden){
		//echo($orden);
		$conexion = $this ->Conexion;
		$query = "UPDATE hit_alojamientos SET ";
		$query .= " NOMBRE = '".$nombre."'";
		$query .= ", TIPO = '".$tipo."'"; 
		$query .= ", CIUDAD = '".$ciudad."'"; 
		//$query .= ", DESTINO_PRODUCTO = '".$destino_producto."'";
		//$query .= ", ZONA_PRODUCTO = '".$zona_producto."'";
		$query .= ", CATEGORIA = '".$categoria."'";
		$query .= ", DIRECCION = '".$direccion."'";
		$query .= ", CODIGO_POSTAL = '".$codigo_postal."'";
		//$query .= ", PROVINCIA = '".$provincia."'";
		//$query .= ", PAIS = '".$pais."'";
		$query .= ", CIF = '".$cif."'";
		$query .= ", TELEFONO = '".$telefono."'";
		$query .= ", FAX = '".$fax."'";
		$query .= ", EMAIL = '".$email."'";
		$query .= ", RESPONSABLE = '".$responsable."'";
		$query .= ", CARGO_RESPONSABLE = '".$cargo_responsable."'";
		$query .= ", NOMBRE_COMERCIAL = '".$nombre_comercial."'";
		$query .= ", OBSERVACIONES = '".$observaciones."'";
		$query .= ", LOCALIDAD = '".$localidad."'";
		$query .= ", VISIBLE = '".$visible."'";
		$query .= ", DESCRIPCION = '".$descripcion."'";
		$query .= ", SITUACION = '".$situacion."'";
		$query .= ", UBICACION = '".$ubicacion."'";
		$query .= ", URL = '".$url."'";
		$query .= ", DESCRIPCION_COMPLETA = '".$descripcion_completa."'";
		$query .= ", COMO_LLEGAR = '".$como_llegar."'";
		$query .= ", DESCRIPCION_HABITACIONES = '".$descripcion_habitaciones."'";
		$query .= ", DESCRIPCION_ACTIVIDADES = '".$descripcion_actividades."'";
		$query .= ", DESCRIPCION_RESTAURANTES = '".$descripcion_restaurantes."'";
		$query .= ", DESCRIPCION_BELLEZA = '".$descripcion_belleza."'";
		$query .= ", LATITUD = '".$latitud."'";
		$query .= ", LONGITUD = '".$longitud."'";
		$query .= ", MOVIL_COMERCIAL = '".$movil_comercial."'";
		$query .= ", RESERVAS_RESPONSABLE = '".$reservas_responsable."'";
		$query .= ", RESERVAS_TELEFONO = '".$reservas_telefono."'";
		$query .= ", RESERVAS_MAIL = '".$reservas_mail."'";
		$query .= ", ORDEN = '".$orden."'";
		$query .= " WHERE ID = '".$id."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($id){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_alojamientos WHERE ID = '".$id."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($nombre,$tipo,$ciudad,$destino_producto,$zona_producto,$categoria,$direccion,$codigo_postal,$provincia,$pais,$cif,$telefono,$fax,$email,$responsable,$cargo_responsable,$nombre_comercial,$observaciones,$localidad,$visible,$descripcion,$situacion,$ubicacion,$url,$descripcion_completa,$como_llegar,$descripcion_habitaciones,$descripcion_actividades,$descripcion_restaurantes,$descripcion_belleza,$latitud, $longitud, $movil_comercial, $reservas_responsable, $reservas_telefono, $reservas_mail, $orden){

		$conexion = $this ->Conexion;

		$query = "INSERT INTO hit_alojamientos (NOMBRE,
			TIPO,
			CIUDAD, 
			CATEGORIA,
			DIRECCION,
			CODIGO_POSTAL,
			CIF,
			TELEFONO,
			FAX,
			EMAIL,
			RESPONSABLE,
			CARGO_RESPONSABLE,
			NOMBRE_COMERCIAL,
			OBSERVACIONES,
			LOCALIDAD,
			VISIBLE,	
			DESCRIPCION,
			SITUACION,
			UBICACION,
			URL,
			DESCRIPCION_COMPLETA,
			COMO_LLEGAR,
			DESCRIPCION_HABITACIONES,
			DESCRIPCION_ACTIVIDADES,
			DESCRIPCION_RESTAURANTES,
			DESCRIPCION_BELLEZA,
			LATITUD,LONGITUD, 
			MOVIL_COMERCIAL, 
			RESERVAS_RESPONSABLE, 
			RESERVAS_TELEFONO, 
			RESERVAS_MAIL, 
			ORDEN) VALUES (";
		$query .= "'".$nombre."',"; 
		$query .= "'".$tipo."',";
		$query .= "'".$ciudad."',";
		//$query .= "'".$destino_producto."',";
		//$query .= "'".$zona_producto."',";
		$query .= "'".$categoria."',";
		$query .= "'".$direccion."',";
		$query .= "'".$codigo_postal."',";
		//$query .= "'".$provincia."',";
		//$query .= "'".$pais."',";
		$query .= "'".$cif."',";
		$query .= "'".$telefono."',";
		$query .= "'".$fax."',";
		$query .= "'".$email."',";
		$query .= "'".$responsable."',";
		$query .= "'".$cargo_responsable."',";
		$query .= "'".$nombre_comercial."',"; 
		$query .= "'".$observaciones."',";
		$query .= "'".$localidad."',";
		$query .= "'".$visible."',";
		$query .= "'".$descripcion."',";
		$query .= "'".$situacion."',";
		$query .= "'".$ubicacion."',";
		$query .= "'".$url."',";
		$query .= "'".$descripcion_completa."',";
		$query .= "'".$como_llegar."',";
		$query .= "'".$descripcion_habitaciones."',";
		$query .= "'".$descripcion_actividades."',";
		$query .= "'".$descripcion_restaurantes."',";
		$query .= "'".$descripcion_belleza."',";
		$query .= "'".$latitud."',";
		$query .= "'".$longitud."',";
		$query .= "'".$movil_comercial."',";
		$query .= "'".$reservas_responsable."',";
		$query .= "'".$reservas_telefono."',";
		$query .= "'".$reservas_mail."',";
		$query .= "'".$orden."')";


		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}



//---------------------------------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE LAS INTERFACES----
//---------------------------------------------------------------------------------------------

	function Cargar_interfaces($id, $filadesde){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
		
		//ECHO($id.'-');
		if($id == ''){
			$id=0;
		}


		$resultado =$conexion->query("select id_alojamiento interfaces_id_alojamiento,  codigo_interfaz interfaces_codigo_interfaz, 

			DATE_FORMAT(salidas_desde, '%d-%m-%Y') AS interfaces_salidas_desde,
			DATE_FORMAT(salidas_hasta, '%d-%m-%Y') AS interfaces_salidas_hasta,
			orden_aplicacion interfaces_orden_aplicacion, 
		 	codigo_externo interfaces_codigo_externo,
		 	codigo_externo_completo interfaces_codigo_externo_completo
			from hit_alojamientos_interfaces where id_alojamiento = ".$id." order by orden_aplicacion");

		//echo($CADENA_BUSCAR);
		
		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_INTERFACES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$interfaces = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['interfaces_id_alojamiento'] == ''){
				break;
			}
			array_push($interfaces,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $interfaces;		
		
	}

	function Cargar_lineas_nuevas_interfaces(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_INTERFACES' AND USUARIO = '".$Usuario."'");
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

	function Cargar_combo_selector_interfaces($id, $filadesde){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		if($id == ''){
			$id=0;
		}
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select * from hit_alojamientos_interfaces 
						where id_alojamiento = ".$id." order by orden_aplicacion");
		$numero_filas = $resultadoc->num_rows;         //----------


		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		if($numero_filas > 0){
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_INTERFACES' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_interfaces($filadesde, $boton, $id){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
					
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select * from hit_alojamientos_interfaces 
						where id_alojamiento = ".$id." order by orden_aplicacion");
		$numero_filas = $resultadoc->num_rows;         //----------

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'ALOJAMIENTOS_INTERFACES' AND USUARIO = '".$Usuario."'");
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

	function Modificar_interfaces($id, $codigo_interfaz, $salidas_desde, $salidas_hasta, $orden_aplicacion, $codigo_externo, 
		$codigo_interfaz_old, $salidas_desde_old, $salidas_hasta_old, $orden_aplicacion_old){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_alojamientos_interfaces SET ";
		$query .= "CODIGO_INTERFAZ = '".$codigo_interfaz."'";
		$query .= " ,SALIDAS_DESDE = '".date("Y-m-d",strtotime($salidas_desde))."'";
		$query .= ", SALIDAS_HASTA = '".date("Y-m-d",strtotime($salidas_hasta))."'";
		$query .= ", ORDEN_APLICACION = '".$orden_aplicacion."'";
		$query .= ", CODIGO_EXTERNO = '".$codigo_externo."'";
		$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
		$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz_old."'";
		$query .= " AND SALIDAS_DESDE = '".date("Y-m-d",strtotime($salidas_desde_old))."'";
		$query .= " AND SALIDAS_HASTA = '".date("Y-m-d",strtotime($salidas_hasta_old))."'";
		$query .= " AND ORDEN_APLICACION = '".$orden_aplicacion_old."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
			//echo($query);
		}else{
			$respuesta = 'OK';
			//echo($query);
			$datos_codigo_dup =$conexion->query("select codigo_duplicados from hit_interfaces_codigos_hoteles where codigo = '".$codigo_externo."'");
			$odatos_codigo_dup = $datos_codigo_dup->fetch_assoc();
			$codigo_dup = $odatos_codigo_dup['codigo_duplicados'];

			$codigo_completo = $codigo_externo."#";

			if($codigo_dup != 0){
				
				$resultado =$conexion->query("select codigo from hit_interfaces_codigos_hoteles where codigo_duplicados = '".$codigo_dup."' and codigo <> '".$codigo_externo."'");

				for ($num_fila = 0; $num_fila <= $resultado->num_rows; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();

				//Actualizamos los codigos duplicados del interfaz
					switch ($num_fila) {
					   case 0:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_2= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod=$conexion->query($query);
					          break;
					   case 1:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_3= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					   case 2:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_4= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					    case 3:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_5= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					     case 4:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_6= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					     case 5:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_7= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					     case 6:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_8= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					     case 7:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_9= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;					      
					}

					if($fila['codigo'] != null){
						$codigo_completo .= $fila['codigo']."#";
					}
				}

				$resultado->close();			
			}

			$query = "UPDATE hit_alojamientos_interfaces SET ";
			$query .= "CODIGO_EXTERNO_COMPLETO= '".$codigo_completo."'";
			$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
			$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
			$resultado_cod=$conexion->query($query);

		}

		return $respuesta;											
	}

	function Borrar_interfaces($id, $codigo_interfaz, $salidas_desde, $salidas_hasta){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_alojamientos_interfaces WHERE ID_ALOJAMIENTO = '".$id."'";
		$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
		$query .= " AND SALIDAS_DESDE = '".date("Y-m-d",strtotime($salidas_desde))."'";
		$query .= " AND SALIDAS_HASTA = '".date("Y-m-d",strtotime($salidas_hasta))."'";
		
		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_interfaces($id, $codigo_interfaz, $salidas_desde, $salidas_hasta, $orden_aplicacion, $codigo_externo){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

		
		$query = "INSERT INTO hit_alojamientos_interfaces (ID_ALOJAMIENTO, CODIGO_INTERFAZ, SALIDAS_DESDE, SALIDAS_HASTA, ORDEN_APLICACION, CODIGO_EXTERNO) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".$codigo_interfaz."',";
		$query .= "'".date("Y-m-d",strtotime($salidas_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($salidas_hasta))."',";
		$query .= "'".$orden_aplicacion."',";
		$query .= "'".$codigo_externo."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';

			//CREAMOS EL ACUERDO 
			if($codigo_interfaz != 'INTERNO'){
				//clsAcuerdos($conexion, $filadesde, $usuario, $buscar_id, $buscar_acuerdo){
				$iAcuerdos = new clsAcuerdos($conexion, 0, $Usuario, $id, 0);
				$insertaAcuerdos = $iAcuerdos->Insertar_acuerdos_interfaz($id, '', $codigo_interfaz, date("Y-m-d",strtotime($salidas_desde)), date("Y-m-d",strtotime($salidas_hasta)));


				if($insertaAcuerdos == 'OK'){
					$respuesta = 'OK';
				}else{
					$respuesta = $insertaAcuerdos ;
				}
			}


			$datos_codigo_dup =$conexion->query("select codigo_duplicados from hit_interfaces_codigos_hoteles where codigo = '".$codigo_externo."'");
			$odatos_codigo_dup = $datos_codigo_dup->fetch_assoc();
			$codigo_dup = $odatos_codigo_dup['codigo_duplicados'];

			$codigo_completo = $codigo_externo."#";

			if($codigo_dup != 0){
				//echo('hay duplicados');
				$resultado =$conexion->query("select codigo from hit_interfaces_codigos_hoteles where codigo_duplicados = '".$codigo_dup."' and codigo <> '".$codigo_externo."'");

				for ($num_fila = 0; $num_fila <= $resultado->num_rows; $num_fila++) {
					$resultado->data_seek($num_fila);
					$fila = $resultado->fetch_assoc();
					//echo(' Actualizamos los codigos: '.$fila['codigo']);
					//Actualizamos los codigos duplicados del interfaz
					switch ($num_fila) {
					   case 0:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_2= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod=$conexion->query($query);
					          break;
					   case 1:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_3= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					   case 2:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_4= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					    case 3:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_5= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					     case 4:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_6= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					     case 5:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_7= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					     case 6:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_8= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;
					     case 7:
						$query = "UPDATE hit_alojamientos_interfaces SET ";
						$query .= "CODIGO_EXTERNO_9= '".$fila['codigo']."'";
						$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
						$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
						$resultado_cod =$conexion->query($query);
					          break;					 
					}

					if($fila['codigo'] != null){
						$codigo_completo .= $fila['codigo']."#";
					}
				}

				$resultado->close();			
			}

			$query = "UPDATE hit_alojamientos_interfaces SET ";
			$query .= "CODIGO_EXTERNO_COMPLETO= '".$codigo_completo."'";
			$query .= " WHERE ID_ALOJAMIENTO = '".$id."'";
			$query .= " AND CODIGO_INTERFAZ = '".$codigo_interfaz."'";
			$resultado_cod=$conexion->query($query);




		}
		return $respuesta;											
	}

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)

	function clsAlojamientos($conexion, $filadesde, $usuario, $buscar_id, $buscar_nombre,$buscar_ciudad,$buscar_categoria,$buscar_situacion){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id = $buscar_id;
		$this->Buscar_nombre = $buscar_nombre;
		$this->Buscar_ciudad = $buscar_ciudad;
		$this->Buscar_categoria = $buscar_categoria;
		$this->Buscar_situacion = $buscar_situacion;
	}
}

?>