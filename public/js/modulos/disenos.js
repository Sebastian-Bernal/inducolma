$(document).ready(function() {

    $('#listadisenos').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
    });
    
    $('#madera_id').select2({
        width: 'resolve',
        placeholder: 'Seleccione...',
        dropdownParent: $("#creadiseno"),
        theme: "bootstrap-5",
    });

    $('#madera_id_edit').select2({
        width: 'resolve',
        placeholder: 'Seleccione...',      
        theme: "bootstrap-5",
    });


    $('#cliente_id').select2({
        width: 'resolve',
        placeholder: 'Seleccione...',
        dropdownParent: $("#creadiseno"),
        theme: "bootstrap-5",
        
    });
    
});


// funcion para eliminar un usuario
function eliminarDiseno(diseno) {
    Swal.fire({
        title: `¿Está seguro de eliminar el diseño:
                ${diseno.descripcion} ?`,       
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
        if (result.isConfirmed) {
           $.ajax({
                url: `/disenos/${diseno.id}`,
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

// funcion para validar datos de un diseno 
function validarDatosDiseno(diseno) {
    let cliente = $('#cliente_id').val();
    if (cliente =='') {
        Swal.fire({
            title: 'Error!',
            text: 'Debe seleccionar un cliente',
            icon: 'error',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
        } else {
            buscarItemsInsumos(diseno);
        }

   
}

// funcion buscar items e insumos 
function buscarItemsInsumos(diseno) {
    $('#spAsignar').html(
        `<div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>`
    );
    $.ajax({
        url: `/disenos-items-insumos`,
        type: "post",
        dataType: "JSON",
        data: {
            _token: $('input[name="_token"]').val(),
            diseno_id: diseno.id
        },
        success: function (e) {
            if(e.error == true){
                $('#spAsignar').empty();
                Swal.fire({
                    title: '¡Error al asignar diseño!',
                    text: e.message,
                    icon: 'error',
                    confirmButtonColor: '#597504',
                    confirmButtonText: 'OK'
                });
            } else {
                $('#spAsignar').empty();
                asignarDiseno(diseno);
            }
            
            
        },
    })
}

// funcion para asignar un diseño a un cliente
function asignarDiseno(diseno) {
    var cliente = $('#cliente_id').val();
    if (cliente =='') {
        Swal.fire({
            title: 'Error!',
            text: 'Debe seleccionar un cliente',
            icon: 'error',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
        } else {
            Swal.fire({
            title: `¿Está seguro de asignar el diseño:
                    ${diseno.descripcion} ?`,       
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#597504',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, asignarlo!',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
            if (result.isConfirmed) {
            $.ajax({
                    url: `/disenos-cliente`,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        _token: $('input[name="_token"]').val(),
                        cliente_id: $('#cliente_id').val(),
                        diseno_id: diseno.id
                    },
                    success: function (e) {
                        console.log(e.error); 
                        if (e.error == true ) {
                            Swal.fire({
                                title: '¡Error al asignar el diseño!',
                                text: e.message,
                                icon: 'error',
                                confirmButtonColor: '#597504',
                                confirmButtonText: 'OK'
                            });
                        } else{
                            Swal.fire({
                                position: 'top-end',
                                title: '¡Asignado!',
                                text: e.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                    },
                })
            }
        })
    }
}
