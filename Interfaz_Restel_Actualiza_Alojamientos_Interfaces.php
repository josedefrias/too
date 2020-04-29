<?php

	include 'funciones_php/conexiones.php';
	require 'clases/alojamientos_acuerdos.cls.php';

	$conexion = conexion_hit();

	$alojamientos_cargados = 0;
	$alojamientos_interfaces_cargados = 0;
	$alojamientos_contratos_cargados = 0;

	$Usuario = 'jfrias';
	$buscar_interfaz = 'RESTEL';



	//primero hay que ejecutar manualmente  estas tres querys para actualizar los duplicados
	/*update hit_alojamientos_interfaces ai set codigo_duplicados = 0
	   update hit_alojamientos_interfaces ai set codigo_duplicados = (select ci.codigo_duplicados from hit_interfaces_codigos_hoteles ci where ci.CODIGO = ai.CODIGO_EXTERNO)*/
	//actualizamos a cero todos los codigos externos
		/*$query_inicializa = "update hit_alojamientos_interfaces ai set codigo_externo_2 = 0, codigo_externo_3 = 0, codigo_externo_4 = 0, codigo_externo_5 = 0, codigo_externo_6 = 0, codigo_externo_7 = 0, codigo_externo_8 = 0, codigo_externo_9 = 0, codigo_externo_10 = 0, codigo_externo_11 = 0, codigo_externo_12 = 0, codigo_externo_13 = 0, codigo_externo_14 = 0, codigo_externo_completo = 0where codigo_interfaz = 'RESTEL'";

		$resultadom =$conexion->query($query_inicializa);

		if ($resultadom == FALSE){
			$respuesta = $conexion->error;
		}else{
			$respuesta = 'OK';
		}*/



	//AQUI NECESITAMSO LA QUERY PARA IR LLAMANDADO A TODO EL PROCEDIMIENTO CON CADA LINEA
	/*function Insertar($codigo,$ciudad_provincia,$divisa){*/



	$resultado =$conexion->query("select codigo_externo, codigo_duplicados from hit_alojamientos_interfaces where codigo_interfaz = 'RESTEL'");

	for ($k = 0; $k <= $resultado->num_rows; $k++) {
		$resultado->data_seek($k);
		$fila = $resultado->fetch_assoc();


		$codigo = $fila['codigo_externo'];
		$codigo_dup = $fila['codigo_duplicados'];




		//-------------------------------------------------------------------------
		//ACTUALIZAMOS LOS CODIGOS EXTERNOS SI HAY DUPLICADOS
		//-------------------------------------------------------------------------

		/*$datos_codigo_dup =$conexion->query("select codigo_duplicados from hit_interfaces_codigos_hoteles where codigo = '".$hotel->codigo_hotel."'");
		$odatos_codigo_dup = $datos_codigo_dup->fetch_assoc();
		$codigo_dup = $odatos_codigo_dup['codigo_duplicados'];*/

		$codigo_completo = $codigo."#";

		if($codigo_dup != 0 and $codigo_dup != '' and $codigo_dup != null){
			//echo('hay duplicados');
			$resultado2 =$conexion->query("select codigo from hit_interfaces_codigos_hoteles where codigo_duplicados = '".$codigo_dup."' and codigo <> '".$codigo."'");

			for ($num_fila = 0; $num_fila <= $resultado2->num_rows; $num_fila++) {
				$resultado2->data_seek($num_fila);
				$fila = $resultado2->fetch_assoc();
				//echo(' Actualizamos los codigos: '.$fila['codigo']);
				//Actualizamos los codigos duplicados del interfaz
				switch ($num_fila) {
				   case 0:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_2= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod=$conexion->query($query);
				          break;
				   case 1:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_3= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;
				   case 2:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_4= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;
				    case 3:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_5= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;
				     case 4:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_6= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;
				     case 5:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_7= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;
				     case 6:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_8= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;
				     case 7:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_9= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;		
				     case 8:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_10= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;	
				      case 9:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_11= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;	
				     case 10:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_12= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;	
				     case 11:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_13= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;	
				     case 12:
					$query = "UPDATE hit_alojamientos_interfaces SET ";
					$query .= "CODIGO_EXTERNO_14= '".$fila['codigo']."'";
					$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
					$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
					$resultado_cod =$conexion->query($query);
				          break;				 
				}

				if($fila['codigo'] != null){
					$codigo_completo .= $fila['codigo']."#";
				}
			}

			$resultado2->close();			
		}

		$query = "UPDATE hit_alojamientos_interfaces SET ";
		$query .= "CODIGO_EXTERNO_COMPLETO= '".$codigo_completo."'";
		$query .= " WHERE CODIGO_EXTERNO = '".$codigo."'";
		$query .= " AND CODIGO_INTERFAZ = 'RESTEL'";
		$resultado_cod=$conexion->query($query);


									
	}

?>