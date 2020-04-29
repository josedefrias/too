
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//FUNCIONES AJAX DEL BUSCADOR CUANDO SE HACE SUBMIT O SE VIENE DE OTRA PAGINAÑ
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------


function Cargar_inicio_buscar_viajes(pais,destino,producto,fecha,alojamientos_encontrados){
	//alert(destino+'-'+producto+'-'+fecha);
	//alert('Ahora visualizamos las secciones, las habitaciones y recargamos combos');
	visualizar_secciones(producto);
	visualizar_habitaciones();
	carga_productos_Submit(pais,producto);
	if(producto != 'SSV'){
		cargar_mapa(alojamientos_encontrados);
	}
	//carga_origenes_Submit();
	//carga_destinos_Submit();

	//alert(document.forms['mantenimiento_tabla'].recupera_buscar_destino.value);
	//alert(destino+producto+fecha);
	//carga_productos(destino);
	//carga_fechas(destino,producto);

}

//---------------------------------
//YA EN USO EN BUSQUEDA VIAJES
//---------------------------------
function carga_productos_Submit(v_pais, v_producto){

	if(v_pais == null){
		if(document.forms['mantenimiento_tabla'].buscar_pais.value != null){
			var v_pais = document.forms['mantenimiento_tabla'].buscar_pais.value;
		}else{
			var v_pais = document.forms['mantenimiento_tabla'].recupera_buscar_pais.value;
		}
	}

	if(v_producto == null){
		if(document.forms['mantenimiento_tabla'].buscar_producto.value != null){
			var v_producto = document.forms['mantenimiento_tabla'].buscar_producto.value;
		}else{
			var v_producto = document.forms['mantenimiento_tabla'].recupera_buscar_producto.value;
		}
	}

	//alert(v_pais+'-'+v_producto);
	$('#ajax_load_productos')[0].style.display='block';

		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reserva_Producto_Normal.php",
				data: 'pais='+$('#buscar_pais')[0].value,
				dataType: "json",	//expect html to be returned
				success: function(msg){

					/*$('#buscar_producto').html('');
					$('#buscar_fecha').val('');*/

					$('#buscar_origen').html('');
					//$('#buscar_destino').html('');

					$('#buscar_producto').append('<option value="">Seleccione tipo de viaje</option>');

					for (var i = 0; i < msg.length ; i++)
					{
						$('#buscar_producto').append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
					}

					$("#buscar_producto option[value='"+v_producto+"']").attr("selected",true);

					$('#ajax_load_productos')[0].style.display='none';
				}

			});

		carga_origenes_Submit(v_pais);
		/*carga_fechas_Submit(v_destino,v_producto);
		carga_alojamiento_Submit(v_destino,v_producto,v_alojamiento);*/

}



//---------------------------------
//YA EN USO EN BUSQUEDA VIAJES
//---------------------------------
function carga_alojamiento_Submit(v_destino,v_producto,v_alojamiento){


	visualizar_secciones(v_producto);

	if(document.forms['mantenimiento_tabla'].buscar_origen.value == null){
		var v_origen = document.forms['mantenimiento_tabla'].buscar_origen.value;
	}else{
		var v_origen = document.forms['mantenimiento_tabla'].recupera_buscar_origen.value;
	}

	if(v_destino == ''){
		if(document.forms['mantenimiento_tabla'].buscar_destino.value != ''){
			var v_destino = document.forms['mantenimiento_tabla'].buscar_destino.value;
		}else{
			var v_destino = document.forms['mantenimiento_tabla'].recupera_buscar_destino.value;
		}
	}
	if(v_producto == ''){
		if(document.forms['mantenimiento_tabla'].buscar_producto.value != ''){
			var v_producto = document.forms['mantenimiento_tabla'].buscar_producto.value;
		}else{
			var v_producto = document.forms['mantenimiento_tabla'].recupera_buscar_producto.value;
		}
	}
	if(v_alojamiento == ''){
		if(document.forms['mantenimiento_tabla'].buscar_alojamiento.value != ''){
			var v_alojamiento = document.forms['mantenimiento_tabla'].buscar_alojamiento.value;
		}else{
			var v_alojamiento = document.forms['mantenimiento_tabla'].recupera_buscar_alojamiento.value;
		}
	}

	if(document.forms['mantenimiento_tabla'].buscar_zona.value != ''){
		var v_zona = document.forms['mantenimiento_tabla'].buscar_zona.value;
	}else{
		var v_zona = document.forms['mantenimiento_tabla'].recupera_buscar_zona.value;
	}


	if(document.forms['mantenimiento_tabla'].buscar_categoria.value != ''){
		var v_categoria = document.forms['mantenimiento_tabla'].buscar_categoria.value;
	}else{
		var v_categoria = document.forms['mantenimiento_tabla'].recupera_buscar_categoria.value;
	}


	//alert(v_producto);
	//alert(v_origen+'-'+v_destino+'-'+v_producto+'-'+v_zona+'-'+v_categoria);

	$('#ajax_load_alojamiento')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reserva_Alojamiento.php",
				data: 'ciudad='+v_origen+'&destino='+v_destino+'&producto='+v_producto+'&zona='+v_zona+'&categoria='+v_categoria,
				dataType: "json",	//expect html to be returned
				success: function(msg){

					/*$('#buscar_fecha').val('');*/


					$('#buscar_alojamiento').append('<option value="">Todos los Alojamientos</option>');

					for (var i = 0; i < msg.length ; i++)
					{
						$('#buscar_alojamiento').append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
					}

					$("#buscar_alojamiento option[value='"+v_alojamiento+"']").attr("selected",true);

					$('#ajax_load_alojamiento')[0].style.display='none';

				}

			});
		//if(v_producto == 'VAC'){
			//carga_noches_Submit(v_destino,v_producto,v_fecha);
		//}else{
			//carga_fechas_regreso_Submit(v_destino,v_producto,v_fecha);
		//}

		//visualizar_secciones(v_producto);
		//visualizar_habitaciones();
}





//---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------
//FUNCIONES AJAX DEL BUSCADOR CUANDO SE ESTA EN LA PAGINA Y SE HACE ONCHANGE EN CADA CAMPO
//---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------



//---------------------------------
//YA EN USO EN BUSQUEDA VIAJES
//---------------------------------
function carga_cuadros(linea){

	var v_folleto = document.forms['mantenimiento_tabla']['Nuevofolleto'+linea].value;
	var v_tipo = document.forms['mantenimiento_tabla']['Nuevotipo'+linea].value;

	$('#Nuevocuadro'+linea)[0].style.width='165px';
	$('#ajax_load_cuadros'+linea)[0].style.display='block';

		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Ofertas_Cuadros.php",
				//data: 'ciudad='+$('#buscar_origen')[0].value+'&destino='+v_destino,
				data: 'folleto='+v_folleto+'&tipo='+v_tipo,
				dataType: "json",	//expect html to be returned
				success: function(msg){

					$('#Nuevocuadro'+linea).html('');
					//$('#buscar_origen').html('');

					$('#Nuevocuadro'+linea).append('<option value="">Seleccione cuadro</option>');

					for (var i = 0; i < msg.length ; i++)
					{
						$('#Nuevocuadro'+linea).append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
					}

					//$("#buscar_producto option[value='"+v_producto+"']").attr("selected",true);

					$('#ajax_load_cuadros'+linea)[0].style.display='none';
					$('#Nuevocuadro'+linea)[0].style.width='190px';
				}

			});
	Nuevosseleccionar(linea);
}

function carga_paquetes_servicios(linea){

	var v_tipo = document.forms['mantenimiento_tabla']['Nuevotipo'+linea].value;
	var v_folleto = document.forms['mantenimiento_tabla']['Nuevofolleto'+linea].value;
	var v_cuadro = document.forms['mantenimiento_tabla']['Nuevocuadro'+linea].value;

	$('#Nuevodescripcion'+linea)[0].style.width='265px';
	$('#ajax_load_descripcion'+linea)[0].style.display='block';

		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Ofertas_Paquetes_Servicios.php",
				//data: 'ciudad='+$('#buscar_origen')[0].value+'&destino='+v_destino,
				data: 'folleto='+v_folleto+'&cuadro='+v_cuadro+'&tipo='+v_tipo,
				dataType: "json",	//expect html to be returned
				success: function(msg){

					$('#Nuevodescripcion'+linea).html('');
					//$('#buscar_origen').html('');

					$('#Nuevodescripcion'+linea).append('<option value="">Seleccione opción</option>');

					for (var i = 0; i < msg.length ; i++)
					{
						$('#Nuevodescripcion'+linea).append('<option value="'+msg[i].codigo+'">'+msg[i].nombre+'</option>')
					}

					//$("#buscar_producto option[value='"+v_producto+"']").attr("selected",true);

					$('#ajax_load_descripcion'+linea)[0].style.display='none';
					$('#Nuevodescripcion'+linea)[0].style.width='295px';
				}

			});
	Nuevosseleccionar(linea);

}


