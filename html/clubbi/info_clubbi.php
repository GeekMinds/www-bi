<?php
    include_once './settings.php';
    $title_beneficios = "";
    $text_beneficios = "";
    $photo_beneficios = "";

    $title_puntos = "";
    $text_puntos = "";
    $photo_puntos = "";

    $carrousel_videos = "";
    $preguntas_frecuentes = "";

    try {

        $url = $wsServer . '/contenido?tipo=media&id=info';

        $data = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        buildVideoCarrousel(json_decode($result));

        $url = $wsServer . '/contenido?tipo=info&id=beneficios';

        $data = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        buildBeneficios(json_decode($result));


        $url = $wsServer . '/contenido?tipo=info&id=puntos';

        $data = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        buildPuntos(json_decode($result));


        $url = $wsServer . '/contenido?tipo=faq';

        $data = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        buildPreguntasFrecuentes(json_decode($result));


    }catch(Exception $e) {
    }


    
    function buildVideoCarrousel($result=array()){
        global $carrousel_videos;
        $status = $result->status;
        if($status == 0){
            $videos = $result->media;

            for($i=0; $i<count($videos); $i++){
                $video = $videos[$i];
                $carrousel_videos .= '<li>';               
                    $carrousel_videos .= '<div class="small-12 medium-12 large-12 videogallery">';
                    $carrousel_videos .= '<div class="row">';
                        $carrousel_videos .= '<div class="small-12 medium-8 large-6 medium-offset-2 large-offset-3">';
                            $tipo = $video->tipo;
                            if( $tipo->id ==2){
                               $carrousel_videos .= '<iframe width="100%" height="480" src="//' . $video->media . '" frameborder="0" allowfullscreen class="video"></iframe>'; 
                            }else{
                                $carrousel_videos .= '<img src="'. $video->media. '" width="100%"  class="responsive video" style="max-height:480px;"/>';
                            }
                            $carrousel_videos .= '<div class="small-12 medium-12 large-12"><a href="#" class="share right"></a></div>';
                        $carrousel_videos .= '</div>';
                    $carrousel_videos .= '</div>';
                $carrousel_videos .= '</div>';    
                $carrousel_videos .= '</li>';
            }
            
        }
    }

    function buildBeneficios($result=array()){
        global $title_beneficios, $text_beneficios, $photo_beneficios;
        $status = $result->status;
        if($status == 0){
            $info = $result->info[0];
            $title_beneficios = $info->titulo;
            $text_beneficios = $info->texto;
            $photo_beneficios = $info->imagen;

        }
    }

    function buildPuntos($result=array()){
        global $title_puntos, $text_puntos, $photo_puntos;
        $status = $result->status;
        if($status == 0){
            $info = $result->info[0];
            $title_puntos = $info->titulo;
            $text_puntos = $info->texto;
            $photo_puntos = $info->imagen;

        }
    }



    function buildPreguntasFrecuentes($result=array()){
        global $preguntas_frecuentes;
        $status = $result->status;

        if($status == 0){
            $preguntas = $result->faq;

            for($i=0; $i<count($preguntas); $i++){
                $pregunta = $preguntas[$i];

                $number = $i+1;
                $preguntas_frecuentes .= '
                        <li class="large-6 small-12 left" style="margin-top:40px;">
                            <div class="number large-1 left">' . $number  . '.</div>
                                <div class="large-11 left fatTitle">' . $pregunta->pregunta . '</div>
                            <div class="large-11 left facContent">' . $pregunta->respuesta . '</div>
                        </li>';
            }

        }
    }

?>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Club BI| Inicio</title>
        <link rel="stylesheet" href="css/general.css" />
        <link rel="stylesheet" href="css/foundation.css" />
        <link rel="stylesheet" href="css/header.css" />
        <link rel="stylesheet" href="css/footer.css" />
        <link rel="stylesheet" href="css/orbit-override.css" />
        <link rel="stylesheet" href="css/font-raleway.css" />
        <link rel="stylesheet" href="css/info_bi.css" />
        <link rel="stylesheet" href="css/pagination/jPages.css" />
        <link rel="stylesheet" href="css/pagination/animate.css" />
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
            button {
                background: #ffb03d;
                border-bottom: 3px #ed6a00 solid;
                border-radius: 6px;
                color: #fff;
                display: block;
                font-family: "Raleway";
                font-size: 0.90em;
                font-weight: 700;
                margin-bottom: 25px;
                padding: 12px 5px 12px 5px;
                text-align: center;
                text-decoration: none;
                text-transform: uppercase;
                width: 100%;
                background-image: url('media/images/header.jpg');
                background-position-y: 135px;
                background-position-x: -155px;
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
            <ul class="example-orbit" data-orbit>
            <?= $carrousel_videos  ?>
        </ul>
            
        <div id="content">
            <div class="row">
                <div class="large-12 medium-12 small-12 columns">          
                    <div class="large-6 medium-6 small-12 columns">
                        <h2 class="SubTitle"><!--INFORMACION SOBRE BENEFICIOS--><?=$title_beneficios?></h2>
                        <div class="infoContent">
                            <p><?=$text_beneficios?></p>
                            <!--
                            <p>Club Bi es el programa de lealtad de Corporación Bi, el cual brinda a sus tarjetahabientes beneficios y descuentos instantáneos con solo presentar su tarjeta Club Bi en todos los establecimientos afiliados, indistintamente del medio de pago que utilice. Club Bi es un programa abierto, por lo que no es necesario tener productos con Corporación Bi para poder obtener la tarjeta y disfrutar de todos los beneficios que el programa ofrece.
                            </p><p>Club Bi es el programa más grande Guatemala, ya que posee la mayor cantidad de tarjetahabientes, más establecimientos afiliados y los mejores beneficios siempre. Club Bi es además un programa dinámico, que mantiene sus beneficios en constante renovación con el objetivo de darle a sus tarjetahabientes siempre las mejores y más innovadoras promociones en la más amplia gama de establecimientos</p>
                            -->

                        </div>
                    </div>
                    <div class="large-6 medium-6 small-12 columns" style=" margin-top: 10px;">
                        <img src="<?=$photo_beneficios?>"> <!--media/images/img_infoclubbi.jpg-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="large-12 medium-12 small-12 columns">          
                    <div class="large-6 medium-6 small-12 columns">
                        <h2 class="SubTitle"><!--INFORMACION SOBRE PUNTOS--><?=$title_puntos?></h2>
                        <div class="infoContent">
                            <p><?=$text_puntos?></p>
                            <!--
                            <p>Club Bi es el programa de lealtad de Corporación Bi, el cual brinda a sus tarjetahabientes beneficios y descuentos instantáneos con solo presentar su tarjeta Club Bi en todos los establecimientos afiliados, indistintamente del medio de pago que utilice. Club Bi es un programa abierto, por lo que no es necesario tener productos con Corporación Bi para poder obtener la tarjeta y disfrutar de todos los beneficios que el programa ofrece.
                            </p><p>Club Bi es el programa más grande Guatemala, ya que posee la mayor cantidad de tarjetahabientes, más establecimientos afiliados y los mejores beneficios siempre. Club Bi es además un programa dinámico, que mantiene sus beneficios en constante renovación con el objetivo de darle a sus tarjetahabientes siempre las mejores y más innovadoras promociones en la más amplia gama de establecimientos</p>
                            -->
                        </div>
                    </div>
                    <div class="large-6 medium-6 small-12 columns" style=" margin-top: 10px;">
                        <img src="<?=$photo_puntos?>"> <!--media/images/img_infoclubbi.jpg-->
                    </div>
                </div>
            </div>
            <br/>
            <br/>
            <div class="row">
                <div class="large-5 medium-5 small-8  large-centered medium-centered small-centered columns"> 
                    <button class="btn_adquirir" style="text-transform: none;" onClick="window.location.href='./como_obtener.php'">Adquiere tu Club BI</button> 
                </div>
            </div>
            <div class="row faq">
                <div class="large-12 medium-12 small-12  large-centered medium-centered small-centered columns faq">
                    
                        <div class="large-12 medium-12 small-12 columns">
                            <label class="title">PREGUNTAS FRECUENTES</label>
                        </div>
                        <br/>
                        <br/>
                        
                            <ul class="large-12 medium-12 small-12 columns" id="itemContainer">

                                <?=$preguntas_frecuentes?>

                                
                                <!--
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">1.</div>
                                    <div class="large-11 left fatTitle">¿Tengo que ser cliente de BI para poder tener club Bi?</div>
                                    <div class="large-11 left facContent">No, solo debes solicitarla en cualquier agencia.</div>
                                </li>
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">2.</div>
                                    <div class="large-11 left fatTitle">¿Puedo tener tarjeta club Bi adicional?</div>
                                    <div class="large-11 left facContent">No, la tarjeta Club Bi es individual y no se puede tener adicionales; sin embargo cada persona puede tramitar la suya sin necesidad de tener productos en Corporacion Bi.</div>
                                </li>
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">3.</div>
                                    <div class="large-11 left fatTitle">¿TIENE ALGUN COSTO CLUB BI?</div>
                                    <div class="large-11 left facContent">No, es totalmente gratis.</div>
                                </li>  
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">4.</div>
                                    <div class="large-11 left fatTitle">¿Acumulo bi puntos al presentar clib bi en los establecimientos afiliados?</div>
                                    <div class="large-11 left facContent">No, los Bi Puntos se acumulan al pagar su consumo con tarjetas de debito y credito Visa y por los saldos acumulados en tu Sper Cuenta de Ahorros, Super cuenta de depositos monetarios y cuenta de Ahorro 5 Estrellas.</div>
                                </li>
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">5.</div>
                                    <div class="large-11 left fatTitle">¿Tengo que ser cliente de BI para poder tener club Bi?</div>
                                    <div class="large-11 left facContent">No, solo debes solicitarla en cualquier agencia.</div>
                                </li>
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">6.</div>
                                    <div class="large-11 left fatTitle">¿Puedo tener tarjeta club Bi adicional?</div>
                                    <div class="large-11 left facContent">No, la tarjeta Club Bi es individual y no se puede tener adicionales; sin embargo cada persona puede tramitar la suya sin necesidad de tener productos en Corporacion Bi.</div>
                                </li>
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">7.</div>
                                    <div class="large-11 left fatTitle">¿TIENE ALGUN COSTO CLUB BI?</div>
                                    <div class="large-11 left facContent">No, es totalmente gratis.</div>
                                </li>  
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">8.</div>
                                    <div class="large-11 left fatTitle">¿Acumulo bi puntos al presentar clib bi en los establecimientos afiliados?</div>
                                    <div class="large-11 left facContent">No, los Bi Puntos se acumulan al pagar su consumo con tarjetas de debito y credito Visa y por los saldos acumulados en tu Sper Cuenta de Ahorros, Super cuenta de depositos monetarios y cuenta de Ahorro 5 Estrellas.</div>
                                </li>
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">9.</div>
                                    <div class="large-11 left fatTitle">¿Tengo que ser cliente de BI para poder tener club Bi?</div>
                                    <div class="large-11 left facContent">No, solo debes solicitarla en cualquier agencia.</div>
                                </li>
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">10.</div>
                                    <div class="large-11 left fatTitle">¿Puedo tener tarjeta club Bi adicional?</div>
                                    <div class="large-11 left facContent">No, la tarjeta Club Bi es individual y no se puede tener adicionales; sin embargo cada persona puede tramitar la suya sin necesidad de tener productos en Corporacion Bi.</div>
                                </li>
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">11.</div>
                                    <div class="large-11 left fatTitle">¿TIENE ALGUN COSTO CLUB BI?</div>
                                    <div class="large-11 left facContent">No, es totalmente gratis.</div>
                                </li>  
                                <li class="large-6 small-12 left" style="margin-top:40px;">
                                 <div class="number large-1 left">12.</div>
                                    <div class="large-11 left fatTitle">¿Acumulo bi puntos al presentar clib bi en los establecimientos afiliados?</div>
                                    <div class="large-11 left facContent">No, los Bi Puntos se acumulan al pagar su consumo con tarjetas de debito y credito Visa y por los saldos acumulados en tu Sper Cuenta de Ahorros, Super cuenta de depositos monetarios y cuenta de Ahorro 5 Estrellas.</div>
                                </li>

                                -->

                            </ul>                           
                        </div>
                        <br/>
                        <br/>
                        
                        
                        <div class="large-12 medium-12 small-12 pagination-centered" style="margin-top: 35px;">
<!--                            <div class="pagination-centered" id="paginador">
                            <ul class="pagination">
                                <li><a href="">1</a></li>
                                <li><a href="">2</a></li>
                                <li><a href="">3</a></li>
                                <li><a href="">4</a></li>
                                <li><a href="">5</a></li>
                                <li><a href="">6</a></li>
                            </ul>
                            </div>-->
                            <div class="holder inverse"></div>
                        </div>
                        
                    </div>
                
            </div>
            <br/>
            <br/>
               <?php include 'footer.php'; ?>
            <?php include 'popups.php'; ?>
        </div>
        <script src="js/vendor/jquery.js"></script>
        <script src="js/foundation.min.js"></script>
        <script src="js/menuDesktop.js"></script>
        <script src="js/jPages.js"></script>
        <script>
            $(document).foundation();
            $(function(){

  $("div.holder").jPages({
    containerID : "itemContainer",
    animation : "bounceInUp",
    perPage:4,
    next:"→",
    previous:"←"   
  });

});
        </script>
    </body>
</html>  