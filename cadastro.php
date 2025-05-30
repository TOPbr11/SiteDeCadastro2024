<?php
require_once 'conecta.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    if (!empty($nome) && !empty($email)) {
        $stmt = $conexao->prepare("INSERT INTO Tbl_Usuarios (Nome, Email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);
        if ($stmt->execute()) {
            $mensagem = "Usuário cadastrado com sucesso!";
        } else {
            $erro = "Erro ao cadastrar o usuário.";
        }
        $stmt->close();
    } else {
        $erro = "Preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <nav>
        <a href="consulta.php">Consulta de Tarefas</a>
        <a href="tarefas.php">Cadastro de Tarefas</a>
        <a href="menu.html">Menu Principal</a>
    </nav>
    <div class="container">
        <h1>Cadastro de Usuários</h1>
        <?php if (!empty($mensagem)): ?>
            <p class="mensagem"><?php echo $mensagem; ?></p>
        <?php endif; ?>
        <?php if (!empty($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <form action="cadastro.php" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit" class="btn">Cadastrar</button>
        </form>
    </div>
</body>
</html>
