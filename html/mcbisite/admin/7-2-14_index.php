<?php

require_once("service/models/config.php");
require_once("service/models/funcs.page.php");

if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
$_SESSION["site_id"] = "1";


$page_id = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';

$params = array();
$params["site_id"] = $_SESSION["site_id"];
$pages = listPagesDataBase($params);
?>
<!doctype html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin</title>
        
        
        <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
        
        <link rel="stylesheet" type="text/css" href="./dist/jquery.gridster.css">
        <link rel="stylesheet" type="text/css" href="assets/demo.css">
        <link rel="stylesheet" type="text/css" href="../adminTemplate/css/bootstrap-cerulean.css">


        <style type="text/css">
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
				width:84%;
				height:auto;
				float:left;
				padding-left: 1%;
			}
			
			#iframe_page_designer{
				width:100% !important;	
			}
        </style>

    </head>

    <body>
    
    	<iframe class="header"
            id="header"
            title="header"
            width="100%"
            height="50px"
            src="menu_header.php"
            frameborder="0"
            type="text/html"
            style="z-index:3000;"
            allowfullscreen="true" allowtransparency="true">
        </iframe>
    
    	<div id="site_tree">
        				<div id='jqxTree' style='visibility: hidden; float: left; margin-left: 0;'>
                            <ul>
                                <li item-expanded='true'>BANCO INDUSTRIAL
                                    <ul>
                                    	<li>GENERAL
                                        	<ul>
                                            	<li>Usuarios</li>
                                            </ul>
                                        </li>
                                        <li>HEADER
                                        	<ul>
                                                <li data-href="includes/mod_a/">Módulo Pestañas</li>
                                                <li>Módulo Persona</li>
                                                <li>Módulo Barra Nav</li>
                                            </ul>
                                        </li>
                                        <li>SECCIONES
                                        	<ul>
                                                <li data-href="page_designer.php?page=2">Home</li>
                                                <li data-href="page_designer.php?page=7">Acerca del Banco</li>
                                                <li data-href="page_designer.php?page=2">Banca Personas</li>
                                                <li data-href="page_designer.php?page=3">Banca Empresarial</li>
                                                <li data-href="page_designer.php?page=6">Banca Electrónica</li>
                                                <li data-href="page_designer.php?page=4">Contacto</li>
                                                <li>Corporación</li>
                                            </ul>
                                        </li>
                                        <li>PAGINAS
                                            <ul>  
                                            	<?php
												for($i=0; $i<count($pages); $i++){
												?>
                                                	<li data-href="page_designer.php?page=<?=$pages[$i]['id']?>">[<?=$pages[$i]['id']?>]<?=$pages[$i]['title_es']?></li>
                                                    <!--<li>Página 1</li>
                                                    <li>Página 2</li>
                                                    <li>Página 3</li>
                                                    <li>Página 4</li>
                                                    <li>Página 5</li>
                                                    <li>Página 6</li>-->
                                                <?php
												}
												?>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>WESTRUST BANK
                                    <ul>
                                        <li>PC products</li>
                                        <li>Mobile products</li>
                                        <li>All products</li>
                                    </ul>
                                </li>
                                <li>CONTECNICA S.A.
                                    <ul>
                                        <li>Support home</li>
                                        <li>Customer Service</li>
                                        <li>Knowledge base</li>
                                        <li>Books</li>
                                        <li>Training and certification</li>
                                        <li>Support programs</li>
                                        <li>Forums</li>
                                        <li>Documentation</li>
                                        <li>Updates</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
    	</div>
		<div id="page_designer">
            <iframe class="iframe_page_designer"
                id="iframe_page_designer"
                title="iframe_page_designer"
                width="100%"
                height="50px"
                src="page_designer.php?page=<?=$page_id?>"
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
                <h3>Configuración de módulo</h3>
            </div>
            <div class="modal-body">
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

		<div id="footer"></div>

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
				
            });
			
			
			function autoResizeIframes(container){
				// Append an iFrame to the page.
				var iframe = $(container);
				// Called once the Iframe's content is loaded.
				iframe.load(function(){
				// The Iframe's child page BODY element.
				var iframe_content = iframe.contents().find('body');
				
				// Bind the resize event. When the iframe's size changes, update its height as
				// well as the corresponding info div.
				iframe_content.resize(function(){
					var elem = $(this);
					
					if(elem.height()<=0){return;}
					
					// Resize the IFrame.
					iframe.css({ height: elem.outerHeight( true ), width: elem.outerWidth( true )});
					// alert(elem.height());  
					// $('#iframe-info').text( 'IFRAME width: ' + elem.width() + ', height: ' + elem.height() );
					//console.log(elem.width());
					$('#popup_config_selected_module').trigger('reposition');
				});
				// Resize the Iframe and update the info div immediately.
				iframe_content.resize();
				});
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
					
					var params = {};
					var data = {};
					params.module_type_id = module_type_id;
					params.action = "module_name";
					params.data = data;
					
					$("#iframe_config_module").attr("src","");
					
					$('#popup_config_selected_module').lightbox_me({
						centered: true,
						overlayCSS:{position:'fixed', background: 'black', opacity: .6}
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
					$("#iframe_page_designer").attr("src",page);
					/*var index_type = "";
					var n=page.indexOf("page");
					if(n>=0){
						page = page.replace("page_","");
						$("#iframe_page_designer").attr("src","page_designer.php?page="+page);
						//window.location.href = "index.php?page="+page;
					}*/
				});
			}
        </script>

    </body>
</html>
