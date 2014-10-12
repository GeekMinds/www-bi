<?php
require_once("service/models/config.php");
require_once("service/models/funcs.modc.php");
$page_id = isset($_REQUEST['page_id']) ? $_REQUEST['page_id'] : '';
$module_id = isset($_REQUEST['section']) ? $_REQUEST['section'] : '';
$content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';


//Segmento de cógigo para redireccionar desde un iframe cuando no está logueado
$logued = true;
if(!isUserLoggedIn()) { 
	$logued = false;
	echo '<script type="text/javascript">top.location.href="'.$websiteUrl.'login.php";</script>';
	die();
}


global $db;
$parameters = getParameters($_POST, $_GET);
$parameters['id'] = $module_id;


$menu_info = getMenuCInfoDataBase($parameters);


$principal_submenus = getSubMenuDataBase($parameters);
$principal_submenus = $principal_submenus["rows"];

for ($i = 0; $i < count($principal_submenus); $i++) {
    $parameters["parent_submenu_id"] = $principal_submenus[$i]['id'];
    $childs_submenu = getSubMenuChildsDataBase($parameters);
    $principal_submenus[$i]['childs'] = $childs_submenu['rows'];
}

$menu_info = $menu_info["row"];

$principal_submenus_js = json_encode($principal_submenus);

$site_info = siteInfo();

$section_tree = getAllChildsOfSectionDataBase($parameters);

$db->sql_close();
?>
<!doctype html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin</title>


        <link rel="stylesheet" type="text/css" href="css/kickstart/kickstart.css" media="all" />
        <link rel="stylesheet" href="css/general.css" />  
        <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />

        <link rel="stylesheet" type="text/css" href="./dist/jquery.gridster.css">
        <link rel="stylesheet" type="text/css" href="assets/demo.css">
       	<link rel="stylesheet" type="text/css" href="css/bootstrap-cerulean.css">


        <style type="text/css">
            body{
                overflow:hidden;
            }
            .popup{
                display: none;
            }

            .modal {
                position: fixed;
                top: 50%;
                left: 50%;
                z-index: 1050;
                overflow: auto;
                width: 560px;
                margin: -250px 0 0 -280px;
                background-color: #ffffff;
                border: 1px solid #999;
                border: 1px solid rgba(0, 0, 0, 0.3);
                -webkit-border-radius: 6px;
                -moz-border-radius: 6px;
                border-radius: 6px;
                -webkit-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
                -moz-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
                box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
                -webkit-background-clip: padding-box;
                -moz-background-clip: padding-box;
                background-clip: padding-box;
            }

            ul {
                list-style: none;
            }
            #text_content_new{
                text-align: left !important;
            }

            #btn_addcontainer, #btn_getcontainers{
                display: none;
            }
            .gridster{
                width:1000px;
                border:dashed 3px #000000;
            }

            .gs-w{
                border:dashed 2px #940000;
            }


            #header{
                /*position:fixed;*/
            }

            #footer{
                width:100%;
                height:100px;	
            }

            #popup_config_selected_module{
                width:auto;
            }

            #site_tree{
                width:15%;
                height:auto;
                margin-left:6px;
                float:left;
            }
            #page_designer{
                width:84%;
                height:auto;
                float:left;
                padding-left: 1%;
            }

            #iframe_page_designer{
                width:100% !important;
                min-width:1016px;
            }

            #workarea_container{
				/*display: flex;*/
				display:inline-table;
				width:100%;	
            }

            .tituloBloque {
                background: #004484;
                border-radius: 11px 5px 0px 0;
                width: 100%;
                font-family: "robotobold";
            }
            img {
                display: inline-block;
                vertical-align: middle;
            }
            .tituloBloque label {
                color: #fff;
                display: inline-block;
                font-family: "robotobold";
                font-size: 14px;
                padding-left: 10px;
            }

            .tituloBloque img {
                width: 40px;
                height: 40px;
            }

            #return_button{
                width: 192px;
                float: right;
                margin-right: 43px;	
            }

            .breadcrumb{ 
                margin-left:5px;
                margin-right:5px;	
            }
        </style>

    </head>

    <body>

        <ul class="breadcrumb">
            <li>
                <a href="sites.php">Portales</a> <span class="divider">/</span>
            </li>
            <li>
                <a href="javascript:void(0)"><?=$site_info["title_es"]?></a> <span class="divider">/</span>
            </li>
            <li>
                <a href="general.php?site=<?= $site_id ?>&option=sections">Secciones</a> <span class="divider">/</span>
            </li>
            <li>
                <a href="javascript:void(0)"><?= $menu_info['title_es'] ?></a>
            </li>
        </ul>
        <div id="workarea_container"> 
            <div id="site_tree">
                <div class="tituloBloque">
                    <img src="../assets/images/<?= $menu_info['icon_vertical_menu'] ?>" alt="icono">
                    <label><?= $menu_info['title_es'] ?></label>
                </div>
                <div id='jqxTree' style='visibility: hidden; float: left; margin-left: 0;'>

                </div>
            </div>
            <div id="page_designer">
                <iframe class="iframe_page_designer"
                        id="iframe_page_designer"
                        title="iframe_page_designer"
                        width="100%"
                        height="50px"
                        src=""
                        frameborder="0"
                        type="text/html"
                        style="z-index:3000;"
                        allowfullscreen="true" allowtransparency="true">
                </iframe>
            </div>

        </div>

        <!--CONTENIDO NUEVO-->
        <div id="popup_addcontent" class="modal hide fade in popup">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Agregar Contenido</h3>
            </div>
            <div class="modal-body">
                <p>Escribe el nombre del contenido:</p>
                <input id="Newtext_content" type="text" value=""> 
                <p>Elige el tipo de módulo para agregar de contenido:</p>
                <select id="Newcontent_type" onChange=""></select>

            </div>
            <div class="modal-footer">
                <a href="#" class="btn closePopup" data-dismiss="modal">Cerrar</a>
                <a href="#" id="btn_addnewcontent" class="btn btn-primary">Agregar Contenido</a>
            </div>
        </div>


        <!--CONTENIDO EXISTENTE-->
        <div id="popup_addexistentcontent" class="modal hide fade in popup">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Agregar Contenido Existente</h3>
            </div>

            <div class="modal-body">
                <p>Escribe el nombre del contenido:</p>
                <input id="text_content" type="text" value="" style="text-align: right;"> 
                <p>Elige el tipo de módulo para agregar de contenido:</p>
                <select id="content_type" onChange=""></select>
            </div>

            <div class="modal-footer">
                <a href="#" class="btn closePopup" data-dismiss="modal">Cerrar</a>
                <a href="#" id="btn_addexistentcontent" class="btn btn-primary">Agregar Contenido</a>
            </div>
        </div>


        <div id="popup_createpage" class="modal hide fade in popup">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Crea una nueva página</h3><br>
                <h4>Título de la página en español</h4>
                <input id="inpt_title_es" class="input" type="text" value=""> 
                <h4>Título de la página en ingles</h4>
                <input id="inpt_title_en" class="input" type="text" value=""> 
                <h4>Descripción de la página</h4>
                <textarea id="inpt_description" class="input" type="text" value=""> </textarea>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn closePopup" data-dismiss="modal">Cancelar</a>
                <a id="btn_confirmcreatenewpage" href="#" class="btn btn-primary">Crear Página</a>
            </div>
        </div>
        <div id="popup_deletePage" class="modal hide fade in popup">
            <h3>¿Seguro que desea eliminar todo el contenido?</h3><br>
            <h4>Toda la informacion contenida en la pagina se perdera y no podra recuperarse</h4>
            <div class="modal-footer">
                <a href="#" class="btn closePopup" data-dismiss="modal">Cancelar</a>
                <a id="btn_confirmdelete" href="#" class="btn btn-primary">Eliminar</a>
            </div>
        </div>


        <div id="popup_choicecontent" class="modal hide fade in popup">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>¿Qué tipo de contenido deseas crear?</h3>
            </div>
            <div class="modal-footer">
                <a id="btn_nuevocontenido" href="#" class="btn" data-dismiss="modal">Nuevo Contenido</a>
                <a id="btn_yaexistente" href="#" class="btn btn-primary">Uno ya Existente</a>
            </div>
        </div>


        <div id="popup_config_selected_module" class="modal hide fade in popup">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 id="popup_config_title">Configuración de módulo</h3>
            </div>
            <div class="modal-body">
                <div id="gifLoading"></div>
                <iframe class="iframe"
                        id="iframe_config_module" 
                        title="iframe" 
                        width="100%" 
                        height="100%" 
                        src="" 
                        frameborder="0" 
                        type="text/html" 
                        allowfullscreen="true" 
                        allowtransparency="true">
                </iframe>
                
            </div>
            <div class="modal-footer">
                <!--<a id="btn_nuevocontenido" href="#" class="btn" data-dismiss="modal">Nuevo Contenido</a>
                <a id="btn_yaexistente" href="#" class="btn btn-primary">Uno ya Existente</a>-->
            </div>
        </div>

        <!--MODAL-->

        <div id="mjs_permission" class="modal" style="visibility:hidden;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Advertencia</h3>
                    </div>
                    <div class="modal-body">
                        <p>No tiene permisos para este contenido...</p>
                    </div>
                    <div class="modal-footer">
                        <button id="close_msj_permission" type="button" class="btn btn-primary btn-block">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="mjs_permission_fondo" style="height: 100%; position: fixed; width: 100%; top: 0px; left: 0px; right: 0px; bottom: 0px; z-index: 1001; opacity: 0.6; display: none; background: white;"></div>
        <!--Fin modal-->
        <!--
          <script type="text/javascript" src="assets/jquery.js"></script>
          
        -->
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="js/kickstart.js"></script>
        <script src="./dist/jquery.gridster.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="js/jquery.lightbox_me.js"></script>
       	<script type="text/javascript" src="js/jquery.ba-resize.js"></script>



        <!--<script type="text/javascript" src="../../scripts/jquery-1.10.2.min.js"></script>-->
        <script type="text/javascript" src="./scripts/demos.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxcore.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxbuttons.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxscrollbar.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxpanel.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxtree.js"></script>
        <script type="text/javascript" src="./jqwidgets/jqxcheckbox.js"></script>


        <script type="text/javascript">
            var gridster;
            var PAGE = {};
            var edit_page = "<?= $page_id ?>";
            //$('#mjs_permission').css('visibility','hidden');
            //MODAL DE PERMISOS
            $(function(){
                $("#close_msj_permission").click(function(){
                    $('#mjs_permission').css('visibility','hidden');
                    $('#mjs_permission_fondo').css('display','none');
                });
                $("#mjs_permission_fondo").click(function(){
                    $('#mjs_permission').css('visibility','hidden');
                    $('#mjs_permission_fondo').css('display','none');
                });
            });
            //FIN MODAL DE PERMISOS

            var module_id = '<?= $module_id ?>';
            var principal_submenus = <?= $principal_submenus_js ?>;
            var module_info = <?= json_encode($menu_info) ?>;
            var div_id_first_item_of_tree = "";
			
			var tree_in_list = <?=json_encode($section_tree)?>;
			var section_tree = null;

            var isNewContent = "";
            $(function() {
                $(".closePopup").click(function() {
                    $('#popup_addcontent').trigger('close');
                    $('#popup_addexistentcontent').trigger('close');
                    $('#popup_choicecontent').trigger('close');
                    $('#popup_createpage').trigger('close');
                    $('#popup_deletePage').trigger('close');
                    clearInputs();
                });

                $("iframe").each(function(index) {
                    autoResizeIframes(this);
                });
                $('#popup_config_selected_module').find(".modal-body").css("max-height", "none");



                createStructure();
                loadSiteTree();


            });

            function getRandomInt(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }

            function autoResizeIframes(container) {
                // Append an iFrame to the page.
                var iframe = $(container);
                // Called once the Iframe's content is loaded.
                iframe.load(function() {

                    if (iframe.attr("id") != "iframe_config_module")
                        $("#workarea_container").css("height", "550px");
                    iframe.css({height: 220, width: 1060});
                    // The Iframe's child page BODY element.
                    var iframe_content = iframe.contents().find('body');
					focusFirstInput(iframe_content);

                    // Bind the resize event. When the iframe's size changes, update its height as
                    // well as the corresponding info div.
                    iframe_content.resize(function() {
                        var elem = $(this);
						
						
                        if (elem.height() <= 0) {
                            return;
                        }
                        $("#workarea_container").css("height", "auto");
                        // Resize the IFrame.
                        iframe.css({height: elem.outerHeight(true), width: elem.outerWidth(true)});
                        // alert(elem.height());  
                        // $('#iframe-info').text( 'IFRAME width: ' + elem.width() + ', height: ' + elem.height() );
                        //console.log(elem.width());
                        if ($("#popup_config_selected_module").css("display") == "block") {
                            var top_y_of_popup = $("#popup_config_selected_module").position().top;
                            if ($("#popup_config_selected_module").outerHeight(true) + top_y_of_popup > $("#workarea_container").outerHeight(true)) {
                                //iframe.css({ height: $("#popup_config_selected_module").outerHeight( true )});

                                $("#workarea_container").css({height: $("#popup_config_selected_module").outerHeight(true) + top_y_of_popup});
                            }
                            $('#popup_config_selected_module').trigger('reposition');
                            if (parent != null) {
                                //parent.moveScrollTop(top_y_of_popup - 100);
                            }
                        }

                        //$('#popup_config_selected_module').trigger('reposition');
                    });
                    // Resize the Iframe and update the info div immediately.
                    iframe_content.resize();
                });
            }
			
			
			
			function focusFirstInput(object){
				//$('form:first *:input[type!=hidden]:first').focus();
				$(object).find ('input:visible:first').focus();	
			}


            function showPopAddExistentContent() {
                isNewContent = "";
                $('#popup_addexistentcontent').lightbox_me({
                    centered: true
                });
            }
			


            function openModuleConfiguration(data) {
                var data_module_title = data.data_module_title;
                var data_content_id = data.data_content_id;
                var module_type_id = data.module_type_id;
                var module_id = data.module_id;
				var module_description = data.module_description;

                var params = {};
                var data = {};
                params.module_type_id = module_type_id;
                params.action = "module_name";
                params.data = data;

                $("#iframe_config_module").attr("src", "");
				$("#popup_config_title").html("Configuración de módulo "+module_description);
                                $("#gifLoading").html('<center><img src="../assets/img/loading2.gif" alt="Loading"></center>');
                                
                $('#popup_config_selected_module').lightbox_me({
                    centered: true,
                    overlayCSS: {position: 'fixed', background: 'white', opacity: .6},
					onClose:function(){
						var current_src_configuration = $("#iframe_page_designer").attr("src");
						$("#iframe_page_designer").attr("src", current_src_configuration);
						$("#popup_config_title").html("Configuración");
					}
                });
                $("#iframe_config_module").css("height", "0px");
                $("#iframe_config_module").css("width", "0px");
                $.post('service/page_content.php', params, function(data) {
                    var module_name = "";
                    if (data.error == "0") {
                        module_name = data.result.name;
                        $("#iframe_config_module").attr("src", "./includes/" + module_name + "/?content_id=" + data_content_id + "&module_id=" + module_id);
                    } else {
                        alert("Error en el sistema, intenta más tarde.");
                    }
                }, "json");
            }
//MODAL  de permisos para cunado no tiene acceso a contenido
            function openModulePermission(){
                $('#mjs_permission').css('visibility','visible');
                $('#mjs_permission_fondo').css('display','block');
            }
//Fin MODAL
			
			function closeModuleConfiguration(){
				$("#popup_config_selected_module").trigger('close');
			}


            function loadSiteTree() {
                // Create jqxTree
                $('#jqxTree').jqxTree({height: 'auto', width: '100%'});
                $('#jqxTree').jqxTree('selectItem', $("#jqxTree").find('li:first')[0]);
                $('#jqxTree').css('visibility', 'visible');

                $('#jqxTree').on('select', function(event) {
                    //var page = event.args.element.href;
                    var page = $(event.args.element).attr("data-href");
                    $("#iframe_page_designer").attr("src", page);
                    /*var index_type = "";
                     var n=page.indexOf("page");
                     if(n>=0){
                     page = page.replace("page_","");
                     $("#iframe_page_designer").attr("src","page_designer.php?page="+page);
                     //window.location.href = "index.php?page="+page;
                     }*/
                });
                $('#jqxTree').jqxTree('expandAll');
                if (div_id_first_item_of_tree != "") {
                    $("#iframe_page_designer").attr("src", $(div_id_first_item_of_tree).attr("data-href"));
                }
            }



            function getPageEditLink(link, menu_id) {
                //La variable content_id se le envia para poder agregar el menu lateral correspondiente a su sección a una página nueva
                //Para reutilizar los contenidos ya creados y que lo hereden otras páginas nuevas
                var n = link.indexOf("./?page=");
                if (n == 0) {
                    var pid = link.replace("./?page=", "");
                    return 'data-href="page_designer.php?page=' + pid + '&menu_id=' + menu_id + '&content_id=' + module_info.content_id + '"';
                }
                if (link.length > 0) {
                    return 'data-href="page_designer.php?menu_id=' + menu_id + '&content_id=' + module_info.content_id + '&module_c_id=' + module_id + '&link=' + link + '"';
                } else {
                    return 'data-href="page_designer.php?menu_id=' + menu_id + '&content_id=' + module_info.content_id + '&module_c_id=' + module_id + '"';
                }
            }
			
			
			
			
			
			function formatTree(){
				section_tree = {};
				var list_of_items = {}; 
				for(var i=0; i<tree_in_list.length; i++){
					tree_in_list[i].childs = new Array();
					list_of_items[tree_in_list[i].id] = tree_in_list[i];
					if(tree_in_list[i].parent_submenu_id==""){
						section_tree[tree_in_list[i].id] = tree_in_list[i];
					}else{
						if(list_of_items[tree_in_list[i].parent_submenu_id]!=undefined){
							list_of_items[tree_in_list[i].parent_submenu_id].childs.push(tree_in_list[i]);
						}
					}
				}
			}
			
			
			function getHTMLTree(tree, level){
				var _html = '<ul>';
				var expanded = "";
				var i=0;
				$.each(tree, function( index, data ) {
					var asigned_page = getPageEditLink(data.link, data.id);
					
					_html += '<li id="section' + data.id
						_html += '" data-level="0" data-index="' + data.id + '" data-id="' + data.id; 
						_html += '" ' + asigned_page + '>' + data.title_es;
					if(data.childs.length>0){
						childs = data.childs;
						_html += getHTMLTree(childs, level+1);
					}
					_html += "</li>";
					
					if (i == 0 && level==1) {//Para que por default cuando cargue abra para su edición la primer opción
                        div_id_first_item_of_tree = '#section' + data.id;
                    }
					i++;
				});
				
				return _html+= '</ul>'; 
			}
			

            function createStructure() {
				formatTree();
				var _html = getHTMLTree(section_tree, 1);
				$('#jqxTree').html(_html);
				/*
                if (principal_submenus.length <= 0) {
                    return false;
                }
                if (principal_submenus.length == 1) {
                    if (principal_submenus[0].childs == false && typeof principal_submenus[0].title_es == "undefined") {
                        return false;
                    }
                }

                var menu_str = "<ul>";
                for (var i = 0; i < principal_submenus.length; i++) {
                    var asigned_page = getPageEditLink(principal_submenus[i].link, principal_submenus[i].id);


                    menu_str += '<li id="section' + principal_submenus[i].id + '" data-level="0" data-index="' + i + '" data-id="' + principal_submenus[i].id + '" ' + asigned_page + '>' + principal_submenus[i].title_es;
                    var childs = principal_submenus[i].childs;

                    if (childs.length > 0) {
                        menu_str += "<ul>";
                    }
                    for (var j = 0; j < childs.length; j++) {
                        asigned_page = getPageEditLink(childs[j].link, childs[j].id);
                        menu_str += '<li id="section' + childs[j].id + '" data-level="1" data-index="' + i + '" data-child_index="' + j + '" data-id="' + childs[j].id + '" ' + asigned_page + '>';
                        menu_str += childs[j].title_es;
                        menu_str += "</li>";
                    }

                    if (childs.length > 0) {
                        menu_str += "</ul>";
                    }

                    menu_str += "</li>";

                    if (i == 0) {
                        div_id_first_item_of_tree = '#section' + principal_submenus[i].id;
                    }


                }
                menu_str += "</ul>";
                $('#jqxTree').html(menu_str);*/

            }

            function onPageCreated(page_id, section_id) {
                $("#section" + section_id).attr("data-href", 'page_designer.php?page=' + page_id + '&menu_id=' + section_id + '&content_id=' + module_info.content_id);
                $("#iframe_page_designer").attr("src", $("#section" + section_id).attr("data-href"));
            }
            function onPageDelete(section_id) {
                $("#section" + section_id).attr("data-href", 'page_designer.php?menu_id=' + section_id + '&content_id=' + module_info.content_id);
                $("#iframe_page_designer").attr("src", $("#section" + section_id).attr("data-href"));
            }
			
			
			function onLinkCreated(link, section_id){
				$("#section" + section_id).attr("data-href", 'page_designer.php?menu_id=' + section_id + '&content_id=' + module_info.content_id + '&module_c_id=' + section_id + '&link=' + link);
                $("#iframe_page_designer").attr("src", $("#section" + section_id).attr("data-href"));
				//return 'data-href="page_designer.php?menu_id=' + menu_id + '&content_id=' + module_info.content_id + '&module_c_id=' + module_id + '&link=' + link + '"';
			}
                        function onLoading(i){
                            
                            if(i){
                                $('#gifLoading').html('');
                            }
                        }
        </script>

    </body>
</html>
