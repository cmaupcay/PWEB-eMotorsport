<?php
    include_once 'modele/Utilisateur.php';

    class JetonAuthentification extends Utilisateur
    {
        private const CLE_ROLE = 'role';
        private const SEP_ROLE = ',';
        private $_roles;
        public function roles() : array { return $this->_roles; }
        public function est_du_role(string $role) : bool
        {
            if ($this->_roles === null) return false;
            return array_search($role, $this->_roles, true) !== false;
        }
        public static function liste_roles(int $id, array $ini) : array
        {
            if (!isset($ini[self::CLE_ROLE])) return [];
            $roles = [];
            foreach ($ini[self::CLE_ROLE] as $role => $ids)
            {
                $ids = explode(self::SEP_ROLE, $ids);
                if (array_search($id, $ids) !== false) $roles[] = $role;
            }
            return $roles;
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
                if (!($ini = parse_ini_file($fichier_ini)))
                    throw new Exception("Impossible d'initialiser le jeton depuis le fichier \"$fichier_ini\".");
                $this->_roles = self::liste_roles($id, $ini);
            }
            else $this->_roles = [];
        }
    }

?>