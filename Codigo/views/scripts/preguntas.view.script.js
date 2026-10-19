/*Coger Datos*/
const svgFavoritos = document.getElementsByClassName("botonFavorito");

Array.from(svgFavoritos).forEach((boton) => {
    boton.addEventListener('click', anadirFavoritos);
});

function anadirFavoritos(){

    if(this.getAttribute('fill') == "none"){
        this.setAttribute('fill', '#FFDD33');
    } else {
        this.setAttribute('fill', 'none');
    }
}