<?php
	/*
		UserPie Langauge File.
		Language: English.
	*/
	
	/*
		%m1% - Dymamic markers which are replaced at run time by the relevant index.
	*/

	$lang = array();
	
	//Account
	$lang = array_merge($lang,array(
		"ACCOUNT_SPECIFY_USERNAME" 				=> "Por favor ingrese su usuario",
		"ACCOUNT_SPECIFY_PASSWORD" 				=> "Por favor ingrese su contraseña",
		"ACCOUNT_SPECIFY_EMAIL"					=> "Por favor ingrese su email",
		"ACCOUNT_INVALID_EMAIL"					=> "Email inválido",
		"ACCOUNT_INVALID_USERNAME"				=> "Usuario inválido",
		"ACCOUNT_USER_OR_EMAIL_INVALID"			=> "Usuario o email inválido",
		"ACCOUNT_USER_OR_PASS_INVALID"			=> "Usuario o contraseña inválida",
		"ACCOUNT_ALREADY_ACTIVE"				=> "Su cuenta ya fue activada",
		"ACCOUNT_INACTIVE"						=> "Su cuenta está inactiva. Revise su correo electrónico / folder de spam para las instrucciones de activación",
		"ACCOUNT_USER_CHAR_LIMIT"				=> "Su nombre de usuario no puede contener menos de %m1% caracteres o más de %m2%",
		"ACCOUNT_PASS_CHAR_LIMIT"				=> "Su password no debe contener menos de %m1% caracteres o más de %m2%",
		"ACCOUNT_PASS_MISMATCH"					=> "El passwords debe coincidir",
		"ACCOUNT_USERNAME_IN_USE"				=> "El usuario %m1% ingresado se encuentra ya en uso",
		"ACCOUNT_EMAIL_IN_USE"					=> "El email ingresado %m1% ya se encuentra en uso",
		"ACCOUNT_LINK_ALREADY_SENT"				=> "Un correo electrónico de activación ya ha sido enviado a esta dirección de correo electrónico en las últimas %m1% hora(s)",
		"ACCOUNT_NEW_ACTIVATION_SENT"			=> "Hemos enviado un nuevo link de activacío, porfavor revise su email",
		"ACCOUNT_NOW_ACTIVE"					=> "Su cuenta está ahora activa",
		"ACCOUNT_SPECIFY_NEW_PASSWORD"			=> "Porfavor ingrese su nuevo password",	
		"ACCOUNT_NEW_PASSWORD_LENGTH"			=> "El nuevo password no puede tener menos de %m1% caracteres o más de %m2%",	
		"ACCOUNT_PASSWORD_INVALID"				=> "La contraseña actual no coincide con la que tenemos en nuestro registro",	
		"ACCOUNT_EMAIL_TAKEN"					=> "Esta dirección de correo electrónico ya está en uso por otro usuario",
		"ACCOUNT_DETAILS_UPDATED"				=> "La información de la cuenta fue actualizada",
		"ACTIVATION_MESSAGE"					=> "Usted necesitará primero activar su cuenta antes de poder iniciar sesión, siga el siguiente enlace para activar su cuenta:
													<br/><br/>%m1%activate-account.php?token=%m2% <br/><br/> Su contraseña es %m3%",							
		"ACCOUNT_REGISTRATION_COMPLETE_TYPE1"	=> "Se ha registrado correctamente. Ahora puede iniciar sesión <a href=\"login.php\">aquí</a>.",
		"ACCOUNT_REGISTRATION_COMPLETE_TYPE2"	=> "Se ha registrado correctamente. En breve recibirá un email de activación. 
													Usted debe activar su cuenta antes de iniciar.",
	));
	
	//Forgot password
	$lang = array_merge($lang,array(
		"FORGOTPASS_INVALID_TOKEN"				=> "Token inválido",
		"FORGOTPASS_NEW_PASS_EMAIL"				=> "Le hemos enviado por correo electrónico una nueva contraseña",
		"FORGOTPASS_REQUEST_CANNED"				=> "Solicitud de perdida de password cancelada",
		"FORGOTPASS_REQUEST_EXISTS"				=> "Ya existe una solicitud de pérdida de contraseña para esta cuenta",
		"FORGOTPASS_REQUEST_SUCCESS"			=> "Ya existe una solicitud para contraseña perdida para esta cuenta",
	));
	
	//Miscellaneous
	$lang = array_merge($lang,array(
		"CONFIRM"								=> "Confirmar",
		"DENY"									=> "Denegar",
		"SUCCESS"								=> "Éxito",
		"ERROR"									=> "Error",
		"NOTHING_TO_UPDATE"						=> "No hay nada que actualizar",
		"SQL_ERROR"								=> "Fatal error en SQL ",
		"MAIL_ERROR"							=> "Fatal error al intentar enviar correo electrónico, póngase en contacto con el administrador del servidor",
		"MAIL_TEMPLATE_BUILD_ERROR"				=> "Error al construir la plantilla de correo",
		"MAIL_TEMPLATE_DIRECTORY_ERROR"			=> "No se puede abrir el directorio mail-templates. Intente configurar el directorio de correo a %m1%",
		"MAIL_TEMPLATE_FILE_EMPTY"				=> "Archivo para Template esta vacío... no se puede enviar",
		"FEATURE_DISABLED"						=> "Esta característica está deshabilitada en este momento.",
	));
?>