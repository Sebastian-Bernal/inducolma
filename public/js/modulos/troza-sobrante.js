// variable local
var cubicajesSobrantes = [];
var numBloque = 0;
var ultimoIngreso;
//carga de pagina
$(document).ready(function () {
    comprobarLocalStorageSobrante();

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
    } else {
        cubicajesSobrantes = JSON.parse(localStorage.getItem("cubicajesSobrantes"));
        listarPaquetasSobrante(cubicajesSobrantes);
    }
}

// funcion verificarInputs, verifica que los inputs no esten vacios, si no estan agrega el dato a la tabla
// guarda en localstorage, y asigna el id a la variable local cubicaje
function verificarInputsSobrante() {
    var valido;
    var campos = $("#agregarSobrante").find("input");
    $.each(campos, function (index, value) {
        if (value.value == "") {
            Swal.fire({
                title: "¡Ingrese todos los datos!",
                icon: "warning",
                confirmButtonColor: "#597504",
                confirmButtonText: "OK",
            });
            valido = false;
        } else {
            obtenerUltimoIngresado();
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

function obtenerUltimoIngresado() {
    cubicajeBloques
}

// funcion validarLargoSobrante, valida que el largo este entre 70 y 600, sino muestra un mensaje de error
// y se hace focus en el input hasta que se ingrese un valor valido
function validarLargoSobrante() {
    var largo = $("#largo").val();
    if (largo < 70 || largo > 600) {
        Swal.fire({
            title: "¡Ingrese un valor de largo entre 70 y 600!",
            icon: "warning",
            confirmButtonColor: "#597504",
            confirmButtonText: "OK",
        });
        $("#largo").focus();
        return true;
    } else {
        return false;
    }
}

// funcion validarAnchoSobrante, valida que el ancho este entre 10 y 50, sino muestra un mensaje de error
// y se hace focus en el input hasta que se ingrese un valor valido
function validarAnchoSobrante() {
    var ancho = $("#ancho").val();
    if (ancho < 10 || ancho > 50) {
        Swal.fire({
            title: "¡Ingrese un valor de ancho entre 10 y 50!",
            icon: "warning",
            confirmButtonColor: "#597504",
            confirmButtonText: "OK",
        });
        $("#ancho").focus();
        return true;
    } else {
        return false;
    }
}

// funcion validarAltoSobrante, valida que el alto este entre 10 y 50, sino muestra un mensaje de error
// y se hace focus en el input hasta que se ingrese un valor valido
function validarAltoSobrante() {
    var alto = $("#alto").val();
    if (alto < 10 || alto > 50) {
        Swal.fire({
            title: "¡Ingrese un valor de alto entre 10 y 50!",
            icon: "warning",
            confirmButtonColor: "#597504",
            confirmButtonText: "OK",
        });
        $("#alto").focus();
        return true;
    } else {
        return false;
    }
}



// funcion guardarPaquetaSobrante, guarda los datos en la tabla paquetas, guarda en memoria localstorage y asigna el id a la variable local cubicaje
// limpia los inputs
function guardarPaquetaSobrante() {
    numBloque++;
    let paqueta = $("#paqueta").val();
    let bloque = numBloque;
    let largo = $("#largo").val();
    let alto = $("#alto").val();
    let ancho = $("#ancho").val();
    let pulgadasAlto = $("#pulgadas_alto").val();
    let pulgadasAncho = $("#pulgadas_ancho").val();
    let entrada_id = $("#entradaId").val();
    let user_id = $("#userId").val();

    registroPaqueta = Object.assign(
        {},
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
    // $('#largo').val('');
    $("#alto").val("");
    $("#ancho").val("");
    $("#pulgadas_alto").val("0");
    $("#pulgadas_ancho").val("0");
    $("#alto").focus();
}

// funcion listarPaquetasSobrante, recibe un array de objetos y los muestra en la tabla
function listarPaquetasSobrante(cubicajesSobrantes) {
    $("#listarPaquetasSobrante").html("");
    let trid = 0;
    //let id = 0;
    cubicajesSobrantes.forEach((cubicaje) => {

        let fila = `<tr id ="${trid}">
                        <td>${cubicaje.paqueta}</td>
                        <td>${cubicaje.bloque}</td>
                        <td>${cubicaje.largo}</td>
                        <td>${cubicaje.alto}</td>
                        <td>${cubicaje.ancho}</td>
                        <td>${cubicaje.pulgadasAlto}</td>
                        <td>${cubicaje.pulgadasAncho}</td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarMaderaSobrante(${trid},${cubicaje.bloque})"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>`;
        $("#listarPaquetasSobrante").append(fila);
        trid++;
    });
}

// funcion eliminarMaderaSobrante, recibe el id de la fila y el id de la madera, elimina la madera de la tabla y de la memoria localstorage
function eliminarMaderaSobrante(id, bloque) {
    numBloque--;
    Swal.fire({
        title: "¿Está seguro que desea eliminar la paqueta?",
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
            title: "¿Está seguro que desea terminar la paqueta?",
            text: "¡No podrá revertir esta acción!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#597504",
            cancelButtonColor: "#d33",
            confirmButtonText: "¡Si, terminar!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                estado();
                $("#calificarMadera").click();
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
            cubicajesSobrantes: cubicajesSobrantes,
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
                    window.location.href = "/cubicaje";
                });
            } else {
                Swal.fire({
                    title: guardado.message,
                    icon: "error",
                    confirmButtonColor: "#597504",
                    confirmButtonText: "OK",
                });
            }
        },
    });
}
