/* 
 *Author: Javier Cifuentes
 */

function ROUND(number, digits) {
    return Math.round(number * Math.pow(10, digits)) / Math.pow(10, digits);
}
function INT(number) {
    return Math.floor(number);
}
function TRUNC(number, digits) {
    digits = (typeof digits === 'undefined') ? 0 : digits;
    var sign = (number > 0) ? 1 : -1;
    return sign * (Math.floor(Math.abs(number) * Math.pow(10, digits))) / Math.pow(10, digits);
}
function EFFECT(rate, periods) {
    // Return error if any of the parameters is not a number
    if (isNaN(rate) || isNaN(periods)) {
        return '#VALUE!';
    }

    // Return error if rate <=0 or periods < 1
    if (rate <= 0 || periods < 1) {
        return '#NUM!';
    }

    // Truncate periods if it is not an integer
    periods = parseInt(periods, 10);

    // Return effective annual interest rate
    return Math.pow(1 + rate / periods, periods) - 1;
}
function EOMONTH(start_date, months) {
    var edate = moment(new Date(start_date)).add('months', months);
    return new Date(edate.year(), edate.month(), edate.daysInMonth());
}
function EDATE(start_date, months) {
    return moment(new Date(start_date)).add('months', months).toDate();
}
function agregarComas(numero) {
    numero += '';
    x = numero.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
function validateMinMax(value,min,max){
    if(value>=min&&value<=max){
        return true;
    }else{
        return false;
    }
}
function validateMin(value,min){
    if(value>=min){
        return true;
    }else{
        return false;
    }
}
function validateMax(value,max){
    if(value<=max){
        return true
    }else{
        return false;
    }
}
function error(message) {
    try {
        parent.error(message);
    }
    catch (err) {
        alert(message);
    }
}
function validaRequeridos(campos) {
    var validado = true;
    for (var p = 0; p < campos.length; p++) {
        var campo = campos[p];
        if ($('#' + campo).val().length == 0) {
            $('#' + campo).parent().find('small').addClass('error').text("Campo requerido");
            $('#' + campo).focus();
            p = campos.length;
            validado = false;
        } else {
            $('#' + campo).parent().find('small').removeClass('error').text("");
        }
    }
    return validado;
}
function setCalendarLanguage(){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '<Ant',
		nextText: 'Sig>',
		currentText: 'Hoy',
		monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
		dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
		dayNamesShort: ['Dom','Lun','Mar','Mie','Juv','Vie','Sab'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
	};
	$.datepicker.setDefaults($.datepicker.regional['es']);
} 

$(function() {
    try{
    setCalendarLanguage(); 
    }catch(e){
        
    }
    $('.esflotante').keydown(function(e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A
                        (e.keyCode == 65 && e.ctrlKey === true) ||
                        // Allow: home, end, left, right
                                (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    if (e.keyCode == 190) {
                        //chequear que solo haya uno
                        var valor = $(this).val();
                        var matches = valor.match(/\./g);
                        if (matches !== null) {
                            e.preventDefault();
                        }
                    }
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                } else {
                    var valor = $(this).val();
                    var matches = valor.match(/\./g);
                    if (matches !== null) {
                        var index = valor.indexOf('.');
                        if (this.selectionStart > index) {
                            if (valor.length > index + 1) {
                                if (index + 3 <= valor.length) {
                                    e.preventDefault();
                                }
                            }
                        }
                    }
                }
            });
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
});