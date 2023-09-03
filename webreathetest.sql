-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 03 sep. 2023 à 11:48
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `webreathetest`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230830184415', '2023-08-30 20:44:19', 112),
('DoctrineMigrations\\Version20230830193028', '2023-08-30 21:31:19', 42),
('DoctrineMigrations\\Version20230830194333', '2023-08-30 21:43:37', 38);

-- --------------------------------------------------------

--
-- Structure de la table `luminosity_data`
--

CREATE TABLE `luminosity_data` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `luminosity_data`
--

INSERT INTO `luminosity_data` (`id`, `module_id`, `timestamp`, `value`) VALUES
(82, 15, '2023-09-01 13:54:19', 8),
(83, 15, '2023-09-01 13:54:46', 100),
(84, 15, '2023-09-01 13:54:53', 20),
(85, 15, '2023-09-01 13:55:00', 100),
(88, 12, '2023-09-01 01:01:01', 25),
(91, 12, '2023-03-21 15:50:17', 70),
(92, 12, '2023-08-16 15:50:19', 46);

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `localisation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `module`
--

INSERT INTO `module` (`id`, `name`, `status`, `serial_number`, `localisation`) VALUES
(12, 'Ampoule Salon', 'actif', '8888-4451-1223', 'Salon'),
(15, 'Lumiere garage', 'inactif', '4545-5552-4512', 'Garage');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `luminosity_data`
--
ALTER TABLE `luminosity_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_64CAA466AFC2B591` (`module_id`);

--
-- Index pour la table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `luminosity_data`
--
ALTER TABLE `luminosity_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT pour la table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `luminosity_data`
--
ALTER TABLE `luminosity_data`
  ADD CONSTRAINT `FK_64CAA466AFC2B591` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
