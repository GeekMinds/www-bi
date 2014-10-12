<?php
$resultado['estado']='fallo';

if(isset($_FILES["myfile"]))
{
 //Se genera una cadena aleatoria para guardar en el sistema de archivos
  $rndstr=generateRandomString(16);
  $name = $_FILES["myfile"]["name"];
  //Obtener la extension del archivo
  $ext ="." .end(explode(".", $name));
  $url_media="".$rndstr.$ext;
  move_uploaded_file(
    $_FILES["myfile"]["tmp_name"],
	 "../../../assets/images/countries/".$url_media);
  $resultado['estado']='OK';
  $resultado['url_media']=$url_media;
}

echo ($resultado['url_media']);




function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


