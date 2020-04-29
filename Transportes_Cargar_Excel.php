<?php

require_once 'PHPExcel.php';
include 'funciones_php/conexiones.php';
	
$conexion = conexion_hit();

$archivo = "Transportes.xls";
$inputFileType = PHPExcel_IOFactory::identify($archivo);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($archivo);
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();
for ($row = 2; $row <= $highestRow; $row++){ 

		//echo $sheet->getCell("A".$row)->getValue()." ------------- ";


		//echo($limpiado." - <BR>");

		/*echo $sheet->getCell("B".$row)->getValue()." - ";
		echo $sheet->getCell("C".$row)->getValue()." - ";
		echo $sheet->getCell("D".$row)->getValue()." - ";
		echo $sheet->getCell("E".$row)->getValue()." - ";
		echo $sheet->getCell("F".$row)->getValue()." - ";
		echo $sheet->getCell("G".$row)->getValue()." - ";
		echo $sheet->getCell("H".$row)->getValue()." - ";
		echo $sheet->getCell("I".$row)->getValue()." - ";
		echo $sheet->getCell("J".$row)->getValue()." - ";
		echo $sheet->getCell("K".$row)->getValue()." - ";
		echo $sheet->getCell("L".$row)->getValue()." - ";
		echo $sheet->getCell("M".$row)->getValue()." - ";
		echo $sheet->getCell("O".$row)->getValue()." - ";
		echo $sheet->getCell("P".$row)->getValue()." - ";
		echo $sheet->getCell("Q".$row)->getValue()." - ";
		echo $sheet->getCell("R".$row)->getValue()." - ";
		echo $sheet->getCell("S".$row)->getValue()." - ";
		echo $sheet->getCell("T".$row)->getValue()." - ";
		echo $sheet->getCell("U".$row)->getValue()." - ";
		echo $sheet->getCell("V".$row)->getValue();*/

		$quitar_delnombre = array("JAVIER","DEL ","AUTO TRANSPORTES", "AUTOBUSES", "AUTOCARES", "Autocares", "Autobuses", "BUS", "COMPAÑIA", "COMPAÑIA DEL", "EMPRESA", "GRUPO", "TRANSPORTES", "-","URBANOS","TOUR ","S.A. ","S.L. ","LA ","EL ","HERMANOS ","HNOS.","AUTOCARS ","AUTOMOVILES ","SL ","SA ",".",",","AUTOS ","AUTO "," ",")","(","&","á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","'");

		$quitar_delocalidad = array(" ",")","(","&","á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","'");

		$nombre_red = str_replace("Ñ","N",strtoupper (substr(str_replace($quitar_delnombre, "", $sheet->getCell("A".$row)->getValue()), 0,8)));
		$localidad_red = str_replace("Ñ","N",str_replace(" ","",trim(substr(str_replace($quitar_delocalidad, "", $sheet->getCell("D".$row)->getValue())  ,0,7))));
		$cp_red = substr($sheet->getCell("C".$row)->getValue(),0,5);
		$cia = $nombre_red.$cp_red.$localidad_red;

		$nombre = $sheet->getCell("A".$row)->getValue();
		$direccion = $sheet->getCell("B".$row)->getValue();
		$cp = $sheet->getCell("C".$row)->getValue();
		$localidad = $sheet->getCell("D".$row)->getValue();

		$provincia = $sheet->getCell("E".$row)->getValue();

		$cod_provincia = 'SI';
		$codigo_provincia =$conexion->query("select CODIGO from hit_provincias 
								where nombre like '%".$provincia."%'
								and substr(codigo,1,2) = 'ES'
								and '".$provincia."' not in ('ARABA' ,'BIZKAIA','IPUZKOA','OURENSE','LA RIOJA','ILLES BALEARS','SANTA CRUZ DE TENERIFE')");

		/*echo("select CODIGO from hit_provincias 
								where nombre like '%".$provincia."%'
								and substr(codigo,1,2) = 'ES'
								and '".$provincia."' not in ('ARABA' ,'BIZKAIA','IPUZKOA','OURENSE','LA RIOJA','ILLES BALEARS','SANTA CRUZ DE TENERIFE')");*/

		$ocodigo_provincia	 = $codigo_provincia->fetch_assoc();
		$cod_provincia = $ocodigo_provincia['CODIGO'];

		if($cod_provincia == ''){
			if($provincia == 'ARABA'){
				$cod_provincia = 'ESVIT';
			}else if($provincia == 'BIZKAIA'){
				$cod_provincia = 'ESBIO';
			}else if($provincia == 'GIPUZKOA'){
				$cod_provincia = 'ESEAS';
			}else if($provincia == 'OURENSE'){
				$cod_provincia = 'ESORE';
			}else if($provincia == 'LA RIOJA'){
				$cod_provincia = 'ESRIO';
			}else if($provincia == 'ILLES BALEARS'){
				$cod_provincia = 'ESMAH';
			}else if($provincia == 'SANTA CRUZ DE TENERIFE'){
				$cod_provincia = 'ESTCI';
			}else{
				$cod_provincia = 'SI';
			}
		}

		$telefono = $sheet->getCell("F".$row)->getValue();
		$email = $sheet->getCell("G".$row)->getValue();
		$web =  $sheet->getCell("H".$row)->getValue();

		//echo($cia."- ".$nombre."-------".$direccion."- ".$cp."- ".$localidad."- ".$provincia."- ".$cod_provincia."- ".$telefono."- ".$email."- ".$web."- "."<br>");

		//echo($cod_provincia."-------".$provincia."-------".$nombre."- "."<br>");

		$query = "INSERT INTO hit_transportes (CIA, NOMBRE, CIF, LOCALIDAD, DIRECCION, CODIGO_POSTAL, PROVINCIA, PAIS, TELEFONO, EMAIL, RESPONSABLE,URL) VALUES (";
		$query .= "'".$cia."',";
		$query .= "'".$nombre."',";
		$query .= "'pte',";

		$quitar_localidad = array("'","/");
		//$query .= "'".$localidad."',";
		$query .= "'".str_replace($quitar_localidad,"",mysql_real_escape_string($localidad))."',";

		$quitar_direccion = array(",","'","/");
		$query .= "'".str_replace($quitar_direccion,"",mysql_real_escape_string($direccion))."',";
		//$query .= ", DIRECCION = '".mysql_real_escape_string($direccion)."'";
		$query .= "'".$cp."',";
		$query .= "'".$cod_provincia."',";
		$query .= "'ESP',";
		$query .= "'".$telefono."',";
		$query .= "'".$email."',";
		$query .= "'',";
		$query .= "'".$web."')";

		$resultadoi =$conexion->query($query);

		if ($resultadoi == FALSE){
			//$respuesta = 'No se ha podido insertar la nueva compañia. '.$conexion->error;
			echo("<BR>No se ha podido insertar la nueva compañia. ".$cia." - ".$nombre.":   ".$conexion->error);
		}else{
			//$respuesta = 'OK';
			echo('<BR>.OK '.$cia." - ".$nombre);
		}



}

?>