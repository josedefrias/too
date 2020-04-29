
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

function Cargar_busqueda_agencias(){
	$('#ajax_load')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reservas_Agencias.php",
				data: 'nombre_agencia='+$('#buscar_agencia_minorista')[0].value+'&oficina_agencia='+$('#buscar_agencia_oficina')[0].value+'&localidad_agencia='+$('#buscar_agencia_localidad')[0].value+'&direccion_agencia='+$('#buscar_agencia_direccion')[0].value+'&telefono_agencia='+$('#buscar_agencia_telefono')[0].value,
				dataType: "html",	//expect html to be returned
				success: function(msg){
					$('#ajax_load')[0].style.display='none';
					if(parseInt(msg)!=0)	//if no errors
					{
						//alert(msg);
						$('#pageContent').html(msg);	//load the returned html into pageContet

					}
				}

			});
}


function Cargar_busqueda_alojamientos(){
	$('#ajax_load_alojamientos')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reservas_Alojamientos.php",
				data: 'fecha_desde='+$('#busca_alojamiento_fecha_desde')[0].value+'&fecha_hasta='+$('#busca_alojamiento_fecha_hasta')[0].value+'&alojamiento='+$('#busca_alojamiento_alojamiento')[0].value+'&consulta='+$('#busca_alojamiento_consulta')[0].value,
				dataType: "html",	//expect html to be returned
				success: function(msg){
					$('#ajax_load_alojamientos')[0].style.display='none';
					if(parseInt(msg)!=0)	//if no errors
					{
						//alert(msg);
						$('#pageContentAlojamientos').html(msg);	//load the returned html into pageContet

					}
				}

			});
}

function Cargar_busqueda_servicios(){
	$('#ajax_load_servicios')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reservas_Servicios.php",
				data: 'fecha='+$('#busca_servicio_fecha')[0].value+'&proveedor='+$('#busca_servicio_proveedor')[0].value+'&codigo='+$('#busca_servicio_codigo')[0].value+'&tipo='+$('#busca_servicio_tipo')[0].value+'&pax='+$('#pax0')[0].value,
				dataType: "html",	//expect html to be returned
				success: function(msg){
					$('#ajax_load_servicios')[0].style.display='none';
					if(parseInt(msg)!=0)	//if no errors
					{
						//alert(msg);
						$('#pageContentServicios').html(msg);	//load the returned html into pageContet

					}
				}

			});
}

function Cargar_busqueda_aereos_cupos(){
	$('#ajax_load_aereos')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reservas_Aereos.php",
				data: 'fecha='+$('#busca_aereos_cupos_fecha')[0].value+'&origen='+$('#busca_aereos_cupos_origen')[0].value+'&destino='+$('#busca_aereos_cupos_destino')[0].value+'&tipo_trayecto='+$('#busca_aereos_tipo_trayecto')[0].value+'&fecha_vuelta='+$('#busca_aereos_cupos_fecha_vuelta')[0].value,
				dataType: "html",	//expect html to be returned
				success: function(msg){
					$('#ajax_load_aereos')[0].style.display='none';
					if(parseInt(msg)!=0)	//if no errors
					{
						//alert(msg);
						$('#pageContentAereos').html(msg);	//load the returned html into pageContet

					}
				}

			});
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

