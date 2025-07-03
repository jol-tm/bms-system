<?php
session_start();

if (!isset($_SESSION['loggedUser']))
{
    header("Location: ../../app/acesso");
    exit();
}

require_once '../../src/DatabaseConnection.php';
require_once '../../src/DataRepository.php';
require_once '../../src/Authenticator.php';

$conn = new DatabaseConnection();
$data = new DataRepositoy($conn->start());
$auth = new Authenticator($conn->start());

if ($auth->authenticate('usuarios', ['email' => $_POST['email']], ['senha' => $_POST['senha']]))
{
    session_regenerate_id(true);
    $_SESSION['loggedUser'] = $_POST['email'];
    header('Location: ../../app/comercial');
}
else
{
    $_SESSION['notification'] = 'Erro na autenticação. Verifique as credenciais.';
    header('Location: ../../app/acesso');
}