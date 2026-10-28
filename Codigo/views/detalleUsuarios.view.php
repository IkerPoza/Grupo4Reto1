<?php 
    $titulo = 'Detalle Usuario';
    $css = 'detalleUsuario-estilo.css';
    $scripts = '';
    require __DIR__.'/layout/head.php';
?>

<main class="contenedor-principal">

  <!-- Botón Volver -->
  <div class="barra-volver">
    <a href="index.php?controller=ListarUsuariosController&accion=index">
        <svg width="30" height="19" viewBox="0 0 23 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.39156 0.54592C9.39176 0.432169 9.32391 0.321233 9.19752 0.228636C9.07113 0.136039 8.89252 0.0664156 8.68669 0.0295081C8.48085 -0.00739933 8.25809 -0.00974339 8.04958 0.022804C7.84107 0.0553514 7.65725 0.121161 7.52383 0.211024L0.219844 5.11992C0.077359 5.21566 0 5.33351 0 5.45481C0 5.57612 0.077359 5.69396 0.219844 5.78971L7.52383 10.6986C7.65725 10.7885 7.84107 10.8543 8.04958 10.8868C8.25809 10.9194 8.48085 10.917 8.68669 10.8801C8.89252 10.8432 9.07113 10.7736 9.19752 10.681C9.32391 10.5884 9.39176 10.4775 9.39156 10.3637V8.18743C15.0042 8.24852 17.7004 8.80541 19.0547 9.41084C20.3423 9.98627 20.5781 10.6631 20.8233 11.3722L20.887 11.5549C20.9338 11.6857 21.0704 11.803 21.2711 11.8847C21.4718 11.9665 21.7228 12.0071 21.9767 11.999C22.2307 11.9908 22.4702 11.9344 22.6502 11.8404C22.8302 11.7463 22.9382 11.6211 22.954 11.4884C23.1324 9.98954 22.8643 7.81763 20.839 6.00079C18.8732 4.23741 15.3339 2.88146 9.39156 2.73965V0.54592Z" fill="#5B6F8F"/>
        </svg>
    </a>
  </div>

  <section id="vistadetalleUsuario">
    <section class="formularioPerfil">

      <form id="formPerfil" action="index.php?controller=DetalleUsuarioController&accion=update" method="POST" enctype="multipart/form-data">

        <!-- Columna Izquierda: Imagen -->
        <div class="imagenEdicion">
          <img src="<?= htmlspecialchars(!empty($usuario->foto) ? $usuario->foto : 'views/imagenes/ImagenVacia.png') ?>" alt="Foto de perfil" class="fotoPerfil" id="vistaPreviaFoto">
          <p><b>Foto de perfil</b></p>
          <label for="foto" class="btn-subir-foto">Selecciona una imagen</label>
          <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
        </div>

        <!-- Columna Derecha: Campos del Formulario -->
        <div class="camposFormulario">

          <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario->nombre ?? '') ?>" placeholder="Nombre" maxlength="100" required>
          </div>

          <div class="campo">
            <label for="apellidos">Apellido</label>
            <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($usuario->apellidos ?? '') ?>" placeholder="Apellido" maxlength="100" required>
          </div>

          <div class="campo">
            <label for="area">Área de trabajo</label>
            <select id="area" name="area" required>
                <?php if (defined('AREAS')): ?>
                <?php foreach (AREAS as $clave => $texto): ?>
                    <option value="<?= htmlspecialchars($texto) ?>"
                    <?= isset($usuario->area) && $usuario->area === $texto ? 'selected' : '' ?>>
                    <?= htmlspecialchars($texto) ?>
                    </option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
            </div>

          <div class="campo">
            <label for="contacto">Correo electrónico</label>
            <input type="email" id="contacto" name="contacto" value="<?= htmlspecialchars($usuario->email ?? '') ?>" placeholder="Correo" maxlength="100" required>
          </div>

          <div class="campo">
            <label for="tipo">Tipo</label>
            <select id="tipo" name="tipo" required>
              <option value="admin" <?= isset($usuario->tipo) && $usuario->tipo === 'admin' ? 'selected' : '' ?>>Administrador</option>
              <option value="user" <?= isset($usuario->tipo) && $usuario->tipo === 'user' ? 'selected' : '' ?>>Usuario</option>
            </select>
          </div>

          <div class="botones">
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario->id) ?>">
            <button type="submit" class="botonGuardar">Guardar cambios</button>
            <button type="button" class="botonCancelar" onclick="window.history.back()">Cancelar</button>
          </div>

        </div>

      </form>
    </section>
  </section>

</main>

<?php require __DIR__.'/layout/footer.php'; ?>