// variable global formularios


var formReporteIgresoMadera = document.getElementById('reporteIngresoMadera');

function seleccionaReporteIngresoMadera(ruta){
    formReporteIgresoMadera.setAttribute('action', ruta);
}

function reporteIngresoMaderas() {

    let desde = $('#desdeIm');
    let hasta = $('#hastaIm');
    if (formReporteIgresoMadera.getAttribute('action') == '') {
        alertaErrorSimple('Seleccione un tipo de reporte!', 'error');
    } else if(desde.val() == '' || hasta.val() == ''){
        alertaErrorSimple('Ingrese datos validos para las fechas desde y hasta!','error');
    }
    else {
        console.log('submit')
        //$('#reporteIngresoMadera').submit();
        desde.val('');
        hasta.val('');
    }


}

