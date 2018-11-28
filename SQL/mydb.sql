-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2018 at 07:51 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item`
--

DROP TABLE IF EXISTS `tbl_item`;
CREATE TABLE IF NOT EXISTS `tbl_item` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `StockItem` int(11) NOT NULL,
  `ItemMinimum` int(11) NOT NULL,
  `ItemDesc` varchar(45) NOT NULL,
  `ItemPic` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_item`
--

INSERT INTO `tbl_item` (`ID`, `StockItem`, `ItemMinimum`, `ItemDesc`, `ItemPic`) VALUES
(1, 101, 3, 'Towel', 'images/item/1.png'),
(2, 45, 1, 'Bed', 'images/item/2.jpg'),
(3, 49, 1, 'Table', 'images/item/3.jpg'),
(4, 35, 1, 'TV', 'images/item/4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_medicine`
--

DROP TABLE IF EXISTS `tbl_medicine`;
CREATE TABLE IF NOT EXISTS `tbl_medicine` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `MedDesc` varchar(45) NOT NULL,
  `Schedule` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Dumping data for table `tbl_medicine`
--

INSERT INTO `tbl_medicine` (`ID`, `MedDesc`, `Schedule`) VALUES
(1, 'Doliprane\r\n', '3 fois par jours avec 4h minimum d''interval'),
(2, 'Smecta', 'Est vraiment pas bon.\r\nA prendre avec du cour'),
(3, 'Morphine', 'Only when really needed.'),
(5, 'Sleeping pills', 'One every night, another if the patient still'),
(6, 'Antidepressant', 'Follow the psychologist''s instructions');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patient`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Dumping data for table `tbl_patient`
--

INSERT INTO `tbl_patient` (`ID`, `FName`, `LName`, `RoomNb`, `Password`, `Address1`, `Address2`, `PostalCode`, `GradeClassification`, `PatientImage`, `NextOfKin`) VALUES
(3, 'Victor', 'Lamy', '102', 'e1553510fed1991704d85ba82cc2750de6978109', '3 Road', '', 94000, 'A', 'image.png', 1),
(4, 'Luis', 'myla', '103', 'e1553510fed1991704d85ba82cc2750de6978109', '4 Road', '', 94300, 'B', 'image.png', 4),
(5, 'Florent', 'Petrol', '104', 'e1553510fed1991704d85ba82cc2750de6978109', '5 Road', '', 93700, 'A', 'image.png', 37),
(6, 'Martin', 'Comabrieux', '105', 'e1553510fed1991704d85ba82cc2750de6978109', '6 Road', '', 40200, 'C', 'image.png', 21),
(7, 'Jeanne', 'Vitel', '106', 'e1553510fed1991704d85ba82cc2750de6978109', '7 Road', '', 21340, 'C', 'image.png', 7),
(9, 'Laurine', 'Masotti', '108', 'e1553510fed1991704d85ba82cc2750de6978109', '9 Road', '', 50400, 'A', 'image.png', 24),
(10, 'Geoffrey', 'Berland', '109', 'e1553510fed1991704d85ba82cc2750de6978109', '10 Road', '', 49224, 'B', 'image.png', 10),
(11, 'Rémi', 'Jurasick', '200', 'e1553510fed1991704d85ba82cc2750de6978109', '11 Road', '', 49200, 'C', 'image.png', 1),
(12, 'Guillaume', 'Pointel', '201', 'e1553510fed1991704d85ba82cc2750de6978109', '12 Road', '', 27800, 'B', 'image.png', 4),
(13, 'Emilie', 'Beaujeau', '204', 'e1553510fed1991704d85ba82cc2750de6978109', '13 Road', '', 12400, 'B', 'images/user/13.JPG', 13),
(14, 'Marine', 'Guarigua', '203', 'e1553510fed1991704d85ba82cc2750de6978109', '14 Road', '', 43242, 'B', 'image.png', 34),
(16, 'Adeline', 'Legentil', '203', 'e1553510fed1991704d85ba82cc2750de6978109', '16 Road', '', 52479, 'A', 'image.png', 16),
(17, 'Louanne', 'Lemoinde', '204', 'e1553510fed1991704d85ba82cc2750de6978109', '17 Road', '', 4940, 'A', 'image.png', 18),
(18, 'Lea', 'Darocha', '205', 'e1553510fed1991704d85ba82cc2750de6978109', '20 Road', '', 58400, 'C', 'image.png', 18),
(19, 'Eva', 'Navarro', '206', 'e1553510fed1991704d85ba82cc2750de6978109', '22 Road', '', 67895, 'C', 'image.png', 27),
(20, 'Franck', 'James', '207', 'e1553510fed1991704d85ba82cc2750de6978109', '21 Road', '', 69690, 'A', 'image.png', 21),
(21, 'Kevin', 'Rambour', '208', 'e1553510fed1991704d85ba82cc2750de6978109', '24 Road', '', 45986, 'B', 'image.png', 21),
(22, 'Adele', 'Laws', '209', 'e1553510fed1991704d85ba82cc2750de6978109', '18 Road', '', 90456, 'B', 'image.png', 7),
(23, 'Thomas', 'Malgrain', '301', 'e1553510fed1991704d85ba82cc2750de6978109', '19 Road', '', 42400, 'C', 'image.png', 34),
(24, 'Esteban', 'Maestro', '302', 'e1553510fed1991704d85ba82cc2750de6978109', '30 Road', '', 54500, 'A', 'images/user/24.JPG', 38),
(26, 'Mathilde', 'Colas', '304', 'e1553510fed1991704d85ba82cc2750de6978109', '28 Road', '', 63400, 'C', 'image.png', 34),
(27, 'Sarah', 'Brule', '305', 'e1553510fed1991704d85ba82cc2750de6978109', '27 Road', '', 55500, 'C', 'image.png', 19),
(28, 'Jerome', 'Julienne', '306', 'e1553510fed1991704d85ba82cc2750de6978109', '26 Road', '', 65000, 'C', 'image.png', 18),
(29, 'Ricardo', 'Vitorino', '307', 'e1553510fed1991704d85ba82cc2750de6978109', '25 Road', '', 69069, 'C', 'image.png', 30),
(30, 'Tiago', 'Mendoca', '308', 'e1553510fed1991704d85ba82cc2750de6978109', '31 Road', '', 76400, 'C', 'image.png', 18),
(33, 'z', 'z', '10', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '', '', 0, 'A', 'unknown', 23),
(34, 'Test', 'Krokor', '23', '356a192b7913b04c54574d18c28d46e6395428ab', 'Issou', 'Paris', 90700, 'A', 'images/user/.jpg', 20),
(35, 'Julie', 'Lawson', '34', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'Issou', 'Orsay', 78440, 'B', 'images/user/35.jpg', 38);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patient_item`
--

DROP TABLE IF EXISTS `tbl_patient_item`;
CREATE TABLE IF NOT EXISTS `tbl_patient_item` (
  `tbl_Item_ID` int(11) NOT NULL,
  `tbl_Patient_ID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  KEY `fk_tbl_Patient_has_tbl_Item_tbl_Item1_idx` (`tbl_Item_ID`),
  KEY `fk_tbl_Patient_Item_tbl_Patient1_idx` (`tbl_Patient_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_patient_item`
--

INSERT INTO `tbl_patient_item` (`tbl_Item_ID`, `tbl_Patient_ID`, `Quantity`) VALUES
(2, 10, 1),
(2, 6, 1),
(2, 5, 1),
(2, 35, 1),
(1, 35, 2),
(3, 35, 1),
(4, 35, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patient_medicine`
--

DROP TABLE IF EXISTS `tbl_patient_medicine`;
CREATE TABLE IF NOT EXISTS `tbl_patient_medicine` (
  `tbl_Medicine_ID` int(11) NOT NULL,
  `tbl_Patient_ID` int(11) NOT NULL,
  `Dosage` int(11) NOT NULL,
  KEY `fk_tbl_Patient_has_tbl_Medicine_tbl_Medicine1_idx` (`tbl_Medicine_ID`),
  KEY `fk_tbl_Patient_Medicine_tbl_Patient1_idx` (`tbl_Patient_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_patient_medicine`
--

INSERT INTO `tbl_patient_medicine` (`tbl_Medicine_ID`, `tbl_Patient_ID`, `Dosage`) VALUES
(1, 10, 3),
(2, 10, 10),
(1, 35, 3),
(2, 35, 10),
(1, 13, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`ID`, `FName`, `LName`, `Address1`, `Address2`, `PostalCode`, `Email`, `CellNum`, `Password`, `UserImage`, `Status`) VALUES
(1, 'Saad', 'Ike', '1 Road', '', 34600, 'saadi@gmail.com', '0678943001', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(2, 'Jeanne', 'Pic', '2 Road', '', 25090, 'jeannep@gmail.com', '0678943002', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'matron'),
(3, 'Clément', 'Teboul', '3 Road', '', 32700, 'clementt@gmail.com', '0678943003', 'Pe1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'admin'),
(4, 'Thomas', 'Malgrain', '4 Road', '', 54800, 'thomasm@gmail.com', '0678943004', 'Pe1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(5, 'Warren', 'Djedir', '5 Road', '', 78400, 'warrend@gmail.com', '0678943005', 'Pe1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'matron'),
(6, 'Clément', 'Lambling', '6 Road', '', 23500, 'clementl@gmail.com', '0678943006', 'Pe1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'admin'),
(7, 'Jean', 'Guyonnaud', '7 Road', '', 87500, 'jeang@gmail.com', '0678943007', 'Pe1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(9, 'Alicia', 'Schoutteten', '9 Road', '', 12800, 'alicias@gmail.com', '0678943009', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'admin'),
(10, 'Elisa', 'Droussent', '10 Road', '', 23400, 'elisad@gmail.com', '0678943010', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(11, 'Francis', 'Tranis', '11 Road', '', 11100, 'francist@gmail.com', '0678943011', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'matron'),
(12, 'Julie', 'Lawson', '12 Road', '', 22200, 'juliel@gmail.com', '0678943012', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'admin'),
(13, 'Marine', 'Garyga', '12B Road', '', 43500, 'd.claeyman@hotmail.fr', '0678943013', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(14, 'Olivier', 'Ducduc', '13 Road', '', 38700, 'olivierd@gmail.com', '0678943014', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'matron'),
(16, 'Valeria', 'Ollier', '2B Road', '', 23400, 'valeriao@gmail.com', '0678943016', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(17, 'Maeva', 'Gratien', '15 Road', '', 34500, 'maevag@gmail.com', '0678943017', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'matron'),
(18, 'Alexane', 'Meha', '20 Road', '', 45600, 'alexanem@gmail.com', '0678943018', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(19, 'Alexis', 'Ribata', '21 Road', '', 56700, 'alexisr@gmail.com', '0678943019', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(20, 'Victor', 'Lamy', '14 Road', '', 45600, 'victorl@gmail.com', '0678943022', 'e1553510fed1991704d85ba82cc2750de6978109', 'images/user/20.jpg', 'user'),
(21, 'Luis', 'myla', '16 Road', '', 34500, 'luism@gmail.com', '0678943033', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(22, 'Brice', 'Ligot', '15 Road', '', 98700, 'bricel@gmail.com', '0678943044', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'matron'),
(23, 'Nicolas', 'Brozzu', '14B Road', '', 87600, 'nicolasb@gmail.com', '0678943055', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'admin'),
(24, 'Charlotte', 'Bourgeois', '34 Road', '', 76500, 'charlotteb@gmail.com', '0678943066', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(26, 'Arnaud', 'Bouges', '29 Road', '', 54300, 'arnaudb@gmail.com', '0678943088', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'admin'),
(27, 'Florent', 'Bouriez', '28 Road', '', 43200, 'florentb@gmail.com', '0678943099', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(28, 'Antoine', 'Cichowicz', '25 Road', '', 32700, 'antoinec@gmail.com', '0678943065', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'matron'),
(29, 'Damien', 'Claeyman', '27 Road', '', 78440, 'claeymand@gmail.com', '0678943078', 'e1553510fed1991704d85ba82cc2750de6978109', 'images/user/29.JPG', 'admin'),
(30, 'Chiara', 'Stamile', '6B Road', '', 94700, 'chiaras@gmail.com', '0678943094', 'e1553510fed1991704d85ba82cc2750de6978109', 'image.png', 'user'),
(31, 'Wilhelm', 'Rothman', 'CPUT Cape Town', '', 6667, 'admin@rothman.za.bz', '0827741645', 'e1553510fed1991704d85ba82cc2750de6978109', 'images/user/31.jpg', 'admin'),
(34, 'Damien', 'Claeyman', '18 Rue Madeleine Caze', '14 bis rue Berthelot', 78440, 'damien.claeyman@efrei.net', '0668772252', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'unknown', 'user'),
(35, 'Alexis', 'Bibic', 'Gargenville tout en haut', 'Paris', 78440, 'alexisbibic@hotmail.com', '0607986545', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'images/user/35.jpg', 'user'),
(36, 'Jean', 'Michel', '', '', 0, 'jeanmich@gmail.com', '', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'unknown', 'user'),
(37, 'Clement', 'Lambling', '', '', 0, 'clem.lambling@hotmail.fr', '', '356a192b7913b04c54574d18c28d46e6395428ab', 'unknown', 'user'),
(38, 'Wilhelm', 'Rothman', 'CPUT Cape Town', 'Paris', 75000, 'user@rothman.za.bz', '0827741645', 'f3d36135968d1fc9a53298a3719b6668676d226c', 'images/user/38.jpg', 'user'),
(39, 'Wilhelm', 'Rothman', '182 main rd Seapoint, Cape Town', 'La Creuse', 654321, 'matron@rothman.za.bz', '0813740726', '58c09068200a273a01e2f5fcf9a2d55da28ba0ac', 'images/user/39.jpg', 'matron');

SET FOREIGN_KEY_CHECKS = 1;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_patient`
--
ALTER TABLE `tbl_patient`
  ADD CONSTRAINT `fk_tbl_Patient_tbl_User1` FOREIGN KEY (`NextOfKin`) REFERENCES `tbl_user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_patient_item`
--
ALTER TABLE `tbl_patient_item`
  ADD CONSTRAINT `fk_tbl_Patient_has_tbl_Item_tbl_Item1` FOREIGN KEY (`tbl_Item_ID`) REFERENCES `tbl_item` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_Patient_Item_tbl_Patient1` FOREIGN KEY (`tbl_Patient_ID`) REFERENCES `tbl_patient` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_patient_medicine`
--
ALTER TABLE `tbl_patient_medicine`
  ADD CONSTRAINT `fk_tbl_Patient_has_tbl_Medicine_tbl_Medicine1` FOREIGN KEY (`tbl_Medicine_ID`) REFERENCES `tbl_medicine` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_Patient_Medicine_tbl_Patient1` FOREIGN KEY (`tbl_Patient_ID`) REFERENCES `tbl_patient` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
