// on load
$(document).ready(function () {

    $('#producto').select2({
        width: 'resolve',
        placeholder: 'Seleccione...',
        dropdownParent: $("#creapedido"),
        theme: "bootstrap-5",
    });
});

/* cantidadUso - Funcion para seleccionar si usa toda la paqueta o solo media
  recibe de parametros entrada_madera_id, paqueta , cantidad y cantidad_items
*/

function cantidadUso(id_entrada,paqueta,cantidad,cantidad_items,margen_error) {
    mitad_can = (cantidad_items/2)/((margen_error/100)+1)
    if(mitad_can>cantidad){
    Swal.fire({
        title: 'La cantidad a producir supera la cantidad necesaria. Desea usar toda la paqueta?',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Si, usar toda la paqueta',
        confirmButtonColor: '#597504',
        denyButtonText: `No, dividir la paqueta`,
        denyButtonColor: '#ff7e00',
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
        Swal.fire('Paqueta guardada con exito', '', 'success')
        } else if (result.isDenied) {
            $.ajax({
                url: `/paqueta`,
                type: "POST",
                dataType: "JSON",
                data: {
                    entrada_madera_id: id_entrada,
                    paqueta: paqueta,
                    _token: $('input[name="_token"]').val()
                },
                success: function (e) {
                    console.log(e)
                    let lista =`<table class="table table-striped table-bordered table-hover"><thead><tr><th>Bloque</th><th>Pulgadas cuadradas</th><tr></thead><tbody>` ;
                    e.forEach(element => {
                        lista = lista +'<tr>' +
                            '<td>' + element.bloque + '</td>' +
                            '<td>' + element.pulgadas_cuadradas + '</td>' +
                        '</tr>'
                    });
                    
                    Swal.fire({
                        title: 'Datos de la paqueta',
                        html: lista + '</tbody></table>',
                        confirmButtonColor: '#597504',
                        confirmButtonText: 'OK'
                    });
                },
            })    
        }
    })
   }else{
    Swal.fire('Se resta la cantidad')
   }
   
}
