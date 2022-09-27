var rutasProg = [];
var numProceso = 0;
// Agregar valores al local storage
comprobarLocalStorage()

function comprobarLocalStorage() {
    if(localStorage.getItem('rutasProg') == null || localStorage.getItem('rutasProg') == '[]') {
        rutasProg = [];
    } else {
        rutasProg = JSON.parse(localStorage.getItem('rutasProg'));
        listarProcesos(rutasProg);
    }
}

function guardarProceso(entra, sale, maquina, idMaquina, observa) {

    let coincide = rutasProg.filter(rutasProg => rutasProg.maquina == maquina ).length
    if(coincide > 0 ){
        Swal.fire({
            title: '¡Proceso ya fue definido!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        });

    }else{
        numProceso++;
        let rutaNum = numProceso;
        registroProceso = Object.assign({}, {rutaNum, entra, sale, maquina, idMaquina, observa});
        rutasProg.unshift(registroProceso);
        localStorage.setItem('rutasProg', JSON.stringify(rutasProg));
        listarProcesos(rutasProg);
    };
};
// Funciones de validacion de campos en formularios

function agregaRutaInicial(){
    let valido, proceso = 'inicial', campoValor = 0;
    let campos = $('#agregarInicial').find('select');
    let textarea = $('#agregarInicial').find('textarea');
    let entra, sale, maquina, observa, idMaquina;
    let maquinaria = document.getElementById("maquinaInicial");
    entra = ''
    sale = ''
    maquina = ''
    idMaquina = 0
    $.each(campos, function(index,value) {
        campoValor = value.value
        if(campoValor == 0){
            camposVacios()

        }else{
            if(value.id == 'entraInicial'){
                entra= value.value
            }
            if(value.id == 'saleInicial'){
                sale = value.value
            }
            if(value.id == 'maquinaInicial'){
                maquina = maquinaria.options[maquinaria.selectedIndex].text
                idMaquina = value.value
            }
        }
    });
    if(entra == 0 || sale == 0 || maquina == 0){
        campoValor = 0
    }
    let observaciones = $("#observacionInicial").val()
            if(observaciones == ''){
                Swal.fire({
                    title: '¡No contiene observaciones, desea dejar así!',
                    icon: 'warning',
                    confirmButtonColor: '#597504',
                    confirmButtonText: 'Si',
                    showDenyButton: true,
                    denyButtonText: 'No'

                }).then((result) => {
                    if(result.isConfirmed){
                        if(campoValor != 0){
                        valido = true;
                        observa = ''
                        validar(valido, proceso, entra, sale, maquina, idMaquina, observa)
                        }else{
                            camposVacios()
                            valido=false;
                            validar(valido, proceso, entra, sale, maquina, idMaquina, observa)
                        }
                    }else{
                        valido = false;
                        validar(valido, proceso ,  entra, sale, maquina, idMaquina, observa)
                    }
                });
            }
            if(observaciones != '' && campoValor != 0){
                valido = true;
                observa = observaciones
                validar(valido, proceso,  entra, sale, maquina, idMaquina, observa)
            }

};

function agregaRutaIntermedia(){
    let valido , proceso = 'intermedio', campoValor = 0;
    let campos = $('#agregarIntermedia').find('select');
    let textarea = $('#agregarIntermedia').find('textarea');
    let entra, sale, maquina, observa,entracampo,salecampo,maquinacampo;
    let maquinaria = document.getElementById("maquinaIntermedia");
    $.each(campos, function(index, value) {
        campoValor = value.value
        if(campoValor == 0){
        camposVacios()
        }else{
            if(value.id == 'entraIntermedia'){

                entra= value.value
            }
            if(value.id == 'saleIntermedia'){

                sale = value.value
            }
            if(value.id == 'maquinaIntermedia'){

                maquina = maquinaria.options[maquinaria.selectedIndex].text
                idMaquina = value.value
            }
        }
    });
       if(entra == 0 || sale == 0 || maquina == 0){
        campoValor = 0
    }
    let observaciones = $("#observacionIntermedia").val()
    if(observaciones == ''){
        Swal.fire({
            title: '¡No contiene observaciones, desea dejar así!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'Si',
            showDenyButton: true,
            denyButtonText: 'No'

        }).then((result) => {
            if(result.isConfirmed){
                if(campoValor != 0){
                valido = true;
                observa = ''
                validar(valido, proceso, entra, sale, maquina, idMaquina, observa)
                }else{
                    camposVacios()

                }
            }else{
                valido = false;
                validar(valido, proceso ,  entra, sale, maquina, idMaquina, observa)
            }
        });
    }
    if(observaciones != '' && campoValor != 0){
        valido = true;
        observa = observaciones
        validar(valido, proceso,  entra, sale, maquina, idMaquina, observa)
    }


};

function agregaRutaFinal(){
    let valido , proceso = 'final', campoValor = 0;
    let campos = $('#agregarFinal').find('select');
    let entra, sale, maquina, observa;
    let maquinaria = document.getElementById("maquinaFinal");
    $.each(campos, function(index, value) {
        campoValor = value.value
        if(campoValor == 0){
        camposVacios()
        }else{
            if(value.id == 'entraFinal'){
                entra= value.value
            }
            if(value.id == 'saleFinal'){
                sale = value.value
            }
            if(value.id == 'maquinaFinal'){
                maquina = maquinaria.options[maquinaria.selectedIndex].text
                idMaquina = value.value
            }
        }
    });
    if(entra == 0 || sale == 0 || maquina == 0){
        campoValor = 0
    }
    let observaciones = $("#observacionFinal").val()
    if(observaciones == ''){
        Swal.fire({
            title: '¡No contiene observaciones, desea dejar así!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'Si',
            showDenyButton: true,
            denyButtonText: 'No'

        }).then((result) => {
            if(result.isConfirmed){
                if(campoValor != 0){
                valido = true;
                observa = ''
                validar(valido, proceso, entra, sale, maquina, idMaquina, observa)
                }else{
                    camposVacios()
                }
            }else{
                valido = false;
                validar(valido, proceso ,  entra, sale, maquina, idMaquina, observa)
            }
        });
    }
    if(observaciones != '' && campoValor != 0){
        valido = true;
        observa = observaciones
        validar(valido, proceso,  entra, sale, maquina, idMaquina, observa)
    }

};

function agregaRutaAcabados(){
    let valido , proceso = 'acabados', campoValor = 0;
    let campos = $('#agregarAcabados').find('select');
    let entra, sale, maquina, observa;
    let maquinaria = document.getElementById("maquinaAcabados");
    $.each(campos, function(index, value) {
        campoValor = value.value
        if(campoValor == 0){
        camposVacios()
        }else{
            if(value.id == 'entraAcabados'){
                entra= value.value
            }
            if(value.id == 'saleAcabados'){
                sale = value.value
            }
            if(value.id == 'maquinaAcabados'){
                maquina = maquinaria.options[maquinaria.selectedIndex].text
                idMaquina = value.value
            }
        }
    });
    if(entra == 0 || sale == 0 || maquina == 0){
        campoValor = 0
    }
    let observaciones = $("#observacionAcabados").val()
    if(observaciones == ''){
        Swal.fire({
            title: '¡No contiene observaciones, desea dejar así!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'Si',
            showDenyButton: true,
            denyButtonText: 'No'

        }).then((result) => {
            if(result.isConfirmed){
                if(campoValor != 0){
                valido = true;
                observa = ''
                validar(valido, proceso, entra, sale, maquina, idMaquina, observa)
                }else{
                    camposVacios()
                }
            }else{
                valido = false;
                validar(valido, proceso ,  entra, sale, maquina, idMaquina, observa)
            }
        });
    }
    if(observaciones != '' && campoValor != 0){
        valido = true;
        observa = observaciones
        validar(valido, proceso,  entra, sale, maquina, idMaquina, observa)
    }

};



// Validacion de datos correcta - procede a enviar a localStorage
function validar(valido, proceso,  entra, sale, maquina, idMaquina, observa) {
    if(valido) {
        console.log("envia datos");
        procesoEjecutado(proceso)
        guardarProceso(entra, sale, maquina, idMaquina, observa)
    }else{
    console.log("no envia datos");
    procesoEjecutado(proceso)
    }
}

// define que formulario se debe limpiar
function procesoEjecutado(proceso) {
    if(proceso == 'inicial'){
            limpiarInicial()
    }
    if(proceso == 'intermedio'){
        limpiarIntermedia()
    }
    if(proceso == 'final'){
        limpiarFinal()
    }
    if(proceso == 'acabados'){
        limpiarAcabados()
    }
}

// Limpiar Inputs en formularios
function limpiarInicial(){
    $('#entraInicial').val('0');
    $('#saleInicial').val('0');
    $('#maquinaInicial').val('0');
    $('#observacionInicial').val('');
    $('#entraInicial').focus();
};
function limpiarIntermedia(){
    $('#entraIntermedia').val('0');
    $('#saleIntermedia').val('0');
    $('#maquinaIntermedia').val('0');
    $('#observacionIntermedia').val('');
    $('#entraIntermedia').focus();
};
function limpiarFinal(){
    $('#entraFinal').val('0');
    $('#saleFinal').val('0');
    $('#maquinaFinal').val('0');
    $('#observacionFinal').val('');
    $('#entraFinal').focus();
};
function limpiarAcabados(){
    $('#entraAcabados').val('0');
    $('#saleAcabados').val('0');
    $('#maquinaAcabados').val('0');
    $('#observacionAcabados').val('');
    $('#entraAcabados').focus();
};

//Mensaje de campos vacios en select
function camposVacios(){

        Swal.fire({
            title: '¡Ingrese todos los datos!',
            icon: 'warning',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        });
        valido = false;

}

// funcion listarProcesos, recibe un array de objetos y los muestra en la tabla
function listarProcesos(rutasProg) {
    $('#listarProcesos').html('');
    rutasProg.forEach(rutasProg => {
        let fila = `<tr id ="${rutasProg.rutaNum}">
                        <td>${rutasProg.rutaNum}</td>
                        <td>${rutasProg.maquina}</td>
                        <td>${rutasProg.entra}</td>
                        <td>${rutasProg.sale}</td>
                        <td>${rutasProg.observa}</td>
                        <td><button type="button" class="btn btn-danger" onclick="eliminarMadera(${rutasProg.rutaNum})"><i class="fas fa-trash-alt"></i></button></td>
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
            rutasProg = rutasProg.filter(rutasProg => rutasProg.rutaNum != rutaNum);
            localStorage.setItem('rutasProg', JSON.stringify(rutasProg));
            listarProcesos(rutasProg);
        }
    })
}

function guardarRutaBD() {

    $.ajax({
        url: '/procesos',
        data: {
            proceso: rutasProg,
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
                    rutasProg = [];
                    numBloque = 0;
                    localStorage.removeItem('rutasProg');
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
