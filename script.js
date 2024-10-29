function salvarCadastro() {
    const username = $('#username').val();
    const nome = $('#nome').val();
    const telefone = $('#telefone').val();
    const email = $('#email').val();
    const senha = $('#senha').val();

    $.ajax({
        url: 'salvar_usuario.php',
        method: 'POST',
        data: { username, nome, telefone, email, senha },
        success: function(response) {
            console.log(response);
            $('#modalCadastro').modal('hide');
            alert(response);
        },
        error: function(error) {
            console.error(error);
            alert('Ocorreu um erro ao cadastrar o usuário.');
        }
    });
}