$(document).ready(function() {

    $('#listaTipoMadera').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true

    });

});

function mayusculas() {

    var x = document.getElementById("descripcion");
    x.value = x.value.toUpperCase();

}
