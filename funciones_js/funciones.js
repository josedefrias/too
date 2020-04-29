 function Posicion(elemento){
    document.getElementById(elemento).focus();
 }

function CancelSubmit(e){
	var keynum
	if(window.event) // IE
	{
		keynum = e.keyCode
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		keynum = e.which
	}        
	return (keynum != 13);
}

function validar_fecha(Cadena){  
	//alert(Cadena);
    var Fecha= new String(Cadena)   // Crea un string  
    var RealFecha= new Date()   // Para sacar la fecha de hoy  
    // Cadena Año  
    var Ano= new String(Fecha.substring(Fecha.lastIndexOf("-")+1,Fecha.length))  
    // Cadena Mes  
    var Mes= new String(Fecha.substring(Fecha.indexOf("-")+1,Fecha.lastIndexOf("-")))  
    // Cadena Día  
    var Dia= new String(Fecha.substring(0,Fecha.indexOf("-")))  
  
    // Compruebo que no este en blanco
    if (Fecha == ''){  
        alert('La fecha no puede estar en blanco')  
        return false  
    } 

    // Valido el año
    if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){  
            alert('Año inválido')  
        return false  
    } 

    // Valido el Mes  
    if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){  
        alert('Mes inválido')  
        return false  
    }  
    // Valido el Dia  
    if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){  
        alert('Día inválido')  
        return false  
    }  
    if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {  
        if (Mes==2 && Dia > 28 || Dia>30) {  
            alert('Día inválido')  
            return false  
        }  
    }  
     
	function Obtiene_fecha_ayer() {
        fecha.setTime(fecha.getTime() - 24*60*60*1000);
		    
		auxFecha = fecha.getDate() + "-" + (fecha.getMonth()+1) + "-" + fecha.getFullYear(); //fecha.getYear();
		fecha.setTime(fecha.getTime() + 7*24*60*60*1000);
		auxFecha2 = fecha.getDate() + "-" + (fecha.getMonth()+1) + "-" + fecha.getFullYear(); //fecha.getYear();
		    
		//document.getElementById("FECHAI").value=auxFecha;
		//document.getElementById("FECHAF").value=auxFecha2;
    }

  //para que envie los datos, quitar las  2 lineas siguientes  
  //alert("Fecha correcta.")  
  //return false    
} 

function mostrarOcultar(id){
	mostrado=0;
	elem = document.getElementById(id);
	if(elem.style.display=='block'){
		mostrado=1;
	}
	elem.style.display='none';
	if(mostrado!=1){
		elem.style.display='block';
	}
}

function Visualizacion_Secciones_Cupos_Alojamientos()
{
	if(seccion_actualizar_cupos.style.display=='block'){
		document.forms['mantenimiento_tabla'].seccion_actualizar_cupos_display.value = 'block';
	}else{
		document.forms['mantenimiento_tabla'].seccion_actualizar_cupos_display.value = 'none';
	}
}

function Visualizacion_Secciones_Reservas()
{
	if(seccion_observaciones.style.display=='block'){
		document.forms['mantenimiento_tabla'].seccion_observaciones_display.value = 'block';
	}else{
		document.forms['mantenimiento_tabla'].seccion_observaciones_display.value = 'none';
	}

	if(seccion_buscar_agencia.style.display=='block'){
		document.forms['mantenimiento_tabla'].seccion_buscar_agencia_display.value = 'block';
	}else{
		document.forms['mantenimiento_tabla'].seccion_buscar_agencia_display.value = 'none';
	}

	if(seccion_anadir_aereos.style.display=='block'){
		document.forms['mantenimiento_tabla'].seccion_anadir_aereos_display.value = 'block';
	}else{
		document.forms['mantenimiento_tabla'].seccion_anadir_aereos_display.value = 'none';
	}

	if(seccion_modificar_aereos_pasajeros.style.display=='block'){

		document.forms['mantenimiento_tabla'].seccion_modificar_aereos_pasajeros_display.value = 'block';
	}else{
		document.forms['mantenimiento_tabla'].seccion_modificar_aereos_pasajeros_display.value = 'none';
	}

	if(seccion_anadir_trayectos_manual.style.display=='block'){
		document.forms['mantenimiento_tabla'].seccion_anadir_trayectos_manual_display.value = 'block';
	}else{
		document.forms['mantenimiento_tabla'].seccion_anadir_trayectos_manual_display.value = 'none';
	}

	if(seccion_anadir_trayectos_cupos.style.display=='block'){
		document.forms['mantenimiento_tabla'].seccion_anadir_trayectos_cupos_display.value = 'block';
	}else{
		document.forms['mantenimiento_tabla'].seccion_anadir_trayectos_cupos_display.value = 'none';
	}

	if(seccion_anadir_alojamiento.style.display=='block'){
		document.forms['mantenimiento_tabla'].seccion_anadir_alojamiento_display.value = 'block';
	}else{
		document.forms['mantenimiento_tabla'].seccion_anadir_alojamiento_display.value = 'none';
	}

	if(seccion_anadir_servicio.style.display=='block'){
		document.forms['mantenimiento_tabla'].seccion_anadir_servicio_display.value = 'block';
	}else{
		document.forms['mantenimiento_tabla'].seccion_anadir_servicio_display.value = 'none';
	}
}

function Visualizacion_Cabecera_Cuadros()
{
	if(seccion_cabecera_cuadro.style.display=='block'){
		document.forms['mantenimiento_tabla'].seccion_cabecera_cuadro_display.value = 'block';
	}else{
		document.forms['mantenimiento_tabla'].seccion_cabecera_cuadro_display.value = 'none';
	}
}

function Visualizacion_Cabecera_Teletipos()
{
	if(seccion_cabecera_teletipo.style.display=='block'){
		document.forms['mantenimiento_tabla'].seccion_cabecera_teletipo_display.value = 'block';
	}else{
		document.forms['mantenimiento_tabla'].seccion_cabecera_teletipo_display.value = 'none';
	}
}

function seleccionar(i)
{
	//document.forms['mantenimiento_tabla'].seleccion[i].checked = true;

	document.forms['mantenimiento_tabla']['seleccion'+i].checked = true;
	document.forms['mantenimiento_tabla']['selec'+i].value = 'S';
	document.forms['mantenimiento_tabla']['borrar'+i].checked = false;
}	

function seleccionar2(i)
{
	if(document.forms['mantenimiento_tabla']['seleccion'+i].checked == false){
		document.forms['mantenimiento_tabla']['seleccion'+i].checked = false;
		document.forms['mantenimiento_tabla']['selec'+i].value = 'N';
	}else{
		document.forms['mantenimiento_tabla']['seleccion'+i].checked = true;
		document.forms['mantenimiento_tabla']['selec'+i].value = 'S';
	}
}	

function seleccionar_2nivel(i)
{
	//document.forms['mantenimiento_tabla'].seleccion[i].checked = true;
	document.forms['mantenimiento_tabla']['seleccion_2nivel'+i].checked = true;
	document.forms['mantenimiento_tabla']['selec_2nivel'+i].value = 'S';
	document.forms['mantenimiento_tabla']['borrar_2nivel'+i].checked = false;
}

function seleccionar_3nivel(i)
{
	//document.forms['mantenimiento_tabla'].seleccion[i].checked = true;
	document.forms['mantenimiento_tabla']['seleccion_3nivel'+i].checked = true;
	document.forms['mantenimiento_tabla']['selec_3nivel'+i].value = 'S';
	document.forms['mantenimiento_tabla']['borrar_3nivel'+i].checked = false;
}

function seleccionar_31nivel(i)
{
	document.forms['mantenimiento_tabla']['seleccion_31nivel'+i].checked = true;
	document.forms['mantenimiento_tabla']['selec_31nivel'+i].value = 'S';
	document.forms['mantenimiento_tabla']['borrar_31nivel'+i].checked = false;
}

function seleccionar_32nivel(i)
{
	document.forms['mantenimiento_tabla']['seleccion_32nivel'+i].checked = true;
	document.forms['mantenimiento_tabla']['selec_32nivel'+i].value = 'S';
	document.forms['mantenimiento_tabla']['borrar_32nivel'+i].checked = false;
}

function seleccionar_4nivel(i)
{
	//document.forms['mantenimiento_tabla'].seleccion[i].checked = true;
	document.forms['mantenimiento_tabla']['seleccion_4nivel'+i].checked = true;
	document.forms['mantenimiento_tabla']['selec_4nivel'+i].value = 'S';
	document.forms['mantenimiento_tabla']['borrar_4nivel'+i].checked = false;
}

function seleccionar_4nivel_alojamiento_coste_pax(i)
{
	//document.forms['mantenimiento_tabla'].seleccion[i].checked = true;
	document.forms['mantenimiento_tabla']['seleccion_4nivel'+i].checked = true;
	document.forms['mantenimiento_tabla']['selec_4nivel'+i].value = 'S';
	document.forms['mantenimiento_tabla']['borrar_4nivel'+i].checked = false;

	document.forms['mantenimiento_tabla']['coste_habitacion'+i].value = document.forms['mantenimiento_tabla']['coste_pax'+i].value * document.forms['mantenimiento_tabla']['uso'+i].value;
	if(document.forms['mantenimiento_tabla']['calculo'+i].value == 'A'){
		document.forms['mantenimiento_tabla']['neto_pax'+i].value = Number(document.forms['mantenimiento_tabla']['coste_pax'+i].value / (1 - (document.forms['mantenimiento_tabla']['porcentaje_neto'+i].value/100))).toFixed(2);
		document.forms['mantenimiento_tabla']['neto_habitacion'+i].value = Number(document.forms['mantenimiento_tabla']['coste_habitacion'+i].value / (1 - (document.forms['mantenimiento_tabla']['porcentaje_neto'+i].value/100))).toFixed(2);
		document.forms['mantenimiento_tabla']['pvp_pax'+i].value = Number(Number(document.forms['mantenimiento_tabla']['coste_pax'+i].value / (1 - (document.forms['mantenimiento_tabla']['porcentaje_neto'+i].value/100))).toFixed(2) / (1 - (document.forms['mantenimiento_tabla']['porcentaje_com'+i].value/100))).toFixed(2);
		document.forms['mantenimiento_tabla']['pvp_habitacion'+i].value = Number(Number(document.forms['mantenimiento_tabla']['coste_habitacion'+i].value / (1 - (document.forms['mantenimiento_tabla']['porcentaje_neto'+i].value/100))).toFixed(2) / (1 - (document.forms['mantenimiento_tabla']['porcentaje_com'+i].value/100))).toFixed(2);
	}
}

function seleccionar_5nivel(i)
{
	//document.forms['mantenimiento_tabla'].seleccion[i].checked = true;
	document.forms['mantenimiento_tabla']['seleccion_5nivel'+i].checked = true;
	document.forms['mantenimiento_tabla']['selec_5nivel'+i].value = 'S';
	document.forms['mantenimiento_tabla']['borrar_5nivel'+i].checked = false;
}

function seleccionar_6nivel(i)
{
	//document.forms['mantenimiento_tabla'].seleccion[i].checked = true;
	document.forms['mantenimiento_tabla']['seleccion_6nivel'+i].checked = true;
	document.forms['mantenimiento_tabla']['selec_6nivel'+i].value = 'S';
	document.forms['mantenimiento_tabla']['borrar_6nivel'+i].checked = false;
}

function desmarcarSeleccion(i)
{
	//document.forms['mantenimiento_tabla'].seleccion[i].checked = false;
	document.forms['mantenimiento_tabla']['seleccion'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra'+i].value = 'N';
	}
	if(document.forms['mantenimiento_tabla']['seleccion'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec'+i].value = 'N';
	}
}

function desmarcarSeleccion_2nivel(i)
{
	document.forms['mantenimiento_tabla']['seleccion_2nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_2nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_2nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_2nivel'+i].value = 'N';
	}
	if(document.forms['mantenimiento_tabla']['seleccion_2nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_2nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_2nivel'+i].value = 'N';
	}
}

function desmarcarSeleccion_3nivel(i)
{
	document.forms['mantenimiento_tabla']['seleccion_3nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_3nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_3nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_3nivel'+i].value = 'N';
	}
	if(document.forms['mantenimiento_tabla']['seleccion_3nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_3nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_3nivel'+i].value = 'N';
	}
}


function desmarcarSeleccion_31nivel(i)
{
	document.forms['mantenimiento_tabla']['seleccion_31nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_31nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_31nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_31nivel'+i].value = 'N';
	}
	if(document.forms['mantenimiento_tabla']['seleccion_31nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_31nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_31nivel'+i].value = 'N';
	}
}

function desmarcarSeleccion_32nivel(i)
{
	document.forms['mantenimiento_tabla']['seleccion_32nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_32nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_32nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_32nivel'+i].value = 'N';
	}
	if(document.forms['mantenimiento_tabla']['seleccion_32nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_32nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_32nivel'+i].value = 'N';
	}
}

function desmarcarSeleccion_4nivel(i)
{
	document.forms['mantenimiento_tabla']['seleccion_4nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_4nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_4nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_4nivel'+i].value = 'N';
	}
	if(document.forms['mantenimiento_tabla']['seleccion_4nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_4nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_4nivel'+i].value = 'N';
	}
}

function desmarcarSeleccion_5nivel(i)
{
	document.forms['mantenimiento_tabla']['seleccion_5nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_5nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_5nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_5nivel'+i].value = 'N';
	}
	if(document.forms['mantenimiento_tabla']['seleccion_5nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_5nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_5nivel'+i].value = 'N';
	}
}

function desmarcarSeleccion_6nivel(i)
{
	document.forms['mantenimiento_tabla']['seleccion_6nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_6nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_6nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_6nivel'+i].value = 'N';
	}
	if(document.forms['mantenimiento_tabla']['seleccion_6nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_6nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_6nivel'+i].value = 'N';
	}
}

function desmarcarSeleccion_solicitud_vacio(i)
{
	if(document.forms['mantenimiento_tabla']['sol_estado_solicitud'+i].value == 'PE'){
		//document.forms['mantenimiento_tabla'].seleccion[i].checked = false;
		document.forms['mantenimiento_tabla']['seleccion'+i].checked = false;
		if(document.forms['mantenimiento_tabla']['borrar'+i].checked == true){
			document.forms['mantenimiento_tabla']['borra'+i].value = 'S';
		}else{
			document.forms['mantenimiento_tabla']['borra'+i].value = 'N';
		}
		if(document.forms['mantenimiento_tabla']['seleccion'+i].checked == true){
			document.forms['mantenimiento_tabla']['selec'+i].value = 'S';
		}else{
			document.forms['mantenimiento_tabla']['selec'+i].value = 'N';
		}
	}else{
		alert("Sólo se pueden borrar solicitudes  que esten en estado Pendiente");
	}
}

function desmarcarBorrar(i)
{
	//document.forms['mantenimiento_tabla'].borrar[i].checked = false;
	document.forms['mantenimiento_tabla']['borrar'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra'+i].value = 'N';
	}

	if(document.forms['mantenimiento_tabla']['seleccion'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec'+i].value = 'N';
	}
}

function desmarcarBorrar_2nivel(i)
{
	//document.forms['mantenimiento_tabla'].borrar[i].checked = false;
	document.forms['mantenimiento_tabla']['borrar_2nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_2nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_2nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_2nivel'+i].value = 'N';
	}

	if(document.forms['mantenimiento_tabla']['seleccion_2nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_2nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_2nivel'+i].value = 'N';
	}
}

function desmarcarBorrar_3nivel(i)
{
	//document.forms['mantenimiento_tabla'].borrar[i].checked = false;
	document.forms['mantenimiento_tabla']['borrar_3nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_3nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_3nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_3nivel'+i].value = 'N';
	}

	if(document.forms['mantenimiento_tabla']['seleccion_3nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_3nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_3nivel'+i].value = 'N';
	}
}

function desmarcarBorrar_31nivel(i)
{
	//document.forms['mantenimiento_tabla'].borrar[i].checked = false;
	document.forms['mantenimiento_tabla']['borrar_31nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_31nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_31nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_31nivel'+i].value = 'N';
	}

	if(document.forms['mantenimiento_tabla']['seleccion_31nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_31nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_31nivel'+i].value = 'N';
	}
}

function desmarcarBorrar_32nivel(i)
{
	//document.forms['mantenimiento_tabla'].borrar[i].checked = false;
	document.forms['mantenimiento_tabla']['borrar_32nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_32nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_32nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_32nivel'+i].value = 'N';
	}

	if(document.forms['mantenimiento_tabla']['seleccion_32nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_32nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_32nivel'+i].value = 'N';
	}
}

function desmarcarBorrar_4nivel(i)
{
	//document.forms['mantenimiento_tabla'].borrar[i].checked = false;
	document.forms['mantenimiento_tabla']['borrar_4nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_4nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_4nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_4nivel'+i].value = 'N';
	}

	if(document.forms['mantenimiento_tabla']['seleccion_4nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_4nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_4nivel'+i].value = 'N';
	}
}

function desmarcarBorrar_5nivel(i)
{
	//document.forms['mantenimiento_tabla'].borrar[i].checked = false;
	document.forms['mantenimiento_tabla']['borrar_5nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_5nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_5nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_5nivel'+i].value = 'N';
	}

	if(document.forms['mantenimiento_tabla']['seleccion_5nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_5nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_5nivel'+i].value = 'N';
	}
}

function desmarcarBorrar_6nivel(i)
{
	//document.forms['mantenimiento_tabla'].borrar[i].checked = false;
	document.forms['mantenimiento_tabla']['borrar_6nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_6nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_6nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_6nivel'+i].value = 'N';
	}

	if(document.forms['mantenimiento_tabla']['seleccion_6nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_6nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_6nivel'+i].value = 'N';
	}
}

function desmarcarBorrar_Dias_Semana(i)
{

	if(document.forms['mantenimiento_tabla']['dia'+i].checked == true){
		document.forms['mantenimiento_tabla'][i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla'][i].value = 'N';
	}
}

function Seleccionar_Todos(i)
{
	if(document.forms['mantenimiento_tabla']['seleccion0'].checked == false){	
		for(var k=0; k<i; k++){
			document.forms['mantenimiento_tabla']['seleccion'+k].checked = true;
			document.forms['mantenimiento_tabla']['selec'+k].value = 'S';
		}
	}else{
		for(var k=0; k<i; k++){
			document.forms['mantenimiento_tabla']['seleccion'+k].checked = false;
			document.forms['mantenimiento_tabla']['selec'+k].value = 'N';
		}	
	}
}

function Borrar_Todos(i)
{
	if(document.forms['mantenimiento_tabla']['borrar0'].checked == false){
		for(var k=0; k<i; k++){
			document.forms['mantenimiento_tabla']['borrar'+k].checked = true;
			document.forms['mantenimiento_tabla']['borra'+k].value = 'S';
		}
	}else{
		for(var k=0; k<i; k++){
			document.forms['mantenimiento_tabla']['borrar'+k].checked = false;
			document.forms['mantenimiento_tabla']['borra'+k].value = 'N';
		}	
	}
}

function Borrar_Todos_2nivel(i)
{
	if(document.forms['mantenimiento_tabla']['borrar_2nivel0'].checked == false){
		for(var k=0; k<i; k++){
			document.forms['mantenimiento_tabla']['borrar_2nivel'+k].checked = true;
			document.forms['mantenimiento_tabla']['borra_2nivel'+k].value = 'S';
		}
	}else{
		for(var k=0; k<i; k++){
			document.forms['mantenimiento_tabla']['borrar_2nivel'+k].checked = false;
			document.forms['mantenimiento_tabla']['borra_2nivel'+k].value = 'N';
		}	
	}
}

function Borrar_Todos_3nivel(i)
{
	if(document.forms['mantenimiento_tabla']['borrar_3nivel0'].checked == false){
		for(var k=0; k<i; k++){
			document.forms['mantenimiento_tabla']['borrar_3nivel'+k].checked = true;
			document.forms['mantenimiento_tabla']['borra_3nivel'+k].value = 'S';
		}
	}else{
		for(var k=0; k<i; k++){
			document.forms['mantenimiento_tabla']['borrar_3nivel'+k].checked = false;
			document.forms['mantenimiento_tabla']['borra_3nivel'+k].value = 'N';
		}	
	}
}

function Borrar_Todos_4nivel(i)
{
	if(document.forms['mantenimiento_tabla']['borrar_4nivel0'].checked == false){
		for(var k=0; k<i; k++){
			document.forms['mantenimiento_tabla']['borrar_4nivel'+k].checked = true;
			document.forms['mantenimiento_tabla']['borra_4nivel'+k].value = 'S';
		}
	}else{
		for(var k=0; k<i; k++){
			document.forms['mantenimiento_tabla']['borrar_4nivel'+k].checked = false;
			document.forms['mantenimiento_tabla']['borra_4nivel'+k].value = 'N';
		}	
	}
}

function Seleccionar_Borrar_Dias_Semana(i)
{
	if(document.forms['mantenimiento_tabla']['dialunes'].checked == false){	

			document.forms['mantenimiento_tabla']['dialunes'].checked = true;
			document.forms['mantenimiento_tabla']['diamartes'].checked = true;
			document.forms['mantenimiento_tabla']['diamiercoles'].checked = true;
			document.forms['mantenimiento_tabla']['diajueves'].checked = true;
			document.forms['mantenimiento_tabla']['diaviernes'].checked = true;
			document.forms['mantenimiento_tabla']['diasabado'].checked = true;
			document.forms['mantenimiento_tabla']['diadomingo'].checked = true;

			document.forms['mantenimiento_tabla']['lunes'].value = 'S';
			document.forms['mantenimiento_tabla']['martes'].value = 'S';
			document.forms['mantenimiento_tabla']['miercoles'].value = 'S';
			document.forms['mantenimiento_tabla']['jueves'].value = 'S';
			document.forms['mantenimiento_tabla']['viernes'].value = 'S';
			document.forms['mantenimiento_tabla']['sabado'].value = 'S';
			document.forms['mantenimiento_tabla']['domingo'].value = 'S';

	}else{
			document.forms['mantenimiento_tabla']['dialunes'].checked = false;
			document.forms['mantenimiento_tabla']['diamartes'].checked = false;
			document.forms['mantenimiento_tabla']['diamiercoles'].checked = false;
			document.forms['mantenimiento_tabla']['diajueves'].checked = false;
			document.forms['mantenimiento_tabla']['diaviernes'].checked = false;
			document.forms['mantenimiento_tabla']['diasabado'].checked = false;
			document.forms['mantenimiento_tabla']['diadomingo'].checked = false;

			document.forms['mantenimiento_tabla']['lunes'].value = 'N';
			document.forms['mantenimiento_tabla']['martes'].value = 'N';
			document.forms['mantenimiento_tabla']['miercoles'].value = 'N';
			document.forms['mantenimiento_tabla']['jueves'].value = 'N';
			document.forms['mantenimiento_tabla']['viernes'].value = 'N';
			document.forms['mantenimiento_tabla']['sabado'].value = 'N';
			document.forms['mantenimiento_tabla']['domingo'].value = 'N';
	}
}

function Seleccion_factura(i)
{
	if(document.forms['mantenimiento_tabla']['seleccion'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec'+i].value = 'N';
	}
	//alert(document.forms['mantenimiento_tabla']['selec'+i].value);
}

function Seleccion_precobro(i)
{
	if(document.forms['mantenimiento_tabla']['seleccion'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec'+i].value = 'N';
	}
	//alert(document.forms['mantenimiento_tabla']['selec'+i].value);
}

function Seleccionar_Aereos(i)
{
	//document.forms['mantenimiento_tabla'].borrar[i].checked = false;
	document.forms['mantenimiento_tabla']['borrar_3nivel'+i].checked = false;
	if(document.forms['mantenimiento_tabla']['borrar_3nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['borra_3nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['borra_3nivel'+i].value = 'N';
	}

	if(document.forms['mantenimiento_tabla']['seleccion_3nivel'+i].checked == true){
		document.forms['mantenimiento_tabla']['selec_3nivel'+i].value = 'S';
	}else{
		document.forms['mantenimiento_tabla']['selec_3nivel'+i].value = 'N';
	}

	//Aqui resaltamos las lineas que tienen que ver con la reserva de aereos seleccionada
	linea = document.getElementById('linea_aereos'+i);
	if(document.forms['mantenimiento_tabla']['seleccion_3nivel'+i].checked == true){
		linea.style.backgroundColor = '#FFFFCC';

		//Aqui iluminamos los pasajeros y los trayectos que incluye la reserva de vuelos seleccionada
		//alert(document.forms['mantenimiento_tabla']['aereos_clave'+i].value);
		//alert(document.forms['mantenimiento_tabla']['cantidad_lineas_aereos_pasajeros'+i].value);
		for(var k=0; k<document.forms['mantenimiento_tabla']['cantidad_lineas_aereos_pasajeros'+i].value; k++){
			if(document.forms['mantenimiento_tabla']['aereos_clave'+i].value == document.forms['mantenimiento_tabla']['aereos_pasajeros_clave_aereo'+k].value){
				numero_pax = document.forms['mantenimiento_tabla']['aereos_pasajeros_numero'+k].value;
				//alert(numero_pax);
				linea_pasajeros = document.getElementById('lineas_pasajeros'+numero_pax);
				linea_pasajeros.style.backgroundColor = '#FFFFCC';
			}
		}
		//alert(document.forms['mantenimiento_tabla']['cantidad_lineas_aereos_trayectos'+i].value);
		for(var k=0; k<document.forms['mantenimiento_tabla']['cantidad_lineas_aereos_trayectos'+i].value; k++){
			//alert(document.forms['mantenimiento_tabla']['aereos_clave'+i].value+ ' - '+document.forms['mantenimiento_tabla']['aereos_trayectos_clave_aereo'+k].value);
			if(document.forms['mantenimiento_tabla']['aereos_clave'+i].value == document.forms['mantenimiento_tabla']['aereos_trayectos_clave_aereo'+k].value){
				//numero_trayecto = document.forms['mantenimiento_tabla']['aereos_trayectos_numero'+k].value;
				//alert(numero_pax);
				linea_pasajeros = document.getElementById('linea_aereos_trayectos'+k);
				linea_pasajeros.style.backgroundColor = '#FFFFCC';
			}
		}


	}else{
		linea.style.backgroundColor = '';

		//Aqui apagamos los pasajeros y los trayectos que incluye la reserva de vuelos seleccionada
		for(var k=0; k<document.forms['mantenimiento_tabla']['cantidad_lineas_aereos_pasajeros'+i].value; k++){
			if(document.forms['mantenimiento_tabla']['aereos_clave'+i].value == document.forms['mantenimiento_tabla']['aereos_pasajeros_clave_aereo'+k].value){
				numero_pax = document.forms['mantenimiento_tabla']['aereos_pasajeros_numero'+k].value;
				linea_pasajeros = document.getElementById('lineas_pasajeros'+numero_pax);
				linea_pasajeros.style.backgroundColor = '';
			}
		}

		for(var k=0; k<document.forms['mantenimiento_tabla']['cantidad_lineas_aereos_trayectos'+i].value; k++){
			if(document.forms['mantenimiento_tabla']['aereos_clave'+i].value == document.forms['mantenimiento_tabla']['aereos_trayectos_clave_aereo'+k].value){
				//numero_trayecto = document.forms['mantenimiento_tabla']['aereos_trayectos_numero'+k].value;
				//alert(numero_pax);
				linea_pasajeros = document.getElementById('linea_aereos_trayectos'+k);
				linea_pasajeros.style.backgroundColor = '';
			}
		}

	}



}

function salir(pagina)
{
	//alert(pagina);
	document.forms['mantenimiento_tabla'].ir_a.value = pagina;
	document.forms['mantenimiento_tabla'].submit();
}

function salir_cuadros(pagina)
{
	//alert(pagina);
	document.forms['mantenimiento_tabla'].ir_a.value = pagina;
	Visualizacion_Cabecera_Cuadros();
	//alert(document.forms['mantenimiento_tabla'].seccion_cabecera_cuadro_display.value);
	document.forms['mantenimiento_tabla'].submit();
}

function salir_teletipos(pagina)
{
	//alert(pagina);
	document.forms['mantenimiento_tabla'].ir_a.value = pagina;
	Visualizacion_Cabecera_Teletipos();
	//alert(document.forms['mantenimiento_tabla'].seccion_cabecera_teletipo_display.value);
	document.forms['mantenimiento_tabla'].submit();
}

function salir_confecha(pagina,fecha)
{
	//alert(pagina);
	document.forms['mantenimiento_tabla'].ir_a.value = pagina;
	document.forms['mantenimiento_tabla'].salir_confecha_fecha.value = fecha;
	document.forms['mantenimiento_tabla'].submit();
}

function salir_devacios_asolicitudes(pagina,id_vacio)
{
	//alert(pagina);
	document.forms['mantenimiento_tabla'].ir_a.value = pagina;
	document.forms['mantenimiento_tabla'].buscar_id.value = id_vacio;
	document.forms['mantenimiento_tabla'].submit();
}

function salir_desolicitudes_avacios(pagina,id_vacio)
{
	//alert(pagina);
	document.forms['mantenimiento_tabla'].ir_a.value = pagina;
	document.forms['mantenimiento_tabla'].buscar_id.value = id_vacio;
	document.forms['mantenimiento_tabla'].submit();
}

function grabar()
{
	document.forms['mantenimiento_tabla'].actuar.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function expande_cupos_2nivel(i)
{
	if(confirm("Se expandirán todos las fechas de cupo que no lo estén ya.")){
		document.forms['mantenimiento_tabla']['seleccion_2nivel'+i].checked = true;
		document.forms['mantenimiento_tabla']['selec_2nivel'+i].value = 'S';
		document.forms['mantenimiento_tabla']['borrar_2nivel'+i].checked = false;
		document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'E';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function borra_cupos_2nivel(i)
{
	if(confirm("Se borrarán todas las fechas de cupo que no tengan reservas realizadas.")){
		document.forms['mantenimiento_tabla']['seleccion_2nivel'+i].checked = true;
		document.forms['mantenimiento_tabla']['selec_2nivel'+i].value = 'S';
		document.forms['mantenimiento_tabla']['borrar_2nivel'+i].checked = false;
		document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'B';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function grabar_grupos()
{
	document.forms['mantenimiento_tabla'].actuar.value = 'S';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_buscar';	
	document.forms['mantenimiento_tabla'].submit();
}

function actualizar()
{
	document.forms['mantenimiento_tabla'].actuar.value = 'A';
	document.forms['mantenimiento_tabla'].submit();
}

function Bloquear_Desbloquear()
{
	if(document.forms['mantenimiento_tabla'].localizador0.value != ''){
		document.forms['mantenimiento_tabla'].actuar.value = 'D';
		document.forms['mantenimiento_tabla'].submit();
	}else{
		alert('Debe seleccionar antes un localizador');
	}
}

function Actualizar_Precios()
{
	if(document.forms['mantenimiento_tabla'].localizador0.value != ''){
		document.forms['mantenimiento_tabla'].actuar.value = 'A';
		document.forms['mantenimiento_tabla'].submit();
	}else{
		alert('Debe seleccionar antes un localizador');
	}
}

function Enviar_Mail_Agencia()
{
	if(confirm("Se enviará un mail de confirmación de servicios a la agencia y al usuario de la sesión actual. Confirme por favor.")) {
		if(document.forms['mantenimiento_tabla'].localizador0.value != ''){
			document.forms['mantenimiento_tabla'].actuar.value = 'E';
			document.forms['mantenimiento_tabla'].submit();
		}else{
			alert('Debe seleccionar antes un localizador');
		}
	}
}

function Enviar_Mail_Hotel()
{
	if(confirm("Se enviará un mail de Peticion de Hotel al hotel y al usuario de la sesión actual. Confirme por favor.")) {
		if(document.forms['mantenimiento_tabla'].localizador0.value != ''){
			document.forms['mantenimiento_tabla'].actuar.value = 'H';
			document.forms['mantenimiento_tabla'].submit();
		}else{
			alert('Debe seleccionar antes un localizador');
		}
	}
}

function grabar_2nivel()
{
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_2nivel_cuadros()
{
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'S';
	Visualizacion_Cabecera_Cuadros();
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_2nivel_teletipos()
{
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'S';
	Visualizacion_Cabecera_Teletipos();
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_2nivel_teletipos_visualizar()
{
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'E';
	Visualizacion_Cabecera_Teletipos();
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_2nivel_alojamientos_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_condiciones';	
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_2nivel_transportes_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_precios';	
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function recalcula_precios_servicios_2nivel()
{
	if(confirm("Se recalcularán todos los precios de servicios en todos los cuadros que tengan salidas posteriores a la fecha de hoy y que tengan este servicio.")){
		document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'R';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function recalcula_tarifarios_2nivel()
{
	if(confirm("Se calcularan/recalcularan todos los costes y precios del tarifario.\nIMPORTANTE: Si el tarifario es con servicios aereos debe indicar una ciudad de salida existente en el cuadro base o \nNO SE CALCULARÁN LOS PRECIOS DE AEREOS")){
		document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'R';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function recalcula_precios_tarifarios_2nivel()
{
	if(confirm("Se recalcularán sólo los precios del tarifario aplicando los parametros de comercializacion del mismo.")){
		document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'P';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function grabar_3nivel_cuadros()
{
	document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'S';
	Visualizacion_Cabecera_Cuadros();
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_4nivel_cuadros()
{
	document.forms['mantenimiento_tabla'].actuar_4nivel.value = 'S';
	Visualizacion_Cabecera_Cuadros();
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_3nivel()
{
	document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_3nivel_alojamientos_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_condiciones';
	document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_3nivel_presupuestos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'marca_pie';
	document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_2nivel_presupuestos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'marca_pie';
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_3nivel_transportes_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_precios';	
	document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_31nivel()
{
	document.forms['mantenimiento_tabla'].actuar_31nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_32nivel()
{
	document.forms['mantenimiento_tabla'].actuar_32nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_4nivel()
{
	document.forms['mantenimiento_tabla'].actuar_4nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_4nivel_alojamientos_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_nuevo_pie';
	document.forms['mantenimiento_tabla'].actuar_4nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_4nivel_transportes_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_nuevo_pie';
	document.forms['mantenimiento_tabla'].actuar_4nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_5nivel()
{
	document.forms['mantenimiento_tabla'].actuar_5nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_5nivel_alojamientos_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_usos';
	document.forms['mantenimiento_tabla'].actuar_5nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function calcular_condiciones_cuadros_5nivel_alojamientos_acuerdos()
{
	if(confirm("Se añadirán todas las condciones nuevas en todos los cuadros que tengan salidas posteriores a la fecha de hoy y que tengan algun paquete apuntando a este acuerdo.")) {
		document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_usos';
		document.forms['mantenimiento_tabla'].actuar_5nivel.value = 'C';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function recalcular_condiciones_cuadros_5nivel_alojamientos_acuerdos()
{
	if(confirm("Se borrarán y recalcularán todas las condciones de todos los cuadros que tengan salidas posteriores a la fecha de hoy y que tengan algun paquete apuntando a este acuerdo.")) {
		document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_usos';
		document.forms['mantenimiento_tabla'].actuar_5nivel.value = 'R';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function grabar_6nivel()
{
	document.forms['mantenimiento_tabla'].actuar_6nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_6nivel_alojamientos_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_precios';
	document.forms['mantenimiento_tabla'].actuar_6nivel.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function Reserva_grabar_pasajeros()
{
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'S';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_grabar_aereos';
	Visualizacion_Secciones_Reservas();
	document.forms['mantenimiento_tabla'].submit();
}

function Reserva_grabar_aereos()
{
	document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'S';
	document.forms['mantenimiento_tabla'].actuar_31nivel.value = 'S';
	document.forms['mantenimiento_tabla'].actuar_32nivel.value = 'S';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_grabar_alojamientos';
	Visualizacion_Secciones_Reservas();
	document.forms['mantenimiento_tabla'].submit();
}

function Reserva_grabar_aereos_pasajeros()
{
	document.forms['mantenimiento_tabla'].actuar_31nivel.value = 'S';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_grabar_alojamientos';
	Visualizacion_Secciones_Reservas();
	document.forms['mantenimiento_tabla'].submit();
}

function Reserva_grabar_aereos_trayectos()
{
	document.forms['mantenimiento_tabla'].actuar_32nivel.value = 'S';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_grabar_alojamientos';
	//Visualizacion_Secciones_Reservas();
	document.forms['mantenimiento_tabla'].submit();
}

function Reserva_grabar_alojamientos()
{
	document.forms['mantenimiento_tabla'].actuar_4nivel.value = 'S';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_grabar_servicios';
    Visualizacion_Secciones_Reservas();
	document.forms['mantenimiento_tabla'].submit();
}

function Reserva_grabar_servicios()
{
	document.forms['mantenimiento_tabla'].actuar_5nivel.value = 'S';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'pvp_comisionable0';
    Visualizacion_Secciones_Reservas();
	document.forms['mantenimiento_tabla'].submit();
}

function duplicar_producto(accion)
{
	document.forms['mantenimiento_tabla'].actuar.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}


function Expandir_cupos_alojamientos(accion)
{
	document.forms['mantenimiento_tabla'].actuar.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Incluir_alojamiento_producto(accion)
{
	if(confirm("Se incluirá este acuerdo en todos los cuadros de producto que tengan salidas vigentes. Se calcularán los paquetes, los precios y las condiciones. Si ya existe en algún cuadro se borrarán sus datos en la misma y se calcularán de nuevo.")) {
		document.forms['mantenimiento_tabla'].actuar.value = accion;
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Actualizar_fechas_cuadro()
{
	document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'A';
	document.forms['mantenimiento_tabla'].submit();
}

function Actualizar_calendario_cuadro()
{
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'A';
	document.forms['mantenimiento_tabla'].submit();
}

function Borrar_cupos_alojamientos(accion)
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_condiciones';
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Borrar_cupos_transportes(accion)
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_precios';
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function grabar_Tipo_Registro()
{
	if(document.forms['mantenimiento_tabla'].nuevo_registro.value == 'S'){
		document.forms['mantenimiento_tabla'].grabar_registro.value = 'S';
		document.forms['mantenimiento_tabla'].actuar.value = 'N';
	}else{
		document.forms['mantenimiento_tabla'].grabar_registro.value = 'N';
		document.forms['mantenimiento_tabla'].actuar.value = 'S';
	}
	document.forms['mantenimiento_tabla'].submit();
}


function grabar_Tipo_Registro_Reserva()
{

	if(document.forms['mantenimiento_tabla'].borra0.value == 'S'){

		if(confirm("Se va a Cancelar la reserva. Tenga en cuenta que, si la reserva tiene alojamientos de interfaz externo, los alojamientos deberan haber sido anulados manualmente por el procedimiento de cancelación de la interfaz o el sistema cobrara el 100% de gastos de los mismos. Confirme anulacion por favor")) {

			if(document.forms['mantenimiento_tabla'].nuevo_registro.value == 'S'){
				if(document.forms['mantenimiento_tabla'].busca_agencias.value != ''){
					document.forms['mantenimiento_tabla'].grabar_registro.value = 'S';
					document.forms['mantenimiento_tabla'].actuar.value = 'N';
					Visualizacion_Secciones_Reservas();
					document.forms['mantenimiento_tabla'].submit();
				}else{
					alert("Es necesario indicar agencia");
				}

			}else{
				document.forms['mantenimiento_tabla'].grabar_registro.value = 'N';
				document.forms['mantenimiento_tabla'].actuar.value = 'S';
				Visualizacion_Secciones_Reservas();
				document.forms['mantenimiento_tabla'].submit();
			}

		}

	}else{

		if(document.forms['mantenimiento_tabla'].nuevo_registro.value == 'S'){
			if(document.forms['mantenimiento_tabla'].busca_agencias.value != ''){
				document.forms['mantenimiento_tabla'].grabar_registro.value = 'S';
				document.forms['mantenimiento_tabla'].actuar.value = 'N';
				Visualizacion_Secciones_Reservas();
				document.forms['mantenimiento_tabla'].submit();
			}else{
				alert("Es necesario indicar agencia");
			}

		}else{
			document.forms['mantenimiento_tabla'].grabar_registro.value = 'N';
			document.forms['mantenimiento_tabla'].actuar.value = 'S';
			Visualizacion_Secciones_Reservas();
			document.forms['mantenimiento_tabla'].submit();
		}
	}

}

function Reserva_grabar_Tipo_Registro_final()
{
	if(document.forms['mantenimiento_tabla'].nuevo_registro.value == 'S'){
		document.forms['mantenimiento_tabla'].grabar_registro.value = 'S';
		document.forms['mantenimiento_tabla'].actuar.value = 'N';
	}else{
		document.forms['mantenimiento_tabla'].grabar_registro.value = 'N';
		document.forms['mantenimiento_tabla'].actuar.value = 'S';
	}
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_grabar_final';
	Visualizacion_Secciones_Reservas();
	document.forms['mantenimiento_tabla'].submit();
}

function Actualizar_Precios()
{
	if(document.forms['mantenimiento_tabla'].localizador0.value != ''){
		document.forms['mantenimiento_tabla'].actuar.value = 'A';
		document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_actualizar_final';
		Visualizacion_Secciones_Reservas();
		document.forms['mantenimiento_tabla'].submit();
	}else{
		alert('Debe seleccionar antes un localizador');
	}
}

function Nuevosseleccionar(j)
{
	document.forms['mantenimiento_tabla']['Nuevoseleccion'+j].checked = true;
	document.forms['mantenimiento_tabla']['Nuevoselec'+j].value = 'S';
}	

function Nuevosseleccionar_2nivel(j)
{
	document.forms['mantenimiento_tabla']['Nuevoseleccion_2nivel'+j].checked = true;
	document.forms['mantenimiento_tabla']['Nuevoselec_2nivel'+j].value = 'S';
}	

function Nuevosseleccionar_3nivel(j)
{
	document.forms['mantenimiento_tabla']['Nuevoseleccion_3nivel'+j].checked = true;
	document.forms['mantenimiento_tabla']['Nuevoselec_3nivel'+j].value = 'S';
}	

function Nuevosseleccionar_31nivel(j)
{
	document.forms['mantenimiento_tabla']['Nuevoseleccion_31nivel'+j].checked = true;
	document.forms['mantenimiento_tabla']['Nuevoselec_31nivel'+j].value = 'S';
}	

function Nuevosseleccionar_32nivel(j)
{
	document.forms['mantenimiento_tabla']['Nuevoseleccion_32nivel'+j].checked = true;
	document.forms['mantenimiento_tabla']['Nuevoselec_32nivel'+j].value = 'S';
}

function Nuevosseleccionar_4nivel(j)
{
	document.forms['mantenimiento_tabla']['Nuevoseleccion_4nivel'+j].checked = true;
	document.forms['mantenimiento_tabla']['Nuevoselec_4nivel'+j].value = 'S';
}	

function Nuevosseleccionar_4nivel_alojamiento_coste_pax(j)
{
	document.forms['mantenimiento_tabla']['Nuevoseleccion_4nivel'+j].checked = true;
	document.forms['mantenimiento_tabla']['Nuevoselec_4nivel'+j].value = 'S';
	
	document.forms['mantenimiento_tabla']['Nuevocoste_habitacion'+j].value = document.forms['mantenimiento_tabla']['Nuevocoste_pax'+j].value * document.forms['mantenimiento_tabla']['Nuevouso'+j].value;
	if(document.forms['mantenimiento_tabla']['Nuevocalculo'+j].value == 'A'){
		document.forms['mantenimiento_tabla']['Nuevoneto_pax'+j].value = Number(document.forms['mantenimiento_tabla']['Nuevocoste_pax'+j].value / (1 - (document.forms['mantenimiento_tabla']['Nuevoporcentaje_neto'+j].value/100))).toFixed(2);
		document.forms['mantenimiento_tabla']['Nuevoneto_habitacion'+j].value = Number(document.forms['mantenimiento_tabla']['Nuevocoste_habitacion'+j].value / (1 - (document.forms['mantenimiento_tabla']['Nuevoporcentaje_neto'+j].value/100))).toFixed(2);
		document.forms['mantenimiento_tabla']['Nuevopvp_pax'+j].value = Number(Number(document.forms['mantenimiento_tabla']['Nuevocoste_pax'+j].value / (1 - (document.forms['mantenimiento_tabla']['Nuevoporcentaje_neto'+j].value/100))).toFixed(2) / (1 - (document.forms['mantenimiento_tabla']['Nuevoporcentaje_com'+j].value/100))).toFixed(2);
		document.forms['mantenimiento_tabla']['Nuevopvp_habitacion'+j].value = Number(Number(document.forms['mantenimiento_tabla']['Nuevocoste_habitacion'+j].value / (1 - (document.forms['mantenimiento_tabla']['Nuevoporcentaje_neto'+j].value/100))).toFixed(2) / (1 - (document.forms['mantenimiento_tabla']['Nuevoporcentaje_com'+j].value/100))).toFixed(2);
	}

}	


function Nuevosseleccionar_5nivel(j)
{
	document.forms['mantenimiento_tabla']['Nuevoseleccion_5nivel'+j].checked = true;
	document.forms['mantenimiento_tabla']['Nuevoselec_5nivel'+j].value = 'S';
}

function Nuevosseleccionar_6nivel(j)
{
	document.forms['mantenimiento_tabla']['Nuevoseleccion_6nivel'+j].checked = true;
	document.forms['mantenimiento_tabla']['Nuevoselec_6nivel'+j].value = 'S';
}

function NuevoRegistro()
{
	document.forms['mantenimiento_tabla'].buscar_id.value = '';
	document.forms['mantenimiento_tabla'].buscar_nombre.value = 'XXXXX';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}	

function NuevoRegistro_Grupo_Gestion()
{
	document.forms['mantenimiento_tabla'].buscar_nombre.value = 'YY';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function NuevoRegistro_Minoristas()
{
	document.forms['mantenimiento_tabla'].buscar_nombre.value = 'YY';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function NuevoRegistro_Impuestos()
{
	document.forms['mantenimiento_tabla'].buscar_codigo.value = '';
	document.forms['mantenimiento_tabla'].buscar_codigo2.value = 'NUEVO';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}	

function NuevoRegistro_Alojamientos_Acuerdos()
{
	document.forms['mantenimiento_tabla'].buscar_id.value = '';
	document.forms['mantenimiento_tabla'].buscar_acuerdo.value = '';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}	

function NuevoRegistro_Transportes_Acuerdos()
{
	document.forms['mantenimiento_tabla'].buscar_cia.value = '';
	document.forms['mantenimiento_tabla'].buscar_acuerdo.value = '';
	document.forms['mantenimiento_tabla'].buscar_subacuerdo.value = '';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function NuevoRegistro_Servicios()
{
	document.forms['mantenimiento_tabla'].buscar_id.value = 0;
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}	

function NuevoRegistro_Transportes()
{
	document.forms['mantenimiento_tabla'].buscar_cia.value = '';
	document.forms['mantenimiento_tabla'].buscar_nombre.value = '   ';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function NuevoRegistro_Folletos()
{
	document.forms['mantenimiento_tabla'].buscar_codigo.value = '';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function NuevoRegistro_Cuadros()
{
	document.forms['mantenimiento_tabla'].buscar_codigo.value = ''
	document.forms['mantenimiento_tabla'].buscar_cuadro.value = '';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function NuevoRegistro_Teletipos()
{
	document.forms['mantenimiento_tabla'].buscar_id.value = ''
	document.forms['mantenimiento_tabla'].buscar_nombre.value = '';
	//document.forms['mantenimiento_tabla'].buscar_fecha_salida.value = '';
	document.forms['mantenimiento_tabla'].buscar_destino.value = '';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function NuevoRegistro_Teletipos_Colores()
{
	document.forms['mantenimiento_tabla'].buscar_id.value = ''
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function NuevoRegistro_Tarifarios()
{
	document.forms['mantenimiento_tabla'].buscar_entidad.value = ''
	document.forms['mantenimiento_tabla'].buscar_anno.value = '';
	document.forms['mantenimiento_tabla'].buscar_destino.value = '';
	document.forms['mantenimiento_tabla'].buscar_noches.value = '';
	document.forms['mantenimiento_tabla'].buscar_nombre.value = '';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}

function NuevoRegistro_Teletipos()
{
	document.forms['mantenimiento_tabla'].buscar_id.value = '';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	document.forms['mantenimiento_tabla'].submit();
}	

function NuevoRegistro_Reserva()
{
	//document.forms['mantenimiento_tabla'].buscar_localizador.value = '0';
	seccion_buscar_agencia.style.display='block';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';

		var frm = document.getElementById("mantenimiento_tabla");
		for (i=0;i<frm.elements.length;i++)
		{
			if(frm.elements[i].type != 'button'){
				frm.elements[i].value = '';
			}
		}

	//Valores automáticos para la reserva manual
	document.forms['mantenimiento_tabla'].folleto0.value = 'BLN';
	document.forms['mantenimiento_tabla'].cuadro0.value = 'BLN';
	document.forms['mantenimiento_tabla'].paquete0.value = 'BLN';
	//document.forms['mantenimiento_tabla'].buscar_localizador.value = '0';
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'S';
	//document.forms['mantenimiento_tabla'].ciudad_salida0.value = '';
	//document.forms['mantenimiento_tabla'].submit();

}

function NuevoReserva_Trayectos_Cupos()
{
	document.forms['mantenimiento_tabla'].actuar_32nivel.value = 'S';
	document.forms['mantenimiento_tabla'].Añadir_Reserva_Trayectos_Cupos.value = 'S';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_grabar_alojamientos';
	Visualizacion_Secciones_Reservas();
	document.forms['mantenimiento_tabla'].submit();
}	

function NuevoReserva_Alojamientos()
{
	document.forms['mantenimiento_tabla'].actuar_4nivel.value = 'S';
	document.forms['mantenimiento_tabla'].Añadir_Reserva_Alojamiento.value = 'S';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_grabar_servicios';
	Visualizacion_Secciones_Reservas();
	document.forms['mantenimiento_tabla'].submit();
}	

function NuevoReserva_Servicios()
{
	document.forms['mantenimiento_tabla'].actuar_5nivel.value = 'S';
	document.forms['mantenimiento_tabla'].Añadir_Reserva_Servicio.value = 'S';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'pvp_comisionable0';
	Visualizacion_Secciones_Reservas();
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_general()
{
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_Interfaz_Disponibilidad()
{
	if(document.forms['mantenimiento_tabla'].buscar_interfaz.value == '' || document.forms['mantenimiento_tabla'].buscar_provincia.value == '' || document.forms['mantenimiento_tabla'].buscar_fecha_entrada.value == '' || document.forms['mantenimiento_tabla'].buscar_fecha_salida.value == ''){
		
		alert("Los campos de búsqueda: Interfaz, Provincia, Entrada y Salida son obligatorios");

	}else{
		document.forms['mantenimiento_tabla'].submit();
	}	
}

function Buscar_Interfaz_Disponibilidad_Geo()
{
	if(document.forms['mantenimiento_tabla'].buscar_interfaz.value == '' || document.forms['mantenimiento_tabla'].buscar_pais.value == '' || document.forms['mantenimiento_tabla'].buscar_fecha_entrada.value == '' || document.forms['mantenimiento_tabla'].buscar_fecha_salida.value == ''){
		
		alert("Los campos de búsqueda: Interfaz, Pais, Entrada y Salida son obligatorios");

	}else{
		document.forms['mantenimiento_tabla'].submit();
	}	
}

function Buscar_alojamientos_acuerdos_periodos_temporadas()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_condiciones';
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_alojamientos_acuerdos_condiciones()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_usos';
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_alojamientos_acuerdos_usos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_precios';
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_alojamientos_acuerdos_precios()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_nuevo_pie';
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_transportes_acuerdos_trayectos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_precios';
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_transportes_acuerdos_precios()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_nuevo_pie';
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_cupos_alojamiento()
{
	Visualizacion_Secciones_Cupos_Alojamientos();
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar()
{
	if(document.forms['mantenimiento_tabla'].buscar_nombre.value == '  '){
		document.forms['mantenimiento_tabla'].buscar_nombre.value = document.forms['mantenimiento_tabla'].nombre0.value;
		document.forms['mantenimiento_tabla'].buscar_id.value = document.forms['mantenimiento_tabla'].id0.value;
	}
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'N';
	document.forms['mantenimiento_tabla'].actuar.value = 'N';
	document.forms['mantenimiento_tabla'].grabar_registro.value = 'N';
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_Grupos()
{
	document.forms['mantenimiento_tabla'].actuar.value = 'N';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_buscar';
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_Folleto()
{
	if(document.forms['mantenimiento_tabla'].buscar_codigo.value == ' '){
		document.forms['mantenimiento_tabla'].buscar_codigo.value = document.forms['mantenimiento_tabla'].codigo0.value;
		/*document.forms['mantenimiento_tabla'].buscar_id.value = document.forms['mantenimiento_tabla'].id0.value;*/
	}
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'N';
	document.forms['mantenimiento_tabla'].actuar.value = 'N';
	document.forms['mantenimiento_tabla'].grabar_registro.value = 'N';
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_Producto()
{
	if(document.forms['mantenimiento_tabla'].buscar_codigo.value == ' '){
		document.forms['mantenimiento_tabla'].buscar_codigo.value = document.forms['mantenimiento_tabla'].codigo0.value;
		/*document.forms['mantenimiento_tabla'].buscar_id.value = document.forms['mantenimiento_tabla'].id0.value;*/
	}
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'N';
	document.forms['mantenimiento_tabla'].actuar.value = 'N';
	document.forms['mantenimiento_tabla'].grabar_registro.value = 'N';
	Visualizacion_Cabecera_Cuadros();
	document.forms['mantenimiento_tabla'].submit();
}

function Buscar_Teletipo()
{
	if(document.forms['mantenimiento_tabla'].buscar_id.value == ' '){
		document.forms['mantenimiento_tabla'].buscar_id.value = document.forms['mantenimiento_tabla'].id0.value;
	}
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'N';
	document.forms['mantenimiento_tabla'].actuar.value = 'N';
	document.forms['mantenimiento_tabla'].grabar_registro.value = 'N';
	Visualizacion_Cabecera_Teletipos();
	document.forms['mantenimiento_tabla'].submit();
}

function Anadir_Remitentes_Teletipos()
{

	var select = document.getElementById( 'buscar_provincia' );
	var cuenta_provincias = 0;


	for ( i = 0; i < select.length; i++ ) {

	    if(select[i].selected == true && select[i].text != ''){
		    //alert(select[i].selected+'-'+select[i].value+'-'+select[i].text);
		    //alert(select.length);
		    cuenta_provincias++;
		}
	}


	if(cuenta_provincias == 0){
		alert('Debe seleccionar alguna provincia');
	}else{
		if(document.forms['mantenimiento_tabla'].ciudad_salida.value == ''){
	 		alert('Es obligatorio indicar una ciudad de salida');
	 	}else{
			document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'A';
			Visualizacion_Cabecera_Teletipos();
			document.forms['mantenimiento_tabla'].submit();
		}
	}
}

function Teletipos_Selecciona_Comunidad(comunidad)
{

	var select = document.getElementById( 'buscar_provincia' );


	for ( i = 0; i < select.length; i++ ) {

		if(comunidad == 'AND' && (select[i].value == 'AL' || select[i].value == 'CA' || select[i].value == 'CE' || select[i].value == 'CO' || select[i].value == 'GR' || select[i].value == 'H' || select[i].value == 'J' || select[i].value == 'MA' || select[i].value == 'ML' || select[i].value == 'SE')){
			if(document.forms['mantenimiento_tabla']['seleccion_Andalucia'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'ARG' && (select[i].value == 'HU' || select[i].value == 'TE' || select[i].value == 'Z')){
			if(document.forms['mantenimiento_tabla']['seleccion_Aragon'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'AST' && (select[i].value == 'O')){
			if(document.forms['mantenimiento_tabla']['seleccion_Asturias'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'BAL' && (select[i].value == 'PM')){
			if(document.forms['mantenimiento_tabla']['seleccion_Baleares'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'CAN' && (select[i].value == 'CN' || select[i].value == 'GC' || select[i].value == 'LP' || select[i].value == 'TF' || select[i].value == 'VD')){
			if(document.forms['mantenimiento_tabla']['seleccion_Canarias'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'CNT' && (select[i].value == 'S')){
			if(document.forms['mantenimiento_tabla']['seleccion_Cantabria'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'CSM' && (select[i].value == 'AB' || select[i].value == 'CR' || select[i].value == 'CU' || select[i].value == 'GU' || select[i].value == 'TO')){
			if(document.forms['mantenimiento_tabla']['seleccion_CastLaMancha'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'CSL' && (select[i].value == 'AV' || select[i].value == 'BU' || select[i].value == 'LE' || select[i].value == 'P' || select[i].value == 'SA' || select[i].value == 'SG' || select[i].value == 'SO' || select[i].value == 'VA' || select[i].value == 'ZA')){
			if(document.forms['mantenimiento_tabla']['seleccion_CastLeon'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'CAT' && (select[i].value == 'B' || select[i].value == 'GI' || select[i].value == 'L' || select[i].value == 'T')){
			if(document.forms['mantenimiento_tabla']['seleccion_Cataluna'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'VAL' && (select[i].value == 'A' || select[i].value == 'CS' || select[i].value == 'V')){
			if(document.forms['mantenimiento_tabla']['seleccion_CValenciana'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'EXT' && (select[i].value == 'BA' || select[i].value == 'CC')){
			if(document.forms['mantenimiento_tabla']['seleccion_Extremadura'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'GAL' && (select[i].value == 'C' || select[i].value == 'LU' || select[i].value == 'OR' || select[i].value == 'PO')){
			if(document.forms['mantenimiento_tabla']['seleccion_Galicia'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'RIO' && (select[i].value == 'RI' || select[i].value == 'LO')){
			if(document.forms['mantenimiento_tabla']['seleccion_LaRioja'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'MAD' && (select[i].value == 'M')){
			if(document.forms['mantenimiento_tabla']['seleccion_Madrid'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'MURCI' && (select[i].value == 'MU')){
			if(document.forms['mantenimiento_tabla']['seleccion_Murcia'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'NAV' && (select[i].value == 'NA')){
			if(document.forms['mantenimiento_tabla']['seleccion_Navarra'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

		if(comunidad == 'VAS' && (select[i].value == 'VI' || select[i].value == 'SS'|| select[i].value == 'BI')){
			if(document.forms['mantenimiento_tabla']['seleccion_PVasco'].checked == true){
				select[i].selected = true;
			}else{
				select[i].selected = false;
			}
		}

	}


}

function Activar_Remitentes_Teletipos()
{
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'V';
	Visualizacion_Cabecera_Teletipos();
	document.forms['mantenimiento_tabla'].submit();
}

function Desactivar_Remitentes_Teletipos()
{
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'D';
	Visualizacion_Cabecera_Teletipos();
	document.forms['mantenimiento_tabla'].submit();
}

function Borrar_Remitentes_Teletipos()
{
	document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'B';
	Visualizacion_Cabecera_Teletipos();
	document.forms['mantenimiento_tabla'].submit();
}

function Calcular_Precios_Teletipos()
{
	if(confirm("Se borrarán todos los precios y logos y se calcularán de nuevo.")) {
		document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'C';
		Visualizacion_Cabecera_Teletipos();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Reserva_Buscar_Pasajero()
{
	if(document.forms['mantenimiento_tabla'].buscar_nombre.value == '  '){
		document.forms['mantenimiento_tabla'].buscar_nombre.value = document.forms['mantenimiento_tabla'].nombre0.value;
		document.forms['mantenimiento_tabla'].buscar_id.value = document.forms['mantenimiento_tabla'].id0.value;
	}
	document.forms['mantenimiento_tabla'].nuevo_registro.value = 'N';
	document.forms['mantenimiento_tabla'].actuar.value = 'N';
	document.forms['mantenimiento_tabla'].grabar_registro.value = 'N';
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_grabar_aereos';
	document.forms['mantenimiento_tabla'].submit();
}

function Selector(accion)
{
	document.forms['mantenimiento_tabla'].botonSelector.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}	

function Selector_2nivel(accion)
{
	document.forms['mantenimiento_tabla'].botonSelector_2nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_2nivel_alojamientos_acuerdos(accion)
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_condiciones';
	document.forms['mantenimiento_tabla'].botonSelector_2nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Submit_2nivel_alojamientos_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_condiciones';
	document.forms['mantenimiento_tabla'].submit();
}		

function Selector_2nivel_transportes_acuerdos(accion)
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_precios';
	document.forms['mantenimiento_tabla'].botonSelector_2nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Submit_2nivel_transportes_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_precios';
	document.forms['mantenimiento_tabla'].submit();
}

function Submit_2nivel_grupos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'marca_pie';
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_3nivel(accion)
{
	document.forms['mantenimiento_tabla'].botonSelector_3nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_3nivel_alojamientos_acuerdos(accion)
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_condiciones';
	document.forms['mantenimiento_tabla'].botonSelector_3nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Submit_3nivel_alojamientos_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_condiciones';
	document.forms['mantenimiento_tabla'].submit();
}

function Submit_3nivel_grupos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'marca_pie';
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_31nivel(accion)
{
	document.forms['mantenimiento_tabla'].botonSelector_31nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_32nivel(accion)
{
	document.forms['mantenimiento_tabla'].botonSelector_32nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_4nivel(accion)
{
	document.forms['mantenimiento_tabla'].botonSelector_4nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_4nivel_alojamientos_acuerdos(accion)
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_nuevo_pie';
	document.forms['mantenimiento_tabla'].botonSelector_4nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Submit_4nivel_alojamientos_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_nuevo_pie';
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_4nivel_transportes_acuerdos(accion)
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_nuevo_pie';
	document.forms['mantenimiento_tabla'].botonSelector_4nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Submit_4nivel_transportes_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'boton_nuevo_pie';
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_5nivel(accion)
{
	document.forms['mantenimiento_tabla'].botonSelector_5nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_5nivel_alojamientos_acuerdos(accion)
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_usos';
	document.forms['mantenimiento_tabla'].botonSelector_5nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Submit_5nivel_alojamientos_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_usos';
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_6nivel(accion)
{
	document.forms['mantenimiento_tabla'].botonSelector_6nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Selector_6nivel_alojamientos_acuerdos(accion)
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_precios';
	document.forms['mantenimiento_tabla'].botonSelector_6nivel.value = accion;
	document.forms['mantenimiento_tabla'].submit();
}

function Submit_6nivel_alojamientos_acuerdos()
{
	document.forms['mantenimiento_tabla'].manda_posicion.value = 'filadesde_precios';
	document.forms['mantenimiento_tabla'].submit();
}

function rellenar_bln(i)
{
	//if(document.forms['mantenimiento_tabla']['Nuevocuadro'+i].value == ''){
	//	document.forms['mantenimiento_tabla']['Nuevocuadro'+i].value = 'BLN';
	//}

	if(document.forms['mantenimiento_tabla'][i].value == ''){
		document.forms['mantenimiento_tabla'][i].value = 'BLN';
	}

}

function facturar_seleccionadas()
{
	if(confirm("Se facturaran todas las reservas con el check de seleccion. Confirme por favor.")) {
		document.forms['mantenimiento_tabla'].actuar.value = 'S';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function facturar_parametros()
{
	if(confirm("Se facturaran todas las reservas de la seleccion por parametros. Confirme por favor.")) {
		document.forms['mantenimiento_tabla'].actuar.value = 'P';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Enviar_aviso_seleccionadas()
{
	if(confirm("Se enviará un mail a la agencia por cada reserva seleccionada con el check de selección. Confirme por favor.")) {
		document.forms['mantenimiento_tabla'].actuar.value = 'A';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Enviar_aviso_parametros()
{
	if(confirm("Se enviará un mail a la agencia por cada reserva seleccionada según los parámetros. Confirme por favor.")) {
		document.forms['mantenimiento_tabla'].actuar.value = 'E';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Cuadros_servicios_calcula_pvp()
{
	if(confirm("Se calcularán todos los pvp's de los servicios que no tengan precio.")) {
		document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'C';
		Visualizacion_Cabecera_Cuadros();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Cuadros_servicios_recalcula_pvp()
{
	if(confirm("Se borrarán los precios de todos los servicios y se volverán a calcular.")) {
		document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'R';
		Visualizacion_Cabecera_Cuadros();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Cuadros_transportes_calcula_pvp()
{
	if(confirm("Se calcularán todos los pvp's de los transportes que no tengan precio.")) {
		document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'C';
		Visualizacion_Cabecera_Cuadros();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Cuadros_transportes_recalcula_pvp()
{
	if(confirm("Se borrarán los precios de todos los transportes y se volverán a calcular.")) {
		document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'R';
		Visualizacion_Cabecera_Cuadros();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Cuadros_alojamientos_calcula()
{
	if(confirm("Se calcularán todos paquetes de los alojamientos que no existan.")) {
		document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'C';
		Visualizacion_Cabecera_Cuadros();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Cuadros_alojamientos_recalcula()
{
	if(confirm("Se borrarán todos los paquetes de alojamientos y sus precios y se volverán a calcular los paquetes.")) {
		document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'R';
		Visualizacion_Cabecera_Cuadros();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Cuadros_alojamientos_borrar_todos()
{
	if(confirm("ATENCION!! Se borrarán todos los paquetes de alojamientos, sus precios y sus condiciones.")) {
		document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'B';
		Visualizacion_Cabecera_Cuadros();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Cuadros_alojamientos_calcula_pvp()
{
	if(confirm("Se calcularán todos los pvp's de los paquetes que no tengan precio.")) {
		document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'C';
		Visualizacion_Cabecera_Cuadros();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Cuadros_alojamientos_recalcula_pvp()
{
	if(confirm("Se borrarán los precios de todos los paquetes y se volverán a calcular.")) {
		document.forms['mantenimiento_tabla'].actuar_3nivel.value = 'R';
		Visualizacion_Cabecera_Cuadros();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Cuadros_alojamientos_calcula_condiciones()
{
	if(confirm("Se calcularán todas las condiciones de los paquetes que no existan ya.")) {
		document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'C';
		Visualizacion_Cabecera_Cuadros();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Cuadros_alojamientos_recalcula_condiciones()
{
	if(confirm("Se borrarán las condiciones de todos los paquetes y se volverán a calcular.")) {
		document.forms['mantenimiento_tabla'].actuar_2nivel.value = 'R';
		Visualizacion_Cabecera_Cuadros();
		document.forms['mantenimiento_tabla'].submit();
	}
}

function showVal(newVal){
  document.getElementById("valBox").innerHTML=newVal;
}

function Excel_Tarifarios(){
	//alert('hola');

	if(document.forms['mantenimiento_tabla']['clave0'].value != '' && document.forms['mantenimiento_tabla']['buscar_fecha_desde_precios'].value!= '' && document.forms['mantenimiento_tabla']['buscar_fecha_hasta_precios'].value!= ''){
  			document.location.href="Excel_Tarifarios.php?clave="+document.forms['mantenimiento_tabla']['clave0'].value+"&fecha_desde="+document.forms['mantenimiento_tabla']['buscar_fecha_desde_precios'].value+"&fecha_hasta="+document.forms['mantenimiento_tabla']['buscar_fecha_hasta_precios'].value;
  	}else{
  		alert('Se debe indicar un periodo. (El periodo no debe incluir más de 20 fechas de salida).');	
  	}
	/*if(confirm("sadfhgrwth.")) {
	}*/

  /*document.location.href="Excel_Tarifarios.php?clave=4&fecha_desde=01-06-2015&fecha_hasta=30-09-2015";*/
}

function Excel_Tarifarios_Calculos(){
	//alert('hola');

	if(document.forms['mantenimiento_tabla']['clave0'].value != '' && document.forms['mantenimiento_tabla']['buscar_fecha_desde_precios'].value!= '' && document.forms['mantenimiento_tabla']['buscar_fecha_hasta_precios'].value!= ''){
  			document.location.href="Excel_Tarifarios_Calculos.php?clave="+document.forms['mantenimiento_tabla']['clave0'].value+"&fecha_desde="+document.forms['mantenimiento_tabla']['buscar_fecha_desde_precios'].value+"&fecha_hasta="+document.forms['mantenimiento_tabla']['buscar_fecha_hasta_precios'].value;
  	}else{
  		alert('Se debe indicar un periodo. (El periodo no debe incluir más de 20 fechas de salida).');	
  	}
	/*if(confirm("sadfhgrwth.")) {
	}*/

  /*document.location.href="Excel_Tarifarios.php?clave=4&fecha_desde=01-06-2015&fecha_hasta=30-09-2015";*/
}

function Cargar_Disponibilidad_Nocturna_Api()
{
	if(confirm("Se actualizarán todos los cupos de los acuerdos del tipo 'Regular Cupo Amadeus' a partir de la fecha de hoy. Tenga cuidado de no tener un acuerdo de cupos normales como de cupos Amadeus ya que este proceso los dejará primero a cero y despues los actualizará con la información obtenida del api.")) {
		document.forms['mantenimiento_tabla'].actuar.value = 'D';
		document.forms['mantenimiento_tabla'].submit();
	}
}

function Consultar_Api_Cryptic()
{
	document.forms['mantenimiento_tabla'].actuar.value = 'C';
	document.forms['mantenimiento_tabla'].submit();
}

function Seleccionar_Hotal_Interfaz(i)
{
	if(document.forms['mantenimiento_tabla']['ciudad_provincia'+i].value == ''){
		document.forms['mantenimiento_tabla']['seleccion'+i].checked = false;
		document.forms['mantenimiento_tabla']['selec'+i].value = 'N';
		alert('Se debe seleccionar una ciudad de Hits antes de añadir el alojamiento en la base de datos.');
	}else{
		if(document.forms['mantenimiento_tabla']['seleccion'+i].checked == true){
			document.forms['mantenimiento_tabla']['selec'+i].value = 'S';
		}else{
			document.forms['mantenimiento_tabla']['selec'+i].value = 'N';
		}
	}

}