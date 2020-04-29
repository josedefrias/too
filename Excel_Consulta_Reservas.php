<?php

 	//include 'funciones_php/conexiones.php';
	require_once 'PHPExcel.php';
	//include 'PHPExcel/IOFactory.php';
	//include 'PHPExcel/Writer/Excel5.php';
	
$parametrosg = $_GET;

$buscar_salida_desde = $parametrosg['buscar_salida_desde'];
$buscar_salida_hasta = $parametrosg['buscar_salida_hasta']; 
$buscar_reservas_desde = $parametrosg['buscar_reserva_desde']; 
$buscar_reservas_hasta = $parametrosg['buscar_reserva_hasta']; 
$buscar_localizador = $parametrosg['buscar_localizador']; 
$buscar_agencia = $parametrosg['buscar_agencia']; 
$orden = $parametrosg['orden']; 
$buscar_origen = $parametrosg['buscar_origen'];

$conexion = new mysqli('testdb.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com','hit','dacasale','too');
//$conexion = new mysqli('testdb.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com','hit','dacasale','hit');
//$conexion = new mysqli('bikefriendly.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com','hit','bikef2015','hit');
if (mysqli_connect_errno()) {
   printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
   exit();
}
//$conexion = conexion_hit();

//Montamos los parametros de busqueda
		if($buscar_localizador != null){
			$CADENA_BUSCAR = " and r.localizador = '".$buscar_localizador."'";
		}elseif($buscar_reservas_desde != null and $buscar_reservas_hasta != null){
			$periodo_salid = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$orig = " AND rt.origen = '".$buscar_origen."'";
			$CADENA_BUSCAR = " and r.fecha_reserva between '".date("Y-m-d",strtotime($buscar_reservas_desde))."' and '".date("Y-m-d",strtotime($buscar_reservas_hasta))."'";
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}	
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
			if($buscar_salida_desde != null and $buscar_salida_hasta != null){
				$CADENA_BUSCAR .= $periodo_salid;	
			}	
		}elseif($buscar_salida_desde != null and $buscar_salida_hasta != null){
			$agenc = " AND m.nombre LIKE '%".$buscar_agencia."%'";
			$orig = " AND rt.origen = '".$buscar_origen."'";
			$CADENA_BUSCAR = " and r.fecha_salida between '".date("Y-m-d",strtotime($buscar_salida_desde))."' and '".date("Y-m-d",strtotime($buscar_salida_hasta))."'";
			if($buscar_agencia != null){
				$CADENA_BUSCAR .= $agenc;	
			}
			if($buscar_origen != null){
				$CADENA_BUSCAR .= $orig;	
			}
		}else{
			$CADENA_BUSCAR = " and r.localizador = '0'"; 
		}	

		if($orden == 'LOC'){
			$CADENA_ORDEN = " ORDER BY r.localizador";
		}elseif($orden == 'SAL_LOC'){
			$CADENA_ORDEN = " ORDER BY r.fecha_salida, r.localizador";			
		}elseif($orden == 'RES_LOC'){
			$CADENA_ORDEN = " ORDER BY r.fecha_reserva, r.localizador";			
		}else{
			$CADENA_ORDEN = " ORDER BY r.localizador";			
		}

					//OBTENEMOS NOMBRE DEL VIAJE Á
					$resultado =$conexion->query("select r.LOCALIZADOR localizador, r.SITUACION situacion, 
												 DATE_FORMAT(r.FECHA_RESERVA, '%d-%m-%Y') AS fecha_reserva,
												 DATE_FORMAT(r.FECHA_SALIDA, '%d-%m-%Y') AS fecha_salida,
												 rt.ORIGEN origen,
												 rt.DESTINO destino,
												 concat(rt.CIA,'/',rt.VUELO,' ',time_format(rt.HORA_SALIDA, '%H:%i'),'-',time_format(rt.HORA_LLEGADA, '%H:%i')) vuelo,
												 concat(r.PAX,' (',r.ADULTOS,'+',r.NINOS,'+',r.BEBES,')') pax_reserva,
												 p.APELLIDO apellido, p.NOMBRE nombre, 
												 a.NOMBRE hotel, 
												 DATE_FORMAT(ra.FECHA_ENTRADA, '%d-%m-%Y') AS fecha_entrada,
												 ra.NOCHES noches,  ac.NOMBRE habitacion, count(ra.HABITACION) cantidad, ra.SITUACION situacion_hotel,
												 concat(sum(ra.PAX),' (',sum(ra.ADULTOS),'+',sum(ra.NINOS1),'+',sum(ra.BEBES),')') pax_hotel,
												 m.NOMBRE agencia, o.LOCALIDAD localidad, o.DIRECCION direccion, o.TELEFONO telefono, o.MAIL mail, 
												 o.USUARIO_WEB usuario_web, o.PASSWORD_WEB password_web
										from
											hit_reservas r left join hit_reservas_pasajeros p on r.LOCALIZADOR = p.LOCALIZADOR and p.NUMERO = 1
																left join hit_reservas_alojamientos ra on r.LOCALIZADOR = ra.LOCALIZADOR
																			 left join hit_alojamientos a on ra.ALOJAMIENTO = a.ID
																			 left join hit_habitaciones_car ac on ra.CARACTERISTICA = ac.CODIGO
																left join hit_reservas_aereos_trayectos rt on r.LOCALIZADOR = rt.LOCALIZADOR and r.FECHA_SALIDA = rt.FECHA,
											hit_oficinas o, 
											hit_minoristas m
										where
											r.MINORISTA = o.ID
											and r.OFICINA = o.OFICINA
											and o.ID = m.ID
											and r.SITUACION <> 'A' ".$CADENA_BUSCAR." group by r.LOCALIZADOR, r.SITUACION, r.FECHA_RESERVA, r.FECHA_SALIDA, a.NOMBRE, ra.FECHA_ENTRADA, ra.NOCHES, ac.NOMBRE, ra.SITUACION, ac.NOMBRE,
												 p.APELLIDO, p.NOMBRE
										".$CADENA_ORDEN);

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
 
			$tituloReporte = "Consulta de reservas por parámetros: ";
			$titulosColumnas = array('LOC', 'SIT', 'F RESERVA', 'F SALIDA', 'ORG', 'DES', 'VUELO', 'PAX', 'APELLIDO', 'NOMBR', 'HOTEL', 'ENTRADA', 'NOC', 'HABITACION', 'CAN', 'SIT', 'PAX HOTEL', 'AGENCIA', 'LOCALIDAD', 'DIRECCION', 'TELEFONO', 'MAIL', 'USUARIO WEB', 'PASSWORD WEB');
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
				->setCellValue('X3',  $titulosColumnas[23]);
	
			/*Como pueden apreciar para asignar contenido a una celda se selecciona primero la hoja con setActiveSheetIndex(Indice de hoja) y después con setCellValue(celda, valor) asignamos el contenido a la celda deseada.*/

			/*Ahora procedemos a rellenar las columnas con la información de los alumnos, para ello vamos a recorrer el resultado utilizando un while, las celdas se van a rellenar de forma parecida al paso anterior solo que aquí para indicar el número de fila se va a utilizar una variable que se va a estar incrementando cada vez que se rellene la información de un alumno.*/
	 
			//Se agregan los datos de los alumnos
 
			 $i = 4; //Numero de fila donde se va a comenzar a rellenar
			 while ($fila = $resultado->fetch_array()) {
				 $objPHPExcel->setActiveSheetIndex(0)
					 ->setCellValue('A'.$i, $fila['localizador'])
					 ->setCellValue('B'.$i, $fila['situacion'])
					 ->setCellValue('C'.$i, $fila['fecha_reserva'])
					 ->setCellValue('D'.$i, $fila['fecha_salida'])
					 ->setCellValue('E'.$i, $fila['origen'])
					 ->setCellValue('F'.$i, $fila['destino'])
					 ->setCellValue('G'.$i, $fila['vuelo'])
					 ->setCellValue('H'.$i, $fila['pax_reserva'])
					 ->setCellValue('I'.$i, $fila['apellido'])
					 ->setCellValue('J'.$i, $fila['nombre'])
					 ->setCellValue('K'.$i, $fila['hotel'])
					 ->setCellValue('L'.$i, $fila['fecha_entrada'])
					 ->setCellValue('M'.$i, $fila['noches'])
					 ->setCellValue('N'.$i, $fila['habitacion'])
					 ->setCellValue('O'.$i, $fila['cantidad'])
					 ->setCellValue('P'.$i, $fila['situacion_hotel'])
					 ->setCellValue('Q'.$i, $fila['pax_hotel'])
					 ->setCellValue('R'.$i, $fila['agencia'])
					 ->setCellValue('S'.$i, $fila['localidad'])
					 ->setCellValue('T'.$i, $fila['direccion'])
					 ->setCellValue('U'.$i, $fila['telefono'])
					 ->setCellValue('V'.$i, $fila['mail'])
					 ->setCellValue('W'.$i, $fila['usuario_web'])
					 ->setCellValue('X'.$i, $fila['password_web']);
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
			$objPHPExcel->getActiveSheet()->getStyle('A3:X3')->applyFromArray($estiloTituloColumnas);
			$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:X".($i-1));
 
			/*Como pueden ver el formato se aplicó de 2 formas diferentes una con getStyle(celda)->applyFromArray($arreglo) y la otra con setSharedStyle($estilo, celdas). ¿Cuál es la diferencia entre una y otra?, esto lo podemos apreciar cuando se aplican bordes: con la primer opción por ejemplo si se indica que se van a aplicar bordes superiores a las celdas desde B5 hasta F15, solo estaría aplicando el borde superior a la fila 5 debido a que toma el rango como si fuera una sola celda. En cambio con la segunda opción los bordes superiores se aplicarían a todas las filas desde la 5 hasta la 15 digamos que el formato se aplica a cada celda del rango indicado.*/

			/*Ahora procedemos a asignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas y lo hacemos con un ciclo de la siguiente forma.*/

			for($i = 'A'; $i <= 'X'; $i++){
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
			header('Content-Disposition: attachment;filename="Consulta_Reservas.xlsx"');
			header('Cache-Control: max-age=0');
			 
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			

			
			
			exit;
		}
	}else{
		print_r('No hay resultados para mostrar');
	}


?>


