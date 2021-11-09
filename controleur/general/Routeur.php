<?php
    include_once 'controleur/Controleur.php';

    class Routeur extends _Controleur
    {
        public function informations(): array
        { return [
            'index', 'control',
            'args',
            'ierr', 'icss', 'ijs', 'imedia'
        ]; }
         
        /// PARAMETRES
        // Nom de la vue par défaut
        protected $_index;
        public function index() : string { return $this->_index; }
        protected function modifier_strict(bool $valeur) { $this->_strict = $valeur; }
        // Clé d'indication d'une erreur
        protected $_ierr;
        public function ierr() : string { return $this->_ierr; }
        // Clé d'appel d'un fichier CSS
        protected $_icss;
        public function icss() : string { return $this->_icss; }
        // Clé d'appel d'un fichier CSS
        protected $_ijs;
        public function ijss() : string { return $this->_ijs; }
        // Clé d'appel d'un fichier multimédia
        protected $_imedia;
        public function imedia() : string { return $this->_imedia; }
        // Liste des associations vue / controleur
        protected $_control;
        public function control() : array { return $this->_control; }
        // Liste des vues acceptants des arguments
        protected $_args;
        protected function est_URI_argumente(string $uri) : ?string
        {
            if ($this->_args !== null)
            {
                $longueur = strlen($uri);
                foreach ($this->_args as $vue)
                {
                    if ($longueur > strlen($vue) + 1 && strpos($uri, $vue) === 0) return $vue;
                }
            }
            return null;
        }
        public static function extraire_arguments(string $uri, string $vue) : array
        {
            $args = explode('/', substr($uri, strlen($vue) + 1));
            if (strlen($args[0]) === 0) return [];
            // Sécurisation face aux injections SQL
            foreach ($args as &$arg)
            {
                $arg = urldecode($arg);
                $arg = str_replace('-', ' ', $arg);
                $arg = str_replace(['"','\'',';'], '', $arg);
                $arg = utf8_decode($arg);
            }
            return $args;
        }

        /// APPEL RESSOURCES
        // Chargement d'un fichier
        private function _charger_fichier(string $fichier, ?string $type = null)
        {
            if (strlen($fichier) > 0 && $fichier[-1] !== '/' && file_exists($fichier))
            {
                // Changer le type MIME du contenu
                if ($type === null)
                    header('Content-type: ' . mime_content_type($fichier));
                else 
                    header('Content-type: ' . $type);
                include $fichier;
                die();
            }
        }
        // Chargement d'un fichier CSS
        private function _charger_css(string $fichier)
        {
            // Les fichiers CSS ne sont chargés que depuis la racine '/vue/style/' !
            // L'extension de fichier '.css' ne doit pas être spécifiée.
            $fichier = 'vue/style/' . $fichier . '.css';
            $this->_charger_fichier($fichier, 'text/css');
        }
        // Chargement d'un fichier JavaScript
        private function _charger_js(string $fichier)
        {
            // Les fichiers JavaScript ne sont chargés que depuis la racine '/js/' !
            // L'extension de fichier '.js' ne doit pas être spécifiée.
            $fichier = 'js/' . $fichier . '.js';
            $this->_charger_fichier($fichier, 'text/javascript');
        }
        // Chargement d'un fichier multimédia
        private function _charger_media(string $fichier)
        {
            // Les médias ne sont chargés que depuis la racine '/vue/media/' !
            // L'extension de fichier doit être spécifiée.
            $fichier = 'media/' . $fichier;
            $this->_charger_fichier($fichier);
        }

        /// ROUTAGE
        // Gestion des appels de ressource
        private function _appel_ressource(string $uri) : string
        {
            // Récupération de la clé de type de ressource et du nom de la ressource
            // Au format <cle_type>=<nom> dans l'URI
            $var = explode('=', substr($uri, 1), 2);
            // Vérifier que le format est respecté
            if (count($var) === 2)
            {   
                // TYPES D'APPEL RESSOURCE
                switch ($var[0]) 
                {
                // Spécifiaction d'une erreur
                case $this->_ierr:
                    // Retourner <cle_erreur>/<nom_erreur>
                    return implode('/', $var);
                // Chargement d'un fichier multimédia
                case $this->_imedia:
                    $this->_charger_media($var[1]);
                    break;
                // Chargement d'un fichier CSS
                case $this->_icss:
                    $this->_charger_css($var[1]);
                    break;
                // Chargement d'un fichier JavaScript
                case $this->_ijs:
                    $this->_charger_js($var[1]);
                    break;
                // Type inconnu : redirection vers la page d'erreur 404
                default:
                    break;
                }
            }
            return $this->_ierr . '=404';
        }
        // Définition de la vue selon l'URI
        public function definir_vue(string $uri, array &$params) : string
        {
            // Retirer le premier '/'
            $uri = substr($uri, 1);
            // Si la requête est vide, renvoyer à la page par défaut
            if (strlen($uri) === 0) return $this->_index;
            // Retirer le dernier '/' si il existe (c'est un appel de vue)
            if ($uri[-1] === '/') $uri = substr($uri, 0, -1);
            else 
            {
                // Sinon, vérifier que c'est un appel de ressource.
                if ($uri[0] === '?') return $this->_appel_ressource($uri);
                // Si ce n'en est pas un, renvoyer vers la page d'erreur 404.
                return $this->_ierr . '=404';
            }
            // Vérifier si c'est une vue argumentée
            if (($vue = $this->est_URI_argumente($uri)) !== null)
            {
                // Extraire les arguments de l'URI et les ajouter les arguments de l'URI au tableau des paramètres
                $params[URI] = self::extraire_arguments($uri, $vue);
                // Retourner la vue ayant acceptée les arguments
                return $vue;
            }
            // APPEL DE RESSOURCE
            // Retourner la vue définie
            return $uri;
        }
        // Chargement des controleurs associés à une vue
        public function charger_controleurs(string $vue) : array
        {
            // Par défaut, aucun controleur n'est chargé.
            $c_general = []; $c_vue = [];
            // Si des controleurs généraux son spécifiés, on les charges.
            // Les controleurs généraux s'appliquent à toutes les pages.
            if (isset($this->_control[0])) $c_general = explode(',', $this->_control[0]);
            // Si des controleurs sont spécifiés pour cette vue, on les charges.
            if (isset($this->_control[$vue])) $c_vue = explode(',', $this->_control[$vue]);
            // Fusionner les deux listes de noms de controleurs obtenus et supprimer les doublons.
            $c_noms = array_unique(array_merge($c_general, $c_vue));

            // Transformer la liste de nom en liste d'objet Controleur
            $c = [];
            foreach ($c_noms as $n)
            {
                // Transformer le nom de controleur en nom de fichier
                $fichier = 'controleur/' . $n . '.php';
                try
                {
                    // Vérifier que le fichier existe
                    if (!file_exists($fichier)) throw null;
                    // Inclure le fichier
                    require_once $fichier;
                    // Instancier le Controleur dans le tableau
                    $c[] = new $n();
                }
                // Si le controleur est introuvable, erreur fatale !
                catch (\Throwable $th) { throw new Error('Le controleur "' . $n . '" est introuvable.'); }
            }
            return $c;
        }
        // Redirection vers une vue
        // Si $vue === null, la redirection se fera sur la vue par défaut.
        public function redirection(?string $vue)
        { header('Location: /' . (is_null($vue) ? '' : $vue . '/')); die('Redirection...'); }
    }

?>