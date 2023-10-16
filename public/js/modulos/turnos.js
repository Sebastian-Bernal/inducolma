$(document).ready(function() {

    $('#listaTipoMadera').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true

    });

});

function mayusculas() {

    var x = document.getElementById("turno");
    x.value = x.value.toUpperCase();

}

/**
 * Funcion que permite enviar un request para eliminar turno
 * @param {object} turno
 * @returns {void}
 */
function eliminarTurno(turno) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de eliminar el turno ${turno.turno}  ?`;
    var confirmButtonText = 'Si, eliminarl!';
    var url =  `/turnos/${turno.id}`;
    var tipo = "DELETE";
    var datos = { _token: token  };
    var titulo =  'Eliminado!';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}

