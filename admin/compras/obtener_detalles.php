<?php
include("../../conexion.php");

$idOrden = intval($_GET['id']);
$orden = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT o.Fecha, p.Nombre AS Proveedor
  FROM ordenes_compra o
  JOIN proveedores p ON o.idProveedor = p.idProveedor
  WHERE o.idOrden = $idOrden"));

$detalle = mysqli_query($conexion, "SELECT i.Nombre, i.Medida, d.Cantidad, d.CostoUnitario 
  FROM detalle_compra d
  JOIN ingredientes i ON d.idIngrediente = i.idIngrediente
  WHERE d.idOrden = $idOrden");

$total = 0;
?>

<p><strong>Proveedor:</strong> <?= htmlspecialchars($orden['Proveedor']) ?></p>
<p><strong>Fecha:</strong> <?= $orden['Fecha'] ?></p>
<hr>
<table class="table">
  <thead>
    <tr>
      <th>Ingrediente</th>
      <th>Medida</th>
      <th>Cantidad</th>
      <th>Costo Unitario</th>
      <th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($d = mysqli_fetch_assoc($detalle)):
      $subtotal = $d['Cantidad'] * $d['CostoUnitario'];
      $total += $subtotal;
    ?>
    <tr>
      <td><?= htmlspecialchars($d['Nombre']) ?></td>
      <td><?= $d['Medida'] ?></td>
      <td><?=  $d['Cantidad'] ?></td>
      <td>$<?= number_format($d['CostoUnitario'], 2) ?></td>
      <td>$<?= number_format($subtotal, 2) ?></td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<p class="text-end fw-bold">Total: $<?= number_format($total, 2) ?></p>