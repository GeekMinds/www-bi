/* 
 * Author: Javier Cifuentes
 */
var arregloEmpresas = {};
var arregloZonas = [];
var arregloCategorias = [];
var ws = 'http://54.200.51.188/wscb/public/empresa';
var wsgallery = 'http://54.200.51.188/wscb/public/establecimientodestacado';
var galleryContainerId = 'itemContainer';
var carrousellContainerId = 'galleryEmpresas';
var _itemsPerPage = 3;
var _allItems = false;
function loadEmpresas(webservice, gallery) {
    if(typeof webservice == 'undefined'){
        webservice = ws;
    }
    $('#' + galleryContainerId).html("");
    getEmpresas(webservice);

}
function getEmpresas(webservice) {
    if(typeof webservice == 'undefined'){
        webservice = ws;
    }
    $.ajax({type: 'GET', url: webservice, crossDomain: true, dataType: 'json'}).done(function(data) {
        arregloEmpresas = data;
        //console.dir(data);
        for (var i = 0; i < data.empresas.length; i++) {
            var empresa = data.empresas[i];
            var categorias = empresa.categoria;
            for (k = 0; k < categorias.length; k++) {
                var categoria = categorias[k];
                addCategoria(categoria.id, categoria.nombre, empresa.id);
            }
            var zonas = empresa.zonas;
            for (var k = 0; k < zonas.length; k++) {
                var zona = zonas[k];
                addZona(zona.zona, empresa.id);
            }
        }
        writeEmpresas(arregloEmpresas);
        $("div.holder").jPages({
            containerID: galleryContainerId,
            perPage: 3,
            animation: "bounceInUp",
            next: "→",
            previous: "←"
        });


    });
}
function writeEmpresas(empresasAEscribir) {
    var htmItem = "";
    var empresasAgregadas = 0;
    for (var i = 0; i < empresasAEscribir.empresas.length; i++) {
        var empresa = empresasAEscribir.empresas[i];
        if (empresasAgregadas == 0) {
            htmItem = '<li class="row">';
        }
        var params = {};
        params.idbeneficio = empresa.id;
        params.imgbeneficio = empresa.logo;
        params.textobeneficio = empresa.nombre;
        htmItem += $('#esta_tmpl').tmpl(params)[0].outerHTML;
        empresasAgregadas++;
        if (empresasAgregadas == 3 || i == empresasAEscribir.empresas.length - 1) {
            empresasAgregadas = 0;
            htmItem += '</li>';
            $('#' + galleryContainerId).append(htmItem);
        }

    }
    $('#contadorEmpresas').text(empresasAEscribir.empresas.length + ' Establecimientos');
}

function addCategoria(id, nombre, empresaID) {
    if (typeof arregloCategorias[id] == 'undefined') {
        var categoria = {};
        categoria.id = id;
        categoria.nombre = nombre;
        var empresas = [];
        empresas.push(empresaID);
        categoria.empresas = empresas;
        arregloCategorias[id] = categoria;
    } else {
        var categoria = arregloCategorias[id];
        var empresas = categoria.empresas;
        var encontrado = false;
        for (var i = 0; i < empresas.length; i++) {
            var empresa = empresas[i];
            if (empresa == empresaID) {
                encontrado = true;
            }
        }
        if (encontrado == false) {
            empresas.push(empresaID);
        }
    }
}

function addZona(nombre, empresaID) {
    var zonaID = zonaExists(nombre);
    if (zonaID == -1) {
        var zona = {};
        zona.nombre = nombre;
        var empresas = [];
        empresas.push(empresaID);
        zona.empresas = empresas;
        arregloZonas.push(zona);
    } else {
        var zona = arregloZonas[zonaID];
        var empresas = zona.empresas;
        var encontrado = false;
        for (var i = 0; i < empresas.length; i++) {
            var empresa = empresas[i];
            if (empresa == empresaID) {
                encontrado = true;
            }
        }
        if (encontrado == false) {
            empresas.push(empresaID);
        }
    }
}
function zonaExists(nombre) {
    for (var i = 0; i < arregloZonas.length; i++) {
        var zona = arregloZonas[i];
        if (zona.nombre == nombre) {
            return i;
        }
    }
    return -1;
}
function itemsByPage(items) {
    _allItems = false;
    _itemsPerPage = items;
    try {
        $("div.holder").jPages("destroy").jPages({
            containerID: galleryContainerId,
            perPage: items,
            animation: "bounceInUp",
            next: "→",
            previous: "←"
        });
    } catch (e) {
        $("div.holder").jPages({
            containerID: galleryContainerId,
            perPage: items,
            animation: "bounceInUp",
            next: "→",
            previous: "←"
        });
    }
}

function sortEmpresas(by, category) {
    switch (by) {
        case 0://by category
            var categoria = arregloCategorias[category];
            var arreglo = {};
            var arreglo_empresa = [];
            for (var i = 0; i < categoria.empresas.length; i++) {
                var empresaABuscar = categoria.empresas[i];
                for (var j = 0; j < arregloEmpresas.empresas.length; j++) {
                    var empresa = arregloEmpresas.empresas[j];
                    if (empresa.id == empresaABuscar) {
                        arreglo_empresa.push(empresa);
                    }
                }
            }
            arreglo.empresas = arreglo_empresa;
            $('#' + galleryContainerId).html("");
            writeEmpresas(arreglo);
            break;
        case 1://by place
            var zona = arregloZonas[category];
            var arreglo = {};
            var arreglo_zona = [];
            for (var i = 0; i < zona.empresas.length; i++) {
                var empresaABuscar = zona.empresas[i];
                for (var j = 0; j < arregloEmpresas.empresas.length; j++) {
                    var empresa = arregloEmpresas.empresas[j];
                    if (empresa.id == empresaABuscar) {
                        arreglo_zona.push(empresa);
                    }
                }
            }
            arreglo.empresas = arreglo_zona;
            $('#' + galleryContainerId).html("");
            writeEmpresas(arreglo);
            break;
        case 2://by date
            var arreglo = {};
            arreglo = sortFecha(arregloEmpresas, category);
            $('#' + galleryContainerId).html("");
            writeEmpresas(arreglo);
            break;
    }
    itemsByPage(_itemsPerPage);
}

function sortFecha(arreglo, order) {
    var sortedArray = {};
    var sortedEmpresas = arreglo.empresas;
    if (order == 0) {
        sortedArray.empresas = sortedEmpresas.sort(ascFecha);
    } else {
        sortedArray.empresas = sortedEmpresas.sort(descFecha);
    }
    return sortedArray;

}
function descFecha(a, b) {
    var valora = new Date(a.created_at.replace(" ", "T"));
    var valorb = new Date(b.created_at.replace(" ", "T"));
    if (valora < valorb)
        return 1;
    if (valora > valorb)
        return -1;
    return 0;

}
function ascFecha(a, b) {
    var valora = new Date(a.fecha.replace(" ", "T"));
    var valorb = new Date(b.fecha.replace(" ", "T"));
    if (valora < valorb)
        return -1;
    if (valora > valorb)
        return 1;
    return 0;

}

function addCategories() {
    $('#categoryContainer').html("");
    for (var i in arregloCategorias) {
        var categoria = arregloCategorias[i];
        $('#categoryContainer').append('<li class="large-3 medium-3 small-12 left"><a href="javascript:sortEmpresas(0,' + categoria.id + ')">' + categoria.nombre + '</a></li>');
    }
}
function addZonas() {
    $('#categoryContainer').html("");
    for (var i in arregloZonas) {
        var zona = arregloZonas[i];
        $('#categoryContainer').append('<li class="large-3 medium-3 small-12 left"><a href="javascript:sortEmpresas(1,' + i + ')">' + zona.nombre + '</a></li>');
    }
}

function toDate(date) {
    return ((date.getDate() < 10) ? ('0' + date.getDate()) : (date.getDate())) + "/" + (((date.getMonth() + 1) < 10) ? ('0' + (date.getMonth() + 1)) : ((date.getMonth() + 1))) + "/" + ("" + date.getFullYear()).substring(2, 4);
}

function allItems() {
    $("div.holder").jPages("destroy");
    _allItems = true;
}
function searchBeneficio() {
    var value = $('.searchBeneficio').val();
    var arreglo = {};
    var arreglo_busqueda = [];
    for (var i = 0; i < arregloEmpresas.empresas.length; i++) {
        var empresa = arregloEmpresas.empresas[i];
        var found = false;
        if (find(value, empresa.nombre)) {
            found = true;
        }
        if (found) {
            arreglo_busqueda.push(empresa);
        }
    }
    arreglo.empresas = arreglo_busqueda;
    $('#' + galleryContainerId).html("");
    writeEmpresas(arreglo);
    itemsByPage(_itemsPerPage);
}
function find(needle, haystack) {
    var haystack = haystack.toUpperCase();
    if (haystack.indexOf(needle.toUpperCase()) != -1) {
        return true;
    }
    return false;
}
function loadCarousel(webservice){
     if(typeof webservice == 'undefined'){
        webservice = wsgallery;
    }
     $('#' + carrousellContainerId).html("");
    getGallery(webservice);
}
function getGallery(webservice) {
     if(typeof webservice == 'undefined'){
        webservice = wsgallery;
    }
    $.ajax({type: 'GET', url: webservice, crossDomain: true, dataType: 'json'}).done(function(data) {
        var htmItem = '';
        for (var i = 0; i < data.empresas.length; i++) {
            var empresa = data.empresas[i];
            var params = {};
            params.idempresa = empresa.id;
            params.imgempresa = empresa.banner;
            params.imgempresalogo = empresa.logo;
            htmItem += $('#gall_tmpl').tmpl(params)[0].outerHTML;

        }
        $('#' + carrousellContainerId).append(htmItem);
        $(document).foundation();
    });
}
/*old code
 *         <script>
 
 var ESTABLECIMIENTOS = $.parseJSON('<?=$result?>').empresas;
 
 
 $(document).foundation();
 $(function(){
 
 $("div.holder").jPages({
 containerID : "itemContainer",
 perPage:3,
 animation : "bounceInUp",
 next:"→",
 previous:"←"   
 });
 
 $('#btn_search').click(function(){
 searchBeneficio();
 });
 
 });
 
 
 function findWord(word){
 var ret = new Array();
 for(var i=0; i<ESTABLECIMIENTOS.length; i++){
 var establecimiento = ESTABLECIMIENTOS[i];
 var establecimiento_name = establecimiento.nombre.toUpperCase();
 if (establecimiento_name.indexOf(word.toUpperCase()) !=-1) {
 ret.push(establecimiento);
 }
 }
 
 return ret;
 }
 
 
 function searchBeneficio(){
 var value = $('.searchBeneficio').val();
 
 var items = findWord(value);
 var html_items = '';
 for(var i=0; i<items.length; i++){
 var item = items[i];
 
 html_items += '<div class="large-4 medium-4 small-12 left  fixpadding columns">'; 
 html_items += '<div class="container-logo"><img src="' + item.logo + '" /></div>';
 html_items += '<div class="text-banner column">';
 html_items += item.nombre.toUpperCase() + ' <br> rating: ' + item.rating; 
 html_items += '</div>';
 html_items += '<a href="detalleBeneficio.php?id=' + item.id + '"> ';
 html_items += '<img class="responsive" src="' + item.banner + '" />';
 html_items += '</a>';
 html_items += '</div>';
 
 }
 
 $('#itemContainer').html(html_items);   
 }
 
 </script>
 * 
 * */


