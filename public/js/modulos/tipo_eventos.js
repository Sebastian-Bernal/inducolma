$(document).ready(function() {

    $('#listatipo_eventos').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true

    });

});

// funcion mayusculas descripcion
function mayusculas() {
    var x = document.getElementById("tipo_evento");
    x.value = x.value.toUpperCase();
}


/**
 * Funcion que permite enviar un request para eliminar tipo_evento
 * @param {object} tipo_evento
 * @returns {void}
 */
function eliminarTipoEvento(tipo_evento) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de eliminar el tipo evento ${tipo_evento.tipo_evento}  ?`;
    var confirmButtonText = 'Si, eliminarl!';
    var url =  `/tipo-eventos/${tipo_evento.id}`;
    var tipo = "DELETE";
    var datos = { _token: token  };
    var titulo =  'Eliminado!';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}
