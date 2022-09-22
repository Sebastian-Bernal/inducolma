function reporteIngresoMaderas() {

    let desde = $('#desdeIm');
    let hasta = $('#hastaIm');
    let reporte = $('#tipoReporte');
    let especifico = $('#especifico');
    if (reporte.val() == "") {
        alertaErrorSimple('Seleccione un tipo de reporte!', 'error');
    } else if(desde.val() == '' || hasta.val() == ''){
        alertaErrorSimple('Ingrese datos validos para las fechas desde y hasta!','error');
    }
    else {
        if ((reporte.val() == '3' || reporte.val() == '4') && especifico.val() == "") {
            alertaErrorSimple('Seleccione el filtro de busqueda!','error');
            especifico.click();
        } else {
            $('#reporteIngresoMadera').submit();
            desde.val('');
            hasta.val('');
            reporte.val('');
        }
    }


}

function datoEspecifico() {
    let reporte = $('#tipoReporte');
    let especifico = $('#especifico');

    if (reporte.val() == '3') {
        proveedores();
    } else if (reporte.val() == '4'){
        tipoMadera();
    } else{
        $('#divEspecifico').hide(300);
    }
}

function proveedores () {
    $('#especifico').val('')
    $('#divEspecifico').show(300);
    $('#especifico').select2({
        width: 'aut',
        placeholder: 'Seleccione...',
        theme: "bootstrap-5",
        ajax: {
            url: '/get-proveedores',
            type: 'get',
            delay: 800,
            data: function (params) {
                return {
                    descripcion: params.term,
                    _token: $('input[name="_token"]').val()
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
        },
        language: {
            noResults: function () {
                return "No hay resultados";
            },
            searching: function () {
                return "Buscando..";
            },

        },
    })
}

function tipoMadera() {
    $('#especifico').val('')
    $('#divEspecifico').show(300);
    $('#especifico').select2({
        width: 'aut',
        placeholder: 'Seleccione...',
        theme: "bootstrap-5",
        ajax: {
            url: '/get-tipo-maderas',
            type: 'get',
            delay: 800,
            data: function (params) {
                return {
                    descripcion: params.term,
                    _token: $('input[name="_token"]').val()
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
        },
        language: {
            noResults: function () {
                return "No hay resultados";
            },
            searching: function () {
                return "Buscando..";
            },

        },
    })
}
