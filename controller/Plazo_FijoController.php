<?php

require_once "../config/config.php";

$accion = $_POST['accion'] ?? $_GET['accion'];

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
    "tipoSeguro"        => $_POST['tipoSeguro'] ?? null,
    "estadoCivil"       => $_POST['estadoCivil'] ?? null,
    "departamento"      => $_POST['departamento'] ?? null,
    "provincia"         => $_POST['provincia'] ?? null,
    "distrito"          => $_POST['distrito'] ?? null,
    "cuotaInicial"      => $_POST['cuota_inicial'] ?? null,
    "plazoAnios"        => $_POST['plazoAnios'] ?? null,
);

// Verificando a cual accion tomar
switch ($accion) {
    case "insertar":
        // Insertar
        insertar($pdo, $tabla, $datos);
        break;
    case "calcular":
        // Calcular
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
    $sql = $pdo->prepare("SELECT * FROM $tabla c INNER JOIN deposito_plazo_fijo dp ON c.cliente_id = dp.id_cliente  INNER JOIN seguros s ON dp.id_deposito_plazo_fijo = s.id_deposito_plazo_fijo WHERE c.dni = :dni");
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



function calculosCredito($datos) {
    $cuotaInicial = isset($datos['cuotaInicial']) ? (float)$datos['cuotaInicial'] : 0;
    $plazoAnios = isset($datos['plazoAnios']) ? (int)$datos['plazoAnios'] : 0;
    $tipoSeguro = isset($datos['tipoSeguro']) ? (int)$datos['tipoSeguro'] : null;

    // Verificar y asignar la tasa de interés (tea) según el plazo
    if ($plazoAnios >= 1 && $plazoAnios <= 5) {
        $tea = 0.03; // 3% anual
    } elseif ($plazoAnios >= 6 && $plazoAnios <= 10) {
        $tea = 0.04; // 4% anual
    } elseif ($plazoAnios > 10) {
        $tea = 0.05; // 5% anual
    } else {
        $tea = 0; // No se aplica interés para plazo 0 o negativo
    }

    // Verificar si $tea es un número válido
    if (!is_numeric($tea)) {
        $tea = 0.03; // Asignar valor predeterminado si no es numérico
    }

    // Verificar el tipo de seguro y asignar su valor (si el tipo de seguro es 5)
    $seguro = 0;
    if ($tipoSeguro == 5) {
        $seguro = 0.077; // Valor del seguro
    }

    $cronograma_pagos = [];
    $capitalActual = $cuotaInicial; // Inicializamos el capital con el monto ingresado

    // Inicializar las variables para el total de intereses y seguro
    $totalIntereses = 0;
    $totalSeguro = 0;

    // Generar cronograma de pagos por cada año
    for ($i = 0; $i <= $plazoAnios; $i++) {
        // Calcular el interés anual, solo después del primer año
        $interesAnual = $i > 0 ? round($capitalActual * $tea, 2) : 0;
        $capitalFinal = round($capitalActual + $interesAnual + $seguro, 2); // Agregar el seguro al capital final

        // Sumar los intereses y el seguro acumulados
        $totalIntereses += $interesAnual;
        $totalSeguro += $seguro;

        // Guardar el cronograma de pagos
        $cronograma_pagos[] = [
            'año' => $i,
            'capital_inicial' => round($capitalActual, 2),
            'tasa_interes' => $i > 0 ? ($tea * 100) . '%' : '-', // Mostrar tasa solo para años mayores a 0
            'interes_anual' => $interesAnual,
            'seguro' => $seguro, // Agregar seguro
            'capital_final' => $capitalFinal,
        ];

        // Actualizamos el capital para el siguiente año
        $capitalActual = $capitalFinal;
    }

    // Almacenar los datos en la sesión
    $_SESSION['datos_credito'] = $datos;
    $_SESSION['cronograma_pagos'] = $cronograma_pagos;
    $_SESSION['totalIntereses'] = $totalIntereses;
    $_SESSION['totalSeguro'] = $totalSeguro;

    // Respuesta en formato JSON con los resultados del cronograma y los totales
    $msg = array(
        "tipo"  => "success",
        "texto" => "plazo_fijo/confirmacion_credito.php",
        "totalIntereses" => round($totalIntereses, 2),
        "totalSeguro" => round($totalSeguro, 2)
    );

    echo json_encode($msg);
    return;
}


function insertar($pdo, $tabla, $datos)
{
    try {
        // Verificación de los datos iniciales
        if (!$pdo || !$tabla || empty($datos)) {
            throw new InvalidArgumentException("Parámetros inválidos: Verifica la conexión PDO, la tabla y los datos proporcionados.");
        }

        // Asignar valores con validaciones
        $cuotaInicial = isset($datos['cuotaInicial']) ? (float)$datos['cuotaInicial'] : 0;
        $plazoAnios = isset($datos['plazoAnios']) ? (int)$datos['plazoAnios'] : 0;
        $tipoSeguro = isset($datos['tipoSeguro']) ? (int)$datos['tipoSeguro'] : null;

        // Calcular TEA
        $tea = $plazoAnios > 10 ? 0.05 : ($plazoAnios > 5 ? 0.04 : 0.03);

        // Iniciar transacción
        $pdo->beginTransaction();

        // Primera inserción: Datos del cliente
        $sqlCliente = "
            INSERT INTO $tabla(
                nombre, apellidos, dni, telefono, correo, fecha_nacimiento, tipo_ingreso, 
                ingreso_mensual, estado_civil_id, departamento_id, provincia_id, distrito_id, direccion
            ) 
            VALUES (
                :nombre, :apellidos, :dni, :telefono, :correo, :fechaNacimiento, :tipoIngreso, 
                :ingresoMensual, :estadoCivil, :departamento, :provincia, :distrito, :direccion
            )
        ";

        $stmtCliente = $pdo->prepare($sqlCliente);
        $stmtCliente->execute([
            ':nombre' => $datos['nombre'],
            ':apellidos' => $datos['apellidos'],
            ':dni' => $datos['dni'],
            ':telefono' => $datos['telefonoCliente'],
            ':correo' => $datos['emailCliente'],
            ':fechaNacimiento' => $datos['fechaNacimiento'],
            ':tipoIngreso' => null,
            ':ingresoMensual' => $datos['ingresoMensual'],
            ':estadoCivil' => $datos['estadoCivil'],
            ':departamento' => $datos['departamento'],
            ':provincia' => $datos['provincia'],
            ':distrito' => $datos['distrito'],
            ':direccion' => $datos['direccionCliente']
        ]);

        $cliente_id = $pdo->lastInsertId();

        // Segunda inserción: Datos del depósito
        $sqlCredito = "
            INSERT INTO deposito_plazo_fijo (
                id_cliente, tipo_seguro, monto_deposito, plazo, tea
            ) 
            VALUES (
                :id_cliente, :tipo_seguro, :montoDeposito, :plazo, :tea
            )
        ";

        $stmtCredito = $pdo->prepare($sqlCredito);
        $stmtCredito->execute([
            ':id_cliente' => $cliente_id,
            ':tipo_seguro' => $tipoSeguro,
            ':montoDeposito' => $cuotaInicial,
            ':plazo' => $plazoAnios,
            ':tea' => $tea
        ]);

        $credito_id = $pdo->lastInsertId();

        // Tercera inserción: Datos del seguro
        $cost = $tipoSeguro === 5 ? 0.077 : 0;

        $sqlSeguro = "
            INSERT INTO seguros (
                tipo_seguro_id, id_credito_hipotecario, id_credito_vehicular, 
                id_credito_estudio, id_deposito_plazo_fijo, costo
            ) 
            VALUES (
                :tipo_seguro_id, NULL, NULL, NULL, :id_deposito_plazo_fijo, :costo
            )
        ";

        $stmtSeguro = $pdo->prepare($sqlSeguro);
        $stmtSeguro->execute([
            ':tipo_seguro_id' => $tipoSeguro,
            ':id_deposito_plazo_fijo' => $credito_id,
            ':costo' => $cost
        ]);

        // Confirmar transacción
        $pdo->commit();

        $msg = array(
            "tipo"  => "success",
            "texto" => "Datos insertados satisfactoriamente."
        );
    } catch (PDOException $e) {
        // Verificar si hay transacción activa antes de revertir
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $msg = array(
            "tipo"  => "error",
            "texto" => "Error en la base de datos: " . $e->getMessage(),
            "trace" => $e->getTraceAsString()
        );
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $msg = array(
            "tipo"  => "error",
            "texto" => "Error general: " . $e->getMessage(),
            "trace" => $e->getTraceAsString()
        );
    }

    // Devolver el mensaje
    echo json_encode($msg);
    return;
}

function verCronograma($pdo, $tabla, $datos)
{
    try {
        // Validar parámetros básicos
        if (!$pdo || !$tabla || empty($datos['dni'])) {
            throw new InvalidArgumentException("Parámetros inválidos: Verifica la conexión PDO, la tabla y el DNI.");
        }

        // Consultar los datos del cliente
        $sql = $pdo->prepare("
            SELECT * 
            FROM $tabla c 
            INNER JOIN deposito_plazo_fijo dp ON c.cliente_id = dp.id_cliente
            INNER JOIN seguros s ON dp.id_deposito_plazo_fijo = s.id_deposito_plazo_fijo
            WHERE c.dni = :dni
        ");
        $sql->bindParam(':dni', $datos['dni']);
        $sql->execute();

        $cliente = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$cliente) {
            throw new Exception("No se encontraron datos para el cliente con DNI: {$datos['dni']}");
        }

        // Cálculos iniciales
        $cuotaInicial = isset($cliente['monto_deposito']) ? (float)$cliente['monto_deposito'] : 0;
        $plazoAnios = isset($cliente['plazo']) ? (int)$cliente['plazo'] : 0;
        $tipoSeguro = isset($cliente['tipo_seguro']) ? (int)$cliente['tipo_seguro'] : null;

        // Calcular la tasa de interés anual (TEA) según el plazo
        $tea = 0; // Valor predeterminado
        if ($plazoAnios >= 1 && $plazoAnios <= 5) {
            $tea = 0.03; // 3%
        } elseif ($plazoAnios >= 6 && $plazoAnios <= 10) {
            $tea = 0.04; // 4%
        } elseif ($plazoAnios > 10) {
            $tea = 0.05; // 5%
        }

        // Asignar el valor del seguro
        $seguro = ($tipoSeguro == 5) ? 0.077 : 0;

        // Inicializar variables para el cronograma
        $cronograma_pagos = [];
        $capitalActual = $cuotaInicial; // Capital inicial

        $totalIntereses = 0;
        $totalSeguro = 0;

        // Generar cronograma de pagos
        for ($i = 1; $i <= $plazoAnios; $i++) {
            $interesAnual = round($capitalActual * $tea, 2); // Calcular interés
            $capitalFinal = round($capitalActual + $interesAnual + $seguro, 2); // Agregar seguro al capital final

            // Sumar totales acumulados
            $totalIntereses += $interesAnual;
            $totalSeguro += $seguro;

            // Agregar al cronograma
            $cronograma_pagos[] = [
                'año' => $i,
                'capital_inicial' => round($capitalActual, 2),
                'tasa_interes' => ($tea * 100) . '%',
                'interes_anual' => $interesAnual,
                'seguro' => $seguro,
                'capital_final' => $capitalFinal,
            ];

            $capitalActual = $capitalFinal; // Actualizar capital para el siguiente año
        }

        // Guardar datos en la sesión
        $_SESSION['datos_credito'] = $cliente;
        $_SESSION['cronograma_pagos'] = $cronograma_pagos;
        $_SESSION['totalIntereses'] = round($totalIntereses, 2);
        $_SESSION['totalSeguro'] = round($totalSeguro, 2);

        // Devolver respuesta exitosa
        echo json_encode([
            "tipo" => "success",
            "texto" => "plazo_fijo/confirmacion_credito.php",
            "totalIntereses" => round($totalIntereses, 2),
            "totalSeguro" => round($totalSeguro, 2)
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "tipo" => "error",
            "texto" => "Error en la base de datos: " . $e->getMessage()
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "tipo" => "error",
            "texto" => $e->getMessage()
        ]);
    }
}


