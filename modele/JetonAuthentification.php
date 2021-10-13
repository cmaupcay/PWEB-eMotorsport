<?php
    include_once 'modele/Client.php';

    class JetonAuthentification extends Client
    {
        // TYPE D'AUTHENTIFICATION
        const ADMIN = 0;
        const ENTREPRISE = 1;
        const LOUEUR = 2;

        private $_type;                                                     // Type de l'authentification
        public function type() : ?int { return $this->_type; }

        private $_valide;                                                   // Validité de l'authentification
        public function valide() : bool { return $this->_valide; }
        public function __construct(?int $id = null)                      
        { // TEMPORAIRE
            $this->_valide = !is_null($id);
            $this->_id = $id;
            $this->_type = $id % (self::LOUEUR + 1);
        }
    }

?>