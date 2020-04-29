
function carga_ciudades(origen_destino, linea, key_event){
	
	//alert(origen_destino+'-'+linea+'-'+key_event);

	//Usar el campo del formulario ultima tecla para cuando se cambia la direccion del cursor

	Nuevosseleccionar(linea);

		//alert(key_event.keyCode);

		if(key_event.keyCode == 40){

			if(parseInt(document.forms['mantenimiento_tabla']['ultima_tecla'].value) == 38){
				if(lineacursor == 0){
					lineacursor = parseInt(document.forms['mantenimiento_tabla']['Nuevo'+origen_destino+'_numerocursor'+linea].value) + 1; 
				}else{
					lineacursor = parseInt(document.forms['mantenimiento_tabla']['Nuevo'+origen_destino+'_numerocursor'+linea].value) + 2; 
				}
			}else{
				lineacursor = parseInt(document.forms['mantenimiento_tabla']['Nuevo'+origen_destino+'_numerocursor'+linea].value);
			}
			//pulsar('+origen_destino+'_destino, linea, key_event, lineacursor);

			$("[id*=linea_"+origen_destino+"_caja]").css('background-color', '#FFFFFF');
                          $("[id*=linea_"+origen_destino+"_caja]").css('color', '#0C2E8A');

			if(lineacursor == 0){
			   	idElemento = 'linea_'+origen_destino+'_caja'+linea+'0';

			      	document.getElementById(idElemento).focus();

				$("#linea_"+origen_destino+"_caja"+linea+'0').css('background-color', '#0C2E8A');
				$("#linea_"+origen_destino+"_caja"+linea+'0').css('color', '#FFFFFF');
				document.forms['mantenimiento_tabla']['Nuevo'+origen_destino+'_numerocursor'+linea].value = lineacursor + 1;
			}else{
				

				$("#linea_"+origen_destino+"_caja"+linea+lineacursor).css('background-color', '#0C2E8A');
				$("#linea_"+origen_destino+"_caja"+linea+lineacursor).css('color', '#FFFFFF');

				document.forms['mantenimiento_tabla']['Nuevo'+origen_destino+'_numerocursor'+linea].value = lineacursor + 1;
			}

			document.forms['mantenimiento_tabla']['ultima_tecla'].value = 40;

		}else if(key_event.keyCode == 38){

			if(parseInt(document.forms['mantenimiento_tabla']['ultima_tecla'].value) == 40){
				if(lineacursor == 0){
					lineacursor = parseInt(document.forms['mantenimiento_tabla']['Nuevo'+origen_destino+'_numerocursor'+linea].value) - 1; 
				}else{
					lineacursor = parseInt(document.forms['mantenimiento_tabla']['Nuevo'+origen_destino+'_numerocursor'+linea].value) - 2; 
				}
			}else{
				lineacursor = parseInt(document.forms['mantenimiento_tabla']['Nuevo'+origen_destino+'_numerocursor'+linea].value);
			}

			$("[id*=linea_"+origen_destino+"_caja]").css('background-color', '#FFFFFF');
                          $("[id*=linea_"+origen_destino+"_caja]").css('color', '#0C2E8A');

			if(lineacursor == 0){
			   	idElemento = 'linea_'+origen_destino+'_caja'+linea+'0';

			      	document.getElementById(idElemento).focus();

				$("#linea_"+origen_destino+"_caja"+linea+'0').css('background-color', '#0C2E8A');
				$("#linea_"+origen_destino+"_caja"+linea+'0').css('color', '#FFFFFF');
			}else{
				
				$("#linea_"+origen_destino+"_caja"+linea+lineacursor).css('background-color', '#0C2E8A');
				$("#linea_"+origen_destino+"_caja"+linea+lineacursor).css('color', '#FFFFFF');

				document.forms['mantenimiento_tabla']['Nuevo'+origen_destino+'_numerocursor'+linea].value = lineacursor - 1;
			}

			document.forms['mantenimiento_tabla']['ultima_tecla'].value = 38;


		}else if(key_event.keyCode == 13){
		   	document.forms['mantenimiento_tabla']['Nuevo'+origen_destino+''+linea].value = document.forms['mantenimiento_tabla']['linea_'+origen_destino+'_nombre'+linea+lineacursor].value; 
			document.forms['mantenimiento_tabla']['Nuevocod_'+origen_destino+''+linea].value = document.forms['mantenimiento_tabla']['linea_'+origen_destino+'_codigo'+linea+lineacursor].value; 
			document.forms['mantenimiento_tabla']['Nuevo'+origen_destino+'_old'+linea].value = document.forms['mantenimiento_tabla']['linea_'+origen_destino+'_nombre'+linea+lineacursor].value; 
			document.forms['mantenimiento_tabla']['Nuevocod_'+origen_destino+'_old'+linea].value = document.forms['mantenimiento_tabla']['linea_'+origen_destino+'_codigo'+linea+lineacursor].value; 
			oculta_cajas();

			document.forms['mantenimiento_tabla']['ultima_tecla'].value = 13;

		}else{
			


			if(origen_destino == 'origen'){

				var nombre_buscar = document.forms['mantenimiento_tabla']['Nuevoorigen'+linea].value;
				var caracteres = nombre_buscar.length;

				if(caracteres >= 3){

					$.ajax({	//create an ajax request to load_page.php
						type: "POST",
						url: "funciones_php_ajax/buscar_ciudades.php",
						//data: 'ciudad='+$('#buscar_origen')[0].value+'&destino='+v_destino,
						data: 'nombre='+nombre_buscar,
						dataType: "json",	//expect html to be returned
						success: function(msg){

							$('#resultado_origen'+linea).html('');

							if(msg.length > 0){

								for (var i = 0; i < msg.length ; i++)
								{
									seleccionar = "onClick=Seleccionar('"+origen_destino+"',"+linea+","+i+")";

									$('#resultado_origen'+linea).append("<div id="+"'linea_origen_caja"+linea+i+"' name="+"'linea_origen_caja"+linea+i+"' class='ciudad_div' style='display:block; width:180px; height:20px; margin-left: 0px; top: 0px; padding: 1px 2px; position: relative; z-index: 999; font-size:0.9em; background-color: #FFFFFF; color: #0C2E8A;cursor:pointer; hover {background: yellow}' "+seleccionar+"><input type= 'hidden' name= 'linea_origen_codigo"+linea+i+"' value='"+msg[i].codigo+"'><input type= 'hidden' name= 'linea_origen_nombre"+linea+i+"' value='"+msg[i].nombre+"'>"+msg[i].nombre+"</div>");
								}
							}else{
									$('#resultado_origen').append("<div id='linea_nombre0' name='linea_nombre0' style='display:none;margin-left: 150px; top: 1px; padding: 0px 0px; position: relative; width:50px;z-index: 500; font-size:1.4em;background-color: #FFFFFF;color: #0C2E8A;-moz-border-radius: 10px;border-radius: 0px;border:solid 2.5px #0C2E8A;' >No se han encontrado resultados</div>");
							}
						}
					});

					//resultado_origen+linea.style.display='block';
					document.forms['mantenimiento_tabla']['Nuevoorigen_numerocursor'+linea].value = 0;
					lineacursor = 0;
					$("#resultado_origen"+linea).css("display", "block");


				}


			}else{
				//alert('destino');
				var nombre_buscar = document.forms['mantenimiento_tabla']['Nuevodestino'+linea].value;
				var caracteres = nombre_buscar.length;

				if(caracteres >= 3){

					$.ajax({	//create an ajax request to load_page.php
						type: "POST",
						url: "funciones_php_ajax/buscar_ciudades.php",
						//data: 'ciudad='+$('#buscar_origen')[0].value+'&destino='+v_destino,
						data: 'nombre='+nombre_buscar,
						dataType: "json",	//expect html to be returned
						success: function(msg){

							$('#resultado_destino'+linea).html('');

							if(msg.length > 0){

								for (var i = 0; i < msg.length ; i++)
								{
									seleccionar = "onClick=Seleccionar('"+origen_destino+"',"+linea+","+i+")";

									$('#resultado_destino'+linea).append("<div id="+"'linea_destino_caja"+linea+i+"' name="+"'linea_destino_caja"+linea+i+"' class='ciudad_div' style='display:block; width:180px; height:20px; margin-left: 0px; top: 0px; padding: 1px 2px; position: relative; z-index: 999; font-size:0.9em; background-color: #FFFFFF; color: #0C2E8A;cursor:pointer; hover {background: yellow}' "+seleccionar+"><input type= 'hidden' name= 'linea_destino_codigo"+linea+i+"' value='"+msg[i].codigo+"'><input type= 'hidden' name= 'linea_destino_nombre"+linea+i+"' value='"+msg[i].nombre+"'>"+msg[i].nombre+"</div>");
								}
							}else{
									$('#resultado_destino').append("<div id='linea_nombre0' name='linea_nombre0' style='display:none;margin-left: 150px; top: 1px; padding: 0px 0px; position: relative; width:50px;z-index: 500; font-size:1.4em;background-color: #FFFFFF;color: #0C2E8A;-moz-border-radius: 10px;border-radius: 0px;border:solid 2.5px #0C2E8A;' >No se han encontrado resultados</div>");
							}
						}
					});

					//resultado_origen+linea.style.display='block';
					document.forms['mantenimiento_tabla']['Nuevodestino_numerocursor'+linea].value = 0;
					lineacursor = 0;
					$("#resultado_destino"+linea).css("display", "block");


				}


			}

			document.forms['mantenimiento_tabla']['ultima_tecla'].value = 0;
		}




}

function Seleccionar(origen_destino, linea, linea_div){

	//alert(origen_destino+'-'+linea+'-'+linea_div);

	if(origen_destino == 'origen'){

		document.forms['mantenimiento_tabla']['Nuevoorigen'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_nombre'+linea+linea_div].value; 
		document.forms['mantenimiento_tabla']['Nuevocod_origen'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_codigo'+linea+linea_div].value; 
		document.forms['mantenimiento_tabla']['Nuevoorigen_old'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_nombre'+linea+linea_div].value; 
		document.forms['mantenimiento_tabla']['Nuevocod_origen_old'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_codigo'+linea+linea_div].value;
		$("#resultado_origen"+linea).css("display", "none");

	}else{

		document.forms['mantenimiento_tabla']['Nuevodestino'+linea].value = document.forms['mantenimiento_tabla']['linea_destino_nombre'+linea+linea_div].value; 
		document.forms['mantenimiento_tabla']['Nuevocod_destino'+linea].value = document.forms['mantenimiento_tabla']['linea_destino_codigo'+linea+linea_div].value; 
		document.forms['mantenimiento_tabla']['Nuevodestino_old'+linea].value = document.forms['mantenimiento_tabla']['linea_destino_nombre'+linea+linea_div].value; 
		document.forms['mantenimiento_tabla']['Nuevocod_destino_old'+linea].value = document.forms['mantenimiento_tabla']['linea_destino_codigo'+linea+linea_div].value; 
		$("#resultado_destino"+linea).css("display", "none");

	}

	//window.scrollTo(0,0);

}

function validar_salida(origen_destino, linea){

	

	if(origen_destino == 'origen' && document.forms['mantenimiento_tabla']['Nuevoorigen'+linea].value !=''){

		//alert(linea+'-'+origen_destino+'-'+document.forms['mantenimiento_tabla']['Nuevocod_origen'+linea].value);

		if(document.forms['mantenimiento_tabla']['Nuevocod_origen'+linea].value == '' ){

			document.forms['mantenimiento_tabla']['Nuevoorigen'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_nombre'+linea+'0'].value; 
			document.forms['mantenimiento_tabla']['Nuevocod_origen'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_codigo'+linea+'0'].value; 
			document.forms['mantenimiento_tabla']['Nuevoorigen_old'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_nombre'+linea+'0'].value; 
			document.forms['mantenimiento_tabla']['Nuevocod_origen_old'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_codigo'+linea+'0'].value; 
		}else{
			if(document.forms['mantenimiento_tabla']['Nuevoorigen'+linea].value != document.forms['mantenimiento_tabla']['Nuevoorigen_old'+linea].value){
				document.forms['mantenimiento_tabla']['Nuevoorigen'+linea].value = document.forms['mantenimiento_tabla']['Nuevoorigen_old'+linea].value; 
				document.forms['mantenimiento_tabla']['Nuevocod_origen'+linea].value = document.forms['mantenimiento_tabla']['Nuevocod_origen_old'+linea].value; 	
			}
		}

		//$("#resultado_origen"+linea).css("display", "none");

	}

	if(origen_destino == 'destino' && document.forms['mantenimiento_tabla']['Nuevodestino'+linea].value !=''){
		//alert('destino');

		if(document.forms['mantenimiento_tabla']['Nuevocod_destino'+linea].value == ''){

			document.forms['mantenimiento_tabla']['Nuevodestino'+linea].value = document.forms['mantenimiento_tabla']['linea_destino_nombre'+linea+'0'].value; 
			document.forms['mantenimiento_tabla']['Nuevocod_destino'+linea].value = document.forms['mantenimiento_tabla']['linea_destino_codigo'+linea+'0'].value; 
			document.forms['mantenimiento_tabla']['Nuevodestino_old'+linea].value = document.forms['mantenimiento_tabla']['linea_destino_nombre'+linea+'0'].value; 
			document.forms['mantenimiento_tabla']['Nuevocod_destino_old'+linea].value = document.forms['mantenimiento_tabla']['linea_destino_codigo'+linea+'0'].value;
		}else{
			if(document.forms['mantenimiento_tabla']['Nuevodestino'+linea].value != document.forms['mantenimiento_tabla']['Nuevodestino_old'+linea].value){
				document.forms['mantenimiento_tabla']['Nuevodestino'+linea].value = document.forms['mantenimiento_tabla']['Nuevodestino_old'+linea].value; 
				document.forms['mantenimiento_tabla']['Nuevocod_destino'+linea].value = document.forms['mantenimiento_tabla']['Nuevocod_destino_old'+linea].value; 	
			}
		}

		//$("#resultado_destino"+linea).css("display", "none");

	}


}

function oculta_cajas(){
	$("[id*=resultado_origen]").hide();
           $("[id*=resultado_destino]").hide();
}

function oculta_caja_destino(){
         $("[id*=resultado_destino]").hide();
}

function oculta_caja_origen(){
	$("[id*=resultado_origen]").hide();
}

function mostrar_cajas(origen_destino, linea){

	if(origen_destino == 'origen'){
		$("#resultado_origen"+linea).css("display", "block");
	}else{
	         $("#resultado_destino"+linea).css("display", "block");
	}

}


function pulsar(origen_destino, linea, e, lineacursor) {

  switch (e.keyCode) {

    case 13: 

   	document.forms['mantenimiento_tabla']['Nuevoorigen'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_nombre'+linea+lineacursor].value; 
	document.forms['mantenimiento_tabla']['Nuevocod_origen'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_codigo'+linea+lineacursor].value; 
	document.forms['mantenimiento_tabla']['Nuevoorigen_old'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_nombre'+linea+lineacursor].value; 
	document.forms['mantenimiento_tabla']['Nuevocod_origen_old'+linea].value = document.forms['mantenimiento_tabla']['linea_origen_codigo'+linea+lineacursor].value; 
	oculta_cajas();

	break;

    case 37: alert('Izquierda');
      break;

    case 38: alert('Arriba');
      break;

    case 39: alert('Derecha');
      break;

    case 40: /*alert('Abajo');*/
    	if(lineacursor == 0){
	   	idElemento = 'linea_origen_caja'+linea+'0';

	      	document.getElementById(idElemento).focus();

		$("#linea_origen_caja"+linea+'0').css('background-color', '#0C2E8A');
		$("#linea_origen_caja"+linea+'0').css('color', '#FFFFFF');
		//document.forms['mantenimiento_tabla']['Nuevoorigen_numerocursor'+linea].value = document.forms['mantenimiento_tabla']['Nuevoorigen_numerocursor'+linea].value + 1;
	}else{
		
		//alert(lineacursor);
		document.forms['mantenimiento_tabla']['Nuevoorigen_numerocursor'+linea].value = document.forms['mantenimiento_tabla']['Nuevoorigen_numerocursor'+linea].value + 1;
	}
	break;


  }
}

