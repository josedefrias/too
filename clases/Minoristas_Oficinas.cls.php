<?php

class clsMinoristas_Oficinas{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var	$buscar_nombre;
	//--------------------------------------------------

	function Cargar($id){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_oficina = $this ->Buscar_oficina;
		$buscar_telefono = $this ->Buscar_telefono;
		$buscar_direccion = $this ->Buscar_direccion;
		$buscar_codigo_postal = $this ->Buscar_codigo_postal;	
		$buscar_provincia = $this ->Buscar_provincia;	

		if($buscar_oficina != null){
			$CADENA_BUSCAR = " WHERE o.PROVINCIA = p.CODIGO AND o.ID = '".$id."' AND o.OFICINA = '".$buscar_oficina."'";
		}elseif($buscar_telefono != null){
			$CADENA_BUSCAR = " WHERE o.PROVINCIA = p.CODIGO AND o.ID = '".$id."' AND o.TELEFONO LIKE '%".$buscar_telefono."%' ";
		}elseif($buscar_direccion != null){
			$CADENA_BUSCAR = " WHERE o.PROVINCIA = p.CODIGO AND o.ID = '".$id."' AND o.DIRECCION LIKE '%".$buscar_direccion."%'";
		}elseif($buscar_codigo_postal != null){
			$CADENA_BUSCAR = " WHERE o.PROVINCIA = p.CODIGO AND o.ID = '".$id."' AND o.CODIGO_POSTAL = '".$buscar_codigo_postal."'";
		}elseif($buscar_provincia != null){
			$CADENA_BUSCAR = " WHERE o.PROVINCIA = p.CODIGO AND o.ID = '".$id."' AND o.PROVINCIA = '".$buscar_provincia."'";
		}else{
			$CADENA_BUSCAR = " WHERE o.PROVINCIA = p.CODIGO AND ID = '".$id."'";
		}

		$resultado =$conexion->query("SELECT p.nombre provincia, o.localidad, o.direccion, o.codigo_postal, o.telefono, o.mail, o.oficina
									  FROM hit_oficinas o, hit_provincias p ".$CADENA_BUSCAR." ORDER BY p.NOMBRE, o.LOCALIDAD, 
									  o.DIRECCION, o.TELEFONO, o.MAIL, o.OFICINA");



		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'MINORISTAS_OFICINAS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$oficinas = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($oficinas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $oficinas;											
	}


	function Cargar_combo_selector($id){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_oficina = $this ->Buscar_oficina;
		$buscar_telefono = $this ->Buscar_telefono;
		$buscar_direccion = $this ->Buscar_direccion;
		$buscar_codigo_postal = $this ->Buscar_codigo_postal;	
		$buscar_provincia = $this ->Buscar_provincia;	
	
		if($buscar_oficina != null){
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND O.ID = '".$id."' AND O.OFICINA = '".$buscar_oficina."'";
		}elseif($buscar_telefono != null){
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND O.ID = '".$id."' AND O.TELEFONO LIKE '%".$buscar_telefono."%' ";
		}elseif($buscar_direccion != null){
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND O.ID = '".$id."' AND O.DIRECCION LIKE '%".$buscar_direccion."%'";
		}elseif($buscar_codigo_postal != null){
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND O.ID = '".$id."' AND O.CODIGO_POSTAL = '".$buscar_codigo_postal."'";
		}elseif($buscar_provincia != null){
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND O.ID = '".$id."' AND O.PROVINCIA = '".$buscar_provincia."'";
		}else{
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND ID = '".$id."'";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT P.NOMBRE, O.LOCALIDAD, O.DIRECCION, O.CODIGO_POSTAL, O.TELEFONO, O.MAIL, O.OFICINA
									  FROM hit_oficinas O, hit_provincias P ".$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'MINORISTAS_OFICINAS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector($filadesde, $boton, $id){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
		
		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_oficina = $this ->Buscar_oficina;
		$buscar_telefono = $this ->Buscar_telefono;
		$buscar_direccion = $this ->Buscar_direccion;
		$buscar_codigo_postal = $this ->Buscar_codigo_postal;	
		$buscar_provincia = $this ->Buscar_provincia;	
	
		if($buscar_oficina != null){
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND O.ID = '".$id."' AND O.OFICINA = '".$buscar_oficina."'";
		}elseif($buscar_telefono != null){
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND O.ID = '".$id."' AND O.TELEFONO LIKE '%".$buscar_telefono."%' ";
		}elseif($buscar_direccion != null){
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND O.ID = '".$id."' AND O.DIRECCION LIKE '%".$buscar_direccion."%'";
		}elseif($buscar_codigo_postal != null){
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND O.ID = '".$id."' AND O.CODIGO_POSTAL = '".$buscar_codigo_postal."'";
		}elseif($buscar_provincia != null){
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND O.ID = '".$id."' AND O.PROVINCIA = '".$buscar_provincia."'";
		}else{
			$CADENA_BUSCAR = " WHERE O.PROVINCIA = P.CODIGO AND ID = '".$id."'";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT P.NOMBRE, O.LOCALIDAD, O.DIRECCION, O.CODIGO_POSTAL, O.TELEFONO, O.MAIL, O.OFICINA
									  FROM hit_oficinas O, hit_provincias P ".$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'MINORISTAS_OFICINAS' AND USUARIO = '".$Usuario."'");
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

	/*function Modificar($codigo, $nombre, $mail){

		$conexion = $this ->Conexion;
		$query = "UPDATE HIT_DEPARTAMENTOS SET ";
		$query .= " NOMBRE = '".$nombre."'";
		$query .= ", MAIL = '".$mail."'";
		$query .= " WHERE CODIGO = '".$codigo."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}*/




	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsMinoristas_Oficinas($conexion, $filadesde, $usuario, $buscar_oficina, $buscar_telefono, $buscar_direccion, $buscar_codigo_postal, $buscar_provincia){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_oficina = $buscar_oficina;
		$this->Buscar_telefono = $buscar_telefono;
		$this->Buscar_direccion = $buscar_direccion;
		$this->Buscar_codigo_postal = $buscar_codigo_postal;
		$this->Buscar_provincia = $buscar_provincia;
	}
}

?>