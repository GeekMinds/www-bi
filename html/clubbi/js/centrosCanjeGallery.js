/* 
 * Author: Javier Cifuentes
 */
var arregloEmpresas = {};
var arregloZonas = [];
var arregloCategorias = [];
var ws = 'http://54.200.51.188/wscb/public/ccanje';
var wsgallery = 'http://54.200.51.188/wscb/public/beneficiodestacado';
var galleryContainerId = 'itemContainer';
var carrousellContainerId = 'galleryBenefits';
var _itemsPerPage = 3;
var _allItems = false;
function loadEmpresas() {
    $('#' + galleryContainerId).html("");
    getEmpresas();

}
function getEmpresas() {
    $.ajax({type: 'GET', url: ws, crossDomain: true, dataType: 'json'}).done(function(data) {
        arregloEmpresas = data;
        console.dir(data);
//        for (var i = 0; i < data.empresas.length; i++) {
//            var empresa = data.empresas[i];
//            var categorias = empresa.categoria;
//            for (k = 0; k < categorias.length; k++) {
//                var categoria = categorias[k];
//                addCategoria(categoria.id, categoria.nombre, empresa.id);
//            }
//            var zonas = empresa.zonas;
//            for (var k = 0; k < zonas.length; k++) {
//                var zona = zonas[k];
//                addZona(zona.zona, empresa.id);
//            }
//        }
//        writeEmpresas(arregloEmpresas);
        $("div.holder").jPages({
            containerID: galleryContainerId,
            perPage: 3,
            animation: "bounceInUp",
            next: "→",
            previous: "←"
        });


    });
}


