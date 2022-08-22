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

function cantidadUso(id_entrada,paqueta,producir,cantidad_items,margen_error,item,pedido) {
    location.reload()
    if (window.history.replaceState) { // verificamos disponibilidad
        window.history.replaceState(null, null, window.location.href);
    }
    mitad_can = (cantidad_items/2)/((margen_error/100)+1)
    if(mitad_can>producir){
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
                Swal.fire({
                    title: 'Paqueta guardada con exito',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#597504',
                })
            } else if (result.isDenied) {
                $.ajax({
                    url: `/dividir-paqueta`,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        entrada_madera_id: parseInt(id_entrada),
                        paqueta: parseInt(paqueta),
                        id_pedido: parseInt(pedido['id']),
                        id_item: parseInt(item),
                        _token: $('input[name="_token"]').val()
                    },
                    success: function (e) {
                        console.log(e)
                        let total_items = 0
                        let lista =`<div><div style="width: 45%; float: left; min-width: 320px; margin-left: 3%"><h2>Mitad No. 1</h2></br><table class="table table-striped table-bordered table-hover"><thead><tr><th>Bloque</th><th>Items por bloque</th><tr></thead><tbody>` ;
                        e.cubicajes[0].forEach(element => {
                            
                            total_items += element.cantidad_items
                            lista = lista +'<tr>' +
                                '<td>' + element.bloque + '</td>' +
                                '<td>' + element.cantidad_items + '</td>' +
                            '</tr>'
                        })
                        lista = lista + '</tbody></table></br><p>Total: '+total_items+'</p></div>';
                        let total_items2 = 0
                        let lista2 =`<div style="width: 45%; float: left; min-width: 320px; margin-left: 3%"><h2>Mitad No. 2</h2></br><table class="table table-striped table-bordered table-hover"><thead><tr><th>Bloque</th><th>Items por bloque</th><tr></thead><tbody>` ;
                        e.cubicajes[1].forEach(element => {
                            total_items2 += element.cantidad_items
                            lista2 = lista2 +'<tr>' +
                                '<td>' + element.bloque + '</td>' +
                                '<td>' + element.cantidad_items + '</td>' +
                            '</tr>'
                        });
                        lista2 = lista2 + '</tbody></table></br><p>Total: '+total_items2+'</p></div></div>';
                        
                        Swal.fire({
                            title: 'Paquetas divididas',
                            html: lista+lista2,
                            width: '80%',
                            confirmButtonColor: '#597504',
                            confirmButtonText: 'Usar mitad No. 1',
                            denyButtonColor: '#ff7e00',
                            denyButtonText: 'Usar mitad No. 2',
                            showDenyButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if(result.isConfirmed) {
                                Swal.fire({
                                    title: 'Esta Seguro de usar esta mitad No. 1',
                                    html: lista+lista2,
                                    width: '80%',
                                    confirmButtonColor: '#597504',
                                    confirmButtonText: 'Aceptar',
                                    denyButtonColor: '#ff7e00',
                                    denyButtonText: 'No usar',
                                    showDenyButton: true,
                                }).then((result) => {
                                    if(result.isConfirmed) {
                                        $.ajax({
                                            url: `/dividir-paqueta`,
                                            type: "POST",
                                            dataType: "JSON",
                                            data: {
                                                entrada_madera_id: parseInt(id_entrada),
                                                paqueta: parseInt(paqueta),
                                                id_pedido: parseInt(pedido['id']),
                                                id_item: parseInt(item),
                                                bloque_inicial: parseInt(bloqueIni),
                                                bloque_final: parseInt(bloquefin),
                                                _token: $('input[name="_token"]').val()
                                            },
                                            success: function(e) {
                                                console.log(e)
                                                
                                                Swal.fire({
                                                title: 'Mitad No. 1 de la paqueta guardada con exito',
                                                confirmButtonText: 'Aceptar',
                                                confirmButtonColor: '#597504',                                    
                                                })
                                            },
                                        })
                                    }else{

                                    } 
                                })    
                            }else{
                                if(result.isDenied) {
                                    Swal.fire({
                                        title: 'Mitad No. 2 de la paqueta guardada con exito',
                                        confirmButtonText: 'Aceptar',
                                        confirmButtonColor: '#597504',
                                    }) 
                                }
                            }    
                        });
                    },
                })    
            }else{
                swal.fire('Se resta la cantidad')
            }
        })
    }
}    