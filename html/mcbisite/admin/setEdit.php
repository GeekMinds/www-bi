<?php
session_start();
$ecs = "FALSE";
if(isset($_REQUEST['edit']) && $_REQUEST['edit']=="true"){
    $_SESSION['edit'] ="_edit";
    $ecs = "TRUE";
}else{
    unset($_SESSION['edit']);
}
echo "ESTADO: ".$ecs;
