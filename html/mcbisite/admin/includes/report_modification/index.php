<!DOCTYPE html>
<html>
    <head>
        <title>BANCO INDUSTRIAL</title>
        
      
    <!--JTABLES-->
    <!--<link rel="stylesheet" type="text/css" href="css/kickstart/kickstart.css" media="all" />-->

      <link href="../../css/themes/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" type="text/css" href="../../css/bootstrap-cerulean.css">  
      <link href='../../css/opa-icons.css' rel='stylesheet'>
    
      
      <link href="../../css/search_cmb/select2.css" rel="stylesheet"/>
      <link rel="stylesheet" href="../../js/jtable/themes/lightcolor/gray/jtable.css">
      <link rel="stylesheet" href="../../css/jquery-ui.css">
    
    <!--<script src="jTable-PHP-Samples/Codes/scripts/jquery-1.6.4.min.js" type="text/javascript"></script>-->
        <script src="../../js/jquery-1.7.1.min.js" type="text/javascript"></script>
        <script src="../../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
        <script src="../../js/jtable2/jquery.jtable.js" type="text/javascript"></script>
        <script src="../../js/search_cmb/select2.js"></script>
    


        <style>
        
                        body{
                            overflow:hidden;

                        }
                        .contenedorFormulariofx{
                            border-radius: 10px;
                            background-color: #F7F7F7;
                            padding-top: 5px;  
                            padding-bottom: 30px;
                        }

                        label{
                            font-size: 13px;
                            color: #2a5b95;
                            margin-top: 15px;
                        }
                      
                        .titulo{
                            border-top: 0;  
                            margin-top: 0;
                        }

                        

                      
                      

        </style>

        
    



    </head>
    <body>


      
      
          
  
      <center>
        <div style="max-width: 600px;"  >
            <div  class="row-fluid show-grid contenedorFormulariofx" style="max-width: 550px;" >
                   <span></span>
                          <div class="span12">  
                                    <label class="control-label labelfont">Sitio Web: </label>
                                    <select from="es_prueba" id="cmb_sitio_web">
                                    </select>
                           </div>
                           <br>
                
                   
                    
                          <div class="span12">
                                <label class="control-label">Título de página: </label>
                                    <select name="cmb_pagina" id="cmb_pagina">
                                    </select>
                         </div>
                         <br>

                    
                          <div class="span12">
                                <label class="control-label">Tipo de módulo: </label>
                                      
                                    <select name="cmb_modulo" id="cmb_modulo">
                                    </select>
                          </div>
                          <br>
                    

                         <div class="span12">
                                <label class="control-label">Estatus a filtrar: </label>
                                
                                      <select name="cmb_filtro" id="cmb_filtro">
                                      <option value="0">Seleccione un esatus</option>
                                      <option value="1">Modificaciones</option>
                                      <option value="2">Aprobaciones/rechazos</option>
                                    </select>
                                
                                
                         </div>
                         <br>
                        
                
                <div id="div_modificacion" style="display: none;">
                
                    <div class="span6">
                            <label class="control-label">Fecha Inicial:</label>
                            <input type="text"  id="txt_fecha_ini_mod" class="datepic"  placeholder="dd/mm/yyyy">
                    
                    </div>
                    
                    
                    <div class="span6">
                            <label class="control-label">Fecha Final:</label>
                            <input type="text"  id="txt_fecha_fin_mod" class="datepic"  placeholder="dd/mm/yyyy">
                    
                    </div>
                    <br>



                       <div class="span12">
                                <label class="control-label">Usuario que realizó el cambio: </label>
                                        
                                    <select name="cmb_user_m" id="cmb_user_m">
                                      
                                    </select>
                                
                                
                         </div>
                         <br>

                 </div>

                 <div id="div_aprobacion" style="display: none;">

                    <div class="span6">
                            <label class="control-label">Fecha Inicial:</label>
                            <input type="text"  id="txt_fecha_ini_apr" class="datepic"   placeholder="dd/mm/yyyy">
                    
                    </div>


                     
                     
                     <div class="span6">
                            <label class="control-label">Fecha Final:</label>
                            <input type="text"  id="txt_fecha_fin_apr" class="datepic"  placeholder="dd/mm/yyyy">
                    
                    </div>
                    <br>

                       <div class="span12">
                                <label class="control-label">Estatus: </label>
                                <div class="selectArea">        
                                    <select name="cmb_filtro_estatus" id="cmb_filtro_estatus">
                                      <option value="0">Seleccione un estatus</option>
                                      <option value="1">Aprobados</option>
                                      <option value="3">Rechazados</option>
                                    </select>
                                </div>
                                
                         </div>
                         <br>


                       <div class="span12">
                                <label class="control-label">Usuario que realizó el cambio: </label>
                                <div class="selectArea">        
                                    <select name="cmb_user_a" id="cmb_user_a">
                                      
                                      
                                    </select>
                                </div>
                                
                         </div>
                         <br>


                 </div>


                  </div>
                </div>

                    </center>
    
                          <br>
                          <br>
                        <div class="row contenedorFormulario"> 
                            <div class="large-12 columns">
                                <center>
                                     <button id="btnBuscar" name="btnBuscar" class="btn btn-primary"  > Buscar</button>
                                </center>

                            </div>
                        </div>


				<div style="margin-top: 25px;">
				  <center>
					<div id="warning"></div>
				  </center>
						<div style="float: right;">
						  <img src="../../css/media/general/pdf.png" border="0" style="padding-bottom: -50px;" id="btnreportPDF">
						  <img src="../../css/media/general/excel.png" border="0" style="padding-bottom: -50;" id="btnreportExcel">
						</div>
				</div>
        <br>
    
    
          <hr>
           <div id="content"  style="margin-top: 15px;"></div>
              <script src="../../js/formulas.js"></script>
                <script>
				
				
                				
				
                        $(function() {
                            $('.datepic').datepicker({
                                dateFormat: 'dd/mm/yy',
                                inline: true
                            });

                        });
                </script>
              
      </body>
</html>

      
        
        <script>

        warning = document.getElementById("warning");
        warning.innerHTML = '';

      random="";

           $(document).ready(function(){ 
              var get_user_bool =true;
              $("#cmb_filtro").select2(); 
              $("#cmb_filtro_estatus").select2(); 

                        get_sites();
                        


                     




              function get_sites(){
                $('#cmb_sitio_web').children().remove();

                $.post('../../service/report_modification.php?action=getSites', function(data) {
                    select = document.getElementById('cmb_sitio_web');
                    if (data.length >0){

                                 get_option_cmb(data,select);
                                 $("#cmb_sitio_web option[value=0]").attr("selected",true);
                                 $("#cmb_sitio_web").select2(); 
                                 get_pages( $("#cmb_sitio_web").val() );
                           
                            }
                            else{
                                        var opt = document.createElement('option'); 
                                        opt.value = 000;
                                        opt.innerHTML = 'Sin sitios web';
                                        select.appendChild(opt);
                                }

                            }, 'json');

            }



        function get_pages(id_site){
          $('#cmb_pagina').children().remove();
          select = document.getElementById('cmb_pagina');
        
          if (id_site==0){
                  var opt = document.createElement('option'); 
                            opt.value = 0;
                            opt.innerHTML = 'Todas las paginas';
                            select.appendChild(opt);
                            $("#cmb_pagina").select2(); 
                            $('#cmb_pagina').attr('disabled',true); 
                            
              get_module();


        }else{ 

            
            $.post('../../service/report_modification.php?action=getPages&id_site='+id_site , function(data) {
                    
                    if (data.length >0){
                                 get_option_cmb(data,select);
                                 $("#cmb_pagina").select2(); 
                                 
                                 get_module();
                                 $('#cmb_pagina').attr('disabled',false); 
                                 $("#cmb_pagina option[value=0]").attr("selected",true);
                                 // #cmb_user_m ,#cmb_user_a
                     }
                            else{
                                        var opt = document.createElement('option'); 
                                        opt.value = 000;
                                        opt.innerHTML = 'Sin paginas';
                                        select.appendChild(opt);
                                }

                            }, 'json');

                }

             }


        function get_module(){

            $('#cmb_modulo').children().remove();
            $.post('../../service/report_modification.php?action=getModule' , function(data) {
                    select = document.getElementById('cmb_modulo');
                    if (data.length >0){
                                 get_option_cmb(data,select);
                                 $("#cmb_modulo option[value=0]").attr("selected",true);
                                 $("#cmb_modulo").select2(); 
                                 
                                 if (get_user_bool){
                                 get_user();
                                 get_user_bool=false;
                                 }
                                
                     }
                            else{
                                        var opt = document.createElement('option'); 
                                        opt.value = 000;
                                        opt.innerHTML = 'Sin modulos';
                                        select.appendChild(opt);
                                        $("#cmb_modulo").select2(); 
                                        get_user();


                                }

                            }, 'json');

             }
             
           
        
        
        function get_user(){
          $('#cmb_user_m').children().remove();
          $('#cmb_user_a').children().remove();

            $.post('../../service/report_modification.php?action=getUser' , function(data) {
                    select_a = document.getElementById('cmb_user_a');
                    select_m = document.getElementById('cmb_user_m');
                    if (data.length >0){
                                 get_option_cmb(data,select_m);
                                 get_option_cmb(data,select_a);
                                 $("#cmb_user_a option[value=0]").attr("selected",true);
                                 $("#cmb_user_a").select2(); 
                                 
                                 $("#cmb_user_m option[value=0]").attr("selected",true);
                                 $("#cmb_user_m").select2(); 
                                params = {};
                                params.filter =  0;
                                params.html=1;
                                params_excel={};
                                params_excel=params;
                                get_jtable();
                                 
                                
                     }
                            else{
                                 var opt = document.createElement('option'); 
                                 opt.value = 000;
                                 opt.innerHTML = 'Sin modulos';
                                 select_a.appendChild(opt);
                                 select_m.appendChild(opt);
                                 $("#cmb_user_a").select2();
                                 $("#cmb_user_m").select2(); 

                                }

                            }, 'json');   
            }


          function get_option_cmb(data,select){
               data.forEach(function(elemento) {
                     var opt = document.createElement('option'); 
                     opt.value = elemento.id;
                     opt.innerHTML = elemento.description;
                     select.appendChild(opt);
                    });
            }

      $('#cmb_sitio_web').change(function() {  
          get_pages( $("#cmb_sitio_web").val() );
      });


      $('#cmb_pagina').change(function() {  
           //get_module( $("#cmb_pagina").val() );
      });


       $('#cmb_filtro').change(function() {
            
                        if ( $('#cmb_filtro').val()=="1"){
                          $('#div_modificacion').show();
                          $('#div_aprobacion').hide();
                        }

                        if ( $('#cmb_filtro').val()=="2"){
                          $('#div_modificacion').hide();
                          $('#div_aprobacion').show();
                        }

                        if ( $('#cmb_filtro').val()=="0"){
                          $('#div_modificacion').hide();
                          $('#div_aprobacion').hide();

                 }
                 
             
            });


          $('#btnreportExcel').click(function(){ 
				warning.innerHTML = 'Generando Excel';
				get_report(1);    
           });
		   
		   $('#btnreportPDF').click(function(){ 
				warning.innerHTML = 'Generando PDF';
				get_report(2);    
           });


           $('#btnBuscar').click(function(){
				  
				  warning.innerHTML = '';
				  params = {};
                  
                  params.site =  $('#cmb_sitio_web').val();
                  params.page=   $('#cmb_pagina').val();
                  params.module= $('#cmb_modulo').val();

               if ( $('#cmb_filtro').val()!="0"){

                  if ( $('#cmb_filtro').val()=="1" ){
                        params.filter =  '1';
                        params.user=$('#cmb_user_m').val();

                    if ( $('#txt_fecha_ini_mod').val().trim().length!=0 && $('#txt_fecha_fin_mod').val().trim().length!=0) {

                          if ( !validatedate($('#txt_fecha_ini_mod').val().trim(), $('#txt_fecha_fin_mod').val().trim() ) ){
                              return;
                          }else{
                              params.date =  "2";
                              params.date_ini=$('#txt_fecha_ini_mod').val().trim();
                              params.date_fin=$('#txt_fecha_fin_mod').val().trim();

                          }

                      }else{

                      if ( $('#txt_fecha_ini_mod').val().trim().length==0 && $('#txt_fecha_fin_mod').val().trim().length==0) {
                                  params.date ="0";
                        }else{

                                if ( $('#txt_fecha_ini_mod').val().trim().length>0 ){
                                    params.date ="1";
                                    params.date_ini=$('#txt_fecha_ini_mod').val().trim();
                                }

                                 if ( $('#txt_fecha_fin_mod').val().trim().length>0 ){
                                    params.date="3";
                                    params.date_fin=$('#txt_fecha_fin_mod').val().trim();
                                 } 


                              }

                            }


                      }else{
                          
                                params.filter =  '2';
                                params.user=$('#cmb_user_a').val();
                                params.estatus_a_r=$('#cmb_filtro_estatus').val();

                    if ( $('#txt_fecha_ini_apr').val().trim().length!=0 && $('#txt_fecha_fin_apr').val().trim().length!=0) {

                          if ( !validatedate($('#txt_fecha_ini_apr').val().trim(), $('#txt_fecha_fin_apr').val().trim() ) ){
                              return;
                          }else{
                              params.date =  "2";
                              params.date_ini=$('#txt_fecha_ini_apr').val().trim();
                              params.date_fin=$('#txt_fecha_fin_apr').val().trim();

                          }

                      }else{

                      if ( $('#txt_fecha_ini_apr').val().trim().length==0 && $('#txt_fecha_fin_apr').val().trim().length==0) {
                                  params.date ="0";
                        }else{

                                if ( $('#txt_fecha_ini_apr').val().trim().length>0 ){
                                    params.date ="1";
                                    params.date_ini=$('#txt_fecha_ini_apr').val().trim();
                                }

                                 if ( $('#txt_fecha_fin_apr').val().trim().length>0 ){
                                    params.date="3";
                                    params.date_fin=$('#txt_fecha_fin_apr').val().trim();
                                 } 


                              }

                            }
                          
                          
                          
                          
                      }



                  }else{
                       params.filter =  '0';

                  }

                 params.html=1;
                 params_excel={};
                 params_excel=params;
                  
                  get_jtable();

               });


               function validatedate(fech1,fech2 ){
                 
                    var afech1= fech1.split("/");
                    var afech2= fech2.split("/");
                    fech1=afech1[2]+afech1[1]+afech1[0];
                    fech2=afech2[2]+afech2[1]+afech2[0];
                    if ((fech1)>(fech2)){
                        alert('La fecha final debe ser mayor a la fecha inicial');
                        return false;
                    }       
                    return true;
                }



                //Mascarad de entrada fechas
                var patron = new Array(2,2,4)
                var patron2 = new Array(1,3,3,3,3)
                function mascara(d,sep,pat,nums){
                    if(d.valant != d.value){
                        val = d.value
                        largo = val.length
                        val = val.split(sep)
                        val2 = ''
                        for(r=0;r<val.length;r++){
                            val2 += val[r]  
                        }
                        if(nums){
                            for(z=0;z<val2.length;z++){
                                if(isNaN(val2.charAt(z))){
                                    letra = new RegExp(val2.charAt(z),"g")
                                    val2 = val2.replace(letra,"")
                                }
                            }
                        }
                        val = ''
                        val3 = new Array()
                        for(s=0; s<pat.length; s++){
                            val3[s] = val2.substring(0,pat[s])
                            val2 = val2.substr(pat[s])
                        }
                        for(q=0;q<val3.length; q++){
                            if(q ==0){
                                val = val3[q]
                            }else{
                                if(val3[q] != ""){
                                    val += sep + val3[q]
                                }
                            }
                        }
                        d.value = val
                        d.valant = val
                    }
                }

            //end ready function       
          });


          
          function get_jtable(){

       
                $('#content').jtable({
                    title: 'Notificaciones',
                    sorting: true,
                    defaultSorting: 's.title_es DESC',
                    title: 'Parametros',   
                    paging:true,
                    pageSize:10,
                    pageSizes: [5, 10, 50, 100, 250, 500],

   
                    
                    actions: {
                        listAction: '../../service/report_modification.php?action=listModification',
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
                           sorting: true,
                           defaultSorting: 'ap.id  DESC',
                           paging:true,
                           pageSize:10,
                           pageSizes: [5, 10, 50, 100, 250, 500],

                          actions: {
                            listAction: '../../service/report_modification.php?action=list_detail&approval_id=' + approvalData.record.id,
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
                              sorting: false,
                              list: false
                            },
                            detail_description: {
                              title: 'Descripción',
                              sorting: false,
                              type: 'textarea'
                            },
                            name_edit: {
                              title: 'Editor',
                              sorting: false
                              
                            },
                            edit_date: {
                              title: 'Fecha de cambio',
                              sorting: false,
                              create: false,
                              edit: false
                            },
                            name_aprovade: {
                              title: 'Aprobo/Rechazo',
                              sorting: false
                              
                            },
                            aprov_date: {
                              title: 'Fecha de Aprobacion',
                              create: false,
                              sorting: false,
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


            site_title: {
              title: 'Título de Sitio',
              edit: false,
              list:true
            },

            title_pag: {
              title: 'Título de Página',
              edit: false,
              list:true
            },

            DisplayText: {
              title: 'Modulo',
              edit: false,
              list:true
            },

            message: {
              title: 'Actualización',
              type: 'textarea',
              sorting: false
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


            status: {
              title: 'Status de prueba',
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
          }, messages: {
                    serverCommunicationError: 'Ocurrió un error al conectar con el servidor',
                    loadingMessage: 'Cargando modificaciones',
                    noDataAvailable: 'No hay modificaciones',
                    addNewRecord: 'Agregar modificaciones',
                    editRecord: 'Editar modificaciones',
                    areYouSure: '¿Está seguro?',
                    deleteConfirmation: 'Esta modificacion será eliminada. ¿Está seguro?',
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
          
          
        }); 
        
         

        $('#content').jtable('load',params );
        
        }


		
		
		function get_report(tipo){
		params_excel.html=0;
		params ={};
                  var Artitulo  = [];
                  var Arfiltros =[];
                  var Arcolumnas =[];
                  var Arpapas =[];
                  var Arperzonalizado =[];
                  var Arcontenido=[];
                  var Arcabecera=[];
        

                    //titulo
                    var dat={};
                    dat["fila"]=4;
                    dat["columna"]='A';
                    dat["description"]='Reporte de modificaciones';
                    Artitulo.push(dat);

                        var dat ={};
                        dat["fila_ini"]=7;
                        dat["columna_ini"]='A';
                        dat["orientation"]=0;
                        
                
                          switch ($('#cmb_filtro').val()) {
                                  case '0':
                                        dat["titulo"]=["Sitio Web","Título de página","Tipo de módulo","Estatus a filtrar"];
                                        dat["values"]=[$("#cmb_sitio_web :selected").text(), $("#cmb_pagina :selected").text(),$("#cmb_modulo :selected").text(),$("#cmb_filtro :selected").text()];
                                        
                                  break;

                                  case '1':
                                        dat["titulo"]=["Sitio Web","Título de página","Tipo de módulo","Estatus a filtrar","Fecha Inicial","Fecha Final","Editor"];
                                        dat["values"]=[$("#cmb_sitio_web :selected").text(), $("#cmb_pagina :selected").text(),$("#cmb_modulo :selected").text(),$("#cmb_filtro :selected").text(),$('#txt_fecha_ini_mod').val(),$('#txt_fecha_fin_mod').val(),$("#cmb_user_m :selected").text()];
                                        
                                  break;

                                  case '3':
                                        dat["titulo"]=["Sitio Web","Título de página","Tipo de módulo","Estatus a filtrar","Fecha Inicial","Fecha Final","Estatus","Aprobo/Rechazo"];
                                        dat["values"]=[$("#cmb_sitio_web :selected").text(), $("#cmb_pagina :selected").text(),$("#cmb_modulo :selected").text(),$("#cmb_filtro :selected").text(),$('#txt_fecha_ini_apr').val(),$('#txt_fecha_fin_apr').val(),$("#cmb_filtro_estatus :selected").text(),$("#cmb_user_a :selected").text()];
                                        
                                  break;
                              }
                        

                    Arfiltros=dat;

                    var dat={};
                      dat["fila_in"]=17;
                      dat["colum_in"]='A';
                      dat["orientation"]=1;
                      dat["titulo"]=["Actualización","Título de Sitio","Título de Página","Modulo","Actualización","Descripción","Editor","Fecha de cambio","Aprobo/Rechazo","Fecha de Aprobacion","Status"];

                    Arcolumnas=dat;

                    //papas
                    var dat={}
                      dat["fila"]=16;
                      dat["columna"]='G';
                      dat["titulo"]= 'Modificaciones';
                    Arpapas.push(dat);  

                    var dat={}
                      dat["fila"]=16;
                      dat["columna"]='I';
                      dat["titulo"]= 'Aprobaciones/Rechazos';
                    Arpapas.push(dat);

                    //personalizados 

                    var dat={}
                      dat["type_personalization"]=0;                     
                      dat["range"]='A4:E4';
                      Arperzonalizado.push(dat);
                    
                    var dat={}
                    var ArDetalleCabeceera=[];
                    var ArDetalleGeneral=[];
                          dat["columna"]="A";
                          dat["fila"]=19;
                    var new_estatus='';
                    
                       $.post('../../service/report_modification.php?action=listModification' , params_excel,function(data_titulos) {
                        ids="";

                        try {
                          data_titulos.Records.forEach(function(elemento) {
                                    ids += elemento.id+",";
                              });
    
                            }
                            catch(err) {
                                alert('Usuario no logueado');
                                window.location.assign("../../login.php")                                
                            }

                              

                            ids = ids.substring(0, ids.length-1);
                              //detalles
                                $.post('../../service/report_modification.php?action=list_detailTotal&ids='+ids ,function(data_detalle) {
                                    

                                      
                           ArDetalleCabeceera=[];
                                      data_titulos.Records.forEach(function(elemento) {


                                           switch(elemento.status){
                                                case '0':
                                                  new_estatus = 'Pendiente';
                                                break;
                                                case '1':
                                                  new_estatus = 'Aprobado';
                                                break;
                                                case '2':
                                                  new_estatus = 'Faltan cambios';
                                                break;
                                                case '3':
                                                  new_estatus = 'No aprobado';
                                                break;
                                              }

                                              


                  
                                        Arcabecera.push(  elemento.message+" ||"+elemento.site_title+" ||"+elemento.title_pag+" ||"+elemento.DisplayText+" ||"+new_estatus);
                                        tildes =elemento.message;

                                       o_p = $.grep(data_detalle.Records, function (i) { return (i.approval_m == ""+elemento.id+"") });
                                       
                                          ArDetalleGeneral=[];
                                        o_p.forEach(function(entry) {
                                                      ArDetalleGeneral.push( entry["detail_description"]+" ||"+entry["name_edit"]+" ||"+entry["edit_date"]+" ||"+entry["name_aprovade"]+" ||"+entry["aprov_date"]);
                                              
                                              });

                                                  ArDetalleCabeceera.push(ArDetalleGeneral);

                                      });

                                            dat["titulo_cabecera"]=['ACTUALIZACION','SITIO','PAGINA','MODULO','ESTATUS'];
                                            dat["cabecera"]=Arcabecera;
                                            dat["titulo_detalle"]=['DETALLE','EDITOR','FECHA','APROBO/RECHAZO','FECHA'];
                                            dat["detalle"]=ArDetalleCabeceera;
                                            
                                              Arcontenido=dat;



                              params.personalizados=Arperzonalizado;
                              params.papas=Arpapas;
                              params.columnas=Arcolumnas;
                              params.titulo=Artitulo;
                              params.filtros=Arfiltros;
                              params.contenido_dinamico=Arcontenido;
              							  params.tipo=tipo;
              							  params.action='getreport';


                              params.tiene_personalizado=1;
                              params.tiene_papas=0;
                              params.tiene_columnas=0;
                              params.tiene_titulo=1;
                              params.tiene_filtros=1;
                              params.tiene_contenido_dinamico=1;
                              params.tiene_contenido_fijo=0;
                    
       
       
       
       
        $.ajax({
        url: "../../service/models/funcs.report_xls_pdf.php",
        type:"post",
        async: false,
        data: {
            datos: JSON.stringify(params)
        },
        success: function(response){       
		var extencion='';
		if (tipo==1){
		extencion='xlsx';
     window.location.href = "../../service/models/lib/Classes/PHPExcel/reportes/"+response+"."+extencion+""; // mandamos 
	 warning.innerHTML = 'Excel generado exitosamente';

   }else{
		extencion='pdf';
    window.open("../../service/models/lib/Classes/PHPExcel/reportes/"+response+"."+extencion+"",'_newtab');
	warning.innerHTML = 'PDF generado exitosamente';

     
		}
        
        },
        error:function(xhr, ajaxOptions, thrownError){alert(xhr.responseText); ShowMessage("??? ?? ?????? ??????? ????","fail");}
    });

                                    
                                    }, 'json');   


  
                            }, 'json');   
		
		
		}
		
    
        </script>

		