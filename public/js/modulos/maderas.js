$(document).ready(function() {
    $('#listaMaderas').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
    });
} );


// funcion para eliminar un proveedor
function eliminarMadera(madera) {
        
        Swal.fire({
        title: `¿Está seguro de eliminar la madera ${madera.nombre}, ${madera.nombre_cientifico}?`,       
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
        if (result.isConfirmed) {
           $.ajax({
                url: `/maderas/${madera.id}`,
                type: "DELETE",
                dataType: "JSON",
                data: {
                    _token: $('input[name="_token"]').val()
                },
                success: function (e) {
                   
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