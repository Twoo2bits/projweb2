<?php
session_start();
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Requisição inválida.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['carrinho'])) {
        $_SESSION['carrinho_de_compras'] = $data['carrinho'];
        $response = ['status' => 'success', 'message' => 'Carrinho salvo com sucesso!', 'carrinho' => $_SESSION['carrinho_de_compras']];
    } else {
        $response['message'] = 'Dados do carrinho ausentes na requisição POST.';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['acao']) && $_GET['acao'] === 'carregar') {
   
    $carrinho_atual = $_SESSION['carrinho_de_compras'] ?? [];
    $response = ['status' => 'success', 'carrinho' => $carrinho_atual];
}

echo json_encode($response);
?>
