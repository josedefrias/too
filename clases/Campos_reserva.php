
	CAMPOS TABLA
	localizador,
	referencia,
	situacion,
	estado,
	estado_usuario,
	folleto,
	cuadro,
	ciudad_salida
	paquete,
	fecha_salida,
	fecha_regreso,
	fecha_reserva,
	usuario_reserva,
	fecha_modificacion,
	usuario_modificacion,
	nombre_titular,
	tipo_pax,
	pax,
	adultos,
	ninos,
	bebes,
	novios,
	jubilados,
	observaciones,
	minorista,
	oficina,
	agente,
	referencia_agencia,
	envio,
	prepago,
	operador_prepago,
	fecha_pago,
	divisa_base,
	divisa_ctual,
	cambio,
	redondeo,
	pvp_calculado_comisionable,
	pvp_calculado_no_comisionable,
	pvp_calculado_comision,
	pvp_calculado_importe_comision,
	pvp_calculado_total,
	pvp_comisionable,
	pvp_no_comisionable,
	pvp_comision,
	pvp_importe_comision,
	pvp_total,
	modificacion_motivo,
	modificacion_responsable,
	factura,
	factura_fecha_emision,
	factura_fecha_salida,
	factura_fecha_reserva,
	factura_pax,
	factura_primera_emision,
	factura_ultima_emision,
	factura_ituacion,
	factura_comisisonable,
	factura_n_comisionable,
	factura_comision,
	factura_importe_comision,
	factura_impuesto_comision,
	factura_a_deducir,
	factura_total_pagar,
	free,
	anulacion_fecha,
	anulacion_usuario,
	tipos_descuento,
	modificar,
	modificar_comisionable,
	modificar_detalle_comisionable,
	modificar_no_comisionable,
	modificar_detalle_no_comisionable,
	modificar_comision,
	modificar_usuario,
	visa,
	alternativa_aerea,
	password,

	VARIABLES
	$localizador,
	$referencia,
	$situacion,
	$estado,
	$estado_usuario,
	$folleto,
	$cuadro,
	$ciudad_salida,
	$paquete,
	$fecha_salida,
	$fecha_regreso,
	$fecha_reserva,
	$usuario_reserva,
	$fecha_modificacion,
	$usuario_modificacion,
	$nombre_titular,
	$tipo_pax,
	$pax,
	$adultos,
	$ninos,
	$bebes,
	$novios,
	$jubilados,
	$observaciones,
	$minorista,
	$oficina,
	$agente,
	$referencia_agencia,
	$envio,
	$prepago,
	$operador_prepago,
	$fecha_pago,
	$divisa_base,
	$divisa_ctual,
	$cambio,
	$redondeo,
	$pvp_calculado_comisionable,
	$pvp_calculado_no_comisionable,
	$pvp_calculado_comision,
	$pvp_calculado_importe_comision,
	$pvp_calculado_total,
	$pvp_comisionable,
	$pvp_no_comisionable,
	$pvp_comision,
	$pvp_importe_comision,
	$pvp_total,
	$modificacion_motivo,
	$modificacion_responsable,
	$factura,
	$factura_fecha_emision,
	$factura_fecha_salida,
	$factura_fecha_reserva,
	$factura_pax,
	$factura_primera_emision,
	$factura_ultima_emision,
	$factura_ituacion,
	$factura_comisisonable,
	$factura_n_comisionable,
	$factura_comision,
	$factura_importe_comision,
	$factura_impuesto_comision,
	$factura_a_deducir,
	$factura_total_pagar,
	$free,
	$anulacion_fecha,
	$anulacion_usuario,
	$tipos_descuento,
	$modificar,
	$modificar_comisionable,
	$modificar_detalle_comisionable,
	$modificar_no_comisionable,
	$modificar_detalle_no_comisionable,
	$modificar_comision,
	$modificar_usuario,
	$visa,
	$alternativa_aerea,
	$password,


	CAMPOS A TECLEAR
	"$referencia" => $recuperareferencia ,
	"$folleto" => $recuperafolleto ,
	"$cuadro" => $recuperacuadro ,
	"$ciudad_salida" => $recuperaciudad_salida ,
	"$paquete" => $recuperapaquete ,
	"$fecha_salida" => $recuperafecha_salida ,
	"$nombre_titular" => $recuperanombre_titular ,
	"$adultos" => $recuperaadultos ,
	"$ninos" => $recuperaninos ,
	"$bebes" => $recuperabebes ,
	"$novios" => $recuperanovios ,
	"$jubilados" => $recuperajubilados ,
	"$observaciones" => $recuperaobservaciones ,
	"$minorista" => $recuperaminorista ,
	"$oficina" => $recuperaoficina ,
	"$agente" => $recuperaagente ,
	"$referencia_agencia" => $recuperareferencia_agencia ,
	"$envio" => $recuperaenvio ,
	"$divisa_ctual" => $recuperadivisa_ctual ,
	"$modificacion_motivo" => $recuperamodificacion_motivo ,
	"$free" => $recuperafree ,
	"$modificar" => $recuperamodificar ,
	"$modificar_comisionable" => $recuperamodificar_comisionable ,
	"$modificar_detalle_comisionable" => $recuperamodificar_detalle_comisionable ,
	"$modificar_no_comisionable" => $recuperamodificar_no_comisionable ,
	"$modificar_detalle_no_comisionable" => $recuperamodificar_detalle_no_comisionable ,
	"$modificar_comision" => $recuperamodificar_comision ,
	"$visa" => $recuperavisa ,
	"$alternativa_aerea" => $recuperaalternativa_aerea





--SELECCION
select 
	localizador,referencia,situacion,estado,estado_usuario,folleto,cuadro,ciudad_salida,paquete,fecha_salida,fecha_regreso,fecha_reserva,
	usuario_reserva,fecha_modificacion,usuario_modificacion,nombre_titular,tipo_pax,pax,adultos,ninos,bebes,novios,jubilados,observaciones,
	minorista,oficina,agente,referencia_agencia,envio,prepago,operador_prepago,fecha_pago,divisa_base,divisa_actual,cambio,redondeo,
	pvp_calculado_comisionable,pvp_calculado_no_comisionable,pvp_calculado_comision,pvp_calculado_importe_comision,pvp_calculado_total,
	pvp_comisionable,pvp_no_comisionable,pvp_comision,pvp_importe_comision,pvp_total,modificacion_motivo,modificacion_responsable,
	factura,factura_fecha_emision,factura_fecha_salida,factura_fecha_reserva,factura_pax,factura_primera_emision,factura_ultima_emision,
	factura_situacion,factura_comisionable,factura_no_comisionable,factura_comision,factura_importe_comision,factura_impuesto_comision,
	factura_a_deducir,factura_total_pagar,free,anulacion_fecha,anulacion_usuario,tipos_descuento,modificar,modificar_comisionable,
	modificar_detalle_comisionable,modificar_no_comisionable,modificar_detalle_no_comisionable,modificar_comision,modificar_usuario,
	visa,	alternativa_aerea,password
from hit_reservas

--SELECCION CON JOINS A RESTO TABLAS
select 
	r.localizador,r.referencia,r.situacion,r.estado,r.estado_usuario,r.folleto,r.cuadro,r.ciudad_salida,r.paquete,r.fecha_salida,r.fecha_regreso,r.fecha_reserva,
	r.usuario_reserva,r.fecha_modificacion,r.usuario_modificacion,r.nombre_titular,r.tipo_pax,r.pax,r.adultos,r.ninos,r.bebes,r.novios,r.jubilados,r.observaciones,
	r.minorista,r.oficina,r.agente,r.referencia_agencia,r.envio,r.prepago,r.operador_prepago,r.fecha_pago,r.divisa_base,r.divisa_actual,r.cambio,redondeo,
	r.pvp_calculado_comisionable,r.pvp_calculado_no_comisionable,r.pvp_calculado_comision,r.pvp_calculado_importe_comision,r.pvp_calculado_total,
	r.pvp_comisionable,r.pvp_no_comisionable,r.pvp_comision,r.pvp_importe_comision,r.pvp_total,r.modificacion_motivo,r.modificacion_responsable,
	r.factura,r.factura_fecha_emision,r.factura_fecha_salida,r.factura_fecha_reserva,r.factura_pax,r.factura_primera_emision,r.factura_ultima_emision,
	r.factura_situacion,r.factura_comisionable,r.factura_no_comisionable,r.factura_comision,r.factura_importe_comision,r.factura_impuesto_comision,
	r.factura_a_deducir,r.factura_total_pagar,r.free,r.anulacion_fecha,r.anulacion_usuario,r.tipos_descuento,r.modificar,r.modificar_comisionable,
	r.modificar_detalle_comisionable,r.modificar_no_comisionable,r.modificar_detalle_no_comisionable,r.modificar_comision,r.modificar_usuario,
	r.visa,r.alternativa_aerea,r.password, m.NOMBRE, o.OFICINA, o.LOCALIDAD, o.DIRECCION, o.TELEFONO, pf.nombre, pc.nombre, pp.nombre
from hit_reservas r, hit_minoristas m, hit_oficinas o, hit_producto_folletos pf, hit_producto_cuadros pc, hit_producto_paquetes pp
where
	r.MINORISTA = m.ID	and r.MINORISTA = o.ID and r.OFICINA = o.OFICINA and r.FOLLETO = pf.folleto and r.FOLLETO = pc.folleto	and r.CUADRO = pc.cuadro
	and r.FOLLETO = pp.folleto	and r.CUADRO = pp.cuadro and r.PAQUETE = pp.paquete



CAMPOS A MOSTRAR

r.localizador,--------------
r.referencia,-----------------
r.situacion,
r.estado,
r.estado_usuario,
r.folleto,----------------------
r.cuadro,-----------------------
r.ciudad_salida,----------------
r.paquete,----------------------
r.fecha_salida,
r.fecha_regreso,
r.fecha_reserva,
r.usuario_reserva,
r.fecha_modificacion,
r.usuario_modificacion,
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
r.referencia_agencia,-------------
r.envio,
r.prepago,
r.operador_prepago,
r.fecha_pago,
r.divisa_base,
r.divisa_actual,
r.cambio,
redondeo,
r.pvp_calculado_comisionable,
r.pvp_calculado_no_comisionable,
r.pvp_calculado_comision,
r.pvp_calculado_importe_comision,
r.pvp_calculado_total,
r.pvp_comisionable,
r.pvp_no_comisionable,
r.pvp_comision,
r.pvp_importe_comision,
r.pvp_total,
r.modificacion_motivo,
r.modificacion_responsable,
r.factura,
r.factura_fecha_emision,
r.factura_fecha_salida,
r.factura_fecha_reserva,
r.factura_pax,
r.factura_primera_emision,
r.factura_ultima_emision,
r.factura_situacion,
r.factura_comisionable,
r.factura_no_comisionable,
r.factura_comision,
r.factura_importe_comision,
r.factura_impuesto_comision,
r.factura_a_deducir,
r.factura_total_pagar,
r.free,r.anulacion_fecha,
r.anulacion_usuario,
r.tipos_descuento,
r.modificar,
r.modificar_comisionable,
r.modificar_detalle_comisionable,
r.modificar_no_comisionable,
r.modificar_detalle_no_comisionable,
r.modificar_comision,
r.modificar_usuario,
r.visa,
r.alternativa_aerea,
r.password, 
m.NOMBRE, 
o.OFICINA, 
o.LOCALIDAD, 
o.DIRECCION, 
o.TELEFONO, 
pf.nombre, 
pc.nombre, 
pp.nombre