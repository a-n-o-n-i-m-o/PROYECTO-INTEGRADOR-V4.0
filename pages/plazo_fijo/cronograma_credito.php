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
    <thead class="table-dark">
                <tr>
                    <th>Año</th>
                    <th>Capital Inicial</th>
                    <th>Tasa de Interés</th>
                    <th>Interés</th>
                    <th>Seguro</th>
                    <th>Capital Final</th> <!-- Nueva columna para el seguro -->
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
    
                <?php endforeach; ?>

                <?php foreach ($_SESSION['cronograma_pagos'] as $pago) { ?>
                    <tr>
                <td><?php echo $pago['año']; ?></td>
                <td>S/ <?php echo $pago['capital_inicial']; ?></td>
                <td>S/ <?php echo $pago['tasa_interes']; ?> </td>
                <td>S/ <?php echo $pago['interes_anual']; ?></td>
                <td>S/ <?php echo $pago['seguro']; ?> </td>
                <td>S/ <?php echo $pago['capital_final']; ?></td>
                </tr>
                     <?php } ?>
            </tbody>
    </table>

    <div class='buttons'>
        <button type="button" onclick="history.back()" class="cancel">Regresar</button>

        <form method="POST" action="../../../SISTEMA-FINANCIERO/controller/generar_pdf_p.php" target="_blank">
            <input type="hidden" name="cronograma" value='<?php echo htmlspecialchars(serialize($_SESSION['cronograma_pagos'])); ?>'>
            <button type="submit" name="generar_pdf">Generar PDF</button>
        </form>
    </div>

    <?php include "../../modules/footer.php" ?>