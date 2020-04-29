<?php

class clsGeneral{

	var $Usuario;

	function Cargar_combo_SiNo(){
		$maxfila = 1;
		$codigosi= 'S';
		$mostrarsi = 'Si';
		$codigono= 'N';
		$mostrarno = 'No';
		$combo_sino = array();
		/*for ($num_fila = 0; $num_fila <= $maxfila; $num_fila++) {
			if($num_fila == 0){
				$combo_sino[$num_fila] = array ("valor" => $codigosi, "mostrar" => $mostrarsi);
			}else{
				$combo_sino[$num_fila] = array ("valor" => $codigono, "mostrar" => $mostrarno);
			}
		}*/
		$combo_sino[0] = array ( "valor" => '', "mostrar" => '');
		$combo_sino[2] = array ( "valor" => 'N', "mostrar" => 'No');
		$combo_sino[1] = array ( "valor" => 'S', "mostrar" => 'Si');
		return $combo_sino;											
	}

	function Cargar_combo_SiNo_No(){
		$maxfila = 1;
		$codigosi= 'S';
		$mostrarsi = 'Si';
		$codigono= 'N';
		$mostrarno = 'No';
		$combo_sino = array();
		/*for ($num_fila = 0; $num_fila <= $maxfila; $num_fila++) {
			if($num_fila == 0){
				$combo_sino[$num_fila] = array ("valor" => $codigosi, "mostrar" => $mostrarsi);
			}else{
				$combo_sino[$num_fila] = array ("valor" => $codigono, "mostrar" => $mostrarno);
			}
		}*/
		$combo_sino[0] = array ( "valor" => 'N', "mostrar" => 'No');
		$combo_sino[1] = array ( "valor" => 'S', "mostrar" => 'Si');
		return $combo_sino;											
	}

	function Cargar_combo_SiNo_Si(){
		$combo_sino = array();
		$combo_sino[0] = array ( "valor" => 'S', "mostrar" => 'Si');
		$combo_sino[1] = array ( "valor" => 'N', "mostrar" => 'No');
		return $combo_sino;											
	}	
	
	function Cargar_combo_SiNo_alojamientos_servicios(){
		$maxfila = 1;
		$codigosi= 'S';
		$mostrarsi = 'Si';
		$codigono= 'N';
		$mostrarno = 'No';
		$combo_sino = array();
		/*for ($num_fila = 0; $num_fila <= $maxfila; $num_fila++) {
			if($num_fila == 0){
				$combo_sino[$num_fila] = array ("valor" => $codigosi, "mostrar" => $mostrarsi);
			}else{
				$combo_sino[$num_fila] = array ("valor" => $codigono, "mostrar" => $mostrarno);
			}
		}*/
		$combo_sino[0] = array ( "valor" => 'N', "mostrar" => 'No');
		$combo_sino[1] = array ( "valor" => 'S', "mostrar" => 'Si');
		$combo_sino[2] = array ( "valor" => 'C', "mostrar" => 'Con cargo');
		return $combo_sino;											
	}

	function Cargar_combo_Tipo_Usuarios(){
		$Cargar_combo_Tipo_Usuarios = array();
		$Cargar_combo_Tipo_Usuarios[0] = array ( "valor" => 'I', "mostrar" => 'Interno');
		$Cargar_combo_Tipo_Usuarios[1] = array ( "valor" => 'T', "mostrar" => 'Compañia de Transportes');
		return $Cargar_combo_Tipo_Usuarios;											
	}

	function Cargar_combo_Actividad(){
		$combo_actividad = array();
		$combo_actividad[0] = array ( "valor" => '', "mostrar" => '');
		$combo_actividad[1] = array ( "valor" => 'A', "mostrar" => 'Activa');
		$combo_actividad[2] = array ( "valor" => 'I', "mostrar" => 'Inactiva');
		return $combo_actividad;											
	}	

	function Cargar_combo_Tipo_Empresa(){
		$combo_Tipo_Empresa = array();
		$combo_Tipo_Empresa[0] = array ( "valor" => '1', "mostrar" => 'Mayorista de viajes');
		$combo_Tipo_Empresa[1] = array ( "valor" => '2', "mostrar" => 'Agencia Minorista');
		return $combo_Tipo_Empresa;											
	}

	function Cargar_Combo_Dias_Semana(){

		$Cargar_Combo_Dias_Semana = array();
		$Cargar_Combo_Dias_Semana[0] = array ( "valor" => '', "mostrar" => '');
		$Cargar_Combo_Dias_Semana[1] = array ( "valor" => 'L', "mostrar" => 'Lunes');
		$Cargar_Combo_Dias_Semana[2] = array ( "valor" => 'M', "mostrar" => 'Martes');
		$Cargar_Combo_Dias_Semana[3] = array ( "valor" => 'X', "mostrar" => 'Miercoles');		
		$Cargar_Combo_Dias_Semana[4] = array ( "valor" => 'J', "mostrar" => 'Jueves');
		$Cargar_Combo_Dias_Semana[5] = array ( "valor" => 'V', "mostrar" => 'Viernes');
		$Cargar_Combo_Dias_Semana[6] = array ( "valor" => 'S', "mostrar" => 'Sabado');
		$Cargar_Combo_Dias_Semana[7] = array ( "valor" => 'D', "mostrar" => 'Domingo');

		return $Cargar_Combo_Dias_Semana;											
	}	

	function Cargar_combo_Divisas(){

		$conexion = $this ->Conexion;

		$divisas =$conexion->query("SELECT codigo,nombre FROM hit_divisas ORDER BY NOMBRE");
		$numero_filas = $divisas->num_rows;

		if ($divisas == FALSE){
			echo('Error en la consulta');
			$divisas->close();
			$conexion->close();
			exit;
		}

		$combo_divisas = array();
		$combo_divisas[-1] = array ( "codigo" => '', "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$divisas->data_seek($num_fila);
			$fila = $divisas->fetch_assoc();
			array_push($combo_divisas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$divisas->close();

		return $combo_divisas;											
	}

	function Cargar_combo_Idiomas(){

		$conexion = $this ->Conexion;

		$idiomas =$conexion->query("SELECT codigo,nombre FROM hit_idiomas ORDER BY NOMBRE");
		$numero_filas = $idiomas->num_rows;

		if ($idiomas == FALSE){
			echo('Error en la consulta');
			$idiomas->close();
			$conexion->close();
			exit;
		}
		$combo_idiomas = array();
		$combo_idiomas[-1] = array ( "codigo" => '', "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$idiomas->data_seek($num_fila);
			$fila = $idiomas->fetch_assoc();
			array_push($combo_idiomas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$idiomas->close();

		return $combo_idiomas;											
	}

	function Cargar_combo_Continentes(){

		$conexion = $this ->Conexion;

		$continentes =$conexion->query("SELECT codigo,nombre FROM hit_continentes where visible_hits = 'S' ORDER BY NOMBRE");
		$numero_filas = $continentes->num_rows;

		if ($continentes == FALSE){
			echo('Error en la consulta');
			$continentes->close();
			$conexion->close();
			exit;
		}

		$combo_continentes = array();
		$combo_continentes[-1] = array ( "codigo" => '', "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$continentes->data_seek($num_fila);
			$fila = $continentes->fetch_assoc();
			array_push($combo_continentes,$fila);
		}

		//Liberar Memoria usada por la consulta
		$continentes->close();

		return $combo_continentes;											
	}

	function Cargar_combo_Paises(){

		$conexion = $this ->Conexion;

		$paises =$conexion->query("SELECT codigo,nombre FROM hit_paises where visible_hits = 'S' ORDER BY NOMBRE");
		$numero_filas = $paises->num_rows;

		if ($paises == FALSE){
			echo('Error en la consulta');
			$paises->close();
			$conexion->close();
			exit;
		}

		$combo_paises = array();
		$combo_paises[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$paises->data_seek($num_fila);
			$fila = $paises->fetch_assoc();
			array_push($combo_paises,$fila);
		}

		//Liberar Memoria usada por la consulta
		$paises->close();

		return $combo_paises;											
	}

	function Cargar_combo_Paises_Reserva(){

		$conexion = $this ->Conexion;

		$paises =$conexion->query("SELECT codigo,nombre FROM hit_paises where codigo in ('ESP','PRT') and visible_hits = 'S' ORDER BY NOMBRE");
		$numero_filas = $paises->num_rows;

		if ($paises == FALSE){
			echo('Error en la consulta');
			$paises->close();
			$conexion->close();
			exit;
		}

		$combo_paises = array();
		$combo_paises[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$paises->data_seek($num_fila);
			$fila = $paises->fetch_assoc();
			array_push($combo_paises,$fila);
		}

		//Liberar Memoria usada por la consulta
		$paises->close();

		return $combo_paises;											
	}

	function Cargar_combo_Paises_ISO2(){

		$conexion = $this ->Conexion;

		$paises =$conexion->query("SELECT codigo_corto codigo ,nombre FROM hit_paises where visible_hits = 'S' ORDER BY NOMBRE");
		$numero_filas = $paises->num_rows;

		if ($paises == FALSE){
			echo('Error en la consulta');
			$paises->close();
			$conexion->close();
			exit;
		}

		$combo_paises = array();
		$combo_paises[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$paises->data_seek($num_fila);
			$fila = $paises->fetch_assoc();
			array_push($combo_paises,$fila);
		}

		//Liberar Memoria usada por la consulta
		$paises->close();

		return $combo_paises;											
	}

	function Cargar_combo_Provincias(){

		$conexion = $this ->Conexion;

		$provincias =$conexion->query("SELECT codigo, concat(pais,' - ',nombre) nombre FROM hit_provincias where visible_hits = 'S'  ORDER BY NOMBRE");
		$numero_filas = $provincias->num_rows;

		if ($provincias == FALSE){
			echo('Error en la consulta');
			$provincias->close();
			$conexion->close();
			exit;
		}

		$combo_provincias = array();
		$combo_provincias[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$provincias->data_seek($num_fila);
			$fila = $provincias->fetch_assoc();
			array_push($combo_provincias,$fila);
		}

		//Liberar Memoria usada por la consulta
		$provincias->close();

		return $combo_provincias;											
	}

	function Cargar_combo_Provincias_Seleccion1(){

		$conexion = $this ->Conexion;

		$provincias =$conexion->query("SELECT codigo,nombre FROM hit_provincias WHERE PAIS = 'ESP' AND codigo <> 'LN' ORDER BY NOMBRE");
		$numero_filas = $provincias->num_rows;

		if ($provincias == FALSE){
			echo('Error en la consulta');
			$provincias->close();
			$conexion->close();
			exit;
		}

		$combo_provincias = array();
		$combo_provincias[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$provincias->data_seek($num_fila);
			$fila = $provincias->fetch_assoc();
			array_push($combo_provincias,$fila);
		}

		//Liberar Memoria usada por la consulta
		$provincias->close();

		return $combo_provincias;											
	}

	function Cargar_combo_Provincias_Pais($pais){

		$conexion = $this ->Conexion;

		$provincias =$conexion->query("SELECT codigo,nombre FROM hit_provincias WHERE PAIS = '".$pais."' AND codigo <> 'LN' ORDER BY NOMBRE");
		$numero_filas = $provincias->num_rows;

		if ($provincias == FALSE){
			echo('Error en la consulta');
			$provincias->close();
			$conexion->close();
			exit;
		}

		$combo_provincias = array();
		$combo_provincias[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$provincias->data_seek($num_fila);
			$fila = $provincias->fetch_assoc();
			array_push($combo_provincias,$fila);
		}

		//Liberar Memoria usada por la consulta
		$provincias->close();

		return $combo_provincias;											
	}

	function Cargar_combo_Areas(){

		$conexion = $this ->Conexion;

		$areas =$conexion->query("SELECT codigo,nombre FROM hit_areas where visible_hits = 'S' ORDER BY NOMBRE");
		$numero_filas = $areas->num_rows;

		if ($areas == FALSE){
			echo('Error en la consulta');
			$areas->close();
			$conexion->close();
			exit;
		}

		$combo_areas = array();
		$combo_areas[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$areas->data_seek($num_fila);
			$fila = $areas->fetch_assoc();
			array_push($combo_areas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$areas->close();

		return $combo_areas;											
	}

	function Cargar_combo_Departamentos(){

		$conexion = $this ->Conexion;

		$departamentos =$conexion->query("SELECT codigo,nombre FROM hit_departamentos ORDER BY NOMBRE");
		$numero_filas = $departamentos->num_rows;

		if ($departamentos == FALSE){
			echo('Error en la consulta');
			$departamentos->close();
			$conexion->close();
			exit;
		}

		$combo_departamentos = array();
		$combo_departamentos[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$departamentos->data_seek($num_fila);
			$fila = $departamentos->fetch_assoc();
			array_push($combo_departamentos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$departamentos->close();

		return $combo_departamentos;											
	}

	function Cargar_combo_Comunidades(){

		$conexion = $this ->Conexion;

		$comunidades =$conexion->query("SELECT codigo,nombre FROM hit_comunidades ORDER BY NOMBRE");
		$numero_filas = $comunidades->num_rows;

		if ($comunidades == FALSE){
			echo('Error en la consulta');
			$comunidades->close();
			$conexion->close();
			exit;
		}

		$combo_comunidades = array();
		$combo_comunidades[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$comunidades->data_seek($num_fila);
			$fila = $comunidades->fetch_assoc();
			array_push($combo_comunidades,$fila);
		}

		//Liberar Memoria usada por la consulta
		$comunidades->close();

		return $combo_comunidades;											
	}

	function Cargar_combo_Logos(){

		$conexion = $this ->Conexion;

		$idiomas =$conexion->query("SELECT id,nombre FROM hit_logos ORDER BY NOMBRE");
		$numero_filas = $idiomas->num_rows;

		if ($idiomas == FALSE){
			echo('Error en la consulta');
			$idiomas->close();
			$conexion->close();
			exit;
		}
		$combo_logos = array();
		$combo_logos[-1] = array ( "id" => '', "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$idiomas->data_seek($num_fila);
			$fila = $idiomas->fetch_assoc();
			array_push($combo_logos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$idiomas->close();

		return $combo_logos;											
	}

	function Cargar_combo_Impuestos(){

		$conexion = $this ->Conexion;

		$impuestos =$conexion->query("SELECT codigo,nombre FROM hit_impuestos ORDER BY NOMBRE");
		$numero_filas = $impuestos->num_rows;

		if ($impuestos == FALSE){
			echo('Error en la consulta');
			$impuestos->close();
			$conexion->close();
			exit;
		}

		$combo_impuestos = array();
		$combo_impuestos[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$impuestos->data_seek($num_fila);
			$fila = $impuestos->fetch_assoc();
			array_push($combo_impuestos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$impuestos->close();

		return $combo_impuestos;											
	}

	function Cargar_combo_Ciudades(){

		$conexion = $this ->Conexion;

		$ciudades =$conexion->query("SELECT codigo,nombre FROM hit_ciudades ORDER BY NOMBRE");
		$numero_filas = $ciudades->num_rows;

		if ($ciudades == FALSE){
			echo('Error en la consulta');
			$ciudades->close();
			$conexion->close();
			exit;
		}

		$combo_ciudades = array();
		$combo_ciudades[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$ciudades->data_seek($num_fila);
			$fila = $ciudades->fetch_assoc();
			array_push($combo_ciudades,$fila);
		}

		//Liberar Memoria usada por la consulta
		$ciudades->close();

		return $combo_ciudades;											
	}

	function Cargar_combo_Ciudades_Provincia($provincia){

		$conexion = $this ->Conexion;

		$ciudades =$conexion->query("SELECT codigo,nombre FROM hit_ciudades WHERE  PROVINCIA = '".$provincia."' ORDER BY NOMBRE");
		$numero_filas = $ciudades->num_rows;

		if ($ciudades == FALSE){
			echo('Error en la consulta');
			$ciudades->close();
			$conexion->close();
			exit;
		}

		$combo_ciudades = array();
		$combo_ciudades[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$ciudades->data_seek($num_fila);
			$fila = $ciudades->fetch_assoc();
			array_push($combo_ciudades,$fila);
		}

		//Liberar Memoria usada por la consulta
		$ciudades->close();

		return $combo_ciudades;											
	}


	function Cargar_combo_Aeropuertos(){

		$conexion = $this ->Conexion;

		$aeropuertos =$conexion->query("SELECT codigo,nombre FROM hit_aeropuertos ORDER BY NOMBRE");
		$numero_filas = $aeropuertos->num_rows;

		if ($aeropuertos == FALSE){
			echo('Error en la consulta');
			$aeropuertos->close();
			$conexion->close();
			exit;
		}

		$combo_aeropuertos = array();
		$combo_aeropuertos[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$aeropuertos->data_seek($num_fila);
			$fila = $aeropuertos->fetch_assoc();
			array_push($combo_aeropuertos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$aeropuertos->close();

		return $combo_aeropuertos;											
	}

	function Cargar_combo_Cargos(){

		$conexion = $this ->Conexion;

		$cargos =$conexion->query("SELECT codigo,nombre FROM hit_cargos ORDER BY NOMBRE");
		$numero_filas = $cargos->num_rows;

		if ($cargos == FALSE){
			echo('Error en la consulta');
			$cargos->close();
			$conexion->close();
			exit;
		}

		$combo_cargos = array();
		$combo_cargos[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$cargos->data_seek($num_fila);
			$fila = $cargos->fetch_assoc();
			array_push($combo_cargos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$cargos->close();

		return $combo_cargos;											
	}

	function Cargar_combo_Productos(){

		$conexion = $this ->Conexion;

		$productos =$conexion->query("SELECT codigo,nombre FROM hit_productos ORDER BY NOMBRE");
		$numero_filas = $productos->num_rows;

		if ($productos == FALSE){
			echo('Error en la consulta');
			$productos->close();
			$conexion->close();
			exit;
		}

		$combo_productos = array();
		$combo_productos[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$productos->data_seek($num_fila);
			$fila = $productos->fetch_assoc();
			array_push($combo_productos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$productos->close();

		return $combo_productos;											
	}

	function Cargar_combo_Grupos_comision(){

		$conexion = $this ->Conexion;

		$grupos_comision =$conexion->query("SELECT codigo,nombre FROM hit_grupos_comision ORDER BY NOMBRE");
		$numero_filas = $grupos_comision->num_rows;

		if ($grupos_comision == FALSE){
			echo('Error en la consulta');
			$grupos_comision->close();
			$conexion->close();
			exit;
		}

		$combo_grupos_comision = array();
		$combo_grupos_comision[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$grupos_comision->data_seek($num_fila);
			$fila = $grupos_comision->fetch_assoc();
			array_push($combo_grupos_comision,$fila);
		}

		//Liberar Memoria usada por la consulta
		$grupos_comision->close();

		return $combo_grupos_comision;											
	}

	function Cargar_combo_Grupos_gestion(){

		$conexion = $this ->Conexion;

		$grupos_gestion =$conexion->query("SELECT id,nombre FROM hit_grupos_gestion ORDER BY NOMBRE");
		$numero_filas = $grupos_gestion->num_rows;

		if ($grupos_gestion == FALSE){
			echo('Error en la consulta');
			$grupos_gestion->close();
			$conexion->close();
			exit;
		}

		$combo_grupos_gestion = array();
		$combo_grupos_gestion[-1] = array ( "id" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$grupos_gestion->data_seek($num_fila);
			$fila = $grupos_gestion->fetch_assoc();
			array_push($combo_grupos_gestion,$fila);
		}

		//Liberar Memoria usada por la consulta
		$grupos_gestion->close();

		return $combo_grupos_gestion;											
	}

	function Cargar_combo_Situacion_venta(){

		$conexion = $this ->Conexion;

		$situacion_venta =$conexion->query("SELECT codigo,nombre FROM hit_situacion_venta ORDER BY NOMBRE");
		$numero_filas = $situacion_venta->num_rows;

		if ($situacion_venta == FALSE){
			echo('Error en la consulta');
			$situacion_venta->close();
			$conexion->close();
			exit;
		}

		$combo_situacion_venta = array();
		$combo_situacion_venta[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$situacion_venta->data_seek($num_fila);
			$fila = $situacion_venta->fetch_assoc();
			array_push($combo_situacion_venta,$fila);
		}

		//Liberar Memoria usada por la consulta
		$situacion_venta->close();

		return $combo_situacion_venta;											
	}

	function Cargar_combo_Delegaciones(){

		$conexion = $this ->Conexion;

		$delegaciones =$conexion->query("SELECT codigo,nombre FROM hit_delegaciones ORDER BY NOMBRE");
		$numero_filas = $delegaciones->num_rows;

		if ($delegaciones == FALSE){
			echo('Error en la consulta');
			$delegaciones->close();
			$conexion->close();
			exit;
		}

		$combo_delegaciones= array();
		$combo_delegaciones[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$delegaciones->data_seek($num_fila);
			$fila = $delegaciones->fetch_assoc();
			array_push($combo_delegaciones,$fila);
		}

		//Liberar Memoria usada por la consulta
		$delegaciones->close();

		return $combo_delegaciones;											
	}


	function Cargar_combo_Tipos_alojamiento(){

		$conexion = $this ->Conexion;

		$tipos =$conexion->query("SELECT codigo,nombre FROM hit_tipos_alojamiento ORDER BY NOMBRE");
		$numero_filas = $tipos->num_rows;

		if ($tipos == FALSE){
			echo('Error en la consulta');
			$tipos->close();
			$conexion->close();
			exit;
		}

		$combo_tipos= array();
		$combo_tipos[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$tipos->data_seek($num_fila);
			$fila = $tipos->fetch_assoc();
			array_push($combo_tipos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$tipos->close();

		return $combo_tipos;											
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
		$combo_categorias[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$categorias->data_seek($num_fila);
			$fila = $categorias->fetch_assoc();
			array_push($combo_categorias,$fila);
		}

		//Liberar Memoria usada por la consulta
		$categorias->close();

		return $combo_categorias;											
	}

	function Cargar_combo_Situacion(){

		$conexion = $this ->Conexion;

		$situacion =$conexion->query("SELECT codigo,nombre FROM hit_situacion ORDER BY NOMBRE");
		$numero_filas = $situacion->num_rows;

		if ($situacion == FALSE){
			echo('Error en la consulta');
			$situacion->close();
			$conexion->close();
			exit;
		}

		$combo_situacion= array();
		$combo_situacion[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$situacion->data_seek($num_fila);
			$fila = $situacion->fetch_assoc();
			array_push($combo_situacion,$fila);
		}

		//Liberar Memoria usada por la consulta
		$situacion->close();

		return $combo_situacion;											
	}

	function Cargar_combo_Tipo_imagenes(){

		$combo_tipo_imagenes = array();
		$combo_tipo_imagenes[0] = array ( "valor" => '', "mostrar" => '');
		$combo_tipo_imagenes[1] = array ( "valor" => 'P', "mostrar" => 'Principal');
		$combo_tipo_imagenes[2] = array ( "valor" => 'H', "mostrar" => 'Habitaciones');
		$combo_tipo_imagenes[3] = array ( "valor" => 'R', "mostrar" => 'Recepcion');
		$combo_tipo_imagenes[4] = array ( "valor" => 'I', "mostrar" => 'Instalaciones');
		$combo_tipo_imagenes[5] = array ( "valor" => 'S', "mostrar" => 'Servicios');
		$combo_tipo_imagenes[6] = array ( "valor" => 'E', "mostrar" => 'Entorno');
		$combo_tipo_imagenes[7] = array ( "valor" => 'G', "mostrar" => 'Gastronomia');
		$combo_tipo_imagenes[8] = array ( "valor" => 'C', "mostrar" => 'Piscina');
		return $combo_tipo_imagenes;											
	}

	function Cargar_combo_Tipo_imagenes_Cuadros(){

		$combo_tipo_imagenes = array();
		$combo_tipo_imagenes[0] = array ( "valor" => '', "mostrar" => '');
		$combo_tipo_imagenes[1] = array ( "valor" => 'P', "mostrar" => 'Principal');
		$combo_tipo_imagenes[2] = array ( "valor" => 'D', "mostrar" => 'Destalles');
		return $combo_tipo_imagenes;											
	}

	function Cargar_combo_Numeros_imagenes(){

		$combo_numero_imagenes = array();
		$combo_numero_imagenes[0] = array ( "valor" => '1', "mostrar" => '1');
		$combo_numero_imagenes[1] = array ( "valor" => '2', "mostrar" => '2');
		$combo_numero_imagenes[2] = array ( "valor" => '3', "mostrar" => '3');
		$combo_numero_imagenes[3] = array ( "valor" => '4', "mostrar" => '4');
		$combo_numero_imagenes[4] = array ( "valor" => '5', "mostrar" => '5');
		$combo_numero_imagenes[5] = array ( "valor" => '6', "mostrar" => '6');
		$combo_numero_imagenes[6] = array ( "valor" => '7', "mostrar" => '7');
		$combo_numero_imagenes[7] = array ( "valor" => '8', "mostrar" => '8');
		$combo_numero_imagenes[8] = array ( "valor" => '9', "mostrar" => '9');
		$combo_numero_imagenes[9] = array ( "valor" => '10', "mostrar" => '10');
		$combo_numero_imagenes[10] = array ( "valor" => '11', "mostrar" => '11');
		$combo_numero_imagenes[11] = array ( "valor" => '12', "mostrar" => '12');
		$combo_numero_imagenes[12] = array ( "valor" => '13', "mostrar" => '13');
		$combo_numero_imagenes[13] = array ( "valor" => '14', "mostrar" => '14');
		$combo_numero_imagenes[14] = array ( "valor" => '15', "mostrar" => '15');
		return $combo_numero_imagenes;											
	}


	function Cargar_combo_Alojamientos(){

		$conexion = $this ->Conexion;

		$alojamientos =$conexion->query("SELECT id,nombre FROM hit_alojamientos WHERE visible = 'S' ORDER BY nombre");
		$numero_filas = $alojamientos->num_rows;

		if ($alojamientos == FALSE){
			echo('Error en la consulta');
			$alojamientos->close();
			$conexion->close();
			exit;
		}

		$combo_alojamientos = array();
		$combo_alojamientos[-1] = array ( "id" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$alojamientos->data_seek($num_fila);
			$fila = $alojamientos->fetch_assoc();
			array_push($combo_alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$alojamientos->close();

		return $combo_alojamientos;											
	}

	function Cargar_combo_AlojamientosProvincias($provincia){

		$conexion = $this ->Conexion;

		$alojamientos =$conexion->query("SELECT id,nombre FROM hit_alojamientos WHERE visible = 'X' and provincia = '".$provincia."' ORDER BY nombre");
		$numero_filas = $alojamientos->num_rows;

		if ($alojamientos == FALSE){
			echo('Error en la consulta');
			$alojamientos->close();
			$conexion->close();
			exit;
		}

		$combo_alojamientos = array();
		$combo_alojamientos[-1] = array ( "id" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$alojamientos->data_seek($num_fila);
			$fila = $alojamientos->fetch_assoc();
			array_push($combo_alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$alojamientos->close();

		return $combo_alojamientos;											
	}

	function Cargar_combo_Alojamientos_Teletipos($clave_cuadro){

		$conexion = $this ->Conexion;


		$Alojamientos =$conexion->query("select distinct a.ID id, a.NOMBRE nombre
										FROM hit_producto_cuadros_alojamientos c, hit_alojamientos a
										WHERE c.alojamiento = a.id and c.clave_cuadro = '".$clave_cuadro."' ORDER BY nombre");


		$numero_filas = $Alojamientos->num_rows;

		if ($Alojamientos == FALSE){
			echo('Error en la consulta');
			$Servicios->close();
			$conexion->close();
			exit;
		}

		$combo_Alojamientos = array();
		$combo_Alojamientos[-1] = array ( "id" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$Alojamientos->data_seek($num_fila);
			$fila = $Alojamientos->fetch_assoc();
			array_push($combo_Alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$Alojamientos->close();

		return $combo_Alojamientos;											
	}

	function Cargar_combo_Alojamientos_like($buscar_nombre){

		$conexion = $this ->Conexion;

		if($buscar_nombre == null){
			$buscar_nombre = 'xxxx';
		}

		$alojamientos =$conexion->query("SELECT a.id,concat(a.pais,' - ', ciu.NOMBRE, ' - ', a.nombre) nombre 
								FROM hit_alojamientos a, hit_paises p, hit_provincias pr, hit_ciudades ciu
								WHERE 
								p.CODIGO = pr.PAIS
								and pr.CODIGO = ciu.PROVINCIA
								and ciu.CODIGO = a.CIUDAD
								and a.visible = 'S' and a.nombre like '%".$buscar_nombre."%' ORDER BY p.NOMBRE, ciu.NOMBRE, a.nombre");
		$numero_filas = $alojamientos->num_rows;

		if ($alojamientos == FALSE){
			echo('Error en la consulta');
			$alojamientos->close();
			$conexion->close();
			exit;
		}

		$combo_alojamientos = array();
		$combo_alojamientos[-1] = array ( "id" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$alojamientos->data_seek($num_fila);
			$fila = $alojamientos->fetch_assoc();
			array_push($combo_alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$alojamientos->close();

		return $combo_alojamientos;											
	}

	function Cargar_combo_Alojamientos_Destino($destino){

		$conexion = $this ->Conexion;

		$alojamientos =$conexion->query("SELECT id,nombre FROM hit_alojamientos WHERE visible = 'S' and destino_producto = '".$destino."' ORDER BY nombre");
		$numero_filas = $alojamientos->num_rows;

		if ($alojamientos == FALSE){
			echo('Error en la consulta');
			$alojamientos->close();
			$conexion->close();
			exit;
		}

		$combo_alojamientos = array();
		$combo_alojamientos[-1] = array ( "id" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$alojamientos->data_seek($num_fila);
			$fila = $alojamientos->fetch_assoc();
			array_push($combo_alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$alojamientos->close();

		return $combo_alojamientos;											
	}

	function Cargar_combo_Tipos_acuerdo(){

		$combo_tipos_acuerdo = array();
		$combo_tipos_acuerdo[0] = array ( "valor" => 'C', "mostrar" => 'Con cupos');
		$combo_tipos_acuerdo[1] = array ( "valor" => 'O', "mostrar" => 'On-request');
		$combo_tipos_acuerdo[2] = array ( "valor" => 'I', "mostrar" => 'Interfaz');
		return $combo_tipos_acuerdo;											
	}

	function Cargar_combo_Tipos_acuerdo_transportes(){

		$combo_tipos_acuerdo = array();
		$combo_tipos_acuerdo[0] = array ( "valor" => '', "mostrar" => '');
		$combo_tipos_acuerdo[1] = array ( "valor" => 'CHR', "mostrar" => 'Charter');
		$combo_tipos_acuerdo[2] = array ( "valor" => 'RCU', "mostrar" => 'Regular Cupos');
		$combo_tipos_acuerdo[3] = array ( "valor" => 'RAC', "mostrar" => 'Regular Cupos Amadeus');
		$combo_tipos_acuerdo[4] = array ( "valor" => 'ROR', "mostrar" => 'Regular On-request');
		$combo_tipos_acuerdo[5] = array ( "valor" => 'BUS', "mostrar" => 'Bus');
		return $combo_tipos_acuerdo;											
	}

	function Cargar_combo_Situacion_acuerdo(){

		$combo_situacion_acuerdo = array();
		$combo_situacion_acuerdo[0] = array ( "valor" => 'P', "mostrar" => 'Pendiente');
		$combo_situacion_acuerdo[1] = array ( "valor" => 'A', "mostrar" => 'Aprobado');
		$combo_situacion_acuerdo[2] = array ( "valor" => 'E', "mostrar" => 'Expandido');
		return $combo_situacion_acuerdo;											
	}

	function Cargar_combo_Situacion_servicio(){

		$combo_situacion_servicio = array();
		$combo_situacion_servicio[0] = array ( "valor" => 'P', "mostrar" => 'Pendiente');
		$combo_situacion_servicio[1] = array ( "valor" => 'A', "mostrar" => 'Aprobado');
		$combo_situacion_servicio[2] = array ( "valor" => 'E', "mostrar" => 'Expandido');
		$combo_situacion_servicio[3] = array ( "valor" => 'D', "mostrar" => 'Denegado');
		return $combo_situacion_servicio;											
	}

	function Cargar_combo_Tipos_cupo(){

		$combo_tipo_cupo = array();
		$combo_tipo_cupo[0] = array ( "valor" => 'F', "mostrar" => 'Free booking');
		$combo_tipo_cupo[1] = array ( "valor" => 'O', "mostrar" => 'On request');
		$combo_tipo_cupo[2] = array ( "valor" => 'C', "mostrar" => 'Con cupos');
		return $combo_tipo_cupo;											
	}

	function Cargar_combo_Tipos_Gratuidad(){

		$combo_tio_gratuidad = array();
		$combo_tio_gratuidad[0] = array ( "valor" => 'S', "mostrar" => 'Sin gratuidad');
		$combo_tio_gratuidad[1] = array ( "valor" => 'P', "mostrar" => 'Por Pasajeros');
		$combo_tio_gratuidad[2] = array ( "valor" => 'H', "mostrar" => 'Por Habitacion');
		return $combo_tio_gratuidad;											
	}

	function Cargar_combo_Habitaciones(){

		$conexion = $this ->Conexion;

		$habitaciones =$conexion->query("SELECT codigo,nombre FROM hit_habitaciones ORDER BY NOMBRE");
		$numero_filas = $habitaciones->num_rows;

		if ($habitaciones == FALSE){
			echo('Error en la consulta');
			$habitaciones->close();
			$conexion->close();
			exit;
		}

		$combo_habitaciones = array();
		$combo_habitaciones[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$habitaciones->data_seek($num_fila);
			$fila = $habitaciones->fetch_assoc();
			array_push($combo_habitaciones,$fila);
		}

		//Liberar Memoria usada por la consulta
		$habitaciones->close();

		return $combo_habitaciones;											
	}

	function Cargar_combo_Habitaciones_car(){

		$conexion = $this ->Conexion;

		$caracteristicas =$conexion->query("SELECT codigo,nombre FROM hit_habitaciones_car ORDER BY NOMBRE");
		$numero_filas = $caracteristicas->num_rows;

		if ($caracteristicas == FALSE){
			echo('Error en la consulta');
			$caracteristicas->close();
			$conexion->close();
			exit;
		}

		$combo_caracteristicas = array();
		$combo_caracteristicas[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$caracteristicas->data_seek($num_fila);
			$fila = $caracteristicas->fetch_assoc();
			array_push($combo_caracteristicas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$caracteristicas->close();

		return $combo_caracteristicas;											
	}

	function Cargar_combo_Habitaciones_car_contrato($id_hotel,$contrato){

		$conexion = $this ->Conexion;

		//echo($id_hotel."-".$contrato);

		$caracteristicas =$conexion->query("SELECT c.codigo, concat(c.nombre,' (',c.codigo,')') nombre 
												FROM hit_habitaciones_car c, hit_alojamientos_usos u
												where
													u.CARACTERISTICA = c.CODIGO
													and u.ID = '".$id_hotel."'
													and u.ACUERDO = '".$contrato."'
												ORDER BY NOMBRE");

		$numero_filas = $caracteristicas->num_rows;

		if ($caracteristicas == FALSE){
			echo('Error en la consulta');
			$caracteristicas->close();
			$conexion->close();
			exit;
		}

		$combo_caracteristicas = array();
		$combo_caracteristicas[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$caracteristicas->data_seek($num_fila);
			$fila = $caracteristicas->fetch_assoc();
			array_push($combo_caracteristicas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$caracteristicas->close();

		return $combo_caracteristicas;											
	}

	function Cargar_combo_Calculo(){

		$combo_calculo = array();
		$combo_calculo[0] = array ( "valor" => 'A', "mostrar" => 'Automático');
		$combo_calculo[1] = array ( "valor" => 'M', "mostrar" => 'Manual');
		return $combo_calculo;											
	}

	function Cargar_combo_Tipo_Pago(){

		$combo_tipo_pago = array();
		$combo_tipo_pago[0] = array ( "valor" => 'P', "mostrar" => 'Prepago');
		$combo_tipo_pago[1] = array ( "valor" => 'C', "mostrar" => 'Credito');
		return $combo_tipo_pago;											
	}

	function Cargar_combo_Forma_Pago(){

		$combo_forma_pago = array();
		$combo_forma_pago[0] = array ( "valor" => 'T', "mostrar" => 'Transferencia tarjeta');
		$combo_forma_pago[1] = array ( "valor" => '2', "mostrar" => 'Transferencia talon');
		$combo_forma_pago[2] = array ( "valor" => '3', "mostrar" => 'Transferencia pagare');
		$combo_forma_pago[3] = array ( "valor" => 'M', "mostrar" => 'Metalico');
		return $combo_forma_pago;											
	}

	function Cargar_combo_Forma_Pago_Transportes(){

		$combo_forma_pago = array();
		$combo_forma_pago[0] = array ( "valor" => 'T', "mostrar" => 'Transferencia tarjeta');
		$combo_forma_pago[1] = array ( "valor" => '2', "mostrar" => 'Transferencia talon');
		$combo_forma_pago[2] = array ( "valor" => '3', "mostrar" => 'Transferencia pagare');
		$combo_forma_pago[3] = array ( "valor" => 'M', "mostrar" => 'Metalico');
		$combo_forma_pago[4] = array ( "valor" => 'B', "mostrar" => 'BSP');
		return $combo_forma_pago;											
	}

	function Cargar_combo_Tipo_Pvp(){

		$combo_tipo_pvp = array();
		$combo_tipo_pvp[0] = array ( "valor" => 'C', "mostrar" => 'Comisionable');
		$combo_tipo_pvp[1] = array ( "valor" => 'N', "mostrar" => 'Neto');
		return $combo_tipo_pvp;											
	}

	function Cargar_combo_Tipo_Cuadros(){

		$combo_tipo_cuadro = array();
		$combo_tipo_cuadro[0] = array ( "valor" => '', "mostrar" => '');
		$combo_tipo_cuadro[1] = array ( "valor" => 'P', "mostrar" => 'Paquete de estancias');
		$combo_tipo_cuadro[2] = array ( "valor" => 'V', "mostrar" => 'Solo vuelo');
		$combo_tipo_cuadro[3] = array ( "valor" => 'H', "mostrar" => 'Solo hotel');
		$combo_tipo_cuadro[4] = array ( "valor" => 'S', "mostrar" => 'Solo servicios');
		$combo_tipo_cuadro[5] = array ( "valor" => 'C', "mostrar" => 'Circuito');
		return $combo_tipo_cuadro;											
	}

	function Cargar_combo_Situacion_Cuadros(){

		$combo_situacion_cuadro = array();
		$combo_situacion_cuadro[0] = array ( "valor" => '', "mostrar" => '');
		$combo_situacion_cuadro[1] = array ( "valor" => 'X', "mostrar" => 'Certificacion Interfaz');
		$combo_situacion_cuadro[2] = array ( "valor" => 'P', "mostrar" => 'Pendiente');
		$combo_situacion_cuadro[3] = array ( "valor" => 'V', "mostrar" => 'Venta interna');
		$combo_situacion_cuadro[4] = array ( "valor" => 'W', "mostrar" => 'Venta web');
		$combo_situacion_cuadro[5] = array ( "valor" => 'C', "mostrar" => 'Cerrado');
		return $combo_situacion_cuadro;											
	}

	function Cargar_combo_Frecuencia_Salidas(){

		$combo_frecuencia_salidas = array();
		$combo_frecuencia_salidas[0] = array ( "valor" => '', "mostrar" => '');
		$combo_frecuencia_salidas[1] = array ( "valor" => 'S', "mostrar" => 'Semanal');
		$combo_frecuencia_salidas[2] = array ( "valor" => 'U', "mostrar" => 'Unica');
		$combo_frecuencia_salidas[3] = array ( "valor" => 'Q', "mostrar" => 'Quincenal');
		return $combo_frecuencia_salidas;											
	}

	function Cargar_combo_Forma_Calculo(){

		$combo_forma_calculo = array();
		$combo_forma_calculo[-1] = array ( "valor" => '', "mostrar" => '');
		$combo_forma_calculo[0] = array ( "valor" => 'P', "mostrar" => 'Porcentaje');
		$combo_forma_calculo[1] = array ( "valor" => 'C', "mostrar" => 'Cantidad');
		return $combo_forma_calculo;											
	}

	function Cargar_combo_Forma_Calculo_acuerdos_alojamientos(){

		$combo_forma_calculo = array();
		$combo_forma_calculo[-1] = array ( "valor" => '', "mostrar" => '');
		$combo_forma_calculo[0] = array ( "valor" => 'P', "mostrar" => '% Todo');
		$combo_forma_calculo[1] = array ( "valor" => 'R', "mostrar" => '% Regimen');	
		$combo_forma_calculo[2] = array ( "valor" => 'E', "mostrar" => '% Estancia');	
		$combo_forma_calculo[3] = array ( "valor" => 'C', "mostrar" => 'Cantidad Estancia');
		$combo_forma_calculo[4] = array ( "valor" => 'N', "mostrar" => 'Cantidad Noche');
		return $combo_forma_calculo;											
	}

	function Cargar_combo_Tipo_Condiciones(){

		/*$combo_tipo_codiciones = array();
		$combo_tipo_codiciones[-1] = array ( "valor" => '', "mostrar" => '');
		$combo_tipo_codiciones[0] = array ( "valor" => 'AN', "mostrar" => 'Gastos de Anulacion');
		$combo_tipo_codiciones[1] = array ( "valor" => 'N1', "mostrar" => 'Descuento Primer Niño');
		$combo_tipo_codiciones[2] = array ( "valor" => 'N2', "mostrar" => 'Descuento Segundo Niño');
		$combo_tipo_codiciones[3] = array ( "valor" => 'NA', "mostrar" => 'Descuento Niño Acompañado un Adulto');
		$combo_tipo_codiciones[4] = array ( "valor" => 'NG', "mostrar" => 'Niño Gratis');
		$combo_tipo_codiciones[5] = array ( "valor" => 'BE', "mostrar" => 'Bebe Gratis');
		$combo_tipo_codiciones[6] = array ( "valor" => '3P', "mostrar" => 'Descuento Tercera Persona');
		$combo_tipo_codiciones[7] = array ( "valor" => '4P', "mostrar" => 'Descuento Cuarta Persona');
		$combo_tipo_codiciones[8] = array ( "valor" => 'AT', "mostrar" => 'Descuento Antelacion');
		$combo_tipo_codiciones[9] = array ( "valor" => 'AC', "mostrar" => 'Descuento Acompañante');
		$combo_tipo_codiciones[10] = array ( "valor" => 'NO', "mostrar" => 'Descuento Novios');
		$combo_tipo_codiciones[11] = array ( "valor" => 'SE', "mostrar" => 'Descuento Senior');
		$combo_tipo_codiciones[12] = array ( "valor" => 'AG', "mostrar" => 'Descuento Agente de Viajes');
		$combo_tipo_codiciones[13] = array ( "valor" => 'MI', "mostrar" => 'Miniprecio');
		$combo_tipo_codiciones[14] = array ( "valor" => 'EA', "mostrar" => 'Exclusivo Adultos');
		$combo_tipo_codiciones[15] = array ( "valor" => 'DS', "mostrar" => 'Descuento Fecha de Salida/Reserva');
		$combo_tipo_codiciones[16] = array ( "valor" => 'SS', "mostrar" => 'Suplemento Fecha de Salida/Reserva');
		$combo_tipo_codiciones[17] = array ( "valor" => 'CA', "mostrar" => 'Suplemento Carburante');
		$combo_tipo_codiciones[18] = array ( "valor" => 'EX', "mostrar" => 'Suplemento Exceso de Equipaje');
		$combo_tipo_codiciones[19] = array ( "valor" => 'CF', "mostrar" => 'Cesta de Frutas / Regalo de Bienvenida');
		return $combo_tipo_codiciones;		*/
		


		$conexion = $this ->Conexion;

		$condiciones =$conexion->query("SELECT codigo valor,nombre mostrar FROM hit_condiciones_comerciales WHERE visible = 'S' ORDER BY NOMBRE");
		$numero_filas = $condiciones->num_rows;

		if ($condiciones == FALSE){
			echo('Error en la consulta');
			$condiciones->close();
			$conexion->close();
			exit;
		}

		$combo_condiciones = array();
		$combo_condiciones[-1] = array ( "valor" => null, "mostrar" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$condiciones->data_seek($num_fila);
			$fila = $condiciones->fetch_assoc();
			array_push($combo_condiciones,$fila);
		}

		//Liberar Memoria usada por la consulta
		$condiciones->close();

		return $combo_condiciones;	

	}

	function Cargar_combo_Aplicacion_Condicion(){

		$combo_aplicacion_condicion = array();
		$combo_aplicacion_condicion[-1] = array ( "valor" => '', "mostrar" => '');
		$combo_aplicacion_condicion[0] = array ( "valor" => 'T', "mostrar" => 'Todo el precio');
		$combo_aplicacion_condicion[1] = array ( "valor" => 'A', "mostrar" => 'Solo Aereos');
		$combo_aplicacion_condicion[2] = array ( "valor" => 'R', "mostrar" => 'Solo Hotel');
		return $combo_aplicacion_condicion;											
	}

	function Cargar_combo_Imprimir_Bono(){

		$combo_imprimir_bono = array();
		$combo_imprimir_bono[0] = array ( "valor" => 'N', "mostrar" => 'No');
		$combo_imprimir_bono[1] = array ( "valor" => 'S', "mostrar" => 'Si');
		$combo_imprimir_bono[2] = array ( "valor" => 'M', "mostrar" => 'Si Gestionado');
		return $combo_imprimir_bono;											
	}

	function Cargar_combo_Tipo_Unidad(){

		$combo_tipo_unidad = array();
		$combo_tipo_unidad[0] = array ( "valor" => 'P', "mostrar" => 'Persona');
		$combo_tipo_unidad[1] = array ( "valor" => 'V', "mostrar" => 'Reserva');
		$combo_tipo_unidad[2] = array ( "valor" => 'D', "mostrar" => 'Día');
		$combo_tipo_unidad[3] = array ( "valor" => 'S', "mostrar" => 'Persona y Día');
		$combo_tipo_unidad[4] = array ( "valor" => 'R', "mostrar" => 'Prorrateado');
		return $combo_tipo_unidad;											
	}

	function Cargar_combo_Corresponsales(){

		$conexion = $this ->Conexion;

		$corresponsales =$conexion->query("SELECT id,nombre FROM hit_corresponsales WHERE visible = 'S' ORDER BY NOMBRE");
		$numero_filas = $corresponsales->num_rows;

		if ($corresponsales == FALSE){
			echo('Error en la consulta');
			$corresponsales->close();
			$conexion->close();
			exit;
		}

		$combo_corresponsales = array();
		$combo_corresponsales[-1] = array ( "id" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$corresponsales->data_seek($num_fila);
			$fila = $corresponsales->fetch_assoc();
			array_push($combo_corresponsales,$fila);
		}

		//Liberar Memoria usada por la consulta
		$corresponsales->close();

		return $combo_corresponsales;											
	}

	function Cargar_combo_Proveedores(){

		$conexion = $this ->Conexion;

		$proveedores =$conexion->query("SELECT id,nombre FROM hit_proveedores WHERE visible = 'S' ORDER BY NOMBRE");
		$numero_filas = $proveedores->num_rows;

		if ($proveedores == FALSE){
			echo('Error en la consulta');
			$proveedores->close();
			$conexion->close();
			exit;
		}

		$combo_proveedores = array();
		$combo_proveedores[-1] = array ( "id" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$proveedores->data_seek($num_fila);
			$fila = $proveedores->fetch_assoc();
			array_push($combo_proveedores,$fila);
		}

		//Liberar Memoria usada por la consulta
		$proveedores->close();

		return $combo_proveedores;											
	}

	function Cargar_combo_Entidades(){

		$conexion = $this ->Conexion;

		$entidades =$conexion->query("SELECT codigo,nombre FROM hit_entidades ORDER BY NOMBRE");
		$numero_filas = $entidades->num_rows;

		if ($entidades == FALSE){
			echo('Error en la consulta');
			$entidades->close();
			$conexion->close();
			exit;
		}

		$combo_entidades = array();
		$combo_entidades[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$entidades->data_seek($num_fila);
			$fila = $entidades->fetch_assoc();
			array_push($combo_entidades,$fila);
		}

		//Liberar Memoria usada por la consulta
		$entidades->close();

		return $combo_entidades;											
	}

	function Cargar_combo_Tipo_Minorista(){

		$combo_tipo_minorista = array();
		$combo_tipo_minorista[0] = array ( "valor" => 'M', "mostrar" => 'Minorista');
		$combo_tipo_minorista[1] = array ( "valor" => 'Y', "mostrar" => 'Mayorista');
		$combo_tipo_minorista[2] = array ( "valor" => 'I', "mostrar" => 'Mayorista/Minorista');
		$combo_tipo_minorista[3] = array ( "valor" => 'C', "mostrar" => 'Cliente Final');
		return $combo_tipo_minorista;											
	}

	function Cargar_combo_Tipos_Servicio(){

		$conexion = $this ->Conexion;

		$tipos =$conexion->query("SELECT codigo,nombre FROM hit_tipos_servicio ORDER BY NOMBRE");
		$numero_filas = $tipos->num_rows;

		if ($tipos == FALSE){
			echo('Error en la consulta');
			$tipos->close();
			$conexion->close();
			exit;
		}

		$combo_tipos = array();
		$combo_tipos[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$tipos->data_seek($num_fila);
			$fila = $tipos->fetch_assoc();
			array_push($combo_tipos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$tipos->close();

		return $combo_tipos;											
	}

	function Cargar_combo_Tipos_Servicio_Producto(){

		$combo_tipo_minorista = array();		
		$combo_tipo_minorista[-1] = array ( "valor" => '', "mostrar" => '');
		$combo_tipo_minorista[0] = array ( "valor" => 'I', "mostrar" => 'Incluido');
		$combo_tipo_minorista[1] = array ( "valor" => 'O', "mostrar" => 'Opcional');
		return $combo_tipo_minorista;											
	}

	function Cargar_combo_Transportes(){

		$conexion = $this ->Conexion;

		$transportes =$conexion->query("SELECT cia, concat_ws(' - ',nombre,cia) nombre FROM hit_transportes ORDER BY NOMBRE");
		$numero_filas = $transportes->num_rows;

		if ($transportes == FALSE){
			echo('Error en la consulta');
			$transportes->close();
			$conexion->close();
			exit;
		}

		$combo_transportes = array();
		$combo_transportes[-1] = array ( "cia" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$transportes->data_seek($num_fila);
			$fila = $transportes->fetch_assoc();
			array_push($combo_transportes,$fila);
		}

		//Liberar Memoria usada por la consulta
		$transportes->close();

		return $combo_transportes;											
	}

	function Cargar_combo_Transportes_Id(){

		$conexion = $this ->Conexion;

		$transportes =$conexion->query("SELECT id cia, concat_ws(' - ',nombre,cia) nombre FROM hit_transportes ORDER BY NOMBRE");
		$numero_filas = $transportes->num_rows;

		if ($transportes == FALSE){
			echo('Error en la consulta');
			$transportes->close();
			$conexion->close();
			exit;
		}

		$combo_transportes = array();
		$combo_transportes[-1] = array ( "cia" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$transportes->data_seek($num_fila);
			$fila = $transportes->fetch_assoc();
			array_push($combo_transportes,$fila);
		}

		//Liberar Memoria usada por la consulta
		$transportes->close();

		return $combo_transportes;											
	}

	function Cargar_combo_Gds(){

		$combo_gds = array();
		$combo_gds[0] = array ( "valor" => 'A', "mostrar" => 'Amadeus');
		$combo_gds[1] = array ( "valor" => 'O', "mostrar" => 'Otros');
		$combo_gds[2] = array ( "valor" => 'S', "mostrar" => 'Sin GDS');
		return $combo_gds;											
	}

	function Cargar_combo_Tipos_trayecto(){

		$combo_tipos_trayecto = array();
		$combo_tipos_trayecto[0] = array ( "valor" => '', "mostrar" => '');
		$combo_tipos_trayecto[1] = array ( "valor" => 'O', "mostrar" => 'One way');
		$combo_tipos_trayecto[2] = array ( "valor" => 'R', "mostrar" => 'Round trip');
		$combo_tipos_trayecto[3] = array ( "valor" => 'J', "mostrar" => 'Open jaw');
		return $combo_tipos_trayecto;											
	}

	function Cargar_combo_Tipos_trayecto_operando(){

		$combo_tipos_trayecto = array();
		$combo_tipos_trayecto[0] = array ( "valor" => 'I', "mostrar" => 'Ida');
		$combo_tipos_trayecto[1] = array ( "valor" => 'V', "mostrar" => 'Vuelta');
		return $combo_tipos_trayecto;											
	}

	function Cargar_combo_Tipo_coste_plazas_fijas(){

		$combo_Tipo_coste_plazas_fijas = array();
		$combo_Tipo_coste_plazas_fijas[0] = array ( "valor" => 'C', "mostrar" => 'Cupo Completo');
		$combo_Tipo_coste_plazas_fijas[1] = array ( "valor" => 'P', "mostrar" => 'Por Pax');

		return $combo_Tipo_coste_plazas_fijas;											
	}


	function Cargar_combo_Acuerdos_UnAlojamiento($id){

		$conexion = $this ->Conexion;

		$acuerdos =$conexion->query("select a.acuerdo, concat_ws(' - ',a.acuerdo, substr(a.descripcion,1,25)) descripcion 
		from hit_alojamientos_acuerdos a, hit_alojamientos h where a.ID = h.ID and a.id = '".$id."' order by acuerdo");
		$numero_filas = $acuerdos->num_rows;

		if ($acuerdos == FALSE){
			echo('Error en la consulta');
			$acuerdos->close();
			$conexion->close();
			exit;
		}

		$combo_acuerdos = array();
		$combo_acuerdos[-1] = array ( "acuerdo" => null, "descripcion" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$acuerdos->data_seek($num_fila);
			$fila = $acuerdos->fetch_assoc();
			array_push($combo_acuerdos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$acuerdos->close();

		return $combo_acuerdos;											
	}

	function Cargar_combo_Acuerdos_UnaCia($cia){

		$conexion = $this ->Conexion;

		$acuerdos =$conexion->query("select concat_ws('',a.acuerdo, a.subacuerdo) acuerdo, concat_ws(' - ',concat_ws('/',a.acuerdo, a.subacuerdo), substr(a.descripcion,1,25)) descripcion 
		from hit_transportes_acuerdos a where a.cia = '".$cia."' order by acuerdo");
		$numero_filas = $acuerdos->num_rows;

		if ($acuerdos == FALSE){
			echo('Error en la consulta');
			$acuerdos->close();
			$conexion->close();
			exit;
		}

		$combo_acuerdos = array();
		$combo_acuerdos[-1] = array ( "acuerdo" => null, "descripcion" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$acuerdos->data_seek($num_fila);
			$fila = $acuerdos->fetch_assoc();
			array_push($combo_acuerdos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$acuerdos->close();

		return $combo_acuerdos;											
	}

	function Cargar_combo_Servicios_UnProveedor($buscar_id, $buscar_id_prove, $buscar_codigo, $buscar_tipo, $buscar_ciudad){

		$conexion = $this ->Conexion;


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


		$servicios =$conexion->query("select codigo, substr(nombre,1,25) nombre 
		FROM hit_servicios ".$CADENA_BUSCAR." order by nombre");
		$numero_filas = $servicios->num_rows;

		if ($servicios == FALSE){
			echo('Error en la consulta');
			$servicios->close();
			$conexion->close();
			exit;
		}

		$combo_servicios = array();
		$combo_servicios[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$servicios->data_seek($num_fila);
			$fila = $servicios->fetch_assoc();
			array_push($combo_servicios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$servicios->close();

		return $combo_servicios;											
	}

	function Cargar_combo_Servicios_UnsoloProveedor($buscar_id){

		$conexion = $this ->Conexion;

		$servicios =$conexion->query("select codigo, substr(nombre,1,25) nombre 
		FROM hit_servicios WHERE ID_PROVEEDOR = '".$buscar_id."' order by nombre");
		$numero_filas = $servicios->num_rows;

		if ($servicios == FALSE){
			echo('Error en la consulta');
			$servicios->close();
			$conexion->close();
			exit;
		}

		$combo_servicios = array();
		$combo_servicios[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$servicios->data_seek($num_fila);
			$fila = $servicios->fetch_assoc();
			array_push($combo_servicios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$servicios->close();

		return $combo_servicios;											
	}

	function Cargar_combo_Folletos(){

		$conexion = $this ->Conexion;

		$folletos =$conexion->query("SELECT codigo, nombre FROM hit_producto_folletos ORDER BY NOMBRE");
		$numero_filas = $folletos->num_rows;

		if ($folletos == FALSE){
			echo('Error en la consulta');
			$folletos->close();
			$conexion->close();
			exit;
		}

		$combo_folletos = array();
		$combo_folletos[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$folletos->data_seek($num_fila);
			$fila = $folletos->fetch_assoc();
			array_push($combo_folletos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$folletos->close();

		return $combo_folletos;											
	}

	function Cargar_combo_Cuadros($folleto){

		$conexion = $this ->Conexion;

		$cuadros =$conexion->query("SELECT c.clave clave, concat(c.folleto,'  -  ',c.nombre) nombre FROM hit_producto_cuadros c WHERE substr(c.nombre,1,1) <> ':' and c.folleto = '".$folleto."' ORDER BY c.folleto, c.NOMBRE");
		$numero_filas = $cuadros->num_rows;

		if ($cuadros == FALSE){
			echo('Error en la consulta');
			$cuadros->close();
			$conexion->close();
			exit;
		}

		$combo_cuadros = array();
		$combo_cuadros[-1] = array ( "clave" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$cuadros->data_seek($num_fila);
			$fila = $cuadros->fetch_assoc();
			array_push($combo_cuadros,$fila);
		}

		//Liberar Memoria usada por la consulta
		$cuadros->close();

		return $combo_cuadros;											
	}

	function Cargar_combo_Cuadros_Teletipo(){

		$conexion = $this ->Conexion;

		$cuadros =$conexion->query("SELECT c.clave clave, concat(c.folleto,'  -  ',c.nombre) nombre FROM hit_producto_cuadros c WHERE substr(c.nombre,1,1) <> ':'  ORDER BY c.folleto, c.NOMBRE");
		$numero_filas = $cuadros->num_rows;

		if ($cuadros == FALSE){
			echo('Error en la consulta');
			$cuadros->close();
			$conexion->close();
			exit;
		}

		$combo_cuadros = array();
		$combo_cuadros[-1] = array ( "clave" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$cuadros->data_seek($num_fila);
			$fila = $cuadros->fetch_assoc();
			array_push($combo_cuadros,$fila);
		}

		//Liberar Memoria usada por la consulta
		$cuadros->close();

		return $combo_cuadros;											
	}

	function Cargar_combo_Destinos_Grupos(){

		$conexion = $this ->Conexion;

		$destinos =$conexion->query("SELECT codigo, nombre FROM hit_destinos_grupos ORDER BY NOMBRE");
		$numero_filas = $destinos->num_rows;

		if ($destinos == FALSE){
			echo('Error en la consulta');
			$destinos->close();
			$conexion->close();
			exit;
		}

		$combo_destinos = array();
		$combo_destinos[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$destinos->data_seek($num_fila);
			$fila = $destinos->fetch_assoc();
			array_push($combo_destinos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$destinos->close();

		return $combo_destinos;											
	}

	function Cargar_combo_Destinos(){

		$conexion = $this ->Conexion;

		$destinos =$conexion->query("SELECT codigo, concat(pais,' - ',nombre) nombre FROM hit_destinos where visible = 'S' ORDER BY PAIS, NOMBRE");
		$numero_filas = $destinos->num_rows;

		if ($destinos == FALSE){
			echo('Error en la consulta');
			$destinos->close();
			$conexion->close();
			exit;
		}

		$combo_destinos = array();
		$combo_destinos[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$destinos->data_seek($num_fila);
			$fila = $destinos->fetch_assoc();
			array_push($combo_destinos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$destinos->close();

		return $combo_destinos;											
	}

	function Cargar_combo_Zonas(){

		/*$Cargar_combo_Zonas = array();
		$Cargar_combo_Zonas[0] = array ( "valor" => 'SINZONA', "mostrar" => 'Sin zona');
		$Cargar_combo_Zonas[1] = array ( "valor" => 'TCINORTE', "mostrar" => 'Tenerife Norte');
		$Cargar_combo_Zonas[2] = array ( "valor" => 'TCISUR', "mostrar" => 'Tenerife Sur');
		return $Cargar_combo_Zonas;		*/

		$conexion = $this ->Conexion;

		$zonas =$conexion->query("SELECT codigo valor, nombre mostrar FROM hit_zonas where visible = 'S' ORDER BY NOMBRE");
		$numero_filas = $zonas->num_rows;

		if ($zonas == FALSE){
			echo('Error en la consulta');
			$zonas->close();
			$conexion->close();
			exit;
		}

		$combo_zonas = array();
		$combo_zonas[-1] = array ( "valor" => null, "mostrar" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$zonas->data_seek($num_fila);
			$fila = $zonas->fetch_assoc();
			array_push($combo_zonas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$zonas->close();

		return $combo_zonas;



	}	
	
	function Cargar_combo_Reservas($buscar_localizador, $buscar_referencia, $buscar_referencia_agencia, $buscar_folleto, $buscar_cuadro, $buscar_fecha_salida, $buscar_fecha_reserva, $buscar_minorista, $buscar_oficina, $buscar_telefono_oficina, $buscar_nombre){

		$conexion = $this ->Conexion;


		/*if($buscar_localizador != null){
			$CADENA_BUSCAR = " AND r.localizador = '".$buscar_localizador."'";
		}else*/if($buscar_referencia != null){
			$CADENA_BUSCAR = " AND r.referencia = '".$buscar_referencia."'";
		}elseif($buscar_referencia_agencia != null){
			$CADENA_BUSCAR = " AND r.referencia_agencia = '".$buscar_referencia_agencia."'";
		}elseif($buscar_folleto != null){
			$cuadr = " AND r.cuadro = '".$buscar_cuadro."'";
			$salid = " AND r.fecha_salida = '".date("Y-m-d",strtotime($buscar_fecha_salida))."'";
			$reserv = " AND r.fecha_reserva = '".date("Y-m-d",strtotime($buscar_fecha_reserva))."'";
			$minor = " AND m.nombre like '%".$buscar_minorista."%'";
			$ofic = " AND r.oficina = '".$buscar_oficina."'";
			$telef = " AND o.telefono = '".$buscar_telefono_oficina."'";		
			$nombre = " AND rp.apellido like '%".$buscar_nombre."%'";
			$CADENA_BUSCAR = " AND r.folleto = '".$buscar_folleto."'";
			if($buscar_cuadro != null){
				$CADENA_BUSCAR .= $cuadr;	
			}
			if($buscar_fecha_salida != null){
				$CADENA_BUSCAR .= $salid;	
			}
			if($buscar_fecha_reserva != null){
				$CADENA_BUSCAR .= $reserv;	
			}
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $minor;	
			}
			if($buscar_oficina != null){
				$CADENA_BUSCAR .= $ofic;	
			}
			if($buscar_telefono_oficina != null){
				$CADENA_BUSCAR .= $telef;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_cuadro != null){
			$salid = " AND r.fecha_salida = '".date("Y-m-d",strtotime($buscar_fecha_salida))."'";
			$reserv = " AND r.fecha_reserva = '".date("Y-m-d",strtotime($buscar_fecha_reserva))."'";
			$minor = " AND m.nombre like '%".$buscar_minorista."%'";
			$ofic = " AND r.oficina = '".$buscar_oficina."'";
			$telef = " AND o.telefono = '".$buscar_telefono_oficina."'";		
			$nombre = " AND rp.apellido like '%".$buscar_nombre."%'";
			$CADENA_BUSCAR = " AND r.cuadro = '".$buscar_cuadro."'";
			if($buscar_fecha_salida != null){
				$CADENA_BUSCAR .= $habit;	
			}
			if($buscar_fecha_reserva != null){
				$CADENA_BUSCAR .= $reserv;	
			}
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $minor;	
			}
			if($buscar_oficina != null){
				$CADENA_BUSCAR .= $ofic;	
			}
			if($buscar_telefono_oficina != null){
				$CADENA_BUSCAR .= $telef;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_fecha_salida != null){
			$reserv = " AND r.fecha_reserva = '".date("Y-m-d",strtotime($buscar_fecha_reserva))."'";
			$minor = " AND m.nombre like '%".$buscar_minorista."%'";
			$ofic = " AND r.oficina = '".$buscar_oficina."'";
			$telef = " AND o.telefono = '".$buscar_telefono_oficina."'";		
			$nombre = " AND rp.apellido like '%".$buscar_nombre."%'";
			$CADENA_BUSCAR = " and r.fecha_salida = '".date("Y-m-d",strtotime($buscar_fecha_salida))."'";
			if($buscar_fecha_reserva != null){
				$CADENA_BUSCAR .= $reserv;	
			}
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $minor;	
			}
			if($buscar_oficina != null){
				$CADENA_BUSCAR .= $ofic;	
			}
			if($buscar_telefono_oficina != null){
				$CADENA_BUSCAR .= $telef;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_fecha_reserva != null){
			$minor = " AND m.nombre like '%".$buscar_minorista."%'";
			$ofic = " AND r.oficina = '".$buscar_oficina."'";
			$telef = " AND o.telefono = '".$buscar_telefono_oficina."'";		
			$nombre = " AND rp.apellido like '%".$buscar_nombre."%'";
			$CADENA_BUSCAR = " and r.fecha_reserva = '".date("Y-m-d",strtotime($buscar_fecha_reserva))."'";
			if($buscar_minorista != null){
				$CADENA_BUSCAR .= $minor;	
			}
			if($buscar_oficina != null){
				$CADENA_BUSCAR .= $ofic;	
			}
			if($buscar_telefono_oficina != null){
				$CADENA_BUSCAR .= $telef;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_minorista != null){
			$ofic = " AND r.oficina = '".$buscar_oficina."'";
			$telef = " AND o.telefono = '".$buscar_telefono_oficina."'";		
			$nombre = " AND rp.apellido like '%".$buscar_nombre."%'";
			$CADENA_BUSCAR = " and m.nombre like '%".$buscar_minorista."%'";
			if($buscar_oficina != null){
				$CADENA_BUSCAR .= $ofic;	
			}
			if($buscar_telefono_oficina != null){
				$CADENA_BUSCAR .= $telef;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_oficina != null){
			$telef = " AND o.telefono = '".$buscar_telefono_oficina."'";		
			$nombre = " AND rp.apellido like '%".$buscar_nombre."%'";
			$CADENA_BUSCAR = " and r.oficina = '".$buscar_oficina."'";
			if($buscar_telefono_oficina != null){
				$CADENA_BUSCAR .= $telef;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_telefono_oficina != null){
			$nombre = " AND rp.apellido like '%".$buscar_nombre."%'";
			$CADENA_BUSCAR = " and o.telefono = '".$buscar_telefono_oficina."'";
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_telefono_oficina != null){
			$nombre = " AND rp.apellido like '%".$buscar_nombre."%'";
			$CADENA_BUSCAR = " and o.telefono = '".$buscar_telefono_oficina."'";
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " and rp.apellido like '%".$buscar_nombre."%'";
		}else{
			$CADENA_BUSCAR = " and r.localizador = 0";    
  		}


		$reservas =$conexion->query("select 
				r.localizador, concat(
				rpad ((case r.situacion when 'P' then 'Pendiente' when 'F' then 'Firme' when 'A' then 'Anulada' else 'Pendiente' end),12,'.') ,
				
				DATE_FORMAT(r.fecha_salida, '%d-%m-%Y'),' - ',r.localizador,' - ',r.cuadro,' - ',rpad (m.nombre, 25,'.'),' ',upper(concat(rp.APELLIDO,', ',rp.nombre))) nombre
			from hit_reservas r, hit_minoristas m, hit_oficinas o, hit_producto_folletos pf, hit_producto_cuadros pc, hit_reservas_pasajeros rp
			where
				r.MINORISTA = m.ID	and r.MINORISTA = o.ID and r.OFICINA = o.OFICINA and r.FOLLETO = pf.codigo and r.FOLLETO = pc.folleto	and r.CUADRO = pc.cuadro and r.LOCALIZADOR = rp.LOCALIZADOR and rp.NUMERO = (select min(rp2.numero) from hit_reservas_pasajeros rp2 where rp2.LOCALIZADOR = r.LOCALIZADOR)
				 ".$CADENA_BUSCAR." ORDER BY r.fecha_salida");
		$numero_filas = $reservas->num_rows;

		if ($reservas == FALSE){
			echo('Error en la consulta');
			$reservas->close();
			$conexion->close();
			exit;
		}

		$combo_reservas = array();
		$combo_reservas[-1] = array ( "localizador" => null, "nombre" => 'Estado&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Salida&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Loc&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cuadro&nbsp;&nbsp;&nbsp;Agencia&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pasajero');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$reservas->data_seek($num_fila);
			$fila = $reservas->fetch_assoc();
			array_push($combo_reservas,$fila);
		}

		//Liberar Memoria usada por la consulta
		$reservas->close();

		return $combo_reservas;											
	}



	function Cargar_combo_Agencias($buscar_agencia_minorista, $buscar_agencia_oficina, $buscar_agencia_direccion, $buscar_agencia_telefono){

		$conexion = $this ->Conexion;

		if($buscar_agencia_minorista != null){
			$ofic = " AND o.oficina = '".$buscar_agencia_oficina."'";		
			$direc = " AND o.direccion like '%".$buscar_agencia_direccion."%'";
			$telef = " AND o.telefono = '".$buscar_agencia_telefono."'";
			$CADENA_BUSCAR = " and m.nombre like '%".$buscar_agencia_minorista."%'";
			if($buscar_agencia_telefono != null){
				$CADENA_BUSCAR .= $ofic;	
			}
			if($buscar_agencia_direccion != null){
				$CADENA_BUSCAR .= $direc;	
			}
			if($buscar_agencia_telefono != null){
				$CADENA_BUSCAR .= $telef;	
			}

		}elseif($buscar_agencia_oficina != null){
			$direc = " AND o.direccion like '%".$buscar_agencia_direccion."%'";
			$telef = " AND o.telefono = '".$buscar_agencia_telefono."'";
			$CADENA_BUSCAR = " and o.oficina = '".$buscar_agencia_oficina."'";
			if($buscar_agencia_direccion != null){
				$CADENA_BUSCAR .= $direc;	
			}
			if($buscar_agencia_telefono != null){
				$CADENA_BUSCAR .= $telef;	
			}
		}elseif($buscar_agencia_direccion != null){
			$telef = " AND o.telefono = '".$buscar_agencia_telefono."'";
			$CADENA_BUSCAR = " and o.direccion like '%".$buscar_agencia_direccion."%'";
			if($buscar_agencia_telefono != null){
				$CADENA_BUSCAR .= $telef;	
			}
		}elseif($buscar_agencia_telefono != null){
			$CADENA_BUSCAR = " and o.telefono = '".$buscar_agencia_telefono."'";
		}else{
			$CADENA_BUSCAR = " and o.clave = 0";    
  		}

		$agencias =$conexion->query("SELECT o.clave, concat(m.nombre,' - ',o.localidad,' - ',o.direccion,' - ',o.oficina,' - ',o.telefono) nombre
		FROM hit_oficinas o, hit_minoristas m where m.id = o.id ".$CADENA_BUSCAR." ORDER BY m.nombre, o.localidad, o.direccion,o.oficina");
		$numero_filas = $agencias->num_rows;

		if ($agencias == FALSE){
			echo('Error en la consulta');
			$agencias->close();
			$conexion->close();
			exit;
		}

		$combo_agencias = array();
		$combo_agencias[-1] = array ( "clave" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$agencias->data_seek($num_fila);
			$fila = $agencias->fetch_assoc();
			array_push($combo_agencias,$fila);
		}

		//Liberar Memoria usada por la consulta
		$agencias->close();

		return $combo_agencias;											
	}

	function Cargar_combo_Pasajeros_sexo(){

		$combo_pasajeros_sexo = array();
		$combo_pasajeros_sexo[1] = array ( "valor" => '', "mostrar" => '');
		$combo_pasajeros_sexo[2] = array ( "valor" => 'H', "mostrar" => 'Mr.');
		$combo_pasajeros_sexo[3] = array ( "valor" => 'M', "mostrar" => 'Ms.');
		return $combo_pasajeros_sexo;											
	}

	function Cargar_combo_Pasajeros_tipo(){

		$combo_pasajeros_tipo = array();
		$combo_pasajeros_tipo[0] = array ( "valor" => 'A', "mostrar" => 'Adulto');
		$combo_pasajeros_tipo[1] = array ( "valor" => 'N', "mostrar" => 'Niño');
		$combo_pasajeros_tipo[2] = array ( "valor" => 'B', "mostrar" => 'Bebe');
		$combo_pasajeros_tipo[3] = array ( "valor" => 'J', "mostrar" => 'Jubilado');	
		$combo_pasajeros_tipo[4] = array ( "valor" => 'V', "mostrar" => 'Novios');
		$combo_pasajeros_tipo[5] = array ( "valor" => 'G', "mostrar" => 'Agente');
		return $combo_pasajeros_tipo;											
	}

	function Cargar_combo_Pasajeros_condiciones_acuerdos(){

		$combo_pasajeros_tipo = array();
		$combo_pasajeros_tipo[0] = array ( "valor" => '', "mostrar" => '');
		$combo_pasajeros_tipo[1] = array ( "valor" => 'N1', "mostrar" => 'Dcto. Primer Niño');
		$combo_pasajeros_tipo[2] = array ( "valor" => 'N2', "mostrar" => 'Dcto. Segundo Niño');
		$combo_pasajeros_tipo[3] = array ( "valor" => 'NA', "mostrar" => 'Dcto. Niño con Adulto');
		$combo_pasajeros_tipo[4] = array ( "valor" => '3P', "mostrar" => 'Dcto. Tercer Pax');	
		$combo_pasajeros_tipo[5] = array ( "valor" => '4P', "mostrar" => 'Dcto. Cuarto Pax');
		$combo_pasajeros_tipo[6] = array ( "valor" => '5P', "mostrar" => 'Dcto. Quinto Pax');
		$combo_pasajeros_tipo[7] = array ( "valor" => '6P', "mostrar" => 'Dcto. Sexto Pax');
		$combo_pasajeros_tipo[8] = array ( "valor" => 'SE', "mostrar" => 'Dcto. Mayores de');
		$combo_pasajeros_tipo[9] = array ( "valor" => 'AT', "mostrar" => 'Dcto. Antelación');
		$combo_pasajeros_tipo[10] = array ( "valor" => 'IN', "mostrar" => 'Spto. Individual');
		$combo_pasajeros_tipo[11] = array ( "valor" => 'SG', "mostrar" => 'Spto. Cena de Gala');
		$combo_pasajeros_tipo[12] = array ( "valor" => 'RN', "mostrar" => 'Spto. Régimen Niños');
		$combo_pasajeros_tipo[13] = array ( "valor" => 'R1', "mostrar" => 'Spto. Régimen Primer Niño');
		$combo_pasajeros_tipo[14] = array ( "valor" => 'R2', "mostrar" => 'Spto. Régimen Segundo Niño');
		return $combo_pasajeros_tipo;											
	}

	function Cargar_combo_Pasajeros_documento_tipo(){

		$combo_pasajeros_documento_tipo = array();
		$combo_pasajeros_documento_tipo[0] = array ( "valor" => 'D', "mostrar" => 'Dni');
		$combo_pasajeros_documento_tipo[1] = array ( "valor" => 'P', "mostrar" => 'Pasaporte');
		return $combo_pasajeros_documento_tipo;											
	}

	function Cargar_combo_Pasajeros_edad_ninos(){

		$Cargar_combo_Pasajeros_edad_ninos = array();
		$Cargar_combo_Pasajeros_edad_ninos[0] = array ( "valor" => '0', "mostrar" => '0');
		$Cargar_combo_Pasajeros_edad_ninos[1] = array ( "valor" => '1', "mostrar" => '1');
		$Cargar_combo_Pasajeros_edad_ninos[2] = array ( "valor" => '2', "mostrar" => '2');
		$Cargar_combo_Pasajeros_edad_ninos[3] = array ( "valor" => '3', "mostrar" => '3');
		$Cargar_combo_Pasajeros_edad_ninos[4] = array ( "valor" => '4', "mostrar" => '4');
		$Cargar_combo_Pasajeros_edad_ninos[5] = array ( "valor" => '5', "mostrar" => '5');
		$Cargar_combo_Pasajeros_edad_ninos[6] = array ( "valor" => '6', "mostrar" => '6');
		$Cargar_combo_Pasajeros_edad_ninos[7] = array ( "valor" => '7', "mostrar" => '7');
		$Cargar_combo_Pasajeros_edad_ninos[8] = array ( "valor" => '8', "mostrar" => '8');
		$Cargar_combo_Pasajeros_edad_ninos[9] = array ( "valor" => '9', "mostrar" => '9');
		$Cargar_combo_Pasajeros_edad_ninos[10] = array ( "valor" => '10', "mostrar" => '10');
		$Cargar_combo_Pasajeros_edad_ninos[11] = array ( "valor" => '11', "mostrar" => '11');
		$Cargar_combo_Pasajeros_edad_ninos[12] = array ( "valor" => '12', "mostrar" => '12');


		return $Cargar_combo_Pasajeros_edad_ninos;											
	}

	function Cargar_combo_Pasajeros_edad_adultos(){

		$Cargar_combo_Pasajeros_edad_adultos = array();

		$Cargar_combo_Pasajeros_edad_adultos[0] = array ( "valor" => '17', "mostrar" => '17 Años');
		$Cargar_combo_Pasajeros_edad_adultos[1] = array ( "valor" => '18', "mostrar" => '18 Años');
		$Cargar_combo_Pasajeros_edad_adultos[2] = array ( "valor" => '19', "mostrar" => '19 Años');
		$Cargar_combo_Pasajeros_edad_adultos[3] = array ( "valor" => '20', "mostrar" => '20 Años');
		$Cargar_combo_Pasajeros_edad_adultos[4] = array ( "valor" => '21', "mostrar" => '21 Años');
		$Cargar_combo_Pasajeros_edad_adultos[5] = array ( "valor" => '22', "mostrar" => '22 Años');
		$Cargar_combo_Pasajeros_edad_adultos[6] = array ( "valor" => '23', "mostrar" => '23 Años');
		$Cargar_combo_Pasajeros_edad_adultos[7] = array ( "valor" => '24', "mostrar" => '24 Años');
		$Cargar_combo_Pasajeros_edad_adultos[8] = array ( "valor" => '25', "mostrar" => '25 Años');
		$Cargar_combo_Pasajeros_edad_adultos[9] = array ( "valor" => '26', "mostrar" => '26 Años');
		$Cargar_combo_Pasajeros_edad_adultos[10] = array ( "valor" => '27', "mostrar" => '27 Años');
		$Cargar_combo_Pasajeros_edad_adultos[11] = array ( "valor" => '28', "mostrar" => '28 Años');
		$Cargar_combo_Pasajeros_edad_adultos[12] = array ( "valor" => '29', "mostrar" => '29 Años');
		$Cargar_combo_Pasajeros_edad_adultos[13] = array ( "valor" => '30', "mostrar" => '30 Años');
		$Cargar_combo_Pasajeros_edad_adultos[14] = array ( "valor" => '31', "mostrar" => '31 Años');
		$Cargar_combo_Pasajeros_edad_adultos[15] = array ( "valor" => '32', "mostrar" => '32 Años');
		$Cargar_combo_Pasajeros_edad_adultos[16] = array ( "valor" => '33', "mostrar" => '33 Años');
		$Cargar_combo_Pasajeros_edad_adultos[17] = array ( "valor" => '34', "mostrar" => '34 Años');
		$Cargar_combo_Pasajeros_edad_adultos[18] = array ( "valor" => '35', "mostrar" => '35 Años');
		$Cargar_combo_Pasajeros_edad_adultos[19] = array ( "valor" => '36', "mostrar" => '36 Años');
		$Cargar_combo_Pasajeros_edad_adultos[20] = array ( "valor" => '37', "mostrar" => '37 Años');
		$Cargar_combo_Pasajeros_edad_adultos[21] = array ( "valor" => '38', "mostrar" => '38 Años');
		$Cargar_combo_Pasajeros_edad_adultos[22] = array ( "valor" => '39', "mostrar" => '39 Años');
		$Cargar_combo_Pasajeros_edad_adultos[23] = array ( "valor" => '40', "mostrar" => '40 Años');
		$Cargar_combo_Pasajeros_edad_adultos[24] = array ( "valor" => '41', "mostrar" => '41 Años');
		$Cargar_combo_Pasajeros_edad_adultos[25] = array ( "valor" => '42', "mostrar" => '42 Años');
		$Cargar_combo_Pasajeros_edad_adultos[26] = array ( "valor" => '43', "mostrar" => '43 Años');
		$Cargar_combo_Pasajeros_edad_adultos[27] = array ( "valor" => '44', "mostrar" => '44 Años');
		$Cargar_combo_Pasajeros_edad_adultos[28] = array ( "valor" => '45', "mostrar" => '45 Años');
		$Cargar_combo_Pasajeros_edad_adultos[29] = array ( "valor" => '46', "mostrar" => '46 Años');
		$Cargar_combo_Pasajeros_edad_adultos[30] = array ( "valor" => '47', "mostrar" => '47 Años');
		$Cargar_combo_Pasajeros_edad_adultos[31] = array ( "valor" => '48', "mostrar" => '48 Años');
		$Cargar_combo_Pasajeros_edad_adultos[32] = array ( "valor" => '49', "mostrar" => '49 Años');
		$Cargar_combo_Pasajeros_edad_adultos[33] = array ( "valor" => '50', "mostrar" => '50 Años');
		$Cargar_combo_Pasajeros_edad_adultos[34] = array ( "valor" => '51', "mostrar" => '51 Años');
		$Cargar_combo_Pasajeros_edad_adultos[35] = array ( "valor" => '52', "mostrar" => '52 Años');
		$Cargar_combo_Pasajeros_edad_adultos[36] = array ( "valor" => '53', "mostrar" => '53 Años');
		$Cargar_combo_Pasajeros_edad_adultos[37] = array ( "valor" => '54', "mostrar" => '54 Años');
		$Cargar_combo_Pasajeros_edad_adultos[38] = array ( "valor" => '55', "mostrar" => '55 Años');
		$Cargar_combo_Pasajeros_edad_adultos[39] = array ( "valor" => '56', "mostrar" => '56 Años');
		$Cargar_combo_Pasajeros_edad_adultos[40] = array ( "valor" => '57', "mostrar" => '57 Años');
		$Cargar_combo_Pasajeros_edad_adultos[41] = array ( "valor" => '58', "mostrar" => '58 Años');
		$Cargar_combo_Pasajeros_edad_adultos[42] = array ( "valor" => '59', "mostrar" => '59 Años');
		$Cargar_combo_Pasajeros_edad_adultos[43] = array ( "valor" => '60', "mostrar" => '60 Años');
		$Cargar_combo_Pasajeros_edad_adultos[44] = array ( "valor" => '61', "mostrar" => '61 Años');
		$Cargar_combo_Pasajeros_edad_adultos[45] = array ( "valor" => '62', "mostrar" => '62 Años');
		$Cargar_combo_Pasajeros_edad_adultos[46] = array ( "valor" => '63', "mostrar" => '63 Años');
		$Cargar_combo_Pasajeros_edad_adultos[47] = array ( "valor" => '64', "mostrar" => '64 Años');
		$Cargar_combo_Pasajeros_edad_adultos[48] = array ( "valor" => '65', "mostrar" => '65 Años');
		$Cargar_combo_Pasajeros_edad_adultos[49] = array ( "valor" => '66', "mostrar" => '66 Años');
		$Cargar_combo_Pasajeros_edad_adultos[50] = array ( "valor" => '67', "mostrar" => '67 Años');
		$Cargar_combo_Pasajeros_edad_adultos[51] = array ( "valor" => '68', "mostrar" => '68 Años');
		$Cargar_combo_Pasajeros_edad_adultos[52] = array ( "valor" => '69', "mostrar" => '69 Años');
		$Cargar_combo_Pasajeros_edad_adultos[53] = array ( "valor" => '70', "mostrar" => '70 Años');
		$Cargar_combo_Pasajeros_edad_adultos[54] = array ( "valor" => '71', "mostrar" => '71 Años');
		$Cargar_combo_Pasajeros_edad_adultos[55] = array ( "valor" => '72', "mostrar" => '72 Años');
		$Cargar_combo_Pasajeros_edad_adultos[56] = array ( "valor" => '73', "mostrar" => '73 Años');
		$Cargar_combo_Pasajeros_edad_adultos[57] = array ( "valor" => '74', "mostrar" => '74 Años');
		$Cargar_combo_Pasajeros_edad_adultos[58] = array ( "valor" => '75', "mostrar" => '75 Años');
		$Cargar_combo_Pasajeros_edad_adultos[59] = array ( "valor" => '76', "mostrar" => '76 Años');
		$Cargar_combo_Pasajeros_edad_adultos[60] = array ( "valor" => '77', "mostrar" => '77 Años');
		$Cargar_combo_Pasajeros_edad_adultos[61] = array ( "valor" => '78', "mostrar" => '78 Años');
		$Cargar_combo_Pasajeros_edad_adultos[62] = array ( "valor" => '79', "mostrar" => '79 Años');	
		$Cargar_combo_Pasajeros_edad_adultos[63] = array ( "valor" => '80', "mostrar" => '80 Años');
		$Cargar_combo_Pasajeros_edad_adultos[64] = array ( "valor" => '81', "mostrar" => '81 Años');
		$Cargar_combo_Pasajeros_edad_adultos[65] = array ( "valor" => '82', "mostrar" => '82 Años');
		$Cargar_combo_Pasajeros_edad_adultos[66] = array ( "valor" => '83', "mostrar" => '83 Años');
		$Cargar_combo_Pasajeros_edad_adultos[67] = array ( "valor" => '84', "mostrar" => '84 Años');
		$Cargar_combo_Pasajeros_edad_adultos[68] = array ( "valor" => '85', "mostrar" => '85 Años');
		$Cargar_combo_Pasajeros_edad_adultos[69] = array ( "valor" => '86', "mostrar" => '86 Años');
		$Cargar_combo_Pasajeros_edad_adultos[70] = array ( "valor" => '87', "mostrar" => '87 Años');
		$Cargar_combo_Pasajeros_edad_adultos[71] = array ( "valor" => '88', "mostrar" => '88 Años');
		$Cargar_combo_Pasajeros_edad_adultos[72] = array ( "valor" => '89', "mostrar" => '89 Años');
		$Cargar_combo_Pasajeros_edad_adultos[73] = array ( "valor" => '90', "mostrar" => '90 Años');
		$Cargar_combo_Pasajeros_edad_adultos[74] = array ( "valor" => '91', "mostrar" => '91 Años');
		$Cargar_combo_Pasajeros_edad_adultos[75] = array ( "valor" => '92', "mostrar" => '92 Años');
		$Cargar_combo_Pasajeros_edad_adultos[76] = array ( "valor" => '93', "mostrar" => '93 Años');
		$Cargar_combo_Pasajeros_edad_adultos[77] = array ( "valor" => '94', "mostrar" => '94 Años');
		$Cargar_combo_Pasajeros_edad_adultos[78] = array ( "valor" => '95', "mostrar" => '95 Años');
		$Cargar_combo_Pasajeros_edad_adultos[79] = array ( "valor" => '96', "mostrar" => '96 Años');
		$Cargar_combo_Pasajeros_edad_adultos[80] = array ( "valor" => '97', "mostrar" => '97 Años');
		$Cargar_combo_Pasajeros_edad_adultos[81] = array ( "valor" => '98', "mostrar" => '98 Años');
		$Cargar_combo_Pasajeros_edad_adultos[82] = array ( "valor" => '99', "mostrar" => '99 Años');
		return $Cargar_combo_Pasajeros_edad_adultos;											
	}

	
	function Cargar_combo_Regimen(){

		$combo_regimen = array();
		$combo_regimen[0] = array ( "valor" => 'SA', "mostrar" => 'SA');
		$combo_regimen[1] = array ( "valor" => 'AD', "mostrar" => 'AD');
		$combo_regimen[2] = array ( "valor" => 'MP', "mostrar" => 'MP');
		$combo_regimen[3] = array ( "valor" => 'PC', "mostrar" => 'PC');
		$combo_regimen[4] = array ( "valor" => 'TI', "mostrar" => 'TI');
		return $combo_regimen;											
	}

	function Cargar_combo_Regimen_Producto(){

		$combo_regimen = array();
		$combo_regimen[0] = array ( "valor" => 'TR', "mostrar" => 'Todos');
		$combo_regimen[1] = array ( "valor" => 'SA', "mostrar" => 'SA');
		$combo_regimen[2] = array ( "valor" => 'AD', "mostrar" => 'AD');
		$combo_regimen[3] = array ( "valor" => 'MP', "mostrar" => 'MP');
		$combo_regimen[4] = array ( "valor" => 'PC', "mostrar" => 'PC');
		$combo_regimen[5] = array ( "valor" => 'TI', "mostrar" => 'TI');
		return $combo_regimen;											
	}	
	
	function Cargar_Tipo_Consulta_Alojamientos(){

		$combo_regimen = array();
		$combo_regimen[0] = array ( "valor" => 'C', "mostrar" => 'Solo cupos');
		$combo_regimen[1] = array ( "valor" => 'O', "mostrar" => 'Solo on-request');
		return $combo_regimen;											
	}

	function Cargar_Combo_Situacion_Cupos(){

		$combo_situacion_cupos = array();
		$combo_situacion_cupos[0] = array ( "" => 'OK', "mostrar" => '');
		$combo_situacion_cupos[1] = array ( "valor" => 'OK', "mostrar" => 'OK');
		$combo_situacion_cupos[2] = array ( "valor" => 'ND', "mostrar" => 'ND');
		$combo_situacion_cupos[3] = array ( "valor" => 'OR', "mostrar" => 'OR');
		$combo_situacion_cupos[4] = array ( "valor" => 'AN', "mostrar" => 'AN');
		return $combo_situacion_cupos;											
	}

	function Cargar_combo_Reservas_Aereos($localizador){

		$conexion = $this ->Conexion;

		$aereos =$conexion->query("SELECT clave, reserva FROM hit_reservas_aereos where localizador = '".$localizador."' ORDER BY ORDEN");
		$numero_filas = $aereos->num_rows;

		if ($aereos == FALSE){
			echo('Error en la consulta');
			$aereos->close();
			$conexion->close();
			exit;
		}

		$combo_aereos = array();
		$combo_aereos[-1] = array ( "clave" => null, "reserva" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$aereos->data_seek($num_fila);
			$fila = $aereos->fetch_assoc();
			array_push($combo_aereos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$aereos->close();

		return $combo_aereos;											
	}

	function Cargar_combo_Reservas_Pasajeros($localizador){

		$conexion = $this ->Conexion;

		$pasajeros =$conexion->query("SELECT numero, concat(apellido, ', ',nombre) nombre FROM hit_reservas_pasajeros where localizador = '".$localizador."' ORDER BY NUMERO");
		$numero_filas = $pasajeros->num_rows;

		if ($pasajeros == FALSE){
			echo('Error en la consulta');
			$pasajeros->close();
			$conexion->close();
			exit;
		}

		$combo_pasajeros = array();
		$combo_pasajeros[-1] = array ( "numero" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$pasajeros->data_seek($num_fila);
			$fila = $pasajeros->fetch_assoc();
			array_push($combo_pasajeros,$fila);
		}

		//Liberar Memoria usada por la consulta
		$pasajeros->close();

		return $combo_pasajeros;											
	}

	function Cargar_Combo_Tipo_Trayecto(){

		$combo_tipos_trayectos = array();
		$combo_tipos_trayectos[0] = array ( "valor" => 'RT', "mostrar" => 'Ida y Vuelta');
		$combo_tipos_trayectos[1] = array ( "valor" => 'OW', "mostrar" => 'Sencillo');
		return $combo_tipos_trayectos;											
	}

	function Cargar_Combo_Tipo_Texto(){

		$combo_tipos_trayectos = array();
		$combo_tipos_trayectos[0] = array ( "valor" => '', "mostrar" => '');
		$combo_tipos_trayectos[1] = array ( "valor" => 'D', "mostrar" => 'Descripcion');
		$combo_tipos_trayectos[2] = array ( "valor" => 'I', "mostrar" => 'Itinerario');
		$combo_tipos_trayectos[3] = array ( "valor" => 'N', "mostrar" => 'Incluido');
		$combo_tipos_trayectos[4] = array ( "valor" => 'V', "mostrar" => 'Ventajas');
		return $combo_tipos_trayectos;											
	}

	function Cargar_combo_Cuadros_Aereos_Ciudades($clave_cuadro){

		$conexion = $this ->Conexion;

		$ciudades =$conexion->query("select distinct ciudad, ciudad nombre from hit_producto_cuadros_aereos where clave_cuadro = '".$clave_cuadro."' ORDER BY ciudad");
		$numero_filas = $ciudades->num_rows;

		if ($ciudades == FALSE){
			echo('Error en la consulta');
			$ciudades->close();
			$conexion->close();
			exit;
		}

		$combo_ciudades = array();
		$combo_ciudades[-1] = array ( "ciudad" => '', "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$ciudades->data_seek($num_fila);
			$fila = $ciudades->fetch_assoc();
			array_push($combo_ciudades,$fila);
		}

		//Liberar Memoria usada por la consulta
		$ciudades->close();

		return $combo_ciudades;											
	}



	function Cargar_combo_Cuadros_Precios_Ciudades($clave_cuadro){

		$conexion = $this ->Conexion;

		$ciudades =$conexion->query("select distinct ciudad, ciudad nombre from hit_producto_cuadros_aereos where clave_cuadro = '".$clave_cuadro."' ORDER BY ciudad");
		$numero_filas = $ciudades->num_rows;

		if ($ciudades == FALSE){
			echo('Error en la consulta');
			$ciudades->close();
			$conexion->close();
			exit;
		}

		$combo_ciudades = array();
		$combo_ciudades[-1] = array ( "ciudad" => 'SIN', "nombre" => 'Sin Vuelos');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$ciudades->data_seek($num_fila);
			$fila = $ciudades->fetch_assoc();
			array_push($combo_ciudades,$fila);
		}

		//Liberar Memoria usada por la consulta
		$ciudades->close();

		return $combo_ciudades;											
	}

function Cargar_combo_Cuadros_Aereos_Ciudades_Teletipos($teletipo_id){

		$conexion = $this ->Conexion;

		$ciudades =$conexion->query("select distinct ciudad, ciudad nombre from hit_teletipos_aereos where id = '".$teletipo_id."' ORDER BY ciudad");
		$numero_filas = $ciudades->num_rows;

		if ($ciudades == FALSE){
			echo('Error en la consulta');
			$ciudades->close();
			$conexion->close();
			exit;
		}

		$combo_ciudades = array();
		$combo_ciudades[-2] = array ( "ciudad" => '', "nombre" => '');
		$combo_ciudades[-1] = array ( "ciudad" => 'BLN', "nombre" => 'Sin Vuelos');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$ciudades->data_seek($num_fila);
			$fila = $ciudades->fetch_assoc();
			array_push($combo_ciudades,$fila);
		}

		//Liberar Memoria usada por la consulta
		$ciudades->close();

		return $combo_ciudades;											
	}

	function Cargar_Combo_Tipos_Calendario(){

		$Cargar_Combo_Tipos_Calendario = array();
		$Cargar_Combo_Tipos_Calendario[0] = array ( "valor" => 'P', "mostrar" => 'Periodo');
		$Cargar_Combo_Tipos_Calendario[1] = array ( "valor" => 'G', "mostrar" => 'General');


		return $Cargar_Combo_Tipos_Calendario;											
	}

	function Cargar_combo_Cuadros_Calendario($clave_cuadro){

		$conexion = $this ->Conexion;

		$calendario =$conexion->query("select clave, concat(DATE_FORMAT(fecha_desde, '%d-%m-%Y'),'  /  ',DATE_FORMAT(fecha_hasta, '%d-%m-%Y')) nombre 
										from hit_producto_cuadros_calendarios
										where clave_cuadro = '".$clave_cuadro."' ORDER BY fecha_desde");
		$numero_filas = $calendario->num_rows;

		if ($calendario == FALSE){
			echo('Error en la consulta');
			$calendario->close();
			$conexion->close();
			exit;
		}

		$combo_calendario = array();
		$combo_calendario[-1] = array ( "clave" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$calendario->data_seek($num_fila);
			$fila = $calendario->fetch_assoc();
			array_push($combo_calendario,$fila);
		}

		//Liberar Memoria usada por la consulta
		$calendario->close();

		return $combo_calendario;											
	}

	function Cargar_combo_Cuadros_Aereos($clave_cuadro){

		$conexion = $this ->Conexion;

		$Aereos =$conexion->query("select clave,concat(ciudad, ' - ', opcion, ' - ', numero, ' - ', origen, ' - ', destino, ' - ', cia) nombre
										from hit_producto_cuadros_aereos
										where clave_cuadro = '".$clave_cuadro."' ORDER BY ciudad, opcion, numero");
		$numero_filas = $Aereos->num_rows;

		if ($Aereos == FALSE){
			echo('Error en la consulta');
			$Aereos->close();
			$conexion->close();
			exit;
		}

		$combo_Aereos = array();
		$combo_Aereos[-1] = array ( "clave" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$Aereos->data_seek($num_fila);
			$fila = $Aereos->fetch_assoc();
			array_push($combo_Aereos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$Aereos->close();

		return $combo_Aereos;											
	}

	function Cargar_combo_Cuadros_Servicios($clave_cuadro){

		$conexion = $this ->Conexion;

		$Servicios =$conexion->query("select s.clave, concat(v.nombre, ' - ', s.dia, ' - ', s.orden) nombre
										FROM hit_producto_cuadros_servicios s, hit_servicios v
										WHERE s.id_proveedor = v.id_proveedor and s.codigo_servicio = v.codigo and s.clave_cuadro = '".$clave_cuadro."' ORDER BY dia, orden");


		$numero_filas = $Servicios->num_rows;

		if ($Servicios == FALSE){
			echo('Error en la consulta');
			$Servicios->close();
			$conexion->close();
			exit;
		}

		$combo_Servicios = array();
		$combo_Servicios[-1] = array ( "clave" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$Servicios->data_seek($num_fila);
			$fila = $Servicios->fetch_assoc();
			array_push($combo_Servicios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$Servicios->close();

		return $combo_Servicios;											
	}

	function Cargar_combo_Cuadros_Paquetes($clave_cuadro, $paquete, $clave_paquete){

		$conexion = $this ->Conexion;

		if($paquete != null){
			$clave_paq = " AND CLAVE = '".$clave_paquete."'";
			$cadena_buscar = " and c.paquete = '".$paquete."'";
			if($clave_paquete != null){
				$cadena_buscar .= $clave_paq;	
			}
		}elseif($clave_paquete != null){
			$cadena_buscar = " AND CLAVE = '".$clave_paquete."'";
		}else{
			$cadena_buscar = "";
		}

		$Servicios =$conexion->query("select c.clave, concat(c.paquete, ' - ', c.numero, ' - ', a.nombre, ' - ', c.caracteristica, ' - ', c.regimen) nombre
										FROM hit_producto_cuadros_alojamientos c, hit_alojamientos a
										WHERE c.alojamiento = a.id and c.clave_cuadro = '".$clave_cuadro."'".$cadena_buscar." ORDER BY paquete, numero");


		$numero_filas = $Servicios->num_rows;

		if ($Servicios == FALSE){
			echo('Error en la consulta');
			$Servicios->close();
			$conexion->close();
			exit;
		}

		$combo_Servicios = array();
		$combo_Servicios[-1] = array ( "clave" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$Servicios->data_seek($num_fila);
			$fila = $Servicios->fetch_assoc();
			array_push($combo_Servicios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$Servicios->close();

		return $combo_Servicios;											
	}

	function Cargar_combo_Cuadros_Paquetes_Agrupados($clave_cuadro){

		$conexion = $this ->Conexion;

		$Paquetes =$conexion->query("select distinct c.paquete clave, concat(c.paquete, ' - ', a.nombre) nombre
										FROM hit_producto_cuadros_alojamientos c, hit_alojamientos a
										WHERE c.alojamiento = a.id and c.clave_cuadro = '".$clave_cuadro."' ORDER BY paquete, nombre");


		$numero_filas = $Paquetes->num_rows;

		if ($Paquetes == FALSE){
			echo('Error en la consulta');
			$Paquetes->close();
			$conexion->close();
			exit;
		}

		$combo_Paquetes = array();
		$combo_Paquetes[-1] = array ( "clave" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$Paquetes->data_seek($num_fila);
			$fila = $Paquetes->fetch_assoc();
			array_push($combo_Paquetes,$fila);
		}

		//Liberar Memoria usada por la consulta
		$Paquetes->close();

		return $combo_Paquetes;											
	}

	function Cargar_combo_Alternativas_Aereas($localizador){

		$conexion = $this ->Conexion;
		//echo($localizador);
		$Aereos =$conexion->query("select 
										case
											when pc.NUMERO = 1 then pc.OPCION 
											else concat(pc.OPCION,'B')
										end valor, 
										case
											when pc.NUMERO = 1 then concat('&nbsp;&nbsp;Opcion&nbsp;',pc.OPCION,'&nbsp;&nbsp;-&nbsp;&nbsp;','Trayecto&nbsp;',pc.NUMERO,'&nbsp;&nbsp;-&nbsp;&nbsp;',pc.ORIGEN,'&nbsp;&nbsp;-&nbsp;&nbsp;',pc.DESTINO,'&nbsp;&nbsp;-&nbsp;&nbsp;',pc.CIA,'&nbsp;&nbsp;-&nbsp;&nbsp;',pc.ACUERDO,'&nbsp;&nbsp;-&nbsp;&nbsp;',pc.SUBACUERDO,
												'&nbsp;&nbsp;-&nbsp;&nbsp;',case
														when ta.TIPO = 'CHR' then 'Charter'
														when ta.TIPO = 'RCU' then 'Regular Cupos'
														when ta.TIPO = 'RAC' then 'Regular Cupos Amadeus'
														when ta.TIPO = 'ROR' then 'Regular On-request'
														when ta.TIPO = 'BUS' then 'Bus'
													else 'Regular On-request'
													end)
											else concat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;','Trayecto&nbsp;',pc.NUMERO,'&nbsp;&nbsp;-&nbsp;&nbsp;',pc.ORIGEN,'&nbsp;&nbsp;-&nbsp;&nbsp;',pc.DESTINO,'&nbsp;&nbsp;-&nbsp;&nbsp;',pc.CIA,'&nbsp;&nbsp;-&nbsp;&nbsp;',pc.ACUERDO,'&nbsp;&nbsp;-&nbsp;&nbsp;',pc.SUBACUERDO)
										end mostrar
										from hit_producto_cuadros_aereos pc,
											  hit_reservas r,
										  	  hit_transportes_acuerdos ta
										where
												pc.FOLLETO = r.FOLLETO
												and pc.CUADRO = r.CUADRO
												and pc.CIUDAD = r.CIUDAD_SALIDA
												and pc.CIA = ta.CIA
												and pc.ACUERDO = ta.ACUERDO
												and pc.SUBACUERDO = ta.SUBACUERDO
												and r.LOCALIZADOR = '".$localizador."' order by pc.CIUDAD, pc.OPCION, pc.NUMERO");
		$numero_filas = $Aereos->num_rows;

		if ($Aereos == FALSE){
			echo('Error en la consulta');
			$Aereos->close();
			$conexion->close();
			exit;
		}

		$combo_Aereos = array();
		$combo_Aereos[-1] = array ( "valor" => null, "mostrar" => null);
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$Aereos->data_seek($num_fila);
			$fila = $Aereos->fetch_assoc();
			array_push($combo_Aereos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$Aereos->close();

		return $combo_Aereos;											
	}

	function Cargar_Combo_Cantidad_Habitaciones(){

		$Cargar_Combo_Cantidad_Habitaciones = array();
		$Cargar_Combo_Cantidad_Habitaciones[0] = array ( "valor" => '0', "mostrar" => '0 Habitaciones');
		$Cargar_Combo_Cantidad_Habitaciones[1] = array ( "valor" => '1', "mostrar" => '1 Habitaciones');
		$Cargar_Combo_Cantidad_Habitaciones[2] = array ( "valor" => '2', "mostrar" => '2 Habitaciones');
		$Cargar_Combo_Cantidad_Habitaciones[3] = array ( "valor" => '3', "mostrar" => '3 Habitaciones');
		$Cargar_Combo_Cantidad_Habitaciones[4] = array ( "valor" => '4', "mostrar" => '4 Habitaciones');
		return $Cargar_Combo_Cantidad_Habitaciones;											
	}

	function Cargar_Combo_Habitaciones_Adultos(){

		$combo_habitaciones_adultos = array();
		$combo_habitaciones_adultos[0] = array ( "valor" => '0', "mostrar" => '0 Adultos');
		$combo_habitaciones_adultos[1] = array ( "valor" => '1', "mostrar" => '1 Adulto');
		$combo_habitaciones_adultos[2] = array ( "valor" => '2', "mostrar" => '2 Adultos');
		$combo_habitaciones_adultos[3] = array ( "valor" => '3', "mostrar" => '3 Adultos');
		$combo_habitaciones_adultos[4] = array ( "valor" => '4', "mostrar" => '4 Adultos');
		$combo_habitaciones_adultos[5] = array ( "valor" => '5', "mostrar" => '5 Adultos');
		$combo_habitaciones_adultos[6] = array ( "valor" => '6', "mostrar" => '6 Adultos');
		return $combo_habitaciones_adultos;											
	}

	function Cargar_Combo_Habitaciones_Ninos(){

		$combo_habitaciones_ninos = array();
		$combo_habitaciones_ninos[0] = array ( "valor" => '0', "mostrar" => '0 Niños');
		$combo_habitaciones_ninos[1] = array ( "valor" => '1', "mostrar" => '1 Niños');
		$combo_habitaciones_ninos[2] = array ( "valor" => '2', "mostrar" => '2 Niños');
		return $combo_habitaciones_ninos;											
	}

	function Cargar_Combo_Habitaciones_Bebes(){

		$Cargar_Combo_habitaciones_bebes = array();
		$Cargar_Combo_habitaciones_bebes[0] = array ( "valor" => '0', "mostrar" => '0 Bebes');
		$Cargar_Combo_habitaciones_bebes[1] = array ( "valor" => '1', "mostrar" => '1 Bebes');
		$Cargar_Combo_habitaciones_bebes[2] = array ( "valor" => '2', "mostrar" => '2 Bebes');
		return $Cargar_Combo_habitaciones_bebes;											
	}

	function Cargar_Combo_Habitaciones_Novios(){

		$Cargar_Combo_habitaciones_novios = array();
		$Cargar_Combo_habitaciones_novios[0] = array ( "valor" => 'N', "mostrar" => 'Novios No');
		$Cargar_Combo_habitaciones_novios[1] = array ( "valor" => 'S', "mostrar" => 'Novios Si');
		return $Cargar_Combo_habitaciones_novios;											
	}

	function Cargar_Combo_Habitaciones_Jubilados(){

		$Cargar_Combo_habitaciones_jubilados = array();
		$Cargar_Combo_habitaciones_jubilados[0] = array ( "valor" => '0', "mostrar" => '0 Jubilados');
		$Cargar_Combo_habitaciones_jubilados[1] = array ( "valor" => '1', "mostrar" => '1 Jubilados');
		$Cargar_Combo_habitaciones_jubilados[2] = array ( "valor" => '2', "mostrar" => '2 Jubilados');
		$Cargar_Combo_habitaciones_jubilados[3] = array ( "valor" => '3', "mostrar" => '3 Jubilados');
		$Cargar_Combo_habitaciones_jubilados[4] = array ( "valor" => '4', "mostrar" => '4 Jubilados');
		return $Cargar_Combo_habitaciones_jubilados;											
	}

	function Cargar_Combo_Solo_Vuelo_Adulos(){
		$Cargar_Combo_Solo_Vuelo_Adulos = array();
		$Cargar_Combo_Solo_Vuelo_Adulos[0] = array ( "valor" => '0', "mostrar" => '0 Adultos');
		$Cargar_Combo_Solo_Vuelo_Adulos[1] = array ( "valor" => '1', "mostrar" => '1 Adulto');
		$Cargar_Combo_Solo_Vuelo_Adulos[2] = array ( "valor" => '2', "mostrar" => '2 Adultos');
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
		$Cargar_Combo_Solo_Vuelo_Adulos[5] = array ( "valor" => '5', "mostrar" => '5 Niños');
		$Cargar_Combo_Solo_Vuelo_Adulos[6] = array ( "valor" => '6', "mostrar" => '6 Niños');
		$Cargar_Combo_Solo_Vuelo_Adulos[7] = array ( "valor" => '7', "mostrar" => '7 Niños');
		$Cargar_Combo_Solo_Vuelo_Adulos[8] = array ( "valor" => '8', "mostrar" => '8 Niños');
		$Cargar_Combo_Solo_Vuelo_Adulos[9] = array ( "valor" => '9', "mostrar" => '9 Niños');
		return $Cargar_Combo_Solo_Vuelo_Adulos;											
	}

	function Cargar_Combo_Ciudades_Origen(){

		/*$Cargar_Combo_Ciudades = array();
		$Cargar_Combo_Ciudades[0] = array ( "valor" => '', "mostrar" => 'Seleccione Origen');
		$Cargar_Combo_Ciudades[1] = array ( "valor" => 'ALC', "mostrar" => 'Alicante');
		$Cargar_Combo_Ciudades[2] = array ( "valor" => 'OVD', "mostrar" => 'Asturias');
		$Cargar_Combo_Ciudades[3] = array ( "valor" => 'LCG', "mostrar" => 'A Coruña');
		$Cargar_Combo_Ciudades[4] = array ( "valor" => 'BIO', "mostrar" => 'Bilbao');
		$Cargar_Combo_Ciudades[5] = array ( "valor" => 'MAD', "mostrar" => 'Madrid');
		$Cargar_Combo_Ciudades[6] = array ( "valor" => 'SCQ', "mostrar" => 'Santiago');
		$Cargar_Combo_Ciudades[7] = array ( "valor" => 'VLC', "mostrar" => 'Valencia');
		$Cargar_Combo_Ciudades[8] = array ( "valor" => 'VLL', "mostrar" => 'Valladolid');
		$Cargar_Combo_Ciudades[9] = array ( "valor" => 'VGO', "mostrar" => 'Vigo');
		$Cargar_Combo_Ciudades[10] = array ( "valor" => 'BLN', "mostrar" => 'Sin Vuelos');	
		
		return $Cargar_Combo_Ciudades;		*/

		$conexion = $this ->Conexion;

		$origenes =$conexion->query("SELECT codigo valor,nombre mostrar FROM hit_origenes where visible = 'S' ORDER BY NOMBRE");
		$numero_filas = $origenes->num_rows;

		if ($origenes == FALSE){
			echo('Error en la consulta');
			$origenes->close();
			$conexion->close();
			exit;
		}

		$combo_origenes = array();
		$combo_origenes[-1] = array ( "valor" => null, "mostrar" => 'Seleccione Origen');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$origenes->data_seek($num_fila);
			$fila = $origenes->fetch_assoc();
			array_push($combo_origenes,$fila);
		}

		//Liberar Memoria usada por la consulta
		$origenes->close();

		return $combo_origenes;

		
	}

	function Cargar_Combo_Estado_Salidas(){

		$Cargar_Combo_Estado_Salidas = array();
		$Cargar_Combo_Estado_Salidas[0] = array ( "valor" => 'A', "mostrar" => 'Activa');
		$Cargar_Combo_Estado_Salidas[1] = array ( "valor" => 'C', "mostrar" => 'Cerrada');


		return $Cargar_Combo_Estado_Salidas;											
	}

	function Cargar_Combo_Grupo_Condiciones_Comerciales(){

		/* GRUPOS DE PASAJEROS
		'1TP','Todos los Pax');
		'2TA','Todos los Adultos');
		'2UA','Solo Tercer Pax');
		'2VA','Solo Cuarto Pax');
		'2XA','Solo Quinto Pax');
		'2YA','Solo Sexto Pax');
		'3SN','Solo Novios');
		'4SJ','Solo Jubilados');
		'5SA','Solo Agentes');
		'9BB','Bebes');
		'6TN','Solo Niño + Adulo');
		'7S1','Solo Primer Niño');
		'8S2','Solo Segundo Niño');
		'9TN','Todos los Niños');
		*/

		/*SUBTIPOS DE PASAJEROS
		AD:Adulto, ('1TP','2TA')
		JU:Jubilado, ('1TP','2TA','4SJ')
		NO:Novio, ('1TP','2TA','3SN')
		AG:Agente, ('5SA')
		A3:tercerPAx, ('1TP','2TA','2UA')
		A4:Cuartopax, ('1TP','2TA','2VA')
		N1:primernino, ('1TP','7S1','9TN')
		N2:segundo nino, ('1TP','8S2','9TN')
		NA:niño con Adulto, ('1TP','6TN','9TN')
		BE:Bebe ('9BB')
		*/	

		$Combo_Grupo_Condiciones_Comerciales = array();
		$Combo_Grupo_Condiciones_Comerciales[0] = array ( "valor" => '1TP', "mostrar" => 'Todos los Pax');
		$Combo_Grupo_Condiciones_Comerciales[1] = array ( "valor" => '2TA', "mostrar" => 'Todos los Adultos');
		$Combo_Grupo_Condiciones_Comerciales[2] = array ( "valor" => '2UA', "mostrar" => 'Solo Tercer Pax');
		$Combo_Grupo_Condiciones_Comerciales[3] = array ( "valor" => '2VA', "mostrar" => 'Solo Cuarto Pax');
		$Combo_Grupo_Condiciones_Comerciales[4] = array ( "valor" => '2XA', "mostrar" => 'Solo Quinto Pax');
		$Combo_Grupo_Condiciones_Comerciales[5] = array ( "valor" => '2YA', "mostrar" => 'Solo Sexto Pax');
		$Combo_Grupo_Condiciones_Comerciales[6] = array ( "valor" => '2ZA', "mostrar" => 'Solo desde Septimo Pax');
		$Combo_Grupo_Condiciones_Comerciales[7] = array ( "valor" => '3SN', "mostrar" => 'Solo Novios');
		$Combo_Grupo_Condiciones_Comerciales[8] = array ( "valor" => '4SJ', "mostrar" => 'Solo Jubilados');
		$Combo_Grupo_Condiciones_Comerciales[9] = array ( "valor" => '5SA', "mostrar" => 'Solo Agentes');
		$Combo_Grupo_Condiciones_Comerciales[10] = array ( "valor" => '9BB', "mostrar" => 'Bebes');
		$Combo_Grupo_Condiciones_Comerciales[11] = array ( "valor" => '6TN', "mostrar" => 'Solo Niño + Adulto');
		$Combo_Grupo_Condiciones_Comerciales[12] = array ( "valor" => '7S1', "mostrar" => 'Solo Primer Niño');
		$Combo_Grupo_Condiciones_Comerciales[13] = array ( "valor" => '8S2', "mostrar" => 'Solo Segundo Niño');
		$Combo_Grupo_Condiciones_Comerciales[14] = array ( "valor" => '9TN', "mostrar" => 'Todos los Niños');

		return $Combo_Grupo_Condiciones_Comerciales;											
	}
	function Cargar_Combo_Tipo_Condiciones_Comerciales(){

		$Combo_Tipo_Condiciones_Comerciales = array();
		$Combo_Tipo_Condiciones_Comerciales[0] = array ( "valor" => 'D', "mostrar" => 'Descuento');
		$Combo_Tipo_Condiciones_Comerciales[1] = array ( "valor" => 'S', "mostrar" => 'Suplemento');
		$Combo_Tipo_Condiciones_Comerciales[2] = array ( "valor" => 'P', "mostrar" => 'Penalizacion');
		$Combo_Tipo_Condiciones_Comerciales[3] = array ( "valor" => 'R', "mostrar" => 'Restriccion');
		$Combo_Tipo_Condiciones_Comerciales[4] = array ( "valor" => 'G', "mostrar" => 'Gratificacion');
		return $Combo_Tipo_Condiciones_Comerciales;											
	}

	function Cargar_combo_Grupos(){

		$conexion = $this ->Conexion;

		$grupos =$conexion->query("SELECT id,nombre FROM hit_grupos ORDER BY NOMBRE");
		$numero_filas = $grupos->num_rows;

		if ($grupos == FALSE){
			echo('Error en la consulta');
			$grupos->close();
			$conexion->close();
			exit;
		}

		$combo_grupos = array();
		$combo_grupos[-1] = array ( "id" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$grupos->data_seek($num_fila);
			$fila = $grupos->fetch_assoc();
			array_push($combo_grupos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$grupos->close();

		return $combo_grupos;											
	}	
	
	
	
	function Cargar_Combo_Tipo_Viaje_Grupos(){

		$Cargar_Combo_Tipo_Viaje_Grupos = array();
		$Cargar_Combo_Tipo_Viaje_Grupos[0] = array ( "valor" => 'PAQ', "mostrar" => 'Paquete');
		$Cargar_Combo_Tipo_Viaje_Grupos[1] = array ( "valor" => 'SVO', "mostrar" => 'Solo vuelo');
		$Cargar_Combo_Tipo_Viaje_Grupos[2] = array ( "valor" => 'SHO', "mostrar" => 'Solo hotel');
		$Cargar_Combo_Tipo_Viaje_Grupos[3] = array ( "valor" => 'ENT', "mostrar" => 'Entradas');
		return $Cargar_Combo_Tipo_Viaje_Grupos;											
	}

	function Cargar_Combo_Tipo_Venta(){

		$Cargar_Combo_Tipo_Venta = array();
		$Cargar_Combo_Tipo_Venta[0] = array ( "valor" => 'MM', "mostrar" => 'Mayorista/Minorista');
		$Cargar_Combo_Tipo_Venta[1] = array ( "valor" => 'MA', "mostrar" => 'Mayorista');
		$Cargar_Combo_Tipo_Venta[2] = array ( "valor" => 'MI', "mostrar" => 'Minorista');
		return $Cargar_Combo_Tipo_Venta;											
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
		$Cargar_Combo_Annos[0] = array ( "valor" => '', "mostrar" => '');
		$Cargar_Combo_Annos[1] = array ( "valor" => '2014', "mostrar" => '2014');
		$Cargar_Combo_Annos[2] = array ( "valor" => '2015', "mostrar" => '2015');
		$Cargar_Combo_Annos[3] = array ( "valor" => '2016', "mostrar" => '2016');
		$Cargar_Combo_Annos[4] = array ( "valor" => '2017', "mostrar" => '2017');
		$Cargar_Combo_Annos[5] = array ( "valor" => '2018', "mostrar" => '2018');
		$Cargar_Combo_Annos[6] = array ( "valor" => '2019', "mostrar" => '2019');
		$Cargar_Combo_Annos[7] = array ( "valor" => '2020', "mostrar" => '2020');
		$Cargar_Combo_Annos[8] = array ( "valor" => '2021', "mostrar" => '2021');
		$Cargar_Combo_Annos[9] = array ( "valor" => '2022', "mostrar" => '2022');
		$Cargar_Combo_Annos[10] = array ( "valor" => '2023', "mostrar" => '2023');
		$Cargar_Combo_Annos[11] = array ( "valor" => '2024', "mostrar" => '2024');
		$Cargar_Combo_Annos[12] = array ( "valor" => '2025', "mostrar" => '2025');

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
		$Cargar_Comboorigen_grupos[0] = array ( "valor" => '', "mostrar" => 'Seleccione Origen');
		$Cargar_Comboorigen_grupos[1] = array ( "valor" => 'ALC', "mostrar" => 'Alicante');
		$Cargar_Comboorigen_grupos[2] = array ( "valor" => 'OVD', "mostrar" => 'Asturias');
		$Cargar_Comboorigen_grupos[3] = array ( "valor" => 'LCG', "mostrar" => 'A Coruña');
		$Cargar_Comboorigen_grupos[4] = array ( "valor" => 'BIO', "mostrar" => 'Bilbao');
		$Cargar_Comboorigen_grupos[5] = array ( "valor" => 'MAD', "mostrar" => 'Madrid');
		$Cargar_Comboorigen_grupos[6] = array ( "valor" => 'SCQ', "mostrar" => 'Santiago');
		$Cargar_Comboorigen_grupos[7] = array ( "valor" => 'VLC', "mostrar" => 'Valencia');
		$Cargar_Comboorigen_grupos[8] = array ( "valor" => 'VLL', "mostrar" => 'Valladolid');
		$Cargar_Comboorigen_grupos[9] = array ( "valor" => 'VGO', "mostrar" => 'Vigo');
		$Cargar_Comboorigen_grupos[10] = array ( "valor" => 'BLN', "mostrar" => 'Sin Vuelos');

		return $Cargar_Comboorigen_grupos;											
	}

	/*function Cargar_Combodestino_grupos(){

		$Cargar_Combodestino_grupos = array();
		$Cargar_Combodestino_grupos[1] = array ( "valor" => 'ACE', "mostrar" => 'Lanzarote');
		$Cargar_Combodestino_grupos[2] = array ( "valor" => 'TCISUR', "mostrar" => 'Tenerife');
		$Cargar_Combodestino_grupos[3] = array ( "valor" => 'TFN', "mostrar" => '---Tenerife Norte');
		$Cargar_Combodestino_grupos[4] = array ( "valor" => 'TFS', "mostrar" => '---Tenerife Sur');

		return $Cargar_Combodestino_grupos;											
	}*/


	function Cargar_Combodestino_grupos(){

		$conexion = $this ->Conexion;

		$destinos =$conexion->query("SELECT codigo, nombre FROM hit_destinos where visible_grupos = 'S' ORDER BY NOMBRE");
		$numero_filas = $destinos->num_rows;

		if ($destinos == FALSE){
			echo('Error en la consulta');
			$destinos->close();
			$conexion->close();
			exit;
		}

		$combo_destinos = array();
		$combo_destinos[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$destinos->data_seek($num_fila);
			$fila = $destinos->fetch_assoc();
			array_push($combo_destinos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$destinos->close();

		return $combo_destinos;											
	}

	function Cargar_Comboestado_grupos(){

		$Cargar_Comboestado_grupos = array();
		$Cargar_Comboestado_grupos[0] = array ( "valor" => 'N', "mostrar" => 'Nuevo');
		$Cargar_Comboestado_grupos[1] = array ( "valor" => 'P', "mostrar" => 'En proceso');
		$Cargar_Comboestado_grupos[2] = array ( "valor" => 'M', "mostrar" => 'Modificado');
		$Cargar_Comboestado_grupos[3] = array ( "valor" => 'A', "mostrar" => 'Aprobado');
		$Cargar_Comboestado_grupos[4] = array ( "valor" => 'C', "mostrar" => 'Cancelado');

		return $Cargar_Comboestado_grupos;											
	}

	function Cargar_combo_Teletipos_Formato(){

		$Cargar_combo_Teletipos_Formato = array();
		$Cargar_combo_Teletipos_Formato[0] = array ( "valor" => '0', "mostrar" => 'Primer Formato (Tabla)');
		$Cargar_combo_Teletipos_Formato[1] = array ( "valor" => '1', "mostrar" => 'Segundo Formato (Oferta Bloques)');
		$Cargar_combo_Teletipos_Formato[2] = array ( "valor" => '2', "mostrar" => 'Tercer Formato (Un Hotel)');
		$Cargar_combo_Teletipos_Formato[3] = array ( "valor" => '3', "mostrar" => 'Cuarto Formato (Solo Vuelo)');
		$Cargar_combo_Teletipos_Formato[4] = array ( "valor" => '4', "mostrar" => 'Quinto Formato (Texto Libre)');
		$Cargar_combo_Teletipos_Formato[5] = array ( "valor" => '5', "mostrar" => 'Sexto Formato (Oferta Texto Libre)');
		$Cargar_combo_Teletipos_Formato[6] = array ( "valor" => '6', "mostrar" => 'Septimo Formato A4 (Oferta de jpg con pdf)');
		$Cargar_combo_Teletipos_Formato[7] = array ( "valor" => '7', "mostrar" => 'Septimo Formato 1/2 A4 (Oferta de jpg con pdf)');

		return $Cargar_combo_Teletipos_Formato;											
	}


	function Cargar_combo_Teletipos(){

		$conexion = $this ->Conexion;

		$teletipos =$conexion->query("SELECT id, nombre FROM hit_teletipos ORDER BY NOMBRE");
		$numero_filas = $teletipos->num_rows;

		if ($teletipos == FALSE){
			echo('Error en la consulta');
			$teletipos->close();
			$conexion->close();
			exit;
		}

		$combo_teletipos = array();
		$combo_teletipos[-1] = array ( "id" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$teletipos->data_seek($num_fila);
			$fila = $teletipos->fetch_assoc();
			array_push($combo_teletipos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$teletipos->close();

		return $combo_teletipos;											
	}

	function Cargar_combo_Teletipos_SinNull(){

		$conexion = $this ->Conexion;

		$teletipos =$conexion->query("SELECT id, nombre FROM hit_teletipos ORDER BY NOMBRE");
		$numero_filas = $teletipos->num_rows;

		if ($teletipos == FALSE){
			echo('Error en la consulta');
			$teletipos->close();
			$conexion->close();
			exit;
		}

		$combo_teletipos = array();
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$teletipos->data_seek($num_fila);
			$fila = $teletipos->fetch_assoc();
			array_push($combo_teletipos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$teletipos->close();

		return $combo_teletipos;											
	}

	function Cargar_combo_Teletipos_Colores(){

		$conexion = $this ->Conexion;

		$teletipos =$conexion->query("SELECT id, nombre FROM hit_teletipos_colores ORDER BY NOMBRE");
		$numero_filas = $teletipos->num_rows;

		if ($teletipos == FALSE){
			echo('Error en la consulta');
			$teletipos->close();
			$conexion->close();
			exit;
		}

		$combo_teletipos = array();
		$combo_teletipos[-1] = array ( "id" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$teletipos->data_seek($num_fila);
			$fila = $teletipos->fetch_assoc();
			array_push($combo_teletipos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$teletipos->close();

		return $combo_teletipos;;											
	}

	function Cargar_Combo_tipo_planning(){

		$Cargar_Combo_tipo_planning = array();
		$Cargar_Combo_tipo_planning[0] = array ( "valor" => '', "mostrar" => '');
		$Cargar_Combo_tipo_planning[1] = array ( "valor" => 'G', "mostrar" => 'Planning de Ocupacion General');

		return $Cargar_Combo_tipo_planning;											
	}

	function Cargar_Combo_Estado_Envios(){

		$Cargar_Combo_Estado_Envios = array();
		$Cargar_Combo_Estado_Envios[0] = array ( "valor" => 'PE', "mostrar" => 'Pendiente');
		$Cargar_Combo_Estado_Envios[1] = array ( "valor" => 'EA', "mostrar" => 'Activado');
		$Cargar_Combo_Estado_Envios[2] = array ( "valor" => 'ED', "mostrar" => 'Desactivado');
		$Cargar_Combo_Estado_Envios[3] = array ( "valor" => 'OK', "mostrar" => 'Enviado');
		$Cargar_Combo_Estado_Envios[4] = array ( "valor" => 'ER', "mostrar" => 'Error de Envio');

		return $Cargar_Combo_Estado_Envios;											
	}	
	function Cargar_Combo_Estado_Envios_Todos(){

		$Cargar_Combo_Estado_Envios = array();
		$Cargar_Combo_Estado_Envios[-1] = array ( "valor" => '', "mostrar" => 'Todos');
		$Cargar_Combo_Estado_Envios[0] = array ( "valor" => 'PE', "mostrar" => 'Pendiente');
		$Cargar_Combo_Estado_Envios[1] = array ( "valor" => 'EA', "mostrar" => 'Activado');
		$Cargar_Combo_Estado_Envios[2] = array ( "valor" => 'ED', "mostrar" => 'Desactivado');
		$Cargar_Combo_Estado_Envios[3] = array ( "valor" => 'OK', "mostrar" => 'Enviado');
		$Cargar_Combo_Estado_Envios[4] = array ( "valor" => 'ER', "mostrar" => 'Error de Envio');

		return $Cargar_Combo_Estado_Envios;											
	}

	function Cargar_Combo_Estado_Precobro(){

		$Cargar_Combo_Estado_Precobro = array();
		$Cargar_Combo_Estado_Precobro[0] = array ( "valor" => 'N', "mostrar" => 'Pendiente');
		$Cargar_Combo_Estado_Precobro[1] = array ( "valor" => 'S', "mostrar" => 'Pagado');
		
		return $Cargar_Combo_Estado_Precobro;											
	}	

	function Cargar_Combo_Buscar_Estado_Precobro(){

		$Cargar_Combo_Buscar_Estado_Precobro = array();
		$Cargar_Combo_Buscar_Estado_Precobro[0] = array ( "valor" => '', "mostrar" => 'Todas');
		$Cargar_Combo_Buscar_Estado_Precobro[1] = array ( "valor" => 'N', "mostrar" => 'Pendiente');
		$Cargar_Combo_Buscar_Estado_Precobro[2] = array ( "valor" => 'S', "mostrar" => 'Pagado');
		
		
		return $Cargar_Combo_Buscar_Estado_Precobro;											
	}	

	function Cargar_Combo_Buscar_Estado_Facturacion(){

		$Cargar_Combo_Buscar_Estado_Facturacion = array();
		$Cargar_Combo_Buscar_Estado_Facturacion[0] = array ( "valor" => 'P', "mostrar" => 'Pendiente');
		$Cargar_Combo_Buscar_Estado_Facturacion[1] = array ( "valor" => 'F', "mostrar" => 'Facturado');
		$Cargar_Combo_Buscar_Estado_Facturacion[2] = array ( "valor" => '', "mostrar" => 'Todas');
		
		return $Cargar_Combo_Buscar_Estado_Facturacion;											
	}

	function Cargar_Combo_Buscar_Modo_Facturacion(){

		$Cargar_Combo_Buscar_Estado_Facturacion = array();
		$Cargar_Combo_Buscar_Estado_Facturacion[0] = array ( "valor" => 'O', "mostrar" => 'Oficina');
		$Cargar_Combo_Buscar_Estado_Facturacion[1] = array ( "valor" => 'C', "mostrar" => 'Central');
		
		return $Cargar_Combo_Buscar_Estado_Facturacion;											
	}

	function Cargar_Combo_Orden_reservas(){

		$Cargar_Combo_Orden_reservas = array();
		$Cargar_Combo_Orden_reservas[0] = array ( "valor" => 'RES_LOC', "mostrar" => 'Fecha reserva / Localizador');
		$Cargar_Combo_Orden_reservas[1] = array ( "valor" => 'SAL_LOC', "mostrar" => 'Fecha salida / Localizador');
		$Cargar_Combo_Orden_reservas[2] = array ( "valor" => 'LOC', "mostrar" => 'Localizador');
		
		return $Cargar_Combo_Orden_reservas;											
	}
	
	function Cargar_Combo_Tipo_Abono_Cargo(){

		$Cargar_Combo_Tipo_Abono_Cargo = array();
		$Cargar_Combo_Tipo_Abono_Cargo[0] = array ( "valor" => '', "mostrar" => '');
		$Cargar_Combo_Tipo_Abono_Cargo[1] = array ( "valor" => 'A', "mostrar" => 'Abono');
		$Cargar_Combo_Tipo_Abono_Cargo[2] = array ( "valor" => 'C', "mostrar" => 'Cargo');		
		return $Cargar_Combo_Tipo_Abono_Cargo;											
	}

	function Cargar_Combo_Buscar_Tipo_Abono_Cargo(){

		$Cargar_Combo_Tipo_Abono_Cargo = array();
		$Cargar_Combo_Tipo_Abono_Cargo[0] = array ( "valor" => '', "mostrar" => 'Todos');
		$Cargar_Combo_Tipo_Abono_Cargo[1] = array ( "valor" => 'A', "mostrar" => 'Abono');
		$Cargar_Combo_Tipo_Abono_Cargo[2] = array ( "valor" => 'C', "mostrar" => 'Cargo');		
		return $Cargar_Combo_Tipo_Abono_Cargo;											
	}

	function Cargar_Combo_Tipo_Precio_Reserva(){
		$Cargar_Combo_Tipo_Precio_Reserva = array();
		$Cargar_Combo_Tipo_Precio_Reserva[0] = array ( "valor" => 'A', "mostrar" => 'Automatico');
		$Cargar_Combo_Tipo_Precio_Reserva[1] = array ( "valor" => 'M', "mostrar" => 'Manual');
		$Cargar_Combo_Tipo_Precio_Reserva[2] = array ( "valor" => 'I', "mostrar" => 'Interfaz');
		return $Cargar_Combo_Tipo_Precio_Reserva;											
	}

	function Cargar_Combo_Sistemas(){
		$Cargar_Combo_Sistemas = array();
		$Cargar_Combo_Sistemas[0] = array ( "valor" => '', "mostrar" => 'Todos');
		$Cargar_Combo_Sistemas[1] = array ( "valor" => 'HITS', "mostrar" => 'Hits');
		$Cargar_Combo_Sistemas[2] = array ( "valor" => 'HIWEB', "mostrar" => 'Web');
		$Cargar_Combo_Sistemas[3] = array ( "valor" => 'SINVUELOS', "mostrar" => 'Sin Vuelos');
		return $Cargar_Combo_Sistemas;											
	}

	function Cargar_Combo_TipoInterfaz(){
		$Cargar_Combo_TipoInterfaz = array();
		$Cargar_Combo_TipoInterfaz[0] = array ( "valor" => 'A', "mostrar" => 'Aereos');
		$Cargar_Combo_TipoInterfaz[1] = array ( "valor" => 'H', "mostrar" => 'Hoteles');
		$Cargar_Combo_TipoInterfaz[2] = array ( "valor" => 'S', "mostrar" => 'Servicios');
		$Cargar_Combo_TipoInterfaz[3] = array ( "valor" => 'M', "mostrar" => 'Mixto');
		return $Cargar_Combo_TipoInterfaz;											
	}
	
	function Cargar_combo_Interfaces(){

		$conexion = $this ->Conexion;

		$interfaces =$conexion->query("SELECT codigo,nombre FROM hit_interfaces WHERE visible = 'S' ORDER BY nombre");
		$numero_filas = $interfaces->num_rows;

		if ($interfaces == FALSE){
			echo('Error en la consulta');
			$interfaces->close();
			$conexion->close();
			exit;
		}

		$combo_interfaces = array();
		$combo_interfaces[-1] = array ( "codigo" => null, "nombre" => '');
		for ($num_fila = 0; $num_fila < $numero_filas; $num_fila++) {
			$interfaces->data_seek($num_fila);
			$fila = $interfaces->fetch_assoc();
			array_push($combo_interfaces,$fila);
		}

		//Liberar Memoria usada por la consulta
		$interfaces->close();

		return $combo_interfaces;											
	}


	function Cargar_Combo_tipos_vacios_linea(){

		$Cargar_Combo_tipos_trayecto_linea = array();
		$Cargar_Combo_tipos_trayecto_linea[0] = array ( "valor" => 'BT', "mostrar" => 'Trayecto');
		$Cargar_Combo_tipos_trayecto_linea[1] = array ( "valor" => 'BP', "mostrar" => 'Trayecto con paradas');
		$Cargar_Combo_tipos_trayecto_linea[2] = array ( "valor" => 'BD', "mostrar" => 'A disposición');

		return $Cargar_Combo_tipos_trayecto_linea;											
	}

	function Cargar_Combo_tipos_vacios(){

		$Cargar_Combo_tipos_vacios = array();
		$Cargar_Combo_tipos_vacios[0] = array ( "valor" => '', "mostrar" => 'Todos');
		$Cargar_Combo_tipos_vacios[1] = array ( "valor" => 'BT', "mostrar" => 'Trayecto');
		$Cargar_Combo_tipos_vacios[2] = array ( "valor" => 'BP', "mostrar" => 'Trayecto con paradas');
		$Cargar_Combo_tipos_vacios[3] = array ( "valor" => 'BD', "mostrar" => 'A disposición');

		return $Cargar_Combo_tipos_vacios;											
	}

	function Cargar_Combo_estado_trayecto(){

		$Cargar_Combo_estado_trayecto = array();
		$Cargar_Combo_estado_trayecto[0] = array ( "valor" => '', "mostrar" => 'Todos');
		$Cargar_Combo_estado_trayecto[1] = array ( "valor" => 'D', "mostrar" => 'Disponible');
		$Cargar_Combo_estado_trayecto[2] = array ( "valor" => 'S', "mostrar" => 'Solicitado');
		$Cargar_Combo_estado_trayecto[3] = array ( "valor" => 'R', "mostrar" => 'Reservado');

		return $Cargar_Combo_estado_trayecto;											
	}

	function Cargar_Combo_estado_trayecto_linea(){

		$Cargar_Combo_estado_trayecto = array();
		$Cargar_Combo_estado_trayecto[0] = array ( "valor" => 'D', "mostrar" => 'Disponible');
		$Cargar_Combo_estado_trayecto[1] = array ( "valor" => 'S', "mostrar" => 'Solicitado');
		$Cargar_Combo_estado_trayecto[2] = array ( "valor" => 'R', "mostrar" => 'Reservado');

		return $Cargar_Combo_estado_trayecto;											
	}

	function Cargar_Combo_estado_solicitud(){

		$Cargar_Combo_estado_trayecto = array();
		$Cargar_Combo_estado_trayecto[0] = array ( "valor" => '', "mostrar" => 'Todos');
		$Cargar_Combo_estado_trayecto[1] = array ( "valor" => 'PE', "mostrar" => 'Solicitud Pendiente');
		$Cargar_Combo_estado_trayecto[2] = array ( "valor" => 'AC', "mostrar" => 'Enviar info a Cia');
		$Cargar_Combo_estado_trayecto[3] = array ( "valor" => 'EC', "mostrar" => 'Info enviada a Cia');
		$Cargar_Combo_estado_trayecto[4] = array ( "valor" => 'AA', "mostrar" => 'Enviar info a Agencia');
		$Cargar_Combo_estado_trayecto[5] = array ( "valor" => 'EA', "mostrar" => 'Info enviada a Agencia');
		$Cargar_Combo_estado_trayecto[6] = array ( "valor" => 'AT', "mostrar" => 'Enviar info a Todos');
		$Cargar_Combo_estado_trayecto[7] = array ( "valor" => 'ET', "mostrar" => 'Info enviada a Todos');
		$Cargar_Combo_estado_trayecto[8] = array ( "valor" => 'CE', "mostrar" => 'Acuerdo Cerrado');
		$Cargar_Combo_estado_trayecto[9] = array ( "valor" => 'DE', "mostrar" => 'Solicitud Descartada');

		return $Cargar_Combo_estado_trayecto;											
	}

	function Cargar_Combo_estado_solicitud_linea(){

		$Cargar_Combo_estado_solicitud_linea = array();
		$Cargar_Combo_estado_solicitud_linea[0] = array ( "valor" => 'PE', "mostrar" => 'Solicitud Pendiente');
		$Cargar_Combo_estado_solicitud_linea[1] = array ( "valor" => 'AC', "mostrar" => 'Enviar info a Cia');
		$Cargar_Combo_estado_solicitud_linea[2] = array ( "valor" => 'EC', "mostrar" => 'Info enviada a Cia');
		$Cargar_Combo_estado_solicitud_linea[3] = array ( "valor" => 'AA', "mostrar" => 'Enviar info a Agencia');
		$Cargar_Combo_estado_solicitud_linea[4] = array ( "valor" => 'EA', "mostrar" => 'Info enviada a Agencia');
		$Cargar_Combo_estado_solicitud_linea[5] = array ( "valor" => 'AT', "mostrar" => 'Enviar info a Todos');
		$Cargar_Combo_estado_solicitud_linea[6] = array ( "valor" => 'ET', "mostrar" => 'Info enviada a Todos');
		$Cargar_Combo_estado_solicitud_linea[7] = array ( "valor" => 'CE', "mostrar" => 'Acuerdo Cerrado');
		$Cargar_Combo_estado_solicitud_linea[8] = array ( "valor" => 'DE', "mostrar" => 'Solicitud Descartada');

		return $Cargar_Combo_estado_solicitud_linea;											
	}

	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsGeneral($conexion){
		$this->Conexion = $conexion;
	}


}

?>