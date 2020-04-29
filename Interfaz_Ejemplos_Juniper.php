<?php

class OTA_HotelDescriptiveInfoService{

	function OTA_HotelDescriptiveInfoRQ()
	{
		$this->client = new
		SoapClient('http://xml2.bookingengine.es/WebService/OTA_HotelDescriptiveInfo.asmx?wsdl', array(
		'exceptions' => 0,
		'trace' => 1,
		'connection_timeout' => 1800
		));

		@$this->client->OTA_HotelDescriptiveInfoService->xmlns = 'https://www.ejuniper.com/es';
		@$OTA_HotelDescriptiveInfoService = $this->client->OTA_HotelDescriptiveInfoService;
		@$OTA_HotelDescriptiveInfoService->OTA_HotelDescriptiveInfoRQ->PrimaryLangID = "es";
 
		@$OTA_HotelDescriptiveInfoService->OTA_HotelDescriptiveInfoRQ->POS->Source->AgentDutyCode = "jfrias@hitravel.es";
		@$OTA_HotelDescriptiveInfoService->OTA_HotelDescriptiveInfoRQ->POS->Source->RequestorID->Type = "1";
		@$OTA_HotelDescriptiveInfoService->OTA_HotelDescriptiveInfoRQ->POS->Source->RequestorID->MessagePassword = "mHG9QtFrCj";
		//@$OTA_HotelDescriptiveInfoService->OTA_HotelDescriptiveInfoRQ->HotelDescriptiveInfos->HotelDescriptiveInfo->HotelCode = "JP633562";  
		@$OTA_HotelDescriptiveInfoService->OTA_HotelDescriptiveInfoRQ->HotelDescriptiveInfos->HotelDescriptiveInfo->HotelCode = "JP131823"; 
		//JP633563 // JP633562// HotelCode=" BzVqeJQ79HKBCSNRDwRzQA==" JPCode="JP633562
		//http://54.229.179.250/Interfaz_Ejemplos_Juniper.php

		$dataRQ = $OTA_HotelDescriptiveInfoService;

		try {
			$rp = $this->client->__soapCall('OTA_HotelDescriptiveInfoService', array('OTA_HotelDescriptiveInfoRQ' => $dataRQ));
		} catch (SoapFault $exception){
			$rp = $exception;
		}

		print_r ($rp);
	}
}

$obj = new OTA_HotelDescriptiveInfoService();
$obj->OTA_HotelDescriptiveInfoRQ();

//print_r ($obj->OTA_HotelDescriptiveInfoRQ());

?>
