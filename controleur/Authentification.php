<?php

    class TAuthentification
    {
        // TYPE D'AUTHENTIFICATION
        const ADMIN = 0;
        const ENTREPRISE = 1;
        const LOUEUR = 2;

        private $_type;                                                     // Type de l'authentification
        public function type() : ?int { return $this->_type; }

        private $_valide;                                                   // Validité de l'authentification
        public function est_valide() : bool { return $this->_valide; }
        public function __construct(?int $type = null)                      
        {
            $this->_valide = !is_null($type);                               // A REFAIRE
            $this->_type = $type;                                           // Si l'authentification échoue, aucun type n'est définie
        }
    }

    abstract class Authentification
    {
        const VUE = 'auth';                     // Vue chargée lorsqu'une authentification échoue

        const OBLIGATOIRE = false;              // Définie si l'authentification doit toujours être active
        const REQUISE = [                       // Définie les vues où l'authentification est requise
        ];

        static public function requise(string $nom_vue) : bool
        {
            // Recherche la vue dans les vues demandant l'authentification
            foreach (self::REQUISE as $vue)
                if ($vue === $nom_vue) return true;
            return false;
        }

        static public function verifier(array $session) : TAuthentification
        {
            return new TAuthentification();
        }
    }

?>