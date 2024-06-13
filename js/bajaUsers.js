document.getElementById('registrationForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch('deactivateUser.php', { // Endpoint for user deactivation
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
            text: data.message,
            customClass: {
                confirmButton: 'btn-custom'
            }
        });
        }
    })
    .catch(error => {
        alert('Error al enviar el formulario.');
    });
});