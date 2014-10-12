<?php

    include_once 'settings.php';
    $htmlMovil = "";

    try {

        $url = $wsServer . '/contenido?tipo=info&id=movil';

        $data = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        buildMovil(json_decode($result));


    }catch(Exception $e) {
    }


    function buildMovil($result=array()){
        global $htmlMovil;
        $status = $result->status;
        if($status == 0){
            $info = $result->info;
            $counter = 0;

            for($i=0; $i<count($info); $i++){
                $movil = $info[$i];
                
                $htmlMovil .= '<div class="row">
                    <div class="large-12 medium-12 small-12 left ">
                        <div class="large-7 medium-7 small-12 left ">
                            <h2>' . $movil->titulo . '</h2>
                            <div class="infoContent">
                                    
                                    <p>' . $movil->texto . '</p>

                            </div>
                            <div class="logoSO">
                                <a href="#"><img src="media/images/logo_googleplay.png"></a>
                                
                            </div>
                        </div>
                        <div class="large-5 medium-5 small-12 left small-centered" style="padding-left: 20px;">
                            <img src="' . $movil->imagen . '">
                            <img src="media/images/movil_sombra.png">
                        </div>   
                    </div>
                </div>';


            }

            

        }
    }

?>

<html>
    <head>
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
            .infoContent p {
                font-size: 20px;
            line-height: 23px;
            margin-top: 32px;
            text-align: left;
            margin-left: 10px;
margin-right: 20px;
            }
            h2
            {
                font-size: 22px;
            }
        </style>
        
    </head>
    <body>
<?php include 'header.php'; ?>

    <?=$htmlMovil?>

    <!--
        <div class="row">
            <div class="large-12 medium-12 small-12 left ">
                <div class="large-7 medium-7 small-12 left ">
                    <h2>Descargable para Android</h2>
                    <div class="infoContent">
                            <p>Club Bi es el programa de lealtad de Corporación Bi, el cual brinda a sus tarjetahabientes beneficios y descuentos instantáneos con solo presentar su tarjeta Club Bi en todos los establecimientos afiliados, indistintamente del medio de pago que utilice. Club Bi es un programa abierto, por lo que no es necesario tener productos con Corporación Bi para poder obtener la tarjeta y disfrutar de todos los beneficios que el programa ofrece.
                            </p><p>Club Bi es el programa más grande Guatemala, ya que posee la mayor cantidad de tarjetahabientes,</p>

                    </div>
                    <div class="logoSO">
                        <a href="#"><img src="media/images/logo_googleplay.png"></a>
                        
                    </div>
                </div>
                <div class="large-5 medium-5 small-12 left small-centered" style="padding-left: 20px;">
                    <img src="media/images/movil1.jpg">
                    <img src="media/images/movil_sombra.png">
                </div>   
            </div>
        </div>
        
        
        <div class="row">
            <div class="large-12 medium-12 small-12 left ">
                <div class="large-7 medium-7 small-12 left ">
                    <h2>Descargable para Ios</h2>
                    <div class="infoContent">
                            <p>Club Bi es el programa de lealtad de Corporación Bi, el cual brinda a sus tarjetahabientes beneficios y descuentos instantáneos con solo presentar su tarjeta Club Bi en todos los establecimientos afiliados, indistintamente del medio de pago que utilice. Club Bi es un programa abierto, por lo que no es necesario tener productos con Corporación Bi para poder obtener la tarjeta y disfrutar de todos los beneficios que el programa ofrece.
                            </p><p>Club Bi es el programa más grande Guatemala, ya que posee la mayor cantidad de tarjetahabientes,</p>

                    </div>
                    <div class="logoSO">
                        <a href="#"><img src="media/images/logo_applestore.jpg"></a>
                    </div>
                </div>
                <div class="large-5 medium-5 small-12 left small-centered" style="padding-left: 20px;">
                    <img src="media/images/movil2.jpg">
                    <img src="media/images/movil_sombra.png">
                </div>   
            </div>
        </div>
        
        -->

        <div class="row">
            <div class="large-12 medium-12 small-12 left ">
                <img  src="media/images/banner7.jpg" style="width: 100%;">
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
         </body>
</html>
