// variable local 
var cubicajes = [];
var numBloque = 0;            
//carga de pagina 
$(document).ready(function() {
    comprobarLocalStorage();
    
    $('#paquetas').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true, 
        "pageLength": 5,
        
        "lengthChange": false
        
    });
    
});

// funcion comprobarLocalStorage, se encarga de comprobar si hay datos en localStorage
function comprobarLocalStorage() {
    if(localStorage.getItem('cubicajes') == null || localStorage.getItem('cubicajes') == '[]') {
        cubicajes = [];
    } else {
        cubicajes = JSON.parse(localStorage.getItem('cubicajes'));
        listarPaquetas(cubicajes);    
    }
}


// funcion verificarInputs, verifica que los inputs no esten vacios, si no estan agrega el dato a la tabla 
// guarda en localstorage, y asigna el id a la variable local cubicaje
function verificarInputs() {
    //console.log("entro a verificar inputs");
    var valido ;
    var campos = $('#agregarCubicaje').find('input');
    $.each(campos, function(index, value) {
        if(value.value == '' ) {
            Swal.fire({
                title: '¡Ingrese todos los datos!',
                icon: 'warning',
                confirmButtonColor: '#597504',
                confirmButtonText: 'OK'
            });
            //valido = false;
        } else {
            if(validaLargo() == false && validaAncho() == false && validaAlto() == false && validaPulgadasAlto() == false && validaPulgadasAncho() == false) {
                valido = true;
            }else{
                valido = false;
            }
        }       
    });
    if(valido) {
       // console.log("envia datos");
        guardarPaqueta();
    }else{
        console.log("no envia datos");
       // verificarInputs();
    }

}

// funcion validaLargo, valida que el largo este entre 70 y 600, sino muestra un mensaje de error 
// y se hace focus en el input hasta que se ingrese un valor valido
function validaLargo() {
    var largo = $('#largo').val();
    if(largo < 70 || largo > 600) {
        Swal.fire({
            title: '¡Ingrese un valor de largo entre 70 y 600!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
        $('#largo').focus();
        return true;
    }else{
        return false;
    }
}

// funcion validaAncho, valida que el ancho este entre 10 y 50, sino muestra un mensaje de error
// y se hace focus en el input hasta que se ingrese un valor valido
function validaAncho() {
    var ancho = $('#ancho').val();
    if(ancho < 10 || ancho > 50) {
        Swal.fire({
            title: '¡Ingrese un valor de ancho entre 10 y 50!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
        $('#ancho').focus();
        return true;
    }else{
        return false;
    }
}

// funcion validaAlto, valida que el alto este entre 10 y 50, sino muestra un mensaje de error
// y se hace focus en el input hasta que se ingrese un valor valido
function validaAlto() {
    var alto = $('#alto').val();
    if(alto < 10 || alto > 50) {
        Swal.fire({
            title: '¡Ingrese un valor de alto entre 10 y 50!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
        $('#alto').focus();
        return true;
    } else {
        return false;
    }
}

//funcion validaPulgadasAlto, valida que el alto este entre 0 y 10, sino muestra un mensaje de error
// y se hace focus en el input hasta que se ingrese un valor valido
function validaPulgadasAlto() {
    var pulgadasAlto = $('#pulgadas_alto').val();
    if(pulgadasAlto < 0 || pulgadasAlto > 10) {
        Swal.fire({
            title: '¡Ingrese un valor de pulgadas menos alto entre 0 y 10!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
        $('#pulgadas_alto').focus();
        return true;
    } else {
        return false;
    }
}

//funcion validaPulgadasAncho, valida que el ancho este entre 0 y 10, sino muestra un mensaje de error
// y se hace focus en el input hasta que se ingrese un valor valido
function validaPulgadasAncho() {
    var pulgadasAncho = $('#pulgadas_ancho').val();
    if(pulgadasAncho < 0 || pulgadasAncho > 10) {
        Swal.fire({
            title: '¡Ingrese un valor de pulgadas menos ancho entre 0 y 10!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
        $('#pulgadas_ancho').focus();
        return true;
    } else {
        return false;
    }
}

// funcion guardarPaqueta, guarda los datos en la tabla paquetas, guarda en memoria localstorage y asigna el id a la variable local cubicaje
// limpia los inputs
function guardarPaqueta() {
    numBloque++;
    let paqueta = $('#paqueta').val();
    let bloque = numBloque;
    let largo = $('#largo').val();
    let alto = $('#alto').val();
    let ancho = $('#ancho').val();
    let pulgadasAlto = $('#pulgadas_alto').val();
    let pulgadasAncho = $('#pulgadas_ancho').val();
    let entrada_id = $('#entradaId').val();

    registroPaqueta = Object.assign({}, {paqueta, bloque, largo, alto, ancho, pulgadasAlto, pulgadasAncho, entrada_id});
    cubicajes.unshift(registroPaqueta);
    localStorage.setItem('cubicajes', JSON.stringify(cubicajes));
    //let cubicajesLocal = JSON.parse(localStorage.getItem('cubicajes'));
    listarPaquetas(cubicajes);
    limpiarInputs();
}
//funcion limpiarInputs, limpia los inputs
function limpiarInputs() {
    $('#largo').val('');
    $('#alto').val('');
    $('#ancho').val('');
    $('#pulgadas_alto').val('0');
    $('#pulgadas_ancho').val('0');
    $('#largo').focus();
}


// funcion listarPaquetas, recibe un array de objetos y los muestra en la tabla
function listarPaquetas(cubicajes) {
   $('#listarPaquetas').html('');
    let trid = 0;
    //let id = 0;
    cubicajes.forEach(cubicaje => {
      /*  if (madera.entrada_id == null) {
            id = madera.id;
        }else{
            id = madera.entrada_id;
        }*/
        let fila = `<tr id ="${trid}">
                        <td>${cubicaje.paqueta}</td>
                        <td>${cubicaje.bloque}</td>
                        <td>${cubicaje.largo}</td>
                        <td>${cubicaje.alto}</td>
                        <td>${cubicaje.ancho}</td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarMadera(${trid},${cubicaje.bloque})"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>`;
        $('#listarPaquetas').append(fila);
        trid++;
    })
    
}

// funcion eliminarMadera, recibe el id de la fila y el id de la madera, elimina la madera de la tabla y de la memoria localstorage
function eliminarMadera(id,bloque) {

    Swal.fire({
        title: '¿Está seguro que desea eliminar la paqueta?',
        text: "¡No podrá revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Si, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        //$(`#${id}`).remove(); 
        if (result.isConfirmed) {    
            cubicajes = cubicajes.filter(cubicajes => cubicajes.bloque != bloque); 
            localStorage.setItem('cubicajes', JSON.stringify(cubicajes));
            listarPaquetas(cubicajes);
        }
    })
}

// funcion terminarPaqueta, envia los datos de la variable cubicaje a la funcion guardarPaqueta
function terminarPaqueta() {
    Swal.fire({
        title: '¿Está seguro que desea terminar la paqueta?',
        text: "¡No podrá revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Si, terminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/cubicaje',
                data: {
                    cubicajes: cubicajes,
                    _token: $('input[name="_token"]').val()
                },
                type: 'post', 
                success: function(guardado) {
                    if(guardado.error == false) {
                        Swal.fire({
                            title: guardado.message,
                            icon: 'success',
                            confirmButtonColor: '#597504',
                            confirmButtonText: 'OK'
                        })
                        .then(() => {
                            cubicajes = [];
                            numBloque = 0;
                            localStorage.removeItem('cubicajes');                            
                            window.location.href = '/cubicaje';
                            //$('#editarUltimo').show();
                            //localStorage.setItem('ultimo', guardado.id);
                            
                        })
                    } else {
                        Swal.fire({
                            title: guardado.message,
                            icon: 'error',
                            confirmButtonColor: '#597504',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            })
        }
    })
}