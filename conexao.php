<?php
function conectar_banco() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "agenda";

    $conn = new mysqli($servername, $username, $password);


    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }


    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === FALSE) {
        die("Erro ao criar banco de dados: " . $conn->error);
    }

    $conn->select_db($dbname);


    $sql_usuarios = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        nome VARCHAR(100) NOT NULL,
        senha VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL,
        telefone VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";


    $sql_contatos = "CREATE TABLE IF NOT EXISTS contatos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        telefone VARCHAR(20),
        email VARCHAR(100),
        usuario_id INT,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
    )";


    if ($conn->query($sql_usuarios) === FALSE) {
        die("Erro ao criar tabela usuários: " . $conn->error);
    }
    if ($conn->query($sql_contatos) === FALSE) {
        die("Erro ao criar tabela contatos: " . $conn->error);
    }

    return $conn;
}