-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 185.216.26.53
-- Généré le : jeu. 19 juin 2025 à 17:17
-- Version du serveur : 8.0.42-0ubuntu0.24.04.1
-- Version de PHP : 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `app_g3`
--

-- --------------------------------------------------------

--
-- Structure de la table `actionneurs`
--

CREATE TABLE `actionneurs` (
  `id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `actionneurs`
--

INSERT INTO `actionneurs` (`id`, `nom`) VALUES
(1, 'led'),
(2, 'moteur');

-- --------------------------------------------------------

--
-- Structure de la table `capteurs`
--

CREATE TABLE `capteurs` (
  `id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `unite` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_actif` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `capteurs`
--

INSERT INTO `capteurs` (`id`, `nom`, `unite`, `is_actif`) VALUES
(1, 'luminosite', '%', 1),
(2, 'temperature', '°C', 1),
(3, 'humidite', '%', 1),
(4, 'bouton', NULL, 1),
(5, 'humidite_sol', '%', 1);

-- --------------------------------------------------------

--
-- Structure de la table `etats_actionneurs`
--

CREATE TABLE `etats_actionneurs` (
  `id` int NOT NULL,
  `actionneur_id` int NOT NULL,
  `date_heure` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `etat` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etats_actionneurs`
--

INSERT INTO `etats_actionneurs` (`id`, `actionneur_id`, `date_heure`, `etat`) VALUES
(17, 1, '2025-06-17 12:42:47', 1),

(777, 2, '2025-06-17 14:13:40', 0),
(778, 2, '2025-06-17 14:13:41', 0);

-- --------------------------------------------------------

--
-- Structure de la table `limites`
--

CREATE TABLE `limites` (
  `id` int NOT NULL,
  `id_capteur` int NOT NULL,
  `lim_min` float DEFAULT NULL,
  `lim_max` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `limites`
--

INSERT INTO `limites` (`id`, `id_capteur`, `lim_min`, `lim_max`) VALUES
(1, 1, 20, 150),
(2, 2, NULL, 100),
(3, 3, NULL, 100),
(4, 4, NULL, 100),
(5, 5, NULL, 100);

-- --------------------------------------------------------

--
-- Structure de la table `mesures`
--

CREATE TABLE `mesures` (
  `id` bigint NOT NULL,
  `capteur_id` int NOT NULL,
  `valeur` float NOT NULL,
  `date_heure` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `mesures`
--

INSERT INTO `mesures` (`id`, `capteur_id`, `valeur`, `date_heure`) VALUES
(7018, 2, 15, '2025-06-17 13:31:02'),

(8419, 3, 28, '2025-06-17 14:12:15');
INSERT INTO `mesures` (`id`, `capteur_id`, `valeur`, `date_heure`) VALUES
(8420, 5, 15, '2025-06-17 14:12:16'),

(9752, 2, 23.6, '2025-06-17 14:23:48');
INSERT INTO `mesures` (`id`, `capteur_id`, `valeur`, `date_heure`) VALUES
(9753, 3, 28.5, '2025-06-17 14:23:48'),

(15435, 1, 28.91, '2025-06-19 12:03:09');
INSERT INTO `mesures` (`id`, `capteur_id`, `valeur`, `date_heure`) VALUES
(15436, 1, 33.8, '2025-06-19 12:03:09'),

(15551, 1, 28.89, '2025-06-19 12:03:32'),
(15552, 1, 28.13, '2025-06-19 12:03:33'),
(15553, 1, 28.4, '2025-06-19 12:03:33');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`name`, `id`) VALUES
('etudiant', 1),
('admin', 2);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` char(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `est_verifie` tinyint(1) NOT NULL DEFAULT '0',
  `inactif_depuis` datetime DEFAULT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `mail`, `username`, `password`, `token`, `est_verifie`, `inactif_depuis`, `role_id`) VALUES
(2, 'victor.munerot@eleve.isep.fr', 'savacartonner', '$2y$10$wRTesud5oXCEyG8wOK6m2eDBn2r2N04snpkwdaf35Rti3cB63xx7u', NULL, 1, NULL, 1),
(5, 'guillaume.jacquet@eleve.isep.fr', 'GJ', '$2y$10$T9boBgNr8UMKedPDGSaHG.E.iP2tk39Rp7QyE2mSml.hMhiGV9Ifa', NULL, 0, NULL, 1),
(6, 'angel.segui@eleve.isep.fr', 'angle', '$2y$10$CESYzeYnVN6aUxwWDaMhNOaVoQB/KA4wmxfFfeYWSD9iNr5YKInFO', '52e294180be1731a984bebecd7b84a6b', 1, NULL, 2),
(7, 'vicpoussier@gmail.com', 'Victor', '$2y$10$e9Wl/KwSPALzcE9PnwB8COZvYfGxgx2p/VWA2NkoOeg0hwyKJD7vW', 'd774fd973bc001be42f636dbbf2449be', 0, NULL, 1),
(8, 'admin@smartgarden.fr', 'ADMIN', '$2y$10$ATGODH8DlN2Z91.djaxVO.6CFYGuXaRWzB9HrCg5mI4Pa6rgHszee', '1fe93353da4d48d643b113c2499120e2', 1, NULL, 2),
(9, 'supermail@gmail.com', 'Maman d&#039;Alexis', '$2y$10$urxv8qIneF4xsFxMsfjmWuPJsZB15bbRbSCqGZJWChtfOAxHqyziC', '12516f45e0970cc93611a57492d5cddc', 1, NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actionneurs`
--
ALTER TABLE `actionneurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `capteurs`
--
ALTER TABLE `capteurs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etats_actionneurs`
--
ALTER TABLE `etats_actionneurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etats_actionneurs_actionneurs_FK` (`actionneur_id`);

--
-- Index pour la table `limites`
--
ALTER TABLE `limites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_capteur` (`id_capteur`);

--
-- Index pour la table `mesures`
--
ALTER TABLE `mesures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesures_capteurs_FK` (`capteur_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `mails` (`mail`),
  ADD KEY `user_role_FK` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actionneurs`
--
ALTER TABLE `actionneurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `capteurs`
--
ALTER TABLE `capteurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `etats_actionneurs`
--
ALTER TABLE `etats_actionneurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=783;

--
-- AUTO_INCREMENT pour la table `limites`
--
ALTER TABLE `limites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `mesures`
--
ALTER TABLE `mesures`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15554;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etats_actionneurs`
--
ALTER TABLE `etats_actionneurs`
  ADD CONSTRAINT `etats_actionneurs_actionneurs_FK` FOREIGN KEY (`actionneur_id`) REFERENCES `actionneurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `limites`
--
ALTER TABLE `limites`
  ADD CONSTRAINT `limites_ibfk_1` FOREIGN KEY (`id_capteur`) REFERENCES `capteurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `mesures`
--
ALTER TABLE `mesures`
  ADD CONSTRAINT `mesures_capteurs_FK` FOREIGN KEY (`capteur_id`) REFERENCES `capteurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_role_FK` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
