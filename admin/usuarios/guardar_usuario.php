<?php
include("../../conexion.php");

$id = trim($_POST['id']) ?? '';
$nombre = trim($_POST['nombre']);
$usuario = trim($_POST['usuario']);
$contra = trim($_POST['contra']);
$rol = trim($_POST['rol']);

if (!$nombre || !$usuario || !$contra || !$rol) {
    exit("Todos los campos son obligatorios.");
}

$existe = mysqli_query($conexion, "SELECT * FROM usuarios WHERE idUsuario = $id");
if (mysqli_num_rows($existe) > 0) {
    exit("El ID ya esta ocupado.");
}

$existe = mysqli_query($conexion, "SELECT * FROM usuarios WHERE Usuario = '$usuario'");
if (mysqli_num_rows($existe) > 0) {
    exit("El nombre de usuario ya está en uso.");
}

$insertar = mysqli_query($conexion, "INSERT INTO usuarios (idUsuario, Nombre, Rol, Usuario, Contra) VALUES ($id,'$nombre', '$rol', '$usuario', '$contra')");

if ($insertar) {
    echo "Usuario registrado correctamente.";
} else {
    echo "Error al registrar el usuario.";
}
?>