// Funcion usarInventario, pregunta si va a usar las existencias en el inventario
function usarInventario( item, pedido) {
   console.log(item);
    cantidad = $('#cantidad_atual_'+item).val();    
    existencias = $('#existencia_atual_'+item).val();
    if (parseInt(existencias)  > 0){
        Swal.fire({
            title: `¿Usar existencias del item ?`,
            text: `Existencias: ${existencias} disponibles`,
            icon: 'warning',
            input: 'number',
            inputAttributes: {
                id: 'cantidad_usar',
                name: 'cantidad_usar',
                min: 1,
                max: existencias,
                step: 1,
            },
            inputValue: existencias,
            showCancelButton: true,
            confirmButtonColor: '#597504',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, usar existencias!',
            cancelButtonText: 'cancelar!'
        }).then((result) => {
            if (result.isConfirmed) {
                cantidad_u = $('#cantidad_usar').val();
               if (cantidad_u <= existencias && cantidad_u > 0) {
                    cantidad -=cantidad_u;
                    console.log(cantidad);
                    existencias -=cantidad_u;
                    $('#cantidad_atual_'+item).val(cantidad);
                    $('#existencia_atual_'+item).val(existencias);
                    //modificar la cantidad del pedido y existencias en la tabla
                    $('#cantidad'+item).html(cantidad);
                    $('#existencias'+item).html(existencias);
                    //enviar peticion para crear nueva orden de produccion
                    crearOrdenProduccion(item, pedido, cantidad_u);
               } else{
                    Swal.fire({
                        title: 'Error!',
                        text: 'No puede usar mas existencias que las que tiene',
                        icon: 'error',
                        confirmButtonColor: '#597504',
                        confirmButtonText: 'OK'
                    })
               }
            } 
        })
    } else{
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Las existencias del item seleccionado son insuficientes',
            showConfirmButton: false,
            timer: 1500
        })
    }

}

// Funcion crearOrdenProduccion, envia una peticion para crear una nueva orden de produccion
function crearOrdenProduccion(item, pedido, cantidad_u) {
    $.ajax({
        url: '/orden-items-inventario',
        type: "POST",
        dataType: "JSON",
        data: {
            _token: $('input[name="_token"]').val(),
            item_id: item,
            cantidad: cantidad_u,
            pedido_id: pedido.id,
            estado: 'TERMINADO'
        },
        success: function (e) {
            console.log(e);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: e.success,
                showConfirmButton: false,
                timer: 1500
            })
        }
    })
}
    