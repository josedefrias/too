<?php

	include 'funciones_php/conexiones.php';

	//$parametrosg = $_GET;
	//$codigo = $parametrosg['codigo'];


	//XML Request
	$xml = "codigousu=" . 'QDZN';
	$xml .= "&clausu=" . 'xml476346';
	$xml .= "&afiliacio=" . 'RS';
	$xml .= "&secacc=" . '110592';
	$xml .= "&xml=";
	$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$xml2 .= "<peticion>\n";
	$xml2 .= "<tipo>110</tipo>\n";
	$xml2 .= "<nombre>Petición de Disponibilidad</nombre>\n";
	$xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
	$xml2 .= "<parametros>\n";

		$xml2 .= "<hotel>104248#967359#958543#918714#912878#919485#107942#958620#915595#864073#792389#686341#</hotel>"; //Para consultar varios hoteles  por código en vez de por nobre hay que poner cada código seguido de # 
		$xml2 .= "<pais>ES</pais>";
		$xml2 .= "<provincia>ESTCI</provincia>";

		$xml2 .= "<poblacion></poblacion>";

		$xml2 .= "<categoria></categoria>";
		$xml2 .= "<radio>9</radio>";

		$xml2 .= "<fechaentrada>05/10/2016</fechaentrada>";
		$xml2 .= "<fechasalida>05/15/2016</fechasalida>";

		$xml2 .= "<afiliacion>RS</afiliacion>";
		$xml2 .= "<usuario>D61366</usuario>";

		$xml2 .= "<numhab1>1</numhab1>"; //Número de habitaciones tipo 1.
		$xml2 .= "<paxes1>2-0</paxes1>"; //Formato: adulto-niño. Ejem: Para pedir dos adultos y un niño sería: 2-1.

		//$xml2 .= "<numhab2>".$codigo."</numhab2>";
		//$xml2 .= "<paxes2>".$codigo."</paxes2>";

		//$xml2 .= "<paxes2>".$codigo."</paxes2>";
		//$xml2 .= "<paxes2>".$codigo."</paxes2>";

		$xml2 .= "<restricciones>1</restricciones>";
		//Opción para mostrar los hoteles con restricciones (estancias mínimas).

		$xml2 .= "<idioma>1</idioma>";

		$xml2 .= "<duplicidad>1</duplicidad>";   
		//La duplicidad nos servirá para filtrar duplicados, es decir, en ciertas ocasiones, un hotel nos puede ofrecer diferentes ofertas, si queremos que aparezcan todas ellas en el listado bastará con no incluir este tag o dejarlo a cero, si queremos que en el listado aparezca sólo la mejor de las ofertas dejaremos el tag en valor 1 (el criterio de mejor oferta se basa en este orden: mejor disponibilidad/mejor precio de la primera habitacion/regimen que encuentre).

		$xml2 .= "<comprimido>2</comprimido>"; 
		$xml2 .= "<informacion_hotel>0</informacion_hotel>"; 
		//$xml2 .= "<tarifas_reembolsables>1</tarifas_reembolsables>"; 

		
	$xml2 .= "</parametros>\n";
	$xml2 .= "</peticion>";
	$xml .= urlencode($xml2);
	$length = strlen($xml);


	//XML Connection
	$fp = @fsockopen("xml.hotelresb2b.com", 80);
	fputs($fp, "POST http://xml.hotelresb2b.com/xml/listen_xml.jsp HTTP/1.0\nUser-Agent: PHP XMLRPC
	1.1\r\n");
	fputs($fp, "Host: xml.hotelresb2b.com\n");
	fputs($fp, "Content-Type: application/x-www-form-urlencoded\n");
	fputs($fp, "Content-Length: " . $length . "\n");
	fputs($fp, "\n");
	fputs($fp, $xml);
	$respuesta = "";
	while(!feof($fp)) $respuesta .= fgets($fp);
	fclose ($fp);

	//XML Answer
	$xml = substr($respuesta, strpos($respuesta, "<?xml"));
	//$xml = new SimpleXMLElement($xmlstr); //Simple XML is available from php5
	$xml = new SimpleXMLElement($xml);
	$no_disp = $xml->param->hotls->attributes()->num;



	//echo($xml2);

	//echo($xml->param->error->num_error);
	//echo($xml->param->error->descripcion);
	//echo('Respuestas: '.$xml->param->hotls->attributes()->num.'<br>');

	//$atributos = $xml->param->hotls->attributes();
	//echo "Numero: " .$atributos['num'] . "<br>";

	//echo($xml->param->hotls->hot[0]->nom);

	/*echo('<pre>');
	print_r($xml->param);
	echo('</pre>');*/



	$datos_dispo = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 5.0 Transitional//EN'>
	<HTML>
	  <link rel='StyleSheet' href='css/interfaces_emergente.css'>
	 <HEAD>
	  <meta charset='utf-8'>
	  <TITLE> Menú </TITLE>
	  <META NAME='Generator' CONTENT='EditPlus'>
	  <META NAME='Author' CONTENT='Jose de Frias'>
	  <META NAME='Keywords' CONTENT=''>
	  <META NAME='Description' CONTENT='>
	  <script type='text/javascript' src='funciones_js/funciones.js'></script>
	 </HEAD>
	<body background='imagenes/fondo.jpg'>";



	for($i=0;$i<$no_disp;$i++)
	{
		$datos_dispo .= '';
		$dispo = $xml->param->hotls->hot[$i];



		        //$datos_dispo .= "<strong>RESPUESTA (".count($dispo->res->pax->hab).")</strong><BR><BR>";


		        $datos_dispo .= "<BR><BR><strong>".$dispo->nom."</strong> (".count($dispo->res->pax->hab)." Tipos de Habitacion)<BR><BR>";
		        $datos_dispo .= "<TABLE BORDER=1><TR><TD>COD</TD><TD>AFI</TD><TD align='center'>NOM</TD><TD align='center'>PRO</TD><TD align='center'>PRN</TD><TD align='center'>POB</TD><TD align='center'>CAT</TD><TD align='center'>FEN</TD><TD align='center'>FSAL</TD><TD align='center'>PDR</TD><TD align='center'>CAL</TD><TD align='center'>MAR</TD></TR>";

		        $datos_dispo .= "<TR><TD>".$dispo->cod."</TD>";
		        $datos_dispo .= "<TD>".$dispo->afi."</TD>";
		        $datos_dispo .= "<TD align='center'>".$dispo->nom."</TD>";
		        $datos_dispo .= "<TD align='center'>".$dispo->pro."</TD>";
		        $datos_dispo .= "<TD align='center'>".$dispo->prn."</TD>";
		        $datos_dispo .= "<TD align='center'>".$dispo->pob."</TD>";
	                    $datos_dispo .= "<TD align='center'>".$dispo->cat."</TD>";
	                    $datos_dispo .= "<TD align='center'>".$dispo->fen."</TD>";
	                    $datos_dispo .= "<TD align='center'>".$dispo->fsa."</TD>";
	                    $datos_dispo .= "<TD align='center'>".$dispo->pdr."</TD>";
	                    $datos_dispo .= "<TD align='center'>".$dispo->cal."</TD>";
		        $datos_dispo .= "<TD align='center'>".$dispo->mar."</TD></TR>";

		        $datos_dispo .= '</TABLE>';




		        $datos_dispo .= "<BR><DIV style='margin: 0px 40px;'><strong>HABITACIONES</strong></DIV><BR>";
		        $datos_dispo .= "<TABLE BORDER=1 style='margin: 0px 40px;'>";
		        $no_hab = count($dispo->res->pax->hab);

		       for($j=0;$j<$no_hab;$j++){

		        	       $habit = $dispo->res->pax->hab[$j];

			        $datos_dispo .= "<TR><TD>".$habit ->attributes()->cod."</TD>";
			        
			        $datos_dispo .= "<TD align='left'>".$habit ->attributes()->desc."</TD>";

			        $datos_dispo .= "<TD>REG</TD><TD>PRECIO</TD><TD>DIV</TD><TD>ESTADO</TD><TD>PVP MIN</TD><TD>GASTOS</TD></TR>";


			        $no_reg = count($habit->reg);

		       	        for($k=0;$k<$no_reg;$k++){

		       	        	$regi = $habit->reg[$k];

				$datos_dispo .= "<TR><TD></TD><TD></TD><TD>".$regi ->attributes()->cod."</TD>";
				        
				 $datos_dispo .= "<TD align='left'>".$regi ->attributes()->prr."</TD>";
				 $datos_dispo .= "<TD align='left'>".$regi ->attributes()->div."</TD>";
				 $datos_dispo .= "<TD align='left'>".$regi ->attributes()->esr."</TD>";
				 $datos_dispo .= "<TD align='left'>".$regi ->attributes()->pvp."</TD>";

				 if($regi ->attributes()->nr == 0){
				 	$gastos = 'Sin gastos Restrictivos';
				 }elseif($regi ->attributes()->nr == 1){
				 	$gastos = 'Tarifa con 100%';
				 }else{
				 	$gastos = 'La reserva ya entra en gastos';
				 }
				 
				 $datos_dispo .= "<TD align='left'>".$gastos."</TD>";

				$num_lin = count($habit->reg->lin);
				$datos_dispo .= "<TD align='left'>".$num_lin."</TD>";
				for($k=0;$k<$num_lin;$k++){
					$datos_dispo .= "<TD align='left'>".$regi ->lin[$k]."</TD>";
				}
				 $datos_dispo .= "</TR>";
		       	        }

		        	       /*echo('<pre>');
			        print_r($habit);
			       echo('</pre>');*/


		        }
		        $datos_dispo .= '</TABLE>';

	}

	$datos_dispo .= '</BODY></HTML>';


	echo($datos_dispo);

?>


