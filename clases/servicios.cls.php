<?php

class clsServicios{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	var $buscar_id_prove;
	var	$buscar_codigo;
	var	$buscar_tipo;
	var	$buscar_ciudad;
	//--------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------
	function Cargar($recuperaid_proveedor,$recuperacodigo,$recuperatipo,$recuperanombre,$recuperaciudad,$recuperatipo_cupo,$recuperadias_semana,$recuperahora_desde,$recuperahora_hasta,$recuperasituacion,$recuperapago_tipo,$recuperapago_plazo,$recuperapago_forma,$recuperadivisa,$recuperagratuidades_cantidad,$recuperagratuidades_cada,$recuperagratuidades_maximo,$recuperacorresponsal,$recuperaen_reserva,$recuperaimprimir_bono,$recuperatipo_pvp,$recuperadescripcion){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_id = $this ->Buscar_id;
		$buscar_id_prove = $this ->Buscar_id_prove;
		$buscar_codigo = $this ->Buscar_codigo;
		$buscar_tipo = $this ->Buscar_tipo;
		$buscar_ciudad = $this ->Buscar_ciudad;


		if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_id_prove != null){
			$cod = " AND CODIGO = '".$buscar_codigo."'";
			$tip = " AND TIPO = '".$buscar_tipo."'";
			$ciu = " AND CIUDAD = '".$buscar_ciudad."'";
			$CADENA_BUSCAR = " WHERE ID_PROVEEDOR = '".$buscar_id_prove."'";
			if($buscar_codigo != null){
				$CADENA_BUSCAR .= $cod;	
			}
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tip;	
			}
			if($buscar_ciudad != null){
				$CADENA_BUSCAR .= $ciu;	
			}
		}elseif($buscar_codigo != null){
			$tip = " AND TIPO = '".$buscar_tipo."'";
			$ciu = " AND CIUDAD = '".$buscar_ciudad."'";
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tip;	
			}
			if($buscar_ciudad != null){
				$CADENA_BUSCAR .= $ciu;	
			}
		}elseif($buscar_tipo != null){
			$ciu = " AND CIUDAD = '".$buscar_ciudad."'";
			$CADENA_BUSCAR = " WHERE TIPO = '".$buscar_tipo."'";
			if($buscar_ciudad != null){
				$CADENA_BUSCAR .= $ciu;	
			}
		}elseif($buscar_ciudad != null){
			$CADENA_BUSCAR = " WHERE CIUDAD = '".$buscar_ciudad."'";
			//$CADENA_BUSCAR = "";
		}else{
			//$CADENA_BUSCAR = " WHERE ID = '0' ";
			$CADENA_BUSCAR = "";
		}


		$resultado =$conexion->query("SELECT id, id_proveedor, codigo,tipo,nombre,ciudad,tipo_cupo,dias_semana,time_format(hora_desde, '%H:%i') AS hora_desde,time_format(hora_hasta, '%H:%i') AS hora_hasta, situacion,pago_tipo,pago_plazo,pago_forma, divisa, gratuidades_cantidad, gratuidades_cada,gratuidades_maximo,corresponsal,en_reserva,imprimir_bono,tipo_pvp,descripcion FROM hit_servicios ".$CADENA_BUSCAR." ORDER BY id_proveedor, codigo");

		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$servicios = array();
		if($recuperaid_proveedor != null){
			$servicios[0] = array ("id" => null, "id_proveedor" => $recuperaid_proveedor, "codigo" => $recuperacodigo, "tipo" => $recuperatipo, "nombre" => $recuperanombre, "ciudad" => $recuperaciudad, "tipo_cupo" => $recuperatipo_cupo, "dias_semana" => $recuperadias_semana, "hora_desde" => $recuperahora_desde, "hora_hasta" => $recuperahora_hasta, "situacion" => $recuperasituacion, "pago_tipo" => $recuperapago_tipo, "pago_plazo" => $recuperapago_plazo, "pago_forma" => $recuperapago_forma, "divisa" => $recuperadivisa, "gratuidades_cantidad" => $recuperagratuidades_cantidad, "gratuidades_cada" => $recuperagratuidades_cada, "gratuidades_maximo" => $recuperagratuidades_maximo, "corresponsal" => $recuperacorresponsal, "en_reserva" => $recuperaen_reserva, "imprimir_bono" => $recuperaimprimir_bono, "tipo_pvp" => $recuperatipo_pvp, "descripcion" => $recuperadescripcion);
		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($servicios,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $servicios;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_id = $this ->Buscar_id;
		$buscar_id_prove = $this ->Buscar_id_prove;
		$buscar_codigo = $this ->Buscar_codigo;
		$buscar_tipo = $this ->Buscar_tipo;
		$buscar_ciudad = $this ->Buscar_ciudad;


		if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_id_prove != null){
			$cod = " AND CODIGO = '".$buscar_codigo."'";
			$tip = " AND TIPO = '".$buscar_tipo."'";
			$ciu = " AND CIUDAD = '".$buscar_ciudad."'";
			$CADENA_BUSCAR = " WHERE ID_PROVEEDOR = '".$buscar_id_prove."'";
			if($buscar_codigo != null){
				$CADENA_BUSCAR .= $cod;	
			}
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tip;	
			}
			if($buscar_ciudad != null){
				$CADENA_BUSCAR .= $ciu;	
			}
		}elseif($buscar_codigo != null){
			$tip = " AND TIPO = '".$buscar_tipo."'";
			$ciu = " AND CIUDAD = '".$buscar_ciudad."'";
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tip;	
			}
			if($buscar_ciudad != null){
				$CADENA_BUSCAR .= $ciu;	
			}
		}elseif($buscar_tipo != null){
			$ciu = " AND CIUDAD = '".$buscar_ciudad."'";
			$CADENA_BUSCAR = " WHERE TIPO = '".$buscar_tipo."'";
			if($buscar_ciudad != null){
				$CADENA_BUSCAR .= $ciu;	
			}
		}elseif($buscar_ciudad != null){
			$CADENA_BUSCAR = " WHERE CIUDAD = '".$buscar_ciudad."'";
			//$CADENA_BUSCAR = "";
		}else{
			//$CADENA_BUSCAR = " WHERE ID = '0' ";
			$CADENA_BUSCAR = "";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_servicios'.$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_id = $this ->Buscar_id;
		$buscar_id_prove = $this ->Buscar_id_prove;
		$buscar_codigo = $this ->Buscar_codigo;
		$buscar_tipo = $this ->Buscar_tipo;
		$buscar_ciudad = $this ->Buscar_ciudad;


		if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_id_prove != null){
			$cod = " AND CODIGO = '".$buscar_codigo."'";
			$tip = " AND TIPO = '".$buscar_tipo."'";
			$ciu = " AND CIUDAD = '".$buscar_ciudad."'";
			$CADENA_BUSCAR = " WHERE ID_PROVEEDOR = '".$buscar_id_prove."'";
			if($buscar_codigo != null){
				$CADENA_BUSCAR .= $cod;	
			}
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tip;	
			}
			if($buscar_ciudad != null){
				$CADENA_BUSCAR .= $ciu;	
			}
		}elseif($buscar_codigo != null){
			$tip = " AND TIPO = '".$buscar_tipo."'";
			$ciu = " AND CIUDAD = '".$buscar_ciudad."'";
			$CADENA_BUSCAR = " WHERE CODIGO = '".$buscar_codigo."'";
			if($buscar_tipo != null){
				$CADENA_BUSCAR .= $tip;	
			}
			if($buscar_ciudad != null){
				$CADENA_BUSCAR .= $ciu;	
			}
		}elseif($buscar_tipo != null){
			$ciu = " AND CIUDAD = '".$buscar_ciudad."'";
			$CADENA_BUSCAR = " WHERE TIPO = '".$buscar_tipo."'";
			if($buscar_ciudad != null){
				$CADENA_BUSCAR .= $ciu;	
			}
		}elseif($buscar_ciudad != null){
			$CADENA_BUSCAR = " WHERE CIUDAD = '".$buscar_ciudad."'";
			//$CADENA_BUSCAR = "";
		}else{
			//$CADENA_BUSCAR = " WHERE ID = '0' ";
			$CADENA_BUSCAR = "";
		}											

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_servicios'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id,$id_proveedor,$codigo,$tipo,$nombre,$ciudad,$tipo_cupo,$dias_semana,$hora_desde,$hora_hasta,$situacion,$pago_tipo,$pago_plazo,$pago_forma,$divisa,$gratuidades_cantidad,$gratuidades_cada,$gratuidades_maximo,$corresponsal,$en_reserva,$imprimir_bono,$tipo_pvp,$descripcion){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_servicios SET ";
		$query .= " TIPO = '".$tipo."'";
		$query .= ", NOMBRE = '".$nombre."'";
		$query .= ", CIUDAD = '".$ciudad."'";
		$query .= ", TIPO_CUPO = '".$tipo_cupo."'";
		$query .= ", DIAS_SEMANA = '".$dias_semana."'";
		$query .= ", HORA_DESDE = '".$hora_desde."'";
		$query .= ", HORA_HASTA = '".$hora_hasta."'";
		$query .= ", SITUACION = '".$situacion."'";
		$query .= ", PAGO_TIPO = '".$pago_tipo."'";
		$query .= ", PAGO_PLAZO = '".$pago_plazo."'";
		$query .= ", PAGO_FORMA = '".$pago_forma."'";
		$query .= ", DIVISA = '".$divisa."'";
		$query .= ", GRATUIDADES_CANTIDAD = '".$gratuidades_cantidad."'";
		$query .= ", GRATUIDADES_CADA = '".$gratuidades_cada."'";
		$query .= ", GRATUIDADES_MAXIMO = '".$gratuidades_maximo."'";
		$query .= ", CORRESPONSAL = '".$corresponsal."'";
		$query .= ", EN_RESERVA = '".$en_reserva."'";
		$query .= ", IMPRIMIR_BONO = '".$imprimir_bono."'";
		$query .= ", TIPO_PVP = '".$tipo_pvp."'";
		$query .= ", DESCRIPCION = '".$descripcion."'";
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

		$query = "DELETE FROM hit_servicios WHERE ID = '".$id."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar($id_proveedor,$codigo,$tipo,$nombre,$ciudad,$tipo_cupo,$dias_semana,$hora_desde,$hora_hasta,$situacion,$pago_tipo,$pago_plazo,$pago_forma,$divisa,$gratuidades_cantidad,$gratuidades_cada,$gratuidades_maximo,$corresponsal,$en_reserva,$imprimir_bono,$tipo_pvp,$descripcion){

		$conexion = $this ->Conexion;


		$query = "INSERT INTO hit_servicios(ID_PROVEEDOR,CODIGO,TIPO,NOMBRE,CIUDAD,TIPO_CUPO,DIAS_SEMANA,HORA_DESDE,HORA_HASTA,SITUACION,PAGO_TIPO,
		PAGO_PLAZO,PAGO_FORMA,DIVISA,GRATUIDADES_CANTIDAD,GRATUIDADES_CADA,GRATUIDADES_MAXIMO,CORRESPONSAL,EN_RESERVA,
		IMPRIMIR_BONO,TIPO_PVP,DESCRIPCION) VALUES (";
		$query .= "'".$id_proveedor."',";
		$query .= "'".$codigo."',";
		$query .= "'".$tipo."',";
		$query .= "'".$nombre."',";
		$query .= "'".$ciudad."',";
		$query .= "'".$tipo_cupo."',";
		$query .= "'".$dias_semana."',";
		$query .= "'".$hora_desde."',";
		$query .= "'".$hora_hasta."',";
		$query .= "'".$situacion."',";
		$query .= "'".$pago_tipo."',";
		$query .= "'".$pago_plazo."',";
		$query .= "'".$pago_forma."',";
		$query .= "'".$divisa."',";
		$query .= "'".$gratuidades_cantidad."',";
		$query .= "'".$gratuidades_cada."',";
		$query .= "'".$gratuidades_maximo."',";
		$query .= "'".$corresponsal."',";
		$query .= "'".$en_reserva."',";
		$query .= "'".$imprimir_bono."',";
		$query .= "'".$tipo_pvp."',";
		$query .= "'".$descripcion."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}


//--------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE PRECIOS----
//--------------------------------------------------------------------

	function Cargar_precios($id, $filadesde, $buscar_fecha){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";     
  		}

		$resultado =$conexion->query("SELECT DATE_FORMAT(fecha_desde, '%d-%m-%Y') AS fecha_desde_precios, DATE_FORMAT(fecha_hasta, '%d-%m-%Y') AS fecha_hasta_precios, dias_semana precios_dias_semana, tipo_unidad, unidad_desde, unidad_hasta, coste, coste_ninos, tipo_cupo tipo_cupo_precios, dias_release dias_release_precios, expandido FROM hit_servicios_precios ".$CADENA_BUSCAR." ORDER BY fecha_desde, fecha_hasta, tipo_unidad, unidad_desde, unidad_hasta");



		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$precios = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['fecha_desde_precios'] == ''){
				break;
			}
			array_push($precios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $precios;											
	}


	function Cargar_lineas_nuevas_precios(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_precios($id, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";     
  		}									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_servicios_precios'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------


		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		if($numero_filas > 0){
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_precios($filadesde, $boton, $id, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;
		
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";     
  		}									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_servicios_precios'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------
		
		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_precios($id, $fecha_desde, $fecha_hasta, $precios_dias_semana, $tipo_unidad,$unidad_desde, $unidad_hasta,$coste,$coste_ninos, $tipo_cupo_precios, $dias_release_precios, $expandido, $fecha_desde_old, $fecha_hasta_old, $tipo_unidad_old, $unidad_desde_old, $unidad_hasta_old, $precios_dias_semana_old){

		//$fecha2=date("Y-m-d",strtotime($fecha_desde));
		//echo(date("Y-m-d",strtotime($fecha_desde)));

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_servicios_precios SET ";
		$query .= " FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= ", FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		$query .= ", DIAS_SEMANA = '".$precios_dias_semana."'";
		$query .= ", TIPO_UNIDAD = '".$tipo_unidad."'";
		$query .= ", UNIDAD_DESDE = '".$unidad_desde."'";
		$query .= ", UNIDAD_HASTA = '".$unidad_hasta."'";
		$query .= ", COSTE = '".$coste."'";
		$query .= ", COSTE_NINOS = '".$coste_ninos."'";

		$query .= ", TIPO_CUPO = '".$tipo_cupo_precios."'";
		$query .= ", DIAS_RELEASE = '".$dias_release_precios."'";
		$query .= ", EXPANDIDO = '".$expandido."'";		
		//$query .= ", CALCULO = '".$calculo."'";
		//$query .= ", PORCENTAJE_NETO = '".$porcentaje_neto."'";
		//$query .= ", NETO = '".$neto."'";
		//$query .= ", PORCENTAJE_COM = '".$porcentaje_com."'";
		//$query .= ", PVP = '".$pvp."'";
		$query .= " WHERE ID = '".$id."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde_old))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta_old))."'";
		$query .= " AND TIPO_UNIDAD = '".$tipo_unidad_old."'";
		$query .= " AND UNIDAD_DESDE = '".$unidad_desde_old."'";
		$query .= " AND UNIDAD_HASTA = '".$unidad_hasta_old."'";
		$query .= " AND DIAS_SEMANA = '".$precios_dias_semana_old."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
			//echo($query);
		}else{
			$respuesta = 'OK';
			//echo($query);
		}

		return $respuesta;											
	}

	function Borrar_precios($id,$fecha_desde,$fecha_hasta,$tipo_unidad, $unidad_desde, $unidad_hasta, $precios_dias_semana){

		$conexion = $this ->Conexion;



		$hay_cupos_expandidos =$conexion->query("select count(*) hay_cupos_expandidos from hit_servicios_cupos where id = '".$id."' 
			and fecha between '".date("Y-m-d",strtotime($fecha_desde))."' and  '".date("Y-m-d",strtotime($fecha_hasta))."'");

		$ohay_cupos_expandidos	 = $hay_cupos_expandidos->fetch_assoc();											                        
		$hay_cupos= $ohay_cupos_expandidos['hay_cupos_expandidos'];

		if($hay_cupos == 0){

			$query = "DELETE FROM hit_servicios_precios WHERE ID = '".$id."'";
			$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
			$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
			$query .= " AND TIPO_UNIDAD = '".$tipo_unidad."'";
			$query .= " AND UNIDAD_DESDE = '".$unidad_desde."'";
			$query .= " AND UNIDAD_HASTA = '".$unidad_hasta."'";
			$query .= " AND DIAS_SEMANA = '".$precios_dias_semana."'";

			$resultadob =$conexion->query($query);

			if ($resultadob == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}
		}else{
			$respuesta = "Hay cupos expandidos. <br>No es posible borrar esta linea de precios. <br>Borre primero los cupos pulsando el botón de borrado de cupos. <br>Tenga en cuenta que si hay reservas realizadas no se borrarán esas fechas de cupo.";
		}




		return $respuesta;											
	}

	function Insertar_precios($id,$fecha_desde,$fecha_hasta, $precios_dias_semana, $tipo_unidad,$unidad_desde, $unidad_hasta,$coste,$coste_ninos, $tipo_cupo_precios, $dias_release_precios, $expandido){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_servicios_precios (ID, FECHA_DESDE, FECHA_HASTA, DIAS_SEMANA, TIPO_UNIDAD, UNIDAD_DESDE, UNIDAD_HASTA, COSTE, COSTE_NINOS, TIPO_CUPO, DIAS_RELEASE, EXPANDIDO) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta))."',";
		$query .= "'".$precios_dias_semana."',";
		$query .= "'".$tipo_unidad."',";
		$query .= "'".$unidad_desde."',";
		$query .= "'".$unidad_hasta."',";
		$query .= "'".$coste."',";
		$query .= "'".$coste_ninos."',";
		$query .= "'".$tipo_cupo_precios."',";
		$query .= "'".$dias_release_precios."',";
		$query .= "'".$expandido."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}

//------------------------------------------------------------------------------------------
//------METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE PRECIOS de PROGRAMAS COMPLETOS----
//------------------------------------------------------------------------------------------

	function Cargar_precios_programas($id, $filadesde, $buscar_fecha){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";     
  		}

		$resultado =$conexion->query("SELECT DATE_FORMAT(fecha_desde, '%d-%m-%Y') AS fecha_desde_precios, DATE_FORMAT(fecha_hasta, '%d-%m-%Y') AS fecha_hasta_precios, dias_semana precios_dias_semana, tipo_unidad, unidad_desde, unidad_hasta, paquete, habitacion, caracteristica, uso, coste, coste_ninos, tipo_cupo tipo_cupo_precios, dias_release dias_release_precios, expandido FROM hit_servicios_precios_programas ".$CADENA_BUSCAR." ORDER BY fecha_desde, fecha_hasta, tipo_unidad, unidad_desde, unidad_hasta, paquete, habitacion, caracteristica, uso");



		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS_PROGRAMAS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$precios = array();
		for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['fecha_desde_precios'] == '' and $num_fila != $filadesde-1){
				break;
			}
			array_push($precios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $precios;											
	}


	function Cargar_lineas_nuevas_precios_programas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS_PROGRAMAS' AND USUARIO = '".$Usuario."'");
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


	function Cargar_combo_selector_precios_programas($id, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";     
  		}									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_servicios_precios_programas'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows;         //----------


		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		if($numero_filas > 0){
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS_PROGRAMAS' AND USUARIO = '".$Usuario."'");
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

	function Botones_selector_precios_programas($filadesde, $boton, $id, $buscar_fecha){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;	
		
		if($buscar_fecha != null){
			$CADENA_BUSCAR = " WHERE ID = '".$id."' AND '".date("Y-m-d",strtotime($buscar_fecha))."' BETWEEN FECHA_DESDE AND FECHA_HASTA";
		}else{
			$CADENA_BUSCAR = " WHERE ID = '".$id."'";     
  		}									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_servicios_precios_programas'.$CADENA_BUSCAR);
		$numero_filas = $resultadoc->num_rows; 
		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'SERVICIOS_PRECIOS_PROGRAMAS' AND USUARIO = '".$Usuario."'");
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

	function Modificar_precios_programas($id, $fecha_desde, $fecha_hasta, $precios_dias_semana, $tipo_unidad,$unidad_desde, $unidad_hasta,$paquete, $habitacion, $caracteristica, $uso, $coste, $coste_ninos, $tipo_cupo_precios, $dias_release_precios, $expandido, $fecha_desde_old, $fecha_hasta_old, $tipo_unidad_old, $unidad_desde_old, $unidad_hasta_old, $paquete_old, $habitacion_old, $caracteristica_old, $uso_old, $precios_dias_semana_old){

		//$fecha2=date("Y-m-d",strtotime($fecha_desde));
		//echo(date("Y-m-d",strtotime($fecha_desde)));

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_servicios_precios_programas SET ";
		$query .= " FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
		$query .= ", FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
		$query .= ", DIAS_SEMANA = '".$precios_dias_semana."'";
		$query .= ", TIPO_UNIDAD = '".$tipo_unidad."'";
		$query .= ", UNIDAD_DESDE = '".$unidad_desde."'";
		$query .= ", UNIDAD_HASTA = '".$unidad_hasta."'";
		$query .= ", PAQUETE = '".$paquete."'";
		$query .= ", HABITACION = '".$habitacion."'";
		$query .= ", CARACTERISTICA = '".$caracteristica."'";
		$query .= ", USO = '".$uso."'";
		$query .= ", COSTE = '".$coste."'";
		$query .= ", COSTE_NINOS = '".$coste_ninos."'";

		$query .= ", TIPO_CUPO = '".$tipo_cupo_precios."'";
		$query .= ", DIAS_RELEASE = '".$dias_release_precios."'";
		$query .= ", EXPANDIDO = '".$expandido."'";

		$query .= " WHERE ID = '".$id."'";
		$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde_old))."'";
		$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta_old))."'";
		$query .= " AND TIPO_UNIDAD = '".$tipo_unidad_old."'";
		$query .= " AND UNIDAD_DESDE = '".$unidad_desde_old."'";
		$query .= " AND UNIDAD_HASTA = '".$unidad_hasta_old."'";
		$query .= " AND PAQUETE = '".$paquete_old."'";
		$query .= " AND HABITACION = '".$habitacion_old."'";
		$query .= " AND CARACTERISTICA = '".$caracteristica_old."'";
		$query .= " AND USO = '".$uso_old."'";
		$query .= " AND DIAS_SEMANA = '".$precios_dias_semana_old."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
			//echo($query);
		}else{
			$respuesta = 'OK';
			//echo($query);
		}

		return $respuesta;											
	}

	function Borrar_precios_programas($id,$fecha_desde,$fecha_hasta,$tipo_unidad, $unidad_desde, $unidad_hasta,$paquete, $habitacion, $caracteristica, $uso, $precios_dias_semana){

		$conexion = $this ->Conexion;

		$hay_cupos_expandidos =$conexion->query("select count(*) hay_cupos_expandidos from hit_servicios_cupos where id = '".$id."' 
			and fecha between '".date("Y-m-d",strtotime($fecha_desde))."' and  '".date("Y-m-d",strtotime($fecha_hasta))."'");

		$ohay_cupos_expandidos	 = $hay_cupos_expandidos->fetch_assoc();											                        
		$hay_cupos= $ohay_cupos_expandidos['hay_cupos_expandidos'];

		if($hay_cupos == 0){


			$query = "DELETE FROM hit_servicios_precios_programas WHERE ID = '".$id."'";
			$query .= " AND FECHA_DESDE = '".date("Y-m-d",strtotime($fecha_desde))."'";
			$query .= " AND FECHA_HASTA = '".date("Y-m-d",strtotime($fecha_hasta))."'";
			$query .= " AND TIPO_UNIDAD = '".$tipo_unidad."'";
			$query .= " AND UNIDAD_DESDE = '".$unidad_desde."'";
			$query .= " AND UNIDAD_HASTA = '".$unidad_hasta."'";
			$query .= " AND PAQUETE = '".$paquete."'";
			$query .= " AND HABITACION = '".$habitacion."'";
			$query .= " AND CARACTERISTICA = '".$caracteristica."'";
			$query .= " AND USO = '".$uso."'";
			$query .= " AND DIAS_SEMANA = '".$precios_dias_semana."'";

			$resultadob =$conexion->query($query);

			if ($resultadob == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}
		}else{
			$respuesta = "Hay cupos expandidos. <br>No es posible borrar esta linea de precios. <br>Borre primero los cupos pulsando el botón de borrado de cupos. <br>Tenga en cuenta que si hay reservas realizadas no se borrarán esas fechas de cupo.";
		}

		return $respuesta;											
	}

	function Insertar_precios_programas($id,$fecha_desde,$fecha_hasta, $precios_dias_semana, $tipo_unidad,$unidad_desde, $unidad_hasta,$paquete, $habitacion, $caracteristica, $uso,$coste, $coste_ninos, $tipo_cupo_precios, $dias_release_precios, $expandido){

		$conexion = $this ->Conexion;
		$query = "INSERT INTO hit_servicios_precios_programas (ID, FECHA_DESDE, FECHA_HASTA, DIAS_SEMANA, TIPO_UNIDAD, UNIDAD_DESDE, UNIDAD_HASTA, PAQUETE, HABITACION, CARACTERISTICA, USO, COSTE, COSTE_NINOS, TIPO_CUPO, DIAS_RELEASE, EXPANDIDO) VALUES (";
		$query .= "'".$id."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_desde))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_hasta))."',";
		$query .= "'".$precios_dias_semana."',";
		$query .= "'".$tipo_unidad."',";
		$query .= "'".$unidad_desde."',";
		$query .= "'".$unidad_hasta."',";
		$query .= "'".$paquete."',";
		$query .= "'".$habitacion."',";
		$query .= "'".$caracteristica."',";
		$query .= "'".$uso."',";
		$query .= "'".$coste_ninos."',";
		$query .= "'".$tipo_cupo_precios."',";
		$query .= "'".$dias_release_precios."',";
		$query .= "'".$expandido."')";

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
	function clsServicios($conexion, $filadesde, $usuario, $buscar_id, $buscar_id_prove, $buscar_codigo, $buscar_tipo, $buscar_ciudad){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id = $buscar_id;
		$this->Buscar_id_prove = $buscar_id_prove;
		$this->Buscar_codigo = $buscar_codigo;
		$this->Buscar_tipo = $buscar_tipo;
		$this->Buscar_ciudad = $buscar_ciudad;
	}
}

?>