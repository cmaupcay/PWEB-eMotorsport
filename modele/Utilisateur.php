<?php
    include_once 'modele/ModeleBD.php';

    class Utilisateur extends ModeleBD
    {
        public function informations(): array
        { return ['id', 'nom', 'pseudo', 'email', 'nom_e', 'adresse_e']; }
        public function table(): string { return 'utilisateur'; }

        public function __construct(?int $id = null, ?BD &$bd = null)
        { parent::__construct($id, $bd); }

        private $_nom;
        public function nom() : ?string { return $this->_nom; }
        public function modifier_nom(?string $valeur) { $this->_nom = $valeur; }
        private $_pseudo;
        public function pseudo() : ?string { return $this->_pseudo; }
        public function modifier_pseudo(?string $valeur) { $this->_pseudo = $valeur; }
        private $_email;
        public function email() : ?string { return $this->_email; }
        public function modifier_email(?string $valeur) : bool
        {
            if ($accepte = filter_var($valeur, FILTER_VALIDATE_EMAIL))
                $this->_email = $valeur;
            return $accepte;
        }
        private $_nom_e;
        public function nom_e() : ?string { return $this->_nom_e; }
        public function modifier_nom_e(?string $valeur) { $this->_nom_e = $valeur; }
        private $_adresse_e;
        public function adresse_e() : ?string { return $this->_adresse_e; }
        public function modifier_adresse_e(?string $valeur) { $this->_adresse_e = $valeur; }

        public function mdp(BD &$bd, string $param = 'id') : ?string
        {
            $res = $bd->executer(
                "SELECT mdp FROM " . $this->table() . " WHERE $param = :$param",
                [$param => $this->{$param}()]
            );
            if (isset($res[0], $res[0]['mdp']))
                return $res[0]['mdp'];
            return null;
        }
    }

?>