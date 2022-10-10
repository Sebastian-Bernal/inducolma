usuarioMaquina=[];
let inicial = 0
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

// listado de usuarios auxiliares
function listaUser(turnos){
    if (inicial == 0){
        leerName()
        inicial = 1
    }
        
    let turnoMaquina = turnos
    $('#listarUsers').html('');
    turnoMaquina.forEach(turnoMaquina => {
        let nombreUser = usuarioMaquina.filter(usuarioMaquina => usuarioMaquina.identi == turnoMaquina.user_id)
        let fila = `<tr id ="${ turnoMaquina.id }">
                        <td style="display:none">${ turnoMaquina.id }</td>
                        <td >${ nombreUser[0].nombre }</td>
                        <td style="display:none">${ turnoMaquina.user_id }</td>
                        <td><button type="button" class="btn btn-primary" onclick="confirmarTurno(${ turnoMaquina.id }, ${ turnoMaquina.user_id })"><i class="text-white fa-solid fa-check"></i></button>
                        <button type="button" class="btn btn-danger" onclick="eliminarTurno(${ turnoMaquina.id }, ${ turnoMaquina.user_id })"><i class="text-white fas fa-trash"></i></button></td>
                        </tr>  `;
        $('#listarUsers').append(fila);

    })
}

//injerto para leer y guardar en array objetos traidos del documento
function leerName(){
    let campos = $('#nombres').find('input');
    $.each(campos, function (index, value) {
    
    let identi = value.id
    let nombre = document.getElementById(identi).value
    
 
       registroUsuario = Object.assign({}, { nombre, identi });
       usuarioMaquina.unshift(registroUsuario);
    })
}

function confirmarTurno(turnoUsuario, usuarioId){
    console.log(turnoUsuario, usuarioId)
    alert("se envian los datos")
}

function eliminarTurno(turnoUsuario, usuarioId){
    console.log(turnoUsuario, usuarioId)
    Swal.fire({
        title: '¡Esta seguro de eliminar el usuario asignado!',
        icon: 'warning',
        confirmButtonColor: '#597504',
        confirmButtonText: 'Si',
        showDenyButton: true,
        denyButtonText: 'No'

    }).then((result) => {
        if (result.isConfirmed) {
            alert("se cambia los valores de auxiliares")
        }
    })        
}

function cambiarUsuario(){
    Swal.fire({
        title: '¡Esta seguro de asignar nuevos usuarios!',
        icon: 'warning',
        confirmButtonColor: '#597504',
        confirmButtonText: 'Si',
        showDenyButton: true,
        denyButtonText: 'No'

    }).then((result) => {
        if (result.isConfirmed) {
            alert("se cambia los valores de auxiliares")
        }
    }) 
}