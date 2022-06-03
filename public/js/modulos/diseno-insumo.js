// variables globales
var insumos = [];

// funcion onload, carga los insumos de localStorage
$(document).ready(function() {

    // carga los insumos de localStorage
    //comprobarLocalStorage();
    $('#insumo').select2({
        width: 'resolve',
        placeholder: 'Seleccione...',
        dropdownParent: $("#agregarInsumo"),
        theme: "bootstrap-5",
    });
   
});

/**
 * funcion comprobar localStorage, verifica si existe insumos en localStorage
 */
function comprobarLocalStorage() {
    insumos = JSON.parse(localStorage.getItem('insumos'));
    if (insumos == null) {
        insumos = [];
    } else {
        listaInsumo(insumos);
    }
}

/**
 * funcion verificar insumos del diseño, se verifica el fomulario de formAgregarItem
 * @param {type} diseno
 * @returns {undefined} 
 */
function verificarInsumos(diseno) {
    var campos = $('#formAgregarInsumo').find('input');
    var select = $('#formAgregarInsumo').find('select');
    var valido = true;
    $.each(campos, function(index, value ) {
        if(value.value == '' || value.value <= '0' || value.value >1000 ) {
            valido = false;
        }
    });
    $.each(select, function(index, value) {
        if(value.value == '' ) {
            valido = false;
        }
    });

    if(valido) {
        agregarInsumo(diseno);        
    } else {
        Swal.fire({
            title: '¡Por favor, seleccione un insumo y agrege una cantidad entre 1 y 1000!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
    }

}

/**
 * funcion agregarInsumo, agrega un insumo a la variable global insumo, y se guarda en localStorage
 */
function agregarInsumo(diseno) {

    var insumo = {
        'diseno_id': diseno.id,
        'insumo_id': parseInt($('#insumo option:selected').val()),
        'descripcion': $('#insumo option:selected').text(),
        'cantidad': parseInt($('#cantidad_insumo').val()),      
    }

    // agregar insumo a la tabla
    guardarInsumo(insumo);

}

/**
 * funcion guardarInsumo, guarda un insumo en la base de datos
 */
 function guardarInsumo(insumo) {
    $('#spInsumo').html(
        `<div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>`
    );
    $.ajax({
        url: '/diseno-insumos',
        type: 'POST',
        dataType: "JSON",        
        data: {
            _token: $('input[name="_token"]').val(),
            insumo: insumo
        },
        success: function(it) {
            if (it.success) {
                listaInsumo(insumo,it.insumobd.id);
                $('#formAgregarInsumo')[0].reset();
                $('#spInsumo').html('');
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

/**
 * funcion listaInsumo, agrega un insumo al div listaInsumos
 */
function listaInsumo(insumo, id) {
    var html = `<li class="list-group-item d-flex justify-content-between align-items-center" id="${id}">
                    ${insumo.descripcion}
                    <div class=" justify-content-center py-1">
                        <span class="badge bg-primary square-pill  "> 
                            <h5 class=" m-0 p-0 ">${insumo.cantidad}</h5>
                        </span>
                        <button class="btn btn-danger btn-xs" onclick="eliminarInsumo(${id})">
                            <i class="fa-solid fa-trash-can"></i> 
                        </button>
                    </div>
                </li>`;

    $('#listaInsumo').append(html); 

}

/**
 * funcion eliminar insumo, elimina un insumo de la variable global insumos, y se guarda en localStorage
 */
function eliminarInsumo(id) {
   Swal.fire({
        title: '¿Está seguro que desea eliminar el insumo?',
        text: "No podrá revertir esta acción!",
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
                url:  `/diseno-insumos/${id}`,
                type: 'DELETE',
                dataType: "JSON",        
                data: {
                    _token: $('input[name="_token"]').val(),
                    //item: item
                },
                success: function(it) {
                    if (it.success) {
                        $('#spEliminar').empty();
                        $('#'+id).remove();               
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