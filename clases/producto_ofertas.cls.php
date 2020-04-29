<?php

class clsProducto_ofertas{

	var $Orden1;
	var $Orden2;
	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var	$buscar_nombre;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_tipo = $this->Buscar_tipo;
		$buscar_estado = $this->Buscar_estado;


		if($buscar_tipo != null){
			$estado = " AND o.estado = '".$buscar_estado."'";	
			$CADENA_BUSCAR = "  and o.TIPO = '".$buscar_tipo."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $estado;	
			}

		}elseif($buscar_estado != null){
			$CADENA_BUSCAR = "  and o.ESTADO = '".$buscar_estado."'";
		}else{
			$CADENA_BUSCAR = ""; 
		}



		$resultado =$conexion->query("SELECT o.id, 
								o.orden orden, 
								o.tipo, 
								o.folleto, 
								f.NOMBRE nombre_folleto,
								o.cuadro, 
								c.NOMBRE nombre_cuadro,
								o.paquete, 
								o.regimen, 
								o.ciudad, 
								o.proveedor, 
								p.NOMBRE nombre_proveedor, 
								o.codigo_servicio,  
								s.NOMBRE descripcion,
								DATE_FORMAT(o.desde, '%d-%m-%Y') AS desde, 
								DATE_FORMAT(o.hasta, '%d-%m-%Y') AS hasta, estado 

							FROM hit_producto_ofertas o, hit_producto_folletos f, hit_producto_cuadros c,
									hit_proveedores p, hit_servicios s
							where
								o.FOLLETO = f.CODIGO
								and o.FOLLETO = c.FOLLETO
								and o.CUADRO = c.CUADRO
								and o.PROVEEDOR = p.ID 
								and o.PROVEEDOR = s.ID_PROVEEDOR
								and o.CODIGO_SERVICIO = s.CODIGO
								".$CADENA_BUSCAR."
								and o.TIPO = 'S'

							UNION

							SELECT o.id, 
								o.orden orden, 
								o.tipo, 
								o.folleto, 
								f.NOMBRE nombre_folleto,
								o.cuadro, 
								c.NOMBRE nombre_cuadro,
								o.paquete, 
								o.regimen, 
								o.ciudad, 
								o.proveedor, 
								a.NOMBRE nombre_proveedor, 
								o.codigo_servicio,  
								a.NOMBRE descripcion,
								DATE_FORMAT(o.desde, '%d-%m-%Y') AS desde, 
								DATE_FORMAT(o.hasta, '%d-%m-%Y') AS hasta, o.estado 
							FROM hit_producto_ofertas o, hit_producto_folletos f, hit_producto_cuadros c,
									hit_producto_cuadros_alojamientos ca, hit_alojamientos a
							where
								o.FOLLETO = f.CODIGO
								and o.FOLLETO = c.FOLLETO
								and o.CUADRO = c.CUADRO
								and o.FOLLETO = ca.FOLLETO
								and o.CUADRO = ca.CUADRO
								and o.PAQUETE = ca.PAQUETE
								and o.REGIMEN = ca.REGIMEN
								and ca.ALOJAMIENTO = a.ID
								".$CADENA_BUSCAR."
								and o.TIPO in ('P','H','C')

							ORDER BY orden");


		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_OFERTAS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$producto_ofertas = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['id'] == ''){
				break;
			}
			array_push($producto_ofertas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $producto_ofertas;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_OFERTAS' AND USUARIO = '".$Usuario."'");
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

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_tipo = $this->Buscar_tipo;
		$buscar_estado = $this->Buscar_estado;


		if($buscar_tipo != null){
			$estado = " AND o.estado = '".$buscar_estado."'";	
			$CADENA_BUSCAR = "  and o.TIPO = '".$buscar_tipo."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $estado;	
			}

		}elseif($buscar_estado != null){
			$CADENA_BUSCAR = "  and o.ESTADO = '".$buscar_estado."'";
		}else{
			$CADENA_BUSCAR = ""; 
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT o.id
							FROM hit_producto_ofertas o, hit_producto_folletos f, hit_producto_cuadros c,
									hit_proveedores p, hit_servicios s
							where
								o.FOLLETO = f.CODIGO
								and o.FOLLETO = c.FOLLETO
								and o.CUADRO = c.CUADRO
								and o.PROVEEDOR = p.ID 
								and o.PROVEEDOR = s.ID_PROVEEDOR
								and o.CODIGO_SERVICIO = s.CODIGO
								".$CADENA_BUSCAR."
								and o.TIPO = 'S'
								and o.ESTADO = 'A'

							UNION

							SELECT o.id
							FROM hit_producto_ofertas o, hit_producto_folletos f, hit_producto_cuadros c,
									hit_producto_cuadros_alojamientos ca, hit_alojamientos a
							where
								o.FOLLETO = f.CODIGO
								and o.FOLLETO = c.FOLLETO
								and o.CUADRO = c.CUADRO
								and o.FOLLETO = ca.FOLLETO
								and o.CUADRO = ca.CUADRO
								and o.PAQUETE = ca.PAQUETE
								and o.REGIMEN = ca.REGIMEN
								and ca.ALOJAMIENTO = a.ID
								".$CADENA_BUSCAR."
								and o.TIPO = 'H'
								and o.ESTADO = 'A'");

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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_OFERTAS' AND USUARIO = '".$Usuario."'");
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
		
		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_tipo = $this->Buscar_tipo;
		$buscar_estado = $this->Buscar_estado;


		if($buscar_tipo != null){
			$estado = " AND o.estado = '".$buscar_estado."'";	
			$CADENA_BUSCAR = "  and o.TIPO = '".$buscar_tipo."'";
			if($buscar_estado != null){
				$CADENA_BUSCAR .= $estado;	
			}

		}elseif($buscar_estado != null){
			$CADENA_BUSCAR = "  and o.ESTADO = '".$buscar_estado."'";
		}else{
			$CADENA_BUSCAR = ""; 
		}												

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT o.id
							FROM hit_producto_ofertas o, hit_producto_folletos f, hit_producto_cuadros c,
									hit_proveedores p, hit_servicios s
							where
								o.FOLLETO = f.CODIGO
								and o.FOLLETO = c.FOLLETO
								and o.CUADRO = c.CUADRO
								and o.PROVEEDOR = p.ID 
								and o.PROVEEDOR = s.ID_PROVEEDOR
								and o.CODIGO_SERVICIO = s.CODIGO
								".$CADENA_BUSCAR."
								and o.TIPO = 'S'
								and o.ESTADO = 'A'

							UNION

							SELECT o.id
							FROM hit_producto_ofertas o, hit_producto_folletos f, hit_producto_cuadros c,
									hit_producto_cuadros_alojamientos ca, hit_alojamientos a
							where
								o.FOLLETO = f.CODIGO
								and o.FOLLETO = c.FOLLETO
								and o.CUADRO = c.CUADRO
								and o.FOLLETO = ca.FOLLETO
								and o.CUADRO = ca.CUADRO
								and o.PAQUETE = ca.PAQUETE
								and o.REGIMEN = ca.REGIMEN
								and ca.ALOJAMIENTO = a.ID
								".$CADENA_BUSCAR."
								and o.TIPO = 'H'
								and o.ESTADO = 'A'");
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'PRODUCTO_OFERTAS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id, $orden, $desde, $hasta, $estado){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_producto_ofertas SET ";
		$query .= " ORDEN = '".$orden."'";
		$query .= ", DESDE = '".date("Y-m-d",strtotime($desde))."'";
		$query .= ", HASTA = '".date("Y-m-d",strtotime($hasta))."'";
		$query .= ", ESTADO = '".$estado."'";
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

		$query = "DELETE FROM hit_producto_ofertas WHERE ID = '".$id."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($orden, $tipo, $clave, $desde, $hasta, $estado){

			//orden, tipo, folleto, cuadro, opcion, desde,hasta,estado

		$conexion = $this ->Conexion;
		//echo($tipo);

		if($tipo == 'S'){

			$datos_oferta =$conexion->query("select c.FOLLETO folleto, c.CUADRO cuadro, null paquete, null regimen, null ciudad, cs.ID_PROVEEDOR proveedor, cs.CODIGO_SERVICIO codigo_servicio, cs.CLAVE clave
				from hit_producto_cuadros_servicios cs, hit_producto_cuadros c
				where cs.clave = ".$clave."
				and cs.CLAVE_CUADRO = c.CLAVE");

			$odatos_oferta	 = $datos_oferta->fetch_assoc();
			$folleto = $odatos_oferta['folleto'];
			$cuadro = $odatos_oferta['cuadro'];
			$paquete = $odatos_oferta['paquete'];
			$regimen = $odatos_oferta['regimen'];
			$ciudad = $odatos_oferta['ciudad'];
			$proveedor = $odatos_oferta['proveedor'];
			$codigo_servicio = $odatos_oferta['codigo_servicio'];
			$clave = $odatos_oferta['clave'];


		}else{
			$datos_oferta =$conexion->query("select c.FOLLETO folleto, c.CUADRO cuadro, ca.paquete paquete, ca.regimen regimen, null ciudad, null proveedor, null codigo_servicio, ca.CLAVE clave
				from hit_producto_cuadros_alojamientos ca, hit_producto_cuadros c
				where ca.clave = ".$clave."
				and ca.CLAVE_CUADRO = c.CLAVE");

			$odatos_oferta	 = $datos_oferta->fetch_assoc();
			$folleto = $odatos_oferta['folleto'];
			$cuadro = $odatos_oferta['cuadro'];
			$paquete = $odatos_oferta['paquete'];
			$regimen = $odatos_oferta['regimen'];
			$ciudad = $odatos_oferta['ciudad'];
			$proveedor = $odatos_oferta['proveedor'];
			$codigo_servicio = $odatos_oferta['codigo_servicio'];
			$clave = $odatos_oferta['clave'];		
		}

		//echo($folleto.'-'.$cuadro.'-'.$paquete.'-'.$regimen.'-'.$ciudad.'-'.$proveedor.'-'.$codigo_servicio.'-'.$clave.'-'.$desde.'-'.$hasta.'-'.$estado.'-'.'<br>');

		$query = "INSERT INTO hit_producto_ofertas (ORDEN,TIPO,FOLLETO,CUADRO,PAQUETE,REGIMEN,CIUDAD,PROVEEDOR,CODIGO_SERVICIO, CLAVE, DESDE, HASTA,ESTADO) VALUES (";
		$query .= "'".$orden."',";
		$query .= "'".$tipo."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".$paquete."',";
		$query .= "'".$regimen."',";
		$query .= "'".$ciudad."',";
		$query .= "'".$proveedor."',";
		$query .= "'".$codigo_servicio."',";
		$query .= "'".$clave."',";
		$query .= "'".date("Y-m-d",strtotime($desde))."',";
		$query .= "'".date("Y-m-d",strtotime($hasta))."',";
		$query .= "'".$estado."')";

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
	function clsProducto_ofertas($conexion, $filadesde, $usuario, $buscar_tipo, $buscar_estado){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_tipo = $buscar_tipo;
		$this->Buscar_estado = $buscar_estado;
	}
}

?>