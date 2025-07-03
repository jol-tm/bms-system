<?php
$pageTitle = 'Acesso';
require_once '../assets/header.php'; 
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