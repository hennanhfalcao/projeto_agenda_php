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

##função que busca e retorna o id do contato por meio do nome.
function retorna_id($nome) {
    $conn = conectar_banco();
    $sql = "SELECT id FROM contatos WHERE nome LIKE '%$nome%'";
    $result = $conn->query($sql);
    #Se o usuário for encontrado, retorna
    if ($result->num_rows > 0) {
        #recebe a tupla com o contato 
        $row = $result ->fetch_assoc();
        $conn -> close();
        #retorna o id do contato
        return $row['id'];
    } else {
        $conn -> close();
        return false;
    }
}

function buscar_contato_por_nome($nome) {
    $conn = conectar_banco();
    $nome_padronizado = strtolower($nome);
    $sql = "SELECT * FROM contatos WHERE nome LIKE '%$nome_padronizado%'";
    $result = $conn->query($sql);


    $contatos_encontrados = [];
    while ($row = $result->fetch_assoc()) {
        $contatos_encontrados[] = $row;
    }

    $conn -> close();
    return $contatos_encontrados;
}
function atualizar_contato($id, $nome, $telefone, $email) {
    $conn = conectar_banco();
    $sql = "UPDATE contatos SET nome = '$nome', telefone='$telefone', email='$email' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Contato atualizado com sucesso";
    } else {
        echo "Erro ao atualizar contato: " . $conn->error;
    }
    $conn->close();
}

function apagar_contato($nome) {
    $id = retorna_id($nome); 

    if ($id) {
        $conn = conectar_banco();
        $sql = "DELETE FROM contatos WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo "Contato excluído com sucesso.";
        } else {
            echo "Erro ao excluir contato: " . $conn->error;
        }
        $conn->close();
    } else {
        echo "Contato não encontrado.";
    }
}

##importante criar páginas para solicitar as informações as quais iremos usar nas funções de editar e apagar
#caso exista contatos, a função os mostra em uma tabela no html
function listar_contatos() {
    $conn = conectar_banco();
    $sql = "SELECT * FROM contatos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table'>";
        echo "<thead><tr><th>ID</th><th>Nome</th><th>Telefone</th><th>Email</th><th>Ações</th></tr></thead>";
        echo "<tbody>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["nome"] . "</td>";
            echo "<td>" . $row["telefone"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td><a href='editar.php?id=" . $row["id"] . "'>Editar</a> | <a href='apagar.php?id=" . $row["id"] . "'>Apagar</a></td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "Nenhum contato encontrado.";
    }
    $conn->close();
}
?>