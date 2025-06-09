<?php
include("../conexion.php");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>🧾 Órdenes de Compra</h4>
        <div>
            <button class="btn btn-primary" id="btnNuevaOrden">➕ Nueva Orden</button>
            <button id="btnAbrirModalReporte" class="btn btn-danger ms-2">📄 Reporte PDF</button>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" id="buscadorOrden" placeholder="Buscar proveedor...">
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control" id="filtroFecha">
        </div>
    </div>
    <div id="tablaOrdenes">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ordenes = mysqli_query($conexion, "SELECT o.idOrden, o.Fecha, p.Nombre AS Proveedor, 
      (SELECT SUM(dc.Cantidad * dc.CostoUnitario) FROM detalle_compra dc WHERE dc.idOrden = o.idOrden) AS Total 
      FROM ordenes_compra o, proveedores p WHERE o.idProveedor = p.idProveedor
      ORDER BY o.idOrden DESC");

                while ($o = mysqli_fetch_assoc($ordenes)):
                ?>
                    <tr>
                        <td><?= $o['idOrden'] ?></td>
                        <td><?= $o['Fecha'] ?></td>
                        <td><?= htmlspecialchars($o['Proveedor']) ?></td>
                        <td>$<?= number_format($o['Total'], 2) ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary btn-ver-detalle" data-id="<?= $o['idOrden'] ?>">
                                🔍 Ver Detalles
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalDetalleOrden" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de Orden</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detalleContenido">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalOrden" tabindex="-1" aria-labelledby="modalOrdenLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formOrdenCompra" class="needs-validation" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar Nueva Orden</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="idProveedor" class="form-label"><b>Proveedor:</b></label>
                                <select name="idProveedor" id="idProveedor" class="form-select" required>
                                    <option value="" disabled selected>Seleccione un proveedor</option>
                                    <?php
                                    $proveedores = mysqli_query($conexion, "SELECT * FROM proveedores");
                                    while ($p = mysqli_fetch_assoc($proveedores)) {
                                        echo "<option value='{$p['idProveedor']}'>{$p['Nombre']}</option>";
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Debes seleccionar un proveedor.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><b>Fecha:</b></label>
                                <input type="date" name="fecha" class="form-control" required value="<?= date('Y-m-d') ?>">
                                <div class="invalid-feedback">La fecha es obligatoria</div>
                            </div>
                        </div>

                        <hr>

                        <h6><b>Ingredientes:</b></h6>
                        <div id="ingredientes-container">

                        </div>
                        <button type="button" class="btn btn-outline-secondary mt-2" id="btnAgregarIngrediente" disabled>➕ Agregar Ingrediente</button>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar Orden</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReportePDF" tabindex="-1" aria-labelledby="modalReportePDFLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formReportePDF" target="_blank" action="reportes/reporte_ordenes.php" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalReportePDFLabel">📄 Generar Reporte de Órdenes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="fechaInicio" class="form-label">Desde:</label>
                        <input type="date" class="form-control" name="inicio" id="fechaInicio">
                    </div>
                    <div class="mb-2">
                        <label for="fechaFin" class="form-label">Hasta:</label>
                        <input type="date" class="form-control" name="fin" id="fechaFin">
                    </div>
                    <div class="mb-2">
                        <label for="proveedor" class="form-label">Proveedor:</label>
                        <select name="proveedor" id="proveedor" class="form-select">
                            <option value="">Todos</option>
                            <?php
                            $proveedores = mysqli_query($conexion, "SELECT * FROM proveedores");
                            while ($p = mysqli_fetch_assoc($proveedores)) {
                                echo "<option value='{$p['idProveedor']}'>{$p['Nombre']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Generar PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/compras.js"></script>