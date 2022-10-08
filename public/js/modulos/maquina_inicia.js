usuarioMaquina=[];
document.getElementById("users").click();
//confirmar la maquina a trabajar
function confirmaMaquina(turnos,usuario){
    let campos = $('#maquinas').find('select');
    let usuId = usuario;
    maquinaAsignada = turnos
    let maquinaId
    $.each(campos, function (index, value) {
        campoValor = value.value
        if (campoValor == '') {
            camposVacios()

        } else {
            if (value.id == 'maquina') {
                maquinaId = value.value
            }
            //valida si la maquina seleccionada es la misma asignada
            let coincide = maquinaAsignada.filter(maquinaAsignada => maquinaAsignada.maquina_id == maquinaId).length
            if (coincide <= 0){
                Swal.fire({
                    title: '¡Esta seguro de cambiar la maquina en la que esta asignado!',
                    icon: 'warning',
                    confirmButtonColor: '#597504',
                    confirmButtonText: 'Si',
                    showDenyButton: true,
                    denyButtonText: 'No'
        
                }).then((result) => {
                    if (result.isConfirmed) {
                        actualizaMaquina(maquinaId, usuId)
                        guardaUsuario(maquinaId, usuId)
                        leeUsuraio(maquinaId)
                        alert("se cambia los valores de auxiliares")
                    }
                })        
            }else{
                guardaUsuario(maquinaId,usuId)
                alert("se guarda la informacion")
            }
        }
    })
}

function camposVacios(){
    Swal.fire({
        title: '¡Seleccione una maquina!',
        icon: 'warning',
        confirmButtonColor: '#597504',
        confirmButtonText: 'OK'
    });
}

function leeUsuario(maquinaId){

}

function listaUser(turnos){
    let turnoMaquina = turnos
    $('#listarUsers').html('');
    turnoMaquina.forEach(turnoMaquina => {
        let fila = `<tr id ="${ turnoMaquina.id }">
                        <td >${ turnoMaquina.id }</td>
                        <td>${ turnoMaquina.id }</td>
                        <td>${ turnoMaquina.id }</td>
                        <td><button type="button" class="btn btn-primary" onclick="confirmarTurno(${ turnoMaquina.id })"><i class="text-white fa-solid fa-check"></i></button></td>
                    </tr>  `;
        $('#listarUsers').append(fila);

    })
}