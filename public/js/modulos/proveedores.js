


$(document).ready(function () {
    $('#listaUsuarios').DataTable({
        "language": {
            "url": "/DataTables/Spanish.json"
        },
        "responsive": true
    });
});


// funcion para eliminar un proveedor
function eliminarUsuario(proveedor) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de eliminar el proveedor ${proveedor.nombre} con id ${proveedor.identificacion}?`;
    var confirmButtonText = 'Si, eliminar';
    var url = `/proveedores/${proveedor.id}`;
    var tipo = "DELETE";
    var datos = { _token: token };
    var titulo = 'Eliminado';

    var alertProveedor = AlertSimpleRequestManager.getInstance();
    alertProveedor.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);

}


/**
 * Funcion permite enviar request para restaurar un proveedor eliminado
 * @param proveedor [ objeto proveedor ]
 * @returns void
 */


function restaurarProveedor(proveedor) {

    var token = $('input[name="_token"]').val();

    var principalTitle = `¿Está seguro de restaurar el proveedor ${proveedor.nombre} con id ${proveedor.identificacion}?`;
    var confirmButtonText = 'Si, restaurar!';
    var url = `/restore-proveedor/${proveedor.id}`;
    var tipo = "PUT";
    var datos = { _token: token };
    var titulo = 'Restaurado';

    var alertProveedor = AlertSimpleRequestManager.getInstance();
    alertProveedor.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);

}
