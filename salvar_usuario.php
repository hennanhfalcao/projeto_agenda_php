<?php
header('Content-Type: application/json');
require_once 'conexao.php';
require_once 'funcoes.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Captura e valida todos os campos de uma vez
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, var_name:"email", options: FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, "senha", FILTER_SANITIZE_STRING);
    $telefone = filter_input(INPUT_POST, "telefone", FILTER_SANITIZE_STRING);

    if (!$username || !$nome || !$email || !$senha || !$telefone) {
        echo json_encode(["status" => "error", "message" => "Todos os campos são obrigatórios"]);
        exit;
    }

    $senha_hash = password_hash($senha, algo: PASSWORD_DEFAULT);

    $resultado = cadastra_usuario($username, $nome, $senha_hash, $telefone, $email);

    if ($resultado) {
        echo json_encode(["status" => "success", "message" => "Usuário cadastrado com sucesso!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Ocorreu um erro ao cadastrar o usuário."]);
    }
}