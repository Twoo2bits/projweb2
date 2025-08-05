<?php
session_start();
include 'config.php';

if (!isset($_SESSION['usuario_logado']) || !isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['salvar_dados'])) {
        $novo_nome = $_POST['nome_completo'];
        $novo_email = $_POST['email'];

        $stmt = $conn->prepare("UPDATE usuarios SET nome_completo = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $novo_nome, $novo_email, $usuario_id);
        if ($stmt->execute()) {
            $_SESSION['usuario_nome'] = $novo_nome;
            $_SESSION['usuario_email'] = $novo_email;
            $mensagem = "Dados atualizados com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar os dados.";
        }
    }
    if (isset($_POST['salvar_senha'])) {
        $senha_atual = $_POST['senha_atual'];
        $nova_senha = $_POST['nova_senha'];
        $confirma_nova_senha = $_POST['confirma_nova_senha'];
        $mensagem = "Funcionalidade de alterar senha a ser implementada com segurança.";
    }
}

$result = $conn->query("SELECT nome_completo, email FROM usuarios WHERE id = $usuario_id");
$usuario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil | TechShop+</title>
    <style>
        body { background: #0a0a0a; color: #fff; font-family: 'Roboto', sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: #151515; padding: 30px; border-radius: 15px; border: 1px solid #00ffff; }
        h2 { color: #00ffff; font-family: 'Orbitron', sans-serif; text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; background: #1a1a1a; border: 1px solid #8a2be2; color: #fff; border-radius: 5px; }
        .btn { background: #00ffff; color: #000; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; text-align: center; display: inline-block; text-decoration: none; }
        .btn:hover { background: #00ffee; }
        .mensagem { text-align: center; padding: 10px; background: #32CD32; color: #000; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Perfil</h2>
        <?php if (isset($mensagem)): ?>
            <p class="mensagem"><?= $mensagem ?></p>
        <?php endif; ?>

        <form method="POST" action="editar_perfil.php">
            <h3>Meus Dados</h3>
            <div class="form-group">
                <label for="nome_completo">Nome Completo</label>
                <input type="text" id="nome_completo" name="nome_completo" value="<?= htmlspecialchars($usuario['nome_completo']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>
            <button type="submit" name="salvar_dados" class="btn">Salvar Dados</button>
        </form>

        <hr style="margin: 30px 0; border-color: #8a2be2;">

        <form method="POST" action="editar_perfil.php">
             <h3>Alterar Senha</h3>
            <div class="form-group">
                <label for="senha_atual">Senha Atual</label>
                <input type="password" id="senha_atual" name="senha_atual" required>
            </div>
            <div class="form-group">
                <label for="nova_senha">Nova Senha</label>
                <input type="password" id="nova_senha" name="nova_senha" required>
            </div>
             <div class="form-group">
                <label for="confirma_nova_senha">Confirmar Nova Senha</label>
                <input type="password" id="confirma_nova_senha" name="confirma_nova_senha" required>
            </div>
            <button type="submit" name="salvar_senha" class="btn">Salvar Nova Senha</button>
        </form>
        <br><br>
        <a href="perfil.php" class="btn">Voltar ao Perfil</a>
    </div>
</body>
</html>
