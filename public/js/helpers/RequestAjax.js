class RequestAjax{

    sendAjax(url, type, data, titulo){
        $.ajax({
            url: url,
            type: type,
            dataType: "JSON",
            data: data,
            success: function (e){
                this.successCallback(e,titulo);
            }.bind(this),
            error: this.errorCallback.bind(this)

        });
    }

    successCallback(e, title){
        Swal.fire({
            title: title,
            text: e.success,
            icon: 'success',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        }).then((result) => {
        if (result.isConfirmed) {
            location.reload();
        }
        });
    }

    errorCallback(jqXHR, textStatus, errorThrown) {
        Swal.fire({
            title: jqXHR.responseJSON.errors,
            text: errorThrown,
            icon: 'error',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        });
    }

    // Método estático para obtener la instancia única de la clase
    static getInstance() {
        if (!RequestAjax.instancia) {
        RequestAjax.instancia = new RequestAjax();
        }
        return RequestAjax.instancia;
    }
}
