-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema bd_tcc
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema bd_tcc
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `bd_tcc` DEFAULT CHARACTER SET utf8 ;
USE `bd_tcc` ;

-- -----------------------------------------------------
-- Table `bd_tcc`.`clientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`clientes` (
  `id_cliente` INT NOT NULL AUTO_INCREMENT,
  `nome_cliente` VARCHAR(45) NOT NULL,
  `cidade_cliente` VARCHAR(45) NOT NULL,
  `endereço_cliente` VARCHAR(45) NULL,
  `cpf_cliente` VARCHAR(45) NULL,
  `telefone_cliente` VARCHAR(45) NULL,
  `sexo_cliente` ENUM('Masculino', 'Feminino') NOT NULL,
  `data_nascimento_cliente` DATE NOT NULL,
  `observacao_cliente` TEXT(200) NULL,
  `usuario_cliente` VARCHAR(45) NOT NULL,
  `senha_cliente` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE INDEX `cpf_cliente_UNIQUE` (`cpf_cliente` ASC) VISIBLE,
  UNIQUE INDEX `telefone_UNIQUE` (`telefone_cliente` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`convenios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`convenios` (
  `id_convenio` INT NOT NULL AUTO_INCREMENT,
  `nome_convenio` VARCHAR(45) NOT NULL,
  `cnpj_convenio` VARCHAR(45) NULL,
  `responsavel_convenio` VARCHAR(45) NULL,
  `telefone_convenio` VARCHAR(45) NULL,
  PRIMARY KEY (`id_convenio`),
  UNIQUE INDEX `cnpj_convenio_UNIQUE` (`cnpj_convenio` ASC) VISIBLE,
  UNIQUE INDEX `telefone_convenio_UNIQUE` (`telefone_convenio` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`Especialidades`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`Especialidades` (
  `id_especialidade` INT NOT NULL AUTO_INCREMENT,
  `especialidade` VARCHAR(80) NOT NULL,
  PRIMARY KEY (`id_especialidade`),
  UNIQUE INDEX `ValorDeReferencia_UNIQUE` (`especialidade` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`medicos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`medicos` (
  `id_medico` INT NOT NULL AUTO_INCREMENT,
  `nome_medico` VARCHAR(45) NOT NULL,
  `crm_medico` VARCHAR(45) NULL,
  `telefone_medico` VARCHAR(45) NOT NULL,
  `especialidade_medico` INT NOT NULL,
  PRIMARY KEY (`id_medico`),
  UNIQUE INDEX `telefone_medico_UNIQUE` (`telefone_medico` ASC) VISIBLE,
  INDEX `fk_medicos_Especialidades1_idx` (`especialidade_medico` ASC) VISIBLE,
  CONSTRAINT `fk_medicos_Especialidades1`
    FOREIGN KEY (`especialidade_medico`)
    REFERENCES `bd_tcc`.`Especialidades` (`id_especialidade`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`usuarios` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `nome_usuario` VARCHAR(45) NOT NULL,
  `cpf_usuario` VARCHAR(45) NOT NULL,
  `telefone_usuario` VARCHAR(45) NOT NULL,
  `endereco_usuario` VARCHAR(45) NOT NULL,
  `email_usuario` VARCHAR(45) NOT NULL,
  `senha_usuario` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE INDEX `cpf_usuario_UNIQUE` (`cpf_usuario` ASC) VISIBLE,
  UNIQUE INDEX `telefone_usuario_UNIQUE` (`telefone_usuario` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`guias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`guias` (
  `id_guia` INT NOT NULL AUTO_INCREMENT,
  `data_guia` DATETIME NOT NULL,
  `prazo_guia` DATE NOT NULL,
  `clientes_guia` INT NOT NULL,
  `medicos_guia` INT NULL,
  `convenios_guia` INT NULL,
  `codigo_guia` VARCHAR(45) NOT NULL,
  `senha_guia` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_guia`),
  INDEX `fk_guias_clientes_idx` (`clientes_guia` ASC) VISIBLE,
  INDEX `fk_guias_medicos1_idx` (`medicos_guia` ASC) VISIBLE,
  INDEX `fk_guias_convenios1_idx` (`convenios_guia` ASC) VISIBLE,
  CONSTRAINT `fk_guias_clientes`
    FOREIGN KEY (`clientes_guia`)
    REFERENCES `bd_tcc`.`clientes` (`id_cliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_guias_medicos1`
    FOREIGN KEY (`medicos_guia`)
    REFERENCES `bd_tcc`.`medicos` (`id_medico`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_guias_convenios1`
    FOREIGN KEY (`convenios_guia`)
    REFERENCES `bd_tcc`.`convenios` (`id_convenio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`UnidadesMedida`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`UnidadesMedida` (
  `id_unidademedida` INT NOT NULL AUTO_INCREMENT,
  `unidademedida` VARCHAR(80) NOT NULL,
  PRIMARY KEY (`id_unidademedida`),
  UNIQUE INDEX `ValorDeReferencia_UNIQUE` (`unidademedida` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`Materiais`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`Materiais` (
  `id_material` INT NOT NULL AUTO_INCREMENT,
  `material` VARCHAR(80) NOT NULL,
  PRIMARY KEY (`id_material`),
  UNIQUE INDEX `ValorDeReferencia_UNIQUE` (`material` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`Bancadas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`Bancadas` (
  `id_bancada` INT NOT NULL AUTO_INCREMENT,
  `bancada` VARCHAR(80) NOT NULL,
  PRIMARY KEY (`id_bancada`),
  UNIQUE INDEX `ValorDeReferencia_UNIQUE` (`bancada` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`Metodos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`Metodos` (
  `id_metodo` INT NOT NULL AUTO_INCREMENT,
  `metodo` VARCHAR(80) NOT NULL,
  PRIMARY KEY (`id_metodo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`exames`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`exames` (
  `id_exame` INT NOT NULL AUTO_INCREMENT,
  `nome_exame` VARCHAR(45) NOT NULL,
  `sexo_exame` SET('Feminino', 'Masculino') NOT NULL,
  `requisicao_exame` ENUM('sim', 'nao') NOT NULL,
  `tempo_exame` INT NOT NULL,
  `unidademedida_exame` INT NOT NULL,
  `material_exame` INT NOT NULL,
  `metodo_exame` INT NOT NULL,
  `bancada_exame` INT NOT NULL,
  `descricao_exame` TEXT(200) NULL,
  PRIMARY KEY (`id_exame`),
  INDEX `fk_exames_UnidadesMedida1_idx` (`unidademedida_exame` ASC) VISIBLE,
  INDEX `fk_exames_Materiais1_idx` (`material_exame` ASC) VISIBLE,
  INDEX `fk_exames_Bancadas1_idx` (`bancada_exame` ASC) VISIBLE,
  INDEX `fk_exames_Metodos1_idx` (`metodo_exame` ASC) VISIBLE,
  CONSTRAINT `fk_exames_UnidadesMedida1`
    FOREIGN KEY (`unidademedida_exame`)
    REFERENCES `bd_tcc`.`UnidadesMedida` (`id_unidademedida`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exames_Materiais1`
    FOREIGN KEY (`material_exame`)
    REFERENCES `bd_tcc`.`Materiais` (`id_material`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exames_Bancadas1`
    FOREIGN KEY (`bancada_exame`)
    REFERENCES `bd_tcc`.`Bancadas` (`id_bancada`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exames_Metodos1`
    FOREIGN KEY (`metodo_exame`)
    REFERENCES `bd_tcc`.`Metodos` (`id_metodo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`exames_guias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`exames_guias` (
  `id_exame_guia` INT NOT NULL AUTO_INCREMENT,
  `exames_id_exame` INT NOT NULL,
  `guias_id_guia` INT NOT NULL,
  PRIMARY KEY (`id_exame_guia`),
  INDEX `fk_exames_has_guias_guias1_idx` (`guias_id_guia` ASC) VISIBLE,
  INDEX `fk_exames_has_guias_exames1_idx` (`exames_id_exame` ASC) VISIBLE,
  CONSTRAINT `fk_exames_has_guias_exames1`
    FOREIGN KEY (`exames_id_exame`)
    REFERENCES `bd_tcc`.`exames` (`id_exame`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_exames_has_guias_guias1`
    FOREIGN KEY (`guias_id_guia`)
    REFERENCES `bd_tcc`.`guias` (`id_guia`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`resultados`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`resultados` (
  `id_resultado` INT NOT NULL AUTO_INCREMENT,
  `data_resultado` DATE NOT NULL,
  `guia_resultado` INT NOT NULL,
  `resultado` VARCHAR(45) NULL,
  `laudo_resultado` TEXT(200) NULL,
  PRIMARY KEY (`id_resultado`),
  INDEX `fk_resultados_exames_guias1_idx` (`guia_resultado` ASC) VISIBLE,
  CONSTRAINT `fk_resultados_exames_guias1`
    FOREIGN KEY (`guia_resultado`)
    REFERENCES `bd_tcc`.`exames_guias` (`id_exame_guia`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `bd_tcc`.`ValoresReferencia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd_tcc`.`ValoresReferencia` (
  `id_valorreferencia` INT NOT NULL AUTO_INCREMENT,
  `exames_valorreferencia` INT NOT NULL,
  `valorreferencia` VARCHAR(45) NULL,
  `valordereferencia_min` DECIMAL(8,5) NULL,
  `valordereferencia_max` DECIMAL(8,5) NULL,
  `idade_min` VARCHAR(45) NULL,
  `idade_max` VARCHAR(45) NULL,
  `sexo` VARCHAR(45) NULL,
  PRIMARY KEY (`id_valorreferencia`),
  INDEX `fk_ValoresReferencia_exames1_idx` (`exames_valorreferencia` ASC) VISIBLE,
  CONSTRAINT `fk_ValoresReferencia_exames1`
    FOREIGN KEY (`exames_valorreferencia`)
    REFERENCES `bd_tcc`.`exames` (`id_exame`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
