<?php
session_start();
include 'config.php';

$all_products_for_search = [];
$result_search = $conn->query("SELECT id, nome, descricao FROM produtos");
if ($result_search) {
    while ($row = $result_search->fetch_assoc()) {
        $all_products_for_search[] = $row;
    }
}

$produtos_destaque = [];
$result_featured = $conn->query("SELECT id, nome, descricao, preco, preco_promocional, imagem_url FROM produtos WHERE is_featured = 1");
if ($result_featured) {
    while($row = $result_featured->fetch_assoc()){
        $produtos_destaque[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>TechShop+ | Sua Loja de Tecnologia de Ponta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="shortcut icon" type="image/x-icon" href="logoApp.png">
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
        }

        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(90deg, rgb(134, 15, 214), rgb(66, 188, 245));
            padding: 15px 80px 15px 40px;
            box-shadow: 0 0 20px var(--primary);
            flex-wrap: wrap;
            gap: 20px;
            position: relative;
        }

        .profile-corner-container {
            position: absolute;
            top: 50%;
            right: 25px;
            transform: translateY(-50%);
            z-index: 10;
        }

        .profile-corner-container img {
            height: 48px;
            width: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .profile-corner-container .profile-icon-link, .profile-corner-container .login-icon-link {
            font-size: 42px;
            color: #000;
            text-decoration: none;
            transition: color 0.3s ease, transform 0.3s ease;
            display: inline-block;
        }

        .profile-corner-container a:hover img, .profile-corner-container a:hover .fa-user-circle, .profile-corner-container a:hover .fa-sign-in-alt {
            transform: scale(1.1);
            box-shadow: 0 0 15px var(--accent);
            color: var(--accent);
        }


        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            height: 60px;
            object-fit: contain;
        }

        .logo-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.2em;
            font-weight: 700;
            background: linear-gradient(45deg, #00ffff, #8a2be2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 15px rgba(0, 255, 255, 0.7);
        }

        nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 25px;
        }

        nav a {
            color: #000;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.3s ease, transform 0.2s ease;
            position: relative;
        }

        nav a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--accent);
            transition: width 0.3s ease;
        }

        nav a:hover {
            color: var(--accent);
            transform: translateY(-2px);
        }

        nav a:hover::after {
            width: 100%;
        }

        .search-container {
            position: relative;
            margin-top: 10px;
            flex: 1 1 100%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .search-box {
            display: flex;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.4);
        }

        .search-box input[type="text"] {
            width: 70%;
            padding: 12px 15px;
            border: none;
            background-color: #1a1a1a;
            color: var(--text);
            outline: none;
            font-size: 1em;
        }

        .search-box button {
            width: 30%;
            padding: 12px 20px;
            border: none;
            background: var(--primary);
            color: #000;
            cursor: pointer;
            transition: background 0.3s ease, color 0.3s ease;
            font-weight: bold;
            font-size: 1em;
        }

        .search-box button:hover {
            background: var(--accent);
            color: var(--bg);
        }

        #search-results {
            position: absolute;
            width: 100%;
            background-color: #1a1a1a;
            border: 1px solid var(--primary);
            border-top: none;
            border-radius: 0 0 10px 10px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 0 5px 15px rgba(0, 255, 255, 0.4);
            display: none; 
        }

        #search-results a {
            display: block;
            padding: 10px 15px;
            color: var(--text);
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        #search-results a:hover {
            background-color: #2a2a2a;
            color: var(--accent);
        }


        .actions {
            display: flex;
            gap: 15px;
            margin-top: 10px;
            align-items: center;
        }

        .actions a {
            background: transparent;
            border: 2px solid #000;
            color: #000;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            text-decoration: none;
            font-weight: bold;
            position: relative; 
            display: flex;
            align-items: center;
        }

        .actions a:hover {
            background: #000;
            color: var(--accent);
            border-color: var(--accent);
        }

        .actions a .fas {
            margin-right: 5px;
        }
        
        .actions a.gear-icon:hover::before {
            content: "Gerenciamento";
            position: absolute;
            bottom: -30px; 
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8em;
            white-space: nowrap;
            z-index: 10;
        }

        
        .apresentacao {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            padding: 80px 40px;
            background: linear-gradient(to right, #0f0f0f, #1a1a1a);
            gap: 50px;
            border-bottom: 4px solid var(--primary);
        }

        .apresentacao img {
            width: 500px;
            max-width: 100%;
            border-radius: 15px;
            box-shadow: 0 0 30px var(--primary);
            transition: transform 0.6s ease, box-shadow 0.6s ease;
        }

        .apresentacao img:hover {
            transform: scale(1.03) rotate(1deg);
            box-shadow: 0 0 40px var(--accent);
        }

        .apresentacao .texto {
            max-width: 650px;
            text-align: center;
        }

        .apresentacao h2 {
            color: var(--primary);
            font-size: 2.8em;
            margin-bottom: 20px;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 15px rgba(0,255,255,0.8);
        }

        .apresentacao p {
            font-size: 1.2em;
            line-height: 1.8;
            color: #e0e0e0;
        }
        .apresentacao p strong {
            color: var(--accent);
        }

        
        .produtos-container {
            position: relative;
            padding: 60px 20px;
            background: #0f0f0f;
            margin-top: 50px;
            border-top: 4px solid var(--secondary);
        }

        .produtos-container h2 {
            text-align: center;
            font-size: 2.5em;
            color: var(--primary);
            margin-bottom: 40px;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 15px rgba(0,255,255,0.7);
        }

        .produtos {
            display: flex;
            gap: 30px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 10px 0 30px;
            -ms-overflow-style: none;  
            scrollbar-width: none;  
        }

        .produtos::-webkit-scrollbar {
            display: none; 
        }

        .card {
            flex: 0 0 320px;
            background: var(--dark-card);
            border: 2px solid var(--primary);
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 0 20px rgba(0,255,255,0.3);
            transition: transform 0.4s ease, box-shadow 0.4s ease, background 0.4s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 0 35px var(--accent);
            background: #1e1e1e;
        }

        .produto-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(0,255,255,0.5);
            transition: transform 0.3s ease;
        }

        .card:hover .produto-img {
            transform: scale(1.01);
        }

        .card h3 {
            color: var(--primary);
            font-size: 1.5em;
            margin-bottom: 12px;
            font-family: 'Orbitron', sans-serif;
        }

        .card p {
            font-size: 1em;
            height: 60px;
            margin-bottom: 18px;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #bbb;
        }

        .preco {
            color: #0f0;
            font-weight: bold;
            font-size: 1.3em;
            margin-bottom: 15px;
            text-shadow: 0 0 8px rgba(0,255,0,0.5);
        }

        .card button {
            width: 100%;
            padding: 12px;
            border: none;
            background: var(--primary);
            color: #000;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            font-size: 1.1em;
        }

        .card button:hover {
            background: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 0 15px var(--accent);
        }

        .seta {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.4);
            font-size: 28px;
            padding: 15px 20px;
            cursor: pointer;
            z-index: 5;
            border-radius: 50%;
            backdrop-filter: blur(8px);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.6);
            transition: all 0.4s ease;
        }

        .seta:hover {
            background-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 30px var(--accent);
            transform: translateY(-50%) scale(1.15);
            color: var(--accent);
        }

        .seta.esquerda {
            left: 15px;
        }

        .seta.direita {
            right: 15px;
        }

        
        .why-choose-us {
            background: linear-gradient(to right, #1a1a1a, #0a0a0a);
            padding: 80px 40px;
            text-align: center;
            border-top: 4px solid var(--primary);
        }

        .why-choose-us h2 {
            font-size: 2.8em;
            color: var(--primary);
            margin-bottom: 50px;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 15px rgba(0,255,255,0.7);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-item {
            background: var(--dark-card);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(138, 43, 226, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--secondary);
            cursor: pointer;
        }

        .feature-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 0 30px rgba(138, 43, 226, 0.6);
        }

        .feature-item .icon {
            font-size: 3.5em;
            color: var(--accent);
            margin-bottom: 20px;
        }

        .feature-item h3 {
            font-size: 1.8em;
            color: var(--text);
            margin-bottom: 15px;
            font-family: 'Orbitron', sans-serif;
        }

        .feature-item p {
            font-size: 1.1em;
            color: #ccc;
        }

        
        .testimonials {
            background: linear-gradient(to left, #1a1a1a, #0a0a0a);
            padding: 80px 40px;
            text-align: center;
            border-top: 4px solid var(--accent);
        }

        .testimonials h2 {
            font-size: 2.8em;
            color: var(--primary);
            margin-bottom: 50px;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 15px rgba(0,255,255,0.7);
        }

        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .testimonial-card {
            background: var(--dark-card);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,255,255,0.3);
            text-align: left;
            border: 1px solid var(--primary);
            position: relative;
            overflow: hidden;
        }

        .testimonial-card::before {
            content: "❝";
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 4em;
            color: rgba(0,255,255,0.1);
            z-index: 0;
        }

        .testimonial-card p {
            font-size: 1.1em;
            font-style: italic;
            color: #e0e0e0;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .testimonial-card .author {
            font-weight: bold;
            color: var(--accent);
            font-size: 1.1em;
            position: relative;
            z-index: 1;
        }

        
        .latest-news {
            background: linear-gradient(to right, #0a0a0a, #1a1a1a);
            padding: 80px 40px;
            text-align: center;
            border-top: 4px solid var(--secondary);
        }

        .latest-news h2 {
            font-size: 2.8em;
            color: var(--primary);
            margin-bottom: 50px;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 15px rgba(0,255,255,0.7);
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .news-card {
            background: var(--dark-card);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,255,255,0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--primary);
        }

        .news-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 0 30px var(--accent);
        }

        .news-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .news-card:hover img {
            transform: scale(1.05);
        }

        .news-content {
            padding: 25px;
            text-align: left;
        }

        .news-content h3 {
            font-size: 1.6em;
            color: var(--accent);
            margin-bottom: 10px;
        }

        .news-content p {
            font-size: 0.95em;
            color: #ccc;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .news-content .read-more {
            display: inline-block;
            color: var(--primary);
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .news-content .read-more:hover {
            color: var(--accent);
        }

        
        .monte-seu-pc {
            width: 100%;
            background: linear-gradient(135deg, #0e0e0e, #1d1d1d);
            padding: 80px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-top: 4px solid var(--primary);
            margin-top: 50px;
        }

        .monte-pc-container {
            max-width: 1200px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 60px;
            justify-content: center;
        }

        .monte-pc-img img {
            width: 450px;
            max-width: 100%;
            border-radius: 18px;
            box-shadow: 0 0 40px var(--primary);
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .monte-pc-img img:hover {
            transform: scale(1.06) rotate(-2deg);
            box-shadow: 0 0 50px var(--accent);
        }

        .monte-pc-texto {
            max-width: 600px;
            color: var(--text);
            animation: fadeInUp 1s ease;
            text-align: center;
        }

        .monte-pc-texto h2 {
            font-size: 3em;
            color: var(--primary);
            margin-bottom: 20px;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 20px var(--accent);
        }

        .monte-pc-texto p {
            font-size: 1.2em;
            margin-bottom: 30px;
            line-height: 1.8;
            color: #ddd;
        }

        .botao-montagem {
            display: inline-block;
            padding: 16px 32px;
            background: var(--primary);
            color: #000;
            font-weight: bold;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 0 15px var(--accent);
            font-size: 1.2em;
        }

        .botao-montagem:hover {
            background: var(--accent);
            color: var(--bg);
            transform: translateY(-5px);
            box-shadow: 0 0 25px var(--primary);
        }

        
        footer {
            background: #111;
            color: #aaa;
            text-align: center;
            padding: 30px;
            margin-top: 50px;
            border-top: 4px solid var(--primary);
        }

       
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .modal.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 800px;
            color: white;
            box-shadow: 0 0 30px var(--primary);
            position: relative;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            align-items: center;
            text-align: left;
            transform: translateY(20px);
            opacity: 0;
            transition: transform 0.4s ease, opacity 0.4s ease;
        }
        .modal.active .modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        .modal-content img {
            width: 300px;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 0 15px var(--accent);
        }

        .modal .close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 30px;
            cursor: pointer;
            color: var(--accent);
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .modal .close:hover {
            color: var(--primary);
            transform: rotate(90deg);
        }

        #lista-carrinho div {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 12px 0;
            padding: 10px;
            background: #222;
            border-radius: 8px;
            border: 1px solid rgba(0,255,255,0.2);
        }

        #lista-carrinho button {
            background: #e74c3c;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        #lista-carrinho button:hover {
            background: #c0392b;
        }

        #modal-carrinho h3, #modal-contato h3, #modal-categorias h3 {
            font-family: 'Orbitron', sans-serif;
            color: var(--primary);
            font-size: 2em;
            margin-bottom: 20px;
            width: 100%;
            text-align: center;
        }
        #modal-carrinho h4 {
            width: 100%;
            text-align: right;
            margin-top: 20px;
            font-size: 1.5em;
            color: var(--accent);
        }
        .botao-comprar {
            display: inline-block;
            margin-top: 20px;
            padding: 14px 28px;
            background: var(--accent);
            color: #000;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            font-size: 1.1em;
        }

        .botao-comprar:hover {
            background: #00ffff;
            transform: translateY(-3px);
            box-shadow: 0 0 20px var(--accent);
        }

        #modal-contato .modal-content {
            max-width: 550px;
            padding: 40px;
            text-align: center;
            flex-direction: column;
        }

        #modal-contato p {
            font-size: 1.2em;
            margin: 18px 0;
            color: #ddd;
            display: flex;
            align-items: center;
            gap: 15px;
            justify-content: center;
        }

        #modal-contato .fas {
            color: var(--primary);
            font-size: 1.5em;
        }

        .categoria-link {
            display: block;
            padding: 15px 25px;
            background-color: #1e1e1e;
            color: var(--primary);
            border-radius: 12px;
            text-align: center;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px #00ffff40;
            font-size: 1.1em;
        }

        .categoria-link:hover {
            background-color: var(--primary);
            color: #000;
            box-shadow: 0 0 15px var(--primary);
            transform: translateY(-3px);
        }

        #modal-categorias .modal-content {
            max-width: 750px;
            padding: 40px;
        }

        #modal-categorias table {
            width: 100%;
            margin-top: 30px;
            border-spacing: 0 15px; 
        }

        #modal-categorias td {
            padding: 5px; 
        }
        
        .feature-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        .feature-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .feature-modal-content {
            background: var(--dark-card);
            padding: 40px;
            border-radius: 20px;
            width: 90%;
            max-width: 550px;
            border: 2px solid var(--accent);
            box-shadow: 0 0 40px rgba(138, 43, 226, 0.6);
            text-align: center;
            position: relative;
            transform: scale(0.7);
            opacity: 0;
            transition: transform 0.4s cubic-bezier(0.18, 0.89, 0.32, 1.28), opacity 0.4s ease;
        }
        .feature-modal-overlay.active .feature-modal-content {
            transform: scale(1);
            opacity: 1;
        }
        .feature-modal-content .icon {
            font-size: 4em;
            color: var(--primary);
            margin-bottom: 20px;
            text-shadow: 0 0 15px var(--primary);
        }
        .feature-modal-content h3 {
            font-family: 'Orbitron', sans-serif;
            color: var(--text);
            font-size: 2em;
            margin-bottom: 15px;
        }
        .feature-modal-content p {
            font-size: 1.1em;
            color: #ccc;
            line-height: 1.7;
            margin-bottom: 30px;
        }
        .feature-modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 2em;
            color: var(--text);
            cursor: pointer;
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .feature-modal-close:hover {
            color: var(--accent);
            transform: rotate(90deg);
        }
      
        @media (max-width: 1024px) {
            header {
                padding: 15px 20px;
                justify-content: center;
            }
            nav {
                order: 3;
                width: 100%;
                justify-content: center;
                margin-top: 15px;
            }
            .logo-container, .actions {
                margin-bottom: 10px;
            }
            .search-container { 
                order: 4;
                width: 90%;
            }

            .apresentacao {
                flex-direction: column;
                padding: 60px 20px;
                gap: 30px;
            }
            .apresentacao img {
                width: 90%;
            }
            .apresentacao h2 {
                font-size: 2.2em;
            }
            .apresentacao p {
                font-size: 1em;
            }

            .produtos-container {
                padding: 40px 10px;
            }
            .seta {
                font-size: 22px;
                padding: 10px 15px;
            }

            .why-choose-us, .testimonials, .latest-news, .monte-seu-pc {
                padding: 60px 20px;
            }

            .why-choose-us h2, .testimonials h2, .latest-news h2, .monte-pc-texto h2 {
                font-size: 2.2em;
            }

            .monte-pc-container {
                flex-direction: column;
                gap: 40px;
            }
            .monte-pc-img img {
                width: 90%;
            }
            .monte-pc-texto {
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .logo-text {
                font-size: 1.8em;
            }
            nav a {
                font-size: 0.9em;
            }
            .search-container { 
                width: 95%;
            }
            .search-box input {
                font-size: 0.9em;
            }
            .search-box button {
                font-size: 0.9em;
            }

            .actions {
                justify-content: center;
                width: 100%;
            }

            .card {
                flex: 0 0 280px;
                padding: 20px;
            }
            .produto-img {
                height: 180px;
            }

            #modal-carrinho .modal-content,
            #modal-contato .modal-content,
            #modal-categorias .modal-content {
                width: 95%;
                padding: 25px;
            }
            #modal-contato p {
                font-size: 1em;
                gap: 10px;
            }
            #modal-contato .fas {
                font-size: 1.3em;
            }
            .categoria-link {
                font-size: 1em;
                padding: 12px 15px;
            }
        }

            #lista-carrinho .carrinho-item-img {
                width: 60px; 
                height: 60px; 
                object-fit: cover;
                border-radius: 5px;
                margin-right: 15px;
            }

        @media (max-width: 480px) {
            .logo {
                height: 50px;
            }
            .logo-text {
                font-size: 1.5em;
            }
            header {
                padding: 10px 15px;
                padding-right: 70px;
            }
            nav {
                gap: 15px;
            }
            .actions a {
                padding: 8px 15px;
                font-size: 0.9em;
            }
            .search-box input, .search-box button {
                padding: 10px;
            }

            .apresentacao h2 {
                font-size: 1.8em;
            }
            .apresentacao p {
                font-size: 0.9em;
            }

            .produtos-container h2,
            .why-choose-us h2, .testimonials h2, .latest-news h2, .monte-pc-texto h2 {
                font-size: 1.8em;
            }

            .feature-item .icon {
                font-size: 3em;
            }
            .feature-item h3 {
                font-size: 1.5em;
            }

            .news-content h3 {
                font-size: 1.4em;
            }
            .news-content p {
                font-size: 0.85em;
            }

            .monte-pc-texto h2 {
                font-size: 2.2em;
            }
            .monte-pc-texto p {
                font-size: 1em;
            }
            .botao-montagem {
                padding: 12px 24px;
                font-size: 1em;
            }
            #modal-carrinho h3, #modal-contato h3, #modal-categorias h3 {
                font-size: 1.5em;
            }
            #modal-carrinho h4 {
                font-size: 1.2em;
            }
            .botao-comprar {
                padding: 12px 20px;
                font-size: 1em;
            }
        }
        .modal-content button {
            background: var(--accent); 
            color: #000; 
            border: none; 
            padding: 12px 25px; 
            border-radius: 8px; 
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1em;
            transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.6); 
        }

        .modal-content button:hover {
            background: #00ffff; 
            transform: translateY(-3px);
            box-shadow: 0 0 25px var(--accent); 
        }
    
    </style>
</head>
<body>

<header>
    <div class="logo-container">
        <a href="index.php"><img src="logoApp2.png" alt="TechShop+ Logo" class="logo"></a>
        <span class="logo-text">TechShop+</span>
    </div>
    <nav>
        <a href="index.php">Início</a>
        <a href="javascript:void(0);" onclick="abrirModalCategorias()">Categorias</a>
        <a href="#produtos-destaque">Produtos</a>
        <a href="javascript:void(0);" onclick="abrirModalContato()">Contato</a>
    </nav>
    <div class="actions">
        <?php if (!isset($_SESSION['usuario_logado'])): ?>
            <a href="cadastro.php">Cadastro</a>
        <?php endif; ?>
        <a href="admin.php" class="gear-icon" title="Gerenciamento"><i class="fas fa-cog"></i></a>
        <a href="javascript:void(0);" onclick="abrirModalCarrinho()"><i class="fas fa-shopping-cart"></i> <span id="carrinho-count"></span></a>
    </div>
    <div class="search-container">
        <form class="search-box" action="produtos.php" method="get">
            <input type="text" id="search-input" name="search" placeholder="Buscar gadgets, acessórios...">
            <button type="submit">Buscar</button>
        </form>
        <div id="search-results"></div>
    </div>

    <div class="profile-corner-container">
        <?php if (isset($_SESSION['usuario_logado'])): ?>
            <a href="perfil.php" 
               class="<?php echo (isset($_SESSION['usuario_foto']) && !empty($_SESSION['usuario_foto'])) ? '' : 'profile-icon-link'; ?>"
               title="Ver Perfil">
                <?php if (isset($_SESSION['usuario_foto']) && !empty($_SESSION['usuario_foto'])): ?>
                    <img src="<?= htmlspecialchars($_SESSION['usuario_foto']) ?>" alt="Foto de Perfil">
                <?php else: ?>
                    <i class="fas fa-user-circle"></i>
                <?php endif; ?>
            </a>
        <?php else: ?>
            <a href="login.php" class="login-icon-link" title="Fazer Login">
                <i class="fas fa-sign-in-alt"></i>
            </a>
        <?php endif; ?>
    </div>
</header>

<section class="apresentacao">
    <img src="imgprodutosdesing.png" alt="Imagem de Apresentação do Site: Variedade de Produtos Tecnológicos" />
    <div class="texto">
        <h2>Bem-vindo à <span class="logo-text">TechShop+</span></h2>
        <p>A TechShop+ nasceu com a missão de conectar você à tecnologia de ponta, oferecendo uma curadoria exclusiva dos melhores gadgets, componentes e acessórios. Nossa loja é o seu portal para o futuro, com produtos que transformam a maneira como você vive, trabalha e se diverte. Explore nosso universo de inovação e descubra o que há de mais recente no mundo da tecnologia!</p>
    </div>
</section>

<section class="produtos-container" id="produtos-destaque">
    <h2>Produtos em Destaque</h2>
    <button class="seta esquerda" onclick="rolar(-1)">&#10094;</button>
    <div class="produtos" id="carrossel">
        <?php foreach (array_merge($produtos_destaque, $produtos_destaque) as $produto): ?>
            <div class="card">
                <?php
                    $image_src = $produto['imagem_url'];
                    if (!filter_var($image_src, FILTER_VALIDATE_URL)) {
                        $image_src = 'img/' . $image_src;
                    }
                ?>
                <img src="<?= htmlspecialchars($image_src) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" class="produto-img">
                <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                <p><?= htmlspecialchars($produto['descricao']) ?></p>
                <div class="preco">R$ <?= number_format(!empty($produto['preco_promocional']) ? $produto['preco_promocional'] : $produto['preco'], 2, ',', '.') ?></div>
                <button onclick="abrirModalProduto('<?= htmlspecialchars(addslashes($produto['nome'])) ?>', '<?= htmlspecialchars(addslashes($produto['descricao'])) ?>', '<?= !empty($produto['preco_promocional']) ? $produto['preco_promocional'] : $produto['preco'] ?>', '<?= htmlspecialchars(addslashes($image_src)) ?>')">Ver Detalhes</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="seta direita" onclick="rolar(1)">&#10095;</button>

    <div id="modal-produto" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharModalProduto()">&times;</span>
            <img id="modal-img" src="" alt="" />
            <div>
                <h3 id="modal-nome"></h3>
                <p id="modal-desc"></p>
                <div class="preco" id="modal-preco"></div>
                <button onclick="adicionarAoCarrinho()">Adicionar ao Carrinho</button>
            </div>
        </div>
    </div>

    <div id="modal-carrinho" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharModalCarrinho()">&times;</span>
            <h3>Seu Carrinho de Compras</h3>
            <div id="lista-carrinho"></div>
            <h4>Total: R$ <span id="total-carrinho">0.00</span></h4>
            <a href="pagamento.php" class="botao-comprar" onclick="salvarCarrinhoAntesDeFinalizar()">Finalizar Compra</a>
        </div>
    </div>

    <div id="modal-contato" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharModalContato()">&times;</span>
            <h3>Fale Conosco na TechShop+</h3>
            <p><i class="fas fa-phone"></i> Telefone: <a href="tel:+5511987654321">(51) 98765-4321</a></p>
            <p><i class="fas fa-envelope"></i> E-mail: <a href="mailto:atendimento@techshop.com">atendimento@techshop.com</a></p>
            <p><i class="fas fa-clock"></i> Horário de Atendimento: Segunda a Sexta, das 09h às 18h </p>
            <p><i class="fas fa-map-marker-alt"></i> Endereço: ave.Getúlio vargas, 4502 - Centro, Alvorada - RS</p>
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
</section>

<section class="why-choose-us">
    <h2>Por Que Escolher a TechShop+?</h2>
    <div class="features-grid">
        <div class="feature-item" data-modal="seguranca">
            <div class="icon"><i class="fas fa-shield-alt"></i></div>
            <h3>Segurança & Confiabilidade</h3>
            <p>Transações protegidas e garantia de produtos autênticos e de alta qualidade.</p>
        </div>
        <div class="feature-item" data-modal="entrega">
            <div class="icon"><i class="fas fa-truck"></i></div>
            <h3>Entrega Rápida</h3>
            <p>Receba seus produtos em tempo recorde, com rastreamento completo do pedido.</p>
        </div>
        <div class="feature-item" data-modal="suporte">
            <div class="icon"><i class="fas fa-headset"></i></div>
            <h3>Suporte Especializado</h3>
            <p>Nossa equipe está pronta para ajudar com qualquer dúvida ou problema, antes e depois da compra.</p>
        </div>
        <div class="feature-item" data-modal="precos">
            <div class="icon"><i class="fas fa-tags"></i></div>
            <h3>Melhores Preços</h3>
            <p>Ofertas exclusivas e preços competitivos, para você ter a melhor tecnologia sem gastar muito.</p>
        </div>
    </div>
</section>

<section class="testimonials">
    <h2>O Que Nossos Clientes Dizem</h2>
    <div class="testimonial-grid">
        <div class="testimonial-card">
            <p>"Comprei meu novo headset e a qualidade é impecável! A entrega foi super rápida e o atendimento me surpreendeu."</p>
            <div class="author">- Alanzoka, Gamer Profissional</div>
        </div>
        <div class="testimonial-card">
            <p>"A variedade de produtos é incrível, e consegui montar meu PC gamer dos sonhos com as melhores peças. Recomendo muito!"</p>
            <div class="author">- Naruto Uzumaki, Entusiasta de Hardware</div>
        </div>
        <div class="testimonial-card">
            <p>"Sempre encontro as últimas novidades em tecnologia aqui. O site é fácil de usar e as descrições dos produtos são bem detalhadas."</p>
            <div class="author">- Jorjão da linguiça, Inovadoror Tech</div>
        </div>
    </div>
</section>

<section class="latest-news">
    <h2>Fique por Dentro: Últimas Notícias Tech</h2>
    <div class="news-grid">
        <div class="news-card">
            <img src="processadorfuturo.jpg" alt="Notícia sobre novos processadores">
            <div class="news-content">
                <h3>Lançamento: Processadores de Última Geração</h3>
                <p>Descubra os novos processadores que prometem revolucionar o mercado de alto desempenho...</p>
                <a href="noticiastech.php" class="read-more">Leia Mais <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="news-card">
            <img src="smartfuturo.webp" alt="Notícia sobre tendências de Smartwatch">
            <div class="news-content">
                <h3>Tendências: Smartwatches para 2025</h3>
                <p>Conheça os smartwatches que vão dominar o pulso dos apaixonados por tecnologia neste ano...</p>
                <a href="noticiastech.php" class="read-more">Leia Mais <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="news-card">
            <img src="metaverso.jpeg" alt="Notícia sobre Realidade Virtual">
            <div class="news-content">
                <h3>O Futuro da Realidade Virtual</h3>
                <p>A realidade virtual continua a evoluir, e as novas tecnologias prometem experiências ainda mais imersivas...</p>
                <a href="noticiastech.php" class="read-more">Leia Mais <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<section class="monte-seu-pc">
    <div class="monte-pc-container">
        <div class="monte-pc-img">
            <img src="pcgamer.jpg" alt="Monte seu PC Gamer Personalizado" />
        </div>
        <div class="monte-pc-texto">
            <h2>⚙️ Monte Seu PC Gamer Personalizado!</h2>
            <p>
                A TechNova, criadora da TechShop+, oferece um serviço exclusivo de montagem de computadores sob medida. Com nossa expertise, você seleciona cada componente para criar o PC perfeito para suas necessidades, seja para games, trabalho ou criação de conteúdo. Conte com alta performance, estética impressionante e um preço que cabe no seu orçamento!
            </p>
            <a href="montagem.php" class="botao-montagem">Monte o seu agora!</a>
        </div>
    </div>
</section>

<footer>
    <p>&copy; <?php echo date('Y'); ?> TechShop+. Todos os direitos reservados à empresa TechNova.</p>
    <p>Conectando você ao futuro da tecnologia.</p>
</footer>

<div class="feature-modal-overlay" id="feature-modal">
    <div class="feature-modal-content">
        <span class="feature-modal-close">&times;</span>
        <div class="icon" id="modal-icon"></div>
        <h3 id="modal-title"></h3>
        <p id="modal-text"></p>
    </div>
</div>

<script>
    const carrossel = document.getElementById('carrossel');
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    
    const allProductsForSearch = [
        <?php
        foreach ($all_products_for_search as $product) {
            echo "{ name: " . json_encode($product['nome']) . ", id: " . json_encode($product['id']) . " },";
        }
        ?>
    ];
    
    function rolar(direcao) {
        const scrollAmount = direcao * (carrossel.querySelector('.card').offsetWidth + 30);
        const currentScroll = carrossel.scrollLeft;
        const maxScroll = carrossel.scrollWidth - carrossel.clientWidth;

        if (direcao === 1 && currentScroll >= maxScroll / 2) {
            carrossel.scrollLeft = 0;
        } else if (direcao === -1 && currentScroll <= 0) {
            carrossel.scrollLeft = maxScroll / 2;
        }
        carrossel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        searchResults.innerHTML = ''; 

        if (searchTerm.length > 0) {
            const filteredProducts = allProductsForSearch.filter(product => 
                product.name.toLowerCase().includes(searchTerm)
            );

            if (filteredProducts.length > 0) {
                filteredProducts.forEach(product => {
                    const resultLink = document.createElement('a');
                    resultLink.href = `produtos.php?search=${encodeURIComponent(product.name)}`; 
                    resultLink.textContent = product.name;
                    searchResults.appendChild(resultLink);
                });
                searchResults.style.display = 'block'; 
            } else {
                searchResults.style.display = 'none'; 
            }
        } else {
            searchResults.style.display = 'none'; 
        }
    });

    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
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

    let produtoAtual = {}; 
    
    function abrirModal(modalId) {
        document.getElementById(modalId).classList.add('active');
    }

    function fecharModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }
    
    function abrirModalProduto(nome, desc, preco, imagem) {
        produtoAtual = { nome, desc, preco: parseFloat(preco), imagem };
        document.getElementById('modal-nome').innerText = nome;
        document.getElementById('modal-desc').innerText = desc;
        document.getElementById('modal-preco').innerText = `R$ ${parseFloat(preco).toFixed(2).replace('.', ',')}`;
        document.getElementById('modal-img').src = imagem; 
        abrirModal('modal-produto');
    }

    function fecharModalProduto() {
        fecharModal('modal-produto');
    }

    function adicionarAoCarrinho() {
        carrinho.push({ ...produtoAtual }); 
        atualizarCarrinhoVisual(); 
        salvarCarrinhoNaSessao(); 
        fecharModalProduto(); 
    }
    
    function abrirModalCarrinho() {
        carregarCarrinhoDaSessao(); 
        abrirModal('modal-carrinho');
    }

    function fecharModalCarrinho() {
        fecharModal('modal-carrinho');
    }

    function atualizarCarrinhoVisual() {
        const lista = document.getElementById('lista-carrinho');
        const totalElem = document.getElementById('total-carrinho');
        const carrinhoCountElem = document.getElementById('carrinho-count');
        lista.innerHTML = ''; 
        let total = 0;

        if (carrinho.length === 0) {
            lista.innerHTML = '<p style="text-align: center; color: #ccc;">Seu carrinho está vazio.</p>';
        } else {
            carrinho.forEach((item, index) => {
                total += item.preco;
                const div = document.createElement('div');
                div.classList.add('carrinho-item');
                div.innerHTML = `
                    <img src="${item.imagem}" alt="${item.nome}" class="carrinho-item-img">
                    <span class="carrinho-item-nome">${item.nome}</span>
                    <span class="carrinho-item-preco">R$ ${item.preco.toFixed(2).replace('.', ',')}</span>
                    <button class="remover-item-btn" onclick="removerItemCarrinho(${index})"><i class="fas fa-trash-alt"></i> Remover</button>
                `;
                lista.appendChild(div);
            });
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

    const featureModalData = {
        seguranca: {
            icon: '<i class="fas fa-shield-alt"></i>',
            title: 'Segurança & Confiabilidade',
            text: 'Compre com tranquilidade. Utilizamos criptografia de ponta e os gateways de pagamento mais seguros do mercado para proteger suas informações. Todos os produtos são autênticos, com garantia de fábrica, assegurando a qualidade que você espera e merece.'
        },
        entrega: {
            icon: '<i class="fas fa-truck"></i>',
            title: 'Entrega Rápida',
            text: 'Nossa logística otimizada garante que seu pedido seja processado e despachado em tempo recorde. Oferecemos rastreamento completo para que você possa acompanhar cada etapa do seu novo gadget até a sua porta, com segurança e agilidade.'
        },
        suporte: {
            icon: '<i class="fas fa-headset"></i>',
            title: 'Suporte Especializado',
            text: 'Nossa equipe não é formada por robôs. São apaixonados por tecnologia, prontos para oferecer um atendimento humanizado e solucionar suas dúvidas. Do processo de escolha ao pós-venda, estamos aqui para garantir a melhor experiência.'
        },
        precos: {
            icon: '<i class="fas fa-tags"></i>',
            title: 'Melhores Preços',
            text: 'Acreditamos que a tecnologia de ponta deve ser acessível. Negociamos diretamente com os melhores fornecedores para oferecer preços competitivos e promoções exclusivas, garantindo que você faça o melhor negócio sem abrir mão da qualidade.'
        }
    };
    
    const featureModal = document.getElementById('feature-modal');
    const modalIcon = document.getElementById('modal-icon');
    const modalTitle = document.getElementById('modal-title');
    const modalText = document.getElementById('modal-text');

    document.querySelectorAll('.feature-item').forEach(item => {
        item.addEventListener('click', () => {
            const modalId = item.dataset.modal;
            const data = featureModalData[modalId];
            
            modalIcon.innerHTML = data.icon;
            modalTitle.textContent = data.title;
            modalText.textContent = data.text;
            
            featureModal.classList.add('active');
        });
    });

    featureModal.querySelector('.feature-modal-close').addEventListener('click', () => {
        featureModal.classList.remove('active');
    });

    featureModal.addEventListener('click', (e) => {
        if (e.target === featureModal) {
            featureModal.classList.remove('active');
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        carregarCarrinhoDaSessao();
    });
</script>

</body>
</html>
