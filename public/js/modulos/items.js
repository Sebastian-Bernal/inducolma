$(document).ready(function() {

    $('#listaitems').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true

    });

});

// funcion cambia a mayusculas el input descripcion
function mayusculas() {
    var x = document.getElementById("descripcion");
    x.value = x.value.toUpperCase();
}


/**
 * Funcion que permite enviar un request para eliminar item
 * @param {object} item
 * @returns {void}
 */
function eliminarItem(item) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de eliminar el item ${item.descripcion}?`;
    var confirmButtonText = 'Si, eliminarl!';
    var url =  `/items/${item.id}`;
    var tipo = "DELETE";
    var datos = { _token: token  };
    var titulo =  'Eliminado!';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}
