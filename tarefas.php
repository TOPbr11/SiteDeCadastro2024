<?php
require_once 'conecta.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = trim($_POST['descricao']);
    $setor = trim($_POST['setor']);
    $prioridade = $_POST['prioridade'];
    $status = $_POST['status'];
    $data = $_POST['data'];
    $usuarioId = $_POST['usuario_id'];

    if (!empty($descricao) && !empty($setor) && !empty($prioridade) && !empty($status) && !empty($data) && !empty($usuarioId)) {
        $stmt = $conexao->prepare("INSERT INTO Tbl_Tarefas (Descricao, Setor, Prioridade, Status, Data, usuario_Id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $descricao, $setor, $prioridade, $status, $data, $usuarioId);
        
        if ($stmt->execute()) {
            $mensagem = "Tarefa cadastrada com sucesso!";
        } else {
            $erro = "Erro ao cadastrar a tarefa.";
        }
        $stmt->close();
    } else {
        $erro = "Por favor, preencha todos os campos.";
    }
}

$queryUsuarios = "SELECT ID, Nome FROM Tbl_Usuarios";
$resultUsuarios = $conexao->query($queryUsuarios);
$usuarios = $resultUsuarios->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Tarefas</title>
    <link rel="stylesheet" href="tarefas.css">
</head>
<body>
    <nav>
        <a href="cadastro.php">Cadastro de Usuários</a>
        <a href="consulta.php">Consulta de Tarefas</a>
        <a href="menu.html">Menu Principal</a>
    </nav>
    <div class="container">
        <h1>Cadastro de Tarefas</h1>
        <?php if (!empty($mensagem)): ?>
            <p class="mensagem"><?php echo $mensagem; ?></p>
        <?php endif; ?>
        <?php if (!empty($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <form action="tarefas.php" method="POST">
            <label for="descricao">Descrição da Tarefa:</label>
            <input type="text" id="descricao" name="descricao" required>
            <label for="setor">Setor:</label>
            <input type="text" id="setor" name="setor" required>
            <label for="prioridade">Prioridade:</label>
            <select id="prioridade" name="prioridade" required>
                <option value="Baixa">Baixa</option>
                <option value="Média">Média</option>
                <option value="Alta">Alta</option>
            </select>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="A fazer">A fazer</option>
                <option value="Fazendo">Fazendo</option>
                <option value="Pronto">Pronto</option>
            </select>
            <label for="data">Data:</label>
            <input type="date" id="data" name="data" required>
            <label for="usuario_id">Usuário:</label>
            <select id="usuario_id" name="usuario_id" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?php echo $usuario['ID']; ?>"><?php echo $usuario['Nome']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Cadastrar Tarefa</button>
        </form>
    </div>
</body>
</html>
