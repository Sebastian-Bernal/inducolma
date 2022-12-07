/**
 * valida los datos para generar los reportes
 */

function reportePersonal() {

    let desde = $('#personalDesde');
    let hasta = $('#personalHasta');
    let filtro = $('#filtroPersonal');
    let reporte = $('#tipoReportePersonal');

    if (reporte.val() == "" || desde.val() == "" || hasta.val() == "" ) {
        alertaErrorSimple('Seleccione un tipo de reporte trabajadores, y un rango de fechas!', 'error');
    }
    else {
        if (reporte.val() == '3' && (especifico.val() == "" || desde.val() == "" || hasta.val() == "")) {
            alertaErrorSimple('Seleccione el filtro de busqueda y el rango de fechas ', 'error');
            especifico.click();
        } else {
            $('#formReportePersonal').submit();
            desde.val('');
            hasta.val('');
            reporte.val('');

        }
    }
}


/**
 * envia el formulario para generar el tipo de reporte segun la seleecion del usuario
 *
 * @param {String} tipo_reporte [ numero del tipo de reporte ]
 */

function generarReportePersonal(tipo_reporte) {
    console.log(tipo_reporte);
    switch (tipo_reporte) {
        case '1':
            $('#generar').val('1');
            $('#formGenerarReportePersonal').submit();
            break;
        case '2':
            $('#generar').val('2');
            $('#formGenerarReportePersonal').submit();
            break;
        case '3':
            $('#generar').val('3');
            $('#formGenerarReportePersonal').submit();
            break;
        default:
            alertaErrorSimple('opcion invalida al generar reporte', 'error');
            break;
    }

}
