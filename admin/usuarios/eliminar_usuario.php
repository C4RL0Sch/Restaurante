<?php
include("../../conexion.php");
include("../../usuario.php");

session_start();

if (!isset($_POST['id'])) {
    echo "ID de usuario no proporcionado.";
    exit;
}

$idAEliminar = $_POST['id'];
$usuarioActual = $_SESSION['usuario'] ?? null;

if (!$usuarioActual) {
    echo "No hay sesión activa.";
    exit;
}

if ($usuarioActual->id == $idAEliminar) {
    echo "No puedes eliminar tu propio usuario.";
    exit;
}

$eliminar = mysqli_query($conexion, "DELETE FROM usuarios WHERE idUsuario = $idAEliminar");

if ($eliminar) {
    echo "Usuario eliminado correctamente.";
} else {
    echo "Error al eliminar el usuario.";
}
?>