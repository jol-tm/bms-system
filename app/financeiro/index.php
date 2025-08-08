<?php

$pageTitle = 'Financeiro';

require_once '../../src/header.php';
require_once '../../src/Proposta.php';

$proposta = new Proposta();
$propostas = [];

if (!empty($_GET['pesquisa']))
{
	$propostas = $proposta->pesquisarProposta();
}
else
{
	$propostas = $proposta->verPropostasEmFaseFinanceira();
}

if (isset($_POST['excluirProposta']) && !empty($_POST['id']))
{
	$proposta->excluirProposta();
}

if (isset($_POST['atualizarStatusProposta']) && !empty($_POST['id']) && isset($_POST['dataAceiteProposta']) && isset($_POST['diasAguardandoPagamento']))
{
	$proposta->atualizarStatusProposta();
}

if (isset($_POST['mostrarAtualizarStatus']) && filter_var($_POST['id'], FILTER_VALIDATE_INT))
{
	$propostaParaAtualizar = $proposta->verProposta($_POST['id']);

	echo "
	<div class='formWrapper'>
	<form action='' method='post' class='customForm'>
		<h2>Atualizando Proposta: {$_POST['numeroProposta']}</h2>
		<input type='hidden' name='id' value='{$propostaParaAtualizar['id']}'>
		<input type='hidden' name='diasAguardandoPagamento' value='{$_POST['diasAguardandoPagamento']}'>
		<input type='hidden' name='dataAceiteProposta' value='{$_POST['dataAceiteProposta']}'>
		<label for='numeroRelatorio'>N° do Relatório</label>
		<input type='number' name='numeroRelatorio' id='numeroRelatorio' placeholder='Ex: 123' max='99999999999' value='{$propostaParaAtualizar['numeroRelatorio']}'>
		<label for='dataEnvioRelatorio'>Data de Envio do Relatorio</label>
		<input type='datetime-local' name='dataEnvioRelatorio' id='dataEnvioRelatorio' value='{$propostaParaAtualizar['dataEnvioRelatorio']}'>
		<label for='valor'>Valor da Proposta</label>
		<input type='text' name='valor' id='valor' placeholder='Ex: 999,99' maxlength='10' value='{$propostaParaAtualizar['valor']}'>
		<label for='numeroNotaFiscal'>NF</label>
		<input type='number' name='numeroNotaFiscal' id='numeroNotaFiscal' placeholder='Ex: 123456789' max='999999999' value='{$propostaParaAtualizar['numeroNotaFiscal']}'>
		<label for='dataPagamento'>Data do Pagamento</label>
		<input type='datetime-local' name='dataPagamento' id='dataPagamento' value='{$propostaParaAtualizar['dataPagamento']}'>
		<label for='formaPagamento'>Forma de Pagamento</label>
		<input type='text' name='formaPagamento' id='formaPagamento' placeholder='Ex: Parcelado 2x' maxlength='255' value='{$propostaParaAtualizar['formaPagamento']}'>
		<label for='dataUltimaCobranca'>Data Última Cobrança</label>
		<input type='datetime-local' name='dataUltimaCobranca' id='dataUltimaCobranca' value='{$propostaParaAtualizar['dataUltimaCobranca']}'>
		<label for='observacoes'>Observações</label>
		<input type='text' name='observacoes' id='observacoes' placeholder='Ex: Desenvolvimento...' maxlength='255' value='{$propostaParaAtualizar['observacoes']}'>
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
				<th>Dias em Análise</th>
				<th>Status Proposta</th>
				<th>Data Aceite Proposta</th>
				<th>N° Relatório</th>
				<th>Data Envio Relatório</th>
				<th>NF</th>
				<th>Data Pagamento</th>
				<th>Forma Pagamento</th>
				<th>Status Pagamento</th>
				<th>Dias Aguardando Pagamento</th>
				<th>Data Última Cobrança</th>
				<th>Dias desde Última Cobrança</th>
				<th>Observações</th>
				<th>Atualizar Status</th>
				<th>Apagar</th>
			</tr>
		</thead>
		<tbody>

			<?php

			$hoje = new DateTime();

			foreach ($propostas as $proposta)
			{
				$dataAceiteProposta = '-';
				$dataUltimaCobranca = '-';
				$diasUltimaCobranca = '-';
				$dataEnvioRelatorio = '-';
				$dataPagamento = '-';
				
				empty($proposta['numeroNotaFiscal']) ? $proposta['numeroNotaFiscal'] = '-' : null;
				empty($proposta['formaPagamento']) ? $proposta['formaPagamento'] = '-' : null;
				empty($proposta['numeroRelatorio']) ? $proposta['numeroRelatorio'] = '-' : null;
				empty($proposta['observacoes']) ? $proposta['observacoes'] = '-' : null;
				isset($proposta['diasEmAnalise']) ? null : $proposta['diasEmAnalise'] = '-';
				
				$valorFormatado = str_replace('.', ',', $proposta['valor']);
				
				$statusPagamento = $proposta['statusPagamento'] === 'Aguardando' ? 'pending' : 'received';
				
				if ($proposta['statusProposta'] === 'Recusada')
				{
					$statusProposta = 'refused';
				}
				elseif ($proposta['statusProposta'] === 'Aceita')
				{
					$statusProposta = 'accepted';
				}
				else
				{
					$statusProposta = 'pending';
				}
				
				if ($proposta['dataAceiteProposta'] !== null)
				{
					$dataAceiteProposta = new DateTime($proposta['dataAceiteProposta']);
					$diasAguardandoPagamento = $proposta['statusPagamento'] === 'Aguardando' ? $hoje->diff($dataAceiteProposta)->days : $proposta['diasAguardandoPagamento'];
					$dataAceiteProposta = $dataAceiteProposta->format('d/m/Y H:i');
				}
				
				if ($proposta['dataUltimaCobranca'] !== null)
				{
					$dataUltimaCobranca = new DateTime($proposta['dataUltimaCobranca']);
					
					if ($proposta['statusPagamento'] === 'Aguardando')
					{
						$diasUltimaCobranca = $hoje->diff($dataUltimaCobranca)->days;
					}
					
					$dataUltimaCobranca = $dataUltimaCobranca->format('d/m/Y H:i');
				}
				
				if ($proposta['dataEnvioRelatorio'] !== null)
				{
					$dataEnvioRelatorio = (new DateTime($proposta['dataEnvioRelatorio']))->format('d/m/Y H:i');
				}

				if ($proposta['dataPagamento'] !== null)
				{
					$dataPagamento = (new DateTime($proposta['dataPagamento']))->format('d/m/Y H:i');
				}

				echo "
				<tr>
					<td>{$proposta['numeroProposta']}</td>
					<td>{$proposta['cliente']}</td>
					<td>$valorFormatado</td>
					<td>{$proposta['diasEmAnalise']}</td>
					<td class='{$statusProposta}'>{$proposta['statusProposta']}</td>
					<td>$dataAceiteProposta</td>
					<td>{$proposta['numeroRelatorio']}</td>
					<td>$dataEnvioRelatorio</td>
					<td>{$proposta['numeroNotaFiscal']}</td>
					<td>$dataPagamento</td>
					<td>{$proposta['formaPagamento']}</td>
					<td class='$statusPagamento'>{$proposta['statusPagamento']}</td>
					<td>$diasAguardandoPagamento</td>
					<td>$dataUltimaCobranca</td>
					<td>$diasUltimaCobranca</td>
					<td>{$proposta['observacoes']}</td>
					<td>
					   <form action='' method='post'>
							<input type='hidden' name='id' value='{$proposta['id']}'>
							<input type='hidden' name='numeroProposta' value='{$proposta['numeroProposta']}'>
							<input type='hidden' name='diasAguardandoPagamento' value='{$diasAguardandoPagamento}'>
							<input type='hidden' name='dataAceiteProposta' value='$dataAceiteProposta'>
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
							<button type='submit' name='excluirProposta' onclick=\"return prompt('ATENÇÃO! Exclusão é irreversível! Caso tenha certeza, digite EXCLUIR abaixo.') === 'EXCLUIR'\">
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
