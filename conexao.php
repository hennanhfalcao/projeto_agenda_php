<?php
function conectar_banco() {
    $servername = "localhost";
    $login = "root";
    $password = "";
    $db_name = "contatos";

    $conn = new mysqli($servername, $login, $password, $db_name);
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
}
    return $conn;
}

