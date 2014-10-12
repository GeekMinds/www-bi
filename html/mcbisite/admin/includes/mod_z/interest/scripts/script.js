$(function(){
		

		
	
    

    }
);

$(document).ready(function() {
 		var module_id =document.getElementById("module_id").value;
		var title_en =document.getElementById("title_en");
		var title_es =document.getElementById("title_es");

		

		var _description =document.getElementById("description");
			
			
});

function addYoutubeLink(){
var warning2=document.getElementById("warning2");
var video=document.getElementById("video").value;
if(video==''){

warning2.innerHTML='Debe coloar un link de youtube';

}else{
warning2.innerHTML='';
idvideo=youtube_parser(video);
if(!idvideo){

warning2.innerHTML='El link es inválido. Debe ser un link Youtube';
}else{
//Se procede a guardar el id
//warning2.innerHTML=idvideo;
params={};
var data={};
data.url_media=idvideo;
data.type='2';
data.mod_m = module_id;

params.action='addmodulemediamphoto';
params.data=data;
 $.post('../../service/module_m.php', params, function(data) {



  }, "json");
}

}




function youtube_parser(url){
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[7].length==11){
        return match[7];
    }else{
      return false;
    }
}

}
















function buttonPrueba(){
	alert("click");
}

