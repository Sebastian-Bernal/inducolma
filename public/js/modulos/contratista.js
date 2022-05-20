$(document).ready(function() {

    $('#listacontratistas').DataTable({
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

// funcion para eliminar un usuario
function eliminarContratista(contratista) {
    Swal.fire({
        title: `¿Está seguro de eliminar el contratista:
                ${contratista.cedula},   ${contratista.primer_nombre}, ${contratista.primer_apellido}?`,       
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
        if (result.isConfirmed) {
           $.ajax({
                url: `/contratistas/${contratista.id}`,
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