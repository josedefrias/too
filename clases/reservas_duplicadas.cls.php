<?php

require 'clases_web/Reservas_fin.cls.php';

class clsReservas_duplicadas{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla

	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;
		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;
	
		$resultado =$conexion->query("select r1.LOCALIZADOR localizador1, rp1.APELLIDO apellido1, rp1.NOMBRE nombre1, r1.FOLLETO folleto1, r1.CUADRO cuadro1, 
											 r1.PAQUETE paquete1, DATE_FORMAT(r1.FECHA_SALIDA, '%d-%m-%Y') AS salida1, r1.SITUACION situacion1,
											 r2.LOCALIZADOR localizador2, rp2.APELLIDO apellido2, rp2.NOMBRE nombre2, r2.FOLLETO folleto2, r2.CUADRO cuadro2, 
											 r2.PAQUETE paquete2, DATE_FORMAT(r2.FECHA_SALIDA, '%d-%m-%Y') AS salida2, r2.SITUACION situacion2
											from
												hit_reservas r1, hit_reservas_pasajeros rp1,
												hit_reservas r2, hit_reservas_pasajeros rp2
											where
												r1.LOCALIZADOR = rp1.LOCALIZADOR
												and r2.LOCALIZADOR = rp2.LOCALIZADOR
												and r1.LOCALIZADOR <> r2.LOCALIZADOR
												and rp1.APELLIDO = rp2.APELLIDO 
												and rp1.NOMBRE = rp2.NOMBRE
												and rp1.APELLIDO not like '%PRUEBA%'
												and rp1.NOMBRE not like '%PRUEBA%'
												and rp1.APELLIDO not like '%APELLIDO%'
												and rp1.NOMBRE not like '%NOMBRE%'	
												AND r1.SITUACION <> 'A'
												AND r2.SITUACION <> 'A'
												and r1.CONTROL_DUPLICADOS = 'S'
												and r2.CONTROL_DUPLICADOS = 'S'
												and r1.FECHA_SALIDA between curdate() and DATE_ADD(curdate(),INTERVAL 180 DAY)
												and r2.FECHA_SALIDA between curdate() and DATE_ADD(curdate(),INTERVAL 180 DAY)
												and r1.localizador = (select min(r1.localizador) from
																			hit_reservas r1, hit_reservas_pasajeros rp1,
																			hit_reservas r2, hit_reservas_pasajeros rp2
																		where
																			r1.LOCALIZADOR = rp1.LOCALIZADOR
																			and r2.LOCALIZADOR = rp2.LOCALIZADOR
																			and r1.LOCALIZADOR <> r2.LOCALIZADOR
																			and rp1.APELLIDO = rp2.APELLIDO 
																			and rp1.NOMBRE = rp2.NOMBRE
																			and rp1.APELLIDO not like '%PRUEBA%'
																			and rp1.NOMBRE not like '%PRUEBA%'
																			and rp1.APELLIDO not like '%APELLIDO%'
																			and rp1.NOMBRE not like '%NOMBRE%'	
																			AND r1.SITUACION <> 'A'
																			AND r2.SITUACION <> 'A'
																			and r1.CONTROL_DUPLICADOS = 'S'
																			and r2.CONTROL_DUPLICADOS = 'S'
																			and r1.FECHA_SALIDA between curdate() and DATE_ADD(curdate(),INTERVAL 180 DAY)
																			and r2.FECHA_SALIDA between curdate() and DATE_ADD(curdate(),INTERVAL 180 DAY))
												
												
												ORDER BY r1.LOCALIZADOR");
	
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
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_DUPLICADAS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$reservas_duplicadas = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['localizador1'] == ''){
				break;
			}
			array_push($reservas_duplicadas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $reservas_duplicadas;											
	}

	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		$resultadoc =$conexion->query("select *
										from
												hit_reservas r1, hit_reservas_pasajeros rp1,
												hit_reservas r2, hit_reservas_pasajeros rp2
											where
												r1.LOCALIZADOR = rp1.LOCALIZADOR
												and r2.LOCALIZADOR = rp2.LOCALIZADOR
												and r1.LOCALIZADOR <> r2.LOCALIZADOR
												and rp1.APELLIDO = rp2.APELLIDO 
												and rp1.NOMBRE = rp2.NOMBRE
												and rp1.APELLIDO not like '%PRUEBA%'
												and rp1.NOMBRE not like '%PRUEBA%'
												and rp1.APELLIDO not like '%APELLIDO%'
												and rp1.NOMBRE not like '%NOMBRE%'	
												AND r1.SITUACION <> 'A'
												AND r2.SITUACION <> 'A'
												and r1.CONTROL_DUPLICADOS = 'S'
												and r2.CONTROL_DUPLICADOS = 'S'
												and r1.FECHA_SALIDA between curdate() and DATE_ADD(curdate(),INTERVAL 180 DAY)
												and r2.FECHA_SALIDA between curdate() and DATE_ADD(curdate(),INTERVAL 180 DAY)");
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_DUPLICADAS' AND USUARIO = '".$Usuario."'");
			$Nfilas	 = $num_filas->fetch_assoc();																	  //------
			$cada = $Nfilas['LINEAS_MODIFICACION'] + 1;
			$combo_select = array();
			for ($num_fila = 1; $num_fila <= $numero_filas; $num_fila++) {
				
				if($num_fila == $cada){
					$combo_select[$num_fila] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $cada - 1);
					$cada = $cada + $Nfilas['LINEAS_MODIFICACION'];
				}
				if($num_fila == $numero_filas){
					$combo_select[$num_fila] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $numero_filas);
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
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_reservas');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_DUPLICADAS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($localizador){

		$usuario = $this->Usuario;
	
		$conexion = $this ->Conexion;
		$query = "UPDATE hit_reservas SET ";
		$query .= " CONTROL_DUPLICADOS = 'N'";
		$query .= " WHERE LOCALIZADOR = '".$localizador."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsReservas_duplicadas($conexion, $filadesde, $usuario){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
	}
}

?>