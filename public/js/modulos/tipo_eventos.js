$(document).ready(function() {

    $('#listatipo_eventos').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
        
    });
    
});

// funcion mayusculas descripcion 
function mayusculas() {
    var x = document.getElementById("tipo_evento");
    x.value = x.value.toUpperCase();
}

// funcion para eliminar un usuario
function eliminarTipoEvento(tipo_evento) {
    Swal.fire({
        title: `¿Está seguro de eliminar el tipo_evento:
                   ${tipo_evento.tipo_evento}?`,       
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
        if (result.isConfirmed) {
           $.ajax({
                url: `/tipo-eventos/${tipo_evento.id}`,
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