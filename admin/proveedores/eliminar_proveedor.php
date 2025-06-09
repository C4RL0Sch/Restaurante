<?php
require_once("../../conexion.php");

$id = $_POST['id'] ?? '';

if ($id === '') {
    echo "ID inválido.";
    exit();
}

$stmt = $conexion->prepare("DELETE FROM proveedores WHERE idProveedor = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "🗑️ Proveedor eliminado.";
} else {
    echo "❌ No se pudo eliminar.";
}
$stmt->close();