<?php
require_once('funcoes.php');
require_once('conexao.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Usuário não autenticado.");
}

$user_id = $_SESSION['user_id'];
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$telefone = $_POST['telefone'] ?? null;

if ($username && $email && $telefone) {
    if (atualizar_dados_usuario($user_id, $username, $email, $telefone)) {
        echo json_encode(['success' => true, 'message' => 'Perfil atualizado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar perfil.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
}
