<!-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Crédito Vehicular</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h1>Formulario de Crédito Universitario</h1>
        </div>
        <div class="card-content">
            <form class="form" action="generarCronograma.php" method="post">
                <section>
                    <h2>Datos Personales del Solicitante</h2>
                    <div class="grid">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="name" placeholder="Ingrese su nombre" value="Juan Pérez" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" id="apellido" name="apellido" class="name" placeholder="Ingrese su apellido" value="García" required>
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI</label>
                            <input type="text" id="dni" name="dni" class="name" placeholder="Ingrese su DNI" value="12345678" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" class="name" placeholder="Ingrese su teléfono" value="987654321" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" name="email" class="name" placeholder="Ingrese su correo electrónico" value="correo@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" id="direccion" name="direccion" class="name" placeholder="Ingrese su dirección" value="Av. Siempre Viva 742" required>
                        </div>
                    </div>
                </section>

                <section>
                    <h2>Información Académica</h2>
                    <div class="form-group">
                        <label for="universidad">Universidad</label>
                        <input type="text" id="universidad" name="universidad" placeholder="Ingrese su universidad" value="Universidad Nacional">
                    </div>
                    <div class="form-group">
                        <label for="carrera">Carrera</label>
                        <input type="text" id="carrera" name="carrera" placeholder="Ingrese su carrera" value="Ingeniería de Sistemas">
                    </div>
                    <div class="form-group">
                        <label for="ciclo">Ciclo Actual</label>
                        <select id="ciclo" name="ciclo" class="name" required>
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
                </section>

                <section>
                    <h2>Información Financiera del Solicitante (Opcional)</h2>
                    <div class="form-group">
                        <label for="ingresos">Ingresos Mensuales (S/)</label>
                        <input type="number" id="ingresos" name="ingresos" placeholder="Ingrese sus ingresos mensuales" value="2500">
                    </div>
                    <div class="form-group">
                        <label for="gastos">Gastos Mensuales (S/)</label>
                        <input type="number" id="gastos" name="gastos" placeholder="Ingrese sus gastos mensuales" value="1500">
                    </div>
                    <div class="form-group">
                        <label for="deudas">Deudas Actuales (S/)</label>
                        <input type="number" id="deudas" name="deudas" placeholder="Ingrese sus deudas actuales" value="500">
                    </div>
                </section>
                
                <section>
                    <h2>Crédito Universitario</h2>
                    <div class="form-group">
                        <label for="monto">Monto del Préstamo (S/)</label>
                        <input type="number" id="monto" name="monto" class="name" placeholder="Ingrese el monto del préstamo" value="20000" required>
                    </div>
                    <div class="form-group">
                        <label for="cuotaInicial">Cuota Inicial (S/)</label>
                        <input type="number" id="cuotaInicial" name="cuotaInicial" class="name" placeholder="Ingrese la cuota inicial" value="3000" required>
                    </div>
                    <div class="form-group">
                        <label for="plazo">Plazo de Crédito (meses)</label>
                        <select id="plazo" name="plazo" class="name" required>
                            <option value="" disabled>Seleccione el plazo</option>
                            <option value="12" selected>12 meses</option>
                            <option value="24">24 meses</option>
                            <option value="36" >36 meses</option>
                            <option value="48">48 meses</option>
                            <option value="60">60 meses</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tea">TEA (%)</label>
                        <input type="number" id="tea" name="tea" class="name" placeholder="Ingrese la Tasa Efectiva Anual" value="12" required>
                    </div>
                    <div class="form-group">
                        <label for="seguroPorcentaje">Porcentaje Seguro de Desgravamen (%)</label>
                        <input type="number" id="seguroPorcentaje" name="seguroPorcentaje" placeholder="Ingrese el porcentaje">
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="seguroDesgravamen" name="seguroDesgravamen">
                        <label for="seguroDesgravamen">Incluir Seguro de Desgravamen</label>
                    </div>
                </section>                             
                <button type="submit" class="submit-button">Enviar Solicitud</button>
            </form>
        </div>
    </div>
</body>
</html> -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Crédito Vehicular</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h1>Formulario de Crédito Vehicular</h1>
        </div>
        <div class="card-content">
            <form class="form" action="/app/controllers/generarCronograma.php" method="post">
                <section>
                    <h2>Datos Personales del Solicitante</h2>
                    <div class="grid">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="name" placeholder="Ingrese su nombre" value="Juan Pérez" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" id="apellido" name="apellido" class="name" placeholder="Ingrese su apellido" value="García" required>
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI</label>
                            <input type="text" id="dni" name="dni" class="name" placeholder="Ingrese su DNI" value="12345678" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" class="name" placeholder="Ingrese su teléfono" value="987654321" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" name="email" class="name" placeholder="Ingrese su correo electrónico" value="correo@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" id="direccion" name="direccion" class="name" placeholder="Ingrese su dirección" value="Av. Siempre Viva 742" required>
                        </div>
                    </div>
                </section>

                <section>
                    <h2>Información Académica</h2>
                    <div class="form-group">
                        <label for="universidad">Universidad</label>
                        <input type="text" id="universidad" name="universidad" placeholder="Ingrese su universidad" value="Universidad Nacional">
                    </div>
                    <div class="form-group">
                        <label for="carrera">Carrera</label>
                        <input type="text" id="carrera" name="carrera" placeholder="Ingrese su carrera" value="Ingeniería de Sistemas">
                    </div>
                    <div class="form-group">
                        <label for="ciclo">Ciclo Actual</label>
                        <select id="ciclo" name="ciclo" class="name" required>
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
                </section>

                <section>
                    <h2>Información Financiera del Solicitante (Opcional)</h2>
                    <div class="form-group">
                        <label for="ingresos">Ingresos Mensuales (S/)</label>
                        <input type="number" id="ingresos" name="ingresos" placeholder="Ingrese sus ingresos mensuales" value="2500">
                    </div>
                    <div class="form-group">
                        <label for="gastos">Gastos Mensuales (S/)</label>
                        <input type="number" id="gastos" name="gastos" placeholder="Ingrese sus gastos mensuales" value="1500">
                    </div>
                    <div class="form-group">
                        <label for="deudas">Deudas Actuales (S/)</label>
                        <input type="number" id="deudas" name="deudas" placeholder="Ingrese sus deudas actuales" value="500">
                    </div>
                </section>
                
                <section>
                    <h2>Crédito Universitario</h2>
                    <div class="form-group">
                        <label for="monto">Monto del Préstamo (S/)</label>
                        <input type="number" id="monto" name="monto" class="name" placeholder="Ingrese el monto del préstamo" value="20000" required>
                    </div>
                    <div class="form-group">
                        <label for="cuotaInicial">Cuota Inicial (S/)</label>
                        <input type="number" id="cuotaInicial" name="cuotaInicial" class="name" placeholder="Ingrese la cuota inicial" value="3000" required>
                    </div>
                    <div class="form-group">
                        <label for="plazo">Plazo de Crédito (meses)</label>
                        <select id="plazo" name="plazo" class="name" required>
                            <option value="" disabled>Seleccione el plazo</option>
                            <option value="12" selected>12 meses</option>
                            <option value="24">24 meses</option>
                            <option value="36" >36 meses</option>
                            <option value="48">48 meses</option>
                            <option value="60">60 meses</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tea">TEA (%)</label>
                        <input type="number" id="tea" name="tea" class="name" placeholder="Insertar correctamente los valores previos" readonly>
                    </div>
                    <div class="form-group">
                        <label for="seguroPorcentaje">Porcentaje Seguro de Desgravamen (%)</label>
                        <input type="number" id="seguroPorcentaje" name="seguroPorcentaje" placeholder="0.077%" readonly>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="seguroDesgravamen" name="seguroDesgravamen">
                        <label for="seguroDesgravamen">Incluir Seguro de Desgravamen</label>
                    </div>
                </section>                             
                <button type="submit" class="submit-button">Enviar Solicitud</button>
            </form>
        </div>
    </div>

    <script src="/public/js/script.js"></script>

</body>
</html>