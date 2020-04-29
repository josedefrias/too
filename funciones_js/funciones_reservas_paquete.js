
function realizar_reserva()
{
	document.forms['mantenimiento_tabla'].reservar.value = 'S';
	document.forms['mantenimiento_tabla'].ir_a.value = 'R';
	document.forms['mantenimiento_tabla'].submit();
}

function seleccionar_agencia_reserva_paquetes()
{
	document.forms['mantenimiento_tabla'].clave_agencia_recuperada.value = document.forms['mantenimiento_tabla'].clave_agencia.value;

}

function visualizar_habitaciones()
{

	if(document.forms['mantenimiento_tabla'].cantidad_habitaciones.value == 1 || document.forms['mantenimiento_tabla'].cantidad_habitaciones.value == 0){
		habitacion2.style.display='none';
		habitacion3.style.display='none';
		habitacion4.style.display='none';
	}else if(document.forms['mantenimiento_tabla'].cantidad_habitaciones.value == 2){
		habitacion2.style.display='block';
		habitacion3.style.display='none';
		habitacion4.style.display='none';
	}else if(document.forms['mantenimiento_tabla'].cantidad_habitaciones.value == 3){
		habitacion2.style.display='block';
		habitacion3.style.display='block';
		habitacion4.style.display='none';
	}else if(document.forms['mantenimiento_tabla'].cantidad_habitaciones.value == 4){
		habitacion2.style.display='block';
		habitacion3.style.display='block';
		habitacion4.style.display='block';
	}
	visualizar_pasajeros();
}

function visualizar_pasajeros()
{
	if(document.forms['mantenimiento_tabla'].producto.value != 'SVO' &&  document.forms['mantenimiento_tabla'].producto.value != 'OSV' &&  document.forms['mantenimiento_tabla'].producto.value != 'SSV'){
		
		for(var k=1; k<=9; k++){
				pasajero = document.getElementById('pasajero'+k);
				pasajero.style.display='none';	
				document.forms['mantenimiento_tabla']['pasajero'+k+'_tipo'].value = 'A';
				document.forms['mantenimiento_tabla']['pasajero'+k+'_habitacion'].value = '0';
		}

		if(document.forms['mantenimiento_tabla'].cantidad_habitaciones.value > 0){

			var contador = 0;
			for(var k=1; k<=document.forms['mantenimiento_tabla'].cantidad_habitaciones.value; k++){
				
				var contador_pax_habitacion = 0;
				for(var j=0; j<document.forms['mantenimiento_tabla']['habitacion'+k+'_adultos'].value; j++){
					contador++;
					contador_pax_habitacion++;

					document.forms['mantenimiento_tabla']['pasajero'+contador+'_habitacion'].value = k;
					if(document.forms['mantenimiento_tabla']['habitacion'+k+'_novios'].value == 'S'){
						document.forms['mantenimiento_tabla']['pasajero'+contador+'_tipo'].value  = 'V';
					}

					if(document.forms['mantenimiento_tabla']['habitacion'+k+'_jubilados'].value > 0){
						if(contador_pax_habitacion <= document.forms['mantenimiento_tabla']['habitacion'+k+'_jubilados'].value){
							document.forms['mantenimiento_tabla']['pasajero'+contador+'_tipo'].value  = 'J';
						}
					}
					//document.forms['mantenimiento_tabla']['pasajero'+contador+'_edad'].value  = 30;

					pasajero = document.getElementById('pasajero'+contador);
					pasajero.style.display='block';

					edad = document.getElementById('edad'+contador);
					edad.style.display='none';



				}

				for(var j=0; j<document.forms['mantenimiento_tabla']['habitacion'+k+'_ninos'].value; j++){
					contador++;

					document.forms['mantenimiento_tabla']['pasajero'+contador+'_habitacion'].value = k;
					document.forms['mantenimiento_tabla']['pasajero'+contador+'_tipo'].value = 'N';

					pasajero = document.getElementById('pasajero'+contador);
					pasajero.style.display='block';

					edad = document.getElementById('edad'+contador);
					edad.style.display='block';

				}

				for(var j=0; j<document.forms['mantenimiento_tabla']['habitacion'+k+'_bebes'].value; j++){
					contador++;

					document.forms['mantenimiento_tabla']['pasajero'+contador+'_habitacion'].value = k;
					document.forms['mantenimiento_tabla']['pasajero'+contador+'_tipo'].value = 'B';

					pasajero = document.getElementById('pasajero'+contador);
					pasajero.style.display='block';

					edad = document.getElementById('edad'+contador);
					edad.style.display='block';

				}


			}
		}

	}else{
		for(var k=1; k<=9; k++){
				pasajero = document.getElementById('pasajero'+k);
				pasajero.style.display='none';	
				document.forms['mantenimiento_tabla']['pasajero'+k+'_tipo'].value = 'A';
				document.forms['mantenimiento_tabla']['pasajero'+k+'_habitacion'].value = '0';
		}

		if(document.forms['mantenimiento_tabla'].solo_vuelo_adultos.value > 0 || document.forms['mantenimiento_tabla'].solo_vuelo_ninos.value > 0){

			var contador = 0;


				for(var j=0; j<document.forms['mantenimiento_tabla'].solo_vuelo_adultos.value; j++){
					contador++;


					pasajero = document.getElementById('pasajero'+contador);
					pasajero.style.display='block';

					edad = document.getElementById('edad'+contador);
					edad.style.display='none';


				}

				for(var j=0; j<document.forms['mantenimiento_tabla'].solo_vuelo_ninos.value; j++){
					contador++;


					document.forms['mantenimiento_tabla']['pasajero'+contador+'_tipo'].value = 'N';

					pasajero = document.getElementById('pasajero'+contador);
					pasajero.style.display='block';

					edad = document.getElementById('edad'+contador);
					edad.style.display='block';

				}

		}

	}

}
