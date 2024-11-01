<?php
require_once 'conexao.php';

function cadastra_usuario($username, $nome, $senha, $telefone, $email) {
    $conn = conectar_banco();
    $stmt = $conn->prepare("INSERT INTO usuarios (username, nome, senha, telefone, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $nome, $senha, $telefone, $email);

    $resultado = $stmt->execute();
    $stmt->close();
    $conn->close();

    return $resultado;
}

function verificar_usuario($username, $senha) {
    $conn = conectar_banco();
    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result-> num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($senha, $row['senha'])) {
                return true;
            }
        }
        return false;
    } catch(mysqli_sql_exception $e) {
        echo 'Erro ao verificar usuário: ' . $e->getMessage();
    } finally {
        $stmt->close();
        $conn->close();
    }
}
function salvar_contato($nome, $telefone, $email, $usuario_id) {
    $conn = conectar_banco();
    try {
        $stmt = $conn->prepare("INSERT INTO contatos (nome, telefone, email, usuario_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nome, $telefone, $email, $usuario_id);
        $stmt->execute();
        return true;
    } catch (mysqli_sql_exception $e) {
        echo "Erro ao salvar contato: " . $e->getMessage();
        return false;
    } finally {
        if ($stmt) {
            $stmt->close();
        }
        $conn->close();
    }
}

##função que busca e retorna o id do contato por meio do nome.
function retorna_id($nome, $usuario_id) {
    $conn = conectar_banco();
    try {
        $stmt = $conn->prepare("SELECT id FROM contatos WHERE nome LIKE ? AND usuario_id = ?");
        $nome_wildcard = "%nome%";
        $stmt->bind_param("si", $nome_wildcard, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        #Se o usuário for encontrado, retorna
        if ($result->num_rows > 0) {
            #recebe a tupla com o contato 
            $row = $result ->fetch_assoc();
            #retorna o id do contato
            return $row['id'];
        } else {
            return false;
        }
    } catch(mysqli_sql_exception $e) {
        echo 'Erro ao buscar ID do contato: '. $e->getMessage();
        return false;
    } finally {
        $stmt->close();
        $conn->close();
    }
}

function retorna_usuario_id($username) {
    $conn = conectar_banco();
    try {
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            return null;
        }
    } catch (mysqli_sql_exception $e) {
        echo 'Erro ao obter ID do usuário: ' . $e->getMessage();
        return null;
    } finally {
        $stmt->close();
        $conn->close();
    }
}

function buscar_contato_por_nome_e_usuario($nome, $usuario_id) {
    $conn = conectar_banco();
    
    try {
        $stmt = $conn->prepare("SELECT * FROM contatos WHERE nome LIKE CONCAT('%', ?, '%') AND usuario_id = ?");
        $stmt->bind_param("si", $nome, $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $contatos_encontrados = [];
        while ($row = $result->fetch_assoc()) {
            $contatos_encontrados[] = $row;
        } 
        return $contatos_encontrados;
    } catch(mysqli_sql_exception $e) {
        echo "Erro ao buscar contatos: ". $e->getMessage();
        return [];
    } finally {
        $stmt->close();
        $conn->close();    
    }
}

function atualizar_contato($id, $nome, $telefone, $email, $usuario_id) {
    $conn = conectar_banco();
    try {
        $stmt = $conn->prepare("UPDATE contatos SET nome = ?, telefone = ?, email = ? WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("sssii", $nome, $telefone, $email, $id, $usuario_id);
        $stmt->execute();
        echo "Contato atualizado com sucesso";
    } catch (mysqli_sql_exception $e) {
        echo "Erro ao atualizar contato: " . $e->getMessage();
    } finally {
        if ($stmt) {
            $stmt->close();
        }
        $conn->close();
    }
}
function apagar_contato($id, $usuario_id) {
    $conn = conectar_banco();

    try {
        $stmt = $conn->prepare("DELETE FROM contatos WHERE id = ? AND usuario_id = ?");
        $stmt->bind_param("ii", $id, $usuario_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Contato excluído com sucesso.";
        } else {
            echo "Contato não encontrado ou você não tem permissão para excluir este contato.";
        }
    } catch (mysqli_sql_exception $e) {
        echo "Erro ao excluir contato: " . $e->getMessage();
    } finally {
        $stmt->close();
        $conn->close();
    }
}

##importante criar páginas para solicitar as informações as quais iremos usar nas funções de editar e apagar
#caso exista contatos, a função os mostra em uma tabela no html

function listar_contatos_usuario($usuario_id) {
    $conn = conectar_banco();
    try {
        $stmt = $conn->prepare("SELECT * FROM contatos WHERE usuario_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            if ($result->num_rows > 0) {
                echo "<table class='table table-striped table-bordered'>";
                echo "<thead><tr><th>ID</th><th>Nome</th><th>Telefone</th><th>Email</th><th>Ações</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["nome"] . "</td>";
                    echo "<td>" . $row["telefone"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td><a href='editar.php?id=" . $row["id"] . "' class='btn btn-primary btn-sm'>Editar</a> <a href='apagar.php?id=" . $row["id"] . "' class='btn btn-danger btn-sm'>Apagar</a></td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p class='text-center'>Nenhum contato encontrado.</p>";
            }
        } 
    } catch (mysqli_sql_exception $e) {
            echo "Erro ao listar contatos: " . $e->getMessage();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }
?>