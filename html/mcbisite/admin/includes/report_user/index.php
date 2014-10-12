<?php
require_once("../../service/models/config.php");
$content_id = isset($_REQUEST['content_id']) ? $_REQUEST['content_id'] : '';
$module_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '-1';
global $language;
$lan = $language;
if ($module_id == "") {
    $module_id = "-1";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>BANCO INDUSTRIAL</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=9">

<link rel="stylesheet" href="../youtube/css/bootstrap.min.css">
   <link rel="stylesheet" href="../../js/jtable/themes/lightcolor/gray/jtable.css">
    <link href="../../css/bootstrap-responsive.css" rel="stylesheet">
    <link id="bs-css" href="../../css/bootstrap-cerulean.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="../../js/jtable/themes/lightcolor/gray/jtable.css">-->
    <link rel="stylesheet" href="../../css/jquery-ui.css">
    <script type="text/javascript" src="../../js/jquery-1.7.1.min.js"></script>
    <script src="../../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
   <script src="../../js/jtable2/jquery.jtable.js" type="text/javascript"></script>
     <!--<script src="scripts/jtable/jquery.jtable.js" type="text/javascript"></script>-->
    <script src="../../js/jquery.form.js"></script>
    <script src="../../js/tag-it.min.js" type="text/javascript" charset="utf-8"></script>
    <!--Validacion para jTable-->
    <link href="../../css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../../js/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="../../js/jquery.validationEngine-es.js"></script>
    <script type="text/javascript" src="../../js/jquery.validationEngine-en.js"></script>
    <!--<link href="../../../bisite/css/foundation.perfil.css" rel="stylesheet" type="text/css" />

    <!--===================Prueba==================================-->
        <script src="../../js/jquery.js" type="text/javascript"></script>

        <link href="../../css/jquery.tagit.css" rel="stylesheet" type="text/css">
        <link href="../../css/bootstrap-responsive.css" rel="stylesheet">
        <link rel="stylesheet" href="../../js/jtable/themes/lightcolor/gray/jtable.css">
        <link rel="stylesheet" href="../../css/jquery-ui.css">
         <link href="../../css/validationEngine.jquery.css" rel="stylesheet" type="text/css" />
         <script src="../../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
        <script src="../../js/jtable2/jquery.jtable.js" type="text/javascript"></script>
        <script src="../../js/jquery.form.js"></script>
        <script src="../../js/tag-it.min.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="../../js/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="../../js/jquery.validationEngine-es.js"></script>

        <link href="../../css/search_cmb/select2.css" rel="stylesheet"/>
        <script src="../../js/search_cmb/select2.js"></script>

 <style type="text/css">
 			body{
                /*min-width:500px !important ;
                max-width:550px !important ;
                min-height:700px !important;
                overflow:hidden;
                /*display: -webkit-inline-box;*/
                display:inline-block;

                        }
                        .contenedorFormulariofx{
                            border-radius: 10px;
                            background-color: #F7F7F7;
                            padding-top: 25px;  
                        }
                        .contenedorFormulariofx input[type="text"], input[type="date"]{
                            background: #FFFFFF;
                            border: #DAD8D8 1px solid;  
                        }
                        .titulo{
                            border-top: 0;  
                            margin-top: 0;
                        }
                        .contenedorFormulariofx .selectArea select {
                            height: 40px;
                            -webkit-appearance: none;
                            appearance: none;
                            background-color: transparent;
                            border: 0;
                            margin-left: 5px;
                            outline: 0;
                            opacity: 1;
                        }
                        .contenedorFormulariofx .selectArea {
                            background-color: #fff;
                            border: #DAD8D8 1px solid;
                        }

                        .large-6left{
                            width: 48%;
                            float: left;
                        }

                        .large-6right{
                            width: 48%;
                            float: right;
                        }

                        .datepic{
                            width: 100%;
                            text-align: center;
                            font-weight: bold;
                            
                        }

                        #filtros label{
                            font-size: 13px;
                            color: #2a5b95;
                        }

                        .selectArea{
                            font-size: 15px;
                        }

                        .select2-container{
                            width: 100%;
                        }

        </style>
</head>
<body>
    <br/><br/>
        <center>
            <div id="filtros" class="large-12  columns contenedorFormulario contenedorFormulariofx " style="height:200px; max-width:700px;" >
                   
                <div class="large-6left">
                    <label>Usuario: </label>
                    <div class="selectArea">        
                        <select name="cmb_user" id="cmb_user"></select>
                    </div>
                    <small>&nbsp;</small>
                </div>

                <div class="large-6right">
                    <label>Tipo Acción: </label>
                    <div class="selectArea">        
                        <select name="cmb_action" id="cmb_action"></select>
                    </div>
                    <small>&nbsp;</small>
                </div>
                        
                <div class="large-6left">
                    <label>Fecha Inicial:</label>
                    <input type='text'  id="txt_fecha_ini" class='datepic' placeholder='dd/mm/yyyy' onkeyup="mascara(this,'/',patron,true)" style="height:40px; font-size: 15px;"/>
                    <small>&nbsp;</small>
                </div>
                        
                <div class="large-6right">
                    <label>Fecha Final:</label>
                    <input type='text'  id="txt_fecha_fin" class='datepic' placeholder='dd/mm/yyyy' onkeyup="mascara(this,'/',patron,true)" style="height:40px; font-size: 15px;"/>
                    <small>&nbsp;</small>
                </div>
            </div>
        </center>
    


                <script src="../../js/formulas.js"></script>
                <script>
                        $(function() {
                            $('.datepic').datepicker({
                                dateFormat: 'dd/mm/yy',
                                inline: true
                            });

                        });
                        </script>
                        <div class="row contenedorFormulario"> 
                            <div class="large-12 columns">
                                <center>
                                    <input type="button" id="btnBuscar" class="btn btn-primary" value="Buscar" onclick="filter();">
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

    <div style="margin-top: 15px;">
        <hr>
            <div id="PeopleTableContainer" ></div>
            <div id="ContenedorVideos" ></div>
    </div>
</body>
</html>



    <script  type='text/javascript'>

        warning = document.getElementById("warning");
        warning.innerHTML = '';
        

           $('#btnreportExcel').click(function(){ 
                warning.innerHTML = 'Generando Excel';
                get_report(1);    
           });
           
           $('#btnreportPDF').click(function(){ 
                warning.innerHTML = 'Generando PDF';
                get_report(2);    
           });
      

        $(document).ready(function(){  
            getUser();
            getAction();
            function getUser(){
                    $('#cmb_user').children().remove();

                    $.post('../../service/user_history.php?action=getuser', function(data) {
                        select = document.getElementById('cmb_user');
                        if (data.length >0){
                            var opt = document.createElement('option'); 
                            opt.value = 000;
                            opt.innerHTML = 'Todos los usuarios';
                            select.appendChild(opt);
                            get_option_cmb(data,select);
                            $("#cmb_user option[value=0]").attr("selected",true);
                            $("#cmb_user").select2(); 
                           
                        }else{
                            var opt = document.createElement('option'); 
                            opt.value = 000;
                            opt.innerHTML = 'Sin usuarios';
                            select.appendChild(opt);
                        }

                    }, 'json');

                }

                function getAction(){
                    $('#cmb_action').children().remove();

                    $.post('../../service/user_history.php?action=getuseraction', function(data) {
                        select = document.getElementById('cmb_action');
                        if (data.length >0){
                            var opt = document.createElement('option'); 
                            opt.value = 000;
                            opt.innerHTML = 'Todas las acciones';
                            select.appendChild(opt);
                            get_option_cmb(data,select);
                            $("#cmb_action option[value=0]").attr("selected",true);
                            $("#cmb_action").select2();
                        }else{
                            var opt = document.createElement('option'); 
                            opt.value = 000;
                            opt.innerHTML = 'Sin acciones';
                            select.appendChild(opt);
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

          //$('#cmb_user').change(function() {  
          //    get_pages( $("#cmb_user").val() );
          //});
                

        	buildTable();
        	try {
                    parent.onLoading(true);
                } catch (e) {

                }
        });//fin de load

                function filter(){
                    var fech1=$("#txt_fecha_ini").val();
                    var fech2=$("#txt_fecha_fin").val();

                    if((fech1.trim().length>0)&&(fech2.trim().length>0)){
                        if(validatedate(fech1,fech2)){
                            buildTable();
                        }
                    }else{
                        buildTable();   
                    }
                     
                }
//valida fechas
                function validatedate(fech1,fech2){
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
//Fin valida fechas

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

//fin mascara de entrada fechas

//Contruccion de tabla

                function buildTable(){
                warning.innerHTML = '';
                params={};
                    params.user_id=$("#cmb_user").val();
                    params.action_user=$("#cmb_action").val();
                    params.date1=$("#txt_fecha_ini").val();
                    params.date2=$("#txt_fecha_fin").val();
                    params_excel={};
                    params_excel=params;
            //Preparando Tabla
            $('#PeopleTableContainer').jtable({
                title: 'Listado de parametros',
                paging:true,
                pageSize:10,
                pageSizes: [5, 10, 50, 100, 250, 500],
                sorting:true,
                //defaultSorting: 'first_name ASC',
                actions: {
                    listAction: '../../service/user_history.php?action=getuserhistory',
                    //createAction: '../../service/user_history.php?action=createpromotions',
                    //updateAction: '../../service/user_history.php?action=updatepromotions',
                    //deleteAction: '../../service/user_history.php?action=deletepromotions',
                },
                fields: {
                    id: {
                        key: true,
                        create: false,
                        edit: false,
                        list: false
                    },
                    name:{
                        title: 'Usuario',
                        width: '20%',
                    },
                    
                    action:{
                        title: 'Acción',
                        width: '20%',
                        
                    },
                    description: {
                        title: 'Descripción',
                        list: true,
                        width: '35%',
                        sorting: false

                            },
                    created_at: {
                        title: 'Fecha de creación',
                        list: true,
                        type:'date',
                        width:'25%',
                        sorting: false,
                        displayFormat: 'dd/mm/yy'
                            },
                    
                    
                },

                messages: {
                    serverCommunicationError: 'Ocurrió un error al conectar con el servidor',
                    loadingMessage: 'Cargando registros',
                    noDataAvailable: 'No hay promoción',
                    addNewRecord: 'Agregar promoción',
                    editRecord: 'Editar promoción',
                    areYouSure: '¿Está seguro?',
                    deleteConfirmation: 'Esta promoción será eliminado. ¿Está seguro?',
                    save: 'Guardar',
                    saving: 'Guardando',
                    cancel: 'Cancelar',
                    deleteText: 'Eliminar',
                    deleting: 'Eliminando',
                    error: 'Error',
                    close: 'Cerrar',
                    cannotLoadOptionsFor: 'Can not load options for field {0}',
                    pagingInfo: 'Parámetros {0}-{1} de {2}',
                    pageSizeChangeLabel: 'Row count',
                    gotoPageLabel: 'Go to page',
                    canNotDeletedRecords: 'Can not deleted {0} of {1} records!',
                    deleteProggress: 'Deleted {0} of {1} records, processing...'
                }
            });
            $('#PeopleTableContainer').jtable('load',params);
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
                  var Arcontenido_fijo=[];
        

                    //titulo
                    var dat={};
                    dat["fila"]=4;
                    dat["columna"]='A';
                    dat["description"]='Bitácora de usuarios administrativos';
                    Artitulo.push(dat);

                        var dat ={};
                        dat["fila_ini"]=7;
                        dat["columna_ini"]='A';
                        dat["orientation"]=0;
                        
                
                                        dat["titulo"]=["Usuario","Tipo Acción","Fecha Inicial","Fecha Final"];
                                        dat["values"]=[$("#cmb_user :selected").text(), $("#cmb_action :selected").text(),$('#txt_fecha_ini').val(),$('#txt_fecha_fin').val()];
                                  
                        

                    Arfiltros=dat;

                    var dat={};
                      dat["fila_in"]=13;
                      dat["colum_in"]='A';
                      dat["orientation"]=1;
                      dat["titulo"]=["USURIO","ACCION","DESCRIPCION","FECHA DE CREACION"];

                    Arcolumnas=dat;

                    

                    //personalizados 

                    var dat={}
                      dat["type_personalization"]=0;                     
                      dat["range"]='A4:D4';
                      Arperzonalizado.push(dat);


                    
                    var dat={}
                    
                          dat["columna"]='A';
                          dat["fila"]=14;
                    
                    
                       $.post('../../service/user_history.php?action=getuserhistory' , params_excel,function(data_titulos) {
                        

                          try {
                                       data_titulos.Records.forEach(function(elemento) {
                                            Arcontenido_fijo.push(  elemento.name+" ||"+elemento.action+" ||"+elemento.description+" ||"+elemento.created_at);
                                       });
                            }
                            catch(err) {
                                
                                alert('Usuario no logueado');
                                window.location.assign("../../login.php");                                
                            }

                                            dat["contenido"]=Arcontenido_fijo;
                                            
                                            
                                              Arcontenido=dat;



                              params.personalizados=Arperzonalizado;
                              params.columnas=Arcolumnas;
                              params.titulo=Artitulo;
                              params.filtros=Arfiltros;
                              params.contenido_fijo=Arcontenido;
                  params.tipo=tipo;
                  params.action='getreport';
                              
                              params.tiene_personalizado=1;
                              params.tiene_papas=0;
                              params.tiene_columnas=1;
                              params.tiene_titulo=1;
                              params.tiene_filtros=1;
                              params.tiene_contenido_dinamico=0;
                              params.tiene_contenido_fijo=1;
                    
       
       
       
       
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
        
        
        }
    </script>

      

