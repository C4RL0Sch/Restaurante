<?php
include("../../conexion.php");

$id = trim($_POST['id'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

if ($id==='' || $nombre === '' || $telefono === '' || $direccion === '') {
    echo "Todos los campos son obligatorios.";
    exit();
}

$existe = mysqli_query($conexion, "SELECT * FROM proveedores WHERE idProveedor = $id;");
if (mysqli_num_rows($existe) > 0) {
    exit("El ID ya esta ocupado.");
}

$existe = mysqli_query($conexion, "SELECT * FROM proveedores WHERE Nombre = UPPER('$nombre');");
if (mysqli_num_rows($existe) > 0) {
    exit("El proveedor ya está registrado.");
}

$stmt = mysqli_prepare($conexion, "INSERT INTO proveedores (Nombre, Telefono, Direccion) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sss", $id, $nombre, $telefono, $direccion);

if (mysqli_stmt_execute($stmt)) {
    echo "✅ Proveedor registrado correctamente.";
} else {
    echo "❌ Error al guardar el proveedor.";
}

mysqli_stmt_close($stmt);
?>