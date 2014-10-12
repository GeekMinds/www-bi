<?php 
include_once 'settings.php';
$carrousel_videos = "";
$left_image="";
$right_image="";
$bottom_image = "";
 try {

        $url = $wsServer . '/contenido?tipo=media&id=home';

        $data = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
		
		
        buildVideoCarrousel(json_decode($result));

  }catch(Exception $e) {
  }
  try {

        $url = $wsServer . '/contenido?tipo=index&id=left';

        $data = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        buildLeftImage(json_decode($result));

  }catch(Exception $e) {
  }
  try {

        $url = $wsServer . '/contenido?tipo=index&id=right';

        $data = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        buildRightImage(json_decode($result));

  }catch(Exception $e) {
  }
  try {

        $url = $wsServer . '/contenido?tipo=index&id=bottom';

        $data = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        buildBottomImage(json_decode($result));

  }catch(Exception $e) {
  }
function buildBottomImage($result=array()){
      global $bottom_image;
        $status = $result->status;
        if($status == 0){
          $images = $result->media;
          for($i=0; $i<count($images); $i++){
             $image = $images[$i];
            $bottom_image = '<img src="'.$image->fondo.'" class="responsive"/>'; 
          }
        }
  }
function buildRightImage($result=array()){
      global $right_image;
        $status = $result->status;
        if($status == 0){
          $images = $result->media;
          for($i=0; $i<count($images); $i++){
             $image = $images[$i];
            $right_image = '<img src="'.$image->fondo.'" class="responsive"/>'; 
          }
        }
  }
  function buildLeftImage($result=array()){
      global $left_image;
        $status = $result->status;
        if($status == 0){
          $images = $result->media;
          for($i=0; $i<count($images); $i++){
             $image = $images[$i];
            $left_image = '<img src="'.$image->fondo.'" class="responsive"/>'; 
          }
        }
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
                            //Codicionales de Video e Imagen
                            $tipo = $video->tipo;
                            if( $tipo->id ==2){
                               $carrousel_videos .= '<iframe width="100%" height="480" src="//' . $video->media . '" frameborder="0" allowfullscreen class="video"></iframe>'; 
                            }else{
                                $carrousel_videos .= '<img src="'. $video->media. '" width="100%"  class="video" style="max-height:480px;"/>';
                            }
                            
                            
                            $carrousel_videos .= '<div class="small-12 medium-12 large-12"><a href="#" class="share right"></a></div>';
                        $carrousel_videos .= '</div>';
                    $carrousel_videos .= '</div>';
                    $carrousel_videos .= '</div>';
                $carrousel_videos .= '</li>';
            }
            
        }
    }


?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <?php include 'meta_data.php'; ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Club BI| Inicio</title>
        <link rel="stylesheet" href="css/foundation.css" />
        <link rel="stylesheet" href="css/header.css" />
        <link rel="stylesheet" href="css/footer.css"/>
        <link rel="stylesheet" href="css/font-raleway.css" />
        <link rel="stylesheet" href="css/orbit-override.css" />
        <link rel="stylesheet" href="css/general.css"/>
        <script src="js/vendor/modernizr.js"></script>
    </head>
    <body>
        <?php include 'header.php'; ?>
        <ul class="example-orbit" data-orbit>
            <?=$carrousel_videos?>
            <!--
            <li>
                <div class="small-12 medium-12 large-12 videogallery">
                    <div class="row">
                        <div class="small-12 medium-8 large-6 medium-offset-2 large-offset-3">
                            <iframe width="100%" height="480" src="//www.youtube.com/embed/PEMW87U912k?autoplay=0&showinfo=0&controls=0" frameborder="0" allowfullscreen class="video"></iframe>
                        </div>
                        <div class="small-12 medium-8 large-6 medium-offset-2 large-offset-3">
                             <a href="#" class="share right"></a>
                        </div>
                    </div>
                </div>
            </li>
            <li>

                <div class="small-12 medium-12 large-12 videogallery">
                    <div class="row">
                        <div class="small-12 medium-8 large-6 medium-offset-2 large-offset-3">
                            <iframe width="100%" height="480" src="//www.youtube.com/embed/PEMW87U912k?autoplay=0&showinfo=0&controls=0" frameborder="0" allowfullscreen class="video"></iframe>
                           
                        </div>
                        <div class="small-12 medium-8 large-6 medium-offset-2 large-offset-3">
                             <a href="#" class="share right"></a>
                        </div>
                    </div>
                </div>
            </li>
            -->
        </ul>
        <div class="row">
            <div class="large-12 medium-12 small-12 columns">
                <h2>¿Qué puedo hacer con mi Club BI?</h2>
            </div>
        </div>
        <div class="row withpadding">
            <div class="large-6 medium-6 small-12 columns paddingfix">
                <?= $left_image ?>
                <div class="small-12 medium-12 large-12 cintainside">
                    Acumula puntos y canjealos por increibles premios
                </div>
                <div class="large-6 medium-8 small-12 large-offset-3 medium-offset-2 columns btninside">
                    <button onClick="window.location.href='./catalogoPremios.php'">Ir a Puntos</button>
                </div>


            </div>
            <div class="large-6 medium-6 small-12  columns paddingfix">
                <?= $right_image ?>
                <div class="small-12 medium-12 large-12 cintainside">
                    Obtén beneficios especiales
                </div>
                <div class="large-6 medium-8 small-12 large-offset-3 medium-offset-2 columns btninside">
                    <button onClick="window.location.href='./beneficios.php'">Ir a Beneficios</button>
                </div>

            </div>
        </div>
        <div class="row withpadding">
            <div class="large-12 medium-12 small-12 columns">
                <?= $bottom_image ?>
            </div>
        </div>
        <?php include 'footer.php'; ?>
        <?php include 'popups.php'; ?>
        <div id="shareModal" class="reveal-modal small" data-reveal>
            <h2 class="underlined">Compartir en</h2>

        </div>

        <script src="js/vendor/jquery.js"></script>
        <script src="js/foundation.min.js"></script>
        <script src="js/foundation/foundation.topbar.js"></script>
        <script src="js/menuDesktop.js"></script>
        <script>
            $(document).foundation();
        </script>
    </body>
</html>