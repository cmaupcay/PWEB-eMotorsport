<?php
    include_once 'modele/Modele.php';

    class BD extends Modele
    {
        public function informations(): array
        { return ['driver', 'domaine', 'port', 'base', 'id', 'accepter_hors_ligne', 'debug']; }
        private const INI = 'ini/bd.ini';
        
        protected $_driver;                                             // Nom du driver SQL (ex: mysql)
        public function driver() : ?string { return $this->_driver; }
        protected $_domaine;                                            // Nom du domaine (ex: localhost)
        public function domaine() : ?string { return $this->_domaine; }
        protected $_port;                                               // Port (peut être null)
        public function port() : ?int { return $this->_port; }
        protected $_base;                                               // Nom de la base de données
        public function base() : ?string { return $this->_base; }
        protected $_id;                                                 // Nom d'utilisateur
        public function id() : ?string { return $this->_id; }
        
        // PARAMETRES
        protected $_accepter_hors_ligne;                                // Les erreurs de connexion ne sont plus fatales
        public function accepter_hors_ligne() : ?bool { return $this->_accepter_hors_ligne; }
        protected $_debug;                                              // Active l'affichage des informations de débogage
        public function debug() : ?bool { return $this->_debug; }

        private $_pdo;
        private function _initialiser_pdo(string $mdp) : bool
        {                                                               // Initialise la connexion PDO
            try 
            {
                $this->_pdo = new PDO(
                    $this->_driver .
                    ':host=' . $this->_domaine .
                    (($this->_port !== null) ? ';port=' . $this->_port : '') .
                    ';dbanme=' . $this->_base,
                    $this->_id, $mdp
                );
            }
            catch (\PDOException $err)                                  // Erreur de connexion
            {
                if ($this->_debug)
                {                                                       // Informations de débogage
                    print("<p class=\"erreur\">La base de données est injoignable.<br>\n");
                    print($this->json(true, ['debug']) . "</p>");
                }
                if (!$this->_accepter_hors_ligne) throw $err;
                return false;
            }
            return true;
        }
        public function __construct(?string $fichier_ini = null)
        {
            if ($fichier_ini === null)                                  // Initialisation des attributs depuis un fichier INI
                $fichier_ini = self::INI;
            $ini = $this->depuis_ini($fichier_ini);
            $this->_initialiser_pdo($ini['mdp']);
        }
    }

?>