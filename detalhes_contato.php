<?php
require_once 'conexao.php';
require_once 'funcoes.php';

if (isset($_GET['id'])) {
    $contato_id = $_GET['id'];
    $usuario_id = $_SESSION['usuario_id']; 

    $conn = conectar_banco();
    $stmt = $conn->prepare("SELECT * FROM contatos WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $contato_id, $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $contato = $result->fetch_assoc();
        echo "
            <h5>Detalhes do Contato</h5>
            <p><strong>Nome:</strong> {$contato['nome']}</p>
            <p><strong>Telefone:</strong> {$contato['telefone']}</p>
            <p><strong>Email:</strong> {$contato['email']}</p>
            <div class='d-flex gap-2'>
                <a href='editar_contato.php?id={$contato['id']}' class='btn btn-primary'>Editar</a>
                <a href='apagar_contato.php?id={$contato['id']}' class='btn btn-danger'>Apagar</a>
            </div>";
    } else {
        echo "<p>Contato não encontrado.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Erro: ID do contato não fornecido.</p>";
}