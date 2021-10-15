-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 15 Octobre 2021 à 09:56
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `id` int(11) NOT NULL,
  `ide` int(11) NOT NULL,
  `idv` int(11) NOT NULL,
  `DateD` date NOT NULL,
  `DateF` date NOT NULL,
  `valeur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `etatR` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomE` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresseE` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `pseudo`, `mdp`, `email`, `nomE`, `adresseE`) VALUES
(1, 'Dupont', 'tnopud', '12345', 'dupont.dupont@gmail.com', 'Dupont CORP', '58 rue du general'),
(2, 'Dupuis', 'siupud', '54321', 'dupuis.dupuis@gmail.com', 'Dupuis & Co', '20 rue de la Paix'),
(3, 'Lapierre', 'pierre08', '007', 'pierre.lapierre@gmail.com', 'LaPierre IND', '14 avenue du stade'),
(4, 'admin', 'admin', 'admin', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `voiture`
--

CREATE TABLE `voiture` (
  `id` int(11) NOT NULL,
  `marque` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modele` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nb` int(11) NOT NULL,
  `caract` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `etatL` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table voiture';

--
-- Contenu de la table `voiture`
--

INSERT INTO `voiture` (`id`, `marque`, `modele`, `nb`, `caract`, `photo`, `etatL`) VALUES
(1, 'Peugeot', '208', 2, '[\'Moteur\':\'Essence\',\'Portes\':\'5\',\'Puissance\':\'5CV\',\'Boite\':\'Mecanique\']', 'peugeot_208', 'disponible'),
(2, 'Peugeot ', '308', 6, '[\'Moteur\':\'Diesel\',\'Portes\':\'5\',\'Puissance\':\'6CV\',\'Boite\':\'Automatique\']', 'peugeot_308', 'disponible'),
(3, 'Porsche ', 'Porsche ', 1, '[\'Moteur\':\'Essence\',\'Portes\':\'2\',\'Puissance\':\'26CV\',\'Boite\':\'Automatique\']', 'porsche_911', 'disponible'),
(4, 'Porsche ', 'CAYENNE ', 2, '[\'Moteur\':\'Essence\',\'Portes\':\'5\',\'Puissance\':\'33CV\',\'Boite\':\'Automatique\']', 'porsche_cayenne', 'disponible'),
(5, 'Audi ', 'R8', 1, '[\'Moteur\':\'Essence\',\'Portes\':\'2\',\'Puissance\':\'46CV\',\'Boite\':\'Automatique\']', 'audi_r8', 'en_revision'),
(6, 'Renault', 'ZOE', 3, '[\'Moteur\':\'Electrique\',\'Portes\':\'5\',\'Puissance\':\'4CV\',\'Boite\':\'Automatique\']', 'renault_zoe', 'disponible');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `voiture`
--
ALTER TABLE `voiture`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
