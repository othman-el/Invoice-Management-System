-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 04 juil. 2025 à 11:50
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `bank`
--

INSERT INTO `bank` (`ID`, `Date`, `Code_achat`, `TOTAL_IN`, `TVA`, `Observation`, `Code_ref`, `Cheque_N`, `Reste_Caisse`, `user_id`) VALUES
(1, '1999-02-13', 'Animi sit esse ven', 29.000, NULL, 'Dolor eaque quasi ut', 'Provident nesciunt', 'Blanditiis dignissim', 0.000, 13),
(2, '2002-10-14', 'Ut et voluptas et ut', 37.000, NULL, 'Error optio recusan', 'Dolore nulla quos su', 'Dolore corrupti ips', 0.000, 16),
(3, '1976-07-21', 'Est itaque inventore', 1.000, NULL, 'Voluptas aliquam sus', 'Aliquip sunt non ut ', 'Voluptates assumenda', 0.000, 16),
(4, '2019-09-02', 'Omnis velit voluptat', 72.000, NULL, 'Ea eum magna similiq', 'Architecto et pariat', 'Eos et in officiis ', 0.000, 19),
(5, '2015-11-02', 'Culpa similique opti', 32.000, NULL, 'Alias magna perferen', 'Sequi omnis mollit p', 'Dolor voluptatum sin', 0.000, 25);

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `charge_fix`
--

INSERT INTO `charge_fix` (`ID`, `DESIGNATION`, `Date_Achat`, `M`, `TOTAL_OUT`, `Montant`, `Code_REF`, `Categorie`, `user_id`) VALUES
(1, 'Consequatur duis la', '1980-11-04', '0000-00-00', 39.000, 84.000, 'In lorem excepturi n', 'Eos molestiae ipsam ', 13),
(2, 'Dolorem ipsum rerum', '2001-06-16', '0000-00-00', 78.000, 80.000, 'Quidem ipsa eaque e', 'Laborum quis recusan', 16),
(3, 'Non magna labore exc', '1974-10-16', '0000-00-00', 20.000, 48.000, 'Vero veniam quas de', 'Ipsam ipsam nostrud ', 16),
(4, 'Eaque omnis irure mi', '2001-07-21', '0000-00-00', 82.000, 68.000, 'Qui in deleniti volu', 'Dignissimos ipsa vo', 19),
(5, 'Voluptas nihil eum c', '2011-08-24', '0000-00-00', 63.000, 19.000, 'Adipisci repudiandae', 'Qui et consequatur s', 25);

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
  `taxe` decimal(10,3) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `N_facture` (`N_facture`),
  KEY `ClientID` (`ClientID`),
  KEY `fk_user_bank` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`ID`, `ClientID`, `N_facture`, `type`, `TVA`, `Montant_Total_HT`, `Montant_Total_TTC`, `Date_Creation`, `Conditions`, `condition_re`, `Datee`, `livraison`, `user_id`, `taxe`) VALUES
(18, 22, 'Ipsum mollitia excep', 'devis', 11.00, 4154.00, 4610.94, '2025-06-25 13:15:56', 'Nulla qui quos qui e', 'Quia do porro commod', '1972-11-08', 'Distinctio Ipsum op', 25, NULL),
(19, 22, 'Accusantium ut adipi', 'devis', 7.00, 864.00, 924.48, '2025-06-25 13:32:15', 'Temporibus dolorum s', 'Sint amet sunt vol', '1986-07-14', 'Aliquid esse anim qu', 25, 60.480),
(20, 24, 'Nemo recusandae Rep', 'bl', 1.00, 744.00, 751.44, '2025-06-30 09:59:28', 'Quis consequatur As', 'Quis mollitia et sap', '2018-11-12', 'Consequatur Nihil e', 26, 7.440),
(21, 24, 'Nostrud est excepte', 'bl', 28.00, 320.00, 409.60, '2025-06-30 12:47:56', 'Consequat Expedita ', 'Sunt asperiores earu', '1982-04-22', 'Culpa ipsum asperio', 26, 89.600),
(22, 24, 'Est elit iste modi', 'devis', 78.00, 968.00, 1723.04, '2025-06-30 12:48:23', 'Culpa repudiandae re', 'Illo nobis atque eli', '2024-04-15', 'Et earum quam aute u', 26, 755.040),
(23, 24, 'In ex dolorem aut en', 'facture', 43.00, 14675.00, 20985.25, '2025-06-30 12:49:14', 'Incidunt dolores qu', 'Sunt error culpa of', '1986-12-21', 'Consequatur Minim n', 26, 6310.250),
(24, 24, 'Cillum nihil cum aut', 'devis', 51.00, 10355.00, 15636.05, '2025-06-30 13:49:08', 'Dolor enim dolorum n', 'Numquam eum beatae i', '2020-03-31', 'Molestias in est lab', 26, 5281.050),
(25, 24, 'Cum tempor dolorum e', 'facture', 39.00, 784.00, 1089.76, '2025-06-30 15:17:55', 'Qui voluptatum labor', 'Ut cumque exercitati', '1982-02-04', 'Illo eiusmod a incid', 26, 305.760),
(26, 24, 'Voluptas impedit la', 'bl', 15.00, 874.00, 1005.10, '2025-06-30 15:18:14', '', '', '2003-09-05', '', 26, 131.100),
(27, 26, 'Proident qui aute i', 'devis', 19.00, 7209.00, 8578.71, '2025-07-03 11:34:02', 'Culpa ut tempor ea p', 'Eos voluptas volupta', '1987-06-03', 'Quia assumenda eveni', 32, 1369.710);

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
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `facture_items`
--

INSERT INTO `facture_items` (`ID`, `FactureID`, `Designation`, `Quantite`, `Prix_Unit`, `Montant_HT`, `ordre`) VALUES
(21, 18, 'Consectetur qui blan', 62, 67.00, 4154.00, 1),
(22, 19, 'Eum sint molestias', 54, 16.00, 864.00, 1),
(23, 20, 'Incididunt quisquam', 31, 24.00, 744.00, 1),
(24, 21, 'Cillum ipsa ad qui', 4, 80.00, 320.00, 1),
(25, 22, 'Qui veniam veritati', 44, 22.00, 968.00, 1),
(26, 23, 'Lorem enim ut quis n', 30, 63.00, 1890.00, 1),
(27, 23, 'Lorem maxime rerum e', 79, 78.00, 6162.00, 2),
(28, 23, 'Et et aut voluptate', 21, 51.00, 1071.00, 3),
(29, 23, 'Dolor amet laboris', 21, 93.00, 1953.00, 4),
(30, 23, 'Autem soluta aut qui', 61, 59.00, 3599.00, 5),
(31, 24, 'Magni possimus minu', 35, 59.00, 2065.00, 1),
(32, 24, 'Assumenda cillum seq', 48, 16.00, 768.00, 2),
(33, 24, 'Dolores rerum qui eo', 10, 24.00, 240.00, 4),
(34, 24, 'Et saepe unde volupt', 41, 98.00, 4018.00, 5),
(35, 24, 'Excepturi laboriosam', 48, 68.00, 3264.00, 6),
(36, 25, 'Dolores beatae irure', 28, 28.00, 784.00, 1),
(37, 26, 'Dolorem dolore aperi', 46, 19.00, 874.00, 1),
(38, 27, 'Et ipsum qui iure a', 89, 81.00, 7209.00, 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `items`
--

INSERT INTO `items` (`ID`, `Date`, `Fournisseur`, `N_Facture`, `Article`, `Designation`, `Qte`, `Montant_uHT`, `Total_Uht`, `TVA`, `TOTAL_TTC`, `Date_c`, `N_Devis`, `N_Facture_C`, `Client`, `Code_client`, `Mt_HT`, `Mt_TTC`, `user_id`) VALUES
(1, '2019-04-28', 4, 'Repellendus Eligend', 'Molestiae voluptatem', 'Qui mollit alias fug', 44, 99.000, 4356.000, 20, 5227.200, '1974-11-13', 'Est dolor ut aut lib', 'Aut ipsa nesciunt ', 2, 'Ad numquam natus lab', 4356.000, 5227.200, 13),
(2, '1973-10-14', 11, 'Minima Nam vel aut i', 'Impedit minus numqu', 'Laudantium eveniet', 24, 40.000, 960.000, 20, 1152.000, '2019-02-28', 'Et enim necessitatib', 'Dolorem quas sed quo', 9, 'Cupiditate iste maio', 960.000, 1152.000, 16),
(3, '2018-02-22', 6, 'Nesciunt quidem mol', 'Voluptatum ullam ut ', 'Aut at ut itaque sin', 37, 10.000, 370.000, 20, 444.000, '1979-06-07', 'Mollitia non quam la', 'Quod sit nostrum quo', 5, 'Non quibusdam illum', 370.000, 444.000, 16),
(4, '1991-11-03', 6, 'Eius quibusdam maior', 'A sed neque voluptas', 'Adipisci quis proide', 91, 42.000, 3822.000, 20, 4586.400, '2004-10-22', 'Iste exercitationem ', 'Porro labore labore ', 7, 'Rerum deleniti modi ', 3822.000, 4586.400, 16),
(5, '1984-12-25', 15, 'Ex eveniet eveniet', 'Vel voluptatum moles', 'Commodo dolor vel ni', 29, 32.000, 928.000, 20, 1113.600, '1985-10-11', 'Nisi cupidatat nemo ', 'Totam mollit qui ass', 14, 'Similique sit illo a', 928.000, 1113.600, 19),
(6, '1982-12-09', 19, 'Velit et veniam ea ', 'Laudantium quos vol', 'Aut expedita beatae ', 16, 35.000, 560.000, 20, 672.000, '1986-12-23', 'Incidunt laudantium', 'Quas et molestiae pr', 18, 'Ullamco magnam dolor', 560.000, 672.000, 23),
(7, '2015-12-07', 23, 'Perferendis ad repre', 'Sint doloribus conse', 'Eu modi commodo dese', 28, 99.000, 2772.000, 20, 3326.400, '1984-03-11', 'Quo ut in velit vol', 'Voluptates voluptate', 22, 'Vero ut pariatur Ne', 2772.000, 3326.400, 25);

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
  `NumeroGSM` int DEFAULT NULL,
  `NumeroFixe` int DEFAULT NULL,
  `Activite` varchar(20) NOT NULL,
  `Role` enum('Client','Fournisseur') DEFAULT NULL,
  `Code_de_reference` varchar(10) DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_user_bank` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `liste_fourniseur_client`
--

INSERT INTO `liste_fourniseur_client` (`ID`, `NameEntreprise`, `ICE`, `Adresse`, `Email`, `Contact`, `NumeroGSM`, `NumeroFixe`, `Activite`, `Role`, `Code_de_reference`, `user_id`) VALUES
(3, 'Hashim Rich', 'Mollitia eveniet au', 'Atque labore proiden', 'fucideri@mailinator.com', 'Quisquam est aspern', 1, 1, 'Eos nisi culpa sunt', 'Client', '003', 13),
(2, 'Tad Estes', 'Quo illo aut esse es', 'Aliquip quo irure in', 'fazecas@mailinator.com', 'Velit ratione nisi a', 1, 1, 'Reiciendis vitae exc', 'Client', '002', 13),
(4, 'Drake Cleveland', 'Cupidatat eaque vero', 'Dolore dolor dolorem', 'kuquh@mailinator.com', 'Ullam qui voluptate ', 1, 1, 'Nesciunt optio off', 'Fournisseur', '004', 13),
(5, 'Isaac Baxter', 'Hic reprehenderit om', 'Eligendi cum quia te', 'higyka@mailinator.com', 'Adipisci proident e', 1, 1, 'Laudantium labore d', 'Client', '005', 16),
(6, 'Octavia Mercado', 'Fugit et duis fuga', 'Fugiat harum dolorum', 'qoqeko@mailinator.com', 'Illo quia rem corrup', 1, 1, 'Corporis temporibus ', 'Fournisseur', '006', 16),
(7, 'Shana Gray', 'Hic omnis proident ', 'Nulla eaque expedita', 'jukepani@mailinator.com', 'Voluptates aut non e', 1, 1, 'Magnam est sint eum', 'Client', '007', 16),
(8, 'Renee Pearson', 'Nam molestias ut qui', 'Dolor doloremque tem', 'tisul@mailinator.com', 'Eu id fugiat volup', 1, 1, 'Qui voluptatibus lab', 'Client', '008', 16),
(9, 'Tamara White', 'Dolor quia animi au', 'Sunt non aut beatae', 'budejut@mailinator.com', 'Nulla pariatur Dolo', 1, 1, 'Praesentium reprehen', 'Client', '009', 16),
(10, 'Ferris Ferrell', 'Aliquip non Nam est ', 'Quis eaque irure err', 'rimyjamud@mailinator.com', 'Ducimus veritatis n', 1, 1, 'Asperiores ut quasi ', 'Fournisseur', '010', 16),
(11, 'Kerry Macias', 'Accusamus alias duis', 'Qui laudantium qui ', 'kuhufi@mailinator.com', 'Nisi natus qui sit i', 1, 1, 'At officiis incidunt', 'Fournisseur', '011', 16),
(12, 'Holmes Conrad', 'Omnis fugit deserun', 'Et nihil deserunt te', 'dyci@mailinator.com', 'Aut ea similique est', 1, 1, 'Ut debitis cumque cu', 'Client', '012', 18),
(13, 'Amos Jackson', 'Corporis eos earum i', 'Quod labore et facil', 'vydijily@mailinator.com', 'Laboris perspiciatis', 1, 1, 'Sint qui non nostru', 'Fournisseur', '013', 18),
(14, 'Caleb West', 'Neque laborum In cu', 'Autem veniam in id', 'bovoj@mailinator.com', 'Nobis temporibus nis', 1, 1, 'Corrupti unde asper', 'Client', '014', 19),
(15, 'Thane Richard', 'Sit sint ad omnis s', 'Voluptas consequatur', 'fumesub@mailinator.com', 'Lorem omnis possimus', 1, 1, 'Non numquam necessit', 'Fournisseur', '015', 19),
(19, 'Meredith Mcconnell', 'Hic officia inventor', 'Tempor adipisci mole', 'wiricyto@mailinator.com', 'Deserunt et qui comm', 1, 1, 'Cillum quas et eu ma', 'Fournisseur', '019', 23),
(18, 'Katelyn Gallagher', 'Perspiciatis do qui', 'Et non praesentium e', 'kicogawule@mailinator.com', 'Aut libero optio vo', 1, 1, 'Molestiae accusantiu', 'Client', '018', 23),
(20, 'Addison Sherman', 'Nesciunt ad ea exer', 'Beatae veniam saepe', 'byjesyzo@mailinator.com', 'Quas elit ut deseru', 1, 1, 'Ut lorem est animi', 'Client', '020', 24),
(21, 'Denton Dickson', 'Anim odio aute fugia', 'Sit voluptates alia', 'mysypytipa@mailinator.com', 'Dolor commodo provid', 1, 1, 'Totam obcaecati ex a', 'Fournisseur', '021', 24),
(22, 'Joy Berry', 'Dolor est esse in ei', 'Nisi impedit conseq', 'xoqe@mailinator.com', 'Qui aut optio occae', 1, 1, 'Aliquip delectus qu', 'Client', '022', 25),
(23, 'Ethan Sweeney', 'Nobis officia corpor', 'Sit vitae id adipis', 'zyto@mailinator.com', 'Nisi ut quae nesciun', 1, 1, 'Velit explicabo Sim', 'Fournisseur', '023', 25),
(24, 'Ignatius Hess', 'Saepe facilis aliqui', 'Aut et neque distinc', 'lygurydeku@mailinator.com', 'Libero officiis esse', 1, 1, 'Inventore minus temp', 'Client', '024', 26),
(25, 'Zelda Mooney', 'Qui et perspiciatis', 'Praesentium vel expe', 'vuqativej@mailinator.com', 'Sint impedit sequi ', 1, 1, 'Magnam asperiores co', 'Fournisseur', '025', 26),
(26, 'Benedict Mack', 'Placeat est quos do', 'Cumque nihil qui atq', 'jahilygupu@mailinator.com', 'Cupidatat reprehende', 1, 1, 'Nihil tempora aute e', 'Client', '026', 32),
(27, 'Teagan Schwartz', 'Tempor unde quas vol', 'Nisi est architecto ', 'tihybix@mailinator.com', 'Ut exercitationem ni', 1, 1, 'Voluptate velit cumq', 'Fournisseur', '027', 32);

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
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `email`, `password`) VALUES
(21, 'Mariko Greene', 'Travis Bernard', 'hipizydom@mailinator.com', '$2y$10$bJm7IvBDbls.Uodis8zOZu64sAo3pTq6dp2j6MzDXCFl8EEDWECYa'),
(22, 'Walker Rodriguez', 'Tiger Conway', 'huvujipifi@mailinator.com', '$2y$10$JOsmcmih/OzQowtaJoQ1uum6vcztSDM7J4YttdtKnHACz/G.Z0j82'),
(23, 'Solomon Mccall', 'Ali Fletcher', 'tyrogabur@mailinator.com', '$2y$10$Twrl8VNNJmFrmWfwOfxY5.iUuMTuJVwydfkCfdLTmxzcwI20PCBTa'),
(24, 'Hu Shelton', 'Fitzgerald Wright', 'cacahuj@mailinator.com', '$2y$10$aISoMZZbvRyj05ZmkTEHKOA/5sNeZIdJRX8/0Ohdu/4zP/QIeEzw.'),
(25, 'Pandora Page', 'Burton Lott', 'nemasiho@mailinator.com', '$2y$10$RJopHYWCPNRR4UC8A4ch5urUjI3HiehOnxyi8PIJ8cAcBW8bWj1TO'),
(26, 'Lunea Benjamin', 'Bruno Mcknight', 'quqitamilu@mailinator.com', '$2y$10$JjatCF7A5.q.Q5JWaCdlf.IkR3jFydU3jZdbWrFc1NbkzepnpZmxe'),
(27, 'Kelly Morgan', 'Lara Heath', 'roduza@mailinator.com', '$2y$10$dda/imL0fKLluQToRADvf.CJzr5tbMtK2pAyvMOmeHOaD8AD/OdFO'),
(28, 'La', 'A', 'gabe@mailinator.com', '$2y$10$VDO.6EW8APLWpM8xCZerGuNV3HbyjnmAxDQfH/vI/MUWBAlWLqs1.'),
(29, 'I', 'McK', 'lygakit@mailinator.com', '$2y$10$z2wofsQWYY5HS402JLMQheiU213o9j.srKYrmmg6R.kVFM2WZPqN6'),
(30, 'Fin', 'Juliet Tucker', 'fimo@mailinator.com', '$2y$10$Ov3QVKiqSXXQlj7iI.kMKuamI6OJj2xpkseqqvLewEGSX9nD7ry5e'),
(31, 'Mu', 'Ezekiel Marsh', 'diqyjisori@mailinator.com', '$2y$10$DPt/mqvagy3ltUwpcjaW7eA2wo9p.RTjPyyOEnMJTlk5T8lScww1K'),
(32, 'Regina Norman', 'Quin Williamson', 'tesyjaz@mailinator.com', '$2y$10$3HMU1EZ1b..4chDOAmU6ZeQ6YP.TlByh5u2E/tpHXBn7Ju8w6c.5.'),
(33, 'Zephania Marquez', 'Teegan Erickson', 'ryqasi@mailinator.com', '$2y$10$IthzXMFg9Ga2e5MmciSffuE0H/4QrccEiB0zq3ROmXJnDDm5pzTh6'),
(34, 'Sloane Gibson', 'Edan Gillespie', 'meqicagi@mailinator.com', '$2y$10$7FEVXM1bqUqYXSs40X9FxuHcaKhKzsFEAOq.NsfToYJXt9cZTBmUm'),
(35, 'Katell Miranda', 'Zelenia Kelley', 'lyryzevezy@mailinator.com', '$2y$10$LfmZO93qslxD0xebsUeOjuRDVBD8ddbJ5xKuOl45uZx5O/hBOsbqu'),
(36, 'Jermaine Graham', 'Kirsten Howard', 'xylopehige@mailinator.com', '$2y$10$aGVkBXwymiT0IrCIq9u4/ueRUhkckUQvFtoSBTtP100/VqsatgQ/i'),
(37, 'Idola Duncan', 'Shaine Christian', 'naguzuqyk@gmail.com', '$2y$10$FjAbJvs6L1XTja0GBCoDV.E/p2ZJef9pxzQWFaVd898lg/ME8jULu');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
