/* 
 * Author: Javier Cifuentes
 */
var arregloBeneficios = {};
var arregloCategorias = [];
var arregloZonas = [];
var AsigCategoriaBeneficio = [];
var ws = 'http://54.200.51.188/wscb/public/beneficio';
var wsgallery = 'http://54.200.51.188/wscb/public/beneficiodestacado';
var galleryContainerId = 'itemContainer';
var carrousellContainerId = 'galleryBenefits';
var _itemsPerPage = 3;
var _allItems = false;
var wsParecidos = 'http://54.200.51.188/wscb/public/beneficio?categoria=';


function loadBenefits() {
    $('#' + galleryContainerId).html("");
    getBenefits();

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
            params.vigencia = toDate(new Date(beneficio.fecha_vencimiento.replace(' ', 'T')));
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
        for (var i = 0; i < data.beneficio.length; i++) {
            var beneficio = data.beneficio[i];
            var empresas = beneficio.empresa;
            for (var j = 0; j < empresas.length; j++) {
                var empresa = empresas[j];
                var categorias = empresa.categoria;
                for (k = 0; k < categorias.length; k++) {
                    var categoria = categorias[k];
                    addCategoria(categoria.id, categoria.nombre, beneficio.id);
                }
                var zonas = empresa.zonas;
                for (var k = 0; k < zonas.length; k++) {
                    var zona = zonas[k];
                    addZona(zona.zona, beneficio.id);
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
function addZonas() {
    $('#categoryContainer').html("");
    for (var i in arregloZonas) {
        var zona = arregloZonas[i];
        $('#categoryContainer').append('<li class="large-3 medium-3 small-12 left"><a href="javascript:sortBenefits(2,' + i + ')">' + zona.nombre + '</a></li>');
    }
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
function addZona(nombre, beneficioID) {
    var zonaID = zonaExists(nombre);
    if (zonaID == -1) {
        var zona = {};
        zona.nombre = nombre;
        var beneficios = [];
        beneficios.push(beneficioID);
        zona.beneficios = beneficios;
        arregloZonas.push(zona);
    } else {
        var zona = arregloZonas[zonaID];
        var beneficios = zona.beneficios;
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
function zonaExists(nombre) {
    for (var i = 0; i < arregloZonas.length; i++) {
        var zona = arregloZonas[i];
        if (zona.nombre == nombre) {
            return i;
        }
    }
    return -1;
}

function writeBenefits(beneficiosAEscribir) {
    var htmItem = "";
    var beneficiosAgregados = 0;
    
    
    for (var i = 0; i < beneficiosAEscribir.beneficio.length; i++) {
        var beneficio = beneficiosAEscribir.beneficio[i];
        if (beneficiosAgregados == 0) {
            htmItem = '<li class="row">';
        }
        var params = {};
        params.idbeneficio = beneficio.id;
        params.imgbeneficio = beneficio.imagen;
        params.textobeneficio = beneficio.nombre;
        params.vigencia = toDate(new Date(beneficio.fecha.replace(' ', 'T')));
        try {
            params.imgempresa = beneficio.empresa[0].logo;
        } catch (e) {

        }
        htmItem += $('#bene_tmpl').tmpl(params)[0].outerHTML;
        beneficiosAgregados++;
        if (beneficiosAgregados == 3 || i == beneficiosAEscribir.beneficio.length - 1) {
            beneficiosAgregados = 0;
            htmItem += '</li>';
            $('#' + galleryContainerId).append(htmItem);
        }

    }
    $('#contadorBeneficios').text(beneficiosAEscribir.beneficio.length + ' Beneficios');
}
function sortBenefits(by, category) {
    switch (by) {
        case 0://by category
            var categoria = arregloCategorias[category];
            var arreglo = {};
            var arreglo_beneficio = [];
            for (var i = 0; i < categoria.beneficios.length; i++) {
                var beneficioABuscar = categoria.beneficios[i];
                for (var j = 0; j < arregloBeneficios.beneficio.length; j++) {
                    var beneficio = arregloBeneficios.beneficio[j];
                    if (beneficio.id == beneficioABuscar) {
                        arreglo_beneficio.push(beneficio);
                    }
                }
            }
            arreglo.beneficio = arreglo_beneficio;
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
                for (var j = 0; j < arregloBeneficios.beneficio.length; j++) {
                    var beneficio = arregloBeneficios.beneficio[j];
                    if (beneficio.id == beneficioABuscar) {
                        arreglo_zona.push(beneficio);
                    }
                }
            }
            arreglo.beneficio = arreglo_zona;
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
function sortRating(arreglo, order) {
    var sortedArray = {};
    var sortedBeneficios = arreglo.beneficios;
    if (order == 0) {
        sortedArray.beneficios = sortedBeneficios.sort(ascRating);
    } else {
        sortedArray.beneficios = sortedBeneficios.sort(descRating);
    }
    return sortedArray;
}
function sortFecha(arreglo, order) {
    var sortedArray = {};
    var sortedBeneficios = arreglo.beneficio;
    if (order == 0) {
        sortedArray.beneficios = sortedBeneficios.sort(ascFecha);
    } else {
        sortedArray.beneficios = sortedBeneficios.sort(descFecha);
    }
    return sortedArray;

}
function descRating(a, b) {
    var valora = parseFloat(a.rating);
    var valorb = parseFloat(b.rating);
    if (valora < valorb)
        return 1;
    if (valora > valorb)
        return -1;
    return 0;

}
function ascRating(a, b) {
    var valora = parseFloat(a.rating);
    var valorb = parseFloat(b.rating);
    if (valora < valorb)
        return -1;
    if (valora > valorb)
        return 1;
    return 0;

}
function descFecha(a, b) {
	console.log(a);
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
    for (var i = 0; i < arregloBeneficios.beneficios.length; i++) {
        var beneficio = arregloBeneficios.beneficios[i];
        var found = false;
        if (find(value, beneficio.descripcion_web)) {
            found = true;
        }
        if (find(value, beneficio.nombre) & found == false) {
            found = true;
        }
        if (find(value, beneficio.restriccion_web) & found == false) {
            found = true;
        }
        if (found) {
            arreglo_busqueda.push(beneficio);
        }
    }
    arreglo.beneficios = arreglo_busqueda;
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
function toDate(date) {
    return ((date.getDate() < 10) ? ('0' + date.getDate()) : (date.getDate())) + "/" + (((date.getMonth() + 1) < 10) ? ('0' + (date.getMonth() + 1)) : ((date.getMonth() + 1))) + "/" + ("" + date.getFullYear()).substring(2, 4);
}
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


