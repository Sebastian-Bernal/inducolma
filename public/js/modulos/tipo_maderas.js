$(document).ready(function() {

    $('#listaTipoMadera').DataTable({
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
 * Funcion que permite enviar un request para eliminar tipoMadera
 * @param {object} tipoMadera
 * @returns {void}
 */
function eliminarTipoMadera(tipoMadera) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de eliminar el tipo Madera ${tipoMadera.descripcion}?`;
    var confirmButtonText = 'Si, eliminarl!';
    var url =  `/tipos-maderas/${tipoMadera.id}`;
    var tipo = "DELETE";
    var datos = { _token: token  };
    var titulo =  'Eliminado!';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}


/**
 * Funcion que permite enviar un request para restaurar tipoMadera
 * @param {object} tipoMadera
 * @returns {void}
 */
function restaurarTipoMadera(tipoMadera) {

    var token = $('input[name="_token"]').val();

    var principalTitle =  `¿Está seguro de restaurar el tipo Madera ${tipoMadera.name} ?`;
    var confirmButtonText = 'Si, restaurar';
    var url =  `/restore-tipomadera/${tipoMadera.id}`;
    var tipo = "PUT";
    var datos = { _token: token  };
    var titulo =  'Restaurado';

    var alertName= AlertSimpleRequestManager.getInstance();
    alertName.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo);
}
