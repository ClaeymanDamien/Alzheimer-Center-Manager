SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO `tbl_patient` (`ID`, `FName`, `LName`, `RoomNb`, `Password`, `Address1`, `Address2`, `PostalCode`, `GradeClassification`, `PatientImage`, `NextOfKin`) VALUES
(1, 'Clement', 'Lambling', '100', 'e1553510fed1991704d85ba82cc2750de6978109', '1 Road', '', 78440, 'A', 'image.png', 32),
(2, 'Damien', 'Claeyman', '101', 'e1553510fed1991704d85ba82cc2750de6978109', '2 Road', '', 78900, 'A', 'image.png', 32),
(3, 'Victor', 'Lamy', '102', 'e1553510fed1991704d85ba82cc2750de6978109', '3 Road', '', 94000, 'A', 'image.png', 1),
(4, 'Luis', 'myla', '103', 'e1553510fed1991704d85ba82cc2750de6978109', '4 Road', '', 94300, 'B', 'image.png', 4),
(5, 'Florent', 'Petrol', '104', 'e1553510fed1991704d85ba82cc2750de6978109', '5 Road', '', 93700, 'A', 'image.png', 5),
(6, 'Martin', 'Comabrieux', '105', 'e1553510fed1991704d85ba82cc2750de6978109', '6 Road', '', 40200, 'C', 'image.png', 6),
(7, 'Jeanne', 'Vitel', '106', 'e1553510fed1991704d85ba82cc2750de6978109', '7 Road', '', 21340, 'C', 'image.png', 7),
(8, 'Sam', 'Fleur', '107', 'e1553510fed1991704d85ba82cc2750de6978109', '8 Road', '', 15900, '', 'image.png', 8),
(9, 'Laurine', 'Masotti', '108', 'e1553510fed1991704d85ba82cc2750de6978109', '9 Road', '', 50400, 'A', 'image.png', 9),
(10, 'Geoffrey', 'Berland', '109', 'e1553510fed1991704d85ba82cc2750de6978109', '10 Road', '', 49224, 'B', 'image.png', 10),
(11, 'Rémi', 'Jurasick', '200', 'e1553510fed1991704d85ba82cc2750de6978109', '11 Road', '', 49200, 'C', 'image.png', 11),
(12, 'Guillaume', 'Pointel', '201', 'e1553510fed1991704d85ba82cc2750de6978109', '12 Road', '', 27800, 'B', 'image.png', 12),
(13, 'Emilie', 'Beaujeau', '204', 'e1553510fed1991704d85ba82cc2750de6978109', '13 Road', '', 12400, 'B', 'image.png', 13),
(14, 'Marine', 'Guarigua', '203', 'e1553510fed1991704d85ba82cc2750de6978109', '14 Road', '', 43242, 'B', 'image.png', 14),
(15, 'Lison', 'Filois', '204', 'e1553510fed1991704d85ba82cc2750de6978109', '15 Road', '', 32840, 'B', 'image.png', 15),
(16, 'Adeline', 'Legentil', '203', 'e1553510fed1991704d85ba82cc2750de6978109', '16 Road', '', 52479, 'A', 'image.png', 16),
(17, 'Louanne', 'Lemoinde', '204', 'e1553510fed1991704d85ba82cc2750de6978109', '17 Road', '', 4940, 'A', 'image.png', 17),
(18, 'Lea', 'Darocha', '205', 'e1553510fed1991704d85ba82cc2750de6978109', '20 Road', '', 58400, 'C', 'image.png', 18),
(19, 'Eva', 'Navarro', '206', 'e1553510fed1991704d85ba82cc2750de6978109', '22 Road', '', 67895, 'C', 'image.png', 20),
(20, 'Franck', 'James', '207', 'e1553510fed1991704d85ba82cc2750de6978109', '21 Road', '', 69690, 'A', 'image.png', 22),
(21, 'Kevin', 'Rambour', '208', 'e1553510fed1991704d85ba82cc2750de6978109', '24 Road', '', 45986, 'B', 'image.png', 21),
(22, 'Adele', 'Laws', '209', 'e1553510fed1991704d85ba82cc2750de6978109', '18 Road', '', 90456, 'B', 'image.png', 23),
(23, 'Thomas', 'Malgrain', '301', 'e1553510fed1991704d85ba82cc2750de6978109', '19 Road', '', 42400, 'C', 'image.png', 34),
(24, 'Esteban', 'Maestro', '302', 'e1553510fed1991704d85ba82cc2750de6978109', '30 Road', '', 54500, 'A', 'image.png', 24),
(25, 'Warren', 'Le plus fort', '303', 'e1553510fed1991704d85ba82cc2750de6978109', '29 Road', '', 54300, 'B', 'image.png', 25),
(26, 'Mathilde', 'Colas', '304', 'e1553510fed1991704d85ba82cc2750de6978109', '28 Road', '', 63400, 'C', 'image.png', 26),
(27, 'Sarah', 'Brule', '305', 'e1553510fed1991704d85ba82cc2750de6978109', '27 Road', '', 55500, 'C', 'image.png', 27),
(28, 'Jerome', 'Julienne', '306', 'e1553510fed1991704d85ba82cc2750de6978109', '26 Road', '', 65000, 'C', 'image.png', 28),
(29, 'Ricardo', 'Vitorino', '307', 'e1553510fed1991704d85ba82cc2750de6978109', '25 Road', '', 69069, 'C', 'image.png', 30),
(30, 'Tiago', 'Mendoca', '308', 'e1553510fed1991704d85ba82cc2750de6978109', '31 Road', '', 76400, 'C', 'image.png', 18);

SET FOREIGN_KEY_CHECKS = 1;