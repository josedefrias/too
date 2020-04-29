<?php

 	//include 'funciones_php/conexiones.php';
	require_once 'PHPExcel.php';
	//include 'PHPExcel/IOFactory.php';
	//include 'PHPExcel/Writer/Excel5.php';
	
$parametrosg = $_GET;

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
					$resultado =$conexion->query("select distinct 
														 p.NOMBRE producto,
														 c.NOMBRE viaje,
														 r.localizador localizador,
														 DATE_FORMAT(r.FECHA_SALIDA, '%d-%m-%Y') AS salida,
														 r.PAX pax,
														 pax.APELLIDO apellidos_primer_pax,
														 pax.NOMBRE nombre_primer_pax,
														 r.FACTURA factura,
													    r.PVP_COMISIONABLE + r.PVP_NO_COMISIONABLE total_bruto,
													    r.PVP_COMISION porcentaje_comision,
													    r.PVP_COMISIONABLE comisionable,
													    r.PVP_IMPORTE_COMISION comision, 
														 r.PVP_IMPUESTO_COMISION iva_comision, 
														 r.PVP_NO_COMISIONABLE no_comisionable,
														 r.PVP_TOTAL total_factura,
														 DATE_FORMAT(r.FACTURA_FECHA_EMISION, '%d-%m-%Y') AS fecha_factura,
														 r.PRECOBRO pagado,

														 case o.FACTURACION
														 	when 'C' then 'Central'
														 	when 'O' then 'Oficina'
														 	else o.FACTURACION
														 end facturacion,
														 
														 case o.FACTURACION
														 	when 'C' then case m.NOMBRE_FISCAL when '' then m.NOMBRE else m.NOMBRE_FISCAL end
														 	when 'O' then case m.NOMBRE_FISCAL when '' then m.NOMBRE else m.NOMBRE_FISCAL end
														 	else m.NOMBRE_FISCAL
														 end nombre_fiscal,

													    case o.FACTURACION
														 	when 'C' then case m.CIF when '' then m.CIF else m.CIF end
														 	when 'O' then case m.CIF when '' then m.CIF else m.CIF end
														 	else m.CIF
														 end cif,
														 
														 case o.FACTURACION
														 	when 'C' then case m.DIRECCION_FISCAL when '' then m.DIRECCION else m.DIRECCION_FISCAL end
														 	when 'O' then case o.DIRECCION_FISCAL when '' then o.DIRECCION else o.DIRECCION_FISCAL end
														 	else o.DIRECCION
														 end direccion_fiscal,
														 
														 case o.FACTURACION
														 	when 'C' then case m.LOCALIDAD_FISCAL when '' then m.LOCALIDAD else m.LOCALIDAD_FISCAL end
														 	when 'O' then case o.LOCALIDAD_FISCAL when '' then o.LOCALIDAD else o.LOCALIDAD_FISCAL end
														 	else o.LOCALIDAD_FISCAL
														 end localidad_fiscal,
														 r.PRECOBRO precobro,
														 DATE_FORMAT(r.FECHA_PAGO, '%d-%m-%Y') AS fecha_pago,
														 case r.METODO_PAGO
														 	when 'T' then 'Transferencia'
														 	when 'C' then 'Tarjeta'
														 	else r.METODO_PAGO
														 end metodo_pago,
														 
														 al.NOMBRE alojamiento,

														 cor.NOMBRE corresponsal,
														 
														 case cor.ID
														 	when 220 then al.DIRECCION
														 	else ''
														  end direccion_alojamiento,
														 case cor.ID
														 	when 220 then al.CODIGO_POSTAL
														 	else ''
														  end cp_alojamiento,
														  case cor.ID
														 	when 220 then al.LOCALIDAD
														 	else ''
														  end localidad_alojamiento,
														  case cor.ID
														 	when 220 then al.PROVINCIA
														 	else ''
														  end provincia_alojamiento ,
														  case cor.ID
														 	when 220 then al.CIF
														 	else ''
														  end cif_alojamiento	
												from
													hit_reservas r left join hit_reservas_alojamientos ra on r.LOCALIZADOR = ra.LOCALIZADOR left join hit_alojamientos al on ra.ALOJAMIENTO = al.ID
																																										 left join hit_alojamientos_acuerdos aac on aac.ID = ra.ALOJAMIENTO and  aac.ACUERDO = ra.ACUERDO
																																										 													left join hit_corresponsales cor on aac.CORRESPONSAL = cor.ID,
													
													hit_oficinas o, hit_minoristas m, hit_producto_cuadros c, hit_productos p, hit_reservas_pasajeros pax
												where
													r.MINORISTA = o.ID
													and r.OFICINA = o.OFICINA
													and o.ID = m.ID
													and r.FOLLETO = c.FOLLETO
													and r.CUADRO = c.CUADRO
													and c.PRODUCTO = p.CODIGO
													and r.LOCALIZADOR = pax.LOCALIZADOR
													and pax.NUMERO = 1
													and r.FACTURA > 0
													and r.fecha_salida between '".date("Y-m-d",strtotime($fecha_desde))."' and '".date("Y-m-d",strtotime($fecha_hasta))."'
												order by p.NOMBRE, r.factura");


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
 
			$tituloReporte = "Informacion de Facturacion por Periodo de Salida: ".$fecha_desde." hasta ".$fecha_hasta;
			$titulosColumnas = array('PRODUCTO', 'VIAJE', 'LOCALIZADOR', 'SALIDA', 'PAX', 'APELLIDOS PRIMER PAX', 'NOMBRE PRIMER PAX',
				'FACTURA','TOTAL BRUTO','PORCENTAJE COMISION','COMISIONABLE','COMISION','IMPUESTO COMISION','NO COMISIONABLE','TOTAL FACTURA',
				'FECHA FACTURA','PAGADO','FACTURACION','NOMBRE FISCAL','CIF','DIRECCION FISCAL','LOCALIDAD FISCAL','PRECOBRO','FECHA PAGO',
				'METODO PAGO','ALOJAMIENTO','CORRESPONSAL','DIRECCION ALOJAMIENTO','CP ALOJAMIENTO','LOCALIDAD ALOJAMIENTO','PROVINCIA ALOJAMIENTO','CIF ALOJAMIENTO');
			/*El reporte como ya se habrán dado cuenta va a tener N columnas: Localizador, folleto, .... Por lo tanto solo vamos a ocupar hasta la columna ...*/

			// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
			$objPHPExcel->setActiveSheetIndex(0)
				->mergeCells('A1:J1');
			 
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
				->setCellValue('AF3',  $titulosColumnas[31]);
	
			/*Como pueden apreciar para asignar contenido a una celda se selecciona primero la hoja con setActiveSheetIndex(Indice de hoja) y después con setCellValue(celda, valor) asignamos el contenido a la celda deseada.*/

			/*Ahora procedemos a rellenar las columnas con la información de los alumnos, para ello vamos a recorrer el resultado utilizando un while, las celdas se van a rellenar de forma parecida al paso anterior solo que aquí para indicar el número de fila se va a utilizar una variable que se va a estar incrementando cada vez que se rellene la información de un alumno.*/
	 
			//Se agregan los datos de los alumnos
 
			 $i = 4; //Numero de fila donde se va a comenzar a rellenar
			 while ($fila = $resultado->fetch_array()) {
				 $objPHPExcel->setActiveSheetIndex(0)
					 ->setCellValue('A'.$i, $fila['producto'])
					 ->setCellValue('B'.$i, $fila['viaje'])
					 ->setCellValue('C'.$i, $fila['localizador'])
					 ->setCellValue('D'.$i, $fila['salida'])
					 ->setCellValue('E'.$i, $fila['pax'])
					 ->setCellValue('F'.$i, $fila['apellidos_primer_pax'])
					 ->setCellValue('G'.$i, $fila['nombre_primer_pax'])
					 ->setCellValue('H'.$i, $fila['factura'])
					 ->setCellValue('I'.$i, $fila['total_bruto'])
					 ->setCellValue('J'.$i, $fila['porcentaje_comision'])
					 ->setCellValue('K'.$i, $fila['comisionable'])
					 ->setCellValue('L'.$i, $fila['comision'])
					 ->setCellValue('M'.$i, $fila['iva_comision'])
					 ->setCellValue('N'.$i, $fila['no_comisionable'])
					 ->setCellValue('O'.$i, $fila['total_factura'])
					 ->setCellValue('P'.$i, $fila['fecha_factura'])
					 ->setCellValue('Q'.$i, $fila['pagado'])
					 ->setCellValue('R'.$i, $fila['facturacion'])
					 ->setCellValue('S'.$i, $fila['nombre_fiscal'])
					 ->setCellValue('T'.$i, $fila['cif'])
					 ->setCellValue('U'.$i, $fila['direccion_fiscal'])
					 ->setCellValue('V'.$i, $fila['localidad_fiscal'])
					 ->setCellValue('W'.$i, $fila['precobro'])
					 ->setCellValue('X'.$i, $fila['fecha_pago'])
					 ->setCellValue('Y'.$i, $fila['metodo_pago'])
					 ->setCellValue('Z'.$i, $fila['alojamiento'])
					 ->setCellValue('AA'.$i, $fila['corresponsal'])
					 ->setCellValue('AB'.$i, $fila['direccion_alojamiento'])
					 ->setCellValue('AC'.$i, $fila['cp_alojamiento'])
					 ->setCellValue('AD'.$i, $fila['localidad_alojamiento'])
					 ->setCellValue('AE'.$i, $fila['provincia_alojamiento'])
					 ->setCellValue('AF'.$i, $fila['cif_alojamiento']);
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
			$objPHPExcel->getActiveSheet()->getStyle('A3:AF3')->applyFromArray($estiloTituloColumnas);
			$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:AF".($i-1));
 
			/*Como pueden ver el formato se aplicó de 2 formas diferentes una con getStyle(celda)->applyFromArray($arreglo) y la otra con setSharedStyle($estilo, celdas). ¿Cuál es la diferencia entre una y otra?, esto lo podemos apreciar cuando se aplican bordes: con la primer opción por ejemplo si se indica que se van a aplicar bordes superiores a las celdas desde B5 hasta F15, solo estaría aplicando el borde superior a la fila 5 debido a que toma el rango como si fuera una sola celda. En cambio con la segunda opción los bordes superiores se aplicarían a todas las filas desde la 5 hasta la 15 digamos que el formato se aplica a cada celda del rango indicado.*/

			/*Ahora procedemos a asignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas y lo hacemos con un ciclo de la siguiente forma.*/

			for($i = 'A'; $i <= 'AF'; $i++){
				$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
			}
	
			/*Bien, ahora solo agregamos algunos detalles mas*/
			
			// Se asigna el nombre a la hoja
			$objPHPExcel->getActiveSheet()->setTitle('Facturacion');
			 
			// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
			$objPHPExcel->setActiveSheetIndex(0);
			 
			// Inmovilizar paneles
			//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
			$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

			/*Ya para terminar vamos a enviar el archivo para que el usuario lo descargue.*/
			
			// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Facturacion.xlsx"');
			header('Cache-Control: max-age=0');
			 
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			

			
			
			exit;
		}
	}else{
		print_r('No hay resultados para mostrar');
	}


?>


