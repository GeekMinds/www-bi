<?php
require_once("service/models/config.php");
require_once("service/models/funcs.container.php");
//if(!isUserLoggedIn()) { header("Location: login.php"); die(); }

//Segmento de cógigo para redireccionar desde un iframe cuando no está logueado
$logued = true;
if(!isUserLoggedIn()) { 
	$logued = false;
	echo '<script type="text/javascript">top.location.href="'.$websiteUrl.'login.php";</script>';
	die();
}


$menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : '';
$module_c_id = isset($_REQUEST['module_c_id']) ? $_REQUEST['module_c_id'] : '';
$page_id = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
$page_link = isset($_REQUEST['link']) ? $_REQUEST['link'] : '';
$content_id_of_lateral_menu = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';
$is_product = isset($_REQUEST['isproduct']) ? $_REQUEST['isproduct'] : '';

$default_page = isset($_REQUEST['default_page']) ? $_REQUEST['default_page'] : '';

$geolocator_content_id = getContainerOFDefaultGeolocator();

$geolocator_id = "";
if ($geolocator_content_id) {
    $geolocator_id = $geolocator_content_id['module_id'];
    $geolocator_content_id = $geolocator_content_id['content_id'];
}
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


        <style type="text/css">
            body{
                min-height: 680px;
                overflow:hidden;	
            }
            .popup{
                display: none;
                top:300px !important;
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

            #btn_addcontainer, #btn_getcontainers, #btn_addtags, #btn_deletecontainers{
                display: none;
            }
            .gridster ul{
                /*width:1000px;*/
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

            #iframe_template_gallery{
                width:560px !important;
            }

            .modal-header{
                overflow:hidden;
            }

            .interactive_bar{

            }

            .controls{
                height: 30px;	
            }

            .control_btn{
                float:left;
                margin: 4px;
            }

            .clear{
                width:1px;
                height:1px;
                float:none;
            }

            .not_configured{
                background: #DF8686 !important;
            }



        </style>

    </head>

    <body>


        <div id="page_designer">
            <h1>Administrador de Plantillas de Contenido</h1>
            <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                <p>Haz clic en el boton agregar contenido y distribuye el contenido de tu página como desées 
                con el tamaño deseado.</p>
            <?php }?>
            <div class="controls">
                <!--<button class="js-resize-random">Resize random widget</button>-->

                <!--<button id="btn_createpage">Crear Página</button>-->
                <?php /* if($is_product==''){ */ ?>
                <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                    <a id="btn_createpage" class="btn btn-primary control_btn" href="#"><i class="icon-file icon-white"></i> Crear Página</a>
                    <a id="btn_assignpage" class="btn btn-primary control_btn" href="#"><i class="icon-white icon-globe"></i> Link Externo</a>
                    <a id="btn_addcontainer" class="btn btn-primary control_btn" href="#"><i class="icon-file icon-white "></i> Agregar Contenido</a>
                    <a id="btn_getcontainers" class="btn btn-success control_btn" href="#"><i class="icon-check icon-white"></i> Guardar Página</a>
                    <a id="btn_deletecontainers" class="btn btn-danger control_btn" href="#"><i class="icon-remove icon-white"></i> Eliminar Contenido</a> 
                    <a id="btn_addtags" class="btn btn-info btn-setting control_btn" href="#"><i class="icon-tags icon-white "></i> Agregar tags</a>
                <?php }?>

                <a id="btn_refresh" class="btn control_btn" href="page_designer.php?page=<?= $page_id ?>&menu_id=<?= $menu_id ?>&content_id=<?= $content_id_of_lateral_menu ?>"><i class=" icon-refresh "></i> Actualizar</a>
                <div class="input-prepend" style="padding-top: 4px;">
                    <span class="add-on">Página No:</span><input id="prependedInput" size="16" type="text" value="<?= $page_id ?>" disabled>
                </div>
                <div class="clear"></div>
                <?php /* } */ ?>


            </div>

            <div class="gridster">
                <ul style=" width:1000px !important">
                    <!--
                    <li data-row="1" data-col="1" data-sizex="1" data-sizey="1">0</li>
                    <li data-row="1" data-col="2" data-sizex="3" data-sizey="2">1</li>
                    <li data-row="1" data-col="4" data-sizex="1" data-sizey="1">2</li>
                    <li data-content_id="-1" data-module_type_id="1" data-module_title="test" class="new gs-w" data-col="1" data-row="1" data-sizex="1" data-sizey="1" style="display: list-item;">
                        test
                        <span class="gs-resize-handle gs-resize-handle-x"></span>
                        <span class="gs-resize-handle gs-resize-handle-y"></span>
                        <span class="gs-resize-handle gs-resize-handle-both"></span>
                    </li> -->
                    <?php
                    if ($page_id != "") {
                        $parameters = array();
                        $parameters['page_id'] = $page_id;
                        $page_info = getContainerDistributionDataBase($parameters);
                        //var_dump($page_info);
                        for ($i = 0; $i < count($page_info); $i++) {

                            //error_log("getModuleInfoDataBase");
                            $module_info = getModuleInfoDataBase($page_info[$i]);

                            $title = isset($module_info['title_es']) ? $module_info['title_es'] : '';
                            if ($title == "") {
                                $title = isset($module_info['name']) ? $module_info['name'] : '';
                            }

                            $mod_id = isset($module_info['id']) ? $module_info['id'] : '';
                            //error_log(json_encode( $module_info));
                            if ((int) $page_info[$i]['size_x'] == 0) {
                                $page_info[$i]['size_x'] = "1";
                            }
                            if ((int) $page_info[$i]['size_y'] == 0) {
                                $page_info[$i]['size_y'] = "1";
                            }

                            $not_configured = "";
                            if ($module_info['id'] == "" && $page_info[$i]['module_name'] != "mod_s1" && $page_info[$i]['module_name'] != "mod_recommendation" && $page_info[$i]['module_name'] != "module_login" && $page_info[$i]['module_name'] != "module_register") {
                                $not_configured = "not_configured ";
                            }
                            ?>
                            <li class="<?= $not_configured ?>content" data-content_id="<?= $page_info[$i]['content_id'] ?>" 
                                data-module_type_id="<?= $page_info[$i]['module_type_id'] ?>"
                                data-module_id="<?= $module_info['id'] ?>"
                                data-module_title="<?= $page_info[$i]['title_es'] ?>"
                                data-module_description="<?= $page_info[$i]['module_description'] ?>"
                                <?php if(hasPermissionsSite(array(EDITOR))){?>
                                    data-module_permission="<?= $page_info[$i]['module_permission'] ?>"
                                <?php }?>
                                class="new gs-w" 
                                data-col="<?= $page_info[$i]['col'] ?>" 
                                data-row="<?= $page_info[$i]['row'] ?>" 
                                <?php
                                if ($page_info[$i]['module_type_id']=='21' || $page_info[$i]['module_type_id']=='22'){
                                    ?>
                                data-min-sizex="<?= $page_info[$i]['size_x'] ?>" 
                                data-min-sizey="<?= $page_info[$i]['size_y'] ?>" 
                                data-max-sizex="<?= $page_info[$i]['size_x'] ?>" 
                                data-max-sizey="<?= $page_info[$i]['size_y'] ?>" 
                                <?php }
                                ?>
                                data-sizex="<?= $page_info[$i]['size_x'] ?>" 
                                data-sizey="<?= $page_info[$i]['size_y'] ?>" style="display: list-item;">
                                <br/>
                                <?= $page_info[$i]['module_description'] ?>
                                <br/>
                                [<?= $page_info[$i]['module_name'] ?>]
                                <br/>
                                [<?= $mod_id ?>:<?= $title ?>]<br/>
                                <?php
                                if(hasPermissionsSite(array(EDITOR))){
                                    if( $page_info[$i]['module_permission']==0){?>
                                        <br/><i class="icon-lock"></i><br/>
                                <?php }else{?>
                                        <br/><i class="icon-wrench"></i></br>
                                <?php }
                                }
                                ?>
                                <?php
                                    if ($page_info[$i]['module_name'] == "mod_q" || $page_info[$i]['module_name'] == "module_text") {
                                        if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){
                                ?>
                                            <a class="btn btn-success interactive_bar" href="#"><i class="icon-wrench icon-white"></i> Barra Interactiva</a>
                                <?php 
                                        }
                                    }
                                    if ($page_info[$i]['module_name'] != "module_register" && $page_info[$i]['module_name'] != "module_login" /*&& $page_info[$i]['module_name'] != "mod_q"*/) {
                                        if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){
                                ?>
                                            <a class="btn btn-danger btn_delete_content" href="javascript:void(0);"><i class="icon-trash icon-white"></i> Eliminar contenido</a>
                                <?php 
                                        }
                                    }
                                ?>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
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
        <div id="popup_deletePage" class="modal hide fade in popup">
            <div class="modal-header"> 
            <h3>¿Seguro que desea eliminar todo el contenido?</h3><br>
            </div>
            <div class="modal-body">
                <h4>Toda la informaci&oacute;n contenida en la pagina se perder&aacute; y no podr&aacute; recuperarse</h4>
            </div> 
            <div class="modal-footer">
                <a href="#" class="btn closePopup" data-dismiss="modal">Cancelar</a>
                <a id="btn_confirmdelete" href="#" class="btn btn-primary">Eliminar</a>
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
                <hr/>
                <h4>Seleccione una plantilla</h4>
                <iframe class="iframe_template_gallery"
                        id="iframe_template_gallery" 
                        title="iframe" 
                        width="560px" 
                        height="100%" 
                        src="template_gallery.php" 
                        frameborder="0" 
                        type="text/html" 
                        allowfullscreen="true" 
                        allowtransparency="true">
                </iframe>

            </div>





            <div class="modal-footer">
                <a href="#" class="btn closePopup" data-dismiss="modal">Cancelar</a>
                <a id="btn_confirmcreatenewpage" href="#" class="btn btn-primary">Crear Página</a>
            </div>
        </div>

        <!-- Link Externo-->
        <div id="popup_linkpage" class="modal hide fade in popup">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Linkear a una pagina</h3><br>
                <h4>Link de la pagina:</h4>
                <input id="inpt_link" class="input span6" type="text" value=""> 

            </div>

            <div class="modal-footer">
                <a href="#" class="btn closePopup" data-dismiss="modal">Cancelar</a>
                <a id="btn_confirmlinkpage" href="#" class="btn btn-primary">Guardar</a>
            </div>
        </div>


        <div id="popup_choicecontent" class="modal hide fade in popup">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>¿Realmente deseas agregar un contenido?</h3>
            </div>
            <div class="modal-footer">
                <a id="btn_nuevocontenido" href="#" class="btn" data-dismiss="modal">Agregar</a>
                <!--<a id="btn_yaexistente" href="#" class="btn btn-primary">Uno ya Existente</a>-->
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


        <div id="popup_config_tags" class="modal hide fade in popup">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Configuración de Tags</h3>
            </div>
            <div class="modal-body">
                <iframe class="iframe"
                        id="iframe_config_tags" 
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
            var external_link = "<?= $page_link ?>";
            var edit_page = "<?= $page_id ?>";
            var menu_id = "<?= $menu_id ?>";
            var module_c_id = "<?= $module_c_id ?>";
            var selected_template = null;
            var content_id_of_lateral_menu = "<?= $content_id_of_lateral_menu ?>";
            var default_page = "<?= $default_page ?>";
            var geolocator_content_id = "<?= $geolocator_content_id ?>";
            var geolocator_id = "<?= $geolocator_id ?>";
            var deleted_content_list = new Array();

            var isNewContent = "";
            var old_w =0;
            var old_h =0;
            $(function() {
                if (edit_page != "") {
                    gridster = $(".gridster ul").gridster({
                        widget_base_dimensions: [240, 240],
                        widget_margins: [5, 5],
                        helper: 'clone',
                        resize: {
                            <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                                enabled: true
                            <?php }else{?>
                                enabled: false
                            <?php }?>
                        },
                        max_cols: 4
                    }).data('gridster');
                    <?php if(hasPermissionsSite(array(GESTOR,EDITOR,ANALISTA))){?>
                    gridster.disable();
                    <?php }?>

                }

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
                //lo del link
                $('#btn_assignpage').click(function() {
                    showPopLinkPage();

                });
                function showPopLinkPage() {
                    selected_template = null;
                    clearInputs();
                    $('#inpt_link').val(external_link);
                    $('#popup_linkpage').lightbox_me({
                        centered: true,
                        overlayCSS: {background: 'white', opacity: 0.6, cursor: "wait"}
                    });



                }
                $('#btn_confirmlinkpage').click(function() {
                    confirmLinkPage();
                });
                function confirmLinkPage() {
                    var str = $('#inpt_link').attr('value');
                    if (str == "") {
                        alert("Ingrese una url");
                        return;
                    }
                    var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                            '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
                    if (!pattern.test(str)) {
                        alert("Por favor ingrese una direccion url valida");
                        return;
                    } else {
                        $('#popup_linkpage').trigger('close');
                        var parametros = {};
                        parametros.action = "extLink";
                        parametros.url = str;
                        parametros.id = menu_id;
                        console.dir(parametros);
                        $.post('service/page_content.php', parametros, function(data) {
                            console.dir(data);
                            if (data.Result == "OK") {
                                external_link = str;
                                parent.onLinkCreated(str, menu_id);
                            } else {
                                alert('no se guardo el cambio!');
                            }
                        }, "json");
                    }

                }
                //
                $("#btn_getcontainers").click(function() {
                    saveContainersDistribution();

                });
                $("#btn_deletecontainers").click(function() {
                    showPopDeleteContent();
                   

                });
                $("#btn_confirmdelete").click(function() {
                  
                   deleteContainer();

                });
                $("#btn_nuevocontenido").click(function() {
                    showPopAddContent();
                });


                $("#btn_yaexistente").click(function() {
                    showPopAddExistentContent();
                });

                $("#btn_addtags").click(function() {
                    showPopConfigTags();
                });

 
                $(".closePopup").click(function() {
                    $('#popup_addcontent').trigger('close');
                    $('#popup_addexistentcontent').trigger('close');
                    $('#popup_deletePage').trigger('close');
                    $('#popup_choicecontent').trigger('close');
                    $('#popup_createpage').trigger('close');
                    $('#popup_linkpage').trigger('close');
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



                $("iframe").each(function(index) {
                    autoResizeIframes(this);
                });
                $('#popup_config_selected_module').find(".modal-body").css("max-height", "none");

                if (edit_page != "") {
                    editPage({});
                }
                if (external_link != "") {
                    editLink();
                }

            });


            function autoResizeIframes(container) {
                // Append an iFrame to the page.
                var iframe = $(container);
                // Called once the Iframe's content is loaded.
                iframe.load(function() {
                    //iframe.css({height: 220, width: 1060});

                    // The Iframe's child page BODY element.
                    var iframe_content = iframe.contents().find('body');
                    iframe_content.css("overflow", "hidden");
                    // Bind the resize event. When the iframe's size changes, update its height as
                    // well as the corresponding info div.
                    iframe_content.resize(function() {
                        var elem = $(this);

                        if (elem.height() <= 0) {
                            return;
                        }

                        // Resize the IFrame.
                        iframe.css({height: elem.outerHeight(true), width: elem.outerWidth(true)});
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
                selected_template = null;
                clearInputs();
                $('#popup_createpage').lightbox_me({
                    centered: true,
                    overlayCSS: {background: 'white', opacity: 0.6, cursor: "wait"}
                });

            }

            function showPopChoiceContent() {
                $('#popup_choicecontent').lightbox_me({
                    centered: true,
                    overlayCSS: {background: 'white', opacity: 0.6, cursor: "wait"}
                });
            }

            function showPopAddContent() {
                isNewContent = "New";
                $('#popup_addcontent').lightbox_me({
                    centered: true,
                    overlayCSS: {background: 'white', opacity: 0.6, cursor: "wait"}
                });
            }
            function showPopDeleteContent() {
                
                $('#popup_deletePage').lightbox_me({
                    centered: true,
                    overlayCSS: {background: 'white', opacity: 0.6, cursor: "wait"}
                });
            }

            function showPopAddExistentContent() {
                isNewContent = "";
                $('#popup_addexistentcontent').lightbox_me({
                    centered: true,
                    overlayCSS: {background: 'white', opacity: 0.6, cursor: "wait"}
                });
            }

            function showPopConfigTags() {
                $("#iframe_config_tags").attr("src", "includes/mod_tag/?page_id=<?= $page_id ?>");
                $('#popup_config_tags').lightbox_me({
                    centered: true,
                    overlayCSS: {background: 'white', opacity: 0.6, cursor: "wait"}
                });
            }


            function ConfirmCreatePage() {
                if ($('#inpt_title_es').attr('value') == "") {
                    alert("Ingrese un título en español para la página");
                    return;
                }
                if ($('#inpt_title_en').attr('value') == "") {
                    alert("Ingrese un título en inglés para la página.");
                    return;
                }
                if (selected_template == null) {
                    alert("Seleccione una plantilla para la página.");
                    return;
                }

                PAGE.page_title_es = $('#inpt_title_es').attr('value');
                PAGE.page_title_en = $('#inpt_title_en').attr('value');
                PAGE.page_description = $('#inpt_description').attr('value');
                clearInputs();
                $('#btn_createpage').css('display', 'none');
                $('#btn_assignpage').css('display', 'none');
                $('#btn_addcontainer').css('display', 'block');
                $('#btn_getcontainers').css('display', 'block');
                $('#btn_addtags').css('display', 'block');
                $('#btn_deletecontainers').css('display', 'block');
                $('#popup_createpage').trigger('close');


                $(".gridster ul").html(selected_template);
                gridster = $(".gridster ul").gridster({
                    widget_base_dimensions: [240, 240],
                    widget_margins: [5, 5],
                    helper: 'clone',
                    resize: {
                        enabled: true
                    },
                    max_cols: 4
                }).data('gridster');

                //Configurando contenidos defaul que tendrá una página NUEVA
                checkDefaultConfigurationsForAllModules();
                addEventDoubleClickToContainer();
            }

            function editPage(data) {
                $('#btn_createpage').css('display', 'none');
                $('#btn_assignpage').css('display', 'none');
                $('#btn_addcontainer').css('display', 'block');
                $('#btn_getcontainers').css('display', 'block');
                $('#btn_addtags').css('display', 'block');
                $('#btn_deletecontainers').css('display', 'block');
                $('#popup_createpage').trigger('close');

                addEventDoubleClickToContainer();
            }
            function editLink() {
                $('#btn_createpage').css('display', 'none');
                $('#btn_assignpage').css('display', 'block');
                $('#btn_addcontainer').css('display', 'none');
                $('#btn_getcontainers').css('display', 'none');
                $('#btn_addtags').css('display', 'none');
                $('#btn_deletecontainers').css('display', 'none');
                 
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
                        + '" data-module_description="' + moduleName + '" class="new not_configured">'
                        + moduleName + '<br/><a class="btn btn-danger btn_delete_content" href="javascript:void(0);"><i class="icon-trash icon-white"></i> Eliminar contenido</a></li>', 1, 1);

                checkDefaultConfigurationsForAllModules();
                addEventDoubleClickToContainer();

                parent.parent.moveScrollTop(parseInt($("body").css("height")));
            }


            function addEventDoubleClickToContainer() {
                $(".gridster li ").dblclick(function() {
                    if ($(this).attr('data-content_id') == "-1") {
                        alert("Debe guardar la página para poder editar el contenido.");
                        return;
                    }

                    var data_module_title = $(this).attr('data-module_title');
                    var data_content_id = $(this).attr('data-content_id');
                    var module_type_id = $(this).attr('data-module_type_id');
                    var module_id = $(this).attr('data-module_id');
                    var module_description = $(this).attr('data-module_description');
                    <?php if(hasPermissionsSite(array(EDITOR))){?>
                        var module_permission = $(this).attr('data-module_permission');
                    <?php }?>

                    var data = {};
                    data.data_module_title = data_module_title;
                    data.data_content_id = data_content_id;
                    data.module_type_id = module_type_id;
                    data.module_id = module_id;
                    data.module_description = module_description;
                    //console.log('Entro y es el contenido: '+data_content_id);
                    
                    <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                        parent.openModuleConfiguration(data);
                    <?php }elseif(hasPermissionsSite(array(EDITOR))){?>
                        if(module_permission=="1"){
                            parent.openModuleConfiguration(data);
                        }else{
                            
                            parent.openModulePermission();
                        }
                    <?php }?>
                        
                    
                });

                $(".interactive_bar").click(function() {
                    var li = $(this).parent();

                    if ($(li).attr('data-content_id') == "-1") {
                        alert("Debe guardar la página para poder editar el contenido.");
                        return;
                    }

                    var data_module_title = li.attr('data-module_title');
                    var data_content_id = li.attr('data-content_id');
                    var module_type_id = li.attr('data-module_type_id');
                    var module_id = li.attr('data-module_id');
                    var module_description = "Barra interactiva";

                    var data = {};
                    data.data_module_title = data_module_title;
                    data.data_content_id = data_content_id;
                    data.module_type_id = "23";
                    data.module_id = "";
                    data.module_description = module_description;
                    parent.openModuleConfiguration(data);

                });

                $(".btn_delete_content").click(function(event) {
                    var li = $(this).parent();
                    if ($(li).attr('data-content_id') == "-1") {
                        //Eliminar solo de memoria pues no se encuentra en base de datos
                        gridster.remove_widget(li, false, onContentDeleted);
                        return;
                    }
                    var data_module_title = $(li).attr('data-module_title');
                    var data_content_id = $(li).attr('data-content_id');
                    var module_type_id = $(li).attr('data-module_type_id');
                    var module_id = $(li).attr('data-module_id');

                    var data = {};
                    data.data_module_title = data_module_title;
                    data.content_id = data_content_id;
                    data.module_type_id = module_type_id;
                    data.module_id = module_id;
                    data.page_id = edit_page;

                    //Estaba pensando eliminarlo solamente de memoria y hasta que le de guardar, enviar el cambio a base de datos.
                    deleted_content_list.push(data);//Voy almacenando en un array los contenedores que deseo eliminar
                    gridster.remove_widget(li, false, onContentDeleted);
                });
            }


            function onContentDeleted(data) {

            }

            function deleteContainer(){
            var data = {};
            var params = {};
            data.page_id = edit_page;
            data.menu_id = menu_id;
            params.action = "deletePage";
            params.data = data;
            $.post('service/page_content.php', params, function(data) {
                    window.location.reload();
                    try{
                    parent.onPageDelete( menu_id);
                    }catch(e){ 
                        alert("Por favor recargue esta pagina.");
                    }
                }, "json");
            }

            function saveContainersDistribution() {
                var array_container = gridster.serialize();
                var params = {};
                var data = {};

                data.page_id = edit_page;
                data.page_title_es = PAGE.page_title_es;
                data.page_title_en = PAGE.page_title_en;
                data.page_description = PAGE.page_description;
                data.menu_id = menu_id;
                data.default_page = default_page;
                data.content_configuration = new Array();
                data.deleted_content_list = deleted_content_list;

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
                    if (menu_id == "") {
                        if (default_page == "") {
                            //window.location.href = "index.php?page=" + data.PageID;
                            window.location.reload();
                        } else {
                            parent.onPageCreated(data.PageID, default_page);
                        }
                    } else {
                        parent.onPageCreated(data.PageID, menu_id);
                    }
                }, "json");
            }

            function selectTemplate(template) {
                selected_template = template;
            }

            //\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
            //\\	SECCION DE ADAPTACIONES POR MODULO PARA NUEVAS PAGINAS
            //\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
            /**
             *	Revisar todos los módulos por tipo, solamente para páginas nuevas y contenidos nuevos
             **/
            function checkDefaultConfigurationsForAllModules() {
                $(".gridster li").each(function(index) {
                    var module_type = $(this).attr("data-module_type_id");
                    module_type = parseInt(module_type);
                    if ($(this).attr("data-content_id") == "-1") {
                        switch (module_type) {
                            case 7:
                                checkLateralMenuAndAddDefaultSectionID(this);
                                break;
                            case 8:
                                checkGeolocatorAndSettingDefaultContent(this);
                                break;
                        }


                    }
                    if ($(this).attr("data-module_id") == "") {
                        $(this).addClass("not_configured");
                    }
                });
            }

            /** 
             *  Módulo menú lateral -- Módulo C
             *  A una página nueva se le pondrá por default el menú lateral derecho correspondiente a su sección
             *  Todas la páginas hijas de una sección tendrán el mismo módulo menu lateral derecho (Módulo C), cuando tengan este módulo asignado
             **/
            function checkLateralMenuAndAddDefaultSectionID(element) {
                $(element).attr("data-module_id", module_c_id);
                $(element).attr("data-content_id", content_id_of_lateral_menu);
            }

            /**  
             *  Módulo menú Geolocalizador -- Módulo G
             *  A una página nueva se le pondrá por default el geolocalizador correspondiente a su site
             *  Todas la páginas hijas de un mismo site tendrán el mismo geolocalizador (Módulo G), cuando tengan este módulo asignado
             **/
            function checkGeolocatorAndSettingDefaultContent(element) {
                $(element).attr("data-module_id", geolocator_id);
                $(element).attr("data-content_id", geolocator_content_id);
            }
            function closeModal() {
                $('#popup_config_selected_module').trigger('close');
            }


        </script>

    </body>
</html>
