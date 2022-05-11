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
function consultaUsuario() {
   
    var usuario = $('#cc').val();
    $.ajax({
        url: `/recepcion-usuraio`,
        type: "post",
        dataType: "JSON",
        data: {
            usuario: usuario,
            _token: $('input[name="_token"]').val()
        },
        success: function (e) {
            if (e.success == false) {
               $('#formRecepcion').submit();
            } else {
                if($('#visitante').val() == '0'){
                    Swal.fire({
                        title: '¡La persona no hace parte de los empleados, debe registrarla como visitante !',
                        icon: 'warning',
                        confirmButtonColor: '#597504',
                        confirmButtonText: 'OK'
                    })
                }
                else{
                    $('#formRecepcion').submit();
                }
            } 
        } 
    })

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
                            title: '¡Salida registrada!',
                            text: e.success,
                            icon: 'success',
                            confirmButtonColor: '#597504',
                            confirmButtonText: 'OK'
                        })
                        location.reload();
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
 * funcion focusCc, selecciona el input cc
 */
function focusCc() {
    //console.log('focus');
  document.getElementById('cc').focus();
    
}