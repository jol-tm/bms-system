<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>BMS | <?=$pageTitle;?></title>
    <link rel='stylesheet' href='../assets/style.css'>
</head>

<?php
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['loggedUser']) && $pageTitle !== 'Acesso')
{
    header("Location: ../../app/acesso");
    exit();
}

if (isset($_SESSION['notification']))
{
    echo "<div class='notification'>{$_SESSION['notification']}</div>";
    unset($_SESSION['notification']);
}
?>