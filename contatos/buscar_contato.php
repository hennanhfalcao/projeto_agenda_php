<?php

require_once('../includes/funcoes.php');
require_once('../includes/conexao.php');
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode([]);
    exit;
}

$usuario_id = retorna_usuario_id($_SESSION['username']);
$nome = $_POST['nome'] ??'';


$contatos = buscar_contato_por_nome_e_usuario($nome, $usuario_id);
echo json_encode($contatos);