<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monte Seu PC | TechShop+</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00ffff; 
            --secondary: #8a2be2; 
            --bg: #0a0a0a; 
            --text: #ffffff; 
            --accent: #00ffee; 
            --dark-card: #151515; 
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
            min-height: 100vh; 
            display: flex;
            flex-direction: column;
        }

        
        header {
            background: linear-gradient(90deg, #8a2be2, #00ffff);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 0 20px var(--primary);
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            height: 50px; 
            object-fit: contain;
        }

        .logo-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8em; 
            font-weight: 700;
            background: linear-gradient(45deg, #00ffff, #8a2be2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.7);
        }

        .home-btn {
            background: var(--primary);
            color: #000;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }

        .home-btn:hover {
            background: var(--accent);
            color: var(--bg);
            transform: translateY(-2px);
            box-shadow: 0 0 15px var(--accent);
        }

        
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
            padding: 60px 40px;
            justify-content: center;
            align-items: flex-start;
            flex-grow: 1; 
        }

        
        .imagem-pc {
            flex: 1 1 450px;
            background-color: var(--dark-card);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 0 30px var(--secondary);
            animation: fadeIn 1s ease-out;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 25px;
        }

        .imagem-pc img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(138, 43, 226, 0.6);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .imagem-pc img:hover {
            transform: scale(1.02);
            box-shadow: 0 0 30px var(--accent);
        }
        
        .imagem-pc .description {
            text-align: center;
            color: #ccc;
            font-size: 1.1em;
            line-height: 1.7;
        }

        
        .selecao {
            flex: 2 1 600px;
            background-color: var(--dark-card);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 0 30px var(--primary);
            animation: slideInRight 1s ease-out;
        }

        .selecao h2 {
            margin-bottom: 30px;
            color: var(--primary);
            font-size: 2.5em;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 15px rgba(0,255,255,0.7);
            text-align: center;
        }

        .componente {
            margin-bottom: 30px;
        }

        .componente label {
            display: block;
            margin-bottom: 12px;
            font-size: 1.2em;
            font-weight: bold;
            color: var(--accent);
        }

        .componente select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid rgba(0, 255, 255, 0.3);
            background-color: #0f0f0f;
            color: var(--text);
            font-size: 1em;
            transition: all 0.3s ease;
            cursor: pointer;
            -webkit-appearance: none; 
            -moz-appearance: none;    
            appearance: none;        
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2300ffff%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13%205.7L146.2%20224.2%2018.8%2075.1a17.6%2017.6%200%200%200-25.3%2023.3l137.9%20146.9c5.6%205.7%2013.3%208.7%2021%208.7s15.5-3%2021-8.7l137.9-146.9c8.6-9.2%208.6-22.9%200-32.2z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
        }

        .componente select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.7);
        }

        
        .especificacoes {
            background: #1a1a1a;
            border-radius: 15px;
            padding: 25px;
            margin-top: 35px;
            box-shadow: 0 0 15px var(--primary);
            border: 1px solid var(--primary);
        }

        .especificacoes h3 {
            color: var(--accent);
            font-size: 1.5em;
            margin-bottom: 15px;
            text-align: center;
        }

        .especificacoes ul {
            list-style: none;
            padding: 0;
        }

        .especificacoes li {
            padding: 10px 0;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.1em;
            color: #e0e0e0;
        }
        .especificacoes li:last-child {
            border-bottom: none;
        }
        .especificacoes li strong {
            color: var(--primary);
            flex-basis: 40%;
        }
        .especificacoes li span {
            flex-basis: 60%;
            text-align: right;
        }

       
        .preco-total {
            margin-top: 40px;
            font-size: 2em;
            font-weight: bold;
            color: #0f0;
            text-align: right;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.7);
        }

        .botao-comprar {
            display: block; 
            width: 100%;
            margin-top: 30px;
            padding: 18px 28px;
            background: var(--primary);
            color: #000;
            font-weight: bold;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 1.3em;
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.7);
        }

        .botao-comprar:hover {
            background: var(--accent);
            transform: translateY(-5px);
            box-shadow: 0 0 30px var(--accent);
        }

        
        .recommended-builds {
            width: 100%;
            padding: 80px 40px;
            background: linear-gradient(to right, #1a1a1a, #0a0a0a);
            text-align: center;
            border-top: 4px solid var(--secondary);
            margin-top: 50px;
        }

        .recommended-builds h2 {
            font-size: 2.8em;
            color: var(--primary);
            margin-bottom: 50px;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 15px rgba(0,255,255,0.7);
        }

        .builds-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .build-card {
            background: var(--dark-card);
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 0 25px rgba(138, 43, 226, 0.4);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            border: 2px solid var(--secondary);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .build-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 0 40px var(--accent);
        }

        .build-card h3 {
            font-size: 1.8em;
            color: var(--accent);
            margin-bottom: 15px;
            font-family: 'Orbitron', sans-serif;
        }

        .build-card ul {
            list-style: none;
            padding: 0;
            margin-bottom: 25px;
            text-align: left;
        }

        .build-card ul li {
            margin-bottom: 8px;
            color: #e0e0e0;
            font-size: 1.05em;
        }
        .build-card ul li strong {
            color: var(--primary);
        }

        .build-card .price {
            font-size: 1.8em;
            font-weight: bold;
            color: #0f0;
            margin-bottom: 20px;
            text-shadow: 0 0 8px rgba(0,255,0,0.5);
        }

        .build-card .select-build-btn {
            background: var(--primary);
            color: #000;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }

        .build-card .select-build-btn:hover {
            background: var(--accent);
            color: var(--bg);
            transform: translateY(-3px);
            box-shadow: 0 0 15px var(--accent);
        }
        
      
        .why-build-us {
            width: 100%;
            padding: 80px 40px;
            background: linear-gradient(to left, #1a1a1a, #0a0a0a);
            text-align: center;
            border-top: 4px solid var(--primary);
            margin-top: 50px;
        }

        .why-build-us h2 {
            font-size: 2.8em;
            color: var(--primary);
            margin-bottom: 50px;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 15px rgba(0,255,255,0.7);
        }

        .why-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .why-item {
            background: var(--dark-card);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,255,255,0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--primary);
        }

        .why-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 0 30px var(--accent);
        }

        .why-item .icon {
            font-size: 3.5em;
            color: var(--accent);
            margin-bottom: 20px;
        }

        .why-item h3 {
            font-size: 1.8em;
            color: var(--text);
            margin-bottom: 15px;
            font-family: 'Orbitron', sans-serif;
        }

        .why-item p {
            font-size: 1.1em;
            color: #ccc;
        }

        
        footer {
            background: #111;
            color: #aaa;
            text-align: center;
            padding: 30px;
            margin-top: auto; 
            border-top: 4px solid var(--primary);
        }

        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

       
        @media (max-width: 1200px) {
            .container {
                gap: 30px;
                padding: 40px 30px;
            }
            .imagem-pc, .selecao {
                flex: 1 1 100%; 
            }
        }

        @media (max-width: 768px) {
            header {
                padding: 15px 20px;
                justify-content: center;
                gap: 15px;
            }
            .logo-container {
                width: 100%;
                justify-content: center;
            }
            .home-btn {
                width: 100%;
                text-align: center;
            }
            .container {
                padding: 30px 20px;
            }
            .selecao h2, .recommended-builds h2, .why-build-us h2 {
                font-size: 2em;
            }
            .componente label {
                font-size: 1.1em;
            }
            .componente select {
                padding: 10px 12px;
                font-size: 0.95em;
            }
            .especificacoes {
                padding: 20px;
            }
            .especificacoes h3 {
                font-size: 1.3em;
            }
            .especificacoes li {
                font-size: 1em;
                flex-direction: column;
                align-items: flex-start;
            }
            .especificacoes li span {
                text-align: left;
                margin-top: 5px;
            }
            .preco-total {
                font-size: 1.8em;
                text-align: center;
            }
            .botao-comprar {
                padding: 15px 20px;
                font-size: 1.2em;
            }
            .build-card h3, .why-item h3 {
                font-size: 1.6em;
            }
            .build-card ul li, .why-item p {
                font-size: 1em;
            }
            .build-card .price {
                font-size: 1.6em;
            }
        }

        @media (max-width: 480px) {
            header {
                flex-direction: column;
            }
            .logo-text {
                font-size: 1.5em;
            }
            .home-btn {
                font-size: 0.9em;
            }
            .container {
                padding: 20px 15px;
            }
            .selecao h2, .recommended-builds h2, .why-build-us h2 {
                font-size: 1.6em;
            }
            .imagem-pc {
                padding: 20px;
            }
            .selecao {
                padding: 25px;
            }
            .componente {
                margin-bottom: 20px;
            }
            .componente label {
                font-size: 1em;
            }
            .componente select {
                font-size: 0.85em;
            }
            .especificacoes {
                padding: 15px;
            }
            .especificacoes h3 {
                font-size: 1.1em;
            }
            .especificacoes li {
                font-size: 0.9em;
            }
            .preco-total {
                font-size: 1.5em;
            }
            .botao-comprar {
                padding: 12px 18px;
                font-size: 1.1em;
            }
            .build-card {
                padding: 20px;
            }
            .build-card h3 {
                font-size: 1.4em;
            }
            .build-card .price {
                font-size: 1.4em;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="index.php"><img src="logoApp2.png" alt="TechShop+ Logo" class="logo"></a>
            <span class="logo-text">TechShop+</span>
        </div>
        <a href="index.php" class="home-btn"><i class="fas fa-home"></i> Voltar</a>
    </header>

    <div class="container">
        <div class="imagem-pc">
            <img src="pc2.jpg" alt="PC Gamer Personalizável" />
            <img src="pcgamer.jpg" alt="PC Gamer Personalizável" />
            <div class="description">
                <p>Construa o PC dos seus sonhos com a TechShop+! Selecione cada componente e veja o seu PC gamer ou de trabalho ganhar forma. Qualidade, compatibilidade e desempenho garantidos para você. Experimente o poder da personalização!</p>
            </div>
        </div>
        <div class="selecao">
            <h2>Monte seu PC Personalizado</h2>

            <div class="componente">
                <label for="processador"><i class="fas fa-microchip"></i> Processador</label>
                <select id="processador" onchange="atualizarConfiguracao()">
                    <option value="">-- Selecione um Processador --</option>
                    <option value="Intel Core i5-13600K|1500">Intel Core i5-13600K - R$ 1.500,00</option>
                    <option value="Intel Core i7-14700K|2500">Intel Core i7-14700K - R$ 2.500,00</option>
                    <option value="Intel Core i9-14900K|3800">Intel Core i9-14900K - R$ 3.800,00</option>
                    <option value="AMD Ryzen 5 7600X|1400">AMD Ryzen 5 7600X - R$ 1.400,00</option>
                    <option value="AMD Ryzen 7 7800X3D|2300">AMD Ryzen 7 7800X3D - R$ 2.300,00</option>
                    <option value="AMD Ryzen 9 7950X3D|3700">AMD Ryzen 9 7950X3D - R$ 3.700,00</option>
                </select>
            </div>

            <div class="componente">
                <label for="placa-mae"><i class="fas fa-Hdd"></i> Placa Mãe</label>
                <select id="placa-mae" onchange="atualizarConfiguracao()">
                    <option value="">-- Selecione uma Placa Mãe --</option>
                    <option value="ASUS Prime B760M-A (Intel)|900">ASUS Prime B760M-A (Intel) - R$ 900,00</option>
                    <option value="MSI PRO Z790-P WIFI (Intel)|1500">MSI PRO Z790-P WIFI (Intel) - R$ 1.500,00</option>
                    <option value="Gigabyte B650 AORUS Elite AX (AMD)|1100">Gigabyte B650 AORUS Elite AX (AMD) - R$ 1.100,00</option>
                    <option value="ASRock X670E Taichi (AMD)|2000">ASRock X670E Taichi (AMD) - R$ 2.000,00</option>
                </select>
            </div>

            <div class="componente">
                <label for="placa-video"><i class="fas fa-desktop"></i> Placa de Vídeo</label>
                <select id="placa-video" onchange="atualizarConfiguracao()">
                    <option value="">-- Selecione uma Placa de Vídeo --</option>
                    <option value="NVIDIA GeForce RTX 3060|2400">NVIDIA GeForce RTX 3060 - R$ 2.400,00</option>
                    <option value="NVIDIA GeForce RTX 4070 Super|4500">NVIDIA GeForce RTX 4070 Super - R$ 4.500,00</option>
                    <option value="NVIDIA GeForce RTX 4080 Super|6800">NVIDIA GeForce RTX 4080 Super - R$ 6.800,00</option>
                    <option value="AMD Radeon RX 6700 XT|2200">AMD Radeon RX 6700 XT - R$ 2.200,00</option>
                    <option value="AMD Radeon RX 7800 XT|3900">AMD Radeon RX 7800 XT - R$ 3.900,00</option>
                </select>
            </div>

            <div class="componente">
                <label for="memoria-ram"><i class="fas fa-memory"></i> Memória RAM</label>
                <select id="memoria-ram" onchange="atualizarConfiguracao()">
                    <option value="">-- Selecione a Memória RAM --</option>
                    <option value="16GB DDR4 (2x8GB) 3200MHz|350">16GB DDR4 (2x8GB) 3200MHz - R$ 350,00</option>
                    <option value="32GB DDR4 (2x16GB) 3600MHz|600">32GB DDR4 (2x16GB) 3600MHz - R$ 600,00</option>
                    <option value="32GB DDR5 (2x16GB) 5600MHz|800">32GB DDR5 (2x16GB) 5600MHz - R$ 800,00</option>
                    <option value="64GB DDR5 (2x32GB) 6000MHz|1500">64GB DDR5 (2x32GB) 6000MHz - R$ 1.500,00</option>
                </select>
            </div>

            <div class="componente">
                <label for="ssd-armazenamento"><i class="fas fa-hdd"></i> Armazenamento SSD</label>
                <select id="ssd-armazenamento" onchange="atualizarConfiguracao()">
                    <option value="">-- Selecione o Armazenamento --</option>
                    <option value="SSD NVMe PCIe 4.0 500GB|280">SSD NVMe PCIe 4.0 500GB - R$ 280,00</option>
                    <option value="SSD NVMe PCIe 4.0 1TB|490">SSD NVMe PCIe 4.0 1TB - R$ 490,00</option>
                    <option value="SSD NVMe PCIe 4.0 2TB|900">SSD NVMe PCIe 4.0 2TB - R$ 900,00</option>
                    <option value="SSD NVMe PCIe 5.0 1TB|1200">SSD NVMe PCIe 5.0 1TB - R$ 1.200,00</option>
                </select>
            </div>

            <div class="componente">
                <label for="fonte-alimentacao"><i class="fas fa-power-off"></i> Fonte de Alimentação</label>
                <select id="fonte-alimentacao" onchange="atualizarConfiguracao()">
                    <option value="">-- Selecione a Fonte --</option>
                    <option value="Corsair CV650 80 Plus Bronze (650W)|400">Corsair CV650 80 Plus Bronze (650W) - R$ 400,00</option>
                    <option value="Cooler Master MWE Gold V2 750W 80 Plus Gold|700">Cooler Master MWE Gold V2 750W 80 Plus Gold - R$ 700,00</option>
                    <option value="Seasonic Focus GX-850 80 Plus Gold (850W)|1000">Seasonic Focus GX-850 80 Plus Gold (850W) - R$ 1.000,00</option>
                </select>
            </div>

            <div class="componente">
                <label for="gabinete-pc"><i class="fas fa-box"></i> Gabinete</label>
                <select id="gabinete-pc" onchange="atualizarConfiguracao()">
                    <option value="">-- Selecione um Gabinete --</option>
                    <option value="Gabinete Gamer Aerocool Bolt RGB (Mid-Tower)|350">Gamer Aerocool Bolt RGB (Mid-Tower) - R$ 350,00</option>
                    <option value="Gabinete Minimalista NZXT H5 Flow (Mid-Tower)|550">Minimalista NZXT H5 Flow (Mid-Tower) - R$ 550,00</option>
                    <option value="Gabinete Gamer Lian Li O11 Dynamic EVO (Full-Tower)|900">Gamer Lian Li O11 Dynamic EVO (Full-Tower) - R$ 900,00</option>
                </select>
            </div>

            <div class="componente">
                <label for="perifericos-kit"><i class="fas fa-keyboard"></i> Periféricos (Opcional)</label>
                <select id="perifericos-kit" onchange="atualizarConfiguracao()">
                    <option value="">-- Nenhum --</option>
                    <option value="Kit Teclado e Mouse Gamer Redragon|180">Kit Teclado e Mouse Gamer Redragon - R$ 180,00</option>
                    <option value="Headset Gamer HyperX Cloud Stinger Core|250">Headset Gamer HyperX Cloud Stinger Core - R$ 250,00</option>
                    <option value="Monitor Gamer AOC Hero 24 (144Hz)|1200">Monitor Gamer AOC Hero 24" (144Hz) - R$ 1.200,00</option>
                </select>
            </div>

            <div class="especificacoes" id="especificacoes">
                <h3>Resumo da sua Configuração</h3>
                <ul>
                    <li>Nenhum componente selecionado.</li>
                </ul>
            </div>

            <div class="preco-total">Total Estimado: R$ <span id="precoTotal">0,00</span></div>
            <form id="formFinalizarMontagem" action="pagamento.php" method="POST" style="display: none;">
                <div id="hiddenInputsContainer"></div>
                <input type="hidden" name="total_montagem" id="inputTotalMontagem">
            </form>
            <button type="button" class="botao-comprar" onclick="submitMontagem()"><i class="fas fa-dollar-sign"></i> Finalizar Montagem</button>
        </div>
    </div>

    <section class="recommended-builds">
        <h2>Montagens Recomendadas</h2>
        <div class="builds-grid">
            <div class="build-card">
                <h3>Build Essencial Gamer</h3>
                <ul>
                    <li><strong>Processador:</strong> AMD Ryzen 5 7600X</li>
                    <li><strong>Placa Mãe:</strong> Gigabyte B650 AORUS Elite AX</li>
                    <li><strong>Placa de Vídeo:</strong> AMD Radeon RX 6700 XT</li>
                    <li><strong>Memória RAM:</strong> 16GB DDR5 5200MHz</li>
                    <li><strong>Armazenamento:</strong> SSD NVMe 1TB</li>
                    <li><strong>Fonte:</strong> Corsair CV650 (650W)</li>
                    <li><strong>Gabinete:</strong> Gamer Aerocool Bolt RGB</li>
                </ul>
                <div class="price">R$ 6.300,00</div>
                <button class="select-build-btn" onclick="preencherConfiguracao('gamer-essencial')"><i class="fas fa-cogs"></i> Carregar Configuração</button>
            </div>

            <div class="build-card">
                <h3>Build Alta Performance</h3>
                <ul>
                    <li><strong>Processador:</strong> Intel Core i7-14700K</li>
                    <li><strong>Placa Mãe:</strong> MSI PRO Z790-P WIFI</li>
                    <li><strong>Placa de Vídeo:</strong> NVIDIA GeForce RTX 4070 Super</li>
                    <li><strong>Memória RAM:</strong> 32GB DDR5 5600MHz</li>
                    <li><strong>Armazenamento:</strong> SSD NVMe 2TB</li>
                    <li><strong>Fonte:</strong> Cooler Master MWE Gold V2 750W</li>
                    <li><strong>Gabinete:</strong> Minimalista NZXT H5 Flow</li>
                </ul>
                <div class="price">R$ 10.750,00</div>
                <button class="select-build-btn" onclick="preencherConfiguracao('alta-performance')"><i class="fas fa-cogs"></i> Carregar Configuração</button>
            </div>

            <div class="build-card">
                <h3>Build Ultimate Profissional</h3>
                <ul>
                    <li><strong>Processador:</strong> Intel Core i9-14900K</li>
                    <li><strong>Placa Mãe:</strong> ASRock X670E Taichi (ou equivalente Intel)</li>
                    <li><strong>Placa de Vídeo:</strong> NVIDIA GeForce RTX 4080 Super</li>
                    <li><strong>Memória RAM:</strong> 64GB DDR5 6000MHz</li>
                    <li><strong>Armazenamento:</strong> SSD NVMe 2TB PCIe 5.0</li>
                    <li><strong>Fonte:</strong> Seasonic Focus GX-850 (850W)</li>
                    <li><strong>Gabinete:</strong> Gamer Lian Li O11 Dynamic EVO</li>
                </ul>
                <div class="price">R$ 17.500,00</div>
                <button class="select-build-btn" onclick="preencherConfiguracao('ultimate-pro')"><i class="fas fa-cogs"></i> Carregar Configuração</button>
            </div>
        </div>
    </section>

    <section class="why-build-us">
        <h2>Por Que Montar Seu PC Conosco?</h2>
        <div class="why-grid">
            <div class="why-item">
                <div class="icon"><i class="fas fa-tools"></i></div>
                <h3>Montagem Profissional</h3>
                <p>Nossos técnicos experientes garantem que seu PC será montado com perfeição e organização.</p>
            </div>
            <div class="why-item">
                <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                <h3>Testes Rigorosos</h3>
                <p>Cada PC montado passa por testes extensivos para assegurar estabilidade e desempenho máximo.</p>
            </div>
            <div class="why-item">
                <div class="icon"><i class="fas fa-cogs"></i></div>
                <h3>Compatibilidade Garantida</h3>
                <p>Ajudamos você a escolher componentes 100% compatíveis, evitando dores de cabeça futuras.</p>
            </div>
            <div class="why-item">
                <div class="icon"><i class="fas fa-award"></i></div>
                <h3>Garantia e Suporte</h3>
                <p>Oferecemos garantia estendida e suporte técnico dedicado para sua tranquilidade.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 TechShop+. Todos os direitos reservados à empresa TechNova.</p>
        <p>Sua plataforma para tecnologia de ponta.</p>
    </footer>

    <script>
        
        const builds = {
            'gamer-essencial': {
                'processador': 'AMD Ryzen 5 7600X|1400',
                'placa-mae': 'Gigabyte B650 AORUS Elite AX (AMD)|1100',
                'placa-video': 'AMD Radeon RX 6700 XT|2200',
                'memoria-ram': '16GB DDR5 (2x8GB) 5200MHz|450', 
                'ssd-armazenamento': 'SSD NVMe PCIe 4.0 1TB|490',
                'fonte-alimentacao': 'Corsair CV650 80 Plus Bronze (650W)|400',
                'gabinete-pc': 'Gamer Aerocool Bolt RGB (Mid-Tower)|350',
                'perifericos-kit': ''
            },
            'alta-performance': {
                'processador': 'Intel Core i7-14700K|2500',
                'placa-mae': 'MSI PRO Z790-P WIFI (Intel)|1500',
                'placa-video': 'NVIDIA GeForce RTX 4070 Super|4500',
                'memoria-ram': '32GB DDR5 (2x16GB) 5600MHz|800',
                'ssd-armazenamento': 'SSD NVMe PCIe 4.0 2TB|900',
                'fonte-alimentacao': 'Cooler Master MWE Gold V2 750W 80 Plus Gold|700',
                'gabinete-pc': 'Minimalista NZXT H5 Flow (Mid-Tower)|550',
                'perifericos-kit': 'Headset Gamer HyperX Cloud Stinger Core|250'
            },
            'ultimate-pro': {
                'processador': 'Intel Core i9-14900K|3800',
                'placa-mae': 'MSI PRO Z790-P WIFI (Intel)|1500', 
                'placa-video': 'NVIDIA GeForce RTX 4080 Super|6800',
                'memoria-ram': '64GB DDR5 (2x32GB) 6000MHz|1500',
                'ssd-armazenamento': 'SSD NVMe PCIe 5.0 1TB|1200',
                'fonte-alimentacao': 'Seasonic Focus GX-850 80 Plus Gold (850W)|1000',
                'gabinete-pc': 'Gamer Lian Li O11 Dynamic EVO (Full-Tower)|900',
                'perifericos-kit': 'Monitor Gamer AOC Hero 24 (144Hz)|1200'
            }
        };

        function atualizarConfiguracao() {
            const ids = [
                "processador", "placa-mae", "placa-video", "memoria-ram",
                "ssd-armazenamento", "fonte-alimentacao", "gabinete-pc", "perifericos-kit"
            ];
            let total = 0;
            let texto = '<h3>Resumo da sua Configuração</h3><ul>';
            let hasSelection = false;

            const hiddenInputsContainer = document.getElementById('hiddenInputsContainer');
            hiddenInputsContainer.innerHTML = '';

            const selectedComponentsData = {}; 

            ids.forEach(id => {
                const select = document.getElementById(id);
                if (select) { 
                    const valor = select.value;
                    let labelText = select.previousElementSibling ? select.previousElementSibling.textContent.replace(/\s\s+/g, ' ').trim() : id; 
                    labelText = labelText.replace(/<i class="[^"]*"><\/i>\s*/, ''); 

                    if (valor) {
                        const [descricao, preco] = valor.split('|');
                        const precoFloat = parseFloat(preco);
                        total += precoFloat;
                        texto += `<li><strong>${labelText}:</strong> <span>${descricao}</span></li>`;
                        hasSelection = true;

                        selectedComponentsData[labelText] = {
                            descricao: descricao,
                            preco: precoFloat
                        };

                        const inputDesc = document.createElement('input');
                        inputDesc.type = 'hidden';
                        inputDesc.name = `componentes[${labelText}][descricao]`;
                        inputDesc.value = descricao;
                        hiddenInputsContainer.appendChild(inputDesc);

                        const inputPreco = document.createElement('input');
                        inputPreco.type = 'hidden';
                        inputPreco.name = `componentes[${labelText}][preco]`;
                        inputPreco.value = precoFloat.toFixed(2);
                        hiddenInputsContainer.appendChild(inputPreco);

                    } else {
                        texto += `<li><strong>${labelText}:</strong> <span>Não selecionado</span></li>`;
                    }
                }
            });

            if (!hasSelection) {
                texto = '<h3>Resumo da sua Configuração</h3><ul><li>Nenhum componente selecionado.</li></ul>';
            } else {
                texto += '</ul>';
            }
            
            localStorage.setItem('pc_montado', JSON.stringify(selectedComponentsData));

            document.getElementById('inputTotalMontagem').value = total.toFixed(2);

            document.getElementById("especificacoes").innerHTML = texto;
            document.getElementById("precoTotal").innerText = total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function preencherConfiguracao(buildName) {
            const selectedBuild = builds[buildName];
            if (selectedBuild) {
                for (const componentId in selectedBuild) {
                    const selectElement = document.getElementById(componentId);
                    if (selectElement) {
                        selectElement.value = selectedBuild[componentId];
                    }
                }
                atualizarConfiguracao(); 
                window.scrollTo({ top: 0, behavior: 'smooth' }); 
            } else {
                console.error("Build configuration not found:", buildName);
            }
        }

        function submitMontagem() {
            
            atualizarConfiguracao(); 

            document.getElementById('formFinalizarMontagem').submit();
        }

        document.addEventListener('DOMContentLoaded', atualizarConfiguracao);
    </script>
</body>
</html>
