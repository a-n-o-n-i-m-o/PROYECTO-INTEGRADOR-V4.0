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
    "tasa_interes"        => $_POST['tasa_interes'] ?? null,
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

function buscar($pdo, $tabla, $datos)
{
    $sql = $pdo->prepare("SELECT * FROM $tabla c INNER JOIN creditos_hipotecarios ch ON c.cliente_id = ch.id_cliente  INNER JOIN seguros s ON ch.id_credito_hipotecario = s.id_credito_hipotecario WHERE c.dni = :dni");
    $sql->bindParam(':dni', $datos['dni']);
    $sql->execute();
    $cliente = $sql->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        $msg = array(
            "tipo"  => "success",
            "data" => $cliente
        );
    } else {
        $msg = array(
            "tipo"  => "error",
            "texto" => "No se encontró al cliente"
        );
    }

    echo json_encode($msg);
    return;
}






function insertar($pdo, $tabla, $datos)
{
    try {
        // Primera inserción: Datos del cliente
        $sql = $pdo->prepare("
            INSERT INTO $tabla(
                nombre, apellidos, dni, telefono, correo, fecha_nacimiento, tipo_ingreso, 
                ingreso_mensual, estado_civil_id, departamento_id, provincia_id, distrito_id, direccion
            ) 
            VALUES (
                :nombre, :apellidos, :dni, :telefono, :correo, :fechaNacimiento, :tipoIngreso, 
                :ingresoMensual, :estadoCivil, :departamento, :provincia, :distrito, :direccion
            )
        ");

        $sql->bindParam(':nombre', $datos['nombre']);
        $sql->bindParam(':apellidos', $datos['apellidos']);
        $sql->bindParam(':dni', $datos['dni']);
        $sql->bindParam(':telefono', $datos['telefonoCliente']);
        $sql->bindParam(':correo', $datos['emailCliente']);
        $sql->bindParam(':fechaNacimiento', $datos['fechaNacimiento']);
        $sql->bindValue(':tipoIngreso', null, PDO::PARAM_NULL);
        $sql->bindParam(':ingresoMensual', $datos['ingresoMensual']);
        $sql->bindParam(':estadoCivil', $datos['estadoCivil']);
        $sql->bindParam(':departamento', $datos['departamento']);
        $sql->bindParam(':provincia', $datos['provincia']);
        $sql->bindParam(':distrito', $datos['distrito']);
        $sql->bindParam(':direccion', $datos['direccionCliente']);

        if (!$sql->execute()) {
            throw new Exception("Error al insertar los datos del cliente: " . json_encode($sql->errorInfo()));
        }

        $cliente_id = $pdo->lastInsertId();

        // Cálculo de TEA y TEM según el plazo
        if ($datos['plazo'] >= 4 && $datos['plazo'] <= 10) {
            $tea = 0.10;
        } elseif ($datos['plazo'] >= 11 && $datos['plazo'] <= 20) {
            $tea = 0.12;
        } elseif ($datos['plazo'] >= 21 && $datos['plazo'] <= 25) {
            $tea = 0.14;
        } else {
            $tea = 0;
        }

        $tem = (pow(1 + $tea, 1.0 / 12)) - 1;

        // Segunda inserción: Datos del crédito hipotecario
        $sql = $pdo->prepare("
            INSERT INTO creditos_hipotecarios (
                id_cliente, monto, cuota_ini, plazo, tea, tem, tipo_seguro
            ) 
            VALUES (
                :id_cliente, :monto_credito, :cuota_ini, :plazo, :tea, :tem, :tipo_seguro
            )
        ");
        $sql->bindParam(':id_cliente', $cliente_id);
        $sql->bindParam(':monto_credito', $datos['monto_credito']);
        $sql->bindParam(':cuota_ini', $datos['cuotaInicial']);
        $sql->bindParam(':plazo', $datos['plazo']);
        $sql->bindParam(':tea', $tea);
        $sql->bindParam(':tem', $tem);
        $sql->bindParam(':tipo_seguro', $datos['tipoSeguro']);

        if (!$sql->execute()) {
            throw new Exception("Error al insertar los datos del crédito hipotecario: " . json_encode($sql->errorInfo()));
        }

        $credito_id = $pdo->lastInsertId();

        // Validación del tipo de seguro y cálculo de costo
        $cost = 0;
        if (!empty($datos['tipoSeguro'])) {
            switch ($datos['tipoSeguro']) {
                case '3':
                    $cost = 0.015;
                    break;
                case '4':
                    $cost = 0.02;
                    break;
            }
        }

        // Tercera inserción: Datos del seguro
        $sql = $pdo->prepare("
            INSERT INTO seguros (
                tipo_seguro_id, id_credito_hipotecario, id_credito_vehicular, 
                id_credito_estudio, id_deposito_plazo_fijo, costo
            ) 
            VALUES (
                :tipo_seguro_id, :id_credito_hipotecario, :id_credito_vehicular, 
                :id_credito_estudio, :id_deposito_plazo_fijo, :costo
            )
        ");
        $tipo_seguro = (int)$datos['tipoSeguro'];
        $sql->bindParam(':tipo_seguro_id', $tipo_seguro);
        $sql->bindParam(':id_credito_hipotecario', $credito_id);
        $sql->bindValue(':id_credito_vehicular', null, PDO::PARAM_NULL);
        $sql->bindValue(':id_credito_estudio', null, PDO::PARAM_NULL);
        $sql->bindValue(':id_deposito_plazo_fijo', null, PDO::PARAM_NULL);
        $sql->bindParam(':costo', $cost);

        if (!$sql->execute()) {
            throw new Exception("Error al insertar los datos del seguro: " . json_encode($sql->errorInfo()));
        }

        // Éxito en todas las operaciones
        $msg = array(
            "tipo"  => "success",
            "texto" => "Crédito creado satisfactoriamente."
        );
    } catch (Exception $e) {
        // Manejo de errores con detalle
        $msg = array(
            "tipo"  => "error",
            "texto" => $e->getMessage()
        );
    }

    echo json_encode($msg);
    return;
}







function calculosCredito($datos)
{
    // Cálculos
    $cuotaInicialSoles = $datos['monto_credito'] * 0.1;
    $montoCapital = $datos['monto_credito'] - $cuotaInicialSoles; // El capital restante después de la cuota inicial
    if ($datos['plazo'] >= 4 && $datos['plazo'] <= 10) {
        $tea = 0.10; // Tasa en formato numérico
    } else if ($datos['plazo'] >= 11 && $datos['plazo'] <= 20) {
        $tea = 0.12;
    } else if ($datos['plazo'] >= 21 && $datos['plazo'] <= 25) {
        $tea = 0.14;
    } else {
        $tea = 0;
    }
    $tem = (pow(1 + $tea, 1.0 / 12)) - 1;
    $payment = $montoCapital * ($tem / (1 - pow(1 + $tem, -$datos['plazo']*12)));

    $totalSeguroGenerado = 0;
    $interesTotal = 0;
    $totalCapitalPagado = 0;
    $cronograma_pagos = [];
    $fecha_inicio = new DateTime();

    for ($i = 0; $i < $datos['plazo']*12; $i++) {
        $interes = round($montoCapital * $tem, 2);
        $cuotaMensual = round($payment, 2);
        $capitalPagado = round($cuotaMensual - $interes, 2);
        $montoCapital -= $capitalPagado;

        // Si el saldo de capital es negativo, lo ajustamos a 0
        if ($montoCapital < 0) {
            $montoCapital = 0;
        }

        $interesTotal += $interes;
        $totalCapitalPagado += $capitalPagado;

        $fecha_pago = clone $fecha_inicio;
        $fecha_pago->modify("+$i month");

        $seguro = 0;

        if (!empty($datos['tipoSeguro'])) {
            // Inicializamos el seguro
            $seguro = 0;
        
            // Verificamos y calculamos el seguro dependiendo del tipo
            switch ($datos['tipoSeguro']) {
                case '3': // Tipo 3: Desgravamen
                    $seguro = round(0.015 * $montoCapital, 2); // 1.5%
                    break;
                case '4': // Tipo 4: Inmueble
                    $seguro = round(0.02 * $montoCapital, 2); // 2%
                    break;
                default:
                    // Validación para cualquier otro tipo de seguro
                    $seguro = 0; // No aplica seguro
                    break;
            }
        
            // Acumulamos el seguro total generado
            $totalSeguroGenerado += $seguro;
        }

        // Ajustar a enteros
        $cronograma_pagos[] = [
            'mes' => $fecha_pago->format('Y-m'),
            'monto_capital' => number_format(round($capitalPagado, 0), 1, '.', ''), // Redondeamos a enteros y agregamos ".0"
            'interes' => number_format(round($interes, 0), 1, '.', ''), // Redondeamos a enteros y agregamos ".0"
            'cuota_mensual' => number_format(round($cuotaMensual + $seguro, 0), 1, '.', ''), // Redondeamos a enteros y agregamos ".0"
            'saldo_capital' => number_format(round($montoCapital, 0), 1, '.', ''), // Redondeamos a enteros y agregamos ".0"
            'seguro' => number_format(round($seguro, 0), 1, '.', '') // Redondeamos a enteros y agregamos ".0"
        ];
    }
 
    $montoTotal = $cuotaInicialSoles + $totalCapitalPagado + $interesTotal + $totalSeguroGenerado;

    // Almacenar los datos en la sesión
    $_SESSION['datos_credito'] = $datos;
    $_SESSION['cronograma_pagos'] = $cronograma_pagos;
    $_SESSION['montoTotal'] = number_format(round($montoTotal, 0), 1, '.', ''); // Redondeamos a enteros y agregamos ".0"
    $_SESSION['cuotaInicialSoles'] = number_format(round($cuotaInicialSoles, 0), 1, '.', ''); // Redondeamos a enteros y agregamos ".0"
    $_SESSION['totalCapitalPagado'] = number_format(round($totalCapitalPagado, 0), 1, '.', ''); // Redondeamos a enteros y agregamos ".0"
    $_SESSION['interesTotal'] = number_format(round($interesTotal, 0), 1, '.', ''); // Redondeamos a enteros y agregamos ".0"
    $_SESSION['totalSeguroGenerado'] = number_format(round($totalSeguroGenerado, 0), 1, '.', ''); // Redondeamos a enteros y agregamos ".0"

    // Respuesta de éxito
    $msg = array(
        "tipo"  => "success",
        "texto" => "credito_hipotecario/confirmacion_credito.php",
        "texto1" => $datos['tipoSeguro']
    );

    echo json_encode($msg);
    return;
}




function verCronograma($pdo, $tabla, $datos)
{

    $sql = $pdo->prepare("SELECT * FROM $tabla c INNER JOIN creditos_hipotecarios ch ON c.cliente_id = ch.id_cliente  INNER JOIN seguros s ON ch.id_credito_hipotecario= s.id_credito_hipotecario WHERE c.dni = :dni");
    $sql->bindParam(':dni', $datos['dni']);

    $sql->execute();
    $cliente = $sql->fetch(PDO::FETCH_ASSOC);

       // Cálculos
    $cuotaInicialSoles = $cliente['monto'] * 0.1;
    $montoCapital = $cliente['monto'] - $cuotaInicialSoles; // El capital restante después de la cuota inicial
    if ($cliente['plazo'] >= 4 && $cliente['plazo'] <= 10) {
        $tea = 0.10; // Tasa en formato numérico
    } else if ($cliente['plazo'] >= 11 && $cliente['plazo'] <= 20) {
        $tea = 0.12;
    } else if ($cliente['plazo'] >= 21 && $cliente['plazo'] <= 25) {
        $tea = 0.14;
    } else {
        $tea = 0;
    }
    $tem = (pow(1 + $tea, 1.0 / 12)) - 1;
    $payment = $montoCapital * ($tem / (1 - pow(1 + $tem, -$cliente['plazo']*12)));

    $totalSeguroGenerado = 0;
    $interesTotal = 0;
    $totalCapitalPagado = 0;
    $cronograma_pagos = [];
    $fecha_inicio = new DateTime();

    for ($i = 0; $i < $cliente['plazo']*12; $i++) {
        $interes = round($montoCapital * $tem, 2);
        $cuotaMensual = round($payment, 2);
        $capitalPagado = round($cuotaMensual - $interes, 2);
        $montoCapital -= $capitalPagado;

        // Si el saldo de capital es negativo, lo ajustamos a 0
        if ($montoCapital < 0) {
            $montoCapital = 0;
        }

        $interesTotal += $interes;
        $totalCapitalPagado += $capitalPagado;

        $fecha_pago = clone $fecha_inicio;
        $fecha_pago->modify("+$i month");

        $seguro = 0;

        if (!empty($cliente['tipo_seguro'])) {
            // Inicializamos el seguro
            $seguro = 0;
        
            // Verificamos y calculamos el seguro dependiendo del tipo
            switch ($cliente['tipo_seguro']) {
                case '3': // Tipo 3: Desgravamen
                    $seguro = round(0.015 * $montoCapital, 2); // 1.5%
                    break;
                case '4': // Tipo 4: Inmueble
                    $seguro = round(0.02 * $montoCapital, 2); // 2%
                    break;
                default:
                    // Validación para cualquier otro tipo de seguro
                    $seguro = 0; // No aplica seguro
                    break;
            }
        
            // Acumulamos el seguro total generado
            $totalSeguroGenerado += $seguro;
        }

        // Ajustar a enteros
        $cronograma_pagos[] = [
            'mes' => $fecha_pago->format('Y-m'),
            'monto_capital' => number_format(round($capitalPagado, 0), 1, '.', ''), // Redondeamos a enteros y agregamos ".0"
            'interes' => number_format(round($interes, 0), 1, '.', ''), // Redondeamos a enteros y agregamos ".0"
            'cuota_mensual' => number_format(round($cuotaMensual + $seguro, 0), 1, '.', ''), // Redondeamos a enteros y agregamos ".0"
            'saldo_capital' => number_format(round($montoCapital, 0), 1, '.', ''), // Redondeamos a enteros y agregamos ".0"
            'seguro' => number_format(round($seguro, 0), 1, '.', '') // Redondeamos a enteros y agregamos ".0"
        ];
    }
 
    $montoTotal = $cuotaInicialSoles + $totalCapitalPagado + $interesTotal + $totalSeguroGenerado;

    // Almacenar los datos en la sesión
    $_SESSION['datos_credito'] = $cliente;
    $_SESSION['cronograma_pagos'] = $cronograma_pagos;
    $_SESSION['montoTotal'] = number_format(round($montoTotal, 0), 1, '.', ''); // Redondeamos a enteros y agregamos ".0"
    $_SESSION['cuotaInicialSoles'] = number_format(round($cuotaInicialSoles, 0), 1, '.', ''); // Redondeamos a enteros y agregamos ".0"
    $_SESSION['totalCapitalPagado'] = number_format(round($totalCapitalPagado, 0), 1, '.', ''); // Redondeamos a enteros y agregamos ".0"
    $_SESSION['interesTotal'] = number_format(round($interesTotal, 0), 1, '.', ''); // Redondeamos a enteros y agregamos ".0"
    $_SESSION['totalSeguroGenerado'] = number_format(round($totalSeguroGenerado, 0), 1, '.', ''); // Redondeamos a enteros y agregamos ".0"

    // Respuesta de éxito
    $msg = array(
        "tipo"  => "success",
        "texto" => "credito_hipotecario/confirmacion_credito.php",
        "texto1" => $cliente['tipo_seguro']
    );

    echo json_encode($msg);
    return;
}

