document.addEventListener('DOMContentLoaded', () => {
    const temas = document.querySelectorAll('.filtrarTema .tema');
    const filas = document.querySelectorAll('.tablaDocumentos .filaDocumento:not(.cabeceraTabla)');
    const mensajeVacio = document.querySelector('.mensajeVacio');


    function filtrarDocumentos(categoria) {
        let visibles = 0;

        filas.forEach(fila => {
            const categoriaDocumento = fila
                .querySelector('.etiquetaCategoria')
                ?.textContent.trim();

            const coincide =
                categoria === 'todas' ||
                categoriaDocumento === categoria;

            fila.hidden = !coincide;

            if (coincide) {
                visibles++;
            }
        });

        if (mensajeVacio) {
            mensajeVacio.hidden = visibles > 0;

            if (visibles === 0) {
                mensajeVacio.textContent = 'No hay documentos en esta categoría.';
            }
        }
    }


    temas.forEach(tema => {
        tema.setAttribute('role', 'button');
        tema.setAttribute('tabindex', '0');

        function seleccionarTema() {
            const categoria = tema.dataset.id;

            temas.forEach(elemento => {
                elemento.removeAttribute('id');
            });

            tema.id = 'seleccionado';

            filtrarDocumentos(categoria);
        }

        tema.addEventListener('click', seleccionarTema);

        tema.addEventListener('keydown', evento => {
            if (evento.key === 'Enter' || evento.key === ' ') {
                evento.preventDefault();
                seleccionarTema();
            }
        });
    });


    const temaSeleccionado = document.querySelector('.filtrarTema .tema#seleccionado');

    filtrarDocumentos(temaSeleccionado?.dataset.id || 'todas');


    document.querySelectorAll('form.formEliminarDocumento')
        .forEach(formulario => {
            formulario.addEventListener('submit', evento => {
                const confirmar = window.confirm('¿Seguro que quieres eliminar este documento?');

                if (!confirmar) {
                    evento.preventDefault();
                }
            });
        });

    const formularioSubida = document.querySelector('#formularioSubidaDocumento');
    const botonAbrir = document.querySelector('#abrirFormularioDocumento');
    const botonCancelar = document.querySelector('#cancelarSubidaDocumento');

    function abrirFormulario() {
        formularioSubida.hidden = false;
        botonAbrir.setAttribute('aria-expanded', 'true');
        formularioSubida.querySelector('#tituloDocumento').focus();
    }

    function cerrarFormulario() {
        formularioSubida.hidden = true;
        formularioSubida.reset();
        botonAbrir.setAttribute('aria-expanded', 'false');
    }

    if (formularioSubida && botonAbrir) {
        botonAbrir.addEventListener('click', () => {
            if (formularioSubida.hidden) {
                abrirFormulario();
            } else {
                cerrarFormulario();
            }
        });

        botonCancelar?.addEventListener('click', cerrarFormulario);

        document.addEventListener('keydown', evento => {
            if (evento.key === 'Escape' && !formularioSubida.hidden) {
                cerrarFormulario();
                botonAbrir.focus();
            }
        });

        document.addEventListener('click', evento => {
            if (
                !formularioSubida.hidden &&
                !formularioSubida.contains(evento.target) &&
                !botonAbrir.contains(evento.target)
            ) {
                cerrarFormulario();
            }
        });
    }

    if (formularioSubida) {
        formularioSubida.addEventListener('submit', evento => {
            const campoArchivo = formularioSubida.querySelector('input[type="file"]');

            const archivo = campoArchivo?.files?.[0];

            if (!archivo) {
                evento.preventDefault();
                alert('Selecciona un archivo para subir.');
                return;
            }

            const maximo = 10 * 1024 * 1024;

            if (archivo.size > maximo) {
                evento.preventDefault();
                alert('El archivo no puede superar los 10 MB.');
                return;
            }

            formularioSubida
                .querySelector('.btnConfirmarDocumento')
                .disabled = true;
        });
    }
});
