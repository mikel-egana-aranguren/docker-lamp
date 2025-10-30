function validarNIF_JS(nif) {
    nif = nif.toUpperCase().trim();
    const nifRegex = /^[0-9]{8}-[A-Z]$/;
    if (!nifRegex.test(nif)) return false;
    
    let [numero, letra] = nif.split('-');
    const letrasValidas = "TRWAGMYFPDXBNJZSQVHLCKE";
    let letraCalculada = letrasValidas[parseInt(numero) % 23];
    return letra === letraCalculada;
}

function validateRegisterForm() {
    let nombre = document.getElementById('nombre').value;
    let nan = document.getElementById('nan').value;
    let tel = document.getElementById('tel').value;
    let fecha = document.getElementById('fecha').value;
    let email = document.getElementById('email').value;
    let pass = document.getElementById('pass').value;

    let nombreError = document.getElementById('nombre_error');
    let nanError = document.getElementById('nan_error');
    let telError = document.getElementById('tel_error');
    let fechaError = document.getElementById('fecha_error');
    let emailError = document.getElementById('email_error');
    let passError = document.getElementById('pass_error');

    nombreError.innerHTML = "";
    nanError.innerHTML = "";
    telError.innerHTML = "";
    fechaError.innerHTML = "";
    emailError.innerHTML = "";
    passError.innerHTML = "";

    let isValid = true;
    const nombreRegex = /^[a-zA-Z\s]+$/;
    if (!nombreRegex.test(nombre)) {
        nombreError.innerHTML = "Izenak letrak eta hutsuneak soilik izan ditzake.";
        isValid = false;
    }
    if (!validarNIF_JS(nan)) {
        nanError.innerHTML = "NANa ez da zuzena. Formatua: 11111111-Z eta letra zuzena.";
        isValid = false;
    }
    const telRegex = /^[0-9]{9}$/;
    if (!telRegex.test(tel)) {
        telError.innerHTML = "Telefonoak 9 zenbaki zehatz izan behar ditu.";
        isValid = false;
    }
    const fechaRegex = /^\d{4}-\d{2}-\d{2}$/;
    if (!fechaRegex.test(fecha)) {
        fechaError.innerHTML = "Datak uuuu-hh-ee formatua izan behar du.";
        isValid = false;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        emailError.innerHTML = "Email formatua ez da zuzena.";
        isValid = false;
    }
    if (pass.length < 6) {
        passError.innerHTML = "Pasahitzak gutxienez 6 karaktere izan behar ditu.";
        isValid = false;
    }
    return isValid;
}

function validateModifyForm() {
    let nombre = document.getElementById('nombre').value;
    let nan = document.getElementById('nan').value;
    let tel = document.getElementById('tel').value;
    let fecha = document.getElementById('fecha').value;
    let email = document.getElementById('email').value;
    let pass = document.getElementById('pass_new').value;
    let passRep = document.getElementById('pass_rep').value;

    let nombreError = document.getElementById('nombre_error');
    let nanError = document.getElementById('nan_error');
    let telError = document.getElementById('tel_error');
    let fechaError = document.getElementById('fecha_error');
    let emailError = document.getElementById('email_error');
    let passError = document.getElementById('pass_error');
    let passRepError = document.getElementById('pass_rep_error');

    nombreError.innerHTML = "";
    nanError.innerHTML = "";
    telError.innerHTML = "";
    fechaError.innerHTML = "";
    emailError.innerHTML = "";
    passError.innerHTML = "";
    passRepError.innerHTML = "";

    let isValid = true;
    const nombreRegex = /^[a-zA-Z\s]+$/;
    if (!nombreRegex.test(nombre)) {
        nombreError.innerHTML = "Izenak letrak eta hutsuneak soilik izan ditzake.";
        isValid = false;
    }
    if (!validarNIF_JS(nan)) {
        nanError.innerHTML = "NANa ez da zuzena. Formatua: 11111111-Z eta letra zuzena.";
        isValid = false;
    }
    const telRegex = /^[0-9]{9}$/;
    if (!telRegex.test(tel)) {
        telError.innerHTML = "Telefonoak 9 zenbaki zehatz izan behar ditu.";
        isValid = false;
    }
    const fechaRegex = /^\d{4}-\d{2}-\d{2}$/;
    if (!fechaRegex.test(fecha)) {
        fechaError.innerHTML = "Datak uuuu-hh-ee formatua izan behar du.";
        isValid = false;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        emailError.innerHTML = "Email formatua ez da zuzena.";
        isValid = false;
    }
    if (pass.length > 0) {
        if (pass.length < 6) {
            passError.innerHTML = "Pasahitz berriak gutxienez 6 karaktere izan behar ditu.";
            isValid = false;
        }
        if (pass !== passRep) {
            passRepError.innerHTML = "Pasahitzak ez datoz bat.";
            isValid = false;
        }
    }
    return isValid;
}

function validateLoginForm() {
    let email = document.getElementById('email').value;
    let pass = document.getElementById('pass').value;

    let emailError = document.getElementById('email_error');
    let passError = document.getElementById('pass_error');

    emailError.innerHTML = "";
    passError.innerHTML = "";

    let isValid = true;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        emailError.innerHTML = "Email formatua ez da zuzena.";
        isValid = false;
    }
    if (pass.length === 0) {
        passError.innerHTML = "Pasahitza ezin da hutsik egon.";
        isValid = false;
    }
    return isValid;
}

function validateItemForm() {
    let izena = document.getElementById('izena').value;
    let prezioa = document.getElementById('prezioa').value;
    let stocka = document.getElementById('stocka').value;
    let portadaInput = document.getElementById('portada'); 

    let izenaError = document.getElementById('izena_error');
    let prezioaError = document.getElementById('prezioa_error');
    let stockaError = document.getElementById('stocka_error');
    let portadaError = document.getElementById('portada_error');

    izenaError.innerHTML = "";
    prezioaError.innerHTML = "";
    stockaError.innerHTML = "";
    if (portadaError) portadaError.innerHTML = ""; 

    let isValid = true;

    if (izena.trim().length === 0) {
        izenaError.innerHTML = "Izenburua ezin da hutsik egon.";
        isValid = false;
    }
    const prezioaRegex = /^\d+(\.\d{1,2})?$/;
    if (!prezioaRegex.test(prezioa)) {
        prezioaError.innerHTML = "Prezioa ez da zuzena (Adib. 25.50).";
        isValid = false;
    }
    const stockaRegex = /^\d+$/;
    if (!stockaRegex.test(stocka)) {
        stockaError.innerHTML = "Stocka zenbaki osoa izan behar da (Adib. 10).";
        isValid = false;
    }

    if (portadaInput && portadaInput.files.length > 0) {
        let fileName = portadaInput.files[0].name;
        let fileSize = portadaInput.files[0].size; 
        let allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
        
        if (!allowedExtensions.exec(fileName)) {
            portadaError.innerHTML = "Fitxategi mota ez da onartzen (JPG edo PNG soilik).";
            isValid = false;
        }
        if (fileSize > 2 * 1024 * 1024) { 
            portadaError.innerHTML = "Fitxategia handiegia da (gehienez 2MB).";
            isValid = false;
        }
    }
    return isValid; 
}