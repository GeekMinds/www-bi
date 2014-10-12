

<html>
    <?php
    include_once './settings.php';
    $htmlEstablecimientos = "";

    try {

        $url = $wsServer . '/promocionespeciales?tipo=bipuntos';

        $data = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        buildPromocionesEspeciales(json_decode($result));
    } catch (Exception $e) {
        
    }

    function buildPromocionesEspeciales($result = array()) {
        global $htmlEstablecimientos;
        $status = $result->status;
        if ($status == 0) {
            $promociones = $result->bipuntos;
            $counter = 0;

            for ($i = 0; $i < count($promociones); $i++) {
                $promocion = $promociones[$i];

                if ($counter == 0) {
                    $htmlEstablecimientos .= '<li class="large-12 medium-12 small-12 columns" style="margin-bottom: 40px;">';
                }

                $htmlEstablecimientos .= "
                    <div class=\"large-6 medium-6 small-12 columns\"> 
                        <img onclick=\"modalImagen('" . $promocion->fondo . "')\" class=\"responsive\" src=\"" . $promocion->fondo . "\" />
                    </div>";

                if ($counter == 1) {
                    $htmlEstablecimientos .= '</li>';
                }

                $counter++;

                if ($counter == 2) {
                    $counter = 0;
                }
            }

            if ($counter != 0) {
                $htmlEstablecimientos .= '</li>';
            }
        }
    }
    ?>
    <head>
        <meta charset="utf-8" />
        <?php include 'meta_data.php'; ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Club BI| Inicio</title>
        <link rel="stylesheet" href="css/foundation.css" />
        <link rel="stylesheet" href="css/header.css" />
        <link rel="stylesheet" href="css/footer.css" />
        <link rel="stylesheet" href="css/font-raleway.css" />
        <link rel="stylesheet" href="css/general.css" />
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
            button:hover, button:focus, .button:hover, .button:focus {
                background: #ff9d0c;
            }
            .btninside {
                margin-top: -48px;
            }
            .cintainsidegallery {
                background-color: #2b5aa8;
                font-family: "Raleway";
                color: #fff;
                text-align: right;
                margin-top: -5rem;
                position: relative;
                padding-top: 10px;
                padding-bottom: 10px;
                text-transform: uppercase;
                font-size: 0.8rem;
                padding-right: 5px;
            }
            li.active.arrow ul li a
            {
                color: #26B4F1;
            }
        </style>
    </head>
    <body>
<?php include 'header.php'; ?>

        <div class="row">
            <div class="large-12 medium-12 small-12 columns">
                <h2>Promociones Especiales</h2>
            </div>
        </div>
        <div class="row withpadding">
            <ul id="itemContainer">
<?= $htmlEstablecimientos ?>
            </ul>

        </div>
        <br/>
        <div class="row">
            <div class="large-12 medium-12 small-12 columns">

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
                    <div class="holder"></div>
                </div>
            </div>
        </div>
<?php include 'footer.php'; ?>
<?php include 'popups.php'; ?>
    </div>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/menuDesktop.js"></script>
    <script src="js/jPages.js"></script>
    <script>
        $(document).foundation();
//        $(function() {
//
//            $("div.holder").jPages({
//                containerID: "itemContainer",
//                perPage: 3,
//                animation: "bounceInUp",
//                next: "→",
//                previous: "←"
//            });
//
//        });
    </script>
</body>
</html>  
