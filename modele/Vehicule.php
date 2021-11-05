<?php
    include_once 'modele/Facture.php';

    class Vehicule extends ModeleBD
    {
        public function informations(): array
        { return ['id', 'typeV', 'marque', 'modele', 'nb', 'caract', 'photo', 'dispo']; }
        public function table() : string { return 'vehicule'; }

        public function __construct(?int $id = null, ?BD &$bd = null)
        { parent::__construct($id, $bd); }
        
        private $_typeV;
        public function typeV() : ?string { return $this->_typeV; }
        public function modifier_typeV(?string $valeur) { $this->_typeV = $valeur; }
        private $_marque;
        public function marque() : ?string { return $this->_marque; }
        public function modifier_marque(?string $valeur) { $this->_marque = $valeur; }
        private $_modele;
        public function modele() : ?string { return $this->_modele; }
        public function modifier_modele(?string $valeur) { $this->_modele = $valeur; }
        private $_nb;
        public function nb() : ?int { return $this->_nb; }
        public function modifier_nb(?int $valeur) { $this->_nb = $valeur; }
        private $_caract;
        public function caract() : ?array { return $this->_caract; }
        public function modifier_caract(?string $valeur) { $this->_caract = json_decode($valeur, true); }
        private $_photo;
        public function photo() : ?string { return $this->_photo; }
        public function modifier_photo(?string $valeur) { $this->_photo = $valeur; }
        private $_dispo;
        public function dispo() : ?bool { return $this->_dispo; }
        public function modifier_dispo(?bool $valeur) { $this->_dispo = $valeur; }

        public function est_loue(BD $bd) : bool
        {
            if ($this->id() !== null)
            {
                // Vérifier s'il existe une facture pour ce véhicule et dont la date de fin de location n'est pas encore passée.
                $f = (new Facture())->selection($bd, ['idv'], 'idv = ' . $this->id() . ' AND date_f >= CURRENT_TIME', true);
                return (count($f) === 1);
            }
            return false;
        }
    }

?>