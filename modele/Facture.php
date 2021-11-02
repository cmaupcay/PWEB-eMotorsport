<?php
    include_once 'modele/ModeleBD.php';

    class Facture extends ModeleBD
    {
        public function informations(): array
        { return ['id', 'ide', 'idv', 'date_d', 'date_f', 'valeur', 'etat_r']; }
        public function table() : string { return 'facture'; }

        public function __construct(?int $id = null, ?BD &$bd = null)
        { parent::__construct($id, $bd); }
        
        private $_ide;
        public function ide() : ?int { return $this->_ide; }
        private $_idv;
        public function idv() : ?int { return $this->_idv; }
        private $_date_d;
        public function date_d() : ?DateTime { return $this->_date_d; }

        private $_date_f;
        public function date_f() :? DateTime { return $this->_date_f; }
        public function modifier_date_f(?DateTime $valeur) { $this->_date_f = $valeur; }
        private $_valeur;
        public function valeur() : ?float { return $this->_valeur; }
        public function modifier_valeur(?float $valeur) { $this->_valeur = $valeur; }
        private $_etat_r;
        public function etat_r() : ?bool { return $this->_etat_r; }
        public function modifier_etat_r(?bool $valeur) 
        { $this->_etat_r = $valeur; }
    }

?>