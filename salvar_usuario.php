<?php
require_once 'conexao.php';
require_once 'funcoes.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $senha = password_hash(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING), PASSWORD_DEFAULT);
    $telefone = filter_var($_POST['telefone'], FILTER_SANITIZE_NUMBER_INT);

    if (!$username || !$nome || !$email || !$senha) {
        echo "Todos os campos são obrigatórios";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "E-mail inválido";
        exit;
    }

    if (cadastra_usuario($username, $nome, $senha, $telefone, $email)) {
        echo 'Usuário cadastrado com sucesso!';
    } else {
        echo 'Ocorreu um erro ao cadastrar o usuário.';
    }
}