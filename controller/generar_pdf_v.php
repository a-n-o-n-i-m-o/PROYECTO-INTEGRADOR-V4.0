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
$pdf->SetAuthor('Mi Empresa');
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
    $pdf->Cell(30, 10, $pago['mes'], 1);
    $pdf->Cell(40, 10, 'S/ ' . $pago['monto_capital'], 1);
    $pdf->Cell(30, 10, 'S/ ' . $pago['interes'], 1);
    $pdf->Cell(40, 10, 'S/ ' . $pago['cuota_mensual'], 1);
    $pdf->Cell(40, 10, 'S/ ' . $pago['saldo_capital'], 1);
    $pdf->Cell(30, 10, 'S/ ' . $pago['seguro'], 1, 1);
}

// Resumen del monto total
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Monto Total a Pagar: S/ ' . $_SESSION['montoTotal'], 0, 1);
$pdf->Cell(0, 10, 'Desglose del Monto Total', 0, 1);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Cuota Inicial: S/ ' . $_SESSION['cuotaInicialSoles'], 0, 1);
$pdf->Cell(0, 10, 'Total de Cuotas Mensuales: S/ ' . $_SESSION['totalCapitalPagado'], 0, 1);
$pdf->Cell(0, 10, 'Total de Intereses Generados: S/ ' . $_SESSION['interesTotal'], 0, 1);
$pdf->Cell(0, 10, 'Total de Seguro Generado: S/ ' . $_SESSION['totalSeguroGenerado'], 0, 1);

// Output del PDF
$pdf->Output('cronograma_pagos.pdf', 'I'); // 'I' para mostrar en el navegador, 'D' para forzar descarga
?>
