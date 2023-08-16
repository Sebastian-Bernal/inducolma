/**
 * muestra mensaje de error simple
 * @param {String} title
 * @param {String} icon
 */
function alertaErrorSimple(title, icon){
    Swal.fire({
        title: title,
        icon: icon,
        confirmButtonColor: '#597504',
        confirmButtonText: 'OK'
    })
}
