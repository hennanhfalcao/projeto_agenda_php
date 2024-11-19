<?php
require_once '../includes/conexao.php';
require_once '../includes/funcoes.php';

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
                <a href='../contatos/editar_contato.php?id={$contato['id']}' class='btn btn-primary'>Editar</a>
                <a href='../contatos/apagar_contato.php?id={$contato['id']}' class='btn btn-danger'>Apagar</a>
            </div>";
        
    } else {
        echo "<p>Contato não encontrado.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>Erro: ID do contato não fornecido.</p>";
}

    echo " <script>
            $('#editarContato').on('click', function() {
                const id = $(this).data('id');
                window.location.href = '../contatos/editar_contato.php?id=' + id;
            });

            $('#apagarContato').on('click', function() {
                const id = $(this).data('id');
                if (confirm('Deseja realmente excluir este contato?')) {
                    $.ajax({
                        url: '../contatos/apagar_contato.php',
                        type: 'POST',
                        data: { id: id },
                        success: function(res) {
                            const response = JSON.parse(res);
                            alert(response.message || 'Contato excluído com sucesso');
                            window.location.href = '../usuario/home.php';
                        },
                        error: function() {
                            alert('Erro ao excluir o contato.');
                        }
                    });
                }
            });
            $('#formEditarContato').on('submit', function(e) {
                e.preventDefault();
                
                const formData = $(this).serialize();
                
                $.ajax({
                    url: '../contatos/atualizar_contato.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response.message || 'Contato atualizado com sucesso!');
                        window.location.reload();
                    },
                    error: function() {
                        alert('Erro ao atualizar contato.');
                    }
                });
            });
    </script>";