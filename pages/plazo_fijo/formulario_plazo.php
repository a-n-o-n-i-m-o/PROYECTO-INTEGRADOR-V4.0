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
            justify-content: space-between;
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

    <!-- Header -->
    <header>
        <h1>Plazo Fijo</h1>
    </header>

    <!-- Contenedor Principal -->
    <div class="container">
        <h2>Formulario de Plazo Fijo</h2>

        <!-- Formulario -->
        <form id="formFormulario">

            <!-- Sección de Formularios en Tres Columnas -->
            <div class="form-section">

                <!-- Columna Datos del Cliente -->
                <div class="form-column">
                    <h3>Datos del Cliente</h3>

                    <label for="dni">DNI:</label>
                    <input type="text" id="dni" name="dni" required maxlength="8" inputmode="numeric" placeholder="Ingrese su DNI" pattern="^\d{8}$" title="Debe tener exactamente 8 dígitos y ser solo números" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)" />

                    <label for="nombre">Nombres:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ingrese sus nombres" required pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" title="Solo se permiten letras" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" />

                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" placeholder="Ingrese sus apellidos" required pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" title="Solo se permiten letras" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" />


                    <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fechaNacimiento" name="fechaNacimiento" required onchange="validarMayorEdad(this)">
                    <small id="fechaNacimientoError" style="color: red; display: none;">Debes ser mayor de edad.</small>


                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" placeholder="Ingrese su dirección" name="direccion" required>

                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Ingrese su teléfono" required pattern="^9\d{8}$" maxlength="9" title="Debe contener exactamente 9 dígitos y comenzar con 9" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)" />

                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" placeholder="Ingrese su correo electrónico" required>

                    <label for="ingresoMensual">Ingreso Mensual:</label>
                    <input type="number" id="ingresoMensual" placeholder="Ingrese su ingreso mensual" name="ingresoMensual" required min="1" title="Ingrese un valor mayor a 0">

                    <label for="estadoCivil">Estado Civil:</label>
                    <select id="estadoCivil" name="estadoCivil" required>
                        <?php foreach ($estado_civil as $estado_civil) { ?>
                            <option value="<?php echo $estado_civil['estado_civil_id']; ?>"><?php echo $estado_civil['descripcion']; ?></option>
                        <?php } ?>
                    </select>

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
                </div>

                <!-- Columna Datos del plazo fijo -->
                <div class="form-column credit-section">
                    <h3>Datos del Crédito</h3>
                    <div class="credit-row">
                        <label for="porcentajeCuotaInicial">Cuota Inicial:</label>
                        <input type="number" id="cuota_inicial" name="cuota_inicial" required min="1" title="Ingrese un valor mayor a 0">
                    </div>

                    <div class="credit-row">
                        <label>Plazo de Crédito:</label>
                        <div class="slider-container">
                            <input type="range" id="plazoAnios" placeholder="Ingrese el plazo de crédito" name="plazoAnios" min="1" max="30" value="24" oninput="this.nextElementSibling.value = this.value">
                            <output>24</output> Años
                        </div>
                    </div>

                    <div class="credit-row">
                        <label for="tipoSeguro">Tipo de Seguro:</label>
                        <select id="tipoSeguro" name="tipoSeguro" required>
                            <?php foreach ($seguros as $seguro) { ?>
                                <?php if ($seguro['tipo_seguro_id'] == 1 && $seguro['tipo_seguro_id'] == 2) continue; ?>
                                <option value="<?php echo $seguro['tipo_seguro_id']; ?>"><?php echo $seguro['descripcion']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" id="btnFormulario">Solicitar Crédito</button>
        </form>

        <button type="button"><a style="text-decoration : none; color:white;" href="<?php echo PAGES . "plazo_fijo/consultas.php"; ?>">Consultar</a></button>
    </div>

    <?php include "../../modules/footer.php" ?>


    <script>
        // Validar si es mayor de edad
        function validarMayorEdad(input) {
            const fechaNacimiento = new Date(input.value);
            const hoy = new Date();
            const edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
            const mes = hoy.getMonth() - fechaNacimiento.getMonth();
            const dia = hoy.getDate() - fechaNacimiento.getDate();

            if (edad > 18 || (edad === 18 && (mes > 0 || (mes === 0 && dia >= 0)))) {
                document.getElementById("fechaNacimientoError").style.display = "none";
                input.setCustomValidity("");
            } else {
                document.getElementById("fechaNacimientoError").style.display = "block";
                input.setCustomValidity("Debes ser mayor de edad.");
            }
        }

        //INICIALIZAR
        const base_url = " <?php echo CONTROLLERS . "Plazo_FijoController.php"; ?>";
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

                    // redireccionar a la página de confirmación

                    if (res.tipo == "success") {
                        window.location.href = "<?php echo PAGES . "plazo_fijo/confirmacion_plazo_fijo.php" ?>";
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

        $("#nombre").keyup(function(e) {
            $(this).val($(this).val().toUpperCase());
        });

        $("#apellidos").keyup(function(e) {
            $(this).val($(this).val().toUpperCase());
        });
    </script>


</body>

</html>