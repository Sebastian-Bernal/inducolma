/**
 * valida los datos para generar los reportes
 */
function reportePedidos() {

    let desde = $('#pedidoDesde');
    let hasta = $('#pedidoHasta');
    let cliente = $('#filtroPedido1');
    let pedido = $('#nPedido');
    let reporte = $('#tipoReportePedidos');

    if (reporte.val() == "" ) {
        alertaErrorSimple('Seleccione un tipo de reporte de pedidos!', 'error');
    }
    else {
        if ((Array('2', '3', '5').includes(reporte.val())) && (cliente.val() == "")) {
            alertaErrorSimple('Seleccione el cliente ', 'error');
            cliente.click();
        } else if((reporte.val() == '1') && (desde.val() == "" || hasta.val() == "")){
            alertaErrorSimple('Seleccione el cliente y los rangos de fechas');
        } else if(reporte.val() == '4' && (desde.val() == "" || hasta.val() == "")){
            alertaErrorSimple('Seleccioneun rango de fehcas ');
        } else if((Array('6','7').includes(reporte.val())) && pedido.val() == ""){
            alertaErrorSimple('Ingrese el numero de pedido ');
        } else {
            $('#formReportePedidos').submit();
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
function datoEspecificoPedidos() {

    let reporte = $('#tipoReportePedidos');

    let especifico = $('#filtroPersonal');
    if (reporte.val() == '1' || reporte.val() == '2' || reporte.val() == '3' || reporte.val() == '5'){
        dataSelect('/get-clientes');
    }else if (reporte.val() == '6' || reporte.val() == '7'){
        $('#divEspecifico2').show(300);
        $('#divEspecifico').hide(300);
    }
    else {
        $('#divEspecifico').hide(300);
        $('#divEspecifico2').hide(300);
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

    $('#divEspecifico2').hide(300);
    $('#filtroPedido1').val('')
    $('#divEspecifico').show(300);
    $('#filtroPedido1').select2({
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
