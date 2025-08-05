<?php
session_start();
include 'config.php';

$noticias_db = [];
$stmt_news = $conn->prepare("SELECT id, titulo, data_publicacao, imagem_url, conteudo_completo FROM noticias ORDER BY data_publicacao DESC");
if ($stmt_news) {
    $stmt_news->execute();
    $result_news = $stmt_news->get_result();
    while ($row = $result_news->fetch_assoc()) {
        $noticias_db[] = $row;
    }
    $stmt_news->close();
} else {
    error_log("Erro ao carregar notícias: " . $conn->error);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>TechShop+ | Notícias de Tecnologia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="shortcut icon" type="image/x-icon" href="logoApp.png">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #1a1a2e;
            --secondary-bg: #16213e;
            --accent: #0f3460;
            --text-light: #e0e0e0;
            --text-dark: #b0b0b0;
            --border-color: #0d2547;
            --glow-color: #00ffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
            color: var(--text-light);
        }

        body {
            background-color: var(--primary-bg);
            overflow-x: hidden;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: var(--glow-color);
            transition: color 0.3s ease;
        }

        a:hover {
            color: #7affff;
        }

        header {
            background-color: var(--secondary-bg);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--border-color);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            flex-wrap: wrap;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo-container {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }

        .logo {
            height: 50px;
            margin-right: 10px;
        }

        .logo-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8em;
            color: var(--glow-color);
            text-shadow: 0 0 8px var(--glow-color);
        }

        header nav a {
            margin: 0 15px;
            font-size: 1.1em;
            font-weight: bold;
            color: var(--text-light);
            transition: color 0.3s ease, text-shadow 0.3s ease;
        }

        header nav a:hover {
            color: var(--glow-color);
            text-shadow: 0 0 5px var(--glow-color);
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-left: auto;
        }

        .actions a {
            color: var(--text-light);
            font-size: 1.1em;
            transition: color 0.3s ease, text-shadow 0.3s ease;
            position: relative;
        }

        .actions a:hover {
            color: var(--glow-color);
            text-shadow: 0 0 5px var(--glow-color);
        }

        .actions .fas {
            font-size: 1.4em;
        }

        #carrinho-count {
            background-color: var(--accent);
            color: white;
            border-radius: 50%;
            padding: 2px 7px;
            font-size: 0.8em;
            position: absolute;
            top: -8px;
            right: -10px;
            min-width: 20px;
            text-align: center;
        }

        .search-container {
            display: flex;
            flex-direction: column;
            position: relative;
            margin-top: 10px;
            width: 100%;
            max-width: 300px;
            order: 4;
        }

        .search-box {
            display: flex;
            width: 100%;
        }

        .search-box input {
            flex-grow: 1;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px 0 0 5px;
            background-color: var(--primary-bg);
            color: var(--text-light);
            font-size: 1em;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .search-box input:focus {
            border-color: var(--glow-color);
            box-shadow: 0 0 5px var(--glow-color);
        }

        .search-box button {
            background-color: var(--accent);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .search-box button:hover {
            background-color: #0056b3;
        }

        #search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: var(--secondary-bg);
            border: 1px solid var(--border-color);
            border-top: none;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-height: 200px;
            overflow-y: auto;
            display: none;
            z-index: 999;
        }

        #search-results a {
            display: block;
            padding: 10px 15px;
            color: var(--text-light);
            text-decoration: none;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        #search-results a:hover {
            background-color: var(--accent);
            color: white;
        }


        .noticias-container {
            padding: 40px;
            max-width: 1200px;
            margin: 20px auto;
            background-color: var(--secondary-bg);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .noticias-container h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5em;
            color: var(--glow-color);
            text-align: center;
            margin-bottom: 40px;
            text-shadow: 0 0 10px var(--glow-color);
        }

        .noticia-item {
            background-color: var(--primary-bg);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
            margin-bottom: 50px;
            padding: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .noticia-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6), 0 0 15px rgba(0, 255, 255, 0.5);
        }

        .noticia-item img {
            width: 100%;
            max-height: 450px;
            object-fit: cover; 
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
        }

        .noticia-item h3 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.2em;
            color: var(--glow-color);
            margin-bottom: 15px;
            line-height: 1.3;
            text-align: center;
            text-shadow: 0 0 8px var(--glow-color);
        }

        .noticia-item .data {
            font-size: 1em;
            color: var(--text-dark);
            margin-bottom: 25px;
            text-align: center;
            display: block;
        }

        .noticia-item p {
            font-size: 1.1em;
            color: var(--text-light);
            margin-bottom: 15px;
            line-height: 1.8;
            text-align: justify;
        }


        footer {
            background-color: var(--secondary-bg);
            color: var(--text-dark);
            text-align: center;
            padding: 30px 20px;
            border-top: 2px solid var(--border-color);
            margin-top: 50px;
            font-size: 0.9em;
        }

        footer p {
            margin: 5px 0;
            color: var(--text-dark);
        }


        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background-color: var(--secondary-bg);
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.5);
            max-width: 600px;
            width: 90%;
            position: relative;
            animation: modalOpen 0.3s ease-out forwards;
        }

        @keyframes modalOpen {
            from { opacity: 0; transform: translateY(-50px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-content .close {
            color: var(--text-dark);
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .modal-content .close:hover,
        .modal-content .close:focus {
            color: var(--glow-color);
        }

        .modal-content h3 {
            font-family: 'Orbitron', sans-serif;
            color: var(--glow-color);
            margin-bottom: 20px;
            text-align: center;
            font-size: 2em;
        }

        .modal-content p, .modal-content div {
            color: var(--text-light);
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .modal-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.3);
        }


        #modal-categorias table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        #modal-categorias td {
            padding: 12px 15px;
            background-color: var(--primary-bg);
            border-radius: 8px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        #modal-categorias td:hover {
            background-color: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3), 0 0 10px var(--glow-color);
        }

        #modal-categorias td a {
            color: var(--text-light);
            font-weight: bold;
            font-size: 1.1em;
            display: block;
            text-decoration: none;
        }

        #modal-categorias td a:hover {
            color: white;
        }


        .botao-comprar {
            display: block;
            width: fit-content;
            margin: 20px auto 0;
            background-color: var(--glow-color);
            color: var(--primary-bg);
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.1em;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.6);
        }

        .botao-comprar:hover {
            background-color: #7affff;
            transform: translateY(-3px);
            box-shadow: 0 0 25px var(--glow-color);
            color: var(--primary-bg);
        }


        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px 20px;
            }

            .logo-container {
                margin-bottom: 15px;
                width: 100%;
                justify-content: center;
            }

            header nav {
                width: 100%;
                text-align: center;
                margin-bottom: 15px;
            }

            header nav a {
                display: block;
                margin: 10px 0;
            }

            .actions {
                width: 100%;
                justify-content: center;
                margin-left: 0;
                margin-bottom: 15px;
            }

            .search-container {
                order: initial;
                width: 100%;
                max-width: none;
                margin-top: 0;
            }

            .noticias-container {
                padding: 20px;
                margin: 10px auto;
            }

            .noticias-container h2 {
                font-size: 1.8em;
                margin-bottom: 30px;
            }

            .noticia-item {
                padding: 20px;
                margin-bottom: 30px;
            }

            .noticia-item h3 {
                font-size: 1.6em;
            }

            .noticia-item img {
                max-height: 250px;
            }

            .noticia-item p {
                font-size: 1em;
            }

            .modal-content {
                width: 95%;
                padding: 20px;
            }

            .modal-content h3 {
                font-size: 1.5em;
            }

            #modal-categorias td {
                padding: 10px;
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>

<header>
    <div class="logo-container">
        <a href="index.php"><img src="logoApp2.png" alt="TechShop+ Logo" class="logo"></a>
        <span class="logo-text">TechNotícias+</span>
    </div>
    <nav>
        <a href="index.php">Início</a>
        <a href="javascript:void(0);" onclick="abrirModalCategorias()">Categorias</a>
        <a href="index.php#produtos-destaque">Produtos</a>
    </nav>
    <div class="actions">
        <?php if (isset($_SESSION['usuario_logado'])): ?>
            <a href="perfil.php">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_logado']); ?></a>
            <a href="logout.php">Sair</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="cadastro.php">Cadastro</a>
        <?php endif; ?>
        <a href="admin.php" class="gear-icon" title="Gerenciamento"><i class="fas fa-cog"></i></a>
        <a href="javascript:void(0);" onclick="abrirModalCarrinho()"><i class="fas fa-shopping-cart"></i> <span id="carrinho-count">(0)</span></a>
    </div>
    <div class="search-container">
        <form class="search-box" action="#" method="get">
            <input type="text" id="search-input" placeholder="Buscar notícias, gadgets...">
            <button type="submit">Buscar</button>
        </form>
        <div id="search-results"></div>
    </div>
</header>

<section class="noticias-container">
    <h2>Últimas Notícias Tech</h2>
    <?php if (empty($noticias_db)): ?>
        <p style="text-align:center; color:#888; margin-top:50px;">Nenhuma notícia cadastrada ainda.</p>
    <?php else: ?>
        <?php foreach ($noticias_db as $noticia):
            $noticia_image_src = $noticia['imagem_url'];
            if (!filter_var($noticia_image_src, FILTER_VALIDATE_URL)) {
                $noticia_image_src = 'img/' . $noticia_image_src; 
            }
        ?>
            <div class="noticia-item" id="noticia-<?= htmlspecialchars($noticia['id']) ?>">
                <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                <span class="data">Publicado em: <?php echo date('d de F de Y', strtotime($noticia['data_publicacao'])); ?></span>
                <img src="<?php echo htmlspecialchars($noticia_image_src); ?>" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
                <?php echo $noticia['conteudo_completo']; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<footer>
    <p>&copy; <?php echo date('Y'); ?> TechShop+. Todos os direitos reservados à empresa TechNova.</p>
    <p>Conectando você ao futuro da tecnologia.</p>
</footer>

<div id="modal-carrinho" class="modal">
    <div class="modal-content">
        <span class="close" onclick="fecharModalCarrinho()">&times;</span>
        <h3>Seu Carrinho de Compras</h3>
        <div id="lista-carrinho"></div>
        <h4>Total: R$ <span id="total-carrinho">0.00</span></h4>
        <a href="pagamento.php" class="botao-comprar">Finalizar Compra</a>
    </div>
</div>

<div id="modal-contato" class="modal">
    <div class="modal-content">
        <span class="close" onclick="fecharModalContato()">&times;</span>
        <h3>Fale Conosco na TechShop+</h3>
        <p><i class="fas fa-phone"></i> Telefone: <a href="tel:+5511987654321">(51) 98765-4321</a></p>
        <p><i class="fas fa-envelope"></i> E-mail: <a href="mailto:atendimento@techshop.com">atendimento@techshop.com</a></p>
        <p><i class="fas fa-clock"></i> Horário de Atendimento: Segunda a Sexta, das 09h às 18h (GMT-3)</p>
        <p><i class="fas fa-map-marker-alt"></i> Endereço: aven.Getúlio vargas, 4502 - Centro, Alvorada - RS</p>
    </div>
</div>

<div id="modal-categorias" class="modal">
    <div class="modal-content">
        <span class="close" onclick="fecharModalCategorias()">&times;</span>
        <h3>Explore Nossas Categorias</h3>
        <table>
            <tr>
                <td><a href="produtos.php?categoria=smartwatch" class="categoria-link">⌚ Smartwatches</a></td>
                <td><a href="produtos.php?categoria=headset" class="categoria-link">🎧 Headsets Gamer</a></td>
            </tr>
            <tr>
                <td><a href="produtos.php?categoria=automacao" class="categoria-link">🏠 Automação Residencial</a></td>
                <td><a href="produtos.php?categoria=mouse" class="categoria-link">🖱️ Mouses e Teclados</a></td>
            </tr>
            <tr>
                <td><a href="produtos.php?categoria=placa-video" class="categoria-link">🎮 Placas de Vídeo</a></td>
                <td><a href="produtos.php?categoria=consoles" class="categoria-link">🕹️ Consoles & Jogos</a></td>
            </tr>
            <tr>
                <td><a href="produtos.php?categoria=hardware" class="categoria-link">🖥️ Componentes de Hardware</a></td>
                <td><a href="produtos.php?categoria=perifericos" class="categoria-link">⌨️ Periféricos Essenciais</a></td>
            </tr>
            <tr>
                <td><a href="produtos.php?categoria=placa-mae" class="categoria-link">🧠 Placas Mãe</a></td>
                <td><a href="produtos.php?categoria=memoria-ram" class="categoria-link">🧬 Memórias RAM</a></td>
            </tr>
            <tr>
                <td><a href="produtos.php?categoria=ssd" class="categoria-link">💾 Armazenamento SSD/HD</a></td>
                <td><a href="produtos.php?categoria=tv" class="categoria-link">📺 Smart TVs e Monitores</a></td>
            </tr>
            <tr>
                <td><a href="produtos.php?categoria=acessorios" class="categoria-link">💡 Acessórios e Gadgets</a></td>
                <td><a href="produtos.php?categoria=baterias" class="categoria-link">🔋 Carregadores & Baterias</a></td>
            </tr>
        </table>
    </div>
</div>

<script>
    function abrirModal(modalId) {
        document.getElementById(modalId).classList.add('active');
        document.getElementById(modalId).style.display = 'flex';
    }

    function fecharModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
        document.getElementById(modalId).style.display = 'none';
    }

    function abrirModalCarrinho() {
       carregarCarrinhoDaSessao(); 
        abrirModal('modal-carrinho');
    }

    function fecharModalCarrinho() {
        fecharModal('modal-carrinho');
    }

    function abrirModalContato() {
        abrirModal('modal-contato');
    }

    function fecharModalContato() {
        fecharModal('modal-contato');
    }

    function abrirModalCategorias() {
        abrirModal('modal-categorias');
    }

    function fecharModalCategorias() {
        fecharModal('modal-categorias');
    }

    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    const noticiasData = [
        <?php
        foreach ($noticias_db as $noticia) {
            echo "{ id: \"noticia-" . htmlspecialchars($noticia['id']) . "\", titulo: " . json_encode($noticia['titulo']) . ", conteudo_completo: " . json_encode(strip_tags($noticia['conteudo_completo'])) . " },";
        }
        ?>
    ];

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            searchResults.innerHTML = '';

            if (searchTerm.length > 0) {
                const filteredNews = noticiasData.filter(news =>
                    news.titulo.toLowerCase().includes(searchTerm) ||
                    news.conteudo_completo.toLowerCase().includes(searchTerm)
                );

                if (filteredNews.length > 0) {
                    filteredNews.forEach(news => {
                        const resultLink = document.createElement('a');
                        resultLink.href = `#${news.id}`; 
                        resultLink.textContent = news.titulo;
                        resultLink.onclick = function(event) {
                           event.preventDefault(); 
                            document.getElementById(news.id).scrollIntoView({ behavior: 'smooth', block: 'start' });
                            searchResults.style.display = 'none';
                            searchInput.value = news.titulo; 
                        };
                        searchResults.appendChild(resultLink);
                    });
                    searchResults.style.display = 'block';
                } else {
                    const noResults = document.createElement('div');
                    noResults.textContent = "Nenhum resultado encontrado.";
                    noResults.style.padding = "10px";
                    noResults.style.color = "var(--text-dark)";
                    searchResults.appendChild(noResults);
                    searchResults.style.display = 'block';
                }
            } else {
                searchResults.style.display = 'none';
            }
        });
    }

    document.addEventListener('click', function(event) {
        if (searchInput && searchResults && !searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.style.display = 'none';
        }
    });

    let carrinho = [];

    function carregarCarrinhoDaSessao() {
        fetch('salvar_carrinho.php?acao=carregar')
            .then(response => response.json())
            .then(data => {
                if (data.carrinho) {
                    carrinho = data.carrinho;
                    atualizarCarrinhoVisual();
                }
            })
            .catch(error => console.error('Erro ao carregar carrinho:', error));
    }

    function salvarCarrinhoNaSessao() {
        fetch('salvar_carrinho.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ carrinho: carrinho })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status !== 'success') {
                console.error('Erro ao salvar carrinho:', data.message);
            }
        })
        .catch(error => console.error('Erro de rede ao salvar carrinho:', error));
    }

    function atualizarCarrinhoVisual() {
        const lista = document.getElementById('lista-carrinho');
        const totalElem = document.getElementById('total-carrinho');
        const carrinhoCountElem = document.getElementById('carrinho-count');
        lista.innerHTML = '';
        let total = 0;

        if (carrinho.length === 0) {
            lista.innerHTML = '<p style="text-align: center; color: #ccc;">Seu carrinho está vazio.</p>';
            carrinhoCountElem.classList.remove('has-items');
        } else {
            carrinho.forEach((item, index) => {
                let item_img_src = item.imagem;
                if (!item_img_src.startsWith('http://') && !item_img_src.startsWith('https://')) {
                    item_img_src = 'img/' + item_img_src;
                }

                total += item.preco;
                const div = document.createElement('div');
                div.classList.add('carrinho-item');
                div.innerHTML = `
                    <img src="${item_img_src}" alt="${item.nome}" class="carrinho-item-img">
                    <span class="carrinho-item-nome">${item.nome}</span>
                    <span class="carrinho-item-preco">R$ ${item.preco.toFixed(2).replace('.', ',')}</span>
                    <button class="remover-item-btn" onclick="removerItemCarrinho(${index})"><i class="fas fa-trash-alt"></i> Remover</button>
                `;
                lista.appendChild(div);
            });
            carrinhoCountElem.classList.add('has-items');
        }
        totalElem.innerText = total.toFixed(2).replace('.', ',');
        carrinhoCountElem.innerText = `(${carrinho.length})`;
    }

    function removerItemCarrinho(index) {
        carrinho.splice(index, 1);
        atualizarCarrinhoVisual();
        salvarCarrinhoNaSessao();
    }

    function salvarCarrinhoAntesDeFinalizar() {
        salvarCarrinhoNaSessao();
    }

    document.addEventListener('DOMContentLoaded', carregarCarrinhoDaSessao);
</script>

</body>
</html>
