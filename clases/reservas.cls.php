<?php

require 'clases_web/Reservas_fin.cls.php';
require 'interfaz_reserva.cls.php';

class clsReservas{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_localizador;
	var $buscar_referencia;
	var $buscar_referencia_agencia;
	var $buscar_folleto;
	var $buscar_cuadro;
	var $buscar_fecha_salida;
	var $buscar_fecha_reserva;
	var $buscar_minorista;
	var $buscar_oficina;
	var $buscar_telefono_oficina;
	var $buscar_nombre;

	//--------------------------------------------------

//-------------------------------------------------------------------------------
//------METODOS PARA LA PARTE SUPERIOR CON LOS DATOS GENERALES DE LA RESERVA-----
//-------------------------------------------------------------------------------
	function Cargar($recuperareferencia,$recuperafolleto,$recuperacuadro,$recuperaciudad_salida,$recuperapaquete,$recuperaregimen,$recuperafecha_salida,$recuperanombre_titular,$recuperaadultos,$recuperaninos,$recuperabebes,$recuperanovios,$recuperajubilados,$recuperaobservaciones,$recuperaagente,$recuperareferencia_agencia,$recuperaenvio,$recuperadivisa_actual,$recuperamodificacion_motivo,$recuperamodificacion_responsable,$recuperafree,$recuperamodificar,$recuperamodificar_comisionable,$recuperamodificar_detalle_comisionable,$recuperamodificar_no_comisionable,$recuperamodificar_detalle_no_comisionable,$recuperamodificar_comision,$recuperamodificar_usuario,$recuperavisa, $recuperaobservaciones_internas, $recuperaobservaciones_hotel, $recuperaobservaciones_clientes){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_referencia = $this->Buscar_referencia;
		$buscar_referencia_agencia = $this->Buscar_referencia_agencia;
		$buscar_folleto = $this->Buscar_folleto;
		$buscar_cuadro = $this->Buscar_cuadro;
		$buscar_fecha_salida = $this->Buscar_fecha_salida;
		$buscar_fecha_reserva = $this->Buscar_fecha_reserva;
		$buscar_minorista = $this->Buscar_minorista;
		$buscar_oficina = $this->Buscar_oficina;
		$buscar_telefono_oficina = $this->Buscar_telefono_oficina;
		$buscar_nombre = $this->Buscar_nombre;


		if($buscar_localizador != null){
			$CADENA_BUSCAR = " AND r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_referencia != null){
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
			$CADENA_BUSCAR = " and r.oficina = '".$buscar_oficina."'";
			if($buscar_telefono_oficina != null){
				$CADENA_BUSCAR .= $telef;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_telefono_oficina != null){
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
			$CADENA_BUSCAR = " and o.telefono = '".$buscar_telefono_oficina."'";
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_telefono_oficina != null){
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
			$CADENA_BUSCAR = " and o.telefono = '".$buscar_telefono_oficina."'";
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " and r.nombre_titular like '%".$buscar_nombre."%'";
		}else{
			$CADENA_BUSCAR = " and r.localizador = 0";    
  		}


		$resultado =$conexion->query("select 
				r.localizador,
				r.referencia,
				CASE r.situacion
					WHEN 'P' THEN 'PENDIENTE'
					WHEN 'F' THEN 'EN FIRME'
					WHEN 'A' THEN 'ANULADA'
					ELSE r.situacion
				END situacion,
				r.situacion codigo_situacion,
				CASE r.bloqueada
					WHEN 'S' THEN 'Si'
					WHEN 'N' THEN 'No'
					ELSE r.bloqueada
				END bloqueada,
				r.bloqueada_usuario,
				r.folleto,
				r.cuadro,
				r.ciudad_salida,
				r.paquete, 
				r.regimen,
				DATE_FORMAT(r.fecha_salida, '%d-%m-%Y') AS fecha_salida, 
				DATE_FORMAT(r.fecha_regreso, '%d-%m-%Y') AS fecha_regreso, 
				DATE_FORMAT(r.fecha_reserva, '%d-%m-%Y') AS fecha_reserva,
				r.usuario_reserva, 
				DATE_FORMAT(r.fecha_modificacion, '%d-%m-%Y') AS fecha_modificacion, 
				r.usuario_modificacion,
				r.dias_cuadro dias_cuadro,
				r.dias_total - dias_cuadro noches_extra,
				r.nombre_titular,
				r.tipo_pax,r.pax,
				r.adultos,
				r.ninos,
				r.bebes,
				r.novios,
				r.jubilados,
				r.observaciones,
				r.minorista,
				r.oficina,
				r.agente,
				r.referencia_agencia,
				r.envio,


				CASE r.precobro
					WHEN 'N' THEN 'Pendiente cobro'
					WHEN 'S' THEN 'Pagado'
					WHEN 'C' THEN 'Credito'
					ELSE r.situacion
				END precobro,


				r.operador_prepago,DATE_FORMAT(r.fecha_pago, '%d-%m-%Y') AS fecha_pago,
				di.nombre divisa_base,
				r.divisa_actual,
				r.cambio,r.redondeo,
				(r.pvp_calculado_comisionable + r.pvp_calculado_no_comisionable) calculado_pvp,
				r.pvp_calculado_comisionable,
				r.pvp_calculado_no_comisionable,
				r.pvp_calculado_comision,
				r.pvp_calculado_importe_comision,
				r.pvp_calculado_impuesto_comision,
				r.pvp_calculado_total,
				(r.pvp_comisionable + r.pvp_no_comisionable) pvp,
				r.pvp_comisionable,
				r.pvp_no_comisionable,
				r.pvp_comision,
				r.pvp_importe_comision,
				r.pvp_impuesto_comision,
				r.pvp_total,
				r.modificacion_motivo,
				r.modificacion_responsable,
				r.factura,
				DATE_FORMAT(r.factura_fecha_emision, '%d-%m-%Y') AS factura_fecha_emision,
				DATE_FORMAT(r.factura_fecha_salida, '%d-%m-%Y') AS factura_fecha_salida,
				DATE_FORMAT(r.factura_fecha_reserva, '%d-%m-%Y') AS factura_fecha_reserva,
				r.factura_pax,
				DATE_FORMAT(r.factura_primera_emision, '%d-%m-%Y') AS factura_primera_emision,
				DATE_FORMAT(r.factura_ultima_emision, '%d-%m-%Y') AS factura_ultima_emision,
				r.factura_situacion,
				r.factura_comisionable,
				r.factura_no_comisionable,
				r.factura_comision,
				r.factura_importe_comision,
				r.factura_impuesto_comision,
				r.factura_a_deducir,
				r.factura_total_pagar,
				r.free,DATE_FORMAT(r.anulacion_fecha, '%d-%m-%Y') AS anulacion_fecha,
				r.anulacion_usuario,
				r.tipos_descuento,
				r.modificar,
				r.modificar_comisionable,
				r.modificar_detalle_comisionable,
				r.modificar_no_comisionable,
				r.modificar_detalle_no_comisionable,
				r.modificar_comision,
				r.visa,
				r.alternativa_aerea,
				r.password, 
				m.NOMBRE agencia, 
				r.minorista,
				o.LOCALIDAD agencia_localidad, 
				o.DIRECCION agencia_direccion, 
				o.TELEFONO agencia_telefono,
				o.MAIL agencia_mail,
				pf.nombre nombre_folleto, 
				pc.nombre nombre_cuadro,
				r.observaciones_internas, 
				r.observaciones_hotel, 
				r.observaciones_clientes
			from hit_reservas r, hit_minoristas m, hit_oficinas o, hit_producto_folletos pf, hit_producto_cuadros pc, hit_divisas di
			where
				r.MINORISTA = m.ID	and r.MINORISTA = o.ID and r.OFICINA = o.OFICINA and r.FOLLETO = pf.codigo and r.FOLLETO = pc.folleto	and r.CUADRO = pc.cuadro
				and r.divisa_base = di.codigo ".$CADENA_BUSCAR." ORDER BY r.localizador");

		if ($resultado == FALSE){
			echo($CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}

		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'MANTENIMIENTO' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$reservas = array();
		if($recuperafolleto != null){	
			$reservas[0] = array (	"referencia" => $recuperareferencia ,"folleto" => $recuperafolleto ,"cuadro" => $recuperacuadro ,"ciudad_salida" => $recuperaciudad_salida ,"paquete" => $recuperapaquete,"regimen" => $recuperaregimen ,"fecha_salida" => $recuperafecha_salida ,"nombre_titular" => $recuperanombre_titular ,"adultos" => $recuperaadultos ,"ninos" => $recuperaninos ,"bebes" => $recuperabebes ,"novios" => $recuperanovios ,"jubilados" => $recuperajubilados ,"observaciones" => $recuperaobservaciones ,"minorista" => $recuperaminorista ,"oficina" => $recuperaoficina ,"agente" => $recuperaagente ,"referencia_agencia" => $recuperareferencia_agencia ,"envio" => $recuperaenvio ,"divisa_actual" => $recuperadivisa_actual ,"modificacion_motivo" => $recuperamodificacion_motivo ,"free" => $recuperafree ,"modificar" => $recuperamodificar ,"modificar_comisionable" => $recuperamodificar_comisionable ,"modificar_detalle_comisionable" => $recuperamodificar_detalle_comisionable ,"modificar_no_comisionable" => $recuperamodificar_no_comisionable ,"modificar_detalle_no_comisionable" => $recuperamodificar_detalle_no_comisionable ,"modificar_comision" => $recuperamodificar_comision ,"visa" => $recuperavisa, "observaciones_internas" => $recuperaobservaciones_internas, "observaciones_hotel" => $recuperaobservaciones_hotel, "observaciones_clientes" => $recuperaobservaciones_clientes);
		}else{
			for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($reservas,$fila);
			}
		}
		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $reservas;											
	}

	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_referencia = $this->Buscar_referencia;
		$buscar_referencia_agencia = $this->Buscar_referencia_agencia;
		$buscar_folleto = $this->Buscar_folleto;
		$buscar_cuadro = $this->Buscar_cuadro;
		$buscar_fecha_salida = $this->Buscar_fecha_salida;
		$buscar_fecha_reserva = $this->Buscar_fecha_reserva;
		$buscar_minorista = $this->Buscar_minorista;
		$buscar_oficina = $this->Buscar_oficina;
		$buscar_telefono_oficina = $this->Buscar_telefono_oficina;
		$buscar_nombre = $this->Buscar_nombre;


		if($buscar_localizador != null){
			$CADENA_BUSCAR = " AND r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_referencia != null){
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
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
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
			$CADENA_BUSCAR = " and r.oficina = '".$buscar_oficina."'";
			if($buscar_telefono_oficina != null){
				$CADENA_BUSCAR .= $telef;	
			}
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_telefono_oficina != null){
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
			$CADENA_BUSCAR = " and o.telefono = '".$buscar_telefono_oficina."'";
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_telefono_oficina != null){
			$nombre = " AND r.nombre_titular = '".$buscar_nombre."'";
			$CADENA_BUSCAR = " and o.telefono = '".$buscar_telefono_oficina."'";
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " and r.nombre_titular = '".$buscar_nombre."'";
		}else{
			$CADENA_BUSCAR = " and r.localizador = 0";    
  		}


		$resultadoc =$conexion->query("select 
				*
			from hit_reservas r, hit_minoristas m, hit_oficinas o, hit_producto_folletos pf, hit_producto_cuadros pc
			where
				r.MINORISTA = m.ID	and r.MINORISTA = o.ID and r.OFICINA = o.OFICINA and r.FOLLETO = pf.codigo and r.FOLLETO = pc.folleto	and r.CUADRO = pc.cuadro
				 ".$CADENA_BUSCAR." ORDER BY r.localizador");

		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo($CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/
		//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
		//																						       ESPECIFICO: Solo el nombre del formulario en la query
		if($numero_filas > 0){
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'MANTENIMIENTO' AND USUARIO = '".$Usuario."'");
			$Nfilas	 = $num_filas->fetch_assoc();																	  //------
			$cada = $Nfilas['LINEAS_MODIFICACION'] + 1;
			$combo_select = array();
			for ($num_fila = 1; $num_fila <= $numero_filas; $num_fila++) {
				
				if($num_fila == $cada){
					$combo_select[$num_fila] = array ( "inicio" => $cada - $Nfilas['LINEAS_MODIFICACION'], "fin" => $numero_filas);
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
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'MANTENIMIENTO' AND USUARIO = '".$Usuario."'");
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

	function Modificar($localizador,$referencia,$codigo_situacion,$bloqueada,$bloqueada_usuario,$folleto,$cuadro,$ciudad_salida,$paquete,$regimen,
	$fecha_salida,$fecha_regreso,$fecha_modificacion,$usuario_modificacion,$nombre_titular,$pax,$adultos,$ninos,$bebes,$novios,
	$observaciones,$minorista,$oficina,$clave_oficina,$agente,$referencia_agencia,$envio,$divisa_actual,
	$free,$modificar,$modificar_comisionable,$modificar_detalle_comisionable,$modificar_no_comisionable,$modificar_detalle_no_comisionable,
	$modificar_comision,$modificacion_motivo,$modificacion_responsable, $observaciones_internas, $observaciones_hotel, $observaciones_clientes, $alternativa_aerea){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;

		//$pax = $adultos + $ninos + $bebes;
		$pax = $adultos + $ninos;

		if($clave_oficina != null){

			$oficic =$conexion->query("select id, oficina from hit_oficinas where clave = '".$clave_oficina."'");
			$ofi = $oficic->fetch_assoc();
			$minorista = $ofi['id'];
			$oficina = $ofi['oficina'];
		}
		//echo($observaciones.'-');
		$query = "UPDATE hit_reservas SET ";
		$query .= " REFERENCIA = '".$referencia."'";
		$query .= ", SITUACION = '".$codigo_situacion."'";
		$query .= ", BLOQUEADA = '".$bloqueada."'";
		$query .= ", BLOQUEADA_USUARIO = '".$bloqueada_usuario."'";
		$query .= ", FOLLETO = '".$folleto."'";
		$query .= ", CUADRO = '".$cuadro."'";
		$query .= ", CIUDAD_SALIDA = '".$ciudad_salida."'";
		$query .= ", PAQUETE = '".$paquete."'";
		$query .= ", REGIMEN = '".$regimen."'";
		$query .= ", FECHA_SALIDA = '".date("Y-m-d",strtotime($fecha_salida))."'";
		$query .= ", FECHA_REGRESO = '".date("Y-m-d",strtotime($fecha_regreso))."'";
		$query .= ", FECHA_MODIFICACION = '".date("Y-m-d")."'";
		$query .= ", USUARIO_MODIFICACION = '".$Usuario."'";
		$query .= ", NOMBRE_TITULAR = '".$nombre_titular."'";
		$query .= ", PAX = '".$pax."'";
		$query .= ", ADULTOS = '".$adultos."'";
		$query .= ", NINOS = '".$ninos."'";
		$query .= ", BEBES = '".$bebes."'";
		$query .= ", NOVIOS = '".$novios."'";
		$query .= ", OBSERVACIONES = '".$observaciones."'";
		$query .= ", MINORISTA = '".$minorista."'";
		$query .= ", OFICINA = '".$oficina."'";
		$query .= ", AGENTE = '".$agente."'";
		$query .= ", REFERENCIA_AGENCIA = '".$referencia_agencia."'";
		$query .= ", ENVIO = '".$envio."'";
		$query .= ", DIVISA_ACTUAL = '".$divisa_actual."'";
		$query .= ", FREE = '".$free."'";
		$query .= ", MODIFICAR = '".$modificar."'";
		$query .= ", MODIFICAR_COMISIONABLE = '".$modificar_comisionable."'";
		$query .= ", MODIFICAR_DETALLE_COMISIONABLE = '".$modificar_detalle_comisionable."'";
		$query .= ", MODIFICAR_NO_COMISIONABLE = '".$modificar_no_comisionable."'";
		$query .= ", MODIFICAR_DETALLE_NO_COMISIONABLE = '".$modificar_detalle_no_comisionable."'";
		$query .= ", MODIFICAR_COMISION = '".$modificar_comision."'";
		$query .= ", MODIFICACION_MOTIVO = '".$modificacion_motivo."'";
		$query .= ", MODIFICACION_RESPONSABLE = '".$modificacion_responsable."'";
		$query .= ", OBSERVACIONES_INTERNAS = '".$observaciones_internas."'";
		$query .= ", OBSERVACIONES_HOTEL = '".$observaciones_hotel."'";
		$query .= ", OBSERVACIONES_CLIENTES = '".$observaciones_clientes."'";
		$query .= ", ALTERNATIVA_AEREA = '".$alternativa_aerea."'";
		$query .= " WHERE LOCALIZADOR = ".$localizador;


		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
			
			//Revisamos pasajeros

			$Revisa_pax = $this->Revisar_añadir_pasajeros($localizador, $pax);
			/*if($Revisa_pax == FALSE){
				$respuesta = $conexion->error;
			}*/
		}

		return $respuesta;											
	}

	function Anular($localizador){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;

		$politica_cancelacion = "";
		$observaciones = "";

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//GUARDAMOS INFORMACION DE LA RESERVA PARA ENVIAR MAILS DE CANCELACION A LA AGENCIA Y AL HOTEL EN CAS DE CANCELACION CORRECTA
		//ESTO LO HACEMOS ANTES PORQUE LOS SERVICIOS SE BORRAN EN EL PROCEDIMIENTO DE CANCELACION DE RESERVA
		
			//DATOS OBTENIDOS DE LA TABLA HIT_RESERVAS
			$datos_reserva =$conexion->query("SELECT folleto, cuadro, ciudad_salida, fecha_salida, paquete, regimen, adultos, ninos, 
													 bebes, novios, jubilados, minorista, oficina, agente, referencia_agencia, observaciones
			FROM hit_reservas WHERE LOCALIZADOR = '".$localizador."'");
			$odatos_reserva = $datos_reserva->fetch_assoc();


			$reserva_folleto = $odatos_reserva['folleto'];
			$reserva_cuadro = $odatos_reserva['cuadro'];
			$reserva_minorista = $odatos_reserva['minorista'];
			$reserva_oficina = $odatos_reserva['oficina'];
			
			//DATOS MAIL DE USUARIO
			$datos_usuario =$conexion->query("select email from hit_usuarios where usuario = '".$Usuario."'");
			$odatos_usuario = $datos_usuario->fetch_assoc();


			$mail_usuario = $odatos_usuario['email'];


			/*$reserva_ciudad = $odatos_reserva['ciudad_salida'];
			$reserva_opcion = 1;
			$reserva_fecha = $odatos_reserva['fecha_salida'];
			$reserva_paquete = $odatos_reserva['paquete'];
			$reserva_regimen = $odatos_reserva['regimen'];
			$adultos = $odatos_reserva['adultos'];
			$ninos = $odatos_reserva['ninos'];
			$bebes = $odatos_reserva['bebes'];
			$novios = $odatos_reserva['novios'];
			$jubilados = $odatos_reserva['jubilados'];
			$agente = $parametros['agente'];
			$referencia_agencia = $parametros['referencia_agencia'];
			$observaciones = $parametros['observaciones'];	*/	
		


			//OBTENEMOS NOMBRE Y PRODUCTO DEL VIAJE
			$datos_nombre_viaje =$conexion->query("select nombre, producto from hit_producto_cuadros where folleto = '".$reserva_folleto."' and cuadro = '".$reserva_cuadro."'");
			$onombre_viaje = $datos_nombre_viaje->fetch_assoc();
			$nombre_viaje = $onombre_viaje['nombre'];
			$producto_viaje = $onombre_viaje['producto'];

			//OBTENEMOS SALIDA, REGRESO, ESTADO
			$datos_salida_viaje =$conexion->query("select DATE_FORMAT(fecha_salida, '%d-%m-%Y') AS fecha_salida, DATE_FORMAT(fecha_regreso, '%d-%m-%Y') AS fecha_regreso, 
			case situacion 
				when 'P' then 'Servicios pendientes'
				when 'F' then 'Servicios confirmados'
			end situacion
			from hit_reservas where localizador = '".$localizador."'");
			$odatos_salida_viaje = $datos_salida_viaje->fetch_assoc();
			$datos_salida = $odatos_salida_viaje['fecha_salida'];
			$datos_regreso = $odatos_salida_viaje['fecha_regreso'];
			$datos_situacion = $odatos_salida_viaje['situacion'];

			$oReservas_fin = new clsReservas_fin($conexion, $localizador);
			$sdatos_agencia = $oReservas_fin->Cargar_datos_agencia($localizador);
			$sdatos_pasajeros = $oReservas_fin->Cargar_pasajeros();
			$sServicios = $oReservas_fin->Cargar_servicios();
			$sAereos = $oReservas_fin->Cargar_aereos();
					
			// !!OJO¡¡ estos datos de alojamiento ahora valen porque son estancias en un solo hotel. cuando esto cambie habrá que mostrarlo en bucle
			// leyendo la tabla de alojamientos de la reserva Y sacando toda la informacion de cada hotel
			$sNombre_hotel = $oReservas_fin->Cargar_nombre_hotel();
			$sPeriodo_hotel = $oReservas_fin->Cargar_periodo_hotel();
			$shoteles = $oReservas_fin->Cargar_hoteles();
			
			if ($producto_viaje != 'SVO' and $producto_viaje != 'OSV'){
										
				//Buscamos el mail del hotel
					$datos_hotel =$conexion->query("select max(reservas_mail) mail_hotel, max(reservas_responsable) responsable_hotel, max(interfaz_codigo) interfaz_codigo
														from hit_reservas_alojamientos pal,
																hit_alojamientos a
													where
														pal.ALOJAMIENTO = a.ID
														and pal.LOCALIZADOR = '".$localizador."'");
					$datos_alojamiento = $datos_hotel->fetch_assoc();
					$mail_hotel = $datos_alojamiento['mail_hotel'];
					$responsable_hotel = $datos_alojamiento['responsable_hotel'];
					$interfaz_codigo = $datos_alojamiento['interfaz_codigo'];
			}
		
		
		//LLAMAMOS AL PROCEDIMIENTO DE LA BASE DE DATOS PARA ANULAR RESERVAS
		$reserva =@$conexion->query("SELECT `RESERVAS_ANULA RESERVA`('".$localizador."','".$Usuario."') MENSAJE");

		
			if ($reserva == FALSE){
				$respuesta = 'No se ha podido anular la reserva. '.$conexion->error;
			}else{
				$resultado_reserva = $reserva->fetch_assoc();
				
				//--------------------------------------------------------------
				//--------------------------------------------------------------
				//ENVIAMOS MAIL DE AVISO DE CANCELACION DE RESERVA A LA AGENCIA
				
				//OBTENEMOS PRECIOS DESPUES DE LA ANULACION PARA MOSTRAR LOS GASTOS DE CANCELACION ENCASO DE HABERLOS.		
				$sdesglose = $oReservas_fin->Cargar_desglose('L');
				$spvp = $oReservas_fin->Cargar_pvp('L');				
				
				//MAIL PROVISIONAL USADO INICIALMENTE DURANTE EL DESARROLLO DE HITS
				/*$mensaje_html = "
				<TABLE border='1' bgcolor='#B0FFFF'>
					<TR colspan='2'>
						<TD>
							<img src='imagenes/Logo_3.jpg' align='center' height='30' width='250'>
						</TD>
					</TR>
					<TR>
						<TD>
							<b>Localizador</b>
						</TD>
						<TD>
							<b>".$resultado_reserva['MENSAJE']."</b>
						</TD>
					</TR>
				</TABLE>";*/

				////////////////////////////////////////////////////////////////////////////////////////////////
				////////////////////////////////////////////////////////////////////////////////////////////////
				///AQUI MONTAMOS EL HTML PARA EL CONTENIDO DEL CORREO ELECTRONICO

					$mensaje_html = "
					<html lang='es'>
					<head>	
						<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
					</head>

					<body>

					<img src='imagenes/Logo_Mail.jpg' align='center' height='60' width='300'>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:25px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>CONFIRMAMOS CANCELACION DE LA RESERVA:<strong> ".$localizador."</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Viaje: <strong>".$nombre_viaje."</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Salida: <strong>".$datos_salida."</strong>&nbsp;&nbsp;&nbsp;Regreso: <strong>".$datos_regreso."</strong>&nbsp;&nbsp;&nbsp;<strong>Servicios Cancelados</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Agencia</h1>

					<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>	

							<p style='font-size:15px;font-family:arial,serif;'>".$sdatos_agencia[0]['a_nombre']."</p>
							<p>".$sdatos_agencia[0]['a_direccion']." - (".$sdatos_agencia[0]['a_c_postal'].") ".$sdatos_agencia[0]['a_localidad'].". Telefono:  
							".$sdatos_agencia[0]['a_telefono'].". <a href='mailto:".$sdatos_agencia[0]['a_email']."'>".$sdatos_agencia[0]['a_email']."</a></p>
							<p>Agente: ".$sdatos_agencia[0]['a_agente']."</p>
							<p>Referencia agencia: ".$sdatos_agencia[0]['a_referencia_agencia']."</p>
							<p>Observaciones: ".$sdatos_agencia[0]['a_observaciones']."</p>

					</DIV>



					<h1 style='font-size:17px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Pasajeros</h1>

					<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	
						for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

							$mensaje_html .= "<p  style='font-size:13px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
							".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

							if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
								$mensaje_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
							}
							$mensaje_html .= "</p>";
							
						}
					$mensaje_html .= "</DIV>";

					//SERVICIOS
					$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Resumen de los servicios</h1>";

					//AEREOS
					$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Aereos</h1>";

					$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	

						for ($i = 0; $i < count($sAereos); $i++) {
								$mensaje_html .= "<h2 style='font-size:15px;font-family:arial,serif;line-height:120%;color:#000000;font-weight:normal;text-align:left;'>".$sAereos[$i]['v_ida_vuelta']."</h2>

							  <span style='font-size:13px;font-family:arial,serif;'><img src='imagenes/transportes/".$sAereos[$i]['v_codigo_cia']."_logo.jpg' alt='logo compañía aérea' height='20' width='60' />  &nbsp;&nbsp;Numero: <strong>".$sAereos[$i]['v_vuelo']."</strong>, <strong>".$sAereos[$i]['v_origen']." - ".$sAereos[$i]['v_destino']."</strong></span>

								<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;".$sAereos[$i]['v_fecha']."</strong>,<strong>  ".$sAereos[$i]['v_salida']." hs -  ".$sAereos[$i]['v_llegada']." hs</strong></span>

								<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;Pasajeros: <strong>".$sAereos[$i]['v_pax']."</strong></span>

								<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;Cancelado</strong></span>";

						}
					$mensaje_html .= "</DIV>";
	
					//ECHO($parametros['buscar_producto']);
					if ($producto_viaje != 'SVO' and $producto_viaje != 'OSV'){

						//ALOJAMIENTOS
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Alojamientos</h1>";

						$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	

							$mensaje_html .= "<h2 style='font-size:18px;font-family:arial,serif;line-height:120%;color:#000000;font-weight:normal;text-align:left;'><strong>".$sNombre_hotel[0]['h_nombre']."</strong></h2>";
							$mensaje_html .= "<p style='font-size:15px;font-family:arial,serif;'><strong>".$sPeriodo_hotel[0]['h_periodo']."</strong></p>";

							for ($i = 0; $i < count($shoteles); $i++) {

									$mensaje_html .= "<span style='font-size:13px;font-family:arial,serif;'><strong>".$shoteles[$i]['h_caracteristica']."</strong>
									- ".$shoteles[$i]['h_pax']." ".$shoteles[$i]['h_desglose_pax'].".
									".$shoteles[$i]['h_texto_regimen']." <strong>".$shoteles[$i]['h_regimen']."</strong></span>

									<span><strong>Cancelado</strong></span><br><br>";
									$politica_cancelacion .= $shoteles[$i]['h_politica_cancelacion'];
									$observaciones .= $shoteles[$i]['h_observaciones'];

							}
						$mensaje_html .= "</DIV>";

						//SERVICIOS
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Servicios</h1>";

						$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	


							for ($i = 0; $i < count($sServicios); $i++) {

									$mensaje_html .= "<span style='font-size:13px;font-family:arial,serif;'>Servicio <strong>".$sServicios[$i]['s_nombre']."</strong></span>
									<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;Fecha <strong>".$sServicios[$i]['s_fecha']."</strong></span>
									<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;".$sServicios[$i]['s_pax']." Personas</span>
									<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;Cancelado</strong></span><br><br>";

							}
						$mensaje_html .= "</DIV>";

					}

						//PRECIOS DESGLOSE
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Precios</h1>";

						/*$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	*/

							$mensaje_html .= "<table width='80%' style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#c7c7c7;padding:5px 5px 5px;clear:both;line-height:10px'>";

							$mensaje_html .= "<tr><td align='left'><strong>Detalle</strong></td><td align='right'><strong>Precio unidad</strong></td><td align='right'><strong>Cantidad</strong></td><td align='right'><strong>Total</strong></td></tr>";
							
							for ($i = 0; $i < count($sdesglose); $i++) {
									
									$mensaje_html .= "<tr><td align='left'><span style='font-size:13px;font-family:arial,serif;'>".$sdesglose[$i]['p_detalle']."</span></td>

									<td align='right'><span style='font-size:13px;font-family:arial,serif;'><strong>".$sdesglose[$i]['p_precio_pax']." Eur.</strong></span></td>

									<td align='right'><span style='font-size:13px;font-family:arial,serif;'>".$sdesglose[$i]['p_pax']." ";

									if($sdesglose[$i]['p_pax'] == 1){
										$mensaje_html .= "Persona";
									}else{
										$mensaje_html .= "Personas";
									}


									$mensaje_html .= "</td><td align='right'>
									<span style='font-size:13px;font-family:arial,serif;'><strong>".$sdesglose[$i]['p_total']." Eur.</strong></span></td>";

								$mensaje_html .= "</tr>";
							}
							$mensaje_html .= "</table>";
						/*$mensaje_html .= "</DIV>";*/




					 $mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px;background:#6f7073;clear:both;display:block;'>     
							<h1 style='font-size:1.3em;line-height:120%;color:#6f7073;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>
							<img src='imagenes/comun/icon-precio.png' alt='Precio Total' height='40' width='40' />

									<span style='font-size:18px;font-family:arial,serif;color:#FFFFFF;vertical-align: middle'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Servicios: <strong>".$spvp[0]['pvp_comis']." Eur.</strong></span>
									<span style='font-size:18px;font-family:arial,serif;color:#FFFFFF;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tasas: <strong>".$spvp[0]['pvp_tasas']." Eur.</strong></span>
									<span style='font-size:18px;font-family:arial,serif;color:#FFFFFF;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio Total: <strong>".$spvp[0]['pvp_total']." Eur.</strong></span>
							</h1>
					 </div>";

					$mensaje_html .= "<h1 style='font-size:1.5em;line-height:120%;color:#6f7073;margin:40px 40px 10px;font-weight:normal;text-align:left;clear:both;	display:block;'><strong>Lamentamos la cancelación. Gracias por trabajar con Hitravel.</strong></h1>";


					$mensaje_html .= "<section id='gastos_cancelacion'>";
					
						if ($producto_viaje == 'OVA' || $producto_viaje == 'OSV'){
						$mensaje_html .= "<h1 style='font-size:0.8em;
												font-family:arial,serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:left;
												clear:both;'>INFORMACION AÉREO - VUELOS CON RYANAIR</h1>
											<p style='font-size:0.8em;
												font-family:serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Es imprescindible que realices la facturación on-line e imprimas las tarjetas de embarque siguiendo el enlace indicado en el correo de confirmación que te enviamos de la compañía aérea o bien entrando directamente en la página de facturación de RyanAir seleccionando la opción 1, 2 o 3 y sigue con el resto de pasos. Si no facturas on-line, RyanAir te cobrará un gasto adicional en el aeropuerto antes de embarcar. Facturación en línea: Asignación general de asientos - Los clientes que no deseen pagar por un asiento asignado Premium o Regular pueden facturar online entre 7 días y 2 horas antes de cada vuelo y se le asignará un asiento de forma gratuita. Facturación online por adelantado - (desde 30 días hasta 8 días antes de cada vuelo) sólo está disponible para los clientes que eligen comprar un asiento asignado.</p>
																	
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Recuerda que los gastos de equipaje y/o tarjeta de crédito del vuelo, no incluidos en estos importes de cancelación, no son en ningún caso reembolsables.</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											La reserva puede ser cancelada o modificada enviando un e-mail a info@hitravel.es se deberá recibir en la Agencia en horario laboral de Lunes a Viernes de 9 a 19 hrs y Sábados de 9:30 a 13.30 hrs, para que se considere como efectiva. En caso contrario, la fecha de anulación o modificación se entenderá como efectiva a partir del siguiente día laborable. <br>
											</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											- Este presupuesto está sujeto a disponibilidad de plazas y tarifa en el momento de efectuar la reserva<br>
											- <strong>Este presupuesto no contempla los suplementos que la compañía aérea puede cargar en función del equipaje.</strong><br>
											- Es responsabilidad del viajero disponer de la documentación en regla necesaria para poder viajar al destino solicitado
											</p>";					
						}
					
						$mensaje_html .= "<h1 style='font-size:0.8em;
												font-family:arial,serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:left;
												clear:both;'>POLITICA DE CANCELACION/MODIFICACION</h1>
											<p style='font-size:0.8em;
												font-family:serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>- Vuelos que sean del grupo Iberia y Air Europa : emisión 10 días antes de la salida, excepto con Air Nostrum que la emisión será 10 días tras la confirmación de reserva, 100% gastos aéreo en el momento de la emisión.<br>
												- Vuelos que no sean del grupo Iberia emisión 24 horas después de la confirmación de la reserva 100% de gastos.<br
												- Vuelos low cost: emisión inmediata 100% gastos en el momento de confirmación de la reserva. La agencia Minorista deberá realizar el pago del aéreo el mismo día de la confirmación de reserva, enviado comprobante de pago a info@hitravel.es</p>";

						if($politica_cancelacion != ""){
							$mensaje_html .= "<p style='font-size:0.8em;
												font-family:serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>- Alojamientos: ".$politica_cancelacion."</p>";
						}

						$mensaje_html .= "<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Los gastos de gestión más los gastos de anulación, si los hubiere <br>
												- Los gastos de gestión por reserva, modificación total* y cancelación de los servicios solicitados se aplicarán en función del tiempo que medie desde la creación de la reserva, según el siguiente escalado:<br>
												- Hasta las 72 h posteriores desde la creación de la reserva: Sin gastos<br>
												- A partir de 72 h y hasta 7 días naturales desde la creación de la reserva: 25€<br>
												- Más de 7 días naturales desde la creación de la reserva: 55 €</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											- Una penalización, consistente en el 5% del total del viaje si el desistimiento se produce con más de diez días naturales y menos de quince días de antelación a la fecha del comienzo del viaje<br>
											- Una penalización consistente en el 15% entre los días 3 y 10, <br>
											- Una penalización consistente en el 25% dentro de las cuarenta y ocho horas anteriores a la salida. .  
											</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											- De no presentarse a la hora prevista para la salida, no tendrá derecho a devolución alguna de la cantidad abonada, salvo acuerdo entre las partes en otro sentido.<br>
											*Modificaciones totales: Cambio de todos los nombres de la reserva, cambio de destino, cambio de las dos fechas de viaje y cambio de tipo de venta.<br>
											Las reservas confirmadas entre 7 días y 2 días antes de la fecha de inicio del viaje, dispondrán de 24 hrs. para cancelar sin gastos excepto vuelos low cost que será el 100% de la emisión de los vuelos. Transcurrido dicho plazo se aplicarán los gastos de gestión arriba indicados, más los gastos de cancelación. Dentro de las 48 hrs. anteriores a la fecha de inicio del viaje se aplicarán los gastos generales.
											</p>
									</section>";
									
						$mensaje_html .= "<h1 style='font-size:0.8em;
														line-height:120%;
														color:#000000;
														margin:15px 40px 5px;
														font-weight:normal;
														text-align:left;
														clear:both;'>POLITICA CANCELACION GRUPOS</h1>
											
											<table style='margin:0px 20px 20px 40px; border: 1px solid #000;
												border-collapse: collapse;font-size:10px;font-family:arial,serif;color:#000000;font-weight:normal;text-align:left;'>
												<tr>
													<td></td>
													<td colspan='2' style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>Cancelación Parcial</td>
													<td rowspan='2' style='border: 1px solid #000;padding:0px 5px 0px 5px;text-align:center;'>Cancelación Total</td>
												</tr>
												<tr>
													<td></td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>-20 %Pax</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>+20 %Pax</td>
												</tr>												
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 60 a 45 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>5%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>10%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 44 a 31 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>10%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>20%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 30 a 21 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>25%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>30%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 20 a 11 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>30%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>50%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>40%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>Menos de 10 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
												</tr>
											</table>";
									
									

					$mensaje_html .= "</body></html>";
			
				
				////FIN MENSAJE CONTENIDO///////////////////////////////////////////////////////////////////////
				////////////////////////////////////////////////////////////////////////////////////////////////				
				
				
				
				//aqui buscamos el mail de la agencia obteniendolo de la reserva
				$datos_agencia =$conexion->query("select mail from hit_oficinas where ID = '".$reserva_minorista."' and OFICINA = '".$reserva_oficina."'");
				$datos_agenc = $datos_agencia->fetch_assoc();
				$direccion_correo = $datos_agenc['mail'];
				
				$nombre_destinatario = "Agencia de Viajes";
				$asunto = "HI TRAVEL - CANCELACION DE LA RESERVA: ".$localizador;
				$envio = enviar_mail_reservas($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario);
				//echo($envio);
				//---------------------------------------
				//---------------------------------------
				


					////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////
					//ENVIAMOS AL HOTEL LA CANCELACION DE LA RESERVA POR CORREO 
					//echo('interfaz-'$interfaz_codigo.'-');
					if($interfaz_codigo == ''){
						if ($producto_viaje != 'SVO' and $producto_viaje != 'OSV'){
											

							$asunto_hotel = "HITRAVEL - CANCELACION DE RESERVA DE ALOJAMIENTO: ".$localizador;

							/*echo('<pre>');
							print_r($sdatos_agencia);
							echo('</pre>');
							echo($sdatos_agencia[0]['a_nombre']);*/
						

							$mensaje_hotel_html = "
							<html lang='es'>
							<head>	
								<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
							</head>

							<body>

							<img src='imagenes/Logo_Mail.jpg' align='center' height='60' width='300'>

							<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:25px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Rogamos cancelen la reserva de alojamiento - <strong>Localizador: ".$localizador."</strong></h1>

							<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Viaje: <strong>".$nombre_viaje."</strong></h1>

							<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Salida: <strong>".$datos_salida."</strong>&nbsp;&nbsp;&nbsp;Regreso: <strong>".$datos_regreso."</strong>&nbsp;&nbsp;&nbsp;<strong>Servicios cancelados</strong></h1>


							<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Pasajeros</h1>

							<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	
							for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

								$mensaje_hotel_html .= "<p  style='font-size:13px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
								".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

								if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
									$mensaje_hotel_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
								}
								$mensaje_hotel_html .= "</p>";
								
							}
							$mensaje_hotel_html .= "</DIV>";

							//ALOJAMIENTOS
							$mensaje_hotel_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Alojamientos</h1>";

							$mensaje_hotel_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	

								$mensaje_hotel_html .= "<h2 style='font-size:18px;font-family:arial,serif;line-height:120%;color:#000000;font-weight:normal;text-align:left;'><strong>".$sNombre_hotel[0]['h_nombre']."</strong></h2>";
								$mensaje_hotel_html .= "<p style='font-size:15px;font-family:arial,serif;'><strong>".$sPeriodo_hotel[0]['h_periodo']."</strong></p>";

								for ($i = 0; $i < count($shoteles); $i++) {

										$mensaje_hotel_html .= "<span style='font-size:13px;font-family:arial,serif;'><strong>".$shoteles[$i]['h_caracteristica']."</strong>
										- ".$shoteles[$i]['h_pax']." ".$shoteles[$i]['h_desglose_pax'].".
										".$shoteles[$i]['h_texto_regimen']." <strong>".$shoteles[$i]['h_regimen']."</strong></span>

										<span><strong>Cancelado</strong></span><br><br>";
								}
							$mensaje_hotel_html .= "</DIV>";

							$mensaje_hotel_html .= "<h1 style='font-size:1.5em;line-height:120%;color:#6f7073;margin:40px 40px 10px;font-weight:normal;text-align:left;clear:both;	display:block;'><strong>Lamentamos las molestias ocasionadas.</strong></h1>";						
							
							$mensaje_hotel_html .= "</body></html>";
									
							//Enviamos mail llamando a lafuncion 'enviar_mail'
							$envio = enviar_mail_hotel($asunto_hotel, $mensaje_hotel_html, $mail_hotel, $mail_usuario, $responsable_hotel);
							
							//echo($envio);
						
						}
					}
					//-------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------


				

				$Mensaje = 'Reserva anulada: '.$resultado_reserva['MENSAJE']; //Esto se sustituira por un enlace a la pantalla de mantenimiento con el parametro localizador.
				$respuesta = $resultado_reserva['MENSAJE'];
			}
		return $respuesta;											
	}

	
	function Enviar_mail_agencia($localizador){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;

		$politica_cancelacion = "";
		$observaciones = "";

		$respuesta = '';
		$fecha_envio = date("d-m-Y  H:i"); 
		
					//OBTENEMOS SALIDA, REGRESO, ESTADO
					$datos_salida_viaje =$conexion->query("select DATE_FORMAT(fecha_salida, '%d-%m-%Y') AS fecha_salida, DATE_FORMAT(fecha_regreso, '%d-%m-%Y') AS fecha_regreso, 
					case situacion 
						when 'P' then 'Servicios pendientes'
						when 'F' then 'Servicios confirmados'
					end situacion,
					folleto, cuadro, minorista, oficina
					from hit_reservas where localizador = '".$localizador."'");
					$odatos_salida_viaje = $datos_salida_viaje->fetch_assoc();
					$datos_salida = $odatos_salida_viaje['fecha_salida'];
					$datos_regreso = $odatos_salida_viaje['fecha_regreso'];
					$datos_situacion = $odatos_salida_viaje['situacion'];
					$datos_folleto = $odatos_salida_viaje['folleto'];
					$datos_cuadro = $odatos_salida_viaje['cuadro'];
					$datos_minorista = $odatos_salida_viaje['minorista'];
					$datos_oficina = $odatos_salida_viaje['oficina'];

					//OBTENEMOS NOMBRE DEL VIAJE
					$datos_nombre_viaje =$conexion->query("select nombre, producto from hit_producto_cuadros where folleto = '".$datos_folleto."' and cuadro = '".$datos_cuadro."'");
					$onombre_viaje = $datos_nombre_viaje->fetch_assoc();
					$nombre_viaje = $onombre_viaje['nombre'];		
					$producto_viaje = $onombre_viaje['producto'];					
					

					$oReservas_fin = new clsReservas_fin($conexion, $localizador);
					$sdatos_agencia = $oReservas_fin->Cargar_datos_agencia($localizador);
					$sdatos_pasajeros = $oReservas_fin->Cargar_pasajeros();
					$sServicios = $oReservas_fin->Cargar_servicios();
					$sAereos = $oReservas_fin->Cargar_aereos();


					//CONTROL HAY VUELOS
					$datos_hay_vuelos =$conexion->query("select count(*) hay_vuelos from hit_reservas_aereos_trayectos where localizador = ".$localizador);
					$odatos_hay_vuelos = $datos_hay_vuelos->fetch_assoc();
					$hay_vuelos = $odatos_hay_vuelos['hay_vuelos'];

					//CONTROL HAY ALOJAMIENTOS
					$datos_hay_alojamientos =$conexion->query("select count(*) hay_alojamientos from hit_reservas_alojamientos where localizador = ".$localizador);
					$odatos_hay_alojamientos = $datos_hay_alojamientos->fetch_assoc();
					$hay_alojamientos = $odatos_hay_alojamientos['hay_alojamientos'];

					//CONTROL HAY SERVICIOS
					$datos_hay_servicios =$conexion->query("select count(*) hay_servicios from hit_reservas_servicios where localizador = ".$localizador);
					$odatos_hay_servicios = $datos_hay_servicios->fetch_assoc();
					$hay_servicios = $odatos_hay_servicios['hay_servicios'];
					
					// !!OJO¡¡ estos datos de alojamiento ahora valen porque son estancias en un solo hotel. cuando esto cambie habrá que mostrarlo en bucle
					// leyendo la tabla de alojamientos de la reserva Y sacando toda la informacion de cada hotel
					$sNombre_hotel = $oReservas_fin->Cargar_nombre_hotel();
					$sPeriodo_hotel = $oReservas_fin->Cargar_periodo_hotel();
					$shoteles = $oReservas_fin->Cargar_hoteles();
					
					$sdesglose = $oReservas_fin->Cargar_desglose('L');
					$spvp = $oReservas_fin->Cargar_pvp('L');

					//Llamada a la clase general de combos
					/*$oCombos = new clsGeneral($conexion);
					$comboTitulos = $oCombos->Cargar_combo_Pasajeros_sexo();*/


					/*echo('<pre>');
					print_r($sdatos_agencia);
					echo('</pre>');
					echo($sdatos_agencia[0]['a_nombre']);*/
					/* style='font-size:8.5pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#666666'*/

					/*$mensaje_html = "
					<html lang='es'>
					<head>	
						<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
					</head>

					<body>

					<img src='imagenes/Logo_Mail.jpg' align='center' height='60' width='300'>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:25px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Fecha de envío: <strong>".$fecha_envio."</strong></h1>
					
					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:25px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Informacion de la reserva - <strong>Localizador: ".$localizador."</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Viaje: <strong>".$nombre_viaje."</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Salida: <strong>".$datos_salida."</strong>&nbsp;&nbsp;&nbsp;Regreso: <strong>".$datos_regreso."</strong>&nbsp;&nbsp;&nbsp;<strong>".$datos_situacion."</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Agencia</h1>

					<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>	

							<p style='font-size:15px;font-family:arial,serif;'>".$sdatos_agencia[0]['a_nombre']."</p>
							<p>".$sdatos_agencia[0]['a_direccion']." - (".$sdatos_agencia[0]['a_c_postal'].") ".$sdatos_agencia[0]['a_localidad'].". Telefono:  
							".$sdatos_agencia[0]['a_telefono'].". <a href='mailto:".$sdatos_agencia[0]['a_email']."'>".$sdatos_agencia[0]['a_email']."</a></p>
							<p>Agente: ".$sdatos_agencia[0]['a_agente']."</p>
							<p>Referencia agencia: ".$sdatos_agencia[0]['a_referencia_agencia']."</p>
							<p>Observaciones: ".$sdatos_agencia[0]['a_observaciones']."</p>

					</DIV>



					<h1 style='font-size:17px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Pasajeros</h1>

					<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	
						for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

							$mensaje_html .= "<p  style='font-size:13px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
							".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

							//if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
								$mensaje_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
							//}
							$mensaje_html .= "</p>";
							
						}
					$mensaje_html .= "</DIV>";

					//SERVICIOS
					$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Resumen de los servicios</h1>";

					//AEREOS
					$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Aereos</h1>";

					$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	

						for ($i = 0; $i < count($sAereos); $i++) {
								$mensaje_html .= "<h2 style='font-size:15px;font-family:arial,serif;line-height:120%;color:#000000;font-weight:normal;text-align:left;'>".$sAereos[$i]['v_ida_vuelta']."</h2>

							  <span style='font-size:13px;font-family:arial,serif;'><img src='imagenes/transportes/".$sAereos[$i]['v_codigo_cia']."_logo.jpg' alt='logo compañía aérea' height='20' width='60' />  &nbsp;&nbsp;Numero: <strong>".$sAereos[$i]['v_vuelo']."</strong>, <strong>".$sAereos[$i]['v_origen']." - ".$sAereos[$i]['v_destino']."</strong></span>

								<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;".$sAereos[$i]['v_fecha']."</strong>,<strong>  ".$sAereos[$i]['v_salida']." hs -  ".$sAereos[$i]['v_llegada']." hs</strong></span>

								<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;Pasajeros: <strong>".$sAereos[$i]['v_pax']."</strong></span>

								<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;".$sAereos[$i]['v_disponibilidad']."</strong></span>";

						}
					$mensaje_html .= "</DIV>";
	
					//ECHO($parametros['buscar_producto']);
					if ($producto_viaje != 'SVO' and $producto_viaje != 'OSV'){

						//ALOJAMIENTOS
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Alojamientos</h1>";

						$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	

							$mensaje_html .= "<h2 style='font-size:18px;font-family:arial,serif;line-height:120%;color:#000000;font-weight:normal;text-align:left;'><strong>".$sNombre_hotel[0]['h_nombre']."</strong></h2>";
							$mensaje_html .= "<p style='font-size:15px;font-family:arial,serif;'><strong>".$sPeriodo_hotel[0]['h_periodo']."</strong></p>";

							for ($i = 0; $i < count($shoteles); $i++) {

									$mensaje_html .= "<span style='font-size:13px;font-family:arial,serif;'><strong>".$shoteles[$i]['h_caracteristica']."</strong>
									- ".$shoteles[$i]['h_pax']." ".$shoteles[$i]['h_desglose_pax'].".
									".$shoteles[$i]['h_texto_regimen']." <strong>".$shoteles[$i]['h_regimen']."</strong></span>

									<span><strong>".$shoteles[$i]['h_estado']."</strong></span><br><br>";

							}
						$mensaje_html .= "</DIV>";

						//SERVICIOS
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Servicios</h1>";

						$mensaje_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	


							for ($i = 0; $i < count($sServicios); $i++) {

									$mensaje_html .= "<span style='font-size:13px;font-family:arial,serif;'>Servicio <strong>".$sServicios[$i]['s_nombre']."</strong></span>
									<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;Fecha <strong>".$sServicios[$i]['s_fecha']."</strong></span>
									<span style='font-size:13px;font-family:arial,serif;'>&nbsp;&nbsp;".$sServicios[$i]['s_pax']." Personas</span>
									<span style='font-size:13px;font-family:arial,serif;'><strong>&nbsp;&nbsp;".$sServicios[$i]['s_estado']."</strong></span><br><br>";

							}
						$mensaje_html .= "</DIV>";

					}

						//PRECIOS DESGLOSE
						$mensaje_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Precios</h1>";



							$mensaje_html .= "<table width='80%' style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#c7c7c7;padding:5px 5px 5px;clear:both;line-height:10px'>";

							$mensaje_html .= "<tr><td align='left'><strong>Detalle</strong></td><td align='right'><strong>Precio unidad</strong></td><td align='right'><strong>Cantidad</strong></td><td align='right'><strong>Total</strong></td></tr>";
							
							for ($i = 0; $i < count($sdesglose); $i++) {
									
									$mensaje_html .= "<tr><td align='left'><span style='font-size:13px;font-family:arial,serif;'>".$sdesglose[$i]['p_detalle']."</span></td>

									<td align='right'><span style='font-size:13px;font-family:arial,serif;'><strong>".$sdesglose[$i]['p_precio_pax']." Eur.</strong></span></td>

									<td align='right'><span style='font-size:13px;font-family:arial,serif;'>".$sdesglose[$i]['p_pax']." ";

									if($sdesglose[$i]['p_pax'] == 1){
										$mensaje_html .= "Persona";
									}else{
										$mensaje_html .= "Personas";
									}


									$mensaje_html .= "</td><td align='right'>
									<span style='font-size:13px;font-family:arial,serif;'><strong>".$sdesglose[$i]['p_total']." Eur.</strong></span></td>";

								$mensaje_html .= "</tr>";
							}
							$mensaje_html .= "</table>";





					 $mensaje_html .= "<div style='-moz-border-radius:15px;border-radius:15px;background:#6f7073;clear:both;display:block;'>     
							<h1 style='font-size:1.3em;line-height:120%;color:#6f7073;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>
							<img src='imagenes/comun/icon-precio.png' alt='Precio Total' height='40' width='40' />

									<span style='font-size:18px;font-family:arial,serif;color:#FFFFFF;vertical-align: middle'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Servicios: <strong>".$spvp[0]['pvp_comis']." Eur.</strong></span>
									<span style='font-size:18px;font-family:arial,serif;color:#FFFFFF;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tasas: <strong>".$spvp[0]['pvp_tasas']." Eur.</strong></span>
									<span style='font-size:18px;font-family:arial,serif;color:#FFFFFF;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio Total: <strong>".$spvp[0]['pvp_total']." Eur.</strong></span>
							</h1>
					 </div>";

					$mensaje_html .= "<h1 style='font-size:1.5em;line-height:120%;color:#6f7073;margin:40px 40px 10px;font-weight:normal;text-align:left;clear:both;	display:block;'><strong>Gracias por confiar en Hi Travel.</strong></h1>";


					$mensaje_html .= "<section id='gastos_cancelacion'>";
					
						if ($producto_viaje == 'OVA' || $producto_viaje == 'OSV'){
						$mensaje_html .= "<h1 style='font-size:0.8em;
												font-family:arial,serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:left;
												clear:both;'>INFORMACION AÉREO - VUELOS CON RYANAIR</h1>
											<p style='font-size:0.8em;
												font-family:serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Es imprescindible que realices la facturación on-line e imprimas las tarjetas de embarque siguiendo el enlace indicado en el correo de confirmación que te enviamos de la compañía aérea o bien entrando directamente en la página de facturación de RyanAir seleccionando la opción 1, 2 o 3 y sigue con el resto de pasos. Si no facturas on-line, RyanAir te cobrará un gasto adicional en el aeropuerto antes de embarcar. Facturación en línea: Asignación general de asientos - Los clientes que no deseen pagar por un asiento asignado Premium o Regular pueden facturar online entre 7 días y 2 horas antes de cada vuelo y se le asignará un asiento de forma gratuita. Facturación online por adelantado - (desde 30 días hasta 8 días antes de cada vuelo) sólo está disponible para los clientes que eligen comprar un asiento asignado.</p>
																	
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Recuerda que los gastos de equipaje y/o tarjeta de crédito del vuelo, no incluidos en estos importes de cancelación, no son en ningún caso reembolsables.</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											La reserva puede ser cancelada o modificada enviando un e-mail a info@hitravel.es se deberá recibir en la Agencia en horario laboral de Lunes a Viernes de 9 a 19 hrs y Sábados de 9:30 a 13.30 hrs, para que se considere como efectiva. En caso contrario, la fecha de anulación o modificación se entenderá como efectiva a partir del siguiente día laborable. <br>
											</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											- Este presupuesto está sujeto a disponibilidad de plazas y tarifa en el momento de efectuar la reserva<br>
											- <strong>Este presupuesto no contempla los suplementos que la compañía aérea puede cargar en función del equipaje.</strong><br>
											- Es responsabilidad del viajero disponer de la documentación en regla necesaria para poder viajar al destino solicitado
											</p>";					
						}
					
						$mensaje_html .= "<h1 style='font-size:0.8em;
												font-family:arial,serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:left;
												clear:both;'>POLITICA DE CANCELACION/MODIFICACION</h1>
											<p style='font-size:0.8em;
												font-family:serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>- Vuelos que sean del grupo Iberia y Air Europa : emisión 10 días antes de la salida, excepto con Air Nostrum que la emisión será 10 días tras la confirmación de reserva, 100% gastos aéreo en el momento de la emisión.<br>
												- Vuelos que no sean del grupo Iberia emisión 24 horas después de la confirmación de la reserva 100% de gastos.<br
												- Vuelos low cost: emisión inmediata 100% gastos en el momento de confirmación de la reserva. La agencia Minorista deberá realizar el pago del aéreo el mismo día de la confirmación de reserva, enviado comprobante de pago a info@hitravel.es</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Los gastos de gestión más los gastos de anulación, si los hubiere <br>
												- Los gastos de gestión por reserva, modificación total* y cancelación de los servicios solicitados se aplicarán en función del tiempo que medie desde la creación de la reserva, según el siguiente escalado:<br>
												- Hasta las 72 h posteriores desde la creación de la reserva: Sin gastos<br>
												- A partir de 72 h y hasta 7 días naturales desde la creación de la reserva: 25€<br>
												- Más de 7 días naturales desde la creación de la reserva: 55 €</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											- Una penalización, consistente en el 5% del total del viaje si el desistimiento se produce con más de diez días naturales y menos de quince días de antelación a la fecha del comienzo del viaje<br>
											- Una penalización consistente en el 15% entre los días 3 y 10, <br>
											- Una penalización consistente en el 25% dentro de las cuarenta y ocho horas anteriores a la salida. .  
											</p>
											<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>
											- De no presentarse a la hora prevista para la salida, no tendrá derecho a devolución alguna de la cantidad abonada, salvo acuerdo entre las partes en otro sentido.<br>
											*Modificaciones totales: Cambio de todos los nombres de la reserva, cambio de destino, cambio de las dos fechas de viaje y cambio de tipo de venta.<br>
											Las reservas confirmadas entre 7 días y 2 días antes de la fecha de inicio del viaje, dispondrán de 24 hrs. para cancelar sin gastos excepto vuelos low cost que será el 100% de la emisión de los vuelos. Transcurrido dicho plazo se aplicarán los gastos de gestión arriba indicados, más los gastos de cancelación. Dentro de las 48 hrs. anteriores a la fecha de inicio del viaje se aplicarán los gastos generales.
											</p>
											
									</section>";

						$mensaje_html .= "<h1 style='font-size:0.8em;
														line-height:120%;
														color:#000000;
														margin:15px 40px 5px;
														font-weight:normal;
														text-align:left;
														clear:both;'>POLITICA CANCELACION GRUPOS</h1>
											
											<table style='margin:0px 20px 20px 40px; border: 1px solid #000;
												border-collapse: collapse;font-size:11px;font-family:arial,serif;color:#000000;font-weight:normal;text-align:left;'>
												<tr>
													<td></td>
													<td colspan='2' style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>Cancelación Parcial</td>
													<td rowspan='2' style='border: 1px solid #000;padding:0px 5px 0px 5px;text-align:center;'>Cancelación Total</td>
												</tr>
												<tr>
													<td></td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>-20 %Pax</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>+20 %Pax</td>
												</tr>												
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 60 a 45 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>5%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>10%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 44 a 31 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>10%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>20%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 30 a 21 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>25%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>30%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 20 a 11 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>30%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>50%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>40%</td>
												</tr>
												<tr>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>Menos de 10 días antes de la salida</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
													<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
												</tr>
											</table>";									

						$mensaje_html .= "<section id='notas_importantes'>
									<h1 style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:left;
												clear:both;'>NOTAS IMPORTANTES</h1>
									<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Precio y carburante el precio del viaje combinado ha sido calculado segun los tipos de cambio, tarifas de transporte, coste del carburante y tasas e impuestos aplicables en la fecha de inicio de la reserva. Cualquier variacion del precio de los citados elementos podra dar lugar a la revision del precio final del viaje comunicandolo con 21 dias de antelacion a la salida.</p>
									<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Los servicios reflejados en este presupuesto están pendientes de disponibilidad a la hora de efectuar la reserva. Se reconfirmarán los precios en el momento de confirmar la reserva. Los precios están cotizados en base a las tarifas y cambios de moneda vigentes a día de hoy, estando sujetos a cambios por posibles incrementos en el precio del combustible y clases aéreas disponibles. Todos los precios reflejados en este presupuesto son precios de venta al público.</p>
									<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>La realización de la reserva en firme implica la aceptación de la política de gastos de hi travel. Véase condiciones de gastos en nuestra web www.hitravel.es</p>
									<p style='font-size:0.8em;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>Recomendamos ofrecer a vuestros clientes nuestro seguro opcional de la compañía viasegur contra gastos de cancelación y ampliación de cobertura. Para una información más detallada diríjase a www.hitravel.es. Una vez confirmada la reserva no será reembolsable el importe del seguro. </p>
									
									</section>";

					$mensaje_html .= "</body></html>";*/






					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					///------------------NUEVO MAIL DE CONFIRMACION----------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------


					$mensaje_nuevo_html = "";

					if($datos_situacion == 'Servicios confirmados'){
						$texto_confirmacion = 'CONFIRMACION DE LA RESERVA';
					}else{
						$texto_confirmacion = 'INFORMACION DE LA RESERVA';	
					}

					$mensaje_nuevo_html .= "<body bgcolor='#f5f5f5' align='center'>
									<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td align='center'>
										<table width='770' border='0' align='center' cellpadding='0' cellspacing='0'><tr><td bgcolor='#FFFFFF'><a href='http://hitravel.es/' title='HI TRAVEL'><img src='imagenes/cab.jpg' alt='HI TRAVEL' border='1'></a></td></tr>"; 
					// Nombre del viaje y fecha de envio
					$mensaje_nuevo_html .= "<tr><td bgcolor='#2D5E47' style='padding:5px 0 5px 60px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif;'><span style='font-size: 40px;'>".$nombre_viaje."</span></p></td></tr>

						<tr><td height='60' valign='top'><table width='100%' border='0' cellspacing='0' cellpadding='0'>
					    	<tr>
					      <td valign='middle' style='padding:15px 0 15px 20px; color:#b9d305; font-family: Verdana, Geneva, sans-serif; font-size: 20px; font-weight:bold;'>".$texto_confirmacion;


					 $mensaje_nuevo_html .= "</td>
					      <td align='right' valign='middle' style='padding: 15px 0px 15px 0px; color:#361DB7; font-family: Verdana, Geneva, sans-serif; font-size: 14px; '>Enviado el ".$fecha_envio."</td>
					    	</tr>
					  </table></td>
					</tr>";

					  if($datos_situacion == 'Servicios confirmados'){
					  	 $icono_situacion = 'ico_1.jpg';
					  }else{
					  	 $icono_situacion = 'ico_4.jpg';
					  }

					$mensaje_nuevo_html .= "<tr>
					  <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
					    <tr>

					      <td height='60' bgcolor='#361DB7' align='center' style='padding:0px 0px 0px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 20px;'>Localizador: 
					        <span style='color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 25px; font-weight:bold;'>".$localizador."</span>
					      </td>

					      <td height='60' bgcolor='#b9d305' align='center' style='padding:0px 0px 0px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 25px;'>
					      <img src='imagenes/".$icono_situacion."'  border='0' width='25' height='25'>&nbsp;&nbsp;".$datos_situacion."</td>
					    </tr>
					  </table></td>
					</tr>";


					      // DATOS GENERALES
					      $mensaje_nuevo_html .= "<tr><td align='center' bgcolor='#FFFFFF'>
					      						<table width='722' border='0' cellspacing='0' cellpadding='0'>
					      				      			<tr><td>&nbsp;</td></tr>
					      				      			<tr><td valign='middle' height='50' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 24px;'><img src='imagenes/ico_datos_generales_3.jpg' border='0' width='30' height='30'>&nbsp;Datos generales de su viaje</td></tr>";


					 // PASAJEROS
					      $mensaje_nuevo_html .= "<tr><td bgcolor='#F3F4EB' style='padding: 20px 30px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif;'><span style='font-size:14px;'>Sres:</span><br><span style='font-size: 20px;'>";
						
						for ($i = 0; $i < count($sdatos_pasajeros); $i++) {
							$mensaje_nuevo_html .= $sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." (".$sdatos_pasajeros[$i]['pax_tipo'];

								if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
									$mensaje_nuevo_html .= " - ".$sdatos_pasajeros[$i]['pax_edad']." años";
								}
								$mensaje_nuevo_html .= ")<br>";
							
						}
	

					      $mensaje_nuevo_html .= "</span></td></tr><tr><td valign='top'>&nbsp;</td></tr>";

					 //FECHAS
					      $mensaje_nuevo_html .= "<tr><td valign='top'><table width='722' border='0' cellspacing='0' cellpadding='0'>";
					      // Fecha de salida
					      $mensaje_nuevo_html .= "<tr><td width='200' style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Fecha de Salida</td><td width='522' bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$datos_salida."</td></tr>";

					      $mensaje_nuevo_html .= "<tr><td width='200' style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Fecha de Regreso</td><td width='522' bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$datos_regreso."</td></tr>";
					     

					$mensaje_nuevo_html .= "</table></td></tr>";


					// DATOS AGENCIA
					 $mensaje_nuevo_html .="<tr><td valign='top'>&nbsp;</td></tr><tr><td valign='top'><table width='722' border='0' cellspacing='0' cellpadding='0'>
					 						<tr><td width='200' style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Agencia</td><td width='522' bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_nombre']."</td>
					 						</tr>";

					      $mensaje_nuevo_html .="<tr><td style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Oficina</td><td bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_direccion']." - (".$sdatos_agencia[0]['a_c_postal'].") ".$sdatos_agencia[0]['a_localidad']."</td></tr>";

					      $mensaje_nuevo_html .="<tr><td style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Agente</td><td bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_agente']."</td></tr>";

					      $mensaje_nuevo_html .="<tr><td style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Referencia Agencia</td><td bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_referencia_agencia']."</td></tr>";

					      $mensaje_nuevo_html .="<tr><td style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Observaciones</td><td bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$sdatos_agencia[0]['a_observaciones']."</td></tr>";

					$mensaje_nuevo_html .="</tr></table></td></tr>";




					  // VUELOS
					  if($hay_vuelos > 0){
						  $mensaje_nuevo_html .="
						  <tr><td valign='top'>&nbsp;</td></tr>
						  <tr><td height='50' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 24px;'><img src='imagenes/ico_avion_4.jpg' border='0' width='30' height='30'>&nbsp;Vuelos</td></tr>
						  <tr>
							<td valign='top'>
								<table width='100%' border='1' cellspacing='0' cellpadding='0'>
						  			<tr>
										  <td bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Fecha</td>
										  <td bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Orig</td>
										  <td bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Dest</td>
										  <td bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Cía.</td>
										  
										  <td bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Vuelo</td>
										  <td bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Clase</td>
										  <td bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Sal</td>

										  <td bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Lleg</td>
										  <td bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Plazas</td>	
										  <td bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Estado</td>
						  			</tr>
						  			<tr>
						      				<td colspan='10'>&nbsp;</td>
						    			</tr>";

									for ($i = 0; $i < count($sAereos); $i++) {

										$mensaje_nuevo_html .= "

										 <tr>
										      	<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sAereos[$i]['v_fecha']."</td>
										       	<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sAereos[$i]['v_origen']."</td>
										       	<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sAereos[$i]['v_destino']."</td>
										       	<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'><img src='imagenes/transportes/".$sAereos[$i]['v_codigo_cia']."_logo.jpg' border='0' width='60' alt='".$sAereos[$i]['v_codigo_cia']."''></td>
										       	
										       	<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sAereos[$i]['v_codigo_cia']."-".$sAereos[$i]['v_vuelo']."</td>
										       	<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sAereos[$i]['v_clase']."</td>
										       	<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sAereos[$i]['v_salida']."</td>
										       	<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sAereos[$i]['v_llegada']."</td>
										       	<td style='text-align:center; text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sAereos[$i]['v_pax']."</td>
										       	<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>".$sAereos[$i]['v_disponibilidad']."</td>

										</tr>";



									}


						$mensaje_nuevo_html .="</table></td></tr>";
					}


					// ALOJAMIENTOS
					if($hay_alojamientos > 0){
						$mensaje_nuevo_html .= "
						<tr>
							<td valign='top'>&nbsp;</td></tr><tr><td height='50' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 24px;'><img src='imagenes/ico_alojamiento_4.jpg' border='0' width='30' height='30'>&nbsp;Alojamientos</td>
						</tr>
						<tr>
						  <td valign='top'>
						  	<table width='100%' border='1' cellspacing='0' cellpadding='0'>
						    		<tr>
						    			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Hotel</td>
						      			<td colspan='3' width='60%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Estancia</td>
						    		</tr>";

						 			$mensaje_nuevo_html .= "<tr>   		
										<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px; font-weight:bold;'>".$sNombre_hotel[0]['h_nombre']."<br></td>
										<td colspan='3' style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px;'>".$sPeriodo_hotel[0]['h_periodo']."<br></td>
								    		</tr>";		

									$mensaje_nuevo_html .= "<tr>
								      			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Habitación</td>
								      			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Distribución</td>
								      			<td width='5%' bgcolor='#2D5E47' style='text-align:center; padding:5px 0px 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Rég</td>
								      			<td width='15%' bgcolor='#2D5E47' style='text-align:center; padding:5px 0px 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Estado</td>
								    		</tr>";

								for ($i = 0; $i < count($shoteles); $i++) {

						        			$mensaje_nuevo_html .= "<tr>
										<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_caracteristica']."<br></td>
										<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_pax']." ".$shoteles[$i]['h_desglose_pax']."<br></td>
										<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_regimen_siglas']."<br></td>
						  				<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>".$shoteles[$i]['h_estado']."</td>
						  				</tr>";	

						  			$politica_cancelacion .= $shoteles[$i]['h_politica_cancelacion'];
									$observaciones .= $shoteles[$i]['h_observaciones'];							

								}
								if($sNombre_hotel[0]['h_comunidad'] == 'BAL' and $observaciones == ""){
									$observaciones = 'TASA ECOLOGICA: Todos los clientes con estancia en este establecimiento a partir del 01.07.16 (incluidos aquellos que hayan iniciado su estancia con anterioridad) se ven afectados por la obligatoriedad del pago del impuesto (ECOTASA) aprobado por el Gobierno Balear. Dicho pago deberá ser efectuado directamente en el establecimiento y en función de la categoría oficial del mismo. Información detallada en: www.caib.es/eboibfront/es/2016/10470/578257/ley-2-2016-de-30-de-marzo-del-impuesto-sobre-estan';
								}

						$mensaje_nuevo_html .= "</table></td></tr>";
					}



					// SERVICIOS
					if($hay_servicios > 0){
						$mensaje_nuevo_html .= "
						<tr>
							<td valign='top'>&nbsp;</td></tr><tr><td height='50' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 24px;'><img src='imagenes/ico_servicios_2.png' border='0' width='30' height='30'>&nbsp;Servicios</td>
						</tr>
						<tr>
						  <td valign='top'>
						  	<table width='100%' border='1' cellspacing='0' cellpadding='0'>
						    		<tr>
						      			<td width='20%' bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Fecha</td>
						      			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Nombre</td>
						      			<td width='20%' bgcolor='#2D5E47' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Personas</td>
						      			<td width='20%' bgcolor='#2D5E47' style='text-align:center; padding:5px 0px 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Estado</td>
						    		</tr>";

								for ($i = 0; $i < count($sServicios); $i++) {

						        			$mensaje_nuevo_html .= "<tr>
										<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sServicios[$i]['s_fecha']."<br></td>
										<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sServicios[$i]['s_nombre']."<br></td>
										<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sServicios[$i]['s_pax']."<br></td>
						  				<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>".$sServicios[$i]['s_estado']."</td>
						  				</tr>";								

								}



						$mensaje_nuevo_html .= "</table></td></tr>";
					}




					// PRECIOS
					$mensaje_nuevo_html .= "
					<tr>
						<td valign='top'>&nbsp;</td>
					</tr>
					<tr>
						<td valign='top'>&nbsp;</td>
					</tr>
					<tr>
						<td valign='top'>&nbsp;</td>
					</tr>
					<tr><td bgcolor='#361DB7' style='padding:5px 0 5px 20px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif;'><span style='font-size: 30px;'>PRECIOS</span></p></td></tr>
					<tr>
					  <td valign='top'>
					  	<table width='100%' border='1' cellspacing='0' cellpadding='0'>
					    		<tr>
					      			<td width='45%' bgcolor='#361DB7' style='padding:5px 0px 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Concepto</td>
					      			<td width='20%' bgcolor='#361DB7' style='text-align:right; padding:5px 10px 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Importe</td>
					      			<td width='15%' bgcolor='#361DB7' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Plazas</td>
					      			<td width='20%' bgcolor='#361DB7' style='text-align:right; padding:5px 10px 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Subtotal</td>
					    		</tr>";

							for ($i = 0; $i < count($sdesglose); $i++) {

								$p_precio_pax = number_format ($sdesglose[$i]['p_precio_pax'], 2 , "," ,  "." );
								$p_total = number_format ($sdesglose[$i]['p_total'], 2 , "," ,  "." );

					        			$mensaje_nuevo_html .= "<tr>
									<td style='padding:2px 0px 2px 10px; color:#361DB7; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sdesglose[$i]['p_detalle']."<br></td>
									<td style='text-align:right; padding:2px 10px 2px 10px; color:#361DB7; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$p_precio_pax." €<br></td>
									<td style='text-align:center; padding:2px 0 2px 0px; color:#361DB7; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$sdesglose[$i]['p_pax']."<br></td>
					  				<td style='text-align:right; padding:2px 10px 2px 0px; color:#361DB7; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>".$p_total." €</td>
					  				</tr>";								

							}

					$pvp_comis = number_format ($spvp[0]['pvp_comis'], 2 , "," ,  "." );
					$pvp_tasas = number_format ($spvp[0]['pvp_tasas'], 2 , "," ,  "." );
					$pvp_total =  number_format ($spvp[0]['pvp_total'], 2 , "," ,  "." );

					$mensaje_nuevo_html .= "<tr>
										<td bgcolor='#361DB7' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif;'><span style='font-size: 20px;'>Servicios<br> ".$pvp_comis."€</span></p></td>
										<td bgcolor='#361DB7' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif;'><span style='font-size: 20px;'>Tasas<br>".$pvp_tasas."€</span></p></td>
										<td colspan='2' bgcolor='#361DB7' style='text-align:center; padding:5px 0 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif;'><span style='font-size: 20px;'>Precio Total<br>".$pvp_total."€</span></p></td>
									</tr>";


					$mensaje_nuevo_html .= "</table></td></tr>";


					//PIE DE MAIL

					$mensaje_nuevo_html .= "<h1 style='font-size:1.5em;line-height:120%;color:#2D5E47;margin:40px 40px 10px;font-weight:normal;text-align:left;clear:both;	display:block;'><strong>Gracias por reservar con Hi Travel.</strong></h1>";


					//GASTOS DE CANCELACION Y NOTAS

					$mensaje_nuevo_html .= "
					<tr>
						<td valign='top'>";


					$mensaje_nuevo_html .= "<section id='gastos_cancelacion'>";
										
					if ($producto_viaje == 'OVA' || $producto_viaje == 'OSV'){
					$mensaje_nuevo_html .= "<h1 style='font-size:0.8em;
											font-family:arial,serif;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:left;
											clear:both;'>INFORMACION AÉREO - VUELOS CON RYANAIR</h1>
										<p style='font-size:0.8em;
											font-family:serif;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>Es imprescindible que realices la facturación on-line e imprimas las tarjetas de embarque siguiendo el enlace indicado en el correo de confirmación que te enviamos de la compañía aérea o bien entrando directamente en la página de facturación de RyanAir seleccionando la opción 1, 2 o 3 y sigue con el resto de pasos. Si no facturas on-line, RyanAir te cobrará un gasto adicional en el aeropuerto antes de embarcar. Facturación en línea: Asignación general de asientos - Los clientes que no deseen pagar por un asiento asignado Premium o Regular pueden facturar online entre 7 días y 2 horas antes de cada vuelo y se le asignará un asiento de forma gratuita. Facturación online por adelantado - (desde 30 días hasta 8 días antes de cada vuelo) sólo está disponible para los clientes que eligen comprar un asiento asignado.</p>
																
										<p style='font-size:0.8em;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>Recuerda que los gastos de equipaje y/o tarjeta de crédito del vuelo, no incluidos en estos importes de cancelación, no son en ningún caso reembolsables.</p>
										<p style='font-size:0.8em;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>
										La reserva puede ser cancelada o modificada enviando un e-mail a info@hitravel.es se deberá recibir en la Agencia en horario laboral de Lunes a Viernes de 9 a 19 hrs y Sábados de 9:30 a 13.30 hrs, para que se considere como efectiva. En caso contrario, la fecha de anulación o modificación se entenderá como efectiva a partir del siguiente día laborable. <br>
										</p>
										<p style='font-size:0.8em;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>
										- Este presupuesto está sujeto a disponibilidad de plazas y tarifa en el momento de efectuar la reserva<br>
										- <strong>Este presupuesto no contempla los suplementos que la compañía aérea puede cargar en función del equipaje.</strong><br>
										- Es responsabilidad del viajero disponer de la documentación en regla necesaria para poder viajar al destino solicitado
										</p>";					
					}
				
					$mensaje_nuevo_html .= "<h1 style='font-size:0.8em;
											font-family:arial,serif;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:left;
											clear:both;'>POLITICA DE CANCELACION/MODIFICACION</h1>
										<p style='font-size:0.8em;
											font-family:serif;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>- Vuelos que sean del grupo Iberia y Air Europa : emisión 10 días antes de la salida, excepto con Air Nostrum que la emisión será 10 días tras la confirmación de reserva, 100% gastos aéreo en el momento de la emisión.<br>
											- Vuelos que no sean del grupo Iberia emisión 24 horas después de la confirmación de la reserva 100% de gastos.<br>
											- Vuelos low cost: emisión inmediata 100% gastos en el momento de confirmación de la reserva. La agencia Minorista deberá realizar el pago del aéreo el mismo día de la confirmación de reserva, enviado comprobante de pago a info@hitravel.es</p>";
						if($politica_cancelacion != ""){
							$mensaje_nuevo_html .= "<p style='font-size:0.8em;
												font-family:serif;
												line-height:120%;
												color:#6f7073;
												margin:20px 40px 5px;
												font-weight:normal;
												text-align:justify;'>- Alojamientos: ".$politica_cancelacion."</p>";
						}


							$mensaje_nuevo_html .= "<p style='font-size:0.8em;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>Los gastos de gestión más los gastos de anulación, si los hubiere <br>
											- Los gastos de gestión por reserva, modificación total* y cancelación de los servicios solicitados se aplicarán en función del tiempo que medie desde la creación de la reserva, según el siguiente escalado:<br>
											- Hasta las 72 h posteriores desde la creación de la reserva: Sin gastos<br>
											- A partir de 72 h y hasta 7 días naturales desde la creación de la reserva: 25€<br>
											- Más de 7 días naturales desde la creación de la reserva: 55 €</p>
										<p style='font-size:0.8em;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>
										- Una penalización, consistente en el 5% del total del viaje si el desistimiento se produce con más de diez días naturales y menos de quince días de antelación a la fecha del comienzo del viaje<br>
										- Una penalización consistente en el 15% entre los días 3 y 10, <br>
										- Una penalización consistente en el 25% dentro de las cuarenta y ocho horas anteriores a la salida. .  
										</p>
										<p style='font-size:0.8em;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>
										- De no presentarse a la hora prevista para la salida, no tendrá derecho a devolución alguna de la cantidad abonada, salvo acuerdo entre las partes en otro sentido.<br>
										*Modificaciones totales: Cambio de todos los nombres de la reserva, cambio de destino, cambio de las dos fechas de viaje y cambio de tipo de venta.<br>
										Las reservas confirmadas entre 7 días y 2 días antes de la fecha de inicio del viaje, dispondrán de 24 hrs. para cancelar sin gastos excepto vuelos low cost que será el 100% de la emisión de los vuelos. Transcurrido dicho plazo se aplicarán los gastos de gestión arriba indicados, más los gastos de cancelación. Dentro de las 48 hrs. anteriores a la fecha de inicio del viaje se aplicarán los gastos generales.
										</p>
								</section>

					 			<h1 style='font-size:0.8em;
													line-height:120%;
													color:#000000;
													margin:15px 40px 5px;
													font-weight:normal;
													text-align:left;
													clear:both;'>POLITICA CANCELACION GRUPOS</h1>
										
										<table style='margin:0px 20px 20px 40px; border: 1px solid #000;
											border-collapse: collapse;font-size:10px;font-family:arial,serif;color:#000000;font-weight:normal;text-align:left;'>
											<tr>
												<td></td>
												<td colspan='2' style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>Cancelación Parcial</td>
												<td rowspan='2' style='border: 1px solid #000;padding:0px 5px 0px 5px;text-align:center;'>Cancelación Total</td>
											</tr>
											<tr>
												<td></td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>-20 %Pax</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>+20 %Pax</td>
											</tr>												
											<tr>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 60 a 45 días antes de la salida</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>5%</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>10%</td>
											</tr>
											<tr>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 44 a 31 días antes de la salida</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>10%</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>20%</td>
											</tr>
											<tr>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 30 a 21 días antes de la salida</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>0%</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>25%</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>30%</td>
											</tr>
											<tr>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>De 20 a 11 días antes de la salida</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>30%</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>50%</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>40%</td>
											</tr>
											<tr>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>Menos de 10 días antes de la salida</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
												<td style='border: 1px solid #000;padding:2px 5px 2px 5px;text-align:center;'>100%</td>
											</tr>
										</table>									

								<section id='notas_importantes'>
								<h1 style='font-size:0.8em;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:left;
											clear:both;'>NOTAS IMPORTANTES</h1>";

							if($observaciones != ""){
								$mensaje_nuevo_html .= "<p style='font-size:0.8em;
													font-family:serif;
													line-height:120%;
													color:#6f7073;
													margin:20px 40px 5px;
													font-weight:normal;
													text-align:justify;'>- Alojamientos: ".$observaciones."</p>";
							}

							$mensaje_nuevo_html .= "<p style='font-size:0.8em;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>Precio y carburante el precio del viaje combinado ha sido calculado segun los tipos de cambio, tarifas de transporte, coste del carburante y tasas e impuestos aplicables en la fecha de inicio de la reserva. Cualquier variacion del precio de los citados elementos podra dar lugar a la revision del precio final del viaje comunicandolo con 21 dias de antelacion a la salida.</p>
								<p style='font-size:0.8em;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>Los servicios reflejados en este presupuesto están pendientes de disponibilidad a la hora de efectuar la reserva. Se reconfirmarán los precios en el momento de confirmar la reserva. Los precios están cotizados en base a las tarifas y cambios de moneda vigentes a día de hoy, estando sujetos a cambios por posibles incrementos en el precio del combustible y clases aéreas disponibles. Todos los precios reflejados en este presupuesto son precios de venta al público.</p>
								<p style='font-size:0.8em;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>La realización de la reserva en firme implica la aceptación de la política de gastos de hi travel. Véase condiciones de gastos en nuestra web www.hitravel.es</p>
								<p style='font-size:0.8em;
											line-height:120%;
											color:#6f7073;
											margin:20px 40px 5px;
											font-weight:normal;
											text-align:justify;'>Recomendamos ofrecer a vuestros clientes nuestro seguro opcional de la compañía viasegur contra gastos de cancelación y ampliación de cobertura. Para una información más detallada diríjase a www.hitravel.es. Una vez confirmada la reserva no será reembolsable el importe del seguro. </p>
								
								</section>




						</td>
					</tr>";




					$mensaje_nuevo_html .= "</table><p>&nbsp;</p></td></tr></table></body></html>";


					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//---------------------------------FIN NUEVO MAIL DE CONFIRMACION----------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------





					





				//ENVIAMOS MAIL A LA AGENCIA
									//aqui buscamos el mail de la agencia obteniendolo de la reserva
				$datos_agencia =$conexion->query("select mail from hit_oficinas where ID = '".$datos_minorista."' and OFICINA = '".$datos_oficina."'");
				$datos_agenc = $datos_agencia->fetch_assoc();
				$direccion_correo = $datos_agenc['mail'];
				
				// Para pruebas usamos un mail interno
				/*$direccion_correo = "jfrias@hitravel.es";*/
				
				
				$nombre_destinatario = "Agencia de Viajes";
				$asunto = "HI TRAVEL - INFORMACION DE LA RESERVA: ".$localizador;
				//$asunto = "HI TRAVEL - OFERTAS";

				//TAMBIEN ENVIAMOS MAIL AL USUARIO QUE HA REALIZADO EL CAMBIO
				$datos_usuario =$conexion->query("select email from hit_usuarios where usuario = '".$Usuario."'");
				$odatos_usuario = $datos_usuario->fetch_assoc();
				$mail_usuario = $odatos_usuario['email'];

				$envio = reenviar_mail_reservas($asunto, $mensaje_nuevo_html, $direccion_correo, $mail_usuario, $nombre_destinatario);
				
				if($envio == 'OK'){
					$respuesta = $direccion_correo.", ".$mail_usuario;
				}				
	
				
				
				//echo($respuesta);
				//---------------------------------------
				//---------------------------------------

					
					
					////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////
					//ENVIAMOS AL HOTEL LA CONFIRMACION DE LA RESERVA POR CORREO 
					
					/*if ($parametros['buscar_producto'] != 'SVO' and $parametros['buscar_producto'] != 'OSV'){
										
						//Buscamos el mail del hotel
							$datos_hotel =$conexion->query("select max(reservas_mail) mail_hotel, max(reservas_responsable) responsable_hotel
																from hit_reservas_alojamientos pal,
																		hit_alojamientos a,
																		hit_tipos_alojamiento t
															where
																pal.ALOJAMIENTO = a.ID
																and a.TIPO = t.CODIGO
																and pal.LOCALIZADOR = '".$localizador."'");
							$datos_alojamiento = $datos_hotel->fetch_assoc();
							$mail_hotel = $datos_alojamiento['mail_hotel'];
							$responsable_hotel = $datos_alojamiento['responsable_hotel'];


						$asunto_hotel = "HITRAVEL - NUEVA RESERVA DE ALOJAMIENTO REALIZADA: ".$localizador;

						$mensaje_hotel_html = "
						<html lang='es'>
						<head>	
							<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
						</head>

						<body>

						<img src='imagenes/Logo_Mail.jpg' align='center' height='60' width='300'>

						<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:25px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Informacion de la reserva de alojamiento - <strong>Localizador: ".$localizador."</strong></h1>

						<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Viaje: <strong>".$nombre_viaje."</strong></h1>

						<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Salida: <strong>".$datos_salida."</strong>&nbsp;&nbsp;&nbsp;Regreso: <strong>".$datos_regreso."</strong>&nbsp;&nbsp;&nbsp;<strong>".$datos_situacion."</strong></h1>


						<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Pasajeros</h1>

						<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	
						for ($i = 0; $i < count($sdatos_pasajeros); $i++) {

							$mensaje_hotel_html .= "<p  style='font-size:13px;font-family:arial,serif;'>".$sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_tipo']."</strong>
							".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." ";

							if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
								$mensaje_hotel_html .= "<em> (".$sdatos_pasajeros[$i]['pax_edad']." años)</em>";
							}
							$mensaje_hotel_html .= "</p>";
							
						}
						$mensaje_hotel_html .= "</DIV>";

						//ALOJAMIENTOS
						$mensaje_hotel_html .= "<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Alojamientos</h1>";

						$mensaje_hotel_html .= "<DIV style='-moz-border-radius:15px;border-radius:15px;margin:5px 15px 15px;background:#e6e6e6;padding:5px 5px 5px;clear:both;line-height:10px'>";	

							$mensaje_hotel_html .= "<h2 style='font-size:18px;font-family:arial,serif;line-height:120%;color:#000000;font-weight:normal;text-align:left;'><strong>".$sNombre_hotel[0]['h_nombre']."</strong></h2>";
							$mensaje_hotel_html .= "<p style='font-size:15px;font-family:arial,serif;'><strong>".$sPeriodo_hotel[0]['h_periodo']."</strong></p>";

							for ($i = 0; $i < count($shoteles); $i++) {

									$mensaje_hotel_html .= "<span style='font-size:13px;font-family:arial,serif;'><strong>".$shoteles[$i]['h_caracteristica']."</strong>
									- ".$shoteles[$i]['h_pax']." ".$shoteles[$i]['h_desglose_pax'].".
									".$shoteles[$i]['h_texto_regimen']." <strong>".$shoteles[$i]['h_regimen']."</strong></span>

									<span><strong>".$shoteles[$i]['h_estado']."</strong></span><br><br>";
							}
						$mensaje_hotel_html .= "</DIV>";

						$mensaje_hotel_html .= "<h1 style='font-size:1.5em;line-height:120%;color:#6f7073;margin:40px 40px 10px;font-weight:normal;text-align:left;clear:both;	display:block;'><strong>Rogamos confirmen reserva y envíen factura proforma.</strong></h1>";						
						
						$mensaje_hotel_html .= "</body></html>";
								
						//Enviamos mail llamando a lafuncion 'enviar_mail'
						$envio = enviar_mail_hotel($asunto_hotel, $mensaje_hotel_html, $mail_hotel, $responsable_hotel);
						
						//echo($envio);
					
					}
					//-------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------*/
			



		return $respuesta;											
	}	





	function Enviar_mail_hotel($localizador){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;

		$respuesta = '';
		$fecha_envio = date("d-m-Y  H:i"); 


		//CONTROL HAY ALOJAMIENTOS
		$datos_hay_alojamientos =$conexion->query("select count(*) hay_alojamientos from hit_reservas_alojamientos where localizador = ".$localizador);
		$odatos_hay_alojamientos = $datos_hay_alojamientos->fetch_assoc();
		$hay_alojamientos = $odatos_hay_alojamientos['hay_alojamientos'];

		if ($hay_alojamientos > 0){
												
			//Buscamos el mail del hotel
				$datos_hotel =$conexion->query("select max(reservas_mail) mail_hotel, max(reservas_responsable) responsable_hotel
													from hit_reservas_alojamientos pal,
															hit_alojamientos a,
															hit_tipos_alojamiento t
												where
													pal.ALOJAMIENTO = a.ID
													and a.TIPO = t.CODIGO
													and pal.LOCALIZADOR = '".$localizador."'");
				$datos_alojamiento = $datos_hotel->fetch_assoc();
				$mail_hotel = $datos_alojamiento['mail_hotel'];
				$responsable_hotel = $datos_alojamiento['responsable_hotel'];



		
					//OBTENEMOS SALIDA, REGRESO, ESTADO
					$datos_salida_viaje =$conexion->query("select DATE_FORMAT(fecha_salida, '%d-%m-%Y') AS fecha_salida, DATE_FORMAT(fecha_regreso, '%d-%m-%Y') AS fecha_regreso, 
					case situacion 
						when 'P' then 'Servicios pendientes'
						when 'F' then 'Servicios confirmados'
					end situacion,
					folleto, cuadro, minorista, oficina
					from hit_reservas where localizador = '".$localizador."'");
					$odatos_salida_viaje = $datos_salida_viaje->fetch_assoc();
					$datos_salida = $odatos_salida_viaje['fecha_salida'];
					$datos_regreso = $odatos_salida_viaje['fecha_regreso'];
					$datos_situacion = $odatos_salida_viaje['situacion'];
					$datos_folleto = $odatos_salida_viaje['folleto'];
					$datos_cuadro = $odatos_salida_viaje['cuadro'];
					$datos_minorista = $odatos_salida_viaje['minorista'];
					$datos_oficina = $odatos_salida_viaje['oficina'];

					//OBTENEMOS NOMBRE DEL VIAJE
					$datos_nombre_viaje =$conexion->query("select nombre, producto from hit_producto_cuadros where folleto = '".$datos_folleto."' and cuadro = '".$datos_cuadro."'");
					$onombre_viaje = $datos_nombre_viaje->fetch_assoc();
					$nombre_viaje = $onombre_viaje['nombre'];		
					$producto_viaje = $onombre_viaje['producto'];					
					

					$oReservas_fin = new clsReservas_fin($conexion, $localizador);
					$sdatos_pasajeros = $oReservas_fin->Cargar_pasajeros();

;
					
					// !!OJO¡¡ estos datos de alojamiento ahora valen porque son estancias en un solo hotel. cuando esto cambie habrá que mostrarlo en bucle
					// leyendo la tabla de alojamientos de la reserva Y sacando toda la informacion de cada hotel
					$sNombre_hotel = $oReservas_fin->Cargar_nombre_hotel();
					$sPeriodo_hotel = $oReservas_fin->Cargar_periodo_hotel();
					$shoteles = $oReservas_fin->Cargar_hoteles();


					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					///------------------NUEVO MAIL AL HOTEL---------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------



					$mensaje_nuevo_hotel_html = "";


					$mensaje_nuevo_hotel_html .= "<body bgcolor='#f5f5f5' align='center'>
									<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td align='center'>
										<table width='770' border='0' align='center' cellpadding='0' cellspacing='0'><tr><td bgcolor='#FFFFFF'><a href='http://hitravel.es/' title='HI TRAVEL'><img src='imagenes/cab.jpg' alt='HI TRAVEL' border='1'></a></td></tr>"; 
					// Nombre del viaje y fecha de envio
					$mensaje_nuevo_hotel_html .= "<tr><td bgcolor='#2D5E47' style='padding:5px 0 5px 60px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif;'><span style='font-size: 20px;'>PETICION DE RESERVA DE ALOJAMIENTO</span></p></td></tr>";


						$mensaje_nuevo_hotel_html .= "
						<tr><td valign='top'>&nbsp;</td></tr>
						<tr>
						  <td><table width='100%' border='0' cellspacing='0' cellpadding='0'>
						    <tr>

						      <td height='50' bgcolor='#361DB7' align='center' style='padding:0px 0px 0px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 20px;'>Localizador: 
						        <span style='color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 25px; font-weight:bold;'>".$localizador."</span>
						      </td>

						      <td align='right' valign='middle' style='padding: 0px 5px 0px 0px; color:#361DB7; font-family: Verdana, Geneva, sans-serif; font-size: 14px; '>Reservado el ".$fecha_envio."</td>
						    </tr>
						  </table></td>
						</tr>";


					      // DATOS GENERALES
					      $mensaje_nuevo_hotel_html .= "<tr><td align='center' bgcolor='#FFFFFF'>
					      						<table width='722' border='0' cellspacing='0' cellpadding='0'>
					      				      			<tr><td>&nbsp;</td></tr>
					      				      			<tr><td valign='middle' height='50' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 24px;'><img src='imagenes/ico_datos_generales_3.jpg' border='0' width='30' height='30'>&nbsp;Nombres</td></tr>";


					 // PASAJEROS
					      $mensaje_nuevo_hotel_html .= "<tr><td bgcolor='#F3F4EB' style='padding: 20px 30px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif;'><span style='font-size:14px;'>Sres:</span><br><span style='font-size: 20px;'>";
						
						for ($i = 0; $i < count($sdatos_pasajeros); $i++) {
							$mensaje_nuevo_hotel_html .= $sdatos_pasajeros[$i]['pax_numero'].". ".$sdatos_pasajeros[$i]['pax_titulo']." ".$sdatos_pasajeros[$i]['pax_apellidos']." ".$sdatos_pasajeros[$i]['pax_nombre']." (".$sdatos_pasajeros[$i]['pax_tipo'];

								if($sdatos_pasajeros[$i]['pax_tipo_cod'] == 'N'){
									$mensaje_nuevo_hotel_html .= " - ".$sdatos_pasajeros[$i]['pax_edad']." años";
								}
								$mensaje_nuevo_hotel_html .= ")<br>";
							
						}


					      $mensaje_nuevo_hotel_html .= "</span></td></tr><tr><td valign='top'>&nbsp;</td></tr>";
			


					 //FECHAS
					      $mensaje_nuevo_hotel_html .= "<tr><td valign='top'><table width='722' border='0' cellspacing='0' cellpadding='0'>";
					      // Fecha de salida
					      $mensaje_nuevo_hotel_html .= "<tr><td width='200' style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Entrada</td><td width='522' bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$datos_salida."</td></tr>";

					      $mensaje_nuevo_hotel_html .= "<tr><td width='200' style='padding: 5px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>Salida</td><td width='522' bgcolor='#F3F4EB' style='padding: 5px 10px; color:#6a7c88; font-family: Verdana, Geneva, sans-serif; font-size:14px;'>".$datos_regreso."</td></tr>";
					     

					$mensaje_nuevo_hotel_html .= "</table></td></tr>";


					// ALOJAMIENTOS
					if($hay_alojamientos > 0){
						$mensaje_nuevo_hotel_html .= "
						<tr>
							<td valign='top'>&nbsp;</td></tr><tr><td height='50' style='color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 24px;'><img src='imagenes/ico_alojamiento_4.jpg' border='0' width='30' height='30'>&nbsp;Alojamientos</td>
						</tr>
						<tr>
						  <td valign='top'>
						  	<table width='100%' border='1' cellspacing='0' cellpadding='0'>
						    		<tr>
						    			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Hotel</td>
						      			<td colspan='3' width='60%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Estancia</td>
						    		</tr>";

						 			$mensaje_nuevo_hotel_html .= "<tr>   		
										<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px; font-weight:bold;'>".$sNombre_hotel[0]['h_nombre']."<br></td>
										<td colspan='3' style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 14px;'>".$sPeriodo_hotel[0]['h_periodo']."<br></td>
								    		</tr>";		

									$mensaje_nuevo_hotel_html .= "<tr>
								      			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Habitación</td>
								      			<td width='40%' bgcolor='#2D5E47' style='padding:5px 0 5px 10px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Distribución</td>
								      			<td width='5%' bgcolor='#2D5E47' style='text-align:center; padding:5px 0px 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Rég</td>
								      			<td width='15%' bgcolor='#2D5E47' style='text-align:center; padding:5px 0px 5px 0px; color:#FFFFFF; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>Estado</td>
								    		</tr>";

								for ($i = 0; $i < count($shoteles); $i++) {

						        			$mensaje_nuevo_hotel_html .= "<tr>
										<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_caracteristica']."<br></td>
										<td style='padding:2px 0 2px 10px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_pax']." ".$shoteles[$i]['h_desglose_pax']."<br></td>
										<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px;'>".$shoteles[$i]['h_regimen_siglas']."<br></td>
						  				<td style='text-align:center; padding:2px 0 2px 0px; color:#2D5E47; font-family: Verdana, Geneva, sans-serif; font-size: 12px; font-weight:bold;'>".$shoteles[$i]['h_estado']."</td>
						  				</tr>";								

								}

						$mensaje_nuevo_hotel_html .= "</table></td></tr>";
					}




					//PIE DE MAIL

					$mensaje_nuevo_hotel_html .= "<h1 style='font-size:1.5em;line-height:120%;color:#2D5E47;margin:40px 40px 10px;font-weight:normal;text-align:left;clear:both;	display:block;'><strong>Rogamos confirmen reserva y envíen factura proforma.</strong></h1>";


					$mensaje_nuevo_hotel_html .= "</table><p>&nbsp;</p></td></tr></table></body></html>";


					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//---------------------------------FIN NUEVO MAIL AL HOTEL----------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------
					//-------------------------------------------------------------------------------------------------------------------

					$asunto_hotel = "HITRAVEL - NUEVA RESERVA DE ALOJAMIENTO REALIZADA: ".$localizador;

					//TAMBIEN ENVIAMOS MAIL AL USUARIO QUE HA REALIZADO EL CAMBIO
					$datos_usuario =$conexion->query("select email from hit_usuarios where usuario = '".$Usuario."'");
					$odatos_usuario = $datos_usuario->fetch_assoc();
					$mail_usuario = $odatos_usuario['email'];

					//Enviamos mail llamando a lafuncion 'enviar_mail'
					$envio = enviar_mail_hotel($asunto_hotel, $mensaje_nuevo_hotel_html, $mail_hotel, $mail_usuario, $responsable_hotel);

					if($envio == 'OK'){
						$respuesta = $mail_hotel.", ".$mail_usuario;
					}else{
						$respuesta = "No se ha podido enviar la solicitud al hotel.";
					}


		return $respuesta;	

		}										
	}


	
	function Insertar($referencia,$folleto,$cuadro,$ciudad_salida,$paquete,$regimen,$fecha_salida,$nombre_titular,$adultos,$ninos,$bebes,$novios,$observaciones,
	$minorista,$oficina,$clave_oficina,$agente,$referencia_agencia,$envio,$divisa_actual,$free, $observaciones_internas, $observaciones_hotel, $observaciones_clientes){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;

		//$pax = $adultos+$ninos+$bebes;
		$pax = $adultos+$ninos;

		if($divisa_actual == null){
			$divisa_actual = 'EUR';
		}
		if($free == null){
			$free = 'N';
		}
		if($envio == null){
			$envio = 'N';
		}

		if($clave_oficina != null){

			$oficic =$conexion->query("select id, oficina from hit_oficinas where clave = '".$clave_oficina."'");
			$ofi = $oficic->fetch_assoc();
			$minorista = $ofi['id'];
			$oficina = $ofi['oficina'];
		}else{
			$minorista = '2';
			$oficina = '4';
		}


		$query = "INSERT INTO hit_reservas(referencia,situacion,bloqueada,bloqueada_usuario,folleto,cuadro,paquete,regimen,ciudad_salida,fecha_salida,fecha_regreso,fecha_reserva,
	usuario_reserva,fecha_modificacion,usuario_modificacion,nombre_titular,pax,adultos,ninos,bebes,novios,jubilados,observaciones,minorista,oficina,agente,referencia_agencia,envio,divisa_base,divisa_actual,
	cambio,redondeo,pvp_calculado_comisionable,pvp_calculado_no_comisionable,pvp_calculado_comision,pvp_calculado_importe_comision,pvp_calculado_total,pvp_comisionable,
	pvp_no_comisionable,pvp_comision,pvp_importe_comision,pvp_total,free, observaciones_internas, observaciones_hotel, observaciones_clientes) VALUES (";
		$query .= "'".$referencia."',";
		$query .= "'P',";
		$query .= "'N',";
		$query .= "'".$Usuario."',";
		$query .= "'".$folleto."',";
		$query .= "'".$cuadro."',";
		$query .= "'".$paquete."',";
		$query .= "'".$regimen."',";
		$query .= "'".$ciudad_salida."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_salida))."',";
		$query .= "'".date("Y-m-d",strtotime($fecha_salida))."',";
		$query .= "'".date("Y-m-d")."',";
		$query .= "'".$Usuario."',";
		$query .= "'".date("Y-m-d")."',";
		$query .= "'".$Usuario."',";
		$query .= "'".$nombre_titular."',";
		$query .= "'".$pax."',";
		$query .= "'".$adultos."',";
		$query .= "'".$ninos."',";
		$query .= "'".$bebes."',";
		$query .= "'".$novios."',";
		$query .= "'0',";
		$query .= "'".$observaciones."',";
		$query .= "'".$minorista."',";
		$query .= "'".$oficina."',";
		$query .= "'".$agente."',";
		$query .= "'".$referencia_agencia."',";
		$query .= "'".$envio."',";
		$query .= "'EUR',";
		$query .= "'".$divisa_actual."',";
		$query .= "'1',";
		$query .= "'2',";
		$query .= "'0',";
		$query .= "'0',";
		$query .= "'0',";
		$query .= "'0',";
		$query .= "'0',";
		$query .= "'0',";
		$query .= "'0',";
		$query .= "'0',";
		$query .= "'0',";
		$query .= "'0',";
		$query .= "'".$free."',";
		$query .= "'".$observaciones_internas."',";
		$query .= "'".$observaciones_hotel."',";
		$query .= "'".$observaciones_clientes."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido añadir la nueva reserva. '.$conexion->error;
		}else{
			//$respuesta = 'OK';

			$localizador =$conexion->query("select max(localizador) localizador from hit_reservas");
			$Loc = $localizador->fetch_assoc();
			$respuesta = $Loc['localizador'];

			if($referencia == null){
			
				$query_ref = "UPDATE hit_reservas SET referencia = 'HIT".$respuesta."' WHERE localizador ='".$respuesta."'";
				$resultaref =$conexion->query($query_ref);
			}

			//Revisamos pasajeros
			$Revisa_pax = $this->Revisar_añadir_pasajeros($respuesta, $pax);
			if($Revisa_pax == FALSE){
				ECHO($conexion->error);
			}

		}

		return $respuesta;											
	}


	function Bloquear_desbloquear($localizador){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;

		$bloqueo =$conexion->query("select bloqueada, bloqueada_usuario, factura from hit_reservas where LOCALIZADOR = ".$localizador);
		$bloq = $bloqueo->fetch_assoc();
		$bloqueada = $bloq['bloqueada'];
		$usuario_reserva = $bloq['bloqueada_usuario'];
		$factura_reserva = $bloq['factura'];
		
		//if($factura_reserva == 0){
			if($usuario_reserva == $Usuario or $usuario_reserva == null or $Usuario == 'jfrias'){
				if($bloqueada == 'S'){
					$query = "UPDATE hit_reservas SET ";
					$query .= " bloqueada = 'N'";
					$query .= ", bloqueada_usuario = null";
					$query .= " WHERE LOCALIZADOR = ".$localizador;

					$resultadob =$conexion->query($query);
					if ($resultadob == FALSE){
						$respuesta = $conexion->error;
					}else{
						$respuesta = 'La reserva ha sido desbloqueda';
					}

				}else{
					$query = "UPDATE hit_reservas SET ";
					$query .= " bloqueada = 'S'";
					$query .= ", bloqueada_usuario = '".$Usuario."'";
					$query .= " WHERE LOCALIZADOR = ".$localizador;

					$resultadob =$conexion->query($query);
					if ($resultadob == FALSE){
						$respuesta = $conexion->error;
					}else{
						$respuesta = 'La reserva ha sido bloqueda';
					}

				}


			}else{
					$respuesta = 'La reserva debe ser desbloqueada por el usuario que la tiene bloqueada';
			}
		/*}else{
			$respuesta = 'La reserva ya esta facturada. No se permiten modificaciones.';
		}*/


		return $respuesta;											
	}

	function Actualizar_precios($localizador){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion; 

		$actualizar_precio = "CALL `RESERVAS_ACTUALIZA_PRECIOS`('R','".$localizador."')";
		$resultadoactualizar =$conexion->query($actualizar_precio);
			//echo($expandir);
		if ($resultadoactualizar == FALSE){
			$respuesta = 'No se ha podido atualizar el precio de la reserva. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}


//--------------------------------------------------------------------------------
//----- METODOS PARA LA PARTE INTERMEDIA CON LOS DATOS DE PASAJEROS - NIVEL 2 ----
//--------------------------------------------------------------------------------

	function Cargar_pasajeros($localizador, $filadesde, $buscar_apellido, $buscar_nombre, $paginacion){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		if($buscar_apellido != null){
			$nombre = " and nombre like '%".$buscar_nombre."%'";
			$CADENA_BUSCAR = " and apellido like '%".$buscar_apellido."%'";
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " and nombre like '%".$buscar_nombre."%'";
		}else{
			$CADENA_BUSCAR = "";	
		}
		$resultado =$conexion->query("SELECT numero,habitacion,apellido,nombre nombre_pax,sexo,tipo,edad,documento_tipo,documento,DATE_FORMAT(fecha_nacimiento, '%d-%m-%Y') AS fecha_nacimiento, pais,telefono, observaciones observaciones_pax FROM hit_reservas_pasajeros where localizador ='".$localizador."'".$CADENA_BUSCAR." ORDER BY numero");

		/*if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		//Si el usuario solicita paginacion paginamos segun la tabla del formulario, sino se muestran todos los resgistros
		if($paginacion == 'S'){
			//Guardamos el resultado en una matriz con un numero fijo de registros
			//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
			$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_PASAJEROS' AND USUARIO = '".$Usuario."'");
			$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

			$pasajeros = array();
			for ($num_fila = $filadesde-1; $num_fila <= $filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
				$resultado->data_seek($num_fila);
				$fila = $resultado->fetch_assoc();
				array_push($pasajeros,$fila);
			}
			$numero_filas->close();
		}else{
			$pasajeros = array();
			for ($i = 1; $i <= $resultado->num_rows; $i++) {
				//$resultado->data_seek($i);
				$fila = $resultado->fetch_assoc();
				array_push($pasajeros,$fila);
			}
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $pasajeros;											
	}


	function Cargar_combo_selector_pasajeros($localizador, $buscar_apellido, $buscar_nombre, $paginacion){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

	
		if($buscar_apellido != null){
			$nombre = " and nombre like '%".$buscar_nombre."%'";
			$CADENA_BUSCAR = " and apellido like '%".$buscar_apellido."%'";
			if($buscar_nombre != null){
				$CADENA_BUSCAR .= $nombre;	
			}
		}elseif($buscar_nombre != null){
			$CADENA_BUSCAR = " and nombre like '%".$buscar_nombre."%'";
		}else{
			$CADENA_BUSCAR = "";	
		}

		$resultadoc =$conexion->query("SELECT * FROM hit_reservas_pasajeros where localizador ='".$localizador."'".$CADENA_BUSCAR." ORDER BY numero");
		$numero_filas = $resultadoc->num_rows;         //----------

		/*if ($numero_filas == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultadoc->close();
			$conexion->close();
			exit;
		}*/

		//Si el usuario solicita pagincion paginamos segun la tabla del formulario, sino se muestran todos los resgistros
		if($paginacion == 'S'){		

			//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
			//																						       ESPECIFICO: Solo el nombre del formulario en la query
			if($numero_filas > 0){
				$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_PASAJEROS' AND USUARIO = '".$Usuario."'");
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
		}else{
			//Cargamos el array con una linea desde uno hasta el total de registros
			if($numero_filas > 0){

				$combo_select[1] = array ( "inicio" => 1, "fin" => $numero_filas);
				$resultadoc->close();

			}else{
				$combo_select[1] = array ( "inicio" => 1, "fin" => 0);
				$resultadoc->close();
			}
		}


		return $combo_select;											
	}

	function Botones_selector_pasajeros($filadesde, $boton, $paginacion){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		
												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query('SELECT * FROM hit_reservas_pasajeros');
		$numero_filas = $resultadoc->num_rows;         //----------
		if ($numero_filas == FALSE){
			echo('Error en la consulta');
			$resultadoc->close();
			$conexion->close();
			exit;
		}

		//Si el usuario solicita pagincion paginamos segun la tabla del formulario, sino se muestran todos los resgistros
		if($paginacion == 'S'){	

			//Cargamos el array para el combo con las lineas cada n registros. (n registros se obtendran de la tabla de usuarios_formularios).
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_PASAJEROS' AND USUARIO = '".$Usuario."'");
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
			$num_filas->close();
		}else{
			$selector = 1;
		}

		$resultadoc->close();
		return $selector;											
	}


	function Modificar_pasajeros($localizador, $numero, $habitacion, $apellido, $nombre, $sexo, $tipo, $edad, $documento_tipo, $documento, $fecha_nacimiento, $pais, $telefono, $observaciones){


		$conexion = $this ->Conexion;
		$query = "UPDATE hit_reservas_pasajeros SET ";
		$query .= " HABITACION = '".$habitacion."'";
		$query .= ", APELLIDO = '".$apellido."'";
		$query .= ", NOMBRE = '".$nombre."'";
		$query .= ", SEXO = '".$sexo."'";
		$query .= ", TIPO = '".$tipo."'";
		$query .= ", EDAD = '".$edad."'";
		$query .= ", DOCUMENTO_TIPO = '".$documento_tipo."'";
		$query .= ", DOCUMENTO = '".$documento."'";
		if($fecha_nacimiento != null){
			$query .= ", FECHA_NACIMIENTO = '".date("Y-m-d",strtotime($fecha_nacimiento))."'";
		}
		$query .= ", PAIS = '".$pais."'";
		$query .= ", TELEFONO = '".$telefono."'";
		$query .= ", OBSERVACIONES = '".$observaciones."'";
		$query .= " WHERE LOCALIZADOR = '".$localizador."'";
		$query .= " AND NUMERO = '".$numero."'";



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

	function Borrar_pasajeros($localizador,$numero,$pax){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_reservas_pasajeros WHERE LOCALIZADOR = '".$localizador."'";
		$query .= " AND NUMERO = '".$numero."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';

			//Esto añade pasajeros si al borrarlos no coinciden con los campos de numeros de pax de la reserva
			//Se prescinde de ello porque al borrar pasajeros estamos actualizando esos campos
			//Revisamos pasajeros
			/*$Revisa_pax = $this->Revisar_añadir_pasajeros($localizador, $pax);
			if($Revisa_pax == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}*/


		}

		return $respuesta;											
	}

	function Revisar_añadir_pasajeros($localizador, $pax_obligados){

		$conexion = $this ->Conexion;
		$respuesta = '';
		$count_pax =$conexion->query("SELECT * FROM hit_reservas_pasajeros WHERE LOCALIZADOR = '".$localizador."'");
		$pax_reales	 = $count_pax->num_rows;

		//ECHO("LOCALIZADOR: ".$pax_obligados);
		//$count_pax =$conexion->query("SELECT count(*) PAX_RESERVA FROM hit_reservas_pasajeros WHERE LOCALIZADOR = '".$localizador."'");
		//$Nfilas	 = $count_pax->fetch_assoc();																	  //------
		//$pax_reales = $Nfilas['PAX_RESERVA'];


		//$resultadoi =$conexion->query($query);
		if ($count_pax == FALSE){
			$respuesta = 'No se han podido contar los pasajeros. '.$conexion->error;
		}else{

			if($pax_obligados > $pax_reales){
				for ($i = 1; $i <= $pax_obligados; $i++) {
					$existe =$conexion->query("SELECT * FROM hit_reservas_pasajeros WHERE LOCALIZADOR = '".$localizador."' and NUMERO = '".$i."'");
					$existe_pax	 = $existe->num_rows;
					
					if($existe_pax == 0 and $pax_obligados > $pax_reales){

						$query = "INSERT INTO hit_reservas_pasajeros (LOCALIZADOR, NUMERO, APELLIDO, NOMBRE, TIPO, PAIS) VALUES (";
						$query .= "'".$localizador."',";
						$query .= "'".$i."',";
						$query .= "'APELLIDO-".$i."',";
						$query .= "'NOMBRE-".$i."',";
						$query .= "'A',";
						$query .= "'ESP')";

						$resultadoi =$conexion->query($query);
						if ($resultadoi == FALSE){
							$respuesta = 'No se han podido insertar los nuevos pax. '.$conexion->error;
						}else{
							$respuesta = 'OK';
							$pax_reales++;
						}						
						
					}
				}
			}
		}


		//return $respuesta;											
	}


	function Revisar_numeros_pasajeros($localizador){

		$conexion = $this ->Conexion;
		$respuesta = '';

		$numeros_pax =$conexion->query("select 	SUM(CASE tipo	WHEN 'A' THEN 1 WHEN 'V' THEN 1 WHEN 'J' THEN 1 WHEN 'N' THEN 1 WHEN 'B' THEN 1	ELSE 0	END) PAX,
												SUM(CASE tipo	WHEN 'A' THEN 1 WHEN 'V' THEN 1	WHEN 'J' THEN 1	ELSE 0	END) ADULTOS,
												SUM(CASE tipo	WHEN 'N' THEN 1 ELSE 0 END) NINOS, 
												SUM(CASE tipo	WHEN 'B' THEN 1	ELSE 0 END) BEBES,
												SUM(CASE tipo	WHEN 'V' THEN 1	ELSE 0 END) NOVIOS,
												SUM(CASE tipo	WHEN 'J' THEN 1	ELSE 0 END) JUBILADOS
										from hit_reservas_pasajeros where localizador = '".$localizador."'");
		$numeros = $numeros_pax->fetch_assoc();																	  //------
		$pax_pax = $numeros['PAX'];
		$pax_adultos = $numeros['ADULTOS'];
		$pax_ninos = $numeros['NINOS'];
		$pax_bebes = $numeros['BEBES'];
		$pax_novios = $numeros['NOVIOS'];
		$pax_jublados = $numeros['JUBILADOS'];

		//$resultadoi =$conexion->query($query);
		if ($numeros_pax == FALSE){
			$respuesta = 'No se han podido contar los pasaeros. '.$conexion->error;
		}else{

			$conexion = $this ->Conexion;
			$query = "UPDATE hit_reservas SET ";
			$query .= " PAX = '".$pax_pax."'";
			$query .= ", ADULTOS = '".$pax_adultos."'";
			$query .= ", NINOS = '".$pax_ninos."'";
			$query .= ", BEBES = '".$pax_bebes."'";
			$query .= ", NOVIOS = '".$pax_novios."'";
			$query .= ", JUBILADOS = '".$pax_jublados."'";
			$query .= " WHERE LOCALIZADOR = '".$localizador."'";

			$resultadonumeros =$conexion->query($query);

			if ($resultadonumeros == FALSE){
				$respuesta = $conexion->error;
				//echo($query);
			}else{
				$respuesta = 'OK';
				//echo($query);
			}
			
		}


		//return $respuesta;											
	}


//-------------------------------------------------------
//----- METODOS PARA LA PARTE DE LOS AEREOS - NIVEL 3----
//-------------------------------------------------------

	function Cargar_aereos($localizador){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	

		$resultado =$conexion->query("SELECT clave aereos_clave, orden aereos_orden, reserva aereos_reserva, tipo_acuerdo aereos_tipo_cuerdo, cia aereos_cia, plazas aereos_plazas, 
									  DATE_FORMAT(fecha_reserva, '%d-%m-%Y') AS aereos_fecha_reserva,
									  emitido aereos_emitido,
									  DATE_FORMAT(fecha_emision, '%d-%m-%Y') AS aereos_fecha_emision,
									  pvp aereos_pvp, tasas_pvp aereos_tasas_pvp
									  FROM hit_reservas_aereos WHERE localizador = '".$localizador."' ORDER BY orden");

		if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}

		//Cargamos un array con todas las lineas de alojamientos
		$aereos = array();
		for ($num_fila = 0; $num_fila < $resultado->num_rows; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($aereos,$fila);
		}


		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $aereos;											
	}

	function Cargar_lineas_nuevas_aereos(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_AEREOS' AND USUARIO = '".$Usuario."'");
		if ($num_filas == FALSE){
			echo('error al leer lineas nuevas');
			$Nfilasnuevas = 1;
		}
		$Nfilasnuevas	 = $num_filas->fetch_assoc();																	  //------
		$combo_nuevas = array();
		for ($num_fila = 0; $num_fila <= $Nfilasnuevas['LINEAS_NUEVAS']-1; $num_fila++) {
			$combo_nuevas[$num_fila] = array ("linea" => $num_fila);
		}

		$num_filas->close();
		return $combo_nuevas;											
	}


	function Modificar_aereos($clave, $reserva, $tipo_acuerdo, $cia, $plazas, $fecha_reserva, $emitido, $fecha_emision){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_reservas_aereos SET ";
		$query .= " RESERVA = '".$reserva."'";
		$query .= ", TIPO_ACUERDO = '".$tipo_acuerdo."'";
		$query .= ", CIA = '".$cia."'";
		$query .= ", PLAZAS = '".$plazas."'";
		$query .= ", FECHA_RESERVA = '".date("Y-m-d",strtotime($fecha_reserva))."'";
		$query .= ", EMITIDO = '".$emitido."'";

		if($fecha_emision != ''){
			$query .= ", FECHA_EMISION = '".date("Y-m-d",strtotime($fecha_emision))."'";
		}else{
			$query .= ", FECHA_EMISION = null";
		}

		$query .= " WHERE CLAVE = '".$clave."'";


		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
			//echo($query);
		}else{


			$respuesta = 'OK';

			//Actualizamos precios de los trayectos que quedan en la reserva
				$resultado_actualiza_precio_trayectos =$conexion->query("select localizador, orden, numero
												from hit_reservas_aereos_trayectos
												where CLAVE_AEREO =  '".$clave."'");

				//Cargamos un array con todas las lineas de TRAYECTO			$aereos_trayectos = array();
				//$v_hay_trayectos = 0;
				for ($num_fila = 0; $num_fila < $resultado_actualiza_precio_trayectos->num_rows; $num_fila++) {
					$fila = $resultado_actualiza_precio_trayectos->fetch_assoc();

					$actualizar_precios_trayectos = "CALL `RESERVAS_ACTUALIZA_PRECIO_TRAYECTO`('R','".$fila['localizador']."','".$fila['orden']."','".$fila['numero']."')";
					$actualizarpreciostrayectos =$conexion->query($actualizar_precios_trayectos);
					if($actualizarpreciostrayectos == FALSE){
						$respuesta = $conexion->error;
					}else{
						$respuesta = 'OK';
					}
					//$v_hay_trayectos++;
				}
				//Liberar Memoria usada por la consulta
				$resultado_actualiza_precio_trayectos->close();

				//Si no hay trayectos pasamos el procedimiento con numero de trayecto 0 para que deje a cero los precios de la reserva de aereos
				/*if($v_hay_trayectos == 0){
					$actualizar_precios_trayectos = "CALL `RESERVAS_ACTUALIZA_PRECIO_TRAYECTO`('R','".$localizador."','".$orden."',0)";
					$actualizarpreciostrayectos =$conexion->query($actualizar_precios_trayectos);
					if($actualizarpreciostrayectos == FALSE){
						$respuesta = $conexion->error;
					}else{
						$respuesta = 'OK';
					}
				}*/



			//echo($query);
		}

		return $respuesta;											
	}


	function Borrar_aereos($clave){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_reservas_aereos WHERE CLAVE = '".$clave."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}



	function Insertar_aereos($localizador, $orden , $reserva, $tipo_acuerdo, $cia, $plazas, $fecha_reserva, $emitido, $fecha_emision){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;

		if($tipo_acuerdo == 'CHR'){
			$reserva = 'CHARTER/'.$localizador.'/'.$orden;
		}elseif($tipo_acuerdo == 'BUS'){
			$reserva = 'BUS/'.$localizador.'/'.$orden;
		}elseif($tipo_acuerdo == 'RCU'){
			$reserva = 'CUPOS/'.$localizador.'/'.$orden;
		}elseif($tipo_acuerdo == 'RAC'){
			$reserva = 'CUPOS_AM/'.$localizador.'/'.$orden;
		}elseif($tipo_acuerdo == 'ROR'){
			if($reserva == null){
				$reserva = 'AMADEUS/'.$localizador.'/'.$orden;
			}else{
				$reserva = $reserva;			
			}
		}else{
				$reserva = 'REGULAR/'.$localizador.'/'.$orden;			
		}

		$query = "INSERT INTO hit_reservas_aereos (LOCALIZADOR, ORDEN, RESERVA, TIPO_ACUERDO, CIA, PLAZAS, FECHA_RESERVA, USUARIO, EMITIDO, FECHA_EMISION) VALUES (";
		$query .= "'".$localizador."',";
		$query .= "'".$orden."',";
		$query .= "'".$reserva."',";
		$query .= "'".$tipo_acuerdo."',";
		$query .= "'".$cia."',";
		$query .= "'".$plazas."',";
		$query .= "'".date("Y-m-d")."',";
		$query .= "'".$Usuario."',";
		if($fecha_emision != ''){
			$query .= "'".$emitido."',";
		}else{
			$query .= "'N',";
		}

		if($fecha_emision != ''){
			$query .= "'".date("Y-m-d",strtotime($fecha_emision))."')";
		}else{
			$query .= "null)";
		}

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}

//--------------------------------------------------------------------
//----- METODOS PARA LA PARTE DE LOS AEREOS / PASAJEROS - NIVEL 31----
//--------------------------------------------------------------------

	function Cargar_aereos_pasajeros($localizador){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
	
		$resultado =$conexion->query("select a.ORDEN aereos_pasajeros_orden, a.RESERVA aereos_pasajeros_reserva, ap.NUMERO aereos_pasajeros_numero, ap.CLAVE_PASAJERO												 aereos_pasajeros_clave_pasajero, 
												 ap.CLAVE_AEREO aereos_pasajeros_clave_aereo,
												 p.APELLIDO aereos_pasajeros_apellido, 
												 p.NOMBRE aereos_pasajeros_nombre, ap.BILLETE aereos_pasajeros_billete, ap.COSTE aereos_pasajeros_coste, ap.TASAS aereos_pasajeros_tasas
										from hit_reservas_aereos_pasajeros ap, hit_reservas_pasajeros p, hit_reservas_aereos a
										where ap.CLAVE_PASAJERO = p.CLAVE
												and ap.CLAVE_AEREO = a.CLAVE
												and ap.LOCALIZADOR =  '".$localizador."' 
										order by ap.ORDEN, ap.NUMERO");

		if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}

		//Cargamos un array con todas las lineas de alojamientos
		$aereos_pasajeros = array();
		for ($num_fila = 0; $num_fila < $resultado->num_rows; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($aereos_pasajeros,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $aereos_pasajeros;											
	}

	function Cargar_lineas_nuevas_aereos_pasajeros(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_AEREOS_PASAJEROS' AND USUARIO = '".$Usuario."'");
		if ($num_filas == FALSE){
			echo('error al leer lineas nuevas');
			$Nfilasnuevas = 1;
		}
		$Nfilasnuevas	 = $num_filas->fetch_assoc();																	  //------
		$combo_nuevas = array();
		for ($num_fila = 0; $num_fila <= $Nfilasnuevas['LINEAS_NUEVAS']-1; $num_fila++) {
			$combo_nuevas[$num_fila] = array ("linea" => $num_fila);
		}

		$num_filas->close();
		return $combo_nuevas;											
	}


	function Modificar_aereos_pasajeros($localizador, $orden, $numero, $billete, $coste, $tasas){

		$conexion = $this ->Conexion;
		$query = "UPDATE hit_reservas_aereos_pasajeros SET ";
		$query .= " BILLETE = '".$billete."'";
		$query .= ", COSTE = '".$coste."'";
		$query .= ", TASAS = '".$tasas."'";
		$query .= " WHERE LOCALIZADOR = '".$localizador."' and ORDEN = '".$orden."' and NUMERO = '".$numero."'";


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

	function Borrar_aereos_pasajeros($localizador, $orden, $numero){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_reservas_aereos_pasajeros WHERE LOCALIZADOR = '".$localizador."' and ORDEN = '".$orden."' and NUMERO = '".$numero."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_aereos_pasajeros($clave, $numero){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;


		$query = "INSERT INTO hit_reservas_aereos_pasajeros (LOCALIZADOR, ORDEN, NUMERO, CLAVE_PASAJERO, CLAVE_AEREO) 
				select a.LOCALIZADOR, a.ORDEN, p.NUMERO, p.CLAVE, a.CLAVE
				from hit_reservas_aereos a, hit_reservas_pasajeros p
				where
				a.LOCALIZADOR = p.LOCALIZADOR
				and a.CLAVE = '".$clave."'
				and p.NUMERO = '".$numero."'";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';
		}
		return $respuesta;											
	}


//--------------------------------------------------------------------
//----- METODOS PARA LA PARTE DE LOS AEREOS / TRAYECTOS - NIVEL 32----
//--------------------------------------------------------------------

	function Cargar_aereos_trayectos($localizador){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;

		$resultado =$conexion->query("select orden aereos_trayectos_orden, numero aereos_trayectos_numero, clave_aereo aereos_trayectos_clave_aereo, 
										DATE_FORMAT(fecha, '%d-%m-%Y') AS aereos_trayectos_fecha, origen aereos_trayectos_origen, destino aereos_trayectos_destino, 
										cia aereos_trayectos_cia, acuerdo aereos_trayectos_acuerdo, subacuerdo aereos_trayectos_subacuerdo, vuelo aereos_trayectos_vuelo, 
										time_format(hora_salida, '%H:%i') AS aereos_trayectos_hora_salida,
										time_format(hora_llegada, '%H:%i') AS aereos_trayectos_hora_llegada,
										desplazamiento_llegada aereos_trayectos_desplazamiento_llegada, clase aereos_trayectos_clase, situacion aereos_trayectos_situacion,
										pvp_total_trayecto aereos_trayectos_pvp_total_trayecto, tasas_pvp_total_trayecto aereos_trayectos_tasas_pvp_total_trayecto, tipo_precio aereos_trayectos_tipo_precio
										from hit_reservas_aereos_trayectos
										where LOCALIZADOR =  '".$localizador."' 
										order by FECHA, HORA_SALIDA");

		if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}

		//Cargamos un array con todas las lineas de alojamientos
		$aereos_trayectos = array();
		for ($num_fila = 0; $num_fila < $resultado->num_rows; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($aereos_trayectos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $aereos_trayectos;											
	}

	function Cargar_lineas_nuevas_aereos_trayectos(){
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//Cargamos el array para el combo con las lineas nuevas. (n lineas se obtendran de la tabla de usuarios_formularios).
		//																						  ESPECIFICO: Solo el nombre del formulario en la query
		$num_filas =$conexion->query("SELECT LINEAS_NUEVAS FROM hit_usuarios_formularios WHERE FORMULARIO = 'RESERVAS_AEREOS_TRAYECTOS' AND USUARIO = '".$Usuario."'");
		if ($num_filas == FALSE){
			echo('error al leer lineas nuevas');
			$Nfilasnuevas = 1;
		}
		$Nfilasnuevas	 = $num_filas->fetch_assoc();																	  //------
		$combo_nuevas = array();
		for ($num_fila = 0; $num_fila <= $Nfilasnuevas['LINEAS_NUEVAS']-1; $num_fila++) {
			$combo_nuevas[$num_fila] = array ("linea" => $num_fila);
		}

		$num_filas->close();
		return $combo_nuevas;											
	}



	function Modificar_aereos_trayectos($clave_aereo, $numero, $fecha, $origen, $destino, $cia, $acuerdo, $subacuerdo, $vuelo, $hora_salida, $hora_llegada,
										$desplazamiento_llegada, $clase, $situacion, $pvp_total_trayecto, $tasas_pvp_total_trayecto, $tipo_precio){
		/*ECHO($clave_aereo.' - '.$numero.' - '.$fecha.' - '.$origen.' - '.$destino.' - '.$cia.' - '.$acuerdo.' - '.$subacuerdo.' - '.$vuelo.' - '.$hora_salida.' - '.$hora_llegada.' - '.$desplazamiento_llegada.' - '.$clase.' - '.$situacion);*/
		$conexion = $this ->Conexion;

		$datos =$conexion->query("select localizador, orden, plazas from hit_reservas_aereos where clave = '".$clave_aereo."'");
		$aereos = $datos->fetch_assoc();
		$localizador = $aereos['localizador'];
		$orden = $aereos['orden'];
		$plazas = $aereos['plazas'];
		
		$pvp_pax = $pvp_total_trayecto / $plazas;
		$tasas_pax = $tasas_pvp_total_trayecto / $plazas;

		$query = "UPDATE hit_reservas_aereos_trayectos SET ";
		$query .= " FECHA = '".date("Y-m-d",strtotime($fecha))."'";
		$query .= ", ORIGEN = '".$origen."'";
		$query .= ", DESTINO = '".$destino."'";
		$query .= ", CIA = '".$cia."'";
		$query .= ", ACUERDO = '".$acuerdo."'";
		$query .= ", SUBACUERDO = '".$subacuerdo."'";
		$query .= ", VUELO = '".$vuelo."'";
		$query .= ", HORA_SALIDA = '".$hora_salida."'";
		$query .= ", HORA_LLEGADA = '".$hora_llegada."'";
		$query .= ", DESPLAZAMIENTO_LLEGADA = '".$desplazamiento_llegada."'";
		$query .= ", CLASE = '".$clase."'";
		$query .= ", SITUACION = '".$situacion."'";
		if($tipo_precio == 'M'){
			$query .= ", PVP_PAX = '".$pvp_pax."'";
			$query .= ", TASAS_PAX = '".$tasas_pax."'";
			$query .= ", PVP_TOTAL_TRAYECTO = '".$pvp_total_trayecto."'";
			$query .= ", TASAS_PVP_TOTAL_TRAYECTO = '".$tasas_pvp_total_trayecto."'";
		}
		$query .= ", TIPO_PRECIO = '".$tipo_precio."'";
		$query .= " WHERE CLAVE_AEREO = '".$clave_aereo."' and NUMERO = '".$numero."'";


		$resultadom =$conexion->query($query);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
			//echo($query);
		}else{
			$actualizar_tipo_trayecto = "CALL `RESERVAS_ACTUALIZA_TIPOS_TRAYECTO`('R','".$clave_aereo."','".$origen."','".$destino."','".$cia."','".$acuerdo."','".$subacuerdo."')";
			$resultadoactualizartrayecto =$conexion->query($actualizar_tipo_trayecto);
			if($tipo_precio == 'A'){
				/*echo($localizador."-".$orden."-".$numero);*/
				$actualizar_precios_trayectos = "CALL `RESERVAS_ACTUALIZA_PRECIO_TRAYECTO`('R','".$localizador."','".$orden."','".$numero."')";
				$actualizarpreciostrayectos =$conexion->query($actualizar_precios_trayectos);
			
				if($resultadoactualizartrayecto == FALSE){
					$respuesta = $conexion->error;
				}elseif($actualizarpreciostrayectos == FALSE){
					$respuesta = $conexion->error;
				}else{
					$respuesta = 'OK';
				}
			}
			//echo($query);
			
			//ACTUALIZAMOS PRECIOS DE LA RESERVA DE AEREOS
			$resultadosumaprecios =$conexion->query("update hit_reservas_aereos 
													set PVP = (select sum(pvp_total_trayecto) from hit_reservas_aereos_trayectos where LOCALIZADOR = ".$localizador." and ORDEN = ".$orden."),
														TASAS_PVP = (select sum(tasas_pvp_total_trayecto) from hit_reservas_aereos_trayectos where LOCALIZADOR = ".$localizador." and ORDEN = ".$orden.")
													where	LOCALIZADOR = ".$localizador." and ORDEN = ".$orden);			

			if($resultadosumaprecios == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}			
			
		}

		return $respuesta;											
	}

	function Borrar_aereos_trayectos($clave_aereo, $numero){

		$conexion = $this ->Conexion;

		$datos =$conexion->query("select localizador, orden from hit_reservas_aereos where clave = '".$clave_aereo."'");
		$aereos = $datos->fetch_assoc();
		$localizador = $aereos['localizador'];
		$orden = $aereos['orden'];

		$datos_trayecto =$conexion->query("SELECT origen, destino, cia, acuerdo, subacuerdo FROM hit_reservas_aereos_trayectos c WHERE CLAVE_AEREO = '".$clave_aereo."' and NUMERO = '".$numero."'");
		$datos = $datos_trayecto->fetch_assoc();
		$origen = $datos['origen'];
		$destino = $datos['destino'];
		$cia = $datos['cia'];
		$acuerdo = $datos['acuerdo'];
		$subacuerdo = $datos['subacuerdo'];

		$primer_trayecto = 1;

		$query = "DELETE FROM hit_reservas_aereos_trayectos WHERE CLAVE_AEREO = '".$clave_aereo."' and NUMERO = '".$numero."'";

		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{

			//echo($clave_aereo."','".$destino."','".$origen."','".$cia."','".$acuerdo."','".$subacuerdo."'");
			$actualizar_tipo_trayecto = "CALL `RESERVAS_ACTUALIZA_TIPOS_TRAYECTO`('R','".$clave_aereo."','".$destino."','".$origen."','".$cia."','".$acuerdo."','".$subacuerdo."')";
			$resultadoactualizartrayecto =$conexion->query($actualizar_tipo_trayecto);
			if($resultadoactualizartrayecto == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}

			//Actualizamos precios de los trayectos que quedan en la reserva
				$resultado_actualiza_precio_resto_trayectos =$conexion->query("select numero
												from hit_reservas_aereos_trayectos
												where CLAVE_AEREO =  '".$clave_aereo."'");
				/*if ($resultado_actualiza_precio_resto_trayectos == FALSE){
					echo('Error en la consulta');
					$resultado_actualiza_precio_resto_trayectos->close();
					$conexion->close();
					exit;
				}*/
				//Cargamos un array con todas las lineas de TRAYECTO			$aereos_trayectos = array();
				$v_hay_trayectos = 0;
				for ($num_fila = 0; $num_fila < $resultado_actualiza_precio_resto_trayectos->num_rows; $num_fila++) {
					$fila = $resultado_actualiza_precio_resto_trayectos->fetch_assoc();

					$actualizar_precios_trayectos = "CALL `RESERVAS_ACTUALIZA_PRECIO_TRAYECTO`('R','".$localizador."','".$orden."','".$fila['numero']."')";
					$actualizarpreciostrayectos =$conexion->query($actualizar_precios_trayectos);
					if($actualizarpreciostrayectos == FALSE){
						$respuesta = $conexion->error;
					}else{
						$respuesta = 'OK';
					}
					$v_hay_trayectos++;
				}
				//Liberar Memoria usada por la consulta
				$resultado_actualiza_precio_resto_trayectos->close();

				//Si no hay trayectos pasamos el procedimiento con numero de trayecto 0 para que deje a cero los precios de la reserva de aereos
				if($v_hay_trayectos == 0){
					$actualizar_precios_trayectos = "CALL `RESERVAS_ACTUALIZA_PRECIO_TRAYECTO`('R','".$localizador."','".$orden."',0)";
					$actualizarpreciostrayectos =$conexion->query($actualizar_precios_trayectos);
					if($actualizarpreciostrayectos == FALSE){
						$respuesta = $conexion->error;
					}else{
						$respuesta = 'OK';
					}
				}
				
				//ACTUALIZAMOS PRECIOS DE LA RESERVA DE AEREOS
				$resultadosumaprecios =$conexion->query("update hit_reservas_aereos 
														set PVP = (select sum(pvp_total_trayecto) from hit_reservas_aereos_trayectos where LOCALIZADOR = ".$localizador." and ORDEN = ".$orden."),
															TASAS_PVP = (select sum(tasas_pvp_total_trayecto) from hit_reservas_aereos_trayectos where LOCALIZADOR = ".$localizador." and ORDEN = ".$orden.")
														where	LOCALIZADOR = ".$localizador." and ORDEN = ".$orden);			

				if($resultadosumaprecios == FALSE){
					$respuesta = $conexion->error;
				}else{
					$respuesta = 'OK';
				}				
				
		}

		return $respuesta;											
	}

	function Insertar_aereos_trayectos_Manual($clave, $fecha, $origen, $destino, $cia, $vuelo, $hora_salida, $hora_llegada,
										$desplazamiento_llegada, $clase, $situacion, $acuerdo, $subacuerdo, $pvp_total_trayecto, $tasas_pvp_total_trayecto, $tipo_precio){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;

		$datos =$conexion->query("select localizador, orden, plazas from hit_reservas_aereos where clave = '".$clave."'");
		$aereos = $datos->fetch_assoc();
		$localizador = $aereos['localizador'];
		$orden = $aereos['orden'];
		$plazas = $aereos['plazas'];

		$orden_max =$conexion->query("SELECT MAX(NUMERO) numero FROM hit_reservas_aereos_trayectos c WHERE CLAVE_AEREO = '".$clave."'");
		$numero = $orden_max->fetch_assoc();
		$nuevo_numero = $numero['numero'] + 1;
		
		$pvp_pax = $pvp_total_trayecto / $plazas;
		$tasas_pax = $tasas_pvp_total_trayecto / $plazas;

		$query = "INSERT INTO hit_reservas_aereos_trayectos (LOCALIZADOR, ORDEN, NUMERO, CLAVE_AEREO, FECHA, ORIGEN,DESTINO, CIA, 
		ACUERDO, SUBACUERDO, VUELO, HORA_SALIDA, HORA_LLEGADA, DESPLAZAMIENTO_LLEGADA, CLASE, SITUACION, 
		PVP_PAX, TASAS_PAX, PVP_TOTAL_TRAYECTO, TASAS_PVP_TOTAL_TRAYECTO, TIPO_PRECIO) VALUES (";
		$query .= "'".$localizador."',";
		$query .= "'".$orden."',";
		$query .= "'".$nuevo_numero."',";
		$query .= "'".$clave."',";
		$query .= "'".date("Y-m-d",strtotime($fecha))."',";
		$query .= "'".$origen."',";
		$query .= "'".$destino."',";
		$query .= "'".$cia."',";
		$query .= "'".$acuerdo."',";
		$query .= "'".$subacuerdo."',";
		$query .= "'".$vuelo."',";
		$query .= "'".$hora_salida."',";
		$query .= "'".$hora_llegada."',";
		$query .= "'".$desplazamiento_llegada."',";
		$query .= "'".$clase."',";
		$query .= "'".$situacion."',";
		$query .= "'".$pvp_pax."',";
		$query .= "'".$tasas_pax."',";
		$query .= "'".$pvp_total_trayecto."',";
		$query .= "'".$tasas_pvp_total_trayecto."',";
		$query .= "'".$tipo_precio."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
		
			$actualizar_tipo_trayecto = "CALL `RESERVAS_ACTUALIZA_TIPOS_TRAYECTO`('R','".$clave."','".$origen."','".$destino."','".$cia."','".$acuerdo."','".$subacuerdo."')";
			$resultadoactualizartrayecto =$conexion->query($actualizar_tipo_trayecto);

			$actualizar_precios_trayectos = "CALL `RESERVAS_ACTUALIZA_PRECIO_TRAYECTO`('R','".$localizador."','".$orden."','".$nuevo_numero."')";
			$actualizarpreciostrayectos =$conexion->query($actualizar_precios_trayectos);

			if($resultadoactualizartrayecto == FALSE){
				$respuesta = $conexion->error;
			}elseif($actualizarpreciostrayectos == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}
			
			//ACTUALIZAMOS PRECIOS DE LA RESERVA DE AEREOS
			$resultadosumaprecios =$conexion->query("update hit_reservas_aereos 
														set PVP = (select sum(pvp_total_trayecto) from hit_reservas_aereos_trayectos where LOCALIZADOR = ".$localizador." and ORDEN = ".$orden."),
															TASAS_PVP = (select sum(tasas_pvp_total_trayecto) from hit_reservas_aereos_trayectos where LOCALIZADOR = ".$localizador." and ORDEN = ".$orden.")
														where	LOCALIZADOR = ".$localizador." and ORDEN = ".$orden);			

			if($resultadosumaprecios == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}
														
		}
		return $respuesta;											
	}

	function Insertar_aereos_trayectos_Cupos($localizador, $clave_aereo, $clave_ida, $clave_vuelta){
		//echo($localizador.' - '.$clave_aereo.' - '.$clave_ida.' - '.$clave_vuelta);
		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;

		$datos =$conexion->query("select localizador, orden from hit_reservas_aereos where clave = '".$clave_aereo."'");
		$aereos = $datos->fetch_assoc();
		$localizador = $aereos['localizador'];
		$orden = $aereos['orden'];

		$orden_max =$conexion->query("SELECT MAX(NUMERO) numero FROM hit_reservas_aereos_trayectos c WHERE CLAVE_AEREO = '".$clave_aereo."'");
		$numero = $orden_max->fetch_assoc();
		$nuevo_numero = $numero['numero'] + 1;

		$datos_cupos_ida =$conexion->query("select fecha, origen, destino, cia, acuerdo, subacuerdo, vuelo, hora_salida, hora_llegada, clase from hit_transportes_cupos where clave = '".$clave_ida."'");
		$cupos_ida = $datos_cupos_ida->fetch_assoc();

		$query = "INSERT INTO hit_reservas_aereos_trayectos (LOCALIZADOR, ORDEN, NUMERO, CLAVE_AEREO, FECHA, ORIGEN,DESTINO, CIA, 
		ACUERDO, SUBACUERDO, VUELO, HORA_SALIDA, HORA_LLEGADA, DESPLAZAMIENTO_LLEGADA, CLASE) VALUES (";
		$query .= "'".$localizador."',";
		$query .= "'".$orden."',";
		$query .= "'".$nuevo_numero."',";
		$query .= "'".$clave_aereo."',";
		$query .= "'".date("Y-m-d",strtotime($cupos_ida['fecha']))."',";
		$query .= "'".$cupos_ida['origen']."',";
		$query .= "'".$cupos_ida['destino']."',";
		$query .= "'".$cupos_ida['cia']."',";
		$query .= "'".$cupos_ida['acuerdo']."',";
		$query .= "'".$cupos_ida['subacuerdo']."',";
		$query .= "'".$cupos_ida['vuelo']."',";
		$query .= "'".$cupos_ida['hora_salida']."',";
		$query .= "'".$cupos_ida['hora_llegada']."',";
		$query .= "'0',";
		$query .= "'".$cupos_ida['clase']."')";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo trayecto. '.$conexion->error;
		}elseif($clave_vuelta != 0){
				$orden_max_vuelta =$conexion->query("SELECT MAX(NUMERO) numero FROM hit_reservas_aereos_trayectos c WHERE CLAVE_AEREO = '".$clave_aereo."'");
				$numero_vuelta = $orden_max_vuelta->fetch_assoc();
				$nuevo_numero_vuelta = $numero_vuelta['numero'] + 1;

				$datos_cupos_vuelta =$conexion->query("select fecha, origen, destino, cia, acuerdo, subacuerdo, vuelo, hora_salida, hora_llegada, clase from hit_transportes_cupos where clave = '".$clave_vuelta."'");
				$cupos_vuelta = $datos_cupos_vuelta->fetch_assoc();

				$query_vuelta = "INSERT INTO hit_reservas_aereos_trayectos (LOCALIZADOR, ORDEN, NUMERO, CLAVE_AEREO, FECHA, ORIGEN,DESTINO, CIA, 
				ACUERDO, SUBACUERDO, VUELO, HORA_SALIDA, HORA_LLEGADA, DESPLAZAMIENTO_LLEGADA, CLASE) VALUES (";
				$query_vuelta .= "'".$localizador."',";
				$query_vuelta .= "'".$orden."',";
				$query_vuelta .= "'".$nuevo_numero_vuelta."',";
				$query_vuelta .= "'".$clave_aereo."',";
				$query_vuelta .= "'".date("Y-m-d",strtotime($cupos_vuelta['fecha']))."',";
				$query_vuelta .= "'".$cupos_vuelta['origen']."',";
				$query_vuelta .= "'".$cupos_vuelta['destino']."',";
				$query_vuelta .= "'".$cupos_vuelta['cia']."',";
				$query_vuelta .= "'".$cupos_vuelta['acuerdo']."',";
				$query_vuelta .= "'".$cupos_vuelta['subacuerdo']."',";
				$query_vuelta .= "'".$cupos_vuelta['vuelo']."',";
				$query_vuelta .= "'".$cupos_vuelta['hora_salida']."',";
				$query_vuelta .= "'".$cupos_vuelta['hora_llegada']."',";
				$query_vuelta .= "'0',";
				$query_vuelta .= "'".$cupos_vuelta['clase']."')";

				$resultadov =$conexion->query($query_vuelta);
				if ($resultadov == FALSE){
					echo($query_vuelta);
					$respuesta = 'No se ha podido insertar el nuevo trayecto de vuelta. '.$conexion->error;
				}else{
			//echo($clave_aereo."','".$cupos_ida['origen']."','".$cupos_ida['origen']."','".$cupos_ida['cia']."','".$cupos_ida['acuerdo']."','".$cupos_ida['subacuerdo']."'");
					$actualizar_tipo_trayecto = "CALL `RESERVAS_ACTUALIZA_TIPOS_TRAYECTO`('R','".$clave_aereo."','".$cupos_ida['origen']."','".$cupos_ida['destino']."','".$cupos_ida['cia']."','".$cupos_ida['acuerdo']."','".$cupos_ida['subacuerdo']."')";
					$resultadoactualizartrayecto =$conexion->query($actualizar_tipo_trayecto);

					$actualizar_precios_trayectos = "CALL `RESERVAS_ACTUALIZA_PRECIO_TRAYECTO`('R','".$localizador."','".$orden."','".$nuevo_numero."')";
					$actualizarpreciostrayectos =$conexion->query($actualizar_precios_trayectos);

					$actualizar_precios_trayectos_vuelta = "CALL `RESERVAS_ACTUALIZA_PRECIO_TRAYECTO`('R','".$localizador."','".$orden."','".$nuevo_numero_vuelta."')";
					$actualizarpreciostrayectos_vuelta =$conexion->query($actualizar_precios_trayectos_vuelta);

					if($resultadoactualizartrayecto == FALSE){
						$respuesta = $conexion->error;
					}elseif($actualizarpreciostrayectos == FALSE){
						$respuesta = $conexion->error;
					}elseif($actualizarpreciostrayectos_vuelta == FALSE){
						$respuesta = $conexion->error;
					}else{
						$respuesta = 'OK';
					}
				}
		}else{
			//echo($clave_aereo."','".$cupos_ida['origen']."','".$cupos_ida['destino']."','".$cupos_ida['cia']."','".$cupos_ida['acuerdo']."','".$cupos_ida['subacuerdo']."'");
			$actualizar_tipo_trayecto = "CALL `RESERVAS_ACTUALIZA_TIPOS_TRAYECTO`('R','".$clave_aereo."','".$cupos_ida['origen']."','".$cupos_ida['destino']."','".$cupos_ida['cia']."','".$cupos_ida['acuerdo']."','".$cupos_ida['subacuerdo']."')";
			$resultadoactualizartrayecto =$conexion->query($actualizar_tipo_trayecto);

			$actualizar_precios_trayectos = "CALL `RESERVAS_ACTUALIZA_PRECIO_TRAYECTO`('R','".$localizador."','".$orden."','".$nuevo_numero."')";
			$actualizarpreciostrayectos =$conexion->query($actualizar_precios_trayectos);

					if($resultadoactualizartrayecto == FALSE){
						$respuesta = $conexion->error;
					}elseif($actualizarpreciostrayectos == FALSE){
						$respuesta = $conexion->error;
					}else{
						$respuesta = 'OK';
					}
		}

		return $respuesta;											
	}

//---------------------------------------------------------
//------ METODOS PARA LA PARTE DE LOS ALOJAMIENTOS - NIVEL 4----
//---------------------------------------------------------

	function Cargar_alojamientos($localizador){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
			

		$resultado =$conexion->query("select ra.clave clave_alojamiento,
										DATE_FORMAT(ra.FECHA_ENTRADA, '%d-%m-%Y') AS fecha_entrada_alojamiento,
										case 
											when ra.INTERFAZ_NOMBRE_ALOJAMIENTO is null then a.NOMBRE
											else ra.INTERFAZ_NOMBRE_ALOJAMIENTO 
											end 
										nombre_alojamiento, 
										ra.alojamiento, ra.orden orden_alojamiento,
										ra.HABITACION habitacion_alojamiento, 
										ra.CARACTERISTICA caracteristica_alojamiento,
										ra.USO uso_alojamiento, ra.REGIMEN regimen_alojamiento, ra.ACUERDO acuerdo_alojamiento, 
										ra.NOCHES noches_alojamiento, ra.CANTIDAD_HABITACIONES cantidad_habitaciones_alojamiento,ra.PAX pax_alojamiento, ra.ADULTOS adultos_alojamiento,ra.NINOS1 ninos1_alojamiento, ra.BEBES bebes_alojamiento ,ra.NOVIOS novios_alojamiento, 
										ra.SITUACION situacion_alojamiento, 
										ac.TIPO tipo_acuerdo_alojamiento, 
										CASE ac.TIPO
											WHEN 'C' THEN 'Cupos'
											WHEN 'O' THEN 'On-request'
											WHEN 'I' THEN 'Interfaz'
											ELSE ac.TIPO
										END tipo_nombre_acuerdo_alojamiento,
										ra.OBSERVACIONES observaciones_alojamiento,
										ra.PVP_TOTAL_ALOJAMIENTO pvp_total_alojamiento,
										ra.TIPO_PRECIO tipo_precio_alojamiento,
										ra.INTERFAZ_CODIGO interfaz_codigo_alojamiento,
										ra.INTERFAZ_CODIGO_ALOJAMIENTO interfaz_codigo_alojamiento_alojamiento,
										ra.INTERFAZ_CARACTERISTICA interfaz_caracteristica_alojamiento,
										ra.INTERFAZ_LINEAS interfaz_lineas_alojamiento,
										ra.INTERFAZ_LOCALIZADOR interfaz_localizador_alojamiento,
										ra.INTERFAZ_IMPORTE interfaz_importe_alojamiento,
										ra.INTERFAZ_PORCENTAJE_CANCELACION interfaz_porcentaje_cancelacion_alojamiento,
										ra.INTERFAZ_LOCALIZADOR_BAJA interfaz_localizador_baja_alojamiento
										from hit_reservas_alojamientos ra, hit_alojamientos a, hit_alojamientos_acuerdos ac
										where
											ra.ALOJAMIENTO = a.ID
											and ra.ALOJAMIENTO = ac.ID
											and ra.ACUERDO = ac.ACUERDO
											and ra.LOCALIZADOR = '".$localizador."' order by ra.FECHA_ENTRADA, ra.ORDEN,  ra.USO");

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}
		//Cargamos un array con todas las lineas de alojamientos
		$alojamientos = array();
		for ($num_fila = 0; $num_fila < $resultado->num_rows; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($alojamientos,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $alojamientos;											
	}



	function Modificar_alojamientos($clave, $habitacion, $habitacion_car, $uso, $regimen, $acuerdo, $noches, $cantidad_habitaciones,$adultos, $ninos, $bebes, $novios, $situacion,$orden, $pvp_total, $tipo_precio, $interfaz_porcentaje_cancelacion, $interfaz_localizador_baja){

		//echo('-'.$interfaz_porcentaje_cancelacion.'-'.$interfaz_localizador_baja.'-');

		$conexion = $this ->Conexion;

		
		if($regimen == 'AD'){
				$coste_regimen = "ate.SPTO_AD";
		}elseif($regimen == 'MP'){
				$coste_regimen = "ate.SPTO_MP";
		}elseif($regimen == 'PC'){
				$coste_regimen = "ate.SPTO_PC";
		}elseif($regimen == 'TI'){
				$coste_regimen = "ate.SPTO_TI";
		}else{
				$coste_regimen = "0";
		}


		//obtenemos los costes 
		$costes =$conexion->query("SELECT ac.DIVISA, apr.COSTE_PAX, apr.COSTE_HABITACION, ".$coste_regimen." COSTE_REGIMEN, apr.CALCULO,
									apr.PORCENTAJE_NETO, apr.NETO_PAX, apr.NETO_HABITACION, apr.PORCENTAJE_COM, apr.PVP_PAX, apr.PVP_HABITACION
									FROM hit_reservas_alojamientos ra, 
										  hit_alojamientos_acuerdos ac,
										  hit_alojamientos_periodos ap,
										  hit_alojamientos_temporadas ate,
										  hit_alojamientos_precios apr
									WHERE 
										ra.ALOJAMIENTO = ac.ID
										and ra.ACUERDO = ac.ACUERDO
										and ra.ALOJAMIENTO = ap.ID
										and ra.ALOJAMIENTO = ap.ID
										and ra.FECHA_ENTRADA between ap.FECHA_DESDE and ap.FECHA_HASTA
										and ra.ALOJAMIENTO = ate.ID
										and ra.ACUERDO = ate.ACUERDO
										and ap.TEMPORADA = ate.TEMPORADA
										and ate.TEMPORADA = apr.TEMPORADA
										and ra.ALOJAMIENTO = apr.ID
										and ra.ACUERDO = apr.ACUERDO
										and ra.HABITACION = apr.HABITACION
										and ra.CARACTERISTICA = apr.CARACTERISTICA
										and ra.USO = apr.USO
										and ra.CLAVE = '".$clave."'");
		$linea_costes = $costes->fetch_assoc();

		//$pax = $adultos + $ninos + $bebes;
		$pax = $uso * $cantidad_habitaciones;
		$pvp_pax = $pvp_total / $pax;
		$pvp_habitacion = $pvp_total / $cantidad_habitaciones;

		//echo($tipo_precio.'-'.$pvp_total);
		


		//El actual tipo de precio
		$qtipo_precio_old =$conexion->query("SELECT tipo_precio FROM hit_reservas_alojamientos WHERE  CLAVE = '".$clave."'");
		$otipo_precio_old = $qtipo_precio_old->fetch_assoc();
		$tipo_precio_old = $otipo_precio_old['tipo_precio'];



		$query = "UPDATE hit_reservas_alojamientos SET ";
		$query .= " ORDEN = '".$orden."'";
		if($tipo_precio != 'I' && $tipo_precio_old != 'I'){
			$query .= ", HABITACION = '".$habitacion."'";
			$query .= ", CARACTERISTICA = '".$habitacion_car."'";
			$query .= ", USO = '".$uso."'";
			$query .= ", REGIMEN = '".$regimen."'";
			$query .= ", ACUERDO = '".$acuerdo."'";
			$query .= ", NOCHES = '".$noches."'";
			$query .= ", CANTIDAD_HABITACIONES = '".$cantidad_habitaciones."'";
			$query .= ", PAX = '".$pax."'";
			$query .= ", ADULTOS = '".$adultos."'";
			$query .= ", NINOS1 = '".$ninos."'";
			$query .= ", BEBES = '".$bebes."'";
			$query .= ", NOVIOS = '".$novios."'";
			$query .= ", SITUACION = '".$situacion."'";
			$query .= ", DIVISA = '".$linea_costes['DIVISA']."'"; 
			$query .= ", CAMBIO = '1'";  
			if($tipo_precio == 'A'){
				$query .= ", COSTE_PAX = '".$linea_costes['COSTE_PAX']."'"; 
				$query .= ", COSTE_HABITACION = '".$linea_costes['COSTE_HABITACION']."'"; 
				$query .= ", COSTE_REGIMEN = '".$linea_costes['COSTE_REGIMEN']."'";  
				$query .= ", CALCULO = '".$linea_costes['CALCULO']."'";  
				$query .= ", NETO_PORCENTAJE = '".$linea_costes['PORCENTAJE_NETO']."'"; 
				$query .= ", NETO_PAX = '".$linea_costes['NETO_PAX']."'";  
				$query .= ", NETO_HABITACION = '".$linea_costes['NETO_HABITACION']."'";  
				$query .= ", PVP_PORCENTAJE = '".$linea_costes['PORCENTAJE_COM']."'"; 
			}
		}

		if($tipo_precio == 'I' && $tipo_precio_old == 'I' and ($situacion == 'OK' || $situacion == 'AN')){
			$query .= ", SITUACION = '".$situacion."'";
		}

		if($tipo_precio_old == 'I'){
			$query .= ", TIPO_PRECIO = '".$tipo_precio_old."'";
		}else{
			$query .= ", TIPO_PRECIO = '".$tipo_precio."'";			
		}

		/*if($tipo_precio_old == 'I' && $tipo_precio == 'A'){
			$query .= ", TIPO_PRECIO = '".$tipo_precio_old."'";
		}*/

		if($tipo_precio != 'I' && $tipo_precio_old != 'I'){
			if($tipo_precio == 'A'){
				$query .= ", PVP_PAX = '".$linea_costes['PVP_PAX']."'"; 
				$query .= ", PVP_HABITACION = '".$linea_costes['PVP_HABITACION']."'";
			}else{
				$query .= ", PVP_PAX = '".$pvp_pax."'";
				$query .= ", PVP_HABITACION = '".$pvp_habitacion."'";
				$query .= ", PVP_TOTAL_ALOJAMIENTO = '".$pvp_total."'";
			}
		}

		$query .= ", INTERFAZ_PORCENTAJE_CANCELACION = '".$interfaz_porcentaje_cancelacion."'";
		$query .= ", INTERFAZ_LOCALIZADOR_BAJA = '".$interfaz_localizador_baja."'";
		 
		$query .= " WHERE CLAVE = '".$clave."'";


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

	function Borrar_alojamientos($clave){

		$conexion = $this ->Conexion;


		$tipo =$conexion->query("SELECT ra.interfaz_codigo, ra.interfaz_localizador, ra.interfaz_localizador_corto  FROM hit_reservas_alojamientos ra WHERE  ra.CLAVE = '".$clave."'");
		$otipo = $tipo->fetch_assoc();
		$tipo_interfaz = $otipo['interfaz_codigo'];
		$interfaz_localizador = $otipo['interfaz_localizador'];
		$interfaz_localizador_corto = $otipo['interfaz_localizador_corto'];


		//Si la linea de alojamientos no es de interfaz la borramos. Si lo es llamamos a la funcion de cancelacion del interfaz

		if($tipo_interfaz == ''){
			$query = "DELETE FROM hit_reservas_alojamientos WHERE CLAVE = '".$clave."'";

			$resultadob =$conexion->query($query);

			if ($resultadob == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
			}
		}else{
			//$respuesta = 'Esta es una reserva de alojamiento de '.$tipo_interfaz.' y no es posible borrarla';

			$ClaseInterfazReservas = new clsInterfaz_reserva($conexion);

			//PEDIR Y GRABAR INFORMACION DE GASTOS


			//CANCELAR RESERVA Y GRABAR RESPUESTA

			$interfaz_cancelacion = $ClaseInterfazReservas->Cancelar_reserva($tipo_interfaz, $interfaz_localizador, $interfaz_localizador_corto);
				
			if($interfaz_cancelacion['estado'] == '00'){

				//$cancelacion  .= $interfaz_cancelacion['estado'].'+'.$interfaz_cancelacion['localizador'].'+'.$interfaz_cancelacion['localizador_baja'].'*';
				

				$query = "UPDATE hit_reservas_alojamientos SET ";
				$query .= "SITUACION = 'AN'";
				$query .= ", INTERFAZ_PORCENTAJE_CANCELACION = 0";
				$query .= ", INTERFAZ_LOCALIZADOR_BAJA = '".$interfaz_cancelacion['localizador_baja']."'";
				$query .= " WHERE CLAVE = '".$clave."'";

				$resultadob =$conexion->query($query);

				if ($resultadob == FALSE){
					$respuesta = $conexion->error;
				}else{
					$respuesta = $interfaz_cancelacion['localizador_baja'];
				}

			}else{
				$respuesta = $tipo_interfaz.': '.$interfaz_cancelacion['localizador_baja'];
			}



		}

		return $respuesta;											
	}

	function Insertar_alojamientos_cupos($localizador, $clave, $uso, $regimen, $noches, $numero_habitaciones, $adultos, $ninos, $bebes, $novios){

		$conexion = $this ->Conexion;

		$cupos =$conexion->query("SELECT ID, ACUERDO, HABITACION, CARACTERISTICA, FECHA, CUPO, OCUPADAS FROM hit_alojamientos_cupos c WHERE CLAVE = '".$clave."'");
		$linea_cupo = $cupos->fetch_assoc();

		//$pax = $adultos + $ninos + $bebes;

		$pax_nuevos = $uso * $numero_habitaciones;

		if($adultos >= $pax_nuevos){
			$adultos =  $pax_nuevos;
			$ninos = 0;
			$bebes = 0;
		}elseif($adultos + $ninos >= $pax_nuevos){
			$adultos =  $pax_nuevos - $ninos;
			$ninos = $ninos;
			$bebes = 0;
		}elseif($adultos + $ninos + $bebes >= $pax_nuevos){
			$adultos =  $pax_nuevos - $ninos - $bebes;
			$ninos = $ninos;
			$bebes = $bebes;
		}else{
			$adultos =  $pax_nuevos;
			$ninos = 0;
			$bebes = 0;
		}

		if($regimen == 'AD'){
				$coste_regimen = "temporadas.SPTO_AD";
		}elseif($regimen == 'MP'){
				$coste_regimen = "temporadas.SPTO_MP";
		}elseif($regimen == 'PC'){
				$coste_regimen = "temporadas.SPTO_PC";
		}elseif($regimen == 'TI'){
				$coste_regimen = "temporadas.SPTO_TI";
		}else{
				$coste_regimen = "0";
		}

		$orden =$conexion->query("SELECT (ifnull(max(orden),0) + 1) ORDEN FROM hit_reservas_alojamientos a WHERE LOCALIZADOR = '".$localizador."' AND a.ALOJAMIENTO = '".$linea_cupo['ID']."'");
		$linea_orden = $orden->fetch_assoc();

		//echo($clave);

		//ECHO($linea_cupo['FECHA']);
		$query = "INSERT INTO hit_reservas_alojamientos (LOCALIZADOR, FECHA_ENTRADA, ALOJAMIENTO, ORDEN, HABITACION, CARACTERISTICA, USO, REGIMEN, ACUERDO,NOCHES, CANTIDAD_HABITACIONES, PAX, ADULTOS, NINOS1, BEBES, NOVIOS, JUBILADOS, DIVISA, CAMBIO, COSTE_PAX, COSTE_HABITACION, COSTE_REGIMEN, CALCULO, NETO_PORCENTAJE, NETO_PAX, NETO_HABITACION, PVP_PORCENTAJE, PVP_PAX, PVP_HABITACION) 
		SELECT '".$localizador."', '".$linea_cupo['FECHA']."', '".$linea_cupo['ID']."', '".$linea_orden['ORDEN']."', '".$linea_cupo['HABITACION']."', '".$linea_cupo['CARACTERISTICA']."', precios.USO, '".$regimen."', '".$linea_cupo['ACUERDO']."', '".$noches."', '".$numero_habitaciones."', '".$pax_nuevos."', '".$adultos."', '".$ninos."', '".$bebes."', '".$novios."', 0, acuerdos.DIVISA, 1, precios.COSTE_PAX,  precios.COSTE_HABITACION, ".$coste_regimen.", precios.CALCULO, precios.PORCENTAJE_NETO, precios.NETO_PAX, precios.NETO_HABITACION, precios.PORCENTAJE_COM, precios.PVP_PAX, precios.PVP_HABITACION
					FROM
					  hit_alojamientos_acuerdos acuerdos,
					  hit_alojamientos_periodos periodos,
					  hit_alojamientos_temporadas temporadas,
					  hit_alojamientos_precios precios,
					  hit_alojamientos alojamientos
					where ".$linea_cupo['ID']." = alojamientos.ID
					  and '".$linea_cupo['ID']."' = acuerdos.ID
					  and '".$linea_cupo['ACUERDO']."' = acuerdos.ACUERDO
					  and '".$linea_cupo['ID']."' = periodos.ID
					  and '".$linea_cupo['ACUERDO']."' = periodos.ACUERDO
					  and '".$linea_cupo['FECHA']."' between periodos.FECHA_DESDE and periodos.FECHA_HASTA
					  and periodos.ID = temporadas.ID
					  and periodos.ACUERDO = temporadas.ACUERDO
					  and periodos.TEMPORADA = temporadas.TEMPORADA
					  and '".$linea_cupo['ID']."' = precios.ID
					  and '".$linea_cupo['ACUERDO']."' = precios.ACUERDO
					  and temporadas.TEMPORADA = precios.TEMPORADA
					  and '".$linea_cupo['HABITACION']."' = precios.HABITACION
					  and '".$linea_cupo['CARACTERISTICA']."' = precios.CARACTERISTICA
					  and precios.USO = '".$uso."'";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';

		}
		return $respuesta;											
	}

	function Insertar_alojamientos_onrequest($localizador, $fecha_desde, $clave, $uso, $regimen, $noches, $numero_habitaciones, $adultos, $ninos, $bebes, $novios){

		$conexion = $this ->Conexion;

		$cupos =$conexion->query("SELECT ID, ACUERDO, HABITACION, CARACTERISTICA, FECHA, CUPO, OCUPADAS FROM hit_alojamientos_cupos c WHERE CLAVE = '".$clave."'");
		$linea_cupo = $cupos->fetch_assoc();

		//$pax = $adultos + $ninos + $bebes;

		$pax_nuevos = $uso * $numero_habitaciones;

		if($adultos >= $pax_nuevos){
			$adultos =  $pax_nuevos;
			$ninos = 0;
			$bebes = 0;
		}elseif($adultos + $ninos >= $pax_nuevos){
			$adultos =  $pax_nuevos - $ninos;
			$ninos = $ninos;
			$bebes = 0;
		}elseif($adultos + $ninos + $bebes >= $pax_nuevos){
			$adultos =  $pax_nuevos - $ninos - $bebes;
			$ninos = $ninos;
			$bebes = $bebes;
		}else{
			$adultos =  $pax_nuevos;
			$ninos = 0;
			$bebes = 0;
		}

		if($regimen == 'AD'){
				$coste_regimen = "temporadas.SPTO_AD";
		}elseif($regimen == 'MP'){
				$coste_regimen = "temporadas.SPTO_MP";
		}elseif($regimen == 'PC'){
				$coste_regimen = "temporadas.SPTO_PC";
		}elseif($regimen == 'TI'){
				$coste_regimen = "temporadas.SPTO_TI";
		}else{
				$coste_regimen = "0";
		}
		
		$orden =$conexion->query("SELECT (ifnull(max(orden),0) + 1) ORDEN FROM hit_reservas_alojamientos a WHERE LOCALIZADOR = '".$localizador."' AND a.ALOJAMIENTO = '".$linea_orden['ID']."'");
		$linea_orden = $orden->fetch_assoc();

		//ECHO($linea_cupo['FECHA']);
		$query = "INSERT INTO hit_reservas_alojamientos (LOCALIZADOR, FECHA_ENTRADA, ALOJAMIENTO, ORDEN, HABITACION, CARACTERISTICA, USO, REGIMEN, ACUERDO,NOCHES, CANTIDAD_HABITACIONES, PAX, ADULTOS, NINOS1, NINOS2, BEBES, NOVIOS, JUBILADOS, DIVISA, CAMBIO, COSTE_PAX, COSTE_HABITACION, COSTE_REGIMEN, CALCULO, NETO_PORCENTAJE, NETO_PAX, NETO_HABITACION, PVP_PORCENTAJE, PVP_PAX, PVP_HABITACION) 
		SELECT '".$localizador."', '".date("Y-m-d",strtotime($fecha_desde))."', precios.ID, '".$linea_orden['ORDEN']."', precios.HABITACION, precios.CARACTERISTICA, precios.USO, '".$regimen."', precios.ACUERDO, '".$noches."', '".$numero_habitaciones."', '".$pax_nuevos."', '".$adultos."', '".$ninos."', '".$bebes."', '".$novios."', 0, acuerdos.DIVISA, 1, precios.COSTE_PAX,  precios.COSTE_HABITACION, ".$coste_regimen.", precios.CALCULO, precios.PORCENTAJE_NETO, precios.NETO_PAX, precios.NETO_HABITACION, precios.PORCENTAJE_COM, precios.PVP_PAX, precios.PVP_HABITACION
					FROM
					  hit_alojamientos_acuerdos acuerdos,
					  hit_alojamientos_periodos periodos,
					  hit_alojamientos_temporadas temporadas,
					  hit_alojamientos_precios precios,
					  hit_alojamientos alojamientos
					where precios.ID = alojamientos.ID
					  and precios.ID = acuerdos.ID
					  and precios.ACUERDO = acuerdos.ACUERDO
					  and precios.ID = periodos.ID
					  and precios.ACUERDO = periodos.ACUERDO
					  and '".date("Y-m-d",strtotime($fecha_desde))."' between periodos.FECHA_DESDE and periodos.FECHA_HASTA
					  and periodos.ID = temporadas.ID
					  and periodos.ACUERDO = temporadas.ACUERDO
					  and periodos.TEMPORADA = temporadas.TEMPORADA
					  and temporadas.TEMPORADA = precios.TEMPORADA
					  and precios.clave = '".$clave."'";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';

		}
		return $respuesta;											
	}



//-----------------------------------------------------------
//------ METODOS PARA LA PARTE DE LOS SERVICIOS - NIVEL 5----
//-----------------------------------------------------------

	function Cargar_servicios($localizador){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
			

		$resultado =$conexion->query("select DATE_FORMAT(rs.FECHA, '%d-%m-%Y') AS servicios_fecha, rs.ORDEN servicios_orden, rs.clave servicios_clave,
											p.NOMBRE servicios_nombre_proveedor, s.NOMBRE servicios_nombre_servicio, 
											s.ID servicios_id_servicio,
											 rs.PAX servicios_pax, rs.ADULTOS servicios_adultos, rs.NINOS servicios_ninos, 
											 rs.VECES servicios_veces, rs.SITUACION servicios_situacion, 
											CASE s.TIPO_CUPO
												WHEN 'F' THEN 'Free Booking'
												WHEN 'O' THEN 'On-request'
												ELSE s.TIPO_CUPO
											END servicios_tipo_cupo, 
											rs.PVP_TOTAL_SERVICIO servicios_pvp_total,
											rs.TIPO_PRECIO servicios_tipo_precio
									from
										hit_reservas_servicios rs,
										hit_servicios s,
										hit_proveedores p
									where
										rs.ID_SERVICIO = s.ID
										and s.ID_PROVEEDOR = p.ID
										and rs.LOCALIZADOR = '".$localizador."' order by rs.fecha, rs.orden");

		if ($resultado == FALSE){
			echo('Error en la consulta: '.$CADENA_BUSCAR);
			$resultado->close();
			$conexion->close();
			exit;
		}
		//Cargamos un array con todas las lineas de alojamientos
		$servicios = array();
		for ($num_fila = 0; $num_fila < $resultado->num_rows; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($servicios,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $servicios;											
	}



	function Modificar_servicios($clave,$orden,$adultos,$ninos,$veces,$situacion,$pvp_total,$tipo_precio){

		$conexion = $this ->Conexion;


		$pax = $adultos + $ninos;

		$query = "UPDATE hit_reservas_servicios SET ";
		$query .= " ORDEN = '".$orden."'";
		$query .= ", PAX = '".$pax."'";
		$query .= ", ADULTOS = '".$adultos."'";
		$query .= ", NINOS = '".$ninos."'";
		$query .= ", VECES = '".$veces."'";
		$query .= ", SITUACION = '".$situacion."'";
		$query .= ", PVP_TOTAL_SERVICIO = '".$pvp_total."'";
		$query .= ", TIPO_PRECIO = '".$tipo_precio."'";
		$query .= " WHERE CLAVE = '".$clave."'";


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

	function Borrar_servicios($clave){

		$conexion = $this ->Conexion;

		$query = "DELETE FROM hit_reservas_servicios WHERE CLAVE = '".$clave."'";


		$resultadob =$conexion->query($query);

		if ($resultadob == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	function Insertar_servicios($localizador, $fecha, $clave, $pax, $adultos, $ninos, $bebes, $novios){

		$conexion = $this ->Conexion;

		$orden =$conexion->query("SELECT MAX(ORDEN) numero FROM hit_reservas_servicios c WHERE LOCALIZADOR = '".$localizador."'");
		$linea_orden = $orden->fetch_assoc();

		$nuevo_orden = $linea_orden['numero'] + 1;
		//$pax = $adultos + $ninos + $bebes;
		if($pax == null){
			$pax_nuevos = $adultos + $ninos + $bebes;
		}else{
			$pax_nuevos = $pax;
		}

		if($adultos >= $pax_nuevos){
			$adultos =  $pax_nuevos;
			$ninos = 0;
			$bebes = 0;
		}elseif($adultos + $ninos >= $pax_nuevos){
			$adultos =  $pax_nuevos - $ninos;
			$ninos = $ninos;
			$bebes = 0;
		}elseif($adultos + $ninos + $bebes >= $pax_nuevos){
			$adultos =  $pax_nuevos - $ninos - $bebes;
			$ninos = $ninos;
			$bebes = $bebes;
		}else{
			$adultos =  $pax_nuevos;
			$ninos = 0;
			$bebes = 0;
		}



		//ECHO($linea_cupo['FECHA']);
		$query = "INSERT INTO hit_reservas_servicios (LOCALIZADOR, FECHA, ORDEN, ID_SERVICIO, SITUACION, ID_PROVEEDOR, CODIGO, PAX, ADULTOS, NINOS, NOVIOS, JUBILADOS, VECES, DIVISA, CAMBIO, TIPO_PVP) 
					SELECT '".$localizador."', '".date("Y-m-d",strtotime($fecha))."', '".$nuevo_orden."', '".$clave."',	
					CASE TIPO_CUPO
						WHEN 'F' THEN 'OK'
						WHEN 'O' THEN 'OR'
						ELSE 'OR'
					END,
					ID_PROVEEDOR,
					CODIGO,
					'".$pax_nuevos."',
					'".$adultos."',
					'".$ninos."',
					'".$novios."',
					0,
					1,
					DIVISA,
					1,
					TIPO_PVP
					FROM
					  hit_servicios
					where 
					  ID = '".$clave."'";

		$resultadoi =$conexion->query($query);
		if ($resultadoi == FALSE){
			$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
		}else{
			$respuesta = 'OK';

		}
		return $respuesta;											
	}

//-------------------------------------------------------------
//------ METODOS PARA LA PARTE DE LAS CONDICIONES - NIVEL 6----
//-------------------------------------------------------------

	function Cargar_condiciones($localizador){

		$conexion = $this ->Conexion;
		$Usuario = $this ->Usuario;
			

		$resultado =$conexion->query("select CASE habitacion
										WHEN -1 THEN 'Base'
											else habitacion
										END habitacion_reserva, 
										detalle, precio_pax, pax, precio_total
										from hit_reservas_condiciones
										where
											LOCALIZADOR = '".$localizador."' order by habitacion, detalle");


		//Cargamos un array con todas las lineas de alojamientos
		$condiciones = array();
		for ($num_fila = 0; $num_fila < $resultado->num_rows; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			array_push($condiciones,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();

		return $condiciones;											
	}


	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsReservas($conexion, $filadesde, $usuario, $buscar_localizador, $buscar_referencia, $buscar_referencia_agencia, $buscar_folleto, $buscar_cuadro, $buscar_fecha_salida, $buscar_fecha_reserva, $buscar_minorista, $buscar_oficina, $buscar_telefono_oficina, $buscar_nombre){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_localizador = $buscar_localizador;
		$this->Buscar_referencia = $buscar_referencia;
		$this->Buscar_referencia_agencia = $buscar_referencia_agencia;
		$this->Buscar_folleto = $buscar_folleto;
		$this->Buscar_cuadro = $buscar_cuadro;
		$this->Buscar_fecha_salida = $buscar_fecha_salida;
		$this->Buscar_fecha_reserva = $buscar_fecha_reserva;
		$this->Buscar_minorista = $buscar_minorista;
		$this->Buscar_oficina = $buscar_oficina;
		$this->Buscar_telefono_oficina = $buscar_telefono_oficina;
		$this->Buscar_nombre = $buscar_nombre;
	}
}

?>