$(document).ready(function() {

    $('#listarecepcion').DataTable({
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

// funcion para eliminar un Insumo
function eliminarRecepcion(recepcion) {
    Swal.fire({
        title: `¿Está seguro de eliminar el recepcion:
                ${recepcion.nombre_completo}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
        if (result.isConfirmed) {
           $.ajax({
                url: `/recepcion/${recepcion.id}`,
                type: "DELETE",
                dataType: "JSON",
                data: {
                    _token: $('input[name="_token"]').val()
                },
                success: function (e) {
                   // console.log(e);
                    Swal.fire({
                        title: 'Eliminado!',
                        text: e.success,
                        icon: 'success',
                        confirmButtonColor: '#597504',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    })

                },
          })
        }
    })
}

// funcion consultaUsuario, para verificar si el usuario existe en la base de datos, si existe, se permite el registro
// como visitante o como usuario registrado, si no existe se permite registrar como visitante
function ingersoVisitante() {

    var usuario = $('#cc').val();
    var primer_nombre = $('#primer_nombre').val();
    var primer_apellido = $('#primer_apellido').val();
    if (usuario == '' || primer_nombre == '' || primer_apellido == '') {
        Swal.fire({
            title: '¡Error!',
            text: 'Debe ingresar los campos numero de cédula, primer nombre y primer apellido',
            icon: 'error',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
    } else {
        $.ajax({
        url: `/recepcion`,
        type: "post",
        dataType: "JSON",
        data: {
            cc: usuario,
            primer_nombre: primer_nombre,
            primer_apellido: primer_apellido,
            _token: $('input[name="_token"]').val()
        },
        success: function (e) {
                if (e.error == false) {
                    limpiarForm();
                    Swal.fire({
                        position: 'top-end',
                        title: e.title,
                        text: e.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 3000
                    })
                } else {
                    Swal.fire({
                        title: '¡Error!',
                        text: 'Algo salió mal, no se pudo hacer el registro',
                        icon: 'error',
                        confirmButtonColor: '#597504',
                        confirmButtonText: 'OK'
                    })
                }

            },
        error: function (jqXHR, textStatus, errorThrown) {

            Swal.fire({
                title: jqXHR.responseJSON.errors.cc,
                text: errorThrown,
                icon: 'error',
                confirmButtonColor: '#597504',
                confirmButtonText: 'OK'
            })
            }
        })
    }


}

/**
 * Funcion horaSalida, actualiza la hora de salida de la persona.
 * redirecciona al index
 */

function horaSalida(recepcion) {

    Swal.fire({
        title: `¿Está seguro de dar salida a:
                ${recepcion.nombre_completo}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/recepcion/${recepcion.id}`,
                type: "DELETE",
                dataType: "JSON",
                data: {
                    _token: $('input[name="_token"]').val(),
                },
                success: function (e) {
                    if (e.error == false) {
                        Swal.fire({
                            position: 'top-end',
                            title: e.title,
                            text: e.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        })
                    } else {
                        Swal.fire({
                            title: '¡Error!',
                            text: 'No se pudo registrar la salida',
                            icon: 'error',
                            confirmButtonColor: '#597504',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            })

        }})



}

/**
 * funcion limpiarForm, limpia el formulario de registro de recepcion
 */

function limpiarForm() {
    $('#formRecepcion').trigger("reset");
}

/**
 * Funcion cedula extrae la cedula de ua cadena de
 */
 function cedula(){
    //validar el keypress
    var key = window.event.keyCode;
    if (key == 13 || key ==9){
        var cedula = $('#cc_empleado').val();
        var cedula = cedula.substring(0,10);
        $('#cc_empleado').val(cedula);

        guardarIngresoEmpleado(cedula);

    }

 }
//  $('#cc_empleado').blur(function(){

//     var cedula = $('#cc_empleado').val();
//     if (cedula != '') {
//         var cedula = cedula.substring(0,10);
//         $('#cc_empleado').val(cedula);
//         guardarIngresoEmpleado(cedula);
//         $(this).focus();
//     }else{
//         $('#cc_empleado').focus();
//     }
// })

/**
  * Funcion guardarIngresoEmpleado, guarda el ingreso de un empleado
*/

function guardarIngresoEmpleado(cedula){
    $('#cc_empleado').val('');
    $.ajax({
        url: `/recepcion-empleado`,
        type: "post",
        dataType: "JSON",
        data: {
            cedula: cedula,
            _token: $('input[name="_token"]').val()
        },
        success: function (e) {
            if (e.error == false) {
                $('#cc_empleado').focus();

                Swal.fire({
                    position: 'top-end',
                    title: e.title,
                    text: e.message,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                })

            } else {
                $('#cc_empleado').focus();
                $('#cc_empleado').val('');
                Swal.fire({
                    title: '¡Error!',
                    text: e.message,
                    icon: 'error',
                    confirmButtonColor: '#597504',
                    confirmButtonText: 'OK'
                })
            }
        }
    })
}

/**
  * Funcion guardarIngresoContratista, guarda el ingreso de un contratista
*/
/**
 * Funcion cedula extrae la cedula de ua cadena de
 */
 function cedulaContratista(){
    //validar el keypress
    var key = window.event.keyCode;
    if (key == 13 || key ==9){
        var cedula = $('#cc_contratista').val();
        var cedula = cedula.substring(0,10);
        $('#cc_contratista').val(cedula);

        guardarIngresoContratista(cedula);

    }

 }

function guardarIngresoContratista(cedula){
    $('#cc_contratista').val('');
    $.ajax({
        url: `/recepcion-contratista`,
        type: "post",
        dataType: "JSON",
        data: {
            cedula: cedula,
            _token: $('input[name="_token"]').val()
        },
        success: function (e) {
            if (e.error == false) {
                $('#cc_contratista').focus();

                Swal.fire({
                    position: 'top-end',
                    title: '¡Ingreso registrado!',
                    text: e.message,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                })

            } else {
                $('#cc_contratista').focus();
                $('#cc_contratista').val('');
                Swal.fire({
                    title: '¡Error!',
                    text: e.message,
                    icon: 'error',
                    confirmButtonColor: '#597504',
                    confirmButtonText: 'OK'
                })
            }
        }
    })
}
