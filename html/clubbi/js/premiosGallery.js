var arregloBeneficios = {};
var arregloCategorias = [];
var arregloRango = [];
var ws = 'http://54.200.51.188/wscb/public/premio';
var wsRango = 'http://54.200.51.188/wscb/public/rango';
var wsgallery = 'http://54.200.51.188/wscb/public/premiodestacado';
var wsParecidos = 'http://54.200.51.188/wscb/public/premio?categoria=';
var galleryContainerId = 'itemContainer';
var carrousellContainerId = 'galleryBenefits';
var _itemsPerPage = 3;
var _allItems = false;


function loadParecidos(catId, limite) {
    var wsFixed = wsParecidos + catId + '&limit=' + limite;
    $.ajax({type: 'GET', url: wsFixed, crossDomain: true, dataType: 'json'}).done(function(data) {
        writeBenefits(data);
        $("div.holder").jPages({
            containerID: galleryContainerId,
            perPage: 1,
            animation: "bounceInUp",
            next: "→",
            previous: "←"
        });
    });

}
function loadBenefits() {
    $('#' + galleryContainerId).html("");
    getBenefits();
    getRango();
}
function loadGallery() {
    $('#' + carrousellContainerId).html("");
    getGallery();
}
function getGallery() {
    $.ajax({type: 'GET', url: wsgallery, crossDomain: true, dataType: 'json'}).done(function(data) {
        var htmItem = '';
        for (var i = 0; i < data.beneficio.length; i++) {
            var beneficio = data.beneficio[i];
            var empresas = beneficio.empresa;
            var params = {};
            params.idbeneficio = beneficio.id;
            params.imgbeneficio = beneficio.imagen;
            params.textobeneficio = beneficio.nombre;
            params.vigencia = toDate(new Date(beneficio.fecha_vencimiento.replace(' ','T')));
            if (empresas.length > 0) {
                var empresa = empresas[0];
                params.imgempresa = empresa.logo;
                if (empresa.categoria.length > 0) {
                    var categoria = empresa.categoria[0];
                    params.categoria = categoria.nombre;
                }

            }
            htmItem += $('#gall_tmpl').tmpl(params)[0].outerHTML;

        }
        $('#' + carrousellContainerId).append(htmItem);
        $(document).foundation();
    });
}
function getBenefits() {
    $.ajax({type: 'GET', url: ws, crossDomain: true, dataType: 'json'}).done(function(data) {
        arregloBeneficios = data;
        for (var i = 0; i < data.premios.length; i++) {
            var beneficio = data.premios[i];
            var empresas = beneficio.empresa;
            for (var j = 0; j < empresas.length; j++) {
                var empresa = empresas[j];
                var categorias = empresa.categoria;
                for (k = 0; k < categorias.length; k++) {
                    var categoria = categorias[k];
                    addCategoria(categoria.id, categoria.nombre, beneficio.id);
                }
            }
        }
        writeBenefits(arregloBeneficios);
        $("div.holder").jPages({
            containerID: galleryContainerId,
            perPage: 3,
            animation: "bounceInUp",
            next: "→",
            previous: "←"
        });


    });
}
function addCategories() {
    $('#categoryContainer').html("");
    for (var i in arregloCategorias) {
        var categoria = arregloCategorias[i];
        $('#categoryContainer').append('<li class="large-3 medium-3 small-12 left"><a href="javascript:sortBenefits(0,' + categoria.id + ')">' + categoria.nombre + '</a></li>');
    }
}
function writeBenefits(beneficiosAEscribir) {
    var htmItem = "";
    var beneficiosAgregados = 0;
    for (var i = 0; i < beneficiosAEscribir.premios.length; i++) {
        var premio = beneficiosAEscribir.premios[i];
        if (beneficiosAgregados == 0) {
            htmItem = '<li class="row">';
        }
        var params = {};
        params.idbeneficio = premio.id;
        params.imgbeneficio = premio.imagen;
        params.textobeneficio = premio.nombre;
        params.vigencia = toDate(new Date(premio.fecha_vencimiento.replace(' ','T')));
        try {
            params.imgempresa = premio.empresa[0].logo;
        } catch (e) {

        }
        htmItem += $('#bene_tmpl').tmpl(params)[0].outerHTML;
        beneficiosAgregados++;
        if (beneficiosAgregados == 3 || i == beneficiosAEscribir.premios.length - 1) {
            beneficiosAgregados = 0;
            htmItem += '</li>';
            $('#' + galleryContainerId).append(htmItem);
        }

    }
    $('#contadorBeneficios').text(beneficiosAEscribir.premios.length + ' Premios');
}
function toDate(date) {
    return ((date.getDate()<10)?('0'+date.getDate()):(date.getDate()) )+ "/" + (((date.getMonth() + 1)<10)?('0'+(date.getMonth() + 1)):((date.getMonth() + 1))) + "/" + ("" + date.getFullYear()).substring(2, 4);
}
function addCategoria(id, nombre, beneficioID) {
    if (typeof arregloCategorias[id] == 'undefined') {
        var categoria = {};
        categoria.id = id;
        categoria.nombre = nombre;
        var beneficios = [];
        beneficios.push(beneficioID);
        categoria.beneficios = beneficios;
        arregloCategorias[id] = categoria;
    } else {
        var categoria = arregloCategorias[id];
        var beneficios = categoria.beneficios;
        var encontrado = false;
        for (var i = 0; i < beneficios.length; i++) {
            var beneficio = beneficios[i];
            if (beneficio == beneficioID) {
                encontrado = true;
            }
        }
        if (encontrado == false) {
            beneficios.push(beneficioID);
        }
    }
}
function sortBenefits(by, category) {
    switch (by) {
        case 0://by category
            var categoria = arregloCategorias[category];
            var arreglo = {};
            var arreglo_beneficio = [];
            for (var i = 0; i < categoria.beneficios.length; i++) {
                var beneficioABuscar = categoria.beneficios[i];
                for (var j = 0; j < arregloBeneficios.premios.length; j++) {
                    var beneficio = arregloBeneficios.premios[j];
                    if (beneficio.id == beneficioABuscar) {
                        arreglo_beneficio.push(beneficio);
                    }
                }
            }
            arreglo.premios = arreglo_beneficio;
            $('#' + galleryContainerId).html("");
            writeBenefits(arreglo);
            break;
        case 1://by rating
            arreglo = sortRating(arregloBeneficios, category);
            $('#' + galleryContainerId).html("");
            writeBenefits(arreglo);
            break;
        case 2://by place
            var zona = arregloZonas[category];
            var arreglo = {};
            var arreglo_zona = [];
            for (var i = 0; i < zona.beneficios.length; i++) {
                var beneficioABuscar = zona.beneficios[i];
                for (var j = 0; j < arregloBeneficios.premios.length; j++) {
                    var beneficio = arregloBeneficios.premios[j];
                    if (beneficio.id == beneficioABuscar) {
                        arreglo_zona.push(beneficio);
                    }
                }
            }
            arreglo.premios = arreglo_zona;
            $('#' + galleryContainerId).html("");
            writeBenefits(arreglo);
            break;
        case 3://by date
            var arreglo = {};
            arreglo = sortFecha(arregloBeneficios, category);
            $('#' + galleryContainerId).html("");
            writeBenefits(arreglo);
            break;
    }
    itemsByPage(_itemsPerPage);
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
function allItems() {
    $("div.holder").jPages("destroy");
    _allItems = true;
}
function searchBeneficio() {
    var value = $('.searchBeneficio').val();
    var arreglo = {};
    var arreglo_busqueda = [];
    for (var i = 0; i < arregloBeneficios.premios.length; i++) {
        var beneficio = arregloBeneficios.premios[i];
        var found = false;
        if (find(value, beneficio.descripcion_web)) {
            found = true;
        }
        if (find(value, beneficio.nombre) & found == false) {
            found = true;
        }
        if (find(value, beneficio.descripcion_movil) & found == false) {
            found = true;
        }
        if (find(value, beneficio.restriccion_movil) & found == false) {
            found = true;
        }
        if (find(value, beneficio.restriccion_web) & found == false) {
            found = true;
        }
        if (found) {
            arreglo_busqueda.push(beneficio);
        }
    }
    arreglo.premios = arreglo_busqueda;
    $('#' + galleryContainerId).html("");
    writeBenefits(arreglo);
    itemsByPage(_itemsPerPage);
}
function find(needle, haystack) {
    var haystack = haystack.toUpperCase();
    if (haystack.indexOf(needle.toUpperCase()) != -1) {
        return true;
    }
    return false;
}
function getRango() {
   $.ajax({type: 'GET', url: wsRango, crossDomain: true, dataType: 'json'}).done(function(data) {
        arregloRango = data.rango;
    });
}
function addRangos(){
    $('#categoryContainer').html("");
    for (var i in arregloRango) {
        var rango = arregloRango[i];
        $('#categoryContainer').append('<li class="large-3 medium-3 small-12 left"><a href="javascript:sortRango('+rango.min+',' + rango.max + ')">' + rango.min + '-'+rango.max+'</a></li>');
    }
}
function sortRango(min, max) {
    var arreglo = {};
    var arreglo_busqueda = [];
    for (var i = 0; i < arregloBeneficios.premios.length; i++) {
        var premio = arregloBeneficios.premios[i];
        var found = false;
        var premioPuntos = parseInt(premio.puntos);
        if (premioPuntos>=parseInt(min) && premioPuntos<=parseInt(max)) {
            found = true;
        }
        if (found) {
            arreglo_busqueda.push(premio);
        }
    }
    arreglo.premios = arreglo_busqueda;
    $('#' + galleryContainerId).html("");
    writeBenefits(arreglo);
    itemsByPage(_itemsPerPage);
}