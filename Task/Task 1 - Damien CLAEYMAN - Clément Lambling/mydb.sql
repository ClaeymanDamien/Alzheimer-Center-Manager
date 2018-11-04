-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 29 oct. 2018 à 14:32
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `mydb`
--

-- --------------------------------------------------------

--
-- Structure de la table `tbl_item`
--

DROP TABLE IF EXISTS `tbl_item`;
CREATE TABLE IF NOT EXISTS `tbl_item` (
  `ID` int(11) NOT NULL,
  `ItemDesc` varchar(45) NOT NULL,
  `ItemPic` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_medicine`
--

DROP TABLE IF EXISTS `tbl_medicine`;
CREATE TABLE IF NOT EXISTS `tbl_medicine` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `MedDesc` varchar(45) NOT NULL,
  `Schedule` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_patient`
--

DROP TABLE IF EXISTS `tbl_patient`;
CREATE TABLE IF NOT EXISTS `tbl_patient` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FName` varchar(45) NOT NULL,
  `LName` varchar(45) NOT NULL,
  `RoomNb` varchar(45) NOT NULL,
  `Password` varchar(45) NOT NULL,
  `Address1` varchar(45) NOT NULL,
  `Address2` varchar(45) DEFAULT NULL,
  `PostalCode` int(11) NOT NULL,
  `GradeClassification` char(1) NOT NULL,
  `PatientImage` varchar(50) DEFAULT NULL,
  `NextOfKin` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_tbl_Patient_tbl_User1_idx` (`NextOfKin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_patient-item`
--

DROP TABLE IF EXISTS `tbl_patient-item`;
CREATE TABLE IF NOT EXISTS `tbl_patient-item` (
  `tbl_Item_ID` int(11) NOT NULL,
  `tbl_Patient_ID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  PRIMARY KEY (`tbl_Item_ID`),
  KEY `fk_tbl_Patient_has_tbl_Item_tbl_Item1_idx` (`tbl_Item_ID`),
  KEY `fk_tbl_Patient-Item_tbl_Patient1_idx` (`tbl_Patient_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_patient-medicine`
--

DROP TABLE IF EXISTS `tbl_patient-medicine`;
CREATE TABLE IF NOT EXISTS `tbl_patient-medicine` (
  `tbl_Medicine_ID` int(11) NOT NULL,
  `tbl_Patient_ID` int(11) NOT NULL,
  `Dosage` int(11) NOT NULL,
  PRIMARY KEY (`tbl_Medicine_ID`),
  KEY `fk_tbl_Patient_has_tbl_Medicine_tbl_Medicine1_idx` (`tbl_Medicine_ID`),
  KEY `fk_tbl_Patient-Medicine_tbl_Patient1_idx` (`tbl_Patient_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FName` varchar(45) NOT NULL,
  `LName` varchar(45) NOT NULL,
  `Address1` varchar(45) NOT NULL,
  `Address2` varchar(45) DEFAULT NULL,
  `PostalCode` int(11) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `CellNum` varchar(45) DEFAULT NULL,
  `Password` varchar(45) NOT NULL,
  `UserImage` varchar(50) DEFAULT NULL,
  `Status` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `tbl_patient`
--
ALTER TABLE `tbl_patient`
  ADD CONSTRAINT `fk_tbl_Patient_tbl_User1` FOREIGN KEY (`NextOfKin`) REFERENCES `tbl_user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tbl_patient-item`
--
ALTER TABLE `tbl_patient-item`
  ADD CONSTRAINT `fk_tbl_Patient-Item_tbl_Patient1` FOREIGN KEY (`tbl_Patient_ID`) REFERENCES `tbl_patient` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_Patient_has_tbl_Item_tbl_Item1` FOREIGN KEY (`tbl_Item_ID`) REFERENCES `tbl_item` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tbl_patient-medicine`
--
ALTER TABLE `tbl_patient-medicine`
  ADD CONSTRAINT `fk_tbl_Patient-Medicine_tbl_Patient1` FOREIGN KEY (`tbl_Patient_ID`) REFERENCES `tbl_patient` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_Patient_has_tbl_Medicine_tbl_Medicine1` FOREIGN KEY (`tbl_Medicine_ID`) REFERENCES `tbl_medicine` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
