<?php
session_start();
require_once 'config.php';

// Verifica se o formulário foi submetido
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cadastrar'])){
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];

    // Prepara e executa a consulta SQL para inserir um novo usuário
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)");
    $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $senha, 'tipo' => $tipo]);

    // Redireciona para a página de login após o cadastro
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Cadastro de Usuário
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="nome">Nome:</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha:</label>
                                <input type="password" name="senha" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="tipo">Tipo:</label>
                                <select name="tipo" class="form-control" required>
                                    <option value="cliente">Cliente</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button type="submit" name="cadastrar" class="btn btn-primary">Cadastrar</button>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
