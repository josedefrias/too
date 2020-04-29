<?
	require ('Smphparty.class.php');
	include 'funciones_php/conexiones.php';
	require 'clases/general.cls.php';
	require 'clases/provincias.cls.php';


	session_start();

	$usuario = $_SESSION['usuario'];
	$nombre =  $_SESSION['nombre'];

	/*echo('<pre>');
	print_r($usuario);
	echo('-');
	print_r($nombre);
	echo('</pre>');*/

	/*echo('<pre>');
	print_r($_POST);
	echo('</pre>');*/

	$parametros = $_POST;
	$error = '';
	$conexion = conexion_hit();







	$xml = "codigousu=" . 'QDZN';
	$xml .= "&clausu=" . 'xml476346';
	$xml .= "&afiliacio=" . 'RS';
	$xml .= "&secacc=" . '110592';
	$xml .= "&xml=";
	$xml2 = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
	$xml2 .= "<peticion>\n";

	$xml2 .= "<nombre>Petición de Lista de Provincias</nombre>\n";
	$xml2 .= "<agencia>Agencia de Prueba</agencia>\n";
	$xml2 .= "<tipo>6</tipo>\n";

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
	$no_hotels = count($xml->parametros->provincias->provincia);
	//ECHO($respuesta.'<br><br><br>');
	ECHO($no_hotels.'<br><br><br>');
	for($i=0;$i<$no_hotels;$i++)
	{
		$provincia = $xml->parametros->provincias->provincia[$i];
		//print('CODIGO PAIS:'.$provincia->codigo_pais.' -  '.'CODIGO PROVINCIA:'.$provincia->codigo_provincia.' -  '.'NOMBRE:'.$provincia->nombre_provincia.'<br>'); 
		//print($i);


		//OComprobamos si ya existe
		$existe =$conexion->query("SELECT count(*) existe FROM hit_provincias WHERE CODIGO = '".$provincia->codigo_provincia."'");
		$Nexiste = $existe->fetch_assoc();
		$existe = $Nexiste['existe'];


		if($existe == 0){

			print('CODIGO PAIS:'.$provincia->codigo_pais.' -  '.'CODIGO PROVINCIA:'.$provincia->codigo_provincia.' -  '.'NOMBRE:'.$provincia->nombre_provincia.'<br>');

			//Obtenemos codigo 3 de pais
			$paises =$conexion->query("SELECT CODIGO FROM hit_paises WHERE CODIGO_CORTO = '".$provincia->codigo_pais."'");
			$Npaises	 = $paises->fetch_assoc();
			$pais = $Npaises['CODIGO'];


			$query = "INSERT INTO hit_provincias (CODIGO, NOMBRE, CODIGO_POSTAL, COMUNIDAD, PAIS, IMPUESTO) VALUES (";
			$query .= "'".$provincia->codigo_provincia."',";
			$query .= "'".$provincia->nombre_provincia."',";
			$query .= "'',";
			$query .= "'',";
			$query .= "'".$pais."',";
			$query .= "'SIN')";

			$resultadoi =$conexion->query($query);

			if ($resultadoi == FALSE){
				$respuesta = 'No se ha podido insertar el nuevo registro. '.$conexion->error;
			}else{
				$respuesta = 'OK';
			}

			print($respuesta.'<br>');
		}

	}
	//print($i);



	

	$conexion->close();


?>

