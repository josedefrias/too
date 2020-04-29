
<?php

	function getData($ref)
	{
		
		$result = Db::getInstance()->ExecuteS('

		SELECT DISTINCT o.id_order.....
		
		');

		return $result;
		
	}
$results = getData($_POST['ref']);

if(sizeof($results) == 0){
	echo "No resultados";
}else{
	for ($i = 0; $i < sizeof($results); $i ++){
		$p = $results[$i];
		echo $p['atributo1']." X ".$p['atributo2']."<br/><br/>";
	}
}
?>
