<?php 

require_once "../../config/config.php"; 




?>
<!DOCTYPE html>
<html lang='es'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='https://fonts.googleapis.com/css2?family=KoHo&display=swap' rel='stylesheet'>
    <style>
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

    <table>
        <tr>
            <th>Nro. Mes</th>
            <th>Mes</th>
            <th>Monto Capital (S/)</th>
            <th>Interés (S/)</th>
            <th>Cuota Mensual (S/)</th>
            <th>Saldo Capital (S/)</th>
            <th>Seguro (S/)</th>
        </tr>
        <?php $nroMes=1; ?>
        <?php foreach ($_SESSION['cronograma_pagos'] as $pago) { ?>
            <tr>
                <td><?= $nroMes++; ?></td>
                <td><?php echo $pago['mes']; ?></td>
                <td>S/ <?php echo $pago['monto_capital']; ?></td>
                <td>S/ <?php echo $pago['interes']; ?> </td>
                <td>S/ <?php echo $pago['cuota_mensual']; ?></td>
                <td>S/ <?php echo $pago['saldo_capital']; ?> </td>
                <td>S/ <?php echo $pago['seguro']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <div class='summary'>
        <h4>Monto Total a Pagar: S/ <?php echo $_SESSION['montoTotal']; ?></h4>
        <h4>Desglose del Monto Total</h4>
        <p>Cuota Inicial: S/ <?php echo $_SESSION['cuotaInicialSoles']; ?></p>
        <p>Total de Cuotas Mensuales: S/ <?php echo $_SESSION['totalCapitalPagado']; ?></p>
        <p>Total de Intereses Generados: S/ <?php echo $_SESSION['interesTotal']; ?></p>
        <p>Total de Seguro Generado: S/ <?php echo $_SESSION['totalSeguroGenerado']; ?></p>
    </div>
    <div class='buttons'>
        <button type="button" onclick="history.back()" class="cancel">Regresar</button>

        <form method="POST" action="../../../SISTEMA-FINANCIERO/controller/generar_pdf_v.php" target="_blank">
            <input type="hidden" name="cronograma" value='<?php echo htmlspecialchars(serialize($_SESSION['cronograma_pagos'])); ?>'>
            <button type="submit" name="generar_pdf">Generar PDF</button>
        </form>
    </div>

    <?php include "../../modules/footer.php" ?>