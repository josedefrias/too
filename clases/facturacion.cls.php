<?php

require 'clases_web/Reservas_fin.cls.php';

class clsFacturacion{

	var $Filadesde;
	var $Usuario;
	//ESPECIFICO: Variables especificas de esta pantalla
	var $buscar_codigo;
	var	$buscar_nombre;
	//--------------------------------------------------

	function Cargar(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_grupo = $this->Buscar_grupo;
		$buscar_estado = $this->Buscar_estado;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_factura = $this->Buscar_factura;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_factura != null){
			$CADENA_BUSCAR = " and r.factura = '".$buscar_factura."'";		
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$grup = " AND m.grupo_gestion = ".$buscar_grupo;	
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_estado != null){
				if($buscar_estado == 'P'){
					$estad = " AND r.factura = 0";
				}else{
					$estad = " AND r.factura > 0";				
				}
				$CADENA_BUSCAR .= $estad;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}			
			
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	
		
		$resultado =$conexion->query("select DATE_FORMAT(r.FECHA_SALIDA, '%d-%m-%Y') AS fecha_salida,
												r.localizador localizador, 
												r.referencia referencia,
												 m.NOMBRE agencia, o.OFICINA oficina, o.DIRECCION direccion, 
												 r.PVP_TOTAL importe, r.DIVISA_ACTUAL moneda, 
												 case
												 when r.factura = 0 then ' Pediente'
												 when r.factura > 0 then ' Facturado'
												 end estado, 
												 r.factura factura,
												 DATE_FORMAT(r.FACTURA_FECHA_EMISION, '%d-%m-%Y') AS fecha
										from hit_reservas r,
											  hit_minoristas m,
											  hit_oficinas o
										where 
												r.MINORISTA = m.ID
												and r.MINORISTA = o.ID
												and r.OFICINA = o.OFICINA
												and r.pvp_total > 0 ".$CADENA_BUSCAR." order by r.FECHA_SALIDA,m.NOMBRE,r.localizador");
		//echo($CADENA_BUSCAR.'<br>');
		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Guardamos el resultado en una matriz con un numero fijo de registros
		//que controlaremos por una tabla de configuracion de pantallas de usuarios. ESPECIFICO: Solo el nombre del formulario en la query
		$numero_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'FACTURACION' AND USUARIO = '".$Usuario."'");
		$Nfilas	 = $numero_filas->fetch_assoc();											                        //------

		$facturacion = array();
		for ($num_fila = $Filadesde-1; $num_fila <= $Filadesde + $Nfilas['LINEAS_MODIFICACION']-2; $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			if($fila['localizador'] == ''){
				break;
			}
			array_push($facturacion,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		$numero_filas->close();

		return $facturacion;											
	}

	
	function Cargar_Importe_Total(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_grupo = $this->Buscar_grupo;
		$buscar_estado = $this->Buscar_estado;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_factura = $this->Buscar_factura;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_factura != null){
			$CADENA_BUSCAR = " and r.factura = '".$buscar_factura."'";		
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$grup = " AND m.grupo_gestion = ".$buscar_grupo;	
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_estado != null){
				if($buscar_estado == 'P'){
					$estad = " AND r.factura = 0";
				}else{
					$estad = " AND r.factura > 0";				
				}
				$CADENA_BUSCAR .= $estad;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}			
			
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	
		
		$resultado =$conexion->query("select ifnull(sum(r.PVP_TOTAL),0) IMPORTE_TOTAL
										from hit_reservas r,
											  hit_minoristas m,
											  hit_oficinas o
										where 
												r.MINORISTA = m.ID
												and r.MINORISTA = o.ID
												and r.OFICINA = o.OFICINA
												and r.pvp_total > 0 ".$CADENA_BUSCAR." order by r.FECHA_SALIDA,m.NOMBRE,r.localizador");
		//echo($CADENA_BUSCAR);
		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/

		$fila = $resultado->fetch_assoc();
		$importe_total = $fila['IMPORTE_TOTAL'];
		
		//Liberar Memoria usada por la consulta
		$resultado->close();


		return $importe_total;											
	}

	function Cargar_combo_selector(){

		$Usuario = $this ->Usuario;
		$conexion = $this ->Conexion;		

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_grupo = $this->Buscar_grupo;
		$buscar_estado = $this->Buscar_estado;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_factura = $this->Buscar_factura;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_factura != null){
			$CADENA_BUSCAR = " and r.factura = '".$buscar_factura."'";		
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$grup = " AND m.grupo_gestion = ".$buscar_grupo;	
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_estado != null){
				if($buscar_estado == 'P'){
					$estad = " AND r.factura = 0";
				}else{
					$estad = " AND r.factura > 0";				
				}
				$CADENA_BUSCAR .= $estad;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}			
			
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	

												//ESPCIFICO: nombre tabla
		$resultadoc =$conexion->query("select *
										from hit_reservas r,
											  hit_minoristas m,
											  hit_oficinas o
										where 
												r.MINORISTA = m.ID
												and r.MINORISTA = o.ID
												and r.OFICINA = o.OFICINA
												and r.pvp_total > 0 ".$CADENA_BUSCAR);
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
			$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'FACTURACION' AND USUARIO = '".$Usuario."'");
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
		$num_filas =$conexion->query("SELECT LINEAS_MODIFICACION FROM hit_usuarios_formularios WHERE FORMULARIO = 'FACTURACION' AND USUARIO = '".$Usuario."'");
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

	function Facturar_reserva($localizador){

		$usuario = $this->Usuario;
		$conexion = $this ->Conexion;


		//OBTENEMOS SIGUIENTE NUMERO DE FACTURA DISPONIBLE
		$datos_factura =$conexion->query("select max(factura) + 1 factura from hit_reservas");
		$odatos_factura = $datos_factura->fetch_assoc();
		$factura = $odatos_factura['factura'];

		$datos_factura_numero =$conexion->query("select factura facturado, pvp_total pvp_total, situacion situacion, fecha_salida salida, referencia referencia from hit_reservas where localizador = '".$localizador."'");
		$odatos_factura_numero = $datos_factura_numero->fetch_assoc();
		$facturado = $odatos_factura_numero['facturado'];
		$pvp_total = $odatos_factura_numero['pvp_total'];
		$situacion = $odatos_factura_numero['situacion'];
		$referencia = $odatos_factura_numero['referencia'];
		//$fecha_facturacion = $odatos_factura_numero['salida'];

		
		if($facturado == 0 and $pvp_total > 0 and $situacion != 'P'){
			$query = "UPDATE hit_reservas SET ";
			$query .= " FACTURA = '".$factura."'";
			//$query .= ", FACTURA_FECHA_EMISION = '".$fecha_facturacion."'";
			$query .= ", FACTURA_FECHA_EMISION = CURDATE()";
			$query .= " WHERE REFERENCIA = '".$referencia."' and FACTURA = 0";

			$resultadom =$conexion->query($query);

			if ($resultadom == FALSE){
				$respuesta = $conexion->error;
			}else{
				$respuesta = 'OK';
				
				////////////////////////////////////////////////////////////////////////////////////////////////				
				//ENVIAR MAIL INFORMANDO A LA AGENCIA DE LA DISPONIBILIDAD DE LA FACTURA
				////////////////////////////////////////////////////////////////////////////////////////////////

				//OBTENEMOS NOMBRE Y PRODUCTO DEL VIAJE
				$datos_nombre_viaje =$conexion->query("select nombre 
														from hit_producto_cuadros c, hit_reservas r 
														where c.FOLLETO = r.FOLLETO and c.CUADRO = r.CUADRO and r.LOCALIZADOR = '".$localizador."'");
				$onombre_viaje = $datos_nombre_viaje->fetch_assoc();
				$nombre_viaje = $onombre_viaje['nombre'];

				//OBTENEMOS SALIDA, REGRESO, ESTADO Y DATOS AGENCIA
				$datos_salida_viaje =$conexion->query("select DATE_FORMAT(fecha_salida, '%d-%m-%Y') AS fecha_salida, DATE_FORMAT(fecha_regreso, '%d-%m-%Y') AS fecha_regreso, minorista, oficina
				from hit_reservas where localizador = '".$localizador."'");
				$odatos_salida_viaje = $datos_salida_viaje->fetch_assoc();
				$datos_salida = $odatos_salida_viaje['fecha_salida'];
				$datos_regreso = $odatos_salida_viaje['fecha_regreso'];
				$reserva_minorista = $odatos_salida_viaje['minorista'];
				$reserva_oficina = $odatos_salida_viaje['oficina'];

				$oReservas_fin = new clsReservas_fin($conexion, $localizador);
				$sdatos_pasajeros = $oReservas_fin->Cargar_pasajeros();
				
				///AQUI MONTAMOS EL HTML PARA EL CONTENIDO DEL CORREO ELECTRONICO

					$mensaje_html = "
					<html lang='es'>
					<head>	
						<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
					</head>

					<body>

					<img src='imagenes/Logo_Mail.jpg' align='center' height='60' width='300'>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:25px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Les informamos que ya esta disponible en nuestra web la factura de la reserva:<strong> ".$localizador."</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Viaje: <strong>".$nombre_viaje."</strong></h1>

					<h1 style='font-size:20px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 15px;font-weight:normal;text-align:left;clear:both;display:block;'>Salida: <strong>".$datos_salida."</strong>&nbsp;&nbsp;&nbsp;Regreso: <strong>".$datos_regreso."</strong></h1>";

					$mensaje_html .= "<h1 style='font-size:17px;font-family:arial,serif;line-height:120%;color:#000000;margin:15px 15px 10px;font-weight:normal;text-align:left;clear:both;display:block;'>Pasajeros</h1>

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
					
					$mensaje_html .= "<h1 style='font-size:1.5em;line-height:120%;color:#6f7073;margin:40px 20px 10px;font-weight:normal;text-align:left;clear:both;	display:block;'><strong>Gracias por confiar en Hi Travel.</strong></h1>";					
					
					$mensaje_html .= "</body></html>";
			
				
				////FIN MENSAJE CONTENIDO///////////////////////////////////////////////////////////////////////
				////////////////////////////////////////////////////////////////////////////////////////////////				
				
				
				//aqui buscamos el mail de la agencia obteniendolo de la reserva. comprobamos si se factura a la central o a la oficina e intentamos pasarlo al mail de contabilidad. Sino al normal.
				$datos_agencia =$conexion->query("select o.MAIL_CONTABILIDAD mail_oficina_contabilidad, 
														o.MAIL mail_oficina,
														 m.MAIL_CONTABILIDAD mail_central_contabilidad, 
														 m.MAIL mail_central, 
														 facturacion 
												from hit_oficinas o, hit_minoristas m where o.ID = m.ID and o.ID = '".$reserva_minorista."' and o.OFICINA = '".$reserva_oficina."'");
				$datos_agenc = $datos_agencia->fetch_assoc();
				$facturacion_modo = $datos_agenc['facturacion'];
				if($facturacion_modo == 'C'){
					if($datos_agenc['mail_central_contabilidad'] != ''){
						$direccion_correo = $datos_agenc['mail_central_contabilidad'];
					}else{
						$direccion_correo = $datos_agenc['mail_central'];						
					}
				}elseif($facturacion_modo == 'O'){
					if($datos_agenc['mail_oficina_contabilidad'] != ''){
						$direccion_correo = $datos_agenc['mail_oficina_contabilidad'];
					}else{
						$direccion_correo = $datos_agenc['mail_oficina'];	
					}
				}
				
				$nombre_destinatario = "Agencia de Viajes";
				$asunto = "HI TRAVEL - FACTURACION DISPONIBLE: ".$localizador;
				$envio = enviar_mail_facturas($asunto, $mensaje_html, $direccion_correo, $nombre_destinatario);
				//echo($envio);
				//---------------------------------------
				//---------------------------------------

				
			}
		}else{
			$respuesta = 'OK';
		}

		return $respuesta;											
	}

	
	function Facturar_grupo(){

		$conexion = $this ->Conexion;

		//Consulta
		$Filadesde = $this ->Filadesde;
		$Usuario = $this ->Usuario;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		$buscar_salida_desde = $this->Buscar_salida_desde;
		$buscar_salida_hasta = $this->Buscar_salida_hasta;
		$buscar_grupo = $this->Buscar_grupo;
		$buscar_estado = $this->Buscar_estado;
		$buscar_localizador = $this->Buscar_localizador;
		$buscar_factura = $this->Buscar_factura;

		//ESPECIFICO: Cargamos las lineas solicitadas para esta pantalla
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_factura != null){
			$CADENA_BUSCAR = " and r.factura = '".$buscar_factura."'";		
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$grup = " AND m.grupo_gestion = ".$buscar_grupo;	
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_estado != null){
				if($buscar_estado == 'P'){
					$estad = " AND r.factura = 0";
				}else{
					$estad = " AND r.factura > 0";				
				}
				$CADENA_BUSCAR .= $estad;	
			}
			if($buscar_grupo != null){
				$CADENA_BUSCAR .= $grup;	
			}			
			
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	
		
		$resultado =$conexion->query("select r.localizador localizador, r.factura factura
										from hit_reservas r,
											  hit_minoristas m,
											  hit_oficinas o
										where 
												r.MINORISTA = m.ID
												and r.MINORISTA = o.ID
												and r.OFICINA = o.OFICINA
												and r.pvp_total > 0 ".$CADENA_BUSCAR." order by r.FECHA_SALIDA,m.NOMBRE,r.localizador");
		//echo($CADENA_BUSCAR);
		/*if ($resultado == FALSE){
			echo('Error en la consulta');
			$resultado->close();
			$conexion->close();
			exit;
		}*/
		//----------------------------------------------------------------

		//Recorremos el resultado facturando cada localizador
				                        //------

		$facturas = 0;
		
		for ($i = 0; $i < $resultado->num_rows; $i++) {
			$resultado->data_seek($i);
			$fila = $resultado->fetch_assoc();
			if($fila['factura'] == 0){
				$this->Facturar_reserva($fila['localizador']);
				$facturas++;
			}
			//Esto es para dejar de cargar lineas en caso de que sea la ultima pagina de la consulta
			/*if($fila['localizador'] == ''){
				break;
			}*/
			//array_push($facturacion,$fila);
		}

		//Liberar Memoria usada por la consulta
		$resultado->close();
		
		return $facturas;
											
	}
	
	
	


	//Metodo Constructor (Debe llamarse igual que la clase. Sirve para indicar parametros
	//o argumentos que se indicarán a la hora de crear la clase en el código.)
	function clsFacturacion($conexion, $filadesde, $usuario, $buscar_salida_desde, $buscar_salida_hasta, $buscar_grupo, $buscar_estado, $buscar_localizador, $buscar_factura){
		$this->Conexion = $conexion;
		$this->Filadesde = $filadesde;
		$this->Usuario = $usuario;
		$this->Buscar_salida_desde = $buscar_salida_desde;
		$this->Buscar_salida_hasta = $buscar_salida_hasta;
		$this->Buscar_grupo = $buscar_grupo;
		$this->Buscar_estado = $buscar_estado;
		$this->Buscar_localizador = $buscar_localizador;
		$this->Buscar_factura = $buscar_factura;
	}
}

?>