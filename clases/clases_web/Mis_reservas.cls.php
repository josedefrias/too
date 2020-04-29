<?php

	header('Content-type: text/html; charset=utf-8');

class clsMis_reservas{

	//ESPECIFICO: Variables especificas de esta pantallaÑ
	var	$oficina;
	var	$localizador;

	//--------------------------------------------------

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----
//------------------------------------------------------------------

	function Cargar_datos_hoteles(){

		$conexion = $this ->Conexion;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$localizador = $this ->localizador;

		//echo($localizador)
		$resultado =$conexion->query("select
										concat(alo.DIRECCION,'  ',alo.LOCALIDAD,'-',alo.CODIGO_POSTAL,' ',ciu.NOMBRE) dh_direccion,
										alo.TELEFONO dh_telefono,
										alo.URL dh_url
			
									from
										hit_reservas_alojamientos pal,
										hit_alojamientos alo,
										hit_ciudades ciu
									where
										pal.ALOJAMIENTO= alo.ID
										and alo.CIUDAD = ciu.CODIGO
										and pal.LOCALIZADOR = '".$localizador."' order by pal.FECHA_ENTRADA");


		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$hoteles = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($hoteles,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $hoteles;											
	}



	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)

	function clsMis_reservas($conexion,$localizador){
		$this->Conexion = $conexion;
		$this->Localizador = $localizador;

	}
}

?>