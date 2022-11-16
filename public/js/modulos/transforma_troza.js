var TransformaPendiente = [];
var numProceso = 0;
var orden_id = $('#orden_id').val();
var cubicaje_id = $('#cubicaje_id').val();
var item_id = $('#item_id').val();
var pedido_id = $('#pedido_id').val();
// Agregar valores al local storage
comprobarLocalStorage()

function comprobarLocalStorage() {
    if (localStorage.getItem('TransformaPendiente') == null || localStorage.getItem('TransformaPendiente') == '[]') {
        TransformaPendiente = [];
    } else {
        TransformaPendiente = JSON.parse(localStorage.getItem('TransformaPendiente'));
        listarTransformaciones(TransformaPendiente);
    }
}

function guardarProceso() {

    let coincide = TransformaPendiente.filter(TransformaPendiente => TransformaPendiente.maquina == maquina).length
    if (coincide > 0) {
        Swal.fire({
            title: '¡Proceso ya fue definido!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        });

    } else {
        numProceso++;
        let rutaNum = numProceso;
        registroProceso = Object.assign({}, {  });
        TransformaPendiente.unshift(registroProceso);
        localStorage.setItem('TransformaPendiente', JSON.stringify(TransformaPendiente));
        listarTransformaciones(TransformaPendiente);
    };
};


// funcion listarTransformaciones, recibe un array de objetos y los muestra en la tabla
function listarTransformaciones(TransformaPendiente) {
    $('#listarTransformaciones').html('');
    TransformaPendiente.forEach(TransformaPendiente => {
        let fila = `<tr id ="${TransformaPendiente.loqueva}">
                        <td>${TransformaPendiente.loqueva}</td>
                        <td>${TransformaPendiente.loqueva}</td>
                        <td>${TransformaPendiente.loqueva}</td>
                        <td>${TransformaPendiente.loqueva}</td>
                        <td>${TransformaPendiente.loqueva}</td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarMadera(${TransformaPendiente.loqueva})"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>`;
        $('#listarTransformaciones').append(fila);

    })

}

function eliminarMadera(rutaNum) {

    Swal.fire({
        title: '¿Está seguro que desea eliminar el proceso?',
        text: "¡No podrá revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#ccc',
        confirmButtonText: '¡Si, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        //$(`#${id}`).remove();
        if (result.isConfirmed) {
            TransformaPendiente = TransformaPendiente.filter(TransformaPendiente => TransformaPendiente.loqueva != loqueva);
            localStorage.setItem('TransformaPendiente', JSON.stringify(TransformaPendiente));
            listarTransformaciones(TransformaPendiente);
        }
    })
}
