<?php
include("../../conexion.php");

$idProveedor = isset($_GET['idProveedor']) ? intval($_GET['idProveedor']) : 0;

$ingredientes = mysqli_query($conexion, "
  SELECT idIngrediente, Nombre, Medida 
  FROM ingredientes 
  WHERE idProveedor = $idProveedor
");

ob_start();
?>
<div class="row mb-2 grupo-ingrediente">
  <div class="col-md-5">
    <select class="form-select medida-dinamica" name="ingredientes[]" required>
      <option value="" disabled selected>Seleccione ingrediente</option>
      <?php while ($ing = mysqli_fetch_assoc($ingredientes)): ?>
        <option value="<?= $ing['idIngrediente'] ?>" data-medida="<?= $ing['Medida'] ?>">
          <?= htmlspecialchars($ing['Nombre']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="col-md-3">
    <input type="number" name="cantidades[]" class="form-control cantidad-dinamica" placeholder="Cantidad" required>
  </div>
  <div class="col-md-3">
    <input type="number" name="costos[]" class="form-control costo-dinamico" placeholder="Costo unitario" required>
  </div>
  <div class="col-md-1">
    <button type="button" class="btn btn-danger btn-sm btnEliminarIngrediente">🗑</button>
  </div>
</div>
<?php
echo ob_get_clean();
?>