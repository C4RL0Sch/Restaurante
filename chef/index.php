<?php
include("../usuario.php");

session_start();
$usuario = $_SESSION['usuario'] ?? null;

if (!isset($usuario) || $usuario->rol !== 'chef') {
    header('Location: ../');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chefs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark text-white">
                <div class="d-flex flex-column align-items-center align-items-sm-start pt-3 min-vh-100">
                    <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline">Mi Restaurante</span>
                    </a>
                    <div class="mb-3 text-center w-100">
                        <img src="../img/logo.png" alt="Usuario" width="64" height="64" class="mb-2">
                        <h6 class="mb-0"><?= htmlspecialchars($usuario->nombre) ?></h6>
                        <small class="text-muted"><?= strtoupper($usuario->rol) ?></small>
                    </div>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="nav-item w-100">
                            <a href="#" onclick="cargarVista('inventario.php')" class="nav-link text-white">📦 Ver Inventario</a>
                        </li>
                        <li class="nav-item w-100">
                            <a href="#" onclick="cargarVista('consumo.php')" class="nav-link text-white">🍳 Registrar Consumo</a>
                        </li>
                        <li class="nav-item w-100 mt-3">
                            <a href="../logout.php" class="nav-link text-danger">🔒 Cerrar sesión</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="contenido" class="col py-3">
                <h2>Bienvenido, <?= htmlspecialchars($usuario->nombre) ?> 👋</h2>
                <p>Selecciona una opción del menú para comenzar.</p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="js/chef.js"></script>
    <div id="scripts"></div>
</body>
</html>