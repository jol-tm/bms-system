<?php

ini_set('display_errors', 1);
ini_set('session.cookie_lifetime', 600);
ini_set('session.gc_maxlifetime', 600);

date_default_timezone_set('America/Sao_Paulo');

session_start();

if (!isset($_SESSION['authenticatedUser']) && ($pageTitle !== 'Acesso'))
{
	header('Location: ../acesso');
	$_SESSION['notification'] = 'Não autenticado ou sessão expirada!';
	exit();
}

if (isset($_SESSION['authenticatedUser']) && ($pageTitle === 'Acesso'))
{
	header('Location: ../comercial');
	exit();
}

$baseAssetsPath = '/crm-bms/app/assets/';

?>

<!DOCTYPE html>
<html lang='pt-br'>

<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<title>BMS | <?= $pageTitle; ?></title>
	<link rel='icon' href='<?= $baseAssetsPath . 'LOGOTIPO.svg'; ?>' type='image/svg+xml'>
	<link rel='stylesheet' href='<?= $baseAssetsPath . 'style.css'; ?>'>
	<script defer src='<?= $baseAssetsPath . 'script.js'; ?>'></script>
</head>

<body>
	<svg id="logo" width="150" height="30" viewBox="0 0 1100 250" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="M458.732 121.709C472.612 126.104 483.485 133.507 491.35 143.917C499.216 154.096 503.149 166.704 503.149 181.741C503.149 203.024 494.821 219.448 478.164 231.015C461.739 242.351 437.68 248.019 405.987 248.019H280.371V5.11466H399.047C428.658 5.11466 451.329 10.7824 467.06 22.1179C483.022 33.4535 491.003 48.8374 491.003 68.2697C491.003 80.0679 488.112 90.5937 482.328 99.8472C476.776 109.101 468.911 116.388 458.732 121.709ZM336.239 47.4494V104.705H392.107C405.987 104.705 416.513 102.276 423.684 97.4182C430.856 92.5601 434.442 85.3886 434.442 75.9038C434.442 66.419 430.856 59.3632 423.684 54.7365C416.513 49.8784 405.987 47.4494 392.107 47.4494H336.239ZM401.823 205.684C416.629 205.684 427.733 203.255 435.136 198.397C442.77 193.539 446.587 186.02 446.587 175.841C446.587 155.715 431.666 145.652 401.823 145.652H336.239V205.684H401.823Z"
			fill="#847B75" />
		<path
			d="M766.751 248.019L766.404 102.276L694.921 222.34H669.589L598.453 105.399V248.019H545.708V5.11466H592.207L683.123 156.062L772.65 5.11466H818.802L819.496 248.019H766.751Z"
			fill="#847B75" />
		<path
			d="M956.937 252.183C937.736 252.183 919.113 249.638 901.069 244.549C883.256 239.228 868.913 232.403 858.041 224.075L877.126 181.741C887.536 189.375 899.912 195.505 914.255 200.132C928.598 204.759 942.941 207.072 957.284 207.072C973.246 207.072 985.045 204.759 992.679 200.132C1000.31 195.274 1004.13 188.912 1004.13 181.047C1004.13 175.263 1001.82 170.521 997.19 166.819C992.794 162.887 987.011 159.764 979.839 157.45C972.899 155.137 963.415 152.592 951.385 149.816C932.878 145.421 917.725 141.025 905.927 136.63C894.129 132.234 883.95 125.179 875.391 115.462C867.063 105.746 862.899 92.7914 862.899 76.5978C862.899 62.4863 866.716 49.7627 874.35 38.4272C881.984 26.8604 893.435 17.7225 908.703 11.0138C924.203 4.30499 943.057 0.950607 965.265 0.950607C980.765 0.950607 995.917 2.8013 1010.72 6.50268C1025.53 10.2041 1038.48 15.5248 1049.59 22.4649L1032.24 65.1466C1009.8 52.4231 987.358 46.0613 964.918 46.0613C949.187 46.0613 937.505 48.606 929.871 53.6955C922.468 58.7849 918.766 65.4936 918.766 73.8218C918.766 82.1499 923.046 88.396 931.606 92.5601C940.397 96.4928 953.698 100.426 971.511 104.358C990.018 108.754 1005.17 113.149 1016.97 117.544C1028.77 121.94 1038.83 128.88 1047.16 138.365C1055.72 147.85 1060 160.689 1060 176.882C1060 190.763 1056.07 203.486 1048.2 215.053C1040.57 226.389 1029 235.411 1013.5 242.12C997.999 248.828 979.145 252.183 956.937 252.183Z"
			fill="#847B75" />
		<path d="M246.309 245.98L128.727 0.814133V190.245L246.309 245.98Z" fill="#C1A995" />
		<path d="M118.75 0.814232L0 245.98L118.75 190.245V0.814232Z" fill="#A17850" />
		<path d="M239.346 252.693H6.12891L122.154 199.01L239.346 252.693Z" fill="#F5F5F5" />
	</svg>

	<?php

	if ($pageTitle !== 'Acesso')
	{
		$comercialId = $pageTitle === 'Comercial' ? 'currentPage' : null;
		$financeiroId = $pageTitle === 'Financeiro' ? 'currentPage' : null;

		echo "
		<h5 id='authenticatedUser'>
			Olá, {$_SESSION['authenticatedUser']} | <a href='./?desconectar'>Desconectar</a>
		</h5>
		<nav>
			<a id='$comercialId' href='../comercial/ '>Comercial</a>
			<a id='$financeiroId' href='../financeiro/'>Financeiro</a>
		</nav>
		";
	}

	if (isset($_GET['desconectar']))
	{
		require_once '../../src/DatabaseConnection.php';
		require_once '../../src/Authenticator.php';

		$connection = new DatabaseConnection();
		$authenticator = new Authenticator($connection->start());
		$disconnectSuccess = $authenticator->disconnect();

		if ($disconnectSuccess)
		{
			header('Location: ../acesso?desconectado');
			exit();
		}
	}

	if (isset($_GET['desconectado']))
	{
		echo "<div class='notification'>Desconectado com sucesso.</div>";
	}

	if (isset($_SESSION['notification']))
	{
		echo "<div class='notification'>{$_SESSION['notification']}</div>";
		unset($_SESSION['notification']);
	}

	?>
