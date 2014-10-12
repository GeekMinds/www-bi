<?php
    include_once './settings.php';
    
?>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Club BI| ¿Cómo Obtener?</title>
        
        <link rel="stylesheet" href="css/foundation.css" />
        <link rel="stylesheet" href="css/general.css" />
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
        <div class="row">
        <div class="large-6 medium-6 small-12 columns">
            <h2>¿Como Obtener mi Club Bi?</h2>
        </div>
        </div>
        <div class="row">
            <div class="large-12 medium-12 small-12 columns" style="text-align: center;">
                <img src="<?=$wsServerJS?>/contenido?tipo=infografia" >
            </div>  
        </div>  
        <br>
          
               <?php include 'footer.php'; ?>
               <?php include 'popups.php'; ?>
        <script src="js/vendor/jquery.js"></script>
        <script src="js/foundation.min.js"></script>
        <script src="js/menuDesktop.js"></script>
        <script src="js/jPages.js"></script>
        <script>
            $(document).foundation();
            $(function(){



            });
        </script>
    </body>
</html>  