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
        <link rel="stylesheet" href="css/contacto.css" />  
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
                <h2>Contacto</h2>
            </div>
        </div>
        <div class="row">
        <div class="large-6 medium-6 small-12 columns">
            <div class="title">Nombre</div>
            <input type="text" class="fieldBiPuntos" id="nombre" placeholder="Ingrese los datos:" name="buscar" maxlength="254">
            <div class="title">Email</div>
            <input type="text" class="fieldBiPuntos" id="email" placeholder="Ingrese los numeros:" name="buscar" maxlength="254">
            <div class="title">Telefono</div>
            <input type="text" class="fieldBiPuntos esentero" id="telefono" placeholder="Ingrese los numeros:" name="buscar" maxlength="10">
        </div>
            <div class="large-6 medium-6 small-12 columns">
               <div class="title">Tipo de cuenta</div>
               <input type="radio" id="c1" name="tipo" value="Individual" checked>
               <label class="labelBipuntos" for="c1">Individual <span></span> </label>          
              <input type="radio" id="c2" name="tipo" value="Empresa">
               <label class="labelBipuntos" for="c2">Empresa <span></span> </label>          
              <div class="title">¿Tienes Tarjeta Club Bi?</div> 
               <input type="radio" id="c3" name="tiene_tarjeta" value="Si" checked>
              <label class="labelBipuntos" for="c3">Si&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <span></span> </label>          
              <input type="radio" id="c4" name="tiene_tarjeta" value="No">
              <label class="labelBipuntos" for="c4">No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span></span></label>  
               
               <div class="title">Numero de Tarjeta</div> 
               <input type="text" class="fieldBiPuntos esentero" id="tarjeta" placeholder="Ingrese los numeros:" name="buscar" maxlength="16">
            </div>
            
        </div>
        <div class="row">
            <div class="large-12 medium-12 small-12 columns">
               <div class="title">Mensaje</div> 
               <textarea class="mensaje" id="msg" placeholder="Ingresa tu Mensaje" maxlength="894"></textarea>
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
            $( document ).ready(function() {
                $('.esentero').keydown(function(e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
                // Allow: Ctrl+A
                        (e.keyCode == 65 && e.ctrlKey === true) ||
                        // Allow: home, end, left, right
                                (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
            });
            function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  if( !emailReg.test( $email ) ) {
      error("Alto","Ingrese un correo Valido");
    return false;
  } else {
    return true;
  }
}
function enviar(){
    //Validate
    var email = $("#email").val();
    if(email.trim()===""){
        error("Alto","Debe ingresar un correo.");
        return false;
    }
    if(!validateEmail(email)){
        error("Alto","Debe ingresar un correo valido.");
        return false;
    }
    if($("#nombre").val().trim()===""){
                    error("Alto","Debes especificar un nombre");
                    return false;
                }
                if($("#email").val().trim()===""){
                    error("Alto","Debes especificar un email");
                    return false;
                }
                if($("#telefono").val().length<8){
                    error("Alto","Debes especificar un telefono valido");
                    return false;
                }
                if($("#msg").val().trim()===""){
                    error("Alto","Debes especificar un mensaje");
                    return false;
                }
                if($("input[name='tiene_tarjeta']:checked").val()==="Si"){
                  if($("#tarjeta").val().length!==16){
                    error("Alto","El n&uacute;mero de tarjeta debe tener  16 digitos.");
                    return false;
                }  
                }
                var params= {};
                params.action="enviarComentario";
                params.tipo = $("input[name='tipo']:checked").val();
                params.tarjeta = $("input[name='tiene_tarjeta']:checked").val();
                params.nombre = $("#nombre").val();
                params.email = $("#email").val();
                params.telefono = $("#telefono").val();
                params.notarjeta = $("#tarjeta").val();
                params.msg = $("#msg").val();
                
                $.post('mail/serviceMail.php', params, function(data) {
                    
                    console.dir(data);
                    if(data==="OK"){
                        successful("¡Comentario Enviado!","Envio exitoso");
                    }
                      $("#nombre").val("");
                 $("#email").val("");
                 $("#telefono").val("");
                 $("#tarjeta").val("");
                 $("#msg").val("");
                });
  
    
}
            </script>
    </body>
</html>