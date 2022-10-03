// on load
$(document).ready(function () {

    $('#producto').select2({
        width: 'resolve',
        placeholder: 'Seleccione...',
        dropdownParent: $("#creapedido"),
        theme: "bootstrap-5",
    });
});

/**
 * verPaqueta - Funcion que muestra los detalles de la paqueta seleccionada
 * se muestra en un swiftalert
 * @param  {[int]} id_entrada [entrada_madera_id]
 * @return {[int]} paqueta    [paqueta_id]
 */
function verPaqueta(id_entrada,paqueta) {
    $.ajax({
        url: `/paqueta`,
        type: "POST",
        dataType: "JSON",
        data: {
            entrada_madera_id: id_entrada,
            paqueta: paqueta,
            _token: $('input[name="_token"]').val()
        },
        success: function (e) {
            let lista =`<table class="table table-striped table-bordered table-hover"><thead><tr><th>Bloque</th><th>Pulgadas cuadradas</th><tr></thead><tbody>` ;
            e.forEach(element => {
                lista = lista +'<tr>' +
                    '<td>' + element.bloque + '</td>' +
                    '<td>' + element.pulgadas_cuadradas + '</td>' +
                '</tr>'
            });

            Swal.fire({
                title: 'Datos de la paqueta',
                html: lista + '</tbody></table>',
                confirmButtonColor: '#597504',
                confirmButtonText: 'OK'
            });
        },
    })
}
