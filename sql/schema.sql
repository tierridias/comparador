DROP TABLE IF EXISTS `compras`;
DROP TABLE IF EXISTS `produtos`;
DROP TABLE IF EXISTS `clientes`;


CREATE TABLE `clientes` (
  `id_cliente` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(100) NOT NULL,
  `telemovel` VARCHAR(20) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `uq_clientes_email` (`email`) /* impede que dois utilizadores tenham emails iguais */
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `produtos` (
  `id_produto` INT NOT NULL AUTO_INCREMENT,
  `nome_produto` VARCHAR(150) NOT NULL,
  `categoria` VARCHAR(100) DEFAULT NULL,
  `descricao` TEXT DEFAULT NULL,
  PRIMARY KEY (`id_produto`),
  KEY `idx_produtos_categoria` (`categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `compras` (
  `id_compra` INT NOT NULL AUTO_INCREMENT,
  `id_cliente` INT NOT NULL,
  `id_produto` INT NOT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  `quantidade` INT NOT NULL DEFAULT 1,
  `data_compra` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_compra`),
  KEY `fk_compras_cliente_idx` (`id_cliente`),
  KEY `fk_compras_produto_idx` (`id_produto`),
  CONSTRAINT `fk_compras_cliente`
    FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_compras_produto`
    FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
