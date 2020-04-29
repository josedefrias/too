<?php

class clsTransportes_cupos{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	//var $buscar_alojamiento;
	//var	$buscar_tipo;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_cia = $this ->Buscar_cia;
		$buscar_acuerdo = $this ->Buscar_acuerdo;
		$buscar_subacuerdo = $this ->Buscar_subacuerdo;
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_localizador = $this ->Buscar_localizador;
		$buscar_fecha_depuracion_1 = $this ->Buscar_fecha_depuracion_1;
		$buscar_fecha_depuracion_2 = $this ->Buscar_fecha_depuracion_2;
		$buscar_fecha_depuracion_final = $this ->Buscar_fecha_depuracion_final;
		$buscar_dia_semana = $this ->Buscar_dia_semana;
	
		if($buscar_cia != null){
			$acuer = " AND t.ACUERDO = '".$buscar_acuerdo."'";
			$subacuer = " AND t.SUBACUERDO = '".$buscar_subacuerdo."'";
			$orig = " AND t.ORIGEN = '".$buscar_origen."'";
			$desti = " AND t.DESTINO = '".$buscar_destino."'";
			$fech = " AND t.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND t.DIA = '".$buscar_dia_semana."'";
			$loc = " AND t.LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND t.DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND t.DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND t.DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE t.CIA = '".$buscar_cia."'";
			if($buscar_acuerdo != null){
				$CADENA_BUSCAR .= $acuer;	
			}
			if($buscar_subacuerdo != null){
				$CADENA_BUSCAR .= $subacuer;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}

		}elseif($buscar_acuerdo != null){
			$subacuer = " AND t.SUBACUERDO = '".$buscar_subacuerdo."'";
			$orig = " AND t.ORIGEN = '".$buscar_origen."'";
			$desti = " AND t.DESTINO = '".$buscar_destino."'";
			$fech = " AND t.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND t.DIA = '".$buscar_dia_semana."'";
			$loc = " AND t.LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND t.DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND t.DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND t.DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE t.ACUERDO = '".$buscar_acuerdo."'";
			if($buscar_subacuerdo != null){
				$CADENA_BUSCAR .= $subacuer;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_subacuerdo != null){
			$orig = " AND t.ORIGEN = '".$buscar_origen."'";
			$desti = " AND t.DESTINO = '".$buscar_destino."'";
			$fech = " AND t.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND t.DIA = '".$buscar_dia_semana."'";
			$loc = " AND t.LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND t.DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND t.DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND t.DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE t.SUBACUERDO = '".$buscar_subacuerdo."'";

			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_origen != null){
			$desti = " AND t.DESTINO = '".$buscar_destino."'";
			$fech = " AND t.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND t.DIA = '".$buscar_dia_semana."'";
			$loc = " AND t.LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND t.DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND t.DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND t.DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE t.ORIGEN = '".$buscar_origen."'";

			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_destino != null){
			$fech = " AND t.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND t.DIA = '".$buscar_dia_semana."'";
			$loc = " AND t.LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND t.DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND t.DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND t.DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE t.DESTINO = '".$buscar_destino."'";

			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha != null){
			$diasem = " AND t.DIA = '".$buscar_dia_semana."'";
			$loc = " AND t.LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND t.DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND t.DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND t.DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE t.FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";

			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}

		}elseif($buscar_dia_semana != null){
			$loc = " AND t.LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND t.DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND t.DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND t.DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE t.DIA = '".$buscar_dia_semana."'";

			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
	
		}elseif($buscar_localizador != null){
			$depur_1 = " AND t.DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND t.DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND t.DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE t.LOCALIZADOR = '".$buscar_localizador."'";

			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha_depuracion_1 != null){
			$depur_2 = " AND t.DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND t.DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE t.DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";

			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha_depuracion_2 != null){
			$depur_final = " AND t.DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE t.DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";

			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha_depuracion_final != null){
			$CADENA_BUSCAR = " WHERE t.DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";

		}else{
			$CADENA_BUSCAR = " WHERE t.CIA = '".$buscar_cia."' AND t.ACUERDO = '".$buscar_acuerdo."' AND t.SUBACUERDO = '".$buscar_subacuerdo."'";     
  		}

		$resultado =$conexion->query("SELECT t.cia, t.acuerdo, t.subacuerdo,t.origen,t.destino, DATE_FORMAT(t.fecha, '%d-%m-%Y') AS fecha, t.dia, t.vuelo, 
											 time_format(t.hora_salida, '%H:%i') AS hora_salida, time_format(t.hora_llegada, '%H:%i') AS hora_llegada,
									         t.clase, t.cupo, t.plazas_ok, t.plazas_wl, t.limite_clase_superior, t.localizador_cia, DATE_FORMAT(t.depuracion_1, '%d-%m-%Y') AS depuracion_1, DATE_FORMAT(t.depuracion_2, '%d-%m-%Y') AS depuracion_2, DATE_FORMAT(t.depuracion_final, '%d-%m-%Y') AS depuracion_final, t.maximo_bebes,
									         t.clase_1, t.cupo_1, t.clase_2, t.cupo_2, t.clase_3, t.cupo_3, t.clase_4, t.cupo_4, t.clase_5, t.cupo_5, tran.tipo,
									         DATE_FORMAT(t.ultima_modificacion, '%d-%m-%Y %H:%i:%S') AS ultima_modificacion
									  FROM hit_transportes_cupos t, hit_transportes_acuerdos tran ".$CADENA_BUSCAR." and t.CIA = tran.CIA and t.ACUERDO = tran.ACUERDO and t.SUBACUERDO = tran.SUBACUERDO ORDER BY t.fecha, ORIGEN, DESTINO, ACUERDO");

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
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_CUPOS' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$transportes_cupos = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['cia'] == ''){
				break;
			}
			array_push($transportes_cupos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $transportes_cupos;											
	}

	function Cargar_lineas_nuevas(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_CUPOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_cia = $this ->Buscar_cia;
		$buscar_acuerdo = $this ->Buscar_acuerdo;
		$buscar_subacuerdo = $this ->Buscar_subacuerdo;
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_localizador = $this ->Buscar_localizador;
		$buscar_fecha_depuracion_1 = $this ->Buscar_fecha_depuracion_1;
		$buscar_fecha_depuracion_2 = $this ->Buscar_fecha_depuracion_2;
		$buscar_fecha_depuracion_final = $this ->Buscar_fecha_depuracion_final;
		$buscar_dia_semana = $this ->Buscar_dia_semana;
	
		if($buscar_cia != null){
			$acuer = " AND ACUERDO = '".$buscar_acuerdo."'";
			$subacuer = " AND SUBACUERDO = '".$buscar_subacuerdo."'";
			$orig = " AND ORIGEN = '".$buscar_origen."'";
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$fech = " AND FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE CIA = '".$buscar_cia."'";
			if($buscar_acuerdo != null){
				$CADENA_BUSCAR .= $acuer;	
			}
			if($buscar_subacuerdo != null){
				$CADENA_BUSCAR .= $subacuer;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}

		}elseif($buscar_acuerdo != null){
			$subacuer = " AND SUBACUERDO = '".$buscar_subacuerdo."'";
			$orig = " AND ORIGEN = '".$buscar_origen."'";
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$fech = " AND FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE ACUERDO = '".$buscar_acuerdo."'";
			if($buscar_subacuerdo != null){
				$CADENA_BUSCAR .= $subacuer;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_subacuerdo != null){
			$orig = " AND ORIGEN = '".$buscar_origen."'";
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$fech = " AND FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE SUBACUERDO = '".$buscar_subacuerdo."'";

			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_origen != null){
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$fech = " AND FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE ORIGEN = '".$buscar_origen."'";

			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_destino != null){
			$fech = " AND FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE DESTINO = '".$buscar_destino."'";

			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha != null){
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";

			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}

		}elseif($buscar_dia_semana != null){
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE DIA = '".$buscar_dia_semana."'";

			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
	
		}elseif($buscar_localizador != null){
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE LOCALIZADOR = '".$buscar_localizador."'";

			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha_depuracion_1 != null){
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";

			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha_depuracion_2 != null){
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";

			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha_depuracion_final != null){
			$CADENA_BUSCAR = " WHERE DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";

		}else{
			$CADENA_BUSCAR = " WHERE CIA = '".$buscar_cia."' AND ACUERDO = '".$buscar_acuerdo."' AND SUBACUERDO = '".$buscar_subacuerdo."'";     
  		}										

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes_cupos'.$CADENA_BUSCAR);


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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_CUPOS' AND USUARIO = '".$Usuario."'");
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
		$buscar_cia = $this ->Buscar_cia;
		$buscar_acuerdo = $this ->Buscar_acuerdo;
		$buscar_subacuerdo = $this ->Buscar_subacuerdo;
		$buscar_origen = $this ->Buscar_origen;
		$buscar_destino = $this ->Buscar_destino;
		$buscar_fecha = $this ->Buscar_fecha;
		$buscar_localizador = $this ->Buscar_localizador;
		$buscar_fecha_depuracion_1 = $this ->Buscar_fecha_depuracion_1;
		$buscar_fecha_depuracion_2 = $this ->Buscar_fecha_depuracion_2;
		$buscar_fecha_depuracion_final = $this ->Buscar_fecha_depuracion_final;
		$buscar_dia_semana = $this ->Buscar_dia_semana;
	
		if($buscar_cia != null){
			$acuer = " AND ACUERDO = '".$buscar_acuerdo."'";
			$subacuer = " AND SUBACUERDO = '".$buscar_subacuerdo."'";
			$orig = " AND ORIGEN = '".$buscar_origen."'";
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$fech = " AND FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE CIA = '".$buscar_cia."'";
			if($buscar_acuerdo != null){
				$CADENA_BUSCAR .= $acuer;	
			}
			if($buscar_subacuerdo != null){
				$CADENA_BUSCAR .= $subacuer;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}

		}elseif($buscar_acuerdo != null){
			$subacuer = " AND SUBACUERDO = '".$buscar_subacuerdo."'";
			$orig = " AND ORIGEN = '".$buscar_origen."'";
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$fech = " AND FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE ACUERDO = '".$buscar_acuerdo."'";
			if($buscar_subacuerdo != null){
				$CADENA_BUSCAR .= $subacuer;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_subacuerdo != null){
			$orig = " AND ORIGEN = '".$buscar_origen."'";
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$fech = " AND FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE SUBACUERDO = '".$buscar_subacuerdo."'";

			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_origen != null){
			$desti = " AND DESTINO = '".$buscar_destino."'";
			$fech = " AND FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE ORIGEN = '".$buscar_origen."'";

			if($buscar_destino != null){
				$CADENA_BUSCAR .= $desti;	
			}
			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_destino != null){
			$fech = " AND FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE DESTINO = '".$buscar_destino."'";

			if($buscar_fecha != null){
				$CADENA_BUSCAR .= $fech;	
			}
			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha != null){
			$diasem = " AND DIA = '".$buscar_dia_semana."'";
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE FECHA >= '".date("Y-m-d",strtotime($buscar_fecha))."'";

			if($buscar_dia_semana != null){
				$CADENA_BUSCAR .= $diasem;	
			}
			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}

		}elseif($buscar_dia_semana != null){
			$loc = " AND LOCALIZADOR = '".$buscar_localizador."'";
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE DIA = '".$buscar_dia_semana."'";

			if($buscar_localizador != null){
				$CADENA_BUSCAR .= $loc;	
			}
			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
	
		}elseif($buscar_localizador != null){
			$depur_1 = " AND DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE LOCALIZADOR = '".$buscar_localizador."'";

			if($buscar_fecha_depuracion_1 != null){
				$CADENA_BUSCAR .= $depur_1;	
			}
			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha_depuracion_1 != null){
			$depur_2 = " AND DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE DEPURACION_1 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_1))."'";

			if($buscar_fecha_depuracion_2 != null){
				$CADENA_BUSCAR .= $depur_2;	
			}
			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha_depuracion_2 != null){
			$depur_final = " AND DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";
			$CADENA_BUSCAR = " WHERE DEPURACION_2 = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_2))."'";

			if($buscar_fecha_depuracion_final != null){
				$CADENA_BUSCAR .= $depur_final;	
			}
		}elseif($buscar_fecha_depuracion_final != null){
			$CADENA_BUSCAR = " WHERE DEPURACION_FINAL = '".date("Y-m-d",strtotime($buscar_fecha_depuracion_final))."'";

		}else{
			$CADENA_BUSCAR = " WHERE CIA = '".$buscar_cia."' AND ACUERDO = '".$buscar_acuerdo."' AND SUBACUERDO = '".$buscar_subacuerdo."'";     
  		}										

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_transportes_cupos'.$CADENA_BUSCAR);


		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'TRANSPORTES_CUPOS' AND USUARIO = '".$Usuario."'");
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

	function Modificar($cia,$acuerdo,$subacuerdo,$origen,$destino,$fecha,$vuelo,$hora_salida,$hora_llegada,$clase,$cupo,$limite_clase_superior,$localizador,$depuracion_1,$depuracion_2,$depuracion_final, $maximo_bebes, $clase_1, $cupo_1, $clase_2, $cupo_2, $clase_3, $cupo_3, $clase_4, $cupo_4, $clase_5, $cupo_5){

		$conexion = $this ->Conexion;


		//Comprobamos el tipo de contrato
		$tipo_contrato =$conexion->query("SELECT tran.TIPO tipo_contrato FROM hit_transportes_acuerdos tran WHERE tran.CIA = '".$cia."' and tran.ACUERDO = ".$acuerdo." and tran.SUBACUERDO = ".$subacuerdo);
		$otipo_contrato = $tipo_contrato->fetch_assoc();
		$datos_tipo_contrato = $otipo_contrato['tipo_contrato'];

		$query = "UPDATE hit_transportes_cupos SET ";
		$query .= " VUELO = '".$vuelo."'";
		$query .= ", HORA_SALIDA = '".$hora_salida."'";
		$query .= ", HORA_LLEGADA = '".$hora_llegada."'";

		if($datos_tipo_contrato != 'RAC'){
			$query .= ", CLASE = '".$clase."'";
			$query .= ", CUPO = '".$cupo."'";
			$query .= ", LIMITE_CLASE_SUPERIOR = '".$limite_clase_superior."'";
			$query .= ", MAXIMO_BEBES = '".$maximo_bebes."'";
			$query .= ", LOCALIZADOR_CIA = '".$localizador."'";
			$query .= ", DEPURACION_1 = '".date("Y-m-d",strtotime($depuracion_1))."'";
			$query .= ", DEPURACION_2 = '".date("Y-m-d",strtotime($depuracion_2))."'";
			$query .= ", DEPURACION_FINAL = '".date("Y-m-d",strtotime($depuracion_final))."'";
			$query .= " WHERE PLAZAS_OK <= ".$cupo." AND CIA = '".$cia."'";
		}else{
			$query .= ", CLASE_1 = '".$clase_1."'";
			$query .= ", CUPO_1 = '".$cupo_1."'";
			$query .= ", CLASE_2 = '".$clase_2."'";
			$query .= ", CUPO_2 = '".$cupo_2."'";
			$query .= ", CLASE_3 = '".$clase_3."'";
			$query .= ", CUPO_3 = '".$cupo_3."'";
			$query .= ", CLASE_4 = '".$clase_4."'";
			$query .= ", CUPO_4 = '".$cupo_4."'";
			$query .= ", CLASE_5 = '".$clase_5."'";
			$query .= ", CUPO_5 = '".$cupo_5."'";
			$query .= " WHERE  CIA = '".$cia."'";
		}	
		
		//$query .= " WHERE PLAZAS_OK <= ".$cupo." AND CIA = '".$cia."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND SUBACUERDO = '".$subacuerdo."'";
		$query .= " AND ORIGEN = '".$origen."'";
		$query .= " AND DESTINO = '".$destino."'";
		$query .= " AND FECHA = '".date("Y-m-d",strtotime($fecha))."'";

		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){

			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Borrar($cia,$acuerdo,$subacuerdo,$origen,$destino,$fecha){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_transportes_cupos WHERE PLAZAS_OK = 0 AND CIA = '".$cia."'";
		$query .= " AND ACUERDO = '".$acuerdo."'";
		$query .= " AND SUBACUERDO = '".$subacuerdo."'";
		$query .= " AND ORIGEN = '".$origen."'";
		$query .= " AND DESTINO = '".$destino."'";
		$query .= " AND FECHA = '".date("Y-m-d",strtotime($fecha))."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}

	function Insertar($cia,$acuerdo,$subacuerdo,$origen,$destino,$fecha,$vuelo,$hora_salida,$hora_llegada,$clase,$cupo,$limite_clase_superior,$localizador,$depuracion_1,$depuracion_2,$depuracion_final, $clase_1, $cupo1, $clase_2, $cupo2, $clase_3, $cupo3, $clase_4, $cupo4, $clase_5, $cupo5){

		$conexion = $this ->Conexion;

		$ResultadoNombreDia =$conexion->query("SELECT DAYNAME('".date("Y-m-d",strtotime($fecha))."')");
		$Nombre_dia_semana = mysqli_fetch_row($ResultadoNombreDia);	

		$resultadodia =$conexion->query("SELECT `GENERAL_TRADUCE_DIA`('".$Nombre_dia_semana[0]."')");
		$dia_semana = mysqli_fetch_row($resultadodia);	
		
		//------

		$query = "INSERT INTO hit_transportes_cupos (CIA,ACUERDO,SUBACUERDO,ORIGEN,DESTINO,FECHA,DIA,VUELO,HORA_SALIDA,HORA_LLEGADA,CLASE,CUPO,PLAZAS_OK,PLAZAS_WL,LIMITE_CLASE_SUPERIOR,LOCALIZADOR_CIA,DEPURACION_1,DEPURACION_2,DEPURACION_FINAL,CLASE_1,CUPO_1,CLASE_2,CUPO_2,CLASE_3,CUPO_3,CLASE_4,CUPO_4,CLASE_5,CUPO_5) VALUES (";
		$query .= "'".$cia."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$subacuerdo."',";
		$query .= "'".$origen."',";
		$query .= "'".$destino."',";
		$query .= "'".date("Y-m-d",strtotime($fecha))."',";
		$query .= "'".$dia_semana[0]."',";
		$query .= "'".$vuelo."',";
		$query .= "'".$hora_salida."',";
		$query .= "'".$hora_llegada."',";
		$query .= "'".$clase."',";
		$query .= "'".$cupo."',";
		$query .= "0,";
		$query .= "0,";
		$query .= "'".$limite_clase_superior."',";
		$query .= "'".$localizador."',";
		$query .= "'".date("Y-m-d",strtotime($depuracion_1))."',";
		$query .= "'".date("Y-m-d",strtotime($depuracion_2))."',";
		$query .= "'".date("Y-m-d",strtotime($depuracion_final))."',";
		$query .= "'".$clase_1."',";
		$query .= "'".$cupo_1."',";
		$query .= "'".$clase_2."',";
		$query .= "'".$cupo_2."',";
		$query .= "'".$clase_3."',";
		$query .= "'".$cupo_3."',";
		$query .= "'".$clase_4."',";
		$query .= "'".$cupo_4."',";
		$query .= "'".$clase_5."',";
		$query .= "'".$cupo_5."')";


		//ECHO($query);

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}


	function Actualizar($fecha_desde,$fecha_hasta,$lunes,$martes,$miercoles,$jueves,$viernes,$sabado,$domingo,$origen,$destino,$vuelo,$nuevo_cupo,$nuevo_vuelo,$nuevo_hora_salida,$nuevo_hora_llegada){
		
		$buscar_cia = $this ->Buscar_cia;
		$buscar_acuerdo = $this ->Buscar_acuerdo;
		$buscar_subacuerdo = $this ->Buscar_subacuerdo;

		//Montamos la variable con los dias de la semana
		$dias_semana = '';
		if($lunes == 'S'){
			$dias_semana .= 'L';
		}
		if($martes == 'S'){
			$dias_semana .= 'M';
		}
		if($miercoles == 'S'){
			$dias_semana .= 'X';
		}
		if($jueves == 'S'){
			$dias_semana .= 'J';
		}
		if($viernes == 'S'){
			$dias_semana .= 'V';
		}
		if($sabado == 'S'){
			$dias_semana .= 'S';
		}
		if($domingo == 'S'){
			$dias_semana .= 'D';
		}

		$CADENA_BUSCAR = "";
		$dia = " AND (DIA = substr('".$dias_semana."',1,1) OR DIA = substr('".$dias_semana."',2,1) OR DIA = substr('".$dias_semana."',3,1) OR DIA = substr('".$dias_semana."',4,1) OR DIA = substr('".$dias_semana."',5,1) OR DIA = substr('".$dias_semana."',6,1) OR DIA = substr('".$dias_semana."',7,1))";
		$orig = " AND ORIGEN = '".$origen."'";
		$destin = " AND DESTINO = '".$destino."'";
		$vuel = " AND VUELO = '".$vuelo."'";
		$subacuerd = " AND SUBACUERDO = '".$buscar_subacuerdo."'";

		if($dia != null){
			$CADENA_BUSCAR .= $dia;	
		}
		if($origen != null){
			$CADENA_BUSCAR .= $orig;	
		}
		if($destino != null){
			$CADENA_BUSCAR .= $destin;	
		}
		if($vuelo != null){
			$CADENA_BUSCAR .= $vuel;	
		}
		if($buscar_subacuerdo != null){
			$CADENA_BUSCAR .= $subacuerd;	
		}


		$CADENA_ACTUALIZAR_1 = "";

		if($nuevo_cupo != null){
			$CADENA_ACTUALIZAR_1 = " cupo = ".$nuevo_cupo;
			$act_vuel = ", vuelo = ".$nuevo_vuelo;
			$act_hora_salid = ", hora_salida = '".$nuevo_hora_salida."'";
			$act_hora_llegad = ", hora_llegada = '".$nuevo_hora_llegada."'";

			if($nuevo_vuelo != null){
				$CADENA_ACTUALIZAR_1 .= $act_vuel;	
			}
			if($nuevo_hora_salida != null){
				$CADENA_ACTUALIZAR_1 .= $act_hora_salid;	
			}
			if($nuevo_hora_llegada != null){
				$CADENA_ACTUALIZAR_1 .= $act_hora_llegad;	
			}

		}elseif($nuevo_vuelo != null){
			$CADENA_ACTUALIZAR_1 = " vuelo = ".$nuevo_vuelo;
			$act_hora_salid = ", hora_salida = '".$nuevo_hora_salida."'";
			$act_hora_llegad = ", hora_llegada = '".$nuevo_hora_llegada."'";

			if($nuevo_hora_salida != null){
				$CADENA_ACTUALIZAR_1 .= $act_hora_salid;	
			}
			if($nuevo_hora_llegada != null){
				$CADENA_ACTUALIZAR_1 .= $act_hora_llegad;	
			}

		}elseif($nuevo_hora_salida != null){
			$CADENA_ACTUALIZAR_1 = " hora_salida = '".$nuevo_hora_salida."'";
			$act_hora_llegad = ", hora_llegada = '".$nuevo_hora_llegada."'";

			if($nuevo_hora_llegada != null){
				$CADENA_ACTUALIZAR_1 .= $act_hora_llegad;	
			}

		}elseif($nuevo_hora_llegada != null){
			$CADENA_ACTUALIZAR_1 = " hora_llegada = '".$nuevo_hora_llegada."'";
		}

		$CADENA_ACTUALIZAR_2 = "";

		if($nuevo_cupo != null){
			$CADENA_ACTUALIZAR_2 = " cupo = PLAZAS_OK";
			$act_vuel = ", vuelo = ".$nuevo_vuelo;
			$act_hora_salid = ", hora_salida = '".$nuevo_hora_salida."'";
			$act_hora_llegad = ", hora_llegada = '".$nuevo_hora_llegada."'";

			if($nuevo_vuelo != null){
				$CADENA_ACTUALIZAR_2 .= $act_vuel;	
			}
			if($nuevo_hora_salida != null){
				$CADENA_ACTUALIZAR_2 .= $act_hora_salid;	
			}
			if($nuevo_hora_llegada != null){
				$CADENA_ACTUALIZAR_2 .= $act_hora_llegad;	
			}

		}elseif($nuevo_vuelo != null){
			$CADENA_ACTUALIZAR_2 = " vuelo = ".$nuevo_vuelo;
			$act_hora_salid = ", hora_salida = '".$nuevo_hora_salida."'";
			$act_hora_llegad = ", hora_llegada = '".$nuevo_hora_llegada."'";

			if($nuevo_hora_salida != null){
				$CADENA_ACTUALIZAR_2 .= $act_hora_salid;	
			}
			if($nuevo_hora_llegada != null){
				$CADENA_ACTUALIZAR_2 .= $act_hora_llegad;	
			}

		}elseif($nuevo_hora_salida != null){
			$CADENA_ACTUALIZAR_2 = " hora_salida = '".$nuevo_hora_salida."'";
			$act_hora_llegad = ", hora_llegada = '".$nuevo_hora_llegada."'";

			if($nuevo_hora_llegada != null){
				$CADENA_ACTUALIZAR_2 .= $act_hora_llegad;	
			}

		}elseif($nuevo_hora_llegada != null){
			$CADENA_ACTUALIZAR_2 = " hora_llegada = '".$nuevo_hora_llegada."'";
		}

		//echo($nuevo_cupo." | ".$nuevo_vuelo." | ".$nuevo_hora_salida." | ".$nuevo_hora_llegada);


		if($buscar_cia != '' and $buscar_acuerdo != '' and $fecha_desde != '' and $fecha_hasta != '' and $dias_semana != '' and $origen  != '' and $destino != ''){
				$respuesta = '';
				/*echo($buscar_cia." | ".$buscar_acuerdo." | ".$fecha_desde." | ".$fecha_hasta." | ".$dias_semana." | ".$origen." | ".$destino." | ".$vuelo." | ".$nuevo_cupo." | ".$nuevo_vuelo." | ".$nuevo_hora_salida." | ".$nuevo_hora_llegada);*/

			if($nuevo_cupo != '' or $nuevo_vuelo != '' or $nuevo_hora_salida != '' or $nuevo_hora_llegada != ''){	

				//actualizamos los cupos que quedan por encima de las plazas ocupadas
				$conexion = $this ->Conexion;
				$query = "UPDATE hit_transportes_cupos SET";
				$query .= $CADENA_ACTUALIZAR_1;
				$query .= " WHERE PLAZAS_OK <= ".$nuevo_cupo." and cia = '".$buscar_cia."' and acuerdo = ".$buscar_acuerdo." and FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
				$query .= $CADENA_BUSCAR;

				//echo($query);

				$resultadom =$conexion->query($query);
				if ($resultadom == FALSE){

					$respuesta = $conexion->error;
				}else{
					$respuesta = 'OK';
				}
				
				//actualizamos los cupos que quedan por debajo de las plazas ocupadas
				$conexion = $this ->Conexion;
				$query = "UPDATE hit_transportes_cupos SET";
				$query .= $CADENA_ACTUALIZAR_2;
				$query .= " WHERE PLAZAS_OK > ".$nuevo_cupo." and cia = '".$buscar_cia."' and acuerdo = ".$buscar_acuerdo." and FECHA BETWEEN '".date("Y-m-d",strtotime($fecha_desde))."' AND '".date("Y-m-d",strtotime($fecha_hasta))."'";
				$query .= $CADENA_BUSCAR;

				//echo($query);

				$resultadom =$conexion->query($query);
				if ($resultadom == FALSE){

					$respuesta = $conexion->error;
				}else{
					$respuesta = 'OK';
				}


			}else{
				$respuesta = 'Se debe teclear algún valor a modificar: Cupo, Vuelo, Salida o Llegada.';
			}	

		}else{
			$respuesta = 'Debe indicar compañía, contrato, periodo de fechas, origen, destino y como mínimo un día de la semana.';
		}

		return $respuesta;										
	}



	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsTransportes_cupos($conexion, $filadesde, $usuario, $buscar_cia, $buscar_acuerdo, $buscar_subacuerdo, $buscar_origen, $buscar_destino, $buscar_fecha, $buscar_localizador, $buscar_fecha_depuracion_1, $buscar_fecha_depuracion_2, $buscar_fecha_depuracion_final, $buscar_dia_semana){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_cia = $buscar_cia;
		$this->Buscar_acuerdo = $buscar_acuerdo;
		$this->Buscar_subacuerdo = $buscar_subacuerdo;
		$this->Buscar_origen = $buscar_origen;
		$this->Buscar_destino = $buscar_destino;
		$this->Buscar_fecha = $buscar_fecha;
		$this->Buscar_localizador = $buscar_localizador;
		$this->Buscar_fecha_depuracion_1 = $buscar_fecha_depuracion_1;
		$this->Buscar_fecha_depuracion_2 = $buscar_fecha_depuracion_2;
		$this->Buscar_fecha_depuracion_final = $buscar_fecha_depuracion_final;
		$this->Buscar_dia_semana = $buscar_dia_semana;
	}
}

?>