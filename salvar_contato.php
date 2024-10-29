<?php
require_once("conexao.php");
require_once("funcoes.php");

session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "message" => "Usuário não autenticado."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = filter_input(INPUT_POST,"nome", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST,"email", FILTER_VALIDATE_EMAIL);
    $telefone = filter_var($_POST['telefone'], FILTER_SANITIZE_NUMBER_INT);
    $usuario_id = $_SESSION['usuario_id'];

    if (!$nome || !$telefone) {
        echo json_encode(["status" => "error", "message" => "Os campos nome e telefone são obrigatórios."]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status"=> "error", "message"=> "E-mail inválido"]);
        exit;
    }

    if (salvar_contato($nome, $telefone, $email, $usuario_id)) {
        echo json_encode(["status"=> "success","message"=> "Contato cadastrado com sucesso!"]);
    } else {
        echo json_encode(["status"=> "error","message"=> "Ocorreu um erro ao cadastrar o contato."]);
    }
}