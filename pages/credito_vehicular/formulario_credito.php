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
        <h1>Crédito Vehicular</h1>
    </header>

    <!-- Contenedor Principal -->
    <div class="container">
        <h2>Formulario de Crédito Vehicular</h2>

        <!-- Formulario -->
        <form id="formFormulario">

            <!-- Sección de Formularios en Tres Columnas -->
            <div class="form-section">

                <!-- Columna Datos del Cliente -->
                <div class="form-column">
                    <h3>Datos del Cliente</h3>
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" title="Solo se permiten letras">

                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" required pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" title="Solo se permiten letras">

                    <label for="dni">DNI:</label>
                    <input type="text" id="dni" name="dni" required pattern="^\d{8}$" title="Debe tener exactamente 8 dígitos">

                    <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fechaNacimiento" name="fechaNacimiento" required>

                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" required>

                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" required pattern="^\d{9}$" title="Debe tener exactamente 9 dígitos">

                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" required>

                    <label for="ingresoMensual">Ingreso Mensual:</label>
                    <input type="number" id="ingresoMensual" name="ingresoMensual" required min="0" title="Ingrese un valor numérico válido">

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

                <!-- Columna Datos del Vehículo -->
                <div class="form-column">
                    <h3>Datos del Vehículo</h3>
                    <label for="marca">Marca:</label>
                    <select id="marca" name="marca" required>
                        <option value="Toyota">Toyota</option>
                        <option value="Honda">Honda</option>
                        <option value="Ford">Ford</option>
                        <option value="BMW">BMW</option>
                        <option value="Chevrolet">Chevrolet</option>
                        <option value="Volkswagen">Volkswagen</option>
                        <option value="Nissan">Nissan</option>
                        <option value="Mazda">Mazda</option>
                    </select>

                    <label for="modelo">Modelo:</label>
                    <input type="text" id="modelo" name="modelo" required>

                    <label for="montoVehiculo">Monto del Vehículo:</label>
                    <input type="number" id="montoVehiculo" name="montoVehiculo" required>
                </div>

                <!-- Columna Datos del Crédito -->
                <div class="form-column credit-section">
                    <h3>Datos del Crédito</h3>
                    <div class="credit-row">
                        <label for="porcentajeCuotaInicial">Porcentaje de Cuota Inicial (%):</label>
                        <input type="number" id="porcentajeCuotaInicial" name="porcentajeCuotaInicial" required>
                    </div>

                    <div class="credit-row">
                        <label>Plazo de Crédito:</label>
                        <div class="slider-container">
                            <input type="range" id="plazoMeses" name="plazoMeses" min="12" max="72" value="24" oninput="this.nextElementSibling.value = this.value">
                            <output>24</output> meses
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

        <a href="<?php echo PAGES . "credito_vehicular/consultas.php"; ?>">Consultar</a>
    </div>

    <?php include "../../modules/footer.php" ?>


    <script>
        //INICIALIZAR
        const base_url = " <?php echo CONTROLLERS . "Credito_VehicularController.php"; ?>";
        const base_url_departamento = " <?php echo CONTROLLERS . "Provincia_Controller.php"; ?>";
        const base_url_provincia = " <?php echo CONTROLLERS . "Distrito_Controller.php"; ?>";
            $("#formFormulario").submit(function(e) {
            e.preventDefault();

            let data=new FormData(e.target);

            data.append("accion", "calcular" );

            $.ajax({
            type: "POST" ,
            url: base_url,
            data: data,
            processData: false,
            contentType: false,
            success: function(response) {
            let res=JSON.parse(response);

            // redireccionar a la página de confirmación

            if (res.tipo=="success" ) {
            window.location.href="<?php echo PAGES . "credito_vehicular/confirmacion_credito.php" ?>" ;
            } else {
            alert(res.texto);
            }


            }
            });

            });

            $("#departamento").change(function () {
                
                let distritosSelect = $("#distrito");
                distritosSelect.empty(); // Limpiar el select
                distritosSelect.append('<option value="">Seleccione un distrito</option>');

                $.ajax({
                    type: "POST",
                    url: base_url_departamento + "?accion=buscar_departamento",
                    data: { departamento_id: $("#departamento").val() },
                    success: function (data) {
                       
                        let res = JSON.parse(data);
                        
                        console.log("Respuesta recibida:", res);

                        let provinciasSelect = $("#provincia");
                        provinciasSelect.empty(); // Limpiar el select
                         
                        if (Array.isArray(res) && res.length > 0) {
                            provinciasSelect.append('<option value="">Seleccione una provincia</option>');

                            res.forEach(function (provincia) {
                                provinciasSelect.append('<option value="' + provincia.provincia_id + '">' + provincia.nombre + '</option>');
                            });}
                    },
                    error: function (xhr, status, error) {
                        console.error("Error en la solicitud:", status, error);
                    }
                });
            });
            
            //DISTRITO BUSCAR 

            $("#provincia").change(function () {
              
              $.ajax({
                  type: "POST",
                  url: base_url_provincia + "?accion=buscar_provincia",
                  data: { provincia_id: $("#provincia").val() },
                  success: function (data) {
                     
                      let res = JSON.parse(data);
                      
                      console.log("Respuesta recibida:", res);

                      let distritosSelect = $("#distrito");
                      distritosSelect.empty(); // Limpiar el select

                      if (Array.isArray(res) && res.length > 0) {
                        distritosSelect.append('<option value="">Seleccione un distrito</option>');

                          res.forEach(function (distrito) {
                             distritosSelect.append('<option value="' + distrito.distrito_id + '">' + distrito.nombre + '</option>');
                          });}
                  },
                  error: function (xhr, status, error) {
                      console.error("Error en la solicitud:", status, error);
                  }
              });
          });




            </script>


</body>

</html>