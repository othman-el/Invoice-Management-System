-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 21 mai 2025 à 15:06
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
-- Structure de la table `liste_fourniseur_client`
--

DROP TABLE IF EXISTS `liste_fourniseur_client`;
CREATE TABLE IF NOT EXISTS `liste_fourniseur_client` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Nom de l'enreprise` varchar(250) NOT NULL,
  `ICE` int NOT NULL,
  `Adresse` varchar(250) NOT NULL,
  `Email` varchar(250) DEFAULT NULL,
  `Contact` varchar(250) NOT NULL,
  `N GSM` int DEFAULT NULL,
  `N FIX` int DEFAULT NULL,
  `Activité` varchar(250) NOT NULL,
  `ROLE` enum('Fourniseur','Client') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `email`, `password`) VALUES
(1, 'otm', 'om', 'test@gmail.com', '$2y$10$VoB.5PYShzRqdocNmDFIT.iiFtseAOC.H8.IhjmhIV3WOAu4Ltocq'),
(2, ' test1', 'mmm', 'ehhfjf@gmail.com', '$2y$10$qJGfCeXVGlE2teBLHlivZuIKrXNH2b6xGT2wYFe.xtKVLh5/5JBL6');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
