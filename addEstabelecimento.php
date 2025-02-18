<?php

session_start();
require_once 'config.php'; // Inclui o arquivo de configuração que contém a conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

?>


<!DOCTYPE html> 
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Adicionar Barbearia</title>
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
  <!-- navigation -->
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
  <!-- /navigation -->

  <!-- Formulário para Adicionar Barbearia -->
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card mb-4">
          <div class="card-header">
            Informações da Barbearia
          </div>
          <div class="card-body">
            <form action="processa_adicao.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <label for="nome">Nome da Barbearia</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
              </div>
              <div class="form-group">
                <label for="rua">Rua</label>
                <input type="text" class="form-control" id="rua" name="rua" required>
              </div>
              <div class="form-group">
                <label for="numero">Número</label>
                <input type="text" class="form-control" id="numero" name="numero" required>
              </div>
              <div class="form-group">
                <label for="bairro">Bairro</label>
                <input type="text" class="form-control" id="bairro" name="bairro" required>
              </div>
              <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" class="form-control" id="cidade" name="cidade" required>
              </div>
              <div class="form-group">
                <label for="estado">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado" required>
              </div>
              <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" required>
              </div>
              <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" required>
              </div>
              <div class="form-group">
                <label for="imagem">Imagem da Barbearia</label>
                <input type="file" class="form-control" id="imagem" name="imagem" required>
              </div>
              <button type="submit" class="btn btn-primary">Adicionar Barbearia</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- footer -->
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
            <li class="list-inline-item active"><a href="index.php">Home</a></li>
            <li class="list-inline-item"><a href="#team">Team</a></li>
            <li class="list-inline-item"><a href="#pricing">Pricing</a></li>
            <li class="list-inline-item"><a href="contact.php">Contact</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </footer>
  <!-- /footer -->

  <!-- jQuery -->
  <script src="plugins/jQuery/jquery.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="plugins/bootstrap/bootstrap.min.js"></script>
  <!-- Main Script -->
  <script src="js/script.js"></script>
</body>
</html>
