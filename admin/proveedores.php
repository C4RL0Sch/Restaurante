<?php
include("../conexion.php");

$resultado = mysqli_query($conexion, "SELECT * FROM proveedores");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>📋 Lista de Proveedores</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProveedor">
            ➕ Nuevo proveedor
        </button>
    </div>

    <!-- Modal de Guardado -->
    <div class="modal fade" id="modalProveedor" tabindex="-1" aria-labelledby="modalProveedorLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formProveedor">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalProveedorLabel">Registrar nuevo proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body mx-2">
                        <div class="row mb-2">
                            <label for="id" class="form-label px-0 mb-0"><b>ID:</b></label>
                            <input type="number" maxlength="10" class="form-control" id="id" name="id" placeholder="ID del proveedor">
                        </div>
                        <div class="row mb-2">
                            <label for="nombre" class="form-label px-0 mb-0"><b>Nombre:</b></label>
                            <input type="text" maxlength="80" class="form-control" id="nombre" name="nombre" placeholder="Nombre del proveedor">
                        </div>
                        <div class="row mb-2">
                            <label for="telefono" class="form-label px-0 mb-0"><b>Telefono:</b></label>
                            <input type="number" maxlength="12" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                        </div>
                        <div class="row mb-2">
                            <label for="direccion" class="form-label px-0 mb-0"><b>Dirección:</b></label>
                            <input type="text" maxlength="100" class="form-control" id="direccion" name="direccion" placeholder="Dirección">
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

    <!-- Modal de edición -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formEditarProveedor">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarLabel">Editar proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body mx-2">
                        <div class="row mb-2">
                            <label for="id" class="form-label px-0 mb-0"><b>ID:</b></label>
                            <input type="number" readonly maxlength="10" class="form-control" id="editar-id" name="id" placeholder="ID del proveedor">
                        </div>
                        <div class="row mb-2">
                            <label for="editar-nombre" class="form-label px-0 mb-0"><b>Nombre:</b></label>
                            <input type="text" maxlength="80" class="form-control" name="nombre" id="editar-nombre" placeholder="Nombre">
                        </div>
                        <div class="row mb-2">
                            <label for="editar-telefono" class="form-label px-0 mb-0"><b>Telefono:</b></label>
                            <input type="number" maxlength="12" class="form-control" name="telefono" id="editar-telefono" placeholder="Teléfono">
                        </div>
                        <div class="row mb-2">
                            <label for="editar-direccion" class="form-label px-0 mb-0"><b>Dirección:</b></label>
                            <input type="text" maxlength="100" class="form-control" name="direccion" id="editar-direccion" placeholder="Dirección">
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
        <input type="search" class="form-control" placeholder="Buscar..." onkeyup="filtrarTabla(this, 'tabla-proveedores')">
    </div>
    <table id="tabla-proveedores" class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?= $row['idProveedor'] ?></td>
                    <td><?= htmlspecialchars($row['Nombre']) ?></td>
                    <td><?= htmlspecialchars($row['Telefono']) ?></td>
                    <td><?= htmlspecialchars($row['Direccion']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning me-1 btn-editar"
                            data-id="<?= $row['idProveedor'] ?>"
                            data-nombre="<?= htmlspecialchars($row['Nombre']) ?>"
                            data-telefono="<?= htmlspecialchars($row['Telefono']) ?>"
                            data-direccion="<?= htmlspecialchars($row['Direccion']) ?>">
                            ✏️
                        </button>
                        <button class="btn btn-sm btn-danger btn-eliminar"
                            data-id="<?= $row['idProveedor'] ?>">
                            🗑️
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="js/proveedores.js"></script>