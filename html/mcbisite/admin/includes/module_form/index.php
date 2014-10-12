<?php
require_once("../../service/models/config.php");
require_once("../../service/models/funcs.module_form.php");

$content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';
$module_id = isset($_REQUEST['module_id']) ? $_REQUEST['module_id'] : '';

$crm_id = "";
if ($module_id != "") {
    $crm_id = getCRMID($module_id);
}
?>

<!doctype html>
<html>
    <head>
        <title>Creación de Formularios</title>
        <meta name="description" content="">
        <link rel="stylesheet" href="vendor/css/vendor.css" />
        <link rel="stylesheet" href="../../../bisite/css/lightbox.css" />
        <link rel="stylesheet" href="libs/formbuilder.css" />
        <link rel="stylesheet" href="jquery.tagsinput.css"/>
        <style>
            * {
                box-sizing: border-box;
            }

            body {
                background-color: #444;
                font-family: sans-serif;
                width:800px;
            }

            .fb-main {
                background-color: #fff;
                border-radius: 5px;
                min-height: 600px;
            }

            input[type=text] {
                height: 26px;
                margin-bottom: 3px;
            }

            select {
                margin-bottom: 5px;
                font-size: 40px;
            }
        </style>
    </head>
    <body>
        <div id="form_name"><input id="inpt_form_name_es" type="text" placeholder="Ingresa el nombre de tu formulario." style="width:100%"></div>
        <div id="form_name_es"><input id="inpt_form_name_en" type="text" placeholder="Nombren en ingles." style="width:100%"></div>
        <div id="form_mail"><input id="inpt_form_mail" type="text" placeholder="Email " style="width:40%">
            <div id="nCRM"><button id="loadCRM" class="js-save-crm">Load CRM</button></div>
        </div>


        <div class='fb-main'></div>

        <script src="vendor/js/vendor.js"></script>
        <script src="libs/formbuilder.js"></script>
        <script src="jquery.tagsinput.js"></script>
        <script>
            var content_id = "<?= $content_id ?>";
            var module_id = "<?= $module_id ?>";
            var crm_id = "<?= $crm_id ?>";


            $(function() {

                try {
                    parent.onLoading(true);
                } catch (e) {

                }

                $('#inpt_form_mail').tagsInput({
                    // my parameters here
                    'defaultText':'agregue un email',
                    'onAddTag': function(tag) {
                        expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                        if (!expr.test(tag)) {
                            $('#inpt_form_mail').removeTag(tag);
                            //alert('Ingrese un Email Valido');
                        }else{
                             $('.js-save-form').removeAttr('disabled').text(Formbuilder.options.dict.SAVE_FORM);
                        }
                    },
                    'onRemoveTag':function(){
                        $('.js-save-form').removeAttr('disabled').text(Formbuilder.options.dict.SAVE_FORM);
                    }
                });

                getFormStructure();

            });
            $("#form_name").keyup(function() {
                $('.js-save-form').removeAttr('disabled').text(Formbuilder.options.dict.SAVE_FORM);
            });
            $("#form_name_es").keyup(function() {
                $('.js-save-form').removeAttr('disabled').text(Formbuilder.options.dict.SAVE_FORM);
            });
            $("#form_mail").keyup(function() {
                $('.js-save-form').removeAttr('disabled').text(Formbuilder.options.dict.SAVE_FORM);
            });
            $('body').on('click', '#loadCRM', function() {

                var num = prompt("Ingrese un Codigo de Formulario:");
                var flag = false;
                if (!isNaN(num) && num != "" && num != null) {
                    crm_id = num;
                    var params = {};
                    params.codigo = num;
                    params.action = "getCrm";
                    $.ajax({
                        url: '../../service/module_form.php',
                        data: params,
                        type: 'POST',
                        async: false,
                        success: function(r) {

                            loadEvents(r.result, 1);
                        },
                        error: function(a, b, c) {
                            alert(a.responseText);
                        },
                        dataType: 'json'

                    });

                } else {
                    alert("Ingrese un numero valido!");
                }
            });
            function getFormStructure() {
                var data = {};
                params = {};

                data.module_id = module_id;
                data.content_id = content_id;
                params.action = "getform";
                params.data = data;

                $.post('../../service/module_form.php', params, function(data) {
                    if (data.result != false) {

                        var fields = changeBooleanValues(data.result);
                        //AGREGANDO LAS OPCIONES DE LOS CHECKBOXES,RADIO,DROPDOWN
                        for (var k = 0; k < fields.length; k++) {
                            var dataOp = {};
                            var paraOp = {};
                            dataOp.field_id = fields[k].cid;
                            paraOp.action = "getOptions";
                            paraOp.data = dataOp;
                            $.ajax({
                                url: "../../service/module_form.php",
                                type: "post",
                                async: false,
                                dataType: "json",
                                data: paraOp,
                                success: function(data) {


                                    var fields_option = data.result;
                                    var options = new Array();
                                    var field_options = {};
                                    for (var y = 0; y < fields_option.length; y++)
                                    {
                                        var fieldOp = fields_option[y];

                                        var checked = false;
                                        if (fieldOp.checked === '0')
                                        {
                                            checked = true;
                                        }
                                        var newOption = {
                                            label: fieldOp.name,
                                            checked: checked
                                        };
                                        options[y] = newOption;


                                        fields[k].field_options = field_options;
                                        fields[k].field_options.options = options;

                                    }


                                }
                            });


                        }

                        var module_name_es = fields[0].module_name_es;
                        var module_name_en = fields[0].module_name_en;
                        var module_mail = fields[0].module_mail;
                        $('#inpt_form_name_es').val(module_name_es);
                        $('#inpt_form_name_en').val(module_name_en);
                        //$('#inpt_form_mail').val(module_mail);
                        var mails = module_mail.split(",");
                        for(var oo=0;oo<mails.length;oo++){
                            var curmail = mails[oo];
                            if(!$('#inpt_form_mail').tagExist(curmail)){
                                $('#inpt_form_mail').addTag(curmail);
                            }
                        }
                        
                        loadEvents(fields, 0);
                    } else {
                        var fields = [];
                        loadEvents(fields, 0);
                    }
                }, "json");
            }
            function stateSaveButton() {


                $('.js-save-form').removeAttr('disabled').text('Guardar Cambios');

            }
            function loadEvents(fields, fromCRM) {

                console.log("ENVIO CRM" + fromCRM);
                fb = new Formbuilder({
                    selector: '.fb-main',
                    bootstrapData: fields,
                    crm: fromCRM
                });
                //[{"label":"Ingresa tu nombre","field_type":"text","required":true,"field_options":{},"cid":""}]
                //if(fromCRM!==0){stateSaveButton();}

                fb.on('save', function(payload) {

                    payload = JSON.parse(payload);
                    console.dir(payload);
                    var data = {};
                    params = {};
                    data.form_name_es = $('#inpt_form_name_es').val();
                    if ($.trim($('#inpt_form_name_en').val()).length > 0) {
                        data.form_name_en = $('#inpt_form_name_en').val();
                    }
                    else
                    {
                        data.form_name_en = $('#inpt_form_name_es').val();
                    }

                    data.form_mail = $('#inpt_form_mail').val();
                    data.module_id = module_id;
                    data.content_id = content_id;
                    data.crm_id = crm_id;

                    data.form_field = payload;




                    params.action = "createform";
                    params.data = data;

                    $.post('../../service/module_form.php', params, function(data) {
                        module_id = data.module_id;
                        try
                        {
                            parent.closeModuleConfiguration();
                        }
                        catch(e)
                        {
                           getFormStructure(); 
                        }
//                        getFormStructure();
                        
                    }, "json");


                });

                fb.on('name', function(payload) {


                    if (payload === "ERROR") {

                        alert("Debe Colocar un nombre al formulario");
                    }


                });



            }

            function changeBooleanValues(data) {

                for (var i = 0; i < data.length; i++) {
                    if (data[i].required == 'true') {
                        data[i].required = true;

                    } else {
                        data[i].required = false;
                    }
                    if (data[i].hidden == 'true') {
                        data[i].hidden = true;

                    } else {
                        data[i].hidden = false;
                    }
                }

                return data;
            }
            //Validar las Opciones Min y Max Lenght en campos de texto
            function valmin() {
                var minimo = parseInt($("#min").val(), 10);
                var maximo = parseInt($("#max").val(), 10);

                if (isNaN(maximo))
                {

                }
                else if (maximo !== 0) {

                    if (minimo !== 0) {
                        if (minimo < maximo) {

                        }
                        else
                        {

                            $("#min").val(minimo = maximo - 1);
                        }
                    }
                }
            }
            function valmax()
            {
                var minimo = parseInt($("#min").val(), 10)
                var maximo = parseInt($("#max").val(), 10)
                if (isNaN(minimo))
                {

                }
                else if (minimo !== 0) {

                    if (maximo !== 0) {
                        if (minimo < maximo) {

                        }
                        else
                        {

                            $("#max").val(maximo = minimo + 1);
                        }
                    }
                }
            }


        </script>

    </body>
</html>