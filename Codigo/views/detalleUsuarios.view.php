<?php 
    $titulo = 'Detalle Usuario';
    $css = 'detalleUsuario-estilo.css';
    $scripts = '';
    require __DIR__.'/layout/head.php';
?>


<main class="contenedor-principal">

  
  <div class="barra-volver">
    <a href="javascript:history.back()" class="btn-volver" title="Volver">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#4a607a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 14 4 9 9 4"></polyline>
        <path d="M20 20v-7a4 4 0 0 0-4-4H4"></path>
      </svg>
    </a>
  </div>

  <section id="vistadetalleUsuario">
    <section class="formularioPerfil">

      <form id="formPerfil" action="index.php?controller=DetalleUsuarioController&accion=update" method="POST" enctype="multipart/form-data">

        
        <div class="imagenEdicion">
          <img src="<?= htmlspecialchars(!empty($usuario->foto) ? $usuario->foto : 'views/imagenes/ImagenVacia.png') ?>" alt="Foto de perfil" class="fotoPerfil" id="vistaPreviaFoto">
          <p><b>Foto de perfil</b></p>
          <label for="foto" class="btn-subir-foto">Selecciona una imagen</label>
          <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
        </div>

        
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