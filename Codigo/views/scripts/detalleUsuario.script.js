document.addEventListener('DOMContentLoaded', () => {
    const inputFoto = document.getElementById('foto');
    const vistaPrevia = document.getElementById('vistaPreviaFoto');

    if (!inputFoto || !vistaPrevia) return;

    inputFoto.addEventListener('change', () => {
        const archivo = inputFoto.files[0];
        if (!archivo) return;

        if (archivo.size > 2 * 1024 * 1024) {
            alert('La imagen no puede superar los 2 MB.');
            inputFoto.value = '';
            return;
        }

        vistaPrevia.src = URL.createObjectURL(archivo);
    });
});
