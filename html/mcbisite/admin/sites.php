<?php
require_once("service/models/config.php");
$content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';
$module_id = isset($_REQUEST['module_id']) ? $_REQUEST['module_id'] : '-1';

//Segmento de cógigo para redireccionar desde un iframe cuando no está logueado
$logued = true;
if (!isUserLoggedIn()) {
    $logued = false;
    echo '<script type="text/javascript">top.location.href="' . $websiteUrl . 'login.php";</script>';
    die();
}


if ($module_id == "") {
    $module_id = "-1";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />

        <title>Administrador de Portales</title>
        <script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>

        <!--JTABLES-->
        <!--<link rel="stylesheet" type="text/css" href="css/kickstart/kickstart.css" media="all" />-->
        <link href="css/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
        <link href="js/jtable/themes/lightcolor/gray/jtable.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap-cerulean.css">  

        <!-- COLOR PICKER -->
        <link rel="stylesheet" type="text/css" href="css/spectrum.css">
        <script type="text/javascript" src="js/spectrum.js"></script>
        <script type="text/javascript" src="js/i18n/jquery.spectrum-es.js"></script>
        <!-- <script type='text/javascript' src='js/color_picker.js'></script>-->


        <!--<script src="jTable-PHP-Samples/Codes/scripts/jquery-1.6.4.min.js" type="text/javascript"></script>-->
        <script src="js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
        <script src="js/jtable/jquery.jtable.js" type="text/javascript"></script>
        <!--JTABLES-->
        <!--<link href="./css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />-->
    <!--<script type="text/javascript" src="js/jquery.validationEngine.js"></script>-->
        <link href="css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="js/jquery.validationEngine-es.js"></script>
        <style>
            form.jtable-dialog-form div.jtable-input-field-container{
                float:none !important;
                width:auto !important;	
            }
            body{
                min-height:1200px;
                overflow:hidden;
            }
            .ui-widget-overlay{
                background: #fff !important;
            }
            #SiteTableContainer{
                max-width: 920px;
                margin: auto;
            }

            .btn_admin_site{
                border-style: dashed;
                padding: 5px;
                cursor: pointer;
                background-color: #E6E7E9;
                font-family: "robotoregular";
                display: inline-block;
                border-width: 2px;
                border-color: #969696;
                color: #7C7C7C;
                font-size: 14px;	
            }
            .btn_admin_site:hover{
                background-color:#FFF;	
            }


        </style>



        <script type="text/javascript" charset="utf-8">
            /* Data set - can contain whatever information you want */
            var sitemessages = {
                serverCommunicationError: 'Ocurrió un error en la comunicación con el servidor.',
                loadingMessage: 'Cargando portales...',
                noDataAvailable: 'No hay datos disponibles!',
                addNewRecord: 'Crear nuevo portal',
                editRecord: 'Editar portal',
                areYouSure: '¿Está seguro?',
                deleteConfirmation: 'El portal será eliminado. ¿Está seguro?',
                save: 'Guardar',
                saving: 'Guardando',
                cancel: 'Cancelar',
                deleteText: 'Eliminar',
                deleting: 'Eliminando',
                error: 'Error',
                close: 'Cerrar',
                cannotLoadOptionsFor: 'No se pueden cargar las opciones para el campo {0}',
                pagingInfo: 'Mostrando portales {0} a {1} de {2}',
                canNotDeletedRecords: 'No se puede borrar portal(es) {0} de {1}!',
                deleteProggress: 'Eliminando {0} de {1} portales, procesando...',
                pageSizeChangeLabel: 'Portales por página',
                gotoPageLabel: 'Ir a página'
            };
            $(document).ready(function() {
                getSitesTable();
            });


            function getSitesTable() {
                $('#SiteTableContainer').jtable({
                    title: 'Administración de Portales',
                    paging: true, //Enable paging
                    pageSize: 5, //Set page size (default: 10)
                    pageSizes: [5, 10, 50, 100, 250, 500],
                    sorting: true, //Enable sorting
                     messages: sitemessages,
                    actions: {
                        listAction: 'service/site.php?action=list',
                        <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR))){?>
                            createAction: 'service/site.php?action=create',
                        <?php }?>
                        <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                            updateAction: 'service/site.php?action=update',
                        <?php }?>
                        <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR))){?>
                            deleteAction: 'service/site.php?action=delete'
                        <?php }?>
                        
                        
                    },
                    fields: {
                        id: {
                            key: true,
                            create: false,
                            edit: false,
                            list: false
                        },
                        country_group_id: {
                            title: 'País al que pertenece el portal',
                            options: 'service/country.php?action=list',
                            edit: true,
							list:false
                        },
                        country_id: {
                            title: 'Portal principal para',
                            options: 'service/country.php?action=list',
                            edit: true
                        },
                        title_es: {
                            title: 'Nombre español',
                            inputClass: 'validate[required]'
                        },
                        title_en: {
                            title: 'Nombre inglés',
                            inputClass: 'validate[required]',
							list:false
                        },
                        alias: {
                            title: 'Alias',
                            inputClass: 'validate[funcCall[AliasValidate],required]'
                        },
                        show_exchange_rate: {
                            title: 'Mostrar tipo de cambio',
                            type: 'checkbox',
                            values: {'0': 'Oculto', '1': 'Visible'},
                            defaultValue: '1',
                            list: false
                        },
                        local_currency_id: {
                            title: 'Moneda local',
                            options: 'service/currency.php?action=list',
                            list: false
                        },
                        foreing_currency_id: {
                            title: 'Moneda extranjera',
                            options: 'service/currency.php?action=list',
                            list: false
                        },
                        background_light_color: {
                            title: 'Color más claro (Hexadecimal)',
                            defaultValue: "#40baeb",
                            list: false
                        },
                        background_middle_color: {
                            title: 'Color medio (Hexadecimal)',
                            defaultValue: "#033d78",
                            list: false
                        },
                        background_dark_color: {
                            title: 'Color más obscuro (Hexadecimal)',
                            defaultValue: "#022b51",
                            list: false
                        },
                        foreground_color:{
                            title: 'Color de texto header (Hexadecimal)',
                            defaultValue: "#fff",
                            list: false
                        },
                        description: {
                            title: 'Descripción',
                            type: "textarea",
                            list: false
                        },
                        site: {
                            title: 'Administrar sitio',
                            display: function(data) {
                                //return '<div class="btn_admin_site" onclick="gotoAdminSite(this)">ADMINISTRAR</div>';
                                //return '<div style="text-align: center;"><button class="medium" onclick="gotoAdminSite(this)"><i class="icon-edit"></i>ADMINISTRAR</button></div>';
                                //
                                return '<div style="text-align: center;"><a class="btn btn-success" href="javascript:void(0)" onclick="gotoAdminSite(this)"><i class="icon-zoom-in icon-white"></i> Administrar</a></div>';
                            },
                            create: false,
                            sorting:false,
                            edit: false
                        }
                    },
                    formCreated: function(event, data) {

                        if ($("#Edit-background_light_color").val().replace(" ", "") == "") {
                            $("#Edit-background_light_color").val("#40baeb");
                        }
                        if ($("#Edit-background_middle_color").val().replace(" ", "") == "") {
                            $("#Edit-background_middle_color").val("#033d78");
                        }
                        if ($("#Edit-background_dark_color").val().replace(" ", "") == "") {
                            $("#Edit-background_dark_color").val("#022b51");
                        }
                        if ($("#Edit-foreground_color").val().replace(" ", "") == "") {
                            $("#Edit-foreground_color").val("#fff");
                        }

                        loadColorPicker("#Edit-background_light_color", $("#Edit-background_light_color").val());
                        loadColorPicker("#Edit-background_middle_color", $("#Edit-background_middle_color").val());
                        loadColorPicker("#Edit-background_dark_color", $("#Edit-background_dark_color").val());
                        loadColorPicker("#Edit-foreground_color", $("#Edit-foreground_color").val());
                        $("#Edit-alias").attr('maxlength', '20');
                        var params = {};
                        params.action = "Droplist";
                        $.ajax({
                            url: "service/country.php?action=Droplist",
                            data: params,
                            type: "POST",
                            async: false,
                            dataType: 'json',
                            success: function(data) {
                                var resultado = data.Data;
								if(resultado!=undefined){
									for (var i = 0; i < resultado.length; i++) {
										var value = resultado[i].Value;
										if(value!=$("select#Edit-country_id").val()){
											$("select#Edit-country_id option[value='" + value + "']").remove();
										}
									}
								}
                            }
                        });
						
						$("select#Edit-country_group_id option[value='6']").remove();
						if(data.record == undefined){
							$('select#Edit-country_group_id option[value="1"]').attr("selected",true);
						}
                        $('#Edit-alias').keyup(function() {
                            var valor = $('#Edit-alias').val();
                            valor = valor.toLowerCase();
                            valor = valor.replace(new RegExp("[^a-z]"), "");
                            $('#Edit-alias').val(valor);
                        });
                        data.form.validationEngine({
                            ajaxFormValidation: true,
                            onAjaxFormComplete: function() {
                                console.log('1');
                            }
                        });
                    },
                    formSubmitting: function(event, data) {
						if($("select#Edit-country_id").val()!="6" && $("select#Edit-country_id").val()!=$("select#Edit-country_group_id").val()){
							var pais_principal = $("select#Edit-country_id option[value='"+$("select#Edit-country_id").val()+"']").text();
							var pais_grupo = $("select#Edit-country_group_id option[value='"+$("select#Edit-country_group_id").val()+"']").text();
							$('#SiteTableContainer').jtable('showError','El portal principal '+pais_principal + ' no puede pertenecer a otro país. Usted seleccionó que el portal '+pais_principal+ " pertenecerá al grupo de portales para "+pais_grupo+", su operación es incorrecta.");
							return false;	
						}
                        var validado = data.form.validationEngine('validate');
                        return validado;

                    },
                    formClosed: function(event, data) {
                        data.form.validationEngine('hide');
                        data.form.validationEngine('detach');
                    }
                });


                $('#SiteTableContainer').jtable('load');
            }

            function gotoAdminSite(element) {
                var site_id = $(element).parent().parent().parent().attr("data-record-key");
                //window.location.href = "./includes/module_bar_nav/draganddrop.php?site="+site_id;
                window.location.href = "general.php?site=" + site_id;
            }


            function loadColorPicker(css_element, color) {
                $(css_element).spectrum({
                    color: color,
                    showInput: true,
                    className: "full-spectrum",
                    showInitial: true,
                    showPalette: true,
                    showSelectionPalette: true,
                    maxPaletteSize: 10,
                    preferredFormat: "hex",
                    localStorageKey: "spectrum.demo",
                    move: function(color) {

                    },
                    show: function() {

                    },
                    beforeShow: function() {

                    },
                    hide: function() {

                    },
                    change: function() {

                    },
                    palette: [
                        ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
                            "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(255, 255, 255)"],
                        ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
                            "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
                        ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
                            "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
                            "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
                            "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
                            "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
                            "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
                            "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
                            "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
                            "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
                            "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
                    ]
                });
            }

        </script>
        <script>
            function AliasValidate() {
                var msg = "";
                var params = {};
                params.action = "validatealias";
                params.fieldvalue = $("#Edit-alias").val();
                var editid = $('#Edit-id').val();
                if (editid !== "") {
                    params.editid = editid;
                }
                $.ajax({
                    url: "service/site.php",
                    data: params,
                    type: "POST",
                    async: false,
                    dataType: 'json',
                    success: function(data) {
                        var resultado = data[1];
                        if (resultado === false) {
                            msg = "Ya existe este alias";
                        }
                    }
                });
                if (msg !== "") {
                    return msg;
                }
            }
            
            
        </script>
    </head>

    <body >
        <div id="SiteTableContainer"></div>
    </body>

</html>