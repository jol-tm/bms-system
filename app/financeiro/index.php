<?php
$pageTitle = 'Propostas | Financeiro';

require_once '../assets/header.php';
require_once '../../src/DatabaseConnection.php';
require_once '../../src/DataRepository.php';

$conn = new DatabaseConnection();
$data = new DataRepositoy($conn->start());
?>

<body>
    <nav>
        <a href="../comercial/" class="navItem">Comercial</a>
        <a href="../financeiro/" class="navItem">Financeiro</a>
    </nav>
    <h2><?= $pageTitle; ?></h2>
    <table>
        <thead>
            <tr>
                <th>N° Proposta</th>
                <!-- <th>Envio</th> -->
                <th>Cliente</th>
                <th>Valor (R$)</th>
                <th>N° Relatório</th>
                <th>Data Envio Relatório</th>
                <th>NF</th>
                <th>Data Pagamento</th>
                <th>Status Pagamento</th>
                <!-- <th>Status</th> -->
                <th>Observações</th>
                <!-- <th>Aprovar</th> -->
                <!-- <th>Rejeitar</th> -->
                <th>Apagar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $conn = new DatabaseConnection();
            $data = new DataRepositoy($conn->start());

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
                        <a href='../../src/actions/deleteProposal.php?id={$proposta['id']}''>
                            <svg class='deleteProposalBtn' aria-label='apagar' xmlns='http://www.w3.org/2000/svg'
                                height='24px' viewBox='0 -960 960 960' width='24px' fill='#fff'>
                                <path
                                    d='M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Zm200 316 76 76q11 11 28 11t28-11q11-11 11-28t-11-28l-76-76 76-76q11-11 11-28t-11-28q-11-11-28-11t-28 11l-76 76-76-76q-11-11-28-11t-28 11q-11 11-11 28t11 28l76 76-76 76q-11 11-11 28t11 28q11 11 28 11t28-11l76-76Z' />
                            </svg>
                        </a>
                    </td>
                </tr>
                ";
            }
            ?>
        </tbody>
    </table>
</body>

</html>