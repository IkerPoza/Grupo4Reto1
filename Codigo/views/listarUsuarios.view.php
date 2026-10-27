<?php 
    $titulo = 'Registro';
    $css = 'listarUsuarios-estilo.css';
    $scripts = '';
    require __DIR__.'/layout/head.php';
?>

<main class="contenedor-principal">

  <div class="seccion-busqueda">
    <form class="caja-busqueda" action="index.php" method="POST">
      <input type="text" name="busqueda" placeholder="Buscar preguntas, temas o normativas..." />
      <button type="submit" aria-label="Buscar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
      </button>
    </form>
  </div>

  
  <div class="barra-controles">
    <div class="control-izquierdo">
      <a href="javascript:history.back()" class="btn-volver" title="Volver">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#4a607a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 14 4 9 9 4"></polyline>
          <path d="M20 20v-7a4 4 0 0 0-4-4H4"></path>
        </svg>
      </a>
      <h2>Mostrando usuarios</h2>
    </div>

    <div class="control-derecho">
      <label for="ordenar">Ordenar por:</label>
      <select id="ordenar" name="ordenar">
        <option value="asc">Ascendente</option>
        <option value="desc">Descendente</option>
      </select>
    </div>
  </div>




  <div class="lista-usuarios">
  <?php if (!empty($usuarios)): ?>
    <?php foreach ($usuarios as $usuario): ?>
      <div class="tarjeta-usuario">
        
        <div class="usuario-info">
          <div class="icono-avatar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <circle cx="12" cy="12" r="10"></circle>
              <circle cx="12" cy="10" r="3"></circle>
              <path d="M6.168 18.849A4 4 0 0 1 10 16h4a4 4 0 0 1 3.832 2.849"></path>
            </svg>
          </div>
          <span class="nombre-usuario">
            <?= htmlspecialchars(trim(($usuario->nombre ?? '') . ' ' . ($usuario->apellidos ?? ''))) ?>
          </span>
        </div>

        <a href="index.php?controller=ListarUsuariosController&accion=eliminar&id=<?= $usuario->id ?>" 
           class="btn-borrar" 
           title="Eliminar usuario"
           onclick="return confirm('¿Estás seguro de eliminar este usuario?');">
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