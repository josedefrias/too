<?php

 	//include 'funciones_php/conexiones.php';
	require_once 'PHPExcel.php';
	//include 'PHPExcel/IOFactory.php';
	//include 'PHPExcel/Writer/Excel5.php';
	
$parametrosg = $_GET;

$clave = $parametrosg['clave'];
$fecha_desde = $parametrosg['fecha_desde'];
$fecha_hasta = $parametrosg['fecha_hasta'];

//echo($clave.'-'.$fecha_desde.'-'.$fecha_hasta);

$conexion = new mysqli('testdb.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com','hit','dacasale','too');
//$conexion = new mysqli('testdb.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com','hit','dacasale','hit');
//$conexion = new mysqli('bikefriendly.cs8sjl1o1etb.eu-west-1.rds.amazonaws.com','hit','bikef2015','hit');
if (mysqli_connect_errno()) {
   printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
   exit();
}

					//OBTENEMOS DATOS
	
	/*vueltas = count($sCargos);*/


		$Datos_tarifario =$conexion->query("SELECT e.NOMBRE ENTIDAD, d.NOMBRE DESTINO, t.anno ANNO 
												FROM hit_tarifarios t, hit_entidades e, hit_destinos d
												WHERE 
												t.ENTIDAD = e.CODIGO
												and t.DESTINO = d.CODIGO
												and t.clave = '".$clave."'");
		/*$columnas_fechas = $cantidad_fechas->num_rows;
		echo($columnas_fechas);*/
		$Datos	 = $Datos_tarifario->fetch_assoc();	
		$entidad = $Datos['ENTIDAD'];
		$destino = $Datos['DESTINO'];
		$anno = $Datos['ANNO'];

					//Obtenemos fechas del tarifario
					$Datos_fechas =$conexion->query("select distinct tp.FECHA
														from hit_tarifarios_precios tp
														where tp.CLAVE_TARIFARIO = '".$clave."' and tp.FECHA between '".date("Y-m-d",strtotime($fecha_desde))."' and '".date("Y-m-d",strtotime($fecha_hasta))."' order by tp.FECHA");		
					$columnas_fechas = $Datos_fechas->num_rows;	

					for ($i = 0; $i <= $Datos_fechas->num_rows; $i++) {
							$Datos_fechas->data_seek($i);
							$fila = $Datos_fechas->fetch_assoc();
							/*echo($fila['FECHA'].'<BR>');*/
					}



					/*for($i = 'A'; $i <= $columna_hasta; $i++){
							ECHO($i.'-');
						}		*/	


					//Obtenemos lineas del tarifario
					$query = "select
											a.nombre NOMBRE, a.CIF CIF, cat.NOMBRE CAT, hc.NOMBRE HABITACION, t.NOCHES NOCHES, tp.REGIMEN REGIMEN ";
						

					for ($i = 0; $i < $Datos_fechas->num_rows; $i++) {	
							$Datos_fechas->data_seek($i);
							$fila = $Datos_fechas->fetch_assoc();

							if($i == 0)		{		

								$query .= ", sum(case tp.FECHA
													when '".$fila['FECHA']."' then tp.PRECIO_TRANSPORTES + tp.PRECIO_ALOJAMIENTOS + tp.PRECIO_SERVICIOS
												end) ".date("d_m",strtotime($fila['FECHA']));
							}else{
												
								$query .= ", sum(case tp.FECHA
													when '".$fila['FECHA']."' then tp.PRECIO_TRANSPORTES + tp.PRECIO_ALOJAMIENTOS + tp.PRECIO_SERVICIOS
												end) ".date("d_m",strtotime($fila['FECHA']));
							}
					}					
											
					$query .= " from hit_tarifarios_precios tp, hit_alojamientos a, hit_habitaciones_car hc, hit_tarifarios t, hit_categorias cat
										where t.CLAVE = tp.CLAVE_TARIFARIO
												and tp.ALOJAMIENTO = a.ID
												and tp.CARACTERISTICA_HABITACION = hc.CODIGO
												and a.CATEGORIA = cat.CODIGO
												and t.CLAVE = '".$clave."'
												and tp.FECHA between '".date("Y-m-d",strtotime($fecha_desde))."' and '".date("Y-m-d",strtotime($fecha_hasta))."' 
										GROUP BY cat.NOMBRE, a.NOMBRE, hc.NOMBRE, t.NOCHES, tp.REGIMEN
										order by cat.NOMBRE, a.NOMBRE, hc.NOMBRE, t.NOCHES, tp.REGIMEN ";
					//echo($query);					

					$resultado =$conexion->query($query);



	/*echo($resultado->num_rows);*/

	/*Lo siguiente es verificar que la consulta obtuvo los registros a mostrar y lo hacemos con la siguiente condición, si el número de registros es mayor que 0 quiere decir que si se obtuvieron datos por lo tanto procedemos a crear el reporte.*/
	if($fecha_desde != '' || $fecha_hasta != ''){
		if($resultado->num_rows > 0 && $columnas_fechas < 21){
		
				
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

	 			//EJEMPLO PARA IR AÑADIENDO CAMPOS AL ARRAY DENTRO DE UN FOR
				/*$array =Array ("casa1"=>1,"casa2"=>2,"casa3"=>3);
				$array["casa4"]="4";
				echo('<pre>');
				print_r($array);
				echo('</pre>'); */

				//CON ESTO RECORREMOS EL CURSOR DE FECHAS OBTENIEDO LAS LETRAS QUE NECESITAMOS
				/*$prueba = 'F';
				for ($i = 0; $i < $Datos_fechas->num_rows; $i++) {
					$Datos_fechas->data_seek($i);
					$fila = $Datos_fechas->fetch_assoc();
					//echo($fila['FECHA'].'<BR>');
					echo($prueba);
					$prueba++;
				}*/

				$tituloReporte = /*"Tarifario ".*/$entidad.": ".$destino." - ".$anno;

				// Se combinan las celdas A1 hasta D1, para colocar ahí el titulo del reporte
				$objPHPExcel->setActiveSheetIndex(0)
					->mergeCells('A1:D1');
				 
				// Se agregan los titulos del reporte
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1',$tituloReporte); // Titulo del reporte



				//Añadimos los titulos de columna fijos
				$titulosColumnas = array('NOMBRE', 'CIF', 'CAT', 'HABITACION', 'NOC', 'REG');
				//$titulosColumnas[5]='06-06';
				$objPHPExcel->setActiveSheetIndex(0)
					//->setCellValue('A1',$tituloReporte) // Titulo del reporte
					->setCellValue('A3',  $titulosColumnas[0])  //Titulo de las columnas
					->setCellValue('B3',  $titulosColumnas[1])
					->setCellValue('C3',  $titulosColumnas[2])
					->setCellValue('D3',  $titulosColumnas[3])
					->setCellValue('E3',  $titulosColumnas[4])
					->setCellValue('F3',  $titulosColumnas[5]);


				$columna = 'G';
				for ($i = 0; $i < $Datos_fechas->num_rows; $i++) {
					$Datos_fechas->data_seek($i);
					$fila = $Datos_fechas->fetch_assoc();
					//$titulosColumnas[$i+5]=$fila['FECHA'];
					$titulosColumnas[$i+5]=date("d-m",strtotime($fila['FECHA']));
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue($columna.'3',  $titulosColumnas[$i+5]);
						$columna++;
				}


				/*Como pueden apreciar para asignar contenido a una celda se selecciona primero la hoja con setActiveSheetIndex(Indice de hoja) y después con setCellValue(celda, valor) asignamos el contenido a la celda deseada.*/

				/*Ahora procedemos a rellenar las columnas con la información de los alumnos, para ello vamos a recorrer el resultado utilizando un while, las celdas se van a rellenar de forma parecida al paso anterior solo que aquí para indicar el número de fila se va a utilizar una variable que se va a estar incrementando cada vez que se rellene la información de un alumno.*/
		 
				//Se agregan los datos de los alumnos
	 
				 $i = 4; //Numero de fila donde se va a comenzar a rellenar
				 while ($fila = $resultado->fetch_array()) {
					 $objPHPExcel->setActiveSheetIndex(0)
						 ->setCellValue('A'.$i, $fila['NOMBRE'])
						 ->setCellValue('B'.$i, $fila['CIF'])
						 ->setCellValue('C'.$i, $fila['CAT'])
						 ->setCellValue('D'.$i, $fila['HABITACION'])
						 ->setCellValue('E'.$i, $fila['NOCHES'])
						 ->setCellValue('F'.$i, $fila['REGIMEN']);

						$columna = 'G';
						for ($j = 0; $j < $Datos_fechas->num_rows; $j++) {
							$Datos_fechas->data_seek($j);
							$fila_fecha = $Datos_fechas->fetch_assoc();
							$titulosColumnas[$j+5]=date("d_m",strtotime($fila_fecha['FECHA']));

							$objPHPExcel->setActiveSheetIndex(0)
							 ->setCellValue($columna.$i, $fila[$titulosColumnas[$j+5]]);
							$columna++;
						}


						 /*$objPHPExcel->setActiveSheetIndex(0)
							 ->setCellValue('F'.$i, $fila['06_06'])
							 ->setCellValue('G'.$i, $fila['13_06'])
							 ->setCellValue('H'.$i, $fila['20_06'])
							 ->setCellValue('I'.$i, $fila['27_06'])
							 ->setCellValue('J'.$i, $fila['04_07'])
							 ->setCellValue('K'.$i, $fila['11_07'])
							 ->setCellValue('L'.$i, $fila['18_07'])
							 ->setCellValue('M'.$i, $fila['25_07'])
							 ->setCellValue('N'.$i, $fila['01_08'])
							 ->setCellValue('O'.$i, $fila['08_08'])
							 ->setCellValue('P'.$i, $fila['15_08'])
							 ->setCellValue('Q'.$i, $fila['22_08'])
							 ->setCellValue('R'.$i, $fila['29_08'])
							 ->setCellValue('S'.$i, $fila['05_09'])
							 ->setCellValue('T'.$i, $fila['12_09'])
							 ->setCellValue('U'.$i, $fila['19_09'])
							 ->setCellValue('V'.$i, $fila['26_09']);*/

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
	 			
				//$columna_hasta = 'V';
				$columna_hasta = $columna;

				$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($estiloTituloReporte);
				$objPHPExcel->getActiveSheet()->getStyle('A3:'.$columna_hasta.'3')->applyFromArray($estiloTituloColumnas);
				$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:".$columna_hasta.($i-1));
	 
				/*Como pueden ver el formato se aplicó de 2 formas diferentes una con getStyle(celda)->applyFromArray($arreglo) y la otra con setSharedStyle($estilo, celdas). ¿Cuál es la diferencia entre una y otra?, esto lo podemos apreciar cuando se aplican bordes: con la primer opción por ejemplo si se indica que se van a aplicar bordes superiores a las celdas desde B5 hasta F15, solo estaría aplicando el borde superior a la fila 5 debido a que toma el rango como si fuera una sola celda. En cambio con la segunda opción los bordes superiores se aplicarían a todas las filas desde la 5 hasta la 15 digamos que el formato se aplica a cada celda del rango indicado.*/

				/*Ahora procedemos a asignar el ancho de las columnas de forma automática en base al contenido de cada una de ellas y lo hacemos con un ciclo de la siguiente forma.*/

				for($i = 'A'; $i <= $columna_hasta; $i++){
					$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);
				}
		
				/*Bien, ahora solo agregamos algunos detalles mas*/
				
				// Se asigna el nombre a la hoja
				$objPHPExcel->getActiveSheet()->setTitle('Tarifario');
				 
				// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
				$objPHPExcel->setActiveSheetIndex(0);
				 
				// Inmovilizar paneles
				//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
				$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

				/*Ya para terminar vamos a enviar el archivo para que el usuario lo descargue.*/
				
				// Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="Tarifario.xlsx"');
				header('Cache-Control: max-age=0');
				 
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				
				exit;
			}
		}else{
			print_r('No hay resultados para mostrar o el numero de fechas del tarifario es mayor de 20.');
		}
	}else{
		print_r('Se debe teclear periodo de fechas para la consulta del tarifario.');
	}

?>


