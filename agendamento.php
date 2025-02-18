<?php
session_start();
include 'config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id_usuario'])) {
    echo "Erro: Usuário não está logado.";
    exit; // Para impedir o acesso à página
}

// Obter o ID do usuário logado
$id_usuario = $_SESSION['usuario']['id_usuario'];

// Verificar se o botão de "Pronto" foi clicado para atualizar o status
if (isset($_POST['pronto_agendamento'])) {
    $id_agendamento = $_POST['id_agendamento'];
    $status_selecionado = $_POST['status_selecionado'];

    // Atualiza o status do agendamento conforme a escolha do barbeiro
    $sql = "UPDATE agendamentos SET status = ? WHERE id_agendamento = ? AND id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status_selecionado, $id_agendamento, $id_usuario]);

    // Aqui removemos o redirecionamento para manter o usuário na mesma página
    // A página será atualizada automaticamente com o novo status
}

// Buscar os agendamentos do usuário
$sql = "SELECT id_agendamento, id_servico, data, horario, status
        FROM agendamentos
        WHERE id_usuario = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_usuario]);
$agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Meus Agendamentos - Harstil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <style>
    /* Estilo para garantir a responsividade */
    @media (max-width: 768px) {
      .table thead {
        display: none;
      }

      .table tbody,
      .table tr,
      .table td {
        display: block;
        width: 100%;
        box-sizing: border-box;
      }

      .table td {
        text-align: left;
        padding-left: 50%;
        position: relative;
      }

      .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        font-weight: bold;
      }
    }

    /* Adicionando estilo para o botão "Pronto" ao lado do select */
    .status-container {
      display: flex;
      align-items: center;
    }

    .status-container select {
      margin-right: 10px;
    }
  </style>
</head>
<body>
<!-- Navegação -->
<section class="fixed-top navigation">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="index.php"><img src="images/logo.png" alt="logo" class="logoHarstil"></a>
      <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse text-center" id="navbar">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="indexlogado.php">Home</a></li>
          <li class="nav-item"><a class="nav-link page-scroll" href="servicos.php">Serviços</a></li>
          <li class="nav-item"><a class="nav-link" href="#pricing">Planos</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Suporte</a></li>
          <li class="nav-item"><a class="nav-link" href="cadastro.php">Cadastro</a></li>
        </ul>
        <a href="logout.php" class="btn btn-primary ml-lg-3 primary-shadow">Logout</a>
      </div>
    </nav>
  </div>
</section>
<!-- /Navegação -->

<!-- Exibição de agendamentos -->
<div class="container mt-5">
  <div class="row justify-content-center">
    <h2 class="col-12 text-center mb-5">Meus Agendamentos</h2>
    <?php if (empty($agendamentos)): ?>
      <div class="col-12 text-center">
        <h5>Você não tem agendamentos no momento.</h5>
      </div>
    <?php else: ?>
      <div class="col-12">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID Agendamento</th>
              <th>ID Serviço</th>
              <th>Data</th>
              <th>Horário</th>
              <th>Status</th>
              <th>Ação</th> <!-- Nova coluna para o botão -->
            </tr>
          </thead>
          <tbody>
            <?php foreach ($agendamentos as $agendamento): ?>
              <tr>
                <td><?php echo htmlspecialchars($agendamento['id_agendamento']); ?></td>
                <td><?php echo htmlspecialchars($agendamento['id_servico']); ?></td>
                <td><?php echo htmlspecialchars($agendamento['data']); ?></td>
                <td><?php echo htmlspecialchars($agendamento['horario']); ?></td>
                <td><?php echo htmlspecialchars($agendamento['status']); ?></td>
                <td>
                  <!-- Formulário para selecionar status e clicar em "Pronto" -->
                  <form method="POST">
                    <input type="hidden" name="id_agendamento" value="<?php echo $agendamento['id_agendamento']; ?>">
                    <div class="status-container">
                      <select name="status_selecionado" class="form-control">
                        <option value="Confirmado" <?php echo ($agendamento['status'] == 'Confirmado') ? 'selected' : ''; ?>>Confirmado</option>
                        <option value="Cancelado" <?php echo ($agendamento['status'] == 'Cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                      </select>
                      <button type="submit" name="pronto_agendamento" class="btn btn-primary">Pronto</button>
                    </div>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
<!-- /Exibição de agendamentos -->

<!-- Rodapé -->
<footer class="footer-section footer" style="background-image: url(images/backgrounds/footer-bg.png);">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 text-center text-lg-left mb-4 mb-lg-0">
        <a href="index.php">
          <img class="img-fluid" src="images/logo.png" alt="logo">
        </a>
      </div>
      <nav class="col-lg-8 align-self-center mb-5">
        <ul class="list-inline text-lg-right text-center footer-menu">
          <li class="list-inline-item"><a href="index.php">Home</a></li>
          <li class="list-inline-item"><a href="about.php">Sobre</a></li>
          <li class="list-inline-item"><a href="team.php">Equipe</a></li>
          <li class="list-inline-item"><a href="contact.php">Contato</a></li>
        </ul>
      </nav>
    </div>
  </div>
</footer>
<!-- /Rodapé -->

<!-- jQuery -->
<script src="plugins/jQuery/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="plugins/bootstrap/bootstrap.min.js"></script>
<!-- Script principal -->
<script src="js/script.js"></script>
</body>
</html>
