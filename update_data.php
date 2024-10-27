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