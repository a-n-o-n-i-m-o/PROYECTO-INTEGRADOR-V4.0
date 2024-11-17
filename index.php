<?php require_once "config/config.php"; ?>

<!-- Header -->
<?php include "modules/header.php" ?>

<!-- Captaciones Section -->
<section class="section section-captaciones">
    <h2 class="section-title">Captaciones</h2>
    <div class="cards-container">
        <div class="card">
            <h3 class="card-title">Crédito Vehicular</h3>
            <p class="card-text">Solicita tu crédito vehicular con las mejores tasas del mercado.</p>
            <a class="interest-button" href="<?php echo PAGES . "credito_vehicular/formulario_credito.php"; ?>">Me interesa</a>

        </div>

        <div class="card">
            <h3 class="card-title">Crédito Universitario</h3>
            <p class="card-text">Financia tus estudios con cuotas accesibles.</p>
            <a class="interest-button" href="<?php echo PAGES . "credito_universitario/formulario_credito.php"; ?>">Me interesa</a>
        </div>
        <div class="card">
            <h3 class="card-title">Crédito Hipotecario</h3>
            <p class="card-text">Compra la casa de tus sueños con facilidades de pago.</p>
            <a class="interest-button" href="<?php echo PAGES . "credito_vehicular/formulario_credito.php"; ?>">Me interesa</a>
        </div>
    </div>
</section>

<!-- Colocaciones Section -->
<section class="section section-colocaciones">
    <h2 class="section-title">Colocaciones</h2>
    <div class="cards-container">
        <div class="card">
            <h3 class="card-title">Plazo Fijo</h3>
            <p class="card-text">Ahorra con tasas preferenciales y asegura tu futuro.</p>
            <a class="interest-button" href="<?php echo PAGES . "credito_vehicular/formulario_credito.php"; ?>">Me interesa</a>
        </div>
    </div>
</section>

<!-- Footer -->
<?php include "modules/footer.php" ?>

</body>

</html>