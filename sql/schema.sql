DROP TABLE IF EXISTS itens_orcamento;
DROP TABLE IF EXISTS orcamentos;
DROP TABLE IF EXISTS compras;
DROP TABLE IF EXISTS precos;
DROP TABLE IF EXISTS lojas;
DROP TABLE IF EXISTS produtos;
DROP TABLE IF EXISTS clientes;

CREATE TABLE clientes (
  id_cliente INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) DEFAULT NULL,
  email VARCHAR(100) NOT NULL,
  telemovel VARCHAR(20) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  PRIMARY KEY (id_cliente),
  UNIQUE KEY uq_clientes_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE produtos (
  id_produto INT NOT NULL AUTO_INCREMENT,
  nome_produto VARCHAR(150) NOT NULL,
  categoria VARCHAR(100) DEFAULT NULL,
  descricao TEXT DEFAULT NULL,
  PRIMARY KEY (id_produto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE lojas (
  id_loja INT NOT NULL AUTO_INCREMENT,
  nome_loja VARCHAR(100) NOT NULL,
  PRIMARY KEY (id_loja)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE precos (
  id_preco INT NOT NULL AUTO_INCREMENT,
  id_produto INT NOT NULL,
  id_loja INT NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id_preco),
  FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_loja) REFERENCES lojas(id_loja) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE compras (
  id_compra INT NOT NULL AUTO_INCREMENT,
  id_cliente INT NOT NULL,
  id_produto INT NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  quantidade INT NOT NULL DEFAULT 1,
  data_compra DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_compra),
  FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE orcamentos (
    id_orcamento INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT, 
    valor_maximo DECIMAL(10,2) DEFAULT 0.00,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('aberto', 'finalizado') DEFAULT 'aberto',
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE itens_orcamento (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_orcamento INT,
    id_produto INT,
    quantidade INT DEFAULT 1,
    FOREIGN KEY (id_orcamento) REFERENCES orcamentos(id_orcamento) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;