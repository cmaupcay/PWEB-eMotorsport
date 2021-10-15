<?php
    include_once 'modele/Utilisateur.php';

    // A REFAIRE
    class JetonAuthentification extends Utilisateur
    {
        // TYPE D'AUTHENTIFICATION
        const ADMIN = 0;
        const ENTREPRISE = 1;
        const LOUEUR = 2;

        private $_type;                                                     // Type de l'authentification
        public function type() : ?int { return $this->_type; }

        private $_valide;                                                   // Validité de l'authentification
        public function valide() : bool { return $this->_valide; }
        public function __construct(?int $id = null, ?BD &$bd = null)                      
        { 
            parent::__construct($id, $bd);
            $this->_valide = !is_null($id);
            $this->_type = 2;
        }
    }

?>