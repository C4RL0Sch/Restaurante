<?php
require_once("../../conexion.php");

$id = $_POST['id'] ?? '';
if ($id === '') {
    echo "ID inválido.";
    exit();
}

$stmt = $conexion->prepare("DELETE FROM ingredientes WHERE idIngrediente = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "🗑️ Ingrediente eliminado.";
} else {
    echo "❌ No se pudo eliminar.";
}
$stmt->close();