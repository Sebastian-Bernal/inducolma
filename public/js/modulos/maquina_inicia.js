document.getElementById("users").click();

// listado de usuarios auxiliares
function listaUser(turnos){

    if (turnos == undefined) {
        $('#listarUsers').append('ningun usuario asignado a este turno');
    } else {
        $('#listarUsers').html('');
        turnos.forEach(turno => {
            if (turno.asistencia == null) {
                let fila = `<tr id ="${ turno.id }">
                            <td style="display:none">${ turno.id }</td>
                            <td >${ turno.user.name }</td>
                            <td style="display:none">${ turno.user_id }</td>
                            <td><button type="button" class="btn btn-primary" onclick="confirmarTurno(${ turno.turno_id }, ${ turno.user_id }, ${ turno.maquina_id}, ${ 1 })"><i class="text-white fa-solid fa-check"></i></button>
                            <button type="button" class="btn btn-danger" onclick="faltaTurno(${ turno.turno_id }, ${ turno.user_id }, ${ turno.maquina_id})"><i class="text-white fas fa-xmark"></i></button></td>
                            </tr>  `;
                $('#listarUsers').append(fila);
            }

        })
    }


}
/**
 * envia peticion Ajax para confirmar la asistencia del usuario, retorna mensaje de confirmacion
 * o error, y lista los usuarios
 *
 * @param {integer} turno_id
 * @param {integer} user_id
 * @param {integer} maquina_id
 * @param {boolean} estado
 */
function confirmarTurno(turno_id, user_id, maquina_id, estado){
    let title;
    if(estado == 0 ){
        title = 'Esta seguro de asignar la falta al usuario?';
    } else {
        title = '¡Esta seguro de confirar el turno para el usuario!';
    }

    Swal.fire({
        title: title,
        icon: 'warning',
        confirmButtonColor: '#597504',
        confirmButtonText: 'Si',
        showDenyButton: true,
        denyButtonText: 'No'

    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/guardar-asistencia`,
                type: "POST",
                dataType: "JSON",
                data: {
                    turno_id: turno_id,
                    usuario_id: user_id,
                    maquina_id: maquina_id,
                    estado: estado,
                    _token: $('input[name="_token"]').val()
                },
                beforeSend: function (e) {
                    $('#spinnerAuxiliares').append(
                        `<div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                        </div>`
                    );
                },
                success: function (e) {
                    $('#spinnerAuxiliares').html('');

                    if (e.usuarios.length <= 0) {
                        location.reload();
                    }
                    listaUser(e.usuarios);
                    alertaErrorSimple(e.mensaje, 'success');
                },

                error: function (error){
                    console.log(error);
                }
            })
        }
    })
}

/**
 * envia la peticion Ajax para asignar la falta del usuario al proceso del dia retorna
 * mensaje de confirmacion o error
 *
 * @param {integer} turnoUsuario
 * @param {integer} usuarioId
 */
var turnoUsuario, usuarioId, maquina;
function faltaTurno(turno, usuario, maquina){

    Swal.fire({
        title: '¡Esta seguro de asignar falta al usuario asignado!',
        icon: 'warning',
        confirmButtonColor: '#597504',
        confirmButtonText: 'Si',
        showDenyButton: true,
        denyButtonText: 'No'

    }).then((result) => {

        if (result.isConfirmed) {
            $('#eventualidad').click();
            turnoUsuario = turno;
            usuarioId = usuario;
            maquinaId = maquina;
        }
    })
}

/**
 * guarda la eventualidad del usuario
 *
 * @param {integer} userId {usuario que registra el evento}
 */

function guardaEvento(userId){

    let eventoId = $('select[name="eventos"] option:selected');
    let observacion = $('#observacionEvento').val();
    $.ajax({
        url: `/guardar-eventualidad`,
        type: "POST",
        dataType: "JSON",
        data: {
            proceso_id: maquinaId,
            evento_id: eventoId.val(),
            user_id: userId,
            observaciones: observacion,
            _token: $('input[name="_token"]').val()
        },
        success: function (e) {
            if (e.error) {
                alertaErrorSimple(e.message, 'error');
            }else {
                alertaErrorSimple(e.mensaje, 'success');
                confirmarTurno(turnoUsuario, usuarioId, maquinaId, 0);
                $('#cerrarEvento').click();
            }

        },
        error: function (error){
            console.log(error);
        }


    })
}
