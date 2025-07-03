<?php
$pageTitle = 'Comercial';

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
    <h2>Propostas</h2>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>N°</th>
                <th>Envio</th>
                <th>Cliente</th>
                <th>Valor (R$)</th>
                <!-- <th>N° Relatório</th> -->
                <!-- <th>Envio do Relatório</th> -->
                <!-- <th>NF</th> -->
                <!-- <th>Pagamento</th> -->
                <!-- <th>Status do Pagamento</th> -->
                <th>Status</th>
                <th>Observações</th>
                <th>Aprovar</th>
                <th>Rejeitar</th>
                <th>Apagar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>1234</td>
                <td>12/5/2025<br>08:12</td>
                <td>Damme</td>
                <td>1000,00</td>
                <!-- <td>4564</td>
                    <td>2/6/2025 14:11</td>
                    <td>5496498</td>
                    <td>Pendente</td>
                    <td>Pendente</td> -->
                <td>Pendente</td>
                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                <td>
                    <a href="../../src/actions/aproveProposal.php">
                        <svg class="aproveProposalBtn" aria-label="aprovar" xmlns="http://www.w3.org/2000/svg"
                            height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff">
                            <path
                                d="m382-354 339-339q12-12 28-12t28 12q12 12 12 28.5T777-636L410-268q-12 12-28 12t-28-12L182-440q-12-12-11.5-28.5T183-497q12-12 28.5-12t28.5 12l142 143Z" />
                        </svg>
                    </a>
                </td>
                <td>
                    <a href="../../src/actions/denyProposal.php">
                        <svg class="denyProposalBtn" aria-label="rejeitar" xmlns="http://www.w3.org/2000/svg"
                            height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff">
                            <path
                                d="M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z" />
                        </svg>
                    </a>
                </td>
                <td>
                    <a href="">
                        <svg class="deleteProposalBtn" aria-label="apagar" xmlns="http://www.w3.org/2000/svg"
                            height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff">
                            <path
                                d="M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520Zm-400 0v520-520Zm200 316 76 76q11 11 28 11t28-11q11-11 11-28t-11-28l-76-76 76-76q11-11 11-28t-11-28q-11-11-28-11t-28 11l-76 76-76-76q-11-11-28-11t-28 11q-11 11-11 28t11 28l76 76-76 76q-11 11-11 28t11 28q11 11 28 11t28-11l76-76Z" />
                        </svg>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>