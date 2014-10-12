<?php
require_once("service/models/config.php");

$enable_toolbar = false;

/*echo "<preg>";
print_r($_SERVER);
echo "</preg>";*/

function setRedirectURL($src){
	$resul = array();
	$resul["css_class"] = "";
	$resul["href"] = "";
	$script_name = $_SERVER['SCRIPT_NAME'];
	$script_name = explode("/admin/", $script_name);
	$script_name = $script_name[1];
	$script_name = str_replace("index.php", "", $script_name);
	
	error_log($script_name . " CONTENIDO EN ".$src);
	
	if (strpos($src,$script_name) !== false) {
		$resul["css_class"]  = 'class="current"';
		$resul["href"] = 'href="'.$src.'" ';
	}
	$resul["href"] = ' href="'.$src.'" ';	
	
	return '<li '.$resul["css_class"].'><a '.$resul["href"] .'>';
}

if($enable_toolbar){
?>
<label id="portal_name">Editando portal <?=$websiteName?></label>
<!-- Menu Horizontal -->
<ul id="toolbar" class="menu">
	<?=setRedirectURL('../../general.php?page=2')?><i class="icon-home"></i> General</a></li>
    <li><a href="javascript:void(0)"><i class="icon-inbox"></i> Header</a>
        <ul>
            <?=setRedirectURL('../../includes/mod_a/')?><i class="icon-cog"></i> Pestañas</a></li>
            <?=setRedirectURL('../../includes/mod_social/')?><i class="icon-facebook-sign"></i> Sociales</a></li>
        </ul>
    </li>
    <?=setRedirectURL('../../general.php?site='.$site_id)?><i class="icon-sitemap"></i> Secciones</a></li>
    <!--<li><a href="javascript:void(0)"><i class="icon-cog"></i>Footer</a>|</li>-->
    <?=setRedirectURL('../../includes/mod_p/admin.php')?><i class="icon-comment"></i> Moderar Comentarios</a></li>
</ul>
<hr />
<?php
}
?>