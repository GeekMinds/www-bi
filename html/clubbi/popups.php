<div id="correcto" class="reveal-modal small" data-reveal>
<!--    <div class="large-12 cerrar" >
        <div class="large-1 right">-->
        <img class="btnCerrarModal" src="media/images/cerrar.jpg" style="cursor: pointer;" />
<!--        </div>
    </div>-->
    <div class="large-12 titulo_mondal">
        <div class="row">
            
            <b class="title_mondal">Correcto</b>
        </div>
    </div>
    <div class="large-11 modal_barra">
        
    </div>
    <div class="row">
        <div class="large-8 menaje large-centered small-12 columns">
            <p class="message">
            </p>   
        </div>
        <div class="large-5 columns large-centered small-12">
            <button class="btnContinuar">Aceptar</button>
                
            </a>
        </div>
    </div>
</div>
<div id="rating" class="reveal-modal small" data-reveal>
    <div class="large-12 cerrar" >
        <div class="large-1 right">
        <img class="btnCerrarModal" src="media/images/cerrar.jpg" style="cursor: pointer;" />
        </div>
    </div>
    <div class="large-12 titulo_mondal">
        <div class="row">
            
            <b class="title_mondal">CALIFICAR</b>
        </div>
    </div>
    <div class="large-11 modal_barra">
        
    </div>
    <div class="row">
        <div class="large-3 menaje large-centered small-12 columns" style="margin-top: 40px;">
            <div class="basic" data-average="5" data-id="1"></div>
        </div>
    </div>
</div>
<div id="shared" class="reveal-modal small" data-reveal>
    <div class="large-12 cerrar" >
        <div class="large-1 right">
        <img class="btnCerrarModal" src="media/images/cerrar.jpg" style="cursor: pointer;" />
        </div>
    </div>
    <div class="large-12 titulo_mondal">
        <div class="row">
            
            <b class="title_mondal">COMPARTIR EN</b>
        </div>
    </div>
    <div class="large-11 modal_barra">
        
    </div>
    <div class="row">
        <div class="large-8 menaje large-centered small-12 columns" style="text-align: center;">
            <div style="display: inline;">   <img class="shared-image fb" src="media/images/fb.png" style="cursor: pointer;"  /></div>
        <div id="twitter-share-section" style="display: inline;"></div>
        </div>
    </div>
</div>
<div id="error" class="reveal-modal small" data-reveal >
    <div class="large-12 cerrar">
        <div class="large-1 right">
        <img class="btnCerrarModal" src="media/images/cerrar.jpg" style="cursor: pointer;"  />
        </div>
    </div>
    <div class="large-12 titulo_mondal">
        <div class="row">
            <b class="title_mondal">Error</b>
        </div>
    </div>
    <div class="large-11 modal_barra">
        
    </div>
    <div class="row">
        <div class="large-8 menaje large-centered small-12 columns">
            <p class="message">
            </p>   
        </div>
        <div class="large-5 columns large-centered small-12">
            <button class="btnContinuar">Aceptar</button>
                
            </a>
        </div>
    </div>
</div>
<div id="image" class="reveal-modal" data-reveal >
    
    <div class="row">
        <div class="content_image" class="large-12 large-centered small-12 small-centered columns" style="text-align: center;"> 
        </div>
    </div>
</div>

<script>
    
    
 
    function successful(titulo,msg){
            $("#correcto .message").html(msg);
            $("#correcto .title_mondal").html(titulo);
            $('#correcto').foundation('reveal', 'open');
                $(".btnContinuar").click(function(){
            $('#correcto').foundation('reveal', 'close');
            });
            $(".btnCerrarModal").click(function(){
                $('#correcto').foundation('reveal', 'close');
            });
            }
            function error(titulo,msg){
            $("#error .message").html(msg);
            $("#error .title_mondal").html(titulo);
            $('#error').foundation('reveal', 'open');
            $(".btnContinuar").click(function(){
                $('#error').foundation('reveal', 'close');
            });
            $(".btnCerrarModal").click(function(){
                $('#error').foundation('reveal', 'close');
            });
            }
            function modalImagen(url){
            $("#image .content_image").html('<img src="'+url+'" /><img class="btnCerrarModal" src="media/images/cerrar.jpg" style="cursor: pointer;"  />');
            $('#image').foundation('reveal', 'open');
            $(".btnCerrarModal").click(function(){
                $('#image').foundation('reveal', 'close');
            });
            }
            function rating(idBeneficio){
                beneficio=idBeneficio;
            $('#rating').foundation('reveal', 'open');
            $(".btnCerrarModal").click(function(){
                $('#rating').foundation('reveal', 'close');
            });
            }
            function modalShared(url){
            var url2 = '<?= $server ?>/'+url;
            $('#shared').foundation('reveal', 'open');
             $('#twitter-share-section').html('&nbsp;'); 
              $('#twitter-share-section').html('<a href="https://twitter.com/share?url='+url2+'"&text=Club%20Bi target="_blank"></a>');
                
            
            $(".btnCerrarModal").click(function(){
                $('#shared').foundation('reveal', 'close');
            });
            $(".shared-image.fb").click(function(){
                share(url);
            });
            }
            function ratingBeneficio(id_beneficio,rating){
                
                var params = {};
                params.Id_Beneficio = id_beneficio;
                params.Rating = rating;
                

                $.ajax({type: 'POST', url: '<?= $wsServer ?>beneficioRating', data:params, crossDomain: true, dataType: 'json'}).done(function(data) {
                    
                    $('#rating').foundation('reveal', 'close');
                    
                });

            }
            function share(url){
                window.open('https://www.facebook.com/sharer/sharer.php?u=' + url,'facebook-share-dialog','width=626,height=436');
            }
    </script>
