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
		$propostas = $this->data->read('propostas', 'WHERE statusProposta = "Aceita" ORDER BY dataAceiteProposta ASC;');
		
		$hoje = (new DateTime())->setTime(0, 0, 0);

		foreach ($propostas as &$proposta)
		{
			if ($proposta['dataAceiteProposta'] !== null)
			{
				$proposta['dataAceiteProposta'] = new DateTime($proposta['dataAceiteProposta']);
				$proposta['diasAguardandoPagamento'] = $proposta['statusPagamento'] === 'Aguardando' ? $hoje->diff($proposta['dataAceiteProposta'])->days : $proposta['diasAguardandoPagamento'];
				$proposta['dataAceiteProposta'] = $proposta['dataAceiteProposta']->format('d/m/Y');
			}
			
			if ($proposta['dataUltimaCobranca'] !== null)
			{
				$proposta['dataUltimaCobranca'] = new DateTime($proposta['dataUltimaCobranca']);
				
				if ($proposta['statusPagamento'] === 'Aguardando')
				{
					$proposta['diasUltimaCobranca'] = $hoje->diff($proposta['dataUltimaCobranca'])->days;
				}
				
				$proposta['dataUltimaCobranca'] = $proposta['dataUltimaCobranca']->format('d/m/Y');
			}
			
			if ($proposta['dataEnvioRelatorio'] !== null)
			{
				$proposta['dataEnvioRelatorio'] = (new DateTime($proposta['dataEnvioRelatorio']))->format('d/m/Y');
			}
			
			if ($proposta['dataPagamento'] !== null)
			{
				$proposta['dataPagamento'] = (new DateTime($proposta['dataPagamento']))->format('d/m/Y');
			}
			
			$proposta['dataEnvioProposta'] = (new DateTime($proposta['dataEnvioProposta']))->format('d/m/Y');
			
			empty($proposta['dataAceiteProposta']) ? $proposta['dataAceiteProposta'] = '-' : null;
			empty($proposta['dataUltimaCobranca']) ? $proposta['dataUltimaCobranca'] = '-' : null;
			empty($proposta['dataEnvioRelatorio']) ? $proposta['dataEnvioRelatorio'] = '-' : null;
			empty($proposta['dataPagamento']) ? $proposta['dataPagamento'] = '-' : null;
			empty($proposta['numeroNotaFiscal']) ? $proposta['numeroNotaFiscal'] = '-' : null;
			empty($proposta['formaPagamento']) ? $proposta['formaPagamento'] = '-' : null;
			empty($proposta['numeroRelatorio']) ? $proposta['numeroRelatorio'] = '-' : null;
			empty($proposta['observacoes']) ? $proposta['observacoes'] = '-' : null;
			empty($proposta['numeroProposta']) ? $proposta['numeroProposta'] = '-' : null;
			isset($proposta['diasEmAnalise']) ? null : $proposta['diasEmAnalise'] = '-';
			isset($proposta['diasAguardandoPagamento']) ? null : $proposta['diasAguardandoPagamento'] = '-';
			isset($proposta['diasUltimaCobranca']) ? null : $proposta['diasUltimaCobranca'] = '-';
			$proposta['valor'] = str_replace('.', ',', $proposta['valor']);
		}
		
		return $propostas;
	}

	public function verPropostasEmFaseComercial(): array
	{
		$propostas = $this->data->read('propostas', 'WHERE statusProposta = "Em análise" OR statusProposta = "Recusada" ORDER BY dataEnvioProposta ASC');
		
		$hoje = (new DateTime())->setTime(0, 0, 0);
		
		foreach ($propostas as &$proposta)
		{
			$proposta['dataEnvioProposta'] = new DateTime($proposta['dataEnvioProposta']);
			
			$proposta['diasEmAnalise'] = $proposta['statusProposta'] === 'Em análise' ? $hoje->diff($proposta['dataEnvioProposta'])->days : $proposta['diasEmAnalise'];
			
			$proposta['dataEnvioProposta'] = $proposta['dataEnvioProposta']->format('d/m/Y');
			
			$proposta['valor'] = str_replace('.', ',', $proposta['valor']);
			
			$proposta['numeroProposta'] === null ? $proposta['numeroProposta'] = '-' : null;
			
			empty($proposta['observacoes']) ? $proposta['observacoes'] = '-' : null;
		}
		
		return $propostas;
	}
	
	public function pesquisarProposta(): array|false
	{
		if ($data = DateTime::createFromFormat('m/Y', $_GET['q']))
		{
			$_GET['q'] = $data->format('Y-m');
		}
		
		$propostas = $this->data->search('propostas', [
			'numeroProposta',
			'numeroNotaFiscal',
			'dataAceiteProposta',
			'dataEnvioProposta',
			'statusPagamento',
			'valor',
			'cliente',
			'observacoes',
		], $_GET['q'], 'ORDER BY dataEnvioProposta ASC;');
		
		$hoje = (new DateTime())->setTime(0, 0, 0);
		
		foreach ($propostas as &$proposta)
		{
			if ($proposta['dataAceiteProposta'] !== null)
			{
				$proposta['dataAceiteProposta'] = new DateTime($proposta['dataAceiteProposta']);
				$proposta['diasAguardandoPagamento'] = $proposta['statusPagamento'] === 'Aguardando' ? $hoje->diff($proposta['dataAceiteProposta'])->days : $proposta['diasAguardandoPagamento'];
				$proposta['dataAceiteProposta'] = $proposta['dataAceiteProposta']->format('d/m/Y');
			}
			
			if ($proposta['dataUltimaCobranca'] !== null)
			{
				$proposta['dataUltimaCobranca'] = new DateTime($proposta['dataUltimaCobranca']);
				
				if ($proposta['statusPagamento'] === 'Aguardando')
				{
					$proposta['diasUltimaCobranca'] = $hoje->diff($proposta['dataUltimaCobranca'])->days;
				}
				
				$proposta['dataUltimaCobranca'] = $proposta['dataUltimaCobranca']->format('d/m/Y');
			}
			
			if ($proposta['dataEnvioRelatorio'] !== null)
			{
				$proposta['dataEnvioRelatorio'] = (new DateTime($proposta['dataEnvioRelatorio']))->format('d/m/Y');
			}
			
			if ($proposta['dataPagamento'] !== null)
			{
				$proposta['dataPagamento'] = (new DateTime($proposta['dataPagamento']))->format('d/m/Y');
			}
			
			$proposta['dataEnvioProposta'] = new DateTime($proposta['dataEnvioProposta']);
			
			empty($proposta['numeroProposta']) ? $proposta['numeroProposta'] = '-' : null;
			empty($proposta['dataAceiteProposta']) ? $proposta['dataAceiteProposta'] = '-' : null;
			empty($proposta['dataUltimaCobranca']) ? $proposta['dataUltimaCobranca'] = '-' : null;
			empty($proposta['dataEnvioRelatorio']) ? $proposta['dataEnvioRelatorio'] = '-' : null;
			empty($proposta['dataPagamento']) ? $proposta['dataPagamento'] = '-' : null;
			empty($proposta['numeroNotaFiscal']) ? $proposta['numeroNotaFiscal'] = '-' : null;
			empty($proposta['formaPagamento']) ? $proposta['formaPagamento'] = '-' : null;
			empty($proposta['numeroRelatorio']) ? $proposta['numeroRelatorio'] = '-' : null;
			empty($proposta['observacoes']) ? $proposta['observacoes'] = '-' : null;
			isset($proposta['diasAguardandoPagamento']) ? null : $proposta['diasAguardandoPagamento'] = '-';
			isset($proposta['diasUltimaCobranca']) ? null : $proposta['diasUltimaCobranca'] = '-';
			$proposta['diasEmAnalise'] = $proposta['statusProposta'] === 'Em análise' ? $hoje->diff($proposta['dataEnvioProposta'])->days : $proposta['diasEmAnalise'];
			$proposta['dataEnvioProposta'] = ($proposta['dataEnvioProposta'])->format('d/m/Y');
			$proposta['valor'] = str_replace('.', ',', $proposta['valor']);
		}
		
		return $propostas;
	}

	public function cadastrarProposta(): bool
	{
		$created = $this->data->create('propostas', [
			'numeroProposta' => $_POST['numeroProposta'] == 0 ? null : $_POST['numeroProposta'],
			'dataEnvioProposta' => $_POST['dataEnvioProposta'],
			'valor' => str_replace(',', '.', $_POST['valor']),
			'cliente' => $_POST['cliente'],
			'observacoes' => empty($_POST['observacoes']) ? null : $_POST['observacoes'],
		]);

		if ($created)
		{
			$_SESSION['notification'] = [
				'message' => 'Proposta criada com sucesso.',
				'status' => 'success'			
			];
			header('Location: ./');
			return true;
		}

		$_SESSION['notification'] = [
			'message' => 'Erro ao criar proposta.',
			'status' => 'failure'			
		];
		header('Location: ./');
		return false;
	}

	public function atualizarStatusProposta(): bool
	{  
		$dataPagamento = new DateTime($_POST['dataPagamento']);
		$dataAceiteProposta = DateTime::createFromFormat('d/m/Y', $_POST['dataAceiteProposta']);
		
		if ($dataAceiteProposta !== false)
		{
			$_POST['diasAguardandoPagamento'] = (($dataPagamento)->diff($dataAceiteProposta))->days;
		}
		
		$affectedRows = $this->data->update('propostas', [
				'statusProposta' => empty($_POST['statusProposta']) ? null : $_POST['statusProposta'],
				'numeroProposta' => empty($_POST['numeroProposta']) ? null : $_POST['numeroProposta'],
				'cliente' => empty($_POST['cliente']) ? null : $_POST['cliente'],
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
			$_SESSION['notification'] = [
				'message' => 'Status da Proposta atualizado com sucesso.',
				'status' => 'success'			
			];
			header('Location: ./');
			return true;
		}
		
		$_SESSION['notification'] = [
			'message' => 'Erro ao atualizar Status da Proposta. Nenhuma modificada.',
			'status' => 'failure'			
		];
		header('Location: ./');
		return false;
	}

	public function aceitarProposta(): bool
	{
		$hoje = (new DateTime())->setTime(0, 0, 0);
		$diasEmAnalise = ($hoje->diff((DateTime::createFromFormat('d/m/Y', $_POST['dataEnvioProposta']))->setTime(0, 0, 0)))->days;

		$affectedRows = $this->data->update('propostas', [
				'statusProposta' => 'Aceita',
				'statusPagamento' => 'Em análise',
				'dataAceiteProposta' => $hoje->format('Y-m-d'), 
				'diasEmAnalise' => $diasEmAnalise
			], 
			[
				'id' => $_POST['id']
			]);

		if ($affectedRows > 0)
		{
			$_SESSION['notification'] = [
				'message' => 'Proposta aceita com sucesso. Movida para "Financeiro".',
				'status' => 'success'			
			];
			header('Location: ./');
			return true;
		}
		
		$_SESSION['notification'] = [
			'message' => 'Erro ao aceitar proposta. Nenhuma modificada.',
			'status' => 'failure'			
		];
		header('Location: ./');
		return false;
	}

	public function recusarProposta(): bool
	{
		$hoje = (new DateTime())->setTime(0, 0, 0);
		$diasEmAnalise = ($hoje->diff((DateTime::createFromFormat('d/m/Y', $_POST['dataEnvioProposta']))->setTime(0, 0, 0)))->days;
		
		$affectedRows = $this->data->update('propostas', [
			'statusProposta' => 'Recusada',
			'statusPagamento' => 'Recusada',
			'diasEmAnalise' => $diasEmAnalise
		], 
		[
			'id' => $_POST['id']
		]);

		if ($affectedRows > 0)
		{
			$_SESSION['notification'] = [
				'message' => 'Proposta recusada com sucesso.',
				'status' => 'success'			
			];
			header('Location: ./');
			return true;
		}

		$_SESSION['notification'] = [
			'message' => 'Erro ao recusar proposta. Nenhuma modificada.',
			'status' => 'failure'			
		];
		header('Location: ./');
		return false;

	}
	
	public function excluirProposta(): bool
	{
		$affectedRows = $this->data->delete('propostas', ['id' => $_POST['id']]);

		if ($affectedRows > 0)
		{
			$_SESSION['notification'] = [
				'message' => 'Proposta excluída com sucesso.',
				'status' => 'success'			
			];
			header('Location: ./');
			return true;
		}
		
		$_SESSION['notification'] = [
			'message' => 'Erro ao excluir proposta. Nenhuma modificada.',
			'status' => 'failure'			
		];
		header('Location: ./');
		return false;
	}
	
	public function gerarRelatorio(string $data): array|bool
	{
		$data = new DateTime($data);
		$mes = $data->format('m');
		$ano = $data->format('Y');

		$propostasEnviadas = $this->data->count('propostas', "WHERE MONTH(dataEnvioProposta) = $mes AND YEAR(dataEnvioProposta) = $ano");
		$propostasAceitas = $this->data->count('propostas', "WHERE MONTH(dataAceiteProposta) = $mes AND YEAR(dataAceiteProposta) = $ano");
		$valorRecebido = $this->data->sum('propostas', 'valor', "WHERE (MONTH(dataAceiteProposta) = $mes AND YEAR(dataAceiteProposta) = $ano) AND statusPagamento = 'Recebido'");
		
		return [
			'data' => "$mes/$ano",
			'propostasEnviadas' => $propostasEnviadas,
			'propostasAceitas' => $propostasAceitas,
			'valorRecebido' => str_replace('.', ',', $valorRecebido)
		];
	}
}
