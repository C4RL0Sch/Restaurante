<?php
include("../usuario.php");
session_start();
$usuario = $_SESSION['usuario'] ?? null;
if (!isset($usuario) || $usuario->rol !== 'chef') {
    header('Location: ../');
    exit();
}
include("../conexion.php");

$result = mysqli_query($conexion, "
    SELECT i.Nombre, i.Medida, i.Cantidad, i.Costo, p.Nombre AS Proveedor
    FROM ingredientes i
    JOIN proveedores p ON i.idProveedor = p.idProveedor
    ORDER BY i.Nombre
");
?>

<div class="container-fluid">
    <h4 class="mb-3">📦 Inventario de Ingredientes</h4>
    <div class="mb-3">
        <input
            type="search"
            id="buscadorInventario"
            class="form-control"
            onkeyup="filtrarTabla(this, 'tablaInventario')"
            placeholder="Buscar ingrediente...">
    </div>

    <table id="tablaInventario" class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Ingrediente</th>
                <th>Medida</th>
                <th>Cantidad</th>
                <th>Costo</th>
                <th>Proveedor</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)):
                $cantidad = $row['Cantidad'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['Nombre']) ?></td>
                    <td><?= htmlspecialchars($row['Medida']) ?></td>
                    <td><?= $cantidad ?></td>
                    <td>$<?= number_format($row['Costo'], 2) ?></td>
                    <td><?= htmlspecialchars($row['Proveedor']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>