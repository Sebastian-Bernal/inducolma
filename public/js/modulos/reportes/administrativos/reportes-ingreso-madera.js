/**
 * envia el formulario reporteIngresoMaderas
 *
 * @return {void}
 */
function reporteIngresoMaderas() {

    let desde = $('#desdeIm');
    let hasta = $('#hastaIm');
    let reporte = $('#tipoReporte');
    let especifico = $('#especifico');
    if (reporte.val() == "") {
        alertaErrorSimple('Seleccione un tipo de reporte!', 'error');
    } else if (desde.val() == '' || hasta.val() == '') {
        alertaErrorSimple('Ingrese datos validos para las fechas desde y hasta!', 'error');
    }
    else {
        if ((reporte.val() == '3' || reporte.val() == '4') && especifico.val() == "") {
            alertaErrorSimple('Seleccione el filtro de busqueda!', 'error');
            especifico.click();
        } else {
            $('#reporteIngresoMadera').submit();
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
function datoEspecifico() {

    let reporte = $('#tipoReporte');
    let especifico = $('#especifico');
    console.log(reporte.val());
    if (reporte.val() == '3') {
        proveedoresIngreso();
    } else if (reporte.val() == '4') {
        tipoMadera();
    } else {
        $('#divEspecifico').hide(300);
    }
}

/**
 * busca los proveedores y los carga en el select
 *
 * @returns {void}
 */
function proveedoresIngreso() {
    $('#especifico').val('')
    $('#divEspecifico').show(300);
    $('#especifico').select2({
        width: 'aut',
        placeholder: 'Seleccione...',
        theme: "bootstrap-5",
        ajax: {
            url: '/get-proveedores',
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

/**
 * busca llos tipos de madera y los carga en el select
 *
 * @returns {void}
 */
function tipoMadera() {
    $('#especifico').val('')
    $('#divEspecifico').show(300);
    $('#especifico').select2({
        width: 'aut',
        placeholder: 'Seleccione...',
        theme: "bootstrap-5",
        ajax: {
            url: '/get-tipo-maderas',
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

/**
 * envia el formulario para generar el tipo de reporte segun la seleecion del usuario
 *
 * @param {String} tipo_reporte [ numero del tipo de reporte ]
 */

function generarReporteIngresoMadera(tipo_reporte) {
    console.log(tipo_reporte);
    switch (tipo_reporte) {
        case '1':
            $('#generar').val('1');
            $('#generarReporteIngresoMadera').submit();
            break;
        case '2':
            $('#generar').val('2');
            $('#generarReporteIngresoMadera').submit();
            break;
        case '3':
            $('#generar').val('3');
            $('#generarReporteIngresoMadera').submit();
            break;
        default:
            alertaErrorSimple('opcion invalida al generar reporte', 'error');
            break;
    }

}
