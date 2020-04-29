<?php

	include 'funciones_php/conexiones.php';

	//$parametrosg = $_GET;
	//$codigo = $parametrosg['codigo'];

	$codigo=746438;


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
		//$xml2 .= "<codigo>746438</codigo>";
		//$xml2 .= "<codigo>745388</codigo>";
		$xml2 .= "<codigo>".$codigo."</codigo>";
		$xml2 .= "<idioma>1</idioma>";
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

	/*echo('<pre>');
	print_r($xml->parametros->hotel);
	echo('</pre>');*/

	$datos_hotel = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 5.0 Transitional//EN'>
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



	for($i=0;$i<$no_hotels;$i++)
	{
		$datos_hotel .= '';
		$hotel = $xml->parametros->hotel[$i];

		        $datos_hotel .= '<strong>DATOS PRINCIPALES</strong><BR><BR>';

		        $datos_hotel .= '<TABLE BORDER=1>';
		        $datos_hotel .= '<TR><TD><strong>NOMBRE:</strong></TD><TD><strong>'.$hotel->nombre_h.'</strong></TD></TR>';
		        $datos_hotel .= '<TR><TD>PAIS:</TD><TD>'.$hotel->pais.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>CODIGO HOTEL:</TD><TD>'.$hotel->codigo_hotel.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>CODIGO INTERNO:</TD><TD>'.$hotel->codigo.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>AFILIACION:</TD><TD>'.$hotel->hot_afiliacion.'</TD></TR>';
		        $datos_hotel .=  '<TR><TD>CATEGORIA:</TD><TD>'.$hotel->categoria.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>DIRECCION:</TD><TD>'.$hotel->direccion.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>COD PROVINCIA:</TD><TD>'.$hotel->codprovincia.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>NOMBRE PROVINCIA:</TD><TD>'.$hotel->provincia.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>COD POBLACION:</TD><TD>'.$hotel->codpoblacion.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>POBLACION:</TD><TD>'.$hotel->poblacion.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>CODIGO POSTAL:</TD><TD>'.$hotel->cp.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>COD DUPLICADO:</TD><TD>'.$hotel->coddup.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>MAIL:</TD><TD>'.$hotel->mail.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>WEB:</TD><TD>'.$hotel->web.'</TD></TR>';
		        $datos_hotel .= '</TABLE>';


		        $datos_hotel .= '<BR><BR><strong>FOTOS</strong><BR><BR>';

		        $datos_hotel .= '<TABLE BORDER=1>';
		        for($j=0;$j<=20;$j++){
		        	if($hotel->fotos->foto[$j] != ''){
			        $datos_hotel .= '<TR><TD>FOTO '.$j.' URL:'.$hotel->fotos->foto[$j].'</TD></TR>';
			        $datos_hotel .= '<TR><TD><img src=\''.$hotel->fotos->foto[$j].'\'</img></TD></TR>';
			 }
		        }
		        $datos_hotel .= '</TABLE>';

		        $datos_hotel .= '<BR><BR><strong>DATOS GENERALES</strong><BR><BR>';

		        $datos_hotel .= '<TABLE BORDER=1>';
		        $datos_hotel .= '<TR><TD>URL PLANO:</TD><TD>'.$hotel->plano.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>PLANO:</TD><TD><img src=\''.$hotel->plano.'\'</img></TD></TR>';
		        $datos_hotel .= '<TR><TD>DESCRIPCION BREVE:</TD><TD>'.$hotel->desc_hotel.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>NUMERO DE HABITACIONES:</TD><TD>'.$hotel->num_habitaciones.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>COMO LLEGAR:</TD><TD>'.$hotel->como_llegar.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>TIPO DE ESTABLECIMIENTO:</TD><TD>'.$hotel->tipo_establecimiento.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>CATEGORIA 2:</TD><TD>'.$hotel->categoria2.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>URL LOGO:</TD><TD>'.$hotel->logo_h.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>LOGO:</TD><TD><img src=\''.$hotel->logo_h.'\'</img></TD></TR>';
		        $datos_hotel .= '<TR><TD>CHECK IN:</TD><TD>'.$hotel->checkin.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>CHECK OUT:</TD><TD>'.$hotel->checkout.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>EDAD NIÑOS DESDE:</TD><TD>'.$hotel->edadnindes.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>EDAD NIÑOS HASTA:</TD><TD>'.$hotel->edadninhas.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>DIVISA:</TD><TD>'.$hotel->currency.'</TD></TR>';

		        //ETIQUETAS ELIMINADAS POR RESTEL
		        /*$datos_hotel .= '<TR><TD>DESCRIPCION GENERAL:</TD><TD>'.$hotel->descripciones.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>DESCRIPCION ENTORNO 1:</TD><TD>'.$hotel->descripciones->entorno1.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>DESCRIPCION ENTORNO 2:</TD><TD>'.$hotel->descripciones->entorno2.'</TD></TR>';
		        $datos_hotel .=  '<TR><TD>DESCRIPCION ENTORNO 2:</TD><TD>'.$hotel->descripciones->entorno2.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>DESCRIPCION COMO LLEGAR 1:</TD><TD>'.$hotel->descripciones->comollegar1.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>DESCRIPCION COMO LLEGAR 2:</TD><TD>'.$hotel->descripciones->comollegar2.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>DESCRIPCION COMO LLEGAR 3:</TD><TD>'.$hotel->descripciones->comollegar3.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>DESCRIPCION HABITACIONES 1:</TD><TD>'.$hotel->descripciones->habitaciones1.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>DESCRIPCION HABITACIONES 2:</TD><TD>'.$hotel->descripciones->habitaciones2.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>DESCRIPCION HABITACIONES 3:</TD><TD>'.$hotel->descripciones->habitaciones3.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>DESCRIPCION HABITACIONES 4:</TD><TD>'.$hotel->descripciones->habitaciones4.'</TD></TR>';*/

		        $datos_hotel .= '<TR><TD>MARCA:</TD><TD>'.$hotel->marca.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>LONGITUD:</TD><TD>'.$hotel->longitud.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>LATITUD:</TD><TD>'.$hotel->latitud.'</TD></TR>';
		        $datos_hotel .= '<TR><TD>CITY TAX:</TD><TD>'.$hotel->city_tax.'<b/TD></TRr>'; 
		        $datos_hotel .= '</TABLE>';



		        $datos_hotel .= '<BR><BR><strong>SERVICIOS HOTEL</strong><BR><BR>';
		        $datos_hotel .= '<TABLE BORDER=1><TR><TD>SERVICIO</TD><TD>CODIGO</TD><TD>DESCRIPCION</TD></TR>';
		        for($k=1;$k<=20;$k++){
		        	if(@$hotel->servicios->servicio[$k]->codigo_servicio){
			        $datos_hotel .= '<TR><TD>'.$k.'</TD><TD>'.$hotel->servicios->servicio[$k]->codigo_servicio.'</TD><TD>'.$hotel->servicios->servicio[$k]->desc_serv.'</TD></TR>';
			 }
		        }
		        $datos_hotel .= '</TABLE>';


		        $datos_hotel .= '<BR><BR><strong>SERVICIOS HABITACION</strong><BR><BR>';
		        $datos_hotel .= '<TABLE BORDER=1><TR><TD>SERVICIO</TD><TD>CODIGO</TD><TD>DESCRIPCION</TD></TR>';
		        for($m=1;$m<=20;$m++){
		        	if(@$hotel->servicios_habitacion->servicio_habitacion[$m]->codigo_servicio_hab){
			        $datos_hotel .= '<TR><TD>'.$m.'</TD><TD>'.$hotel->servicios_habitacion->servicio_habitacion[$m]->codigo_servicio_hab.'</TD><TD>'.$hotel->servicios_habitacion->servicio_habitacion[$m]->descripciones.'</TD></TR>';
			 }
		        }
		        $datos_hotel .= '</TABLE>';


		        $datos_hotel .= '<BR><BR><strong>DISTANCIAS</strong><BR><BR>';
		        $datos_hotel .= '<TABLE BORDER=1><TR><TD>LUGAR</TD><TD>KMS</TD><TD>METROS</TD><TD>PIE/HORAS</TD><TD>PIE/MINUTOS</TD><TD>COCHE HORAS</TD><TD>COCHE MINUTOS</TD></TR>';
		        for($n=1;$n<=20;$n++){
		        	if(@$hotel->distancias->distancia[$n]->lugar){
			        $datos_hotel .= "<TR><TD>".$hotel->distancias->distancia[$n]->lugar."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->kms."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->mts."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->pie_horas."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->pie_min."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->coche_horas."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->distancias->distancia[$n]->coche_min."</TD></TR>";
			 }
		        }
		        $datos_hotel .= '</TABLE>';

		        $datos_hotel .= "<BR><BR><strong>SALONES</strong><BR><BR>";
		        $datos_hotel .= "<TABLE BORDER=1><TR><TD>CODIGO</TD><TD>NOMBRE</TD><TD align='center'>METROS CUADRADOS</TD><TD align='center'>CAPACIDAD PARA TEATRO </TD><TD align='center'>CAPACIDAD PARA ESCUELA</TD><TD align='center'>CAPACIDAD PARA COCKTAIL</TD><TD align='center'>CAPACIDAD PARA BANQUETE</TD><TD align='center'>CAPACIDAD DE MESAS</TD></TR>";
		        for($o=1;$o<=20;$o++){
		        	if(@$hotel->salones->salon[$o]->codigo_salon){
			        $datos_hotel .= "<TR><TD>".$hotel->salones->salon[$o]->codigo_salon."</TD>";
			        $datos_hotel .= "<TD>".$hotel->salones->salon[$o]->nombre."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->m2."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->teadro."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->escuela."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->cocktail."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->banquete."</TD>";
			        $datos_hotel .= "<TD align='center'>".$hotel->salones->salon[$o]->mesau."</TD></TR>";

			 }
		        }
		        $datos_hotel .= '</TABLE>';

	$datos_hotel .= '</BODY></HTML>';

	}

	echo($datos_hotel);

?>


