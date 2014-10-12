function uploadAjax(){

var inputFileImage = document.getElementById(“archivoImage”);

var file = inputFileImage.files[0];

var data = new FormData();

data.append(‘archivo’,file);

var url = “upload.php”;

$.ajax({

url:url,

type:’POST’,

contentType:false,

data:data,

processData:false,

cache:false});

}




