<?php
    $ip = '192.168.1.78'; // IP a pingear
    $timeout = 1; // Tiempo de espera en segundos
    
    $ping = "ping -c 1 -w $timeout $ip";
    exec($ping, $output, $return_var);


    // Determinar el estado
    if ($return_var === 0) {
        $status = 'OK';
    } else {
        $status = 'ERROR';
    }

    // Devolver el estado en formato JSON
    echo json_encode(['status' => $status]);
?>
