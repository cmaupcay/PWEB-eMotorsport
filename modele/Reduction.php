<?php
    require_once 'modele/Modele.php';

    class Reduction extends Modele
    {
        const DOSSIER = 'ini/reductions/';

        public function informations(): array
        { return ['condition', 'valeur']; }

        protected $_condition;
        public function condition() { return $this->_condition; }
        protected $_valeur;
        public function valeur() : float { return $this->_valeur; }

        public function __construct(string $fichier)
        { 
            $fichier = self::DOSSIER . $fichier . '.json';
            if (file_exists($fichier))
                if ($this->depuis_json(file_get_contents($fichier))) return;
            throw new Exception("Le fichier de réduction \"$fichier\" n'existe pas.");
        }
    }
?>