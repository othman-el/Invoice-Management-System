-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 24 juin 2025 à 08:56
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sgf`
--

-- --------------------------------------------------------

--
-- Structure de la table `bank`
--

DROP TABLE IF EXISTS `bank`;
CREATE TABLE IF NOT EXISTS `bank` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Date` date DEFAULT NULL,
  `Code_achat` text,
  `TOTAL_IN` decimal(10,3) DEFAULT NULL,
  `TVA` int DEFAULT NULL,
  `Observation` text,
  `Code_ref` text,
  `Cheque_N` text,
  `Reste_Caisse` decimal(10,3) DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_user_bank` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `bank`
--

INSERT INTO `bank` (`ID`, `Date`, `Code_achat`, `TOTAL_IN`, `TVA`, `Observation`, `Code_ref`, `Cheque_N`, `Reste_Caisse`, `user_id`) VALUES
(1, '1999-02-13', 'Animi sit esse ven', 29.000, NULL, 'Dolor eaque quasi ut', 'Provident nesciunt', 'Blanditiis dignissim', 0.000, 13),
(2, '2002-10-14', 'Ut et voluptas et ut', 37.000, NULL, 'Error optio recusan', 'Dolore nulla quos su', 'Dolore corrupti ips', 0.000, 16),
(3, '1976-07-21', 'Est itaque inventore', 1.000, NULL, 'Voluptas aliquam sus', 'Aliquip sunt non ut ', 'Voluptates assumenda', 0.000, 16);

-- --------------------------------------------------------

--
-- Structure de la table `charge_fix`
--

DROP TABLE IF EXISTS `charge_fix`;
CREATE TABLE IF NOT EXISTS `charge_fix` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `DESIGNATION` text,
  `Date_Achat` date DEFAULT NULL,
  `M` date DEFAULT NULL,
  `TOTAL_OUT` decimal(12,3) DEFAULT NULL,
  `Montant` decimal(12,3) DEFAULT NULL,
  `Code_REF` text,
  `Categorie` text,
  `user_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_user_bank` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `charge_fix`
--

INSERT INTO `charge_fix` (`ID`, `DESIGNATION`, `Date_Achat`, `M`, `TOTAL_OUT`, `Montant`, `Code_REF`, `Categorie`, `user_id`) VALUES
(1, 'Consequatur duis la', '1980-11-04', '0000-00-00', 39.000, 84.000, 'In lorem excepturi n', 'Eos molestiae ipsam ', 13),
(2, 'Dolorem ipsum rerum', '2001-06-16', '0000-00-00', 78.000, 80.000, 'Quidem ipsa eaque e', 'Laborum quis recusan', 16),
(3, 'Non magna labore exc', '1974-10-16', '0000-00-00', 20.000, 48.000, 'Vero veniam quas de', 'Ipsam ipsam nostrud ', 16);

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

DROP TABLE IF EXISTS `factures`;
CREATE TABLE IF NOT EXISTS `factures` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ClientID` int NOT NULL,
  `N_facture` varchar(100) NOT NULL,
  `type` enum('facture','bl','devis') DEFAULT 'facture',
  `TVA` decimal(5,2) DEFAULT '20.00',
  `Montant_Total_HT` decimal(10,2) DEFAULT '0.00',
  `Montant_Total_TTC` decimal(10,2) DEFAULT '0.00',
  `Date_Creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Conditions` text,
  `condition_re` text,
  `Datee` date DEFAULT NULL,
  `livraison` text,
  `user_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `N_facture` (`N_facture`),
  KEY `ClientID` (`ClientID`),
  KEY `fk_user_bank` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`ID`, `ClientID`, `N_facture`, `type`, `TVA`, `Montant_Total_HT`, `Montant_Total_TTC`, `Date_Creation`, `Conditions`, `condition_re`, `Datee`, `livraison`, `user_id`) VALUES
(1, 3, 'Voluptatibus aliquid', 'facture', 20.00, 7896.00, 9475.20, '2025-06-11 14:42:52', 'Facere molestiae atq', NULL, '2020-04-16', 'Proident ullamco su', 13),
(2, 5, 'Non id quo consequat', 'facture', 20.00, 1615.00, 1938.00, '2025-06-12 07:46:26', 'Esse at ea sed fugit', NULL, '1996-03-03', 'A omnis cillum est ', 16),
(3, 5, 'Omnis esse incididu', 'bl', 20.00, 5612.00, 6734.40, '2025-06-12 08:48:17', 'Voluptas exercitatio', '', '2009-07-10', 'Cupidatat nesciunt ', 16),
(4, 5, 'Veniam blanditiis m', 'devis', 20.00, 16.00, 19.20, '2025-06-12 08:48:35', 'Voluptate aut hic ea', 'Dolor pariatur Volu', '1988-12-19', 'Laboris ut et modi q', 16),
(5, 5, 'Exercitation est dol', 'bl', 20.00, 4941.00, 5929.20, '2025-06-12 08:51:36', 'Voluptate aut dolore', 'Corporis cumque quo ', '1977-07-13', 'Quisquam unde dolore', 16),
(6, 9, 'Mollit est qui incid', 'bl', 20.00, 3445.00, 4134.00, '2025-06-12 08:56:40', 'Velit vero est maxi', 'Mollitia et nemo nat', '2019-10-19', 'Corporis qui velit a', 16),
(7, 12, '12302', 'bl', 20.00, 300.00, 360.00, '2025-06-12 09:32:01', 'virment', '30 jour', '2025-06-16', '02020202', 8),
(8, 14, '25FA-42-002', 'facture', 20.00, 800.00, 960.00, '2025-06-13 13:38:19', 'virement', '30 jour ', '2025-06-15', '3 jour', 18);

-- --------------------------------------------------------

--
-- Structure de la table `facture_items`
--

DROP TABLE IF EXISTS `facture_items`;
CREATE TABLE IF NOT EXISTS `facture_items` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `FactureID` int NOT NULL,
  `Designation` text NOT NULL,
  `Quantite` int NOT NULL DEFAULT '1',
  `Prix_Unit` decimal(10,2) NOT NULL,
  `Montant_HT` decimal(10,2) NOT NULL,
  `ordre` int DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `FactureID` (`FactureID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `facture_items`
--

INSERT INTO `facture_items` (`ID`, `FactureID`, `Designation`, `Quantite`, `Prix_Unit`, `Montant_HT`, `ordre`) VALUES
(1, 1, 'Atque ut animi volu', 84, 94.00, 7896.00, 1),
(2, 2, 'Fugiat adipisci in a', 17, 95.00, 1615.00, 1),
(3, 3, 'Ratione eum ipsum qu', 61, 92.00, 5612.00, 1),
(4, 4, 'Sit sit doloremque e', 8, 2.00, 16.00, 1),
(5, 5, 'Eveniet adipisicing', 61, 81.00, 4941.00, 1),
(6, 6, 'Deserunt consequuntu', 53, 65.00, 3445.00, 1),
(7, 7, 'casque', 3, 100.00, 300.00, 1),
(8, 8, 'casque', 8, 100.00, 800.00, 1);

-- --------------------------------------------------------

--
-- Structure de la table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Date` date DEFAULT NULL,
  `Fournisseur` int DEFAULT NULL,
  `N_Facture` text,
  `Article` text,
  `Designation` text,
  `Qte` int DEFAULT NULL,
  `Montant_uHT` decimal(10,3) DEFAULT NULL,
  `Total_Uht` decimal(10,3) DEFAULT NULL,
  `TVA` int DEFAULT NULL,
  `TOTAL_TTC` decimal(10,3) DEFAULT NULL,
  `Date_c` date DEFAULT NULL,
  `N_Devis` text,
  `N_Facture_C` text,
  `Client` int DEFAULT NULL,
  `Code_client` text,
  `Mt_HT` decimal(10,3) DEFAULT NULL,
  `Mt_TTC` decimal(10,3) DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Fournisseur` (`Fournisseur`),
  KEY `Client` (`Client`),
  KEY `fk_user_bank` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `items`
--

INSERT INTO `items` (`ID`, `Date`, `Fournisseur`, `N_Facture`, `Article`, `Designation`, `Qte`, `Montant_uHT`, `Total_Uht`, `TVA`, `TOTAL_TTC`, `Date_c`, `N_Devis`, `N_Facture_C`, `Client`, `Code_client`, `Mt_HT`, `Mt_TTC`, `user_id`) VALUES
(1, '2019-04-28', 4, 'Repellendus Eligend', 'Molestiae voluptatem', 'Qui mollit alias fug', 44, 99.000, 4356.000, 20, 5227.200, '1974-11-13', 'Est dolor ut aut lib', 'Aut ipsa nesciunt ', 2, 'Ad numquam natus lab', 4356.000, 5227.200, 13),
(2, '1973-10-14', 11, 'Minima Nam vel aut i', 'Impedit minus numqu', 'Laudantium eveniet', 24, 40.000, 960.000, 20, 1152.000, '2019-02-28', 'Et enim necessitatib', 'Dolorem quas sed quo', 9, 'Cupiditate iste maio', 960.000, 1152.000, 16),
(3, '2018-02-22', 6, 'Nesciunt quidem mol', 'Voluptatum ullam ut ', 'Aut at ut itaque sin', 37, 10.000, 370.000, 20, 444.000, '1979-06-07', 'Mollitia non quam la', 'Quod sit nostrum quo', 5, 'Non quibusdam illum', 370.000, 444.000, 16),
(4, '1991-11-03', 6, 'Eius quibusdam maior', 'A sed neque voluptas', 'Adipisci quis proide', 91, 42.000, 3822.000, 20, 4586.400, '2004-10-22', 'Iste exercitationem ', 'Porro labore labore ', 7, 'Rerum deleniti modi ', 3822.000, 4586.400, 16);

-- --------------------------------------------------------

--
-- Structure de la table `liste_fourniseur_client`
--

DROP TABLE IF EXISTS `liste_fourniseur_client`;
CREATE TABLE IF NOT EXISTS `liste_fourniseur_client` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NameEntreprise` varchar(250) NOT NULL,
  `ICE` varchar(100) NOT NULL,
  `Adresse` varchar(250) NOT NULL,
  `Email` varchar(250) NOT NULL,
  `Contact` varchar(250) NOT NULL,
  `NumeroGSM` varchar(100) DEFAULT NULL,
  `NumeroFixe` varchar(100) DEFAULT NULL,
  `Activite` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Role` enum('Client','Fournisseur') DEFAULT NULL,
  `Code_de_reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_user_bank` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `liste_fourniseur_client`
--

INSERT INTO `liste_fourniseur_client` (`ID`, `NameEntreprise`, `ICE`, `Adresse`, `Email`, `Contact`, `NumeroGSM`, `NumeroFixe`, `Activite`, `Role`, `Code_de_reference`, `user_id`) VALUES
(3, 'Hashim Rich', 'Mollitia eveniet au', 'Atque labore proiden', 'fucideri@mailinator.com', 'Quisquam est aspern', '1', '1', 'Eos nisi culpa sunt', 'Client', '003', 13),
(2, 'Tad Estes', 'Quo illo aut esse es', 'Aliquip quo irure in', 'fazecas@mailinator.com', 'Velit ratione nisi a', '1', '1', 'Reiciendis vitae exc', 'Client', '002', 13),
(4, 'Drake Cleveland', 'Cupidatat eaque vero', 'Dolore dolor dolorem', 'kuquh@mailinator.com', 'Ullam qui voluptate ', '1', '1', 'Nesciunt optio off', 'Fournisseur', '004', 13),
(5, 'Isaac Baxter', 'Hic reprehenderit om', 'Eligendi cum quia te', 'higyka@mailinator.com', 'Adipisci proident e', '1', '1', 'Laudantium labore d', 'Client', '005', 16),
(6, 'Octavia Mercado', 'Fugit et duis fuga', 'Fugiat harum dolorum', 'qoqeko@mailinator.com', 'Illo quia rem corrup', '1', '1', 'Corporis temporibus ', 'Fournisseur', '006', 16),
(7, 'Shana Gray', 'Hic omnis proident ', 'Nulla eaque expedita', 'jukepani@mailinator.com', 'Voluptates aut non e', '1', '1', 'Magnam est sint eum', 'Client', '007', 16),
(8, 'Renee Pearson', 'Nam molestias ut qui', 'Dolor doloremque tem', 'tisul@mailinator.com', 'Eu id fugiat volup', '1', '1', 'Qui voluptatibus lab', 'Client', '008', 16),
(9, 'Tamara White', 'Dolor quia animi au', 'Sunt non aut beatae', 'budejut@mailinator.com', 'Nulla pariatur Dolo', '1', '1', 'Praesentium reprehen', 'Client', '009', 16),
(10, 'Ferris Ferrell', 'Aliquip non Nam est ', 'Quis eaque irure err', 'rimyjamud@mailinator.com', 'Ducimus veritatis n', '1', '1', 'Asperiores ut quasi ', 'Fournisseur', '010', 16),
(11, 'Kerry Macias', 'Accusamus alias duis', 'Qui laudantium qui ', 'kuhufi@mailinator.com', 'Nisi natus qui sit i', '1', '1', 'At officiis incidunt', 'Fournisseur', '011', 16),
(12, 'sg tech', '123456', 'technopark tanger', 'sg@contact.com', '0203030303', '303030303', '0', 'informatique', 'Client', '012', 8),
(13, 'detroit computer', '-----------', 'Jirari 3, Rue 20 Resid. Sabri, Tanger - 90000 Maroc ', 'commercial@detroitcomputer.ma', '+212 661-253540', '--------', '+212 539-957318 ', 'Vente, Maintenance de Matériel Informatique', 'Fournisseur', '013', 18),
(14, 'Sg Tech', '---------', 'Technopark / Tanger .109', 'imane.chbani@sgtech.tech', '-------', '-------', '-------', 'Services et conseil aux entreprises', 'Client', '014', 18);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `email`, `password`) VALUES
(1, 'hicham', 'el hmaydi', 'hicham@gmail.comm', 'HESOYAM'),
(2, 'Rosalyn Harrington', 'Orla Massey', 'lexorofib@mailinator.com', '$2y$10$Ay3iNGvdwzrW7hEQNbvIJuJh7t9YL/gUUdwJfsQm/K0q0uLZWyCA.'),
(3, 'Sandra Crosby', 'Lewis Hopper', 'cepibu@mailinator.com', '$2y$10$.FYKXXRWvA.NOjRTBEPjGuGsuKGM1QEGnKuPdqUHSpfCgo22IKW/W'),
(4, 'Stone George', 'Leilani Stewart', 'lanagufel@mailinator.com', '$2y$10$LycKVcnD7zq4BNcxiRmuFubU8PKfnmnWFY01Lp8pCotenKx9BQgw6'),
(5, 'Kristen Nunez', 'Indigo Dennis', 'vowabynagy@mailinator.com', '$2y$10$hxaDgenimu7rpaPtgsiIk.7.vb7romv4k0Xw9G/LaFCQdkOULm2yC'),
(6, 'Maryam Acevedo', 'Ginger Larson', 'sezytadu@mailinator.com', '$2y$10$QGMEbnXioHrFlCHKgvoqIekbaazimp/TqNgY4W8Zbf3CLwqWKop/W'),
(7, 'Astra Casey', 'Lareina Atkins', 'hylocef@mailinator.com', '$2y$10$D7cz6DkVtx8myNM7C3kdhO8HTbtGE3FtY/4HTWWZgGKRE4.3EQJ0a'),
(8, 'test', 'test', 'test@gmail.com', '$2y$10$1LsTNJP4KCYzLptCeigNNO5P3VQfenqoM3mJxivP72wOmrfAuSegm'),
(9, 'Rosalyn Ayers', 'Ferris Cervantes', 'tytyjohu@mailinator.com', '$2y$10$UTBtnlznd2Dq2zumk1Dj4esNx1XdMtFFYlm/Mt2KEazLVTVcvw.pe'),
(10, 'Isaiah Sweeney', 'Priscilla Warner', 'hycyduce@mailinator.com', '$2y$10$rNe5pGE4jqj9jeBFlX72meG50x26adxnrfLCb4dWf/hbATPoYB7xq'),
(11, 'Aline Mcconnell', 'Jenna Lucas', 'qohuhuku@mailinator.com', '$2y$10$8NhxkJf2EEecNxF2odP/gOE.yD7eZ2el.1SEC0QoIf.4sWztxYsdC'),
(12, 'Melanie Roberts', 'Kasimir Kennedy', 'nohivigeqo@mailinator.com', '$2y$10$yk7jVBLLHAdeE/8AqgUz1Osg.SWU26eJRo.nTwm3xB9Mz/rtGRjyS'),
(13, 'Gannon Myers', 'Hilda Graham', 'gihixobada@mailinator.com', '$2y$10$xqSx9v.MUbaKw4dqgk7bZOlHzYP610xc/meMSXhAoABofmvmVjdLS'),
(14, 'Donna Fischer', 'Vernon Rutledge', 'wybujyr@mailinator.com', '$2y$10$COhcCNPitszlBm1tZSKJw.Mz10HI.i6rZ1C5JWQoh3hULgX6aO7kK'),
(15, 'Maris Reilly', 'Shoshana Sosa', 'tiwihatev@mailinator.com', '$2y$10$l9KLuC07WdxdILI/D6DMHOXlgIXKrKLJBT6BRDvAJDi1NdlgD1Wse'),
(16, 'Wilma Lindsey', 'Sybill Acevedo', 'nemaqilux@mailinator.com', '$2y$10$xaa7P9L1ommsooaed.v8w.bruFRntiW5inwHWnECGoUTo4yIhuH4e'),
(17, 'Vielka Hurley', 'Yeo Hickman', 'wufypike@mailinator.com', '$2y$10$dl71.dOSDBrAL.msOi7Fnu54/fXhDSDxVVuNjqvASyR78E2z6kiLa'),
(18, 'othman', 'khrouf', 'otm@gmail.com', '$2y$10$gTheN9GBpWE7BRtjQLr.1OhU/S/I5oCzJeB2tMo5AicOi9GtKrdm.');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
