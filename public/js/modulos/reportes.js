// variable global formularios
var formReporteIgresoMadera = document.getElementById('reporteIngresoMadera');

function reporteIngresoMaderas(ruta) {
    let desde = $('#desdeIm');
    let hasta = $('#hastaIm');
    if (desde.val() == '' || hasta.val() == '') {
        Swal.fire({
            title: 'Ingrese las fechas desde y hasta.!',
            icon: 'error',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        })
    } else {

        formReporteIgresoMadera.setAttribute('action', ruta);
        $('#reporteIngresoMadera').submit();
        desde.val('');
        hasta.val('');
    }


}
