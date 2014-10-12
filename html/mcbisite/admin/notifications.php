<?php
require_once("service/models/config.php");
$logued = true;
if(!isUserLoggedIn()) { 
	$logued = false;
	echo '<script type="text/javascript">top.location.href="'.$websiteUrl.'login.php";</script>';
	die();
}
?>

<!DOCTYPE html>
<html>
    <head>
    	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>
    	
    	<!--JTABLES-->
		<!--<link rel="stylesheet" type="text/css" href="css/kickstart/kickstart.css" media="all" />-->
    	<link href="css/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
		<link href="js/jtable/themes/lightcolor/gray/jtable.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap-cerulean.css">  
        <link href='css/opa-icons.css' rel='stylesheet'>
        
        <!-- COLOR PICKER -->
        <link rel="stylesheet" type="text/css" href="css/spectrum.css">
        <script type="text/javascript" src="js/spectrum.js"></script>
        <script type="text/javascript" src="js/i18n/jquery.spectrum-es.js"></script>
        <!-- <script type='text/javascript' src='js/color_picker.js'></script>-->
        
        
		<!--<script src="jTable-PHP-Samples/Codes/scripts/jquery-1.6.4.min.js" type="text/javascript"></script>-->
	    <script src="js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	    <script src="js/jtable/jquery.jtable.js" type="text/javascript"></script>
            <script src="js/jtable/localization/jquery.jtable.es.js" type="text/javascript"></script>
        
        <style>
        	body{
				min-height:800px;
			}
			.ui-widget-overlay{
				background:none;
				background-color:#FFFFFF;
			}
			
			#content{
				max-width: 920px;
				margin: auto;
			}
                        #content_format{
				max-width: 920px;
				margin: auto;
			}
			
        	form.jtable-dialog-form div.jtable-input-field-container{
				float:none !important;
				width:auto !important;	
			}
			
			.approval_detail{
				cursor:pointer;
			}
        </style>
    </head>
    <body>
    	<?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL,GESTOR))){?>
        <div id='content'>
        	
            <a id="btn_approve" href="javascript:void(0);" class="btn btn-success" data-rel="popover" data-content="" data-original-title=""><i class="icon-ok icon-white"></i> Aprobar</a>
            <a id="btn_disapprove_notdiscard" href="javascript:void(0);" class="btn btn-warning" data-rel="popover" data-content="" data-original-title=""><i class="icon-remove icon-white"></i> No aprobar y mantener </a>
            <a id="btn_disapprove_discard" href="javascript:void(0);" class="btn btn-danger" data-rel="popover" data-content="" data-original-title=""><i class="icon-trash icon-white"></i> No aprobar y descartar </a>
            
            <br/>
            <br/>
        </div>
        <?php }?>
        <br/>
        <br/>
        <?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
        <div id='content_format'>
        	
            <a id="btn_approve_forma" href="javascript:void(0);" class="btn btn-success" data-rel="popover" data-content="" data-original-title=""><i class="icon-ok icon-white"></i> Aprobar</a>
            <a id="btn_disapprove_notdiscard_forma" href="javascript:void(0);" class="btn btn-warning" data-rel="popover" data-content="" data-original-title=""><i class="icon-remove icon-white"></i> No aprobar y mantener </a>
            <a id="btn_disapprove_discard_forma" href="javascript:void(0);" class="btn btn-danger" data-rel="popover" data-content="" data-original-title=""><i class="icon-trash icon-white"></i> No aprobar y descartar </a>
            
            <br/>
            <br/>
        </div>
        <?php }?>
        <script>
            $(function() {
            	<?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL,GESTOR))){?>
                $('#content').jtable({
                    title: 'Notificaciones',
                    sorting: true,
                    defaultSorting: 'id DESC',
					selecting: true, //Enable selecting
					multiselect: true, //Allow multiple selecting
					selectingCheckboxes: true, //Show checkboxes on first column
                    actions: {
                        listAction: 'service/approval.php?action=list',
                       /* deleteAction: 'service/approval.php?action=delete',*/
                        createAction: false
                    },
                    fields: {
                        id: {
                            key: true,
                            create: false,
                            edit: false,
                            list: false
                        },
						detail: {
								title: 'Detalle',
								width: '5%',
								sorting: false,
								edit: false,
								create: false,
								display: function (approvalData) {
									var $img = $('<span  title=".icon32  .icon-darkgray  .icon-document " class="icon32 icon-darkgray icon-document approval_detail"></span>');
									$img.click(function (event) {
										//event.preventdefault();
										event.stopPropagation();
										$('#content').jtable('openChildTable',
												$img.closest('tr'),
												{
													title: approvalData.record.message + ' - Detalle',
													actions: {
														listAction: 'service/approval.php?action=list_detail&approval_id=' + approvalData.record.id,
														createAction: false
													},
													fields: {
														approval_id: {
															type: 'hidden',
															defaultValue: approvalData.record.id
														},
														detail_id: {
															key: true,
															create: false,
															edit: false,
															list: false
														},
														detail_description: {
															title: 'Descripción',
															type: 'textarea'
														},
														editor_id: {
															title: 'Editor',
															options:'service/user.php?action=list'
														},
														edit_date: {
															title: 'Fecha de cambio',
															create: false,
															edit: false
														}
													}
												}, function (data) { //opened handler
													data.childTable.jtable('load');
												});
												
									});
											
									return $img;
								}
						},
						message: {
							title: 'Actualización',
							type: 'textarea'
						},
						content_id: {
							title: 'Contenido',
							edit: false,
							list:false
						},
						module_type: {
							title: 'Tipo de módulo',
							edit: false,
							list:false
						},
						page_id: {
							title: 'Página',
							display: function (data) {
								var info = data.record;
								var page_id = info.page_id;
								return '<div style="text-align: center;"><div class="btn btn-success" onClick="getSiteModeEdition('+page_id+');"><i class="icon-zoom-in icon-white"></i> Ver página</div></div>';
							},
							edit: false
						},
						status: {
							title: 'Status',
							display: function (data) {
								var info = data.record;
								var page_id = info.page_id;
								var html = '';
								switch(info.status){
									case '0':
										html = '<span class="label label-important">Pendiente</span>';
									break;
									case '1':
										html = '<span class="label label-success">Aprobado</span>';
									break;
									case '2':
										html = '<span class="label label-warning">Faltan cambios</span>';
									break;
									case '3':
										html = '<span class="label label-important">No aprobado</span>';
									break;
								}
								return html;
							},
							edit: false
						},
						approved_by: {
							title: 'Aprobado por',
							options:'service/user.php?action=list',
							edit: false,
							list: false
						},
						approval_date: {
							title: 'Fecha de aprobación',
							/*type: 'date',
							displayFormat: 'dd-mm-yy',*/
							edit: false,
							list: false
						},
						approved: {
							title: 'Aprobado',
							type: 'checkbox',
							values: { '0': 'No aprobado', '1': 'Aprobado' },
							defaultValue: '0',
							list: false
						}
					}
				}); 
<?php }?>

<?php if(hasPermissionsSite(array(SUPER_ADMINISTRADOR,ADMINISTRADOR_REGIONAL))){?>
                    $('#content_format').jtable({
                    title: 'Notificaciones para cambios Generales',
                    sorting: true,
                    defaultSorting: 'id DESC',
					selecting: true, //Enable selecting
					multiselect: true, //Allow multiple selecting
					selectingCheckboxes: true, //Show checkboxes on first column
                    actions: {
                        listAction: 'service/approval.php?action=list_forma',
                       /* deleteAction: 'service/approval.php?action=delete',*/
                        createAction: false
                    },
                    fields: {
                        id: {
                            key: true,
                            create: false,
                            edit: false,
                            list: false
                        },
						detail: {
								title: 'Detalle',
								width: '5%',
								sorting: false,
								edit: false,
								create: false,
								display: function (approvalData) {
									var $img = $('<span  title=".icon32  .icon-darkgray  .icon-document " class="icon32 icon-darkgray icon-document approval_detail"></span>');
									$img.click(function (event) {
										//event.preventdefault();
										event.stopPropagation();
										$('#content').jtable('openChildTable',
												$img.closest('tr'),
												{
													title: approvalData.record.message + ' - Detalle',
													actions: {
														listAction: 'service/approval.php?action=list_detail&approval_id=' + approvalData.record.id,
														createAction: false
													},
													fields: {
														approval_id: {
															type: 'hidden',
															defaultValue: approvalData.record.id
														},
														detail_id: {
															key: true,
															create: false,
															edit: false,
															list: false
														},
														detail_description: {
															title: 'Descripción',
															type: 'textarea'
														},
														editor_id: {
															title: 'Editor',
															options:'service/user.php?action=list'
														},
														edit_date: {
															title: 'Fecha de cambio',
															create: false,
															edit: false
														}
													}
												}, function (data) { //opened handler
													data.childTable.jtable('load');
												});
												
									});
											
									return $img;
								}
						},
						content_id: {
							title: 'Contenido',
							edit: false,
							list:false
						},
                                                module_type: {
							title: 'Actualizacion',
                            options: { '1': 'Se realizaron cambios en un modulo <b>pestañas</b>', '2': 'Se realizaron cambios en un modulo <b>secciones</b>','3':'Se realizacon cambios en el modulo de <b>intereses</b>','4':'Se realizo cambio en un <b>sitio </b>','5':'Se realizaron cambios en el modulo <b>footer</b>','6':'Se realizaron cambios en la <b>Configuraci&oacute;n de una P&aacute;gina</b>','7':'Se lrealizaron cambios en <b>M&oacute;dulo de Paises</b>','8':'Se ha realizado un cambio en <b>Modulo de Redes Sociales</b>' },
							edit: false,
							list:true
						},
                            id_change: {
							title: 'Contenido',
							edit: false,
							list:false
						},
						page_id: {
							title: 'Página',
							display: function (data) {
								var info = data.record;
								var mod_type= data.record.module_type;
								var id_change= data.record.id_change;
								//var page_id = info.page_id;
								return '<div style="text-align: center;"><div class="btn btn-success" onClick="gotoIndex('+mod_type+','+id_change+');"><i class="icon-zoom-in icon-white"></i> Ver página</div></div>';
							},
							edit: false
						},
						status: {
							title: 'Status',
							display: function (data) {
								var info = data.record;
								var page_id = info.page_id;
								var html = '';
								switch(info.status){
									case '0':
										html = '<span class="label label-important">Pendiente</span>';
									break;
									case '1':
										html = '<span class="label label-success">Aprobado</span>';
									break;
									case '2':
										html = '<span class="label label-warning">Faltan cambios</span>';
									break;
									case '3':
										html = '<span class="label label-important">No aprobado</span>';
									break;
								}
								return html;
							},
							edit: false
						},
						approved_by: {
							title: 'Aprobado por',
							options:'service/user.php?action=list',
							edit: false,
							list: false
						},
						approval_date: {
							title: 'Fecha de aprobación',
							/*type: 'date',
							displayFormat: 'dd-mm-yy',*/
							edit: false,
							list: false
						},
						approved: {
							title: 'Aprobado',
							type: 'checkbox',
							values: { '0': 'No aprobado', '1': 'Aprobado' },
							defaultValue: '0',
							list: false
						}
					}
				}); 
<?php }?>
				$('#content').jtable('load');
				
				$('#content_format').jtable('load');
				$('#btn_approve').click(function () {
					var $selectedRows = $('#content').jtable('selectedRows');
					//$('#content').jtable('deleteRows', $selectedRows);
					callAprovationAction($selectedRows, "approve");
				});
				$('#btn_approve_forma').click(function () {
					var $selectedRows = $('#content_format').jtable('selectedRows');
					//$('#content').jtable('deleteRows', $selectedRows);
					callAprovationActionForma($selectedRows, "approve_forma");
				});
				$('#btn_disapprove_notdiscard').click(function () {
					var $selectedRows = $('#content').jtable('selectedRows');
					//$('#content').jtable('deleteRows', $selectedRows);
					callAprovationAction($selectedRows, "notdiscard");
				});
                                $('#btn_disapprove_notdiscard_forma').click(function () {
					var $selectedRows = $('#content_format').jtable('selectedRows');
					//$('#content').jtable('deleteRows', $selectedRows);
					callAprovationActionForma($selectedRows, "notdiscard_forma");
				});
				$('#btn_disapprove_discard').click(function () {
					var $selectedRows = $('#content').jtable('selectedRows');
					//$('#content').jtable('deleteRows', $selectedRows);
					callAprovationAction($selectedRows, "discard");
				});
                                $('#btn_disapprove_discard_forma').click(function () {
					var $selectedRows = $('#content_format').jtable('selectedRows');
					//$('#content').jtable('deleteRows', $selectedRows);
					callAprovationActionForma($selectedRows, "discard_forma");
				});
				
				
				
            });
			
			//
			function callAprovationAction(rows, action){
				var params = {};
				var rowsData = new Array();			
				params.action = action;
				
                if (rows.length > 0) {
                    //Show selected rows
                    rows.each(function () {
                        var record = $(this).data('record');
                       /* record.id,
					      record.content_id
                        */
						rowsData.push(record);
                    });
					params.rows = rowsData;
					$.post('./service/approval.php', params, function(data) {
						$('#content').jtable('showError',data.Message);
						if(data.Result == "OK"){
							$('#content').jtable('deleteRowsInClientOnly', rows);
						}
					}, "json");
                } else {
                    //No rows selected
                   $('#content').jtable('showError','No se ha seleccionado ninguna aprobación.');
                }
			} 
			function callAprovationActionForma(rows, action){
				var params = {};
				var rowsData = new Array();			
				params.action = action;
				
                if (rows.length > 0) {
                    //Show selected rows
                    rows.each(function () {
                        var record = $(this).data('record');
                       /* record.id,
					      record.content_id
                        */
						rowsData.push(record);
                    });
					params.rows = rowsData;
					$.post('./service/approval.php', params, function(data) {
						$('#content_format').jtable('showError',data.Message);
						if(data.Result == "OK"){
							$('#content_format').jtable('deleteRowsInClientOnly', rows);
						}
					}, "json");
                } else {
                    //No rows selected
                   $('#content_format').jtable('showError','No se ha seleccionado ninguna aprobación.');
                }
			}
			
			
			function getSiteModeEdition(page_id){
				var params = {};
				params.action = "sitemode";
				params.mode = "edition";
				
				var page_href = '../bisite/?page='+page_id;
				
				$.post('./service/approval.php', params, function(data) {
					if(data.Result == "OK"){
						
					}
					window.open(page_href,'_blank');
				}, "json");	
			}
            

            function gotoIndex( tipo,id){
				var params = {};
				params.action = "sitemode";
				params.mode = "edition";
				var page_href='';

				var params_site={};
				params_site.action ='info_site';
				


switch (tipo) {
  
				  case 4:
				  params_site.id=id;

						$.post('./service/site.php', params_site, function(data) {

							page_href = '../bisite/'+data.Records.pref+'/'+data.Records.alias+'';

							console.log(page_href);
						}, "json");	
						
				  break;

				  default:
				  console.log('salio');
				  page_href = '../bisite/';
				 
				}
				
				$.post('./service/approval.php', params, function(data) {
					if(data.Result == "OK"){
						
					}
					window.open(page_href,'_blank');
				}, "json");	
			}
                        
        </script>
    </body>
</html>