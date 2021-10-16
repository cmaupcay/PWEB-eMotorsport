<?php
    include_once 'controleur/Controleur.php';

    class Routeur extends _Controleur
    {
        public function informations(): array
        { return ['index', 'control', 'strict', 'ierr']; }
        public function ini() : string { return 'ini/routeur.ini'; }
        
        protected $_index;                          // Nom de la vue par défaut
        public function index() : string { return $this->_index; }
        protected $_strict;                          // Si vrai, seule les requêtes de la forme */<vue>/ seront acceptées
        public function strict() : bool { return $this->_strict; }
        protected function modifier_strict(bool $valeur) { $this->_strict = $valeur; }
        protected $_ierr;                           // Clé d'indication du code erreur
        public function ierr() : string { return $this->_ierr; }
        protected $_control;
        public function control() : array { return $this->_control; }

        public function definir_vue(string $uri) : string
        {
            $uri = substr($uri, 1);                 // On retire le premier '/'
            if (strlen($uri) === 0)                 // La requête est vide
                return $this->_index;                  // On chargera la vue par défaut
            if 
            (   // MODE STRICT
                ((
                    $uri[0] == '?'                                  // Appel GET
                    && (strpos($uri, '+', 1) < strpos($uri, '=', 1))// 1er argument GET sans valeur 
                )
                || $uri[-1] !== '/')                                // Pas de slash a la fin
                && $this->_strict                               // Mode strict activé
            ) $uri = $this->_ierr . '=404';                     // => 404
            
            if ($uri[0] == '?')              // Adaptation des requêtes depuis GET
                $uri = substr($uri, 1);              // On retire le '?'
            if ($uri[-1] == '/')             // Adaptation des requêtes de la forme */<vue>/
                $uri = substr($uri, 0, -1);             // On retire le dernier '/'
            
            $err = explode('=', $uri, 2);
            if ($err[0] === $this->_ierr)   // Spécification d'une erreur
                return implode('/', $err);
            
            return $uri;
        }
        
        public function charger_controleurs(string $vue) : array
        {
            $c_general = []; $c_vue = [];
            if (isset($this->_control[0]))
                $c_general = explode(',', $this->_control[0]);
            if (isset($this->_control[$vue]))
            {
                $c_vue = explode(',', $this->_control[$vue]);
                var_dump($c_vue, $c_general);
                $c_noms = array_unique(array_merge($c_general, $c_vue));
                $c = [];
                foreach ($c_noms as $n)
                {
                    $fichier = 'controleur/' . $n . '.php';
                    try
                    {
                        if (!file_exists($fichier)) throw null;
                        require_once $fichier;
                        $c[] = new $n();
                    }
                    catch (\Throwable $th)
                    { throw new Error('Le controleur "' . $n . '" est introuvable.'); }
                }
                return $c;
            }
            return [];
        }

        public function redirection(?string $vue)
        { header('Location: /' . ($vue ?? '')); die('Redirection...'); }
    }

?>