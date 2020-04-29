

<h1>Indique el dato que desea confirmar</h1>
<br/><br/>

<form id="cr">
	<label>Número de referencia:</label>
	<input id="ref" type="text"></input>
	<a class="button go_search" onclick="checkReference1()" href="#">Comprobar</a>
	<img id="ajax_load" src="ajax_load.gif" style="display:none"/>
</form>

<br/><br/><br/>

<div id="pageContent"></div>

<script type="text/javascript">
function checkReference1(){
	$('#ajax_load')[0].style.display='block';
		$.ajax({	//create an ajax request to load_page.php
				type: "POST",
				url: "actions.php",
				data: 'ref='+$('#ref')[0].value,
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

function checkReference1(){
	var theForm = $("#cr");
	var params = theForm.serialize();
	
	$('#ajax_load')[0].style.display='block';
	
		$.ajax({	
				type: "POST",
				url: "actions.php",
				data: params,
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
</script>
