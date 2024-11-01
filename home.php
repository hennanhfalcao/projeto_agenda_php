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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBuscaContato">Buscar Contato</button>
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

    <div class="modal fade" id="modalBuscaContato" tabindex="-1" aria-labelledby="modalBuscaContatoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBuscaContatoLabel">Buscar Contato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Campo de busca -->
                    <input type="text" id="campoBuscaContato" class="form-control" placeholder="Digite o nome do contato">
                    
                    <!-- Resultados da busca -->
                    <div id="resultadosBusca" class="mt-3"></div>

                    <!-- Detalhes do contato selecionado -->
                    <div id="detalhesContato" class="mt-4" style="display: none;">
                        <h6>Detalhes do Contato</h6>
                        <p><strong>Nome:</strong> <span id="detalheNome"></span></p>
                        <p><strong>Telefone:</strong> <span id="detalheTelefone"></span></p>
                        <p><strong>Email:</strong> <span id="detalheEmail"></span></p>
                        
                        <!-- Botões de Ação -->
                        <button id="editarContato" class="btn btn-primary">Editar</button>
                        <button id="apagarContato" class="btn btn-danger">Apagar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <script>
$(document).ready(function() {
    // Função para busca de contatos dinâmica
    $('#campoBuscaContato').on('input', function() {
        let query = $(this).val();

        if (query.length > 0) {
            $.ajax({
                url: 'buscar_contato.php',
                type: 'POST',
                data: { nome: query },
                dataType: 'json',
                success: function(response) {
                    let html = '';
                    if (response.length > 0) {
                        response.forEach(function(contato) {
                            html += `
                                <div class="contact-item" data-id="${contato.id}" data-nome="${contato.nome}" data-telefone="${contato.telefone}" data-email="${contato.email}">
                                    <strong>${contato.nome}</strong> - ${contato.telefone} - ${contato.email}
                                </div>`;
                        });
                    } else {
                        html = '<p>Nenhum contato encontrado.</p>';
                    }
                    $('#resultadosBusca').html(html);

                    // Configura evento de clique para exibir detalhes ao selecionar contato
                    $('.contact-item').click(function() {
                        const id = $(this).data('id');
                        const nome = $(this).data('nome');
                        const telefone = $(this).data('telefone');
                        const email = $(this).data('email');

                        // Preenche os detalhes do contato no modal
                        $('#detalheNome').text(nome);
                        $('#detalheTelefone').text(telefone);
                        $('#detalheEmail').text(email);
                        $('#detalhesContato').show();

                        // Configura o botão de edição para redirecionar à página de edição
                        $('#editarContato').off().on('click', function() {
                            window.location.href = `editar_contato.php?id=${id}`;
                        });

                        // Configura o botão de apagar para excluir o contato
                        $('#apagarContato').off().on('click', function() {
                            if (confirm('Deseja realmente excluir este contato?')) {
                                $.ajax({
                                    url: 'apagar_contato.php',
                                    type: 'POST',
                                    data: { id: id },
                                    success: function(res) {
                                        alert(res.message || 'Contato excluído com sucesso');
                                        $('#detalhesContato').hide();
                                        $('#campoBuscaContato').trigger('input'); // Atualiza a lista de contatos
                                    },
                                    error: function() {
                                        alert('Erro ao excluir o contato.');
                                    }
                                });
                            }
                        });
                    });
                },
                error: function() {
                    $('#resultadosBusca').html('<p>Erro ao buscar contatos</p>');
                }
            });
        } else {
            $('#resultadosBusca').html('');
            $('#detalhesContato').hide();
        }
    });

    // Carregamento do modal de cadastro
    $('#modalCadastro').on('show.bs.modal', function(event) {
        $(this).find('.modal-body').load('cadastro.html');
    });
    
    // Carregamento do modal para novo contato
    $('#modalNovoContato').on('show.bs.modal', function(event) {
        $(this).find('.modal-body').load('salvar_contato.html');
    });

    // Formulário de login
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