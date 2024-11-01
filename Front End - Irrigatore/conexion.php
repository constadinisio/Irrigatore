<?php
     $servername = "127.0.0.2";
     $username = "root";
     $password = "";
     $dbname = "irrigatore";

     // Crear conexión
     $conn = new mysqli($servername, $username, $password, $dbname);

     // Comprobar conexión
     if ($conn->connect_error) {
         die("Conexión fallida: " . $conn->connect_error);
     }
?>