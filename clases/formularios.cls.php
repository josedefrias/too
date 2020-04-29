<?php

class clsFormularios{

	var $Usuario;

	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Usuario = $this ->Usuario;

		$resultado =$conexion->query("select f.modulo, u.formulario, u.lineas_modificacion, u.lineas_nuevas
									  from hit_usuarios_formularios u, hit_formularios f 
									  where u.formulario = f.formulario and f.permitido_cambio = 'S' 
											and u.USUARIO = '".$Usuario."' order by f.id");
		$numero_filas = $resultado->num_rows;
		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		$formularios = array();
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($formularios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $formularios;											
	}



	function Modificar($formulario, $lineas_modificacion, $lineas_nuevas){

		$conexion = $this ->Conexion;
		//Consulta
		$Usuario = $this ->Usuario;

		$query = "UPDATE hit_usuarios_formularios SET ";
		$query .= " LINEAS_MODIFICACION = '".$lineas_modificacion."'";
		$query .= ", LINEAS_NUEVAS = '".$lineas_nuevas."'";
		$query .= " WHERE USUARIO = '".$Usuario."'";
		$query .= " AND FORMULARIO = '".$formulario."'";

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
	function clsFormularios($conexion, $usuario){
		$this->Conexion = $conexion;
		$this->Usuario = $usuario;
	}
}

?>