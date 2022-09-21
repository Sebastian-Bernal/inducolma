function reporteIngresoMaderas() {

    let desde = $('#desdeIm');
    let hasta = $('#hastaIm');
    let reporte = $('#tipoReporte');
    if (reporte.val() == "") {
        alertaErrorSimple('Seleccione un tipo de reporte!', 'error');
    } else if(desde.val() == '' || hasta.val() == ''){
        alertaErrorSimple('Ingrese datos validos para las fechas desde y hasta!','error');
    }
    else {
        $('#reporteIngresoMadera').submit();
        desde.val('');
        hasta.val('');
        reporte.val('');
    }


}

