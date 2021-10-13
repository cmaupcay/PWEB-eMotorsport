<?php
    include_once 'modele/Modele.php';

    class Client extends Modele
    {
        private $_id;
        public function id() : ?int { return $this->_id; }
        
        public $nom;
        public $pseudo;
        public $email;
        public $nom_e;
        public $adresse_e;

        public function depuis_json(string $json)
        {
            $json = json_decode($json, true);
            $this->nom = isset($json['nom']) ? $json['nom'] : null;
            $this->pseudo = isset($json['pseudo']) ? $json['pseudo'] : null;
            $this->email = isset($json['email']) ? $json['email'] : null;
            $this->nom_e = isset($json['nom_e']) ? $json['nom_e'] : null;
            $this->adresse_e = isset($json['adresse_e']) ? $json['adresse_e'] : null;
        }
    }

?>