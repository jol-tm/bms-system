<?php

session_start();

if (!isset($_SESSION['loggedUser']))
{
    header("Location: app/acesso");
}
else
{
    header("Location: app/comercial");
}