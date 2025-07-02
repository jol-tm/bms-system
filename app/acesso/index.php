<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>BMS | Acesso</title>
    <link rel='stylesheet' href='../../assets/style.css'>
</head>

<?php
session_start();
require_once '../../assets/header.php';
// require_once '../../src/DatabaseConnection.php';
// require_once '../../src/DataRepository.php';
// require_once '../../src/Authenticator.php';

// senha joao = senha123

if (isset($_SESSION['notification']))
{
    echo "<div class='notification'>{$_SESSION['notification']}</div>";
    unset($_SESSION['notification']);
}
?>

<body id='bodyLogin'>
    <form id='formLogin' action='../../src/actions/login.php' method='post'>
        <h1>Acesso</h1>
        <input type='email' name='email' placeholder='Email'>
        <input type='password' name='senha' placeholder='Senha'>
        <button id='loginBtn' type='submit' name='entrar'>Entrar</button>
    </form>
</body>

</html>