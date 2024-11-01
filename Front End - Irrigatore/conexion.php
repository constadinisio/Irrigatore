<?php
     $servername = "ip_mysql";
     $username = "mysql_user";
     $password = "mysql_pw";
     $dbname = "db_name";

     // Crear conexión
     $conn = new mysqli($servername, $username, $password, $dbname);

     // Comprobar conexión
     if ($conn->connect_error) {
         die("Conexión fallida: " . $conn->connect_error);
     }
?>