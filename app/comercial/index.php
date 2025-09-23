<?php

$pageTitle = 'Comercial';

require_once '../../src/header.php';
require_once '../../src/Proposta.php';

$proposta = new Proposta();
$propostas = $proposta->verPropostasEmFaseComercial();

if (isset($_POST['cadastrarProposta']) && !empty($_POST['dataEnvioProposta']) && !empty($_POST['valor']) && !empty($_POST['cliente']))
{
	$proposta->cadastrarProposta();
}

if (isset($_POST['aceitarProposta']) && !empty($_POST['id']) && isset($_POST['dataEnvioProposta']))
{
	$proposta->aceitarProposta();
}

if (isset($_POST['recusarProposta']) && !empty($_POST['id']) && isset($_POST['dataEnvioProposta']))
{
	$proposta->recusarProposta();
}

if (isset($_POST['excluirProposta']) && !empty($_POST['id']))
{
	$proposta->excluirProposta();
}

?>

<button id="showRegisterProposalFormBtn">+ Nova Proposta</button>
<div id="registerProposalForm" class="formWrapper">
	<form action="" method="post" class="customForm">
		<h2>Cadastrar Proposta</h2>
		<label for="numeroProposta">N° da Proposta</label>
		<input type="number" name="numeroProposta" id="numeroProposta" placeholder="Ex: 2020001" max="99999999999" required>
		<label for="dataEnvioProposta">Data de Envio da Proposta</label>
		<input type="date" name="dataEnvioProposta" id="dataEnvioProposta" required>
		<label for="valor">Valor da Proposta</label>
		<input type="text" name="valor" id="valor" placeholder="Ex: 999,99" maxlength="10" required>
		<label for="cliente">Cliente</label>
		<input type="text" name="cliente" id="cliente" placeholder="Nome do Cliente" maxlength="255" required>
		<label for="observacoes">Observações</label>
		<input type="text" name="observacoes" id="observacoes" placeholder="Ex: Desenvolvimento..." maxlength="255">
		<button id="registerProposalBtn" type="submit" name="cadastrarProposta">Cadastrar</button>
		<button id="cancelRegisterProposalBtn" type="button">Cancelar</button>
	</form>
</div>
<div class="tableResponsive">
	<table>
		<thead>
			<tr>
				<th>N° Proposta</th>
				<th>Cliente</th>
				<th>Valor (R$)</th>
				<th>Data Envio Proposta</th>
				<th>Dias em Análise</th>
				<th>Status</th>
				<th>Observações</th>
				<th>Aceitar</th>
				<th>Recusar</th>
				<th>Excluir</th>
			</tr>
		</thead>
		<tbody>

			<?php

			foreach ($propostas as $proposta)
			{
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
					<td>{$proposta['diasEmAnalise']}</td>
					<td class='{$statusProposta}'>{$proposta['statusProposta']}</td>
					<td>" . htmlspecialchars($proposta['observacoes']) . "</td>
					<td>
						<form action='' method='post'>
							<input type='hidden' name='id' value='{$proposta['id']}'>
							<input type='hidden' name='dataEnvioProposta' value='{$proposta['dataEnvioProposta']}'>
							<button class='aproveProposalBtn' type='submit' name='aceitarProposta'>✓</button>
						</form>
					</td>
					<td>
						<form action='' method='post'>
							<input type='hidden' name='id' value='{$proposta['id']}'>
							<input type='hidden' name='dataEnvioProposta' value='{$proposta['dataEnvioProposta']}'>
							<button  class='denyProposalBtn' type='submit' name='recusarProposta'>✗</button>
						</form>
					</td>
					<td>
					   <form action='' method='post'>
							<input type='hidden' name='id' value='{$proposta['id']}'>
							<button class='deleteProposalBtn type='submit' name='excluirProposta' onclick=\"return prompt('ATENÇÃO! Excluir permanentemente proposta N° {$proposta['numeroProposta']}? Caso tenha certeza, digite EXCLUIR abaixo.') === 'EXCLUIR'\">✖</button>
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
