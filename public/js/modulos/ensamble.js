$(document).ready(function () {
    $('#listaOrdenes').DataTable({
        "language": {
            "url": "/DataTables/Spanish.json"
        },
        "responsive": true

    });
})


/**
 * Muestra mensaje al usuario si esta seguro de guardar,
 *
 */
function guardarProducto() {
    $('#terminar').val(1);
    Swal.fire({
        title: 'Guardar producto',
        text: '¿Está seguro de guardar el producto?',
        icon: 'warning',
        confirmButtonColor: '#597504',
        confirmButtonText: 'Si',
        showDenyButton: true,
        denyButtonText: 'No'

    }).then((result) => {
        if (result.isConfirmed) {
            $('#formProducto').submit();
        }

    })
}

/**
 * Muestra alerta al usaurio si esta seguro de terminar el pedido
 * cambia el valor del iput terminar
 */

function terminarPedido() {
    $('#terminar').val(2);
    Swal.fire({
        title: 'Terminar ensamble',
        text: '¿Está seguro de terminar ensamble?',
        icon: 'warning',
        confirmButtonColor: '#597504',
        confirmButtonText: 'Si',
        showDenyButton: true,
        denyButtonText: 'No'

    }).then((result) => {
        if (result.isConfirmed) {
            $('#formProducto').submit();
        }

    })
}
