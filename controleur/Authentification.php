<?php
    include_once 'modele/JetonAuthentification.php';
    include_once 'modele/Cookie.php';

    class Authentification extends _Controleur
    {
        public function informations(): array
        { return [
            'vue_connexion', 'vue_interdit', 
            'identifiant', 'cookie', 'nom_cookie', 
            'obligatoire', 'requise', 
            'admin_requis', 'prop_requis'
        ]; }
        public function ini() : string { return 'ini/auth.ini'; }

        protected $_vue_connexion;
        public function vue_connexion() : string { return $this->_vue_connexion; }
        protected $_vue_interdit;
        public function vue_interdit() : string { return $this->_vue_interdit; }

        protected $_identifiant;
        public function identifiant() : string { return $this->_identifiant; }
        protected $_cookie;                  
        public function cookie() : bool { return $this->_cookie; }
        protected $_nom_cookie;
        public function nom_cookie() : string { return $this->_nom_cookie; }
        protected $_obligatoire;                  // Définie si l'authentification doit toujours être active
        public function obligatoire() : bool { return $this->_obligatoire; }
        protected function modifier_obligatoire(bool $valeur) { $this->_obligatoire = $valeur; }
        protected $_requise;                      // Définie les vues où l'authentification est requise
        public function requise() : array { return $this->_requise; }
        protected $_admin_requis;                      // Définie les vues où l'authentification en tant qu'admin est requise
        public function admin_requis() : array { return $this->_admin_requis; }
        protected $_prop_requis;                      // Définie les vues où l'authentification en tant que propriétaire est requise
        public function prop_requis() : array { return $this->_prop_requis; }

        public function __construct(?string $fichier_ini = null)
        {
            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            parent::__construct($fichier_ini);
        }

        public function est_requise(string $nom_vue) : bool
        { return (array_search($nom_vue, $this->_requise ?? []) !== false); }
        public function admin_est_requis(string $nom_vue) : bool
        { return (array_search($nom_vue, $this->_admin_requis ?? []) !== false); }
        public function prop_est_requis(string $nom_vue) : bool
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
                        if (password_verify($post[self::CLE_MDP], $mdp))
                        {
                            $u->recevoir($bd, $this->_identifiant, ['id']);
                            $session[self::CLE_ID] = $u->id();
                            return true;
                        }
                }
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
                    if (isset($post[self::CLE_MDP])) unset($post[self::CLE_MDP]);
                    $id = $session[self::CLE_ID];
                    if ($this->_cookie && isset($post[self::CLE_COOKIE]))
                        Cookie::ecrire($bd, $id, $ip, $this->nom_cookie());
                }
                else if (isset($post[self::FORMULAIRE], $post[self::CLE_ID_CONNEXION]))
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
            return new JetonAuthentification($this->ini(), $id, $bd);
        }

        public function verifier_droits(string $vue, JetonAuthentification $auth, Routeur $routeur) : string
        {
            if ($auth->valide() && $vue === $this->vue_connexion())
                $routeur->redirection(null);
            if (($this->admin_est_requis($vue) && !$auth->admin()) // Si on est sur une vue admin|prop et que le jeton n'est pas admin|prop
               || ($this->prop_est_requis($vue) && !$auth->prop()))
                return $this->vue_interdit();    // Un jeton admin|prop est obligatoirement valide (cf. JetonAuthentification::admin()).
            if (!$auth->valide() && ($this->obligatoire() || $this->est_requise($vue)))
                return $this->vue_connexion();
            return $vue;
        }
    }

?>