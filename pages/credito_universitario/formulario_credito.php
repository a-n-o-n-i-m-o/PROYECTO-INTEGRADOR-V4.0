<?php


require_once "../../config/config.php";


$sqlSeguro = "SELECT * FROM tipo_seguros";

$seguros = $pdo->query($sqlSeguro)->fetchAll(PDO::FETCH_ASSOC);

$sqlDistrito = "SELECT * FROM distritos";

$distritos = $pdo->query($sqlDistrito)->fetchAll(PDO::FETCH_ASSOC);


$sqlProvincias = "SELECT * FROM provincias";

$provincias = $pdo->query($sqlProvincias)->fetchAll(PDO::FETCH_ASSOC);


$sqlDepartamentos = "SELECT * FROM departamentos";

$departamentos = $pdo->query($sqlDepartamentos)->fetchAll(PDO::FETCH_ASSOC);


$sqlEstado_civil = "SELECT * FROM estado_civil";

$estado_civil = $pdo->query($sqlEstado_civil)->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=KoHo&display=swap" rel="stylesheet">
    <title>Formulario de Crédito Vehicular</title>
    <style>
        body {
            font-family: 'KoHo', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header, footer {
            background-color: #002f6c;
            color: white;
            text-align: center;
            padding: 15px 0;
        }

        header h1, footer p {
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #FF4500;
            margin-bottom: 30px;
            font-size: 1.8rem;
        }

        .form-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-column {
            flex: 1;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .form-column h3 {
            text-align: center;
            color: #FF4500;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #002f6c;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #fff;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input:focus,
        select:focus {
            border-color: #FF4500;
            outline: none;
        }

        .credit-section {
            padding: 20px;
        }

        .credit-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .credit-row label {
            width: 45%;
        }

        .credit-row input,
        .credit-row select {
            width: 50%;
        }

        .slider-container {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .slider-container input {
            flex: 1;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #FF4500;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e03d00;
        }

        footer {
            margin-top: 40px;
        }

        footer p {
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Formulario de Crédito Vehicular</h1>
    </header>
    <div class="container">
        <form class="form" action="/app/controllers/generarCronograma.php" method="post">
            <section>
                <h2>Datos Personales del Solicitante</h2>
                <div class="form-section">
                    <div class="form-column">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ingrese su nombre" required>

                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" placeholder="Ingrese su apellido"  required>

                        <label for="dni">DNI</label>
                        <input type="text" id="dni" name="dni" placeholder="Ingrese su DNI" required>
                        
                        <label for="telefono">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" placeholder="Ingrese su n°telefono" required pattern="^\d{9}$" title="Debe tener exactamente 9 dígitos">

                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" placeholder="Ingrese su correo electrónico" required>

                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" placeholder="Ingrese su dirección" required>
                    </div>
                </div>
            </section>

            <section>
                <h2>Información Académica</h2>
                <div class="form-section">
                    <div class="form-column">
                        <label for="universidad">Universidad</label>
                        <input type="text" id="universidad" name="universidad" placeholder="Ingrese su universidad">

                        <label for="carrera">Carrera</label>
                        <input type="text" id="carrera" name="carrera" placeholder="Ingrese su carrera">

                        <label for="ciclo">Ciclo Actual</label>
                        <select id="ciclo" name="ciclo" required>
                            <option value="" disabled selected>Seleccione el ciclo</option>
                            <option value="1" selected>Ciclo 1</option>
                            <option value="2">Ciclo 2</option>
                            <option value="3">Ciclo 3</option>
                            <option value="4">Ciclo 4</option>
                            <option value="5">Ciclo 5</option>
                            <option value="6">Ciclo 6</option>
                            <option value="7">Ciclo 7</option>
                            <option value="8">Ciclo 8</option>
                            <option value="9">Ciclo 9</option>
                            <option value="10">Ciclo 10</option>
                        </select>
                    </div>
                </div>
            </section>

            <section>
                <h2>Información Financiera del Solicitante (Opcional)</h2>
                <div class="form-section">
                    <div class="form-column">
                        <label for="ingresos">Ingresos Mensuales (S/)</label>
                        <input type="number" id="ingresos" name="ingresos" placeholder="Ingrese sus ingresos mensuales">

                        <label for="gastos">Gastos Mensuales (S/)</label>
                        <input type="number" id="gastos" name="gastos" placeholder="Ingrese sus gastos mensuales">

                        <label for="deudas">Deudas Actuales (S/)</label>
                        <input type="number" id="deudas" name="deudas" placeholder="Ingrese sus deudas actuales">
                    </div>
                </div>
            </section>

            <section>
                <h2>Crédito Universitario</h2>
                <div class="form-section">
                    <div class="form-column">
                        <label for="monto">Monto del Préstamo (S/)</label>
                        <input type="number" id="monto" name="monto" placeholder="Ingrese el monto del préstamo" required>

                        <label for="cuotaInicial">Cuota Inicial (S/)</label>
                        <input type="number" id="cuotaInicial" name="cuotaInicial" placeholder="Ingrese la cuota inicial" required>

                        <label for="plazo">Plazo de Crédito (meses)</label>
                        <select id="plazo" name="plazo" required>
                            <option>Seleccione el plazo</option>
                            <option value="12">12 meses</option>
                            <option value="24">24 meses</option>
                            <option value="36">36 meses</option>
                            <option value="48">48 meses</option>
                            <option value="60">60 meses</option>
                        </select>

                        <label for="tea">TEA (%)</label>
                        <input type="number" id="tea" name="tea" placeholder="Insertar correctamente los valores previos">
                        <input type="number" id="seguroPorcentaje" name="seguroPorcentaje" placeholder="Indique el porcentaje del seguro">

                        <label for="seguroPorcentaje">Porcentaje del Seguro</label>
                        <input type="number" id="seguroPorcentaje" name="seguroPorcentaje" placeholder="Indique el porcentaje del seguro">

                        <div class="checkbox-group">
                        <input type="checkbox" id="seguroDesgravamen" name="seguroDesgravamen">
                        <label for="seguroDesgravamen">Incluir Seguro de Desgravamen</label>
                        </div>
                </div>
            </section>

            <button type="submit">Generar Cronograma</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Crédito Vehicular. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
