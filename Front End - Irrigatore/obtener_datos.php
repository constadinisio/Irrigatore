<?php
	$conn = new mysqli('127.0.0.2', 'root', '', 'irrigatore');

	if ($conn->connect_error) {
	    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
	}

	$sql = "SELECT temperatura, humedad_aire, humedad_tierra, estado_rele, 	creacion FROM ciclo ORDER BY id DESC";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    $row = $result->fetch_assoc();
	    echo json_encode($row);
	} else {
	    echo json_encode(['error' => 'No data found']);
	}

	echo json_encode($data);
	$conn->close();
?>