<?php
session_start();  // Inicia la sesión
ob_start(); // Inicia el almacenamiento en búfer de salida
// Incluir la biblioteca TCPDF
require_once('C:\xampp\htdocs\SISTEMA-FINANCIERO\vendor\tecnickcom\tcpdf\tcpdf.php');

// Recuperar los datos del cronograma de pagos desde la solicitud POST
$cronograma = unserialize($_POST['cronograma']);

$pdf = new TCPDF();

// Configurar el documento
$pdf->SetCreator('Cronograma de Pagos');
$pdf->SetAuthor('SISTEMA-FINANCIERO');
$pdf->SetTitle('Cronograma de Pagos');
$pdf->SetSubject('Detalles del Crédito Vehicular');

// Agregar una página
$pdf->AddPage();

// Configurar fuente
$pdf->SetFont('helvetica', '', 12);

// Título de la sección
$pdf->Cell(0, 10, 'Cronograma de Pagos', 0, 1, 'C');

// Espaciado
$pdf->Ln(10);

// Crear tabla con los datos del cronograma
$pdf->SetFillColor(255, 69, 0); // Color de fondo de los encabezados
$pdf->SetTextColor(255, 255, 255); // Color de texto de los encabezados

// Encabezado de la tabla
$pdf->Cell(30, 10, 'Mes', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Monto Capital (S/)', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Interés (S/)', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Cuota Mensual (S/)', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Saldo Capital (S/)', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Seguro (S/)', 1, 1, 'C', 1);

// Restablecer color de texto
$pdf->SetTextColor(0, 0, 0);

// Insertar los datos del cronograma
foreach ($cronograma as $pago) {
    $pdf->Cell(30, 10, isset($pago['plazoAnios']) ? $pago['plazoAnios'] : 'N/A', 1);
    $pdf->Cell(40, 10, 'S/ ' . (is_numeric($pago['cuotaInicial']) ? number_format($pago['cuotaInicial'], 2) : '0.00'), 1);
    $pdf->Cell(30, 10, 'S/ ' . (is_numeric($pago['tasa_interes']) ? number_format($pago['tasa_interes'], 2) : '0.00'), 1);
    $pdf->Cell(40, 10, 'S/ ' . (is_numeric($pago['interes_anual']) ? number_format($pago['interes_anual'], 2) : '0.00'), 1);
    $pdf->Cell(40, 10, 'S/ ' . (is_numeric($pago['capital_final']) ? number_format($pago['capital_final'], 2) : '0.00'), 1);
    $pdf->Cell(30, 10, 'S/ ' . (is_numeric($pago['seguro']) ? number_format($pago['seguro'], 2) : '0.00'), 1, 1);
}

// Resumen del monto total
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Monto Total a Pagar: S/ ' . (is_numeric($_SESSION['montoTotal']) ? number_format($_SESSION['montoTotal'], 2) : '0.00'), 0, 1);
$pdf->Cell(0, 10, 'Desglose del Monto Total', 0, 1);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Cuota Inicial: S/ ' . (is_numeric($_SESSION['cuotaInicialSoles']) ? number_format($_SESSION['cuotaInicialSoles'], 2) : '0.00'), 0, 1);
$pdf->Cell(0, 10, 'Total de Cuotas Mensuales: S/ ' . (is_numeric($_SESSION['totalCapitalPagado']) ? number_format($_SESSION['totalCapitalPagado'], 2) : '0.00'), 0, 1);
$pdf->Cell(0, 10, 'Total de Intereses Generados: S/ ' . (is_numeric($_SESSION['interesTotal']) ? number_format($_SESSION['interesTotal'], 2) : '0.00'), 0, 1);
$pdf->Cell(0, 10, 'Total de Seguro Generado: S/ ' . (is_numeric($_SESSION['totalSeguroGenerado']) ? number_format($_SESSION['totalSeguroGenerado'], 2) : '0.00'), 0, 1);

// Output del PDF
$pdf->Output('cronograma_pagos.pdf', 'I'); // 'I' para mostrar en el navegador, 'D' para forzar descarga
?>
