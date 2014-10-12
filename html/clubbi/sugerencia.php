<?php 
    include_once 'settings.php';
    ?>
</html><head>
        <meta charset="utf-8" />
        <?php include 'meta_data.php'; ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Club BI| Inicio</title>
        <link rel="stylesheet" href="css/foundation.css" />
        <link rel="stylesheet" href="css/header.css" />
        <link rel="stylesheet" href="css/footer.css" />
        <link rel="stylesheet" href="css/orbit-override.css" />
        <link rel="stylesheet" href="css/font-raleway.css" />
        <link rel="stylesheet" href="css/general.css" />
        <link rel="stylesheet" href="css/sugerencia.css" />   
        <script src="js/vendor/modernizr.js"></script>
        <style>

            .row.withpadding {
                padding-top: 1.5rem;
                padding-bottom: 1.5rem;
            }
            img.responsive{
                width: 100%;
            }
            .paddingfix{
                padding-top: 1.5rem;
                padding-bottom: 1.5rem;
            }
            button:hover, button:focus, .button:hover, .button:focus {
                background: #ff9d0c;
            }
            .btninside {
                margin-top: -48px;
            }
        </style>
        
    </head>
    <body>
<?php include 'header.php'; ?>
        <div class="row">
            <div class="large-12 medium-12 small-12 left columns">
                <h2>Sugerencia</h2>
            </div>
        </div>
        <div class="row">
        <div class="large-6 medium-6 small-12 columns">
            <div class="title">Dirigido a:</div>
            <input type="radio" id="c1" name="tipo" value="Club bi" checked>
            <label class="labelBipuntos" for="c1">Club bi <span></span> </label> 
            <input type="radio" id="c2" name="tipo" value="Bi puntos">
            <label class="labelBipuntos" for="c2">Bi puntos <span></span> </label>  
            <div class="title">Nombre del Establecimiento</div>
            <input type="text" class="fieldBiPuntos" id="establecimiento" placeholder="Ingrese el establecimiento:" name="buscar" maxlength="254">            
        </div>
            <div class="large-6 medium-6 small-12 columns">
            <div class="title">Categoria</div>
            <select class="categoria" id="categoria">
                  <option value="Cine" selected>Cine</option>
                  <option value="Comida">Comida</option>
              </select>
            <div class="title">Otra Categoria</div>
            <input type="text" class="fieldBiPuntos" id="otracategoria" placeholder="Ingresa la  categoria:" name="buscar" maxlength="254">
            </div>
            
        </div>
        <div class="row">
            <div class="large-12 medium-12 small-12 columns">
               <div class="title">Mensaje</div> 
               <textarea class="mensaje" id="msg" placeholder="Ingresa tu Mensaje" maxlength="900"></textarea>
            </div>
        </div>
        <div class="row">
                <div class="large-4 medium-4 small-12 right">
                    <button class="enviar" onclick="enviar()" >Enviar</button>
                   
                </div>
        </div>
        <div class="row">
            <div class="large-12 medium-12 small-12 left ">
                <img src="media/images/banner7.jpg" style="width: 100%;">
            </div>
        </div>
        <br/>
<?php include 'footer.php'; ?>
<?php include 'popups.php'; ?>
        <script src="js/vendor/jquery.js"></script>
        <script src="js/foundation.min.js"></script>
        <script src="js/menuDesktop.js"></script>

        <script>
            $(document).foundation();
        </script>
        <script>
            
            function enviar(){
                if($("#establecimiento").val().trim()===""){
                    error("Alto","Debes especificar un nombre");
                    return false;
                }
                if($("#msg").val().trim()===""){
                    error("Alto","Debes especificar un mensaje");
                    return false;
                }
                var params= {};
                params.action="enviarSugerencia";
                params.dirigido = $("input[name='tipo']:checked").val();
                params.establecimiento = $("#establecimiento").val();
                params.categoria = $("#categoria").val();
                params.otracategoria= $("#otracategoria").val();
                params.msg = $("#msg").val();
                $.post('mail/serviceMail.php', params, function(data) {
                    
                    console.dir(data);
                    if(data==="OK"){
                        successful("¡Sugerencia Enviada!","Envio exitoso");
                    }
                    params.establecimiento = $("#establecimiento").val("");
                    params.otracategoria= $("#otracategoria").val("");
                    params.msg = $("#msg").val("");
                });
            }
            
            
        </script>
         </body>
</html>
