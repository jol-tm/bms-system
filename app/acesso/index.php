<?php

$pageTitle = 'Acesso';

require_once '../../src/header.php';
require_once '../../src/DatabaseConnection.php';
require_once '../../src/DataRepository.php';
require_once '../../src/Authenticator.php';

if (!empty($_POST['email']) && !empty($_POST['senha']) && isset($_POST['acessar']))
{
    $connection = new DatabaseConnection();
    $authenticator = new Authenticator($connection->start());
    $authenticationSuccess = $authenticator->authenticate('usuarios', ['email' => $_POST['email']], ['senha' => $_POST['senha']]);

    if ($authenticationSuccess)
    {
        header('Location: ../../app/comercial');
    }
    else
    {
        $_SESSION['notification'] = 'Erro na autenticação. Verifique as credenciais.';
        header('Location: ../../app/acesso');
    }
}

?>

<body id='bodyLogin'>
    <form id='formLogin' action='' method='post'>
        <h1>Acesso</h1>
        <input type='email' name='email' placeholder='Email' required>
        <input type='password' name='senha' placeholder='Senha' required>
        <button id='loginBtn' type='submit' name='acessar'>Acessar</button>
    </form>
</body>

</html>