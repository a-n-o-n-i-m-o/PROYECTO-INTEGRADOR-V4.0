<?php


require_once "../../config/config.php";

?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=KoHo&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'KoHo', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        h2 {
            color: #002f6c;
            text-align: center;
        }

        main {
            flex: 1;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 150px);
            /* header y footer */
        }

        .container {
            width: 100%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form,
        .resultados {
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            font-size: 16px;
            color: white;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        footer {
            width: 100%;
            margin-top: auto;
        }
    </style>
</head>

<body>

    <header>
        <h1>Consultar Deposito a plazo</h1>
    </header>

    <main>
        <div class="container">

            <form id="formBusqueda">
                <label for="dni">Inserte su DNI:</label>
                <input type="text" id="dni" name="dni" minlength="8" maxlength="8" required>
                <button type="submit">Consultar</button>
            </form>

            <div class='resultados' id="listadoResultadoCliente">
                <h2>Datos del Cliente</h2>
                <p><strong>Nombre:</strong> <span id="resultadoNombres"></span></p>
                <p><strong>DNI:</strong> <span id="resultadoDNI"></span></p>
                <p><strong>Teléfono:</strong> <span id="resultadoTelefono"></span></p>
                <p><strong>Correo:</strong> <span id="resultadoCorreo"></span></p>
            </div>


            <div class="resultados">
                <table id="tablaResultadoCredito">
                    <thead>
                        <tr>
                            <th>Cuota inicial</th>
                            <th>Plazo</th>
                            <th>Tea</th>
                            <th>Tipo</th>
                            <th>Fecha de solicitud</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
    </main>

    <?php include "../../modules/footer.php" ?>


    <script>
        //INICIALIZAR
        const base_url = "<?php echo CONTROLLERS . "Plazo_FijoController.php" ?>";

        if ($('#resultadoNombres').text() == "") {
            $('#listadoResultadoCliente').hide();
            $('#resultados #tablaResultadoCredito').hide();
        } else {
            $('#listadoResultadoCliente').show();
            $('#resultados #tablaResultadoCredito').show();
        }

        function verCronograma(dni) {
            let data = new FormData();
            data.append("accion", "cronograma");
            data.append("dni", dni);

            $.ajax({
                type: "POST",
                url: base_url,
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    let res = JSON.parse(response);

                    if (res.tipo == "success") {
                        // redireccionar a la página de confirmación
                        window.location.href = "<?php echo PAGES . "plazo_fijo/cronograma_credito.php" ?>";
                    } else {
                        alert(res.texto);
                    }
                }
            });
        }

        $("#formBusqueda").submit(function(e) {
            e.preventDefault();

            let data = new FormData(e.target);

            data.append("accion", "buscar");

            $.ajax({
                type: "POST",
                url: base_url,
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    let res = JSON.parse(response);

                    if (res.tipo == "success") {

                        $("#resultadoNombres").text(res.data.nombre + " " + res.data.apellidos);
                        $("#resultadoDNI").text(res.data.dni);
                        $("#resultadoTelefono").text(res.data.telefono);
                        $("#resultadoCorreo").text(res.data.correo);

                        if ($('#resultadoNombres').text() == "") {
                            $('#listadoResultadoCliente').hide();
                            $('#resultados #tablaResultadoCredito').hide();
                        } else {
                            $('#listadoResultadoCliente').show();
                            $('#resultados #tablaResultadoCredito').show();
                        }

                        // listar res.data en la tabla id tablaResultadoCredito
                        let tabla = $('#tablaResultadoCredito tbody');
                        tabla.empty();

                        let fila = `
                            <tr>
                                <td>${res.data.monto_deposito}</td>
                                <td>${res.data.plazo}</td>
                                <td>${res.data.tea}</td>
                                <td>${res.data.tipo_seguro}</td>
                                <td>2024-11-25</td>
                                <td>
                                    <button onclick="verCronograma(${res.data.dni})">Ver</button>
                                </td>
                            </tr>
                            `;

                        tabla.append(fila);

                    } else {
                        alert(res.texto);
                    }


                }
            });



        });
    </script>

</body>

</html>