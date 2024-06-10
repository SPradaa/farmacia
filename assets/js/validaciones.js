document.getElementById('form1').addEventListener('submit', function(event) {
    let valid = true;

    // Documento validation
    const documento = document.getElementById('documento');
    const errorDocumento = document.getElementById('errorDocumento');
    if (documento.value.length < 8 || documento.value.length > 11) {
        documento.classList.add('invalid');
        errorDocumento.style.display = 'inline';
        valid = false;
    } else {
        documento.classList.remove('invalid');
        errorDocumento.style.display = 'none';
    }

    // Nombre validation
    const nombre = document.getElementById('nombre');
    const errorNombre = document.getElementById('errorNombre');
    if (nombre.value.length < 3 || /[^a-zA-ZÑ-ñ´ ]/.test(nombre.value)) {
        nombre.classList.add('invalid');
        errorNombre.style.display = 'inline';
        valid = false;
    } else {
        nombre.classList.remove('invalid');
        errorNombre.style.display = 'none';
    }

    // Apellido validation
    const apellido = document.getElementById('apellido');
    const errorApellido = document.getElementById('errorApellido');
    if (apellido.value.length < 4 || /[^a-zA-ZÑ-ñ´ ]/.test(apellido.value)) {
        apellido.classList.add('invalid');
        errorApellido.style.display = 'inline';
        valid = false;
    } else {
        apellido.classList.remove('invalid');
        errorApellido.style.display = 'none';
    }

    // Telefono validation
    const telefono = document.getElementById('telefono');
    const errorTelefono = document.getElementById('errorTelefono');
    if (!/^[0-9]{10}$/.test(telefono.value)) {
        telefono.classList.add('invalid');
        errorTelefono.style.display = 'inline';
        valid = false;
    } else {
        telefono.classList.remove('invalid');
        errorTelefono.style.display = 'none';
    }

    // Correo validation
    const correo = document.getElementById('correo');
    const errorCorreo = document.getElementById('errorCorreo');
    if (correo.value.length < 8 || !/[@]/.test(correo.value)) {
        correo.classList.add('invalid');
        errorCorreo.style.display = 'inline';
        valid = false;
    } else {
        correo.classList.remove('invalid');
        errorCorreo.style.display = 'none';
    }

    // Prevent form submission if any field is invalid
    if (!valid) {
        event.preventDefault();
    }
});
