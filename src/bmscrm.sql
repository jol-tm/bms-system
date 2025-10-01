CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE propostas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numeroProposta INT UNIQUE NULL,
    dataEnvioProposta DATE,
    cliente VARCHAR(255) NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    numeroRelatorio INT UNIQUE,
    dataEnvioRelatorio DATE,
    numeroNotaFiscal INT UNIQUE,
    dataPagamento DATE,
    statusPagamento VARCHAR(50) DEFAULT 'Aguardando',
    statusProposta VARCHAR(50) DEFAULT 'Em análise',
    dataAceiteProposta DATE,
    diasEmAnalise INT,
    diasAguardandoPagamento INT,
    formaPagamento varchar(255),
    dataUltimaCobranca DATE,
    observacoes VARCHAR(255)
);

