<?php

$pageTitle = 'Financeiro';

require_once '../../src/header.php';
require_once '../../src/Proposta.php';

$proposta = new Proposta();
$propostas = [];
$pesquisa = '';

if (!empty($_POST['pesquisa']))
{
	$propostas = $proposta->pesquisarProposta();
	
	if ($pesquisa = DateTime::createFromFormat('Y-m', $_POST['pesquisa']))
	{
		$pesquisa = $pesquisa->format('m/Y');
	}
	else
	{
		$pesquisa = $_POST['pesquisa'];
	}
}
else
{
	$propostas = $proposta->verPropostasEmFaseFinanceira();
}

if (isset($_POST['excluirProposta']) && !empty($_POST['id']))
{
	$proposta->excluirProposta();
}

if (isset($_POST['atualizarStatusProposta']) && !empty($_POST['id']) && isset($_POST['dataAceiteProposta']))
{
	$proposta->atualizarStatusProposta();
}

if (isset($_POST['mostrarAtualizarStatus']) && filter_var($_POST['id'], FILTER_VALIDATE_INT))
{
	$propostaParaAtualizar = $proposta->verProposta($_POST['id']);
	$propostaParaAtualizar['statusProposta'] === 'Em análise' ? $analise = 'checked' : $aceita = 'checked';

	echo "
	<div class='formWrapper'>
	<form action='' method='post' class='customForm'>
		<h2>Atualizando Proposta: {$_POST['numeroProposta']}</h2>
		<input type='hidden' name='id' value='{$propostaParaAtualizar['id']}'>
		<input type='hidden' name='dataAceiteProposta' value='{$_POST['dataAceiteProposta']}'>
		<h3>Status Proposta</h3>
		<div>
			<input id='aceita' type='radio' name='statusProposta' value='Aceita' $aceita>
			<label for='aceita'>Aceita</label>
		</div>
		<div>
			<input id='emAnalise' type='radio' name='statusProposta' value='Em análise' $analise>
			<label for='emAnalise'>Em análise</label>
		</div>
		<label for='numeroRelatorio'>N° do Relatório</label>
		<input type='number' name='numeroRelatorio' id='numeroRelatorio' placeholder='Ex: 123' max='99999999999' value='{$propostaParaAtualizar['numeroRelatorio']}'>
		<label for='dataEnvioRelatorio'>Data de Envio do Relatorio</label>
		<input type='date' name='dataEnvioRelatorio' id='dataEnvioRelatorio' value='{$propostaParaAtualizar['dataEnvioRelatorio']}'>
		<label for='valor'>Valor da Proposta</label>
		<input type='text' name='valor' id='valor' placeholder='Ex: 999,99' maxlength='10' value='{$propostaParaAtualizar['valor']}'>
		<label for='numeroNotaFiscal'>NF</label>
		<input type='number' name='numeroNotaFiscal' id='numeroNotaFiscal' placeholder='Ex: 123456789' max='999999999' value='{$propostaParaAtualizar['numeroNotaFiscal']}'>
		<label for='dataPagamento'>Data do Pagamento</label>
		<input type='date' name='dataPagamento' id='dataPagamento' value='{$propostaParaAtualizar['dataPagamento']}'>
		<label for='formaPagamento'>Forma de Pagamento</label>
		<input type='text' name='formaPagamento' id='formaPagamento' placeholder='Ex: Parcelado 2x' maxlength='255' value='{$propostaParaAtualizar['formaPagamento']}'>
		<label for='dataUltimaCobranca'>Data Última Cobrança</label>
		<input type='date' name='dataUltimaCobranca' id='dataUltimaCobranca' value='{$propostaParaAtualizar['dataUltimaCobranca']}'>
		<label for='observacoes'>Observações</label>
		<input type='text' name='observacoes' id='observacoes' placeholder='Ex: Desenvolvimento...' maxlength='255' value='{$propostaParaAtualizar['observacoes']}'>
		<button id='updateStatusBtn' type='submit' name='atualizarStatusProposta'>Atualizar</button>
		<a id='cancelUpdateStatusBtn' href=''>Cancelar</a>
	</form>
	</div>
	";
}

?>

<form id='searchBox' action='' method='post'>
	<input type='text' name='pesquisa' value='<?= $pesquisa; ?>' placeholder='Texto para pesquisa'>
	<button id='searchBtn' type='submit' name=''>🔍︎</button>
</form>
<div class="tableResponsive">
	<table>
		<thead>
			<tr>
				<th>N° Proposta</th>
				<th>Cliente</th>
				<th>Valor (R$)</th>
				<th>Data Envio Proposta</th>
				<th>Data Aceite Proposta</th>
				<th>Dias em Análise</th>
				<th>Status Proposta</th>
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

			foreach ($propostas as $proposta)
			{
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

				echo "
				<tr>
					<td>{$proposta['numeroProposta']}</td>
					<td>" . htmlspecialchars($proposta['cliente']) . "</td>
					<td>{$proposta['valor']}</td>
					<td>{$proposta['dataEnvioProposta']}</td>
					<td>{$proposta['dataAceiteProposta']}</td>
					<td>{$proposta['diasEmAnalise']}</td>
					<td class='$statusProposta'>{$proposta['statusProposta']}</td>
					<td>{$proposta['numeroRelatorio']}</td>
					<td>{$proposta['dataEnvioRelatorio']}</td>
					<td>{$proposta['numeroNotaFiscal']}</td>
					<td>{$proposta['dataPagamento']}</td>
					<td>" . htmlspecialchars($proposta['formaPagamento']) . "</td>
					<td class='$statusPagamento'>{$proposta['statusPagamento']}</td>
					<td>{$proposta['diasAguardandoPagamento']}</td>
					<td>{$proposta['dataUltimaCobranca']}</td>
					<td>{$proposta['diasUltimaCobranca']}</td>
					<td>" . htmlspecialchars($proposta['observacoes']) . "</td>
					<td>
					   <form action='' method='post'>
							<input type='hidden' name='id' value='{$proposta['id']}'>
							<input type='hidden' name='numeroProposta' value='{$proposta['numeroProposta']}'>
							<input type='hidden' name='dataAceiteProposta' value='{$proposta['dataAceiteProposta']}'>
							<button class='updateProposalBtn' type='submit' name='mostrarAtualizarStatus'>✎</button>
						</form>
					</td>
					<td>
					   <form action='' method='post'>
							<input type='hidden' name='id' value='{$proposta['id']}'>
							<button class='deleteProposalBtn' type='submit' name='excluirProposta' onclick=\"return prompt('ATENÇÃO! Excluir permanentemente proposta N° {$proposta['numeroProposta']}? Caso tenha certeza, digite EXCLUIR abaixo.') === 'EXCLUIR'\">✖</button>
						</form>
					</td>
				</tr>
				";
			}

			?>

		</tbody>
	</table>
</div>

<?php
require_once '../../src/footer.php';
?>

</body>

</html>
