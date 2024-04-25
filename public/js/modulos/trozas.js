
// variables globales
var trozasEntrada;
var cubicajeBloques = []
var cubicajeIngresado = [];
var listaCubicajes = $("#listaCubicaje").DataTable({
    language: {
        url: "/DataTables/Spanish.json",
    },
    responsive: true,
    pageLength: 5,
    lengthChange: false,
});


$(document).ready(function () {
    $('#trozas').click();
    comprobarLocalStorage();
    mostrarTrozaActual();


});




/**
 * Loads the given 'trozas' into the 'trozasEntrada' variable.
 *
 * @param {Array} trozas - The array of 'trozas' to be loaded.
 * @returns {undefined}
 */
function cargarTrozas(trozas) {
    trozasEntrada = trozas;
}

/**
 * Displays the current troza (log) to be processed.
 *
 * If there are no trozas in the 'cubicajeIngresado' array, it calls the 'siguienteTroza' function to get the next troza from the 'trozasEntrada' array.
 * If there are no more trozas in the 'trozasEntrada' array, it calls the 'mostrarValorTrozaActual' function without any arguments.
 * Otherwise, it calls the 'siguienteTroza' function to get the next troza from the 'trozasEntrada' array.
 *
 * @returns {void}
 */
function mostrarTrozaActual() {

    if (cubicajeIngresado.length == 0) {
        siguienteTroza();
    } else if( trozasEntrada.length == 0){
        mostrarValorTrozaActual();
    }
    else {
        siguienteTroza();
    }

}
/**
 * Displays the value of the current troza.
 *
 * @param {Object} mostrar - The troza object to display. Default is null.
 * @returns {Object} - The next troza object.
 */
function mostrarValorTrozaActual(mostrar = null) {

    if (mostrar == null) {
        alertaErrorSimple('Fin de las trozas a transformar, por favor termine el proceso de transformacion de la entrada')
        $('#largo').val('');
        $('#numeroBloque').empty();
    } else {
        $('#idCubicaje').val('').val(mostrar.id);
        $('#numeroBloque').empty().append(mostrar.bloque);
        $('#largo').val(mostrar.largo);
        $('#entradaId').val(mostrar.entrada_madera_id);
        $('#bloque').val(mostrar.bloque);
        $('#paqueta').val(mostrar.paqueta);
    }
}



/**
 * Checks the local storage for existing data and updates the global variables 'cubicajeBloques' and 'cubicajeIngresado' accordingly.
 * If no data is found in the local storage, the global variables are set to empty arrays.
 *
 * @returns {void}
 */
function comprobarLocalStorage() {
    if (
        localStorage.getItem("cubicajes") == null ||
        localStorage.getItem("cubicajes") == "[]"
    ) {
        cubicajeBloques = [];
        cubicajeIngresado = [];
    } else {
        cubicajeBloques = JSON.parse(localStorage.getItem("cubicajes"));
        cubicajeIngresado = JSON.parse(localStorage.getItem("cubicajeIngresados"));
        listarPaquetas(sortData(cubicajeBloques));
    }


}

/**
 * Verifies the inputs for adding cubicaje.
 *
 * @returns {boolean} - Returns true if all inputs are valid and saves the paqueta, otherwise returns false.
 */
function verificarInputs() {
    //console.log("entro a verificar inputs");
    var valido;
    var campos = $("#agregarCubicaje").find("input");
    $.each(campos, function (index, value) {

        if (value.id == 'largo'){
            validaLargo();
            return false;
        }

        if (value.value == "") {
            Swal.fire({
                title: "¡Ingrese todos los datos!",
                icon: "warning",
                confirmButtonColor: "#597504",
                confirmButtonText: "OK",
            });
            valido = false;
        } else {
            if (
                validaLargo() == false &&
                validaAncho() == false &&
                validaAlto() == false

            ) {
                valido = true;
            } else {
                valido = false;
            }
        }
    });
    if (valido) {

        guardarPaqueta();
    }
}

/**
 * Validates the length value.
 *
 * @returns {boolean} True if the length is invalid, false otherwise.
 */
function validaLargo() {
    var largo = $("#largo").val();
    if (largo == "") {
        mostrarTrozaActual();
        return ;
    }
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


/**
 * Validates the width value.
 *
 * This function checks if the width value entered by the user is within the range of 10 to 50.
 * If the value is outside this range, it displays a warning message using Swal.fire() function.
 *
 * @returns {boolean} - Returns true if the width value is outside the range, otherwise returns false.
 */
function validaAncho() {
    var ancho = $("#ancho").val();
    if (ancho < 6 || ancho > 50) {
        Swal.fire({
            title: "¡Ingrese un valor de ancho entre 6 y 50!",
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

/**
 * Validates the value of the 'alto' input field.
 *
 * @returns {boolean} True if the value is not between 10 and 50, false otherwise.
 */
function validaAlto() {
    var alto = $("#alto").val();
    if (alto < 6 || alto > 50) {
        Swal.fire({
            title: "¡Ingrese un valor de alto entre 6 y 50!",
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



/**
 * Saves the paqueta information to the cubicajeBloques array and updates the localStorage.
 *
 * @returns {void}
 */
function guardarPaqueta() {

    let id = $('#idCubicaje').val();
    let paqueta = $("#paqueta").val();
    let bloque = $('#bloque').val();
    let largo = $("#largo").val();
    let alto = $("#alto").val();
    let ancho = $("#ancho").val();
    let pulgadasAlto = $("#pulgadas_alto").val();
    let pulgadasAncho = $("#pulgadas_ancho").val();
    let entrada_id = $("#entradaId").val();
    let user_id = $("#userId").val();

    $('#ingresoAnterior').val(id);

    registroPaqueta = Object.assign(
        {},
        {
            id,
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

    if(bloqueGuardado(siguiente)){
        alertaErrorSimple('El bloque ya esta guardado, por favor ingrese el siguiente bloque.')
        limpiarInputs();
        siguienteTroza();
        return;
    }
    cubicajeIngresado.push(siguiente);
    cubicajeBloques.unshift(registroPaqueta);
    setLocalStorage(cubicajeBloques, cubicajeIngresado);

    listarPaquetas(cubicajeBloques);
    limpiarInputs();

    mostrarTrozaActual();

}


/**
 * Check if a given block is already saved in the cubicajeIngresado array.
 *
 * @param {Object} ingresado - The block to check.
 * @param {string} ingresado.id - The ID of the block.
 * @returns {boolean} - True if the block is already saved, false otherwise.
 */
function bloqueGuardado(ingresado) {
    const contains = cubicajeIngresado.some((cubicaje) => cubicaje.id === ingresado.id);
    return contains;
}

/**
 * Retrieves the next troza from the trozasEntrada array and updates the UI with its values.
 *
 * @returns {Object} The next troza object from the trozasEntrada array.
 */
function siguienteTroza() {

    if (cubicajeIngresado.length > 0) {
        let idsCubicajesIngresados = cubicajeIngresado.map((cubicaje) => cubicaje.id);
        trozasEntrada = trozasEntrada.filter((troza) => !idsCubicajesIngresados.includes(troza.id));
    }
    siguiente = trozasEntrada.shift();
    mostrarValorTrozaActual(siguiente);
    return siguiente;

}


/**
 * Clears the input fields for length, height, width,
 * Sets the focus on the height input field.
 */
function limpiarInputs() {
    // $('#largo').val('');
    $("#alto").val("");
    $("#ancho").val("");
    $("#alto").focus();
}

/**
 * Function to list the packages in the table.
 *
 * @param {Array} cubicajeBloques - The array of cubicaje blocks.
 * @returns {void}
 */
function listarPaquetas(cubicajeBloques) {
    // Eliminar todas las filas existentes en la tabla
    listaCubicajes.clear().draw();

    cubicajeBloques.forEach((cubicaje) => {
        let fila = [
            cubicaje.paqueta,
            cubicaje.entrada_id,
            cubicaje.bloque,
            cubicaje.largo,
            cubicaje.alto,
            cubicaje.ancho,
            `<button type="button" class="btn btn-danger" onclick="eliminarMadera(${cubicaje.id})"><i class="fas fa-trash-alt"></i></button>`
        ];

        // Agregar la nueva fila a la tabla
        listaCubicajes.row.add(fila).draw();
    });

}


/**
 * Function to delete a wood block.
 *
 * @param {number} idCubicaje - The ID of the cubicaje to be deleted.
 * @returns {void}
 */
function eliminarMadera(idCubicaje) {
    //console.log(idCubicaje);
    Swal.fire({
        title: "¿Está seguro que desea eliminar el bloque?",
        text: "¡No podrá revertir esta acción!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "¡Si, eliminar!",
        cancelButtonText: "Cancelar",
    }).then((result) => {

        if (result.isConfirmed) {

            trozasEntrada.unshift(cubicajeIngresado.find((cubicaje) => cubicaje.id == parseInt(idCubicaje)));
            cubicajeIngresado = cubicajeIngresado.filter((ingresado) => ingresado.id != parseInt(idCubicaje))
            cubicajeBloques = cubicajeBloques.filter((cubicaje) => parseInt(cubicaje.id) != idCubicaje);
            setLocalStorage(sortData(cubicajeBloques), cubicajeIngresado);
            listarPaquetas(sortData(cubicajeBloques));

            if (siguiente == undefined) {
                mostrarTrozaActual();
            }
        }
    });

}

/**
 * Sets the cubicajeBloques and cubicajeIngresado arrays in the local storage.
 *
 * @param {Array} cubicajeBloques - The cubicajeBloques array to be stored in the local storage.
 * @param {Array} cubicajeIngresado - The cubicajeIngresado array to be stored in the local storage.
 * @returns {void}
 */
function setLocalStorage(cubicajeBloques, cubicajeIngresado) {
    localStorage.setItem("cubicajes", JSON.stringify(cubicajeBloques));
    localStorage.setItem("cubicajeIngresados", JSON.stringify(cubicajeIngresado));
}

function sortData(data) {
    data.sort((a, b) => parseInt(a.bloque) - parseInt(b.bloque));
    return data;
}

/**
 * Terminates the transformation of the input by saving the package to the database.
 *
 * If the 'cubicajeBloques' array has elements, a confirmation dialog is displayed to the user.
 * If the user confirms, the 'guardarPaquetaBD' function is called to save the package to the database.
 *
 * If the 'cubicajeBloques' array is empty, an error message is displayed to the user.
 *
 * @returns {void}
 */
function terminarPaqueta() {
    if (cubicajeBloques.length > 0) {
        //guardarPaquetaBD();
        Swal.fire({
            title: "¿Está seguro que desea terminar la transformacion de la entrada?",
            text: "¡No podrá revertir esta acción!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#597504",
            cancelButtonColor: "#d33",
            confirmButtonText: "¡Si, terminar!",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                guardarPaquetaBD();
            }
        });
    } else {
        alertaErrorSimple('La paqueta tiene 0 bloques no puede guardar', 'warning');
    }
}

/**
 * Sends an AJAX request to save the package in the database.
 *
 * @returns {void}
 */
function guardarPaquetaBD() {
    $.ajax({
        url: "/cubicaje-transformacion",
        data: {
            cubicajesTransformacion: cubicajeBloques,
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
                    cubicajes = [];
                    numBloque = 0;
                    localStorage.clear();
                    window.location.href = "/trabajo-maquina";
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
        error: function(error){
            alertaErrorSimple('Error interno del servidor', 'error');
            console.log(error);
        }
    });
}
