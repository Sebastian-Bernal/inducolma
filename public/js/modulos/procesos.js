/**
 * valida si la cantidad de salida ingresada es 0, de ser asi asigna 0 a todos los inputs
 * con valor vacio.
 *
 * @param {integer} value
 */

function salidaCero(value) {
    if (value <= 0) {
        Swal.fire({
            title: 'Cantidad salida',
            text: '¿Está seguro que la cantidad de salida es 0?',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'Si',
            showDenyButton: true,
            denyButtonText: 'No'

        }).then((result) => {
            if (result.isConfirmed) {
                let inputs = $('#formSubpaqueta').find('input');
                $.each(inputs, function (index, value) {
                    if (value.value == '') {
                        value.value = 0;
                    }
                })
            }
            $('#cantidadSalida').focus();

        })
    }
}

/**
 * Pregunta al usuario si esta seguro de guardar, valida si el campo cantidad salida
 * es 0 y valida la funcion salidaCero
 */
function guardarSubpaqueta() {
    Swal.fire({
        title: 'Guardar subpaqueta',
        text: '¿Está seguro de guardar la subpaqueta?',
        icon: 'warning',
        confirmButtonColor: '#597504',
        confirmButtonText: 'Si',
        showDenyButton: true,
        denyButtonText: 'No'

    }).then((result) => {
        if (result.isConfirmed) {
            $('#formSubpaqueta').submit();
        }

    })
}

/**
 * Calcula los cm3 de salida se multiplica alto, largo y ancho
 */
function calcularCm3() {
    let alto = $('#alto').val();
    let largo = $('#largo').val();
    let ancho = $('#ancho').val();
    let cm3Salida = $('#cm3Salida');
    let cm3 = parseFloat(alto) * parseFloat(largo) * parseFloat(ancho);
    cm3Salida.val(cm3);

}

/**
 * Pregunta al usuario si esta seguro de terminar la orden
 */
function terminarOrden() {
    $('#terminar').val(3);
    Swal.fire({
        title: 'Terminar orden',
        text: '¿Está seguro de terminar la orden?',
        icon: 'warning',
        confirmButtonColor: '#597504',
        confirmButtonText: 'Si',
        showDenyButton: true,
        denyButtonText: 'No'

    }).then((result) => {
        if (result.isConfirmed) {
            $('#formSubpaqueta').submit();
        }

    })
}
