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
					

actions={
	listAction: 	'../../service/country.php?action=list',
	createAction: 	'../../service/country.php?action=create',
	deleteAction: 	'../../service/country.php?action=delete',
	updateAction:   '../../service/country.php?action=update'
		};				
					   
$(function() {
    var pestanamessages = {
                serverCommunicationError: 'Ocurrió un error en la comunicación con el servidor.',
                loadingMessage: 'Cargando paises...',
                noDataAvailable: 'No hay datos disponibles!',
                addNewRecord: 'Crear un nuevo Pais',
                editRecord: 'Editar Pais',
                areYouSure: '¿Está seguro?',
                deleteConfirmation: 'El pais será eliminado. ¿Está seguro?',
                save: 'Guardar',
                saving: 'Guardando',
                cancel: 'Cancelar',
                deleteText: 'Eliminar',
                deleting: 'Eliminando',
                error: 'Error',
                close: 'Cerrar',
                cannotLoadOptionsFor: 'No se pueden cargar las opciones para el campo {0}',
                pagingInfo: 'Mostrando pestañas {0} a {1} de {2}',
                canNotDeletedRecords: 'No se puede borrar pais(ses) {0} de {1}!',
                deleteProggress: 'Eliminando {0} de {1} paises, procesando...',
                pageSizeChangeLabel: 'Paises por página',
                gotoPageLabel: 'Ir a página'
            };
	$('#tabla').jtable(
		{
		title: 'Paises',
		paging: true, //Enable paging
		pageSize: 5, //Set page size (default: 10)
		pageSizes: [5, 10, 15],
		sorting: true, //Enable sorting
		actions:actions,
                messages: pestanamessages,
		fields: {
			id: {
			key: true,
			create: false,
			edit: false,
			list: false
			},
			name:{
			title: 'Nombre',
			create: true
	        },
			code: {
			title: 'Código',
			create: true
	        },
	        area_code: {
			title: 'Código de área',
			create: true
	        },
	        alias:{
			title: 'Alias',
			list:false,
			create: true,
			edit:true
			}
	        ,
			flagcountry: {
			title: 'Imagen',
			create: true,
			display:
				function (data) {
					var img=data.record.flagcountry;
					if(!(img.length>0))
							img='default.jpg'
					return '<center><img class="frame" src="../../../assets/images/countries/' + img +'"/></center>';
				}
	        }
	        
		},
		formSubmitting:function(event,data){
			var resultado=true;
			if($("#Edit-flagcountry").val().trim().length==0)	resultado=false;
			if($("#Edit-name").val().trim().length==0)			resultado=false;
			if(!resultado)alert('El nombre y la imagen son obligatorios');
			return resultado;
		},
		formCreated: function (event, data) {
				$("#Edit-flagcountry").parent().append(htmlUploader);
				$("#Edit-flagcountry").css('display','none');
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
						$("#Edit-flagcountry").attr("value", filename);
						//status.html(xhr.responseText);
					}
				});
		}
		}
	);


	$('#tabla').jtable('load');

});
