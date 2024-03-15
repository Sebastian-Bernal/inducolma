// variable local
var cubicajesSobrantes = [];
var numBloque = 0;
var ultimoIngreso;
var ultimaPaqueta;
//carga de pagina
$(document).ready(function () {
    comprobarLocalStorageSobrante();
    ObtenerUltimaPaqueta();
    $("#paquetas").DataTable({
        language: {
            url: "/DataTables/Spanish.json",
        },
        responsive: true,
        pageLength: 5,

        lengthChange: false,
    });
});

// funcion comprobarLocalStorageSobrante, se encarga de comprobar si hay datos en localStorage
function comprobarLocalStorageSobrante() {
    if (
        localStorage.getItem("cubicajesSobrantes") == null ||
        localStorage.getItem("cubicajesSobrantes") == "[]"
    ) {
        cubicajesSobrantes = [];
        obtenerUltimoIngreso();
    } else {
        cubicajesSobrantes = JSON.parse(localStorage.getItem("cubicajesSobrantes"));
        listarPaquetasSobrante(cubicajesSobrantes);
        obtenerUltimoIngreso();
    }
}
/**
 * Selecciona el ultimo objeto ingresado en el cubicaje de la troza
 *
 * @return {void}
 */

function obtenerUltimoIngreso() {
    var copiaIngresados = [].concat(cubicajeBloques);
    ultimoIngreso = copiaIngresados.pop();
    if (ultimoIngreso != undefined) {
        $('#ingresoAnterior').val(ultimoIngreso.entrada_id);
        $('#trozaId').val(ultimoIngreso.id);
    }

}

/**
 * Selecciona el ultimo registro del array trozasEntrada
 *
 * @return {void}
 */
function ObtenerUltimaPaqueta() {
    var copiaTrozasPaqueta = [].concat(trozasEntrada);
    if (copiaTrozasPaqueta.length > 0) {
        ultimaPaqueta = copiaTrozasPaqueta.pop();
    }

}

/**
 * funcion verificarInputs, verifica que los inputs no esten vacios, si no estan agrega el dato a la tabla
 * guarda en localstorage, y asigna el id a la variable local cubicaje
 *
 * @return {void}
 * */
function verificarInputsSobrante() {

    var valido;

    if ($('#ingresoAnterior').val() == "") {
        alertaErrorSimple('No existe el identificador del ultimo ingreso, no puede agregar el sobrante', 'error');
    }

    var campos = $("#agregarSobrante").find("input");
    $.each(campos, function (index, value) {
        if (value.value == "") {
            alertaErrorSimple("Ingrese todos los datos del sobrante", "warning");
            obtenerUltimoIngreso();
            valido = false;
        } else {
            obtenerUltimoIngreso();
            if (
                validarLargoSobrante() == false &&
                validarAnchoSobrante() == false &&
                validarAltoSobrante() == false
            ) {
                valido = true;
            } else {
                valido = false;
            }
        }
    });
    if (valido) {
        guardarPaquetaSobrante();
    }
}



// funcion validarLargoSobrante, valida que el largo este entre 70 y 600, sino muestra un mensaje de error
// y se hace focus en el input hasta que se ingrese un valor valido
function validarLargoSobrante() {
    var largo = parseFloat($("#largoSobrante").val());
    if (largo < 1 || largo > parseInt(ultimoIngreso.largo)) {
        Swal.fire({
            title: "¡Ingrese un valor de largo entre 1 y "+ parseInt(ultimoIngreso.largo),
            icon: "warning",
            confirmButtonColor: "#597504",
            confirmButtonText: "OK",
        });
        $("#largoSobrante").focus();
        return true;
    } else {
        return false;
    }
}

// funcion validarAnchoSobrante, valida que el ancho este entre 10 y 50, sino muestra un mensaje de error
// y se hace focus en el input hasta que se ingrese un valor valido
function validarAnchoSobrante() {
    var ancho = parseFloat($("#anchoSobrante").val());
    if (ancho < 1 || ancho > ultimoIngreso.ancho) {
        Swal.fire({
            title: "¡Ingrese un valor de ancho entre 1 y "+ parseInt(ultimoIngreso.ancho),
            icon: "warning",
            confirmButtonColor: "#597504",
            confirmButtonText: "OK",
        });
        $("#anchoSobrante").focus();
        return true;
    } else {
        return false;
    }
}

// funcion validarAltoSobrante, valida que el alto este entre 10 y 50, sino muestra un mensaje de error
// y se hace focus en el input hasta que se ingrese un valor valido
function validarAltoSobrante() {
    var alto = parseFloat($("#altoSobrante").val());
    if (alto < 1 || alto > ultimoIngreso.alto) {
        Swal.fire({
            title: "¡Ingrese un valor de alto entre 1 y "+ parseInt(ultimoIngreso.alto),
            icon: "warning",
            confirmButtonColor: "#597504",
            confirmButtonText: "OK",
        });
        $("#altoSobrante").focus();
        return true;
    } else {
        return false;
    }
}



// funcion guardarPaquetaSobrante, guarda los datos en la tabla paquetas, guarda en memoria localstorage y asigna el id a la variable local cubicaje
// limpia los inputs
function guardarPaquetaSobrante() {
    numBloque++;
    let paqueta = ultimaPaqueta + 1;
    let bloque = numBloque;
    let largo = $("#largoSobrante").val();
    let alto = $("#altoSobrante").val();
    let ancho = $("#anchoSobrante").val();
    let entrada_id = $("#ingresoAnterior").val();
    let user_id = $("#userId").val();
    let troza_id = $("#trozaId").val();
    let pulgadasAlto = 0;
    let pulgadasAncho = 0;

    registroPaqueta = Object.assign({},
        {
            paqueta,
            bloque,
            largo,
            alto,
            ancho,
            pulgadasAlto,
            pulgadasAncho,
            entrada_id,
            user_id,
            troza_id
        }
    );
    cubicajesSobrantes.unshift(registroPaqueta);
    localStorage.setItem("cubicajesSobrantes", JSON.stringify(cubicajesSobrantes));
    //let cubicajesLocal = JSON.parse(localStorage.getItem('cubicajesSobrantes'));
    listarPaquetasSobrante(cubicajesSobrantes);
    limpiarInputsSobrante();
}
//funcion limpiarInputsSobrante, limpia los inputs
function limpiarInputsSobrante() {
    $('#largoSobrante').val('');
    $("#altoSobrante").val("");
    $("#anchoSobrante").val("");
}

// funcion listarPaquetasSobrante, recibe un array de objetos y los muestra en la tabla
function listarPaquetasSobrante(cubicajesSobrantes) {
    $("#listarSobrantes").html("");
    let trid = 0;
    //let id = 0;
    cubicajesSobrantes.forEach((cubicaje) => {

        let fila = `<tr id ="${trid}">
                        <td>${cubicaje.paqueta}</td>
                        <td>${cubicaje.entrada_id}</td>
                        <td>${cubicaje.bloque}</td>
                        <td>${cubicaje.largo}</td>
                        <td>${cubicaje.alto}</td>
                        <td>${cubicaje.ancho}</td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarMaderaSobrante(${trid},${cubicaje.bloque})"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>`;
        $("#listarSobrantes").append(fila);
        trid++;
    });
}

// funcion eliminarMaderaSobrante, recibe el id de la fila y el id de la madera, elimina la madera de la tabla y de la memoria localstorage
function eliminarMaderaSobrante(id, bloque) {
    numBloque--;
    Swal.fire({
        title: "¿Está seguro que desea eliminar el bloque sobrante # "+ bloque +"?" ,
        text: "¡No podrá revertir esta acción!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "¡Si, eliminar!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        //$(`#${id}`).remove();
        if (result.isConfirmed) {
            cubicajesSobrantes = cubicajesSobrantes.filter((cubicajesSobrantes) => cubicajesSobrantes.bloque != bloque);
            localStorage.setItem("cubicajesSobrantes", JSON.stringify(cubicajesSobrantes));
            listarPaquetasSobrante(cubicajesSobrantes);
        }
    });
}

// funcion terminarPaquetaSobrante, envia los datos de la variable cubicaje a la funcion guardarPaquetaSobrante
function terminarPaquetaSobrante() {
    if (cubicajesSobrantes.length > 0) {
        //guardarPaquetaBDSobrante();
        Swal.fire({
            title: "¿Está seguro que desea terminar la paqueta sobrante?",
            text: "¡No podrá revertir esta acción!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#597504",
            cancelButtonColor: "#d33",
            confirmButtonText: "¡Si, terminar!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                guardarPaquetaBDSobrante();
            }
        });
    } else {
        swal.fire({
            title: "¡La paqueta tiene 0 bloques agregados no se puede terminar!",
            icon: "warning",
            confirmButtonColor: "#597504",
            confirmButtonText: "OK",
        });
    }
}

// funcion guardarPaquetaBDSobrante, guarda los datos en la base de datos
function guardarPaquetaBDSobrante() {
    $.ajax({
        url: "/cubicaje",
        data: {
            cubicajes: cubicajesSobrantes,
            _token: $('input[name="_token"]').val(),
        },
        type: "post",
        success: function (guardado) {
            if (guardado.error == false) {
                Swal.fire({
                    title: guardado.message,
                    icon: "success",
                    confirmButtonColor: "#597504",
                    confirmButtonText: "OK",
                }).then(() => {
                    cubicajesSobrantes = [];
                    numBloque = 0;
                    localStorage.removeItem("cubicajesSobrantes");
                });
            } else {
                alertaErrorSimple(guardado.message, 'error')
            }
        },
        error: function(error){
            alertaErrorSimple('Error interno del servidor', 'error');
            console.log(error);
        },
    });
}
