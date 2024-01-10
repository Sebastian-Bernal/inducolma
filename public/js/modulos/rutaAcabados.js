var rutas = [];
var numProceso = 0;
var cantidad = 0
var pedido_id = $('#pedido_id').val();
var cantidad_p = $('#cantidad_p').val();
var cliente_rs = $('#cliente_rs').val();
var diseno_name = $('#diseno_name').val();
var User_id = $('#User_id').val();
// Agregar valores al local storage
comprobarLocalStorage()

function comprobarLocalStorage() {
    if (localStorage.getItem('rutas') == null || localStorage.getItem('rutas') == '[]') {
        rutas = [];
    } else {
        rutas = JSON.parse(localStorage.getItem('rutas'));
        listarProcesos(rutas);
    }
}

function guardarAcabado(entra, sale, maquina, maquina_id, observaciones, tipo_corte, cantidad) {

    let coincide = rutas.filter(rutas => rutas.maquina == maquina).length
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
        registroProceso = Object.assign({}, { rutaNum, entra, sale, maquina, maquina_id, observaciones, tipo_corte, pedido_id, cliente_rs, cantidad });
        rutas.unshift(registroProceso);
        localStorage.setItem('rutas', JSON.stringify(rutas));
        listarProcesos(rutas);
    };
};
// Funciones de validacion de campos en formularios

function agregaRutaEnsamble() {
    let valido, proceso = 'Ensamble', campoValor = 0;
    let campos = $('#agregarEnsamble').find('select');
    let textarea = $('#agregarEnsamble').find('textarea');
    let entra, sale, maquina, observaciones, maquina_id;
    let maquinaria = document.getElementById("maquinaensamble");
    let tipo_corte = 'ENSAMBLE';
    let cantidadId = $('#CantidadEnsamble').val();
    entra = ''
    sale = ''
    maquina = ''
    maquina_id = 0
    $.each(campos, function (index, value) {
        campoValor = value.value
        if (campoValor == 0) {


        } else {
            if (value.id == 'maquinaAcabadoensamble') {
                maquina = maquinaria.options[maquinaria.selectedIndex].text
                maquina_id = value.value
            }

        }
    });
    if (cantidadId != '' && parseInt(cantidadId) > 0 ) {
        cantidad = cantidadId
    }

    if (entra == 0 || sale == 0 || maquina == 0) {
        campoValor = 1
    }
    let observacionesId = $("#observacionEnsamble").val()
    if (observacionesId == '') {
        Swal.fire({
            title: '¡No contiene observacionesId, desea dejar así!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'Si',
            showDenyButton: true,
            denyButtonText: 'No'

        }).then((result) => {
            if (result.isConfirmed) {
                if (campoValor != 0) {
                    valido = true;
                    observaciones = ''
                    validar(valido, proceso, entra, sale, maquina, maquina_id, observaciones, tipo_corte, cantidad)
                } else {
                    camposVacios()
                    valido = false;
                    validar(valido, proceso, entra, sale, maquina, maquina_id, observaciones, tipo_corte , cantidad)
                }
            } else {
                valido = false;
                validar(valido, proceso, entra, sale, maquina, maquina_id, observaciones, tipo_corte , cantidad)
            }
        });
    }
    if (observacionesId != '' && campoValor != 0) {
        valido = true;
        observaciones = observacionesId
        validar(valido, proceso, entra, sale, maquina, maquina_id, observaciones, tipo_corte , cantidad)
    }

};

function agregaRutaAcabadoEnsamble() {
    let valido, proceso = 'AcabadoEnsamble', campoValor = 0;
    let campos = $('#agregarAcabadoEnsamble').find('select');
    let textarea = $('#agregarAcabadoEnsamble').find('textarea');
    let entra, sale, maquina, observaciones, maquina_id;
    let maquinaria = document.getElementById("maquinaAcabadoensamble");
    let tipo_corte = 'ACABADO_ENSAMBLE';
    let cantidadAcabadoId = $('#CantidadAcabadoEnsamble').val();
    entra = ''
    sale = ''
    maquina = ''
    maquina_id = 0
    $.each(campos, function (index, value) {
        campoValor = value.value
        if (campoValor == 0) {


        } else {
            if (value.id == 'maquinaAcabadoensamble') {
                maquina = maquinaria.options[maquinaria.selectedIndex].text
                maquina_id = value.value
            }

        }
    });

    if (cantidadAcabadoId != '' && parseInt(cantidadAcabadoId) > 0 ) {
        cantidad = cantidadAcabadoId
    }

    if (entra == 0 || sale == 0 || maquina == 0) {
        campoValor = 1
    }
    let observacionesId = $("#observacionAcabadoEnsamble").val()
    if (observacionesId == '') {
        Swal.fire({
            title: '¡No contiene observacionesId, desea dejar así!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'Si',
            showDenyButton: true,
            denyButtonText: 'No'

        }).then((result) => {
            if (result.isConfirmed) {
                if (campoValor != 0) {
                    valido = true;
                    observaciones = ''
                    validar(valido, proceso, entra, sale, maquina, maquina_id, observaciones, tipo_corte, cantidad)
                } else {
                    camposVacios()
                    valido = false;
                    validar(valido, proceso, entra, sale, maquina, maquina_id, observaciones, tipo_corte , cantidad)
                }
            } else {
                valido = false;
                validar(valido, proceso, entra, sale, maquina, maquina_id, observaciones, tipo_corte , cantidad)
            }
        });
    }
    if (observacionesId != '' && campoValor != 0) {
        valido = true;
        observaciones = observacionesId
        validar(valido, proceso, entra, sale, maquina, maquina_id, observaciones, tipo_corte , cantidad)
    }

};



// Validacion de datos correcta - procede a enviar a localStorage
function validar(valido, proceso, entra, sale, maquina, maquina_id, observaciones, tipo_corte, cantidad) {
    if (valido) {
        console.log("envia datos");
        procesoEjecutado(proceso)
        guardarAcabado(entra, sale, maquina, maquina_id, observaciones, tipo_corte, cantidad)
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
    $('#maquinaensamble').val('0');
    $('#observacionEnsamble').val('');
    $('#CantidadEnsamble').val('0');
    $('#entraEnsamble').focus();
};
function limpiarAcabadosEnsamble() {
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
function listarProcesos(rutas) {
    $('#listarProcesos').html('');
    rutas.forEach(rutas => {
        let fila = `<tr id ="${rutas.rutaNum}">
                        <td>${rutas.rutaNum}</td>
                        <td>${rutas.maquina}</td>
                        <td>${rutas.cantidad}</td>
                        <td>${rutas.observaciones}</td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarMadera(${rutas.rutaNum})"><i class="fas fa-trash-alt"></i></button></td>
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
            rutas = rutas.filter(rutas => rutas.rutaNum != rutaNum);
            localStorage.setItem('rutas', JSON.stringify(rutas));
            listarProcesos(rutas);
        }
    })
}

/**
 * guardarRutaBD(), envia la ruta seleccionada para ser guardada en la base de datos
 * @param {Object} rutas
 * @return {Swal}, retorna un mensaje Swal con mensaje de exito o error
 */
function guardarRutaBD() {
    if (rutas.length > 0) {
        var principalTitle =  `¿Está seguro de terminar la creacion de la ruta de programación?`;
        var confirmButtonText = 'Si, terminar';
        var url =  `/api/crear-ruta-acabado-producto`;
        var tipo = "POST";
        var datos = {
            'pedido_id': pedido_id,
            'user_id': User_id,
            'rutas': rutas,
        };
        var successTitle =  'Rutas Creadas!';

        var guardarRuta= AlertSimpleRequestManager.getInstance();
        guardarRuta.showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, successTitle);

    }else {
        swal.fire({
            title: "¡No hay una ruta creada, no puede terminar!",
            icon: "warning",
            confirmButtonColor: "#597504",
            confirmButtonText: "OK",
        });
    }
}
