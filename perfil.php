<?php
session_start();
include 'config.php';


if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];


$stmt_user = $conn->prepare("SELECT nome_completo, email, foto_perfil FROM usuarios WHERE id = ?");
$stmt_user->bind_param("i", $usuario_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$usuario = $result_user->fetch_assoc();

$usuario_nome = $usuario['nome_completo'];
$usuario_email = $usuario['email'];
$usuario_foto = $usuario['foto_perfil'];

$_SESSION['usuario_nome'] = $usuario_nome;
$_SESSION['usuario_email'] = $usuario_email;
$_SESSION['usuario_foto'] = $usuario_foto;


$conn->query("UPDATE pedidos SET status = 'Entregue' WHERE usuario_id = $usuario_id AND status = 'A Caminho' AND data_previsao_entrega <= NOW()");




function getPedidos($conn, $usuario_id) {
    $sql = "SELECT p.id, p.data_pedido, p.data_previsao_entrega, p.status, p.valor_total, pi.produto_nome, pi.produto_preco, pi.produto_imagem
            FROM pedidos p
            JOIN pedido_itens pi ON p.id = pi.pedido_id
            WHERE p.usuario_id = ? ORDER BY p.data_pedido DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedidos = [];
    while ($row = $result->fetch_assoc()) {
        $pedidos[] = [
            'id_pedido' => $row['id'],
            'data_pedido' => $row['data_pedido'],
            'data_previsao_entrega' => $row['data_previsao_entrega'],
            'status' => $row['status'],
            'valor_total' => $row['valor_total'],
            'nome_item' => $row['produto_nome'],
            'preco_item' => $row['produto_preco'],
            'img_item' => $row['produto_imagem']
        ];
    }
    return $pedidos;
}

$todos_pedidos = getPedidos($conn, $usuario_id);

$hist_result = $conn->query("SELECT acao, data_ocorrencia FROM historico_atividades WHERE usuario_id = $usuario_id ORDER BY data_ocorrencia DESC LIMIT 5");
$historico_atividades = [];
if ($hist_result) {
    while($row = $hist_result->fetch_assoc()) {
        $historico_atividades[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Meu Perfil | TechShop+</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #00ffff; 
            --secondary: #8a2be2; 
            --bg: #0a0a0a;
            --text: #ffffff; 
            --accent: #00ffee; 
            --dark-card: #151515;
            --success-color: #32CD32;
            --warning-color: #FFD700;
            --danger-color: #dc3545;
            --border-gradient: linear-gradient(90deg, #00ffff, #8a2be2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(90deg, rgb(134, 15, 214), rgb(66, 188, 245));
            padding: 15px 40px;
            box-shadow: 0 0 20px var(--primary);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            height: 50px;
        }

        .logo-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 2em;
            font-weight: 700;
            background: linear-gradient(45deg, #00ffff, #8a2be2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 15px rgba(0, 255, 255, 0.7);
        }

        .header-nav a {
            color: #000;
            font-weight: 700;
            text-decoration: none;
            background: var(--primary);
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px var(--accent);
            transition: all 0.3s ease;
        }
        .header-nav a:hover {
            background: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 0 15px var(--accent);
        }

        main {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .profile-banner {
            background: linear-gradient(135deg, #1e1e1e, var(--dark-card));
            padding: 30px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
            border: 1px solid var(--secondary);
            box-shadow: 0 0 30px rgba(138, 43, 226, 0.3);
            animation: fadeInDown 0.8s ease-out;
        }
        
        .profile-pic-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            cursor: pointer;
            flex-shrink: 0;
        }
        
        .profile-pic {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary);
            box-shadow: 0 0 20px var(--accent);
            transition: all 0.3s ease;
        }
        .profile-pic-wrapper:hover .profile-pic {
            filter: brightness(0.7);
            transform: scale(1.05);
        }
        .profile-pic-wrapper .upload-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 2.5em;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }
        .profile-pic-wrapper:hover .upload-icon {
            opacity: 1;
        }
        #profile-pic-form { display: none; }
        
        .profile-banner-info h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5em;
            color: var(--text);
            margin: 0;
        }
        .profile-banner-info p {
            font-size: 1.2em;
            color: #aaa;
        }
        .profile-message { text-align: left; margin-top: 10px; color: var(--primary); font-weight: bold; display: none; }

        .profile-nav {
            display: flex;
            gap: 10px;
            border-bottom: 2px solid #222;
            margin-bottom: 30px;
        }
        .profile-nav button {
            background: none;
            border: none;
            color: #888;
            font-size: 1.2em;
            font-weight: bold;
            padding: 15px 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border-bottom: 4px solid transparent;
        }
        .profile-nav button.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            text-shadow: 0 0 10px var(--primary);
        }
        .profile-nav button:hover {
            color: var(--accent);
        }

        .tab-content {
            display: none;
            animation: fadeInUp 0.5s ease-out;
        }
        .tab-content.active {
            display: block;
        }

        .filter-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .filter-nav button {
            background: #222;
            border: 1px solid #333;
            color: #aaa;
            padding: 8px 18px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .filter-nav button:hover {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }
        .filter-nav button.active {
            background: var(--primary);
            color: #000;
            border-color: var(--primary);
            box-shadow: 0 0 15px var(--primary);
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-card {
            background-color: #1a1a1a;
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid var(--secondary);
            box-shadow: 0 5px 15px rgba(138, 43, 226, 0.2);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }
        .product-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 10px 30px rgba(0, 255, 255, 0.4);
            border-color: var(--primary);
        }
        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .product-card h4 {
            font-size: 1.3em;
            color: var(--text);
            margin-bottom: 10px;
            flex-grow: 1; 
        }
        .product-card p {
            font-size: 0.9em;
            color: #aaa;
            margin-bottom: 10px;
        }
        .product-card .price {
            font-size: 1.4em;
            font-weight: bold;
            color: var(--success-color);
            text-shadow: 0 0 8px rgba(50, 205, 50, 0.6);
            margin-bottom: 15px;
        }
        .product-card .status {
            align-self: flex-start;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .status.acaminho {
            background-color: var(--warning-color); color: #000; box-shadow: 0 0 10px var(--warning-color);
        }
        .status.entregue {
            background-color: var(--success-color); color: #000; box-shadow: 0 0 10px var(--success-color);
        }
        .product-card .tracking-info {
            font-size: 0.85em; font-style: italic; color: var(--accent);
        }

        .settings-section {
            background: var(--dark-card);
            padding: 30px;
            border-radius: 18px;
            border: 1px solid var(--primary);
            margin-bottom: 30px;
        }
        .settings-section h3 {
            font-size: 1.8em; color: var(--primary); font-family: 'Orbitron', sans-serif; margin-bottom: 20px;
        }
        .settings-section ul { list-style: none; }
        .settings-section li {
            background-color: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid var(--accent);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease;
        }
        .settings-section li:hover { background-color: #222; }

        .btn {
            background: var(--primary); color: #000; padding: 8px 18px; border-radius: 8px; font-weight: bold; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }
        .btn:hover {
            background: var(--accent); color: var(--bg); transform: translateY(-2px); box-shadow: 0 0 15px var(--accent);
        }
        .logout-container { text-align: center; margin-top: 40px; }
        .btn-logout {
            background: var(--danger-color); color: white; padding: 12px 30px; font-size: 1.1em; border: none; box-shadow: 0 0 15px rgba(220, 53, 69, 0.5);
        }
        .btn-logout:hover {
            background: #c82333; color: white; box-shadow: 0 0 20px rgba(220, 53, 69, 0.8);
        }

        footer {
            text-align: center; padding: 30px; margin-top: 50px; border-top: 2px solid var(--primary); color: #aaa;
        }

        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="index.php"><img src="logoApp2.png" alt="TechShop+ Logo" class="logo"></a>
            <span class="logo-text">TechShop+</span>
        </div>
        <nav class="header-nav">
             <a href="index.php"><i class="fas fa-store"></i> Voltar à Loja</a>
        </nav>
    </header>

    <main>
        <section class="profile-banner">
            <label for="profile-pic-input" class="profile-pic-wrapper">
                <form id="profile-pic-form" action="upload_perfil.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="profile_pic" id="profile-pic-input" accept="image/*">
                </form>
                <?php $profile_pic_src = !empty($usuario_foto) ? $usuario_foto : 'img/default-user-icon.png'; ?>
                <img src="<?= htmlspecialchars($profile_pic_src) ?>" alt="Foto de Perfil" id="profile-pic-image" class="profile-pic">
                <div class="upload-icon"><i class="fas fa-camera"></i></div>
            </label>
            <div class="profile-banner-info">
                <h2><?= htmlspecialchars($usuario_nome) ?></h2>
                <p><?= htmlspecialchars($usuario_email) ?></p>
                <p id="profile-message" class="profile-message"></p>
            </div>
        </section>

        <nav class="profile-nav">
            <button class="tab-button active" data-tab="pedidos">Meus Pedidos</button>
            <button class="tab-button" data-tab="configuracoes">Configurações</button>
        </nav>

        <div id="pedidos" class="tab-content active">
            <div class="filter-nav">
                <button class="filter-button active" data-filter="todos">Todos</button>
                <button class="filter-button" data-filter="acaminho">A Caminho</button>
                <button class="filter-button" data-filter="entregue">Entregues</button>
            </div>
            <?php if (empty($todos_pedidos)): ?>
                <p style="text-align:center; color:#888;">Você ainda não fez nenhum pedido.</p>
            <?php else: ?>
                <div class="product-list">
                    <?php foreach ($todos_pedidos as $item): ?>
                    <div class="product-card" data-status="<?= strtolower(str_replace(' ', '', $item['status'])) ?>">
                        <img src="<?= htmlspecialchars($item['img_item']) ?>" alt="<?= htmlspecialchars($item['nome_item']) ?>">
                        <h4><?= htmlspecialchars($item['nome_item']) ?></h4>
                        <p>Data da Compra: <?= date('d/m/Y', strtotime($item['data_pedido'])) ?></p>
                        <span class="price">R$ <?= number_format($item['preco_item'], 2, ',', '.') ?></span>
                        <span class="status <?= strtolower(str_replace(' ', '', $item['status'])) ?>">
                            <?= htmlspecialchars($item['status']) ?>
                        </span>
                         <?php if ($item['status'] == 'Entregue'): ?>
                            <p class="tracking-info">Entregue em: <?= date('d/m/Y', strtotime($item['data_previsao_entrega'])) ?></p>
                        <?php else: ?>
                            <p class="tracking-info">Previsão: <?= date('d/m/Y', strtotime($item['data_previsao_entrega'])) ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div id="configuracoes" class="tab-content">
            <div class="settings-section">
                <h3><i class="fas fa-user-cog"></i> Configurações da Conta</h3>
                <ul>
                    <li><strong>Nome:</strong> <?= htmlspecialchars($usuario_nome) ?> <a href="editar_perfil.php" class="btn">Editar</a></li>
                    <li><strong>Email:</strong> <?= htmlspecialchars($usuario_email) ?> <a href="editar_perfil.php" class="btn">Alterar</a></li>
                    <li><strong>Senha:</strong> ******** <a href="editar_perfil.php?secao=senha" class="btn">Redefinir</a></li>
                </ul>
            </div>
            <div class="settings-section">
                <h3><i class="fas fa-history"></i> Histórico de Atividade</h3>
                <?php if(empty($historico_atividades)): ?>
                    <p>Nenhuma atividade recente registrada.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach($historico_atividades as $atividade): ?>
                        <li><strong><?= date('d/m/Y H:i', strtotime($atividade['data_ocorrencia'])) ?>:</strong> <?= htmlspecialchars($atividade['acao']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="logout-container">
                <a href="logout.php" class="btn btn-logout"><i class="fas fa-sign-out-alt"></i> Sair da Conta</a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?= date('Y'); ?> TechShop+. Todos os direitos reservados.</p>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            const filterButtons = document.querySelectorAll('.filter-button');
            const productCards = document.querySelectorAll('.product-card');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    tabContents.forEach(content => {
                        if (content.id === button.dataset.tab) {
                            content.classList.add('active');
                        } else {
                            content.classList.remove('active');
                        }
                    });
                });
            });

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    
                    const filter = button.dataset.filter;

                    productCards.forEach(card => {
                        if (filter === 'todos' || card.dataset.status === filter) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            document.getElementById('profile-pic-input').addEventListener('change', function() {
                const form = document.getElementById('profile-pic-form');
                const formData = new FormData(form);
                const messageEl = document.getElementById('profile-message');

                messageEl.style.display = 'block';
                messageEl.style.color = 'var(--warning-color)';
                messageEl.textContent = 'Enviando imagem...';

                fetch('upload_perfil.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('profile-pic-image').src = data.filePath + '?' + new Date().getTime();
                        messageEl.style.color = 'var(--success-color)';
                        messageEl.textContent = 'Foto de perfil atualizada!';
                    } else {
                        messageEl.style.color = 'var(--danger-color)';
                        messageEl.textContent = 'Erro: ' + data.error;
                    }
                    setTimeout(() => { messageEl.style.display = 'none'; }, 4000);
                })
                .catch(error => {
                    messageEl.style.color = 'var(--danger-color)';
                    messageEl.textContent = 'Ocorreu um erro na requisição.';
                     setTimeout(() => { messageEl.style.display = 'none'; }, 4000);
                });
            });
        });
    </script>
</body>
</html>
