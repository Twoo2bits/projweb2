<?php
session_start();
include 'config.php';

$admin_password = 'admin123';
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $login_error = "Senha incorreta.";
    }
}

if (!isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>TechShop+ - Login Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
        <style>
            :root {
                --primary: #00ffff;
                --accent: #8a2be2;
                --bg: #0a0a0a;
                --text: #e0e0e0;
                --card-bg: #1a1a1a;
                --border-color: #333;
                --shadow-light: rgba(0, 255, 255, 0.4);
                --shadow-dark: rgba(138, 43, 226, 0.4);
            }
            * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Roboto', sans-serif; }
            body { background: var(--bg); color: var(--text); min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; }
            .login-container {
                background: var(--card-bg);
                border: 2px solid var(--primary);
                border-radius: 15px;
                padding: 40px;
                box-shadow: 0 0 25px var(--shadow-dark);
                text-align: center;
                width: 90%;
                max-width: 400px;
            }
            .login-container h2 {
                font-family: 'Orbitron', sans-serif;
                color: var(--primary);
                margin-bottom: 25px;
                font-size: 2.2em;
            }
            .login-container input[type="password"] {
                width: 100%;
                padding: 12px;
                margin-bottom: 20px;
                border: 1px solid var(--border-color);
                border-radius: 8px;
                background: #0d0d0d;
                color: var(--text);
                font-size: 1em;
            }
            .login-container button {
                background: var(--accent);
                color: #fff;
                padding: 12px 25px;
                border: none;
                border-radius: 8px;
                font-size: 1.1em;
                font-weight: bold;
                cursor: pointer;
                transition: background 0.3s ease, transform 0.3s ease;
            }
            .login-container button:hover {
                background: var(--primary);
                color: #000;
                transform: translateY(-2px);
            }
            .login-container .error {
                color: #ff4d4d;
                margin-top: 15px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2>Login de Administrador</h2>
            <form action="admin.php" method="POST">
                <input type="password" name="password" placeholder="Digite a senha de administrador" required>
                <button type="submit">Entrar</button>
            </form>
            <?php if (!empty($login_error)): ?>
                <p class="error"><?= $login_error ?></p>
            <?php endif; ?>
        </div>
    </body>
    </html>
    <?php
    exit();
}


if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

$categorias_nome = [
    "smartwatch"    => "Smartwatches",
    "headset"       => "Headsets",
    "automacao"     => "Automação Residencial",
    "mouse"         => "Mouses Gamer",
    "placa-video"   => "Placas de Vídeo",
    "consoles"      => "Consoles & Jogos",
    "hardware"      => "Hardware Essencial",
    "perifericos"   => "Periféricos Essenciais",
    "placa-mae"     => "Placas Mãe",
    "memoria-ram"   => "Memórias RAM de Alta Performance",
    "ssd"           => "SSDs & Armazenamento",
    "tv"            => "Smart TVs & Monitores",
    "acessorios"    => "Acessórios Diversos",
    "baterias"      => "Carregadores & Power Banks"
];

$message = $_SESSION['admin_message'] ?? '';
unset($_SESSION['admin_message']);

$produtos_db = [];
if (isset($conn) && $conn instanceof mysqli) {
    $stmt_products = $conn->prepare("SELECT id, nome, descricao, preco, preco_promocional, imagem_url, categoria, is_featured FROM produtos");
    if ($stmt_products) {
        $stmt_products->execute();
        $result_products = $stmt_products->get_result();
        while ($row = $result_products->fetch_assoc()) {
            $produtos_db[] = $row;
        }
        $stmt_products->close();
    } else {
        $message .= " Erro ao carregar produtos: " . $conn->error;
    }
} else {
    $message .= " Erro: Conexão com o banco de dados não estabelecida.";
}


$noticias_db = [];
if (isset($conn) && $conn instanceof mysqli) {
    $stmt_news = $conn->prepare("SELECT id, titulo, data_publicacao, imagem_url, conteudo_completo FROM noticias ORDER BY data_publicacao DESC");
    if ($stmt_news) {
        $stmt_news->execute();
        $result_news = $stmt_news->get_result();
        while ($row = $result_news->fetch_assoc()) {
            $noticias_db[] = $row;
        }
        $stmt_news->close();
    } else {
        $message .= " Erro ao carregar notícias: " . $conn->error;
    }
}


$usuarios_db = [];
if (isset($conn) && $conn instanceof mysqli) {
    $stmt_users = $conn->prepare("SELECT id, nome_completo, email, data_cadastro FROM usuarios ORDER BY data_cadastro DESC");
    if ($stmt_users) {
        $stmt_users->execute();
        $result_users = $stmt_users->get_result();
        while ($row = $result_users->fetch_assoc()) {
            $usuarios_db[] = $row;
        }
        $stmt_users->close();
    } else {
        $message .= " Erro ao carregar usuários: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>TechShop+ - Painel Administrativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        :root {
            --primary: #00ffff;
            --accent: #8a2be2;
            --bg: #0a0a0a;
            --text: #e0e0e0;
            --card-bg: #1a1a1a;
            --border-color: #333;
            --shadow-light: rgba(0, 255, 255, 0.4);
            --shadow-dark: rgba(138, 43, 226, 0.4);
            --danger-red: #dc3545;
            --success-green: #28a745;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        header {
            background: linear-gradient(90deg, var(--primary), var(--accent));
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            position: sticky;
            top: 0;
            z-index: 1000;
            flex-wrap: wrap;
            gap: 10px;
        }

        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.2em;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 8px var(--shadow-light);
        }

        .header-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            text-decoration: none;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
            background: var(--accent);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-home {
             background: rgba(255, 255, 255, 0.1);
             color: var(--text);
        }
        .btn-home:hover {
            background: var(--primary);
            color: var(--bg);
            box-shadow: 0 5px 15px var(--shadow-light);
        }
        .btn-logout {
            background: #e74c3c;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--shadow-dark);
        }
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; }
        .btn-info { background: #17a2b8; }
        .btn-warning { background: #ffc107; color: #000; }


        main {
            flex-grow: 1;
            padding: 30px;
            max-width: 1400px;
            margin: 20px auto;
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        h2.section-title {
            text-align: center;
            color: var(--primary);
            margin: 30px 0 30px;
            font-size: 2.5em;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 10px var(--shadow-light);
        }

        .message {
            background: #28a745;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .message.error {
            background: #dc3545;
        }

        .admin-section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: #111;
        }
        .admin-section h3 {
            color: var(--accent);
            margin-bottom: 20px;
            font-size: 1.8em;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 600px;
            margin: 0 auto;
        }
        form label {
            font-weight: bold;
            color: var(--text);
            margin-bottom: 5px;
            display: block;
        }
        form input[type="text"],
        form input[type="number"],
        form input[type="email"],
        form input[type="password"],
        form textarea,
        form select {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            background: #0d0d0d;
            color: var(--text);
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        form input[type="text"]:focus,
        form input[type="number"]:focus,
        form input[type="email"]:focus,
        form input[type="password"]:focus,
        form textarea:focus,
        form select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 5px var(--shadow-light);
        }
        form textarea {
            resize: vertical;
            min-height: 80px;
        }
        form button {
            align-self: flex-end;
            padding: 12px 25px;
            font-size: 1.1em;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 30px;
        }
        
        #products-table-container {
            max-height: 600px;
            overflow-y: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 800px; 
        }
        .data-table th, .data-table td {
            border: 1px solid var(--border-color);
            padding: 12px;
            text-align: left;
        }
        .data-table th {
            background: var(--accent);
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
        }
        .data-table tr:nth-child(even) {
            background: #151515;
        }
        .data-table tr:hover {
            background: #252525;
        }
        
        .data-table td img {
            width: 60px;
            height: 60px;
            object-fit: contain; 
            border-radius: 5px;
            vertical-align: middle;
            background-color: #000; 
            padding: 2px;
        }
        .data-table .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
            min-width: 120px; 
        }
        .data-table .actions .btn {
            padding: 8px 12px;
            font-size: 0.85em;
            white-space: nowrap;
            width: fit-content; 
        }
        .data-table .promo-price {
            color: #0f0;
            font-weight: bold;
        }
        .data-table .original-price {
            text-decoration: line-through;
            color: #aaa;
            font-size: 0.9em;
        }

        .data-table .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }
        .data-table .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .data-table .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        .data-table .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        .data-table input:checked + .slider {
            background-color: var(--primary);
        }
        .data-table input:checked + .slider:before {
            transform: translateX(26px);
        }

        footer {
            background: #111;
            color: #888;
            text-align: center;
            padding: 25px;
            margin-top: 50px;
            border-top: 1px solid var(--border-color);
            font-size: 0.9em;
        }

        .custom-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease-out;
        }

        .custom-modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .custom-modal-content {
            background: var(--card-bg);
            border: 2px solid var(--primary);
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 0 30px var(--shadow-light);
            width: 90%;
            max-width: 450px;
            transform: translateY(-50px);
            opacity: 0;
            transition: transform 0.4s ease-out, opacity 0.4s ease-out;
        }

        .custom-modal-overlay.active .custom-modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        .custom-modal-content h3 {
            font-family: 'Orbitron', sans-serif;
            color: var(--accent);
            font-size: 1.8em;
            margin-bottom: 20px;
            text-shadow: 0 0 10px var(--shadow-dark);
        }

        .custom-modal-content p {
            font-size: 1.1em;
            color: var(--text);
            margin-bottom: 30px;
        }

        .custom-modal-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .custom-modal-buttons button {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .custom-modal-buttons .btn-yes {
            background: var(--danger-red);
            color: #fff;
            box-shadow: 0 0 15px rgba(220, 53, 69, 0.5);
        }

        .custom-modal-buttons .btn-yes:hover {
            background: #c82333;
            transform: translateY(-3px);
            box-shadow: 0 0 20px rgba(220, 53, 69, 0.8);
        }

        .custom-modal-buttons .btn-no {
            background: var(--primary);
            color: #000;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.5);
        }

        .custom-modal-buttons .btn-no:hover {
            background: var(--accent);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.8);
        }


        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 15px;
            }
            .logo {
                font-size: 1.8em;
            }
            .header-actions {
                width: 100%;
                justify-content: center;
            }
            main {
                padding: 15px;
            }
            h2.section-title {
                font-size: 2em;
            }
            .admin-section {
                padding: 15px;
            }
            .table-container {
                overflow-x: auto;
            }
            .data-table {
                min-width: 600px;
            }
            .data-table th, .data-table td {
                padding: 8px;
                font-size: 0.8em;
            }
            .data-table .actions {
                flex-direction: column;
                gap: 5px;
                min-width: unset;
            }
            .data-table .actions .btn {
                width: 100%;
            }
            .custom-modal-content {
                padding: 30px;
            }
            .custom-modal-content h3 {
                font-size: 1.6em;
            }
            .custom-modal-content p {
                font-size: 1em;
            }
        }

        @media (max-width: 480px) {
            .logo {
                font-size: 1.5em;
            }
            .btn {
                padding: 8px 12px;
                font-size: 0.9em;
            }
            h2.section-title {
                font-size: 1.6em;
            }
            .admin-section h3 {
                font-size: 1.5em;
            }
            form button {
                padding: 10px 20px;
                font-size: 1em;
            }
            .data-table {
                min-width: 400px;
            }
            .custom-modal-content {
                padding: 20px;
            }
            .custom-modal-content h3 {
                font-size: 1.4em;
            }
            .custom-modal-content p {
                font-size: 0.9em;
            }
            .custom-modal-buttons button {
                padding: 10px 20px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">TechShop+ Admin</div>
        <div class="header-actions">
            <a href="index.php" class="btn btn-home"><i class="fas fa-store"></i> Ver Loja</a>
            <a href="?logout=true" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </header>

    <main>
        <h2 class="section-title">Painel de Administração</h2>

        <?php if ($message): ?>
            <div class="message <?= strpos($message, 'Erro') !== false ? 'error' : 'success' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="admin-section">
            <h3>Gerenciar Produtos</h3>
            <h4 style="color:var(--primary); margin-top: 20px; margin-bottom: 15px; text-align: center;">Adicionar Novo Produto</h4>
            <form action="process_admin.php" method="POST">
                <input type="hidden" name="action" value="add_product">
                <label for="add_category">Categoria:</label>
                <select id="add_category" name="category" required>
                    <?php foreach ($categorias_nome as $key => $name): ?>
                        <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($name) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="add_name">Nome do Produto:</label>
                <input type="text" id="add_name" name="name" required>

                <label for="add_price">Preço (Ex: 1.000.000,00):</label>
                <input type="text" id="add_price" name="price" pattern="^\d{1,7}(?:\.\d{3})*(?:,\d{2})?$" placeholder="Ex: 1.000.000,00" required>

                <label for="add_promo_price">Preço Promocional (Opcional, Ex: 999.999,99):</label>
                <input type="text" id="add_promo_price" name="promo_price" pattern="^\d{1,7}(?:\.\d{3})*(?:,\d{2})?$" placeholder="Ex: 999.999,99">

                <label for="add_description">Descrição:</label>
                <textarea id="add_description" name="description" required></textarea>

                <label for="add_image">URL da Imagem (Ex: https://example.com/imagem.jpg ou local: meu_produto.webp):</label>
                <input type="text" id="add_image" name="image" placeholder="Ex: https://example.com/imagem.jpg ou produto_local.webp" required>
                <small style="color: #bbb;">Use a URL completa da imagem (ex: Google Imagens) ou o nome do arquivo se a imagem estiver na pasta `img/`.</small>

                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Produto</button>
            </form>

            <hr style="border-top: 1px solid var(--border-color); margin: 40px 0;">

            <h4 style="color:var(--primary); margin-top: 20px; margin-bottom: 15px; text-align: center;">Produtos Cadastrados</h4>
            <?php if (empty($produtos_db)): ?>
                <p style="text-align:center; color:#888;">Nenhum produto cadastrado ainda.</p>
            <?php else: ?>
                <div id="products-table-container" class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Destaque</th>
                                <th>Categoria</th>
                                <th>Preço</th>
                                <th>Promoção</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos_db as $product): ?>
                                <tr>
                                    <td>
                                        <?php
                                        $image_src = $product['imagem_url'];
                                        if (!filter_var($image_src, FILTER_VALIDATE_URL)) {
                                            $image_src = 'img/' . $image_src; 
                                        }
                                        ?>
                                        <img src="<?= htmlspecialchars($image_src) ?>" alt="<?= htmlspecialchars($product['nome']) ?>">
                                    </td>
                                    <td><?= htmlspecialchars($product['nome']) ?></td>
                                    <td>
                                        <form action="process_admin.php" method="POST">
                                            <input type="hidden" name="action" value="toggle_featured">
                                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                            <label class="switch">
                                                <input type="checkbox" name="is_featured" value="1" <?= $product['is_featured'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                                <span class="slider"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td><?= htmlspecialchars($categorias_nome[$product['categoria']] ?? $product['categoria']) ?></td>
                                    <td>R$ <?= number_format($product['preco'], 2, ',', '.') ?></td>
                                    <td>
                                        <?php if (!empty($product['preco_promocional'])): ?>
                                            <span class="promo-price">R$ <?= number_format($product['preco_promocional'], 2, ',', '.') ?></span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <button class="btn btn-info edit-product-button"
                                            data-id="<?= htmlspecialchars($product['id']) ?>"
                                            data-name="<?= htmlspecialchars($product['nome']) ?>"
                                            data-category="<?= htmlspecialchars($product['categoria']) ?>"
                                            data-price="<?= htmlspecialchars(number_format($product['preco'], 2, ',', '.')) ?>"
                                            data-promo-price="<?= htmlspecialchars(!empty($product['preco_promocional']) ? number_format($product['preco_promocional'], 2, ',', '.') : '') ?>"
                                            data-description="<?= htmlspecialchars($product['descricao']) ?>"
                                            data-image="<?= htmlspecialchars($product['imagem_url']) ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <form class="delete-form" action="process_admin.php" method="POST" data-type="produto" data-id="<?= htmlspecialchars($product['id']) ?>" data-name="<?= htmlspecialchars($product['nome']) ?>">
                                            <input type="hidden" name="action" value="delete_product">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <hr style="border-top: 1px solid var(--border-color); margin: 40px 0;">
            <div class="admin-section" id="edit-product-section" style="display:none;">
                <h4 style="color:var(--primary); margin-bottom: 15px; text-align: center;">Editar Produto Selecionado</h4>
                <form action="process_admin.php" method="POST" id="edit-product-form">
                    <input type="hidden" name="action" value="edit_product">
                    <input type="hidden" name="id" id="edit_product_id">
                    
                    <label for="edit_product_category">Categoria:</label>
                    <select id="edit_product_category" name="category" required>
                        <?php foreach ($categorias_nome as $key => $name): ?>
                            <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="edit_product_name">Nome do Produto:</label>
                    <input type="text" id="edit_product_name" name="name" required>

                    <label for="edit_product_price">Preço (Ex: 1.000,00):</label>
                    <input type="text" id="edit_product_price" name="price" placeholder="Ex: 1.000,00" required>

                    <label for="edit_product_promo_price">Preço Promocional (Opcional):</label>
                    <input type="text" id="edit_product_promo_price" name="promo_price" placeholder="Ex: 999,99">

                    <label for="edit_product_description">Descrição:</label>
                    <textarea id="edit_product_description" name="description" required></textarea>

                    <label for="edit_product_image">URL da Imagem:</label>
                    <input type="text" id="edit_product_image" name="image" required>

                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Salvar Alterações</button>
                    <button type="button" class="btn btn-info" onclick="document.getElementById('edit-product-section').style.display='none';"><i class="fas fa-times-circle"></i> Cancelar</button>
                </form>
            </div>
        </div>

        <div class="admin-section">
            <h3>Gerenciar Notícias</h3>
            <h4 style="color:var(--primary); margin-top: 20px; margin-bottom: 15px; text-align: center;">Adicionar Nova Notícia</h4>
            <form action="process_admin.php" method="POST">
                <input type="hidden" name="action" value="add_news">
                <label for="add_news_titulo">Título da Notícia:</label>
                <input type="text" id="add_news_titulo" name="titulo" required>

                <label for="add_news_imagem">URL da Imagem:</label>
                <input type="text" id="add_news_imagem" name="imagem_url" required>

                <label for="add_news_conteudo">Conteúdo Completo (HTML permitido):</label>
                <textarea id="add_news_conteudo" name="conteudo_completo" rows="10" required></textarea>

                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar Notícia</button>
            </form>

            <hr style="border-top: 1px solid var(--border-color); margin: 40px 0;">

            <h4 style="color:var(--primary); margin-top: 20px; margin-bottom: 15px; text-align: center;">Notícias Cadastradas</h4>
            <?php if (empty($noticias_db)): ?>
                <p style="text-align:center; color:#888;">Nenhuma notícia cadastrada ainda.</p>
            <?php else: ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Imagem</th>
                                <th>Título</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($noticias_db as $news): ?>
                                <tr>
                                    <td>
                                        <?php
                                        $image_src = $news['imagem_url'];
                                        if (!filter_var($image_src, FILTER_VALIDATE_URL)) {
                                            $image_src = 'img/' . $image_src; 
                                        }
                                        ?>
                                        <img src="<?= htmlspecialchars($image_src) ?>" alt="<?= htmlspecialchars($news['titulo']) ?>">
                                    </td>
                                    <td><?= htmlspecialchars($news['titulo']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($news['data_publicacao'])) ?></td>
                                    <td class="actions">
                                        <button class="btn btn-info edit-news-button"
                                            data-id="<?= htmlspecialchars($news['id']) ?>"
                                            data-titulo="<?= htmlspecialchars($news['titulo']) ?>"
                                            data-imagem="<?= htmlspecialchars($news['imagem_url']) ?>"
                                            data-conteudo="<?= htmlspecialchars($news['conteudo_completo']) ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <form class="delete-form" action="process_admin.php" method="POST" data-type="notícia" data-id="<?= htmlspecialchars($news['id']) ?>" data-name="<?= htmlspecialchars($news['titulo']) ?>">
                                            <input type="hidden" name="action" value="delete_news">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($news['id']) ?>">
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <hr style="border-top: 1px solid var(--border-color); margin: 40px 0;">
            <div class="admin-section" id="edit-news-section" style="display:none;">
                <h4 style="color:var(--primary); margin-bottom: 15px; text-align: center;">Editar Notícia Selecionada</h4>
                <form action="process_admin.php" method="POST" id="edit-news-form">
                    <input type="hidden" name="action" value="edit_news">
                    <input type="hidden" name="id" id="edit_news_id">

                    <label for="edit_news_titulo">Título da Notícia:</label>
                    <input type="text" id="edit_news_titulo" name="titulo" required>

                    <label for="edit_news_imagem">URL da Imagem:</label>
                    <input type="text" id="edit_news_imagem" name="imagem_url" required>

                    <label for="edit_news_conteudo">Conteúdo Completo (HTML permitido):</label>
                    <textarea id="edit_news_conteudo" name="conteudo_completo" rows="10" required></textarea>

                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Salvar Alterações</button>
                    <button type="button" class="btn btn-info" onclick="document.getElementById('edit-news-section').style.display='none';"><i class="fas fa-times-circle"></i> Cancelar</button>
                </form>
            </div>
        </div>

        <div class="admin-section">
            <h3>Gerenciar Usuários</h3>
            <?php if (empty($usuarios_db)): ?>
                <p style="text-align:center; color:#888;">Nenhum usuário cadastrado ainda.</p>
            <?php else: ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome Completo</th>
                                <th>Email</th>
                                <th>Data de Cadastro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios_db as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['id']) ?></td>
                                    <td><?= htmlspecialchars($user['nome_completo']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($user['data_cadastro'])) ?></td>
                                    <td class="actions">
                                        <form class="delete-form" action="process_admin.php" method="POST" data-type="usuário" data-id="<?= htmlspecialchars($user['id']) ?>" data-name="<?= htmlspecialchars($user['nome_completo']) ?>">
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> TechShop+ Admin. Todos os direitos reservados.</p>
    </footer>

    <div id="customConfirmModal" class="custom-modal-overlay">
        <div class="custom-modal-content">
            <h3 id="confirmModalTitle">Confirmação de Exclusão</h3>
            <p id="confirmModalMessage">Você realmente deseja excluir este item?</p>
            <div class="custom-modal-buttons">
                <button class="btn-yes" id="confirmModalYes">Sim, Excluir</button>
                <button class="btn-no" id="confirmModalNo">Não, Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-product-button').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const category = this.dataset.category;
                const price = this.dataset.price;
                const promoPrice = this.dataset.promoPrice;
                const description = this.dataset.description;
                const image = this.dataset.image; 

                document.getElementById('edit_product_id').value = id;
                document.getElementById('edit_product_category').value = category;
                document.getElementById('edit_product_name').value = name;
                document.getElementById('edit_product_price').value = price;
                document.getElementById('edit_product_promo_price').value = promoPrice;
                document.getElementById('edit_product_description').value = description;
                document.getElementById('edit_product_image').value = image; 

                document.getElementById('edit-product-section').style.display = 'block';
                document.getElementById('edit-product-section').scrollIntoView({ behavior: 'smooth' });
            });
        });

        document.querySelectorAll('.edit-news-button').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const titulo = this.dataset.titulo;
                const imagem = this.dataset.imagem; 
                const conteudo = this.dataset.conteudo;

                document.getElementById('edit_news_id').value = id;
                document.getElementById('edit_news_titulo').value = titulo;
                document.getElementById('edit_news_imagem').value = imagem; 
                document.getElementById('edit_news_conteudo').value = conteudo;

                document.getElementById('edit-news-section').style.display = 'block';
                document.getElementById('edit-news-section').scrollIntoView({ behavior: 'smooth' });
            });
        });

        const customConfirmModal = document.getElementById('customConfirmModal');
        const confirmModalTitle = document.getElementById('confirmModalTitle');
        const confirmModalMessage = document.getElementById('confirmModalMessage');
        const confirmModalYes = document.getElementById('confirmModalYes');
        const confirmModalNo = document.getElementById('confirmModalNo');

        function showConfirmModal(type, id, name, form) {
            confirmModalTitle.textContent = `Confirmar Exclusão de ${type}`;
            confirmModalMessage.innerHTML = `Você realmente deseja excluir ${type} "<strong>${name}</strong>"?`;
            
            customConfirmModal.classList.add('active');

            confirmModalYes.onclick = function() {
                form.submit();
                customConfirmModal.classList.remove('active');
            };

            confirmModalNo.onclick = function() {
                customConfirmModal.classList.remove('active');
            };

            customConfirmModal.onclick = function(event) {
                if (event.target === customConfirmModal) {
                    customConfirmModal.classList.remove('active');
                }
            };
        }

        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); 
                const type = this.dataset.type;
                const id = this.dataset.id;
                const name = this.dataset.name;
                showConfirmModal(type, id, name, this);
            });
        });
    </script>
</body>
</html>
