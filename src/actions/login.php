<?php
$pageTitle = 'Acesso';

require_once '../../app/assets/header.php';
require_once '../../src/DatabaseConnection.php';
require_once '../../src/DataRepository.php';
require_once '../../src/Authenticator.php';

$conn = new DatabaseConnection();
$data = new DataRepositoy($conn->start());
$auth = new Authenticator($conn->start());
$userMatch = $auth->authenticate('usuarios', ['email' => $_POST['email']], ['senha' => $_POST['senha']]);

if ($userMatch)
{
    session_regenerate_id(true);
    $_SESSION['loggedUser'] = $_POST['email'];
    $_SESSION['notification'] = 'Autenticado como ' . $_SESSION['loggedUser'];
    header('Location: ../../app/comercial');
}
else
{
    $_SESSION['notification'] = 'Erro na autenticação. Verifique as credenciais.';
    header('Location: ../../app/acesso');
}