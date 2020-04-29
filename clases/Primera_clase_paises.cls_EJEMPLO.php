<?php

include 'funciones_php/conexiones.php';

class clsPaises{

	//var $Ordenada = FALSE;
	var $Ordenada;
	var $Orden1;
	var $Orden2;



	function Cargar(){

		//Conexion
		$conexion = conexion_hit();


		//Consulta
		$ordenadopor1 = $this ->Orden1;
		$ordenadopor2 = $this ->Orden2;

		if($this ->Ordenada == TRUE){
			$resultado =$conexion->query("SELECT codigo,nombre,continente,idioma,divisa,union_europea,prefijo,visado FROM hit_paises ORDER BY $ordenadopor1, $ordenadopor2");
		}else{
			$resultado =$conexion->query('SELECT codigo,nombre FROM hit_paises');
		}

		if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}/*else{
			echo('Consulta realizada.');
		}*/
		//Mostrar todos los registros
		//$paises = sprintf('<br/>');
		/*while($fila = $resultado->fetch_row()){
			
			$paises .= sprintf('<tr>');
			$paises .= sprintf("<th>%s</th><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td align = 'right'>%s</td><td>%s</td>",
							  $numeracion, $fila[0], $fila[1], $fila[2], $fila[3], $fila[4], $fila[5], $fila[6], $fila[7], $fila[8]);
			$paises .= sprintf('</tr>');
			$numeracion++;
		}*/


		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE USUARIO = 'PRUEBA'");
		$Nfilas	 = $numero_filas->fetch_assoc();

		
		$paises = array();
		for ($num_fila = 0; $num_fila <= $Nfilas['LINEAS_MODIFICACION']-1; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($paises,$fila);
		}


		//Liberar Memoria usada por la consulta
		$resultado->close();

		//Desconexion
		//$conexion->close();
		$estacerrado = $conexion->close();
		/*if ($estacerrado == TRUE){
			echo('La conexion se ha cerrado correctamente.');
		}*/

		return $paises;											
	}

	function Cargar_combo_selector(){

		$conexion = $conexion = conexion_hit();
		$resultadoc =$conexion->query('SELECT * FROM hit_paises');
		$numero_filas = $resultadoc->num_rows;

		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}
		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_pantallas)
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE USUARIO = 'PRUEBA'");
		$Nfilas	 = $num_filas->fetch_assoc();
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

		$resultadoc->close();
		$conexion->close();
		return $combo_select;											
	}

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsPaises($pOrdenada, $pOrden1, $pOrden2){
		$this->Ordenada = $pOrdenada;
		$this->Orden1 = $pOrden1;
		$this->Orden2 = $pOrden2;
	}
}

?>