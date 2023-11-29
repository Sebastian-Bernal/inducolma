var rutasAcabados = [];
var numProceso = 0;
var cantidad_r = 0
var pedido_id = $('#pedido_id').val();
var cantidad_p = $('#cantidad_p').val();
var cliente_rs = $('#cliente_rs').val();
var diseno_name = $('#diseno_name').val();
var User_id = $('#User_id').val();
// Agregar valores al local storage
comprobarLocalStorage()

function comprobarLocalStorage() {
    if (localStorage.getItem('rutasAcabados') == null || localStorage.getItem('rutasAcabados') == '[]') {
        rutasAcabados = [];
    } else {
        rutasAcabados = JSON.parse(localStorage.getItem('rutasAcabados'));
        listarProcesos(rutasAcabados);
    }
}

function guardarAcabado(entra, sale, maquina, idMaquina, observa, tipo_corte, cantidad_r) {

    let coincide = rutasAcabados.filter(rutasAcabados => rutasAcabados.maquina == maquina).length
    if (coincide > 0) {
        Swal.fire({
            title: '¡Proceso ya fue definido!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        });

    } else {
        numProceso++;
        let rutaNum = numProceso;
        registroProceso = Object.assign({}, { rutaNum, entra, sale, maquina, idMaquina, observa, tipo_corte, pedido_id, cliente_rs, cantidad_r });
        rutasAcabados.unshift(registroProceso);
        localStorage.setItem('rutasAcabados', JSON.stringify(rutasAcabados));
        listarProcesos(rutasAcabados);
    };
};
// Funciones de validacion de campos en formularios

function agregaRutaEnsamble() {
    let valido, proceso = 'Ensamble', campoValor = 0;
    let campos = $('#agregarEnsamble').find('select');
    let textarea = $('#agregarEnsamble').find('textarea');
    let entra, sale, maquina, observa, idMaquina;
    let maquinaria = document.getElementById("maquinaensamble");
    let tipo_corte = 'ENSAMBLE';
    entra = ''
    sale = ''
    maquina = ''
    idMaquina = 0
    $.each(campos, function (index, value) {
        campoValor = value.value
        if (campoValor == 0) {
            camposVacios()

        } else {
            if (value.id == 'entraEnsamble') {
                entra = value.value
            }
            if (value.id == 'saleEnsamble') {
                sale = value.value
            }
            if (value.id == 'maquinaensamble') {
                maquina = maquinaria.options[maquinaria.selectedIndex].text
                idMaquina = value.value
            }
            if (value.id == 'CantidadEnsamble') {
                cantidad_r = value.value 
            }
        }
    });
    if (entra == 0 || sale == 0 || maquina == 0) {
        campoValor = 0
    }
    let observaciones = $("#observacionEnsamble").val()
    if (observaciones == '') {
        Swal.fire({
            title: '¡No contiene observaciones, desea dejar así!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'Si',
            showDenyButton: true,
            denyButtonText: 'No'

        }).then((result) => {
            if (result.isConfirmed) {
                if (campoValor != 0) {
                    valido = true;
                    observa = ''
                    validar(valido, proceso, entra, sale, maquina, idMaquina, observa, tipo_corte, cantidad_r)
                } else {
                    camposVacios()
                    valido = false;
                    validar(valido, proceso, entra, sale, maquina, idMaquina, observa, tipo_corte , cantidad_r)
                }
            } else {
                valido = false;
                validar(valido, proceso, entra, sale, maquina, idMaquina, observa, tipo_corte , cantidad_r)
            }
        });
    }
    if (observaciones != '' && campoValor != 0) {
        valido = true;
        observa = observaciones
        validar(valido, proceso, entra, sale, maquina, idMaquina, observa, tipo_corte , cantidad_r)
    }

};

function agregaRutaAcabadoEnsamble() {
    let valido, proceso = 'AcabadoEnsamble', campoValor = 0;
    let campos = $('#agregarAcabadoEnsamble').find('select');
    let textarea = $('#agregarAcabadoEnsamble').find('textarea');
    let entra, sale, maquina, observa, idMaquina;
    let maquinaria = document.getElementById("maquinaAcabadoensamble");
    let tipo_corte = 'ACABADO_ENSAMBLE';
    entra = ''
    sale = ''
    maquina = ''
    idMaquina = 0
    $.each(campos, function (index, value) {
        campoValor = value.value
        if (campoValor == 0) {
            camposVacios()

        } else {
            if (value.id == 'entraAcabadoEnsamble') {
                entra = value.value
            }
            if (value.id == 'saleAcabadoEnsamble') {
                sale = value.value
            }
            if (value.id == 'maquinaAcabadoensamble') {
                maquina = maquinaria.options[maquinaria.selectedIndex].text
                idMaquina = value.value
            }
            if (value.id == 'CantidadAcabadoEnsamble') {
                cantidad_r = value.value 
            }
        }
    });
    if (entra == 0 || sale == 0 || maquina == 0) {
        campoValor = 0
    }
    let observaciones = $("#observacionAcabadoEnsamble").val()
    if (observaciones == '') {
        Swal.fire({
            title: '¡No contiene observaciones, desea dejar así!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'Si',
            showDenyButton: true,
            denyButtonText: 'No'

        }).then((result) => {
            if (result.isConfirmed) {
                if (campoValor != 0) {
                    valido = true;
                    observa = ''
                    validar(valido, proceso, entra, sale, maquina, idMaquina, observa, tipo_corte, cantidad_r)
                } else {
                    camposVacios()
                    valido = false;
                    validar(valido, proceso, entra, sale, maquina, idMaquina, observa, tipo_corte , cantidad_r)
                }
            } else {
                valido = false;
                validar(valido, proceso, entra, sale, maquina, idMaquina, observa, tipo_corte , cantidad_r)
            }
        });
    }
    if (observaciones != '' && campoValor != 0) {
        valido = true;
        observa = observaciones
        validar(valido, proceso, entra, sale, maquina, idMaquina, observa, tipo_corte , cantidad_r)
    }

};



// Validacion de datos correcta - procede a enviar a localStorage
function validar(valido, proceso, entra, sale, maquina, idMaquina, observa, tipo_corte, cantidad_r) {
    if (valido) {
        console.log("envia datos");
        procesoEjecutado(proceso)
        guardarAcabado(entra, sale, maquina, idMaquina, observa, tipo_corte, cantidad_r)
    } else {
        console.log("no envia datos");
        procesoEjecutado(proceso)
    }
}

// define que formulario se debe limpiar
function procesoEjecutado(proceso) {
    if (proceso == 'Ensamble') {
        limpiarEnsamble()
    }
    if (proceso == 'AcabadoEnsamble') {
        limpiarAcabadosEnsamble()
    }
}

// Limpiar Inputs en formularios
function limpiarEnsamble() {
    $('#entraEnsamble').val('0');
    $('#saleEnsamble').val('0');
    $('#maquinaensamble').val('0');
    $('#observacionEnsamble').val('');
    $('#CantidadEnsamble').val('0');
    $('#entraEnsamble').focus();
};
function limpiarAcabadosEnsamble() {
    $('#entraAcabadoEnsamble').val('0');
    $('#saleAcabadoEnsamble').val('0');
    $('#maquinaAcabadoensamble').val('0');
    $('#observacionAcabadoEnsamble').val('');
    $('#CantidadAcabadoEnsamble').val('0');
    $('#entraAcabadoEnsamble').focus();
};


//Mensaje de campos vacios en select
function camposVacios() {

    Swal.fire({
        title: '¡Ingrese todos los datos!',
        icon: 'warning',
        confirmButtonColor: '#597504',
        confirmButtonText: 'OK'
    });
    valido = false;

}

// funcion listarProcesos, recibe un array de objetos y los muestra en la tabla
function listarProcesos(rutasAcabados) {
    $('#listarProcesos').html('');
    rutasAcabados.forEach(rutasAcabados => {
        let fila = `<tr id ="${rutasAcabados.rutaNum}">
                        <td>${rutasAcabados.rutaNum}</td>
                        <td>${rutasAcabados.maquina}</td>
                        <td>${rutasAcabados.entra}</td>
                        <td>${rutasAcabados.sale}</td>
                        <td>${rutasAcabados.observa}</td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarMadera(${rutasAcabados.rutaNum})"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>`;
        $('#listarProcesos').append(fila);

    })

}

function eliminarMadera(rutaNum) {

    Swal.fire({
        title: '¿Está seguro que desea eliminar el proceso?',
        text: "¡No podrá revertir esta acción!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#597504',
        cancelButtonColor: '#ccc',
        confirmButtonText: '¡Si, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        //$(`#${id}`).remove();
        if (result.isConfirmed) {
            rutasAcabados = rutasAcabados.filter(rutasAcabados => rutasAcabados.rutaNum != rutaNum);
            localStorage.setItem('rutasAcabados', JSON.stringify(rutasAcabados));
            listarProcesos(rutasAcabados);
        }
    })
}

function guardarRutaBD() {

    $.ajax({
        url: '/procesos',
        data: {
            proceso: rutasAcabados,
            _token: $('input[name="_token"]').val()
        },
        type: 'post',
        success: function (guardado) {
            if (guardado.error == false) {
                Swal.fire({
                    title: guardado.message,
                    icon: 'success',
                    confirmButtonColor: '#597504',
                    confirmButtonText: 'OK'
                })
                    .then(() => {
                        rutasAcabados = [];
                        numBloque = 0;
                        localStorage.removeItem('rutasAcabados');
                        window.location.href = '/procesos';

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
/**
 * terminarRuta(), muestra cuadro de confirmacion para que el usuario acepte o no enviar los datos
 * a la base de datos
 */
function terminarRuta() {
    if (cubicajes.length > 0) {

        Swal.fire({
            title: '¿Está seguro que desea terminar la ruta?',
            text: "¡No podrá revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#597504',
            cancelButtonColor: '#ff7e00',
            confirmButtonText: '¡Si, terminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                guardarRutaBD();
            }
        })
    } else {
        swal.fire({
            title: '¡La ruta no tiene procesos agregados, no se puede terminar!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
    }
}

/**
 * terminarRuta(), evalua si rutasAcabados contiene datos, para poder guardar en la BD
 * @returns {swal} mensaje de alerta
 */
function terminarRuta() {
    if (rutasAcabados.length > 0) {
        //guardarPaquetaBD();
        Swal.fire({
            title: "¿Está seguro de terminar la creacion de la ruta de programación?",
            text: "¡No podrá revertir esta acción!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#597504",
            cancelButtonColor: "#d33",
            confirmButtonText: "¡Si, terminar!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                guardarRutaBD();
            }
        });
    } else {
        swal.fire({
            title: "¡No hay una ruta creada, no puede terminar!",
            icon: "warning",
            confirmButtonColor: "#597504",
            confirmButtonText: "OK",
        });
    }
}
/**
 * guardarRutaBD(), envia la ruta seleccionada para ser guardada en la base de datos
 * @param {Object} rutasAcabados
 * @return {Swal}, retorna un mensaje Swal con mensaje de exito o error
 */
function guardarRutaBD() {
    let RutaAcabadoFin
    RutaAcabadoFin=object.assign({},{pedido_id,User_id,rutasAcabados})
    console.log(RutaAcabadoFin)
    $.ajax({
        url: "/crear-ruta-acabado-producto",
        data: {
            proceso: RutaAcabadoFin,
            _token: $('input[name="_token"]').val(),
        },
        type: "post",
        success: function (guardado) {
            console.log(guardado);
            if (guardado.error == false) {
                Swal.fire({
                    title: guardado.mensaje,
                    icon: "success",
                    confirmButtonColor: "#597504",
                    confirmButtonText: "OK",
                }).then(() => {
                    rutasAcabados = [];
                    localStorage.removeItem("rutasAcabados");
                    window.location.href = "/crear-ruta-acabado/"+pedido_id;
                });
            } else {
                Swal.fire({
                    title: guardado.mensaje,
                    icon: "error",
                    confirmButtonColor: "#597504",
                    confirmButtonText: "OK",
                });
            }
        },
        error: function (error) {
            console.log(error.status);
            swal.fire({
                title: error.statusText +' '+ error.status ,
                text:  'Error al insertar los datos, por favor comuniquese con el admistrador de la aplicación',
                icon: "error",
                confirmButtonColor: "#597504",
                confirmButtonText: "OK",
            });
        },
    });
}
