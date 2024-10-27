<?php
$ip = '127.0.0.1'; // IP a pingear
$timeout = 1; // Tiempo de espera en segundos

$ping = "ping -c 1 -w $timeout $ip";
exec($ping, $output, $return_var);



// Determinar el estado
if ($return_var === 0) {
    $status = 'OK'; // Ping exitoso
} else {
    $status = 'ERROR'; // Cualquier error
}

// Devolver el estado en formato JSON
echo json_encode(['status' => $status]);
?>
