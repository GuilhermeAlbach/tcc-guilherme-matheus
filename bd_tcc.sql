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

-- Data exporting was unselected.

-- Dumping structure for table bd_tcc.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nome_cliente` varchar(45) NOT NULL,
  `cidade_cliente` varchar(45) NOT NULL,
  `endereco_cliente` varchar(45) DEFAULT NULL,
  `cpf_cliente` varchar(45) DEFAULT NULL,
  `telefone_cliente` varchar(45) DEFAULT NULL,
  `celular_cliente` varchar(45) DEFAULT NULL,
  `sexo_cliente` enum('Masculino','Feminino') NOT NULL,
  `datanascimento_cliente` date NOT NULL,
  `observacao_cliente` text,
  `usuario_cliente` varchar(45) DEFAULT NULL,
  `senha_cliente` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `cpf_cliente_UNIQUE` (`cpf_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table bd_tcc.especialidades
CREATE TABLE IF NOT EXISTS `especialidades` (
  `id_especialidade` int(11) NOT NULL AUTO_INCREMENT,
  `especialidade` varchar(80) NOT NULL,
  PRIMARY KEY (`id_especialidade`),
  UNIQUE KEY `ValorDeReferencia_UNIQUE` (`especialidade`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

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

-- Data exporting was unselected.

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table bd_tcc.materiais
CREATE TABLE IF NOT EXISTS `materiais` (
  `id_material` int(11) NOT NULL AUTO_INCREMENT,
  `material` varchar(80) NOT NULL,
  PRIMARY KEY (`id_material`),
  UNIQUE KEY `ValorDeReferencia_UNIQUE` (`material`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

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

-- Data exporting was unselected.

-- Dumping structure for table bd_tcc.metodos
CREATE TABLE IF NOT EXISTS `metodos` (
  `id_metodo` int(11) NOT NULL AUTO_INCREMENT,
  `metodo` varchar(80) NOT NULL,
  PRIMARY KEY (`id_metodo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table bd_tcc.unidadesmedida
CREATE TABLE IF NOT EXISTS `unidadesmedida` (
  `id_unidademedida` int(11) NOT NULL AUTO_INCREMENT,
  `unidademedida` varchar(80) NOT NULL,
  PRIMARY KEY (`id_unidademedida`),
  UNIQUE KEY `ValorDeReferencia_UNIQUE` (`unidademedida`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table bd_tcc.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome_usuario` varchar(45) NOT NULL,
  `cpf_usuario` varchar(45) NOT NULL,
  `telefone_usuario` varchar(45) DEFAULT NULL,
  `celular_usuario` varchar(45) DEFAULT NULL,
  `endereco_usuario` varchar(45) NOT NULL,
  `user_usuario` varchar(45) NOT NULL,
  `senha_usuario` varchar(45) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `cpf_usuario_UNIQUE` (`cpf_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table bd_tcc.valoresreferencia
CREATE TABLE IF NOT EXISTS `valoresreferencia` (
  `id_valorreferencia` int(11) NOT NULL AUTO_INCREMENT,
  `exame_valorreferencia` int(11) NOT NULL,
  `unidademedida_valorreferencia` int(11) NOT NULL,
  `valorreferencia` varchar(45) DEFAULT NULL,
  `valorreferencia_min` decimal(8,5) DEFAULT NULL,
  `valorreferencia_max` decimal(8,5) DEFAULT NULL,
  `idade_min` varchar(45) DEFAULT NULL,
  `idade_max` varchar(45) DEFAULT NULL,
  `sexo` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_valorreferencia`),
  KEY `fk_ValoresReferencia_exames1_idx` (`exame_valorreferencia`),
  KEY `FK_valoresreferencia_unidadesmedida` (`unidademedida_valorreferencia`),
  CONSTRAINT `FK_valoresreferencia_unidadesmedida` FOREIGN KEY (`unidademedida_valorreferencia`) REFERENCES `unidadesmedida` (`id_unidademedida`),
  CONSTRAINT `fk_ValoresReferencia_exames1` FOREIGN KEY (`exame_valorreferencia`) REFERENCES `exames` (`id_exame`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

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
