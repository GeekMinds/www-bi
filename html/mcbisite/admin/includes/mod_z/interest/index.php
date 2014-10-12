<!DOCTYPE html>
<html>
<head>
	<title>Configurar intereses</title>

	<!-- JTable.org -->

    <!--link rel="stylesheet" type="text/css" href="../../css/kickstart/kickstart.css" media="all" /-->


	<script src="../../../js/jquery-1.7.1.min.js" type="text/javascript"></script>
	<script src="jquery.form.js"></script>
        <script type="text/javascript" src="../../../js/common.js.php"></script>


    <script src="../../../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
    <script src="../../../js/jtable/jquery.jtable.js" type="text/javascript"></script>
    <!-- JTable.org -->



   <link href="../../../css/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
   <link href="../../../js/jtable/themes/lightcolor/gray/jtable.css" rel="stylesheet" type="text/css" />



	<script src="scripts/tag-it.min.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" type="text/css" href="../../../css/jquery-ui.css">
	<link href="css/jquery.tagit.css" rel="stylesheet" type="text/css">

</head>


<style type="text/css">
		body{
			min-width:500px !important ;
			min-height:500px !important ;
			height:710px;
		}
		.frame { 
			height: 104px;
			width: 136px;
			overflow: hidden;
			background-repeat: no-repeat;
			background-position: 50% 50%;
		}
		
		label{
			float:left;	
		}
		form { display: block; margin: 20px auto; background: #eee; border-radius: 10px; padding: 15px }
		.progress { position:relative; width:200px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
		.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
		.percent {
			position:absolute;
			display:inline-block;
			top:3px;
			left:93px;
		}

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
	</style>
<body>
	<center>
		
	<div id="tabla" style="width: 650px;"></div>


	</center>

</body>
<script type="text/javascript">
    var interestmessages = {
                serverCommunicationError: 'Ocurrió un error en la comunicación con el servidor.',
                loadingMessage: 'Cargando intereses...',
                noDataAvailable: 'No hay datos disponibles!',
                addNewRecord: 'Crear nuevo interés',
                editRecord: 'Editar interés',
                areYouSure: '¿Está seguro?',
                deleteConfirmation: 'El interés será eliminado. ¿Está seguro?',
                save: 'Guardar',
                saving: 'Guardando',
                cancel: 'Cancelar',
                deleteText: 'Eliminar',
                deleting: 'Eliminando',
                error: 'Error',
                close: 'Cerrar',
                cannotLoadOptionsFor: 'No se pueden cargar las opciones para el campo {0}',
                pagingInfo: 'Mostrando intereses {0} a {1} de {2}',
                canNotDeletedRecords: 'No se puede borrar interes(es) {0} de {1}!',
                deleteProggress: 'Eliminando {0} de {1} intereses, procesando...',
                pageSizeChangeLabel: 'Intereses por página',
                gotoPageLabel: 'Ir a página'
            };
		var htmlUploader = '<form id="formupload" action="upload.php" method="post" enctype="multipart/form-data">';
			htmlUploader += '    <input id="inpt_image" type="file" name="myfile"><br>';
			htmlUploader += '    <input id="confirmsubmit_upload" type="submit" value="Upload File to Server" style="display:none;">';
			htmlUploader += '    <input id="uri" type="text"  style="display:none;">';
			htmlUploader += '    <div id="msj" ><div>';
			htmlUploader += '</form>';			
			htmlUploader += '<div class="progress" id="progress">';
			htmlUploader += '    <div class="bar" id="bar"></div >';
			htmlUploader += '    <div class="percent" id="percent">0%</div >';
			htmlUploader += '</div>';
			htmlUploader += '<div id="status"></div>';
			htmlUploader += '<div id="mensaje"></div>';

					
					   


	$(function() {
		$('#tabla').jtable(
			{
			title: 'Intereses actuales',
			paging: true, //Enable paging
			pageSize: 5, //Set page size (default: 10)
			pageSizes: [5, 10, 50, 100, 250, 500],
			sorting: true, //Enable sorting,
                        messages: interestmessages,
			actions:{
				listAction: webservice_path_admin+'module_z_interest.php?action=getinterests',
				createAction: webservice_path_admin+'module_z_interest.php?action=addinterest',
				deleteAction: webservice_path_admin+'module_z_interest.php?action=deleteinterest',
				updateAction:   webservice_path_admin+'module_z_interest.php?action=updateinterest'
			},
			fields: {
				id: {
				key: true,
				create: false,
				edit: false,
				list: false
				},
				name_es:{
				title: 'Nombre en español',
				width: '10%',
				create: true
		        },
				name_en:{
				title: 'Nombre en inglés',
				width: '10%',
				create: true
		        },
				interest_type: {
				title: 'Categoría',
				options: { '1': 'Intereses Personales', '2':'Servicios y Beneficios','3': 'Temas de interés' },
				width: '10%',
				create: true
		        },
				img_url: {
				title: 'Archivo',
				width: '10%',
				create: true,
				display: 
					function (data) {
						return '<center><img class="frame" src="' + data.record.img_url +'" /></center>';
					}
		        },
		        tags:{
				title: 'Etiquetas',
				list:false,
				create: true,
				edit:true
				}
			},
			/*messages: {
			    serverCommunicationError: 'Ocurrió un error al conectar con el servidor',
			    loadingMessage: 'Cargando registros...',
			    noDataAvailable: 'No hay intereses cargados.',
			    addNewRecord: 'Agregar interés',
			    editRecord: 'Editar Registro',
			    areYouSure: '¿Está seguro?',
			    deleteConfirmation: 'Este medio será eliminado. ¿Está seguro?',
			    save: 'Guardar',
			    saving: 'Guardando',
			    cancel: 'Cancelar',
			    deleteText: 'Eliminar',
			    deleting: 'Eliminando',
			    error: 'Error',
			    close: 'Cerrar',
			    cannotLoadOptionsFor: 'No se pueden cargar las opciones para el campo {0}',
			    pagingInfo: 'Mostrando {0}-{1} de {2}',
			    pageSizeChangeLabel: 'Conteo de Filas',
			    gotoPageLabel: 'Ir a la página',
			    canNotDeletedRecords: 'Can not deleted {0} of {1} records!',
			    deleteProggress: 'Eliminados {0} de {1} registros, procesando'
			},*/
			formSubmitting:function(event,data){
				/*Validar los resultados*/
				var resultado=true;
				if($("#Edit-img_url").val().trim().length==0)		{resultado=false;}
				if($("#Edit-name_en").val().trim().length==0)		{resultado=false;}
				if($("#Edit-name_es").val().trim().length==0)		{resultado=false;}
				if($("#Edit-interest_type").val().trim().length==0)	{resultado=false;}
				if(!resultado) alert('Todos los campos son requeridos');
				return resultado;
			},
			formCreated: function (event, data) {

					//tagit
					$.post( webservice_path_admin+"module_z_interest.php?action=getalltags", function(data) {
					  	tags= new Array();
				        data.forEach(function(elemento) {
				            tags.push(elemento.tag);
				        });
						$("#Edit-tags").tagit({showAutocompleteOnFocus :true,singleField:true,availableTags: tags
						}
						);
					},'json');

					$("#Edit-img_url").parent().append(htmlUploader);
					$("#Edit-img_url").css('display','none');
					$('#inpt_image').change(function(){
						$('#confirmsubmit_upload').click();
					});  
					
					var bar = $('.bar');
					var percent = $('.percent');
					var status = $('#status'); 
					
					$('#formupload').ajaxForm({
						beforeSend: function() {
							status.empty();
							var percentVal = '0%';
							bar.width(percentVal)
							percent.html(percentVal);
							$("#mensaje").html("<center>Subiendo el archivo...</center>");
						},
						uploadProgress: function(event, position, total, percentComplete) {
							var percentVal = percentComplete + '%';
							bar.width(percentVal)
							percent.html(percentVal);
							//$("#mensaje").html(percentComplete + '%');
						},
						success: function(e) {
							var percentVal = '100%';
							bar.width(percentVal)
							percent.html(percentVal);
							$("#mensaje").html("<center>El archivo fue subido</center>");
						},
						complete: function(xhr) {
							var filename = xhr.responseText;
							$("#Edit-img_url").attr("value", filename);
							//status.html(xhr.responseText);
						}
					});


			}
			}
		);

	
		$('#tabla').jtable('load');

	});

</script>


</html>