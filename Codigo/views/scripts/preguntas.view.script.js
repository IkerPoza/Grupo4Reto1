/*Coger Datos*/
const svgFavoritos = document.getElementsByClassName("botonFavorito");

Array.from(svgFavoritos).forEach((boton) => {
    boton.addEventListener('click', Favoritos);
});

async function Favoritos(){
    if(this.getAttribute('fill') == 'none'){
        let response = await fetch("index.php?accion=anadirFavoritos", {
        method: "POST",
        headers: {
        "Content-Type": "application/json"
        },
        body: JSON.stringify({ pregunta_id : this.dataset.id})
        });

        if (response.ok) {
            this.setAttribute(
                "fill", this.getAttribute("fill") === "none" ? "#FFDD33" : "none"
            );
        }else{
            alert("Problemas con el servidor. Intentalo más tarde.");
        }
    }else{
        let response = await fetch("index.php?accion=eliminarFavoritos", {
        method: "POST",
        headers: {
        "Content-Type": "application/json"
        },
        body: JSON.stringify({ pregunta_id : this.dataset.id})
        });

        if (response.ok) {
            this.setAttribute(
                "fill", this.getAttribute("fill") === "none" ? "#FFDD33" : "none"
            );
        }else{
            alert("Problemas con el servidor. Intentalo más tarde.");
        }
    }
}