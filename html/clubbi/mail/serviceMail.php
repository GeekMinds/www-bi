<?php
require_once("class.mail.php");
function sendSugerenciaMail($parameters = array()){
    $mail = new userPieMail();
	$notification_title = "Nueva Sugerencia";
	
//	$message = str_replace("&437","<br/>",mailbody($parameters));
        $message = "Sugerencia ";
        $message .= "<br> Dirigido ".$parameters["dirigido"];
        $message .= "<br> Nombre Establecimiento ".$parameters["establecimiento"];
        $message .= "<br> Categoria ".$parameters["categoria"];
        $message .= "<br> Otra Categoria ".$parameters["otracategoria"];
        $message .= "<br> Otra Categoria ".$parameters["msg"];

	
	//settind the default mail template directory
        $status = "OK";
        if(!$mail->sendMail("davidxocoy@gmail.com", $notification_title,$message))
        {
                $status = "FAIL";
        }
		
	
    return $status;
}
function sendComentarioMail($parameters = array()){
    $mail = new userPieMail();
	$notification_title = "Nuevo Comentario";
	
	//$message = str_replace("&437","<br/>",$parameters["msg"]);
	$message = "Sugerencia ";
        $message .= "<br> Tipo de Cuenta ".$parameters["tipo"];
        $message .= "<br> Tiene Tarjeta Club Bi ".$parameters["tarjeta"];
        $message .= "<br> Nombre ".$parameters["nombre"];
        $message .= "<br> Email ".$parameters["email"];
        $message .= "<br> Telefono ".$parameters["telefono"];
	$message .= "<br> Numero de Tarjeta ".$parameters["notarjeta"];
        $message .= "<br> Mensaje ".$parameters["msg"];
        
	//settind the default mail template directory
	
		$status = "OK";

			if(!$mail->sendMail("davidxocoy@gmail.com", $notification_title,$message))
			{
				$status = "FAIL";
			}
		
	
    return $status;
}
function getParameters($post_request = array(), $get_request = array()) {

	
    $parameters = array();

    //getting the data

    foreach ($post_request as $key => $val) {

        $parameters[strtolower($key)] = $val;
    }

    foreach ($get_request as $key => $val) {

        $parameters[strtolower($key)] = $val;
    }
	
	
    return $parameters;
}
function getAction($parameters=array()) {
  $action = '';

  $action = isset($parameters['action']) ? $parameters['action'] : '';

  return $action;
}
function getCallback($parameters=array()) {
  $callback = '';
  
  $callback = isset($parameters['callback']) ? $parameters['callback'] : '';
  
  return $callback;
}

$parameters = getParameters($_POST, $_GET);

error_log("POST PARAMETERS -----> ".json_encode($_POST));
$callback = getCallback($parameters);
$action = getAction($parameters);

switch ($action) {
    case 'enviarSugerencia':
       $result = sendSugerenciaMail($parameters);
    break;
    case 'enviarComentario':
        $result = sendComentarioMail($parameters);
    break;
    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
}

if (strlen($callback) > 0) {
    echo $callback . '(' . json_encode($result) . ');';
} else {
    echo json_encode($result);
}
