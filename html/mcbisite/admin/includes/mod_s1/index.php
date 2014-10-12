<?php
require_once("../../../bisite/service/models/config.php");
require_once("../../../bisite/service/models/funcs.container.php");
require_once("../../../bisite/service/models/funcs.mod_s1.php");

$content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';
$module_id = isset($_REQUEST['module_id']) ? $_REQUEST['module_id'] : '-1';
if ($module_id == "") {
    $module_id = "-1";
}
?>
<!DOCTYPE html>
<html>
    <head>
        
        <link rel="stylesheet" type="text/css" href="../../dist/jquery.gridster.css">
        <link rel="stylesheet" type="text/css" href="../../assets/demo.css">
        <link rel="stylesheet" type="text/css" href="../../../adminTemplate/css/bootstrap-cerulean.css">
        <link rel="stylesheet" type="text/css" href="css/kickstart/style.css" media="all" />
        <link rel="stylesheet" type="text/css" href="css/kickstart/kickstart.css" media="all" />
        <script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/kickstart.js"></script>
        <script type="text/javascript" src="../../js/common.js.php"></script>

        <style type="text/css">
            body{
                    min-height: 680px;
                    overflow: hidden;
            }
            .popup{
                display: none;
            }

            .modal{
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
                        #formulario{
                            width: 425px;
                        }
        </style>
    </head>
    <body>
        <div  id="contenedor" style="width: 600px;" class="grid">
            <div style="display:none;" id="popup_choicecontent" class="" style="width:400px;">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal">×</button>-->
                <h3>¿Qué tipo de contenido deseas agregar?</h3>
            </div>
            <div class="modal-footer" style="border-radius: none;">
                <a id="btn_nuevocontenido" href="#" class="btn" data-dismiss="modal">Nuevo Contenido</a>
                <!--<a id="btn_yaexistente" href="#" class="btn btn-primary">Uno ya Existente</a>-->
            </div>
        </div>
        </div>
        
        <script type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>
        <script src="../../dist/jquery.gridster.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="../../js/jquery.lightbox_me.js"></script>
       	<script type="text/javascript" src="../../js/jquery.ba-resize.js"></script>
        
        
        
		<!--<script type="text/javascript" src="../../scripts/jquery-1.10.2.min.js"></script>-->
        <script type="text/javascript" src="../../scripts/demos.js"></script>
        <script type="text/javascript" src="../../jqwidgets/jqxcore.js"></script>
        <script type="text/javascript" src="../../jqwidgets/jqxbuttons.js"></script>
        <script type="text/javascript" src="../../jqwidgets/jqxscrollbar.js"></script>
        <script type="text/javascript" src="../../jqwidgets/jqxpanel.js"></script>
        <script type="text/javascript" src="../../jqwidgets/jqxtree.js"></script>
        <script type="text/javascript" src="../../jqwidgets/jqxcheckbox.js"></script>
        <script>
            $(document).ready(function() {
                var content_id = '<?= $content_id ?>';
                var module_id = '<?= $module_id ?>';
                try{
                parent.onLoading(true);
            }catch(e){
                
            }
                <?php 
                $content_info = array("content_id" => $content_id, "module_name" => "mod_s1");
                $module_info = getModuleInfoDataBase($content_info);
                $show = true;
                   
                        if(empty($module_info['id'])){
                             $show = false;
                        }
                    
                    if(!$show){
                        ?>
                        $('#popup_choicecontent').show();
                        <?php
                    }else{
                ?>
                        getContent();
                <?php
                    }
                    
                ?>
                         $('body').on('click', '.icono-expandir', function() {
                    $(this).closest('tr').next().find('div').slideToggle("fast");
                    $(this).toggleClass('icon-chevron-down');
                    $(this).toggleClass('icon-chevron-up');
                    
                });
                $('body').on('click', '#btn_nuevocontenido', function(){
                var params = {};
                params.action = "getproductofpage";
                params.content_id = content_id;
                $.post(webservice_path_web + 'mod_q.php', params, function(data) {
                    var html = '<div style="width: 600px;" class="col_12">' +
                                '<h5 class="left-align tituloPaginaAdmin">Agregar Pregunta a '+ data.result.titulo_es +'</h5>' +
                                '<form class="vertical" id="formulario" method="post" >' +
                                    '<fieldset>' +
                                        '<legend>Nueva Pregunta</legend>' +
                                        '<div class="col_4">' +
                                            '<label for="text1">Pregunta ES</label>' +
                                            '<input name="pregunta_es" id="text1" type="text" />' +
                                            '<label for="text1">Pregunta EN</label>' +
                                            '<input name="pregunta_en" id="text1" type="text" />' +
                                            '<div style="clear: both;"></div>';
                    
                            html += '<input checked style="display:none;" class="checks" type="checkbox" id="' + data.result.id + '" value="' + data.result.id + '" />';
                                    
                    html +=                 '<div style="clear: both;"></div>' +
                                        '</div>' +
                                    '</fieldset>' +
                                    '<div style="clear: both;"></div>' +
                                    '<a id="nuevaRespuesta" class="medium green"><i style="padding: 10px;" class="icon-plus-sign"></i>Agregar Nueva Respuesta</a>' +
                                    '<div style="clear: both;"></div>' +
                                '</form>' +
                                '<button id="guardarNuevo" style="padding-bottom: 10px;" class="green"><i style="padding-right: 10px;" class="icon-save icon-2x"></i>Guardar Pregunta</button>' +
                            '</div>';
                    $('#contenedor').html(html);
                }, "json");

                });    
                 $('body').on('click', '#guardarNuevo', function(){
                    var form = $(this).parent().find('#formulario');
                var pregunta_es = form.find("input[name='pregunta_es']").val();
                var pregunta_en = form.find("input[name='pregunta_en']").val();
                var productos = [];
                var respuestas_es = [];
                var respuestas_en = [];
                var enlaces_en = [];
                var enlaces_es = [];
                form.find('.checks:checked').each(function(i) {
                    productos.push(this.value);
                });
                if (form.find("input[name='respuesta_es']")) {
                    form.find("input[name='respuesta_es']").each(function(i) {
                         respuestas_es.push(this.value);
                    });
                }
                if (form.find("input[name='respuesta_en']")) {
                    form.find("input[name='respuesta_en']").each(function(i) {
                         respuestas_en.push(this.value);
                    });
                }
                if (form.find("input[name='link_en']")) {
                    form.find("input[name='link_en']").each(function(i) {
                         enlaces_en.push(this.value);
                    });
                }
                if (form.find("input[name='link_es']")) {
                    form.find("input[name='link_es']").each(function(i) {
                        enlaces_es.push(this.value);
                    });
                }
                var pregunta = {};
                var respuestas = [];
                for (var e = 0; e < respuestas_es.length; e++) {
                    var respuesta = {};
                    respuesta['titulo_es'] = respuestas_es[e];
                    respuesta['titulo_en'] = respuestas_en[e];
                    respuesta['link_es'] = enlaces_es[e];
                    respuesta['link_en'] = enlaces_en[e];
                    respuestas.push(respuesta);
                }
                pregunta['question_es'] = pregunta_es;
                pregunta['question_en'] = pregunta_en;
                
                for(var k = respuestas.length - 1; k >= 0 ; k--){
                    if(respuestas[k]['titulo_es']===''&&respuestas[k]['titulo_en']===''&&respuestas[k]['link_en']===''&&respuestas[k]['link_es']===''){
                        respuestas.splice(k, 1);
                    }
                }
                pregunta['respuestas'] = respuestas;
                pregunta['productos'] = productos;
                pregunta['action'] = 'agregarModS1';
                pregunta['content_id'] = content_id;
                if (pregunta['question_es'] !== '' && pregunta['question_en'] !== '' && pregunta['productos'].length > 0) {
                   
                    pregunta = window.JSON.stringify(pregunta);
                     
                    $.ajax({
                        type: 'post',
                        cache: false,
                        url: webservice_path_admin + 'mod_s1.php',
                        data: {pregunta: pregunta}
                    }).done(function(r) {
                        
                        document.location.reload();
//                        alert("success");
                        
                    }).fail(function(e) {
                        alert("error "+e);
                    });
//                        var elPost = $.post(webservice_path_admin+'mod_s1.php', pregunta, sirespuesta1, "json");
//                        elPost.error(Sierrror1);
                } else {
                    
                }
                });
                 $('body').on('click', '#guardar', function(){
                    var form = $(this).parent().find('#formulario');
                var pregunta_es = form.find("input[name='pregunta_es']").val();
                var pregunta_en = form.find("input[name='pregunta_en']").val();
                var productos = [];
                var respuestas_es = [];
                var respuestas_en = [];
                var enlaces_en = [];
                var enlaces_es = [];
                form.find('.checks:checked').each(function(i) {
                    productos.push(this.value);
                });
                if (form.find("input[name='respuesta_es']")) {
                    form.find("input[name='respuesta_es']").each(function(i) {
                         respuestas_es.push(this.value);
                    });
                }
                if (form.find("input[name='respuesta_en']")) {
                    form.find("input[name='respuesta_en']").each(function(i) {
                         respuestas_en.push(this.value);
                    });
                }
                if (form.find("input[name='link_en']")) {
                    form.find("input[name='link_en']").each(function(i) {
                         enlaces_en.push(this.value);
                    });
                }
                if (form.find("input[name='link_es']")) {
                    form.find("input[name='link_es']").each(function(i) {
                        enlaces_es.push(this.value);
                    });
                }
                var pregunta = {};
                var respuestas = [];
                for (var e = 0; e < respuestas_es.length; e++) {
                    var respuesta = {};
                    respuesta['titulo_es'] = respuestas_es[e];
                    respuesta['titulo_en'] = respuestas_en[e];
                    respuesta['link_es'] = enlaces_es[e];
                    respuesta['link_en'] = enlaces_en[e];
                    respuestas.push(respuesta);
                }
                pregunta['question_es'] = pregunta_es;
                pregunta['question_en'] = pregunta_en;
                
                for(var k = respuestas.length - 1; k >= 0 ; k--){
                    if(respuestas[k]['titulo_es']===''&&respuestas[k]['titulo_en']===''&&respuestas[k]['link_en']===''&&respuestas[k]['link_es']===''){
                        respuestas.splice(k, 1);
                    }
                }
                pregunta['respuestas'] = respuestas;
                pregunta['productos'] = productos;
                pregunta['action'] = 'agregarPregunta';
                if (pregunta['question_es'] !== '' && pregunta['question_en'] !== '' && pregunta['productos'].length > 0) {
                   
                    pregunta = window.JSON.stringify(pregunta);
                     
                    $.ajax({
                        type: 'post',
                        cache: false,
                        url: webservice_path_admin + 'mod_s1.php',
                        data: {pregunta: pregunta}
                    }).done(function(r) {
                        document.location.reload();
                    }).fail(function(e) {
                        alert("error "+e);
                    });
//                        var elPost = $.post(webservice_path_admin+'mod_s1.php', pregunta, sirespuesta1, "json");
//                        elPost.error(Sierrror1);
                } else {
                    
                }
                });

                $('body').on('click', '#eliminarRespuesta', function(){
                    $(this).closest('fieldset').remove();
                    
                });
                $('body').on('click', '#nuevaRespuesta', function(){
                     
                var div = '<fieldset>' +
                            '<legend>Nueva Respuesta <a id="eliminarRespuesta" style="padding-left: 50px;margin-left: 5px;padding: 2px;margin-bottom: 2px;" class="button"><i class="icon-remove-sign"></i></a> </legend>' +
                            '<div class="col_4">' +
                                '<label for="text_r_es">Respuesta ES</label>' +
                                '<input name="respuesta_es" type="text" />' +
                                '<label for="text_r_en">Respuesta EN</label>' +
                                '<input name="respuesta_en" type="text" />' +
                                '<div style="clear: both;"></div>' +
                            '</div>' +
                            '<div class="col_4">' +
                                '<label for="text_enlace_es">Enlace ES</label>' +
                                '<input name="link_es"  type="text" />' +
                                '<label for="text_enlace_en">Enlace EN</label>' +
                                '<input name="link_en" type="text" />' +
                                '<div style="clear: both;"></div>' +
                            '</div>' +
                        '</fieldset>';

                $(this).parent().append(div);

                $("html, body").animate({
                    scrollTop: (($(this).offset().top * 1))
                }, 1000);
                });
                
                 function getContent() {
                var params = {};
                params.action = "getcontentproduct";
                params.content_id = content_id;
                $.ajax({
                        type: 'post',
                        cache: false,
                        url: webservice_path_web + 'mod_s1.php',
                        data: params,
                        success: function(data, textStatus, errorThrown){
                            
                            data =  JSON && JSON.parse(data) || $.parseJSON(data);
                            
                            if (data.error == '0') {
                        var html = '<div class="col_12">';
                            html += '<h5 class="left-align tituloPaginaAdmin">M&oacute' + ';dulo' + 'Preguntas Frecuentes</h5>';
                            html += '<p class="left-align descripcionPaginaAdmin">Preguntas frecuentes relacionadas con uno o m&aacute;s productos</p>';
                            html += '<p class="left-align descripcionPaginaAdmin"><b>¡Importante! </b> Para editar las preguntas dirigete a la opcion de FAQ de Productos en el menu General del Administrador y seleccina el producto que has colocado en esta pagina</p>';
                            html += '<br/><br/>'
                            html += ' <table>' +
                                        '<thead>' +
                                        '<tr>' +
                                        '<th>ver respuestas:</th>' +
                                        ' <th>Pregunta Español</th>' +
                                        ' <th>Pregunta Ingles</th>' +
                                        ' <th>Relacionado con:</th>' +
                                        '</tr>' +
                                        '</thead>'
                                        '<tbody>';
                        for (var i = 0; i < data.result.preguntas.length; i++) {

                            if (data.result.preguntas[i].answers.length > 0) {

                                html += '<tr>' +
                                        '    <td><i style="padding: 10px;" class="icon-chevron-down icon-2x icono-expandir"></i></td>' +
                                        '    <td>' + data.result.preguntas[i].question_es + '</td>' +
                                        '    <td>' + data.result.preguntas[i].question_en + '</td>' +
                                        '    <td> <strong>Productos:</strong><br/>';
                                for (var h = 0; h < data.result.preguntas[i].products.length; h++) {
                                    html += data.result.preguntas[i].products[h]['titulo_es'] + '<br/>';
                                }
                                html += '    <br/><strong>Products:</strong><br/>';
                                for (var e = 0; e < data.result.preguntas[i].products.length; e++) {
                                    html += data.result.preguntas[i].products[e]['titulo_en'] + '<br/>';
                                }

                                html += '    </td>' +
                                        '</tr>';
                                html += '<tr >';
                                html += '<td colspan="4" style="border-bottom: none;padding: 0;">';
                                html +=     '<div style="display: none;">';
                                html +=         '<table>' +
                                                    '<thead>' +
                                                        '<tr>' +
                                                            '<th>Titulo ES</th>' +
                                                            '<th>Titulo EN</th>' +
                                                            '<th>Link ES</th>' +
                                                            '<th>Link EN</th>' +
                                                        '</tr>' +
                                                    '</thead>'
                                                    '<tbody>';
                                for (var j = 0; j < data.result.preguntas[i].answers.length; j++) {
                                    html +=         '<tr>' +
                                                        '<td>' + data.result.preguntas[i].answers[j]['title_es'] + '</td>' +
                                                        '<td>' + data.result.preguntas[i].answers[j]['title_en'] + '</td>' +
                                                        '<td>' + data.result.preguntas[i].answers[j]['link_es'] + '</td>' +
                                                        '<td>' + data.result.preguntas[i].answers[j]['link_en'] + '</td>' +
                                                    '</tr>';
                                }
                                html +=             '</tbody>' +
                                                '</table>';
                                html +=     '</div>'
                                html += '</td>';
                                html += '</tr>';
                            } else {
                                html += '<tr>' +
                                            '<td>No disponibles</td>' +
                                            '<td>' + data.result.preguntas[i].question_es + '</td>' +
                                            '<td>' + data.result.preguntas[i].question_en + '</td>' +
                                            '<td></td>' +
                                        '</tr>';
                            }
                        }
                        html += '</tbody>' +
                                '</table>' +
                                '</div>';
                        $('#contenedor').html(html);  
                        }
                        } ,
                        error: function(jqXHR, textStatus, errorThrown){
                            
                        }
                    });
//                $.post(webservice_path_web + 'mod_s1.php', params, function(data) {
//                   
//                        
//                    }
//                }, "json");
            }
            
                 $('body').on('click', '#nuevaPregunta', function(){
var params = {};
                params.action = "getproductofpage";
                params.content_id = content_id;
                $.post(webservice_path_web + 'mod_q.php', params, function(data) {
                    var html = '<div style="width: 600px;" class="col_12">' +
                                '<h5 class="left-align tituloPaginaAdmin">Agregar Pregunta a '+ data.result.titulo_es +'</h5>' +
                                '<form class="vertical" id="formulario" method="post" >' +
                                    '<fieldset>' +
                                        '<legend>Nueva Pregunta</legend>' +
                                        '<div class="col_4">' +
                                            '<label for="text1">Pregunta ES</label>' +
                                            '<input name="pregunta_es" id="text1" type="text" />' +
                                            '<label for="text1">Pregunta EN</label>' +
                                            '<input name="pregunta_en" id="text1" type="text" />' +
                                            '<label>Relacionado Con</label>' + '<div style="clear: both;"></div>';
                    
                            html += '<input checked style="display:none;" class="checks" type="checkbox" id="' + data.result.id + '" value="' + data.result.id + '" />';
                                    
                    html +=                 '<div style="clear: both;"></div>' +
                                        '</div>' +
                                    '</fieldset>' +
                                    '<div style="clear: both;"></div>' +
                                    '<a id="nuevaRespuesta" class="medium green"><i style="padding: 10px;" class="icon-plus-sign"></i>Agregar Nueva Respuesta</a>' +
                                    '<div style="clear: both;"></div>' +
                                '</form>' +
                                '<button id="guardar" style="padding-bottom: 10px;" class="green"><i style="padding-right: 10px;" class="icon-save icon-2x"></i>Guardar Pregunta</button>' +
                            '</div>';
                    $('#contenedor').html(html);
                }, "json");

                });
            });
        </script>
    </body>