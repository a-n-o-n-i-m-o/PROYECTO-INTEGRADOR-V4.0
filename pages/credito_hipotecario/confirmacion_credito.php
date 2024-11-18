<?php
session_start();
if (!isset($_SESSION['calculo_credito'])) {
    die("No se han realizado cálculos aún.");
}

$datosCredito = $_SESSION['calculo_credito'];
?>

<h2>Resultados del Crédito</h2>
<p>Cuota Inicial: S/ <?= $datosCredito['cuota_inicial']; ?></p>
<p><?php $datosCredito['cuota_inicial']; ?></p> b

<p>Monto Capital: S/ <?= $datosCredito['monto_capital']; ?></p>
<p>TEM: <?= $datosCredito['tem']; ?></p>
<p>Cuota Mensual: S/ <?= $datosCredito['cuota_mensual']; ?></p>
<p>Monto Total a Pagar: S/ <?= $datosCredito['monto_total_pagar']; ?></p>
<h3>Cronograma de Pagos</h3>
<table border="1">
    <thead>
        <tr>
            <th>Nro. Mes</th>
            <th>Aporte Capital</th>
            <th>Interes Mensual</th>
            <th>Cuota Mensual</th>
            <th>Saldo Capital</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($datosCredito['cronograma'] as $pago): ?>
        <tr>
            <td><?= $pago['nro_mes']; ?></td>
            <td>S/ <?= $pago['aporte_capital']; ?></td>
            <td>S/ <?= $pago['interes_mensual']; ?></td>
            <td>S/ <?= $pago['cuota_mensual']; ?></td>
            <td>S/ <?= $pago['saldo_capital']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
