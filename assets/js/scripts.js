// scripts.js
// Función para agregar efectos de escala a los botones
function agregarAnimacionBotones() {
    const buttons = document.querySelectorAll('.btn-primary, .btn-outline-primary');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', () => {
            button.style.transform = 'scale(1.1)';
        });
        button.addEventListener('mouseleave', () => {
            button.style.transform = 'scale(1)';
        });
    });
}

// Validación del formulario de inicio de sesión con retroalimentación visual
function validarFormularioLogin() {
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    let isValid = true;

    // Validar el correo electrónico (debe contener "@")
    if (!email.value.includes("@")) {
        document.getElementById("emailError").classList.remove("d-none");
        isValid = false;
    } else {
        document.getElementById("emailError").classList.add("d-none");
    }

    // Validar la longitud de la contraseña (mínimo 5 caracteres)
    if (password.value.length < 5) {
        document.getElementById("passwordError").classList.remove("d-none");
        isValid = false;
    } else {
        document.getElementById("passwordError").classList.add("d-none");
    }

    return isValid;
}

// Inicializar los eventos una vez que el DOM está cargado
document.addEventListener("DOMContentLoaded", function() {
    // Aplicar la animación a los botones
    agregarAnimacionBotones();

    // Validación del formulario de inicio de sesión
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function(event) {
            if (!validarFormularioLogin()) {
                event.preventDefault(); // Prevenir el envío si hay errores de validación
            }
        });
    }

    // Mostrar/ocultar mensaje de error para cualquier otro formulario si existe
    const mensajeError = document.querySelector('.alert-danger, .alert-warning');
    if (mensajeError) {
        setTimeout(() => {
            mensajeError.style.opacity = '0';
        }, 5000); // Ocultar automáticamente el mensaje después de 5 segundos
    }
});
