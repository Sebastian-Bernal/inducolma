$(document).ready(function () {
    $('#listaUsuarios').DataTable({
        "language": {
            "url": "/DataTables/Spanish.json"
        },
        "responsive": true
    });
});
// funcion para eliminar un usuario
function eliminarUsuario(id, name) {
    Swal.fire({
        title: '¿Está seguro de eliminar el usuario con id ' + name + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/usuarios/" + id,
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

//funcion para editar un usuario
function editarUsuario(id) {
    console.log(id);
    $.ajax({
        url: "/usuarios.show/" + id,
        type: "GET",
        dataType: "JSON",
        // data: {
        //     _token: $('input[name="_token"]').val()
        // },
        success: function (e) {
            $("#id").val(e.id);
            $("#nombre").val(e.nombre);
            $("#apellido").val(e.apellido);
            $("#email").val(e.email);
            $("#telefono").val(e.telefono);
            $("#direccion").val(e.direccion);
            $("#rol").val(e.rol);
            $("#estado").val(e.estado);
            $("#modalEditarUsuario").modal("show");
        },
    })
}


// funcion para eliminar un usuario
function restaurarUsuario(id, name) {
    console.log(id)
    Swal.fire({
        title: '¿Está seguro de restaurar el usuario con id ' + name + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, restaurar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/restore-user/" + id,
                type: "PUT",
                dataType: "JSON",
                data: {
                    _token: $('input[name="_token"]').val()
                },
                success: function (e) {
                    // console.log(e);
                    Swal.fire({
                        title: 'Restaurado!',
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
