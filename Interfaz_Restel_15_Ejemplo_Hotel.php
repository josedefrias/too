<?php

	//$provincia = 'ESSOR';
	$provincia = 'FRDIS';
	//$provincia = 'FRPAR';

	//XML Request
	$xml = "codigousu=" . 'QDZN';
	$xml .= "&clausu=" . 'xml476346';
	$xml .= "&afiliacio=" . 'RS';
	$xml .= "&secacc=" . '110592';
	$xml .= "&xml=";
	$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$xml2 .= "<peticion>\n";
	$xml2 .= "<tipo>15</tipo>\n";
	$xml2 .= "<nombre>Petición de Lista de Hoteles</nombre>\n";
	$xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
	$xml2 .= "<parametros>\n";
		$xml2 .= "<codigo>746438</codigo>";
		//$xml2 .= "<codigo>643548	</codigo>";
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
	$no_hotels = count($xml->parametros->hotel);
	//ECHO($respuesta.'<br><br><br>');
	//ECHO($no_hotels.'<br><br><br>');
	for($i=0;$i<$no_hotels;$i++)
	{
		$datos_hotel = '';
		$hotel = $xml->parametros->hotel[$i];

		        $datos_hotel = 'PAIS:'.$hotel->pais.'<br>';
		        $datos_hotel .= 'CODIGO HOTEL:'.$hotel->codigo_hotel.'<br>';
		        $datos_hotel .= 'CODIGO INTERNO:'.$hotel->codigo.'<br>';
		        $datos_hotel .= 'AFILIACION:'.$hotel->hot_afiliacion.'<br>';
		        $datos_hotel .= '<strong>NOMBRE:'.$hotel->nombre_h.'</strong><br>';
		        $datos_hotel .=  'CATEGORIA:'.$hotel->categoria.'<br>';
		        $datos_hotel .= 'DIRECCION:'.$hotel->direccion.'<br>';
		        $datos_hotel .= 'COD PROVINCIA:'.$hotel->codprovincia.'<br>';
		        $datos_hotel .= 'NOMBRE PROVINCIA:'.$hotel->provincia.'<br>';
		        $datos_hotel .=  'COD POBLACION:'.$hotel->codpoblacion.'<br>';
		        $datos_hotel .= 'POBLACION:'.$hotel->poblacion.'<br>';
		        $datos_hotel .= 'CODIGO POSTAL:'.$hotel->cp.'<br>';
		        $datos_hotel .= 'COD DUPLICADO:'.$hotel->coddup.'<br>';
		        $datos_hotel .= 'MAIL:'.$hotel->mail.'<br>';
		        $datos_hotel .= 'WEB:'.$hotel->web.'<br>';

		        for($j=0;$j<=20;$j++){
		        	if($hotel->fotos->foto[$j] != ''){
			        $datos_hotel .= 'URL FOTO '.$j.':'.$hotel->fotos->foto[$j].'<br>';
			        $datos_hotel .= 'FOTO '.$j.':<img src=\''.$hotel->fotos->foto[$j].'\'</img><br>';
			 }
		        }

		        $datos_hotel .= 'URL PLANO:'.$hotel->plano.'<br>';
		        $datos_hotel .= 'PLANO:<img src=\''.$hotel->plano.'\'</img><br>';
		        $datos_hotel .=  'DESCRIPCION BREVE:'.$hotel->desc_hotel.'<br>';
		        $datos_hotel .= 'NUMERO DE HABITACIONES:'.$hotel->num_habitaciones.'<br>';
		        $datos_hotel .= 'COMO LLEGAR:'.$hotel->como_llegar.'<br>';
		        $datos_hotel .= 'TIPO DE ESTABLECIMIENTO:'.$hotel->tipo_establecimiento.'<br>';
		        $datos_hotel .= 'CATEGORIA 2:'.$hotel->categoria2.'<br>';
		        $datos_hotel .= 'URL LOGO:'.$hotel->logo_h.'<br>';
		        $datos_hotel .= 'LOGO:<img src=\''.$hotel->logo_h.'\'</img><br>';
		        $datos_hotel .= 'CHECK IN:'.$hotel->checkin.'<br>';
		        $datos_hotel .= 'CHECK OUT:'.$hotel->checkout.'<br>';
		        $datos_hotel .= 'EDAD NIÑOS DESDE:'.$hotel->edadnindes.'<br>';
		        $datos_hotel .= 'EDAD NIÑOS HASTA:'.$hotel->edadninhas.'<br>';
		        $datos_hotel .= 'DIVISA:'.$hotel->currency.'<br>';


		        $datos_hotel .= 'DESCRIPCION GENERAL:'.$hotel->descripciones->general.'<br>';
		        $datos_hotel .= 'DESCRIPCION ENTORNO 1:'.$hotel->descripciones->entorno1.'<br>';
		        $datos_hotel .= 'DESCRIPCION ENTORNO 2:'.$hotel->descripciones->entorno2.'<br>';
		        $datos_hotel .=  'DESCRIPCION ENTORNO 2:'.$hotel->descripciones->entorno2.'<br>';
		        $datos_hotel .= 'DESCRIPCION COMO LLEGAR 1:'.$hotel->descripciones->comollegar1.'<br>';
		        $datos_hotel .= 'DESCRIPCION COMO LLEGAR 2:'.$hotel->descripciones->comollegar2.'<br>';
		        $datos_hotel .= 'DESCRIPCION COMO LLEGAR 3:'.$hotel->descripciones->comollegar3.'<br>';
		        $datos_hotel .= 'DESCRIPCION HABITACIONES 1:'.$hotel->descripciones->habitaciones1.'<br>';
		        $datos_hotel .= 'DESCRIPCION HABITACIONES 2:'.$hotel->descripciones->habitaciones2.'<br>';
		        $datos_hotel .= 'DESCRIPCION HABITACIONES 3:'.$hotel->descripciones->habitaciones3.'<br>';
		        $datos_hotel .= 'DESCRIPCION HABITACIONES 4:'.$hotel->descripciones->habitaciones4.'<br>';


		        for($k=1;$k<=20;$k++){
		        	if(@$hotel->servicios->servicio[$k]->codigo_servicio){
			        $datos_hotel .= 'SERVICIO '.$k.' CODIGO:'.$hotel->servicios->servicio[$k]->codigo_servicio.'<br>';
			        $datos_hotel .= 'SERVICIO '.$k.' DESCRIPCION:'.$hotel->servicios->servicio[$k]->desc_serv.'<br>';
			 }
		        }


		        for($m=1;$m<=20;$m++){
		        	if(@$hotel->servicios_habitacion->servicio_habitacion[$m]->codigo_servicio_hab){
			        $datos_hotel .= 'SERVICIO HABITACION '.$m.':'.$hotel->servicios_habitacion->servicio_habitacion[$m]->codigo_servicio_hab.'<br>';
			        $datos_hotel .= 'SERVICIO HABITACION DESCRIPCION '.$m.':'.$hotel->servicios_habitacion->servicio_habitacion[$m]->descripciones.'<br>';
			 }
		        }

		        for($n=1;$n<=20;$n++){
		        	if(@$hotel->distancias->distancia[$n]->lugar){
			        $datos_hotel .= 'DISTANCIA LUGAR '.$n.':'.$hotel->distancias->distancia[$n]->lugar.'<br>';
			        $datos_hotel .= 'DISTANCIA KILOMETROS '.$n.':'.$hotel->distancias->distancia[$n]->kms.'<br>';
			        $datos_hotel .= 'DISTANCIA METROS '.$n.':'.$hotel->distancias->distancia[$n]->mts.'<br>';
			        $datos_hotel .= 'DISTANCIA PIE/HORAS '.$n.':'.$hotel->distancias->distancia[$n]->pie_horas.'<br>';
			        $datos_hotel .= 'DISTANCIA PIE/MINUTOS '.$n.':'.$hotel->distancias->distancia[$n]->pie_min.'<br>';
			        $datos_hotel .= 'DISTANCIA COCHE HORAS '.$n.':'.$hotel->distancias->distancia[$n]->coche_horas.'<br>';
			        $datos_hotel .= 'DISTANCIA COCHE MINUTOS '.$n.':'.$hotel->distancias->distancia[$n]->coche_min.'<br>';
			 }
		        }


		        for($o=1;$o<=20;$o++){
		        	if(@$hotel->salones->salon[$o]->codigo_salon){
			        $datos_hotel .= 'SALONES CODIGO '.$o.':'.$hotel->salones->salon[$o]->codigo_salon.'<br>';
			        $datos_hotel .= 'SALONES NOMBRE '.$o.':'.$hotel->salones->salon[$o]->nombre.'<br>';
			        $datos_hotel .= 'SALONES METROS CUADRADOS '.$o.':'.$hotel->salones->salon[$o]->m2.'<br>';
			        $datos_hotel .= 'SALONES CAPACIDAD PARA TEATRO '.$o.':'.$hotel->salones->salon[$o]->teadro.'<br>';
			        $datos_hotel .= 'SALONES CAPACIDAD PARA ESCUELA'.$o.':'.$hotel->salones->salon[$o]->escuela.'<br>';
			        $datos_hotel .= 'SALONES CAPACIDAD PARA COCKTAIL'.$o.':'.$hotel->salones->salon[$o]->cocktail.'<br>';
			        $datos_hotel .= 'SALONES CAPACIDAD PARA BANQUETE'.$o.':'.$hotel->salones->salon[$o]->banquete.'<br>';
			        $datos_hotel .= 'SALONES CAPACIDAD DE MESAS'.$o.':'.$hotel->salones->salon[$o]->mesau.'<br>';

			 }
		        }

		        $datos_hotel .= 'MARCA:'.$hotel->marca.'<br>';
		        $datos_hotel .= 'LONGITUD:'.$hotel->longitud.'<br>';
		        $datos_hotel .= 'LATITUD:'.$hotel->latitud.'<br>';
		        $datos_hotel .= 'CITY TAX:'.$hotel->city_tax.'<br>---------------------------------------------------------------<br>'; 

		//print($i);


	}
	print($datos_hotel);
?>