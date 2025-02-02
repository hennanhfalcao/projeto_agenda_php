<?php
require_once('../includes/funcoes.php');
require_once('../includes/conexao.php');

session_start();

if (!isset($_SESSION['usuario_id'])) { die("Usuário não autenticado."); }

$usuario_id = $_SESSION['usuario_id'];
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$telefone = $_POST['telefone'] ?? null;

if ($username && $email && $telefone) {
    if (atualizar_dados_usuario($usuario_id, $username, $email, $telefone)) {
        echo json_encode(['success' => true, 'message' => 'Perfil atualizado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar perfil.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
}