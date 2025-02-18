<?php

include 'config.php';

session_start();
var_dump($_SESSION); // Verifique se as informações do usuário estão corretas

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'barbeiro') {
    header('Location: erro.php'); // Redireciona se não for barbeiro
    exit();
}

// Definir o caminho da pasta de destino
$pastaDestino = 'uploads/';

// Verificar se a pasta 'uploads/' existe, se não, criar a pasta
if (!is_dir($pastaDestino)) {
    if (!mkdir($pastaDestino, 0777, true)) {
        die('Erro ao criar a pasta de destino para upload.');
    }
}

// Verificar se a pasta 'uploads/' tem permissão de escrita
if (!is_writable($pastaDestino)) {
    die('A pasta de destino não tem permissão de escrita.');
}

// Verificar se os dados do formulário foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Sanitização dos dados
        $nome = $_POST['nome'];
        $rua = $_POST['rua'];
        $numero = $_POST['numero'];
        $bairro = $_POST['bairro'];
        $cidade = $_POST['cidade'];
        $estado = $_POST['estado'];
        $cep = $_POST['cep'];
        $telefone = $_POST['telefone'];

        // Verificar e processar o upload da imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $imagem = $_FILES['imagem'];
            $pastaDestino = 'uploads/'; // Certifique-se que esta pasta tem permissão de escrita
            $nomeImagem = uniqid() . '-' . basename($imagem['name']);
            $caminhoImagem = $pastaDestino . $nomeImagem;

            // Mover a imagem para a pasta de uploads
            if (move_uploaded_file($imagem['tmp_name'], $caminhoImagem)) {
                // Preparar a consulta SQL com o caminho da imagem
                $sql = "INSERT INTO barbearias (nome, rua, numero, bairro, cidade, estado, cep, telefone, imagem)
                        VALUES (:nome, :rua, :numero, :bairro, :cidade, :estado, :cep, :telefone, :imagem)";

                // Preparar a declaração
                $stmt = $pdo->prepare($sql);

                // Associar os parâmetros
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':rua', $rua);
                $stmt->bindParam(':numero', $numero);
                $stmt->bindParam(':bairro', $bairro);
                $stmt->bindParam(':cidade', $cidade);
                $stmt->bindParam(':estado', $estado);
                $stmt->bindParam(':cep', $cep);
                $stmt->bindParam(':telefone', $telefone);
                $stmt->bindParam(':imagem', $caminhoImagem);

                // Executar a consulta
                $stmt->execute();

                // Armazenar o id_barbearia na sessão
                $_SESSION['barbearia'] = [
                    'id_barbearia' => $pdo->lastInsertId(), // Armazena o ID da barbearia
                    'nome' => $nome, // Armazena o nome da barbearia se necessário
                ];

                // Redirecionar após o sucesso
                header("Location: adicionar_servicos.php"); // Altere para a página que irá adicionar serviços
                exit();
            } else {
                echo "Erro ao mover o arquivo de imagem.";
            }
        } else {
            echo "Erro no upload da imagem.";
        }

    } catch (PDOException $e) {
        echo "Erro ao adicionar a barbearia: " . $e->getMessage();
    }
}

// Verificar se a pasta existe e se há permissão para escrever nela
if (is_dir($pastaDestino) && is_writable($pastaDestino)) {
    echo "A pasta existe e tem permissões de escrita.";
} else {
    echo "A pasta não existe ou não tem permissões adequadas.";
}

// Verificar o tamanho e outros atributos do arquivo de imagem
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
    echo "Arquivo recebido com sucesso.";
    echo "Tamanho do arquivo: " . $_FILES['imagem']['size'] . " bytes.";
    echo "Tipo do arquivo: " . $_FILES['imagem']['type'];
} else {
    echo "Erro no upload: " . $_FILES['imagem']['error'];
}

// Fechar a conexão
$pdo = null;
?>
