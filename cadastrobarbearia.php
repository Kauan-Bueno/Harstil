<?php
session_start();
require_once 'config.php';

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cadastrar'])) {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];
    $cpf = $_POST['cpf'];
    
    // Criptografa a senha
    $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);

    try {
        // Prepara e executa a consulta SQL para inserir um novo usuário
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo, cpf) VALUES (:nome, :email, :senha, :tipo, :cpf)");
        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha_hashed,
            'tipo' => $tipo,
            'cpf' => $cpf
        ]);

        // Obtém o ID do último usuário inserido
        $id_usuario = $pdo->lastInsertId();

        // Autentica o usuário após o cadastro
        $_SESSION['usuario'] = [
            'id_usuario' => $id_usuario,
            'nome' => $nome,
            'email' => $email,
            'tipo' => $tipo,
        ];

        // Redireciona para a página desejada após o cadastro
        header("Location: addEstabelecimento.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
  <meta charset="utf-8">
  <title>Harstil</title>

  <!-- mobile responsive meta -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
  <!-- ** Plugins Needed for the Project ** -->
  <!-- Bootstrap -->
  <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
  <!-- themefy-icon -->
  <link rel="stylesheet" href="plugins/themify-icons/themify-icons.css">
  <!-- slick slider -->
  <link rel="stylesheet" href="plugins/slick/slick.css">
  <!-- venobox popup -->
  <link rel="stylesheet" href="plugins/Venobox/venobox.css">
  <!-- aos -->
  <link rel="stylesheet" href="plugins/aos/aos.css">

  <!-- Main Stylesheet -->
  <link href="css/style.css" rel="stylesheet">
  
  <!--Favicon-->
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">

</head>

<body>
  

<!-- navigation -->
<section class="fixed-top navigation">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
      <a class="navbar-brand" href="index.php"><img src="images/logo.png" alt="logo" class="logoHarstil"></a>
      <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- navbar -->
      <div class="collapse navbar-collapse text-center" id="navbar">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link page-scroll" href="index.php">Planos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Suporte</a>
          </li>
        </ul>
        <a href="service.php" class="btn btn-primary ml-lg-3 primary-shadow">Login</a>
      </div>
    </nav>
  </div>
</section>
<!-- /navigation -->

<!-- service -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Cadastro de Usuario Business
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="cpf">CPF:</label>
                            <input type="text" name="cpf" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha:</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>
                                
                        <!-- seleção de tipo com cliente e admin disponível -->
                        <div class="form-group">
                            <label for="tipo">Tipo:</label>
                            <select name="tipo" class="form-control" required>
                                <option value="cliente">Cliente</option>
                                <option value="admin">Admin</option>  
                                <option value="barbeiro">Barbeiro</option> 
                            </select>
                        </div>
                        <button type="submit" name="cadastrar" class="btn btn-primary">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /service -->

<!-- footer -->
<footer class="footer-section footer" style="background-image: url(images/backgrounds/footer-bg.png);">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 text-center text-lg-left mb-4 mb-lg-0">
        <!-- logo -->
        <a href="index.php">
          <img class="img-fluid" src="images/logo.png" alt="logo">
        </a>
      </div>
      <!-- footer menu -->
      <nav class="col-lg-8 align-self-center mb-5">
        <ul class="list-inline text-lg-right text-center footer-menu">
          <li class="list-inline-item active"><a href="index.php">Home</a></li>
          <li class="list-inline-item"><a class="page-scroll" href="#feature">Feature</a></li>
          <li class="list-inline-item"><a href="about.php">About</a></li>
          <li class="list-inline-item"><a class="page-scroll" href="#team">Team</a></li>
          <li class="list-inline-item"><a class="page-scroll" href="#pricing">Pricing</a></li>
          <li class="list-inline-item"><a href="contact.php">Contact</a></li>
        </ul>
      </nav>
      <!-- footer social icon -->
      <nav class="col-12">
        <ul class="list-inline text-lg-right text-center social-icon">
          <li class="list-inline-item">
            <a class="facebook" href="#"><i class="ti-facebook"></i></a>
          </li>
          <li class="list-inline-item">
            <a class="twitter" href="#"><i class="ti-twitter-alt"></i></a>
          </li>
          <li class="list-inline-item">
            <a class="linkedin" href="#"><i class="ti-linkedin"></i></a>
          </li>
          <li class="list-inline-item">
            <a class="black" href="#"><i class="ti-github"></i></a>
          </li>
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
<!-- slick slider -->
<script src="plugins/slick/slick.min.js"></script>
<!-- venobox -->
<script src="plugins/Venobox/venobox.min.js"></script>
<!-- aos -->
<script src="plugins/aos/aos.js"></script>
<!-- Main Script -->
<script src="js/script.js"></script>

</body>
</html>
