<?php
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$id = $_POST['id'] ?? null;
$nome = $_POST['nome'] ?? null;
$telefone = $_POST['telefone'] ?? null;
$email = $_POST['email'] ?? null;
$usuario_id = $_SESSION['usuario_id'];

if ($id && $nome && $telefone && $email) {
    if (atualizar_contato($id, $nome, $telefone, $email, $usuario_id)) {
        echo json_encode(['success' => true, 'message' => 'Contato atualizado com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o contato.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos fornecidos.']);
}
