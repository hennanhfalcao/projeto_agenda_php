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
    <title>Agenda Telefonica</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
    body {
        background-color: #f0ffff;
    }
</style>
</head>
<body>
<div class="container text-center mt-5">
    <div class="d-flex justify-content-center mb-4">
        <div class="d-flex justify-content-between align-items-center" style="width: auto;">
            <h1>Bem-vindo, <?php echo htmlspecialchars(string: strtoupper($_SESSION['username'])); ?>!</h1>
            <button class="btn btn-primary btn-lg ms-3" data-bs-toggle="modal" data-bs-target="#modalMeuPerfil">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                </svg>
            </button>
        </div>
    </div>
</div>

    <div class="d-flex justify-content-center flex-wrap gap-3">
            <button type="button" class="btn btn-primary menu-button" data-bs-toggle="modal" data-bs-target="#modalNovoContato">Novo Contato</button>
            <button type="button" class="btn btn-primary menu-button" data-bs-toggle="modal" data-bs-target="#modalBuscaContato">Buscar Contato</button>
            <button type="button" class="btn btn-primary menu-button" id="btnListarTodosContatos">Listar Contatos</button>
            <form action="logout.php" method="post">
                <button type="submit" class="btn btn-danger menu-button">Sair</button>
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
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Buscar Contato -->
    <div class="modal fade" id="modalBuscaContato" tabindex="-1" aria-labelledby="modalBuscaContatoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBuscaContatoLabel">Buscar Contato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="campoBuscaContato" class="form-control" placeholder="Digite o nome do contato">
                    <div id="resultadosBusca" class="mt-3"></div>
                    <div id="detalhesContato" class="mt-4" style="display: none;">
                        <h6>Detalhes do Contato</h6>
                        <p><strong>Nome:</strong> <span id="detalheNome"></span></p>
                        <p><strong>Telefone:</strong> <span id="detalheTelefone"></span></p>
                        <p><strong>Email:</strong> <span id="detalheEmail"></span></p>
                        <button id="editarContato" class="btn btn-primary">Editar</button>
                        <button id="apagarContato" class="btn btn-danger">Apagar</button>
                    </div>
                    <form id="formEditarContato" class="mt-4" style="display: none;">
                        <h6>Editar Contato</h6>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="editarNome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="editarTelefone" name="telefone" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editarEmail" name="email" required>
                        </div>
                        <input type="hidden" id="editarId" name="id">
                        <button type="submit" class="btn btn-success">Salvar Alterações</button>
                        <button type="button" id="cancelarEdicao" class="btn btn-secondary">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalListarContatos" tabindex="-1" aria-labelledby="modalListarContatosLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalListarContatosLabel">Listar Todos os Contatos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="resultadosListaContatos" class="mt-3" style="max-height: 300px; overflow-y: auto;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMeuPerfil" tabindex="-1" aria-labelledby="modalMeuPerfilLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modalProfileContent">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMeuPerfilLabel">Meu Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalProfileContent">
                    
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <script>
$(document).ready(function() {

    $('#modalNovoContato').on('show.bs.modal', function() {
    $('.modal-body', this).load('salvar_contato.html');
    });

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
                            html += `<div class="contact-item" data-id="${contato.id}" data-nome="${contato.nome}" data-telefone="${contato.telefone}" data-email="${contato.email}">
                                        <strong>${contato.nome}</strong> - ${contato.telefone} - ${contato.email}
                                    </div>`;
                        });
                    } else {
                        html = '<p>Nenhum contato encontrado.</p>';
                    }
                    $('#resultadosBusca').html(html);

                    $(document).on('click', '.contact-item', function() {
                        const id = $(this).data('id');
                        const nome = $(this).data('nome');
                        const telefone = $(this).data('telefone');
                        const email = $(this).data('email');
                        $('#detalheNome').text(nome);
                        $('#detalheTelefone').text(telefone);
                        $('#detalheEmail').text(email);
                        $('#detalhesContato').show();
                        $('#formEditarContato').hide();
                        $('#editarContato').off().on('click', function () {
                            $('#editarNome').val(nome);
                            $('#editarTelefone').val(telefone);
                            $('#editarEmail').val(email);
                            $('#editarId').val(id);
                            $('#detalhesContato').hide();
                            $('#formEditarContato').show();
                        });

                        $('#apagarContato').off().on('click', function() {
                            if (confirm('Deseja realmente excluir este contato?')) {
                                $.ajax({
                                    url: 'apagar_contato.php',
                                    type: 'POST',
                                    data: { id: id },
                                    success: function(res) {
                                        alert(res.message || 'Contato excluído com sucesso');
                                        $('#detalhesContato').hide();
                                        $('#campoBuscaContato').trigger('input');
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

    $('#cancelarEdicao').click(function () {
        $('#formEditarContato').hide();
        $('#detalhesContato').show();
    });

    $('#formEditarContato').submit(function (e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: 'atualizar_contato.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                alert(response.message || 'Contato atualizado com sucesso!');
                $('#modalBuscaContato').modal('hide');
                $('#campoBuscaContato').trigger('input');
            },
            error: function () {
                alert('Erro ao atualizar contato.');
            },
        });
    });

    $('#btnListarTodosContatos').click(function() {
        $.ajax({
            url: 'listar_contatos.php',
            type: 'POST',
            success: function(response) {
                let contatos = JSON.parse(response);
                let html = '';
                if (contatos.length > 0) {
                    contatos.forEach(function(contato) {
                        html += `<div class="contact-item" data-id="${contato.id}" data-nome="${contato.nome}" data-telefone="${contato.telefone}" data-email="${contato.email}">
                                    <strong>${contato.nome}</strong> - ${contato.telefone} - ${contato.email}
                                    </div>`;
                    });
                } else {
                    html = '<p>Nenhum contato encontrado.</p>';
                }
                $('#resultadosListaContatos').html(html);
                $('#modalListarContatos').modal('show');
                
            },
            error: function() {
                $('#resultadosListaContatos').html('<p>Erro ao carregar contatos.</p>');
            }
        });
    });

    $('#modalMeuPerfil').on('show.bs.modal', function() {
            $('.modal-body', this).load('meu_perfil.php'); {
            }
        });
});
</script>
</body>
</html>