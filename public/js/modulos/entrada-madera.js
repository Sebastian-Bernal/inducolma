

// variables globales
var maderas = [];
var solicitud = 0;
var idartmadera = 0;
// funciones cargan al cargar la pagina
$(document).ready(function() {
    $('#listaEntradas').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true,
        "pageLength": 5,
    });

    comprobarLocalStorage();

    if (localStorage.getItem('ultimo') == null || localStorage.getItem('ultimo') == '[]') {
       console.log('no hay ultimo');
    } else {
        console.log('ultimo');
        $('#editarUltimo').attr('href', 'entradas-maderas/'+localStorage.getItem('ultimo'));
    }

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

                entrada.id_ultima = localStorage.getItem('ultimo');
                if (solicitud == 0) {
                    verificarActoAdministrativo(entrada);
                } else {
                    guardarEntradaMadera(entrada);
                }


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

    let id_art = idartmadera++;
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
    } else if(metrosCubicos <= 1 || metrosCubicos > 1000) {
        Swal.fire({
            title: '¡ingrese una cantidad de m3 valido',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
    }
     else{
        madera = Object.assign({}, {id, nombre, condicion, metrosCubicos, id_art});
        maderas.unshift(madera);
        localStorage.setItem('maderas', JSON.stringify(maderas));
        let maderasLocal = JSON.parse(localStorage.getItem('maderas'));
        listarMaderas(maderasLocal);
    }
}

//funcion eliminarMadera, se encarga de eliminar una fila de la tabla listaMaderas
function eliminarMadera(id, idMadera) {
    //console.log('eliminar madera'+id);
    Swal.fire({
        title: '¿Está seguro de eliminar la madera?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminarla!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $(`#${id}`).remove();
            if (maderas[0].entrada_id == null) {
                maderas = maderas.filter(madera => madera.id_art != idMadera);
            } else {
                maderas = maderas.filter(madera => madera.entrada_id != idMadera);
                eliminarMaderaBD(idMadera);
            }
            localStorage.setItem('maderas', JSON.stringify(maderas));
                }
    })
}

//funcion eliminarMaderaBD, se encarga de eliminar una fila de la tabla listaMaderas
function eliminarMaderaBD(idMadera) {
    $.ajax({
        url: '/elimina-madera',
        data: {
            id: idMadera,
            _token: $('input[name="_token"]').val()
        },
        type: 'post',
        success: function(data) {
            console.log(data);
        }
    });
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
    let id = 0;
    maderas.forEach(madera => {
        if (madera.entrada_id == null) {
            id = madera.id_art;
        }else{
            id = madera.entrada_id;
        }


        let fila = `<tr id ="${trid}">
                        <td>${madera.nombre}</td>
                        <td>${madera.condicion}</td>
                        <td>${madera.metrosCubicos}</td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarMadera(${trid},${id})"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>`;
        $('#listaMaderas').append(fila);
        trid++;
    })
}

// funcion guardarEntradaMadera, se encarga de guardar la entrada de madera en la base de datos
function guardarEntradaMadera(datosEntrada) {
    idartmadera = 0;
    let registro = [];
    let maderas = JSON.parse(localStorage.getItem('maderas'));
    registro.push(datosEntrada);
    registro.push(maderas);
    registro.push(solicitud);
    //console.log(registro);
    $.ajax({
        url: '/entradas-maderas',
        data: {
            entrada: registro,
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
                    localStorage.removeItem('maderas');
                    $('#editarUltimo').attr('href', '{{route("entradas-maderas",'+guardado.id+')}}');
                    window.location.href = '/entradas-maderas';
                    //$('#editarUltimo').show();
                    localStorage.setItem('ultimo', guardado.id);

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

// funcion verifica la existencia de un acto administrativo en la base de datos
function verificarActoAdministrativo(entrada) {
    let estado = false;
    $.ajax({
        url: '/revisa-acto',
        data: {
            acto: $('input[name="actoAdministrativo"]').val(),
            _token: $('input[name="_token"]').val()
        },
        type: 'post',
        success: function (respuesta) {
            if (respuesta.error == true) {
                Swal.fire({
                    title: '¡El acto administrativo ya existe!',
                    icon: 'warning',
                    confirmButtonColor: '#597504',
                    confirmButtonText: 'OK'
                })
                estado = true;
            } else {
                guardarEntradaMadera(entrada);
            }
        }
    })

}

//funcion editarUltimo, se encarga de editar el ultimo registro con el id guardado en localStorage como id
function editarUltimo() {
    solicitud = 1;
    localStorage.removeItem('maderas');
    let id = localStorage.getItem('ultimo');

    if (id == null) {
        alertaErrorSimple('No hay una ultima entrada registrada para la fecha: '+new Date().toISOString().split('T')[0], 'error')
        return;
    }

    $.ajax({
        url: 'ultima-entrada',
        data: {
            id: id,
            _token: $('input[name="_token"]').val()
        },
        type: 'post',
        success: function(respuesta) {
            //console.log(respuesta.mes);

            $('select[name="mes"]').val(respuesta.ultimaEntrada.mes);
            $('select[name="ano"]').val(respuesta.ultimaEntrada.ano);
            $('input[name="hora"]').val(respuesta.ultimaEntrada.hora);
            $('input[name="fecha"]').val(respuesta.ultimaEntrada.fecha);
            $('input[name="actoAdministrativo"]').val(respuesta.ultimaEntrada.acto_administrativo);
            $('input[name="salvoconducto"]').val(respuesta.ultimaEntrada.salvoconducto_remision);
            $('input[name="titularSalvoconducto"]').val(respuesta.ultimaEntrada.titular_salvoconducto);
            $('input[name="procedencia"]').val(respuesta.ultimaEntrada.procedencia_madera);
            $('input[name="entidadVigilante"]').val(respuesta.ultimaEntrada.entidad_vigilante);
            $('select[name="proveedor"]').val(respuesta.ultimaEntrada.proveedor_id);

            localStorage.removeItem('maderas');
            maderas = [];
            respuesta.maderas.forEach(madera => {
                //console.log(madera.condicion_madera);
                let id = madera.madera_id;
                let nombre = madera.nombre_cientifico;
                let condicion = madera.condicion_madera;
                let metrosCubicos = madera.m3entrada;
                let entrada_id = madera.id;
                let entrada_maderas_id = madera.entrada_madera_id;
                madera = Object.assign({}, {id, nombre, condicion, metrosCubicos, entrada_id, entrada_maderas_id});
                maderas.unshift(madera);
                localStorage.setItem('maderas', JSON.stringify(maderas));
                let maderasLocal = JSON.parse(localStorage.getItem('maderas'));
                listarMaderas(maderasLocal);

            })
            $('#registrar').click();
            // cargar maderas en localStorage

        }
    })
}

// funcion borrarMaderas, se encarga de vaciar el localStorage de maderas
function borrarMaderas() {
    //console.log('borrar maderas');
    solicitud = 0;
    idartmadera = 0;
    localStorage.removeItem('maderas');
    maderas = [];
    //limpiar el formulario
    $('#formRegistro').trigger('reset');
    //limpiar la tabla
    $('#listaMaderas').html('');
    //$('#listaMaderas').html('');
}
