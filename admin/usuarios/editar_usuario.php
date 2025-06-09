<?php
include("../../conexion.php");

$id = trim($_POST['id']);
$nombre = trim($_POST['nombre']);
$usuario = trim($_POST['usuario']);

if (!$id || !$nombre || !$usuario) {
    exit("Todos los campos son obligatorios.");
}

$existe = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Usuario = '$usuario' AND idUsuario != $id");
if (mysqli_num_rows($existe) > 0) {
    exit("El nombre de usuario ya está en uso por otro usuario.");
}

$actualizar = mysqli_query($conexion, "UPDATE usuarios SET Nombre = '$nombre', Usuario = '$usuario' WHERE idUsuario = $id");

if ($actualizar) {
    echo "Usuario actualizado correctamente.";
} else {
    echo "Error al actualizar el usuario.";
}
?>