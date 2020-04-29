<?php

 	//include 'funciones_php/conexiones.php';
	require_once 'PHPExcel.php';
	//include 'PHPExcel/IOFactory.php';
	//include 'PHPExcel/Writer/Excel5.php';
	
$parametrosg = $_GET;

$clave = $parametrosg['clave'];
$fecha_desde = $parametrosg['fecha_desde']; 
$fecha_hasta = $parametrosg['fecha_hasta']; 


$conexion = new mysqli('testdb.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com','hit','dacasale','too');
//$conexion = new mysqli('testdb.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com','hit','dacasale','hit');
//$conexion = new mysqli('bikefriendly.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com','hit','bikef2015','hit');
if (mysqli_connect_errno()) {
   printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
   exit();
}
//$conexion = conexion_hit();

					//OBTENEMOS NOMBRE DEL VIAJE Á
					$resultado =$conexion->query("select a.NOMBRE alojamiento, tp.ACUERDO acuerdo, cat.NOMBRE categoria, hc.NOMBRE habitacion, 
						 t.NOCHES noches, tp.REGIMEN regimen, 
						 tp.FECHA fecha,
						 tp.DIA_SEMANA dia,
						 t.REDONDEO redondeo,
						 concat('( ',tp.COSTE_TRANSPORTES,' / ',round(1-(t.MARGEN_TRANSPORTES/100),2),' ) + ',tp.TASAS,' = ',round(tp.PRECIO_TRANSPORTES,t.REDONDEO)) calculo_transportes,
						 concat('( ',tp.COSTE_ALOJAMIENTOS,' / ',round(1-(t.MARGEN_ALOJAMIENTOS/100),2),' ) - ',tp.DESCUENTO_ALOJAMIENTO,'% = ',round(tp.PRECIO_ALOJAMIENTOS,t.REDONDEO)) calculo_alojamientos, 
						 concat('( ',tp.COSTE_SERVICIOS,' / ',round(1-(t.MARGEN_SERVICIOS/100),2),' ) = ',round(tp.PRECIO_SERVICIOS,t.REDONDEO)) calculo_servicios,
						 concat(round(tp.PRECIO_TRANSPORTES,t.REDONDEO),' + ',round(tp.PRECIO_ALOJAMIENTOS,t.REDONDEO),' + ',round(tp.PRECIO_SERVICIOS,t.REDONDEO),' = ', round(tp.PRECIO_TRANSPORTES,t.REDONDEO)+round(tp.PRECIO_ALOJAMIENTOS,t.REDONDEO)+round(tp.PRECIO_SERVICIOS,t.REDONDEO)) pvp
						from hit_tarifarios_precios tp, hit_alojamientos a, hit_habitaciones_car hc, hit_tarifarios t, hit_categorias cat
											where t.CLAVE = tp.CLAVE_TARIFARIO
													and tp.ALOJAMIENTO = a.ID
													and tp.CARACTERISTICA_HABITACION = hc.CODIGO
													and a.CATEGORIA = cat.CODIGO
													and t.CLAVE = ".$clave."
													and tp.FECHA between '".date("Y-m-d",strtotime($fecha_desde))."' and '".date("Y-m-d",strtotime($fecha_hasta))."'
											GROUP BY cat.NOMBRE, a.NOMBRE, hc.NOMBRE, t.NOCHES, tp.REGIMEN, tp.FECHA
											order by tp.FECHA, cat.NOMBRE, a.NOMBRE, hc.NOMBRE, t.NOCHES, tp.REGIMEN");

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

			$tituloReporte = "Cálculos del Tarifario: ";
			$titulosColumnas = array('ALOJAMIENTO','ACUERDO','CATEGORIA','HABITACION','NOCHES','REGIMEN','FECHA','DIA','REDONDEO','CALCULO TRANSPORTES','CALCULO ALOJAMIENTOS','CALCULO SERVICIOS','PVP');
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
				->setCellValue('M3',  $titulosColumnas[12]);
	
			/*Como pueden apreciar para asignar contenido a una celda se selecciona primero la hoja con setActiveSheetIndex(Indice de hoja) y después con setCellValue(celda, valor) asignamos el contenido a la celda deseada.*/

			/*Ahora procedemos a rellenar las columnas con la información de los alumnos, para ello vamos a recorrer el resultado utilizando un while, las celdas se van a rellenar de forma parecida al paso anterior solo que aquí para indicar el número de fila se va a utilizar una variable que se va a estar incrementando cada vez que se rellene la información de un alumno.*/

			$titulosColumnas = array('ALOJAMIENTO','ACUERDO','CATEGORIA','HABITACION','NOCHES','REGIMEN','FECHA','DIA','REDONDEO','CALCULO TRANSPORTES','CALCULO ALOJAMIENTOS','CALCULO SERVICIOS','PVP');
	 
			//Se agregan los datos de los alumnos
 
			 $i = 4; //Numero de fila donde se va a comenzar a rellenar
			 while ($fila = $resultado->fetch_array()) {
				 $objPHPExcel->setActiveSheetIndex(0)
					 ->setCellValue('A'.$i, $fila['alojamiento'])
					 ->setCellValue('B'.$i, $fila['acuerdo'])
					 ->setCellValue('C'.$i, $fila['categoria'])
					 ->setCellValue('D'.$i, $fila['habitacion'])
					 ->setCellValue('E'.$i, $fila['noches'])
					 ->setCellValue('F'.$i, $fila['regimen'])
					 ->setCellValue('G'.$i, $fila['fecha'])
					 ->setCellValue('H'.$i, $fila['dia'])
					 ->setCellValue('I'.$i, $fila['redondeo'])
					 ->setCellValue('J'.$i, $fila['calculo_transportes'])
					 ->setCellValue('K'.$i, $fila['calculo_alojamientos'])
					 ->setCellValue('L'.$i, $fila['calculo_servicios'])
					 ->setCellValue('M'.$i, $fila['pvp']);
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
			$objPHPExcel->getActiveSheet()->getStyle('A3:M3')->applyFromArray($estiloTituloColumnas);
			$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:M".($i-1));
 
			/*Como pueden ver el formato se aplicó de 2 formas diferentes una con getStyle(celda)->applyFromArray($arreglo) y la otra con setSharedStyle($estilo, celdas). ¿Cuál es la diferencia entre una y otra?, esto lo podemos apreciar cuando se aplican bordes: con la primer opción por ejemplo si se indica que se van a aplicar bordes superiores a las celdas desde B5 hasta F15, solo estaría aplicando el borde superior a la fila 5 debido a que toma el rango como si fuera una sola celda. En cambio con la segunda opción los bordes superiores se aplicarían a todas las filas desde la 5 hasta la 15 digamos que el formato se aplica a cada celda del rango indicado.*/

			/*Ahora procedemos a asignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas y lo hacemos con un ciclo de la siguiente forma.*/

			for($i = 'A'; $i <= 'M'; $i++){
				$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
			}
	
			/*Bien, ahora solo agregamos algunos detalles mas*/
			
			// Se asigna el nombre a la hoja
			$objPHPExcel->getActiveSheet()->setTitle('Consulta_Reservas');
			 
			// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
			$objPHPExcel->setActiveSheetIndex(0);
			 
			// Inmovilizar paneles
			//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
			$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

			/*Ya para terminar vamos a enviar el archivo para que el usuario lo descargue.*/
			
			// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Calculo_Costes_Tarifario.xlsx"');
			header('Cache-Control: max-age=0');
			 
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			

			
			
			exit;
		}
	}else{
		print_r('No hay resultados para mostrar');
	}


?>


