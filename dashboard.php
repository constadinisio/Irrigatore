<!DOCTYPE html>
<html lang="en">
  <head>
        <title>
        Dashboard
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- The website JavaScript file -->
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
    </script>

    <?php
        // Connect to the database
        $conn = new mysqli('localhost', 'root', '', 'irrigatore');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query the database to get the latest data
        $sql = "SELECT temperatura, humedad_aire, humedad_tierra FROM ciclo ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Get the latest data
            $row = $result->fetch_assoc();
            $tempAmbiente = $row['temperatura'];
            $humAmbiente = $row['humedad_aire'];
            $porcHumedad = $row['humedad_tierra'];

            // Return the data in JSON format
            echo json_encode(array(
                'tempAmbiente' => $tempAmbiente,
                'humAmbiente' => $humAmbiente,
                'porcHumedad' => $porcHumedad
            ));
        } else {
            echo json_encode(array('error' => 'No data found'));
        }

        $conn->close();
    ?>
  </head>
  <body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white h-screen">
            <div class="p-4 flex items-center">
                <img alt="Logo" class="h-8 w-8 mr-2" height="40"
                    src="https://drive.google.com/file/d/1p-9suCW6Kx5vaUO0yW9-U2GQjs87gewj/view" width="40" />
            </div>
            <nav class="mt-10">
                <a  class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 mb-4" href="./dashboard.html">
                    <i class="fas fa-home mr-2">
                    </i>
                    Dashboard
                </a>
                <div class="my-2 opacity-75">
                    <p class="text-center text-sm">
                        DATA
                    </p>
                    <div class="border-t border-gray-600 mt-1">
                    </div>
                </div>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./recomendacion.html">
                    <i class="fas fa-plus mr-2">
                    </i>
                    Recomendaciones
                </a>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./team.html">
                    <i class="fas fa-users mr-2">
                    </i>
                    ¿Quienes somos?
                </a>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./documentation.html">
                    <i class="fas fa-book mr-2">
                    </i>
                    Documentación
                </a>
                <a class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700" href="./contacto.html">
                    <i class="fas fa-phone mr-2">
                    </i>
                    Contacto
                </a>
            </nav>
        </div>
        <!-- Main content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-semibold">
                    Dashboard
                </h2>
                <div class="flex items-center">
                    <button class="text-gray-600 focus:outline-none">
                        <i class="fas fa-bell">
                        </i>
                    </button>
                    <img alt="User avatar" class="ml-4 rounded-full" height="40"
                        src="https://storage.googleapis.com/a1aa/image/GIyiJdYNPHb3BN0i7gYMoDWOM02ayqxUA5IH1atJwA0JEx4E.jpg"
                        width="40" />
                </div>
            </div>

            <!-- Dropdown Menu -->
            <button id="dropdownDelayButton" data-dropdown-toggle="dropdownDelay" data-dropdown-delay="500"
                data-dropdown-trigger="click"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-6 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button">Zonas de Riego<svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 4 4 4-4" />
                </svg>
            </button>
            <div id="dropdownDelay"
                class="dropdown dropdown-menu hidden absolute pt-6 px-4 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <!-- Contenido del menú desplegable -->
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDelayButton">
                    <li>
                        <a href="#"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dashboard</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Earnings</a>
                    </li>
                    <li>
                        <a href="#"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign
                            out</a>
                    </li>
                </ul>
            </div>

            <!-- End Dropdown Menu -->

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="shadow-md bg-yellow-500 text-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">
                        Temperatura Ambiente
                    </h3>
                    <p class="text-2xl">
                    <?php echo $tempAmbiente; ?>°C
                    </p>
                </div>
                <div class="bg-red-500 text-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">
                        Humedad Ambiente
                    </h3>
                    <p class="text-2xl">
                        <?php echo $humAmbiente; ?>°C
                    </p>
                </div>
                <div class="bg-green-500 text-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">
                        Porcentaje de Humedad en Tierra
                    </h3>
                    <p class="text-2xl">
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
                    include 'conexion.php';
        
                    $sql = "SELECT * FROM ciclo";
                    $result = $conn->query($sql);
        
                    if ($result->num_rows > 0) {
                        // Salida de cada fila de datos
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='py-2 px-4 border'>" . $row["id"] . "</td>";
                            echo "<td class='py-2 px-4 border'>" . $row["temperatura"] . "</td>";
                            echo "<td class='py-2 px-4 border'>" . $row["humedad_aire"] . "</td>";
                            echo "<td class='py-2 px-4 border'>" . $row["humedad_tierra"] . "</td>";
                            echo "<td class='py-2 px-4 border'>" . $row["estado_rele"] . "</td>";
                            echo "<td class='py-2 px-4 border'>" . $row["creacion"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='py-2 px-4 border text-center'>No hay resultados</td></tr>";
                    }
        
                    $conn->close();
                    ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
