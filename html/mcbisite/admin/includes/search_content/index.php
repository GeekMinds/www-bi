<html>
    <?php
    $content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';
    $module_id = isset($_REQUEST['module_id']) ? $_REQUEST['module_id'] : '-1';


    if ($module_id == '') {
        
    } else {

    }

    ?>

    <head>




    <link rel="stylesheet" type="text/css" href="../../css/modal/bootstrap.css" />




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


                .contenedorFormulariofx{
                            border-radius: 10px;
                            background-color: #F7F7F7;
                            padding-top: 25px;  
                        }
                .contenedorFormulariofx input[type="text"], input[type="date"]{
                            background: #FFFFFF;
                            border: #DAD8D8 1px solid;  
                        }
    
       form.jtable-dialog-form div.jtable-input-field-container{
                float:none !important;
                width:auto !important;  
            }
      
                  #SiteTableContainer{
                max-width: 920px;
                margin: auto;
            }
            .error
                    {
                        color: red;
                       font-size : 8pt;
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


    <link id="bs-css" href="../../css/bootstrap-cerulean.css" rel="stylesheet">
    

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

         <form  class="form-horizontal"  name="form_data" id="form_data" action="upload.php?type=1" method="post" >
                <fieldset>
                    <input type="hidden" name="content_id" value="<?php echo $content_id; ?>" id="content_id"> 
                    <input type="hidden" name="module_id" value="<?php echo $module_id; ?>" id="module_id"> 

                      

                <div class="control-group" class="form-horizontal">
                        <label class="control-label" for="title_es">Buscador*</label>
                        <div class="controls">
                            <select id="buscador" class="form-control selectpicker" data-live-search="true">
              <option value="000"> Sleccionar una opción</option>
              </select>
                        </div>
                </div>
<div class="contenedorFormulariofx">


   <div class="control-group" style="display:none">
                    
                        <div class="controls">
                            <input type="text"  id="txt_thumbnail"  >
                        </div>
                    </div>

 <h3> Contenido</h3>

                 <div class="control-group">
                        <label class="control-label" for="title_es">Título en español*</label>
                        <div class="controls">
                            <input type="text"  id="txt_title_es"  >
                        </div>
                    </div>
                    <div class="control-group">                   
                        <label class="control-label" for="title_en">Título en inglés*</label>
                        <div class="controls">
                            <input type="text"   id="txt_title_en"  >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="title_es">Descripción corta en espańol*</label>
                        <div class="controls">
                            <input type="text"  id="description_es_small"  >
                        </div>
                    </div>
                    <div class="control-group">                   
                        <label class="control-label" for="title_en">Descripción corta en inglés*</label>
                        <div class="controls">
                            <input type="text"   id="description_en_small"  >
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="description_es">Descripción larga en espańol*</label>
                        
                        <div class="controls">
                        <textarea class="form-control" id="description_es_large" ></textarea>
                        </div>  

                    </div>

                    <div class="control-group">
                        <label class="control-label" for="description_en">Descripción larga en inglés*</label>


                        <div class="controls">
                        <textarea class="form-control" id="description_en_large" ></textarea>
                        </div>  

                        
                    </div> 


  <form id="formupload"  enctype="multipart/form-data">
                     <input id="inpt_image" type="file" name="myfile" ><br>
                      <input id="confirmsubmit_upload" type="submit" value="Upload File to Server"  style="display:none;">
                    
                    <div class="input-group">
                    </div>
                        <div id="msj" ></div>
                    </form>
                    <div class="progress" id="progress">
                        <div class="bar"></div >
                        <div class="percent">0%</div >
                    </div>
                    <div id="status"></div><center>Imagenes (jpg/jpeg/png) Max:5MB</center>


             
                <br>
    </div>
                <br>


                       


                         <div id="div_content" >  <center><div id="warning_load"></center></div>
                               
                         
                        </div>

            
        </fieldset>
        
        <center><div id="warning"></div></center><br>
       </form>

    

            
         

            <div class="form-actions">
               <button id="button_form" name="button_form" class="btn btn-primary btn-block" > Guardar</button>
            </div>


        </div>


</body>
</html>

        <script type="text/javascript">

            
         var content_id = "<?= $content_id ?>";
         var module_id = "<?= $module_id ?>";
         var search_content_id ="";
         var searcher_content_id="";
         var result;
         var name="";
         var values="";

         warning = document.getElementById("warning");
         warning_load = document.getElementById("warning_load");


                  $(document).ready(function(){
      
            try {
                    parent.onLoading(true);
                } catch (e) {

                }

        
                //options
    
                  $.post('../../service/search_content.php?action=getallSearch', function(data) {


                    select = document.getElementById('buscador');
                              
                            
                              tags = new Array();
                                data.forEach(function(elemento) {
                                        var opt = document.createElement('option'); 
                                        opt.value = elemento.id;
                                        opt.innerHTML = elemento.title_es;
                                        select.appendChild(opt);
                                    
                                }
                     
                                
                  );

                                   getData() ;

                              }, 'json');


                  

  
  
             

              //listener select changed 

              $('#buscador').change(function() {
            
                        load_parameters();
                    });

              

              function load_parameters(){

                    $( "#div_parameter" ).remove();
                    warning_load.innerHTML ="Cargando parámetros...";

                
                $.post('../../service/search_content.php?action=getcontent&id='+$('#buscador').val(),  function(data) {
                   

                    $( "#div_content" ).append(data);
                        warning_load.innerHTML="";
                        $('.error').hide();
            

            
                 });
            
              }

        //evento click del primer button para guardar 
        
        $("#button_form").click(function() {




                    if ($("#buscador").val()=="000"){
                        alert('Seleccionar un buscador válido');
                    return false;
                    }

        var pivote="";
                var valida=false;
                //valida text
        var inp = document.getElementsByTagName('input');
              for(var i in inp){
                    if(inp[i].type == "text"){
                        pivote =inp[i].value.trim();
                         if (pivote.length ==0){
                            $("#"+inp[i].id+"").css("border","red 1px solid");
                          
                            valida=true;
                         }  else{
                        
                               $("#"+inp[i].id+"").css("border","#DAD8D8 1px solid");
                         }
                         if ( $("#"+inp[i].id+"").hasClass("datepic")){

                             var dtVal=$("#"+inp[i].id+"").val();
                                   if(ValidateDate(dtVal))
                                   {
                                      $('.error').hide();
                                   }
                                   else
                                   {
                                     $('.error').show();
                                     event.preventDefault();
                                   }



                         }
                    }
                }



            //valita texta area                
                var inp = document.getElementsByTagName('textarea');
           
                for(var i in inp){
                    if(inp[i].type == "textarea"){
                        pivote =inp[i].value.trim();
                         if (pivote.length ==0){
                            $("#"+inp[i].id+"").css("border","red 1px solid");
                            
                            valida=true;
                         }  else{
                         
                               $("#"+inp[i].id+"").css("border","#DAD8D8 1px solid");
                              
                         }

                    }
                }

                if (valida){
                   alert('Existen campos obligatorios vacíos');  
                  return false;
                }



                var file = $('#txt_thumbnail').val();
                                if (!file.trim().length > 0) {
                                    alert('Espere a que el thumbnail suba... o suba uno de nuevo');
                                    return false;
                }

                var parametros =$("#div_parameter").find('select');


                        params = {};
                        params.title_es = document.forms["form_data"]["txt_title_es"].value;
                        params.title_en =document.forms["form_data"]["txt_title_en"].value;
                        params.description_es_small=document.forms["form_data"]["description_es_small"].value;
                        params.description_en_small=document.forms["form_data"]["description_en_small"].value;
                        params.description_es_large=document.forms["form_data"]["description_es_large"].value;
                        params.description_en_large=document.forms["form_data"]["description_en_large"].value;
                        params.txt_thumbnail =document.forms["form_data"]["txt_thumbnail"].value;
                        params.search_id=$("#buscador").val();
                        params.content_id = content_id;
                        //Dejar esto para el update
                        params.module_id = module_id;
                       
        

        var dataString=$("#div_content").find('input:text');
            
           
            $.each(dataString,function(i,entry){// console.log (entry.id+" "+entry.value)

              name+=entry.id+"|";
              values+=entry.value+"|";
          });
               params.names=name;
               params.values=values;

        //       console.log(params);
                        
                  if (module_id == "" || module_id == "undefined") {
                          $.post("../../service/search_content.php?action=createContentSearch", params, function(data) {
                            
                            
                                        if (data.error== '0') {
                                          alert(data.msj);
                                           try {
                                    parent.closeModuleConfiguration();
                                            } catch (e) {

                                            }

                                        } else {
                                    alert(data.msj);

                                         
                                        }

                            },'json');
                                
                                
                        } else {
                          
                           warning.innerHTML = "Espere un momento...";
                            $.post("../../service/search_content.php?action=updateContentSearch", params, function(data) {
                                warning.innerHTML = "Contenido actualizada.";
                                alert(data.msj);
                                try {
                                    parent.closeModuleConfiguration();
                                } catch (e) {


                                }
                            },'json');
                        }




      });

                function ValidateDate(dtValue)
                {
                var dtRegex = new RegExp(/\b\d{1,2}[\/-]\d{1,2}[\/-]\d{4}\b/);
                return dtRegex.test(dtValue);
                }



//funcion que va mandar  a recuperar los datos 
 function getData() {


                    var data = {};
                    params = {};
                    var module_id = document.getElementById("module_id").value;
                    var content_id = document.getElementById("content_id").value;
                    params.module_id = module_id;
                    params.content_id = content_id;
                    params.action = "getContentSearch";
                    params.data = data;
                    
                                    
                    $.post('../../service/search_content.php',
                            params,
                            function(data) {
                        //      console.log (data);
                                if (data.media != false) {
                                    //console.log(data);
                                    $('#txt_title_en').attr('value', data.Record.title_en);
                                    $('#txt_title_es').attr('value', data.Record.title_es);
                                    $('#description_es_small').attr('value', data.Record.content_text_es);
                                    $('#description_en_small').attr('value', data.Record.content_text_en);
                                    //comentario
                                    $('#description_en_large').attr('value', data.Record.content_text_large_en);
                                    $('#description_es_large').attr('value', data.Record.content_text_large_es);
                                    search_content_id=data.Record.id;
                                    searcher_content_id=data.Record.searcher_id;
                                    $("#txt_thumbnail").attr("value", data.Record.thumbnail);

                                     
                  


                                    var html_img ="";
                                    html_img = '<center><a target="_blank" href=' + "'../../../assets/img/" + encodeURI(data.Record.thumbnail) + "'" + '>' + '<img src="http://54.200.51.188/mcbisite/assets/img/'+  encodeURI(data.Record.thumbnail) +'" title="Descargar Imagen" />' + '</a></center></br>';

                                           try {   
                                            
                                            
    
                                if (search_content_id.trim().length>0){
                                  $( ".contenedorFormulariofx" ).append(html_img);
                                  $( "#div_parameter" ).remove();
                                    $("#buscador option[value="+searcher_content_id+"]").attr("selected",true);
                                    $('#form_data').find('select').attr('disabled',true); 
                                  warning_load.innerHTML ="Cargando parámetros...";
                                      params = {};
                                      params.search_content_id = search_content_id;
                                      params.searcher_content_id = searcher_content_id;
                                      params.action = "getcontentEdit";

                                      

                
                                  $.post('../../service/search_content.php',params,  function(data) {

                                        $( "#div_content" ).append(data);
                                        warning_load.innerHTML="";
                                        $('.error').hide();
            
                                      });




                                    }else{
                                    
                                    }


                                }
                                catch(err) {
                                
                                }

                                } else {
                                      warning.innerHTML = 'A ocurrido un error al consultar la informacion';
                                    }
                            },'json');



                           
                }//fin getModM


          //dentro de la siguiente funcion se valida si se puede hacer conuslta o no, de poderse llena los campos

             //    getData() ;
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


                               $('#form_data').ajaxForm({
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
                                     $("#txt_thumbnail").attr("value", filename);
                          
                                    //$("#Edit-thumbnail").attr("value", filename);
                                //    $("#Edit-url_media_2").attr("value", filename);
                                    //status.html(xhr.responseText);
                                }
                            });

         
           });


      

         </script>
     
  

  
