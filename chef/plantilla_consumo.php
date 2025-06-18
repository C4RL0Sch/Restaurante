<?php
include("../conexion.php");
$ingredientes = mysqli_query($conexion, "SELECT idIngrediente, Nombre, Cantidad FROM ingredientes");
ob_start();
?>
<div class="row mb-2 grupo-consumo">
  <div class="col-md-6">
    <select class="form-select select-consumo" name="ingredientes[]" required>
      <option value="" disabled selected>Seleccione ingrediente</option>
      <?php while($ing = mysqli_fetch_assoc($ingredientes)): ?>
        <option value="<?= $ing['idIngrediente'] ?>" ?>
          <?= htmlspecialchars($ing['Nombre']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="col-md-4">
    <input type="number" name="cantidades[]" class="form-control input-cant" placeholder="Cantidad" required>
  </div>
  <div class="col-md-2">
    <button type="button" class="btn btn-danger btn-sm btn-eliminar-consumo">🗑</button>
  </div>
</div>
<?php echo ob_get_clean();
