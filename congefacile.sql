-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 05 mai 2025 à 16:57
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `congefacile`
--

-- --------------------------------------------------------

--
-- Structure de la table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `department`
--

INSERT INTO `department` (`id`, `name`) VALUES
(1, 'Informatique'),
(2, 'Ressources Humaines'),
(3, 'Informatique'),
(4, 'Ressources Humaines');

-- --------------------------------------------------------

--
-- Structure de la table `person`
--

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `alert_new_request` tinyint(1) NOT NULL,
  `alert_on_answer` tinyint(1) NOT NULL,
  `alert_before_vacation` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `person`
--

INSERT INTO `person` (`id`, `last_name`, `first_name`, `manager_id`, `department_id`, `position_id`, `alert_new_request`, `alert_on_answer`, `alert_before_vacation`) VALUES
(1, 'Martin', 'Sophie', NULL, 1, 2, 1, 1, 1),
(2, 'Dupont', 'Jean', 1, 1, 1, 1, 1, 1),
(3, 'Durand', 'Claire', NULL, 1, 2, 1, 1, 1),
(4, 'Martin', 'Alice', 1, 1, 1, 1, 1, 0),
(5, 'Petit', 'Louis', 1, 1, 1, 1, 1, 1),
(6, 'Bernard', 'Sophie', NULL, 2, 2, 1, 1, 1),
(7, 'Lemoine', 'David', 6, 2, 1, 0, 1, 1),
(8, 'Morel', 'Emma', 6, 2, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `position`
--

CREATE TABLE `position` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `nb_postes_dispo` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `position`
--

INSERT INTO `position` (`id`, `name`, `nb_postes_dispo`) VALUES
(1, 'Développeur', 0),
(2, 'Manager RH', 0),
(6, 'Test Poste', 10),
(7, 'Test', 25);

-- --------------------------------------------------------

--
-- Structure de la table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `request_type_id` int(11) NOT NULL,
  `collaborator_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `receipt_file` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `answer_comment` text DEFAULT NULL,
  `answer` tinyint(1) NOT NULL,
  `answer_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `request`
--

INSERT INTO `request` (`id`, `request_type_id`, `collaborator_id`, `department_id`, `created_at`, `start_at`, `end_at`, `receipt_file`, `comment`, `answer_comment`, `answer`, `answer_at`) VALUES
(8, 2, 2, 1, '2025-03-19 10:20:18', '2025-02-25 00:00:00', '2025-03-20 00:00:00', NULL, '', NULL, 0, '2025-04-15 09:02:08'),
(9, 2, 2, 1, '2025-03-19 10:21:08', '2025-02-25 00:00:00', '2025-03-20 00:00:00', NULL, '', NULL, 0, '2025-04-11 09:02:13'),
(10, 1, 1, 2, '2025-03-19 10:23:08', '2025-03-07 00:00:00', '2025-03-21 00:00:00', NULL, 'test', NULL, 0, '2025-04-27 09:02:16'),
(11, 1, 1, 2, '2025-03-19 11:21:14', '2025-03-07 00:00:00', '2025-03-21 00:00:00', NULL, 'test', NULL, 0, '2025-04-01 09:02:20'),
(12, 2, 1, 2, '2025-03-19 11:21:47', '2025-03-13 00:00:00', '2025-03-27 00:00:00', NULL, '', NULL, 0, '2025-03-12 09:02:26'),
(13, 1, 2, 1, '2025-03-31 17:20:25', '2025-03-13 00:00:00', '2025-03-20 00:00:00', NULL, '', NULL, 0, '2025-04-22 09:02:30'),
(14, 1, 2, 1, '2025-03-01 08:00:00', '2025-04-01 00:00:00', '2025-04-10 00:00:00', NULL, 'Vacances de printemps', 'Validée par Claire', 1, '2025-03-05 10:00:00'),
(15, 2, 3, 1, '2025-03-10 08:00:00', '2025-04-15 00:00:00', '2025-04-15 00:00:00', NULL, 'RTT', NULL, 0, '2025-04-03 09:02:35'),
(16, 3, 5, 2, '2025-02-01 09:00:00', '2025-02-03 00:00:00', '2025-02-05 00:00:00', NULL, 'Maladie fièvre', 'Refus - pas de doc', 2, '2025-02-03 13:00:00'),
(17, 1, 6, 2, '2025-05-01 08:00:00', '2025-06-15 00:00:00', '2025-06-25 00:00:00', NULL, 'Congés été', 'OK bon timing', 1, '2025-05-05 12:00:00'),
(18, 1, 3, 1, '2025-05-20 08:00:00', '2025-07-01 00:00:00', '2025-07-03 00:00:00', NULL, 'Petit break', NULL, 0, '2025-04-10 09:02:39'),
(19, 2, 5, 2, '2025-03-20 08:00:00', '2025-03-25 00:00:00', '2025-03-25 00:00:00', NULL, 'RTT ponctuel', 'Accepté', 1, '2025-03-21 10:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `request_type`
--

CREATE TABLE `request_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `request_type`
--

INSERT INTO `request_type` (`id`, `name`) VALUES
(1, 'Congé payé'),
(2, 'Congé sans solde'),
(3, 'Congés payés'),
(4, 'RTT'),
(5, 'Maladie');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `role` varchar(50) NOT NULL,
  `person_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `enabled`, `created_at`, `role`, `person_id`) VALUES
(1, 'sophie.martin@email.com', '$2y$10$CbRF0SfEXWO6breRgb4JD.XuZg1m4KQ6WNUtV/rxKpPzXufei2yRW', 1, '2025-03-14 15:25:14', 'manager', 1),
(2, 'jean.dupont@email.com', '$2y$10$tnDIxPCR07ne2JZQALqzneEeAwwkGnXk0sBgve1OmNt7/i9sgwrMq', 1, '2025-03-14 15:25:14', 'employee', 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `fk_manager` (`manager_id`);

--
-- Index pour la table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_type_id` (`request_type_id`),
  ADD KEY `collaborator_id` (`collaborator_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Index pour la table `request_type`
--
ALTER TABLE `request_type`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `position`
--
ALTER TABLE `position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `request_type`
--
ALTER TABLE `request_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `fk_manager` FOREIGN KEY (`manager_id`) REFERENCES `person` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `person_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `person_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `position` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`request_type_id`) REFERENCES `request_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`collaborator_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
