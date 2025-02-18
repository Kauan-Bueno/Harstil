<?php
if (isset($_GET['q'])) {
    $query = urlencode($_GET['q']);
    $apiKey = '5363a13dc4d6044996467466cdab6f37bbc967ca0506fdbeb4659801475d9c4b'; // Substitua pela sua chave de API
    $url = "https://serpapi.com/search.json?engine=google&q=$query&api_key=$apiKey";

    $response = file_get_contents($url);
    $results = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Busca de Barbearias</title>
    <link rel="stylesheet" href="plugins/bootstrap/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Pesquisa de Barbearias</h1>
    <form action="search.php" method="get">
        <input type="text" name="q" placeholder="Procurar Barbearias" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if (isset($results)): ?>
        <h2>Resultados da Pesquisa</h2>
        <ul>
            <?php foreach ($results['organic_results'] as $result): ?>
                <li>
                    <a href="<?= $result['link'] ?>" target="_blank"><?= $result['title'] ?></a>
                    <p><?= $result['snippet'] ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<script src="plugins/jQuery/jquery.min.js"></script>
<script src="plugins/bootstrap/bootstrap.min.js"></script>
</body>
</html>
