<?php

require_once "../config/config.php";

$accion = $_POST['accion'] ?? $_GET['accion'];

/* $tabla  = "empleados";
$tabla2 = "sesiones";
$tabla3 = "roles"; */

$tabla = "clientes";

$datos = array(
    "nombre"            => $_POST['nombre'] ?? null,
    "apellidos"         => $_POST['apellidos'] ?? null,
    "dni"               => $_POST['dni'] ?? null,
    "fechaNacimiento"   => $_POST['fechaNacimiento'] ?? null,
    "direccionCliente"  => $_POST['direccion'] ?? null,
    "telefonoCliente"   => $_POST['telefono'] ?? null,
    "emailCliente"      => $_POST['correo'] ?? null,
    "ingresoMensual"    => $_POST['ingresoMensual'] ?? null,
    "tipoSeguro"        => $_POST['tipo_seguro'] ?? null,
    "estadoCivil"       => $_POST['estadoCivil'] ?? null,
    "departamento"      => $_POST['departamento'] ?? null,
    "provincia"      => $_POST['provincia'] ?? null,
    "distrito"      => $_POST['distrito'] ?? null,
    "monto_credito"  => $_POST['monto_credito'] ?? null,
    "cuotaInicial"      => $_POST['cuota_inicial'] ?? null,
    "plazo"             => $_POST['plazo_credito'] ?? null,
);

//verificando a cual accion tomar
switch ($accion) {
    case "insertar":
        //insertar
        insertar($pdo, $tabla, $datos);
        break;
    case "calcular":
        //calcular
        calculosCredito($datos);
        break;
    case "buscar":
        buscar($pdo, $tabla, $datos);
        break;
    case "cronograma":
        verCronograma($pdo, $tabla, $datos);
        break;
}


function calculosCredito($datos) {
    // Paso 1: Obtener el monto y cuota inicial
    $montoCredito = $datos['monto_credito'];
    $cuotaInicialPorcentaje = $datos['cuotaInicial'] / 100; // Convertir el porcentaje de la cuota inicial a valor decimal
    $plazo = $datos['plazo']; // El plazo en años (se espera que esté en años)

    // Paso 2: Calcular Monto Capital (restando la cuota inicial)
    $cuotaInicialSoles = $montoCredito * $cuotaInicialPorcentaje;
    $montoCapital = $montoCredito - $cuotaInicialSoles; // Monto Capital es el monto total menos la cuota inicial

    // Paso 3: Calcular TEA y convertir a TEM (Tasa Efectiva Mensual)
    $tea = 0.15; // TEA siempre es 15% (0.15)
    $tem = pow(1 + $tea, 1 / 12) - 1; // TEM es la tasa efectiva mensual

    // Paso 4: Convertir plazo de años a meses
    $plazoMeses = $plazo * 12; // Convertir el plazo de años a meses

    // Paso 5: Verificar que el plazo y el monto sean válidos para evitar división por cero
    if ($plazoMeses <= 0 || $montoCapital <= 0) {
        return "Error: El plazo y el monto deben ser mayores que cero.";
    }

    // Paso 6: Calcular la cuota mensual usando la fórmula de amortización
    $cuotaMensual = $montoCapital * ($tem / (1 - pow(1 + $tem, -$plazoMeses)));

    // Paso 7: Calcular el Monto Total a Pagar
    $interesTotal = 0;
    $saldoCapital = $montoCapital;
    for ($mes = 1; $mes <= $plazoMeses; $mes++) {
        // Calcular el interés mensual y el aporte a capital
        $interesMensual = $saldoCapital * $tem;
        $aporteCapital = $cuotaMensual - $interesMensual;
        $saldoCapital -= $aporteCapital;

        // Acumular el interés total
        $interesTotal += $interesMensual;
    }
    $montoTotalPagar = $montoCapital + $cuotaInicialSoles + $interesTotal;

    // Paso 8: Preparar los datos para mostrar
    $cronogramaPagos = [];
    $saldoCapital = $montoCapital; // Resetear saldo capital para la generación del cronograma
    for ($mes = 1; $mes <= $plazoMeses; $mes++) {
        $interesMensual = $saldoCapital * $tem;
        $aporteCapital = $cuotaMensual - $interesMensual;
        $saldoCapital -= $aporteCapital;

        // Almacenar el cronograma de pagos
        $cronogramaPagos[] = [
            "mes" => $mes,
            "aporteCapital" => number_format($aporteCapital, 2),
            "interesMensual" => number_format($interesMensual, 2),
            "cuotaMensual" => number_format($cuotaMensual, 2),
            "saldoCapital" => number_format($saldoCapital, 2),
        ];
    }

    // Paso 9: Almacenar en la sesión
    $_SESSION['datos_credito'] = $datos;
    $_SESSION['cronograma_pagos'] = $cronogramaPagos;
    $_SESSION['montoTotal'] = number_format($montoTotalPagar, 2);
    $_SESSION['cuotaInicialSoles'] = number_format($cuotaInicialSoles, 2);
    $_SESSION['totalCapitalPagado'] = number_format($montoCapital, 2);
    $_SESSION['interesTotal'] = number_format($interesTotal, 2);
    $_SESSION['monto_capital'] = number_format($montoCapital, 2);

    // Mensaje de éxito
    $msg = [
        "tipo" => "success",
        "texto" => "credito_hipotecario/confirmacion_credito.php"
    ];

    echo json_encode($msg);
    return;
}


