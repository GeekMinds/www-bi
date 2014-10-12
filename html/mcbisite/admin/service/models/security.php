<?php
require_once 'lib/htmlpurifier/HTMLPurifier.auto.php';
$valid_mime_types = array(
		// images
		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		
		 // adobe
		'pdf' => 'application/pdf',
		
		// audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime'
);

/**

* Limpia todas las variables enviadas por $_REQUEST,  $_GET, $_POST
	
**/
function purifyRequestParams(){
	purifyRequestVar();//$_REQUEST
	purifyPostParams();//$_POST
	purifyGetParams();//$_GET
	return $_REQUEST;
}

/**

* Limpia todas las variables enviadas por $_REQUEST
	
**/
function purifyRequestVar(){
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Allowed', '');

	$purifier = new HTMLPurifier($config);

	foreach ($_REQUEST as $key => $val) {
		$_REQUEST[$key] = $purifier->purify($_REQUEST[$key]);
		$_REQUEST[$key] = htmlentities($_REQUEST[$key], ENT_QUOTES);
	}
	
	return $_REQUEST;
}




/**

* Limpia todas las variables $_POST

**/
function purifyPostParams(){
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Allowed', '');

	$purifier = new HTMLPurifier($config);

	foreach ($_POST as $key => $val) {
		$_POST[$key] = $purifier->purify($_POST[$key]);
		$_POST[$key] = htmlentities($_POST[$key], ENT_QUOTES);
	}
	return $_POST;
}

/**

* Limpia todas las variables $_GET
	
**/
function purifyGetParams(){
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Allowed', '');

	$purifier = new HTMLPurifier($config);

	foreach ($_GET as $key => $val) {
		$_GET[$key] = $purifier->purify($_GET[$key]);
		$_GET[$key] = htmlentities($_GET[$key], ENT_QUOTES);
	}
	return $_GET;
}

/**

* Limpia las variables enviadas en forma de array
	
**/
function purifyArray($parameters = array()){
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Allowed', '');

	$purifier = new HTMLPurifier($config);

	foreach ($parameters as $key => $val) {
		$parameters[$key] = $purifier->purify($parameters[$key]);
		$parameters[$key] = htmlentities($parameters[$key], ENT_QUOTES);
	}
	return $parameters;
}

/**

* Limpia una variable enviada a la función
	
**/
function purifyParameter($param){
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Allowed', '');
	$purifier = new HTMLPurifier($config);
	$param = $purifier->purify($param);
	$param = htmlentities($param, ENT_QUOTES);
	
	return $param;
}

/**

* Limpia una variable enviada a la función dejando únicamente los tags html más básicos
	
**/
function purifyAndAllowBasicHTML($param){
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Allowed', 'div,ul,li,a,p,b,table,h1,h2,h3,h4,h5,ol,span');
	$purifier = new HTMLPurifier($config);
	$param = $purifier->purify($param);
	$param = htmlentities($param, ENT_QUOTES);
	return $param;
}

/**

* Convierte una variable a entero
	
**/
function convertToInt($param){
	return intval($param);
}
/**

* Convierte todos los parametros enviados en un array en enteros
	
**/
function convertArrayToInt($parameters = array()){
	foreach ($parameters as $key => $val) {
		$parameters[$key] = intval($parameters[$key]);
	}
	return $parameters;
}

/**

* Valida que el nombre del archivo no sea una ruta
	
**/
function validFileName($fileName){
	if(count(explode("/",$fileName))>1 || count(explode('\\',$fileName))>1){
		return false;
	}
	if(!verifyFileToUpload($fileName)){
		return false;
	}
	return true;
}

/**

* Valida si un archivo ya subido al sistema es una extensión permitida
	
**/
function verifyUploadedFile($path){
	global $valid_mime_types;
	$real_mime_type = mime_content_type($path);
	$real_mime_type = strtolower($real_mime_type);
	
	if(array_item_exists($real_mime_type, $valid_mime_types)){
		return true;
	}
	return false;
}



/**

* Valida si el nombre de un archivo tiene una extensión permitida
	
**/
function verifyFileToUpload($filename){
	global $valid_mime_types;
        $explod=explode('.',$filename);
        $valornuevo=array_pop($explod);
	$ext = strtolower($valornuevo);
	if (array_key_exists($ext, $valid_mime_types)) {
		return true;
	}
	return false;
}



/**

* Función que valida si un item se encuentra dentro del contenido de un array enviado como parámetro
	
**/
function array_item_exists($mime, $array = array()){
	foreach ($array as $key => $val) {
		if($array[$key] == $mime){
			return true;
		}
	}
	return false;
}

/**

* Se tomará como intento de Hackeo si el usuario ha subido un archivo que en su nombre contenga como extensión un archivo permitido
* pero con mime-type un archivo no permitido. 
	
**/
function verificandoIntentoHackeo($path){
	if(!verifyUploadedFile($path) && verifyFileToUpload($path)){
		return true;
	}
	return false;
}

?>