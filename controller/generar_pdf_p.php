<?php
session_start();  // Inicia la sesión
ob_start(); // Inicia el almacenamiento en búfer de salida
// Incluir la biblioteca TCPDF
require_once('C:\xampp\htdocs\SISTEMA-FINANCIERO\vendor\tecnickcom\tcpdf\tcpdf.php');

// Recuperar los datos del cronograma de pagos desde la solicitud POST
$cronograma = unserialize($_POST['cronograma']);

$pdf = new TCPDF();

// Configurar el documento
$pdf->SetCreator('Sistema Financiero');
$pdf->SetAuthor('SISTEMA-FINANCIERO');
$pdf->SetTitle('Cronograma de Pagos');
$pdf->SetSubject('Detalles del Crédito Plazo Fijo');

// Agregar una página
$pdf->AddPage();

// Configurar fuente
$pdf->SetFont('helvetica', '', 12);

// Título de la sección
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Cronograma de Pagos - Plazo Fijo', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);

// Espaciado
$pdf->Ln(10);

// Crear tabla con los datos del cronograma
$pdf->SetFillColor(255, 69, 0); // Color de fondo de los encabezados
$pdf->SetTextColor(255, 255, 255); // Color de texto de los encabezados

// Encabezado de la tabla con los nuevos campos
$pdf->Cell(20, 10, 'Año', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Capital Inicial (S/)', 1, 0, 'C', 1); // Capital Inicial
$pdf->Cell(30, 10, 'Tasa de Interés (%)', 1, 0, 'C', 1); // Tasa de Interés
$pdf->Cell(30, 10, 'Interés (S/)', 1, 0, 'C', 1); // Interés
$pdf->Cell(30, 10, 'Capital Final (S/)', 1, 0, 'C', 1); // Capital Final
$pdf->Cell(30, 10, 'Seguro (S/)', 1, 1, 'C', 1); // Seguro

// Restablecer color de texto
$pdf->SetTextColor(0, 0, 0);

// Insertar los datos del cronograma
foreach ($cronograma as $pago) {
    $pdf->Cell(20, 10, $pago['plazoAnios'], 1); // Año
    $pdf->Cell(30, 10, 'S/ ' . number_format($pago['capital_inicial'], 2), 1); // Capital Inicial
    $pdf->Cell(30, 10, number_format($pago['tasa_interes'], 2), 1); // Tasa de Interés
    $pdf->Cell(30, 10, 'S/ ' . number_format($pago['interes'], 2), 1); // Interés
    $pdf->Cell(30, 10, 'S/ ' . number_format($pago['capital_final'], 2), 1); // Capital Final
    $pdf->Cell(30, 10, 'S/ ' . number_format($pago['seguro'], 2), 1, 1); // Seguro
}

// Resumen del monto total
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Resumen del Monto Total a Pagar', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);

// Output del PDF
$pdf->Output('cronograma_pagos_plazo_fijo.pdf', 'I'); // 'I' para mostrar en el navegador, 'D' para forzar descarga
?>
