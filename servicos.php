<?php
session_start();
include 'config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id_usuario'])) {
    echo "Erro: Usuário não está logado.";
    exit; // Para impedir o acesso à página
}

// Verifica se o usuário é barbeiro, então realiza a busca pelos serviços selecionados
if ($_SESSION['usuario']['tipo'] === 'barbeiro') {
    // Obter o ID do barbeiro logado
    $id_usuario = $_SESSION['usuario']['id_usuario'];

    // Buscar serviços selecionados pelo barbeiro
    $sql = "SELECT s.id_servico, s.nome, s.preco 
            FROM barbeiros_servicos bs 
            JOIN servicos s ON bs.id_servico = s.id_servico 
            WHERE bs.id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario]);
    $servicos_selecionados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Caso seja "cliente", busca todos os serviços disponíveis no banco
if ($_SESSION['usuario']['tipo'] === 'cliente') {
    // Buscar todos os serviços disponíveis
    $sql = "SELECT id_servico, nome, preco FROM servicos";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $servicos_disponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Serviços de Barbearia</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>
<!-- Navegação -->
<section class="fixed-top navigation">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="index.php"><img src="images/logo.png" alt="logo" class="logoHarstil"></a>
      <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse text-center" id="navbar">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="indexlogado.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link page-scroll" href="servicos.php">Serviços</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#pricing">Planos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Suporte</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="cadastro.php">Cadastro</a>
          </li>
        </ul>
        <a href="logout.php" class="btn btn-primary ml-lg-3 primary-shadow">Logout</a>
      </div>
    </nav>
  </div>
</section>
<!-- /Navegação -->

<!-- Exibição de serviços -->
<div class="container mt-5">
  <div class="row justify-content-center">
    <?php if ($_SESSION['usuario']['tipo'] === 'barbeiro'): ?>
      <!-- Card para cada serviço selecionado pelo barbeiro -->
      <?php if ($servicos_selecionados): ?>
        <?php foreach ($servicos_selecionados as $servico): ?>
          <div class="col-md-4">
            <div class="card mb-4">
              <div class="card-body text-center">
                <h5 class="card-title" style="color: #333;"><?php echo htmlspecialchars($servico['nome']); ?></h5>
                <p class="card-text" style="color: #000;">R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></p>
                <a class="btn btn-primary" href="about.php?servico_id=<?php echo $servico['id_servico']; ?>">Escolher Serviço</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-md-12 text-center">
          <h5>Nenhum serviço selecionado.</h5>
        </div>
      <?php endif; ?>

    <?php elseif ($_SESSION['usuario']['tipo'] === 'cliente'): ?>
      <!-- Card para cada serviço disponível para o cliente -->
      <?php if ($servicos_disponiveis): ?>
        <?php foreach ($servicos_disponiveis as $servico): ?>
          <div class="col-md-4">
            <div class="card mb-4">
              <div class="card-body text-center">
                <h5 class="card-title" style="color: #333;"><?php echo htmlspecialchars($servico['nome']); ?></h5>
                <p class="card-text" style="color: #000;">R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></p>
                <a class="btn btn-primary" href="about.php?servico_id=<?php echo $servico['id_servico']; ?>">Escolher Serviço</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-md-12 text-center">
          <h5>Nenhum serviço disponível.</h5>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
<!-- /Exibição de serviços -->

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
