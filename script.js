function salvarCadastro() {
    const username = $('#username').val();
    const nome = $('#nome').val();
    const telefone = $('#telefone').val();
    const email = $('#email').val();
    const senha = $('#senha').val();
    // Envie os dados para o servidor usando AJAX
    $.ajax({
        url: 'salvar_usuario.php',
        method: 'POST',
        data: { username: username, nome: nome, telefone: telefone, email: email, senha: senha },
        success: function(response) {
            console.log(response);
            $('#modalCadastro').modal('hide');
            alert('Usuário cadastrado com sucesso!');
    },
        error: function(error) {
            console.error(error);
            alert('Ocorreu um erro ao cadastrar o usuário.');
    }
    });
}