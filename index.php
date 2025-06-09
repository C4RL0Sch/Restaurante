<?php
require_once("usuario.php");

session_start();
if (isset($_SESSION['usuario']) && $_SESSION['usuario'] instanceof Usuario) {
    $usuario = $_SESSION['usuario'];

    if($usuario->rol=='admin') {
        header('Location: admin/');
    }
    elseif($usuario->rol=='chef') {
        header('Location: chef/');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="col-md-4 mx-auto rounded border p-3">
        <img src="img/logo.png" alt="Usuario" width="64" height="64" class="mb-2 d-block mx-auto">
        <h1 class="text-center mb-4">Iniciar Sesión</h1>
        <div id="error" class="alert alert-danger text-center d-none" role="alert"></div>
        <form id="form-login">
            <div class="row">
                <label for="usuario" class="form-label"><b>Usuario:</b></label>
                <input class="form-control" type="text" id="usuario" name="usuario">
            </div>
            <div class="row mt-3">
                <label class="form-label" for="password"><b>Contraseña:</b></label>
                <input class="form-control" type="password" id="password" name="password">
            </div>
            <div class="row mt-3">
                <div class="col d-grid">
                    <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
                </div>
                <div class="col d-grid">
                    <button type="button" class="btn btn-primary" onclick="validar()">Entrar</button>
                </div>
        </form>
        </div>
    </div>
</body>
<script src="index.js"></script>
</html>