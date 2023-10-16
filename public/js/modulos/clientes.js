$(document).ready(function() {

    $('#listaclientes').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
    });
    $('#listaPedidos').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
    });

});

/**
 * Funcion que permite enviar un request para eliminar cliente
 * @param {object} cliente
 * @returns {void}
 */
function eliminarCliente(cliente) {

    var token = $('input[name="_token"]').val()

    var principalTitle =  `¿Está seguro de eliminar el cliente ${cliente.nombre}  ${cliente.nit}?`;
    var confirmButtonText = 'Si, eliminarlo!';
    var url =  `/clientes/${cliente.id}`;
    var tipo = "DELETE";
    var datos = { _token: token  };
    var titulo =  'Eliminado!';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);

}


/**
 * Funcion que permite enviar un request para restaurar cliente
 * @param {object} cliente
 * @returns {void}
 */
function restaurarCliente(cliente) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de restaurar el cliente ${cliente.name}  ${cliente.nit}?`;
    var confirmButtonText = 'Si, restaurar';
    var url =  `/restore-cliente/${cliente.id}`;
    var tipo = "PUT";
    var datos = { _token: token  };
    var titulo =  'Restaurado';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}
