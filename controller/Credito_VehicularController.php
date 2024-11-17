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
    "montoVehiculo"     => $_POST['montoVehiculo'] ?? null,
    "marcaVehiculo"     => $_POST['marca'] ?? null,
    "modeloVehiculo"    => $_POST['modelo'] ?? null,
    "cuotaInicial"      => $_POST['porcentajeCuotaInicial'] ?? null,
    "plazo"             => $_POST['plazoMeses'] ?? null,
    "tipoSeguro"        => $_POST['tipoSeguro'] ?? null,
    "estadoCivil"       => $_POST['estadoCivil'] ?? null,
    "departamento"      => $_POST['departamento'] ?? null,
    "provincia"      => $_POST['provincia'] ?? null,
    "distrito"      => $_POST['distrito'] ?? null,
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
    $sql = $pdo->prepare("SELECT * FROM $tabla c INNER JOIN credito_vehicular cv ON c.cliente_id = cv.id_cliente  INNER JOIN seguros s ON cv.id_credito_vehicular = s.id_credito_vehicular WHERE c.dni = :dni");
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

    //Validar datos
    if ($datos['nombre'] === '' || $datos['apellidos'] === '' || $datos['dni'] === '' || $datos['fechaNacimiento'] === '' || $datos['direccionCliente'] === '' || $datos['telefonoCliente'] === '' || $datos['emailCliente'] === '' || $datos['ingresoMensual'] === '' || $datos['montoVehiculo'] === '' || $datos['marcaVehiculo'] === '' || $datos['modeloVehiculo'] === '' || $datos['cuotaInicial'] === '' || $datos['plazo'] === '' || $datos['tipoSeguro'] === '') {
        $msg = array(
            "tipo"  => "warning",
            "texto" => "Revisa que ninguno este en blanco"
        );
        echo json_encode($msg);
        return;
    }


    $cuotaInicialSoles = $datos['montoVehiculo'] * ($datos['cuotaInicial'] / 100);
    $montoCapital = $datos['montoVehiculo'] * (1 - ($datos['cuotaInicial'] / 100));
    $tea = 0.15;
    $tem = (pow(1 + $tea, 1.0 / 12)) - 1;
    $payment = $montoCapital * ($tem / (1 - pow(1 + $tem, -$datos['plazo'])));


    $sql = $pdo->prepare("INSERT INTO $tabla(nombre, apellidos, dni, telefono, correo, fecha_nacimiento, tipo_ingreso, ingreso_mensual, estado_civil_id, departamento_id, provincia_id, distrito_id, direccion) VALUES (:nombre, :apellidos, :dni, :telefono, :correo, :fechaNacimiento, :tipoIngreso, :ingresoMensual, :estadoCivil, :departamento, :provincia, :distrito, :direccion)");
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


    if ($sql->execute()) {

        $sql = "";

        $tabla2 = "credito_vehicular";
        $cliente_id = $pdo->lastInsertId();

        $sql = $pdo->prepare("INSERT INTO  $tabla2 (id_cliente,  tipo_seguro,  proveedor_seguro,  monto,  cuota_ini,  tea,  tem,  marca,  modelo, plazo_mes) VALUES (:id_cliente,  :tipo_seguro,  :proveedor_seguro,  :monto,  :cuota_ini,  :tea,  :tem,  :marca,  :modelo, :plazo_mes)");
        $sql->bindParam(':id_cliente', $cliente_id);
        $sql->bindParam(':tipo_seguro', $datos['tipoSeguro']);
        $sql->bindValue(':proveedor_seguro', null, PDO::PARAM_NULL);
        $sql->bindParam(':monto', $datos['montoVehiculo']);
        $sql->bindParam(':cuota_ini', $datos['cuotaInicial']);
        $sql->bindParam(':tea', $tea);
        $sql->bindParam(':tem', $tem);
        $sql->bindParam(':marca', $datos['marcaVehiculo']);
        $sql->bindParam(':modelo', $datos['modeloVehiculo']);
        $sql->bindParam(':plazo_mes', $datos['plazo']);

        if ($sql->execute()) {

            $sql = "";

            $tabla3 = "seguros";
            $credito_id = $pdo->lastInsertId();

            if (!empty($datos['tipoSeguro']) && $datos['tipoSeguro'] !== "1") {
                $cost = 0.00077;
            } else {
                $cost = 0;
            }

            $sql = $pdo->prepare("INSERT INTO  $tabla3 (tipo_seguro_id ,  id_credito_hipotecario,  id_credito_vehicular,  id_credito_estudio,  id_deposito_plazo_fijo,  costo) VALUES (:tipo_seguro_id,  :id_credito_hipotecario,  :id_credito_vehicular,  :id_credito_estudio,  :id_deposito_plazo_fijo,  :costo)");
            //parsear el tipo de seguro a entero
            $tipo_seguro = (int)$datos['tipoSeguro'];
            $sql->bindParam(':tipo_seguro_id', $tipo_seguro);
            $sql->bindValue(':id_credito_hipotecario', null, PDO::PARAM_NULL);
            $sql->bindParam(':id_credito_vehicular', $credito_id);
            $sql->bindValue(':id_credito_estudio', null, PDO::PARAM_NULL);
            $sql->bindValue(':id_deposito_plazo_fijo', null, PDO::PARAM_NULL);
            $sql->bindParam(':costo', $cost);

            if ($sql->execute()) {
                $msg = array(
                    "tipo"  => "success",
                    "texto" => "Credito creado satisfactoriamente"
                );
            } else {
                // Hubo un error en la ejecución
                $msg = array(
                    "tipo"  => "error",
                    "texto" => "Error al crear el credito: " . $sql->errorInfo()[2]
                );
            }
        } else {
            // Hubo un error en la ejecución
            $msg = array(
                "tipo"  => "error",
                "texto" => "Error al crear el credito: " . $sql->errorInfo()[2]
            );
        }
    } else {
        // Hubo un error en la ejecución
        $msg = array(
            "tipo"  => "error",
            "texto" => "Error al crear el credito: " . $sql->errorInfo()[2]
        );
    }

    echo json_encode($msg);
    return;
}


function calculosCredito($datos)
{
    //Validar datos
    if ($datos['nombre'] === '' || $datos['apellidos'] === '' || $datos['dni'] === '' || $datos['fechaNacimiento'] === '' || $datos['direccionCliente'] === '' || $datos['telefonoCliente'] === '' || $datos['emailCliente'] === '' || $datos['ingresoMensual'] === '' || $datos['montoVehiculo'] === '' || $datos['marcaVehiculo'] === '' || $datos['modeloVehiculo'] === '' || $datos['cuotaInicial'] === '' || $datos['plazo'] === '' || $datos['tipoSeguro'] === '') {
        $msg = array(
            "tipo"  => "warning",
            "texto" => "Revisa que ninguno este en blanco"
        );
        echo json_encode($msg);
        return;
    }

    // Cálculos

    $cuotaInicialSoles = $datos['montoVehiculo'] * ($datos['cuotaInicial'] / 100);
    $montoCapital = $datos['montoVehiculo'] * (1 - ($datos['cuotaInicial'] / 100));
    $tea = 0.15;
    $tem = (pow(1 + $tea, 1.0 / 12)) - 1;
    $payment = $montoCapital * ($tem / (1 - pow(1 + $tem, -$datos['plazo'])));

    $totalSeguroGenerado = 0;
    $interesTotal = 0;
    $totalCapitalPagado = 0;
    $cronograma_pagos = [];
    $fecha_inicio = new DateTime();

    for ($i = 0; $i < $datos['plazo']; $i++) {
        $interes = round($montoCapital * $tem, 2);
        $cuotaMensual = round($payment, 2);
        $capitalPagado = round($cuotaMensual - $interes, 2);
        $montoCapital -= $capitalPagado;
        $interesTotal += $interes;
        $totalCapitalPagado += $capitalPagado;
        $fecha_pago = clone $fecha_inicio;
        $fecha_pago->modify("+$i month");

        $seguro = 0;
        if (!empty($datos['tipoSeguro']) && $datos['tipoSeguro'] !== "1") {
            $seguro = round(0.00077 * $montoCapital, 2);
            $totalSeguroGenerado += $seguro; // Add to total insurance
        }

        $cronograma_pagos[] = [
            'mes' => $fecha_pago->format('Y-m'),
            'monto_capital' => $capitalPagado,
            'interes' => $interes,
            'cuota_mensual' => round($cuotaMensual + $seguro, 1),
            'saldo_capital' => round($montoCapital, 0),
            'seguro' => $seguro
        ];
    }

    $montoTotal = $cuotaInicialSoles + $totalCapitalPagado + $interesTotal + $totalSeguroGenerado;

    // Almacenar los datos en la sesión
    $_SESSION['datos_credito'] = $datos;
    $_SESSION['cronograma_pagos'] = $cronograma_pagos;
    $_SESSION['montoTotal'] = $montoTotal;
    $_SESSION['cuotaInicialSoles'] = $cuotaInicialSoles;
    $_SESSION['totalCapitalPagado'] = $totalCapitalPagado;
    $_SESSION['interesTotal'] = $interesTotal;
    $_SESSION['totalSeguroGenerado'] = $totalSeguroGenerado;

    // Hubo un error en la ejecución
    $msg = array(
        "tipo"  => "success",
        "texto" => "credito_vehicular/confirmacion_credito.php"
    );


    echo json_encode($msg);
    return;
}

function verCronograma($pdo, $tabla, $datos)
{

    $sql = $pdo->prepare("SELECT * FROM $tabla c INNER JOIN credito_vehicular cv ON c.cliente_id = cv.id_cliente  INNER JOIN seguros s ON cv.id_credito_vehicular = s.id_credito_vehicular WHERE c.dni = :dni");
    $sql->bindParam(':dni', $datos['dni']);

    $sql->execute();
    $cliente = $sql->fetch(PDO::FETCH_ASSOC);


    // Cálculos

    $cuotaInicialSoles = $cliente['monto'] * ($cliente['cuota_ini'] / 100);
    $montoCapital = $cliente['monto'] * (1 - ($cliente['cuota_ini'] / 100));
    $tea = 0.15;
    $tem = (pow(1 + $tea, 1.0 / 12)) - 1;
    $payment = $montoCapital * ($tem / (1 - pow(1 + $tem, - (int)$cliente['plazo_mes'])));

    $totalSeguroGenerado = 0;
    $interesTotal = 0;
    $totalCapitalPagado = 0;
    $cronograma_pagos = [];
    $fecha_inicio = new DateTime();

    for ($i = 0; $i < $cliente['plazo_mes']; $i++) {
        $interes = round($montoCapital * $tem, 2);
        $cuotaMensual = round($payment, 2);
        $capitalPagado = round($cuotaMensual - $interes, 2);
        $montoCapital -= $capitalPagado;
        $interesTotal += $interes;
        $totalCapitalPagado += $capitalPagado;
        $fecha_pago = clone $fecha_inicio;
        $fecha_pago->modify("+$i month");

        $seguro = 0;
        if (!empty($cliente['tipo_seguro']) && $cliente['tipo_seguro'] !== "1") {
            $seguro = round(0.00077 * $montoCapital, 2);
            $totalSeguroGenerado += $seguro; // Add to total insurance
        }

        $cronograma_pagos[] = [
            'mes' => $fecha_pago->format('Y-m'),
            'monto_capital' => $capitalPagado,
            'interes' => $interes,
            'cuota_mensual' => round($cuotaMensual + $seguro, 1),
            'saldo_capital' => round($montoCapital, 0),
            'seguro' => $seguro
        ];
    }

    $montoTotal = $cuotaInicialSoles + $totalCapitalPagado + $interesTotal + $totalSeguroGenerado;

    // Almacenar los datos en la sesión
    $_SESSION['datos_credito'] = $cliente;
    $_SESSION['cronograma_pagos'] = $cronograma_pagos;
    $_SESSION['montoTotal'] = $montoTotal;
    $_SESSION['cuotaInicialSoles'] = $cuotaInicialSoles;
    $_SESSION['totalCapitalPagado'] = $totalCapitalPagado;
    $_SESSION['interesTotal'] = $interesTotal;
    $_SESSION['totalSeguroGenerado'] = $totalSeguroGenerado;

    // Hubo un error en la ejecución
    $msg = array(
        "tipo"  => "success",
        "texto" => "credito_vehicular/confirmacion_credito.php"
    );


    echo json_encode($msg);
    return;
}
