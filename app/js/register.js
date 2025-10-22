document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById("register_form");
  const nameInput = document.getElementById("name");
  const surnamesInput = document.getElementById("surnames");
  const dniInput = document.getElementById("dni");
  const emailInput = document.getElementById("email");
  const tlfnInput = document.getElementById("tlfn");
  const fechaInput = document.getElementById("fNcto");
  const passwdInput = document.getElementById("passwd");
  const passwdRepeatInput = document.getElementById("passwd_repeat");

  const esFechaValida = (fecha) => {
    const [año, mes, dia] = fecha.split('-').map(Number);
    const date = new Date(año, mes - 1, dia);
    return (
      date.getFullYear() === año &&
      date.getMonth() === mes - 1 &&
      date.getDate() === dia
    );
  };

  form.addEventListener("submit", function (event) {
    const name = nameInput.value.trim();
    const surnames = surnamesInput.value.trim();
    const dni = dniInput.value.trim();
    const email = emailInput.value.trim();
    const tlfn = tlfnInput.value.trim();
    const fecha = fechaInput.value.trim();
    const passwd = passwdInput.value.trim();
    const passwdRepeat = passwdRepeatInput.value.trim();

    const nameRegex = /^[A-Za-z]+$/;
    const surnamesRegex = /^[A-Za-zÀ-ÿ ]+$/;
    const dniRegex = /^(\d{8})[-\s]?([A-Za-z])$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const tlfnRegex = /^[679]\d{8}$/;
    const fechaRegex = /^(19|20)\d{2}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/;

    if (!nameRegex.test(name)) { alert("Nombre inválido."); event.preventDefault(); return; }
    if (!surnamesRegex.test(surnames)) { alert("Apellidos inválidos."); event.preventDefault(); return; }

    const dniMatch = dni.match(dniRegex);
    if (!dniMatch) { alert("DNI inválido (formato). Debe ser 8 números y una letra."); event.preventDefault(); return; }

    const dniNumbers = parseInt(dniMatch[1], 10);
    const dniLetter = dniMatch[2].toUpperCase();
    const letters = "TRWAGMYFPDXBNJZSQVHLCKE";
    const correctLetter = letters[dniNumbers % 23];

    if (dniLetter !== correctLetter) { alert("DNI no válido."); event.preventDefault(); return; }

    if (!fechaRegex.test(fecha) || !esFechaValida(fecha)) { alert("Fecha inválida."); event.preventDefault(); return; }
    if (!emailRegex.test(email)) { alert("Correo inválido."); event.preventDefault(); return; }
    if (!tlfnRegex.test(tlfn)) { alert("Teléfono inválido."); event.preventDefault(); return; }
    if (passwd !== passwdRepeat) { alert("Contraseñas no coinciden."); event.preventDefault(); return; }
  });
});

