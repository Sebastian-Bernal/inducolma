$(document).ready(function() {
    $('#listaMaderas').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
    });
} );

/**
 * Funcion que permite enviar un request para eliminar madera
 * @param {object} madera
 * @returns {void}
 */
function eliminarMadera(madera) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de eliminar el madera ${madera.nombre_cientifico}  ?`;
    var confirmButtonText = 'Si, eliminarl!';
    var url =  `/maderas/${madera.id}`;
    var tipo = "DELETE";
    var datos = { _token: token  };
    var titulo =  'Eliminado!';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}
