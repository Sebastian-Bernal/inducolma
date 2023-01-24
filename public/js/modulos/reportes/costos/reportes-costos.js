/**
 * valida los datos para generar los reportes
 */
function reporteCostos() {

    let desde = $('#costoDesde');
    let hasta = $('#costoHasta');
    let maquina = $('#maquina');
    let item = $('#item');
    let usuario = $('#usuario');
    let pedido = $('#pedido');
    let reporte = $('#tipoReporteCosotos');

    if (reporte.val() == "" ) {
        alertaErrorSimple('Seleccione un tipo de reporte!', 'error');
    }
    else {
        if ((Array('1').includes(reporte.val())) && (maquina.val() == "" || desde.val() == "" || hasta.val() == "" )) {
            alertaErrorSimple('Seleccione la maquina y el rango de fechas', 'warning');
            maquina.focus();

        } else if((Array('2').includes(reporte.val())) && (desde.val() == "" || hasta.val() == "" || item.val() == "" || usuario.val() == "")){
            alertaErrorSimple('Seleccione el usuario y el rango de fechas', 'warning');

        } else if((Array('3').includes(reporte.val())) && (desde.val() == "" || hasta.val() == "" ||pedido.val() == ""  )){
            alertaErrorSimple('Ingrese el numero de pedido y el rango de fehcas ', 'warning');

        } else if((reporte.val() == '4') && (item.val() == "" || desde.val() == "" || hasta.val() == "" )){
            alertaErrorSimple('seleccione el item y el rango de fechas', 'warning');
        }
        else {
            $('#formReporteCostos').submit();
            desde.val('');
            hasta.val('');
            reporte.val('');
            maquina.val('');
            item.val('');
            usuario.val('');
            pedido.val('');
        }
    }
}

/**
 * muestra el input del dato especifico cargado con los datos de la opcion seleccionada
 *
 * @returns {void}
 */
function datoEspecificoCostos() {

    let reporte = $('#tipoReporteCosotos');

    if (Array('1').includes(reporte.val())){
        $('#divEspecifico').show(300);
        $('#divEspecifico1').hide(300);
        $('#divEspecifico2').hide(300);
        $('#divEspecifico3').hide(300);
        dataSelect('/get-maquinas','#maquina');
    }else if (reporte.val() == '2'){
        $('#divEspecifico').hide(300);
        $('#divEspecifico1').show(300);
        $('#divEspecifico2').hide(300);
        $('#divEspecifico3').hide(300);
        dataSelect('/get-empleados','#usuario');
    }else if(reporte.val() == '3'){
        $('#divEspecifico').hide(300);
        $('#divEspecifico1').hide(300);
        $('#divEspecifico2').show(300);
        $('#divEspecifico3').hide(300);
    } else if(reporte.val() == '4'){
        $('#divEspecifico').hide(300);
        $('#divEspecifico1').hide(300);
        $('#divEspecifico2').hide(300);
        $('#divEspecifico3').show(300);
        dataSelect('/get-items','#item');
    }
    else {
        $('#divEspecifico').hide(300);
        $('#divEspecifico1').hide(300);
        $('#divEspecifico2').hide(300);
        $('#divEspecifico3').show(300);
    }
}


/**
 * envia el formulario para generar el tipo de reporte segun la seleecion del usuario
 *
 * @param {String} tipo_reporte [ numero del tipo de reporte ]
 */

function generarReporteCostos(tipo_reporte) {
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
