<?php
include("../conexion.php");

$resultado = mysqli_query($conexion, "SELECT i.idIngrediente, i.Nombre, i.Medida, i.Cantidad, i.Costo, p.Nombre as Proveedor, i.idProveedor 
FROM ingredientes i, proveedores p
WHERE i.idProveedor = p.idProveedor ORDER BY i.idIngrediente ASC");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>📋 Lista de Ingredientes</h4>
        <div>
            <button id="btnNuevoIngrediente" class="btn btn-primary">➕ Nuevo ingrediente</button>
            <a href="reportes/reporte_inventario.php" class="btn btn-outline-danger me-2" target="_blank">📄 Reporte PDF</a>
        </div>
    </div>

    <div class="modal fade" id="modalIngrediente" tabindex="-1" aria-labelledby="modalIngredienteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formIngrediente">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalIngredienteLabel">Registrar nuevo Ingrediente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body mx-2">
                        <div class="row mb-2">
                            <label for="id" class="form-label px-0 mb-0"><b>ID:</b></label>
                            <input type="text" maxlength="10" class="form-control" id="id" name="id" placeholder="ID del ingrediente">
                            <span id="error-id" class="text-danger d-none"></span>
                        </div>
                        <div class="row mb-2">
                            <label for="nombre" class="form-label px-0 mb-0"><b>Ingrediente:</b></label>
                            <input type="text" maxlength="40" class="form-control" id="nombre" name="nombre" placeholder="Nombre del ingrediente">
                            <span id="error-nombre" class="text-danger d-none"></span>
                        </div>
                        <div class="row mb-2">
                            <label for="medida" class="form-label px-0 mb-0"><b>Medida:</b></label>
                            <select class="form-select" id="medida" name="medida">
                                <option value="" disabled selected>Seleccione una medida</option>
                                <option value="Kilogramos">Kilogramos (kg)</option>
                                <option value="Gramos">Gramos (g)</option>
                                <option value="Litros">Litros (l)</option>
                                <option value="Mililitros">Mililitros (ml)</option>
                                <option value="Unidad">Unidad</option>
                            </select>
                            <span id="error-medida" class="text-danger d-none"></span>
                        </div>
                        <div class="row mb-2">
                            <label for="cantidad" class="form-label px-0 mb-0"><b>Cantidad:</b></label>
                            <input type="text" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad">
                            <span id="error-cantidad" class="text-danger d-none"></span>
                        </div>
                        <div class="row mb-2">
                            <label for="costo" class="form-label px-0 mb-0"><b>Costo:</b></label>
                            <input type="text" class="form-control" id="costo" name="costo" placeholder="Costo">
                            <span id="error-costo" class="text-danger d-none"></span>
                        </div>
                        <div class="row mb-2">
                            <label for="idProveedor" class="form-label px-0 mb-0"><b>Proveedor:</b></label>
                            <select class="form-select" id="idProveedor" name="idProveedor">
                                <option value="" disabled selected>Seleccione un proveedor</option>
                                <?php
                                $proveedores = mysqli_query($conexion, "SELECT * FROM proveedores");
                                while ($proveedor = mysqli_fetch_assoc($proveedores)) {
                                    echo "<option value='{$proveedor['idProveedor']}'>{$proveedor['Nombre']}</option>";
                                }
                                ?>
                            </select>
                            <span id="error-idProveedor" class="text-danger d-none"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditarIngrediente">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarLabel">Editar Ingrediente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body mx-2">
                        <div class="row mb-2">
                            <label for="id" class="form-label px-0 mb-0"><b>ID:</b></label>
                            <input type="text" maxlength="10" readonly class="form-control" id="editar-id" name="id" placeholder="ID del ingrediente">
                        </div>
                        <div class="row mb-2">
                            <label for="editar-nombre" class="form-label px-0 mb-0"><b>Nombre:</b></label>
                            <input type="text" maxlength="40" class="form-control" id="editar-nombre" name="nombre" placeholder="Nombre del ingrediente">
                            <span id="error-editar-nombre" class="text-danger d-none"></span>
                        </div>
                        <div class="row mb-2">
                            <label for="editar-medida" class="form-label px-0 mb-0"><b>Medida:</b></label>
                            <select class="form-select" id="editar-medida" name="medida">
                                <option value="" disabled selected>Seleccione una medida</option>
                                <option value="Kilogramos">Kilogramos (kg)</option>
                                <option value="Gramos">Gramos (g)</option>
                                <option value="Litros">Litros (l)</option>
                                <option value="Mililitros">Mililitros (ml)</option>
                                <option value="Unidad">Unidad</option>
                            </select>
                            <span id="error-editar-medida" class="text-danger d-none"></span>
                        </div>
                        <div class="row mb-2">
                            <label for="editar-cantidad" class="form-label px-0 mb-0"><b>Cantidad</b></label>
                            <input type="text" class="form-control" id="editar-cantidad" name="cantidad" placeholder="Cantidad">
                            <span id="error-editar-cantidad" class="text-danger d-none"></span>
                        </div>
                        <div class="row mb-2">
                            <label for="editar-costo" class="form-label px-0 mb-0"><b>Costo:</b></label>
                            <input type="text" class="form-control" id="editar-costo" name="costo" placeholder="Costo">
                            <span id="error-editar-costo" class="text-danger d-none"></span>
                        </div>
                        <div class="row mb-2">
                            <label for="editar-proveedor" class="form-label px-0 mb-0"><b>Proveedor:</b></label>
                            <select class="form-select" id="editar-proveedor" name="idProveedor" required>
                                <option value="" disabled selected>Seleccione un proveedor</option>
                                <?php
                                $proveedores = mysqli_query($conexion, "SELECT * FROM proveedores");
                                while ($proveedor = mysqli_fetch_assoc($proveedores)) {
                                    echo "<option value='{$proveedor['idProveedor']}'>{$proveedor['Nombre']}</option>";
                                }
                                ?>
                            </select>
                            <span id="error-editar-proveedor" class="text-danger d-none"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mb-2">
        <input type="search" class="form-control" placeholder="Buscar..." onkeyup="filtrarTabla(this, 'tabla-ingredientes')">
    </div>
    <table id="tabla-ingredientes" class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Medida</th>
                <th>Cantidad</th>
                <th>Costo</th>
                <th>Proveedor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($resultado)):
                $cantidad = $row['Cantidad'];
                $medida = $row['Medida'];

                if ($medida === "Kilogramos" || $medida === "Litros") {
                    $cantidadFormateada = number_format((float)$cantidad, 3, '.', '');
                } else {
                    $cantidadFormateada = (int)$cantidad;
                }
            ?>
                <tr>
                    <td><?= $row['idIngrediente'] ?></td>
                    <td><?= htmlspecialchars($row['Nombre']) ?></td>
                    <td><?= htmlspecialchars($medida) ?></td>
                    <td><?= htmlspecialchars($cantidadFormateada) ?></td>
                    <td><?= number_format((float)$row['Costo'], 2, '.', '') ?></td>
                    <td><?= htmlspecialchars($row['Proveedor']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning me-1 btn-editar"
                            data-id="<?= $row['idIngrediente'] ?>"
                            data-nombre="<?= htmlspecialchars($row['Nombre']) ?>"
                            data-medida="<?= htmlspecialchars($medida) ?>"
                            data-cantidad="<?= $cantidadFormateada ?>"
                            data-costo="<?= number_format((float)$row['Costo'], 2, '.', '') ?>"
                            data-idProveedor="<?= htmlspecialchars($row['idProveedor']) ?>">
                            ✏️
                        </button>
                        <button class="btn btn-sm btn-danger btn-eliminar"
                            data-id="<?= $row['idIngrediente'] ?>">
                            🗑️
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="js/ingredientes.js"></script>