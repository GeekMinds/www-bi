<!DOCTYPE html>
<html>
    <head>
        <!-- META -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="css/kickstart/style.css" media="all" />
        <link rel="stylesheet" type="text/css" href="css/kickstart/kickstart.css" media="all" />

        <style>
            body{
                display: table;
                overflow: hidden;	
            }
            
.ui-dialog .ui-dialog-titlebar-close{
    display: none;
}

        </style>
        <!-- Javascript -->
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../../css/themes/redmond/jquery-ui-1.8.16.custom.css" media="all" />
        <script type="text/javascript" src="../../js/1.10.4/jquery-ui.js"></script>
        <script type="text/javascript" src="js/kickstart.js"></script>

        <script type="text/javascript" src="../../js/common.js.php"></script>
        <script>
            jQuery(document).ready(function() {
                
                var module_id = '-1';
                getContent();
                
                $('body').on('click', '.icono-expandir', function() {
                    $(this).closest('tr').next().find('div').slideToggle("fast");
                    $(this).toggleClass('icon-chevron-down');
                    $(this).toggleClass('icon-chevron-up');

                });
                 $('body').on('click', '#cancelar', function() {
                      document.location.reload();
                 });
                $('body').on('click', '#guardar', function() {
                    var form = $(this).parent().find('#formulario');
                    var pregunta_es = form.find("input[name='pregunta_es']").val();
                    var pregunta_en = form.find("input[name='pregunta_en']").val();
                    var productos = [];

                    var respuestas_es = [];
                    var respuestas_en = [];
                    var enlaces_en = [];
                    var enlaces_es = [];
                    var respuestas_id = [];
                    var respuestas_accion = [];
                    form.find('.checks:checked').each(function(i) {
                        productos.push(this.value);
                    });
                    if (form.find("input[name='respuesta_es']")) {
                        form.find("input[name='respuesta_es']").each(function(i) {
                            respuestas_es.push(this.value);
                            respuestas_id.push(this.getAttribute("data-id"));
                            respuestas_accion.push(this.getAttribute("data-accion"));
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
                        respuesta['id'] = respuestas_id[e];
                        respuesta['accion'] = respuestas_accion[e];
                        respuestas.push(respuesta);
                    }
                    pregunta['question_es'] = pregunta_es;
                    pregunta['question_en'] = pregunta_en;

                    for (var k = respuestas.length - 1; k >= 0; k--) {
                        if (respuestas[k]['titulo_es'] === '' && respuestas[k]['titulo_en'] === '' && respuestas[k]['link_en'] === '' && respuestas[k]['link_es'] === '') {
                            respuestas.splice(k, 1);
                        }
                    }
                    pregunta['respuestas'] = respuestas;
                    pregunta['productos'] = productos;
                    pregunta['action'] = 'agregarPregunta';
                    pregunta['id'] = module_id;
                    if (pregunta['question_es'] !== '' && pregunta['question_en'] !== '' && pregunta['productos'].length > 0) {

                        pregunta = window.JSON.stringify(pregunta);

                        $.ajax({
                            type: 'post',
                            cache: false,
                            url: webservice_path_admin + 'mod_s1.php',
                            data: {pregunta: pregunta}
                        }).done(function(r) {
                            alert('Se ha guardado su pregunta');
                            document.location.reload();

                        }).fail(function(e) {
                            alert("error " + e);
                        });
//                        var elPost = $.post(webservice_path_admin+'mod_s1.php', pregunta, sirespuesta1, "json");
//                        elPost.error(Sierrror1);
                    } else {

                    }
                });
                $('body').on('click', '#eliminarRespuesta', function() {  
                    $(this).closest('fieldset').find('input[name="respuesta_es"]').attr('data-accion', '-2');
                    $(this).closest('fieldset').hide();
                });
                $('body').on('click', '#nuevaRespuesta', function() {

                    var div = '<fieldset>' +
                            '<legend>Nueva Respuesta <a id="eliminarRespuesta" style="padding-left: 50px;margin-left: 5px;padding: 2px;margin-bottom: 2px;" class="button"><i class="icon-remove-sign"></i></a> </legend>' +
                            '<div class="col_12">' +
                            '<label for="text_r_es">Respuesta ES</label>' +
                            '<input data-accion="-1" name="respuesta_es" type="text" />' +
                            '<label for="text_r_en">Respuesta EN</label>' +
                            '<input data-accion="-1" name="respuesta_en" type="text" />' +
                            '<div style="clear: both;"></div>' +
                            '</div>' +
                            '<div class="col_12">' +
                            '<label for="text_enlace_es">Enlace ES</label>' +
                            '<input data-accion="-1" name="link_es"  type="text" />' +
                            '<label for="text_enlace_en">Enlace EN</label>' +
                            '<input data-accion="-1" name="link_en" type="text" />' +
                            '<div style="clear: both;"></div>' +
                            '</div>' +
                            '</fieldset>';

                    $(this).parent().append(div);

                    $("html, body").animate({
                        scrollTop: (($(this).offset().top * 1))
                    }, 1000);
                });
                $('body').on('click', '.eliminarPregunta', function() {
                    
                    var pregunta = {};
                    pregunta['action'] = "borrarPregunta";
                    pregunta['id'] = $(this).attr('id');
                    pregunta = window.JSON.stringify(pregunta);
                    confirmDelete(pregunta);
                
                });
                $('body').on('click', '.editarPregunta', function() {
                    var params = {};
                    params.action = "getquestionedit";
                    params.id = $(this).attr('id');
                    $.ajax({
                        type: 'post',
                        cache: false,
                        url: webservice_path_web + 'mod_s1.php',
                        data: params
                    }).done(function(data) {

                        data = JSON.parse(data);
                        module_id = params.id;
                        var html = '<div class="col_12">' +
                                '<h5 class="left-align tituloPaginaAdmin">Editar Pregunta</h5>' +
                                '<form class="vertical" id="formulario" method="post" >' +
                                '<fieldset>' +
                                '<legend>Pregunta</legend>' +
                                '<div class="col_12">' +
                                '<label for="text1">Pregunta Español</label>' +
                                '<input value="' + data.result.pregunta.question_es + '" name="pregunta_es" id="text1" type="text" />' +
                                '<label for="text1">Pregunta Ingles</label>' +
                                '<input value="' + data.result.pregunta.question_en + '" name="pregunta_en" id="text1" type="text" />' +
                                '<label>Relacionado Con</label>' + '<div style="clear: both;"></div>';
                        if (data.error === '0') {
                            for (var u = 0; u < data.result.productos.length; u++) {
                                var check = false;
                                $.each(data.result.pregunta.products, function(index, value) {
                                    if (JSON.stringify(data.result.productos[u]) === JSON.stringify(value)) {
                                        html += '<input class="checks" type="checkbox" checked id="' + data.result.productos[u].id + '" value="' + data.result.productos[u].id + '" />' +
                                                '<label for="check1" class="inline" style="padding-left: 0.5em;">' + data.result.productos[u].titulo_es + ' / ' + data.result.productos[u].titulo_en + '</label><br/>';
                                        check = true;
                                    }
                                });
                                if (check) {
                                    continue;
                                } else {
                                    html += '<input class="checks" type="checkbox" id="' + data.result.productos[u].id + '" value="' + data.result.productos[u].id + '" />' +
                                            '<label for="check1" class="inline" style="padding-left: 0.5em;">' + data.result.productos[u].titulo_es + ' / ' + data.result.productos[u].titulo_en + '</label><br/>';
                                }
                            }
                        }
                        html += '<div style="clear: both;"></div>' +
                                '</div>' +
                                '</fieldset>' +
                                '<div style="clear: both;"></div>' +
                                '<a id="nuevaRespuesta" class="medium green"><i style="padding: 10px;" class="icon-plus-sign"></i>Agregar Nueva Respuesta</a>' +
                                '<div style="clear: both;"></div>';
                        for (var a = 0; a < data.result.pregunta.answers.length; a++) {
                            html += '<fieldset>' +
                                    '<legend>Respuesta <a id="eliminarRespuesta" style="padding-left: 50px;margin-left: 5px;padding: 2px;margin-bottom: 2px;" class="button"><i class="icon-remove-sign"></i></a> </legend>' +
                                    '<div class="col_12">' +
                                    '<label for="text_r_es">Respuesta Español</label>' +
                                    '<input value="' + data.result.pregunta.answers[a].title_es + '" data-accion="1" data-id="' + data.result.pregunta.answers[a].id + '" name="respuesta_es" type="text" />' +
                                    '<label for="text_r_en">Respuesta Ingles</label>' +
                                    '<input value="' + data.result.pregunta.answers[a].title_en + '" data-accion="1" data-id="' + data.result.pregunta.answers[a].id + '" name="respuesta_en" type="text" />' +
                                    '<div style="clear: both;"></div>' +
                                    '</div>' +
                                    '<div class="col_12">' +
                                    '<label for="text_enlace_es">Enlace Español</label>' +
                                    '<input value="' + data.result.pregunta.answers[a].link_es + '" data-accion="1" data-id="' + data.result.pregunta.answers[a].id + '" name="link_es"  type="text" />' +
                                    '<label for="text_enlace_en">Enlace Ingles</label>' +
                                    '<input value="' + data.result.pregunta.answers[a].link_en + '" data-accion="1" data-id="' + data.result.pregunta.answers[a].id + '" name="link_en" type="text" />' +
                                    '<div style="clear: both;"></div>' +
                                    '</div>' +
                                    '</fieldset>';
                        }

                        html += '</form>' +
                                '<button id="guardar" style="padding-bottom: 10px;" class="green"><i style="padding-right: 10px;" class="icon-save icon-2x"></i>Guardar Pregunta</button>' +
                                '&nbsp;<button id="cancelar" style="padding-bottom: 10px;" class="red"><i style="padding-right: 10px;" class="icon-remove icon-2x"></i>Cancelar</button>' +
                                '</div>';
                        $('#contenedor').html(html);
                    }).fail(function(e) {
                        alert("error " + e);
                    });
                });
                $('body').on('click', '#nuevaPregunta', function() {
                    var params = {};
                    params.action = "getproducts";
                    $.post(webservice_path_web + 'mod_s1.php', params, function(data) {
                        module_id = '-1';
                        var html = '<div class="col_12">' +
                                '<h5 class="left-align tituloPaginaAdmin">Agregar Pregunta</h5>' +
                                '<form class="vertical" id="formulario" method="post" >' +
                                '<fieldset>' +
                                '<legend>Nueva Pregunta</legend>' +
                                '<div class="col_12">' +
                                '<label for="text1">Pregunta Español</label>' +
                                '<input name="pregunta_es" id="text1" type="text" />' +
                                '<label for="text1">Pregunta Ingles</label>' +
                                '<input name="pregunta_en" id="text1" type="text" />' +
                                '<label>Relacionado Con</label>' + '<div style="clear: both;"></div>';
                        if (data.error == '0') {
                            for (var u = 0; u < data.result.length; u++) {
                                html += '<input class="checks" type="checkbox" id="' + data.result[u].id + '" value="' + data.result[u].id + '" />' +
                                        '<label for="check1" class="inline" style="padding-left: 0.5em;">' + data.result[u].titulo_es + ' / ' + data.result[u].titulo_en + '</label><br/>';
                            }
                        }
                        html += '<div style="clear: both;"></div>' +
                                '</div>' +
                                '</fieldset>' +
                                '<div style="clear: both;"></div>' +
                                '<a id="nuevaRespuesta" class="medium green"><i style="padding: 10px;" class="icon-plus-sign"></i>Agregar Nueva Respuesta</a>' +
                                '<div style="clear: both;"></div>' +
                                '</form>' +
                                '<button id="guardar" style="padding-bottom: 10px;" class="green"><i style="padding-right: 10px;" class="icon-save icon-2x"></i>Guardar Pregunta</button>' +
                                '&nbsp;<button id="cancelar" style="padding-bottom: 10px;" class="red"><i style="padding-right: 10px;" class="icon-remove icon-2x"></i>Cancelar</button>' +
                                '</div>';
                        $('#contenedor').html(html);
                    }, "json");

                });
            });
            
            function getContent() {
                var params = {};
                params.action = "getcontent";
                $.post(webservice_path_web + 'mod_s1.php', params, function(data) {


                    if (data.error == '0') {
                        var html = '<div class="col_12">';
                        html += '<h5 class="left-align tituloPaginaAdmin">M&oacute' + ';dulo' + 'Preguntas Frecuentes</h5>';
                        html += '<p class="left-align descripcionPaginaAdmin">Preguntas frecuentes relacionadas con uno o m&aacute;s productos</p>';
                        html += '<button id="nuevaPregunta" class="medium green"><i style="padding: 10px;" class="icon-plus-sign"></i>Agregar Nueva</button>';
                        html += '<br/><br/>'
                        html += ' <table>' +
                                '<thead>' +
                                '<tr>' +
                                '<th>ver respuestas:</th>' +
                                ' <th>pregunta_es</th>' +
                                ' <th>pregunta_en</th>' +
                                ' <th>Relacionado con:</th>' +
                                ' <th>Acciones</th>' +
                                '</tr>' +
                                '</thead>'
                        '<tbody>';
                        for (var i = 0; i < data.result.length; i++) {

                            if (data.result[i].answers.length > 0) {
                                html += '<tr>' +
                                        '    <td><i style="padding: 10px;" class="icon-chevron-down icon-2x icono-expandir"></i></td>' +
                                        '    <td>' + data.result[i].question_es + '</td>' +
                                        '    <td>' + data.result[i].question_en + '</td>' +
                                        '    <td> <strong>Productos:</strong><br/>';
                                for (var h = 0; h < data.result[i].products.length; h++) {
                                    html += data.result[i].products[h]['titulo_es'] + '<br/>';
                                }
                                html += '    <br/><strong>Products:</strong><br/>';
                                for (var e = 0; e < data.result[i].products.length; e++) {
                                    html += data.result[i].products[e]['titulo_en'] + '<br/>';
                                }

                                html += '    </td>' +
                                        '    <td><a class="editarPregunta" title="Editar Pregunta" id="' + data.result[i].id + '" href="javascript:void(0)"><i class="icon-pencil icon-3x" style="color: gray"></i></a>\n\
                            <a class="eliminarPregunta" title="Eliminar Pregunta" id="' + data.result[i].id + '" href="javascript:void(0)"><i class="icon-remove-sign icon-3x" style="color: gray"></i></a></td>' +
                                        '</tr>';
                                html += '<tr >';
                                html += '<td colspan="4" style="border-bottom: none;padding: 0;">';
                                html += '<div style="display: none;">';
                                html += '<table>' +
                                        '<thead>' +
                                        '<tr>' +
                                        '<th>Titulo Español</th>' +
                                        '<th>Titulo Ingles</th>' +
                                        '<th>Link Español</th>' +
                                        '<th>Link Ingles</th>' +
                                        '</tr>' +
                                        '</thead>'
                                '<tbody>';
                                for (var j = 0; j < data.result[i].answers.length; j++) {
                                    html += '<tr>' +
                                            '<td>' + data.result[i].answers[j]['title_es'] + '</td>' +
                                            '<td>' + data.result[i].answers[j]['title_en'] + '</td>' +
                                            '<td>' + data.result[i].answers[j]['link_es'] + '</td>' +
                                            '<td>' + data.result[i].answers[j]['link_en'] + '</td>' +
                                            '</tr>';
                                }
                                html += '</tbody>' +
                                        '</table>';
                                html += '</div>'
                                html += '</td>';
                                html += '</tr>';
                            } else {

                                html += '<tr>' +
                                        '<td>No disponibles</td>' +
                                        '<td>' + data.result[i].question_es + '</td>' +
                                        '<td>' + data.result[i].question_en + '</td>' +
                                        '    <td> <strong>Productos:</strong><br/>';
                                for (var h = 0; h < data.result[i].products.length; h++) {
                                    html += data.result[i].products[h]['titulo_es'] + '<br/>';
                                }
                                html += '    <br/><strong>Products:</strong><br/>';
                                for (var e = 0; e < data.result[i].products.length; e++) {
                                    html += data.result[i].products[e]['titulo_en'] + '<br/>';
                                }

                                html += '    </td>' +
                                        '    <td><a class="editarPregunta" id="' + data.result[i].id + '" href="javascript:void(0)"><i class="icon-pencil icon-3x" style="color: gray"></i></a>\n\
                                                <a class="eliminarPregunta" id="' + data.result[i].id + '" href="javascript:void(0)"><i class="icon-remove-sign icon-3x" style="color: gray"></i></a></td>' +
                                        '</tr>';
                            }
                        }
                        html += '</tbody>' +
                                '</table>' +
                                '</div>';
                        $('#contenedor').html(html);
                        
                    }
                }, "json");
            }

            function guardarPregunta() {

            }
            function agregar_formulario_preguntas() {


            }

            function agregar_formulario_respuesta() {

            }
        </script>
    </head>
    <body>
        <div id="contenedor" class="grid"></div>
       <div id="dialog" title="Dialog Title" style="display:none">¿Esta seguro que desea eliminar la pregunta?</div>  
</div>
        <script>
        
                function confirmDelete(pregunta)
                {   $( "#dialog" ).dialog({
                resizable: false,
                height:200,
                modal: true,
                closeOnEscape: true,
                buttons: {
                  "Si, Eliminar": function() {
                  $( this ).dialog( "close" );    
                  eliminar(pregunta);
                  },
                  Cancelar: function() {
                    $( this ).dialog( "close" );
                    return false;
                  }
                }
              });
                    $( "#dialog" ).dialog( "open" );        
                  }
                  function eliminar(pregunta){
                      $.ajax({
                        type: 'post',
                        cache: false,
                        url: webservice_path_admin + 'mod_s1.php',
                        data: {pregunta: pregunta}
                    }).done(function(data) {

                        document.location.reload();
                    }).fail(function(e) {
                        alert("error " + e);
                    });
                  }
   
        </script>
    </body>
    
</html>
