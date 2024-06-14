function agregarUrl() {
    Swal.fire({
        title: 'Añadir URL del Documento',
        input: 'url',
        inputLabel: 'Introduce la URL del documento',
        inputPlaceholder: 'https://ejemplo.com/documento.docx',
        showCancelButton: true,
        confirmButtonText: 'Añadir',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'Necesitas escribir una URL!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "actualizar_url.php",
                data: { url_documento: result.value },
                success: function(response) {
                    if (response === "success") {
                        Swal.fire(
                            'Éxito',
                            'La URL del documento ha sido añadida.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error',
                            'Hubo un problema al añadir la URL. Inténtalo de nuevo.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error',
                        'Hubo un problema al comunicar con el servidor. Inténtalo de nuevo.',
                        'error'
                    );
                }
            });
        }
    });
}