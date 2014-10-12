<?php
include_once 'services.php';
//$beneficios = get($wsServer . 'listaBeneficio');
//$destacados = get($wsServer . 'beneficioDestacado');
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <?php include 'meta_data.php'; ?>
        <title>Club BI| Inicio</title>
        <link rel="stylesheet" href="css/foundation.css" />
        <link rel="stylesheet" href="css/header.css" />
        <link rel="stylesheet" href="css/footer.css" />
        <link rel="stylesheet" href="css/orbit-override.css" />
        <link rel="stylesheet" href="css/font-raleway.css" />
        <link rel="stylesheet" href="css/general.css" />
        <link rel="stylesheet" href="css/beneficios.css" />
        <link rel="stylesheet" href="css/pagination/jPages.css" />
        <link rel="stylesheet" href="css/pagination/animate.css" 
        <script src="js/vendor/modernizr.js"></script>
        <style type="text/css" media="screen">
  .btn {
    display: block;
    padding: 2px 5px 2px 20px;
    background: url('https://twitter.com/favicons/favicon.ico') 1px center no-repeat;
    border: 1px solid #ccc;
  }
</style>
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
            .logogallery{
                width: 55px;
                position: absolute;
                margin: 5px;
            }
        </style>

    </head>
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
    <script id="gall_tmpl" type="text/x-jquery-tmpl">
        <li>
        <div class="small-12 medium-12 large-12 columns">
        <div class="row">
        <div class="small-6 medium-6 large-6 columns nopaddingright">
        <div  style="background: url('${imgbeneficio}');" class="imgcarrousell"></div>
        </div>
        <div class="small-6 medium-6 large-6 columns legend nopaddingleft">
        <div class=" small-6 medium-6 large-6 columns logo nopaddingleft nopaddingright">
        <img src="media/images/logo_top_margin.jpg"/>
        <img src="${imgempresa}"/>
        <img src="media/images/abajo.png"/>
        </div>
        <div class="left small-6 medium-6 large-6 info columns">
        <div class="small-12 medium-12 large-12 columns  titulo">
        ${textobeneficio}
        </div>
        <div class="small-12 medium-12 large-12 columns categoria">
        Titulo: ${categoria}
        </div>
        <div class="small-12 medium-12 large-12 columns caducidad">
        Vigencia: ${vigencia}
        </div>

        </div>
        </div>
        </div>
        <div class="row" style="height: 0px;">
        <div class=" right share">
        <div class="small-6 medium-6 large-6 columns nopadding">
        <a class="right" href="javascript:modalShared('detalleBeneficio.php?beneficio=${idbeneficio}');"><img src="media/images/btn_share.png"></a></div>
        <div class="small-6 medium-6 large-6 columns nopadding">
        <a class="right" href="javascript:rating('${idbeneficio}');"><img src="media/images/btn_share.png"></a></div>
        </div>
        </div>
        </div>

        </li>
    </script>
    <body>
        <?php include 'header.php'; ?>
        <ul class="example-orbit" id="galleryBenefits" data-orbit data-options="bullets:true;">
            
        </ul>
        <div class="blueLine"></div>  
        <div>

        </div>
        <div class="row">
            <div class="large-6 medium-6 small-12 columns">
                <h2>Guia Beneficios</h2>
            </div>
            <div class="large-6 medium-6 small-12 columns hide-for-small">
                <label class="numberBeneficios" id="contadorBeneficios"></label>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="large-6 medium-6 small-11 small-centered columns left">
                <input type="text" class="searchBeneficio" placeholder="Buscar:" name="buscar" >
                <img src="media/images/btn_search.jpg" id="btn_search"/>

            </div>
            <div class="large-6 medium-6 small-11 hide-for-small columns">
                <div class="large-2 medium-2 right">
                    <div class="filtroNumberBeneficio Todos"><a href="javascript:allItems();">Todos</a></div>
                </div>
                <div class="large-2 medium-2 right">
                    <div class="filtroNumberBeneficio num36"><a href="javascript:itemsByPage(12);">36</a></div>
                </div>
                <div class="large-2 medium-2 right">
                    <div class="filtroNumberBeneficio num9"><a href="javascript:itemsByPage(3);">9</a></div>
                </div>
            </div>

        </div>
        <br/>
        <!--        <div class="row category">
                    <div class="large-3 medium-3 small-12 left columns category">
                        Ubicación
                    </div>
                    <div class="large-3 medium-3 small-12 left columns category">
                        Categoría
                    </div>
                    <div class="large-3 medium-3 small-12 left columns category">
                        <a href="javascript:sortBenefits(1,1);">Nuevo</a>
                    </div>
                    <div class="large-3 medium-3 small-12 left columns category">
                        <a href="javascript:sortBenefits(3,1);">Votado</a>
                    </div>
                </div>-->
        <div class="row parentSelectMenu">
            <div class="large-12 medium-12 small-12 left category">
                <ul >
                    <li class="large-3 medium-3 small-12 left hasChildren" id="ubicaciones">Ubicación</li>               
                    <li class="large-3 medium-3 small-12 left hasChildren" id="categorias">Categoría</li>
                    <li class="large-3 medium-3 small-12 left"><a href="javascript:sortBenefits(3,1);">Nuevo</a></li>
                    <li class="large-3 medium-3 small-12 left"><a href="javascript:sortBenefits(1,1);">Votado</a></li>
                </ul> 
            </div>
        </div>
        <div class="row selectMenu" id="selectMenu" style="display:none;">
            <div class="large-12 medium-12 small-12 left">
                <ul id="categoryContainer" style="list-style:none;">
                </ul> 
            </div>
        </div>
        <br/>
        <ul id="itemContainer" sytle="list">
            <li class="row">
                <div class="large-12 medium-12 small-12 columns">
                    <div class="large-4 medium-4 small-12  columns paddingfix">
                        <a href="detalleBeneficio.php?beneficio=1"><img src="<?=$wsServerJS?>/uploads/beneficioImages/sala-cine.jpg" class="responsive galleryfix"></a>
                        <div class="small-12 medium-12 large-12 cintainside">
                            2 entradas por Q40.</div>
                    </div>

                    <div class="large-4 medium-4 small-12  columns paddingfix">
                        <a href="detalleBeneficio.php?beneficio=2"><img src="<?=$wsServerJS?>/uploads/beneficioImages/sala-cine.jpg" class="responsive galleryfix"></a>
                        <div class="small-12 medium-12 large-12 cintainside">
                            Beneficio 2 </div>
                    </div>

                    <div class="large-4 medium-4 small-12  columns paddingfix">
                        <a href="detalleBeneficio.php?beneficio=13"><img src="<?=$wsServerJS?>/uploads/beneficioImages/sala-cine.jpg" class="responsive galleryfix"></a>
                        <div class="small-12 medium-12 large-12 cintainside">
                            Beneficio de prueba</div>
                    </div>

                </div>
            </li>

        </ul>
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
        <script src="js/menuDesktop.js"></script>
        <script src="js/jPages.js"></script>
        <script src="js/jquery.tmpl.min.js"></script>
        <script type="text/javascript">
            var beneficio = 1;
            $(function() {
            loadBenefits();
            loadGallery();
            $(".basic").jRating({
         step:false,
         canRateAgain : true,
         rateMax:5,
         length : 5, // nb of stars
         onClick : function(element,rate) {
         ratingBeneficio(beneficio,rate);
        }
       });
        
            $('#ubicaciones').click(function(){
            setTimeout(function () { 
            addZonas();
            } , 500);
            
            });

            $('#categorias').click(function(){
            setTimeout(function () { 
            addCategories();
            } , 500);
            });

            $('#btn_search').click(function(){
            searchBeneficio();
            });
            });
            
            
            
        </script>
        <script src="js/beneficiosGallery.js"></script>
        <script type="text/javascript" src="js/jRating.jquery.js"></script>
    </body>
</html>  