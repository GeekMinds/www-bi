<?php
	require_once("models/config.php");
	require_once("models/funcs.report_xls_pdf.php");

	$parameters = getParameters($_POST,$_GET);
	$callback=getCallback($parameters);
	$action= getAction($parameters);
	$result=array();

	switch($action){
		case 'getreport':
			$result=getreport($parameters);
			break;

		case 'getpage':
			$result=getPage($parameters);
			break;

		case 'notlogued':
        	$result['Result'] = 'ERROR';
        	$result['Message'] = 'Usuario no logueado';
        	break;

		default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
	}

	function getreport($parameters){
		$result=getreport_export($parameters);
		return $result;
	}

	

?>