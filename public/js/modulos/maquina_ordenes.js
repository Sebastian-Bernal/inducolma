let estado = 'INICIAL'
$(document).ready(function() {
    $('#listaOrdenes').DataTable({
        "language": {
                "url": "/DataTables/Spanish.json"
                },
        "responsive": true
        
    });
})


function estadoDeMaquina(xeve){
    alert("se dio click en un boton")
}