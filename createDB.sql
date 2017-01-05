SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Player`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Player` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Player` (
  `idPlayer` VARCHAR(10) NOT NULL,
  `playerName` VARCHAR(45) NOT NULL,
  `photoURL` VARCHAR(200) NULL,
  `height` INT NULL,
  `bornDate` DATE NULL,
  `nationality` VARCHAR(30) NULL,
  PRIMARY KEY (`idPlayer`),
  UNIQUE INDEX `idPlayer_UNIQUE` (`idPlayer` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Team`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Team` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Team` (
  `idTeam` VARCHAR(10) NOT NULL,
  `country` VARCHAR(30) NULL,
  `teamName` VARCHAR(40) NULL,
  PRIMARY KEY (`idTeam`),
  UNIQUE INDEX `idTeam_UNIQUE` (`idTeam` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Referee`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Referee` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Referee` (
  `idReferee` INT NOT NULL,
  `refereeName` VARCHAR(45) NOT NULL,
  `nationality` VARCHAR(45) NULL,
  PRIMARY KEY (`idReferee`),
  UNIQUE INDEX `idReferee_UNIQUE` (`idReferee` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Game`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Game` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Game` (
  `gameCode` INT NOT NULL,
  `season` INT NOT NULL,
  `round` INT NULL,
  `teamH` VARCHAR(10) NULL,
  `teamA` VARCHAR(10) NULL,
  `dateOfGame` DATE NULL,
  `ref1Id` INT NULL,
  `ref2Id` INT NULL,
  `ref3Id` INT NULL,
  `coachH` VARCHAR(30) NULL,
  `coachA` VARCHAR(30) NULL,
  `stadium` VARCHAR(45) NULL,
  `city` VARCHAR(12) NULL,
  `attendance` INT NULL,
  PRIMARY KEY (`gameCode`, `season`),
  INDEX `fk_Game_TeamH_idx` (`teamH` ASC),
  INDEX `fk_Game_Ref1_idx` (`ref1Id` ASC),
  INDEX `fk_Game_TeamA_idx` (`teamA` ASC),
  INDEX `fk_Game_Ref2_idx` (`ref2Id` ASC),
  INDEX `fk_Game_Ref3_idx` (`ref3Id` ASC),
  CONSTRAINT `fk_Game_TeamH`
    FOREIGN KEY (`teamH`)
    REFERENCES `mydb`.`Team` (`idTeam`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Game_Ref1`
    FOREIGN KEY (`ref1Id`)
    REFERENCES `mydb`.`Referee` (`idReferee`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Game_TeamA`
    FOREIGN KEY (`teamA`)
    REFERENCES `mydb`.`Team` (`idTeam`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Game_Ref2`
    FOREIGN KEY (`ref2Id`)
    REFERENCES `mydb`.`Referee` (`idReferee`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Game_Ref3`
    FOREIGN KEY (`ref3Id`)
    REFERENCES `mydb`.`Referee` (`idReferee`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = ' /* comment truncated */ /*			*/';


-- -----------------------------------------------------
-- Table `mydb`.`TeamStats`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`TeamStats` ;

CREATE TABLE IF NOT EXISTS `mydb`.`TeamStats` (
  `gameCode` INT NOT NULL,
  `season` INT NOT NULL,
  `teamId` VARCHAR(10) NOT NULL,
  `POS` INT NOT NULL DEFAULT 0,
  `PTS` INT NOT NULL DEFAULT 0,
  `2FGM` INT NOT NULL DEFAULT 0,
  `2FGA` INT NOT NULL DEFAULT 0,
  `3FGM` INT NOT NULL DEFAULT 0,
  `3FGA` INT NOT NULL DEFAULT 0,
  `FTM` INT NOT NULL DEFAULT 0,
  `FTA` INT NOT NULL DEFAULT 0,
  `OR2` INT NOT NULL DEFAULT 0,
  `DR` INT NOT NULL DEFAULT 0,
  `ASS` INT NOT NULL DEFAULT 0,
  `STL` INT NOT NULL DEFAULT 0,
  `TO2` INT NOT NULL DEFAULT 0,
  `BLK` INT NOT NULL DEFAULT 0,
  `BLKA` INT NOT NULL DEFAULT 0,
  `CM` INT NOT NULL DEFAULT 0,
  `RV` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`gameCode`, `season`, `teamId`),
  INDEX `fk_TeamStats_Team_idx` (`teamId` ASC),
  CONSTRAINT `fk_TeamStats_Game`
    FOREIGN KEY (`gameCode` , `season`)
    REFERENCES `mydb`.`Game` (`gameCode` , `season`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TeamStats_Team`
    FOREIGN KEY (`teamId`)
    REFERENCES `mydb`.`Team` (`idTeam`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`PlayerStats`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`PlayerStats` ;

CREATE TABLE IF NOT EXISTS `mydb`.`PlayerStats` (
  `playerId` VARCHAR(10) NOT NULL,
  `gameCode` INT NOT NULL,
  `season` INT NOT NULL,
  `teamId` VARCHAR(10) NOT NULL,
  `MIN2` INT NOT NULL DEFAULT 0,
  `PTS` INT NOT NULL DEFAULT 0,
  `2FGM` INT NOT NULL DEFAULT 0,
  `2FGA` INT NOT NULL DEFAULT 0,
  `3FGM` INT NOT NULL DEFAULT 0,
  `3FGA` INT NOT NULL DEFAULT 0,
  `FTM` INT NOT NULL DEFAULT 0,
  `FTA` INT NOT NULL DEFAULT 0,
  `OR2` INT NOT NULL DEFAULT 0,
  `DR` INT NOT NULL DEFAULT 0,
  `ASS` INT NOT NULL DEFAULT 0,
  `STL` INT NOT NULL DEFAULT 0,
  `TO2` INT NOT NULL DEFAULT 0,
  `BLK` INT NOT NULL DEFAULT 0,
  `BLGA` INT NOT NULL DEFAULT 0,
  `CM` INT NOT NULL DEFAULT 0,
  `RV` INT NOT NULL DEFAULT 0,
  `PLUSMINUS` INT NOT NULL,
  `POSS` INT NOT NULL,
  INDEX `fk_PlayerStats_Player_idx` (`playerId` ASC),
  INDEX `fk_PlayerStats_Team_idx` (`teamId` ASC),
  INDEX `fk_PlayerStats_Game_idx` (`gameCode` ASC, `season` ASC),
  PRIMARY KEY (`playerId`, `gameCode`, `season`),
  CONSTRAINT `fk_PlayerStats_Player`
    FOREIGN KEY (`playerId`)
    REFERENCES `mydb`.`Player` (`idPlayer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PlayerStats_Team`
    FOREIGN KEY (`teamId`)
    REFERENCES `mydb`.`Team` (`idTeam`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PlayerStats_Game`
    FOREIGN KEY (`gameCode` , `season`)
    REFERENCES `mydb`.`Game` (`gameCode` , `season`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Assists`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Assists` ;

CREATE TABLE IF NOT EXISTS `mydb`.`Assists` (
  `player1Id` VARCHAR(10) NOT NULL,
  `player2Id` VARCHAR(10) NOT NULL,
  `counter` INT NULL DEFAULT 0,
  `sezona` INT NULL,
  PRIMARY KEY (`player1Id`, `player2Id`),
  INDEX `fk_Assists_Player2_idx` (`player2Id` ASC),
  CONSTRAINT `fk_Assists_Player1`
    FOREIGN KEY (`player1Id`)
    REFERENCES `mydb`.`Player` (`idPlayer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Assists_Player2`
    FOREIGN KEY (`player2Id`)
    REFERENCES `mydb`.`Player` (`idPlayer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
