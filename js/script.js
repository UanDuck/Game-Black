function validarFormulario() {
    const email = document.getElementById("email").value;
    const contraseña = document.getElementById("contraseña").value;
    const confirmarContraseña = document.getElementById("confirmar_contraseña").value;

    const regexEmail = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
    if (!regexEmail.test(email)) {
        alert("Por favor, ingrese un correo electrónico válido (el ingresado tiene caracteres invalidos o está mal escrito).");
        return false;
    }

    if (contraseña !== confirmarContraseña) {
        alert("Las contraseñas no coinciden. Por favor, inténtelo de nuevo.");
        return false;
    }

    return true; // Permite el envío del formulario si todas las validaciones pasan.
}