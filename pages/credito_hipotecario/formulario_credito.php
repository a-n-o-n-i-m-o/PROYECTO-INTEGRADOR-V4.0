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
    <title>Solicitud de Crédito Hipotecario</title>
    <link href="https://fonts.googleapis.com/css2?family=KoHo&display=swap" rel="stylesheet">
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
        <h1>Credito Hipotecario</h1>
    </header>
    <div class="container">
        <h2>Formulario Crédito Hipotecario</h2>

        <form id="formFormulario" onsubmit="return verificarAdvertencia()">
            <!-- Información del cliente -->
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" required maxlength="8" inputmode="numeric" placeholder="Ingrese su DNI" pattern="^\d{8}$" title="Debe tener exactamente 8 dígitos y ser solo números" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)" />

            <label for="nombre">Nombres:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ingrese sus nombres" required pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" title="Solo se permiten letras" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" />

            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" placeholder="Ingrese sus apellidos" required pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" title="Solo se permiten letras" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" />


            <div class="mb-3">
                <label>Fecha de nacimiento:</label>
                <input type="date" class="form-control" name="fechaNacimiento" id="fechaNacimiento" required
                    onblur="validarEdad()">
                <small id="edadAdvertencia" class="form-text text-danger" style="display: none;">
                    Debe tener al menos 18 años para aplicar.
                </small>
            </div>

            <div class="mb-3">
                <label>Teléfono:</label>
                <input type="text" class="form-control" name="telefono" required pattern="9\d{8}"
                    maxlength="9" oninvalid="this.setCustomValidity('Ingrese un teléfono válido de 9 dígitos comenzando con 9.')"
                    oninput="this.setCustomValidity('')" title="Debe contener exactamente 9 dígitos y comenzar con 9">
            </div>
            <div class="mb-3">
                <label>Correo:</label>
                <input type="email" class="form-control" name="correo" required placeholder="example@correo.com">
            </div>
            <div class="mb-3">
                <label>Ingreso mensual:</label>
                <input type="number" class="form-control" step="0.01" name="ingresoMensual" id="ingresoMensual" min="1500" required
                    oninvalid="this.setCustomValidity('Para calificar, debe ganar al menos S/ 1500.')"
                    oninput="this.setCustomValidity('')" placeholder="Para calificar debe ganar más o igual a S/1500">
            </div>

            <label for="estadoCivil">Estado Civil:</label>
            <select id="estadoCivil" name="estadoCivil" required>
                <?php foreach ($estado_civil as $estado_civil) { ?>
                    <option value="<?php echo $estado_civil['estado_civil_id']; ?>"><?php echo $estado_civil['descripcion']; ?></option>
                <?php } ?>
            </select>

            <!-- <div class="mb-3">
                <label>Tipo de ingreso:</label>
                <select name="tipo_ingreso" class="form-control" required>
                    <option value="Dependiente">Dependiente</option>
                    <option value="Independiente">Independiente</option>
                    <option value="Mixto">Mixto</option>
                </select>
            </div> -->
            <div class="mb-3">
                <label>Dirección:</label>
                <input type="text" class="form-control" name="direccion" required>
            </div>
            <!-- Departamento, Provincia y Distrito -->
            <label for="departamento">Departamento:</label>
            <select id="departamento" name="departamento" required>
                <option>Seleccione un departamento</option>
                <?php foreach ($departamentos as $departamentos) { ?>
                    <option value="<?php echo $departamentos['departamento_id']; ?>"><?php echo $departamentos['nombre']; ?></option>
                <?php } ?>
            </select>

            <label for="provincia">Provincia:</label>
            <select id="provincia" name="provincia" required>
                <option>Seleccione una provincia</option>
            </select>

            <label for="distrito">Distrito:</label>
            <select id="distrito" name="distrito" required>
                <option>Seleccione un distrito</option>
            </select>

            <!-- Información del crédito hipotecario -->
            <div class="mb-3">
                <label>Monto del crédito (S/):</label>
                <input type="number" class="form-control" step="0.01" name="monto_credito" id="monto_credito" min="32000" required
                    oninput="calcularCuotaInicial(); calcularTasa();" placeholder="Puedes financiarte desde S/ 32,000">
            </div>
            <div class="mb-3">
                <label>Cuota inicial (10% del monto del crédito):</label>
                <input type="text" class="highlight" name="cuota_inicial" id="cuota_inicial" value="0.00">
            </div>

            <div class="mb-3">
                <label>Plazo del crédito (años):</label>
                <input type="number" class="form-control" name="plazo_credito" min="4" max="25" required
                    oninput="calcularTasa(); calcularCuotaMensual()" placeholder="De 4 a 25 años de plazo" id="plazo_credito">
            </div>

            <!-- Tipo de seguro -->
            <div class="credit-row">
                <label for="tipoSeguro">Tipo de Seguro:</label>
                <select id="tipoSeguro" name="tipo_seguro" required>
                    <?php foreach ($seguros as $seguro) { ?>
                        <?php if ($seguro['tipo_seguro_id'] == 1 && $seguro['tipo_seguro_id'] == 2) continue; ?>
                        <option value="<?php echo $seguro['tipo_seguro_id']; ?>"><?php echo $seguro['descripcion']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Porcentaje de cobro del seguro:</label>
                <input type="text" class="form-control" id="porcentaje_seguro" readonly>
            </div>

            <!-- <div class="mb-3">
                <label>Proveedor del seguro:</label>
                <input type="text" class="form-control" name="proveedor_seguro" value="Mapfre" readonly>
            </div> -->

            <!-- Tasa de interés -->
            <div class="mb-3">
                <label>Tasa de interés (%):</label>
                <input type="text" id="tasa_interes" name="tasa_interes">
            </div>

            <div class="mb-3 text-danger" id="advertenciaCuota" style="display:none;">
                La cuota mensual es igual o mayor al 50% de su ingreso mensual. Esto puede representar un riesgo financiero.
            </div>

            <button type="submit" class="btn btn-primary w-100">Enviar Solicitud</button>
            <button type="button"><a style="text-decoration : none; color:white;" href="<?php echo PAGES . "credito_hipotecario/consultas.php"; ?>">Consultar</a></button>
        </form>
    </div>
    <?php include "../../modules/footer.php" ?>

    <script>
        // Validación de la edad mínima de 18 años y otros scripts aquí
        function validarEdad() {
            const fechaNacimiento = new Date(document.getElementById('fechaNacimiento').value);
            const hoy = new Date();
            let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
            const mes = hoy.getMonth() - fechaNacimiento.getMonth();

            if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                edad--;
            }

            const advertencia = document.getElementById('edadAdvertencia');
            if (edad < 18) {
                advertencia.style.display = 'block';
                return false;
            } else {
                advertencia.style.display = 'none';
                return true;
            }
        }

        // Calcula y muestra la cuota inicial
        function calcularCuotaInicial() {
            const monto = parseFloat(document.getElementById('monto_credito').value) || 0;
            console.log(`Monto ingresado: ${monto}`);
            document.getElementById('cuota_inicial').value = (monto * 0.10).toFixed(2);
        }


        // Event listeners para tasa y porcentaje de seguro
        // Event listener para el cambio de tipo de seguro
        document.getElementById('tipoSeguro').addEventListener('change', function() {
            const tipoSeguroId = parseInt(this.value, 10); // Convierte el valor a entero
            let porcentaje = ""; // Inicializa el porcentaje

            // Lógica para asignar el porcentaje según el tipo de seguro
            if (tipoSeguroId === 3) {
                porcentaje = "1.5%";
            } else if (tipoSeguroId === 4) {
                porcentaje = "2%";
            } else {
                porcentaje = "No aplica"; // Valor por defecto si no es 3 o 4
            }

            // Asigna el porcentaje al campo correspondiente
            document.getElementById('porcentaje_seguro').value = porcentaje;
        });

        // Calcula y muestra la tasa de interés según el plazo
        function calcularTasa() {
            // Obtener el valor del plazo ingresado
            const plazo = parseInt(document.getElementById('plazo_credito').value);
            let tasaTexto = '';
            let tasaValor = 0; // Variable para almacenar la tasa como número

            // Verificar si el plazo es válido
            if (!isNaN(plazo)) {
                if (plazo >= 4 && plazo <= 10) {
                    tasaTexto = '10%';
                    tasaValor = 10; // Tasa en formato numérico
                } else if (plazo >= 11 && plazo <= 20) {
                    tasaTexto = '12%';
                    tasaValor = 12;
                } else if (plazo >= 21 && plazo <= 25) {
                    tasaTexto = '14%';
                    tasaValor = 14;
                } else {
                    tasaTexto = 'Plazo fuera de rango';
                    tasaValor = 0;
                }
            }

            // Asignar la tasa en formato de texto al campo de tasa de interés
            document.getElementById('tasa_interes').value = tasaValor;

            // Aquí puedes usar tasaValor para cálculos posteriores, ya que es un número
            // Ejemplo: console.log("La tasa como número es:", tasaValor);
        }
        // Verifica que la cuota mensual no exceda el 50% del ingreso
        function verificarAdvertencia() {
            const ingresoMensual = parseFloat(document.getElementById('ingresoMensual').value);
            const cuotaInicial = parseFloat(document.getElementById('cuota_inicial').textContent);

            if (cuotaInicial >= ingresoMensual * 0.5) {
                document.getElementById('advertenciaCuota').style.display = 'block';
                return confirm("La cuota mensual es igual o mayor al 50% de su ingreso mensual. ¿Desea continuar?");
            } else {
                document.getElementById('advertenciaCuota').style.display = 'none';
                return validarEdad();
            }
        }
        //INICIALIZAR
        const base_url = " <?php echo CONTROLLERS . "Credito_HipotecarioController.php"; ?>";
        const base_url_departamento = " <?php echo CONTROLLERS . "Provincia_Controller.php"; ?>";
        const base_url_provincia = " <?php echo CONTROLLERS . "Distrito_Controller.php"; ?>";
        const consulta_dni = " <?php echo CONTROLLERS . "ApiController.php"; ?>";

        $("#formFormulario").submit(function(e) {
            e.preventDefault();

            let data = new FormData(e.target);

            data.append("accion", "calcular");

            $.ajax({
                type: "POST",
                url: base_url,
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    let res = JSON.parse(response);
                    console.log(res.texto2);

                    // redireccionar a la página de confirmación

                    if (res.tipo == "success") {
                        window.location.href = "<?php echo PAGES . "credito_hipotecario/confirmacion_credito.php" ?>";
                        console.log("Respuesta del servidor:", response);

                    } else {
                        alert(res.texto);
                    }


                }
            });

        });

        $("#departamento").change(function() {

            let distritosSelect = $("#distrito");
            distritosSelect.empty(); // Limpiar el select
            distritosSelect.append('<option value="">Seleccione un distrito</option>');

            $.ajax({
                type: "POST",
                url: base_url_departamento + "?accion=buscar_departamento",
                data: {
                    departamento_id: $("#departamento").val()
                },
                success: function(data) {

                    let res = JSON.parse(data);

                    console.log("Respuesta recibida:", res);

                    let provinciasSelect = $("#provincia");
                    provinciasSelect.empty(); // Limpiar el select

                    if (Array.isArray(res) && res.length > 0) {
                        provinciasSelect.append('<option value="">Seleccione una provincia</option>');

                        res.forEach(function(provincia) {
                            provinciasSelect.append('<option value="' + provincia.provincia_id + '">' + provincia.nombre + '</option>');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud:", status, error);
                }
            });
        });

        //DISTRITO BUSCAR 

        $("#provincia").change(function() {

            $.ajax({
                type: "POST",
                url: base_url_provincia + "?accion=buscar_provincia",
                data: {
                    provincia_id: $("#provincia").val()
                },
                success: function(data) {

                    let res = JSON.parse(data);

                    console.log("Respuesta recibida:", res);

                    let distritosSelect = $("#distrito");
                    distritosSelect.empty(); // Limpiar el select

                    if (Array.isArray(res) && res.length > 0) {
                        distritosSelect.append('<option value="">Seleccione un distrito</option>');

                        res.forEach(function(distrito) {
                            distritosSelect.append('<option value="' + distrito.distrito_id + '">' + distrito.nombre + '</option>');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud:", status, error);
                }
            });
        });

        $("#dni").change(function(e) {
            $.ajax({
                type: "POST",
                url: consulta_dni, // URL donde realizas la consulta
                data: {
                    dni: $("#dni").val() // El valor del DNI ingresado
                },
                success: function(data) {
                    let res = JSON.parse(data);

                    // Verificamos si res.data existe y tiene valores válidos
                    if (res.data && res.data !== "") {
                        let datos = JSON.parse(res.data);

                        // Verifica si los datos de nombre y apellidos están definidos y no son vacíos
                        if (datos.nombres && datos.apellidoPaterno && datos.apellidoMaterno) {
                            // Si los datos son válidos, llenamos los campos
                            $("#nombre").val(datos.nombres);
                            $("#apellidos").val(datos.apellidoPaterno + " " + datos.apellidoMaterno);
                        } else {
                            // Si faltan datos de nombre o apellidos, muestra un mensaje de alerta
                            alert("Porfavor, ingrese manualmente sus datos.");
                            $("#nombre").val('');
                            $("#apellidos").val('');
                        }
                    } else {
                        // Si no se encuentra información para el DNI
                        alert("No se encontraron datos para el DNI proporcionado.");
                        $("#nombre").val('');
                        $("#apellidos").val('');
                    }
                },
                error: function(xhr, status, error) {
                    // Muestra un mensaje de error si hay un problema con la solicitud AJAX
                    alert("Ocurrió un error al consultar el DNI. Intenta nuevamente.");
                    console.error("Error en la solicitud:", status, error);
                }
            });






        });
    </script>

</body>

</html>