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
('admin', 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'admin@emotorsport.fr', '', ''),
('Phillipe Maurice', 'pmaurice', '42dc83019a35f6b82f12bf522ee89ee144bbc7a41f656642f2a125d6e60223f5', 'pmaurice@emotorsport.fr', 'eMotorsport', '141 avenue de Versailles, Paris 75016');

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
('voiture', 'Peugeot', '208', 2, '{"Moteur":"Essence","Portes":"5","Puissance":"5CV","Boite":"Mecanique"}', '/?media=peugeot_208.jpg', 1),
('voiture', 'Peugeot ', '308', 6, '{"Moteur":"Diesel","Portes":"5","Puissance":"6CV","Boite":"Automatique"}', '/?media=peugeot_308.jpg', 1),
('voiture', 'Porsche ', '911 ', 1, '{"Moteur":"Essence","Portes":"2","Puissance":"26CV","Boite":"Automatique"}', '/?media=porsche_911.jpg', 1),
('voiture', 'Porsche ', 'Cayenne ', 2, '{"Moteur":"Essence","Portes":"5","Puissance":"33CV","Boite":"Automatique"}', '/?media=porsche_cayenne.jpg', 1),
('voiture', 'Audi ', 'R8', 1, '{"Moteur":"Essence","Portes":"2","Puissance":"46CV","Boite":"Automatique"}', '/?media=audi_r8.jpg', 0),
('voiture', 'Renault', 'ZOE', 3, '{"Moteur":"Electrique","Portes":"5","Puissance":"4CV","Boite":"Automatique"}', '/?media=renault_zoe.jpg', 1);

CREATE TABLE facture(
   id BIGINT AUTO_INCREMENT,
   idu INT NOT NULL,
   idv INT NOT NULL,
   date_d DATE,
   date_f DATE NOT NULL,
   valeur DECIMAL(19,4),
   etat_r BOOLEAN NOT NULL,
   PRIMARY KEY(id, date_d),
   FOREIGN KEY(idu) REFERENCES utilisateur(id),
   FOREIGN KEY(idv) REFERENCES vehicule(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;