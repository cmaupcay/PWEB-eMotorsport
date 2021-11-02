<?php
    include_once 'controleur/Controleur.php';

    class Vues extends _Controleur
    {
        public function informations(): array
        { return [
            'ext_vue', 'ext_composant',
            'dos_vue', 'dos_composant',
            'ERR'
        ]; }

        protected $_ext_vue;                        // Extension des fichiers de vue
        protected function ext_vue() : string { return $this->_ext_vue; }
        protected $_ext_composant;                  // Extension des fichiers de composant de vue
        protected function ext_composant() : string { return $this->_ext_composant; }
        protected $_dos_vue;                        // Dossier de base pour les fichiers de vue
        protected function dos_vue() : string { return $this->_dos_vue; }
        protected $_dos_composant;                  // Dossier des composants de vue
        protected function dos_composant() : string { return $this->_dos_composant; }
        
        protected $_ERR;                            // Nom des vues à charger lors des erreurs
        public function ERR() : array { return $this->_ERR; }

        private function _vue(string $nom) : string          // Transforme un nom de vue en son chemin de fichier
        { return $this->_dos_vue . $nom . '.' . $this->_ext_vue; }
        private function _composant(string $nom) : string    // Transforme un nom de composant en son chemin de fichier    
        { return $this->_dos_vue . $this->_dos_composant . $nom . '.' . $this->_ext_composant; }

        // NOTE        
        // L'objet $_AUTH et le tableau $_PARAMS sont des constantes définie dès qu'une vue ou qu'un composant est
        // chargé.e. Elles sont utilisées pour passer des informations aux fichiers .phpvue et permet de modifier le
        // rendu selon ces informations.

        public function charger(string $vue, array &$post, array &$get, ?JetonAuthentification $_JETON = null, array $_PARAMS = [])
        {
            $_V = $this;
            if ($_JETON === null) $_JETON = new JetonAuthentification();
            $post = []; $get = [];                              // POST et GET ne peuvent pas être utilisés dans les vues.
            if (isset($_PARAMS[URI])) unset($_PARAMS[URI]);     // De même, on efface les informations relatives à l'URI.
            $vue = $this->_vue($vue);                           // Traduction en nom de fichier vue
            if (file_exists($vue))                              // Recherche du fichier
                include $vue;                                   // On charge la vue   
            else                                                // Le fichier n'existe pas
                include $this->_vue($this->_ERR['404']);        // On charge la vue d'erreur 404
        }
        
        
        // FONCTIONS UTILES AUX VUES
        public function composant(string $nom, ?JetonAuthentification $_JETON = null, array $_PARAMS = [])
        { $_V = $this; include $this->_composant($nom); }   // Permet d'inclure rapidement un composant dans une vue
        public function redirection(?string $vue, array &$post, array &$get, ?JetonAuthentification $_JETON = null, array $_PARAMS = [])
        { $this->charger(($vue ?? $this->_ERR[404]), $post, $get, $_JETON, $_PARAMS); die(); }
    }

?>