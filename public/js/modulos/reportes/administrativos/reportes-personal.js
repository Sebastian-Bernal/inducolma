/**
 * valida los datos para generar los reportes
 */

function reportePersonal() {

    let desde = $('#personalDesde');
    let hasta = $('#personalHasta');
    let especifico = $('#filtroPersonal');
    let reporte = $('#tipoReportePersonal');

    if (reporte.val() == "" || desde.val() == "" || hasta.val() == "" ) {
        alertaErrorSimple('Seleccione un tipo de reporte trabajadores, y un rango de fechas!', 'error');
    }
    else {
        if ((reporte.val() == '4' || reporte.val() == '5') && (especifico.val() == "" || desde.val() == "" || hasta.val() == "")) {
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
 * muestra el input del dato especifico cargado con los datos de la opcion seleccionada
 *
 * @returns {void}
 */
function datoEspecificoPersonal() {

    let reporte = $('#tipoReportePersonal');
    let especifico = $('#filtroPersonal');
    if (reporte.val() == '4') {
        dataSelect('/get-empleados');
    }else if (reporte.val() == '5' ){
        dataSelect('/get-terceros');
    }
    else {
        $('#divEspecificoEmpleado').hide(300);
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


/**
 * busca los empleados y los carga en el select
 *
 * @returns {void}
 */
function dataSelect(ruta) {
    $('#filtroPersonal').val('')
    $('#divEspecificoEmpleado').show(300);
    $('#filtroPersonal').select2({
        width: 'aut',
        placeholder: 'Seleccione...',
        theme: "bootstrap-5",
        ajax: {
            url: ruta,
            type: 'get',
            delay: 800,
            data: function (params) {
                return {
                    descripcion: params.term,
                    _token: $('input[name="_token"]').val()
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
        },
        language: {
            noResults: function () {
                return "No hay resultados";
            },
            searching: function () {
                return "Buscando..";
            },

        },
    })
}
