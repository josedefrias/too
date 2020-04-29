<?php

include 'funciones_php/conexiones.php';

$conexion = conexion_hit();

$resultado =$conexion->query("select e.ID, e.ASUNTO, ec.CONTENIDO, e.REMITENTE_EMAIL, e.REMITENTE_ID1, e.REMITENTE_ID2, e.PROVINCIA
								from hit_envios e, hit_envios_contenido ec 
								where 
								e.CONTENIDO = ec.ID
								and estado = 'EA'");

		//for ($num_fila = 0; $num_fila < $resultado->num_rows;  $num_fila++) {

		for ($num_fila = 0; $num_fila < 300;  $num_fila++) {
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();

			if($fila['PROVINCIA'] != null){
				echo('Provincia: '.$fila['PROVINCIA'].'- Asunto: '.$fila['ASUNTO'].'- Email: '.$fila['REMITENTE_EMAIL'].'- Agencia: '.$fila['REMITENTE_ID1'].'- Oficina: '.$fila['REMITENTE_ID2'].'<BR>');
			}

			//ENVIAMOS EL MAIL A LA ASEGURADORA
			$responsable = '';
			$asunto = $fila['ASUNTO'];
			$mensaje_html = $fila['CONTENIDO'];
			$mail = $fila['REMITENTE_EMAIL'];

			if($mail != ''){
				$envio = enviar_multienvio($asunto, $mensaje_html, $mail, $responsable);		

				if($envio == 'OK'){
					$query = "UPDATE hit_envios SET ";
					$query .= " ESTADO = 'OK'";
					$query .= ", FECHA_ENVIO = CURRENT_TIMESTAMP()";
					$query .= " WHERE ID = '".$fila['ID']."'";
					$resultadom =$conexion->query($query);
					/*if ($resultadom == FALSE){
						$respuesta = $conexion->error;
					}else{
						$respuesta = 'OK';
					}*/
				}else{
					$query = "UPDATE hit_envios SET ";
					$query .= " ESTADO = 'ER'";
					$query .= ", FECHA_ENVIO = CURRENT_TIMESTAMP()";
					$query .= " WHERE ID = '".$fila['ID']."'";
					$resultadom =$conexion->query($query);
				}
			}








		}

?>


