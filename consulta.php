<?php
require_once 'conecta.php';

$mensagem = '';
$erro = '';
$alterando = false;

if (isset($_GET['excluir_id'])) {
    $excluirId = $_GET['excluir_id'];

    $stmt = $conexao->prepare("DELETE FROM Tbl_Tarefas WHERE ID = ?");
    $stmt->bind_param("i", $excluirId);

    if ($stmt->execute()) {
        $mensagem = "Tarefa excluída com sucesso!";
    } else {
        $erro = "Erro ao excluir a tarefa.";
    }

    $stmt->close();
}

if (isset($_GET['editar_id'])) {
    $alterando = true;
    $editarId = $_GET['editar_id'];

    $stmt = $conexao->prepare("SELECT * FROM Tbl_Tarefas WHERE ID = ?");
    $stmt->bind_param("i", $editarId);
    $stmt->execute();
    $result = $stmt->get_result();
    $tarefa = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conexao->prepare("UPDATE Tbl_Tarefas SET Status = ? WHERE ID = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        $mensagem = "Status da tarefa alterado com sucesso!";
    } else {
        $erro = "Erro ao alterar o status da tarefa.";
    }

    $stmt->close();
}

$queryTarefas = "
    SELECT t.ID, t.Descricao, t.Setor, t.Prioridade, t.Status, t.Data, u.Nome AS Usuario
    FROM Tbl_Tarefas t
    JOIN Tbl_Usuarios u ON t.usuario_Id = u.ID
";
$resultTarefas = $conexao->query($queryTarefas);
$tarefas = $resultTarefas->fetch_all(MYSQLI_ASSOC);
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Tarefas</title>
    <link rel="stylesheet" href="consulta.css">
</head>
<body>
    <nav>
        <a href="cadastro.php">Cadastro de Usuários</a>
        <a href="tarefas.php">Cadastro de Tarefas</a>
        <a href="menu.html">Menu Principal</a>
    </nav>
    <div class="container">
        <h1>Consulta de Tarefas</h1>
        <?php if (!empty($mensagem)): ?>
            <p class="mensagem"><?php echo $mensagem; ?></p>
        <?php endif; ?>
        <?php if (!empty($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <?php if ($alterando): ?>
            <h2>Alterar Status da Tarefa</h2>
            <form action="consulta.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $tarefa['ID']; ?>">
                
                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="a fazer" <?php if ($tarefa['Status'] == 'a fazer') echo 'selected'; ?>>A Fazer</option>
                    <option value="fazendo" <?php if ($tarefa['Status'] == 'fazendo') echo 'selected'; ?>>Fazendo</option>
                    <option value="pronto" <?php if ($tarefa['Status'] == 'pronto') echo 'selected'; ?>>Pronto</option>
                </select><br><br>

                <button type="submit" name="alterar">Alterar</button>
            </form>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Setor</th>
                    <th>Prioridade</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tarefas as $tarefa): ?>
                    <tr>
                        <td><?php echo $tarefa['ID']; ?></td>
                        <td><?php echo $tarefa['Descricao']; ?></td>
                        <td><?php echo $tarefa['Setor']; ?></td>
                        <td><?php echo $tarefa['Prioridade']; ?></td>
                        <td><?php echo $tarefa['Status']; ?></td>
                        <td><?php echo $tarefa['Data']; ?></td>
                        <td><?php echo $tarefa['Usuario']; ?></td>
                        <td>
                            <a href="?excluir_id=<?php echo $tarefa['ID']; ?>" class="btn-excluir">Excluir</a>
                            <a href="?editar_id=<?php echo $tarefa['ID']; ?>" class="btn-alterar">Alterar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
