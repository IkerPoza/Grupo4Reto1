<?php 
    $titulo = 'Registro';
    $css = 'listarUsuarios-estilo.css';
    $scripts = '';
    require __DIR__.'/layout/head.php';
    require __DIR__ . '/layout/nav.php';
?>
</nav>
<main class="contenedor-principal">

  <div class="seccion-busqueda">
  <form id="formBusqueda" class="caja-busqueda" action="index.php" method="GET">
    <input type="hidden" name="controller" value="ListarUsuariosController">
    <input type="hidden" name="accion" value="index">
    <input type="text" name="busqueda"
           value="<?= htmlspecialchars($busqueda ?? '') ?>"
           placeholder="Buscar usuarios por nombre, apellido o correo..." />
    <button type="submit" aria-label="Buscar">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"></circle>
        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
      </svg>
    </button>
  </form>
</div>

  
  <div class="barra-controles">
      <h2>Mostrando usuarios</h2>
    
    <div class="control-derecho">
      <label for="ordenar">Ordenar por:</label>
      <select id="ordenar" name="orden" form="formBusqueda" onchange="this.form.submit()">
        <option value="asc"  <?= ($orden ?? 'asc') === 'asc'  ? 'selected' : '' ?>>Ascendente</option>
        <option value="desc" <?= ($orden ?? 'asc') === 'desc' ? 'selected' : '' ?>>Descendente</option>
      </select>
    </div>

      </div>    



<div class="lista-usuarios">
    <?php if (!empty($usuarios)): ?>
      <?php foreach ($usuarios as $usuario): ?>
        
        <div class="tarjeta-usuario" onclick="window.location.href='index.php?controller=ListarUsuariosController&accion=detalle&id=<?= $usuario->id ?>'">
          
          <div class="usuario-info">
            <div class="imagenPerfilLista">
                <img src="<?= (!empty($usuario->foto) && file_exists($usuario->foto)) ? $usuario->foto : 'views/imagenes/Perfil.svg' ?>" alt="Foto de perfil" class="fotoPerfilLista">
            </div>
            <span class="nombre-usuario">
              <?= htmlspecialchars(trim(($usuario->nombre ?? '') . ' ' . ($usuario->apellidos ?? ''))) ?>
            </span>
          </div>

          <a href="index.php?controller=ListarUsuariosController&accion=eliminar&id=<?= $usuario->id ?>" 
             class="btn-borrar" 
             title="Eliminar usuario"
             onclick="event.stopPropagation(); return confirm('¿Estás seguro de eliminar este usuario?');">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
            </svg>
          </a>

        </div>

      <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align: center; color: #666;">No se encontraron usuarios.</p>
    <?php endif; ?>
  </div>
  
</main>

<?php require __DIR__.'/layout/footer.php'; ?>