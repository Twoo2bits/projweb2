<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_type = $_POST['item_type'] ?? '';
    $item_id = $_POST['item_id'] ?? null;

    if ($item_type === 'config' && $item_id !== null) {
        if (isset($_SESSION['configuracao_pc'][$item_id])) {
            unset($_SESSION['configuracao_pc'][$item_id]);
            $response['success'] = true;
            $response['message'] = 'Item de configuração removido.';
        } else {
            $response['message'] = 'Item de configuração não encontrado.';
        }
    } elseif ($item_type === 'cart' && $item_id !== null) {
        if (isset($_SESSION['carrinho_de_compras'][(int)$item_id])) {
            array_splice($_SESSION['carrinho_de_compras'], (int)$item_id, 1);
           
            $_SESSION['carrinho_de_compras'] = array_values($_SESSION['carrinho_de_compras']);

            $response['success'] = true;
            $response['message'] = 'Item do carrinho removido.';
        } else {
            $response['message'] = 'Item do carrinho não encontrado.';
        }
    } else {
        $response['message'] = 'Tipo de item ou ID inválido.';
    }
} else {
    $response['message'] = 'Método de requisição inválido.';
}

echo json_encode($response);
?>
