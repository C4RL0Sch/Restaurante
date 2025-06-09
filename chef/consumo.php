<?php
include("../usuario.php");
include("../conexion.php");
session_start();
$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario || $usuario->rol !== 'chef') {
  header('Location: ../');
  exit();
}

$id = $usuario->id;

$historial = mysqli_query($conexion, "
  SELECT c.idConsumo, c.Fecha 
  FROM consumo c
  WHERE c.idUsuario = {$usuario->id}
  ORDER BY c.idConsumo DESC
");
?>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>🍳 Registrar Consumo</h4>
    <button id="btnNuevoConsumo" class="btn btn-primary">➕ Registrar Consumo</button>
  </div>

  <table class="table table-striped">
    <thead class="table-dark">
      <tr><th>#</th><th>Fecha</th><th>Acciones</th></tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($historial)): ?>
        <tr>
          <td><?= $row['idConsumo'] ?></td>
          <td><?= $row['Fecha'] ?></td>
          <td>
            <button class="btn btn-sm btn-primary btn-ver-consumo" data-id="<?= $row['idConsumo'] ?>">
              🔍 Ver Detalle
            </button>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<div class="modal fade" id="modalConsumo" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formConsumo" class="needs-validation" novalidate>
        <div class="modal-header">
          <h5 class="modal-title">Registrar Uso de Ingredientes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="consumo-container"></div>
          <button type="button" id="btnAgregarConsumo" class="btn btn-outline-secondary mt-2">
            ➕ Agregar Ingrediente
          </button>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar Consumo</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="modalVerConsumo" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle de Consumo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="detalleConsumo"></div>
    </div>
  </div>
</div>

<script src="js/consumo.js"></script>
