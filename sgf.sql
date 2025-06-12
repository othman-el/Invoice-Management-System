-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 12 juin 2025 à 10:14
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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `factures`
--

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `facture_items`
--


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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `liste_fourniseur_client`
--

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
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
