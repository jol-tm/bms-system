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

if (isset($_POST['aceitarProposta']) && !empty($_POST['id']) && isset($_POST['diasEmAnalise']))
{
	$proposta->aceitarProposta();
}

if (isset($_POST['recusarProposta']) && !empty($_POST['id']) && isset($_POST['diasEmAnalise']))
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
							<input type='hidden' name='diasEmAnalise' value='{$proposta['diasEmAnalise']}'>
							<button type='submit' name='aceitarProposta'>
								<svg class='aproveProposalBtn' xmlns='http://www.w3.org/2000/svg'
									height='24px' viewBox='0 -960 960 960' width='24px' fill='#fff'>
									<path
										d='m382-354 339-339q12-12 28-12t28 12q12 12 12 28.5T777-636L410-268q-12 12-28 12t-28-12L182-440q-12-12-11.5-28.5T183-497q12-12 28.5-12t28.5 12l142 143Z' />
								</svg>
							</button>
						</form>
					</td>
					<td>
						<form action='' method='post'>
							<input type='hidden' name='id' value='{$proposta['id']}'>
							<input type='hidden' name='diasEmAnalise' value='{$proposta['diasEmAnalise']}'>
							<button type='submit' name='recusarProposta'>
								<svg class='denyProposalBtn' xmlns='http://www.w3.org/2000/svg'
									height='24px' viewBox='0 -960 960 960' width='24px' fill='#fff'>
									<path
										d='M480-424 284-228q-11 11-28 11t-28-11q-11-11-11-28t11-28l196-196-196-196q-11-11-11-28t11-28q11-11 28-11t28 11l196 196 196-196q11-11 28-11t28 11q11 11 11 28t-11 28L536-480l196 196q11 11 11 28t-11 28q-11 11-28 11t-28-11L480-424Z' />
								</svg>
							</button>
						</form>
					</td>
					<td>
					   <form action='' method='post'>
							<input type='hidden' name='id' value='{$proposta['id']}'>
							<button type='submit' name='excluirProposta' onclick=\"return prompt('ATENÇÃO! Excluir permanentemente proposta N° {$proposta['numeroProposta']}? Caso tenha certeza, digite EXCLUIR abaixo.') === 'EXCLUIR'\">
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

<?php
require_once '../../src/footer.php';
?>

</body>

</html>
