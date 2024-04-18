let estado = 'INICIAL'
$(document).ready(function () {
    $('#listaOrdenes').DataTable({
        "language": {
            "url": "/DataTables/Spanish.json"
        },
        "responsive": true

    });
})

/**
 * guarda el estado de la maquina y actualiza la clase de los botones cuando el resultado es exitoso *
 *
 * @param {object} estado
 * @param {int} maquina
 */
function estadoDeMaquina(estado, maquina) {
    $.ajax({
        url: `/guardar-estado`,
        type: "POST",
        dataType: "JSON",
        data: {
            estado_id: estado.id,
            maquina_id: maquina.id,
            _token: $('input[name="_token"]').val()
        },
        success: function (e) {
            if (e.error) {
                if (estado.id == '1') {
                    Swal.fire({
                        title: e.mensaje,
                        text: '¿Esta seguro que deberia estar encendida?',
                        icon: 'warning',
                        confirmButtonColor: '#597504',
                        confirmButtonText: 'Si',
                        showDenyButton: true,
                        denyButtonText: 'No'

                    }).then((result) => {
                        if (result.isDenied) {
                            console.log('cambia estado ');
                            apagarMaquina(estado, maquina);
                            let timeout;
                            timeout = setTimeout(() =>{
                                $('#eventosDeMaquina').click();
                                $('#tipoEvento2').click();
                            }, 3000)

                        }

                    })
                } else {
                    alertaErrorSimple(e.mensaje, 'warning');
                }

            } else {
                alertaErrorSimple(e.mensaje, 'success');
                $('#estadoMaquina :button').removeClass("btn-primary btn-secondary").addClass('btn-secondary');
                $('#estado' + estado.id).removeClass("btn-secondary").addClass('btn-primary');


            }
        },
    })
}

/**
 *
 * @param {object} estado
 * @param {} maquina
 */

function apagarMaquina(estado, maquina){
    $.ajax({
        url: `/apagar-maquina`,
        type: "POST",
        dataType: "JSON",
        data: {
            estado_id: estado.id,
            maquina_id: maquina.id,
            _token: $('input[name="_token"]').val()
        },
        success: function (e) {
            if (e.error) {
                alertaErrorSimple(e.mensaje, 'error');
            } else {
                //alertaErrorSimple(e.mensaje, 'success');
                estadoDeMaquina(estado, maquina);
            }

        },
        error: function (error) {
            console.log(error);
        }


    })
}
