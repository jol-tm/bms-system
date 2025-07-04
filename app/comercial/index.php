<?php
$pageTitle = 'Propostas | Comercial';

require_once '../assets/header.php';
require_once '../../src/DatabaseConnection.php';
require_once '../../src/DataRepository.php';
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
                <th>Data de Envio da Proposta</th>
                <th>Cliente</th>
                <th>Valor (R$)</th>
                <th>Status</th>
                <th>Dias em Análise</th>
                <th>Observações</th>
                <th>Aprovar</th>
                <th>Recusar</th>
                <th>Apagar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $conn = new DatabaseConnection();
            $data = new DataRepositoy($conn->start());

            $propostas = $data->read('propostas', 'WHERE statusProposta = "Em análise" OR statusProposta = "Recusada"');
            $hoje = new DateTime();

            foreach ($propostas as $proposta)
            {
                $dataEnvioProposta = new DateTime($proposta['dataEnvioProposta']);
                $diasEmAnalise = $proposta['statusProposta'] === 'Em análise' ? $hoje->diff($dataEnvioProposta)->d : $proposta['diasEmAnalise'];
                $valorFormatado = str_replace('.', ',', $proposta['valor']);
                $className = $proposta['statusProposta'] === 'Recusada' ? 'refused' : 'pending';

                echo "
                <tr>
                    <td>{$proposta['numeroProposta']}</td>
                    <td>{$dataEnvioProposta->format('d/m/Y H:m')}</td>
                    <td>{$proposta['cliente']}</td>
                    <td>$valorFormatado</td>
                    <td class='{$className}'>{$proposta['statusProposta']}</td>
                    <td>$diasEmAnalise</td>
                    <td>{$proposta['observacoes']}</td>
                    <td>
                        <a href='../../src/actions/aproveProposal.php?id={$proposta['id']}&diasEmAnalise={$diasEmAnalise}'>
                            <svg class='aproveProposalBtn' aria-label='aprovar' xmlns='http://www.w3.org/2000/svg'
                                height='24px' viewBox='0 -960 960 960' width='24px' fill='#fff'>
                                <path
                                    d='m382-354 339-339q12-12 28-12t28 12q12 12 12 28.5T777-636L410-268q-12 12-28 12t-28-12L182-440q-12-12-11.5-28.5T183-497q12-12 28.5-12t28.5 12l142 143Z' />
                            </svg>
                        </a>
                    </td>
                    <td>
                        <a href='../../src/actions/refuseProposal.php?id={$proposta['id']}&diasEmAnalise={$diasEmAnalise}'>
                            <svg class='denyProposalBtn' aria-label='rejeitar' xmlns='http://www.w3.org/2000/svg'
                                height='24px' viewBox='0 -960 960 960' width='24px' fill='#fff'>
                                <path
                                    d='M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z' />
                            </svg>
                        </a>
                    </td>
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