

function Cargar_busqueda_agencias(){
	$('#ajax_load')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Reservas_Agencias.php",
				data: 'nombre_agencia='+$('#nombre_agencia')[0].value+'&direccion_agencia='+$('#direccion_agencia')[0].value,
				dataType: "html",	//expect html to be returned
				success: function(msg){
					$('#ajax_load')[0].style.display='none';
					if(parseInt(msg)!=0)	//if no errors
					{
						$('#pageContent').html(msg);	//load the returned html into pageContet
					}
				}

			});
}


