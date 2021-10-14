<?php
    include_once 'modele/Modele.php';

    class Routeur extends Modele
    {
        public function informations(): array
        { return ['index', 'ierr']; }
        private const INI = 'ini/routeur.ini';

        public function __construct(?string $fichier_ini = null)
        {
            if ($fichier_ini === null) $fichier_ini = self::INI;             // Initialisation des attributs depuis un fichier INI
            $this->depuis_ini($fichier_ini);
        }
        
        protected $_index;                          // Nom de la vue par défaut
        public function index() : string { return $this->_index; }
        protected $_ierr;                           // Clé d'indication du code erreur
        public function ierr() : string { return $this->_ierr; }
     
        public function definir_vue(array $requete) : string
        {
            if (count($requete) === 0)              // La requête est vide
                return $this->_index;                // On chargera la vue par défaut
                
            $vue = key($requete);                   // Récupération du nom de la vue (premiere clé du tableau de la requête)
            if ($vue === $this->_ierr)   // Spécification d'une erreur
                return $vue . '/' . $requete[$vue];
            return $vue;
        }
    }

?>