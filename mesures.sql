

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




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
(7018, 2, 15, '2025-06-17 13:31:02');

INSERT INTO `mesures` (`id`, `capteur_id`, `valeur`, `date_heure`) VALUES

(9749, 3, 28.5, '2025-06-17 14:23:48'),
(9752, 2, 23.6, '2025-06-17 14:23:48');
INSERT INTO `mesures` (`id`, `capteur_id`, `valeur`, `date_heure`) VALUES
(9753, 3, 28.5, '2025-06-17 14:23:48'),
(9754, 2, 23.6, '2025-06-17 14:23:48'),

(15434, 1, 28.57, '2025-06-19 12:03:09'),
(15435, 1, 28.91, '2025-06-19 12:03:09');
INSERT INTO `mesures` (`id`, `capteur_id`, `valeur`, `date_heure`) VALUES
(15436, 1, 33.8, '2025-06-19 12:03:09'),
(15437, 1, 38.66, '2025-06-19 12:03:10'),

(15551, 1, 28.89, '2025-06-19 12:03:32'),
(15552, 1, 28.13, '2025-06-19 12:03:33'),
(15553, 1, 28.4, '2025-06-19 12:03:33');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `mesures`
--
ALTER TABLE `mesures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesures_capteurs_FK` (`capteur_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `mesures`
--
ALTER TABLE `mesures`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15554;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `mesures`
--
ALTER TABLE `mesures`
  ADD CONSTRAINT `mesures_capteurs_FK` FOREIGN KEY (`capteur_id`) REFERENCES `capteurs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
