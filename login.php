<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conexao.php';
require_once 'funcoes.php';
session_start();

header('Content-Type: application/json'); // Define o cabeçalho JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_SPECIAL_CHARS);

    if (verificar_usuario($username, $senha)) {
        
        $usuario_id = retorna_usuario_id($username);

        if ($usuario_id) {
            $_SESSION['usuario_id'] = $usuario_id;
            $_SESSION['username'] = $username;
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Usuário ou senha inválidos.']);
        }
        exit;
    }
}