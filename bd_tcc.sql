-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for bd_tcc
CREATE DATABASE IF NOT EXISTS `bd_tcc` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `bd_tcc`;

-- Dumping structure for procedure bd_tcc.atualiza_prazo_final
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `atualiza_prazo_final`(
	IN `inGuia` INT
)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN
  DECLARE PrazoFinal, PrazoGuia DECIMAL(8,2);
  select MAX(prazo_guiaexame) into PrazoGuia FROM guiasexames WHERE guia_guiaexame=inGuia;
  
  SET PrazoFinal = PrazoGuia;
  
  update guias set prazofinal_guia=PrazoFinal where id_guia=inGuia;
END//
DELIMITER ;

-- Dumping structure for procedure bd_tcc.atualiza_valor_total
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `atualiza_valor_total`(
	IN `inGuia` INT



)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN
  DECLARE PrecoTotal, PrecoGuia DECIMAL(8,2);
  select ifNULL(sum(preco_guiaexame), 0) into PrecoGuia FROM guiasexames WHERE guia_guiaexame=inGuia;
  
  SET PrecoTotal = PrecoGuia;
  
  update guias set precototal_guia=PrecoTotal where id_guia=inGuia;
END//
DELIMITER ;

-- Dumping structure for table bd_tcc.bancadas
CREATE TABLE IF NOT EXISTS `bancadas` (
  `id_bancada` int(11) NOT NULL AUTO_INCREMENT,
  `bancada` varchar(80) NOT NULL,
  PRIMARY KEY (`id_bancada`),
  UNIQUE KEY `ValorDeReferencia_UNIQUE` (`bancada`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.bancadas: ~2 rows (approximately)
/*!40000 ALTER TABLE `bancadas` DISABLE KEYS */;
INSERT INTO `bancadas` (`id_bancada`, `bancada`) VALUES
	(1, 'banca'),
	(2, 'Bancada'),
	(3, 'Endocrino');
/*!40000 ALTER TABLE `bancadas` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nome_cliente` varchar(45) NOT NULL,
  `cidade_cliente` varchar(45) DEFAULT NULL,
  `endereco_cliente` varchar(45) DEFAULT NULL,
  `cpf_cliente` varchar(45) DEFAULT NULL,
  `rg_cliente` varchar(45) DEFAULT NULL,
  `telefone_cliente` varchar(45) DEFAULT NULL,
  `celular_cliente` varchar(45) DEFAULT NULL,
  `sexo_cliente` enum('Masculino','Feminino') NOT NULL,
  `datanascimento_cliente` date NOT NULL,
  `observacao_cliente` text,
  `usuario_cliente` varchar(45) NOT NULL,
  `senha_cliente` varchar(45) NOT NULL,
  `cep_cliente` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `cpf_cliente_UNIQUE` (`cpf_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.clientes: ~9 rows (approximately)
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` (`id_cliente`, `nome_cliente`, `cidade_cliente`, `endereco_cliente`, `cpf_cliente`, `rg_cliente`, `telefone_cliente`, `celular_cliente`, `sexo_cliente`, `datanascimento_cliente`, `observacao_cliente`, `usuario_cliente`, `senha_cliente`, `cep_cliente`) VALUES
	(1, 'Nome', 'Cidade', 'dsasdasda', '999.999.999-99', NULL, '(11) 11111-111', NULL, 'Feminino', '2004-08-17', 'Observação', 'Usuario', 'Senha', NULL),
	(2, 'a', 'aa', NULL, '111.111.111-11', NULL, '(14) 7522-2222', NULL, 'Masculino', '2001-07-06', 'aaa', '1', '2', NULL),
	(3, 'a', 'aa', '', '996.435.470-35', '', '(14) 7522-2222', '', 'Masculino', '2021-07-09', 'aaa', '556218532', '60fb9464ba629', ''),
	(6, 'a', 'aa', NULL, '879.654.562-31', NULL, '(14) 7522-2222', NULL, 'Masculino', '2021-07-06', 'aaa', '483109802', '60fb96fde5067', NULL),
	(8, 'a', 'aa', NULL, '879.654.562-32', NULL, '(14) 7522-2222', NULL, 'Masculino', '2021-07-06', 'aaa', '474990134', '60fb982d39840', NULL),
	(10, 'a', 'aa', NULL, '879.654.562-33', NULL, '(14) 7522-2222', NULL, 'Masculino', '2021-07-06', 'aaa', '2071085910', '60fb9859c3508', NULL),
	(15, 'teste', 'teste', 'teste', '123.456.789-10', NULL, '(42) 3254-1011', NULL, 'Feminino', '2021-07-15', '', '1291208606', '60fce186052af', NULL),
	(16, 'teste', 'teste', 'teste', '123.456.789-11', NULL, '(42) 3254-1011', NULL, 'Feminino', '2021-07-15', '', '1291208606', '60fce186052af', NULL),
	(17, 'Guilherme Wegrzyn Albach', 'Paulo Frontin', 'Rua João Retcheski, 9', '111.718.899-09', '42.344.366-5', '', '(42) 99909-4762', 'Masculino', '2004-10-23', 'Editar Observação sobre o cliente.', 'TESTE', 'TESTE', '84635-000');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.convenios
CREATE TABLE IF NOT EXISTS `convenios` (
  `id_convenio` int(11) NOT NULL AUTO_INCREMENT,
  `nome_convenio` varchar(45) NOT NULL,
  `cnpj_convenio` varchar(45) DEFAULT NULL,
  `responsavel_convenio` varchar(45) DEFAULT NULL,
  `telefone_convenio` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_convenio`),
  UNIQUE KEY `cnpj_convenio_UNIQUE` (`cnpj_convenio`),
  UNIQUE KEY `telefone_convenio_UNIQUE` (`telefone_convenio`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.convenios: ~3 rows (approximately)
/*!40000 ALTER TABLE `convenios` DISABLE KEYS */;
INSERT INTO `convenios` (`id_convenio`, `nome_convenio`, `cnpj_convenio`, `responsavel_convenio`, `telefone_convenio`) VALUES
	(2, 'Nome', '11.111.111/1111-11', 'responsavel', '(22) 2222-2222'),
	(3, 'Teste', '8979779', 'teste', '78978879'),
	(4, 'clueless', '1475222633', 'oh no no', '897789897798');
/*!40000 ALTER TABLE `convenios` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.especialidades
CREATE TABLE IF NOT EXISTS `especialidades` (
  `id_especialidade` int(11) NOT NULL AUTO_INCREMENT,
  `especialidade` varchar(80) NOT NULL,
  PRIMARY KEY (`id_especialidade`),
  UNIQUE KEY `ValorDeReferencia_UNIQUE` (`especialidade`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.especialidades: ~2 rows (approximately)
/*!40000 ALTER TABLE `especialidades` DISABLE KEYS */;
INSERT INTO `especialidades` (`id_especialidade`, `especialidade`) VALUES
	(1, 'Cardiologia');
/*!40000 ALTER TABLE `especialidades` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.exames
CREATE TABLE IF NOT EXISTS `exames` (
  `id_exame` int(11) NOT NULL AUTO_INCREMENT,
  `nome_exame` varchar(45) NOT NULL,
  `sexo_exame` enum('Masculino','Feminino','Ambos') NOT NULL,
  `requisicao_exame` enum('sim','nao') NOT NULL,
  `tempo_exame` int(11) NOT NULL,
  `material_exame` int(11) NOT NULL,
  `metodo_exame` int(11) NOT NULL,
  `bancada_exame` int(11) NOT NULL,
  `descricao_exame` text,
  `preco_exame` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id_exame`),
  KEY `fk_exames_Materiais1_idx` (`material_exame`),
  KEY `fk_exames_Bancadas1_idx` (`bancada_exame`),
  KEY `fk_exames_Metodos1_idx` (`metodo_exame`),
  CONSTRAINT `fk_exames_Bancadas1` FOREIGN KEY (`bancada_exame`) REFERENCES `bancadas` (`id_bancada`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_exames_Materiais1` FOREIGN KEY (`material_exame`) REFERENCES `materiais` (`id_material`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_exames_Metodos1` FOREIGN KEY (`metodo_exame`) REFERENCES `metodos` (`id_metodo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.exames: ~0 rows (approximately)
/*!40000 ALTER TABLE `exames` DISABLE KEYS */;
INSERT INTO `exames` (`id_exame`, `nome_exame`, `sexo_exame`, `requisicao_exame`, `tempo_exame`, `material_exame`, `metodo_exame`, `bancada_exame`, `descricao_exame`, `preco_exame`) VALUES
	(1, 'Colesterol Total', 'Masculino', 'sim', 5, 1, 1, 2, 'Descrição', 10.00),
	(2, 'Glicose', 'Ambos', 'nao', 24, 2, 2, 3, 'Medida de Glicose', 15.00),
	(3, 'ghfk', 'Feminino', 'sim', 32, 2, 1, 3, 'Desc', 27.00);
/*!40000 ALTER TABLE `exames` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.guias
CREATE TABLE IF NOT EXISTS `guias` (
  `id_guia` int(11) NOT NULL AUTO_INCREMENT,
  `data_guia` datetime NOT NULL,
  `cliente_guia` int(11) NOT NULL,
  `medico_guia` int(11) DEFAULT NULL,
  `convenio_guia` int(11) DEFAULT NULL,
  `codigo_guia` varchar(45) NOT NULL,
  `senha_guia` varchar(45) NOT NULL,
  `precototal_guia` decimal(8,2) NOT NULL DEFAULT '0.00',
  `prazofinal_guia` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_guia`),
  KEY `fk_guias_clientes_idx` (`cliente_guia`),
  KEY `fk_guias_medicos1_idx` (`medico_guia`),
  KEY `fk_guias_convenios1_idx` (`convenio_guia`),
  CONSTRAINT `fk_guias_clientes` FOREIGN KEY (`cliente_guia`) REFERENCES `clientes` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_guias_convenios1` FOREIGN KEY (`convenio_guia`) REFERENCES `convenios` (`id_convenio`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_guias_medicos1` FOREIGN KEY (`medico_guia`) REFERENCES `medicos` (`id_medico`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.guias: ~6 rows (approximately)
/*!40000 ALTER TABLE `guias` DISABLE KEYS */;
INSERT INTO `guias` (`id_guia`, `data_guia`, `cliente_guia`, `medico_guia`, `convenio_guia`, `codigo_guia`, `senha_guia`, `precototal_guia`, `prazofinal_guia`) VALUES
	(1, '2021-07-20 17:04:05', 1, 1, 2, 'as', 'zx', 52.00, 32),
	(2, '2021-07-21 00:54:00', 8, 1, 2, '1262069650', '60fb8ef69a092', 10.00, 5),
	(3, '2021-06-28 00:58:00', 1, 1, 2, '537026644', '60fb8fd88a07b', 27.00, 32),
	(5, '2021-09-21 16:32:27', 15, 1, 2, '1541533904', '614a334bd398d', 42.00, 32),
	(6, '2021-09-21 19:50:51', 1, 1, 2, '609172662', '614a61cbcccb3', 62.00, 32),
	(7, '2021-09-21 20:03:38', 2, 1, 2, '786220226', '614a64ca58ed6', 25.00, 24);
/*!40000 ALTER TABLE `guias` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.guiasexames
CREATE TABLE IF NOT EXISTS `guiasexames` (
  `id_guiaexame` int(11) NOT NULL AUTO_INCREMENT,
  `exame_guiaexame` int(11) NOT NULL,
  `guia_guiaexame` int(11) NOT NULL,
  `preco_guiaexame` decimal(8,2) NOT NULL DEFAULT '0.00',
  `prazo_guiaexame` int(11) NOT NULL,
  PRIMARY KEY (`id_guiaexame`),
  KEY `fk_exames_has_guias_guias1_idx` (`guia_guiaexame`),
  KEY `fk_exames_has_guias_exames1_idx` (`exame_guiaexame`),
  CONSTRAINT `fk_exames_has_guias_exames1` FOREIGN KEY (`exame_guiaexame`) REFERENCES `exames` (`id_exame`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_exames_has_guias_guias1` FOREIGN KEY (`guia_guiaexame`) REFERENCES `guias` (`id_guia`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.guiasexames: ~13 rows (approximately)
/*!40000 ALTER TABLE `guiasexames` DISABLE KEYS */;
INSERT INTO `guiasexames` (`id_guiaexame`, `exame_guiaexame`, `guia_guiaexame`, `preco_guiaexame`, `prazo_guiaexame`) VALUES
	(4, 2, 1, 15.00, 24),
	(6, 1, 1, 10.00, 5),
	(8, 3, 1, 27.00, 32),
	(11, 3, 3, 27.00, 32),
	(12, 2, 5, 15.00, 24),
	(14, 2, 6, 15.00, 24),
	(15, 1, 6, 10.00, 5),
	(16, 3, 6, 27.00, 32),
	(17, 2, 7, 15.00, 24),
	(18, 1, 7, 10.00, 5),
	(19, 1, 6, 10.00, 5),
	(21, 3, 5, 27.00, 32);
/*!40000 ALTER TABLE `guiasexames` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.materiais
CREATE TABLE IF NOT EXISTS `materiais` (
  `id_material` int(11) NOT NULL AUTO_INCREMENT,
  `material` varchar(80) NOT NULL,
  PRIMARY KEY (`id_material`),
  UNIQUE KEY `ValorDeReferencia_UNIQUE` (`material`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.materiais: ~2 rows (approximately)
/*!40000 ALTER TABLE `materiais` DISABLE KEYS */;
INSERT INTO `materiais` (`id_material`, `material`) VALUES
	(1, 'material'),
	(2, 'Plasma');
/*!40000 ALTER TABLE `materiais` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.medicos
CREATE TABLE IF NOT EXISTS `medicos` (
  `id_medico` int(11) NOT NULL AUTO_INCREMENT,
  `nome_medico` varchar(45) NOT NULL,
  `crm_medico` varchar(45) DEFAULT NULL,
  `telefone_medico` varchar(45) NOT NULL,
  `especialidade_medico` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_medico`),
  UNIQUE KEY `telefone_medico_UNIQUE` (`telefone_medico`),
  KEY `fk_medicos_Especialidades1_idx` (`especialidade_medico`),
  CONSTRAINT `fk_medicos_Especialidades1` FOREIGN KEY (`especialidade_medico`) REFERENCES `especialidades` (`id_especialidade`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.medicos: ~1 rows (approximately)
/*!40000 ALTER TABLE `medicos` DISABLE KEYS */;
INSERT INTO `medicos` (`id_medico`, `nome_medico`, `crm_medico`, `telefone_medico`, `especialidade_medico`) VALUES
	(1, 'Nome', '40028922', '22982004', 1);
/*!40000 ALTER TABLE `medicos` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.metodos
CREATE TABLE IF NOT EXISTS `metodos` (
  `id_metodo` int(11) NOT NULL AUTO_INCREMENT,
  `metodo` varchar(80) NOT NULL,
  PRIMARY KEY (`id_metodo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.metodos: ~1 rows (approximately)
/*!40000 ALTER TABLE `metodos` DISABLE KEYS */;
INSERT INTO `metodos` (`id_metodo`, `metodo`) VALUES
	(1, 'Metodo'),
	(2, 'Colorimétrico');
/*!40000 ALTER TABLE `metodos` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.resultados
CREATE TABLE IF NOT EXISTS `resultados` (
  `id_resultado` int(11) NOT NULL AUTO_INCREMENT,
  `data_resultado` date NOT NULL,
  `guia_resultado` int(11) NOT NULL,
  `responsavel_resultado` int(11) NOT NULL,
  `resultado` varchar(45) DEFAULT NULL,
  `laudo_resultado` text,
  `observacao_resultado` text,
  PRIMARY KEY (`id_resultado`),
  KEY `fk_resultados_exames_guias1_idx` (`guia_resultado`),
  KEY `FK_resultados_usuarios` (`responsavel_resultado`),
  CONSTRAINT `FK_resultados_usuarios` FOREIGN KEY (`responsavel_resultado`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_resultados_exames_guias1` FOREIGN KEY (`guia_resultado`) REFERENCES `guiasexames` (`id_guiaexame`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.resultados: ~4 rows (approximately)
/*!40000 ALTER TABLE `resultados` DISABLE KEYS */;
INSERT INTO `resultados` (`id_resultado`, `data_resultado`, `guia_resultado`, `responsavel_resultado`, `resultado`, `laudo_resultado`, `observacao_resultado`) VALUES
	(4, '2021-09-17', 6, 1, '15', NULL, NULL),
	(5, '2021-09-21', 12, 1, 'A', 'A', 'A'),
	(6, '2021-09-25', 18, 2, '120', 'qweqweqeqw', 'dasdasdasd'),
	(7, '2021-09-25', 17, 2, '110', 'Teste', 'teste');
/*!40000 ALTER TABLE `resultados` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.unidadesmedida
CREATE TABLE IF NOT EXISTS `unidadesmedida` (
  `id_unidademedida` int(11) NOT NULL AUTO_INCREMENT,
  `unidademedida` varchar(80) NOT NULL,
  PRIMARY KEY (`id_unidademedida`),
  UNIQUE KEY `ValorDeReferencia_UNIQUE` (`unidademedida`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.unidadesmedida: ~1 rows (approximately)
/*!40000 ALTER TABLE `unidadesmedida` DISABLE KEYS */;
INSERT INTO `unidadesmedida` (`id_unidademedida`, `unidademedida`) VALUES
	(1, '%');
/*!40000 ALTER TABLE `unidadesmedida` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome_usuario` varchar(45) NOT NULL,
  `cpf_usuario` varchar(45) NOT NULL,
  `rg_usuario` varchar(45) DEFAULT NULL,
  `telefone_usuario` varchar(45) DEFAULT NULL,
  `celular_usuario` varchar(45) DEFAULT NULL,
  `endereco_usuario` varchar(45) NOT NULL,
  `cidade_usuario` varchar(45) NOT NULL,
  `cep_usuario` varchar(45) NOT NULL,
  `user_usuario` varchar(45) NOT NULL,
  `senha_usuario` varchar(45) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `cpf_usuario_UNIQUE` (`cpf_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.usuarios: ~4 rows (approximately)
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`id_usuario`, `nome_usuario`, `cpf_usuario`, `rg_usuario`, `telefone_usuario`, `celular_usuario`, `endereco_usuario`, `cidade_usuario`, `cep_usuario`, `user_usuario`, `senha_usuario`) VALUES
	(1, 'a', '147.258.236-96', NULL, '(87) 9541-6546', NULL, 'a', '', '', 'a', 'b'),
	(2, 'Irene Wegrzyn', '111.111.111-11', NULL, '(11) 1111-1111', NULL, '1', '', '', 'user', 'senha'),
	(6, 'Teste', '222.222.222-22', NULL, '(11) 1111-1111', NULL, 'Endereço', '', '', 'teste', '2e6f9b0d5885b6010f9167787445617f553a735f'),
	(7, 'a', '111.111.111-78', '45.345.343-34', '(23) 1213-231_', '(12) 31321-3132', 'weqewqwe', 'qweq', '64684-321', 'u', 'a0f1490a20d0211c997b44bc357e1972deab8ae3');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

-- Dumping structure for table bd_tcc.valoresreferencia
CREATE TABLE IF NOT EXISTS `valoresreferencia` (
  `id_valorreferencia` int(11) NOT NULL AUTO_INCREMENT,
  `exame_valorreferencia` int(11) NOT NULL,
  `unidademedida_valorreferencia` int(11) NOT NULL,
  `valorreferencia` varchar(45) DEFAULT NULL,
  `valorreferencia_min` decimal(8,3) DEFAULT NULL,
  `valorreferencia_max` decimal(8,3) DEFAULT NULL,
  `idade_min` varchar(45) DEFAULT NULL,
  `idade_max` varchar(45) DEFAULT NULL,
  `sexo` varchar(45) NOT NULL,
  PRIMARY KEY (`id_valorreferencia`),
  KEY `fk_ValoresReferencia_exames1_idx` (`exame_valorreferencia`),
  KEY `FK_valoresreferencia_unidadesmedida` (`unidademedida_valorreferencia`),
  CONSTRAINT `FK_valoresreferencia_unidadesmedida` FOREIGN KEY (`unidademedida_valorreferencia`) REFERENCES `unidadesmedida` (`id_unidademedida`),
  CONSTRAINT `fk_ValoresReferencia_exames1` FOREIGN KEY (`exame_valorreferencia`) REFERENCES `exames` (`id_exame`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Dumping data for table bd_tcc.valoresreferencia: ~5 rows (approximately)
/*!40000 ALTER TABLE `valoresreferencia` DISABLE KEYS */;
INSERT INTO `valoresreferencia` (`id_valorreferencia`, `exame_valorreferencia`, `unidademedida_valorreferencia`, `valorreferencia`, `valorreferencia_min`, `valorreferencia_max`, `idade_min`, `idade_max`, `sexo`) VALUES
	(1, 1, 1, '150', NULL, NULL, NULL, NULL, 'Ambos'),
	(2, 1, 1, '15.45', NULL, NULL, '20', '30', 'Masculino'),
	(3, 2, 1, NULL, 150.000, 175.000, '15', NULL, 'Masculino'),
	(5, 3, 1, '120', NULL, NULL, '18', NULL, 'Masculino');
/*!40000 ALTER TABLE `valoresreferencia` ENABLE KEYS */;

-- Dumping structure for trigger bd_tcc.guiasexames_after_delete
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `guiasexames_after_delete` AFTER DELETE ON `guiasexames` FOR EACH ROW BEGIN
CALL atualiza_valor_total(OLD.guia_guiaexame);
CALL atualiza_prazo_final(OLD.guia_guiaexame);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger bd_tcc.guiasexames_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `guiasexames_after_insert` AFTER INSERT ON `guiasexames` FOR EACH ROW BEGIN
CALL atualiza_valor_total(NEW.guia_guiaexame);
CALL atualiza_prazo_final(NEW.guia_guiaexame);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger bd_tcc.guiasexames_after_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `guiasexames_after_update` AFTER UPDATE ON `guiasexames` FOR EACH ROW BEGIN
CALL atualiza_valor_total(NEW.guia_guiaexame);
CALL atualiza_prazo_final(NEW.guia_guiaexame);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
