<?php
    require_once 'controleur/Vues.php';
    require_once 'controleur/Routeur.php';
    require_once 'controleur/Authentification.php';

    class App extends _Controleur
    {
        public function informations(): array
        { return [
            'debug', 'vue_erreur', 'vue_debug',
            'ini_bd', 'ini_routeur', 'ini_auth', 'ini_vues'
        ]; }

        private $_debug;
        public function debug() : bool { return $this->_debug; }
        protected function modifier_debug(bool $valeur) { $this->_debug = $valeur; }
        protected $_vue_erreur;
        public function vue_erreur() : string { return $this->_vue_erreur; }
        protected $_vue_debug;
        public function vue_debug() : string { return $this->_vue_debug; }

        protected $_ini_bd;
        private $_bd;
        public function bd() : BD { return $this->_bd; }
        protected $_ini_routeur;
        private $_routeur;
        public function routeur() : Routeur { return $this->_routeur; }
        protected $_ini_auth;
        private $_auth;
        public function auth() : Authentification { return $this->_auth; }
        protected $_ini_vues;
        private $_vues;
        public function vues() : Vues { return $this->_vues; }

        public function __construct(string $fichier_ini)
        {
            parent::__construct($fichier_ini);
            $this->_bd = new BD($this->_ini_bd);
            $this->_auth = new Authentification($this->_ini_auth);
            $this->_routeur = new Routeur($this->_ini_routeur);
            $this->_vues = new Vues($this->_ini_vues);   
        }

        public function proceder(array &$server, array &$session, array &$post, array &$get, array &$cookie)
        {
            try 
            {
                $_JETON = $this->_auth->jeton($session, $post, $cookie, $server['REMOTE_ADDR'], $this->_bd);   
                // On récupère le jeton d'authentification
            
                $vue = $this->_routeur->definir_vue($server['REQUEST_URI'], $this->_auth);                // Définir la vue
                $vue = $this->_auth->verifier_droits($vue, $_JETON, $this->_routeur);
            
                $controleurs = $this->_routeur->charger_controleurs($vue);
                foreach ($controleurs as $c)
                    $c->executer(
                        $session, $post, $get,
                        $this->_bd, $this->_auth, $this->_auth, $_JETON
                    );
            
                $this->_vues->charger($vue, $post, $get, $_JETON);                                        // Charger la vue
            }
            catch (\Throwable $e) 
            {
                if ($this->debug())
                    $this->_vues->charger($this->_vue_debug, $post, $get, null, [$e]);
                else $this->_vues->charger($this->_vue_erreur, $post, $get, null, [$e]);
            }
        }
    }