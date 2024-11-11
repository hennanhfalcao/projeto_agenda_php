<?php
require_once('funcoes.php');
require_once('conexao.php');
session_start();

if (!isset($_SESSION['usuario_id'])) {
    die("Usuário não autenticado.");
}

$usuario_id = $_SESSION['usuario_id'];
$contato_id = $_GET['id'] ?? null;

if ($contato_id) {
    $conn = conectar_banco();
    $stmt = $conn->prepare("SELECT * FROM contatos WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $contato_id, $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $contato = $result->fetch_assoc();
        $nome = htmlspecialchars($contato['nome']);
        $telefone = htmlspecialchars($contato['telefone']);
        $email = htmlspecialchars($contato['email']);
    } else {
        echo "Contato não encontrado.";
        exit;
    }
} else {
    echo "ID do contato não fornecido.";
    exit;
}
?>

<form id="formEditarContato">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $nome; ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo $telefone; ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
    </div>
    
    <input type="hidden" name="id" value="<?php echo $contato_id; ?>">
    
    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
</form>

<script>
$('#formEditarContato').on('submit', function(e) {
    e.preventDefault();
    
    const formData = $(this).serialize();
    
    $.ajax({
        url: 'atualizar_contato.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            alert(response.message || 'Contato atualizado com sucesso!');
            window.location.href = 'home.php'; // Redireciona após a atualização
        },
        error: function() {
            alert('Erro ao atualizar contato.');
        }
    });
});
</script>