<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <!-- Botões do menu principal -->
            <button type="button" class="btn btn-primary menu-button" data-bs-toggle="modal" data-bs-target="#modalNovoContato">Novo Contato</button>
            <a href="buscar_contato.html" class="btn btn-primary menu-button">Buscar Contato</a>
            <a href="listar_contatos.php" class="btn btn-primary menu-button">Listar Todos os Contatos</a>
            <a href="minhas_informacoes.php" class="btn btn-primary menu-button">Meu Perfil</a>
            <form action="logout.php" method="post">
                <button type="submit" class="btn btn-danger menu-button">Logout</button>
            </form>
        </div>
    </div>


    <div class="modal fade" id="modalNovoContato" tabindex="-1" aria-labelledby="modalNovoContatoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNovoContatoLabel">Novo Contato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- O conteúdo será carregado dinamicamente aqui -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#modalCadastro').on('show.bs.modal', function (event) {
                $(this).find('.modal-body').load('cadastro.html');
            });
            
            $('#modalNovoContato').on('show.bs.modal', function (event) {
                $(this).find('.modal-body').load('salvar_contato.html');
            });
            
            $('#formLogin').submit(function(event) {
                event.preventDefault();
                var formData = {
                    username: $('#username').val(),
                    senha: $('#password').val()
                };

                $.ajax({
                    url: 'login.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = 'home.php';
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Erro de comunicação:', textStatus, errorThrown);
                        console.log('Resposta do servidor:', jqXHR.responseText);
                        alert('Erro na comunicação com o servidor: ' + textStatus + ' - ' + errorThrown);
                    }
                });
            });
        });
    </script>
</body>
</html>