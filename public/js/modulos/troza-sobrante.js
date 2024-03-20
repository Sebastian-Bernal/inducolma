
// variables globales
var trozasEntrada;
var cubicajeBloques = []
var cubicajeIngresado = [];
var index = 0;
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
        alertaErrorSimple('Fin de las trozas a transformar, por favor termine el proceso de transformacion de la entrada', 'warning')

        $('#numeroBloque').empty();
    } else {
        $('#idCubicaje').val('').val(mostrar.id);
        $('#numeroBloque').empty().append(mostrar.bloque);
        $('#numeroPaqueta').empty().append(mostrar.paqueta);
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
        if (localStorage.getItem("cubicajeIngresados") == null ||
            localStorage.getItem("cubicajeIngresados") == "[]"
        ) {
            cubicajeIngresado = [];
        } else{
            cubicajeIngresado = JSON.parse(localStorage.getItem("cubicajeIngresados"));

        }
    } else {
        cubicajeBloques = JSON.parse(localStorage.getItem("cubicajes"));
        cubicajeIngresado = JSON.parse(localStorage.getItem("cubicajeIngresados"));
        obtenerUltimoIndex();
        listarPaquetas(sortData(cubicajeBloques));
    }
}

function obtenerUltimoIndex(){
    index = cubicajeBloques.reduce((max, bloque) => Math.max(max, bloque.index), -1);
}

/**
 * Verifies the inputs for adding cubicaje.
 *
 * @returns {boolean} - Returns true if all inputs are valid and saves the paqueta, otherwise returns false.
 */
function verificarInputs() {

    var valido;
    var campos = $("#agregarSobrante").find("input");
    $.each(campos, function (index, value) {

        if (value.value == "") {
            alertaErrorSimple("!Ingrese todos los datos!", 'warning')
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
    if (largo < 10) {
        alertaErrorSimple("¡Ingrese un valor de largo mayor a 10 ", "warning")
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
    if (ancho < 1) {
        alertaErrorSimple("¡Ingrese un valor de ancho mayor a 1!", "warning")
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
    if (alto < 1) {
        alertaErrorSimple("¡Ingrese un valor de alto mayor a 1", "warning")
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
    let entrada_id = $("#entradaId").val();
    let user_id = $("#userId").val();
    index++;
    $('#ingresoAnterior').val(id);

    registroPaqueta = Object.assign(
        {},
        {
            index,
            id,
            paqueta,
            bloque,
            largo,
            alto,
            ancho,
            entrada_id,
            user_id,
        }
    );

    if(verificarLimiteSobrantes(id)){
        if(siguiente.id == id){
            siguienteTroza();
        }{
            mostrarValorTrozaActual(siguiente);
        }
    }
    if(obtenerCantidadIngresados(id) == 0){
        cubicajeIngresado.push(siguiente);
    }

    cubicajeBloques.unshift(registroPaqueta);
    setLocalStorage(cubicajeBloques, cubicajeIngresado);

    listarPaquetas(cubicajeBloques);
    limpiarInputs();
}

function obtenerCantidadIngresados(idCubicaje){
    return cubicajeIngresado.filter(cubicaje => cubicaje.id == idCubicaje).length;
}

/**
 * Checks if the number of leftover cubicajes for a given ID exceeds the limit.
 *
 * @param {number} id - The ID to check the leftover cubicajes for.
 * @returns {boolean} - True if the number of leftover cubicajes is greater than or equal to 4, or if it is equal to 3; otherwise, false.
 */
function verificarLimiteSobrantes(id){
    const cubicajeSobrantes = cubicajeBloques.filter(cubicaje => cubicaje.id == id);
    const cantidadSobrantes = cubicajeSobrantes.length;

    return cantidadSobrantes >= 4 || cantidadSobrantes == 3;
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

function validarSiguienteTroza(){

    if (typeof(siguiente) != undefined || siguiente != null || siguiente != '') {

        Swal.fire({
            title: "¿Está seguro depasar a los sobrantes de la troza siguiente?",
            text: "!Esta acción no se puede revertir!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#597504",
            cancelButtonColor: "#ff7e00",
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                cubicajeIngresado.push(siguiente);
                setLocalStorage(null, cubicajeIngresado)
                siguienteTroza();
            }
        });
    } else{
        console.log('siguiente not found')
    }
}


/**
 * Clears the input fields for length, height, width,
 * Sets the focus on the height input field.
 */
function limpiarInputs() {
    $('#largo').val('');
    $("#alto").val("");
    $("#ancho").val("");
    $("#largo").focus();
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
            `<button type="button" class="btn btn-danger" onclick="eliminarBloque(${cubicaje.index}, ${cubicaje.id})"><i class="fas fa-trash-alt"></i></button>`
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
function eliminarBloque(index, cubicajeId) {
    ////console.log(idCubicaje);
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

            if(countCubicajeBloques(cubicajeId) == 1){

                trozasEntrada.unshift(siguiente);

                cubicajeIngresado = cubicajeIngresado.filter((ingresado) => ingresado.id != parseInt(cubicajeId));
                //siguienteTroza();
            }else{
                actual = cubicajeIngresado.find((ingresado) => ingresado.id == cubicajeId);
                ////console.log(actual);
                mostrarValorTrozaActual(actual);
            }

            cubicajeBloques = cubicajeBloques.filter((cubicaje) => parseInt(cubicaje.index) != index);
            setLocalStorage(sortData(cubicajeBloques), cubicajeIngresado);
            listarPaquetas(sortData(cubicajeBloques));

            //console.log(cubicajeIngresado, 'ingresado');
            //console.log(trozasEntrada, 'trozasEntrada');
            //console.log(siguiente);
        }
    });

}

/**
 * Counts the number of cubicaje blocks with the given cubicajeId.
 *
 * @param {number} cuicajeId - The ID of the cubicaje to count.
 * @returns {number} The number of cubicaje blocks with the given cubicajeId.
 */
function countCubicajeBloques(cuicajeId){
    return cubicajeBloques.filter((cubicaje) => cubicaje.id == cuicajeId).length;
}

/**
 * Sets the cubicajeBloques and cubicajeIngresado arrays in the local storage.
 *
 * @param {Array} cubicajeBloques - The cubicajeBloques array to be stored in the local storage.
 * @param {Array} cubicajeIngresado - The cubicajeIngresado array to be stored in the local storage.
 * @returns {void}
 */
function setLocalStorage(cubicajeBloques = null , cubicajeIngresado = null ) {
    if (cubicajeBloques != null) {
        localStorage.setItem("cubicajes", JSON.stringify(cubicajeBloques));
    }
    if (cubicajeIngresado != null) {
        localStorage.setItem("cubicajeIngresados", JSON.stringify(cubicajeIngresado));
    }

}

/**
 * Sorts the given data array in descending order based on the 'bloque' property.
 *
 * @param {Array} data - The array to be sorted.
 * @returns {Array} - The sorted array.
 */
function sortData(data) {
    data.sort((a, b) => parseInt(b.bloque) - parseInt(a.bloque));
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
        url: "/sobrante-troza",
        data: {
            transformacionesSobrantes: cubicajeBloques,
            _token: $('input[name="_token"]').val(),
        },
        type: "post",
        success: function (guardado) {
            console.log(guardado);
            alertaErrorSimple('Se guardo correctamente', 'success');
            cubicajes = [];
            localStorage.clear();
            window.location.href = "/trabajo-maquina";

        },
        error: function(error){
            console.log(error.responseJSON.message);
            alertaErrorSimple('Error interno del servidor', 'error');

        }
    });
}
