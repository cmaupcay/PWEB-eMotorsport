<?php
    include_once 'controleur/Controleur.php';

    class Routeur extends _Controleur
    {
        public function informations(): array
        { return ['index', 'control', 'ierr', 'icss']; }
        public function ini() : string { return 'ini/routeur.ini'; }
         
        protected $_index;                          // Nom de la vue par défaut
        public function index() : string { return $this->_index; }
        protected function modifier_strict(bool $valeur) { $this->_strict = $valeur; }
        protected $_ierr;                           // Clé d'indication du code erreur
        public function ierr() : string { return $this->_ierr; }
        protected $_icss;                           // Clé d'appel de CSS
        public function icss() : string { return $this->_icss; }
        protected $_control;
        public function control() : array { return $this->_control; }

        private function _charger_css(string $fichier)
        {
            header('Content-type: text/css');
            include 'vue/style/' . $fichier . '.css';
            die();
        }

        public function definir_vue(string $uri) : string
        {
            $uri = substr($uri, 1);                 // On retire le premier '/'
            if (strlen($uri) === 0)                 // La requête est vide
                return $this->_index;                  // On chargera la vue par défaut
            
            if ($uri[-1] == '/')             // Adaptation des requêtes de la forme */<vue>/
                $uri = substr($uri, 0, -1);             // On retire le dernier '/'
            else if ($uri[0] != '?')
                return $this->_ierr . '=404';

            if ($uri[0] == '?')         // CHARGEMENT DE RESSOURCES
            {
                $var = explode('=', substr($uri, 1), 2);
                if ($var[0] === $this->_ierr)   // Spécification d'une erreur
                    return implode('/', $var);
                else if ($var[0] === $this->_icss)   // Spécification d'une feuille de style
                    $this->_charger_css($var[1]);
                else return $this->_ierr . '=404';
            }

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