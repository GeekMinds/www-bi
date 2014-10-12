<?php
//upload.php

$output_dir = "../../../assets/img/";
//require_once("../../../service/models/config.php");

$resultado['estado']='fallo';


dumpear($_FILES);
//error_log("Pasando por el upload.php");
if(isset($_FILES["myfile"]))
{
    //Filter the file types , if you want.
  
        //move the uploaded file to uploads folder;
        $rndstr=generateRandomString(6);
        //error_log($_FILES);
        $url_media="http://54.200.51.188/mcbisite/admin/assets/img/_".$rndstr.  $_FILES["myfile"]["name"] ;


						  move_uploaded_file($_FILES["myfile"]["tmp_name"],
		  				"../../../assets/img/_"  .$rndstr.  $_FILES["myfile"]["name"]);

						  //global $db, $db_table_prefix;
						  //$sql="DELETE FROM imgtmp ;INSERT INTO imgtmp VALUES(1,'".$url_media."' )";
						  //error_log($sql);
						  //$db->sql_query($sql);
                          $resultado['estado']='OK';
                          $resultado['url_media']=$url_media;



                   // error_log($url_media);
 
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


function dumpear($x){
// Dump x
ob_start();
var_dump($x);
$contents = ob_get_contents();
ob_end_clean();
error_log($contents);

}
?>