document.getElementById('registrationForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch('sendEmail.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Limpiar campos del formulario
            document.getElementById('registrationForm').reset();
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            Swal.fire({
              icon: 'error',
              title: '¡Error :(!',
              text: data.message
          });
        }
    })
    .catch(error => {
        Swal.fire({
              icon: 'error',
              title: '¡Error :(!',
              text: 'Error al enviar el formulario :,('
          });
    });
}); 