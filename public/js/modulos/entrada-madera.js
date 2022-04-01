// variables globales
var maderas = [];

// funciones cargan al cargar la pagina
$(document).ready(function() {
    $('#listaEntradas').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
    });

    comprobarLocalStorage();
    
} );

// funcion confirmarEnvio, se encarga de confirmar el envio de la entrada de madera y envia el formulario entradaMaderas
function confirmarEnvio() {
    //comprobar si todos los campos del formulario formEntradaMaderas estan llenos

    
        Swal.fire({
            title: '¿Está seguro de guardar la entrada de madera?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#597504',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, enviarla!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
               
                let entrada = new Object();
                entrada.mes = document.getElementById('mes').value;
                entrada.ano = document.getElementById('ano').value;
                entrada.hora = document.getElementById('hora').value;
                entrada.fecha = document.getElementById('fecha').value;
                entrada.actoAdministrativo = document.getElementById('actoAdministrativo').value;
                entrada.salvoconducto = document.getElementById('salvoconducto').value;
                entrada.titularSalvoconducto = document.getElementById('titularSalvoconducto').value;
                entrada.procedencia = document.getElementById('procedencia').value;
                entrada.entidadVigilante = document.getElementById('entidadVigilante').value;
                entrada.proveedor = document.getElementById('proveedor').value;

                console.log(entrada);
                guardarEntradaMadera(entrada);
            }
        })
    
}

// funcion validar campos, se encarga de validar que los campos del formulario formEntradaMaderas esten llenos
function validarCampos() {
    //comprobar si todos los campos del formulario formEntradaMaderas estan llenos
    var valido = true;
    var campos = $('#formEntradaMaderas').find('input');
    var select = $('#formEntradaMaderas').find('select');
    $.each(campos, function(index, value) {
        if(value.value == '' ) {
            valido = false;
        }
    });
    $.each(select, function (index, value) {
       /// console.log(value);
        if (value.value == '') {
            valido = false;
        }
    })

    if(localStorage.getItem('maderas') == null || localStorage.getItem('maderas') == '[]') {
        valido = false;
    }

    if(valido) {
        confirmarEnvio();
    } else {
        Swal.fire({
            title: '¡Hay campos sin completar, o ninguna madera ingresada',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
    }
}

// funcion agrega fila, a al body  listaMaderas se le agrega una fila con los datos de los inputs y select
function agregarMadera() {
    
    let id = $('select[name="madera"] option:selected').val();
    let nombre = $('select[name="madera"] option:selected').text();
    let condicion = $('select[name="condicionMadera"] option:selected').val();
    let metrosCubicos = $('input[name="m3entrada"]').val();

    if (id == '' | nombre == '' | condicion == '' | metrosCubicos == '') {
        Swal.fire({
            title: '¡ingrese todos los campos de la madera',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
    } else{
        madera = Object.assign({}, {id, nombre, condicion, metrosCubicos});
        maderas.unshift(madera);
        localStorage.setItem('maderas', JSON.stringify(maderas));
        let maderasLocal = JSON.parse(localStorage.getItem('maderas'));
        listarMaderas(maderasLocal);
    }
}

//funcion eliminarMadera, se encarga de eliminar una fila de la tabla listaMaderas
function eliminarMadera(id, idMadera) {
    //console.log('eliminar madera'+id);

    $(`#${id}`).remove();
    maderas = maderas.filter(madera => madera.id != idMadera);
    localStorage.setItem('maderas', JSON.stringify(maderas));
}


// funcion comprobarLocalStorage, se encarga de comprobar si hay datos en localStorage
function comprobarLocalStorage() {
    if(localStorage.getItem('maderas') == null || localStorage.getItem('maderas') == '[]') {
        maderas = [];
    } else {
        maderas = JSON.parse(localStorage.getItem('maderas'));
        listarMaderas(maderas);
    }
}

// funcion listarMaderas, se encarga de listar las maderas en la tabla listaMaderas
function listarMaderas(maderas) {
    $('#listaMaderas').html('');
    let trid = 0;
    maderas.forEach(madera => {
        let fila = `<tr id ="${trid}">
                        <td>${madera.nombre}</td>
                        <td>${madera.condicion}</td>
                        <td>${madera.metrosCubicos}</td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarMadera(${trid},${madera.id})"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>`;
        $('#listaMaderas').append(fila);
        trid++;
    })
}

// funcion guardarEntradaMadera, se encarga de guardar la entrada de madera en la base de datos
function guardarEntradaMadera(datosEntrada) {

    let registro = [];
    let maderas = JSON.parse(localStorage.getItem('maderas'));
    registro.push(datosEntrada);
    registro.push(maderas);
    console.log(registro);
    $.ajax({
        url: '/entradas-maderas',
        data: {
            entrada: registro,
            _token: $('input[name="_token"]').val()
        },
        type: 'post', 
        success: function(res) {
            if(res.status == 'ok') {
                Swal.fire({
                    title: '¡Entrada de madera guardada correctamente!',
                    icon: 'success',
                    confirmButtonColor: '#597504',
                    confirmButtonText: 'OK'
                })
                .then(() => {
                    window.location.href = '../../views/maderas/entradaMaderas.php';
                })
            } else {
                Swal.fire({
                    title: '¡No se pudo guardar la entrada de madera!',
                    icon: 'error',
                    confirmButtonColor: '#597504',
                    confirmButtonText: 'OK'
                })
            }
        }
    })
}