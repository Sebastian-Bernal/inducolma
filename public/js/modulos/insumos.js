$(document).ready(function() {

    $('#listainsumos').DataTable({
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
 * Funcion que permite enviar un request para eliminar insumo
 * @param {object} insumo
 * @returns {void}
 */
function eliminarInsumo(insumo) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de eliminar el insumo ${insumo.descripcion}?`;
    var confirmButtonText = 'Si, eliminarl!';
    var url =  `/insumos-almacen/${insumo.id}`;
    var tipo = "DELETE";
    var datos = { _token: token  };
    var titulo =  'Eliminado!';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}


/**
 * Funcion que permite enviar un request para restaurar insumo
 * @param {object} insumo
 * @returns {void}
 */
function restaurarInsumo(insumo) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de restaurar el insumo ${insumo.descripcion}?`;
    var confirmButtonText = 'Si, restaurar';
    var url =  `/restore-insumo/${insumo.id}`;
    var tipo = "PUT";
    var datos = { _token: token  };
    var titulo =  'Restaurado';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}
