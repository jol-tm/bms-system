<?php

require_once 'DatabaseConnection.php';
require_once 'DataRepository.php';

class Proposta
{
    public function verProposta(int $id): array|false
    {
        $connectionection = new DatabaseConnection();
        $data = new DataRepositoy($connectionection->start());
        return $data->read('propostas', "WHERE id = $id")[0];
    }

    public function verPropostasEmFaseFinanceira(): array
    {
        $connectionection = new DatabaseConnection();
        $data = new DataRepositoy($connectionection->start());
        return $data->read('propostas', 'WHERE statusProposta = "Aceita" ORDER BY dataEnvioProposta DESC');
    }

    public function verPropostasEmFaseComercial(): array
    {
        $connectionection = new DatabaseConnection();
        $data = new DataRepositoy($connectionection->start());
        return $data->read('propostas', 'WHERE statusProposta = "Em análise" OR statusProposta = "Recusada" ORDER BY dataEnvioProposta DESC');
    }

    public function cadastrarProposta(): bool
    {
        $connection = new DatabaseConnection();
        $data = new DataRepositoy($connection->start());

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

    public function atualizarStatusProposta(): bool
    {
        $connection = new DatabaseConnection();
        $data = new DataRepositoy($connection->start());

        $updated = $data->update(
            'propostas',
            [
                'numeroRelatorio' => empty($_POST['numeroRelatorio']) ? null : $_POST['numeroRelatorio'],
                'dataEnvioRelatorio' => empty($_POST['dataEnvioRelatorio']) ? null : $_POST['dataEnvioRelatorio'],
                'numeroNotaFiscal' => empty($_POST['numeroNotaFiscal']) ? null : $_POST['numeroNotaFiscal'],
                'dataPagamento' => empty($_POST['dataPagamento']) ? null : $_POST['dataPagamento'],
                'statusPagamento' => empty($_POST['dataPagamento']) ? 'Aguardando' : 'Recebido',
                'diasAguardandoPagamento' => $_POST['diasAguardandoPagamento'],
            ],
            [
                'id' => $_POST['id']
            ]
        );

        if ($updated)
        {
            $_SESSION['notification'] = 'Status da Proposta atualizado com sucesso.';
            header('Location: ./');
            return true;
        }

        $_SESSION['notification'] = 'Erro ao atualizar Status da Proposta. Nenhuma modificada.';
        header('Location: ./');
        return false;
    }

    public function aceitarProposta(): bool
    {
        $connection = new DatabaseConnection();
        $data = new DataRepositoy($connection->start());
        $affectedRows = $data->update('propostas', ['statusProposta' => 'Aceita', 'diasEmAnalise' => $_POST['diasEmAnalise']], ['id' => $_POST['id']]);

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
        $connection = new DatabaseConnection();
        $data = new DataRepositoy($connection->start());
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
        $connection = new DatabaseConnection();
        $data = new DataRepositoy($connection->start());
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