<?php

	require_once 'PHPExcel.php';
	include 'funciones_php/conexiones.php';
	

$dias_antes = 12;

$fechaFFase=date("d-m-Y") ;
$fecha= new DateTime($fechaFFase);
$fecha->modify('+'.$dias_antes.' day');
//echo $fecha->format('d-m-Y');


$conexion = new mysqli('localhost','hit','hit','hit');
//$conexion = new mysqli('testdb.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com','hit','dacasale','hit');
if (mysqli_connect_errno()) {
   printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
   exit();
}

					//OBTENEMOS DATOS
										

					$resultado =$conexion->query("select   DATE_FORMAT(r.FECHA_SALIDA, '%d-%m-%Y') AS SALIDA,
																c.NOMBRE VIAJE, 
																DATEDIFF(r.FECHA_REGRESO, r.FECHA_SALIDA) + 1 DIAS_DURACION, 
																r.LOCALIZADOR, 
																rp.APELLIDO,
																rp.NOMBRE,
																CASE rp.TIPO
																		WHEN 'A' THEN 'Adulto'
																		WHEN 'N' THEN 'Niño'
																		WHEN 'B' THEN 'Bebé'
																		ELSE 'Adulto'
																	END TIPO,			
																MAX(concat('(',rs.TIPO,') - ', s.NOMBRE)) SEGURO
													from hit_reservas r, hit_reservas_servicios rs, hit_reservas_pasajeros rp, hit_servicios s, hit_producto_cuadros c
													where
														r.LOCALIZADOR = rs.LOCALIZADOR
														and r.LOCALIZADOR = rp.LOCALIZADOR
														and rs.ID_SERVICIO = s.ID
														and r.FOLLETO = c.FOLLETO
														and r.CUADRO = c.CUADRO
														and s.TIPO = 'SEG'
														and r.SITUACION <> 'A'
														and r.FECHA_SALIDA = DATE_ADD(curdate(),INTERVAL ".$dias_antes." DAY)
													group by SALIDA, VIAJE, DIAS_DURACION, LOCALIZADOR,APELLIDO, NOMBRE,TIPO
													order by r.FECHA_SALIDA, r.LOCALIZADOR, rp.TIPO, APELLIDO, NOMBRE, TIPO");

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
 
			$tituloReporte = "Seguros por Pasajero con Fecha de Salida: ".$fecha->format('d-m-Y');
			$titulosColumnas = array('SALIDA', 'VIAJE', 'DIAS DE DURACION', 'LOCALIZADOR', 'APELLIDO', 'NOMBRE', 'TIPO', 'SEGURO');
			/*El reporte como ya se habrán dado cuenta va a tener N columnas: Localizador, folleto, .... Por lo tanto solo vamos a ocupar hasta la columna ...*/

			// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
			$objPHPExcel->setActiveSheetIndex(0)
				->mergeCells('A1:L1');
			 
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
				->setCellValue('H3',  $titulosColumnas[7]);
	
			/*Como pueden apreciar para asignar contenido a una celda se selecciona primero la hoja con setActiveSheetIndex(Indice de hoja) y después con setCellValue(celda, valor) asignamos el contenido a la celda deseada.*/

			/*Ahora procedemos a rellenar las columnas con la información de los alumnos, para ello vamos a recorrer el resultado utilizando un while, las celdas se van a rellenar de forma parecida al paso anterior solo que aquí para indicar el número de fila se va a utilizar una variable que se va a estar incrementando cada vez que se rellene la información de un alumno.*/
	 
			//Se agregan los datos de los alumnos
 
			 $i = 4; //Numero de fila donde se va a comenzar a rellenar
			 while ($fila = $resultado->fetch_array()) {
				 $objPHPExcel->setActiveSheetIndex(0)
					 ->setCellValue('A'.$i, $fila['SALIDA'])
					 ->setCellValue('B'.$i, $fila['VIAJE'])
					 ->setCellValue('C'.$i, $fila['DIAS_DURACION'])
					 ->setCellValue('D'.$i, $fila['LOCALIZADOR'])
					 ->setCellValue('E'.$i, $fila['APELLIDO'])
					 ->setCellValue('F'.$i, $fila['NOMBRE'])
					 ->setCellValue('G'.$i, $fila['TIPO'])
					 ->setCellValue('H'.$i, $fila['SEGURO']);
				 $i++;
			 }
			 
			/*Hasta este punto ya se tiene el archivo con los datos ahora procedemos a aplicar el formato a las celdas. Para ello vamos a crear 3 variables, la primera va a contener el estilo del título del reporte, la segunda el estilo del título de las columnas y la tercera el estilo de la información de los alumnos.*/
 
			$estiloTituloReporte = array(
				'font' => array(
					'name'      => 'Verdana',
					'bold'      => true,
					'italic'    => false,
					'strike'    => false,
					'size' =>14,
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
 
			$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloTituloReporte);
			$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($estiloTituloColumnas);
			$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:H".($i-1));
 
			/*Como pueden ver el formato se aplicó de 2 formas diferentes una con getStyle(celda)->applyFromArray($arreglo) y la otra con setSharedStyle($estilo, celdas). ¿Cuál es la diferencia entre una y otra?, esto lo podemos apreciar cuando se aplican bordes: con la primer opción por ejemplo si se indica que se van a aplicar bordes superiores a las celdas desde B5 hasta F15, solo estaría aplicando el borde superior a la fila 5 debido a que toma el rango como si fuera una sola celda. En cambio con la segunda opción los bordes superiores se aplicarían a todas las filas desde la 5 hasta la 15 digamos que el formato se aplica a cada celda del rango indicado.*/

			/*Ahora procedemos a asignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas y lo hacemos con un ciclo de la siguiente forma.*/

			for($i = 'A'; $i <= 'H'; $i++){
				$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
			}
	
			/*Bien, ahora solo agregamos algunos detalles mas*/
			
			// Se asigna el nombre a la hoja
			$objPHPExcel->getActiveSheet()->setTitle('Seguros_pasajeros');
			 
			// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
			$objPHPExcel->setActiveSheetIndex(0);
			 
			// Inmovilizar paneles
			//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
			$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

			/*Ya para terminar vamos a enviar el archivo para que el usuario lo descargue.*/
			
			// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
			//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			//header('Content-Disposition: attachment;filename="Seguros_pasajeros.xlsx"');
			//header('Cache-Control: max-age=0');
			 
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			//$objWriter->save('php://output');
			
			$objWriter->save('documentos/seguros/Hitravel_Resumen_Pasajeros.xlsx');
			
			//ENVIAMOS EL MAIL A LA ASEGURADORA
			$mail_aseguradora = '';
			$responsable_aseguradora = '';
			$asunto_aseguradora = "HITRAVEL - RESUMEN PASAJEROS ASEGURADOS CON SALIDA: ".$fecha->format('d-m-Y');
			$mensaje_aseguradora_html = "
							<html lang='es'>
								<head>	
									<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
								</head>
								<body>
								<img src='imagenes/Logo_Mail.jpg' align='center' height='30' width='150'>
								<h1 style='font-size:1.5em;line-height:120%;color:#6f7073;margin:40px 40px 10px;font-weight:normal;text-align:left;clear:both;	display:block;'><strong>Buenos días, adjuntamos fichero excel con los pasajeros asegurados con salida: ".$fecha->format('d-m-Y').".</strong>
								</h1>
								</body>
							</html>";
			$envio = enviar_mail_resumen_aseguradora($asunto_aseguradora, $mensaje_aseguradora_html, $mail_aseguradora, $responsable_aseguradora);

			//exit;
		
		}
	}

?>


