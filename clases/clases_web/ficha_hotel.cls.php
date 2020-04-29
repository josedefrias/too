<?php

	header('Content-type: text/html; charset=utf-8');

class clsFicha_hotel{

//------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS DE GESTION-----Ñ
//------------------------------------------------------------------

	function Cargar_alojamiento_general(){

		$conexion = $this ->Conexion;
		$id = $this ->Id;

		$resultado =$conexion->query("select a.ID id,
												t.NOMBRE tipo, 
												a.NOMBRE nombre,
												c.NOMBRE categoria,
												a.LOCALIDAD localidad,
												a.DESCRIPCION_COMPLETA descripcion,
												a.LATITUD latitud,
												a.LONGITUD longitud,
												a.DIRECCION direccion,
												a.CODIGO_POSTAL c_postal,
												p.NOMBRE provincia,
												a.TELEFONO telefono,
												a.FAX fax,
												a.EMAIL email,
												a.UBICACION ubicacion,
												a.COMO_LLEGAR como_llegar,
												a.DESCRIPCION_HABITACIONES descripcion_habitaciones,
												a.DESCRIPCION_ACTIVIDADES descripcion_actividades,
												a.DESCRIPCION_RESTAURANTES descripcion_restaurantes,
												a.DESCRIPCION_BELLEZA descripcion_belleza
										from hit_alojamientos a,
												hit_provincias p,
												hit_tipos_alojamiento t,
												hit_categorias c
										where
											a.PROVINCIA = p.CODIGO
											and a.TIPO = t.CODIGO
											and a.CATEGORIA = c.CODIGO
											and a.TIPO = t.CODIGO
											and a.ID = '".$id."'");

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz 	

		$alojamiento = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($alojamiento,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $alojamiento;											
	}

	function Cargar_imagenes_general(){
		$conexion = $this ->Conexion;
		$id = $this ->Id;
		$resultado =$conexion->query("select
										case tipo
											when 'P' then concat('Principal',' - ',numero)
											when 'R' then concat('Recepcion',' - ',numero)
											when 'I' then concat('Instalaciones',' - ',numero)
											when 'S' then concat('Servicios',' - ',numero)
											when 'E' then concat('Entorno',' - ',numero)
											when 'G' then concat('Gastronomia',' - ',numero)
											when 'C' then concat('Piscina',' - ',numero)
											else 'General'
										end tipo,
										concat(alojamiento,'_',tipo,'_',numero) imagen
									from hit_alojamientos_imagenes
									where alojamiento = '".$id."'");
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}

		$imagenes = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($imagenes,$fila);
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		return $imagenes;											
	}

	function Cargar_habitaciones(){
		$conexion = $this ->Conexion;
		$id = $this ->Id;
		$resultado =$conexion->query("select
											a.NOMBRE nombre,
											a.DESCRIPCION descripcion,
											a.DESCRIPCION_CORTA descripcion_corta,
											concat(a.ID_ALOJAMIENTO,'_',a.CODIGO) imagen
										from hit_alojamientos_habitaciones a
										where
											a.ID_ALOJAMIENTO = '".$id."'");
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}

		$habitaciones = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($habitaciones,$fila);
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		return $habitaciones;											
	}


	function Cargar_servicios(){
		$conexion = $this ->Conexion;
		$id = $this ->Id;
		$resultado =$conexion->query("select
											CASE ACCESO_INTERNET WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END ACCESO_INTERNET,
											CASE AIRE_ACONDICIONADO_COMUNES WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END AIRE_ACONDICIONADO_COMUNES,
											CASE APARCAMIENTO WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END APARCAMIENTO,
											CASE AREA_JUEGOS WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END AREA_JUEGOS,
											CASE ASCENSORES WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END ASCENSORES,
											CASE BARES WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END BARES,
											CASE CAMBIO_MONEDA WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END CAMBIO_MONEDA,
											CASE COBERTURA_MOVILES WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END COBERTURA_MOVILES,
											CASE MINICLUB WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END MINICLUB,
											PISCINAS_NUMERO,
											CASE PELUQUERIA WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END PELUQUERIA,
											CASE PISCINA_AIRE_LIBRE WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END PISCINA_AIRE_LIBRE,
											CASE PISCINA_AGUA_DULCE WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END PISCINA_AGUA_DULCE,
											CASE PISCINA_NINOS WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END PISCINA_NINOS,
											CASE RESTAURANTES WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END RESTAURANTES,
											CASE RESTAURANTE_CLIMATIZADO WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END RESTAURANTE_CLIMATIZADO,
											CASE SALA_CONFERENCIAS WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END SALA_CONFERENCIAS,
											CASE SERVICIO_FACTURACION_24 WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END SERVICIO_FACTURACION_24,
											CASE SERVICIO_RECEPCION_24 WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END SERVICIO_RECEPCION_24,
											CASE SOMBRILLAS WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END SOMBRILLAS,	
											CASE TERRAZA_SOLARIUM WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END TERRAZA_SOLARIUM,
											CASE TIENDAS WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END TIENDAS,
											CASE TRONAS WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END TRONAS,
											CASE TUMBONAS WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END TUMBONAS,
											CASE VESTIBULO_RECEPCION WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END VESTIBULO_RECEPCION,
											CASE AIRE_ACONDICIONADO_CENTRAL WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END AIRE_ACONDICIONADO_CENTRAL,
											CASE BALCON WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END BALCON,
											CASE BANO WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END BANO,
											CASE CAJA_SEGURIDAD WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END CAJA_SEGURIDAD,
											CASE MINIBAR WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END MINIBAR,
											CASE SECADOR WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END SECADOR,
											CASE TELEFONO_LINEA_DIRECTA WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END TELEFONO_LINEA_DIRECTA,
											CASE TV_SATELITE_CABLE WHEN 'S' THEN 'Si' WHEN 'N' THEN 'No' WHEN 'C' THEN 'Con Cargo' END TV_SATELITE_CABLE
										from hit_alojamientos_servicios 
										where
											ID_ALOJAMIENTO = '".$id."'");
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}

		$servicios = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($servicios,$fila);
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		return $servicios;											
	}



	function Cargar_actividades(){
		$conexion = $this ->Conexion;
		$id = $this ->Id;
		$resultado =$conexion->query("select
											a.NOMBRE nombre,
											a.DESCRIPCION descripcion,
											a.DESCRIPCION_CORTA descripcion_corta,
											concat(a.ID_ALOJAMIENTO,'_',a.NOMBRE) imagen
										from hit_alojamientos_actividades a
										where
											a.ID_ALOJAMIENTO = '".$id."'");
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}

		$actividades = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($actividades,$fila);
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		return $actividades;											
	}


	function Cargar_restaurantes(){
		$conexion = $this ->Conexion;
		$id = $this ->Id;
		$resultado =$conexion->query("select
											a.NOMBRE nombre,
											a.DESCRIPCION descripcion,
											a.DESCRIPCION_CORTA descripcion_corta,
											concat(a.ID_ALOJAMIENTO,'_',a.NOMBRE) imagen
										from hit_alojamientos_restaurantes a
										where
											a.ID_ALOJAMIENTO = '".$id."'");
		if ($resultado == FALSE){
			echo('Error en la consulta: '.$resultado);
			//$resultado->close();
			//$conexion->close();
			//exit;
		}

		$restaurantes = array();
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			array_push($restaurantes,$fila);
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		return $restaurantes;											
	}


	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)

	function clsFicha_hotel($conexion,$id){
		$this->Conexion = $conexion;
		$this->Id = $id;
	}
}

?>