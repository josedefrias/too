
function Cargar_inicio(elemento){
	Cargar_busqueda_agencias();
	Cargar_busqueda_aereos_cupos();
	Cargar_busqueda_alojamientos();
	Cargar_busqueda_servicios();
	Posicion(elemento);
}

 function Posicion(elemento){
    document.getElementById(elemento).focus();
 }

function Cargar_busqueda_agencias2(){
	$('#ajax_load')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reservas_Agencias2.php",
				data: 'nombre_agencia='+$('#buscar_agencia_minorista')[0].value+'&oficina_agencia='+$('#buscar_agencia_oficina')[0].value+'&localidad_agencia='+$('#buscar_agencia_localidad')[0].value+'&direccion_agencia='+$('#buscar_agencia_direccion')[0].value+'&telefono_agencia='+$('#buscar_agencia_telefono')[0].value,
				dataType: "json",	//expect html to be returned
				success: function(msg){
					$('#clave_agencia').html('');
					for (var i = 0; i < msg.length ; i++)
					{
						$('#clave_agencia').append('<option value="'+msg[i].clave+'">'+msg[i].nombre+'</option>')
					}

					$('#ajax_load')[0].style.display='none';
					//if(parseInt(msg)!=0)	//if no errors
					//{
						//alert(msg);
						//$('#pageContent').html(msg);	//load the returned html into pageContet

					//}
				}

			});
}

function carga_destinos(){
	$('#ajax_load_destinos')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reserva_Paquete_Destino.php",
				//data: 'nombre_agencia='+$('#buscar_agencia_minorista')[0].value+'&oficina_agencia='+$('#buscar_agencia_oficina')[0].value+'&localidad_agencia='+$('#buscar_agencia_localidad')[0].value+'&direccion_agencia='+$('#buscar_agencia_direccion')[0].value+'&telefono_agencia='+$('#buscar_agencia_telefono')[0].value,
				data: 'ciudad='+$('#ciudad')[0].value,
				dataType: "json",	//expect html to be returned
				success: function(msg){

					$('#destino').html('');
					$('#producto').html('');
					$('#fecha').html('');
					$('#noches').html('');
					$('#opcion').html('');
					$('#alojamiento').html('');
					$('#caracteristica').html('');
					$('#regimen').html('');

					$('#destino').append('<option value="">Seleccione destino</option>');

					for (var i = 0; i < msg.length ; i++)
					{
						$('#destino').append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
					}

					$('#ajax_load_destinos')[0].style.display='none';
					//if(parseInt(msg)!=0)	//if no errors
					//{
						//alert(msg);
						//$('#pageContent').html(msg);	//load the returned html into pageContet

					//}
				}

			});
}


function carga_productos(){
	$('#ajax_load_productos')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reserva_Paquete_Producto.php",
				//data: 'nombre_agencia='+$('#buscar_agencia_minorista')[0].value+'&oficina_agencia='+$('#buscar_agencia_oficina')[0].value+'&localidad_agencia='+$('#buscar_agencia_localidad')[0].value+'&direccion_agencia='+$('#buscar_agencia_direccion')[0].value+'&telefono_agencia='+$('#buscar_agencia_telefono')[0].value,
				data: 'ciudad='+$('#ciudad')[0].value+'&destino='+$('#destino')[0].value,
				dataType: "json",	//expect html to be returned
				success: function(msg){

					$('#producto').html('');
					$('#fecha').html('');
					$('#noches').html('');
					$('#opcion').html('');
					$('#alojamiento').html('');
					$('#caracteristica').html('');
					$('#regimen').html('');

					$('#producto').append('<option value="">Seleccione producto</option>');

					for (var i = 0; i < msg.length ; i++)
					{
						$('#producto').append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
					}

					$('#ajax_load_productos')[0].style.display='none';
					//if(parseInt(msg)!=0)	//if no errors
					//{
						//alert(msg);
						//$('#pageContent').html(msg);	//load the returned html into pageContet

					//}
				}

			});
}


function carga_fechas(){
	$('#ajax_load_fechas')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reserva_Paquete_Fecha.php",
				//data: 'nombre_agencia='+$('#buscar_agencia_minorista')[0].value+'&oficina_agencia='+$('#buscar_agencia_oficina')[0].value+'&localidad_agencia='+$('#buscar_agencia_localidad')[0].value+'&direccion_agencia='+$('#buscar_agencia_direccion')[0].value+'&telefono_agencia='+$('#buscar_agencia_telefono')[0].value,
				data: 'ciudad='+$('#ciudad')[0].value+'&destino='+$('#destino')[0].value+'&producto='+$('#producto')[0].value,
				dataType: "json",	//expect html to be returned
				success: function(msg){

					$('#fecha').html('');
					$('#noches').html('');
					$('#opcion').html('');
					$('#alojamiento').html('');
					$('#caracteristica').html('');
					$('#regimen').html('');

					$('#fecha').append('<option value="">Seleccione fecha</option>');

					for (var i = 0; i < msg.length ; i++)
					{
						$('#fecha').append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
					}

					$('#ajax_load_fechas')[0].style.display='none';
					//if(parseInt(msg)!=0)	//if no errors
					//{
						//alert(msg);
						//$('#pageContent').html(msg);	//load the returned html into pageContet

					//}
				}

			});


	if(document.forms['mantenimiento_tabla'].producto.value != 'SVO'){
		seccion_habitaciones.style.display='block';
		seccion_pasajeros_solo_vuelo.style.display='none';
	}else{
		seccion_habitaciones.style.display='none';
		seccion_pasajeros_solo_vuelo.style.display='block';
	}

}




function carga_noches(){
	$('#ajax_load_noches')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reserva_Paquete_Noches.php",
				//data: 'nombre_agencia='+$('#buscar_agencia_minorista')[0].value+'&oficina_agencia='+$('#buscar_agencia_oficina')[0].value+'&localidad_agencia='+$('#buscar_agencia_localidad')[0].value+'&direccion_agencia='+$('#buscar_agencia_direccion')[0].value+'&telefono_agencia='+$('#buscar_agencia_telefono')[0].value,
				data: 'ciudad='+$('#ciudad')[0].value+'&destino='+$('#destino')[0].value+'&producto='+$('#producto')[0].value+'&fecha='+$('#fecha')[0].value,
				dataType: "json",	//expect html to be returned
				success: function(msg){

					$('#noches').html('');
					$('#opcion').html('');
					$('#alojamiento').html('');
					$('#caracteristica').html('');
					$('#regimen').html('');

					$('#noches').append('<option value="">Seleccione duracion</option>');

					for (var i = 0; i < msg.length ; i++)
					{
						$('#noches').append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
					}

					$('#ajax_load_noches')[0].style.display='none';
					//if(parseInt(msg)!=0)	//if no errors
					//{
						//alert(msg);
						//$('#pageContent').html(msg);	//load the returned html into pageContet

					//}
				}

			});
}


function carga_opcion(){
	$('#ajax_load_opcion')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reserva_Paquete_Opcion.php",
				//data: 'nombre_agencia='+$('#buscar_agencia_minorista')[0].value+'&oficina_agencia='+$('#buscar_agencia_oficina')[0].value+'&localidad_agencia='+$('#buscar_agencia_localidad')[0].value+'&direccion_agencia='+$('#buscar_agencia_direccion')[0].value+'&telefono_agencia='+$('#buscar_agencia_telefono')[0].value,
				data: 'ciudad='+$('#ciudad')[0].value+'&destino='+$('#destino')[0].value+'&producto='+$('#producto')[0].value+'&fecha='+$('#fecha')[0].value+'&noches='+$('#noches')[0].value,
				dataType: "json",	//expect html to be returned
				success: function(msg){

					$('#opcion').html('');

					$('#opcion').append('<option value="">Seleccione Opcion Vuelos</option>');

					for (var i = 0; i < msg.length ; i++)
					{
						$('#opcion').append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
					}

					$('#ajax_load_opcion')[0].style.display='none';
					//if(parseInt(msg)!=0)	//if no errors
					//{
						//alert(msg);
						//$('#pageContent').html(msg);	//load the returned html into pageContet

					//}
				}

			});
}


function carga_alojamiento(){
	if(document.forms['mantenimiento_tabla'].producto.value != 'SVO' &&  document.forms['mantenimiento_tabla'].producto.value != 'OSV' &&  document.forms['mantenimiento_tabla'].producto.value != 'SSV'){
			$('#ajax_load_alojamiento')[0].style.display='block';
				$.ajax({	//create an ajax request to load_page.php
						type: "POST",
						url: "funciones_php_ajax/Reserva_Paquete_Alojamiento.php",
						//data: 'nombre_agencia='+$('#buscar_agencia_minorista')[0].value+'&oficina_agencia='+$('#buscar_agencia_oficina')[0].value+'&localidad_agencia='+$('#buscar_agencia_localidad')[0].value+'&direccion_agencia='+$('#buscar_agencia_direccion')[0].value+'&telefono_agencia='+$('#buscar_agencia_telefono')[0].value,
						data: 'ciudad='+$('#ciudad')[0].value+'&destino='+$('#destino')[0].value+'&producto='+$('#producto')[0].value+'&fecha='+$('#fecha')[0].value+'&noches='+$('#noches')[0].value,
						dataType: "json",	//expect html to be returned
						success: function(msg){

							$('#alojamiento').html('');
							$('#caracteristica').html('');
							$('#regimen').html('');

							$('#alojamiento').append('<option value="">Seleccione Alojamiento</option>');

							for (var i = 0; i < msg.length ; i++)
							{
								$('#alojamiento').append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
							}

							$('#ajax_load_alojamiento')[0].style.display='none';
							//if(parseInt(msg)!=0)	//if no errors
							//{
								//alert(msg);
								//$('#pageContent').html(msg);	//load the returned html into pageContet

							//}
						}

					});
	}else{
							$('#alojamiento').append('<option value="">Sin Alojamiento</option>');
	}
}


function carga_opcion_alojamiento(){
	carga_opcion();
	carga_alojamiento();
}


function carga_caracteristica(){
	$('#ajax_load_caracteristica')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reserva_Paquete_Alojamiento_Caracteristica.php",
				//data: 'nombre_agencia='+$('#buscar_agencia_minorista')[0].value+'&oficina_agencia='+$('#buscar_agencia_oficina')[0].value+'&localidad_agencia='+$('#buscar_agencia_localidad')[0].value+'&direccion_agencia='+$('#buscar_agencia_direccion')[0].value+'&telefono_agencia='+$('#buscar_agencia_telefono')[0].value,
				data: 'ciudad='+$('#ciudad')[0].value+'&destino='+$('#destino')[0].value+'&producto='+$('#producto')[0].value+'&fecha='+$('#fecha')[0].value+'&noches='+$('#noches')[0].value+'&alojamiento='+$('#alojamiento')[0].value,
				dataType: "json",	//expect html to be returned
				success: function(msg){

					$('#caracteristica').html('');
					$('#regimen').html('');

					$('#caracteristica').append('<option value="">Seleccione Tipo de Habitacion</option>');

					for (var i = 0; i < msg.length ; i++)
					{
						$('#caracteristica').append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
					}

					$('#ajax_load_caracteristica')[0].style.display='none';
					//if(parseInt(msg)!=0)	//if no errors
					//{
						//alert(msg);
						//$('#pageContent').html(msg);	//load the returned html into pageContet

					//}
				}

			});
}


function carga_regimen(){
	$('#ajax_load_regimen')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reserva_Paquete_Alojamiento_Regimen.php",
				data: 'ciudad='+$('#ciudad')[0].value+'&destino='+$('#destino')[0].value+'&producto='+$('#producto')[0].value+'&fecha='+$('#fecha')[0].value+'&noches='+$('#noches')[0].value+'&alojamiento='+$('#alojamiento')[0].value+'&caracteristica='+$('#caracteristica')[0].value,
				dataType: "json",	//expect html to be returned
				success: function(msg){

					$('#regimen').html('');

					$('#regimen').append('<option value="">Regimen</option>');

					for (var i = 0; i < msg.length ; i++)
					{
						$('#regimen').append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
					}

					$('#ajax_load_regimen')[0].style.display='none';
				}

			});
	
	document.forms['mantenimiento_tabla'].habitacion1_caracteristica.value = document.forms['mantenimiento_tabla'].caracteristica.value;
	document.forms['mantenimiento_tabla'].habitacion2_caracteristica.value = document.forms['mantenimiento_tabla'].caracteristica.value;
	document.forms['mantenimiento_tabla'].habitacion3_caracteristica.value = document.forms['mantenimiento_tabla'].caracteristica.value;
	document.forms['mantenimiento_tabla'].habitacion4_caracteristica.value = document.forms['mantenimiento_tabla'].caracteristica.value;
}




