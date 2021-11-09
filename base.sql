START TRANSACTION;
CREATE TABLE utilisateur(
   id INT AUTO_INCREMENT,
   nom VARCHAR(255)  NOT NULL,
   mdp VARCHAR(512)  NOT NULL,
   pseudo VARCHAR(64)  NOT NULL,
   email VARCHAR(255)  NOT NULL,
   nom_e VARCHAR(255)  NOT NULL,
   adresse_e VARCHAR(512)  NOT NULL,
   PRIMARY KEY(id),
   UNIQUE(pseudo),
   UNIQUE(email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO utilisateur (nom, pseudo, mdp, email, nom_e, adresse_e) VALUES
('admin', 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'admin@emotorsport.fr', 'Agence Web', '?'),
('Phillipe Maurice', 'pmaurice', '42dc83019a35f6b82f12bf522ee89ee144bbc7a41f656642f2a125d6e60223f5', 'pmaurice@emotorsport.fr', 'eMotorsport', '141 avenue de Versailles, Paris 75016'),
('Clément Mauperon', 'cmauperon', 'b20218270e1e45cc412e9dacaaceafe26c8a4e2e9773b5e1242db501b96f5256', 'clement.mauperon@mindustries.fr', 'Marx Industries', '20 avenue Vladimir Illitch Lénine, 92000 Nanterre'),
('Clément Prost', 'cprost', '6d816dccec6a9cde8378df63928ef05ad97a75139f417664f31152f23ed4743a', 'cprost@amazon.com', 'Amazon', '67 Boulevard du Général Leclerc, 92110 Clichy'),
('Robin Fauchery', 'rfauchery', 'f35bb88542a058ef42448f15e1523ce22e835b718ef7ea67f27bfe5458d814c9', 'rfauchery@spacex.com', 'Space X', 'Hawthorne, Californie, USA');


CREATE TABLE cookie(
   id INT AUTO_INCREMENT,
   idu INT NOT NULL,
   ip VARCHAR(16) ,
   PRIMARY KEY(id),
   FOREIGN KEY(idu) REFERENCES utilisateur(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE vehicule(
   id INT AUTO_INCREMENT,
   typeV VARCHAR(64)  NOT NULL,
   marque VARCHAR(255)  NOT NULL,
   modele VARCHAR(255)  NOT NULL,
   nb SMALLINT NOT NULL,
   caract TEXT NOT NULL,
   photo VARCHAR(255)  NOT NULL,
   dispo BOOLEAN NOT NULL,
   PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO vehicule (typeV, marque, modele, nb, caract, photo, dispo) VALUES
('voiture', 'Peugeot', '208', 2, '{\"Moteur\":\"Essence\",\"Portes\":\"5\",\"Puissance\":\"5CV\",\"Boite\":\"Mecanique\"}', '44e512282668d4c4900db0932b88e3f2f5775c95.jpg', 1),
('voiture', 'Peugeot', '308', 6, '{\"Moteur\":\"Diesel\",\"Portes\":\"5\",\"Puissance\":\"6CV\",\"Boite\":\"Automatique\"}', '3c4191808aa6e9dcf07fe1ae5d79989a1ce7c3f1.jpg', 1),
('voiture', 'Porsche', '911', 1, '{\"Moteur\":\"Essence\",\"Portes\":\"2\",\"Puissance\":\"26CV\",\"Boite\":\"Automatique\"}', 'a7ec7c0a92da3177d278dc745f38ba8ca2c49cef.jpg', 1),
('voiture', 'Porsche', 'Cayenne', 2, '{\"Moteur\":\"Essence\",\"Portes\":\"5\",\"Puissance\":\"33CV\",\"Boite\":\"Automatique\"}', '14a7abce840e544c0cea136277f95cd6d5d22734.jpg', 1),
('voiture', 'Audi ', 'R8', 1, '{\"Moteur\":\"Essence\",\"Portes\":\"2\",\"Puissance\":\"46CV\",\"Boite\":\"Automatique\"}', '438b18452eb6bbba305fb92dd9c6e05d01445b6d.jpg', 0),
('voiture', 'Renault', 'ZOE', 3, '{\"Moteur\":\"Electrique\",\"Portes\":\"5\",\"Puissance\":\"4CV\",\"Boite\":\"Automatique\"}', '33234cdb315e04695451829705f49fe5b817983a.jpg', 1),
('voiture', 'Citroën', 'C4 Picasso', 4, '{\"Moteur\":\"Diesel\"}', '955dadadc5fe8ca9f2866b9187a71ffc3830cb0f.jpg', 1);

CREATE TABLE facture(
   id BIGINT AUTO_INCREMENT,
   idu INT NOT NULL,
   idv INT NOT NULL,
   date_d DATE NOT NULL,
   date_f DATE,
   valeur DECIMAL(19,4),
   etat_r BOOLEAN NOT NULL,
   PRIMARY KEY(id, date_d),
   FOREIGN KEY(idu) REFERENCES utilisateur(id),
   FOREIGN KEY(idv) REFERENCES vehicule(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO facture (idu, idv, date_d, date_f, valeur, etat_r) VALUES
(3, 1, '2021-11-04', '2021-11-12', '92.0000', 0),
(5, 2, '2021-11-02', NULL, '75.0000', 0),
(4, 4, '2021-11-01', '2021-12-31', NULL, 0);

COMMIT;