<?php
# Cargamos la librería dompdf.
require_once 'dompdf_config.inc.php';

//$parametrosg = $_GET;



//$url = $parametrosg['url'];

$url= 'http://www.panavision-tours.es/rol2/pag/bono.jsp?localizador=615607&Reserva=615607';




 $mensaje_html = file_get_contents($url);  

# Instanciamos un objeto de la clase DOMPDF.
$mipdf = new DOMPDF();
 
# Definimos el tamaño y orientación del papel que queremos.
# O por defecto cogerá el que está en el fichero de configuración.
$mipdf ->set_paper("A4", "landscape");
 
# Cargamos el contenido HTML.
$mipdf ->load_html($mensaje_html,'UTF-8'); 
 
# Renderizamos el documento PDF.
$mipdf ->render();
 
# Enviamos el fichero PDF al navegador.
$nombre_pdf = "Hitravel_Reserva_".ucfirst(strtolower($abono_cargo_tipo))."_".$abono_cargo_factura.".pdf";
$mipdf ->stream($nombre_pdf);


?>


