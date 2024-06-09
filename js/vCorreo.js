document.getElementById('registrationForm').addEventListener('submit', function(event) {
    var email = document.getElementById('email').value;
    var confirmEmail = document.getElementById('confirmEmail').value;
    if (email !== confirmEmail) {
        alert('Los correos electrónicos no coinciden.');
        event.preventDefault();
    }
});