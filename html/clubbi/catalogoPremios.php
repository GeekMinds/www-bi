<?php
include_once 'services.php';
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
        <link rel="stylesheet" href="css/catalogoPremios.css"/>
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
            .header{
    background-image: url('media/images/header-bipuntos.jpg');
    background-color: #2B5AA8;
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
                max-width: 130px;
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
            .nopadding{
                padding: 0;
            }
            li.active.arrow ul li a
            {
                color: #26B4F1;
            }
            .galleryfix{
                height: 230px;
            }
            p.vigencia {
                font-family: "Raleway";
                font-size: 0.6rem;
                text-align: right;
                font-size: 0.6rem;
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
            .logogallery{
                width: 55px;
                position: absolute;
                margin: 5px;
            }
            ul#itemContainer {
list-style: none;
}
.nav li.active {
border-bottom: #01268E 2px solid;
}
        </style>
   <script id="bene_tmpl" type="text/x-jquery-tmpl">
        <div class="large-4 medium-4 small-12  columns paddingfix left" onclick="self.location='detallePremio.php?premio=${idbeneficio}'">
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
        <a class="right" href="#"><img src="media/images/btn_share.png"></a></div>
        <div class="small-6 medium-6 large-6 columns nopadding">
        <a class="right" href="#"><img src="media/images/btn_share.png"></a></div>
        </div>
        </div>
        </div>

        </li>
    </script>
    </head>
    <body>
<?php include 'header.php'; ?>
        <ul class="example-orbit" id="galleryBenefits" data-orbit data-options="bullets:true;">
            
        </ul>
    <div class="blueLine"></div>  
    <div class="row">
        <div class="large-6 medium-6 small-12 columns">
            <h2>Guia de Premios</h2>
        </div>
        <div class="large-6 medium-6 small-12 columns hide-for-small">
            <label class="numberBeneficios"id="contadorBeneficios"></label>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="large-6 medium-6 small-11 small-centered columns left">
            <input type="text" class="searchBeneficio" placeholder="Buscar:" name="buscar">
            <img src="media/images/btn_search_blue.jpg" id="btn_search"/>

        </div>
        <div class="large-6 medium-6 small-11 hide-for-small columns">
            <div class="large-2 medium-2 right">
                <div class="filtroNumberBeneficio todos"><a href="javascript:allItems();">Todos</a></div>
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
    <div class="row parentSelectMenu">
            <div class="large-12 medium-12 small-12 left category">
                <ul >           
                    <li class="large-4 medium-4 small-12 left"><a href="biPuntos.php">Consultar Puntos</a></li>
                    <li class="large-4 medium-4 small-12 left hasChildren" id="categorias">Categorias</li>
                    <li class="large-4 medium-4 small-12 left hasChildren" id="rango">Rango de Puntos</li>
                </ul> 
            </div>
        </div>
        <div class="row selectMenu" id="selectMenu" style="display:none;">
            <div class="large-12 medium-12 small-12 left">
                <ul id="categoryContainer" >                    
                </ul> 
            </div>
        </div>
    <br/>
    <div class="row">
        <ul id="itemContainer">           
        </ul>
    </div>
    <div class="row">
        <div class="large-12 medium-12 small-12 columns">

            <div class="large-12 medium-12 small-12 pagination-centered" style="margin-top: 35px;">
<!--                <div class="pagination-centered" id="paginador">
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
        <script src="js/premiosGallery.js"></script>
        <script src="js/jquery.tmpl.min.js"></script>
        <script src="js/jPages.js"></script>
        <script>
            $(document).foundation();
            $(function(){
            loadBenefits();
            loadGallery();
$('#categorias').click(function(){
            setTimeout(function () { 
            addCategories();
            } , 500);
            });
            $('#rango').click(function(){
            setTimeout(function () { 
            addRangos();
            } , 500);
            });
            
            $('#btn_search').click(function(){
            searchBeneficio();
            });
            
});

        </script>
</body>
</html> 