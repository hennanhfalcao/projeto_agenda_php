<?php
require_once('../includes/conexao.php');

function cadastra_usuario($username, $nome, $senha, $telefone, $email) {
    $conn = conectar_banco();
    $stmt = $conn->prepare("INSERT INTO usuarios (username, nome, senha, telefone, email, imagem) VALUES (?, ?, ?, ?, ?, NULL)");
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

function retorna_usuario($usuario_id) {
    $conn = conectar_banco();
    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Retorna os dados do usuário
        } else {
            return null;
        }
    } catch (mysqli_sql_exception $e) {
        echo 'Erro ao obter dados do usuário: ' . $e->getMessage();
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

        // Verifica se o contato foi realmente atualizado
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception $e) {
        error_log("Erro ao atualizar contato: " . $e->getMessage());
        return false;
    } finally {
        if (isset($stmt)) {
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
    $stmt = $conn->prepare("SELECT * FROM contatos WHERE usuario_id = ?");
    $stmt ->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $contatos = [];
    while ($row = $result->fetch_assoc()) {
        $contatos[] = $row;
    }
    return json_encode($contatos);
}

function atualizar_imagem_usuario($usuario_id, $imagem_caminho) {
    $conn = conectar_banco();
    try {
        $stmt = $conn->prepare("UPDATE usuarios SET imagem = ? WHERE id = ?");
        $stmt->bind_param("si", $imagem_caminho, $usuario_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Imagem do perfil atualizada com sucesso.";
            return true;
        } else {
            echo "Erro ao atualizar imagem do perfil.";
            return false;
        }
    } catch (mysqli_sql_exception $e) {
        echo "Erro ao atualizar imagem do perfil: " . $e->getMessage();
        return false;
    } finally {
        $stmt->close();
        $conn->close();
    }
}

function atualizar_dados_usuario($usuario_id, $username, $email, $telefone) {
    $conn = conectar_banco();
    $stmt = $conn->prepare("UPDATE usuarios SET username = ?, email = ?, telefone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $telefone, $usuario_id);
    
    $resultado = $stmt->execute();
    $stmt->close();
    $conn->close();

    return $resultado;
}