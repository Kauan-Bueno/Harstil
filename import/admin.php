<?php
session_start();
require_once 'config.php';

// Verifica se o usuário está logado como admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Consulta SQL para obter os horários agendados e os respectivos usuários
$stmt = $pdo->query("SELECT h.id, h.data, h.horario, u.nome AS nome_usuario FROM horarios h INNER JOIN usuarios u ON h.usuario_id = u.id");
$horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            Agendar Horário (admin)
                        </div>
                        <form action="logout.php" method="post">
                            <button type="submit" class="btn btn-danger">Sair</button>
                        </form>
                    </div>
                    </div>
                    <div class="card-body">
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Horário</th>
                                    <th>Usuário</th>
                                    <th>Editar agendamento</th>
                                    <th>Apagar horário</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($horarios as $horario): ?>
                                    <tr>
                                        <td><?php echo $horario['data']; ?></td>
                                        <td><?php echo $horario['horario']; ?></td>
                                        <td><?php echo $horario['nome_usuario']; ?></td>
                                        <td><a class="btn btn-warning d-flex justify-content-center" href="editarHorario.php?id=<?= $horario['id'] ?>">Editar</a></td>
                                        <td><a onclick="return confirm('Deseja deletar mesmo esse agendamento?')" class="btn btn-danger d-flex justify-content-center" href="apagarHorario.php?id=<?= $horario['id'] ?>">Apagar</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
