document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById("register_form");
  const nameInput = document.getElementById("name");
  const surnamesInput = document.getElementById("surnames");
  const dniInput = document.getElementById("dni");
  const emailInput = document.getElementById("email");
  const tlfnInput = document.getElementById("tlfn");
  const passwdInput = document.getElementById("passwd");
  const passwdRepeatInput = document.getElementById("passwd_repeat");

  form.addEventListener("submit", function (event) {
    const name = nameInput.value.trim();
    const surnames = surnamesInput.value.trim();
    const dni = dniInput.value.trim();
    const email = emailInput.value.trim();
    const tlfn = tlfnInput.value.trim();
    const passwd = passwdInput.value.trim();
    const passwdRepeat = passwdRepeatInput.value.trim();

    const nameRegex = /^[A-Za-z]+$/;
    const surnamesRegex = /^[A-Za-zÀ-ÿ ]+$/;
    const dniRegex = /^[0-9]{8}[-\s]?[A-Z]$/i;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const tlfnRegex = /^[679]\d{8}$/;

    if (!nameRegex.test(name)) { alert("Nombre inválido."); event.preventDefault(); return; }
    if (!surnamesRegex.test(surnames)) { alert("Apellidos inválidos."); event.preventDefault(); return; }
    if (!dniRegex.test(dni)) { alert("DNI inválido."); event.preventDefault(); return; }
    if (!emailRegex.test(email)) { alert("Correo inválido."); event.preventDefault(); return; }
    if (!tlfnRegex.test(tlfn)) { alert("Teléfono inválido."); event.preventDefault(); return; }
    if (passwd !== passwdRepeat) { alert("Contraseñas no coinciden."); event.preventDefault(); return; }
    // Si pasa todo, no hacemos preventDefault -> formulario se envía
  });
});
