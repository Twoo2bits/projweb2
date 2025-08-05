<?php
session_start();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemType = $_POST['item_type'] ?? '';
    $itemId = $_POST['item_id'] ?? null;

    if ($itemType === 'config' && $itemId !== null) {
        if (isset($_SESSION['configuracao_pc'][$itemId])) {
            $removedPrice = $_SESSION['configuracao_pc'][$itemId]['preco'];
            unset($_SESSION['configuracao_pc'][$itemId]);

            $new_config_total = 0;
            foreach ($_SESSION['configuracao_pc'] as $component) {
                $new_config_total += $component['preco'];
            }
            $_SESSION['total_configuracao_pc'] = $new_config_total;

            $response['success'] = true;
        } else {
            $response['message'] = 'Componente não encontrado na configuração.';
        }
    } elseif ($itemType === 'cart' && $itemId !== null) {
        
        if (isset($_SESSION['carrinho_de_compras'][$itemId])) {
            $removedPrice = $_SESSION['carrinho_de_compras'][$itemId]['preco'];
            array_splice($_SESSION['carrinho_de_compras'], $itemId, 1);
            $response['success'] = true;
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
