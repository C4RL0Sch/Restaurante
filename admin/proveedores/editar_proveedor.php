<?php
require_once("../../conexion.php");

$id = $_POST['id'] ?? '';
$nombre = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

if ($id === '' || $nombre === '' || $telefono === '' || $direccion === '') {
    echo "Todos los campos son obligatorios.";
    exit();
}

$existe = mysqli_query($conexion, "SELECT * FROM proveedores WHERE Nombre = UPPER('$nombre') AND idProveedor != $id");
if (mysqli_num_rows($existe) > 0) {
    exit("El nombre del proveedor ya está en uso.");
}

$stmt = $conexion->prepare("UPDATE proveedores SET Nombre = ?, Telefono = ?, Direccion = ? WHERE idProveedor = ?");
$stmt->bind_param("sssi", $nombre, $telefono, $direccion, $id);

if ($stmt->execute()) {
    echo "✅ Proveedor actualizado.";
} else {
    echo "❌ Error al actualizar.";
}
$stmt->close();