
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
