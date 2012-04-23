SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `ideapankki_dev` DEFAULT CHARACTER SET latin1 ;
USE `ideapankki_dev` ;

-- -----------------------------------------------------
-- Table `ideapankki_dev`.`Category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`Category` (
  `CategoryID` INT(11) NOT NULL AUTO_INCREMENT ,
  `Name` VARCHAR(45) NOT NULL ,
  `Description` VARCHAR(300) NULL DEFAULT NULL ,
  PRIMARY KEY (`CategoryID`) )
ENGINE = InnoDB
AUTO_INCREMENT = 39
DEFAULT CHARACTER SET = latin1
COMMENT = 'Free-form category info, for sorting the ideas.' ;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`User`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`User` (
  `UserID` INT(11) NOT NULL AUTO_INCREMENT ,
  `Name` VARCHAR(80) NOT NULL ,
  `Email` VARCHAR(80) NOT NULL ,
  `PwdHash` CHAR(32) NOT NULL ,
  `Locale` VARCHAR(10) NOT NULL DEFAULT 'en' ,
  `Company` VARCHAR(80) NULL DEFAULT NULL ,
  `CompanyAddress` VARCHAR(80) NULL DEFAULT NULL ,
  `JoinDate` DATETIME NOT NULL ,
  `SelectedTheme` VARCHAR(80) NULL DEFAULT NULL ,
  PRIMARY KEY (`UserID`) ,
  UNIQUE INDEX `Name` (`Name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 37
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'This is a registered user. PwdHash is set at a fixed 32 char' ;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`Idea`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`Idea` (
  `IdeaID` INT(11) NOT NULL AUTO_INCREMENT ,
  `Name` VARCHAR(80) NOT NULL ,
  `Description` VARCHAR(2000) NOT NULL ,
  `Version` INT(10) UNSIGNED NOT NULL ,
  `Status` VARCHAR(45) NOT NULL ,
  `Cost` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `AdditionalInfo` VARCHAR(2000) NULL DEFAULT NULL ,
  `BasedOn` INT(11) NULL DEFAULT NULL ,
  `RequestDate` DATE NOT NULL ,
  `AddingDate` DATETIME NULL DEFAULT NULL ,
  `Inventor` INT(11) NOT NULL ,
  `AcceptedDate` DATETIME NULL DEFAULT NULL ,
  `StatusLastEdited` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`IdeaID`) ,
  INDEX `fk_Idea_User1` (`Inventor` ASC) ,
  CONSTRAINT `fk_Idea_User1`
    FOREIGN KEY (`Inventor` )
    REFERENCES `ideapankki_dev`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 624
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Idea is the main table of the ideabank. Everything spins aro' ;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`Comment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`Comment` (
  `CommentID` INT(11) NOT NULL AUTO_INCREMENT ,
  `Text` VARCHAR(2000) NOT NULL ,
  `User_UserID` INT(11) NULL DEFAULT NULL ,
  `Idea_IdeaID` INT(11) NULL DEFAULT NULL ,
  `Date` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`CommentID`) ,
  INDEX `fk_Comment_User1` (`User_UserID` ASC) ,
  INDEX `fk_Comment_Idea1` (`Idea_IdeaID` ASC) ,
  CONSTRAINT `fk_Comment_Idea1`
    FOREIGN KEY (`Idea_IdeaID` )
    REFERENCES `ideapankki_dev`.`Idea` (`IdeaID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Comment_User1`
    FOREIGN KEY (`User_UserID` )
    REFERENCES `ideapankki_dev`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1584
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Users can comment on ideas.' ;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`Reference`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`Reference` (
  `ReferenceID` INT(11) NOT NULL ,
  `Name` VARCHAR(80) NOT NULL ,
  `Url` VARCHAR(200) NOT NULL ,
  `Description` VARCHAR(300) NULL DEFAULT NULL ,
  PRIMARY KEY (`ReferenceID`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'A link to some other idea.' ;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`Idea_has_Category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`Idea_has_Category` (
  `Idea_IdeaID` INT(11) NOT NULL ,
  `Category_CategoryID` INT(11) NULL DEFAULT NULL ,
  `Reference_ReferenceID` INT(11) NULL DEFAULT NULL ,
  INDEX `fk_Idea_has_Category_Category1` (`Category_CategoryID` ASC) ,
  INDEX `fk_Idea_has_Category_Idea1` (`Idea_IdeaID` ASC) ,
  INDEX `fk_Idea_has_Category_Reference1` (`Reference_ReferenceID` ASC) ,
  CONSTRAINT `fk_Idea_has_Category_Category1`
    FOREIGN KEY (`Category_CategoryID` )
    REFERENCES `ideapankki_dev`.`Category` (`CategoryID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Idea_has_Category_Idea1`
    FOREIGN KEY (`Idea_IdeaID` )
    REFERENCES `ideapankki_dev`.`Idea` (`IdeaID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Idea_has_Category_Reference1`
    FOREIGN KEY (`Reference_ReferenceID` )
    REFERENCES `ideapankki_dev`.`Reference` (`ReferenceID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'A child table for this many-many connection. Should be split' ;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`UserGroup`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`UserGroup` (
  `GroupID` INT(11) NOT NULL AUTO_INCREMENT ,
  `Name` VARCHAR(45) NOT NULL ,
  `Description` VARCHAR(300) NULL DEFAULT NULL ,
  PRIMARY KEY (`GroupID`) )
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'The groups determine the permissions of an user; each idea l' ;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`Idea_has_Group`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`Idea_has_Group` (
  `Idea_IdeaID` INT(11) NOT NULL ,
  `Group_GroupID` INT(11) NOT NULL ,
  `CanComment` TINYINT(1) NOT NULL DEFAULT '1' ,
  `CanView` TINYINT(1) NOT NULL DEFAULT '1' ,
  `CanEdit` TINYINT(1) NOT NULL DEFAULT '0' ,
  INDEX `fk_Idea_has_Group_Group1` (`Group_GroupID` ASC) ,
  INDEX `fk_Idea_has_Group_Idea1` (`Idea_IdeaID` ASC) ,
  CONSTRAINT `fk_Idea_has_Group_Group1`
    FOREIGN KEY (`Group_GroupID` )
    REFERENCES `ideapankki_dev`.`UserGroup` (`GroupID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Idea_has_Group_Idea1`
    FOREIGN KEY (`Idea_IdeaID` )
    REFERENCES `ideapankki_dev`.`Idea` (`IdeaID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`Idea_has_follower`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`Idea_has_follower` (
  `FollowerID` INT(11) NOT NULL ,
  `Followed_IdeaID` INT(11) NOT NULL ,
  `Last_CommentID` INT(11) NULL DEFAULT NULL ,
  INDEX `fk_Idea_has_follower_FollowerID` (`FollowerID` ASC) ,
  INDEX `fk_Idea_has_follower_Followed_IdeaID` (`Followed_IdeaID` ASC) ,
  CONSTRAINT `fk_Idea_has_follower_Followed_IdeaID`
    FOREIGN KEY (`Followed_IdeaID` )
    REFERENCES `ideapankki_dev`.`Idea` (`IdeaID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Idea_has_follower_FollowerID`
    FOREIGN KEY (`FollowerID` )
    REFERENCES `ideapankki_dev`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`Phonenumber`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`Phonenumber` (
  `PhonenumberID` INT(11) NOT NULL ,
  `Number` VARCHAR(25) NOT NULL ,
  `User_UserID` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`PhonenumberID`) ,
  INDEX `fk_Phonenumber_User1` (`User_UserID` ASC) ,
  CONSTRAINT `fk_Phonenumber_User1`
    FOREIGN KEY (`User_UserID` )
    REFERENCES `ideapankki_dev`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Phone numbers are separate instead of a field in User, so th' ;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`Rating`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`Rating` (
  `RatingID` INT(11) NOT NULL AUTO_INCREMENT ,
  `Rating` INT(10) NULL DEFAULT NULL ,
  `Idea_IdeaID` INT(11) NOT NULL ,
  `User_UserID` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`RatingID`) ,
  INDEX `fk_Rating_Idea` (`Idea_IdeaID` ASC) ,
  INDEX `fk_Rating_User1` (`User_UserID` ASC) ,
  CONSTRAINT `fk_Rating_Idea`
    FOREIGN KEY (`Idea_IdeaID` )
    REFERENCES `ideapankki_dev`.`Idea` (`IdeaID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Rating_User1`
    FOREIGN KEY (`User_UserID` )
    REFERENCES `ideapankki_dev`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 270
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'This is a up- or downvote by some user for this idea.' ;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`User_has_Group`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`User_has_Group` (
  `User_UserID` INT(11) NOT NULL ,
  `Group_GroupID` INT(11) NOT NULL ,
  INDEX `fk_User_has_Group_Group1` (`Group_GroupID` ASC) ,
  INDEX `fk_User_has_Group_User1` (`User_UserID` ASC) ,
  CONSTRAINT `fk_User_has_Group_Group1`
    FOREIGN KEY (`Group_GroupID` )
    REFERENCES `ideapankki_dev`.`UserGroup` (`GroupID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_Group_User1`
    FOREIGN KEY (`User_UserID` )
    REFERENCES `ideapankki_dev`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Child table connecting user to group(s).' ;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`User_has_follower`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`User_has_follower` (
  `StalkerID` INT(11) NOT NULL ,
  `StalkedID` INT(11) NOT NULL ,
  `Last_IdeaID` INT(11) NULL DEFAULT NULL ,
  INDEX `fk_User_has_follower_StalkerID` (`StalkerID` ASC) ,
  INDEX `fk_User_has_follower_StalkedID` (`StalkedID` ASC) ,
  CONSTRAINT `fk_User_has_follower_StalkedID`
    FOREIGN KEY (`StalkedID` )
    REFERENCES `ideapankki_dev`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_follower_StalkerID`
    FOREIGN KEY (`StalkerID` )
    REFERENCES `ideapankki_dev`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ideapankki_dev`.`Version`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ideapankki_dev`.`Version` (
  `VersionID` INT(11) NOT NULL AUTO_INCREMENT ,
  `IdeaID` INT(11) NOT NULL ,
  `Name` VARCHAR(80) NULL DEFAULT NULL ,
  `Description` VARCHAR(2000) NULL DEFAULT NULL ,
  `Version` INT(11) NULL DEFAULT NULL ,
  `Status` VARCHAR(45) NULL DEFAULT NULL ,
  `Cost` INT(11) NULL DEFAULT NULL ,
  `AdditionalInfo` VARCHAR(2000) NULL DEFAULT NULL ,
  `BasedOn` INT(11) NULL DEFAULT NULL ,
  `RequestDate` DATE NULL DEFAULT NULL ,
  `AddingDate` DATETIME NULL DEFAULT NULL ,
  `Inventor` INT(11) NOT NULL ,
  `AcceptedDate` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`VersionID`) ,
  INDEX `fk_IdeaID` (`IdeaID` ASC) ,
  INDEX `fk_Inventor` (`Inventor` ASC) ,
  CONSTRAINT `fk_IdeaID`
    FOREIGN KEY (`IdeaID` )
    REFERENCES `ideapankki_dev`.`Idea` (`IdeaID` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Inventor`
    FOREIGN KEY (`Inventor` )
    REFERENCES `ideapankki_dev`.`User` (`UserID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 108
DEFAULT CHARACTER SET = latin1, 
COMMENT = 'Old versions of an idea.' ;

insert into UserGroup (GroupID, Name) values (0, "admin");
insert into User (Name, Email, PwdHash, JoinDate) values ("admin", "admin@example.com", "5df9a2c16f9c42063ff99fb8ae5d95b6", now());
insert into User_has_Group (User_UserID, Group_GroupID) values ((select UserID from User where Name='admin'), 0);

update UserGroup set GroupID=0 where Name='admin';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
