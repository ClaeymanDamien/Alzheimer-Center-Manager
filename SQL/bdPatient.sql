
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO `tbl_patient` (`ID`, `FName`, `LName`,`RoomNb`,`Password`,`NextOfKin`,`Address1`,`Address2`,`GradeClassification`,`PostalCode`,`PatientImage`) VALUES
(1, 'Clement', 'Lambling','100','e1553510fed1991704d85ba82cc2750de6978109',3,'1 Road','','A',78440,'image.png'),
(2, 'Damien', 'Claeyman','101','e1553510fed1991704d85ba82cc2750de6978109',2,'2 Road','','A',78900,'image.png'),
(3, 'Victor', 'Lamy','102','e1553510fed1991704d85ba82cc2750de6978109',1,'3 Road','','A',94000,'image.png'),
(4, 'Luis', 'myla','103','e1553510fed1991704d85ba82cc2750de6978109',4,'4 Road','','B',94300,'image.png'),
(5, 'Florent', 'Petrol','104','e1553510fed1991704d85ba82cc2750de6978109',5,'5 Road','','A',93700,'image.png'),
(6, 'Martin', 'Comabrieux','105','e1553510fed1991704d85ba82cc2750de6978109',6,'6 Road','','C',40200,'image.png'),
(7, 'Jeanne', 'Vitel','106','e1553510fed1991704d85ba82cc2750de6978109',7,'7 Road','','C',21340,'image.png'),
(8, 'Sam', 'Fleur','107','e1553510fed1991704d85ba82cc2750de6978109',8,'8 Road','','',15900,'image.png'),
(9, 'Laurine', 'Masotti','108','e1553510fed1991704d85ba82cc2750de6978109',9,'9 Road','','A',50400,'image.png'),
(10, 'Geoffrey', 'Berland','109','e1553510fed1991704d85ba82cc2750de6978109',10,'10 Road','','B',49224,'image.png'),
(11, 'Rémi', 'Jurasick','200','e1553510fed1991704d85ba82cc2750de6978109',11,'11 Road','','C',49200,'image.png'),
(12, 'Guillaume', 'Pointel','201','e1553510fed1991704d85ba82cc2750de6978109',12,'12 Road','','B',27800,'image.png'),
(13, 'Emilie', 'Beaujeau','202','e1553510fed1991704d85ba82cc2750de6978109',13,'13 Road','','B',12400,'image.png'),
(14, 'Marine', 'Guarigua','203','e1553510fed1991704d85ba82cc2750de6978109',14,'14 Road','','B',43242,'image.png'),
(15, 'Lison', 'Filois','204','e1553510fed1991704d85ba82cc2750de6978109',15,'15 Road','','B',32840,'image.png'),
(16, 'Adeline', 'Legentil','203','e1553510fed1991704d85ba82cc2750de6978109',16,'16 Road','','A',52479,'image.png'),
(17, 'Louanne', 'Lemoinde','204','e1553510fed1991704d85ba82cc2750de6978109',17,'17 Road','','A',04940,'image.png'),
(18, 'Lea', 'Darocha','205','e1553510fed1991704d85ba82cc2750de6978109',18,'20 Road','','C',58400,'image.png'),
(19, 'Eva', 'Navarro','206','e1553510fed1991704d85ba82cc2750de6978109',20,'22 Road','','C',67895,'image.png'),
(20, 'Franck', 'James','207','e1553510fed1991704d85ba82cc2750de6978109',22,'21 Road','','A',69690,'image.png'),
(21, 'Kevin', 'Rambour','208','e1553510fed1991704d85ba82cc2750de6978109',21,'24 Road','','B',45986,'image.png'),
(22, 'Adele', 'Laws','209','e1553510fed1991704d85ba82cc2750de6978109',23,'18 Road','','B',90456,'image.png'),
(23, 'Thomas', 'Malgrain','301','e1553510fed1991704d85ba82cc2750de6978109',34,'19 Road','','C',42400,'image.png'),
(24, 'Esteban', 'Maestro','302','e1553510fed1991704d85ba82cc2750de6978109',24,'30 Road','','A',54500,'image.png'),
(25, 'Warren', 'Bilboche','303','e1553510fed1991704d85ba82cc2750de6978109',25,'29 Road','','B',54300,'image.png'),
(26, 'Mathilde', 'Colas','304','e1553510fed1991704d85ba82cc2750de6978109',26,'28 Road','','C',63400,'image.png'),
(27, 'Sarah', 'Brule','305','e1553510fed1991704d85ba82cc2750de6978109',27,'27 Road','','C',55500,'image.png'),
(28, 'Jerome', 'Julienne','306','e1553510fed1991704d85ba82cc2750de6978109',28,'26 Road','','C',65000,'image.png'),
(29, 'Ricardo', 'Vitorino','307','e1553510fed1991704d85ba82cc2750de6978109',30,'25 Road','','C',69069,'image.png'),
(30, 'Tiago', 'Mendoca','308','e1553510fed1991704d85ba82cc2750de6978109',31,'31 Road','','C',76400,'image.png');

SET FOREIGN_KEY_CHECKS = 1;