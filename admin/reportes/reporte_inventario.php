<?php
require('../../fpdf/fpdf.php');
require('../../conexion.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'Reporte de Inventario de Ingredientes',0,1,'C');
        $this->Ln(5);
        $this->SetFont('Arial','B',10);
        $this->Cell(10,10,'#',1);
        $this->Cell(50,10,'Nombre',1);
        $this->Cell(25,10,'Medida',1);
        $this->Cell(25,10,'Cantidad',1);
        $this->Cell(25,10,'Costo',1);
        $this->Cell(50,10,'Proveedor',1);
        $this->Ln();
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo(),0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

$consulta = mysqli_query($conexion, 
    "SELECT i.Nombre, i.Medida, i.Cantidad, i.Costo, p.Nombre as Proveedor
     FROM ingredientes i 
     INNER JOIN proveedores p ON i.idProveedor = p.idProveedor
     ORDER BY i.idIngrediente DESC");

$contador = 1;
while ($row = mysqli_fetch_assoc($consulta)) {
    $cantidad = ($row['Medida'] === "Kilogramos" || $row['Medida'] === "Litros")
        ? number_format($row['Cantidad'], 3)
        : (int)$row['Cantidad'];

    $pdf->Cell(10,10,$contador++,1);
    $pdf->Cell(50,10,utf8_decode($row['Nombre']),1);
    $pdf->Cell(25,10,$row['Medida'],1);
    $pdf->Cell(25,10,$cantidad,1);
    $pdf->Cell(25,10,'$'.number_format($row['Costo'], 2),1);
    $pdf->Cell(50,10,utf8_decode($row['Proveedor']),1);
    $pdf->Ln();
}

$pdf->Output('I', 'Inventario_Ingredientes.pdf');
?>