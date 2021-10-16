<?php
    include_once 'modele/Utilisateur.php';

    class JetonAuthentification extends Utilisateur
    {
        private const CLE_ADMIN = 'id_admin';
        private $_admin;
        public function admin() : bool 
        {
            if (!$this->_admin) return false;   // Si il n'est pas admin, faux
            return $this->valide();             // Si il l'est, on vérifie que le jeton est valide
        }
        private const CLE_PROP = 'id_prop';
        private $_prop;
        public function prop() : bool 
        {
            if (!$this->_prop) return false;   // Si il n'est pas propriétaire, faux
            return $this->valide();             // Si il l'est, on vérifie que le jeton est valide
        }

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
                $this->_prop = ($ini[self::CLE_PROP] == $this->_id);
            }         
        }
    }

?>