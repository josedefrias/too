<?php

 	//include 'funciones_php/conexiones.php';
	require_once 'PHPExcel.php';
	//include 'PHPExcel/IOFactory.php';
	//include 'PHPExcel/Writer/Excel5.php';
	
$parametrosg = $_GET;

$fecha = $parametrosg['fecha'];

$conexion = new mysqli('localhost','hit','hit','hit');
//$conexion = new mysqli('testdb.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com','hit','dacasale','hit');
if (mysqli_connect_errno()) {
   printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
   exit();
}
//$conexion = conexion_hit();

					//OBTENEMOS NOMBRE DEL VIAJE Á
					$resultado =$conexion->query("SELECT r.LOCALIZADOR localizador, r.FOLLETO folleto, r.CUADRO cuadro, r.SITUACION situacion, 
							DATE_FORMAT(r.FECHA_SALIDA, '%d-%m-%Y') AS fecha_salida,
							DATE_FORMAT(r.FECHA_REGRESO, '%d-%m-%Y') AS fecha_regreso,
							m.NOMBRE agencia, o.LOCALIDAD localidad, m.DIRECCION direccion, m.TELEFONO telefono, m.MAIL mail, r.AGENTE agente, r.REFERENCIA_AGENCIA referencia,
							 p.NUMERO numero_pax, p.APELLIDO apellido, p.NOMBRE nombre, p.TIPO tipo_pax, p.EDAD edad,
							al.NOMBRE hotel, a.NOCHES noches, a.SITUACION sit_hotel, a.ORDEN num_hab, a.HABITACION habitacion,  a.USO uso, a.REGIMEN regimen, car.NOMBRE tipo_hab,
							
							DATE_FORMAT(rt.FECHA, '%d-%m-%Y') AS fecha_vlo_ida,
							rt.SITUACION situacion_ida, rt.ORIGEN origen_ida,rt.DESTINO destino_ida, rt.CIA cia_ida, rt.VUELO vuelo_ida, 
							time_format(rt.HORA_SALIDA, '%H:%i') AS hora_salida_ida,
							time_format(rt.HORA_LLEGADA, '%H:%i') AS hora_llegada_ida,
							
							DATE_FORMAT(rt2.FECHA, '%d-%m-%Y') AS fecha_vlo_reg,
							rt2.SITUACION situacion_reg, rt2.ORIGEN origen_reg,rt2.DESTINO destino_reg,rt2.CIA cia_reg, rt2.VUELO vuelo_reg, 
							time_format(rt2.HORA_SALIDA, '%H:%i') AS hora_salida_reg,
							time_format(rt2.HORA_LLEGADA, '%H:%i') AS hora_llegada_reg,
							
							DATE_FORMAT(rs.FECHA, '%d-%m-%Y') AS fecha_trf_ida,
							rs.CODIGO trf_ida,
							DATE_FORMAT(rs2.FECHA, '%d-%m-%Y') AS fecha_trf_reg,
							rs2.CODIGO trf_reg
					from hit_reservas r left join hit_reservas_servicios rs on r.LOCALIZADOR = rs.LOCALIZADOR and substr(rs.CODIGO,-3) = 'TRF' and rs.FECHA = '".date("Y-m-d",strtotime($fecha))."'
											  left join hit_reservas_servicios rs2 on r.LOCALIZADOR = rs2.LOCALIZADOR and substr(rs2.CODIGO,-3) = 'TRF' and rs2.FECHA > '".date("Y-m-d",strtotime($fecha))."',
						  hit_reservas_pasajeros p left join hit_reservas_alojamientos a on p.LOCALIZADOR = a.LOCALIZADOR and p.HABITACION = a.ORDEN
																				 left join hit_alojamientos al on a.ALOJAMIENTO = al.ID
																				 left join hit_habitaciones_car car on a.CARACTERISTICA = car.CODIGO
														 left join hit_reservas_aereos_pasajeros ap on p.CLAVE = ap.CLAVE_PASAJERO
																								   left join hit_reservas_aereos_trayectos rt on ap.LOCALIZADOR = rt.LOCALIZADOR 
																																								 and ap.ORDEN = rt.ORDEN 
																																								  and ap.CLAVE_AEREO = rt.CLAVE_AEREO
																																								  and rt.FECHA = '".date("Y-m-d",strtotime($fecha))."'
																									left join hit_reservas_aereos_trayectos rt2 on ap.LOCALIZADOR = rt2.LOCALIZADOR 
																																								 and ap.ORDEN = rt2.ORDEN 
																																								  and ap.CLAVE_AEREO = rt2.CLAVE_AEREO
																																								  and rt2.FECHA > '".date("Y-m-d",strtotime($fecha))."',
	  hit_minoristas m, hit_oficinas o		
	  																																  
where 
	r.localizador = p.localizador
	and r.MINORISTA = o.ID
	and o.ID = m.ID
	and r.OFICINA = o.OFICINA
	and r.SITUACION <> 'A'
	and r.FECHA_SALIDA = '".date("Y-m-d",strtotime($fecha))."'
 order by r.LOCALIZADOR, p.NUMERO, p.HABITACION, p.TIPO");


	/*Lo siguiente es verificar que la consulta obtuvo los registros a mostrar y lo hacemos con la siguiente condición, si el número de registros es mayor que 0 quiere decir que si se obtuvieron datos por lo tanto procedemos a crear el reporte.*/
	
	if($resultado->num_rows > 0 ){
	
			
		/*Lo primero que hacemos es definir la zona horaria, debido a que vamos a trabajar con datos de tipo fecha y luego manda un error si no tenemos asignada una zona horaria, en este caso voy a asignar la de la ciudad de Madrid*/
		
		date_default_timezone_set('Europe/Madrid');			

		/*La siguiente línea determina si se está accediendo al archivo vía HTTP o CLI(command line interface), el archivo solo se va a mostrar si se accede desde un navegador web(HTTP).*/	

		if (PHP_SAPI == 'cli'){
		
			die('Este archivo solo se puede ver desde un navegador web');			
					
		}else{
			
			/*Ahora si comenzamos a armar el reporte de Excel*/
 
			// Se crea el objeto PHPExcel
			$objPHPExcel = new PHPExcel();
 
 
			/*Comenzamos agregando las propiedades del archivo de Excel, estas las podemos ver una vez que se haya guardado el archivo haciendo “Clic derecho > Propiedades > Detalles”*/
 
			// Se asignan las propiedades del libro
			$objPHPExcel->getProperties()->setCreator("Codedrinks") // Nombre del autor
				->setLastModifiedBy("Codedrinks") //Ultimo usuario que lo modificó
				->setTitle("Reporte Excel con PHP y MySQL") // Titulo
				->setSubject("Reporte Excel con PHP y MySQL") //Asunto
				->setDescription("Reporte de reservas") //Descripción
				->setKeywords("reporte reservas") //Etiquetas
				->setCategory("Reporte excel"); //Categorias
 
			/*Para los títulos del reporte voy a crear dos variables, de esta forma es un poco más fácil realizar algunos cambios si es que el reporte fuera muy extenso.*/
 
			$tituloReporte = "Informacion de Pasajeros con Salida: ".$fecha;
			$titulosColumnas = array('LOCALIZADOR', 'FOLLETO', 'CUADRO', 'SITUACION', 'SALIDA', 'REGRESO', 'AGENCIA','LOCALIDAD','DIRECCCION','TELEFONO','MAIL','AGENTE','REFERENCIA','NUMERO PAX', 'APELLIDO', 'NOMBRE', 'TIPO PAX', 'EDAD','HOTEL','NOCHES','SITUACION','NUMERO','HABITACION','USO','REGIMEN','TIPO','FECHA IDA','SIT','ORIGEN','DESTINO','CIA','VUELO','SALIDA','LLEGADA','FECHA REG.','SIT','ORIGEN','DESTINO','CIA','VUELO','SALIDA','LLEGADA','FECHA TRF IN','TRF IN','FECHA TRF OUT','TRF OUT');
			/*El reporte como ya se habrán dado cuenta va a tener N columnas: Localizador, folleto, .... Por lo tanto solo vamos a ocupar hasta la columna ...*/

			// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
			$objPHPExcel->setActiveSheetIndex(0)
				->mergeCells('A1:AT1');
			 
			// Se agregan los titulos del reporte
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1',$tituloReporte) // Titulo del reporte
				->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
				->setCellValue('B3',  $titulosColumnas[1])
				->setCellValue('C3',  $titulosColumnas[2])
				->setCellValue('D3',  $titulosColumnas[3])
				->setCellValue('E3',  $titulosColumnas[4])
				->setCellValue('F3',  $titulosColumnas[5])
				->setCellValue('G3',  $titulosColumnas[6])
				->setCellValue('H3',  $titulosColumnas[7])
				->setCellValue('I3',  $titulosColumnas[8])
				->setCellValue('J3',  $titulosColumnas[9])
				->setCellValue('K3',  $titulosColumnas[10])
				->setCellValue('L3',  $titulosColumnas[11])
				->setCellValue('M3',  $titulosColumnas[12])
				->setCellValue('N3',  $titulosColumnas[13])
				->setCellValue('O3',  $titulosColumnas[14])
				->setCellValue('P3',  $titulosColumnas[15])
				->setCellValue('Q3',  $titulosColumnas[16])
				->setCellValue('R3',  $titulosColumnas[17])
				->setCellValue('S3',  $titulosColumnas[18])
				->setCellValue('T3',  $titulosColumnas[19])
				->setCellValue('U3',  $titulosColumnas[20])
				->setCellValue('V3',  $titulosColumnas[21])
				->setCellValue('W3',  $titulosColumnas[22])
				->setCellValue('X3',  $titulosColumnas[23])
				->setCellValue('Y3',  $titulosColumnas[24])
				->setCellValue('Z3',  $titulosColumnas[25])
				->setCellValue('AA3',  $titulosColumnas[26])
				->setCellValue('AB3',  $titulosColumnas[27])
				->setCellValue('AC3',  $titulosColumnas[28])
				->setCellValue('AD3',  $titulosColumnas[29])
				->setCellValue('AE3',  $titulosColumnas[30])
				->setCellValue('AF3',  $titulosColumnas[31])
				->setCellValue('AG3',  $titulosColumnas[32])
				->setCellValue('AH3',  $titulosColumnas[33])
				->setCellValue('AI3',  $titulosColumnas[34])
				->setCellValue('AJ3',  $titulosColumnas[35])
				->setCellValue('AK3',  $titulosColumnas[36])
				->setCellValue('AL3',  $titulosColumnas[37])
				->setCellValue('AM3',  $titulosColumnas[38])
				->setCellValue('AN3',  $titulosColumnas[39])
				->setCellValue('AO3',  $titulosColumnas[40])
				->setCellValue('AP3',  $titulosColumnas[41])
				->setCellValue('AQ3',  $titulosColumnas[42])
				->setCellValue('AR3',  $titulosColumnas[43])
				->setCellValue('AS3',  $titulosColumnas[44])
				->setCellValue('AT3',  $titulosColumnas[45]);
	
			/*Como pueden apreciar para asignar contenido a una celda se selecciona primero la hoja con setActiveSheetIndex(Indice de hoja) y después con setCellValue(celda, valor) asignamos el contenido a la celda deseada.*/

			/*Ahora procedemos a rellenar las columnas con la información de los alumnos, para ello vamos a recorrer el resultado utilizando un while, las celdas se van a rellenar de forma parecida al paso anterior solo que aquí para indicar el número de fila se va a utilizar una variable que se va a estar incrementando cada vez que se rellene la información de un alumno.*/
	 
			//Se agregan los datos de los alumnos
 
			 $i = 4; //Numero de fila donde se va a comenzar a rellenar
			 while ($fila = $resultado->fetch_array()) {
				 $objPHPExcel->setActiveSheetIndex(0)
					 ->setCellValue('A'.$i, $fila['localizador'])
					 ->setCellValue('B'.$i, $fila['folleto'])
					 ->setCellValue('C'.$i, $fila['cuadro'])
					 ->setCellValue('D'.$i, $fila['situacion'])
					 ->setCellValue('E'.$i, $fila['fecha_salida'])
					 ->setCellValue('F'.$i, $fila['fecha_regreso'])
					 ->setCellValue('G'.$i, $fila['agencia'])
					 ->setCellValue('H'.$i, $fila['localidad'])
					 ->setCellValue('I'.$i, $fila['direccion'])
					 ->setCellValue('J'.$i, $fila['telefono'])
					 ->setCellValue('K'.$i, $fila['mail'])
					 ->setCellValue('L'.$i, $fila['agente'])
					 ->setCellValue('M'.$i, $fila['referencia'])
					 ->setCellValue('N'.$i, $fila['numero_pax'])
					 ->setCellValue('O'.$i, $fila['apellido'])
					 ->setCellValue('P'.$i, $fila['nombre'])
					 ->setCellValue('Q'.$i, $fila['tipo_pax'])
					 ->setCellValue('R'.$i, $fila['edad'])
					 ->setCellValue('S'.$i, $fila['hotel'])
					 ->setCellValue('T'.$i, $fila['noches'])
					 ->setCellValue('U'.$i, $fila['sit_hotel'])
					 ->setCellValue('V'.$i, $fila['num_hab'])
					 ->setCellValue('W'.$i, $fila['habitacion'])
					 ->setCellValue('X'.$i, $fila['uso'])
					 ->setCellValue('Y'.$i, $fila['regimen'])
					 ->setCellValue('Z'.$i, $fila['tipo_hab'])
					 ->setCellValue('AA'.$i, $fila['fecha_vlo_ida'])
					 ->setCellValue('AB'.$i, $fila['situacion_ida'])
					 ->setCellValue('AC'.$i, $fila['origen_ida'])
					 ->setCellValue('AD'.$i, $fila['destino_ida'])
					 ->setCellValue('AE'.$i, $fila['cia_ida'])
					 ->setCellValue('AF'.$i, $fila['vuelo_ida'])
					 ->setCellValue('AG'.$i, $fila['hora_salida_ida'])
					 ->setCellValue('AH'.$i, $fila['hora_llegada_ida'])
					 ->setCellValue('AI'.$i, $fila['fecha_vlo_reg'])
					 ->setCellValue('AJ'.$i, $fila['situacion_reg'])
					 ->setCellValue('AK'.$i, $fila['origen_reg'])
					 ->setCellValue('AL'.$i, $fila['destino_reg'])
					 ->setCellValue('AM'.$i, $fila['cia_reg'])
					 ->setCellValue('AN'.$i, $fila['vuelo_reg'])
					 ->setCellValue('AO'.$i, $fila['hora_salida_reg'])
					 ->setCellValue('AP'.$i, $fila['hora_llegada_reg'])
					 ->setCellValue('AQ'.$i, $fila['fecha_trf_ida'])
					 ->setCellValue('AR'.$i, $fila['trf_ida'])
					 ->setCellValue('AS'.$i, $fila['fecha_trf_reg'])
					 ->setCellValue('AT'.$i, $fila['trf_reg']);
				 $i++;
			 }
		 
			/*Hasta este punto ya se tiene el archivo con los datos ahora procedemos a aplicar el formato a las celdas. Para ello vamos a crear 3 variables, la primera va a contener el estilo del título del reporte, la segunda el estilo del título de las columnas y la tercera el estilo de la información de los alumnos.*/
 
			$estiloTituloReporte = array(
				'font' => array(
					'name'      => 'Verdana',
					'bold'      => true,
					'italic'    => false,
					'strike'    => false,
					'size' =>16,
					'color'     => array(
						'rgb' => 'FFFFFF'
					)
				),
				'fill' => array(
					'type'  => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array(
						'argb' => 'FF220835')
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_NONE
					)
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					'rotation' => 0,
					'wrap' => TRUE
				)
			);
			 
			$estiloTituloColumnas = array(
				'font' => array(
					'name'  => 'Arial',
					'bold'  => true,
					'color' => array(
						'rgb' => 'FFFFFF'
					)
				),
				'fill' => array(
					'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
				'rotation'   => 90,
					'startcolor' => array(
						'rgb' => 'c47cf2'
					),
					'endcolor' => array(
						'argb' => 'FF431a5d'
					)
				),
				'borders' => array(
					'top' => array(
						'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
						'color' => array(
							'rgb' => '143860'
						)
					),
					'bottom' => array(
						'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
						'color' => array(
							'rgb' => '143860'
						)
					)
				),
				'alignment' =>  array(
					'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					'wrap'      => TRUE
				)
			);
			 
			$estiloInformacion = new PHPExcel_Style();
			$estiloInformacion->applyFromArray( array(
				'font' => array(
					'name'  => 'Arial',
					'color' => array(
						'rgb' => '000000'
					)
				),
				'fill' => array(
				'type'  => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array(
						'argb' => 'FFFFFF')
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN ,
					'color' => array(
							'rgb' => '3a2a47'
						)
					)
				)
			));
 
			/*La forma más rápida de dar formato a las celdas es a través de arreglos en los cuales se define todo el conjunto de formato que deseamos aplicar a las celdas. Veamos cómo se aplican.*/
 
			$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($estiloTituloReporte);
			$objPHPExcel->getActiveSheet()->getStyle('A3:AT3')->applyFromArray($estiloTituloColumnas);
			$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:AT".($i-1));
 
			/*Como pueden ver el formato se aplicó de 2 formas diferentes una con getStyle(celda)->applyFromArray($arreglo) y la otra con setSharedStyle($estilo, celdas). ¿Cuál es la diferencia entre una y otra?, esto lo podemos apreciar cuando se aplican bordes: con la primer opción por ejemplo si se indica que se van a aplicar bordes superiores a las celdas desde B5 hasta F15, solo estaría aplicando el borde superior a la fila 5 debido a que toma el rango como si fuera una sola celda. En cambio con la segunda opción los bordes superiores se aplicarían a todas las filas desde la 5 hasta la 15 digamos que el formato se aplica a cada celda del rango indicado.*/

			/*Ahora procedemos a asignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas y lo hacemos con un ciclo de la siguiente forma.*/

			for($i = 'A'; $i <= 'AT'; $i++){
				$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
			}
	
			/*Bien, ahora solo agregamos algunos detalles mas*/
			
			// Se asigna el nombre a la hoja
			$objPHPExcel->getActiveSheet()->setTitle('Pasajeros');
			 
			// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
			$objPHPExcel->setActiveSheetIndex(0);
			 
			// Inmovilizar paneles
			//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
			$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

			/*Ya para terminar vamos a enviar el archivo para que el usuario lo descargue.*/
			
			// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Reservas_operativa.xlsx"');
			header('Cache-Control: max-age=0');
			 
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			

			
			
			exit;
		}
	}else{
		print_r('No hay resultados para mostrar');
	}


?>


