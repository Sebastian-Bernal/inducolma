

class AlertSimpleRequestManager{

    showAlertSimpleRequest(principalTitle, confirmButtonText, url, tipo, datos, titulo){
        return Swal.fire({
            title: principalTitle,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#597504',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                var requestRestoreProveedor = RequestAjax.getInstance();
                requestRestoreProveedor.sendAjax(url, tipo, datos, titulo);
            }
        })
    }

    // Método estático para obtener la instancia única de la clase
    static getInstance() {
        if (!AlertSimpleRequestManager.instancia) {
            AlertSimpleRequestManager.instancia = new AlertSimpleRequestManager();
        }
        return AlertSimpleRequestManager.instancia;
    }
}
