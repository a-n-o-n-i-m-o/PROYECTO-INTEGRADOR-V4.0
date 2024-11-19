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

        // Calcular la tasa de interés (TEA) según el plazo
        if ($plazoAnios > 0) {
            if ($plazoAnios <= 5) {
                $tea = 0.03; // 3% anual
            } elseif ($plazoAnios <= 10) {
                $tea = 0.04; // 4% anual
            } else {
                $tea = 0.05; // 5% anual
            }
        } else {
            $tea = 0; // Sin interés si el plazo no es válido
        }

        // Asignar el valor del seguro si aplica
        $seguro = ($tipoSeguro == 5) ? 0.077 : 0;

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

        // Asignación de valores
        $stmtCliente->bindParam(':nombre', $datos['nombre']);
        $stmtCliente->bindParam(':apellidos', $datos['apellidos']);
        $stmtCliente->bindParam(':dni', $datos['dni']);
        $stmtCliente->bindParam(':telefono', $datos['telefonoCliente']);
        $stmtCliente->bindParam(':correo', $datos['emailCliente']);
        $stmtCliente->bindParam(':fechaNacimiento', $datos['fechaNacimiento']);
        $stmtCliente->bindValue(':tipoIngreso', null, PDO::PARAM_NULL); // Cambiar si es necesario
        $stmtCliente->bindParam(':ingresoMensual', $datos['ingresoMensual']);
        $stmtCliente->bindParam(':estadoCivil', $datos['estadoCivil']);
        $stmtCliente->bindParam(':departamento', $datos['departamento']);
        $stmtCliente->bindParam(':provincia', $datos['provincia']);
        $stmtCliente->bindParam(':distrito', $datos['distrito']);
        $stmtCliente->bindParam(':direccion', $datos['direccionCliente']);

        if (!$stmtCliente->execute()) {
            throw new Exception("Error al insertar cliente: " . json_encode($stmtCliente->errorInfo()));
        }

        $cliente_id = $pdo->lastInsertId();

        // Segunda inserción: Datos del crédito hipotecario
        $sqlCredito = "
            INSERT INTO deposito_plazo_fijo (
                id_cliente, tipo_seguro, monto_deposito, plazo, tea
            ) 
            VALUES (
                :id_cliente, :tipo_seguro, :montoDeposito, :plazo, :tea
            )
        ";

        $stmtCredito = $pdo->prepare($sqlCredito);
        $stmtCredito->bindParam(':id_cliente', $cliente_id);
        $stmtCredito->bindParam(':tipo_seguro', $tipoSeguro);
        $stmtCredito->bindParam(':montoDeposito', $cuotaInicial);
        $stmtCredito->bindParam(':plazo', $plazoAnios);
        $stmtCredito->bindParam(':tea', $tea);

        if (!$stmtCredito->execute()) {
            throw new Exception("Error al insertar crédito hipotecario: " . json_encode($stmtCredito->errorInfo()));
        }

        // Confirmar transacción
        $pdo->commit();

        // Mensaje de éxito
        $msg = array(
            "tipo"  => "success",
            "texto" => "Crédito creado satisfactoriamente."
        );
    } catch (PDOException $e) {
        $pdo->rollBack(); // Revertir cambios en caso de error
        $msg = array(
            "tipo"  => "error",
            "texto" => "Error en la base de datos: " . $e->getMessage(),
            "trace" => $e->getTraceAsString()
        );
    } catch (Exception $e) {
        $pdo->rollBack(); // Revertir cambios en caso de error
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

