<?php

require_once 'DatabaseConnection.php';
require_once 'DataRepository.php';

class Proposta
{
    public function verProposta(int $id): array|false
    {
        $connection = new DatabaseConnection();
        $data = new DataRepositoy($connection->start());
        return $data->read('propostas', "WHERE id = $id")[0];
    }

    public function verPropostasEmFaseFinanceira(): array
    {
        $connection = new DatabaseConnection();
        $data = new DataRepositoy($connection->start());
        return $data->read('propostas', 'WHERE statusProposta = "Aceita"');
    }

    public function verPropostasEmFaseComercial(): array
    {
        $connection = new DatabaseConnection();
        $data = new DataRepositoy($connection->start());
        return  $data->read('propostas', 'WHERE statusProposta = "Em análise" OR statusProposta = "Recusada" ORDER BY dataEnvioProposta DESC');
    }

    public function cadastrarProposta(): bool
    {
        $conn = new DatabaseConnection();
        $data = new DataRepositoy($conn->start());

        $created = $data->create('propostas', [
            'numeroProposta' => $_POST['numeroProposta'],
            'dataEnvioProposta' => $_POST['dataEnvioProposta'],
            'valor' => str_replace(',', '.', $_POST['valor']),
            'cliente' => $_POST['cliente'],
            'observacoes' => $_POST['observacoes'],
        ]);

        if ($created)
        {
            $_SESSION['notification'] = 'Proposta criada com sucesso.';
            header('Location: ./');
            return true;
        }

        $_SESSION['notification'] = 'Erro ao criar proposta.';
        header('Location: ./');
        return false;
    }

    public function aceitarProposta(): bool
    {
        $conn = new DatabaseConnection();
        $data = new DataRepositoy($conn->start());
        $affectedRows = $data->update('propostas', [
            'statusProposta' => 'Aceita',
            'diasEmAnalise' => $_POST['diasEmAnalise']
        ], [
            'id' => $_POST['id']
        ]);

        if ($affectedRows > 0)
        {
            $_SESSION['notification'] = 'Proposta aceita com sucesso. Movida para "Financeiro".';
            header('Location: ./');
            return true;
        }

        $_SESSION['notification'] = 'Erro ao aceitar proposta. Nenhuma modificada.';
        header('Location: ./');
        return false;
    }

    public function recusarProposta(): bool
    {
        $conn = new DatabaseConnection();
        $data = new DataRepositoy($conn->start());
        $affectedRows = $data->update('propostas', ['statusProposta' => 'Recusada', 'diasEmAnalise' => $_POST['diasEmAnalise']], ['id' => $_POST['id']]);

        if ($affectedRows > 0)
        {
            $_SESSION['notification'] = 'Proposta recusada com sucesso.';
            header('Location: ./');
            return true;
        }

        $_SESSION['notification'] = 'Erro ao recusar proposta. Nenhuma modificada.';
        header('Location: ./');
        return false;

    }
    public function excluirProposta(): bool
    {
        $conn = new DatabaseConnection();
        $data = new DataRepositoy($conn->start());
        $affectedRows = $data->delete('propostas', ['id' => $_POST['id']]);

        if ($affectedRows > 0)
        {
            $_SESSION['notification'] = 'Proposta excluída com sucesso.';
            header('Location: ./');
            return true;
        }

        $_SESSION['notification'] = 'Erro ao excluir proposta. Nenhuma modificada.';
        header('Location: ./');
        return false;
    }
}