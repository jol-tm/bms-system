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
		$propostas = $this->data->read('propostas', 'WHERE statusProposta = "Aceita" ORDER BY statusPagamento ASC, dataAceiteProposta DESC;');
		
		$hoje = new DateTime();

		foreach ($propostas as &$proposta)
		{
			if ($proposta['dataAceiteProposta'] !== null)
			{
				$proposta['dataAceiteProposta'] = new DateTime($proposta['dataAceiteProposta']);
				$proposta['diasAguardandoPagamento'] = $proposta['statusPagamento'] === 'Aguardando' ? $hoje->diff($proposta['dataAceiteProposta'])->days : $proposta['diasAguardandoPagamento'];
				$proposta['dataAceiteProposta'] = $proposta['dataAceiteProposta']->format('d/m/Y H:i');
			}
			
			if ($proposta['dataUltimaCobranca'] !== null)
			{
				$proposta['dataUltimaCobranca'] = new DateTime($proposta['dataUltimaCobranca']);
				
				if ($proposta['statusPagamento'] === 'Aguardando')
				{
					$proposta['diasUltimaCobranca'] = $hoje->diff($proposta['dataUltimaCobranca'])->days;
				}
				
				$proposta['dataUltimaCobranca'] = $proposta['dataUltimaCobranca']->format('d/m/Y H:i');
			}
			
			empty($proposta['dataAceiteProposta']) ? $proposta['dataAceiteProposta'] = '-' : null;
			empty($proposta['diasAguardandoPagamento']) ? $proposta['diasAguardandoPagamento'] = '-' : null;
			empty($proposta['dataUltimaCobranca']) ? $proposta['dataUltimaCobranca'] = '-' : null;
			empty($proposta['diasUltimaCobranca']) ? $proposta['diasUltimaCobranca'] = '-' : null;
			empty($proposta['dataEnvioRelatorio']) ? $proposta['dataEnvioRelatorio'] = '-' : $proposta['dataEnvioRelatorio'] = (new DateTime($proposta['dataEnvioRelatorio']))->format('d/m/Y H:i');
			empty($proposta['dataPagamento']) ? $proposta['dataPagamento'] = '-' : $proposta['dataPagamento'] = (new DateTime($proposta['dataPagamento']))->format('d/m/Y H:i');
			empty($proposta['numeroNotaFiscal']) ? $proposta['numeroNotaFiscal'] = '-' : null;
			empty($proposta['formaPagamento']) ? $proposta['formaPagamento'] = '-' : null;
			empty($proposta['numeroRelatorio']) ? $proposta['numeroRelatorio'] = '-' : null;
			empty($proposta['observacoes']) ? $proposta['observacoes'] = '-' : null;
			isset($proposta['diasEmAnalise']) ? null : $proposta['diasEmAnalise'] = '-';
			$proposta['valor'] = str_replace('.', ',', $proposta['valor']);
		}
		
		return $propostas;
	}

	public function verPropostasEmFaseComercial(): array
	{
		$propostas = $this->data->read('propostas', 'WHERE statusProposta = "Em análise" OR statusProposta = "Recusada" ORDER BY dataEnvioProposta DESC');
		
		$hoje = new DateTime();
		
		foreach ($propostas as &$proposta)
		{
			$proposta['dataEnvioProposta'] = new DateTime($proposta['dataEnvioProposta']);
			
			$proposta['diasEmAnalise'] = $proposta['statusProposta'] === 'Em análise' ? $hoje->diff($proposta['dataEnvioProposta'])->days : $proposta['diasEmAnalise'];
			
			$proposta['dataEnvioProposta'] = $proposta['dataEnvioProposta']->format('d/m/Y H:i');
			
			$proposta['valor'] = str_replace('.', ',', $proposta['valor']);
			
			empty($proposta['observacoes']) ? $proposta['observacoes'] = '-' : null;
		}
		
		return $propostas;
	}
	
	public function pesquisarProposta(): array|false
	{
		if ($data = DateTime::createFromFormat('m/Y', $_POST['pesquisa']))
		{
			$_POST['pesquisa'] = $data->format('Y-m');
		}
		
		$propostas = $this->data->search('propostas', [
			'numeroProposta',
			'numeroNotaFiscal',
			'dataAceiteProposta',
			'dataEnvioProposta',
			'valor',
			'cliente',
			'observacoes',
		], $_POST['pesquisa']);
		
		foreach ($propostas as &$proposta)
		{
			empty($proposta['dataAceiteProposta']) ? $proposta['dataAceiteProposta'] = '-' : $proposta['dataAceiteProposta'] = (new DateTime($proposta['dataAceiteProposta']))->format('d/m/Y H:i');
			empty($proposta['diasAguardandoPagamento']) ? $proposta['diasAguardandoPagamento'] = '-' : null;
			empty($proposta['dataUltimaCobranca']) ? $proposta['dataUltimaCobranca'] = '-' : $proposta['dataUltimaCobranca'] = (new DateTime($proposta['dataUltimaCobranca']))->format('d/m/Y H:i');
			empty($proposta['diasUltimaCobranca']) ? $proposta['diasUltimaCobranca'] = '-' : null;
			empty($proposta['dataEnvioRelatorio']) ? $proposta['dataEnvioRelatorio'] = '-' : $proposta['dataEnvioRelatorio'] = (new DateTime($proposta['dataEnvioRelatorio']))->format('d/m/Y H:i');
			empty($proposta['dataPagamento']) ? $proposta['dataPagamento'] = '-' : $proposta['dataPagamento'] = (new DateTime($proposta['dataPagamento']))->format('d/m/Y H:i');
			empty($proposta['numeroNotaFiscal']) ? $proposta['numeroNotaFiscal'] = '-' : null;
			empty($proposta['formaPagamento']) ? $proposta['formaPagamento'] = '-' : null;
			empty($proposta['numeroRelatorio']) ? $proposta['numeroRelatorio'] = '-' : null;
			empty($proposta['observacoes']) ? $proposta['observacoes'] = '-' : null;
			isset($proposta['diasEmAnalise']) ? null : $proposta['diasEmAnalise'] = '-';
			$proposta['valor'] = str_replace('.', ',', $proposta['valor']);
		}
		
		return $propostas;
	}

	public function cadastrarProposta(): bool
	{
		$created = $this->data->create('propostas', [
			'numeroProposta' => $_POST['numeroProposta'],
			'dataEnvioProposta' => $_POST['dataEnvioProposta'],
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
		$dataPagamento = new DateTime($_POST['dataPagamento']);
		$dataAceiteProposta = DateTime::createFromFormat('d/m/Y H:i', $_POST['dataAceiteProposta']);
		
		if ($dataAceiteProposta !== false)
		{
			$_POST['diasAguardandoPagamento'] = (($dataPagamento)->diff($dataAceiteProposta))->days;
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
