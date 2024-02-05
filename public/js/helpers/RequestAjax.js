class RequestAjax{

    sendAjax(url, type, data, titulo, redirectTo){
        $.ajax({
            url: url,
            type: type,
            dataType: "JSON",
            data: data,
            success: function (e){
                this.successCallback(e,titulo, redirectTo);
            }.bind(this),
            error: this.errorCallback.bind(this)

        });
    }

    successCallback(e, title, redirectTo){
        Swal.fire({
            title: title,
            text: e.success,
            icon: 'success',
            confirmButtonColor: '#597504',
            confirmButtonText: 'OK'
        }).then((result) => {
        if (result.isConfirmed) {

            if(localStorage.getItem('rutas') != "[]" && localStorage.getItem('rutas') != null ){
                localStorage.setItem('rutas', "[]");
            }

            // redirect to
            if(redirectTo != null){
                window.location.href = redirectTo;
            }
        }
        });
    }

    errorCallback(jqXHR, textStatus, errorThrown) {

        var message = [];
        let errors = jqXHR.responseJSON.errors;
        if ( typeof jqXHR.responseJSON.errors == 'object') {
            for(var key in errors){
                if (errors.hasOwnProperty(key)) {
                    for (let i = 0; i < errors[key].length; i++) {
                        message.push(errors[key][i]);

                    }
                }
            }
        } else{
            message = jqXHR.responseJSON.errors;
        }

        Swal.fire({
            title: message,
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
