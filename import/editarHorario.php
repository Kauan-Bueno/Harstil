<?php
require_once("config.php");
session_start();

$id = $_GET['id'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM horarios WHERE horario = :id");
$stmt->execute(['id' => $id]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt_user = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt_user->execute(['id' => $resultado['usuario_id']]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agendar'])) {
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $usuario_id = $_POST['usuario_id'];

    $stmt_check = $pdo->prepare("SELECT COUNT(*) as total FROM horarios WHERE horario = :horario");
    $stmt_check->execute(['horario' => $horario]);
    $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($result_check['total'] > 0) {
        $error = "Este horário já foi agendado.";
    } else {
        $stmt_insert = $pdo->prepare("UPDATE horarios SET horario = :horario WHERE horario = :id");
        $stmt_insert->execute(['horario' => $horario, 'id' => $id]);
        $success = "Horário atualizado com sucesso!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Editar horário</title>
    <link rel="stylesheet" href="editarHorario.css">
</head>
<body class="my-login-page">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <p>Agendar Horário - <?= $user['nome'] ?></p>
                        </div>
                        <div>
                            <a href="admin.php" type="submit" class="btn btn-danger">Sair</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if (isset($success)) : ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="data">Data:</label>
                                <input type="hidden" name="usuario_id" value="<?= $resultado['usuario_id'] ?>">
                                <input type="datetime-local" name="horario" value="<?= $resultado['horario'] ?>" class="form-control" required>
                            </div>
                            <button type="submit" name="agendar" class="btn btn-primary">Agendar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
