function mostrarAlerta() {
    Swal.fire({
        position: 'bottom-end',
        icon: 'info',
        title: 'Aviso',
        text: 'El alumno aún no ha subido su trabajo terminal.',
        showConfirmButton: false,
        timer: 5000,  // 5000 milisegundos = 5 segundos
        timerProgressBar: true,  // Mostrar la barra de progreso
    });
}