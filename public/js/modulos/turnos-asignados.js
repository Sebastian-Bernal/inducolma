$(document).ready(function() {

    $('#listaTipoMadera').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true

    });

});


/**
 * eliina el turno indicado, devuele la respuesta de confirmacion
 * @param {object} turno
 */
function eliminarTurnoAsignado(turno) {
    Swal.fire({
        title: '¿Está seguro de eliminar el turno?',
        text: 'Usuario : ' + turno.user.name + ' Fecha: ' + turno.fecha + ' Maquina: ' + turno.maquina.maquina,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/asignar-turnos/" +turno.id,
                type: "DELETE",
                dataType: "JSON",
                data: {
                    _token: $('input[name="_token"]').val()
                },
                success: function (e) {
                    console.log(e);
                    Swal.fire({
                        title: 'Eliminado!',
                        text: e.success,
                        icon: 'success',
                        confirmButtonColor: '#597504',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    })

                },
                error: function (e) {
                    // console.log(e);
                    Swal.fire({
                        title: 'No pudo ser eliminado!',
                        text: "El turno no pudo ser eliminado, contacte al administrador de la aplicación",
                        icon: 'success',
                        confirmButtonColor: '#597504',
                        confirmButtonText: 'OK'
                    })
                }
            })
        }
    })
}
