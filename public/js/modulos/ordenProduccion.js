// funcion para eliminar un usuario
function eliminarOrdenProduccion(id, pedido_id) {
    Swal.fire({
        title: '¿Está seguro de eliminar la orden de produccion ' + id + '?',
        text: 'No se podra revertir la acción ',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'red',
        cancelButtonColor: '#597504',
        color: 'white',
        background: 'rgba(238,16,0,0.9)',
        //backdrop: 'rgba(238,16,0,0.4)',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/programaciones/${id}`,
                type: "DELETE",
                dataType: "JSON",
                data: {
                    _token: $('input[name="_token"]').val()
                },
                success: function (e) {
                    if (e.error == false) {
                        Swal.fire({
                            title: 'Eliminado!',
                            text: e.mensaje,
                            icon: 'success',
                            confirmButtonColor: '#597504',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "/programaciones/"+pedido_id;
                            }
                        })
                    } else {
                        alertaErrorSimple(e.mensaje,'error');
                    }
                },
                error: function (e) {
                    alertaErrorSimple('Error interno del servidor 500, comuniquese con el administrador de la aplicacion ','error');
                }
            })
        }
    })
}
