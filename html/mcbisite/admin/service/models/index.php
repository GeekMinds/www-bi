<html>
    <?php
    $content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';
    $module_id = isset($_REQUEST['module_id']) ? $_REQUEST['module_id'] : '-1';


    if ($module_id == '') {
        
    } else {

    }

    ?>

    <head>

        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="../youtube/css/bootstrap.min.css">
        <script src="../../js/jquery.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../js/jtable/themes/lightcolor/gray/jtable.css">
        <link href="../../css/jquery.tagit.css" rel="stylesheet" type="text/css">
        <link href="../../css/bootstrap-responsive.css" rel="stylesheet">

        <link rel="stylesheet" href="../../js/jtable/themes/lightcolor/gray/jtable.css">
        <link rel="stylesheet" href="../../css/jquery-ui.css">
         <link href="../../css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
        

        <style type="text/css">
		
		   form.jtable-dialog-form div.jtable-input-field-container{
                float:none !important;
                width:auto !important;	
            }
			
			            #SiteTableContainer{
                max-width: 920px;
                margin: auto;
            }
      
            body{
                
                min-width:500px !important ;
                max-width:550px !important ;
                min-height:700px !important ;
                overflow:hidden;
                /*display: -webkit-inline-box;*/
				display:inline-block;
            }
            input,#youtubeButton{
                height: 28px !important;
            }
            table {
                font-size: 11px !important;
            }
            
            .frame { 
                height: 35px; 
                width: 50px; 
                overflow: hidden;    
                background-size: cover;
                background-repeat: no-repeat;
                background-position: 50% 50%;
            }

            .form-horizontal{
                min-width:460px;	
            }
            .ui-dialog .ui-dialog-content{
                overflow:hidden;
                height:auto !important;
            }

            body ul { height: 100px; overflow: auto;}

            

        </style>
		<!--se carga 3.2.0 primero para el efecto del collapsed !-->

		<link id="bs-css" href="../../css/bootstrap-cerulean.css" rel="stylesheet">
		
	<!--	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>!-->
        <script src="../../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
        <script src="../../js/jtable2/jquery.jtable.js" type="text/javascript"></script>
        <script src="../../js/jquery.form.js"></script>
        <script src="../../js/tag-it.min.js" type="text/javascript" charset="utf-8"></script>
	
 

        <script type="text/javascript" src="../../js/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="../../js/jquery.validationEngine-es.js"></script>
 



        <script  type='text/javascript'>

            var content_id = "<?= $content_id ?>";
            var module_id = "<?= $module_id ?>";
        </script>

</head>
<body>

   <div id="conten_all">

         <form class="form-horizontal"   name="form_data" id="form_data">
                <fieldset>
                    <input type="hidden" name="content_id" value="<?php echo $content_id; ?>" id="content_id"> 
                    <input type="hidden" name="module_id" value="<?php echo $module_id; ?>" id="module_id"> 
                    <div class="control-group">
                        <label class="control-label" for="title_es">Título en español*</label>
                        <div class="controls">
                            <input type="text" name="txt_title_es" id="txt_title_es"  >
                        </div>
                    </div>
                    <div class="control-group">                   
                        <label class="control-label" for="title_en">Título en inglés*</label>
                        <div class="controls">
                            <input type="text"  name="txt_title_en" id="txt_title_en"  >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="description_es">Descripción en espańol</label>
                        
                        <div class="controls">
						<textarea class="form-control" id="description_es" name="description_es"></textarea>
						</div>	

                    </div>

                    <div class="control-group">
                        <label class="control-label" for="description_en">Descripción en inglés</label>


                        <div class="controls">
						<textarea class="form-control" id="description_en" name="description_en"></textarea>
						</div>	

                        
                    </div>	


                    <div class="control-group" >
                        <label class="control-label" for="text1">Mostrar Descripción</label>
                        <div class="controls">
                           <input type="radio" name="rbt_mostrar_descripcion" value="Si" checked>Si
						   
                           
							<input type="radio" name="rbt_mostrar_descripcion" value="No">No
                        </div>


                    </div>


                    <div class="control-group">
                        <label class="control-label" for="text1">Mostrar Segmentos</label>
                        <div class="controls">
                           <input type="radio" name="rbt_mostrar_segementos" value="Si" checked>Si
                           
							<input type="radio" name="rbt_mostrar_segementos" value="No">No
                        </div>
                    </div>
					
					
					  
				</fieldset>
				
				<center><div id="warning"></div></center><br>
       </form>

		

            
            <div id="TblItem"   >
       
			
			</div>


            <div class="form-actions">
               <button id="button_form" name="button_form" class="btn btn-primary btn-block" > Guardar</button>
            </div>


        </div>


</body>
</html>

        <script type="text/javascript">


         var content_id = "<?= $content_id ?>";
         var module_id = "<?= $module_id ?>";
         var general_gallery_id ="";
         var result;


         warning = document.getElementById("warning");


                  $(document).ready(function(){

 
				  //parte de hace que se oculte la barra de carga 
            try {
                    parent.onLoading(true);
                } catch (e) {

                }
				
              

				//evento click del primer button para guardar 
				
				$("#button_form").click(function() {
			    

   				var result=true;
				//esta variable se usa ara saber si los dos estan en blancos o solo uno a que el contains no me dejo ejecutarlo 
				var entro=false;
				var msj=''; 

                var title_es=document.forms["form_data"]["txt_title_es"].value;
                var title_en=document.forms["form_data"]["txt_title_en"].value;
                var _description_en = document.forms["form_data"]["description_en"].value;
                var _description_es = document.forms["form_data"]["description_es"].value;
                var _rbt_segmento = document.forms["form_data"]["rbt_mostrar_segementos"].value;
                var _rbt_descripcion = document.forms["form_data"]["rbt_mostrar_descripcion"].value;
                var content_id = document.forms["form_data"]["content_id"].value;
                module_id = document.forms["form_data"]["module_id"].value;

                    if (title_en == null || title_en.trim().length==0 ) {
                        result = false;
						msj='El campo título en inglés';
                    }
                    if (title_es == null || title_es.trim().length==0) {
                        result = false;
						if (msj.trim().length==0){
						msj +=' El campo título en español es obligatorio';
						entro=true;
						}else{
						msj +=' y el campo título en español son obligatorios';
						entro=true;
						}
						
					}
					
					if (entro==false){
						msj +=' es obligatorio';
					}
					 
                     if (result == false) {
                        warning.innerHTML = msj;
                    } else {
					
					warning.innerHTML = "Espere un momento...";
						
						
  //Se prodede a llamar al archivo php que realiza la inserción
                        params = {};
                        params.title_en = title_en;
                        params.title_es = title_es;
                        params._description_en = _description_en;
                        params._description_es = _description_es;
                        

                        if (_rbt_segmento=='Si'){
                        params.muestra_segmento='1';
                          }else{
                        params.muestra_segmento='0';
                        }

                        if (_rbt_descripcion=='Si'){
                        params.muestra_descripcion='1';
                        }else{
                        params.muestra_descripcion='0';

                        }

                        params.content_id = content_id;
                           
                            
                        //Dejar esto para el update
                        params.module_id = module_id;
                         
                        



                        if (module_id == "" || module_id == "undefined") {
                            
                            $.get("../../service/general_gallery.php?action=creategeneralgallery", params, function(data) {
                            
                                      
                                        if (data.error== '0') {
                                        
                                            modinput = document.getElementById("module_id");
                                            modinput.value = (data.module_id);
                                            module_id = data.module_id;
                                            general_gallery_id=data.general_gallery_id;
                                            warning.innerHTML = "Galeria creada";
                                            $("#TblItem").show();
                                             Jtabl();
                                            $('#form_data').find('input, textarea, button, select').attr('disabled',true); 
                                            $('#button_form').attr('disabled', true);
                                         
                                        } else {
                                            warning.innerHTML = "Guarde la página antes de continuar";

                                           
                   
                                        }

                            },'json');
                                
                                
                        } else {
                          
                           warning.innerHTML = "Espere un momento";
                            $.get("../../service/general_gallery.php?action=updategeneralgallery&general_gallery_id="+general_gallery_id, params, function(data) {
                                warning.innerHTML = "Galeria actualizada.";
                                alert(data.msj);
                                try {
                                    parent.closeModuleConfiguration();
                                } catch (e) {

                                }
                            },'json');
                        }




                        //esto tiene que pasar si se  inserta correctamente 
							
                        
                    }

 
			});



//funcion que va mandar  a recuperar los datos 
 function getData() {


                    var data = {};
                    params = {};
                    var module_id = document.getElementById("module_id").value;
                    var content_id = document.getElementById("content_id").value;
                    params.module_id = module_id;
                    params.content_id = content_id;
                    params.action = "getgeneralgallery";
                    params.data = data;
                    
                                    
                    $.post('../../service/general_gallery.php',
                            params,
                            function(data) {
                                if (data.media != false) {
                                    //console.log(data);
                                    $('#txt_title_en').attr('value', data.Record.title_en);
                                    $('#txt_title_es').attr('value', data.Record.title_es);
                                    $('#description_es').attr('value', data.Record.description_es);
                                    $('#description_en').attr('value', data.Record.description_en);
                                    general_gallery_id = data.Record.id;
                
                                        if(data.Record.show_description=='0'){
                                            document.forms["form_data"]["rbt_mostrar_descripcion"].value='No';
                                        }
                                        if(data.Record.segmented=='0'){
                                            document.forms["form_data"]["rbt_mostrar_segementos"].value='No';
                                        }


                                           try {   
    
                                if (general_gallery_id.trim().length>0){

                                       Jtabl();  
                                       $("#TblItem").show();     
                                       $("#button_form").text("Actualizar Galeria");
                                    }


                                }
                                catch(err) {
                                      $("#TblItem").hide();     
                                //   console.log ("is exception  "+  err.message);
                                }

  


                                } else {
                                      warning.innerHTML = 'A ocurrido un error al consultar la informacion';
                                    }
                            },'json');



                           
                }//fin getModM


				
               
			   //srcipt de la tabla 


function Jtabl(){


     $("#TblItem").show();   
			 //Preparando Tabla
                var html_img = '<form id="formupload" action="upload.php?type=1" method="post" enctype="multipart/form-data">';
                    html_img += '    <input id="inpt_image" type="file" name="myfile" ><br>';
                    html_img += '    <input id="confirmsubmit_upload" type="submit" value="Upload File to Server"  style="display:none;">';
                    //html += '    <input id="uri" type="text"  style="display:none;">';
                    html_img += '<div class="input-group">';
                    
                    
                    html_img += '</div>';
                    html_img += '    <div id="msj" ><div>';
                    html_img += '</form>';
                    html_img += '<div class="progress" id="progress">';
                    html_img += '    <div class="bar"></div >';
                    html_img += '    <div class="percent">0%</div >';
                    html_img += '</div>';
                    html_img += '<div id="status"></div><center>Imagenes (jpg/jpeg/png) Max:5MB</center>';





          


            $('#TblItem').jtable({
                title: 'Items de la Galeria',   
                paging:true,
                pageSize:10,
                pageSizes: [5, 10, 50, 100, 250, 500],
                sorting:true,
                defaultSorting: 'title_es ASC',
                actions: {



                    listAction: '../../service/general_gallery.php?action=getcontentItems&general_gallery_id=' + general_gallery_id,
                   createAction: '../../service/general_gallery.php?action=createItemsGallery&general_gallery_id=' + general_gallery_id,
                    updateAction: '../../service/general_gallery.php?action=updategalleryitem&general_gallery_id=' + general_gallery_id,
                    deleteAction: '../../service/general_gallery.php?action=deletegalleryitem&general_gallery_id=' + general_gallery_id,
                },
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    title_es:{
                        title: 'Titulo en Español',
                         inputClass: 'validate[required]',
                        width: '30%'
                    },
					
					title_en:{
                        title: 'Titulo en Ingles',
                        inputClass: 'validate[required]',
                        list: false
                    },
                    
                    description_es:{
                        title: 'Descripción',
                        edit: true,
                        type: 'textarea',
                        width:'30%',
                        sorting:false
                    },
					
					description_en:{
                        title: 'Descripción en Ingles',
                        edit: true,
                        type: 'textarea',
						list: false
                    },           

                    thumbnail:{
                        title: 'Thumbnail',
                        edit: true,
					//	inputClass: 'validate[required]',
                        width:'20%',

                         display: function(data) {
                          var disp;
                             disp = '<center><a target="_blank" href=' + "'../../../assets/img/" + encodeURI(data.record.thumbnail) + "'" + '>' + '<img src="http://54.200.51.188/mcbisite/assets/img/'+  encodeURI(data.record.thumbnail) +'" title="Descargar Imagen" />' + '</a></center>';
                              return(disp);
                        }

                    },

                    link:{
                        title: 'Link',
                        edit: true,
                        width:'20%'
                        
                    },
                     tags: {
                                title: 'Etiquetas',
                                edit: true,
                                list: false,
                                create: true,
                                width: '10%'

                            }

                    
					
                },
                messages: {
                    serverCommunicationError: 'Ocurrió un error al conectar con el servidor',
                    loadingMessage: 'Cargando Items',
                    noDataAvailable: 'No hay Items',
                    addNewRecord: 'Agregar Items',
                    editRecord: 'Editar Items',
                    areYouSure: '¿Está seguro?',
                    deleteConfirmation: 'Este Items será eliminado. ¿Está seguro?',
                    save: 'Guardar',
                    saving: 'Guardando',
                    cancel: 'Cancelar',
                    deleteText: 'Eliminar',
                    deleting: 'Eliminando',
                    error: 'Error',
                    close: 'Cerrar',
                    cannotLoadOptionsFor: 'Can not load options for field {0}',
                    pagingInfo: 'Mostrando {0}-{1} de {2}',
                    pageSizeChangeLabel: 'Row count',
                    gotoPageLabel: 'Go to page',
                    canNotDeletedRecords: 'Can not deleted {0} of {1} records!',
                    deleteProggress: 'Deleted {0} of {1} records, processing...'
                }


                  
                      ,  formCreated: function(event, data) {
                        //file upload
                           result= $("#TblItem").height();

                          
                           var difer =result- $('body,html').height();

                        

                            if (difer<0){
                                    difer=-(difer-200);
                                    $('body,html').height(result+difer);
                                }
                            

                             

                            $("#Edit-thumbnail").parent().append(html_img);
                            $("#Edit-thumbnail").css('display', 'none');
                            //parte de los tags
                          $.post("../../service/general_gallery.php?action=getalltags&module_id=" + module_id, function(data) {
                                tags = new Array();
                                data.forEach(function(elemento) {
                                    tags.push(elemento.tag);
                                });
                                $("#Edit-tags").css('display', 'none');
                                $("#Edit-tags").tagit({showAutocompleteOnFocus: true, singleField: true, availableTags: tags,
                                    afterTagAdded: function(event, ui) {
                                        if (tags.indexOf(ui.tagLabel) == -1)
                                            $("#Edit-tags").tagit("removeTagByLabel", ui.tagLabel);
                                    }
                                }
                                );
                            }, 'json');

                   





                            //fin parte de los tags 


                            //para la barra de progreso en el upload 

                            var bar = jQuery('.bar');
                            var percent = jQuery('.percent');
                            var status = jQuery('#status');
                            $('#inpt_image').change(function(e) {
                                var msg = "";
                                var file_list = e.target.files;


                                for (var i = 0, file; file = file_list[i]; i++) {
                                    var sFileName = file.name;
                                    var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
                                    var iFileSize = file.size;
                                    var iConvert = (file.size / 10485760).toFixed(2);
                                    if (!(sFileExtension === "jpg" || sFileExtension === "png" || sFileExtension === "jpeg") || iFileSize > 5242880) {
                                        msg = "Extension : " + sFileExtension + "\n\n";
                                        msg += "Tamaño: " + iConvert + " MB \n\n";
                                        msg += "Asegurese que su archivo sea una imagen y que pese menos de 5 MB.\n\n";
                                    }
                                }
                                if (msg.length === 0) {
                                    $('#confirmsubmit_upload').click();

                                } else {
                                    alert(msg);
                                }
                            });
                            $('#formupload').ajaxForm({
                                beforeSend: function() {
                                    status.empty();
                                    var percentVal = '0%';
                                    bar.width(percentVal)
                                    percent.html(percentVal);
                                },
                                uploadProgress: function(event, position, total, percentComplete) {
                                    var percentVal = percentComplete + '%';
                                    bar.width(percentVal)
                                    percent.html(percentVal);
                                },
                                success: function(e) {
                                    var percentVal = '100%';
                                    bar.width(percentVal)
                                    percent.html(percentVal);
                                },
                                complete: function(xhr) {
                                    var filename = xhr.responseText.split("}");
                                    filename = filename[0];
                                    $("#Edit-thumbnail").attr("value", filename);
                                //    $("#Edit-url_media_2").attr("value", filename);
                                    //status.html(xhr.responseText);
                                }
                            });
							
							
					     data.form.validationEngine({
                            ajaxFormValidation: true,
                            onAjaxFormComplete: function() {
                              //  console.log('1');
                            }
                        });

                        },
            //Validate form when it is being submitted
            formSubmitting: function (event, data) {


                  var file = $('#Edit-thumbnail').val();
                                
                                if (!file.trim() > 0) {
                                    resultado = false;
                                    alert('Espere a que el thumbnail suba... o suba uno de nuevo');
                                    return resultado;
                                }

                 var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
                  if(RegExp.test(url)){
                    
                    }else{
                         alert('El link indicado no es valido');
                        return false;
                    }



                var validado = data.form.validationEngine('validate');
                return validado;
            },
            //Dispose validation logic when form is closed
            formClosed: function (event, data) {
               
                 $('body,html').height('auto');
                data.form.validationEngine('hide');
                data.form.validationEngine('detach');

            }



            });

			
            $('#TblItem').jtable('load');
        }

          //dentro de la siguiente funcion se valida si se puede hacer conuslta o no, de poderse llena los campos

                 getData() ;
			   
           });


			

         </script>

