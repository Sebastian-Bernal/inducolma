$(document).ready(function() {

    $('#listacontratistas').DataTable({
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
 * Funcion que permite enviar un request para eliminar contratista
 * @param {object} contratista
 * @returns {void}
 */
function eliminarContratista(contratista) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de eliminar el contratista ${contratista.nombre}?`;
    var confirmButtonText = 'Si, eliminarl!';
    var url =  `/contratistas/${contratista.id}`;
    var tipo = "DELETE";
    var datos = { _token: token  };
    var titulo =  'Eliminado!';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}


/**
 * Funcion que permite enviar un request para restaurar contratista
 * @param {object} contratista
 * @returns {void}
 */
function restaurarContratista(contratista) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de restaurar el contratista ${contratista.name}  ${contratista.id}?`;
    var confirmButtonText = 'Si, restaurar';
    var url =  `/restore-contratista/${contratista.id}`;
    var tipo = "PUT";
    var datos = { _token: token  };
    var titulo =  'Restaurado';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}
