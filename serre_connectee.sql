-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 17 juin 2025 à 17:28
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
-- Base de données : `serre_connectee`
--

-- --------------------------------------------------------

--
-- Structure de la table `actuators`
--

CREATE TABLE `actuators` (
  `id` int(11) NOT NULL,
  `actuator_type` enum('irrigation','ventilation','lighting','heating') NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `serre_id` int(11) NOT NULL DEFAULT 1,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sensor_data`
--

CREATE TABLE `sensor_data` (
  `id` int(11) NOT NULL,
  `sensor_type` enum('temperature','humidity','light','co2') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `serre_id` int(11) NOT NULL DEFAULT 1,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_type` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `created_at`, `user_type`) VALUES
(2, 'victor@gmail.com', '$2y$10$0rvPqts2X7UGL5wmSRs5tuTNb2sTVB5VuSfTxQmm9Bl3BlTUhQfom', '2025-06-17 14:50:58', 'user'),
(3, 'souksavat@gmail.com', '$2y$10$3rhw4buIkLPyj9XXKM7/MOcbkLfKUu9MuzZqjRQ/SHnkPeBFEg92e', '2025-06-17 14:55:32', 'user'),
(4, 'pipi@gmail.com', '$2y$10$UASdl5/qoS6Gq.1dB.SsTusQcxuNDYbSRE6wcWlyCQSY923X/cchy', '2025-06-17 15:09:51', 'user'),
(5, 'zizi@gmail.com', '$2y$10$y.sbptRqK.6jA12AGLXrTejtr8lFjPUUVc3..zKerFmP/Nxj0W.vO', '2025-06-17 15:10:50', 'user'),
(6, 'victorsouksavat@gmail.com', '$2y$10$MGHvOlzRjlmPH6oa9E/41uxVEmJ67z0ROOhO7yNu6lYPPDaMB41MC', '2025-06-17 15:19:44', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actuators`
--
ALTER TABLE `actuators`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sensor_type` (`sensor_type`,`timestamp`),
  ADD KEY `serre_id` (`serre_id`,`timestamp`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actuators`
--
ALTER TABLE `actuators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
