/*Coger Datos*/
const svgFavoritos = document.getElementsByClassName("botonFavorito");

const sectionTema = document.getElementsByClassName("tema");

Array.from(svgFavoritos).forEach((boton) => {
    boton.addEventListener('click', Favoritos);
});

Array.from(sectionTema).forEach((boton) => {
    boton.addEventListener('click', temaSeleccionado);
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

async function temaSeleccionado() {
    if(this.id != "seleccionado"){
        let response = await fetch("index.php?accion=buscarPorTema", {
        method: "POST",
        headers: {
        "Content-Type": "application/json"
        },
        body: JSON.stringify({ tema : this.dataset.id})
        });

        if(response.ok){
            window.location.href = "index.php";
        }else{
            alert("Problemas con el servidor. Intentalo más tarde.");
        }
    }
}