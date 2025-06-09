<?php
include("conexion.php");
include("usuario.php");

$usuario = trim($_POST['usuario'] ?? '');
$clave = trim($_POST['password'] ?? '');

$consulta = "SELECT * FROM usuarios WHERE usuario = ? AND contra = ?";

$stmt = mysqli_prepare($conexion, $consulta);

mysqli_stmt_bind_param($stmt, "ss", $usuario, $clave);


mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) > 0) {

    $datos = $resultado->fetch_row();
    $id = $datos[0];
    $nombre = $datos[1];
    $rol = $datos[2];
    $usuario = $datos[3];

    $user = new Usuario($id,$nombre,$usuario,$rol);

    session_start();
    $_SESSION['usuario'] = $user;
    
    if ($rol === "admin") {
        echo "admin/";
    } else if ($rol === "chef") {
        echo "chef/";
    }
} else {
    echo "ERROR: Usuario o contraseña incorrectos.";
}

mysqli_stmt_close($stmt);
