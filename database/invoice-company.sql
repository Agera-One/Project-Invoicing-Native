-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema invoice_new
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema invoice_new
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `invoice_new` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `invoice_new` ;

-- -----------------------------------------------------
-- Table `invoice_new`.`company`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `invoice_new`.`company` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(320) NOT NULL,
  `phone` VARCHAR(15) NOT NULL,
  `business_entity` VARCHAR(255) NOT NULL,
  `sector` VARCHAR(255) NOT NULL,
  `website` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `country` VARCHAR(255) NOT NULL,
  `province` VARCHAR(255) NOT NULL,
  `city` VARCHAR(255) NOT NULL,
  `subdistrict` VARCHAR(255) NOT NULL,
  `address` TEXT NOT NULL,
  `logo` TEXT NOT NULL,
  `signature` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  UNIQUE INDEX `phone_UNIQUE` (`phone` ASC) VISIBLE,
  UNIQUE INDEX `website_url_UNIQUE` (`website` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `invoice_new`.`pic`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `invoice_new`.`pic` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `phone` CHAR(15) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NOT NULL,
  `email` VARCHAR(320) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 'active',
  `position` VARCHAR(255) NOT NULL,
  `company_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `phone` (`phone` ASC) VISIBLE,
  UNIQUE INDEX `email` (`email` ASC) VISIBLE,
  INDEX `fk_pic_company1_idx` (`company_id` ASC) VISIBLE,
  CONSTRAINT `fk_pic_company1`
    FOREIGN KEY (`company_id`)
    REFERENCES `invoice_new`.`company` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `invoice_new`.`customer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `invoice_new`.`customer` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `customer_code` CHAR(14) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(320) NOT NULL,
  `phone` CHAR(20) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NOT NULL,
  `address` TEXT NOT NULL,
  `company_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email` (`email` ASC) VISIBLE,
  UNIQUE INDEX `phone` (`phone` ASC) VISIBLE,
  UNIQUE INDEX `customer_code` (`customer_code` ASC) VISIBLE,
  INDEX `fk_customer_company1_idx` (`company_id` ASC) VISIBLE,
  CONSTRAINT `fk_customer_company1`
    FOREIGN KEY (`company_id`)
    REFERENCES `invoice_new`.`company` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 57
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `invoice_new`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `invoice_new`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NOT NULL,
  `email` VARCHAR(320) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `company_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_company1_idx` (`company_id` ASC) VISIBLE,
  CONSTRAINT `fk_user_company1`
    FOREIGN KEY (`company_id`)
    REFERENCES `invoice_new`.`company` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `invoice_new`.`invoice`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `invoice_new`.`invoice` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `invoice_code` CHAR(13) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NOT NULL,
  `date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `pic_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `created_by` INT NOT NULL,
  `company_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code_invoice` (`invoice_code` ASC) VISIBLE,
  INDEX `fk_invoice_user1_idx` (`created_by` ASC) VISIBLE,
  INDEX `fk_invoice_company_pic1_idx` (`pic_id` ASC) VISIBLE,
  INDEX `fk_invoice_customer1_idx` (`customer_id` ASC) VISIBLE,
  INDEX `fk_invoice_company1_idx` (`company_id` ASC) VISIBLE,
  CONSTRAINT `fk_invoice_company_pic1`
    FOREIGN KEY (`pic_id`)
    REFERENCES `invoice_new`.`pic` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_invoice_customer1`
    FOREIGN KEY (`customer_id`)
    REFERENCES `invoice_new`.`customer` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_invoice_user1`
    FOREIGN KEY (`created_by`)
    REFERENCES `invoice_new`.`user` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_invoice_company1`
    FOREIGN KEY (`company_id`)
    REFERENCES `invoice_new`.`company` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 86
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `invoice_new`.`item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `invoice_new`.`item` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ref_no` VARCHAR(13) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `price` BIGINT NOT NULL,
  `company_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `ref_no` (`ref_no` ASC) VISIBLE,
  INDEX `fk_item_company1_idx` (`company_id` ASC) VISIBLE,
  CONSTRAINT `fk_item_company1`
    FOREIGN KEY (`company_id`)
    REFERENCES `invoice_new`.`company` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 35
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `invoice_new`.`payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `invoice_new`.`payment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `payment_code` CHAR(13) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NOT NULL,
  `date` DATE NOT NULL,
  `amount` BIGINT NOT NULL,
  `invoice_id` INT NOT NULL,
  `created_by` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_payment_user1_idx` (`created_by` ASC) VISIBLE,
  INDEX `fk_payment_invoice1_idx` (`invoice_id` ASC) VISIBLE,
  CONSTRAINT `fk_payment_invoice1`
    FOREIGN KEY (`invoice_id`)
    REFERENCES `invoice_new`.`invoice` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_payment_user1`
    FOREIGN KEY (`created_by`)
    REFERENCES `invoice_new`.`user` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 29
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `invoice_new`.`invoice_detail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `invoice_new`.`invoice_detail` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `unit_price` INT NOT NULL,
  `quantity` INT NOT NULL,
  `amount` BIGINT NOT NULL,
  `invoice_id` INT NOT NULL,
  `item_id` INT NOT NULL,
  INDEX `fk_invoice_detail_invoice1_idx` (`invoice_id` ASC) VISIBLE,
  INDEX `fk_invoice_detail_item1_idx` (`item_id` ASC) VISIBLE,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  CONSTRAINT `fk_invoice_detail_invoice1`
    FOREIGN KEY (`invoice_id`)
    REFERENCES `invoice_new`.`invoice` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_detail_item1`
    FOREIGN KEY (`item_id`)
    REFERENCES `invoice_new`.`item` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
