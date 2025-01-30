<!DOCTYPE html>
<?php
    require_once "AnalisadorLexico.php";

    $titulo = "Compilador";

    $codigo = isset($_POST["codigo"]) ? $_POST["codigo"] : "";

    $analisadorLexico = new AnalisadorLexico();
    $vetorTokensErros = $analisadorLexico->executar($codigo);

    $tokens = $vetorTokensErros[0];
    $erros = $vetorTokensErros[1];
?>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1><?php echo $titulo; ?></h1>
    <br>
    <form method="post">
        <textarea name="codigo"></textarea>
        <br>
        <button type="submit">Analisar</button>
    </form>
    <br>
    <?php
        if(isset($_POST["codigo"]) && $codigo != ""){
            echo "<pre><p>".$codigo."</p></pre>";
    ?>
    <h3>Tokens:</h3>
    <pre class="box"><?php
        foreach($tokens as $token)
            echo "< ".$token->getNome().", ".$token->getLexema()." > [Linha ".$token->getLinha().", Coluna ".$token->getColuna()."] <br>";
    ?></pre>
    <?php
        if(!empty($erros)){
    ?>
    <h3>Erros encontrados:</h3>
    <pre class="box"><?php
        foreach($erros as $erro)
            echo "Erro léxico: Token desconhecido '".$erro->getLexema()."' na linha ".$erro->getLinha().", coluna ".$erro->getColuna()."<br>";
    ?></pre>
    <?php
            }
        }
    ?>
</body>
</html>