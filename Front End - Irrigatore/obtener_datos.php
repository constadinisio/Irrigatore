<?php
	$conn = new mysqli('ip_mysql', 'mysql_user', 'mysql_pw', 'db_name');

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

	$conn->close();
?>