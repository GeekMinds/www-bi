<?php
$content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';
$module_id = isset($_REQUEST['module_id']) ? $_REQUEST['module_id'] : '-1';
$lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : 'es';
if ($module_id == "") {
    $module_id = "-1";
}
?>

<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]--><head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- If you are using CSS version, only link these 2 files, you may add app.css to use for your overrides if you like. -->

        <style>
            body{
                min-height: 470px !important;
                min-width: 470px !important;
                display: table;
            }
        </style>
        <script src="js/jquery.min.js"></script>
        <script src="js/kickstart.js"></script>
        <script type="text/javascript" src="../../js/common.js.php"></script>
        <link rel="stylesheet" href="css/kickstart.css" media="all" />
        <script>
            var content_id = '<?= $content_id ?>';
            var module_id = '<?= $module_id ?>';
            var lang = '<?= $lang ?>';
            var params = {};

            params.id = content_id;
            params.module_id = module_id;


            function getContent(action) {

                params.action = action;
                params.module_id = module_id;
                var check = '';

                $.post(webservice_path_admin + 'mod_recommendation.php', params, function(data) {

                    for (n = 0; n < Object.keys(data.Records).length; n++) {
                        var checked = "";
                        if (parseInt(data.Records[n].selected) >= 1) {
                            checked = " checked "
                        }
                        check = check + '<input type="checkbox" id="check1[]" value="' + data.Records[n].id + '" ' + checked + '/><label for="check1" class="inline">' + data.Records[n].titulo_es + '</label><br />';

                    }

                    if (data.Data) {
                        $('#titulo_es').val(data.Data.titulo_es);
                        $('#titulo_en').val(data.Data.titulo_en);
                    }
                    check = "<legend>Seleccione los productos a mostrar en la recomendación:</legend>" + check;
                    $('#interest').html(check);
                    //alert(data.Records[0]) ;
                }, 'json');
            }

            $(document).ready(function() {
                try {
                    parent.onLoading(true);
                } catch (e) {

                }
                getContent('listproducts');
                $('#save').click(function() {
                    params.titulo_es = $('#titulo_es').val();
                    params.titulo_en = $('#titulo_en').val();
                    params.action = 'save';
                    params.content_id = content_id;
                    params.module_id = module_id;
                    var productos = new Array();
                    $('input[id^="check1"]').each(function(i) {
                        if ($(this).is(':checked')) {
                            productos.push(this.value);
                        }

                    });
                    params.productos = productos;
                    var check = '';
                    $.post(webservice_path_admin + 'mod_recommendation.php', params, function(data) {
                        if (data.Result == "ERROR") {
                            alert(data.Message);
                        } else {
                            alert("Información guardada");
                            try {
                                parent.closeModuleConfiguration();
                            }
                            catch (e) {
                            }
                        }
                    });
                });
            });





        </script>
    </head>
    <body >
        <form class="vertical">
            <label for="titulo_es">T&iacute;tulo en espa&ntilde;ol</label>
            <input type="text" id="titulo_es" placeholder="Titulo ES">
            <label for="titulo_es">T&iacute;tulo en ingl&eacute;s</label>
            <input type="text" id="titulo_en" placeholder="Titulo EN">

            <fieldset id="interest">

            </fieldset>
        </form>

        <button id="save" class="blue pull-right">Guardar</button>
    </body>
</html>