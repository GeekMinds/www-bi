<?php
require_once("service/models/config.php");
$content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';
$module_id = isset($_REQUEST['module_id']) ? $_REQUEST['module_id'] : '-1';

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

        <title>Administrador de usuarios</title>
        <script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>

        <!--JTABLES-->


        <!--<link rel="stylesheet" type="text/css" href="css/kickstart/kickstart.css" media="all" />-->
        <link href="css/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
        <link href="js/jtable/themes/lightcolor/gray/jtable.css" rel="stylesheet" type="text/css" />

       	<link rel="stylesheet" type="text/css" href="css/opa-icons.css">

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
                min-height:700px;
            }
            .ui-widget-overlay{
                background: #fff !important;
            }
            #UserTableContainer{
                max-width: 920px;
                margin: auto;
            }

            .permissions_button{
                cursor:pointer;
            }
        </style>



        <script type="text/javascript" charset="utf-8">
            /* Data set - can contain whatever information you want */
            var usermessages = {
                serverCommunicationError: 'Ocurrió un error en la comunicación con el servidor.',
                loadingMessage: 'Cargando usuarios...',
                noDataAvailable: 'No hay datos disponibles!',
                addNewRecord: 'Crear nuevo usuario',
                editRecord: 'Editar usuario',
                areYouSure: '¿Está seguro?',
                deleteConfirmation: 'El usuario será eliminado. ¿Está seguro?',
                save: 'Guardar',
                saving: 'Guardando',
                cancel: 'Cancelar',
                deleteText: 'Eliminar',
                deleting: 'Eliminando',
                error: 'Error',
                close: 'Cerrar',
                cannotLoadOptionsFor: 'No se pueden cargar las opciones para el campo {0}',
                pagingInfo: 'Mostrando usuarios {0} a {1} de {2}',
                canNotDeletedRecords: 'No se puede borrar usuario(s) {0} de {1}!',
                deleteProggress: 'Eliminando {0} de {1} usuarios, procesando...',
                pageSizeChangeLabel: 'Usuarios por página',
                gotoPageLabel: 'Ir a página'
            };
            $(document).ready(function() {
                getUsersTable();
            });


            function getUsersTable() {
                $('#UserTableContainer').jtable({
                    title: 'Administración de usuarios',
                    paging: false, //Enable paging
                    pageSize: 5, //Set page size (default: 10)
                    pageSizes: [5, 10, 50, 100, 250, 500],
                    sorting: true, //Enable sorting
                    messages: usermessages,
                    actions: {
                        listAction: 'service/user.php?action=list',
                        createAction: 'service/user.php?action=create',
                        updateAction: 'service/user.php?action=update',
                        deleteAction: 'service/user.php?action=delete'
                    },
                    fields: {
                        id: {
                            key: true,
                            create: false,
                            edit: false,
                            list: false
                        },
                        permissions: {
                            title: 'Permisos',
                            width: '5%',
                            sorting: false,
                            edit: false,
                            create: false,
                            listClass: 'permissions_column',
                            display: function(adminData) {
                                //Create an image that will be used to open child table
                                var $img = $('<span title=".icon32  .icon-color  .icon-locked " class="icon32 icon-color icon-locked permissions_button"></span>');
                                //Open child table when user clicks the image
                                $img.click(function() {
                                    if (adminData.record.group_id ==<?= SUPER_ADMINISTRADOR ?>) {
                                        alert("Los permisos de este usuario no son configurables.");
                                        return;
                                    }

                                    $('#UserTableContainer').jtable('openChildTable',
                                            $img.closest('tr'),
                                            {
                                                title: adminData.record.user_name + ' - Permisos',
                                                actions: {
                                                    listAction: 'service/permission.php?action=list&admin_id=' + adminData.record.id + "&group_id=" + adminData.record.group_id,
                                                    deleteAction: 'service/permission.php?action=list&admin_id=' + adminData.record.id + "&group_id=" + adminData.record.group_id,
                                                    updateAction: 'service/permission.php?action=list&admin_id=' + adminData.record.id + "&group_id=" + adminData.record.group_id,
                                                    createAction: 'service/permission.php?action=create&admin_id=' + adminData.record.id + "&group_id=" + adminData.record.group_id
                                                },
                                                fields: {
                                                    admin_id: {
                                                        type: 'hidden',
                                                        defaultValue: adminData.record.id
                                                    },
                                                    permission_id: {
                                                        key: true,
                                                        create: false,
                                                        edit: false,
                                                        list: false
                                                    },
                                                    site_id: {
                                                        title: 'Portal',
                                                        width: '30%',
                                                        options: 'service/site.php?action=list&cli=permissions&group_id=' + adminData.record.group_id
                                                    },
                                                    section_id: {
                                                        title: 'Sección',
                                                        dependsOn: 'site_id',
                                                        width: '30%',
                                                        options: function(data) {
                                                            if (data.source == 'list') {
                                                                //Return url of all countries for optimization. 
                                                                //This method is called for each row on the table and jTable caches options based on this url.
                                                                return "service/modc.php?action=list&cli=permissions&group_id=" + adminData.record.group_id + "&site_id=" + data.dependedValues.site_id;
                                                            }
                                                            //This code runs when user opens edit/create form or changes continental combobox on an edit/create form.
                                                            //data.source == 'edit' || data.source == 'create'
                                                            return 'service/modc.php?action=list&cli=permissions&site_id=' + data.dependedValues.site_id + "&group_id=" + adminData.record.group_id;
                                                        }

                                                    },
                                                    page_id: {
                                                        title: "Página",
                                                        dependsOn: 'section_id',
                                                        options: function(data) {
                                                            if (data.source == 'list') {
                                                                return 'service/modc.php?action=listpages&cli=permissions&group_id=' + adminData.record.group_id + "&section_id=" + data.dependedValues.section_id;
                                                            }
                                                            return 'service/modc.php?action=listpages&cli=permissions&section_id=' + data.dependedValues.section_id + "&group_id=" + adminData.record.group_id;
                                                        }
                                                    },
                                                    content_id: {
                                                        title: 'Contenido',
                                                        dependsOn: 'page_id',
                                                        options: function(data) {
                                                            if (data.source == 'list') {
                                                                return 'service/container.php?action=getcontainerlist&cli=permissions&group_id=' + adminData.record.group_id + "&page_id=" + data.dependedValues.page_id;
                                                            }
                                                            return 'service/container.php?action=getcontainerlist&cli=permissions&page_id=' + data.dependedValues.page_id + "&group_id=" + adminData.record.group_id;
                                                        }
                                                    }
                                                },
                                                rowInserted: function(event, data) {

                                                    $(data.row).find("td").each(function() {
                                                        if ($(this).html() == "") {
                                                            $(this).html("*");
                                                        }
                                                    });

                                                }

                                            }, function(data) { //opened handler
                                        data.childTable.jtable('load');
                                    });
                                });
                                //Return image to show on the person row
                                return $img;
                            }
                        },
                        user_name: {
                            title: 'Nombre',
                        },
                        email: {
                            title: 'Email',
                            inputClass: 'validate[required,custom[email]]'
                        },
                        login: {
                            title: 'Login User',
                            inputClass: 'validate[required]'
                        },
                        password: {
                            title: 'Password',
                            type: 'password',
                            list: false,
                            edit: false,
                            inputClass: 'validate[required]'
                        },
                        phone: {
                            title: 'Teléfono contacto',
                            list: false,
                            inputClass: 'validate[required,custom[phone]]'

                        },
                        group_id: {
                            title: 'Tipo de usuario',
                            options: 'service/group.php?action=list'
                        },
                        country_id: {
                            title: 'País',
                            options: 'service/country.php?action=list'
                        },
                        description: {
                            title: 'Descripción',
                            type: "textarea",
                            list: false,
                            edit: false,
                            create: false
                        },
                        enabled: {
                            title: 'Habilitado',
                            type: 'checkbox',
                            values: {'0': 'Deshabilitado', '1': 'Habilitado'},
                            defaultValue: '1'
                        }
                    },
                    formCreated: function(event, data) {
                        data.form.validationEngine();
                    },
                    formSubmitting: function(event, data) {
                        return data.form.validationEngine('validate');
                    },
                    formClosed: function(event, data) {
                        data.form.validationEngine('hide');
                        data.form.validationEngine('detach');
                    }
                });


                $('#UserTableContainer').jtable('load');
            }


        </script>
    </head>

    <body >
        <div id="UserTableContainer"></div>
    </body>

</html>