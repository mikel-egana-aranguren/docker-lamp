//Obtener lista de botones para ver informacion (los que pertenezcan a la clase ".btn-ver-...")
const btnVerInfoPeli = document.querySelectorAll(".btn-ver-info-peli");
//Obtener lista de botones para cerrar informacion (los que pertenezcan a la clase ".btn-cerrar-...")
const btnCerrarInfoPeli = document.querySelectorAll(".btn-cerrar-info-peli");
//Obtener lista de bloques de informacion que se muestran
const infoPeli = document.querySelectorAll(".info-peli");

//Para cada boton de ver info, agregarle un evento en el que si haces click, te muestra la info correspondiente
btnVerInfoPeli.forEach((btn, i) =>
{ 
    btn.addEventListener("click",()=>
    {
        infoPeli[i].showModal();
    })
});

//Para cada boton de cerrar info, agregarle un evento en el que si haces click, esconde la info correspondiente
btnCerrarInfoPeli.forEach((btn, i)=>
{
    btn.addEventListener("click", ()=>
    {
        infoPeli[i].close();
    })
});