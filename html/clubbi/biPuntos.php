
<?php
include_once 'settings.php';
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
    <link rel="stylesheet" href="css/biPuntos.css" />       
    <script src="js/vendor/modernizr.js"></script>
    <style>
        .nav li.active {
            border-bottom: #01268E 2px solid;
        }
        .header{
            background-image: url('media/images/header-bipuntos.jpg');
            background-color: #2B5AA8;
        }
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
        li.active.arrow ul li a
        {
            color: #26B4F1;
        }
    </style>

</head>
<body>
    <?php include 'header.php'; ?>
    <div class="row">
        <div class="large-12 medium-12 small-12 left columns">
            <h2>Consultar Puntos</h2>
        </div>
    </div>

    <div class="row">
        <div class="large-12 medium-12 small-12 columns">
            <div class="large-5 medium-12 small-12 left" id="info_puntos">
                <div class="titleBiPuntos">Tipo de Cliente</div>
                <input type="radio" id="c1" name="tipo" value="0" checked>
                <label class="labelBipuntos" for="c1">Personal <span></span> </label>          
                <input type="radio" id="c2" name="tipo" value="1">
                <label class="labelBipuntos" for="c2">Empresarial <span></span></label>
                <div class="titleBiPuntos">Tipo de Documento</div>

                <input type="radio" id="c3" name="tipo_doc" value="3" checked>
                <label class="labelBipuntos" for="c3">Dpi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <span></span> </label>          
                <input type="radio" id="c4" name="tipo_doc" value="4">
                <label class="labelBipuntos" for="c4">Pasaporte &nbsp;&nbsp;&nbsp;<span></span></label>                         
                <div class="titleBiPuntos ">Numero de Documento</div>
                <input type="text" id="documento" class="fieldBiPuntos" placeholder="Ingrese los numeros:" name="buscar" maxlength="12">
                <div class="titleBiPuntos">Fecha de Nacimiento</div>
                <select class="birthday dia" id="dia">
                    <option value="0" disabled selected>Dia</option>
                    <?php
                    for ($c = 1; $c < 32; $c++) {
                        echo("<option value=" . $c . ">" . $c . "</option>");
                    }
                    ?>

                </select>
                <select class="birthday mes" id="mes" >
                    <option value="0" disabled selected>Mes</option>
                    <?php
                    for ($c = 1; $c < 13; $c++) {
                        echo("<option value=" . $c . ">" . $c . "</option>");
                    }
                    ?>
                </select>
                <select class="birthday year" id="year">
                    <option value="0" disabled selected>Año</option>
                    <?php
                    for ($c = 1940; $c < 2014; $c++) {
                        echo("<option value=" . $c . ">" . $c . "</option>");
                    }
                    ?>
                </select>
                <div class="titleBiPuntos">Numero de Tarjeta Club Bi</div>
                <input type="text" id="numeroTarjeta" class="fieldBiPuntos  esentero" placeholder="Ingrese los numeros:" name="buscar" maxlength="16">
            </div>
            <div class="large-5 medium-12 small-12 left" id="info_puntos_empresa" style="display:none;">
                <div class="titleBiPuntos">Tipo de Cliente</div>
                <input type="radio" id="c1" name="tipo2" value="0">
                <label class="labelBipuntos" for="c1">Personal <span></span> </label>          
                <input type="radio" id="c2" name="tipo2" value="1" checked>
                <label class="labelBipuntos" for="c2">Empresarial <span></span></label>                     
                <div class="titleBiPuntos ">Nit</div>
                <input type="text" id="documento2" class="fieldBiPuntos esentero" placeholder="Ingrese los numeros:" name="buscar" maxlength="15">
                <div class="titleBiPuntos">Numero de Tarjeta Club Bi</div>
                <input type="text" id="numeroTarjeta2" class="fieldBiPuntos  esentero" placeholder="Ingrese los numeros:" name="buscar" maxlength="16">
            </div>
            <div class="large-7 medium-12 small-12 left puntos">
                <div class="large-12 medium-12 small-12 left">
                    <div class="puntosAcumulados">
                        <h4>Puntos Acumulados</h4>
                        <hr style="margin-top: -2px;" />
                        <h3  id="puntosAcumulados">000000</h3>
                    </div>
                </div>

                <div class="large-6 medium-12 small-12 left" style="margin-top: -9px;">
                    <div class="puntosAcumuladosGrafica izquierda">
                        <div id="chart" class="chart"><span id="serie1">30.00%</span></div><div class="legendGrafic"><label>Puntos Acumulados por Banco</label></div>
                    </div>

                </div>
                <div class="large-6 medium-12 small-12 left" style="margin-top: -9px;">
                    <div class="puntosAcumuladosGrafica derecha">
                        <div id="chart2" class="chart"><span id="serie2">70.00%</span></div><div class="legendGrafic"><label>Puntos Acumulados por Contecnica</label></div>
                    </div>
                </div>
                <div class="large-6 medium-12 small-12 left" style="margin-top: -9px;">
                    <div class="puntosAcumuladosGrafica izquierda">
                        <h6>Puntos Acumulados</h6>
                    </div>

                </div>
                <div class="large-6 medium-12 small-12 left" style="margin-top: -9px;">
                    <div class="puntosAcumuladosGrafica derecha">
                        <h6>Puntos Canjeados</h6>
                    </div>
                </div>
                <div class="large-6 medium-12 small-12 left" style="margin-top: -9px;">
                    <div class="puntosAcumuladosGrafica izquierda">
                        <h5 id="puntosAcumulados2">00000</h5>
                    </div>

                </div>
                <div class="large-6 medium-12 small-12 left" style="margin-top: -9px;">
                    <div class="puntosAcumuladosGrafica derecha">
                        <h5 id="puntosCanjeados">00000</h5>
                    </div>
                </div>
                <div class="large-6 medium-12 small-12 left" style="margin-top: -9px;">
                    <div class="puntosAcumuladosGrafica izquierda" style="border-bottom: none;">
                        <h4 style="text-align: left; margin-left: 36px;">PUNTOS QUE VENCERAN EN <span id="anioVencer"></span></h4>
                    </div>

                </div>
                <div class="large-6 medium-12 small-12 left" style="margin-top: -9px;">
                    <div class="puntosAcumuladosGrafica derecha" style="border-bottom: none;">
                        <h5 id="puntosVencer">00000</h5>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="row">
        <div class="large-7 medium-12 small-12 right">
            <button class="consultar" onclick="consultar()" >Consultar Mis Puntos</button>

        </div>
    </div>
    <?php include 'footer.php'; ?>
    <?php include 'popups.php'; ?>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/menuDesktop.js"></script>
    <script src="js/raphael-min.js"></script>
    <script src="js/elycharts.js"></script>
    <script>
                $(document).foundation();
    </script>
    <script>
        var puntos = [];
        var individual = true;
        $(document).ready(function() {
            graficar(30, 70);

            $('.esentero').keydown(function(e) {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
                        // Allow: Ctrl+A
                                (e.keyCode == 65 && e.ctrlKey === true) ||
                                // Allow: home, end, left, right
                                        (e.keyCode >= 35 && e.keyCode <= 39)) {
                            // let it happen, don't do anything
                            return;
                        }
                        // Ensure that it is a number and stop the keypress
                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                            e.preventDefault();
                        }
                    });

            $('input[type="radio"]').click(function() {
                if ($(this).is(':checked'))
                {

                    if ($(this).val() === "0") {
                        $("#info_puntos_empresa").hide();
                        $("#info_puntos").slideDown();
                        individual = true;
                    } else if ($(this).val() === "1") {
                        $("#info_puntos").hide();
                        $("#info_puntos_empresa").slideDown();
                        individual = false;
                    }
                }
            });


        });
        function graficar(serie1, serie2) {
            $.elycharts.templates['pie_basic_2'] = {
                type: "pie",
                style: {
                    height: 160,
                    width: 160,
                    "background-color": "#EBEBEB"

                },
                defaultSeries: {
                    plotProps: {
                        stroke: "#EBEBEB",
                        "stroke-width": 2,
                        opacity: 1.0
                    },
                    highlight: {
                        newProps: {
                            opacity: 1
                        }
                    },
                    tooltip: {
                        active: false,
                        frameProps: {
                            opacity: 1.0
                        }
                    },
                    label: {
                        active: false,
                        props: {
                            fill: "white"
                        }
                    },
                    startAnimation: {
                        active: true,
                        type: "avg"
                    }
                }
            }
            $("#chart").chart({
                template: "pie_basic_2",
                values: {
                    serie1: [serie1, serie2]
                },
                labels: ["a", "b"],
                tooltips: {
                    serie1: ["a", "b"]
                },
                defaultSeries: {
                    r: -0.7,
                    values: [{
                            plotProps: {
                                fill: "#26B4F1"
                            }
                        }, {
                            plotProps: {
                                fill: "#ffffff"
                            }
                        }]
                }
            });
            $("#chart2").chart({
                template: "pie_basic_2",
                values: {
                    serie1: [serie2, serie1]
                },
                labels: ["a", "b"],
                tooltips: {
                    serie1: ["a", "b"]
                },
                defaultSeries: {
                    r: -0.7,
                    values: [{
                            plotProps: {
                                fill: "#26B4F1"
                            }
                        }, {
                            plotProps: {
                                fill: "#ffffff"
                            }
                        }]
                }
            });
        }
        function consultar()
        {
            if (individual) {
                consultarIndividual();
            } else {
                consultarEmpresa();
            }
        }
        function validarFecha(dia, mes, anio) {
            if (dia === null || mes === null || anio === null) {
                error("Alto", "Debe elegir una fecha completa.");
                return false;
            }
            dia = parseInt(dia);
            mes = parseInt(mes);
            anio = parseInt(anio);
            if (dia < 1 || dia > 31) {
                error("Alto", "El valor del día debe estar comprendido entre 1 y 31.");
                return false;
            }
            if (mes < 1 || mes > 12) {
                error("Alto", "El valor del mes debe estar comprendido entre 1 y 12.");
                return false;
            }
            if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31) {
                error("Alto", "El mes " + mes + " no tiene 31 días!");
                return false;
            }
            if (mes == 2) { // bisiesto
                var bisiesto = (anio % 4 == 0 && (anio % 100 != 0 || anio % 400 == 0));
                if (dia > 29 || (dia == 29 && !bisiesto)) {
                    error("Alto", "Febrero del " + anio + " no contiene " + dia + " dias!");
                    return false;
                }
            }
            return true;
        }
        function consultarIndividual() {
            var doc = $("#documento").val();
            if (doc.length !== 12) {
                
                try {
                
                    error("Alto", "El n&uacute;mero de documento debe tener  12 digitos.");
                    return false;
                } catch (e) {

                }
            }
            var tarjeta = $("#numeroTarjeta").val();
            if (tarjeta.length !== 16) {
                try {
                    error("Alto", "El n&uacute;mero de tarjeta debe tener  16 digitos.");
                    return false;
                } catch (e) {

                }
            }

            var dia = $("#dia").val();
            var mes = $("#mes").val();
            var anio = $("#year").val();

            if (!validarFecha(dia, mes, anio)) {
                return false;
            }

            var params = {};
            $.get('<?= $wsServer ?>consultaPuntos/1/' + doc + '/' + dia + '-' + mes + '-' + anio + '/' + tarjeta, params, function(data) {

                puntos = data;
                //Llenado de campos
                if (data.status === 0) {

                    $("#puntosAcumulados").html(data.punto.totalAcumulado);
                    $("#puntosAcumulados2").html(data.punto.totalAcumulado);
                    $("#puntosCanjeados").html(data.punto.totalCanjeado);
                    $("#anioVencer").html(data.punto.anioVencer);
                    $("#puntosVencer").html(data.punto.puntosVencer);
                    //Calculo de graficas

                    var puntos1 = parseInt(puntos.punto.detalleAcumulado.puntos1.replace(",", ""));
                    var puntos2 = parseInt(puntos.punto.detalleAcumulado.puntos2.replace(",", ""));
                    var total = puntos1 + puntos2;
                    var porcentaje1 = (puntos1 * 100) / total;
                    var porcentaje2 = 100 - parseFloat(porcentaje1.toFixed(2));

                    //graficar(porcentaje1,porcentaje2);
                    graficar((parseFloat(porcentaje1.toFixed(2))), porcentaje2);
                    $("#serie1").html(parseFloat(porcentaje1.toFixed(2)) + "%");
                    $("#serie2").html(porcentaje2 + "%");
                }

            });
            return true;
        }
        function consultarEmpresa() {
            var doc = $("#documento2").val();
            if (doc === "") {
                try {
                    error("Alto", "Debe ingresar su numero de Nit.");
                    return false;
                } catch (e) {

                }
            }
            var tarjeta = $("#numeroTarjeta2").val();
            if (tarjeta.length !== 16) {
                try {
                    error("Alto", "El n&uacute;mero de tarjeta debe tener  16 digitos.");
                    return false;
                } catch (e) {

                }
            }
            var fecha = new Date();
            var params = {};
            $.get('<?= $wsServer ?>consultaPuntos/1/' + doc + '/' + fecha.getDate() + '-' + (fecha.getMonth() + 1) + '-' + fecha.getFullYear() + '/' + tarjeta, params, function(data) {

                puntos = data;
                //Llenado de campos
                if (data.status === 0) {

                    $("#puntosAcumulados").html(data.punto.totalAcumulado);
                    $("#puntosAcumulados2").html(data.punto.totalAcumulado);
                    $("#puntosCanjeados").html(data.punto.totalCanjeado);
                    $("#anioVencer").html(data.punto.anioVencer);
                    $("#puntosVencer").html(data.punto.puntosVencer);
                    //Calculo de graficas

                    var puntos1 = parseInt(puntos.punto.detalleAcumulado.puntos1.replace(",", ""));
                    var puntos2 = parseInt(puntos.punto.detalleAcumulado.puntos2.replace(",", ""));
                    var total = puntos1 + puntos2;
                    var porcentaje1 = (puntos1 * 100) / total;
                    var porcentaje2 = 100 - parseFloat(porcentaje1.toFixed(2));

                    //graficar(porcentaje1,porcentaje2);
                    graficar((parseFloat(porcentaje1.toFixed(2))), porcentaje2);
                    $("#serie1").html(parseFloat(porcentaje1.toFixed(2)) + "%");
                    $("#serie2").html(porcentaje2 + "%");
                }

            });
        }
    </script>
</body>
</html>
