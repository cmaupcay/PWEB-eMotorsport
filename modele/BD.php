<?php
    include_once 'modele/Modele.php';

    class BD extends Modele
    {
        public function informations(): array
        { return ['driver', 'domaine', 'port', 'base', 'id', 'accepter_hors_ligne', 'debug']; }
        private const INI = 'modele/bd.ini';
        
        protected $_driver;
        public function driver() : ?string { return $this->_driver; }
        protected $_domaine;
        public function domaine() : ?string { return $this->_domaine; }
        protected $_port;
        public function port() : ?int { return $this->_port; }
        protected $_base;
        public function base() : ?string { return $this->_base; }
        protected $_id;
        public function id() : ?string { return $this->_id; }
        
        protected $_accepter_hors_ligne;
        public function accepter_hors_ligne() : ?bool { return $this->_accepter_hors_ligne; }
        protected $_debug;
        public function debug() : ?bool { return $this->_debug; }

        private $_pdo;
        private function _initialiser_pdo(string $mdp) : bool
        {
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
            catch (\PDOException $err) 
            {
                if ($this->_debug)
                {
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
            if ($fichier_ini === null) 
                $fichier_ini = self::INI;
            if (!($ini = parse_ini_file($fichier_ini, true)))
                throw new Exception('Impossible d\'initialiser la base de données.');
            $this->depuis_tableau($ini);
            $this->_initialiser_pdo($ini['mdp']);
        }
    }

?>