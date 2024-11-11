<?php
require_once('funcoes.php');
require_once('conexao.php');
session_start();

if (!isset($_SESSION['usuario_id'])) {
    die(json_encode(["success" => false, "message" => "Usuário não autenticado."]));
}

$usuario_id = $_SESSION['usuario_id'];
$contato_id = $_POST['id'] ?? null;

if ($contato_id) {
    if (apagar_contato($contato_id, $usuario_id)) {
        echo json_encode(['success' => true, 'message' => 'Contato excluído com sucesso.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir contato.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do contato não fornecido.']);
}