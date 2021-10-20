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
('admin', 'admin', '$2y$10$K5hbVMHh4C5OM2MVDsGamOouw7GoFmjK2lAYfFtiUtb8V/sXcMqVq', 'admin@emotorsport.fr', '', '');

CREATE TABLE cookie(
   id INT AUTO_INCREMENT,
   idu INT NOT NULL,
   ip VARCHAR(16) ,
   PRIMARY KEY(id),
   FOREIGN KEY(idu) REFERENCES utilisateur(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE vehicule(
   id INT AUTO_INCREMENT,
   marque VARCHAR(255)  NOT NULL,
   modele VARCHAR(128)  NOT NULL,
   nb SMALLINT NOT NULL,
   caract TEXT NOT NULL,
   photo VARCHAR(64)  NOT NULL,
   dispo BOOLEAN NOT NULL,
   PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO vehicule (id, marque, modele, nb, caract, photo, dispo) VALUES
(1, 'Peugeot', '208', 2, '[\'Moteur\':\'Essence\',\'Portes\':\'5\',\'Puissance\':\'5CV\',\'Boite\':\'Mecanique\']', 'peugeot_208', 1),
(2, 'Peugeot ', '308', 6, '[\'Moteur\':\'Diesel\',\'Portes\':\'5\',\'Puissance\':\'6CV\',\'Boite\':\'Automatique\']', 'peugeot_308', 1),
(3, 'Porsche ', 'Porsche ', 1, '[\'Moteur\':\'Essence\',\'Portes\':\'2\',\'Puissance\':\'26CV\',\'Boite\':\'Automatique\']', 'porsche_911', 1),
(4, 'Porsche ', 'Cayenne ', 2, '[\'Moteur\':\'Essence\',\'Portes\':\'5\',\'Puissance\':\'33CV\',\'Boite\':\'Automatique\']', 'porsche_cayenne', 1),
(5, 'Audi ', 'R8', 1, '[\'Moteur\':\'Essence\',\'Portes\':\'2\',\'Puissance\':\'46CV\',\'Boite\':\'Automatique\']', 'audi_r8', 0),
(6, 'Renault', 'ZOE', 3, '[\'Moteur\':\'Electrique\',\'Portes\':\'5\',\'Puissance\':\'4CV\',\'Boite\':\'Automatique\']', 'renault_zoe', 1);

CREATE TABLE facture(
   id BIGINT AUTO_INCREMENT,
   idu INT NOT NULL,
   idv INT NOT NULL,
   date_d DATE,
   date_f DATE NOT NULL,
   valeur DECIMAL(19,4) NOT NULL,
   etat_r BOOLEAN NOT NULL,
   PRIMARY KEY(id, date_d),
   FOREIGN KEY(idu) REFERENCES utilisateur(id),
   FOREIGN KEY(idv) REFERENCES vehicule(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;