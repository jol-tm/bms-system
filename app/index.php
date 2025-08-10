<?php
ini_set('session.cookie_lifetime', 300);
ini_set('session.gc_maxlifetime', 300);

session_start();

if (!isset($_SESSION['loggedUser']))
{
	header("Location: acesso");
}
else
{
	header("Location: comercial");
}
