<?php

require_once("service/models/config.php");
require_once("service/models/funcs.page.php");

if(!isUserLoggedIn()) { 
	$_SESSION["original_visited_url"] = $_SERVER['REQUEST_URI'];
	header("Location: login.php"); 
	die(); 
}
$_SESSION["site_id_administered"] = "1";



$section = isset($_REQUEST['section']) ? $_REQUEST['section'] : 'dashboard';
$page_id = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';

$params = array();  
$params["site_id"] = $_SESSION["site_id_administered"];

$pages = listPagesDataBase($params);
?>
<!doctype html>
<html>
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="user-scalable=no" />
        <title>Admin</title>
        
        <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
        
        <link rel="stylesheet" type="text/css" href="./dist/jquery.gridster.css">
        <link rel="stylesheet" type="text/css" href="assets/demo.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap-cerulean.css">  


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
			
			#tool_bar{
				width: 100%;
				height: 106px;
				display:block;
				border-bottom: solid 6px;
				margin-bottom: 23px;
				color: #000;
				margin-top: -5px;
				min-width:704px;
				text-align:center;
			}
			
			#tool_bar a{
				float: right;
				padding: 42px 15px 10px;
				color: #777777;
				text-decoration: none;
				text-shadow: 0 1px 0 #ffffff;
				height: 52px;
				border: solid 1px;
				border-color: #D4D4D4;
			}
			
			#tool_bar a:hover{
				color: #333333;
				text-decoration: none;
				background-color: transparent;
			}
			
			#tool_bar a:first-child{
				float:left;
				margin-top:-19px;
				border:none;
			}
			
			#tool_bar_container{
				max-width: 1212px;
				display: inline-table;
				min-width: 920px;	
			}
			
			
			.content_admin, .header{
				min-width:1270px;	
			}

			.badge{
				background-color: #FF0000;
			}
                     
        </style>
		
		
    </head>

    <body>
    
    	<iframe class="header"
            id="header"
            title="header"
            width="100%"
            height="41px"
            src="menu_header.php"
            frameborder="0"
            type="text/html"
            style="z-index:3000;"
            allowfullscreen="true" allowtransparency="true">
        </iframe>
        
        <div id="tool_bar">
        	<div id="tool_bar_container">
                <a href="."><img src="./css/media/general/logo.png"/></a>
                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL,GESTOR,EDITOR,ANALISTA))){?>
                	<a href="javascript:void(0)">AYUDA Y CONTACTO</a>
                <?php }?>
                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL,EDITOR))){?>
                	<a href="?section=sites">PORTALES</a>
                <?php }?>
                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                	<a href="?section=users">USUARIOS</a>
                <?php }?>
                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL,GESTOR))){?>
                	<a href="?section=notifications">NOTIFICACIONES<ul class="nav nav-pills"><span class="badge"><?php getCountNotification(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL,GESTOR));?></span></ul></a>
                <?php }?>
            </div>
        </div>
    
    	<iframe class="content_admin"
                id="content_admin"
                title="content_admin"
                width="100%"
                height="100%"
                src=""
                data-src="<?=$section?>.php?page=<?=$page_id?>"
                frameborder="0"
                type="text/html"
                allowfullscreen="true" allowtransparency="true">
        </iframe>

        

		<div id="footer"></div>

        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<!--                <script type="text/javascript" src="scripts/jquery-2.0.2.min.js"></script> -->
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
        
        <script>
        
       	
        </script>

        <script type="text/javascript">

            $(function() {
				$( "iframe" ).each(function( index ) {
					var src = $(this).attr("data-src");
					$(this).attr("src", src);
					autoResizeIframes(this);
				});
				$('#popup_config_selected_module').find(".modal-body").css("max-height","none");
            });
			
			
			function autoResizeIframes(container){
				// Append an iFrame to the page.
				var iframe = $(container);
				// Called once the Iframe's content is loaded.
				iframe.load(function(){
					
				$("html, body").animate({ scrollTop: 0 }, "slow");
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
			
			
			function moveScrollTop(pos_y){
				var header_width = $("#header").outerHeight( true ) + $("#tool_bar").outerHeight( true );
				$("html, body").animate({ scrollTop: pos_y}, "slow");	
			}
			function clos(){
				$('#popup_config_selected_module').trigger('close');
			}
                        function resizeIframeDesigner(h){
                
                            $("#content_admin").css("height",h);
                
                        }
                        
                        
			
        </script>

    </body>
</html>
