<?php
include_once 'services.php';
date_default_timezone_set('America/Guatemala');
$idbeneficio = 1;
if (isset($_REQUEST['beneficio'])) {
    $idbeneficio = $_REQUEST['beneficio'];
}
$beneficios = get($wsServer . 'beneficio/' . $idbeneficio);
if ($beneficios["Result"] == false) {
    exit();
}
$beneficios = (array) $beneficios["JSON"];
$beneficio = (array) $beneficios["beneficios"];
$empresa = $beneficio[0]->empresa; //empresas
$imgEmpresa = '';
$categoriaEmpresa = '';
$idCategoria=1;
$nombreCategoria='';
//var_dump($empresa);
if (count($empresa) > 0) {
    $empresa = (array)$empresa[0];
    $imgEmpresa = $empresa["logo"];
    $categoria = (array)$empresa["categoria"]; 
    
    if(count($categoria)>0){
        $categoria = (array)$categoria[0];
        $idCategoria = $categoria["id"];
        $nombreCategoria = $categoria["nombre"];
        
    }
    
}
?>

<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Club BI| Inicio</title>
        <link rel="stylesheet" href="css/foundation.css" />
        <link rel="stylesheet" href="css/header.css" />
        <link rel="stylesheet" href="css/footer.css"/>
        <link rel="stylesheet" href="css/font-raleway.css" />
        <link rel="stylesheet" href="css/orbit-override.css" />
        <link rel="stylesheet" href="css/general.css"/>
        <link rel="stylesheet" href="css/pagination/jPages.css" />
        <link rel="stylesheet" href="css/pagination/animate.css" />
        <script src="js/vendor/modernizr.js"></script>
        <style>
            .bg {
                background: #ffb03d;
                color: #fff;
                padding-top: 10px;
                text-align: center;
                padding-bottom: 10px;
                font-family: "Raleway";
            }
            .arrow-left-color{
                border-left: 18px solid transparent;
                border-right: 12px solid #ffb03d;
                border-top: 18px solid transparent;
                border-bottom: 18px solid transparent;
                margin: 0px;
                padding: 0px;
            }
            .arrow-left-white{
                border-left: 18px solid #ffb03d;
                border-right: 12px solid transparent;
                border-top: 18px solid #ffb03d;
                border-bottom: 18px solid #ffb03d;
                margin: 0px;
                padding: 0px;
            }
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
            .fixpadding{
                padding-left: 3px;
                margin-right: 3px;
            }
            .legend{
                padding-left: 0px;
                padding-top: 9%;
            }
            .legend .logo{
                max-width: 127px;
            }
            .legend .info{
            }
            .nopaddingright{
                padding-right: 0px;
            }
            .nopaddingleft{
                padding-left: 0px;
            }
            .info .titulo{
                padding-left: 0px;
                font-family: "Raleway bold";
                color:#2b5aa8;
            }
            .info .categoria{
                padding-left: 0px;
                font-family: "Raleway";
                color:#2b5aa8;
            }
            .info .caducidad{
                padding-left: 0px;
                font-family: "Raleway";
                color:#2b5aa8;
            }

            @media only screen {
                .info .titulo{
                    font-size: 15px;
                }
                .info .categoria{
                    font-size: 10px;
                }
                .info .caducidad{
                    font-size: 10px;
                }
                .legend {
                    padding-top: 0%;
                }
                .orbit-container .orbit-slides-container{
                    margin-bottom: 0px;
                }
                .share{
                    width: 65px;
                    margin-top: -27px;

                }
                .imgcarrousell{
                    -webkit-background-size: cover !important;
                    -moz-background-size: cover !important;
                    -o-background-size: cover !important;
                    background-size: cover !important;
                    width:100%;
                    height:230px;
                }

            }

            @media only screen and (min-width: 40.063em) {
                .info .titulo{
                    font-size: 20px;
                }
                .info .categoria{
                    font-size: 15px;
                }
                .info .caducidad{
                    font-size: 15px;
                }
                .legend {
                    padding-top: 6%;
                }
                .orbit-container .orbit-slides-container{
                    margin-bottom: 25px;
                }
                .share{
                    width: 73px;
                    margin-top: -38px;
                }
                .imgcarrousell{
                    -webkit-background-size: cover !important;
                    -moz-background-size: cover !important;
                    -o-background-size: cover !important;
                    background-size: cover !important;
                    width:100%;
                    height:275px;
                }
            }

            @media only screen and (min-width: 64.063em) {
                .info .titulo{
                    font-size: 25px;
                }
                .info .categoria{
                    font-size: 20px;
                }
                .info .caducidad{
                    font-size: 20px;
                }
                .legend {
                    padding-top: 11%;
                }
                .orbit-container .orbit-slides-container{
                    margin-bottom:25px;
                }
                .share{
                    width: 95px;
                    margin-top: -47px;
                }
                .imgcarrousell{
                    -webkit-background-size: cover !important;
                    -moz-background-size: cover !important;
                    -o-background-size: cover !important;
                    background-size: cover !important;
                    width:100%;
                    height:485px;
                }
            }
            .nopadding{
                padding: 0;
            }

            .text-banner{
                background-color: #2B5AA8;
                color: #FFF;
                margin-top: 48%;
                position: absolute;
                z-index: 3;
                height: 60px;
                width: 100%;
                text-align: right;
            }
            ul#itemContainer {
                list-style: none;
            }
            .cintainsidegallery {
                background-color: #2b5aa8;
                font-family: "Raleway Bold";
                color: #fff;
                text-align: right;
                margin-top: -3rem;
                position: relative;
                padding-top: 0px;
                padding-bottom: 0px;
                text-transform: uppercase;
                font-size: 0.9rem;
                padding-right: 5px;
            }
            p.vigencia {
                font-family: "Raleway";
                font-size: 0.6rem;
                text-align: right;
                font-size: 0.6rem;
            }
            .galleryfix{
                height: 230px;
            }
            li.active.arrow ul li a
            {
                color: #26B4F1;
            }
            .logogallery{
                width: 55px;
                position: absolute;
                margin: 5px;
            }
        </style>
        <script id="bene_tmpl" type="text/x-jquery-tmpl">
            <div class="large-4 medium-4 small-12  columns paddingfix left" onclick="self.location='detalleBeneficio.php?beneficio=${idbeneficio}'">
            <div class="logogallery">
            <img src="media/images/logo_top_margin.jpg" class="left">
            <img src="${imgempresa}" class="left">
            <img src="media/images/abajo.png" class="left">
            </div>
            <img src="${imgbeneficio}" class="responsive galleryfix">
            <div class="small-12 medium-12 large-12 cintainsidegallery">${textobeneficio}<p class="vigencia">VIGENCIA: ${vigencia}</p></div>
            </div>
        </script>
</head>
<body>
    <?php include 'header.php'; ?>
    <ul class="example-orbit" data-orbit>
        <li>
            <div class="small-12 medium-12 large-12 columns">
                <div class="row">
                    <div class="small-6 medium-6 large-6 columns nopaddingright">
                        <div  style="background: url('<?= $beneficio[0]->imagen ?>');" class="imgcarrousell"></div>
                    </div>
                    <div class="small-6 medium-6 large-6 columns legend nopaddingleft">
                        <div class=" small-6 medium-6 large-6 columns logo nopaddingleft nopaddingright">
                            <img src="media/images/logo_top_margin.jpg"/>
                            <img src=" <?= $imgEmpresa ?>"/>
                            <img src="media/images/abajo.png"/>
                        </div>
                        <div class="left small-6 medium-6 large-6 info columns">
                            <div class="small-12 medium-12 large-12 columns  titulo">
                                <?= $beneficio[0]->nombre ?>
                            </div>
                            <div class="small-12 medium-12 large-12 columns categoria">
                                Titulo: <?=$nombreCategoria ?>
                            </div>
                            <div class="small-12 medium-12 large-12 columns caducidad">
                                Vigencia: <?= date("d/m/Y", strtotime($beneficio[0]->fecha_vencimiento)) ?>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row" style="height: 0px;">
                    <div class=" right share">
                        <div class="small-6 medium-6 large-6 columns nopadding">
                            <a class="right" href="#"><img src="media/images/btn_share.png"></a></div>
                        <div class="small-6 medium-6 large-6 columns nopadding">
                            <a class="right" href="#"><img src="media/images/btn_share.png"></a></div>
                    </div>
                </div>
            </div>

        </li>
    </ul>
    <div class="blueLine"></div>
    <div class="row withpadding">
        <div class="small-12 medium-8 large-5 columns right " onclick="javascript:returnGallery();">
            <div class="small-1 medium-1 large-1 columns arrow-left-color"></div>
            <div class="small-10 medium-10 large-10 columns bg">
                Regresar al catalogo de Beneficios
            </div>
            <div class="small-1 medium-1 large-1 columns arrow-left-white"></div>
        </div>
    </div>
    <div class="row">
        <div class="large-12 medium-12 small-12 columns">          
            <div class="large-12 medium-12 small-12 columns">
                <h2 class="SubTitle">Descripci&oacute;n</h2>
            </div>
            <div class="large-12 medium-12 small-12 columns">
                <div class="infoContent">
                    <p><?= $beneficio[0]->descripcion_web ?></p>
                </div>
            </div>
            <div class="large-12 medium-12 small-12 columns">
                <h2 class="SubTitle">Restricci&oacute;n</h2>
            </div>
            <div class="large-12 medium-12 small-12 columns">
                <div class="infoContent">
                    <p><?= $beneficio[0]->restriccion_web ?></p>

                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="large-12 medium-12 small-12 columns">
            <h2 class="SubTitle">Te podra interesar</h2>
        </div>
        <ul id="itemContainer" sytle="list">

        </ul>

    </div>
    <div class="row">
        <div class="large-12 medium-12 small-12 columns">

            <div class="large-12 medium-12 small-12 pagination-centered" style="margin-top: 35px;">
                <!--            <div class="pagination-centered" id="paginador">
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
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/foundation/foundation.topbar.js"></script>
    <script src="js/menuDesktop.js"></script>
    <script src="js/jPages.js"></script>
    <script src="js/jquery.tmpl.min.js"></script>
    <script>
            $(document).foundation();
            function returnGallery() {
                top.location = 'beneficios.php';
            }
            $(function() {
                loadParecidos(<?= $idCategoria ?>, 9);
            });
            function share(url){
                window.open('https://www.facebook.com/sharer/sharer.php?u=' + url,'facebook-share-dialog','width=626,height=436');
            }
    </script> 
    <script src="js/beneficiosGallery.js"></script>
</body>
</html>
