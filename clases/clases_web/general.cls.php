<?php

	header('Content-type: text/html; charset=utf-8');

class clsGeneral{

	//Ñ
	var $Usuario;

	function Cargar_combo_SiNo(){

		$combo_sino = array();

		$combo_sino[0] = array ( "valor" => '', "mostrar" => '');
		$combo_sino[2] = array ( "valor" => 'N', "mostrar" => 'No');
		$combo_sino[1] = array ( "valor" => 'S', "mostrar" => 'Si');
		return $combo_sino;											
	}

	function Cargar_combo_Categorias(){

		$conexion = $this ->Conexion;

		$categorias =$conexion->query("SELECT codigo,nombre FROM hit_categorias ORDER BY NOMBRE");
		$numero_filas = $categorias->num_rows;

		if ($categorias == FALSE){
			echo('Error en la consulta');
			$categorias->close();
			$conexion->close();
			exit;
		}

		$combo_categorias= array();
		$combo_categorias[-1] = array ( "codigo" => null, "nombre" => 'Todas las Categorias');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$categorias->data_seek($num_fila);
			$fila = $categorias->fetch_assoc();
			array_push($combo_categorias,$fila);
		}

		//Liberar Memoria usada por la consulta
		$categorias->close();

		return $combo_categorias;											
	}

	function Cargar_combo_Origenes(){

		$combo_origenes = array();

		$combo_origenes[0] = array ( "valor" => '', "mostrar" => 'Seleccione Origen');
		$combo_origenes[1] = array ( "valor" => 'OVD', "mostrar" => 'Asturias');
		$combo_origenes[2] = array ( "valor" => 'SCQ', "mostrar" => 'Santiago');
		$combo_origenes[3] = array ( "valor" => 'ACE', "mostrar" => 'Lanzarote');
		$combo_origenes[4] = array ( "valor" => 'TFS', "mostrar" => 'Tenerife Sur');
		return $combo_origenes;											
	}

	function Cargar_combo_Destinos(){

		$conexion = $this ->Conexion;

		$destinos =$conexion->query("SELECT codigo,nombre FROM hit_destinos WHERE codigo <> 'TODOS' ORDER BY NOMBRE");
		$numero_filas = $destinos->num_rows;

		if ($destinos == FALSE){
			echo('Error en la consulta');
			$destinos->close();
			$conexion->close();
			exit;
		}

		$combo_destinos= array();
		$combo_destinos[-1] = array ( "codigo" => null, "nombre" => 'Seleccione Destino');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$destinos->data_seek($num_fila);
			$fila = $destinos->fetch_assoc();
			array_push($combo_destinos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$destinos->close();

		return $combo_destinos;											
	}

	function Cargar_combo_Productos(){

		$conexion = $this ->Conexion;

		$productos =$conexion->query("SELECT codigo,nombre FROM hit_productos where visible = 'S' and codigo not in ('OVA','OSV') ORDER BY NOMBRE");
		$numero_filas = $productos->num_rows;

		if ($productos == FALSE){
			echo('Error en la consulta');
			$productos->close();
			$conexion->close();
			exit;
		}

		$combo_productos= array();
		$combo_productos[-1] = array ( "codigo" => null, "nombre" => 'Seleccione Producto');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$productos->data_seek($num_fila);
			$fila = $productos->fetch_assoc();
			array_push($combo_productos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$productos->close();

		return $combo_productos;											
	}

	function Cargar_combo_Noches(){

		$combo_noches = array();

		$combo_noches[0] = array ( "valor" => '', "mostrar" => 'Seleccione Noches');
		$combo_noches[2] = array ( "valor" => '7', "mostrar" => '7 noches');
		$combo_noches[1] = array ( "valor" => '14', "mostrar" => '14 noches');
		return $combo_noches;											
	}


	function Cargar_combo_Regimenes(){

		$combo_regimenes = array();
		$combo_regimenes[0] = array ( "valor" => '', "mostrar" => 'Todos los Regimenes');
		$combo_regimenes[2] = array ( "valor" => 'SA', "mostrar" => 'Solo Alojamiento');
		$combo_regimenes[3] = array ( "valor" => 'AD', "mostrar" => 'Alojamiento y Desayuno');
		$combo_regimenes[4] = array ( "valor" => 'MP', "mostrar" => 'Media Pension');
		$combo_regimenes[5] = array ( "valor" => 'PC', "mostrar" => 'Pension Completa');
		$combo_regimenes[6] = array ( "valor" => 'TI', "mostrar" => 'Todo Incluido');

		return $combo_regimenes;											
	}

	//Esto es provisional hasta que se carguen los combos segun la seleccion que vaya haciendo el usuario
	function Cargar_combo_Fechas(){

		$combo_fecha = array();

		$combo_fecha[0] = array ( "valor" => '', "mostrar" => 'Seleccione Fecha');
		$combo_fecha[1] = array ( "valor" => '2014-06-23', "mostrar" => '23-06-2014');
		$combo_fecha[2] = array ( "valor" => '2014-06-30', "mostrar" => '30-06-2014');
		$combo_fecha[3] = array ( "valor" => '2014-07-07', "mostrar" => '07-07-2014');
		$combo_fecha[4] = array ( "valor" => '2014-07-14', "mostrar" => '14-07-2014');
		$combo_fecha[5] = array ( "valor" => '2014-07-21', "mostrar" => '21-07-2014');
		$combo_fecha[6] = array ( "valor" => '2014-07-28', "mostrar" => '28-07-2014');
		$combo_fecha[7] = array ( "valor" => '2014-08-04', "mostrar" => '04-08-2014');
		$combo_fecha[8] = array ( "valor" => '2014-08-11', "mostrar" => '11-08-2014');
		$combo_fecha[9] = array ( "valor" => '2014-08-18', "mostrar" => '18-08-2014');
		$combo_fecha[10] = array ( "valor" => '2014-08-25', "mostrar" => '25-08-2014');
		$combo_fecha[11] = array ( "valor" => '2014-09-01', "mostrar" => '01-09-2014');
		$combo_fecha[12] = array ( "valor" => '2014-09-08', "mostrar" => '08-09-2014');
		$combo_fecha[13] = array ( "valor" => '2014-09-15', "mostrar" => '15-09-2014');
		$combo_fecha[14] = array ( "valor" => '2014-09-22', "mostrar" => '22-09-2014');
		$combo_fecha[15] = array ( "valor" => '2014-09-29', "mostrar" => '29-09-2014');
		$combo_fecha[16] = array ( "valor" => '2014-10-07', "mostrar" => '07-10-2014');
		$combo_fecha[17] = array ( "valor" => '2014-10-14', "mostrar" => '14-10-2014');
		$combo_fecha[18] = array ( "valor" => '2014-10-21', "mostrar" => '21-10-2014');
		return $combo_fecha;											
	}

	//Esto es provisional hasta que se carguen los combos segun la seleccion que vaya haciendo el usuario
	function Cargar_combo_Fechas_Regreso(){

		$combo_fecha = array();

		$combo_fecha[0] = array ( "valor" => '', "mostrar" => 'Seleccione Regreso');
		$combo_fecha[1] = array ( "valor" => '2014-06-23', "mostrar" => '23-06-2014');
		$combo_fecha[2] = array ( "valor" => '2014-06-30', "mostrar" => '30-06-2014');
		$combo_fecha[3] = array ( "valor" => '2014-07-07', "mostrar" => '07-07-2014');
		$combo_fecha[4] = array ( "valor" => '2014-07-14', "mostrar" => '14-07-2014');
		$combo_fecha[5] = array ( "valor" => '2014-07-21', "mostrar" => '21-07-2014');
		$combo_fecha[6] = array ( "valor" => '2014-07-28', "mostrar" => '28-07-2014');
		$combo_fecha[7] = array ( "valor" => '2014-08-04', "mostrar" => '04-08-2014');
		$combo_fecha[8] = array ( "valor" => '2014-08-11', "mostrar" => '11-08-2014');
		$combo_fecha[9] = array ( "valor" => '2014-08-18', "mostrar" => '18-08-2014');
		$combo_fecha[10] = array ( "valor" => '2014-08-25', "mostrar" => '25-08-2014');
		$combo_fecha[11] = array ( "valor" => '2014-09-01', "mostrar" => '01-09-2014');
		$combo_fecha[12] = array ( "valor" => '2014-09-08', "mostrar" => '08-09-2014');
		$combo_fecha[13] = array ( "valor" => '2014-09-15', "mostrar" => '15-09-2014');
		$combo_fecha[14] = array ( "valor" => '2014-09-22', "mostrar" => '22-09-2014');
		$combo_fecha[15] = array ( "valor" => '2014-09-29', "mostrar" => '29-09-2014');
		$combo_fecha[16] = array ( "valor" => '2014-10-07', "mostrar" => '07-10-2014');
		$combo_fecha[17] = array ( "valor" => '2014-10-14', "mostrar" => '14-10-2014');
		$combo_fecha[18] = array ( "valor" => '2014-10-21', "mostrar" => '21-10-2014');
		return $combo_fecha;											
	}



	function Cargar_Combo_Cantidad_Habitaciones(){

		$Cargar_Combo_Cantidad_Habitaciones = array();
		//$Cargar_Combo_Cantidad_Habitaciones[0] = array ( "valor" => '0', "mostrar" => 'Numero de habitaciones');
		$Cargar_Combo_Cantidad_Habitaciones[1] = array ( "valor" => '1', "mostrar" => '1 Habitacion');
		$Cargar_Combo_Cantidad_Habitaciones[2] = array ( "valor" => '2', "mostrar" => '2 Habitaciones');
		$Cargar_Combo_Cantidad_Habitaciones[3] = array ( "valor" => '3', "mostrar" => '3 Habitaciones');
		$Cargar_Combo_Cantidad_Habitaciones[4] = array ( "valor" => '4', "mostrar" => '4 Habitaciones');
		return $Cargar_Combo_Cantidad_Habitaciones;											
	}

	function Cargar_Combo_Habitaciones_Adultos(){

		$combo_habitaciones_adultos = array();
		$combo_habitaciones_adultos[0] = array ( "valor" => '2', "mostrar" => '2 Adultos');
		$combo_habitaciones_adultos[1] = array ( "valor" => '1', "mostrar" => '1 Adulto');
		$combo_habitaciones_adultos[3] = array ( "valor" => '3', "mostrar" => '3 Adultos');
		$combo_habitaciones_adultos[4] = array ( "valor" => '4', "mostrar" => '4 Adultos');
		return $combo_habitaciones_adultos;											
	}

	function Cargar_Combo_Habitaciones_Ninos(){

		$combo_habitaciones_ninos = array();
		$combo_habitaciones_ninos[0] = array ( "valor" => '0', "mostrar" => '0 Niños');
		$combo_habitaciones_ninos[1] = array ( "valor" => '1', "mostrar" => '1 Niño');
		$combo_habitaciones_ninos[2] = array ( "valor" => '2', "mostrar" => '2 Niños');
		return $combo_habitaciones_ninos;											
	}

	function Cargar_Combo_Habitaciones_Bebes(){

		$Cargar_Combo_habitaciones_bebes = array();
		$Cargar_Combo_habitaciones_bebes[0] = array ( "valor" => '0', "mostrar" => '0 Bebes');
		$Cargar_Combo_habitaciones_bebes[1] = array ( "valor" => '1', "mostrar" => '1 Bebe');
		$Cargar_Combo_habitaciones_bebes[2] = array ( "valor" => '2', "mostrar" => '2 Bebes');
		return $Cargar_Combo_habitaciones_bebes;											
	}

	function Cargar_Combo_Habitaciones_Novios(){

		$Cargar_Combo_habitaciones_novios = array();
		$Cargar_Combo_habitaciones_novios[0] = array ( "valor" => '0', "mostrar" => 'Novios No');
		$Cargar_Combo_habitaciones_novios[2] = array ( "valor" => '2', "mostrar" => 'Novios Si');
		return $Cargar_Combo_habitaciones_novios;											
	}

	function Cargar_Combo_Habitaciones_Jubilados(){

		$Cargar_Combo_habitaciones_jubilados = array();
		$Cargar_Combo_habitaciones_jubilados[0] = array ( "valor" => '0', "mostrar" => '0 Jubilados');
		$Cargar_Combo_habitaciones_jubilados[1] = array ( "valor" => '1', "mostrar" => '1 Jubilado');
		$Cargar_Combo_habitaciones_jubilados[2] = array ( "valor" => '2', "mostrar" => '2 Jubilados');
		$Cargar_Combo_habitaciones_jubilados[3] = array ( "valor" => '3', "mostrar" => '3 Jubilados');
		$Cargar_Combo_habitaciones_jubilados[4] = array ( "valor" => '4', "mostrar" => '4 Jubilados');
		return $Cargar_Combo_habitaciones_jubilados;											
	}

	function Cargar_Combo_Solo_Vuelo_Adulos(){
		$Cargar_Combo_Solo_Vuelo_Adulos = array();
		$Cargar_Combo_Solo_Vuelo_Adulos[0] = array ( "valor" => '2', "mostrar" => '2 Adultos');
		$Cargar_Combo_Solo_Vuelo_Adulos[1] = array ( "valor" => '1', "mostrar" => '1 Adulto');
		$Cargar_Combo_Solo_Vuelo_Adulos[3] = array ( "valor" => '3', "mostrar" => '3 Adultos');
		$Cargar_Combo_Solo_Vuelo_Adulos[4] = array ( "valor" => '4', "mostrar" => '4 Adultos');
		$Cargar_Combo_Solo_Vuelo_Adulos[5] = array ( "valor" => '5', "mostrar" => '5 Adultos');
		$Cargar_Combo_Solo_Vuelo_Adulos[6] = array ( "valor" => '6', "mostrar" => '6 Adultos');
		$Cargar_Combo_Solo_Vuelo_Adulos[7] = array ( "valor" => '7', "mostrar" => '7 Adultos');
		$Cargar_Combo_Solo_Vuelo_Adulos[8] = array ( "valor" => '8', "mostrar" => '8 Adultos');
		$Cargar_Combo_Solo_Vuelo_Adulos[9] = array ( "valor" => '9', "mostrar" => '9 Adultos');
		return $Cargar_Combo_Solo_Vuelo_Adulos;											
	}

	function Cargar_Combo_Solo_Vuelo_Ninos(){
		$Cargar_Combo_Solo_Vuelo_Adulos = array();
		$Cargar_Combo_Solo_Vuelo_Adulos[0] = array ( "valor" => '0', "mostrar" => '0 Niños');
		$Cargar_Combo_Solo_Vuelo_Adulos[1] = array ( "valor" => '1', "mostrar" => '1 Niño');
		$Cargar_Combo_Solo_Vuelo_Adulos[2] = array ( "valor" => '2', "mostrar" => '2 Niños');
		$Cargar_Combo_Solo_Vuelo_Adulos[3] = array ( "valor" => '3', "mostrar" => '3 Niños');
		$Cargar_Combo_Solo_Vuelo_Adulos[4] = array ( "valor" => '4', "mostrar" => '4 Niños');

		return $Cargar_Combo_Solo_Vuelo_Adulos;											
	}

	function Cargar_Combo_Solo_Vuelo_Bebes(){
		$Cargar_Combo_Solo_Vuelo_Bebes = array();
		$Cargar_Combo_Solo_Vuelo_Bebes[0] = array ( "valor" => '0', "mostrar" => '0 Bebes');
		$Cargar_Combo_Solo_Vuelo_Bebes[1] = array ( "valor" => '1', "mostrar" => '1 Bebe');
		$Cargar_Combo_Solo_Vuelo_Bebes[2] = array ( "valor" => '2', "mostrar" => '2 Bebes');
		$Cargar_Combo_Solo_Vuelo_Bebes[3] = array ( "valor" => '3', "mostrar" => '3 Bebes');
		$Cargar_Combo_Solo_Vuelo_Bebes[4] = array ( "valor" => '4', "mostrar" => '4 Bebes');

		return $Cargar_Combo_Solo_Vuelo_Bebes;											
	}

	function Cargar_combo_Pasajeros_edad_ninos(){

		$Cargar_combo_Pasajeros_edad_ninos = array();

		$Cargar_combo_Pasajeros_edad_ninos[0] = array ( "valor" => '2', "mostrar" => '2 Años');
		$Cargar_combo_Pasajeros_edad_ninos[1] = array ( "valor" => '3', "mostrar" => '3 Años');
		$Cargar_combo_Pasajeros_edad_ninos[2] = array ( "valor" => '4', "mostrar" => '4 Años');
		$Cargar_combo_Pasajeros_edad_ninos[3] = array ( "valor" => '5', "mostrar" => '5 Años');
		$Cargar_combo_Pasajeros_edad_ninos[4] = array ( "valor" => '6', "mostrar" => '6 Años');
		$Cargar_combo_Pasajeros_edad_ninos[5] = array ( "valor" => '7', "mostrar" => '7 Años');
		$Cargar_combo_Pasajeros_edad_ninos[6] = array ( "valor" => '8', "mostrar" => '8 Años');
		$Cargar_combo_Pasajeros_edad_ninos[7] = array ( "valor" => '9', "mostrar" => '9 Años');
		$Cargar_combo_Pasajeros_edad_ninos[8] = array ( "valor" => '10', "mostrar" => '10 Años');
		$Cargar_combo_Pasajeros_edad_ninos[9] = array ( "valor" => '11', "mostrar" => '11 Años');
		$Cargar_combo_Pasajeros_edad_ninos[10] = array ( "valor" => '12', "mostrar" => '12 Años');
		$Cargar_combo_Pasajeros_edad_ninos[11] = array ( "valor" => '13', "mostrar" => '13 Años');
		$Cargar_combo_Pasajeros_edad_ninos[12] = array ( "valor" => '14', "mostrar" => '14 Años');
		$Cargar_combo_Pasajeros_edad_ninos[13] = array ( "valor" => '15', "mostrar" => '15 Años');
		$Cargar_combo_Pasajeros_edad_ninos[14] = array ( "valor" => '16', "mostrar" => '16 Años');


		return $Cargar_combo_Pasajeros_edad_ninos;											
	}

	function Cargar_combo_Pasajeros_sexo(){

		$combo_pasajeros_sexo = array();
		$combo_pasajeros_sexo[1] = array ( "valor" => '', "mostrar" => 'Titulo');
		$combo_pasajeros_sexo[2] = array ( "valor" => 'H', "mostrar" => 'Mr.');
		$combo_pasajeros_sexo[3] = array ( "valor" => 'M', "mostrar" => 'Ms.');
		return $combo_pasajeros_sexo;											
	}

	function Cargar_Combo_Tipo_Viaje_Grupos(){

		$Cargar_Combo_Tipo_Viaje_Grupos = array();
		$Cargar_Combo_Tipo_Viaje_Grupos[0] = array ( "valor" => 'PAQ', "mostrar" => 'Paquete');
		$Cargar_Combo_Tipo_Viaje_Grupos[1] = array ( "valor" => 'SVO', "mostrar" => 'Solo vuelo');
		$Cargar_Combo_Tipo_Viaje_Grupos[2] = array ( "valor" => 'SHO', "mostrar" => 'Solo hotel');
		$Cargar_Combo_Tipo_Viaje_Grupos[3] = array ( "valor" => 'ENT', "mostrar" => 'Entradas');
		return $Cargar_Combo_Tipo_Viaje_Grupos;											
	}

	function Cargar_Combo_Tipo_Grupo(){

		$Cargar_Combo_Tipo_Grupo = array();
		$Cargar_Combo_Tipo_Grupo[0] = array ( "valor" => 'ADU', "mostrar" => 'Adultos');
		$Cargar_Combo_Tipo_Grupo[1] = array ( "valor" => 'INC', "mostrar" => 'Incentivo');
		$Cargar_Combo_Tipo_Grupo[2] = array ( "valor" => 'TER', "mostrar" => 'Tercera Edad');
		$Cargar_Combo_Tipo_Grupo[3] = array ( "valor" => 'COL', "mostrar" => 'Colectivo');
		$Cargar_Combo_Tipo_Grupo[3] = array ( "valor" => 'DEP', "mostrar" => 'Deportivo');
		$Cargar_Combo_Tipo_Grupo[3] = array ( "valor" => 'EST', "mostrar" => 'Estudiantes');
		return $Cargar_Combo_Tipo_Grupo;											
	}

	function Cargar_Combo_Periodos_Gupos(){

		$Cargar_Combo_Periodos_Gupos = array();
		$Cargar_Combo_Periodos_Gupos[0] = array ( "valor" => 'S1', "mostrar" => 'Primera Semana');
		$Cargar_Combo_Periodos_Gupos[1] = array ( "valor" => 'S2', "mostrar" => 'Segunda Semana');
		$Cargar_Combo_Periodos_Gupos[2] = array ( "valor" => 'Q1', "mostrar" => 'Primera Quincena');
		$Cargar_Combo_Periodos_Gupos[3] = array ( "valor" => 'Q2', "mostrar" => 'Segunda Quincena');

		return $Cargar_Combo_Periodos_Gupos;											
	}

	function Cargar_Combo_Traslados_Gupos(){

		$Cargar_Combo_Traslados_Gupos = array();
		$Cargar_Combo_Traslados_Gupos[0] = array ( "valor" => 'C', "mostrar" => 'Colectivos');
		$Cargar_Combo_Traslados_Gupos[1] = array ( "valor" => 'P', "mostrar" => 'Privados');
		$Cargar_Combo_Traslados_Gupos[2] = array ( "valor" => 'N', "mostrar" => 'No');

		return $Cargar_Combo_Traslados_Gupos;											
	}

	function Cargar_Combo_Annos(){

		$Cargar_Combo_Annos = array();
		$Cargar_Combo_Annos[0] = array ( "valor" => '2014', "mostrar" => '2014');
		$Cargar_Combo_Annos[1] = array ( "valor" => '2015', "mostrar" => '2015');
		$Cargar_Combo_Annos[2] = array ( "valor" => '2016', "mostrar" => '2016');
		$Cargar_Combo_Annos[3] = array ( "valor" => '2017', "mostrar" => '2017');
		$Cargar_Combo_Annos[4] = array ( "valor" => '2018', "mostrar" => '2018');
		$Cargar_Combo_Annos[5] = array ( "valor" => '2019', "mostrar" => '2019');
		$Cargar_Combo_Annos[6] = array ( "valor" => '2020', "mostrar" => '2020');

		return $Cargar_Combo_Annos;											
	}

	function Cargar_Combo_meses(){

		$Cargar_Combo_meses = array();
		$Cargar_Combo_meses[0] = array ( "valor" => '1', "mostrar" => 'Enero');
		$Cargar_Combo_meses[1] = array ( "valor" => '2', "mostrar" => 'Febrero');
		$Cargar_Combo_meses[2] = array ( "valor" => '3', "mostrar" => 'Marzo');
		$Cargar_Combo_meses[3] = array ( "valor" => '4', "mostrar" => 'Abril');
		$Cargar_Combo_meses[4] = array ( "valor" => '5', "mostrar" => 'Mayo');
		$Cargar_Combo_meses[5] = array ( "valor" => '6', "mostrar" => 'Junio');
		$Cargar_Combo_meses[6] = array ( "valor" => '7', "mostrar" => 'Julio');
		$Cargar_Combo_meses[7] = array ( "valor" => '8', "mostrar" => 'Agosto');
		$Cargar_Combo_meses[8] = array ( "valor" => '9', "mostrar" => 'Septiembre');
		$Cargar_Combo_meses[9] = array ( "valor" => '10', "mostrar" => 'Octubre');
		$Cargar_Combo_meses[10] = array ( "valor" => '11', "mostrar" => 'Noviembre');
		$Cargar_Combo_meses[11] = array ( "valor" => '12', "mostrar" => 'Diciembre');

		return $Cargar_Combo_meses;											
	}

	function Cargar_Comboorigen_grupos(){

		$Cargar_Comboorigen_grupos = array();
		$Cargar_Comboorigen_grupos[0] = array ( "valor" => 'SCO', "mostrar" => 'Santiago de Compostela');
		$Cargar_Comboorigen_grupos[1] = array ( "valor" => 'OVD', "mostrar" => 'Asturia');
		$Cargar_Comboorigen_grupos[2] = array ( "valor" => 'AMB', "mostrar" => 'Ambos');

		return $Cargar_Comboorigen_grupos;											
	}

	function Cargar_Combodestino_grupos(){

		$Cargar_Combodestino_grupos = array();
		$Cargar_Combodestino_grupos[0] = array ( "valor" => 'TFS', "mostrar" => 'tenerife');
		$Cargar_Combodestino_grupos[1] = array ( "valor" => 'ACE', "mostrar" => 'Lanzarote');
		$Cargar_Combodestino_grupos[2] = array ( "valor" => 'AMB', "mostrar" => 'Ambos');

		return $Cargar_Combodestino_grupos;											
	}

	function Cargar_combo_grupos_Agencia($clave_oficina){

		$conexion = $this ->Conexion;

		$gupos =$conexion->query("select id codigo, concat('Recuperar grupo: ', nombre) nombre from hit_grupos 
										where clave_oficina = '".$clave_oficina."'
										and fecha_salida >= curdate()
										order by nombre");
		$numero_filas = $gupos->num_rows;

		if ($gupos == FALSE){
			echo('Error en la consulta');
			$gupos->close();
			$conexion->close();
			exit;
		}

		$combo_gupos= array();
		$combo_gupos[-1] = array ( "codigo" => null, "nombre" => 'Para solicitar grupo rellene todos los campos y pulse enviar');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$gupos->data_seek($num_fila);
			$fila = $gupos->fetch_assoc();
			array_push($combo_gupos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$gupos->close();

		return $combo_gupos;											
	}

	function Cargar_Combo_asunto_mail_contacto(){

		$Cargar_Combo_asunto_mail_contacto = array();
		$Cargar_Combo_asunto_mail_contacto[0] = array ( "valor" => '', "mostrar" => 'SELECCIONE EL ASUNTO');
		$Cargar_Combo_asunto_mail_contacto[1] = array ( "valor" => '1', "mostrar" => 'ADMINISTRACION');
		$Cargar_Combo_asunto_mail_contacto[2] = array ( "valor" => '2', "mostrar" => 'COMERCIAL');
		$Cargar_Combo_asunto_mail_contacto[3] = array ( "valor" => '3', "mostrar" => 'GENERAL');
		$Cargar_Combo_asunto_mail_contacto[4] = array ( "valor" => '4', "mostrar" => 'RESERVAS');
		$Cargar_Combo_asunto_mail_contacto[5] = array ( "valor" => '5', "mostrar" => 'TECNICO');


		return $Cargar_Combo_asunto_mail_contacto;											
	}

	function Cargar_Orden_reservas(){

		$Cargar_Orden_reservas = array();
		$Cargar_Orden_reservas[0] = array ( "valor" => 'L', "mostrar" => 'Por Localizador');
		$Cargar_Orden_reservas[1] = array ( "valor" => 'S', "mostrar" => 'Por Fecha de Salida');
		$Cargar_Orden_reservas[2] = array ( "valor" => 'P', "mostrar" => 'Por nombre del Primer Pasajero');
		//$Cargar_Orden_reservas[3] = array ( "valor" => 'H', "mostrar" => 'Por Primer Hotel');
		//$Cargar_Orden_reservas[4] = array ( "valor" => 'R', "mostrar" => 'Por Fecha de Reserva');


		return $Cargar_Orden_reservas;											
	}

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsGeneral($conexion){
		$this->Conexion = $conexion;
	}


}

?>