$(document).ready(function () {

    if (window.location.pathname.includes('/pedidos/') || window.location.pathname.includes('/pedidos')) {

    } else {
        cargarProductos();
    }
    $('#listapedidos').DataTable({
        "language": {
            "url": "/DataTables/Spanish.json"
        },
        "responsive": true

    });
    $('#items').select2({
        width: 'resolve',
        placeholder: 'Seleccione un producto',
        dropdownParent: $("#creapedido"),
        theme: "bootstrap-5",
    });
    $('.items').select2({
        width: 'resolve',
        placeholder: 'Seleccione un producto',
        theme: "bootstrap-5",
    });
    $('#cliente').select2({
        width: 'resolve',
        placeholder: 'Seleccione un cliente',
        dropdownParent: $("#creapedido"),
        theme: "bootstrap-5",
    });
    $('.sel-cliente').select2({
        width: 'resolve',
        placeholder: 'Seleccione un cliente',
        theme: "bootstrap-5",
    });

    $('#productos').select2({
        width: 'resolve',
        placeholder: 'Seleccione un cliente',
        dropdownParent: $("#creadiseno"),
        theme: "bootstrap-5",
        ajax: {
            url: '/disenos-buscar',
            //dataType: 'json',
            type: 'post',
            delay: 800,
            data: function (params) {
                return {
                    descripcion: params.term,
                    _token: $('input[name="_token"]').val()
                };
            },
            processResults: function (data) {
                //console.log(data);
                // console.log(JSON.stringify(data));
                return {
                    results: data
                };

            },
        },
        language: {
            noResults: function () {
                return "No hay resultados";
            },
            searching: function () {
                return "Buscando..";
            },

        },
    })
});

// buscar los items al seleccionar un cliente en el select
function cargarProductos() {
    $('#spProducto').html(
        `<div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>`
    );
    var id = $('#cliente').val();
    $.ajax({
        url: '/items-cliente',
        type: 'get',
        data: {
            _token: $('input[name="_token"]').val(),
            id: id
        },
        dataType: 'json',
        success: function (items) {

            if (items.length > 0) {
                $('#spProducto').html('');
                $('#items').empty();
                $.each(items, function (index, value) {
                    $('#items').append('<option value="' + value.id + '">' + value.descripcion + '</option>');
                });
            } else {
                $('#spProducto').html('');
                $('#items').empty();
                Swal.fire({
                    position: 'top-end',
                    title: 'No hay productos para este cliente',
                    text: 'Agregue un producto al cliente',
                    icon: 'warning',
                    showConfirmButton: false,
                    timer: 2000,
                    //background: 'rgba(89, 117, 4, 1)'
                })
                $('#btnAsignar').click();

            }
        }
    });
};



// funcion cambia a mayusculas el input descripcion
function mayusculas() {
    var x = document.getElementById("descripcion");
    x.value = x.value.toUpperCase();
}

// funcion para eliminar un Insumo
function eliminarPedido(pedido) {

    Swal.fire({
        title: `¿Está seguro de eliminar el pedido ?`,
        text: `${pedido.nombre} - ${pedido.descripcion} - ${pedido.cantidad}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarlo!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/pedidos/${pedido.id}`,
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

//funcion volverAPedido, da un click al boton btnpedido
function volverAPedido() {
    $('#btnpedido').click();
}

// funcion para asignar un diseño a un cliente
function asignarDiseno() {
    let cliente = $('#cliente').val();
    let producto = $('#productos').val();
    let cliente_nombre = $('#cliente option:selected').text();
    let producto_nombre = $('#productos option:selected').text();

    if (cliente == '') {
        Swal.fire({
            title: 'Error!',
            text: 'Debe seleccionar un cliente',
            icon: 'error',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
    } else if (producto == '') {
        Swal.fire({
            title: 'Error!',
            text: 'Debe seleccionar un producto',
            icon: 'error',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
    } else {
        Swal.fire({
            title: `¿Está seguro de asignar el diseño:
                    ${producto_nombre} al cliente: ${cliente_nombre} ?`,
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
                        cliente_id: cliente,
                        diseno_id: producto
                    },
                    success: function (e) {
                        console.log(e.error);
                        if (e.error == true) {
                            Swal.fire({
                                title: '¡Error al asignar el diseño!',
                                text: e.message,
                                icon: 'error',
                                confirmButtonColor: '#597504',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            cargarProductos();
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
