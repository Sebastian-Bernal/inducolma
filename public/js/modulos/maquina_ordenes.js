let estado = 'INICIAL'
$(document).ready(function () {
    $('#listaOrdenes').DataTable({
        "language": {
            "url": "/DataTables/Spanish.json"
        },
        "responsive": true

    });
})

/**
 * guarda el estado de la maquina y actualiza la clase de los botones cuando el resultado es exitoso *
 *
 * @param {object} estado
 * @param {int} maquina
 */
function estadoDeMaquina(estado, maquina) {
    $.ajax({
        url: `/guardar-estado`,
        type: "POST",
        dataType: "JSON",
        data: {
            estado_id: estado.id,
            maquina_id: maquina,
            _token: $('input[name="_token"]').val()
        },
        success: function (e) {
            if (e.error) {
                if (estado.id == '1') {
                    Swal.fire({
                        title: e.mensaje,
                        text: '¿Esta seguro que deberia estar encendida?',
                        icon: 'warning',
                        confirmButtonColor: '#597504',
                        confirmButtonText: 'Si',
                        showDenyButton: true,
                        denyButtonText: 'No'

                    }).then((result) => {
                        if (result.isDenied) {
                            console.log('cambia estado ');
                            apagarMaquina(estado, maquina);
                        }

                    })
                } else {
                    alertaErrorSimple(e.mensaje, 'warning');
                }

            } else {
                alertaErrorSimple(e.mensaje, 'success');
                $('#estadoMaquina :button').removeClass("btn-primary btn-secondary").addClass('btn-secondary');
                $('#estado' + estado.id).removeClass("btn-secondary").addClass('btn-primary');


            }
        },
    })
}

/**
 * Muestra los eventos, filtrando los eventos de acuerdo al tipo de evento
 *
 * @param {int} tipo_evento [ descripcion]
 * @param {object} eventos
 */

function listarEventos(tipo_evento, eventos) {

    let invalidEntries = 0;
    function filterByID(item) {
        if (item.tipo_evento_id == tipo_evento) {
            return true;
        }
        invalidEntries++;
        return false;
    }
    const arrByID = eventos.filter(filterByID);
    $('#modalEventos').click();
    $('#listaEventos').empty();
    if (arrByID.length > 0) {
        $.each(arrByID, function (index, value) {
            $('#listaEventos').append('<option value="' + value.id + '">' + value.descripcion + '</option>');
        });
    } else {
        $('#listaEventos').append('<option value="0" selected >NO se encontraron eventos</option>');
    }
}


/**
 * guarda la eventualidad de la maquina
 *
 * @param {integer} userId {usuario que registra el evento}
 */

function guardaEvento(userId, maquinaId) {

    let eventoId = $('select[name="eventosMaquina"] option:selected');
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
                alertaErrorSimple(e.mensaje, 'error');
            } else {
                alertaErrorSimple(e.mensaje, 'success');
                $('#cerrarEvento').click();
            }

        },
        error: function (error) {
            console.log(error);
        }


    })
}

/**
 *
 * @param {object} estado
 * @param {} maquina
 */

function apagarMaquina(estado, maquina){
    $.ajax({
        url: `/apagar-maquina`,
        type: "POST",
        dataType: "JSON",
        data: {
            estado_id: estado.id,
            maquina_id: maquina,
            _token: $('input[name="_token"]').val()
        },
        success: function (e) {
            if (e.error) {
                alertaErrorSimple(e.mensaje, 'error');
            } else {
                alertaErrorSimple(e.mensaje, 'success');
                estadoDeMaquina(estado, maquina);
            }

        },
        error: function (error) {
            console.log(error);
        }


    })
}
