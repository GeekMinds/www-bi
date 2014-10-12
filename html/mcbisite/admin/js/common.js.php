<?php
header("Content-type: text/javascript");

$site_id = 1;
?>
var site_id = <?php echo $site_id;?>;
var webservice_path_web = "http://54.200.51.188/mcbisite/bisite/service/";
var webservice_path_admin = "http://54.200.51.188/mcbisite/admin/service/";