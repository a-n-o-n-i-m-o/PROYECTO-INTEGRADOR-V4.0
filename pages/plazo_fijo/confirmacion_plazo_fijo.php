<?php require_once "../../config/config.php"; ?>
<!DOCTYPE html>
<html lang='es'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='https://fonts.googleapis.com/css2?family=KoHo&display=swap' rel='stylesheet'>
    <style>
        /* Estilos existentes */
        body {
            font-family: 'KoHo', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header,
        footer {
            background-color: #002f6c;
            color: white;
            text-align: center;
            padding: 15px 0;
        }

        header h1,
        footer p {
            margin: 0;
        }

        h2,
        h3,
        h4 {
            color: #002f6c;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #FF4500;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .summary {
            margin-top: 20px;
            background-color: #f4f4f4;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .summary p {
            font-size: 16px;
            color: #333;
            margin: 5px 0;
        }

        .buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .buttons button {
            padding: 10px 25px;
            font-size: 16px;
            margin: 10px;
            border: none;
            cursor: pointer;
            color: white;
            background-color: #28a745;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .buttons button.cancel {
            background-color: #dc3545;
        }

        .buttons button:hover {
            opacity: 0.9;
        }
    </style>

    <header>
        <h1>Cronograma de Pagos</h1>
    </header>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Detalles del Crédito</h2>
        <table class="table table-bordered table-striped mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Año</th>
                    <th>Capital Inicial</th>
                    <th>Tasa de Interés</th>
                    <th>Interés</th>
                    <th>Capital Final</th>
                    <th>Seguro</th> <!-- Nueva columna para el seguro -->
                </tr>
            </thead>
            <tbody>
                <?php 
                    $totalIntereses = 0;
                    $totalSeguro = 0;
                    foreach ($_SESSION['cronograma_pagos'] as $pago): 
                        $totalIntereses += $pago['interes_anual'];
                        $totalSeguro += $pago['seguro'];
                ?>
                    <tr>
                        <td><?= $pago['año']; ?></td>
                        <td><?= number_format($pago['capital_inicial'], 2); ?></td>
                        <td><?= $pago['tasa_interes']; ?></td>
                        <td><?= number_format($pago['interes_anual'], 2); ?></td>
                        <td><?= number_format($pago['capital_final'], 2); ?></td>
                        <td><?= number_format($pago['seguro'], 2); ?></td> <!-- Mostrar seguro -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class='summary'>
            <h4>Resumen</h4>
            <p>Cuota Inicial: S/ <?= number_format($_SESSION['datos_credito']['cuotaInicial'], 2); ?></p>
            <p>Total de Intereses Generados: S/ <?= number_format($totalIntereses, 2); ?></p>
            <p>Total de Seguro Generado: S/ <?= number_format($totalSeguro, 2); ?></p>
        </div>

        <div class='buttons'>
        <form id="formCredito">
            <input type="hidden" name="nombre" value="<?php echo $_SESSION['datos_credito']['nombre']; ?>">
            <input type="hidden" name="apellidos" value="<?php echo $_SESSION['datos_credito']['apellidos']; ?>">
            <input type="hidden" name="dni" value="<?php echo $_SESSION['datos_credito']['dni']; ?>">
            <input type="hidden" name="fechaNacimiento" value="<?php echo $_SESSION['datos_credito']['fechaNacimiento']; ?>">
            <input type="hidden" name="direccion" value="<?php echo $_SESSION['datos_credito']['direccionCliente']; ?>">
            <input type="hidden" name="telefono" value="<?php echo $_SESSION['datos_credito']['telefonoCliente']; ?>">
            <input type="hidden" name="correo" value="<?php echo $_SESSION['datos_credito']['emailCliente']; ?>">
            <input type="hidden" name="ingresoMensual" value="<?php echo $_SESSION['datos_credito']['ingresoMensual']; ?>">
            <input type="hidden" name="cuota_inicial" value="<?php echo $_SESSION['datos_credito']['cuotaInicial']; ?>">
            <input type="hidden" name="plazoAnios" value="<?php echo $_SESSION['datos_credito']['plazoAnios']; ?>">
            <input type="hidden" name="tipoSeguro" value="<?php echo $_SESSION['datos_credito']['tipoSeguro']; ?>">
            <input type="hidden" name="estadoCivil" value="<?php echo $_SESSION['datos_credito']['estadoCivil']; ?>">
            <input type="hidden" name="departamento" value="<?php echo $_SESSION['datos_credito']['departamento']; ?>">
            <input type="hidden" name="provincia" value="<?php echo $_SESSION['datos_credito']['provincia']; ?>">
            <input type="hidden" name="distrito" value="<?php echo $_SESSION['datos_credito']['distrito']; ?>">

            <button type="submit">Confirmar Proceso</button>
            <button type="button" onclick="history.back()" class="cancel">Cancelar</button>
        </form>

        <form method="POST" action="../../../SISTEMA-FINANCIERO/controller/generar_pdf_p.php" target="_blank">
            <input type="hidden" name="cronograma" value='<?php echo htmlspecialchars(serialize($_SESSION['cronograma_pagos'])); ?>'>
            <button type="submit" name="generar_pdf">Generar PDF</button>
        </form>
        </div>
    </div>

    <?php include "../../modules/footer.php" ?>

    <script>
        //INICIALIZAR
        const base_url = "<?php echo CONTROLLERS . 'Plazo_FijoController.php'; ?>";

        $("#formCredito").submit(function(e) {
            e.preventDefault();

            let data = new FormData(e.target);
            data.append("accion", "insertar");

                $.ajax({
                    type: "POST",
                    url: base_url,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let res = JSON.parse(response);

                        if (res.tipo == "success") {
                            alert("Se registro con éxito");
                            window.location.href = "<?php echo PAGES . 'plazo_fijo/formulario_plazo.php'; ?>";
                        } else {
                            alert(res.texto);
                        }
                    }
                });
        
        });
    </script>
</body>

</html>
