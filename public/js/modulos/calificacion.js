/**
 * variables globales
 */

var enviar = false; //variable para validar el formulario
$(document).ready(function() {
    
    
    $('#listaCalificaciones').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true, 
        "pageLength": 5,
        
        "lengthChange": false
        
    });
    
});


/*
 * funcion que suma los puntos de la calificacion
 */
function sumarPuntos() {
    
    var longitud = document.getElementById("longitudMadera").value;
    var cantonera  = document.getElementById("cantonera").value;
    var hongos = document.getElementById("hongos").value;
    var rajadura = document.getElementById("rajadura").value;
    var bichos = document.getElementById("bichos").value;
    var organizacion = document.getElementById("organizacion").value;
    var rango = document.getElementById("rangoMaxMin").value;
    var area = document.getElementById("areas").value;
    var puntos = 0;
    puntos = parseFloat(longitud) +
             parseFloat(cantonera) + 
             parseFloat(hongos) + 
             parseFloat(rajadura) + 
             parseFloat(bichos) + 
             parseFloat(organizacion)+
             parseFloat(rango)+
             parseFloat(area);
    document.getElementById("puntos").value = puntos;

    if(enviar) {
        guardarCalificacion(longitud,cantonera,hongos,rajadura,bichos,organizacion,rango,area,puntos);
    }

    
}   

/**
 * funcion que valida los select del formulario formCalificacion
 */

function validarFormulario() {
    var select = $('#formCalificacion').find('select');
    $.each(select, function (index, value) {
        /// console.log(value);
         if (value.value == '0') {
            value.focus();
            enviar = false;
            swal.fire({
                title: '¡Tiene items sin calificar. por favor asigne todas las calificaciones!',
                icon: 'warning',
                confirmButtonColor: '#597504',
                confirmButtonText: 'OK'
            })
            
         } else{
            enviar = true;
            //sumarPuntos();
         }
     })
    if(enviar) {
        if (window.location.pathname = '/calificaciones') {
            $('#formCalificacion').submit();
        } else{
            sumarPuntos();
        }
        
    }
}

/**
 * Funcion guardarCalificacion, envia json al controlador para guardar la calificacion
 */
function guardarCalificacion(longitud,cantonera,hongos,rajadura,bichos,organizacion,rango,area,puntos) {
    Swal.fire({
        html:
            `<div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>`,
        showConfirmButton: false,
        timer:10000,
    })
    $.ajax({
        url: '/calificaciones',
        data: {
                longitud_madera: longitud,
                cantonera: cantonera,
                hongos: hongos,
                rajadura: rajadura,
                bichos: bichos,
                organizacion: organizacion,
                areas_transversal_max_min: rango,
                areas_no_convenientes: area,
                total: puntos,
                entrada_madera_id: $('#entradaId').val(),
                paqueta: parseInt(cubicajes[0].paqueta),
                _token: $('input[name="_token"]').val()
        },
        type: 'post', 
        success: function(guardado) {
           // console.log(guardado);
            if(guardado.success == true) {
                guardarPaquetaBD();
                
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

