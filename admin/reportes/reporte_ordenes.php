<?php
require '../../fpdf/fpdf.php';
require '../../conexion.php';

$fechaInicio = $_GET['inicio'] ?? null;
$fechaFin = $_GET['fin'] ?? null;
$idProveedor = $_GET['proveedor'] ?? null;

if (!$fechaInicio || !$fechaFin) {
    die("Faltan parámetros de filtrado.");
}

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('Reporte de Órdenes de Compra'), 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Página ' . $this->PageNo(),0,0,'C');
    }

    function agregarFecha($fecha) {
        $this->SetFont('Arial', 'B', 11);
        $fechaFormateada = strftime("%A %d de %B del %Y", strtotime($fecha));
        $this->Cell(0, 10, utf8_decode(strtoupper($fechaFormateada)), 0, 1, 'L');
    }

    function agregarOrden($orden) {
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, "Orden #{$orden['idOrden']} - Proveedor: " . utf8_decode($orden['Proveedor']), 0, 1);
        $this->Ln(2);

        $this->SetFont('Arial', 'B', 9);
        $this->Cell(80, 8, utf8_decode("Ingrediente"), 1);
        $this->Cell(30, 8, "Cantidad", 1);
        $this->Cell(40, 8, "Costo Unitario", 1);
        $this->Cell(40, 8, "Subtotal", 1);
        $this->Ln();

        $this->SetFont('Arial', '', 9);
        $total = 0;
        foreach ($orden['detalles'] as $detalle) {
            $cantidad = ($detalle['Medida'] === "Litros" || $detalle['Medida'] === "Kilogramos") 
                ? number_format($detalle['Cantidad'], 3) 
                : number_format($detalle['Cantidad'], 0);

            $subtotal = $detalle['Cantidad'] * $detalle['CostoUnitario'];
            $total += $subtotal;

            $this->Cell(80, 7, utf8_decode($detalle['Nombre']), 1);
            $this->Cell(30, 7, "$cantidad " . substr($detalle['Medida'], 0, 2), 1);
            $this->Cell(40, 7, "$" . number_format($detalle['CostoUnitario'], 2), 1);
            $this->Cell(40, 7, "$" . number_format($subtotal, 2), 1);
            $this->Ln();
        }

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(150, 8, "TOTAL ORDEN", 1);
        $this->Cell(40, 8, "$" . number_format($total, 2), 1);
        $this->Ln(10);
    }
}

if(!$idProveedor){
    $query = "
    SELECT o.idOrden, o.Fecha, p.Nombre AS Proveedor
    FROM ordenes_compra o
    JOIN proveedores p ON o.idProveedor = p.idProveedor
    WHERE o.Fecha BETWEEN '$fechaInicio' AND '$fechaFin'
    ORDER BY o.Fecha DESC, o.idOrden DESC
";
}
else{
    $query = "
    SELECT o.idOrden, o.Fecha, p.Nombre AS Proveedor
    FROM ordenes_compra o
    JOIN proveedores p ON o.idProveedor = p.idProveedor
    WHERE o.idProveedor = $idProveedor
      AND o.Fecha BETWEEN '$fechaInicio' AND '$fechaFin'
    ORDER BY o.Fecha DESC, o.idOrden DESC
";
}

$resOrdenes = mysqli_query($conexion, $query);

$ordenesAgrupadas = [];

while ($orden = mysqli_fetch_assoc($resOrdenes)) {
    $idOrden = $orden['idOrden'];
    $orden['detalles'] = [];

    $detalles = mysqli_query($conexion, "
        SELECT d.Cantidad, d.CostoUnitario, i.Nombre, i.Medida
        FROM detalle_compra d
        JOIN ingredientes i ON d.idIngrediente = i.idIngrediente
        WHERE d.idOrden = $idOrden
    ");
    while ($detalle = mysqli_fetch_assoc($detalles)) {
        $orden['detalles'][] = $detalle;
    }

    $ordenesAgrupadas[$orden['Fecha']][] = $orden;
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
setlocale(LC_TIME, 'es_MX.UTF-8');

foreach ($ordenesAgrupadas as $fecha => $ordenes) {
    $pdf->agregarFecha($fecha);
    foreach ($ordenes as $orden) {
        $pdf->agregarOrden($orden);
    }
    $pdf->Cell(0, 0, '', 'T'); // Línea divisoria
    $pdf->Ln(5);
}

$pdf->Output("I", "reporte_compras.pdf");
?>
