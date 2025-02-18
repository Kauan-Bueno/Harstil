<?php
// Iniciar a sessão
session_start();

var_dump($_SESSION);

require_once 'config.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario']['id_usuario'])) {
    echo "Erro: Usuário não está logado.";
    exit();
}

// Verificar se o usuário é um barbeiro
$id_usuario = $_SESSION['usuario']['id_usuario'];
$query_verifica_barbeiro = "SELECT tipo FROM usuarios WHERE id_usuario = :id_usuario";
$stmt = $pdo->prepare($query_verifica_barbeiro);
$stmt->bindParam(':id_usuario', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario || $usuario['tipo'] !== 'barbeiro') {
    echo "Erro: Apenas barbeiros podem adicionar serviços.";
    exit();
}

// Verificar se o formulário foi enviado corretamente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['servicos'])) {
    $servicos_selecionados = $_POST['servicos'];

    try {
        // Iniciar transação
        $pdo->beginTransaction();

        // Apagar serviços antigos do barbeiro
        $query_delete = "DELETE FROM barbeiros_servicos WHERE id_usuario = :id_usuario";
        $stmt_delete = $pdo->prepare($query_delete);
        $stmt_delete->bindParam(':id_usuario', $id_usuario);
        $stmt_delete->execute();

        // Inserir novos serviços selecionados na tabela barbeiros_servicos
        $query_insert = "INSERT INTO barbeiros_servicos (id_usuario, id_servico, id_barbearia) VALUES (:id_usuario, :id_servico, :id_barbearia)";
        $stmt_insert = $pdo->prepare($query_insert);

        // Pegar o id_barbearia da sessão
        $id_barbearia = $_SESSION['barbearia']['id_barbearia'];

        foreach ($servicos_selecionados as $id_servico) {
            $stmt_insert->bindParam(':id_usuario', $id_usuario);
            $stmt_insert->bindParam(':id_servico', $id_servico);
            $stmt_insert->bindParam(':id_barbearia', $id_barbearia); // Adiciona o id_barbearia
            $stmt_insert->execute();
        }

        // Confirmar a transação
        $pdo->commit();

        // Redirecionar após o sucesso
        header("Location: indexlogado.php");
        exit();
    } catch (PDOException $e) {
        // Desfazer a transação em caso de erro
        $pdo->rollBack();
        echo "Erro ao salvar os serviços: " . $e->getMessage();
    }
} else {
    echo "Nenhum serviço foi selecionado.";
}