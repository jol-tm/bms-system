<?php

session_start();

if (!isset($_SESSION['loggedUser']))
{
	header("Location: acesso");
}
else
{
	header("Location: comercial");
}
