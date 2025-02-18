<?php
session_start();
include 'config.php';

// Verifica se o usuário está logado e se o tipo é barbeiro
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id_usuario'])) {
    echo "Erro: Usuário não está logado.";
    exit;
}

if ($_SESSION['usuario']['tipo'] !== 'barbeiro') {
    echo "Erro: Apenas barbeiros podem adicionar serviços.";
    exit;
}

// Obter o id_barbearia do barbeiro
$query = "SELECT b.id_barbearia FROM barbeiros b JOIN usuarios u ON b.id_usuario = u.id_usuario WHERE u.id_usuario = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['usuario']['id_usuario']]);
$id_barbearia = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Selecionar Serviços</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="plugins/slick/slick.css">
  <link rel="stylesheet" href="plugins/Venobox/venobox.css">
  <link rel="stylesheet" href="plugins/aos/aos.css">
  <link href="css/style.css" rel="stylesheet">
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body>
  <section class="fixed-top navigation">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="index.php"><img src="images/logo.png" alt="logo" class="logoHarstil"></a>
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-center" id="navbar">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="indexlogado.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link page-scroll" href="#pricing">Planos</a>
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

  <div class="container mt-5">
    <h2>Selecione os Serviços</h2>
    <form method="POST" action="processa_servicos.php">
      <div class="form-group">
        <!-- Checkbox para selecionar todos os serviços -->
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="select_all" onclick="toggleAllCheckboxes(this)">
          <label class="form-check-label" for="select_all">
            Pacote completo, selecionar todos os serviços abaixo
          </label>
        </div>

        <?php
        // Buscar todos os serviços disponíveis
        $sql = "SELECT * FROM servicos";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($servicos as $servico): ?>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="servicos[]" value="<?php echo $servico['id_servico']; ?>" id="servico_<?php echo $servico['id_servico']; ?>">
            <label class="form-check-label" for="servico_<?php echo $servico['id_servico']; ?>">
              <?php echo htmlspecialchars($servico['nome']); ?> - R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?>
            </label>
          </div>
        <?php endforeach; ?>
      </div>
      <button type="submit" class="btn btn-primary">Salvar Serviços</button>
    </form>
</div>

<script>
  // Função para selecionar ou desmarcar todas as checkboxes
  function toggleAllCheckboxes(source) {
    // Seleciona todas as checkboxes de serviços
    const checkboxes = document.querySelectorAll('input[name="servicos[]"]');
    checkboxes.forEach((checkbox) => {
      checkbox.checked = source.checked; // Marca/desmarca com base no estado da checkbox "Pacote completo"
    });
  }
</script>


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
            <li class="list-inline-item"><a href="about.php">About</a></li>
            <li class="list-inline-item"><a href="team.php">Team</a></li>
            <li class="list-inline-item"><a href="contact.php">Contact</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </footer>

  <script src="plugins/jQuery/jquery.min.js"></script>
  <script src="plugins/bootstrap/bootstrap.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>
