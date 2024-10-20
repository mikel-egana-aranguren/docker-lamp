function erakutsiFormularioaGehitu() {
    document.getElementById('modal-gehitu').style.display = 'block';
}
function itxiFormularioaGehitu() {
    document.getElementById('modal-gehitu').style.display = 'none';
}
function erakutsiFormularioaEditatu(index) {
    const bideojokoa = bideojokoak[index];
    document.getElementById('editatuIndex').value = index;
    document.getElementById('editatuTitulua').value = bideojokoa.titulua;
    document.getElementById('editatuEgilea').value=bidejokoa.egilea;
    document.getElementById('editatuPrezioa').value = bideojokoa.prezioa;
    document.getElementById('editatuMota').value = bideojokoa.mota;
    document.getElementById('editatuArgitaratzeData').value = bideojokoa.argitaratzeData;
    document.getElementById('modal-editatu').style.display = 'block';
}
function balioztatuFormularioa() {
    const titulua = document.getElementById('gehituTitulua').value;
    const prezioa = document.getElementById('gehituPrezioa').value;
    const mota = document.getElementById('gehituMota').value;
    const deskribapena = document.getElementById('gehituDeskribapena').value;
    const argitaratzeData = document.getElementById('gehituArgitaratzeData').value;
    if (titulua.trim() === "" || titulua.length > 100) {
        alert("Izenburua baliogabea da. 1 eta 100 karaktere artean izan behar ditu.");
        return false;
    }

    if (isNaN(prezioa) || parseFloat(prezioa) <= 0) {
        alert("Prezioa baliozko zenbakia izan behar da eta 0 baino handiagoa.");
        return false;
    }

    if (mota.trim() === "") {
        alert("Bideojokoaren mota ezin da hutsik egon.");
        return false;
    }

    const unekoUrtea = new Date().getFullYear();
    if (isNaN(argitaratzeData) || parseInt(argitaratzeData) < 1950 || parseInt(argitaratzeData) > unekoUrtea) {
        alert(`Argitaratze urtea baliozko zenbakia izan behar da, 1950 eta ${unekoUrtea} artean.`);
        return false;
    }

    return true;
}
function toggleDetalles(game_id) {
    var table = document.getElementById('detalles-' + game_id);
    if (table.style.display === 'none') {
        table.style.display = 'table';
    } else {
        table.style.display = 'none';
    }
}

function erakutsiFormularioaGehitu() {
    document.getElementById('modal-gehitu').style.display = 'block';
}

function itxiFormularioaGehitu() {
    document.getElementById('modal-gehitu').style.display = 'none';
}