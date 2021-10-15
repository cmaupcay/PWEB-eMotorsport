<?php
    include_once 'modele/ModeleBD.php';

    class Utilisateur extends ModeleBD
    {
        public function informations(): array
        { return ['id', 'nom', 'pseudo', 'email', 'nomE', 'adresseE']; }
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
        private $_nomE;
        public function nomE() : ?string { return $this->_nomE; }
        public function modifier_nomE(?string $valeur) { $this->_nomE = $valeur; }
        private $_adresseE;
        public function adresseE() : ?string { return $this->_adresseE; }
        public function modifier_adresseE(?string $valeur) { $this->_adresseE = $valeur; }
    }

?>