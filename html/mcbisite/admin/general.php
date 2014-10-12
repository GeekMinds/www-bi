<?php
require_once("service/models/config.php");
require_once("service/models/funcs.page.php");
require_once("service/models/funcs.site.php");
require_once("service/models/funcs.container.php");

foreach ($_REQUEST as $key => $val) {
    $_REQUEST[$key] = htmlentities($_REQUEST[$key]);
}



//if(!isUserLoggedIn()) { header("Location: login.php"); die(); }

//Segmento de cógigo para redireccionar desde un iframe cuando no está logueado
$logued = true;
if(!isUserLoggedIn()) { 
	$logued = false;
	echo '<script type="text/javascript">top.location.href="'.$websiteUrl.'login.php";</script>';
	die();
}

$page_id = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';


$option = isset($_REQUEST['option']) ? $_REQUEST['option'] : '';



$site_id = isset($_REQUEST['site']) ? $_REQUEST['site'] : "";
if($site_id!=""){
	selectSiteID($site_id);//Seleccionando y guardando en variable de session el sitio a editar
}

$params = array();
$params["site_id"] = $site_id;
//$pages = listPagesDataBase($params);

$default_pages_db = getDefaultPages();
if($default_pages_db){
	for($i=0; $i<count($default_pages_db); $i++){
		foreach ($defaul_pages as $key => $val) {
			if($key == $default_pages_db[$i]["alias"]){
				$defaul_pages[$key] = "&page=".$default_pages_db[$i]["page_id"];
			}
		}
	}
}

$page_edition_link_home = "page_designer.php?default_page=home".$defaul_pages["home"];
$page_edition_link_login = "page_designer.php?default_page=login".$defaul_pages["login"];
$page_edition_link_register = "page_designer.php?default_page=register".$defaul_pages["register"];
$page_edition_link_geo = "#";
$page_edition_link_products = "includes/manejador_productos/manage_productos.php?accion=listar";
$page_edition_link_interests = "includes/mod_z/interest/";
$page_edition_link_faq = "includes/mod_s1/page_admin_mod_s1.php";


$geolocator_content_id = getContainerOFDefaultGeolocator();
$geolocator_id = "";
if($geolocator_content_id){
	$geolocator_id =  $geolocator_content_id['module_id'];
	$geolocator_content_id = $geolocator_content_id['content_id'];
}

$page_edition_link_geo = "includes/mod_g/?content_id=".$geolocator_content_id."&module_id=".$geolocator_id;

$site_info = siteInfo();
?>
<!doctype html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin</title>
        <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
        
        <link rel="stylesheet" type="text/css" href="./dist/jquery.gridster.css">
        <link rel="stylesheet" type="text/css" href="assets/demo.css">
       	<link rel="stylesheet" type="text/css" href="css/bootstrap-cerulean.css"> 
       	<link rel="stylesheet" type="text/css" href="css/charisma-app.css">
       	<link rel="stylesheet" type="text/css" href="css/opa-icons.css">


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
				float:left;
			}
			#page_designer{
				width:80%;
				height:auto;
				float:left;
				padding-left: 1%;
				margin:0;
				margin-left: 10px;
				margin-right: 10px;
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
			
			.well{
				padding:0;
			}
			
			.jqx-widget-content{
				background: #f5f5f5 !important;	
				background-color: #f5f5f5 !important;	
			}
			
			#header_section{
				margin-left:-14px;
				margin-top:0;	
			}
        </style>

    </head>

    <body>
    	
    
    	<div id="workarea_container">
    	<div id="site_tree" class="span2 main-menu-span">
        				<ul class="breadcrumb">
                            <li>
                                <a href="javascript:void(0)"><i class="icon-cog"></i> General</a>
                            </li>
                        </ul>
        
        				<div id='jqxTree' style='visibility: hidden; float: left; margin-left: 0;' class="well nav-collapse sidebar-nav">
                       
                            <ul>
                                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                                    <li item-expanded="true"><i class="icon-inbox"></i> Header
                                        <ul>
                                            <li data-href="./includes/mod_a/"><i class="icon icon-darkgray icon-pin"></i> Pestañas</li>
                                            <li data-href="./includes/mod_social/"><i class="icon icon-darkgray icon-web"></i> Sociales</li>
                                		  <li data-href="./includes/module_bar_nav/corporation.php" item-expanded="true"><i class="icon-briefcase"></i> Menú Corporativo </li>
                                        </ul>
                                    </li>
                                <?php }?>
                                
                                <li item-expanded='true'><i class="icon-folder-open"></i> Páginas Default
                                    <ul>
                                        <li id="default_page_home" data-href="<?=$page_edition_link_home?>"><i class="icon-home"></i> Home</li>
                                        <li id="default_page_login" data-href="<?=$page_edition_link_login?>"><i class="icon-lock"></i> Login</li>
                                        <li id="default_page_register" data-href="<?=$page_edition_link_register?>"><i class="icon-user"></i> Registro</li>
                                    </ul>
                                </li>
                                
                                <li id="sections" data-href="includes/module_bar_nav/draganddrop.php?site=<?=$params["site_id"]?>" item-expanded='true'><i class="icon-tasks"></i> Secciones </li>

                                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR))){?>
                                    <li item-expanded="true"><i class="icon-tags"></i> Sistema de Tags
                                        <ul>
                                            <li data-href="includes/tags/"><i class="icon-tags"></i> Tags</li>
                                            <li data-href="<?=$page_edition_link_interests?>"><i class="icon-heart"></i> Intereses</li>
                                        </ul>
                                    </li>
                                <?php }?>
                                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                                    <li item-expanded="true"><i class="icon-shopping-cart"></i> Productos
                                        <ul>
                                            <!--<li data-href="<?=$page_edition_link_products?>"><i class="icon-shopping-cart"></i> Productos</li>-->
                                            <li data-href="<?=$page_edition_link_faq?>"><i class="icon-question-sign"></i> FAQ de Productos</li>
                                        </ul>
                                    </li>
                                <?php }?> 
                                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                                    <li data-href="<?=$page_edition_link_geo?>"><i class="icon-globe"></i> Geolocalizador</li>
                                <?php }?>  
                                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?> 
                                    <li data-href="./includes/mod_p/admin.php" item-expanded="true"><i class="icon-comment"></i> Comentarios </li>
                                <?php }?>
                                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR))){?> 
                                    <li data-href="./includes/country/" item-expanded="true"><i class="icon-globe"></i> Paises </li>
                                <?php }?>
                                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?> 
								    <li id="footer" data-href="includes/legal/" item-expanded="true"><i class="icon-tasks"></i> Footer </li>
                                <?php }?>
                            </ul>
                        </div>
    	</div>
		<div id="page_designer" class="box">
            <ul id="header_section" class="breadcrumb">
                <li>
                    <a href="sites.php">Portales</a> <span class="divider">/</span>
                </li>
                <li>
                    <a href="javascript:void(0);"><?=$site_info["title_es"]?></a> <span class="divider">/</span>
                </li>
                <li>
                    <a id="current_section" href="javascript:void(0);">General</a> 
                </li>
            </ul>
            
            <div id="easy_access_buttons">
                <a data-rel="tooltip" class="well span3 top-block" href="#" data-original-title="12 new messages." style="position: relative;" data-href="<?=$page_edition_link_home?>">
                    <br/>
                    <span class="icon32 icon-color icon-home"></span>
                    <br/>
                    <div>Home</div>
                    <br/>
                </a>
                <a data-rel="tooltip" class="well span3 top-block" href="#" data-original-title="12 new messages." style="position: relative;" data-href="<?=$page_edition_link_login?>">
                    <br/>
                    <span class="icon32 icon-color icon-locked"></span>
                    <br/>
                    <div>Login</div>
                    <br/>
                </a>
                
                <a data-rel="tooltip" class="well span3 top-block" href="#" data-original-title="12 new messages." style="position: relative;" data-href="<?=$page_edition_link_register?>">
                    <br/>
                    <span class="icon32 icon-color icon-user"></span>
                    <br/>
                    <div>Registro</div>
                    <br/>
                </a>
                
                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                <a data-rel="tooltip" class="well span3 top-block" href="#" data-original-title="12 new messages." style="position: relative;" data-href="<?=$page_edition_link_geo?>">
                    <br/>
                    <span class="icon32 icon-color icon-globe"></span>
                    <br/>
                    <div>Geolocalizador</div>
                    <br/>
                </a>
                <?php }?>
                <!--
                
                <a data-rel="tooltip" class="well span3 top-block" href="#" data-original-title="12 new messages." style="position: relative;" data-href="<?=$page_edition_link_products?>">
                    <br/>
                    <span class="icon32 icon-color icon-cart"></span>
                    <br/>
                    <div>Productos</div>
                    <br/>
                </a>
                -->
                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                <a data-rel="tooltip" class="well span3 top-block" href="#" data-original-title="12 new messages." style="position: relative;" data-href="<?=$page_edition_link_interests?>">
                    <br/>
                    <span class="icon32 icon-color icon-heart"></span>
                    <br/>
                    <div>Intereses</div>
                    <br/>
                </a>
                
                
                <a data-rel="tooltip" class="well span3 top-block" href="#" data-original-title="12 new messages." style="position: relative;" data-href="<?=$page_edition_link_faq?>">
                    <br/>
                    <span class="icon32 icon-color icon-help"></span>
                    <br/>
                    <div>FAQ para productos</div>
                    <br/>
                </a>
                <?php }?>
            </div>
            
            
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

		</div>

        <!--
          <script type="text/javascript" src="assets/jquery.js"></script>
          
        -->
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
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
            function getRandomInt(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }
        </script>

        <script type="text/javascript">
            var gridster;
            var PAGE = {};
			var edit_page = "<?=$page_id?>";
			var option = "<?=$option?>";

            var isNewContent = "";
            $(function() {
                gridster = $(".gridster ul").gridster({
                    widget_base_dimensions: [240, 240],
                    widget_margins: [5, 5],
                    helper: 'clone',
                    resize: {
                        enabled: true
                    },
					max_cols:4
                }).data('gridster');

                $('.js-resize-random').on('click', function() {
                    gridster.resize_widget(gridster.$widgets.eq(getRandomInt(0, 9)),
                            getRandomInt(1, 4), getRandomInt(1, 4))
                });

                $('#btn_confirmcreatenewpage').click(function() {
                    ConfirmCreatePage();
                });

                $("#btn_addnewcontent").click(function() {
                    AddContentToCanvas();
                });

                $("#btn_addexistentcontent").click(function() {
                    AddContentToCanvas();
                });

                $("#btn_addcontainer").click(function() {
                    showPopChoiceContent();
                });

                $('#btn_createpage').click(function() {
                    showPopCreatePage();
                });

                $("#btn_getcontainers").click(function() {
                    saveContainersDistribution();

                });

                $("#btn_nuevocontenido").click(function() {
                    showPopAddContent();
                });


                $("#btn_yaexistente").click(function() {
                    showPopAddExistentContent();
                });


                $(".closePopup").click(function() {
                    $('#popup_addcontent').trigger('close');
                    $('#popup_addexistentcontent').trigger('close');
                    $('#popup_choicecontent').trigger('close');
                    $('#popup_createpage').trigger('close');
                    clearInputs();
                });

                params = {};
                params.action = "list";
                $.post('service/page_content.php', params, function(data) {
                    for (var i = 0; i < data.Records.length; i++)
                    {
                        actual = data.Records[i];
                        var o = new Option(actual.description, actual.id);
                        $(o).html(actual.description);
                        $("#content_type").append(o);
                        var o1 = new Option(actual.description, actual.id);
                        $(o1).html(actual.description);
                        $("#Newcontent_type").append(o1);
                    }
                }, "json");
				

				
				$( "iframe" ).each(function( index ) {
					autoResizeIframes(this);
				});
				$('#popup_config_selected_module').find(".modal-body").css("max-height","none");
				
				if(edit_page != ""){
					editPage({});
				}
				
				loadSiteTree();
				loadSelectedOption();
            });
			
			
			function loadSelectedOption(){
				if(option!=""){
					var url = $("#"+option).attr("data-href");
					$("#iframe_page_designer").attr("src",url);
					$("#easy_access_buttons").css("display","none");
				}
			}
			
			
			function autoResizeIframes(container){
				// Append an iFrame to the page.
				var iframe = $(container);
				// Called once the Iframe's content is loaded.
				iframe.load(function(){
				
				if(iframe.attr("id")!="iframe_config_module")
					$("#workarea_container").css("height","550px");
				// The Iframe's child page BODY element.
				var iframe_content = iframe.contents().find('body');
				focusFirstInput(iframe_content);
				
				// Bind the resize event. When the iframe's size changes, update its height as
				// well as the corresponding info div.
				iframe_content.resize(function(){
					var elem = $(this);
					
					if(elem.height()<=0){return;}
					
					$("#workarea_container").css("height","auto");
					// Resize the IFrame.
					iframe.css({ height: elem.outerHeight( true ), width: elem.outerWidth( true )});
					
					if($("#popup_config_selected_module").css("display")=="block"){
						var top_y_of_popup = $("#popup_config_selected_module").position().top;
						if($("#popup_config_selected_module").outerHeight( true ) + top_y_of_popup > $("#workarea_container").outerHeight( true )){
							//iframe.css({ height: $("#popup_config_selected_module").outerHeight( true )});
							
							$("#workarea_container").css({ height: $("#popup_config_selected_module").outerHeight( true )+top_y_of_popup}); 
						}
						$('#popup_config_selected_module').trigger('reposition');
						if(parent!=null){
							//parent.moveScrollTop(top_y_of_popup-400);
						}
					}else{
						
					}
					
					// alert(elem.height());  
					// $('#iframe-info').text( 'IFRAME width: ' + elem.width() + ', height: ' + elem.height() );
					//console.log(elem.width());
					
				});
				// Resize the Iframe and update the info div immediately.
				iframe_content.resize();
				});
			}
			
			
			
			
			function focusFirstInput(object){
				//$('form:first *:input[type!=hidden]:first').focus();
				$(object).find ('input:visible:first').focus();	
			}

            function showPopCreatePage() {
                $('#popup_createpage').lightbox_me({
                    centered: true
                });
            }

            function showPopChoiceContent() {
                $('#popup_choicecontent').lightbox_me({
                    centered: true
                });
            }

            function showPopAddContent() {
                isNewContent = "New";
                $('#popup_addcontent').lightbox_me({
                    centered: true
                });
            }


            function showPopAddExistentContent() {
                isNewContent = "";
                $('#popup_addexistentcontent').lightbox_me({
                    centered: true
                });
            }


            function ConfirmCreatePage() {
                PAGE.page_title_es = $('#inpt_title_es').attr('value');
                PAGE.page_title_en = $('#inpt_title_en').attr('value');
                PAGE.page_description = $('#inpt_description').attr('value');
                clearInputs();
                $('#btn_createpage').css('display', 'none');
                $('#btn_addcontainer').css('display', 'block');
                $('#btn_getcontainers').css('display', 'block');
                $('#popup_createpage').trigger('close');
            }
			
			function editPage(data){
                $('#btn_createpage').css('display', 'none');
                $('#btn_addcontainer').css('display', 'block');
                $('#btn_getcontainers').css('display', 'block');
                $('#popup_createpage').trigger('close');
				
				addEventDoubleClickToContainer();
			}
			

            function clearInputs() {
                $('.input').attr('value', '');
            }

            function AddContentToCanvas() {

                $(".closePopup").click();
                contentName = $("#" + isNewContent + "text_content").val();
                moduleName = $("select#" + isNewContent + "content_type option").filter(":selected").text();
                moduleId = $("select#" + isNewContent + "content_type option").filter(":selected").val();
                gridster.add_widget('<li data-content_id="-1" data-module_type_id="'
                        + moduleId + '" data-module_title="' + contentName
                        + '" class="new">'
                        + contentName + '</li>', 1, 1);

						addEventDoubleClickToContainer();
            }
			
			
			function addEventDoubleClickToContainer(){
				$(".gridster li ").dblclick(function(){
					var data_module_title = $(this).attr('data-module_title');
                    var data_content_id = $(this).attr('data-content_id');
					var module_type_id = $(this).attr('data-module_type_id');
					var module_id = $(this).attr('data-module_id');
					
					var data = {};
					data.data_module_title = data_module_title;
					data.data_content_id = data_content_id;
					data.module_type_id = module_type_id;
					data.module_id = module_id;
					openModuleConfiguration(data);
                });
			}
			
			
			function openModuleConfiguration(data){
					var data_module_title =data.data_module_title;
                    var data_content_id = data.data_content_id;
					var module_type_id =data.module_type_id;
					var module_id = data.module_id;
					var module_description = data.module_description;
					
					var params = {};
					var data = {};
					params.module_type_id = module_type_id;
					params.action = "module_name";
					params.data = data;
					
					$("#iframe_config_module").attr("src","");
					
					$("#popup_config_title").html("Configuración de módulo "+module_description);
					$("#gifLoading").html('<center><img src="../assets/img/loading2.gif" alt="Loading"></center>');
					$('#popup_config_selected_module').lightbox_me({
						centered: true,
						overlayCSS:{position:'fixed', background: 'white', opacity: .6},
						onClose:function(){
							var current_src_configuration = $("#iframe_page_designer").attr("src");
							$("#iframe_page_designer").attr("src", current_src_configuration);
							$("#popup_config_title").html("Configuración");
						}
					});
					$("#iframe_config_module").css("height","0px");
					$("#iframe_config_module").css("width","0px");
					$.post('service/page_content.php', params, function(data) {
						var module_name = "";
						if(data.error=="0"){
							module_name =  data.result.name;
							$("#iframe_config_module").attr("src","./includes/"+module_name + "/?content_id="+data_content_id + "&module_id="+module_id);
						}else{
							alert("Error en el sistema, intenta más tarde.");	
						}
					}, "json");
			}
			
			
			
			function closeModuleConfiguration(){
				$("#popup_config_selected_module").trigger('close');
			}
			
			
			
			function saveContainersDistribution(){
					var array_container = gridster.serialize();
                    var params = {};
                    var data = {};

                    data.page_id = edit_page;
                    data.page_title_es = PAGE.page_title_es;
                    data.page_title_en = PAGE.page_title_en;
                    data.page_description = PAGE.page_description;
                    data.content_configuration = new Array();

                    for (var i = 0; i < gridster.$widgets.length; i++)
                    {
                        content_configuration = {};
                        actual = gridster.$widgets[i];

                        content_configuration.size_x = actual.attributes.getNamedItem('data-sizex').nodeValue;
                        content_configuration.size_y = actual.attributes.getNamedItem('data-sizey').nodeValue;
                        content_configuration.col = actual.attributes.getNamedItem('data-col').nodeValue;
                        content_configuration.row = actual.attributes.getNamedItem('data-row').nodeValue;
                        content_configuration.content_id = actual.attributes.getNamedItem('data-content_id').nodeValue;
                        content_configuration.module_type_id = actual.attributes.getNamedItem('data-module_type_id').nodeValue;
                        content_configuration.module_title = actual.attributes.getNamedItem('data-module_title').nodeValue;
                        data.content_configuration.push(content_configuration);
                    }

                    params.action = "createpage";
                    params.data = data;
                    $.post('service/page_content.php', params, function(data) {
						window.location.href = "index.php?page="+data.PageID;
                    }, "json");
			}
			
			function loadSiteTree(){
				// Create jqxTree
				$('#jqxTree').jqxTree({ height: 'auto', width: '100%'});
				$('#jqxTree').jqxTree('selectItem', $("#jqxTree").find('li:first')[0]);
				$('#jqxTree').css('visibility', 'visible');	
				
				$('#jqxTree').on('select', function (event) {
					//var page = event.args.element.href;
					var page = $(event.args.element).attr("data-href");
					
					if(page==undefined){
						return;
					}
					if(page.replace(/^\s+|\s+$/g, '')=="" || page=="#"){
						return;
					}
					
					$("#current_section").html($(event.args.element).text());
					
					//$("#current_section").attr("href",page);
					
					$("#iframe_page_designer").attr("src",page);
					/*var index_type = "";
					var n=page.indexOf("page");
					if(n>=0){
						page = page.replace("page_","");
						$("#iframe_page_designer").attr("src","page_designer.php?page="+page);
						//window.location.href = "index.php?page="+page;
					}*/
					$("#easy_access_buttons").css("display","none");
				});
				
				$("#easy_access_buttons a").click(function(){
					var page = $(this).attr("data-href");
					if(page.replace(/^\s+|\s+$/g, '')=="" || page=="#"){
						return;
					}
					$("#iframe_page_designer").attr("src",page);
					$("#easy_access_buttons").css("display","none");
				});
			}
			
			
			
			function onPageCreated(page_id, default_page){
                            if(typeof module_info != 'undefined'){
				$("#default_page_"+default_page).attr("data-href",'page_designer.php?page='+page_id+'&default_page='+default_page+'&content_id='+module_info.content_id);
                            }else{
                                $("#default_page_"+default_page).attr("data-href",'page_designer.php?page='+page_id+'&default_page='+default_page);
                            }
				$("#iframe_page_designer").attr("src",$("#default_page_"+default_page).attr("data-href"));
			}
                        function onLoading(i){
                            
                            if(i){
                                $('#gifLoading').html('');
                            }
                        }
			function resizeIframeDesigner(h){
                
                            $("#iframe_page_designer").css("height",h);
                            var hb = $("body").height();
                            parent.resizeIframeDesigner((hb+100)+"px");
                        }
        </script>

    </body>
</html>
