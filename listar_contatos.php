<?php
session_start();
require 'funcoes.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
echo listar_contatos_usuario($usuario_id);
