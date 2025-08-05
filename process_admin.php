<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php");
    exit();
}

$action = $_POST['action'] ?? '';
$message = '';

switch ($action) {
    case 'add_product':
        $category = $_POST['category'] ?? '';
        $name = $_POST['name'] ?? '';
        $price = str_replace(['.', ','], ['', '.'], $_POST['price'] ?? '0');
        $promo_price = !empty($_POST['promo_price']) ? str_replace(['.', ','], ['', '.'], $_POST['promo_price']) : NULL;
        $description = $_POST['description'] ?? '';
        $image_url = $_POST['image'] ?? ''; 

        if ($category && $name && $price && $description && $image_url) {
            $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, preco_promocional, imagem_url, categoria) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsss", $name, $description, $price, $promo_price, $image_url, $category);
            if ($stmt->execute()) {
                $message = "Produto '" . htmlspecialchars($name) . "' adicionado com sucesso!";
            } else {
                $message = "Erro ao adicionar produto: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Erro: Dados incompletos para adicionar produto.";
        }
        break;

    case 'edit_product':
        $id = $_POST['id'] ?? '';
        $category = $_POST['category'] ?? '';
        $name = $_POST['name'] ?? '';
        $price = str_replace(['.', ','], ['', '.'], $_POST['price'] ?? '0');
        $promo_price = !empty($_POST['promo_price']) ? str_replace(['.', ','], ['', '.'], $_POST['promo_price']) : NULL;
        $description = $_POST['description'] ?? '';
        $image_url = $_POST['image'] ?? ''; 

        if ($id && $category && $name && $price && $description && $image_url) {
            $stmt = $conn->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, preco_promocional=?, imagem_url=?, categoria=? WHERE id=?");
            $stmt->bind_param("ssdsssi", $name, $description, $price, $promo_price, $image_url, $category, $id);
            if ($stmt->execute()) {
                $message = "Produto '" . htmlspecialchars($name) . "' atualizado com sucesso!";
            } else {
                $message = "Erro ao atualizar produto: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Erro: Dados incompletos para editar produto.";
        }
        break;

    case 'delete_product':
        $id = $_POST['id'] ?? '';
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM produtos WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $message = "Produto excluído com sucesso!";
            } else {
                $message = "Erro ao excluir produto: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Erro: ID do produto não fornecido para exclusão.";
        }
        break;

    case 'toggle_featured':
        $id = $_POST['id'] ?? '';
        if ($id) {
            $stmt_check = $conn->prepare("SELECT is_featured FROM produtos WHERE id = ?");
            $stmt_check->bind_param("i", $id);
            $stmt_check->execute();
            $current_status = $stmt_check->get_result()->fetch_assoc()['is_featured'];
            $stmt_check->close();

            $new_status = $current_status ? 0 : 1;

            $stmt_update = $conn->prepare("UPDATE produtos SET is_featured = ? WHERE id = ?");
            $stmt_update->bind_param("ii", $new_status, $id);
            if ($stmt_update->execute()) {
                $message = "Status de destaque do produto atualizado com sucesso!";
            } else {
                $message = "Erro ao atualizar o status de destaque.";
            }
            $stmt_update->close();
        } else {
            $message = "Erro: ID do produto não fornecido.";
        }
        break;

    case 'add_news':
        $titulo = $_POST['titulo'] ?? '';
        $data_publicacao = date('Y-m-d');
        $imagem_url = $_POST['imagem_url'] ?? ''; 
        $conteudo_completo = $_POST['conteudo_completo'] ?? '';

        if ($titulo && $imagem_url && $conteudo_completo) {
            $stmt = $conn->prepare("INSERT INTO noticias (titulo, data_publicacao, imagem_url, conteudo_completo) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $titulo, $data_publicacao, $imagem_url, $conteudo_completo);
            if ($stmt->execute()) {
                $message = "Notícia '" . htmlspecialchars($titulo) . "' adicionada com sucesso!";
            } else {
                $message = "Erro ao adicionar notícia: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Erro: Dados incompletos para adicionar notícia.";
        }
        break;

    case 'edit_news':
        $id = $_POST['id'] ?? '';
        $titulo = $_POST['titulo'] ?? '';
        $imagem_url = $_POST['imagem_url'] ?? ''; 
        $conteudo_completo = $_POST['conteudo_completo'] ?? '';

        if ($id && $titulo && $imagem_url && $conteudo_completo) {
            $stmt = $conn->prepare("UPDATE noticias SET titulo=?, imagem_url=?, conteudo_completo=? WHERE id=?");
            $stmt->bind_param("sssi", $titulo, $imagem_url, $conteudo_completo, $id);
            if ($stmt->execute()) {
                $message = "Notícia '" . htmlspecialchars($titulo) . "' atualizada com sucesso!";
            } else {
                $message = "Erro ao atualizar notícia: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Erro: Dados incompletos para editar notícia.";
        }
        break;

    case 'delete_news':
        $id = $_POST['id'] ?? '';
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM noticias WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $message = "Notícia excluída com sucesso!";
            } else {
                $message = "Erro ao excluir notícia: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Erro: ID da notícia não fornecido para exclusão.";
        }
        break;

    case 'delete_user':
        $id = $_POST['id'] ?? '';
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $message = "Usuário excluído com sucesso!";
            } else {
                $message = "Erro ao excluir usuário: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Erro: ID do usuário não fornecido para exclusão.";
        }
        break;

    default:
        $message = "Ação inválida.";
        break;
}

$conn->close();

$_SESSION['admin_message'] = $message;
header("Location: admin.php");
exit();
?>
