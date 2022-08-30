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

    mitad_can = (cantidad_items/2)/((margen_error/100)+1)
    if(mitad_can>producir){
        Swal.fire({
            title: 'La cantidad a producir supera la cantidad necesaria. Desea usar toda la paqueta?',
            showDenyButton: true,
            showCloseButton: true,
            confirmButtonText: 'Si, usar toda la paqueta',
            confirmButtonColor: '#597504',
            denyButtonText: `No, dividir la paqueta`,
            denyButtonColor: '#ff7e00',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
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
                       // console.log(e)
                        
                        e.sort(function(a,b){
                           if(parseInt(a.bloque)>parseInt(b.bloque)){
                            return 1;
                           }
                           if(parseInt(a.bloque)<parseInt(b.bloque)){
                            return -1;
                           }
                           return 0;
                        })
                        //console.log(e)
                        let primero
                        let ultimo
                        primero = e.shift()
                        ultimo = e.pop()
                        
                        $.ajax({
                            url: `/seleccionar-madera`,
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                entrada_madera_id: parseInt(id_entrada),
                                paqueta: parseInt(paqueta),
                                id_pedido: parseInt(pedido['id']),
                                id_item: parseInt(item),
                                bloque_inicial: parseInt(primero.bloque),
                                bloque_final: parseInt(ultimo.bloque),
                                cantidad: parseInt(total_items),
                                _token: $('input[name="_token"]').val()
                            },
                            success: function(e) {
                               
                                
                                Swal.fire({
                                    title: 'Paqueta guardada con exito',
                                    confirmButtonText: 'Aceptar',
                                    confirmButtonColor: '#597504',
                                })
                               // console.log(e);
                               location.reload()
                            },
                        
                        });
                    },
                });    
               
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
                            showCloseButton: true,
                        }).then((result) => {
                            if(result.isConfirmed) {
                                Swal.fire({
                                    title: 'Esta Seguro de usar esta mitad No. 1',
                                    confirmButtonColor: '#597504',
                                    confirmButtonText: 'Aceptar',
                                    denyButtonColor: '#ff7e00',
                                    denyButtonText: 'No usar',
                                    showDenyButton: true,
                                }).then((result) => {
                                    if(result.isConfirmed) {
                                        let prueba = e.cubicajes[0]
                                        let primero = prueba.shift()
                                        let ultimo = prueba.pop()
                                        $.ajax({
                                            url: `/seleccionar-madera`,
                                            type: "POST",
                                            dataType: "JSON",
                                            data: {
                                                entrada_madera_id: parseInt(id_entrada),
                                                paqueta: parseInt(paqueta),
                                                id_pedido: parseInt(pedido['id']),
                                                id_item: parseInt(item),
                                                bloque_inicial: parseInt(primero.bloque),
                                                bloque_final: parseInt(ultimo.bloque),
                                                cantidad: parseInt(total_items),
                                                _token: $('input[name="_token"]').val()
                                            },
                                            success: function(e) {
                                               
                                                
                                                Swal.fire({
                                                title: 'Mitad No. 1 de la paqueta guardada con exito',
                                                confirmButtonText: 'Aceptar',
                                                confirmButtonColor: '#597504',                                    
                                                });
                                                location.reload()
                                               // console.log(e);
                                            },
                                        })
                                    }
                                })    
                            }else if(result.isDenied){
                                 
                                    Swal.fire({
                                        title: 'Esta Seguro de usar esta mitad No. 2',
                                        confirmButtonColor: '#597504',
                                        confirmButtonText: 'Aceptar',
                                        denyButtonColor: '#ff7e00',
                                        denyButtonText: 'No usar',
                                        showDenyButton: true,
                                    }).then((result) => {
                                        if(result.isConfirmed) {
                                        let prueba = e.cubicajes[1]
                                        let primero = prueba.shift()
                                        let ultimo = prueba.pop()
                                        $.ajax({
                                            url: `/seleccionar-madera`,
                                            type: "POST",
                                            dataType: "JSON",
                                            data: {
                                                entrada_madera_id: parseInt(id_entrada),
                                                paqueta: parseInt(paqueta),
                                                id_pedido: parseInt(pedido['id']),
                                                id_item: parseInt(item),
                                                bloque_inicial: parseInt(primero.bloque),
                                                bloque_final: parseInt(ultimo.bloque),
                                                cantidad: parseInt(total_items),
                                                _token: $('input[name="_token"]').val()
                                            },
                                            success: function(e) {
                                                //console.log(ultimo)
                                                
                                                Swal.fire({
                                                title: 'Mitad No. 2 de la paqueta guardada con exito',
                                                confirmButtonText: 'Aceptar',
                                                confirmButtonColor: '#597504',                                    
                                                })
                                                location.reload()
                                            },
                                        })
                                    }
                                })
                                
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