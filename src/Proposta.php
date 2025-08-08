<?php

require_once 'DatabaseConnection.php';
require_once 'DataRepository.php';

class Proposta
{
	private ?object $connection = null;
	private ?object $data = null;
	
	public function __construct()
	{
		$this->connection = new DatabaseConnection();
		$this->data = new DataRepository($this->connection->start());
	}
	
	public function verProposta(int $id): array|false
	{
		return $this->data->read('propostas', "WHERE id = $id")[0];
	}

	public function verPropostasEmFaseFinanceira(): array
	{
		return $this->data->read('propostas', 'WHERE statusProposta = "Aceita" ORDER BY statusPagamento ASC, dataAceiteProposta DESC;');
	}

	public function verPropostasEmFaseComercial(): array
	{
		return $this->data->read('propostas', 'WHERE statusProposta = "Em análise" OR statusProposta = "Recusada" ORDER BY dataEnvioProposta DESC');
	}
	
	public function pesquisarProposta(): array|false
	{
		return $this->data->search('propostas', [
			'numeroProposta',
			'dataEnvioProposta',
			'cliente',
			'valor',
			'numeroNotaFiscal',
			'observacoes',
		], $_GET['pesquisa']);
	}

	public function cadastrarProposta(): bool
	{
		$created = $this->data->create('propostas', [
			'numeroProposta' => $_POST['numeroProposta'],
			'valor' => str_replace(',', '.', $_POST['valor']),
			'cliente' => $_POST['cliente'],
			'observacoes' => empty($_POST['observacoes']) ? null : $_POST['observacoes'],
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
		// Corrigi dias aguardando pagamento caso data de pagamento seja diferente de hoje
		if (new DateTime($_POST['dataPagamento']) !== new DateTime())
		{
			$_POST['diasAguardandoPagamento'] = (new DateTime($_POST['dataPagamento']))->diff(new DateTime($_POST['dataAceiteProposta']))->days;
		}
		
		$affectedRows = $this->data->update('propostas', [
				'numeroRelatorio' => empty($_POST['numeroRelatorio']) ? null : $_POST['numeroRelatorio'],
				'dataEnvioRelatorio' => empty($_POST['dataEnvioRelatorio']) ? null : $_POST['dataEnvioRelatorio'],
				'valor' => empty($_POST['valor']) ? null : str_replace(',', '.', $_POST['valor']),
				'numeroNotaFiscal' => empty($_POST['numeroNotaFiscal']) ? null : $_POST['numeroNotaFiscal'],
				'dataPagamento' => empty($_POST['dataPagamento']) ? null : $_POST['dataPagamento'],
				'statusPagamento' => empty($_POST['dataPagamento']) ? 'Aguardando' : 'Recebido',
				'formaPagamento' => empty($_POST['formaPagamento']) ? null : $_POST['formaPagamento'],
				'observacoes' => empty($_POST['observacoes']) ? null : $_POST['observacoes'],
				'diasAguardandoPagamento' => empty($_POST['diasAguardandoPagamento']) ? null : $_POST['diasAguardandoPagamento'],
				'dataUltimaCobranca' => empty($_POST['dataUltimaCobranca']) ? null : $_POST['dataUltimaCobranca'],
			],
			[
				'id' => $_POST['id']
			]
		);

		if ($affectedRows > 0)
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
		$now = (new DateTime())->format('Y-m-d H:i');

		$affectedRows = $this->data->update('propostas', [
				'statusProposta' => 'Aceita', 
				'dataAceiteProposta' => $now, 
				'diasEmAnalise' => $_POST['diasEmAnalise']
			], 
			[
				'id' => $_POST['id']
			]);

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
		$affectedRows = $this->data->update('propostas', ['statusProposta' => 'Recusada', 'diasEmAnalise' => $_POST['diasEmAnalise']], ['id' => $_POST['id']]);

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
		$affectedRows = $this->data->delete('propostas', ['id' => $_POST['id']]);

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
