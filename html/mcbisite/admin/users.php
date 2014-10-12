<?php
require_once("service/models/config.php");
require_once("./service/models/funcs.user.php");
$logued = true;
if (!isUserLoggedIn()) {
    $logued = false;
    echo '<script type="text/javascript">top.location.href="' . $websiteUrl . 'login.php";</script>';
    die();
}
$profile = getInfoUserGroup()[0];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Administrador de usuarios</title>
        <link rel="stylesheet" href="css/jquery-ui.css"/>
        <link href="js/jtable/themes/lightcolor/gray/jtable.css" rel="stylesheet" type="text/css" />
       	<link rel="stylesheet" type="text/css" href="css/opa-icons.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap-cerulean.css">  
        <link href="css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="includes/mod_c/jqwidgets/styles/jqx.base.css" type="text/css" />
        <link rel="stylesheet" href="includes/mod_c/jqwidgets/styles/jqx.bootstrap.css" type="text/css" />
        <link href="css/search_cmb/select2.css" rel="stylesheet"/>



        <style>
            form.jtable-dialog-form div.jtable-input-field-container{
                float:none !important;
                width:auto !important;	
            }
            body{
                min-height:1000px;
            }
            .ui-widget-overlay{
                background: #fff !important;
            }
            .atable{
                padding-right: 20px;
            }

            .permissions_button{ 
                cursor:pointer;
            }
            textarea{
                min-width: 100% !important;
            }
            input#Edit-name {
                min-width: 100% !important;
            }
            .row{
                margin:0px !important;
            }
        </style>
    </head>
    <body>
        <div clas="row-fluid">
            <div class="span-12">
                <h2>Perfil:<small>&nbsp;<?= $profile['name'] ?></small></h2>
                <p><?= $profile['description'] ?></p>
                <?php if (hasPermissionsSite(array(SUPER_ADMINISTRADOR))) { ?>
                    <h3>Gestión de Usuarios Administrativos</h3>
                    <div id="UserTableContainer" class="atable"></div><br>
                    <h3>Grupos de Usuarios del Sitio</h3>
                    <div id="adminGroups" class="atable"></div><br>
                <?php } ?>
                <h3>Administración de Roles</h3>
                <?php if (hasPermissionsSite(array(SUPER_ADMINISTRADOR))) { ?>
                    <div id="adminRegRol" class="atable"></div><br>
                <?php } ?>
                <div id="adminGestRol" class="atable"></div><br>
                <div id="adminEditRol" class="atable"></div>

            </div>

            <div id="dialog-error-message" title="Error" style="display:none">
                <p id="error-msg">
                </p>
            </div>
            <div id="dialog-clone-message" title="Clonar Rol" style="display:none">
                <p>Ingrese el nombre del nuevo rol</p>
                <input type="hidden" id="id-role"/>
                <input type="text" id="newNameRole" style="width:100%"/>
                <textarea id="newDescRole"></textarea>
            </div>
        </div>
<!--        <script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>-->
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
        <script src="js/search_cmb/select2.js"></script>
        <script src="js/jtable/jquery.jtable.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="js/jquery.validationEngine-es.js"></script>
        <script src="js/bootstrap-transition.js"></script>
        <script src="js/bootstrap-alert.js"></script>
        <script src="js/bootstrap-modal.js"></script>
        <script src="js/bootstrap-dropdown.js"></script>
        <script src="js/bootstrap-scrollspy.js"></script>
        <script src="js/bootstrap-tab.js"></script>
        <script src="js/bootstrap-tooltip.js"></script>
        <script src="js/bootstrap-popover.js"></script>
        <script src="js/bootstrap-button.js"></script>
        <script src="js/bootstrap-collapse.js"></script>
        <script src="js/bootstrap-carousel.js"></script>
        <script src="js/bootstrap-typeahead.js"></script>
        <script src="js/bootstrap-tour.js"></script>

        <script type="text/javascript" src="includes/mod_c/scripts/demos.js"></script>
        <script type="text/javascript" src="includes/mod_c/jqwidgets/jqxcore.js"></script>
        <script type="text/javascript" src="includes/mod_c/jqwidgets/jqxbuttons.js"></script>
        <script type="text/javascript" src="includes/mod_c/jqwidgets/jqxscrollbar.js"></script>
        <script type="text/javascript" src="includes/mod_c/jqwidgets/jqxpanel.js"></script>
        <script type="text/javascript" src="includes/mod_c/jqwidgets/jqxtree.js"></script>
        <script type="text/javascript" src="includes/mod_c/jqwidgets/jqxdragdrop.js"></script>
        <script type="text/javascript" src="includes/mod_c/jqwidgets/jqxcheckbox.js"></script>
        <script type="text/javascript" src="includes/mod_c/jqwidgets/jqxexpander.js"></script>
<!--        <script src="js/jquery.js" type="text/javascript"></script>-->

        <script>
            var jqxTree = '<input id="Edit-elim" type="hidden" name="elim">' +
                    '<input id="Edit-idents" type="hidden" name="idents">' +
                    '<div id="jqxWidget">' +
                    '<div id="jqxExpander">' +
                    '<div>' +
                    'Permisos' +
                    '</div>' +
                    '<div style="overflow: hidden;">' +
                    '<div style="border: none;" id="jqxTree">' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            var userMessages = {
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
            var rolMessages = {
                serverCommunicationError: 'Ocurrió un error en la comunicación con el servidor.',
                loadingMessage: 'Cargando roles...',
                noDataAvailable: 'No hay datos disponibles!',
                addNewRecord: 'Crear nuevo rol',
                editRecord: 'Editar rol',
                areYouSure: '¿Está seguro?',
                deleteConfirmation: 'El rol será eliminado. ¿Está seguro?',
                save: 'Guardar',
                saving: 'Guardando',
                cancel: 'Cancelar',
                deleteText: 'Eliminar',
                deleting: 'Eliminando',
                error: 'Error',
                close: 'Cerrar',
                cannotLoadOptionsFor: 'No se pueden cargar las opciones para el campo {0}',
                pagingInfo: 'Mostrando roles {0} a {1} de {2}',
                canNotDeletedRecords: 'No se puede borrar rol(es) {0} de {1}!',
                deleteProggress: 'Eliminando {0} de {1} roles, procesando...',
                pageSizeChangeLabel: 'Roles por página',
                gotoPageLabel: 'Ir a página'
            };
            var usuarioMensajes = {
                serverCommunicationError: 'Ocurrió un error en la comunicación con el servidor.',
                loadingMessage: 'Cargando usuarios...',
                noDataAvailable: 'No hay datos disponibles!',
                addNewRecord: 'Agregar estos permisos a un usuario',
                editRecord: 'Editar usuario',
                areYouSure: '¿Está seguro?',
                deleteConfirmation: 'Los permisos de este rol seran revocados para este usuario. ¿Está seguro?',
                save: 'Guardar',
                saving: 'Guardando',
                cancel: 'Cancelar',
                deleteText: 'Eliminar',
                deleting: 'Eliminando',
                error: 'Error',
                close: 'Cerrar',
                cannotLoadOptionsFor: 'No se pueden cargar las opciones para el campo {0}',
                pagingInfo: 'Mostrando usuarios {0} a {1} de {2}',
                canNotDeletedRecords: 'No se pueden quitar los permisos a los usuario(s) {0} de {1}!',
                deleteProggress: 'Quitando permisos {0} de {1} usuarios, procesando...',
                pageSizeChangeLabel: 'Usuarios por página',
                gotoPageLabel: 'Ir a página'
            };
            var permisoMensajes = {
                serverCommunicationError: 'Ocurrió un error en la comunicación con el servidor.',
                loadingMessage: 'Cargando permisos...',
                noDataAvailable: 'No hay datos disponibles!',
                addNewRecord: 'Modificar permisos',
                editRecord: 'Editar permiso',
                areYouSure: '¿Está seguro?',
                deleteConfirmation: 'Este permiso será eliminado. ¿Está seguro?',
                save: 'Guardar',
                saving: 'Guardando',
                cancel: 'Cancelar',
                deleteText: 'Eliminar',
                deleting: 'Eliminando',
                error: 'Error',
                close: 'Cerrar',
                cannotLoadOptionsFor: 'No se pueden cargar las opciones para el campo {0}',
                pagingInfo: 'Mostrando permisos {0} a {1} de {2}',
                canNotDeletedRecords: 'No se pueden eliminar los permiso(s) {0} de {1}!',
                deleteProggress: 'Quitando permisos {0} de {1}, procesando...',
                pageSizeChangeLabel: 'Permisos por página',
                gotoPageLabel: 'Ir a página'
            };
            var grupoMensajes = {
                serverCommunicationError: 'Ocurrió un error en la comunicación con el servidor.',
                loadingMessage: 'Cargando grupos...',
                noDataAvailable: 'No hay datos disponibles!',
                addNewRecord: 'Crear nuevo grupo',
                editRecord: 'Editar grupo',
                areYouSure: '¿Está seguro?',
                deleteConfirmation: 'El grupo será eliminado. ¿Está seguro?',
                save: 'Guardar',
                saving: 'Guardando',
                cancel: 'Cancelar',
                deleteText: 'Eliminar',
                deleting: 'Eliminando',
                error: 'Error',
                close: 'Cerrar',
                cannotLoadOptionsFor: 'No se pueden cargar las opciones para el campo {0}',
                pagingInfo: 'Mostrando grupos {0} a {1} de {2}',
                canNotDeletedRecords: 'No se pueden eliminar los grupo(s) {0} de {1}!',
                deleteProggress: 'Quitando grupos {0} de {1}, procesando...',
                pageSizeChangeLabel: 'Grupos por página',
                gotoPageLabel: 'Ir a página'
            };
            $(function() {
                $('#UserTableContainer').jtable({
                    title: 'Gestión de usuarios administrativos',
                    sorting: true,
                    paging: true,
                    pageSize: 5,
                    pageSizeChangeArea: false,
                    saveUserPreferences: false,
                    messages: userMessages,
                    actions: {
                        listAction: 'service/user.php?action=list',
                        createAction: 'service/user.php?action=create',
                        updateAction: 'service/user.php?action=update'
                    },
                    fields: {
                        id: {
                            key: true,
                            create: false,
                            edit: false,
                            list: false
                        },
                        login: {
                            title: 'Login',
                            inputClass: 'validate[required]'
                        },
                        email: {
                            title: 'Correo Electrónico',
                            inputClass: 'validate[required]'
                        },
                        password: {
                            title: 'Password',
                            type: 'password',
                            list: false,
                            edit: false,
                            inputClass: 'validate[required]'
                        },
                        name: {
                            title: 'Nombre',
                            inputClass: 'validate[required]'
                        },
                        phone: {
                            title: 'Teléfono',
                            inputClass: 'validate[required]'
                        },
                        country_id: {
                            title: 'País',
                            options: 'service/user.php?action=listCountry',
                            inputClass: 'validate[required]'
                        },
                        group_id: {
                            title: 'Perfil de usuario',
                            options: 'service/user.php?action=listProfiles',
                            inputClass: 'validate[required]'
                        },
                        enabled: {
                            title: 'Habilitado',
                            options: [{Value: '1', DisplayText: 'Habilitado'}, {Value: '0', DisplayText: 'Deshabilitado'}]
                        }
                    }
                });
                $('#UserTableContainer').jtable('load');

                $('#adminRegRol').jtable({
                    title: 'Roles de Administrador Regional',
                    messages: rolMessages,
                    actions: {
                        listAction: 'service/roles.php?action=listroladminreg',
                        createAction: 'service/roles.php?action=createroladminreg',
                        updateAction: 'service/roles.php?action=updateroladminreg',
                        deleteAction: 'service/roles.php?action=deleterole&table=0'
                    },
                    fields: {
                        id: {
                            key: true,
                            create: false,
                            edit: false,
                            list: false
                        },
                        name: {
                            title: 'Nombre del Rol',
                            inputClass: 'validate[required]'
                        },
                        description: {
                            title: 'Descripción',
                            type: 'textarea',
                            inputClass: 'validate[required]'
                        },
                        usuario: {
                            title: 'Usuarios Asignados',
                            create: false,
                            edit: false,
                            display: function(rolesData) {
                                var $boton_users = $('<a class="btn btn-primary control_btn" href="#" style="display: block;"><i class="icon-user icon-white "></i> Ver Usuarios</a>');
                                $boton_users.click(function() {
                                    $('#adminRegRol').jtable('openChildTable', $boton_users.closest('tr'), {
                                        title: 'Usuarios del rol "' + rolesData.record.name + '"',
                                        messages: usuarioMensajes,
                                        actions: {
                                            listAction: 'service/roles.php?action=listuserp&rol=' + rolesData.record.id,
                                            createAction: 'service/roles.php?action=createuserp&rol=' + rolesData.record.id,
                                            deleteAction: 'service/roles.php?action=deleteuserp&rol=' + rolesData.record.id
                                        },
                                        fields: {
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            login: {
                                                title: 'Login Username',
                                                create: false,
                                            },
                                            name: {
                                                title: 'Nombre del usuario',
                                                create: false,
                                            },
                                            email: {
                                                title: 'Correo Electrónico del usuario',
                                                create: false,
                                            },
                                            admin_id: {
                                                title: 'Seleccione el usuario',
                                                options: 'service/roles.php?action=listAdminReg',
                                                list: false,
                                                create: true,
                                            }
                                        },
                                        formCreated: function(event, data) {
                                            //$("#Edit-admin_id option[value=0]").attr("selected",true);
                                            $('#Edit-admin_id').attr('id', 'editadmin_id');
                                            $("#editadmin_id").select2();

                                        },
                                        formSubmitting: function(event, data) {
                                            $('#editadmin_id').attr('id', 'Edit-admin_id');
                                        }


                                    }, function(data) { //opened handler
                                        data.childTable.jtable('load');
                                    });

                                });
                                return $boton_users;
                            }
                        },
                        permisos: {
                            title: 'Permisos Asignados',
                            create: false,
                            edit: false,
                            display: function(rolesData) {
                                var $boton_permisos = $('<a class="btn btn-warning control_btn" href="#" style="display: block;"><i class="icon-lock icon-white "></i> Ver Permisos</a>');
                                $boton_permisos.click(function() {
                                    var $childtable = null;
                                    $('#adminRegRol').jtable('openChildTable', $boton_permisos.closest('tr'), {
                                        title: 'Permisos del rol "' + rolesData.record.name + '"',
                                        messages: permisoMensajes,
                                        actions: {
                                            listAction: 'service/roles.php?action=listsitesper&rol=' + rolesData.record.id,
                                            createAction: 'service/roles.php?action=updatesiteper&rol=' + rolesData.record.id
                                        },
                                        fields: {
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            title_es: {
                                                title: 'Sitio',
                                                create: false,
                                                edit: false
                                            },
                                            alias: {
                                                title: 'Alias',
                                                create: false,
                                                edit: false
                                            },
                                            country_id: {
                                                title: 'País',
                                                options: 'service/roles.php?action=listcountries',
                                                create: false,
                                                edit: false
                                            },
                                            site_id: {
                                                title: 'Permisos',
                                                list: false,
                                                create: true,
                                                edit: false,
                                                input: function(data) {
                                                    return jqxTree;
                                                },
                                            }
                                        }, formCreated: function(event, data) {
                                            data.form.validationEngine();

                                            if (data.formType == 'create') {
                                                createTreeForSites(rolesData.record.id);
                                            }

                                        }, formSubmitting: function(event, data) {
                                            if (data.formType == 'create') {
                                                var selected = null;
                                                $.ajax({url: 'service/roles.php?action=listsitesper&rol=' + rolesData.record.id, async: false, dataType: "json", success: function(data) {
                                                        selected = data.Records;
                                                    }});
                                                var expander = $('#jqxExpander');
                                                var arbol = $('#jqxTree');
                                                var selItems = arbol.jqxTree('getCheckedItems');
                                                var tieneItems = false;
                                                if (selItems.length > 0) {
                                                    tieneItems = true;
                                                    var identificadores = '';
                                                    for (var i = 0; i < selItems.length; i++) {
                                                        if (i == 0) {
                                                            identificadores = selItems[i].id.replace('site', '');
                                                        } else {
                                                            identificadores += ',' + selItems[i].id.replace('site', '');
                                                        }
                                                    }
                                                }
                                                $('#Edit-idents').val(identificadores);
                                                //los que se van a eliminar
                                                var identificadoresElim = '';
                                                var items = arbol.jqxTree('getItems');
                                                $.each(items, function() {
                                                    var item = arbol.jqxTree('getItem', this);
                                                    if (item.id.indexOf('site') === 0) {
                                                        if (identificadoresElim === '') {
                                                            identificadoresElim += item.id.replace('site', '');
                                                        } else {
                                                            identificadoresElim += ',' + item.id.replace('site', '')
                                                        }
                                                        for (var ii = 0; ii < selected.length; ii++) {
                                                            var selectedItem = selected[ii];
                                                            if (selectedItem.id == item.id.replace('site', '')) {
                                                                selected.splice(ii, 1);
                                                            }
                                                        }
                                                    }
                                                });
                                                if (selected.length + selItems.length > 0) {
                                                    tieneItems = true;
                                                }
                                                $('#Edit-elim').val(identificadoresElim);
                                                if (!tieneItems) {
                                                    showError('Debe seleccionar al menos un sitio');
                                                }
                                                return tieneItems;
                                            } else {
                                                return false;
                                            }
                                        }, formClosed: function(event, data) {
                                            $childtable.jtable('reload');
                                        }

                                    }, function(data) {
                                        $childtable = data.childTable;
                                        data.childTable.jtable('load');
                                    });
                                });
                                return $boton_permisos;
                            }
                        },
                        permiso: {
                            title: 'Asignar Permisos',
                            input: function(data) {
                                return jqxTree;
                            },
                            list: false,
                            edit: false,
                            create: true
                        },
                        acciones: {
                            title: 'Acciones',
                            create: false,
                            edit: false,
                            display: function(rolesData) {
                                var $boton_clonar = $('<a class="btn btn-info control_btn" onclick="cloneDialog(' + rolesData.record.id + ')" style="display: block;"><i class="icon-tasks icon-white "></i> Clonar Rol</a>');

                                return $boton_clonar;
                            }

                        }
                    }, formCreated: function(event, data) {
                        data.form.validationEngine();
                        if (data.formType == 'create') {
                            createTreeForSites();
                        }

                    },
                    formSubmitting: function(event, data) {
                        var validado = data.form.validationEngine('validate');
                        if (data.formType == 'create') {
                            var expander = $('#jqxExpander');
                            var arbol = $('#jqxTree');
                            var items = arbol.jqxTree('getCheckedItems');
                            var tieneItems = false;
                            if (items.length > 0) {
                                tieneItems = true;
                                var identificadores = '';
                                for (var i = 0; i < items.length; i++) {
                                    if (i == 0) {
                                        identificadores = items[i].id.replace('site', '');
                                    } else {
                                        identificadores += ',' + items[i].id.replace('site', '');
                                    }
                                }
                            }
                            $('#Edit-idents').val(identificadores);

                            if (!tieneItems) {
                                showError('Debe seleccionar al menos un sitio');
                            }
                            return validado && tieneItems;
                        } else {
                            return validado;
                        }
                    },
                    formClosed: function(event, data) {
                        data.form.validationEngine('hide');
                        data.form.validationEngine('detach');
                    }
                });
                $('#adminRegRol').jtable('load');

                $('#adminGestRol').jtable({
                    title: 'Roles de Gestor',
                    messages: rolMessages,
                    actions: {
                        listAction: 'service/roles.php?action=listrolgestor',
                        createAction: 'service/roles.php?action=createrolgestor',
                        updateAction: 'service/roles.php?action=updaterolgestor',
                        deleteAction: 'service/roles.php?action=deleterole&table=1'
                    },
                    fields: {
                        id: {
                            key: true,
                            create: false,
                            edit: false,
                            list: false
                        },
                        name: {
                            title: 'Nombre del Rol',
                            inputClass: 'validate[required]'
                        },
                        description: {
                            title: 'Descripción',
                            type: 'textarea',
                            inputClass: 'validate[required]'
                        },
                        usuario: {
                            title: 'Usuarios Asignados',
                            create: false,
                            edit: false,
                            display: function(rolesData) {
                                var $boton_users = $('<a class="btn btn-primary control_btn" href="#" style="display: block;"><i class="icon-user icon-white "></i> Ver Usuarios</a>');
                                $boton_users.click(function() {
                                    $('#adminGestRol').jtable('openChildTable', $boton_users.closest('tr'), {
                                        title: 'Usuarios del rol "' + rolesData.record.name + '"',
                                        messages: usuarioMensajes,
                                        actions: {
                                            listAction: 'service/roles.php?action=listuserp&rol=' + rolesData.record.id,
                                            createAction: 'service/roles.php?action=createuserp&rol=' + rolesData.record.id,
                                            deleteAction: 'service/roles.php?action=deleteuserp&rol=' + rolesData.record.id
                                        },
                                        fields: {
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            login: {
                                                title: 'Login Username',
                                                create: false,
                                            },
                                            name: {
                                                title: 'Nombre del usuario',
                                                create: false,
                                            },
                                            email: {
                                                title: 'Correo Electrónico del usuario',
                                                create: false,
                                            },
                                            admin_id: {
                                                title: 'Seleccione el usuario',
                                                options: 'service/roles.php?action=listAdminManager',
                                                list: false,
                                                create: true,
                                            }
                                        },
                                        formCreated: function(event, data) {
                                            //$("#Edit-admin_id option[value=0]").attr("selected",true);
                                            $('#Edit-admin_id').attr('id', 'editadmin_id');
                                            $("#editadmin_id").select2();

                                        },
                                        formSubmitting: function(event, data) {
                                            $('#editadmin_id').attr('id', 'Edit-admin_id');
                                        }


                                    }, function(data) { //opened handler
                                        data.childTable.jtable('load');
                                    });

                                });
                                return $boton_users;
                            }
                        },
                        permisos: {
                            title: 'Permisos Asignados',
                            create: false,
                            edit: false,
                            display: function(rolesData) {
                                var $boton_permisos = $('<a class="btn btn-warning control_btn" href="#" style="display: block;"><i class="icon-lock icon-white "></i> Ver Permisos</a>');
                                $boton_permisos.click(function() {
                                    var $childtable = null;
                                    $('#adminGestRol').jtable('openChildTable', $boton_permisos.closest('tr'), {
                                        title: 'Permisos del rol "' + rolesData.record.name + '"',
                                        messages: permisoMensajes,
                                        actions: {
                                            listAction: 'service/roles.php?action=listpagesper&rol=' + rolesData.record.id,
                                            createAction: 'service/roles.php?action=updatepageper&rol=' + rolesData.record.id
                                        },
                                        fields: {
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            page_title: {
                                                title: 'Página',
                                                create: false,
                                                edit: false
                                            },
                                            site_title: {
                                                title: 'Sitio',
                                                create: false,
                                                edit: false
                                            },
                                            country_id: {
                                                title: 'País',
                                                options: 'service/roles.php?action=listcountries',
                                                create: false,
                                                edit: false
                                            },
                                            page_id: {
                                                title: 'Permisos',
                                                list: false,
                                                create: true,
                                                edit: false,
                                                input: function(data) {
                                                    return jqxTree;
                                                },
                                            }
                                        }, formCreated: function(event, data) {
                                            data.form.validationEngine();

                                            if (data.formType == 'create') {
                                                createTreeForPages(rolesData.record.id);
                                            }

                                        }, formSubmitting: function(event, data) {
                                            if (data.formType == 'create') {
                                                var selected = null;
                                                $.ajax({url: 'service/roles.php?action=listpagesper&rol=' + rolesData.record.id, async: false, dataType: "json", success: function(data) {
                                                        selected = data.Records;
                                                    }});
                                                var expander = $('#jqxExpander');
                                                var arbol = $('#jqxTree');
                                                var selItems = arbol.jqxTree('getCheckedItems');
                                                var tieneItems = false;
                                                if (selItems.length > 0) {
                                                    tieneItems = true;
                                                    var identificadores = '';
                                                    for (var i = 0; i < selItems.length; i++) {
                                                        if (i == 0) {
                                                            identificadores = selItems[i].id.replace('page', '');
                                                        } else {
                                                            identificadores += ',' + selItems[i].id.replace('page', '');
                                                        }
                                                    }
                                                }
                                                $('#Edit-idents').val(identificadores);
                                                //los que se van a eliminar
                                                var identificadoresElim = '';
                                                var items = arbol.jqxTree('getItems');
                                                $.each(items, function() {
                                                    var item = arbol.jqxTree('getItem', this);
                                                    if (item.id.indexOf('page') === 0) {
                                                        if (identificadoresElim === '') {
                                                            identificadoresElim += item.id.replace('page', '');
                                                        } else {
                                                            identificadoresElim += ',' + item.id.replace('page', '')
                                                        }
                                                        for (var ii = 0; ii < selected.length; ii++) {
                                                            var selectedItem = selected[ii];
                                                            if (selectedItem.id == item.id.replace('page', '')) {
                                                                selected.splice(ii, 1);
                                                            }
                                                        }
                                                    }
                                                });
                                                if (selected.length + selItems.length > 0) {
                                                    tieneItems = true;
                                                }
                                                $('#Edit-elim').val(identificadoresElim);
                                                if (!tieneItems) {
                                                    showError('Debe seleccionar al menos una página');
                                                }
                                                return tieneItems;
                                            } else {
                                                return false;
                                            }
                                        }, formClosed: function(event, data) {
                                            $childtable.jtable('reload');
                                        }

                                    }, function(data) {
                                        $childtable = data.childTable;
                                        data.childTable.jtable('load');
                                    });
                                });
                                return $boton_permisos;
                            }
                        },
                        permiso: {
                            title: 'Asignar Permisos',
                            input: function(data) {
                                return jqxTree;
                            },
                            list: false,
                            edit: false,
                            create: true
                        },
                        acciones: {
                            title: 'Acciones',
                            create: false,
                            edit: false,
                            display: function(rolesData) {
                                var $boton_clonar = $('<a class="btn btn-info control_btn" onclick="cloneDialog(' + rolesData.record.id + ')" style="display: block;"><i class="icon-tasks icon-white "></i> Clonar Rol</a>');

                                return $boton_clonar;
                            }
                        }
                    }, formCreated: function(event, data) {
                        data.form.validationEngine();
                        if (data.formType == 'create') {
                            createTreeForPages();
                        }

                    },
                    formSubmitting: function(event, data) {
                        var validado = data.form.validationEngine('validate');
                        if (data.formType == 'create') {
                            var expander = $('#jqxExpander');
                            var arbol = $('#jqxTree');
                            var items = arbol.jqxTree('getCheckedItems');
                            var tieneItems = false;
                            if (items.length > 0) {
                                tieneItems = true;
                                var identificadores = '';
                                for (var i = 0; i < items.length; i++) {
                                    if (i == 0) {
                                        identificadores = items[i].id.replace('page', '');
                                    } else {
                                        identificadores += ',' + items[i].id.replace('page', '');
                                    }
                                }
                            }
                            $('#Edit-idents').val(identificadores);

                            if (!tieneItems) {
                                showError('Debe seleccionar al menos una página');
                            }
                            return validado && tieneItems;
                        } else {
                            return validado;
                        }
                    }, formClosed: function(event, data) {
                        data.form.validationEngine('hide');
                        data.form.validationEngine('detach');
                    }
                });
                $('#adminGestRol').jtable('load');
                $('#adminEditRol').jtable({
                    title: 'Roles de Editor',
                    messages: rolMessages,
                    actions: {
                        listAction: 'service/roles.php?action=listroleditor',
                        createAction: 'service/roles.php?action=createroleditor',
                        updateAction: 'service/roles.php?action=updateroleditor',
                        deleteAction: 'service/roles.php?action=deleterole&table=2'
                    },
                    fields: {
                        id: {
                            key: true,
                            create: false,
                            edit: false,
                            list: false
                        },
                        name: {
                            title: 'Nombre del Rol',
                            inputClass: 'validate[required]'
                        },
                        description: {
                            title: 'Descripción',
                            type: 'textarea',
                            inputClass: 'validate[required]'
                        },
                        usuario: {
                            title: 'Usuarios Asignados',
                            create: false,
                            edit: false,
                            display: function(rolesData) {
                                var $boton_users = $('<a class="btn btn-primary control_btn" href="#" style="display: block;"><i class="icon-user icon-white "></i> Ver Usuarios</a>');
                                $boton_users.click(function() {
                                    $('#adminGestRol').jtable('openChildTable', $boton_users.closest('tr'), {
                                        title: 'Usuarios del rol "' + rolesData.record.name + '"',
                                        messages: usuarioMensajes,
                                        actions: {
                                            listAction: 'service/roles.php?action=listuserp&rol=' + rolesData.record.id,
                                            createAction: 'service/roles.php?action=createuserp&rol=' + rolesData.record.id,
                                            deleteAction: 'service/roles.php?action=deleteuserp&rol=' + rolesData.record.id
                                        },
                                        fields: {
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            login: {
                                                title: 'Login Username',
                                                create: false,
                                            },
                                            name: {
                                                title: 'Nombre del usuario',
                                                create: false,
                                            },
                                            email: {
                                                title: 'Correo Electrónico del usuario',
                                                create: false,
                                            },
                                            admin_id: {
                                                title: 'Seleccione el usuario',
                                                options: 'service/roles.php?action=listAdminEditor',
                                                list: false,
                                                create: true,
                                            }
                                        },
                                        formCreated: function(event, data) {
                                            //$("#Edit-admin_id option[value=0]").attr("selected",true);
                                            $('#Edit-admin_id').attr('id', 'editadmin_id');
                                            $("#editadmin_id").select2();

                                        },
                                        formSubmitting: function(event, data) {
                                            $('#editadmin_id').attr('id', 'Edit-admin_id');
                                        }


                                    }, function(data) { //opened handler
                                        data.childTable.jtable('load');
                                    });

                                });
                                return $boton_users;
                            }
                        },
                        permisos: {
                            title: 'Permisos Asignados',
                            create: false,
                            edit: false,
                            display: function(rolesData) {
                                var $boton_permisos = $('<a class="btn btn-warning control_btn" href="#" style="display: block;"><i class="icon-lock icon-white "></i> Ver Permisos</a>');
                                $boton_permisos.click(function() {
                                    var $childtable = null;
                                    $('#adminEditRol').jtable('openChildTable', $boton_permisos.closest('tr'), {
                                        title: 'Permisos del rol "' + rolesData.record.name + '"',
                                        messages: permisoMensajes,
                                        actions: {
                                            listAction: 'service/roles.php?action=listcontentsper&rol=' + rolesData.record.id,
                                            createAction: 'service/roles.php?action=updatecontentper&rol=' + rolesData.record.id
                                        },
                                        fields: {
                                            id: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            content_name: {
                                                title: 'Contenido',
                                                create: false,
                                                edit: false
                                            },
                                            content_type: {
                                                title: 'Tipo',
                                                create: false,
                                                edit: false
                                            },
                                            page_name: {
                                                title: 'Página',
                                                create: false,
                                                edit: false
                                            },
                                            site_name: {
                                                title: 'Sitio',
                                                create: false,
                                                edit: false
                                            },
                                            site_country: {
                                                title: 'País',
                                                options: 'service/roles.php?action=listcountries',
                                                create: false,
                                                edit: false
                                            },
                                            content_id: {
                                                title: 'Permisos',
                                                list: false,
                                                create: true,
                                                edit: false,
                                                input: function(data) {
                                                    return jqxTree;
                                                },
                                            }
                                        }, formCreated: function(event, data) {
                                            data.form.validationEngine();

                                            if (data.formType == 'create') {
                                                createTreeForContent(rolesData.record.id);
                                            }

                                        }, formSubmitting: function(event, data) {
                                            if (data.formType == 'create') {
                                                var selected = null;
                                                $.ajax({url: 'service/roles.php?action=listcontentsper&rol=' + rolesData.record.id, async: false, dataType: "json", success: function(data) {
                                                        selected = data.Records;
                                                    }});
                                                var expander = $('#jqxExpander');
                                                var arbol = $('#jqxTree');
                                                var selItems = arbol.jqxTree('getCheckedItems');
                                                var tieneItems = false;
                                                if (selItems.length > 0) {
                                                    tieneItems = true;
                                                    var identificadores = '';
                                                    for (var i = 0; i < selItems.length; i++) {
                                                        if (i == 0) {
                                                            identificadores = selItems[i].id.replace('content', '');
                                                        } else {
                                                            identificadores += ',' + selItems[i].id.replace('content', '');
                                                        }
                                                    }
                                                }
                                                $('#Edit-idents').val(identificadores);
                                                //los que se van a eliminar
                                                var identificadoresElim = '';
                                                var items = arbol.jqxTree('getItems');
                                                $.each(items, function() {
                                                    var item = arbol.jqxTree('getItem', this);
                                                    if (item.id.indexOf('content') === 0) {
                                                        if (identificadoresElim === '') {
                                                            identificadoresElim += item.id.replace('content', '');
                                                        } else {
                                                            identificadoresElim += ',' + item.id.replace('content', '')
                                                        }
                                                        for (var ii = 0; ii < selected.length; ii++) {
                                                            var selectedItem = selected[ii];
                                                            if (selectedItem.id == item.id.replace('content', '')) {
                                                                selected.splice(ii, 1);
                                                            }
                                                        }
                                                    }
                                                });
                                                if (selected.length + selItems.length > 0) {
                                                    tieneItems = true;
                                                }
                                                $('#Edit-elim').val(identificadoresElim);
                                                if (!tieneItems) {
                                                    showError('Debe seleccionar al menos un contenido');
                                                }
                                                return tieneItems;
                                            } else {
                                                return false;
                                            }
                                        }, formClosed: function(event, data) {
                                            $childtable.jtable('reload');
                                        }

                                    }, function(data) {
                                        $childtable = data.childTable;
                                        data.childTable.jtable('load');
                                    });
                                });
                                return $boton_permisos;
                            }
                        },
                        permiso: {
                            title: 'Asignar Permisos',
                            input: function(data) {
                                return jqxTree;
                            },
                            list: false,
                            edit: false,
                            create: true
                        },
                        acciones: {
                            title: 'Acciones',
                            create: false,
                            edit: false,
                            display: function(rolesData) {
                                var $boton_clonar = $('<a class="btn btn-info control_btn" onclick="cloneDialog(' + rolesData.record.id + ')" style="display: block;"><i class="icon-tasks icon-white "></i> Clonar Rol</a>');

                                return $boton_clonar;
                            }
                        }
                    }, formCreated: function(event, data) {
                        data.form.validationEngine();
                        if (data.formType == 'create') {
                            createTreeForContent();
                        }

                    },
                    formSubmitting: function(event, data) {
                        var validado = data.form.validationEngine('validate');
                        if (data.formType == 'create') {
                            var expander = $('#jqxExpander');
                            var arbol = $('#jqxTree');
                            var items = arbol.jqxTree('getCheckedItems');
                            var tieneItems = false;
                            if (items.length > 0) {
                                tieneItems = true;
                                var identificadores = '';
                                for (var i = 0; i < items.length; i++) {
                                    if (i == 0) {
                                        identificadores = items[i].id.replace('content', '');
                                    } else {
                                        identificadores += ',' + items[i].id.replace('content', '');
                                    }
                                }
                            }
                            $('#Edit-idents').val(identificadores);
                            if (!tieneItems) {
                                showError('Debe seleccionar al menos un contenido');
                            }
                            return validado && tieneItems;
                        } else {
                            return validado;
                        }
                    }, formClosed: function(event, data) {
                        data.form.validationEngine('hide');
                        data.form.validationEngine('detach');
                    }
                });
                $('#adminEditRol').jtable('reload');
                $('#adminGroups').jtable({
                    title: 'Grupos de usuarios',
                    messages: grupoMensajes,
                    actions: {
                        listAction: 'service/groups.php?action=list',
                        createAction: 'service/groups.php?action=create',
                        updateAction: 'service/groups.php?action=update',
                        deleteAction: 'service/groups.php?action=delete'
                    },
                    fields: {
                        id: {
                            key: true,
                            create: false,
                            edit: false,
                            list: false
                        },
                        name: {
                            title: 'Nombre del grupo',
                            width: '80%',
                        },
                        usuarios: {
                            title: 'Usuarios',
                            width: '20%',
                            display: function(gruposData) {
                                var $boton_users = $('<a class="btn btn-primary control_btn" href="#" style="display: block;"><i class="icon-user icon-white "></i> Ver Usuarios</a>');
                                $boton_users.click(function() {
                                    var $childtable = null;
                                    $('#adminGroups').jtable('openChildTable', $boton_users.closest('tr'), {
                                        title: 'Usuarios del grupo "' + gruposData.record.name + '"',
                                        messages: userMessages,
                                        actions: {
                                            listAction: 'service/groups.php?action=listuser&group=' + gruposData.record.id,
                                            createAction: 'service/groups.php?action=adduser&group=' + gruposData.record.id,
                                            deleteAction: 'service/groups.php?action=removeuser&group=' + gruposData.record.id
                                        },
                                        fields: {
                                            id_user: {
                                                key: true,
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            id_community: {
                                                create: false,
                                                edit: false,
                                                list: false
                                            },
                                            first_name: {
                                                title: 'Nombre(s)',
                                                create: false,
                                                edit: false
                                            },
                                            last_name: {
                                                title: 'Apellido(s)',
                                                create: false,
                                                edit: false
                                            },
                                            email: {
                                                title: 'Correo Electrónico',
                                                create: false,
                                                edit: false
                                            },
                                            cif: {
                                                title: 'Código CIF',
                                                create: false,
                                                edit: false
                                            },
                                            usuario: {
                                                title: 'Seleccione Un Usuario',
                                                list: false,
                                                create: true,
                                                edit: false,
                                                type: 'hidden'
                                            }
                                        }, formCreated: function(event, data) {
                                            $('#Edit-usuario').attr('id', 'editusuario');
                                            $("#editusuario").select2({
                                                minimumInputLength: 1,
                                                ajax: {
                                                    url: './service/groups.php',
                                                    dataType: 'json',
                                                    quietMillis: 100,
                                                    data: function(term, page) {
                                                        return {
                                                            query: term,
                                                            action: 'searchUser'
                                                        };
                                                    }, results: function(data, page) {
                                                        var more = (page * 10) < data.total;
                                                        return {results: data.Records, more: more};
                                                    }
                                                }
                                                , escapeMarkup: function(m) {
                                                    return m;
                                                }
                                            });
                                        },formSubmitting: function(event, data) {
                                            $('#editusuario').attr('id', 'Edit-usuario');
                                        }
                                    }, function(data) {
                                        $childtable = data.childTable;
                                        data.childTable.jtable('load');
                                    });
                                });
                                return $boton_users;

                            },
                            create: false,
                            edit: false,
                        }
                    }
                });
                $('#adminGroups').jtable('load');
            });
            function showError(text) {
                $('#error-msg').text(text);
                $("#dialog-error-message").dialog({
                    modal: true,
                    buttons: {
                        Cerrar: function() {
                            $(this).dialog("close");
                        }
                    }
                });
            }
            function cloneDialog(id) {
                $('#id-role').val(id);
                $('#newNameRole').val('');
                $('#newDescRole').val('');
                $("#dialog-clone-message").dialog({
                    modal: true,
                    buttons: {
                        Cancelar: function() {
                            $(this).dialog("close");
                        },
                        Clonar: function() {
                            var params = {};
                            params.newname = $('#newNameRole').val();
                            params.baserole = $('#id-role').val();
                            params.newdescription = $('#newDescRole').val();
                            $.post('service/roles.php?action=clonerole', params, function(data) {
                                if (data.Result != "OK") {
                                    showError('No se pudo clonar el rol');
                                }
                                $('#adminRegRol').jtable('reload');
                                $('#adminGestRol').jtable('reload');
                                $('#adminEditRol').jtable('reload');
                            }, 'json');
                            $(this).dialog("close");
                        }
                    }
                });
            }

            function createTreeForPages(role) {
                createTree(role, 3);
            }
            function createTreeForSites(role) {
                createTree(role, 2);
            }
            function createTreeForContent(role) {
                createTree(role, 4);
            }

            function createTree(role, level) {
                var maxLevel = level;
                var selected = null;
                if (typeof role !== 'undefined') {
                    if (level == 2) {
                        $.ajax({url: 'service/roles.php?action=listsitesper&rol=' + role, async: false, dataType: "json", success: function(data) {
                                selected = data.Records;
                            }});
                    } else if (level == 3) {
                        $.ajax({url: 'service/roles.php?action=listpagesper&rol=' + role, async: false, dataType: "json", success: function(data) {
                                selected = data.Records;
                            }});
                    }
                    else if (level == 4) {
                        $.ajax({url: 'service/roles.php?action=listcontentsper&rol=' + role, async: false, dataType: "json", success: function(data) {
                                selected = data.Records;
                            }});
                    }
                }
                //crear el jqxExpander
                var expander = $('#jqxExpander');
                var arbol = $('#jqxTree');
                try {
                    expander.jqxExpander('destroy');
                    expander.jqxExpander({showArrow: false, toggleMode: 'none', width: '400px', height: '370px'});
                } catch (e) {
                    expander.jqxExpander({showArrow: false, toggleMode: 'none', width: '400px', height: '370px'});
                }
                //obtener los paises
                var parameters = {};
                parameters.action = 'getCountries';
                var source = null;
                $.ajax({url: './service/roles.php', async: false, data: parameters, dataType: "json", success: function(data) {
                        source = data;
                    }});
                //crear el arbol
                try {
                    arbol.jqxTree('destroy');
                    arbol.on('initialized', function(event) {
                        var items = arbol.jqxTree('getItems');
                        $.each(items, function() {
                            var item = arbol.jqxTree('getItem', this);
                            item.nivel = 0;
                            $(item.element).find('.chkbox').next().css('margin-left', 0);
                            $(item.checkBoxElement).css('display', 'none');
                        });
                    });
                    arbol.jqxTree({source: source, width: '100%', height: '100%', checkboxes: true});
                } catch (e) {
                    arbol.on('initialized', function(event) {
                        var items = arbol.jqxTree('getItems');
                        $.each(items, function() {
                            var item = arbol.jqxTree('getItem', this);
                            item.nivel = 0;
                            $(item.element).find('.chkbox').next().css('margin-left', 0);
                            $(item.checkBoxElement).css('display', 'none');
                        });
                    });
                    arbol.jqxTree({source: source, width: '100%', height: '100%', checkboxes: true});
                }
                arbol.on('expand', function(event) {
                    var item = arbol.jqxTree('getItem', event.args.element);
                    switch (parseInt(item.nivel)) {
                        case 0:
                            if (typeof item.cargado === 'undefined') {
                                //cargar hijos
                                var child = $(event.args.element).find('ul:first').children();
                                var loadingChild = arbol.jqxTree('getItem', child[0]);
                                var children = null;
                                var parametros = {};
                                parametros.action = 'getSites';
                                parametros.country_id = item.id.replace('country', '');
                                if ((maxLevel - 1) > 1) {
                                    parametros.has_siblings = "true";
                                } else {
                                    parametros.has_siblings = "false";
                                }

                                $.ajax({url: './service/roles.php', async: false, data: parametros, dataType: "json", success: function(data) {
                                        children = data;

                                    }});

                                arbol.jqxTree('removeItem', loadingChild.element);
                                arbol.jqxTree('addTo', children, $(event.args.element)[0]);
                                var items = arbol.jqxTree('getItems');
                                $.each(items, function() {
                                    var item = arbol.jqxTree('getItem', this);
                                    if (typeof item.nivel === 'undefined') {
                                        item.nivel = 1;
                                        if (typeof role !== 'undefined' && (maxLevel - 1) == 1) {
                                            for (var k = 0; k < selected.length; k++) {
                                                var seleccionado = selected[k];
                                                if (item.id.replace('site', '') === seleccionado.id) {
                                                    arbol.jqxTree('checkItem', item, true);
                                                }
                                            }

                                        }
                                        if ((maxLevel - 1) > 1) {
                                            $(item.element).find('.chkbox').next().css('margin-left', 0);
                                            $(item.checkBoxElement).css('display', 'none');
                                        }
                                    }
                                });

                                item.cargado = true;
                                arbol.jqxTree('render');

                            }
                            break;
                        case 1:
                            if (typeof item.cargado === 'undefined') {
                                //cargar hijos
                                var child = $(event.args.element).find('ul:first').children();
                                var loadingChild = arbol.jqxTree('getItem', child[0]);
                                var children = null;
                                var parametros = {};
                                parametros.action = 'getPages';
                                parametros.site_id = item.id.replace('site', '');
                                if ((maxLevel - 1) > 2) {
                                    parametros.has_siblings = "true";
                                } else {
                                    parametros.has_siblings = "false";
                                }
                                $.ajax({url: './service/roles.php', async: false, data: parametros, dataType: "json", success: function(data) {
                                        children = data;

                                    }});
                                arbol.jqxTree('removeItem', loadingChild.element);
                                arbol.jqxTree('addTo', children, $(event.args.element)[0]);
                                var items = arbol.jqxTree('getItems');
                                $.each(items, function() {
                                    var item = arbol.jqxTree('getItem', this);
                                    if (typeof item.nivel === 'undefined') {
                                        item.nivel = 2;
                                        if (typeof role !== 'undefined' && (maxLevel - 1) == 2) {
                                            for (var k = 0; k < selected.length; k++) {
                                                var seleccionado = selected[k];
                                                if (item.id.replace('page', '') === seleccionado.id) {
                                                    arbol.jqxTree('checkItem', item, true);
                                                }
                                            }

                                        }
                                        if ((maxLevel - 1) > 2) {
                                            $(item.element).find('.chkbox').next().css('margin-left', 0);
                                            $(item.checkBoxElement).css('display', 'none');
                                        }
                                    }
                                });
                                item.cargado = true;
                            }

                            break;
                        case 2:
                            if (typeof item.cargado === 'undefined') {
                                //cargar hijos
                                var child = $(event.args.element).find('ul:first').children();
                                var loadingChild = arbol.jqxTree('getItem', child[0]);
                                var children = null;
                                var parametros = {};
                                parametros.action = 'getContents';
                                parametros.page_id = item.id.replace('page', '');
                                $.ajax({url: './service/roles.php', async: false, data: parametros, dataType: "json", success: function(data) {
                                        children = data;

                                    }});
                                arbol.jqxTree('removeItem', loadingChild.element);
                                arbol.jqxTree('addTo', children, $(event.args.element)[0]);
                                var items = arbol.jqxTree('getItems');
                                $.each(items, function() {
                                    var item = arbol.jqxTree('getItem', this);
                                    if (typeof item.nivel === 'undefined') {
                                        item.nivel = 2;
                                        if (typeof role !== 'undefined' && (maxLevel - 1) == 3) {
                                            for (var k = 0; k < selected.length; k++) {
                                                var seleccionado = selected[k];
                                                if (item.id.replace('content', '') === seleccionado.id) {
                                                    arbol.jqxTree('checkItem', item, true);
                                                }
                                            }

                                        }
                                    }
                                });
                                item.cargado = true;
                            }
                            break;
                    }
                    return;
                });
                arbol.on('select', function(event) {
                    var item = arbol.jqxTree('getItem', event.args.element);
                });
                return;
            }
        </script>
    </body>

</html>