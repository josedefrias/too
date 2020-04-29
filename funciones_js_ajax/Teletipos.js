//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//FUNCIONES AJAX DE MIS RESERVAS CUANDO SE HACE SUBMIT O SE VIENE DE OTRA PAGINAÑ
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------
//---------------------------------------------------------------------------

function Cargar_inicio_Teletipos(provincia,grupo,minorista){
		carga_minoristas_submit(provincia,grupo,minorista);
}

function carga_minoristas_submit(v_provincia,v_grupo,v_minorista){
	//alert(v_provincia+v_grupo+v_minorista);
	$('#ajax_load_minoristas')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "funciones_php_ajax/Teletipos_Minoristas.php",
				data: 'provincia='+v_provincia+'&grupo_gestion='+v_grupo,

				dataType: "json",	//expect html to be returned
				success: function(msg){

					$('#buscar_minorista').html('');

					$('#buscar_minorista').append('<option value=""></option>');
					
					for (var i = 0; i < msg.length ; i++)
					{
						$('#buscar_minorista').append('<option value="'+msg[i].id+'">'+msg[i].nombre+'</option>')
					}

					$("#buscar_minorista option[value='"+v_minorista+"']").attr("selected",true);

					$('#ajax_load_minoristas')[0].style.display='none';
				}
			});
}


function carga_minoristas(){
	
	v_provincia = document.forms['mantenimiento_tabla'].buscar_provincia.value;
	v_grupo = document.forms['mantenimiento_tabla'].buscar_grupo_gestion.value;
	//alert(v_provincia+v_grupo);
	$('#ajax_load_minoristas')[0].style.display='block';
	
		$.ajax({	//create an ajax request to load_page.php
				
				type: "POST",
				url: "funciones_php_ajax/Teletipos_Minoristas.php",
				data: 'provincia='+v_provincia+'&grupo_gestion='+v_grupo,
				
				dataType: "json",	//expect html to be returned
				success: function(msg){

					$('#buscar_minorista').html('');

					$('#buscar_minorista').append('<option value=""></option>');
					
					for (var i = 0; i < msg.length ; i++)
					{
						$('#buscar_minorista').append('<option value="'+msg[i].id+'">'+msg[i].nombre+'</option>')
					}

					$('#ajax_load_minoristas')[0].style.display='none';
		}
	 });
	 
}









