<?php
require 'conexao.php';

function salvar_contato($nome, $telefone, $email) {
    
    #Conecta ao banco de dados e insere um contato na tabela contatos
    $conn = conectar_banco();
    $sql = "INSERT INTO contatos (nome, telefone, email) VALUES ('$nome', '$telefone', '$email')";

    if ($conn -> query($sql) === TRUE) {
        echo "Contato salvo com sucesso";
    } else {
        echo "Erro ao salvar contato: " . $conn->error;
    }

    #fecha a conexão com o banco de dados
    $conn->close();

}

##function apagar_contato()

?>