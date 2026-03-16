CREATE DATABASE materiel_m2l;
USE materiel_m2l;

CREATE TABLE type (
id_type INT PRIMARY KEY AUTO_INCREMENT,
libelle VARCHAR(50) NOT NULL
);

CREATE TABLE materiel_m2l(
ID INT PRIMARY KEY,
Nom VARCHAR(30),
Annee INT,
Details VARCHAR(50),
Type VARCHAR(20),
Appartenance VARCHAR(30)
);
INSERT INTO type (libelle) VALUES
('PC'),
('Écran'),
('CPU'),
('RAM'),
('Disque'),
('GPU'),
('Carte réseau'),
('OS'),
('Batterie');

Insert into MATERIEL_M2L(ID, Nom, Année, Détails, Type, Appartenance) VALUES
(1, 'PC 1 – Unité centrale' ,2016,'','PC',''), 
(2, 'PC 2 – Unité centrale' ,2017,'','PC',''), 
(3, 'PC 3 – Portable' ,2015,'	Inspiron 15-3558','PC',''), 
(4, 'Écran A' ,2012,'HP LA1951g – 19’’ – 1280×1024 – 60 Hz','Ecran',''), 
(5, 'Écran B	' ,2010,'Dell E178FP – 17’’ – 1280×1024','Ecran',''),
(6, 'Écran C' ,2009,'Samsung 933SN – 18.5’’ – 1366×768','Ecran',''),
(10, 'CPU PC1' ,2016,'Intel Core i3-6100','CPU','PC 1 – Unité centrale'),
(11, 'RAM PC1' ,2016,'4 Go DDR4 (1×4 Go)','RAM','PC 1 – Unité centrale'),
(12, 'Disque PC1' ,2016,'HDD Seagate 500 Go','Disque','PC 1 – Unité centrale'),
(13, 'GPU PC1' ,2016,'Intel HD 530','GPU','PC 1 – Unité centrale'),
(14, 'Carte réseau PC1' ,2016,'1 Gbps','Carte réseau','PC 1 – Unité centrale'),
(15, 'OS PC1' ,2016,'Windows 10 Pro','OS','PC 1 – Unité centrale'),
(20, 'CPU PC2' ,2017,'Intel Core i5-7500','CPU','PC 2 – Unité centrale'),
(21, 'RAM PC2' ,2017,'8 Go DDR4 (2×4 Go)','RAM','PC 2 – Unité centrale'),
(22, 'Disque PC2' ,2017,'SSD A400 240 Go','Disque','PC 2 – Unité centrale'),
(23, 'GPU PC2' ,2017,'Intel HD 630','GPU','PC 2 – Unité centrale'),
(24, 'Carte réseau PC2' ,2017,'1 Gbps','Carte réseau','PC 2 – Unité centrale'),
(25, 'OS PC2' ,2017,'Pas d’OS','OS','PC 2 – Unité centrale'),
(30, 'CPU PC3' ,2015,'Intel Core i3-5005U','CPU','PC 3 – Portable'),
(31, 'RAM PC3' ,2016,'4 Go DDR3L','RAM','PC 3 – Portable'),
(32, 'Disque PC3' ,2015,'HDD WD Blue 500 Go','Disque','PC 3 – Portable'),
(33, 'Batterie PC3' ,2016,'usée (≈ 40 min)','Batterie','PC 3 – Portable'),
(34, 'OS PC3' ,2015,'Windows 10 Pro','OS','PC 3 – Portable');

