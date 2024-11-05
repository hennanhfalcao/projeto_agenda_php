<?php
require_once('funcoes.php');
require_once('conexao.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Usuário não autenticado.");
}

$user_id = $_SESSION['user_id'];

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
    $imagem_nome = $_FILES['imagem']['name'];
    $imagem_temp = $_FILES['imagem']['tmp_name'];
    $imagem_caminho = "uploads/" . basename($imagem_nome);

    if (!file_exists("uploads")) {
        mkdir("uploads", 0777, true);
    }

    if (move_uploaded_file($imagem_temp, $imagem_caminho)) {
        if (atualizar_imagem_usuario($user_id, $imagem_caminho)) {
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
