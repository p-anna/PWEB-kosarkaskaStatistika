-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `mydb` ;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
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
  `nationality` VARCHAR(35) NULL,
  `playerPos` VARCHAR(10) NULL,
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
  `teamName` VARCHAR(30) NULL,
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
COMMENT = '			';


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
  PRIMARY KEY (`gameCode`, `season`),
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


-- -----------------------------------------------------
-- Table `mydb`.`PreostaleUtakmice`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`PreostaleUtakmice` ;

CREATE TABLE IF NOT EXISTS `mydb`.`PreostaleUtakmice` (
  `gameCode` INT NOT NULL,
  PRIMARY KEY (`gameCode`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `mydb`.`PreostaleUtakmice`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (1);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (2);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (3);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (4);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (5);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (6);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (7);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (8);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (9);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (10);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (11);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (12);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (13);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (14);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (15);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (16);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (17);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (18);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (19);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (20);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (21);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (22);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (23);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (24);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (25);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (26);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (27);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (28);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (29);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (30);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (31);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (32);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (33);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (34);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (35);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (36);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (37);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (38);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (39);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (40);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (41);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (42);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (43);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (44);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (45);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (46);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (47);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (48);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (49);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (50);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (51);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (52);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (53);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (54);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (55);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (56);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (57);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (58);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (59);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (60);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (61);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (62);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (63);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (64);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (65);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (66);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (67);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (68);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (69);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (70);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (71);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (72);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (73);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (74);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (75);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (76);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (77);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (78);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (79);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (80);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (81);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (82);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (83);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (84);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (85);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (86);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (87);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (88);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (89);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (90);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (91);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (92);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (93);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (94);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (95);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (96);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (97);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (98);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (99);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (100);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (101);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (102);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (103);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (104);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (105);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (106);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (107);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (108);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (109);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (110);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (111);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (112);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (113);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (114);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (115);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (116);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (117);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (118);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (119);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (120);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (121);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (122);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (123);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (124);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (125);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (126);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (127);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (128);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (129);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (130);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (131);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (132);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (133);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (134);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (135);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (136);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (137);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (138);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (139);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (140);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (141);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (142);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (143);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (144);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (145);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (146);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (147);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (148);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (149);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (150);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (151);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (152);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (153);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (154);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (155);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (156);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (157);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (158);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (159);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (160);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (161);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (162);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (163);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (164);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (165);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (166);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (167);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (168);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (169);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (170);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (171);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (172);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (173);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (174);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (175);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (176);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (177);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (178);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (179);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (180);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (181);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (182);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (183);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (184);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (185);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (186);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (187);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (188);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (189);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (190);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (191);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (192);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (193);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (194);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (195);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (196);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (197);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (198);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (199);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (200);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (201);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (202);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (203);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (204);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (205);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (206);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (207);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (208);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (209);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (210);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (211);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (212);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (213);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (214);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (215);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (216);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (217);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (218);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (219);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (220);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (221);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (222);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (223);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (224);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (225);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (226);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (227);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (228);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (229);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (230);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (231);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (232);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (233);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (234);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (235);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (236);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (237);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (238);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (239);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (240);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (241);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (242);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (243);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (244);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (245);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (246);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (247);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (248);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (249);
INSERT INTO `mydb`.`PreostaleUtakmice` (`gameCode`) VALUES (250);

COMMIT;

