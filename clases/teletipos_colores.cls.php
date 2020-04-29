<?php

class clsTeletipos_colores{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_id;
	//--------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------
	function Cargar($recuperaid,
					$recuperanombre,
					$recuperacolor_borde_logo,
					$recuperacolor_fondo_logo,
					$recuperacolor_cabecera_fondo_logo_izquierda,
					$recuperacolor_cabecera_fondo_destino,
					$recuperacolor_cabecera_fondo_logo_derecha,
					$recuperacolor_cabecera_fuente_destino,
					$recuperacolor_cabecera_imagen_fondo,
					$recuperacolor_tabla_fondo,
					$recuperacolor_cabecera_contenido_fondo,
					$recuperacolor_titulo,
					$recuperacolor_ciudad_salida,
					$recuperacolor_texto_vuelos,
					$recuperacolor_bloque_fondo_cabecera,
					$recuperacolor_bloque_fondo_imagen,
					$recuperacolor_bloque_fondo_precios,
					$recuperacolor_cabecera_precios_nombre_hotel,
					$recuperacolor_cabecera_precios_categoria_hotel,
					$recuperacolor_cabecera_precios_regimen_hotel,
					$recuperacolor_cabecera_precios_precio1_hotel,
					$recuperacolor_cabecera_precios_precio2_hotel,
					$recuperacolor_cabecera_precios_precio3_hotel,
					$recuperacolor_precios_nombre_hotel,
					$recuperacolor_precios_categoria_hotel,
					$recuperacolor_precios_localidad_hotel,
					$recuperacolor_precios_regimen_hotel_tabla,
					$recuperacolor_precios_regimen_hotel_bloques,
					$recuperacolor_precios_precio1_hotel,
					$recuperacolor_precios_precio2_hotel,
					$recuperacolor_precios_precio3_hotel,
					$recuperacolor_pie_fondo,
					$recuperacolor_pie_borde,
					$recuperacolor_pie_texto){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_id = $this ->Buscar_id;
		

		/*if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";				
		}else{
			$CADENA_BUSCAR = "";
		}	*/

		$resultado =$conexion->query("SELECT id,
												nombre,
												color_borde_logo,
												color_fondo_logo,
												color_cabecera_fondo_logo_izquierda,
												color_cabecera_fondo_destino,
												color_cabecera_fondo_logo_derecha,
												color_cabecera_fuente_destino,
												color_cabecera_imagen_fondo,
												color_tabla_fondo,
												color_cabecera_contenido_fondo,
												color_titulo,
												color_ciudad_salida,
												color_texto_vuelos,
												color_bloque_fondo_cabecera,
												color_bloque_fondo_imagen,
												color_bloque_fondo_precios,
												color_cabecera_precios_nombre_hotel,
												color_cabecera_precios_categoria_hotel,
												color_cabecera_precios_regimen_hotel,
												color_cabecera_precios_precio1_hotel,
												color_cabecera_precios_precio2_hotel,
												color_cabecera_precios_precio3_hotel,
												color_precios_nombre_hotel,
												color_precios_categoria_hotel,
												color_precios_localidad_hotel,
												color_precios_regimen_hotel_tabla,
												color_precios_regimen_hotel_bloques,
												color_precios_precio1_hotel,
												color_precios_precio2_hotel,
												color_precios_precio3_hotel,
												color_pie_fondo,
												color_pie_borde,
												color_pie_texto
									FROM hit_teletipos_colores WHERE ID = '".$buscar_id."' ORDER BY nombre");
	

		//ECHO('-'.$buscar_id.'-');
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_COLORES' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$teletipos_colores = array();
		if($recuperanombre != null){
			$teletipos_colores[0] = array ("id" => $recuperaid,
										"nombre" => $recuperanombre,
										"color_borde_logo" => $recuperacolor_borde_logo,
										"color_fondo_logo" => $recuperacolor_fondo_logo,
										"color_cabecera_fondo_logo_izquierda" => $recuperacolor_cabecera_fondo_logo_izquierda,
										"color_cabecera_fondo_destino" => $recuperacolor_cabecera_fondo_destino,
										"color_cabecera_fondo_logo_derecha" => $recuperacolor_cabecera_fondo_logo_derecha,
										"color_cabecera_fuente_destino" => $recuperacolor_cabecera_fuente_destino,
										"color_cabecera_imagen_fondo" => $recuperacolor_cabecera_imagen_fondo,
										"color_tabla_fondo" => $recuperacolor_tabla_fondo,
										"color_cabecera_contenido_fondo" => $recuperacolor_cabecera_contenido_fondo,
										"color_titulo" => $recuperacolor_titulo,
										"color_ciudad_salida" => $recuperacolor_ciudad_salida,
										"color_texto_vuelos" => $recuperacolor_texto_vuelos,
										"color_bloque_fondo_cabecera" => $recuperacolor_bloque_fondo_cabecera,
										"color_bloque_fondo_imagen" => $recuperacolor_bloque_fondo_imagen,
										"color_bloque_fondo_precios" => $recuperacolor_bloque_fondo_precios,
										"color_cabecera_precios_nombre_hotel" => $recuperacolor_cabecera_precios_nombre_hotel,
										"color_cabecera_precios_categoria_hotel" => $recuperacolor_cabecera_precios_categoria_hotel,
										"color_cabecera_precios_regimen_hotel" => $recuperacolor_cabecera_precios_regimen_hotel,
										"color_cabecera_precios_precio1_hotel" => $recuperacolor_cabecera_precios_precio1_hotel,
										"color_cabecera_precios_precio2_hotel," => $recuperacolor_cabecera_precios_precio2_hotel,
										"color_cabecera_precios_precio3_hotel" => $recuperacolor_cabecera_precios_precio3_hotel,
										"color_precios_nombre_hotel" => $recuperacolor_precios_nombre_hotel,
										"color_precios_categoria_hotel" => $recuperacolor_precios_categoria_hotel,
										"color_precios_localidad_hotel" => $recuperacolor_precios_localidad_hotel,
										"color_precios_regimen_hotel_tabla" => $recuperacolor_precios_regimen_hotel_tabla,
										"color_precios_regimen_hotel_bloques" => $recuperacolor_precios_regimen_hotel_bloques,
										"color_precios_precio1_hotel" => $recuperacolor_precios_precio1_hotel,
										"color_precios_precio2_hotel" => $recuperacolor_precios_precio2_hotel,
										"color_precios_precio3_hotel" => $recuperacolor_precios_precio3_hotel,
										"color_pie_fondo" => $recuperacolor_pie_fondo,
										"color_pie_borde" => $recuperacolor_pie_borde,
										"color_pie_texto" => $recuperacolor_pie_texto);


		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($teletipos_colores,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $teletipos_colores;											
	}


	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		$buscar_id = $this ->Buscar_id;

		/*if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";				
		}else{
			$CADENA_BUSCAR = "";
		}		*/									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_teletipos_colores WHERE ID = '".$buscar_id."' ORDER BY nombre");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_COLORES' AND USUARIO = '".$Usuario."'");
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

		/*if($buscar_id != null){
			$CADENA_BUSCAR = " WHERE ID = '".$buscar_id."'";
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " WHERE NOMBRE LIKE '%".$buscar_nombre."%'";				
		}else{
			$CADENA_BUSCAR = "";
		}		*/									

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("SELECT * FROM hit_teletipos_colores WHERE ID = ".$buscar_id." ORDER BY nombre");
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TELETIPOS_COLORES' AND USUARIO = '".$Usuario."'");
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

	function Modificar($id,
						$nombre,
						$color_borde_logo,
						$color_fondo_logo,
						$color_cabecera_fondo_logo_izquierda,
						$color_cabecera_fondo_destino,
						$color_cabecera_fondo_logo_derecha,
						$color_cabecera_fuente_destino,
						$color_cabecera_imagen_fondo,
						$color_tabla_fondo,
						$color_cabecera_contenido_fondo,
						$color_titulo,
						$color_ciudad_salida,
						$color_texto_vuelos,
						$color_bloque_fondo_cabecera,
						$color_bloque_fondo_imagen,
						$color_bloque_fondo_precios,
						$color_cabecera_precios_nombre_hotel,
						$color_cabecera_precios_categoria_hotel,
						$color_cabecera_precios_regimen_hotel,
						$color_cabecera_precios_precio1_hotel,
						$color_cabecera_precios_precio2_hotel,
						$color_cabecera_precios_precio3_hotel,
						$color_precios_nombre_hotel,
						$color_precios_categoria_hotel,
						$color_precios_localidad_hotel,
						$color_precios_regimen_hotel_tabla,
						$color_precios_regimen_hotel_bloques,
						$color_precios_precio1_hotel,
						$color_precios_precio2_hotel,
						$color_precios_precio3_hotel,
						$color_pie_fondo,
						$color_pie_borde,
						$color_pie_texto){


		if($id != 1){

			$conexion = $this ->Conexion;
			$query = "UPDATE hit_teletipos_colores SET ";
			$query .= " NOMBRE = '".$nombre."'";
			$query .= ", COLOR_BORDE_LOGO = '".$color_borde_logo."'";
			$query .= ", COLOR_FONDO_LOGO = '".$color_fondo_logo."'";
			$query .= ", COLOR_CABECERA_FONDO_LOGO_IZQUIERDA = '".$color_cabecera_fondo_logo_izquierda."'";
			$query .= ", COLOR_CABECERA_FONDO_DESTINO = '".$color_cabecera_fondo_destino."'";
			$query .= ", COLOR_CABECERA_FONDO_LOGO_DERECHA = '".$color_cabecera_fondo_logo_derecha."'";
			$query .= ", COLOR_CABECERA_FUENTE_DESTINO = '".$color_cabecera_fuente_destino."'";
			$query .= ", COLOR_CABECERA_IMAGEN_FONDO = '".$color_cabecera_imagen_fondo."'";
			$query .= ", COLOR_TABLA_FONDO = '".$color_tabla_fondo."'";
			$query .= ", COLOR_CABECERA_CONTENIDO_FONDO = '".$color_cabecera_contenido_fondo."'";
			$query .= ", COLOR_TITULO = '".$color_titulo."'";
			$query .= ", COLOR_CIUDAD_SALIDA = '".$color_ciudad_salida."'";
			$query .= ", COLOR_TEXTO_VUELOS = '".$color_texto_vuelos."'";
			$query .= ", COLOR_BLOQUE_FONDO_CABECERA = '".$color_bloque_fondo_cabecera."'";
			$query .= ", COLOR_BLOQUE_FONDO_IMAGEN = '".$color_bloque_fondo_imagen."'";
			$query .= ", COLOR_BLOQUE_FONDO_PRECIOS = '".$color_bloque_fondo_precios."'";
			$query .= ", COLOR_CABECERA_PRECIOS_NOMBRE_HOTEL = '".$color_cabecera_precios_nombre_hotel."'";
			$query .= ", COLOR_CABECERA_PRECIOS_CATEGORIA_HOTEL = '".$color_cabecera_precios_categoria_hotel."'";
			$query .= ", COLOR_CABECERA_PRECIOS_REGIMEN_HOTEL = '".$color_cabecera_precios_regimen_hotel."'";
			$query .= ", COLOR_CABECERA_PRECIOS_PRECIO1_HOTEL = '".$color_cabecera_precios_precio1_hotel."'";
			$query .= ", COLOR_CABECERA_PRECIOS_PRECIO2_HOTEL = '".$color_cabecera_precios_precio2_hotel."'";
			$query .= ", COLOR_CABECERA_PRECIOS_PRECIO3_HOTEL = '".$color_cabecera_precios_precio3_hotel."'";
			$query .= ", COLOR_PRECIOS_NOMBRE_HOTEL = '".$color_precios_nombre_hotel."'";
			$query .= ", COLOR_PRECIOS_CATEGORIA_HOTEL = '".$color_precios_categoria_hotel."'";
			$query .= ", COLOR_PRECIOS_LOCALIDAD_HOTEL = '".$color_precios_localidad_hotel."'";
			$query .= ", COLOR_PRECIOS_REGIMEN_HOTEL_TABLA = '".$color_precios_regimen_hotel_tabla."'";
			$query .= ", COLOR_PRECIOS_REGIMEN_HOTEL_BLOQUES = '".$color_precios_regimen_hotel_bloques."'";
			$query .= ", COLOR_PRECIOS_PRECIO1_HOTEL = '".$color_precios_precio1_hotel."'";
			$query .= ", COLOR_PRECIOS_PRECIO2_HOTEL = '".$color_precios_precio2_hotel."'";
			$query .= ", COLOR_PRECIOS_PRECIO3_HOTEL = '".$color_precios_precio3_hotel."'";
			$query .= ", COLOR_PIE_FONDO = '".$color_pie_fondo."'";
			$query .= ", COLOR_PIE_BORDE = '".$color_pie_borde."'";
			$query .= ", COLOR_PIE_TEXTO = '".$color_pie_texto."'";
			$query .= " WHERE ID = '".$id."' and ID <> '1'";

			$resultadom =$conexion->query($query);

			if ($resultadom == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}

		}else{
			$respuesta = 'No esta permitido realizar cambios en la combinacion base.';
		}

		return $respuesta;											
	}

	function Borrar($id){

		if($id != 1){

			$conexion = $this ->Conexion;

			$query = "DELETE FROM hit_teletipos_colores WHERE ID = '".$id."'";

			$resultadob =$conexion->query($query);

			if ($resultadob == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}
		}else{
			$respuesta = 'No es posible borrar la combinacion base.';
		}

		return $respuesta;											
	}

	function Insertar($nombre){

		$conexion = $this ->Conexion;


		$query = "INSERT INTO hit_teletipos_colores (NOMBRE) VALUES (";

		$query .= "'".$nombre."')";


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

	function clsTeletipos_colores($conexion, $filadesde, $usuario, $buscar_id){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_id = $buscar_id;
	}
}

?>