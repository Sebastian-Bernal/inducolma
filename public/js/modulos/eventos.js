$(document).ready(function() {

    $('#listaeventos').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true

    });

});

// funcion mayusculas descripcion
function mayusculas() {
    var x = document.getElementById("descripcion");
    x.value = x.value.toUpperCase();
}

/**
 * Funcion que permite enviar un request para eliminar evento
 * @param {object} evento
 * @returns {void}
 */
function eliminarEvento(evento) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de eliminar el evento ${evento.descripcion}?`;
    var confirmButtonText = 'Si, eliminarl!';
    var url =  `/eventos/${evento.id}`;
    var tipo = "DELETE";
    var datos = { _token: token  };
    var titulo =  'Eliminado!';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}

/**
 * Funcion que permite enviar un request para restaurar evento
 * @param {object} evento
 * @returns {void}
 */
function restaurarEvento(evento) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de restaurar el evento ${evento.descripcion} ?`;
    var confirmButtonText = 'Si, restaurar';
    var url =  `/restore-evento/${evento.id}`;
    var tipo = "PUT";
    var datos = { _token: token  };
    var titulo =  'Restaurado';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}
