<?php
    $ip = 'ip_esp'; // IP de la placa de desarrollo que estemos usando.
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
