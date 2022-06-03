// variables globales
var items = [];

// funcion onload, carga los items de localStorage
$(document).ready(function() {

    // carga los items de localStorage
    // comprobarLocalStorage();
   $('#item').select2({
        width: 'resolve',
        placeholder: 'Seleccione...',
        dropdownParent: $("#agregarItem"),
        theme: "bootstrap-5",
    });
   
});

/**
 * funcion comprobar localStorage, verifica si existe items en localStorage
 */
function comprobarLocalStorage() {
    items = JSON.parse(localStorage.getItem('items'));
    if (items == null) {
        items = [];
    } else {
        listaItem(items);
    }
}

/**
 * funcion verificar items del diseño, se verifica el fomulario de formAgregarItem
 * @param {type} diseno
 * @returns {undefined} 
 */
function verificarItems(diseno) {
    var campos = $('#formAgregarItem').find('input');
    var select = $('#formAgregarItem').find('select');
    var valido = true;
    $.each(campos, function(index, value) {
        if(value.value == '' || value.value <= '0' || value.value >50) {
            valido = false;
        }
    });
    $.each(select, function(index, value) {
        if(value.value == '' ) {
            valido = false;
        }
    });

    if(valido) {
        agregarItem(diseno);        
    } else {
        Swal.fire({
            title: '¡Por favor, seleccione un item y agrege una cantidad entre 1 y 50!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
    }

}

/**
 * funcion agregarItem, agrega un item a la variable global item, y se guarda en localStorage
 */
function agregarItem(diseno) {

    var item = {
        'diseno_id': diseno.id,
        'item_id': parseInt($('#item option:selected').val()),
        'descripcion': $('#item option:selected').text(),
        'cantidad': parseInt($('#cantidad').val()),      
    }

    // agregar item a la variable global
    //items.unshift(item);

    // agregar item a localStorage
    //localStorage.setItem('items', JSON.stringify(items));

    // agregar item a la tabla
    
    guardarItem(item);
    
}


/**
 * funcion listaItem, agrega un item al div listaItems
 */
function listaItem(item,id) {
   // $('#listaItems').empty(); 
    var html = `<li class="list-group-item d-flex justify-content-between align-items-center" id="${id}">
                        ${item.descripcion}
                        <div class=" justify-content-center py-1">
                            <span class="badge bg-primary square-pill  "> 
                                <h5 class=" m-0 p-0 ">${item.cantidad}</h5>
                            </span>
                            <button class="btn btn-danger btn-xs" onclick="eliminarItem(${id})">
                                <i class="fa-solid fa-trash-can"></i> 
                            </button>
                        </div>
                    </li>`;

    $('#listaItems').append(html);  
    

}

/**
 * funcion eliminar item, elimina un item de la variable global items, y se guarda en localStorage
 */
function eliminarItem(item) {
   
    //eliminar un item de la lista listaItems
    Swal.fire({
        title: '¿Está seguro de eliminar el item?',
        text: "No podrá revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#spEliminar').html(
                    `<div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>`
                );
            $.ajax({
                url:  `/diseno-items/${item}`,
                type: 'DELETE',
                dataType: "JSON",        
                data: {
                    _token: $('input[name="_token"]').val(),
                    //item: item
                },
                success: function(it) {
                    if (it.success) {
                        $('#spEliminar').empty();
                        $('#'+item).remove();               
                        Swal.fire({
                            position: 'top-end',                    
                            title: it.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                        })
                    } else {
                        Swal.fire({
                            title: '¡Error!',
                            icon: 'warning',
                            text: it.message,
                            confirmButtonColor: '#597504',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            });
        }
    })
    
    
}

/**
 * funcion guardarItem, guarda un item en la base de datos
 */
function guardarItem(item) {
    $('#spItem').html(
        `<div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>`
    );
    $.ajax({
        url: '/diseno-items',
        type: 'POST',
        dataType: "JSON",        
        data: {
            _token: $('input[name="_token"]').val(),
            item: item
        },
        success: function(it) {
            if (it.success) {
                listaItem(item,it.itembd.id);
                $('#formAgregarItem').trigger("reset");
                $('#spItem').html('');
                Swal.fire({
                    position: 'top-end',                    
                    title: it.message,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000,
                    //background: 'rgba(89, 117, 4, 1)'
                })
            } else {
                Swal.fire({
                    title: '¡Error!',
                    icon: 'warning',
                    text: it.message,
                    confirmButtonColor: '#597504',
                    confirmButtonText: 'OK'
                })
            }
        }
    });
}