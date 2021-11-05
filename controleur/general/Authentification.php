<?php
    require_once 'controleur/Controleur.php';
    include_once 'modele/JetonAuthentification.php';
    include_once 'modele/Cookie.php';

    class Authentification extends _Controleur
    {
        public function informations(): array
        { return [
            'vue_connexion', 'vue_interdit', 
            'identifiant', 'cookie', 'nom_cookie', 
            'obligatoire', 'requise', 'interdite',
            'role_requis'
        ]; }

        protected $_vue_connexion;
        protected function vue_connexion() : string { return $this->_vue_connexion; }
        protected $_vue_interdit;
        protected function vue_interdit() : string { return $this->_vue_interdit; }

        protected $_identifiant;
        protected function identifiant() : string { return $this->_identifiant; }
        protected $_cookie;                  
        protected function cookie() : bool { return $this->_cookie; }
        protected $_nom_cookie;
        public function nom_cookie() : string { return $this->_nom_cookie; }
        protected $_obligatoire;                  // Définie si l'authentification doit toujours être active
        protected function obligatoire() : bool { return $this->_obligatoire; }
        protected function modifier_obligatoire(bool $valeur) { $this->_obligatoire = $valeur; }
        protected $_requise;                      // Définie les vues où l'authentification est requise
        protected function requise() : array { return $this->_requise; }
        protected $_interdite;                      // Définie les vues où l'authentification est interdite
        protected function interdite() : array { return $this->_interdite; }
        private const SEP_ROLES_REQUIS = ',';
        protected $_role_requis;                      // Définie les vues où l'authentification avec un role spécifique est requise
        protected function role_requis() : array { return $this->_role_requis; }

        private $_ini;
        private const ALGO_HASH = 'sha256';

        public function hash(string $mdp)
        { return hash(self::ALGO_HASH, $mdp); }

        public function __construct(string $fichier_ini)
        {
            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            parent::__construct($fichier_ini);
            $this->_ini = $fichier_ini;
        }

        protected function est_interdite(string $nom_vue) : bool
        { return (array_search($nom_vue, $this->_interdite ?? []) !== false); }
        protected function est_requise(string $nom_vue) : bool
        { return (array_search($nom_vue, $this->_requise ?? []) !== false); }
        protected function roles_autorises(string $nom_vue) : ?array
        {
            // Si aucun role n'est requis pour la vue, tout le monde est autorisé.
            if (!isset($this->_role_requis[$nom_vue])) return null;
            return explode(self::SEP_ROLES_REQUIS, $this->_role_requis[$nom_vue]);
        }
        protected function admin_est_requis(string $nom_vue) : bool
        { return (array_search($nom_vue, $this->_admin_requis ?? []) !== false); }
        protected function prop_est_requis(string $nom_vue) : bool
        { return (array_search($nom_vue, $this->_prop_requis ?? []) !== false); }

        const FORMULAIRE = 'form_auth';
        const CLE_ID = 'auth_id';
        const CLE_ID_CONNEXION = 'auth_psd';
        const CLE_MDP = 'auth_mdp';
        const CLE_COOKIE = 'auth_cookie';
        const CLE_DECO = 'auth_fin';

        private function _connexion(array &$post, array &$session, BD &$bd) : bool
        {
            if (isset($post[self::FORMULAIRE], $post[self::CLE_ID_CONNEXION], $post[self::CLE_MDP]))
            {
                ($u = new Utilisateur())->depuis_tableau([$this->_identifiant => $post[self::CLE_ID_CONNEXION]]);
                if ($u->existe($bd, $this->_identifiant))      // L'utilisateur existe dans la base de données
                {
                    $mdp = $u->mdp($bd, $this->_identifiant);
                    if ($mdp != null)
                        if ($this->hash($post[self::CLE_MDP]) === $mdp)
                        {
                            unset($post[self::CLE_MDP]);
                            $u->recevoir($bd, $this->_identifiant, ['id']);
                            $session[self::CLE_ID] = $u->id();
                            return true;
                        }
                }
                unset($post[self::CLE_MDP]);
            }
            return false;
        }

        public function deconnexion(array &$session, string $ip, ?BD &$bd)
        {
            unset($session[self::CLE_ID]);
            if ($this->_cookie) // On supprime tous les cookies associés à l'adresse IP
                Cookie::effacer($bd, $ip, $this->nom_cookie());
        }
        public function jeton(array &$session, array &$post, array &$cookie, string $ip, BD &$bd) : JetonAuthentification
        {
            $id = null;
            if ($this->_cookie && isset($cookie[$this->nom_cookie()]))
                $id = Cookie::lire($bd, $cookie[$this->nom_cookie()], $ip);
            if ($id === null)
                $id = $session[self::CLE_ID] ?? null;
            if ($id === null)
            {
                if ($this->_connexion($post, $session, $bd))
                {
                    $id = $session[self::CLE_ID];
                    if ($this->_cookie && isset($post[self::CLE_COOKIE]))
                        Cookie::ecrire($bd, $id, $ip, $this->nom_cookie());
                }
                else if (isset($post[self::FORMULAIRE], $post[self::CLE_ID_CONNEXION])) // Connexion échouée
                {
                    ($j = new JetonAuthentification())->depuis_tableau([$this->_identifiant => $post[self::CLE_ID_CONNEXION]]);
                    return $j;
                }
            }
            if ($id != null && isset($post[self::CLE_DECO]))
            {
                $this->deconnexion($session, $ip, $bd);
                $id = null;
            }
            return new JetonAuthentification($this->_ini, $id, $bd);
        }

        public function verifier_droits(string $vue, JetonAuthentification $auth, Routeur $routeur) : string
        {
            if ($auth->valide() && ($vue === $this->vue_connexion() || $this->est_interdite($vue)))
                $routeur->redirection(null);
            $roles_autorises = $this->roles_autorises($vue);
            if ($roles_autorises !== null)
            {
                $autorise = false;
                foreach ($roles_autorises as $role)
                    if ($autorise = $auth->est_du_role($role)) break;
                if (!$autorise) return $this->vue_interdit();
            }
            if (!$auth->valide() && ($this->obligatoire() || $this->est_requise($vue)))
                return $this->vue_connexion();
            return $vue;
        }
    }

?>