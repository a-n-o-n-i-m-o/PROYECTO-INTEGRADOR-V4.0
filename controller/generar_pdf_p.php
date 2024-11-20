<?php
session_start();  // Inicia la sesión
ob_start(); // Inicia el almacenamiento en búfer de salida
// Incluir la biblioteca TCPDF
require_once('C:\xampp\htdocs\SISTEMA-FINANCIERO\vendor\tecnickcom\tcpdf\tcpdf.php');
// Verificar si el cronograma de pagos existe en la sesión
if (!isset($_SESSION['cronograma_pagos']) || !isset($_SESSION['datos_credito'])) {
    die("No hay datos disponibles para generar el PDF.");
}

$cronograma = $_SESSION['cronograma_pagos'];
$datosCredito = $_SESSION['datos_credito'];
$totalIntereses = $_SESSION['totalIntereses'] ?? 0;
$totalSeguro = $_SESSION['totalSeguro'] ?? 0;

// Crear un nuevo PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Configuración del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema Financiero');
$pdf->SetTitle('Cronograma de Pagos');
$pdf->SetSubject('Cronograma de Pagos Detallado');
$pdf->SetMargins(15, 15, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->setFontSubsetting(true);

// Añadir una página
$pdf->AddPage();

// Encabezado
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0, 10, 'Cronograma de Pagos', 0, 1, 'C');

// Información del cliente
$pdf->SetFont('dejavusans', '', 12);
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Datos del Cliente:', 0, 1, 'L');
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0, 7, "Nombre: " . $datosCredito['nombre'] ?? 'No disponible', 0, 1, 'L');
$pdf->Cell(0, 7, "DNI: " . $datosCredito['dni'] ?? 'No disponible', 0, 1, 'L');
$pdf->Cell(0, 7, "Monto Inicial: S/ " . ($datosCredito['monto_deposito'] ?? '0.00'), 0, 1, 'L');
$pdf->Cell(0, 7, "Plazo (años): " . ($datosCredito['plazo'] ?? '0'), 0, 1, 'L');
$pdf->Ln(5);

// Tabla del cronograma
$pdf->SetFont('dejavusans', 'B', 10);
$pdf->Cell(20, 10, 'Año', 1, 0, 'C');
$pdf->Cell(35, 10, 'Capital Inicial', 1, 0, 'C');
$pdf->Cell(35, 10, 'Tasa de Interés', 1, 0, 'C');
$pdf->Cell(35, 10, 'Interés', 1, 0, 'C');
$pdf->Cell(35, 10, 'Seguro', 1, 0, 'C');
$pdf->Cell(35, 10, 'Capital Final', 1, 1, 'C');

// Datos del cronograma
$pdf->SetFont('dejavusans', '', 10);
foreach ($cronograma as $fila) {
    $pdf->Cell(20, 8, $fila['año'], 1, 0, 'C');
    $pdf->Cell(35, 8, 'S/ ' . number_format($fila['capital_inicial'], 2), 1, 0, 'C');
    $pdf->Cell(35, 8, $fila['tasa_interes'], 1, 0, 'C');
    $pdf->Cell(35, 8, 'S/ ' . number_format($fila['interes_anual'], 2), 1, 0, 'C');
    $pdf->Cell(35, 8, 'S/ ' . number_format($fila['seguro'], 2), 1, 0, 'C');
    $pdf->Cell(35, 8, 'S/ ' . number_format($fila['capital_final'], 2), 1, 1, 'C');
}

// Totales
$pdf->Ln(5);
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0, 10, 'Totales:', 0, 1, 'L');
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(0, 7, 'Total Intereses: S/ ' . number_format($totalIntereses, 2), 0, 1, 'L');
$pdf->Cell(0, 7, 'Total Seguro: S/ ' . number_format($totalSeguro, 2), 0, 1, 'L');

// Pie de página
$pdf->Ln(10);
$pdf->SetFont('dejavusans', 'I', 9);
$pdf->MultiCell(0, 5, "Este cronograma de pagos es generado automáticamente y es solo para fines informativos.", 0, 'C');

// Salida del PDF
$pdf->Output('Cronograma_Pagos.pdf', 'I');
?>
