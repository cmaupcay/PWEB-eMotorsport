<?php
    include_once 'modele/ModeleBD.php';

    class Vehicule extends ModeleBD
    {
        public function informations(): array
        { return ['id', 'type', 'nb', 'caract', 'photo', 'etatL']; }
        public function table() : string { return 'vehicule'; }

        private $_id;
        public function id() : ?int { return $this->_id; }

        private $_type;
        public function type() : ?string { return $this->_type; }
        public function modifier_type(?string $valeur) { $this->_type = $valeur; }
        private $_nb;
        public function nb() : ?int { return $this->_nb; }
        public function modifier_nb(?float $valeur) { $this->_nb = $valeur; }
        private $_caract;
        public function caract() : ?array { return $this->_caract; }
        public function modifier_caract(?string $valeur) { $this->_caract = $valeur; }
        private $_photo;
        public function photo() : ?string { return $this->_photo; }
        public function modifier_photo(?string $valeur) { $this->_type = $valeur; }
        private $_etat_l;
        public function etat_l() : ?bool { return $this->_etat_l; }
        public function modifier_etat_l(?bool $valeur) 
        { $this->_etat_l = $valeur; }
    }

?>