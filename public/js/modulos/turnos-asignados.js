$(document).ready(function () {

    $('#listaTurnosAsignados').DataTable({
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
                url: "/asignar-turnos/" + turno.id,
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

/**
 * Envia el usuario y las fechas desde y hasta, si encuentra turnos asignados
 * retorna los datos encontrados, sino envia el formulario para la asignacion de turnos
 *
 */

function comprobarTurno() {
    let usuario = $('#usuario');
    let desde = $('#desde').val();
    let hasta = $('#hasta').val();

    if (desde != '' || hasta != '') {

        $.ajax({
            url: "/turnos-usuario",
            type: "POST",
            dataType: "JSON",
            data: {
                usuario: usuario.val(),
                desde: desde,
                hasta: hasta,
                _token: $('input[name="_token"]').val()
            },
            success: function (e) {

                if (e.turno == false) {
                    $('#formAsignarTurno').submit();
                } else {
                    let lista = '<ul class="list">';
                    e.turnos.forEach(element => {
                        lista += '<li>' + element.fecha + '</li>';
                    });
                    lista += '</ul>';
                    Swal.fire({
                        title: '¡El usuario: ' + $('select[id="usuario"] option:selected').text() + ' ya tiene turnos asignados en las fechas!',
                        icon: 'warning',
                        html: lista,
                        confirmButtonColor: '#597504',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function (e) {
                Swal.fire({
                    title: 'No pudo ser eliminado!',
                    text: "El turno no pudo ser eliminado, contacte al administrador de la aplicación",
                    icon: 'success',
                    confirmButtonColor: '#597504',
                    confirmButtonText: 'OK'
                })
            }
        })
    } else {
        Swal.fire({
            title: 'seleccione las fechas!',
            text: 'Debe ingresar al menos el dato desde o la fecha en caso de actualizacion del registro',
            icon: 'error',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        });
    }
}
