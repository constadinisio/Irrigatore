<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        function updatePingStatus() {
            $.ajax({
                type: 'GET',
                url: './ping.php',
                dataType: 'json',
                success: function (data) {
                    let statusEmoji;
                    switch (data.status) {
                        case 'OK':
                            statusEmoji = '🟢';
                            break;
                        case 'ERROR':
                            statusEmoji = '🔴';
                            break;
                    }
                    $('#pingStatus').text(statusEmoji + ' ' + data.status);
                }
            });
        }

        // Actualizar datos cada 1 segundo
        setInterval(function () {
            updatePingStatus();
        }, 1000);
    </script>

    <script>
        function actualizarDatos() {
            // Actualizar las tarjetas
            $.ajax({
                url: 'obtener_datos.php', // Para las tarjetas
                method: 'GET',
                success: function (data) {
                    const resultado = JSON.parse(data);

                    if (resultado.error) {
                        console.error('Error:', resultado.error);
                        $('#temperatura-ambiente').text('Error al cargar');
                        $('#humedad-aire').text('Error al cargar');
                        $('#porcentaje-humedad').text('Error al cargar');
                        $('#estado-rele').text('Error al cargar');
                        $('#creacion').text('Error al cargar');
                    } else {
                        $('#temperatura-ambiente').text(resultado.temperatura !== undefined ? resultado.temperatura + '°C' : 'No disponible');
                        $('#humedad-aire').text(resultado.humedad_aire !== undefined ? resultado.humedad_aire + '%' : 'No disponible');
                        $('#porcentaje-humedad').text(resultado.humedad_tierra !== undefined ? resultado.humedad_tierra + '%' : 'No disponible');
                        $('#estado-rele').text(resultado.estado_rele !== undefined ? (resultado.estado_rele ? 'Activado' : 'Desactivado') : 'No disponible');
                        $('#creacion').text(resultado.creacion !== undefined ? resultado.creacion : 'No disponible');
                    }
                },
                error: function (error) {
                    console.error('Error al obtener datos:', error);
                }
            });

            // Actualizar la tabla
            $.ajax({
                url: 'obtener_datos_tabla.php', // Para la tabla
                method: 'GET',
                success: function (data) {
                    const resultadosTabla = JSON.parse(data);
                    const tbody = $('tbody'); // Selecciona el tbody de la tabla
                    tbody.empty(); // Limpia el contenido actual

                    if (resultadosTabla.length > 0) {
                        resultadosTabla.forEach(function (row) {
                            const tr = $('<tr>');
                            tr.append(`<td class='py-2 px-4 border'>${row.id}</td>`);
                            tr.append(`<td class='py-2 px-4 border'>${row.temperatura}</td>`);
                            tr.append(`<td class='py-2 px-4 border'>${row.humedad_aire}</td>`);
                            tr.append(`<td class='py-2 px-4 border'>${row.humedad_tierra}</td>`);
                            tr.append(`<td class='py-2 px-4 border'>${row.estado_rele ? 'Activado' : 'Desactivado'}</td>`);
                            tr.append(`<td class='py-2 px-4 border'>${row.creacion}</td>`);
                            tbody.append(tr);
                        });
                    } else {
                        tbody.append("<tr><td colspan='6' class='py-2 px-4 border text-center'>No hay resultados</td></tr>");
                    }
                },
                error: function (error) {
                    console.error('Error al obtener datos de la tabla:', error);
                }
            });
        }

        // Llama a la función una vez al cargar la página
        actualizarDatos();

        // Configura la actualización automática cada 10 segundos
        setInterval(actualizarDatos, 10000); // 10000 milisegundos = 10 segundos

    </script>

    <?php
    // Conexión a la base de datos
    $conn = new mysqli('ip_mysql', 'mysql_user', 'mysql_pw', 'db_name');

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Consultar la base de datos para obtener los datos más recientes
    $sql = "SELECT temperatura, humedad_aire, humedad_tierra, estado_rele, creacion FROM ciclo ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tempAmbiente = $row['temperatura'];
        $humAmbiente = $row['humedad_aire'];
        $porcHumedad = $row['humedad_tierra'];
        $estadoRele = $row['estado_rele'];
        $creacion = $row['creacion'];

        // Almacenar datos en variables de JavaScript
        echo "<script>
                var tempAmbiente = '$tempAmbiente';
                var humAmbiente = '$humAmbiente';
                var porcHumedad = '$porcHumedad';
                var estadoRele = " . ($estadoRele ? 'true' : 'false') . ";
                var creacion = '$creacion';
            </script>";
    } else {
        echo "<script>
                var tempAmbiente = 'No data';
                var humAmbiente = 'No data';
                var porcHumedad = 'No data';
                var estadoRele = false;
                var creacion = 'No data';
            </script>";
    }

    $conn->close();
    ?>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 text-white h-screen" style="background-color:#10242b">
            <div class="p-4 flex items-center">
                <img alt="Logo" class="h-20 w-60 mr-2" height="40"
                    src="https://irrigatore.s3.us-east-2.amazonaws.com/Irrigatore.jpg?response-content-disposition=inline&X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEC4aCXVzLWVhc3QtMiJGMEQCIDHNmFVFD%2BZnTpifmS%2FOdm7uecoUVz04YUBntR9GIUUPAiBFoHeH3AXc1OgDjqNBYvubF1K2hOuqWCjIegdIJAGkYCrQAwin%2F%2F%2F%2F%2F%2F%2F%2F%2F%2F8BEAAaDDkxMzUyNDk0MDYyOCIMYfxWBb3MGMjdGMmsKqQDMq0H49FZmLZPZPA0g7YhzP7erbH5bEnu%2BQHVxtqQ6naxB8I79Vvfz4tlDOsy%2Fwub9J4siG3CFHpsE8wrZE4%2F7UXaaihn4pDv58hZddceAyds1ZHO0YHlQ44BOAwwSWjuCW61biCeXT70EjfQlMo3Y2upbO2NNZ85uZCa%2BRFmQgoZVeGmoq6xRXt1FM6Qx6rAZnSOSg7VMWUmwDu8X8NMXyHTxbjsjcZAVB3%2BKhHlSn5zq5E2MWlC1puXaBzhsjA1CA%2BdUkoRfdYGYOkg%2FrUxO45NL9hITvF%2F2sSA89qvI98Ybm2V4D4vL0lxFgEicf%2BadhL%2FOwE3jea%2FB5XNBe%2Fjbu%2B1NQFs%2FCcHu9yP6tFB%2Bto%2FrdujrXZlsPDL38hDUBtapLnYlZjwsy9weCNmJZsRxE%2BQL3CANS3JNLdxJ5opH3vw%2BL%2BtSA8SJSgACk58lmQES62y4RAthhwved0%2BzZiGQYPnk%2BFhrhdPuYhujBNaII8thKroPl71T%2BQgpdCFyEekqs6%2BRsCerWMnKXPfzI6Ts4dmGsbkXZ2xL4vBTrkm2XZki2cPMLe8k7kGOuUCPlPuAaQSYWMMPdp%2BxyzH%2BGBLAXPasfQL5ErIOkxoLbuK7aZf%2BbsAQKbhlghEJ00dNNxgG%2FqIpgg97FUj8VOVAo1NKHzp%2BXUOTOBkh%2FqH1KFVqKywlU1ILT%2FkPn3JsFp%2BjLHYFTlEpHkY2nVozGcSqpSm7UDfd46BIHjR%2Bpx6cbLcQIco%2B5V%2FHS9Ps7iopcScyULj0zKFCxVACsT%2BOYlnjPJCEvbgP%2BudVVjG4M%2FQqUm4wO9EIq0I8zwF%2BZtNWpXndXD1Y2dRfC%2Bo2jxED8Vt0W6hqQ%2FlKvfCfoipkMJq77SYfa9UmOq8ynwKQFZqmyXC6pF0C%2FnAFFn6rH7Gs1Qoubitfg5mk0qKTsJqNQQhpI1IzqKYftQwbMhiePGnklz0NKiZtPL00YSRD%2BhxRw05P%2FcPHT1CNYK3IZRDd1hgAWqD8Ngsz0VCwjcHxJYYYe9DkDwASfWd0EsVZKdMLc55A%2BUbyV7%2B&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIA5JMSUL5KEQIC6RZB%2F20241101%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20241101T140053Z&X-Amz-Expires=43200&X-Amz-SignedHeaders=host&X-Amz-Signature=42f30667833fc6bac9e2b2f7df9630fcbc0934cd3af9e2f6187862f228a38897"
                    width="40">
            </div>
            <nav class="mt-10">
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 mb-4"
                    href="./dashboard.php">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
                <div class="my-2 opacity-75">
                    <p class="text-center text-sm">DATA</p>
                    <div class="border-t border-gray-600 mt-1"></div>
                </div>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"
                    href="./recomendacion.php">
                    <i class="fas fa-plus mr-2"></i>
                    Recomendaciones
                </a>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./team.php">
                    <i class="fas fa-users mr-2"></i>
                    ¿Quienes somos?
                </a>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"
                    href="./documentation.php">
                    <i class="fas fa-book mr-2"></i>
                    Documentación
                </a>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./contacto.php">
                    <i class="fas fa-phone mr-2"></i>
                    Contacto
                </a>
            </nav>
        </div>
        <!-- Main content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-semibold">Dashboard</h2>
                <div class="flex items-center">
                    <button class="text-gray-600 focus:outline-none">
                        <i class="fas fa-bell"></i>
                    </button>
                    <img alt="User avatar" class="ml-4 rounded-full" height="40"
                        src="https://storage.googleapis.com/a1aa/image/GIyiJdYNPHb3BN0i7gYMoDWOM02ayqxUA5IH1atJwA0JEx4E.jpg"
                        width="40" />
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="shadow-md bg-yellow-500 text-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Temperatura Ambiente</h3>
                    <p class="text-2xl" id="temperatura-ambiente">
                        <?php echo $tempAmbiente; ?>°C
                    </p>
                </div>
                <div class="bg-red-500 text-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Humedad Ambiente</h3>
                    <p class="text-2xl" id="humedad-aire">
                        <?php echo $humAmbiente; ?>%
                    </p>
                </div>
                <div class="bg-green-500 text-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Porcentaje de Humedad en Tierra</h3>
                    <p class="text-2xl" id="porcentaje-humedad">
                        <?php echo $porcHumedad; ?>%
                    </p>
                </div>
                <div class="bg-gray-500 text-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">
                        Estado de NodeMCU ESP8266
                    </h3>
                    <p class="text-2xl" id="pingStatus">
                        🟡 CONECTANDO...
                    </p>
                </div>
            </div>

            <div class="bg-gray-800 text-white p-2 rounded-lg shadow-md"
                style="width:1000px; max-height: 600px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse">
                    <thead>
                        <tr style="background-color:#2563eb">
                            <th class="py-2 px-4 border">N° de Ciclo</th>
                            <th class="py-2 px-4 border">Temperatura de Aire</th>
                            <th class="py-2 px-4 border">Humedad en el Aire</th>
                            <th class="py-2 px-4 border">Humedad de la Tierra</th>
                            <th class="py-2 px-4 border">Estado de Relé</th>
                            <th class="py-2 px-4 border">Fecha y Hora de Ciclo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'conexion.php'; // Asegúrate de que este archivo está bien configurado
                        
                        $sql = "SELECT * FROM ciclo ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='py-2 px-4 border'>" . $row["id"] . "</td>";
                                echo "<td class='py-2 px-4 border'>" . $row["temperatura"] . "</td>";
                                echo "<td class='py-2 px-4 border'>" . $row["humedad_aire"] . "</td>";
                                echo "<td class='py-2 px-4 border'>" . $row["humedad_tierra"] . "</td>";
                                echo "<td class='py-2 px-4 border'>" . ($row["estado_rele"] ? 'Activado' : 'Desactivado') . "</td>";
                                echo "<td class='py-2 px-4 border'>" . $row["creacion"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='py-2 px-4 border text-center'>No hay resultados</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>