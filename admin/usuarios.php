<?php
include("../conexion.php");

$admin_result = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Rol = 'admin' AND idUsuario!=1 ORDER BY idUsuario DESC");
$chef_result = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Rol = 'chef' ORDER BY idUsuario DESC");
?>

<div class="container-fluid">
    <ul class="nav nav-tabs justify-content-center mb-3 me-5" id="tabUsuarios" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="tab-admin" data-bs-toggle="tab" data-bs-target="#contenido-admin" type="button" role="tab">
                <h4>Administradores</h4>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="tab-chef" data-bs-toggle="tab" data-bs-target="#contenido-chef" type="button" role="tab">
                <h4>Chefs</h4>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="contenidoTabs">
        <!-- Administradores -->
        <div class="tab-pane fade show active" id="contenido-admin" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5>👨‍💼 Lista de Administradores</h5>
                <button class="btn btn-primary" onclick="abrirModal('admin')">➕ Nuevo administrador</button>
            </div>
            <div class="mb-2">
                <input type="search" class="form-control" placeholder="Buscar..." onkeyup="filtrarTabla(this, 'tabla-admin')">
            </div>
            <table id="tabla-admin" class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($admin = mysqli_fetch_assoc($admin_result)): ?>
                        <tr>
                            <td><?= $admin['idUsuario'] ?></td>
                            <td><?= htmlspecialchars($admin['Nombre']) ?></td>
                            <td><?= htmlspecialchars($admin['Usuario']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-editar" data-id="<?= $admin['idUsuario'] ?>" data-nombre="<?= htmlspecialchars($admin['Nombre']) ?>" data-usuario="<?= htmlspecialchars($admin['Usuario']) ?>" data-rol="admin">✏️</button>
                                <button class="btn btn-sm btn-danger btn-eliminar" data-id="<?= $admin['idUsuario'] ?>">🗑️</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Chefs -->
        <div class="tab-pane fade" id="contenido-chef" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5>👨‍🍳 Lista de Chefs</h5>
                <button class="btn btn-primary" onclick="abrirModal('chef')">➕ Nuevo chef</button>
            </div>
            <div class="mb-2">
                <input type="search" class="form-control" placeholder="Buscar..." onkeyup="filtrarTabla(this, 'tabla-chef')">
            </div>
            <table id="tabla-chef" class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($chef = mysqli_fetch_assoc($chef_result)): ?>
                        <tr>
                            <td><?= $chef['idUsuario'] ?></td>
                            <td><?= htmlspecialchars($chef['Nombre']) ?></td>
                            <td><?= htmlspecialchars($chef['Usuario']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-editar" data-id="<?= $chef['idUsuario'] ?>" data-nombre="<?= htmlspecialchars($chef['Nombre']) ?>" data-usuario="<?= htmlspecialchars($chef['Usuario']) ?>" data-rol="chef">✏️</button>
                                <button class="btn btn-sm btn-danger btn-eliminar" data-id="<?= $chef['idUsuario'] ?>">🗑️</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formUsuario">
                <div class="modal-header">
                    <h5 id="modal-title" class="modal-title">Registrar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!--<input type="hidden" id="usuario-id" name="id">-->
                    <input type="hidden" id="usuario-rol" name="rol">

                    <div class="mb-2">
                        <label for="usuario-id" class="form-label px-0 mb-0"><b>ID:</b></label>
                        <input type="text" maxlength="10" class="form-control" id="usuario-id" name="id" placeholder="ID del ingrediente">
                        <span id="error-id" class="text-danger d-none"></span>
                    </div>

                    <div class="mb-2">
                        <label for="nombre" class="form-label"><b>Nombre:</b></label>
                        <input type="text" maxlength="70" class="form-control" id="usuario-nombre" name="nombre" placeholder="Nombre completo">
                        <span id="error-nombre" class="text-danger d-none"></span>
                    </div>
                    <div class="mb-2">
                        <label for="usuario" class="form-label"><b>Usuario:</b></label>
                        <input type="text" maxlength="30" class="form-control" id="usuario-usuario" name="usuario" placeholder="Nombre de usuario">
                        <span id="error-usuario" class="text-danger d-none"></span>
                    </div>
                    <div class="mb-2" id="password-group">
                        <label for="contra" class="form-label"><b>Contraseña:</b></label>
                        <input type="password" maxlength="30" class="form-control" id="usuario-contra" name="contra">
                        <span id="error-contra" class="text-danger d-none"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/usuarios.js"></script>