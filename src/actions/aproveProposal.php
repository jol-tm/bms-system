<?php
session_start();

if (!isset($_SESSION['loggedUser']))
{
    header('Location: ../../app/acesso');
    exit();
}

if (!isset($_GET['id']))
{
    $_SESSION['notification'] = 'Erro ao aceitar proposta: Faltando ID da proposta.';
    header('Location: ../../app/comercial');
    exit();
}

require_once '../../src/DatabaseConnection.php';
require_once '../../src/DataRepository.php';

$conn = new DatabaseConnection();
$data = new DataRepositoy($conn->start());

if ($data->update('propostas', ['statusProposta' => 'Aceita'], ['id' => $_GET['id']]))
{
    $_SESSION['notification'] = 'Proposta aceita com sucesso. Movida para Financeiro.';
    header('Location: ../../app/comercial');
}