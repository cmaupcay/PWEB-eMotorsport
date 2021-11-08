<?php
    include_once 'modele/ModeleBD.php';

    class Facture extends ModeleBD
    {
        public function informations(): array
        { return ['id', 'idu', 'idv', 'date_d', 'date_f', 'valeur', 'etat_r']; }
        public function table() : string { return 'facture'; }

        public function __construct(?int $id = null, ?BD &$bd = null)
        { parent::__construct($id, $bd); }
        
        protected $_idu;
        public function idu() : ?int { return $this->_idu; }
        public function modifier_idu(?int $valeur) { $this->_idu = $valeur; }
        protected $_idv;
        public function idv() : ?int { return $this->_idv; }
        public function modifier_idv(?int $valeur) { $this->_idv = $valeur; }
        private $_date_d;
        public function date_d() : ?string { return $this->_date_d->format('Y-m-d'); }
        public function DT_date_d() : ?DateTime { return $this->_date_d; }
        public function modifier_date_d(?string $valeur) { $this->_date_d = new DateTime($valeur); }

        private $_date_f;
        public function date_f() : ?string { return ($this->_date_f === null) ? null : $this->_date_f->format('Y-m-d'); }
        public function DT_date_f() : ?DateTime { return $this->_date_f; }
        public function modifier_date_f(?string $valeur) { $this->_date_f = ($valeur === null) ? $valeur : new DateTime($valeur); }
        private $_valeur;
        public function valeur() : ?float { return $this->_valeur; }
        public function modifier_valeur(?float $valeur) { $this->_valeur = $valeur; }
        private $_etat_r;
        public function etat_r() : ?bool { return $this->_etat_r; }
        public function modifier_etat_r(?bool $valeur) 
        { $this->_etat_r = $valeur; }
    }

?>