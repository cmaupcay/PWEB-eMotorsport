<?php
    include_once 'modele/JetonAuthentification.php';

    class Authentification extends Modele
    {
        public function informations(): array
        { return ['vue', 'obligatoire', 'requise']; }
        private const INI = 'ini/auth.ini';

        protected $_vue;
        public function vue() : string { return $this->_vue; }
        protected $_obligatoire;                  // Définie si l'authentification doit toujours être active
        public function obligatoire() : bool { return $this->_obligatoire; }
        protected function modifier_obligatoire(bool $valeur) { $this->_obligatoire = $valeur; }
        protected $_requise;                      // Définie les vues où l'authentification est requise
        public function requise() : array { return $this->_requise; }

        public function __construct(?string $fichier_ini = null)
        {
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
        const CLE_PSD = 'auth_psd';
        const CLE_MDP = 'auth_mdp';

        public function connexion(array $post, array $session, BD $bd)
        {
            if (isset($post[self::FORMULAIRE], $post[self::CLE_PSD], $post[self::CLE_MDP]))
            {
                // Authentification a faire
                unset($post[self::CLE_MDP]);
            }
        }

        public function jeton(array $session, BD $bd) : JetonAuthentification
        {
            return new JetonAuthentification();
        }
    }

?>