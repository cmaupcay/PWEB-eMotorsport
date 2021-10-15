<?php
    include_once 'modele/JetonAuthentification.php';

    class Authentification extends Modele
    {
        public function informations(): array
        { return ['vue', 'identifiant', 'cookie', 'nom_cookie', 'obligatoire', 'requise']; }
        private const INI = 'ini/auth.ini';

        protected $_vue;
        public function vue() : string { return $this->_vue; }

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

        public function __construct(?string $fichier_ini = null)
        {
            if (session_status() != PHP_SESSION_ACTIVE) session_start();
            if ($fichier_ini === null) $fichier_ini = self::INI;             // Initialisation des attributs depuis un fichier INI
            $this->depuis_ini($fichier_ini);
        }

        public function est_requise(string $nom_vue) : bool
        {
            // Recherche la vue dans les vues demandant l'authentification
            foreach ($this->_requise as $vue)
                if ($vue === $nom_vue) return true;
            return false;
        }

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

        public function jeton(array &$session, array &$post, array &$cookie, BD &$bd) : JetonAuthentification
        {
            if ($this->_cookie)
                $id = $cookie[$this->_nom_cookie] ?? null;
            if ($id === null)
                $id = $session[self::CLE_ID] ?? null;
            if ($id === null)
            {
                if ($this->_connexion($post, $session, $bd))
                {
                    if (isset($post[self::CLE_MDP])) unset($post[self::CLE_MDP]);
                    $id = $session[self::CLE_ID];
                    if ($this->_cookie && isset($post[self::CLE_COOKIE]))
                        setcookie($this->nom_cookie(), $id, time() + 3600, '/'); 
                }
                else if (isset($post[self::FORMULAIRE], $post[self::CLE_ID_CONNEXION]))
                {
                    ($j = new JetonAuthentification())->depuis_tableau([$this->_identifiant => $post[self::CLE_ID_CONNEXION]]);
                    return $j;
                }
            }
            if ($id != null && isset($post[self::CLE_DECO]))
            {
                unset($session[self::CLE_ID]);
                setcookie($this->nom_cookie(), $id, 1, '/');
                $id = null;
            }
            return new JetonAuthentification(self::INI, $id, $bd);
        }
    }

?>