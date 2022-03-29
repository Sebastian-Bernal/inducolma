$(document).ready(function() {
    $('#listaUsuarios').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
    });
} );


// funcion para eliminar un proveedor
function eliminarUsuario(proveedor) {
        console.log(proveedor.identificacion);
        Swal.fire({
        title: `¿Está seguro de eliminar el proveedor ${proveedor.nombre} con id ${proveedor.identificacion}?`,       
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
        if (result.isConfirmed) {
           $.ajax({
                url: `/proveedores/${proveedor.id}`,
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