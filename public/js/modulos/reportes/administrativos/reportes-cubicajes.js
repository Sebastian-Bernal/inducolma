/**
 * muestra el input del dato especifico cargado con los datos de la opcion seleccionada
 *
 * @returns {void}
 */
function filtroCubicajes() {
    let reporte = $('#tipoReporteCubicaje');

    switch (reporte.val()) {
        case '1':
            mostrarInputViaje();
            $('#divFiltroCubicaje2').hide(300);

            $('#divDesde').hide();
            $('#divHasta').hide();
            break;
        case '2':
            mostrarInputViaje();
            $('#divFiltroCubicaje2').hide(300);

            $('#divDesde').hide();
            $('#divHasta').hide();
            break;

        case '3':
            mostrarInputViaje();
            $('#divFiltroCubicaje2').hide(300);

            $('#divDesde').hide();
            $('#divHasta').hide();
            break;

        case '4':
            proveedores();
            $('#divFiltroCubicaje1').hide(300);
            break;
        default:
            $('#divFiltroCubicaje1').hide(300);
            $('#divFiltroCubicaje2').hide(300);
            $('#divDesde').hide();
            $('#divHasta').hide();

            break;
    }

}
/**
 * busca los proveedores y los carga en el select
 *
 * @returns {void}
 */
function proveedores() {
    $('#filtroCubiaje2').val('')
    $('#divFiltroCubicaje2').show(300);
    $('#divDesde').show(100);
    $('#divHasta').show(100);
    $('#filtroCubiaje2').select2({
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
 * muestra el input para ingreso del viaje
 */
function mostrarInputViaje(){
    $('#filtroCubiaje1').val('')
    $('#divFiltroCubicaje1').show(300);
}

/**
 * valida los datos para generar los reportes
 */

function reporteCubicajes() {

    let desde = $('#cubicajeDesde');
    let hasta = $('#cubicajeHasta');
    let viaje = $('#filtroCubiaje1');
    let reporte = $('#tipoReporteCubicaje');
    let especifico = $('#filtroCubiaje2');
    if (reporte.val() == "") {
        alertaErrorSimple('Seleccione un tipo de reporte!', 'error');
    } else if( viaje.val() == "" && reporte.val() != '4'){
        alertaErrorSimple('Ingrese el numero de viaje a consultar')
    }
    else {
        if (reporte.val() == '4' && (especifico.val() == "" || desde.val() == "" || hasta.val() == "")) {
            alertaErrorSimple('Seleccione el filtro de busqueda y el rango de fechas ', 'error');
            especifico.click();
        } else {
            $('#formReporteCubicajes').submit();
            desde.val('');
            hasta.val('');
            reporte.val('');

        }
    }
}
