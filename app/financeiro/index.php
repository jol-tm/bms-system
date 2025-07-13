<?php

$pageTitle = 'Financeiro';

require_once '../../src/header.php';
require_once '../../src/Proposta.php';

if (!empty($_POST['id']) && isset($_POST['excluirProposta']))
{
    $proposta = new Proposta();
    $proposta->excluirProposta();
}

if (!empty($_POST['id']) && isset($_POST['diasAguardandoPagamento']) && isset($_POST['atualizarStatusProposta']))
{
    $proposta = new Proposta();
    $proposta->atualizarStatusProposta();
}

if (filter_var($_POST['id'], FILTER_VALIDATE_INT) && isset($_POST['mostrarAtualizarStatus']))
{
    $proposta = new Proposta();
    $propostaParaAtualizar = $proposta->verProposta($_POST['id']);

    echo "
    <div class='formWrapper'>
    <form action='' method='post' class='customForm'>
        <h2>Atualizar Status</h2>
        <input type='hidden' name='id' value='{$propostaParaAtualizar['id']}'>
        <input type='hidden' name='diasAguardandoPagamento' value='{$_POST['diasAguardandoPagamento']}'>
        <label for='numeroRelatorio'>N° do Relatório</label>
        <input type='number' name='numeroRelatorio' id='numeroRelatorio' placeholder='Ex: 123' max='99999999999' value='{$propostaParaAtualizar['numeroRelatorio']}'>
        <label for='dataEnvioRelatorio'>Data de Envio do Relatorio</label>
        <input type='datetime-local' name='dataEnvioRelatorio' id='dataEnvioRelatorio' value='{$propostaParaAtualizar['dataEnvioRelatorio']}'>
        <label for='numeroNotaFiscal'>NF</label>
        <input type='number' name='numeroNotaFiscal' id='numeroNotaFiscal' placeholder='Ex: 123456789' max='999999999' value='{$propostaParaAtualizar['numeroNotaFiscal']}'>
        <label for='dataPagamento'>Data do Pagamento</label>
        <input type='datetime-local' name='dataPagamento' id='dataPagamento' value='{$propostaParaAtualizar['dataPagamento']}'>
        <label for='formaPagamento'>Forma de Pagamento</label>
        <input type='text' name='formaPagamento' id='formaPagamento' placeholder='Ex: Parcelado 2x' maxlength='255' value='{$propostaParaAtualizar['formaPagamento']}'>
        <button id='updateStatusBtn' type='submit' name='atualizarStatusProposta'>Atualizar</button>
        <a id='cancelUpdateStatusBtn' href=''>Cancelar</a>
    </form>
    </div>
    ";
}

?>

<div class="tableResponsive">
    <table>
        <thead>
            <tr>
                <th>N° Proposta</th>
                <th>Cliente</th>
                <th>Valor (R$)</th>
                <th>N° Relatório</th>
                <th>Data de Envio do Relatório</th>
                <th>NF</th>
                <th>Data do Pagamento</th>
                <th>Forma de Pagamento</th>
                <th>Status do Pagamento</th>
                <th>Dias Aguardando Pagamento</th>
                <th>Observações</th>
                <th>Atualizar Status</th>
                <th>Apagar</th>
            </tr>
        </thead>
        <tbody>

            <?php

            $proposta = new Proposta();
            $propostas = $proposta->verPropostasEmFaseFinanceira();

            $hoje = new DateTime();

            foreach ($propostas as $proposta)
            {
                $dataEnvioProposta = new DateTime($proposta['dataEnvioProposta']);
                $dataAceiteProposta = $dataEnvioProposta->modify("+{$proposta['diasEmAnalise']} days");
                $diasAguardandoPagamento = $proposta['statusPagamento'] === 'Aguardando' ? $hoje->diff($dataAceiteProposta)->days : $proposta['diasAguardandoPagamento'];
                
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
                $proposta['formaPagamento'] === null ? $proposta['formaPagamento'] = '-' : null;
                $proposta['observacoes'] === '' ? $proposta['observacoes'] = '-' : null;
                $valorFormatado = str_replace('.', ',', $proposta['valor']);
                $nomeClasse = $proposta['statusPagamento'] === 'Aguardando' ? 'pending' : 'received';
                
                echo "
                <tr>
                    <td>{$proposta['numeroProposta']}</td>
                    <td>{$proposta['cliente']}</td>
                    <td>$valorFormatado</td>
                    <td>{$proposta['numeroRelatorio']}</td>
                    <td>$dataEnvioRelatorio</td>
                    <td>{$proposta['numeroNotaFiscal']}</td>
                    <td>$dataPagamento</td>
                    <td>{$proposta['formaPagamento']}</td>
                    <td class='{$nomeClasse}'>{$proposta['statusPagamento']}</td>
                    <td>$diasAguardandoPagamento</td>
                    <td>{$proposta['observacoes']}</td>
                    <td>
                       <form action='' method='post'>
                            <input type='hidden' name='id' value='{$proposta['id']}'>
                            <input type='hidden' name='diasAguardandoPagamento' value='$diasAguardandoPagamento'>
                            <button type='submit' name='mostrarAtualizarStatus'>
                                <svg class='updateProposalBtn' xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960' width='24px' fill='#fff'>
                                    <path
                                        d='M240-360h280l80-80H240v80Zm0-160h240v-80H240v80Zm-80-160v400h280l-80 80H80v-560h800v120h-80v-40H160Zm756 212q5 5 5 11t-5 11l-36 36-70-70 36-36q5-5 11-5t11 5l48 48ZM520-120v-70l266-266 70 70-266 266h-70ZM160-680v400-400Z' />
                                </svg>
                            </button>
                        </form>
                    </td>
                    <td>
                       <form action='' method='post'>
                            <input type='hidden' name='id' value='{$proposta['id']}'>
                            <button type='submit' name='excluirProposta' onclick=\"return confirm('ATENÇÃO! Exclusão é IRREVERSÍVEL! Ok para prosseguir?')\">
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
</div>

</body>

</html>