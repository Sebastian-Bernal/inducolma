/**
 * valida los datos para generar los reportes
 */
function reporteConstruccion() {

    let desde = $('#procesoDesde');
    let hasta = $('#procesoHasta');
    let maquina = $('#maquina');
    let item = $('#item');
    let reporte = $('#tipoReporteConstruccion');

    if (reporte.val() == "" ) {
        alertaErrorSimple('Seleccione un tipo de reporte!', 'error');
    }
    else {
        if ((Array('1','3','4','5').includes(reporte.val())) && (maquina.val() == "" || desde.val() == "" || hasta.val() == "" )) {
            alertaErrorSimple('Seleccione la maquina y el rango de fechas', 'warning');
            maquina.focus();
        } else if((reporte.val() == '2') && (desde.val() == "" || hasta.val() == "" || item.val() == "" || maquina.val() == "")){
            alertaErrorSimple('Seleccione la maquina, el item y el rango de fechas', 'warning');
        } else if(reporte.val() == '5' && (desde.val() == "" || hasta.val() == "")){
            alertaErrorSimple('Seleccioneun rango de fehcas ');
        }  else {
            $('#formReporteProceso').submit();
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
function datoEspecificoConstruccion() {

    let reporte = $('#tipoReporteConstruccion');

    if (Array('1','3','4','5').includes(reporte.val())){
        $('#divEspecifico').show(300);
        $('#divEspecifico1').hide(300);
        dataSelect('/get-maquinas','#maquina');
    }else if (reporte.val() == '2'){
        $('#divEspecifico').show(300);
        $('#divEspecifico1').show(300);
        dataSelect('/get-maquinas', '#maquina');
        dataSelect('/get-items', '#item');
    }
    else {
        $('#divEspecifico').hide(300);
        $('#divEspecifico1').hide(300);
    }
}


/**
 * envia el formulario para generar el tipo de reporte segun la seleecion del usuario
 *
 * @param {String} tipo_reporte [ numero del tipo de reporte ]
 */

function generarReporteProceso(tipo_reporte) {
    console.log(tipo_reporte);
    switch (tipo_reporte) {
        case '1':
            $('#generar').val('1');
            $('#formReporteProceso').submit();
            break;
        case '2':
            $('#generar').val('2');
            $('#formReporteProceso').submit();
            break;
        case '3':
            $('#generar').val('3');
            $('#formReporteProceso').submit();
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
function dataSelect(ruta, input) {
    $(input).val('')
    $(input).select2({
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
