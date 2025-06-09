<?php
include("../conexion.php");
$ingredientes = mysqli_query($conexion, "SELECT idIngrediente, Nombre, Medida FROM ingredientes");
ob_start();
?>
<div class="row mb-2 grupo-consumo">
  <div class="col-md-6">
    <select class="form-select select-consumo" name="ingredientes[]" required>
      <option value="" disabled selected>Seleccione ingrediente</option>
      <?php while($ing = mysqli_fetch_assoc($ingredientes)): ?>
        <option value="<?= $ing['idIngrediente'] ?>" data-medida="<?= $ing['Medida'] ?>">
          <?= htmlspecialchars($ing['Nombre']) ?>
        </option>
      <?php endwhile; ?>
    </select>
    <div class="invalid-feedback">Debe seleccionar un ingrediente</div>
  </div>
  <div class="col-md-4">
    <input type="text" name="cantidades[]" class="form-control input-cant" placeholder="Cantidad" required>
    <div class="invalid-feedback">Debe ingresar una cantidad valida</div>
  </div>
  <div class="col-md-2">
    <button type="button" class="btn btn-danger btn-sm btn-eliminar-consumo">🗑</button>
  </div>
</div>
<?php echo ob_get_clean();
