<?php

// ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['loggedUser']) && ($pageTitle !== 'Acesso'))
{
    http_response_code(404);
    // header("Location: ../../app/acesso");
    exit();
}

if (isset($_SESSION['loggedUser']) && ($pageTitle === 'Acesso'))
{
    header('Location: ../comercial');
    exit();
}

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>BMS | <?= $pageTitle; ?></title>
    <link rel='stylesheet' href='../assets/style.css'>
    <script defer src="../assets/script.js"></script>
</head>

<body>
    <?php

    if ($pageTitle !== 'Acesso')
    {
        $comercialId = $pageTitle === 'Comercial' ? 'currentPage' : null;
        $financeiroId = $pageTitle === 'Financeiro' ? 'currentPage' : null;

        echo "
        <nav>
            <a id='$comercialId' href='../comercial/ '>Comercial</a>
            <a id='$financeiroId' href='../financeiro/'>Financeiro</a>
        </nav>
        ";

        echo "
        <h5 id='loggedUser'>
            Olá, {$_SESSION['loggedUser']}
            <br>
            <a href='./?desconectar'>Desconectar</a>
        </h5>
        ";
    }

    if (isset($_GET['desconectar']))
    {
        require_once '../../src/DatabaseConnection.php';
        require_once '../../src/Authenticator.php';

        $connection = new DatabaseConnection();
        $authenticator = new Authenticator($connection->start());
        $disconnectSuccess = $authenticator->disconnect();

        if ($disconnectSuccess)
        {
            header('Location: ../acesso?desconectado');
            exit();
        }
    }

    if (isset($_GET['desconectado']))
    {
        echo "<div class='notification'>Desconectado com sucesso.</div>";
    }

    if (isset($_SESSION['notification']))
    {
        echo "<div class='notification'>{$_SESSION['notification']}</div>";
        unset($_SESSION['notification']);
    }

    ?>