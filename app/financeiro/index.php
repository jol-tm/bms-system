<?php

$pageTitle = 'Financeiro';

require_once '../../src/header.php';
require_once '../../src/Proposta.php';

if (!empty($_POST['id']) && isset($_POST['excluirProposta']))
{
    $proposta = new Proposta();
    $proposta->excluirProposta();
}

?>

<table>
    <thead>
        <tr>
            <th>N° Proposta</th>
            <th>Cliente</th>
            <th>Valor (R$)</th>
            <th>N° Relatório</th>
            <th>Data Envio Relatório</th>
            <th>NF</th>
            <th>Data Pagamento</th>
            <th>Status Pagamento</th>
            <th>Observações</th>
            <th>Atualizar Status</th>
            <th>Apagar</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $connection = new DatabaseConnection();
        $data = new DataRepositoy($connection->start());

        $propostas = $data->read('propostas', 'WHERE statusProposta = "Aceita"');

        foreach ($propostas as $proposta)
        {
            $valorFormatado = str_replace('.', ',', $proposta['valor']);
            $className = $proposta['statusPagamento'] === 'Aguardando' ? 'pending' : 'received';

            if ($proposta['dataEnvioRelatorio'] !== null)
            {
                $dataEnvioRelatorio = new DateTime($proposta['dataEnvioRelatorio']);
                $dataEnvioRelatorio = $dataEnvioRelatorio->format('d/m/Y H:m');
            }
            else
            {
                $dataEnvioRelatorio = '-';
            }

            if ($proposta['dataPagamento'] !== null)
            {
                $dataPagamento = new DateTime($proposta['dataPagamento']);
                $dataPagamento = $dataPagamento->format('d/m/Y H:m');
            }
            else
            {
                $dataPagamento = '-';
            }

            $proposta['numeroNotaFiscal'] === null ? $proposta['numeroNotaFiscal'] = '-' : null;
            $proposta['numeroRelatorio'] === null ? $proposta['numeroRelatorio'] = '-' : null;
            $proposta['observacoes'] === '' ? $proposta['observacoes'] = '-' : null;

            echo "
                <tr>
                    <td>{$proposta['numeroProposta']}</td>
                    <td>{$proposta['cliente']}</td>
                    <td>$valorFormatado</td>
                    <td>{$proposta['numeroRelatorio']}</td>
                    <td>$dataEnvioRelatorio</td>
                    <td>{$proposta['numeroNotaFiscal']}</td>
                    <td>$dataPagamento</td>
                    <td class='{$className}'>{$proposta['statusPagamento']}</td>
                    <td>{$proposta['observacoes']}</td>
                    <td>
                       <form action='' method='post'>
                            <input type='hidden' name='id' value='{$proposta['id']}'>
                            <button type='submit' name='atualizarStatus'>
                                <svg class='updateProposalBtn' xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px' fill='#fff'>
                                    <path
                                        d='M222-200 80-342l56-56 85 85 170-170 56 57-225 226Zm0-320L80-662l56-56 85 85 170-170 56 57-225 226Zm298 240v-80h360v80H520Zm0-320v-80h360v80H520Z' />
                                </svg>
                            </button>
                        </form>
                    </td>
                    <td>
                       <form action='' method='post'>
                            <input type='hidden' name='id' value='{$proposta['id']}'>
                            <button type='submit' name='excluirProposta' onclick=\"return confirm('ATENÇÃO! Exclusão é irreversível! Ok para prosseguir?')\">
                                <svg class='deleteProposalBtn' xmlns='http://www.w3.org/2000/svg'
                                    height='24px' viewBox='0 -960 960 960' width='24px' fill='#fff'>
                                    <path
                                        d='M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Zm200 316 76 76q11 11 28 11t28-11q11-11 11-28t-11-28l-76-76 76-76q11-11 11-28t-11-28q-11-11-28-11t-28 11l-76 76-76-76q-11-11-28-11t-28 11q-11 11-11 28t11 28l76 76-76 76q-11 11-11 28t11 28q11 11 28 11t28-11l76-76Z' />
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                ";
        }

        ?>
    </tbody>
</table>
</body>

</html>