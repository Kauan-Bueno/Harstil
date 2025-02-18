<?php
// Inclui o arquivo de conexão com o banco de dados
include 'config.php';

try {
    // Consulta SQL para buscar as barbearias cadastradas
    $sql = "SELECT id, nome, imagem FROM barbearias";
    $stmt = $pdo->query($sql);

    if ($stmt->rowCount() > 0) {
        // Exibe cada barbearia como um card
        while ($barbearia = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Variáveis com dados da barbearia
            $nome = htmlspecialchars($barbearia['nome']);
            $imagem = htmlspecialchars($barbearia['imagem']);  // Nome da imagem no banco
            $id = $barbearia['id'];
            
            // HTML dinâmico com os dados do banco
            echo "
            <section class='barbearias-section'>
                <div class='Barbearia$id'>
                    $nome
                    <a href='servicos.php?barbearia_id=$id'>
                        <img src='images/$imagem' alt='$nome' class='imagemBarbearia$id'>
                    </a>
                </div>
            </section>
            ";
        }
    } else {
        echo "Nenhuma barbearia cadastrada.";
    }
} catch (PDOException $e) {
    echo "Erro ao buscar barbearias: " . $e->getMessage();
}
?>
