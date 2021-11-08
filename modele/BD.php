<?php
    include_once 'modele/Modele.php';

    class BD extends Modele
    {
        public function informations(): array
        { return [
            'driver', 'domaine', 'port', 'base', 'id',
            'accepter_vidage_table',
            'accepter_hors_ligne', 'debug'
        ]; }
        
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
        protected $_accepter_vidage_table;                                // Defini l'acceptation des requetes "DELETE FROM ... WHERE 1"
        public function accepter_vidage_table() : ?bool { return $this->_accepter_vidage_table; } // (verif dans les modèles)
        // DEV
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
                    ';dbname=' . $this->_base,
                    $this->_id, $mdp
                );
            }
            catch (\PDOException $err)                                  // Erreur de connexion
            {
                $this->_gerer_erreur_PDO($err);
                return false;
            }
            return true;
        }
        private function _gerer_erreur_PDO(\PDOException $erreur)
        {
            if ($this->_debug)
            {                                                       // Informations de débogage
                print("<p class=\"erreur\">" . utf8_encode($erreur->getMessage()) . "<br>\n");
                print($this->json(true, ['debug']) . "</p>");
            }
            if (!$this->_accepter_hors_ligne) throw $erreur;
        }

        public function __construct(string $fichier_ini)
        {
            $ini = $this->depuis_ini($fichier_ini);
            $this->_initialiser_pdo($ini['mdp'] ?? '');
        }

        public function executer(string $sql, array $params = []) : ?array
        {
            if ($this->_pdo === null)
            {
                $this->_gerer_erreur_PDO(
                    new PDOException("Impossible d'executer la requête \"" . $sql . "\" car la base de donnèes n'est pas initialisée.")
                );
            }
            $statut = $this->_pdo->prepare($sql);
            foreach ($params as $cle => $val)
            {
                $type = PDO::PARAM_STR;
                if (is_array($val)) $val = json_encode($val);
                if (is_string($val)) $val = utf8_decode($val);
                else if (is_int($val) || is_float($val)) $type = PDO::PARAM_INT;
                else if (is_bool($val)) $type = PDO::PARAM_BOOL;
                else if ($val !== null) throw new Exception("Type non pris en charge : " . var_dump($val));
                $statut->bindValue($cle, $val, $type);
            }
            if ($statut->execute())
                return $statut->fetchAll(PDO::FETCH_ASSOC);
            $this->_gerer_erreur_PDO(new PDOException($statut->errorInfo()[2]));
            return null;
        }
    }

?>