<?php
require_once('../includes/funcoes.php');
require_once('../includes/conexao.php');

session_start();

if (!isset($_SESSION['usuario_id'])) { 
    die(json_encode(["success" => false, "message" => "Usuário não autenticado."])); 
}

$usuario_id = $_SESSION['usuario_id'];

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $imagem_nome = basename($_FILES['imagem']['name']);
    $imagem_temp = $_FILES['imagem']['tmp_name'];
    $imagem_caminho = "static_files/" . $imagem_nome;

    if (!file_exists("static_files")) { mkdir("static_files", 0777, true); }

    if (move_uploaded_file($imagem_temp, $imagem_caminho)) {
        if (atualizar_imagem_usuario($usuario_id, $imagem_caminho)) {
            echo json_encode(['success' => true, 'imageUrl' => $imagem_caminho]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar imagem no banco de dados.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro no upload da imagem.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nenhuma imagem enviada ou erro no upload.']);
}