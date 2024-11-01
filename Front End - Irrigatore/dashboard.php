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
        $conn = new mysqli('127.0.0.2', 'root', '', 'irrigatore');

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
                <img alt="Logo" class="h-20 w-60 mr-2" height="40" src="https://irrigatore.s3.us-east-2.amazonaws.com/Irrigatore.jpg?response-content-disposition=inline&X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Security-Token=IQoJb3JpZ2luX2VjEAEaCXVzLWVhc3QtMiJGMEQCID5yBNGE8IpESkd26FahJzP%2BJNJVpVq%2F8LE3p3rl2LP6AiABzSPgQHWPcAMjKEy5bodyBENcbvL5FsbFhq77FPVVvyrHAwh6EAAaDDkxMzUyNDk0MDYyOCIM5xO2SguOndKPfXSTKqQDT72Ic4Ae6%2FPD%2ByD%2BbXexGRk3k7LUoKqkPaICBoJcqsBlJFQP4BuvR7UlzYV2NmD2y0RjstzKsMuBtgbtBqBr9WtoK242T8CfwKrLfr%2BBQ7GoOTdKPAG1OQA8j3Brp5ELe3o1FZAyq8kDc3wktd4%2FHVSahMF9jRQrUFqzbb2Oz5C2h9XpHSRS1ZioBCuMRQ2rPCNOkULquZDKwRc8U9qU5uwQCHrP5OmQdK%2Fuwh3EGoMF6hcvIWkTSdMYEl7nlLhytGQWEJzDM8uLDgEHFsQf1qnJ4KQ3nge8Xf%2BVqEtSC%2B2ha%2F1VkwFmnkdcWCk%2F6q8kZ7BXyQ9%2FRAUHKj2pywYrFcEtxBAU7524PO8D5c3GWJG1U%2Fo5GQzSxYm8Os%2BeoSJPRBxhUBWgVfQlXRMQJTYx6tdnCPMyhg4HoL7vy4%2FEAEB5oI%2FDaqLoaT3n%2BFi6rOUW3pA69DoJTmG74SneJ2TA6UFwymyq1%2Bfy88ES2oZktjgLzDeGl2iThKny876x9k5xziG33%2BahgTXF7y2mnSUp98Bix378jPUXnaamgntXgmjNEcmUMIDOibkGOuUCn%2Fvia7W1PGYrklgGbbAVyFbSl4BfzGp0zXWQiFjddowquKxM%2BrZvNUspzVaRp%2BYA0JZ6BzKBoGFNQ%2B1j9Qk4W5ARwohBII8XAhCI7zb2%2BwTkhj1dnCSleFDhn1%2FU3vU2n%2BhJ%2FmJa5Rl6%2F2%2BU%2BR3lJhMYtF9lsKsTAsgkVscgoO68vKC%2BtlAbHfJHh8mvP%2F7X76M4tnN%2F7OUhTEx9zwIiYYgWPd93TWE4%2FU2%2BDNUnPqvtSt1czFRMURNcnaFRpC8w107MKn5wikTtubiVZMhxDZNv6zBW2aSNd01kwU5UMdO9PYloSIpXLugZ8WyGDFGbe8pQnHVzr%2FIVqo9cUzrewSSrVrcs2EFKRbLGGkz9MitUXlOJU7OEcbrfrHmgv%2BDgHMMRXwADklNXS8u1KdUXaP3q2j2DWlEmlAdIHlIIqUs5lL0wM46GksNwISAxUZHVGsnoD7YeaWNPnwBVJWiFQe18bcbN&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=ASIA5JMSUL5KIOR5ITAK%2F20241030%2Fus-east-2%2Fs3%2Faws4_request&X-Amz-Date=20241030T170754Z&X-Amz-Expires=43200&X-Amz-SignedHeaders=host&X-Amz-Signature=c567add772a638c2add86e066e3f4c30f7c30dae99a09ef70a65e9a376ab332c" width="40">
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

            <div class="bg-gray-800 text-white p-2 rounded-lg shadow-md" style="width:1000px; max-height: 600px; overflow-x: auto;">
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
