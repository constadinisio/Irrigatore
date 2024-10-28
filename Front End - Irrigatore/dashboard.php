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
                        success: function(data) {
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

                // Actualizar datos cada 10 segundos
                setInterval(function() {
                    updatePingStatus();
                }, 1000);

                function actualizarDatos() {
                    $.ajax({
                        url: 'obtener_datos.php',
                        method: 'GET',
                        success: function(data) {
                            const resultado = JSON.parse(data);

                            if (resultado.error) {
                                console.error('Error:', resultado.error);
                                $('#temperatura-ambiente').text('Error al cargar');
                                $('#humedad-aire').text('Error al cargar');
                                $('#porcentaje-humedad').text('Error al cargar');
                                $('#estado-rele').text('Error al cargar');
                                $('#creacion').text('Error al cargar');
                            } else {
                                $('#temperatura-ambiente').text(resultado.temperatura + '°C');
                                $('#humedad-aire').text(resultado.humedad_aire + '%');
                                $('#porcentaje-humedad').text(resultado.humedad_tierra + '%');
                                $('#estado-rele').text(resultado.estado_rele ? 'Activado' : 'Desactivado');
                                $('#creacion').text(resultado.creacion); // Asegúrate de que tengas un elemento HTML con este ID
                            }
                        },
                        error: function(error) {
                            console.error('Error al obtener datos:', error);
                        }
                    });
                }


        // Actualizar datos cada 30 segundos
        setInterval(function() {
            actualizarDatos();
        }, 10000);
        
    </script>

    <?php
        // Conexión a la base de datos
        $conn = new mysqli('localhost', 'root', '', 'irrigatore');

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
                <img alt="Logo" class="h-20 w-60 mr-2" height="40" src="https://irrigatore.s3.us-east-2.amazonaws.com/Irrigatore.jpg?response-content-disposition=inline&amp;X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&amp;X-Amz-Security-Token=IQoJb3JpZ2luX2VjENb%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FwEaCXVzLWVhc3QtMiJHMEUCIAu61wV9WrVsEk8W2RgLkGVrntlcqcTs%2Fx2PViWf9yJ8AiEAwTqh5e%2FpBvZtswe8Jjhvamif8m2j3ur27CRpujRXyyMqxwMIUBAAGgw5MTM1MjQ5NDA2MjgiDD1GVLFrUWMFiKwe5yqkA33hjN%2BeWymnc8ODoZDluisvTS54m46zs3MrUfTd1p2hrBauNxMNC7uE8F%2Ffi5f8mAIuVJM7JoXu4Irr5gbLdgD3lo4D2hZd2FPQM6EyHNZLDtadLLda60nh%2F4vYSXKcMKROoPWLI53UmUGEgkQZLaN76dZcrzK5hg%2FlW%2F0KqYLzZXm4uw2UdeUIhRMVkEL1dpN3dESF2ZmfP%2BajL0XAJT3Qt4gYHMPo15HqMf9k7q0sSi39jdFCkGvsdMqo%2BXL%2FsHXEDbx%2BV58cgu5eB1DLq0U7eu2h6wnBxLCXZX1e%2BSOYTZv1fzfFF5p5moP18Yhn5ow5px3lfJSXLxFVTXOmIxigaELEvVwVy4xAnArGm0MhGhsXVybMlAN1K1ijkZPRoQgIEyg62KaeT29TGIFnA9TB5eyQbO%2FZAGWYa5WTLqAVou5f3dzM2fmyKEpOjMIVh9ibxDF51cIf%2BlZR%2B%2BYUTTXfS%2F1TahZTkwsYKqoWWNL%2Fg5CXvlMTN7IQJqJzoTiWwB6BeVGPnH9si%2BmitHhmUI9E9NikwYsxM8o3o48Z4qXjIpUSEDC9nIC5BjrkAuhydlTENnli4OREQ1O1JmOSwQztcfY8Y3v5B56bWWBWFWTtaTwkyaJKasB1GnsTHF3DwztKuC%2Fj3htVYXhMcszo0eWPsSEkUeHSMip73piBnXwbxZy8fFoDCPWPj2IGveXbPmMsYUXXxnp1T1k745xvkAlLcqP%2BRbdf8WLiKeO%2BWhEDgTg7foXyJJ7ehW7IqCLk50jHNjxVDMTOOPg%2BaIKF%2Fmk7w3Rkirg3%2F3fmRiLt1vuJuF9yeYvHOthrjVQBfQ1swGoDkkO7H%2Fav9co7%2B4NQlV4guKAYAjr5bJsFO2VRVf9pJMMJQM41EpS%2By5n%2B0pB%2FkpDJ3yADIka0U1s0UvMlfPbYp5eHvszr3%2F%2F7ezv8BeMPWtVBDWsbKScGFYONDAVESCts4kq96EAkC6Z%2BBc3lQLtsqR6Dn3TWzQA8DatvjtBJHYi8yO2tk3frPriXqWt%2FRiXg3PGweeiOCKdLR3DLVqWN&amp;X-Amz-Algorithm=AWS4-HMAC-SHA256&amp;X-Amz-Credential=ASIA5JMSUL5KEVHE4YEN%2F20241028%2Fus-east-2%2Fs3%2Faws4_request&amp;X-Amz-Date=20241028T222223Z&amp;X-Amz-Expires=43200&amp;X-Amz-SignedHeaders=host&amp;X-Amz-Signature=733a8a84e29a7d409651c23384aa55a3666f1c8550252c73b30d00d364c7f919" width="40">
            </div>
            <nav class="mt-10">
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 mb-4" href="./dashboard.php">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
                <div class="my-2 opacity-75">
                    <p class="text-center text-sm">DATA</p>
                    <div class="border-t border-gray-600 mt-1"></div>
                </div>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./recomendacion.php">
                    <i class="fas fa-plus mr-2"></i>
                    Recomendaciones
                </a>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./team.php">
                    <i class="fas fa-users mr-2"></i>
                    ¿Quienes somos?
                </a>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./documentation.php">
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

            <div class="bg-gray-800 text-white p-2 rounded-lg shadow-md" style="width:1000px">
                <table>
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

                        $sql = "SELECT * FROM ciclo";
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
