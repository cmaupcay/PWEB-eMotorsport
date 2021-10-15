<?php
    include_once 'modele/Utilisateur.php';

    // A REFAIRE
    class JetonAuthentification extends Utilisateur
    {
        private const CLE_ADMIN = 'id_admin';
        private $_admin;
        public function admin() : ?bool { return $this->_admin; }

        private $_valide;                                                   // Validité de l'authentification
        public function valide() : bool { return $this->_valide; }
        
        public function __construct(?string $fichier_ini = null, ?int $id = null, ?BD &$bd = null)                      
        {
            if ($fichier_ini === null) { $this->_valide = false; return; } 
            parent::__construct($id, $bd);
            $this->_valide = !is_null($id);
            if ($this->_valide)
            {
                if (!($ini = parse_ini_file($fichier_ini)) || !isset($ini[self::CLE_ADMIN]))
                    throw new Exception("Impossible d'initialiser le jeton depuis le fichier \"$fichier_ini\".");
                $this->_admin = ($ini[self::CLE_ADMIN] == $this->_id);
            }         
        }
    }

?>