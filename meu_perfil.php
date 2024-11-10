<?php
session_start();
require_once 'funcoes.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;
if ($usuario_id) {
    $usuario = retorna_usuario($usuario_id);

    if ($usuario) {
        $username = htmlspecialchars($usuario['username']);
        $email = htmlspecialchars($usuario['email']);
        $telefone = htmlspecialchars($usuario['telefone']);
        $imagem_perfil = htmlspecialchars($usuario['imagem'] ?? 'default_image.jpg');
    } else {
        echo "Erro ao carregar os dados do usuário.";
        exit;
    }         
} else {
    echo "Usuário não autenticado.";
    exit;
}    
?>

<div class="container mt-4">
    <form id="formPerfil">
        <img src="<?php echo $imagem_perfil; ?>" alt="Imagem do perfil" class="img-thumbnail" id="imgPerfil" style="cursor:pointer;">
        
        <div class="mb-3">
            <label for="username" class="form-label">Nome de Usuário</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>">
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
        </div>
        
        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo $telefone; ?>">
        </div>
        
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

<script>
    document.getElementById('imgPerfil').onclick = function() {
        const inputFile = document.createElement('input');
        inputFile.type = 'file';
        inputFile.accept = 'image/*';
        
        inputFile.onchange = function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imgPerfil').src = e.target.result;
                    
                    // Enviar a imagem para o servidor
                    const formData = new FormData();
                    formData.append('imagem', file);
                    
                    $.ajax({
                        url: 'atualizar_imagem_perfil.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            const res = JSON.parse(response);
                            if (res.success) {
                                alert('Imagem atualizada com sucesso!');
                            } else {
                                alert(res.message || 'Erro ao atualizar imagem.');
                            }
                        },
                        error: function() {
                            alert('Erro ao enviar a imagem.');
                        }
                    });
                };
                reader.readAsDataURL(file);
            }
        };
        
        inputFile.click();
    };

    $('#formPerfil').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: 'atualizar_perfil.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                const res = JSON.parse(response);
                alert(res.message || 'Perfil atualizado com sucesso!');
                $('#modalMeuPerfil').modal('hide');
            },
            error: function() {
                alert('Erro ao atualizar perfil.');
            }
        });
    });
</script>