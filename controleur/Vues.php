<?php

    class Vues
    {
        const EXTENSION = 'phpvue';                 // Extension des fichiers de vue
        const EXTENSION_COMPOSANT = 'cmpvue';       // Extension des fichiers de composant de vue

        const DOSSIER = 'vue/';                     // Dossier de base pour les fichiers de vue
        const DOSSIER_COMPOSANTS = 'composants/';   // Dossier des composants de vue

        const PAR_DEFAUT = 'accueil';               // Nom de la vue par défaut
        const INTROUVABLE = 'err/404';              // Nom de la vue à charger quand la vue demandée n'existe pas

        static private function _vue(string $nom) : string          // Transforme un nom de vue en son chemin de fichier
        { return self::DOSSIER . $nom . '.' . self::EXTENSION; }
        static private function _composant(string $nom) : string    // Transforme un nom de composant en son chemin de fichier    
        { return self::DOSSIER . self::DOSSIER_COMPOSANTS . $nom . '.' . self::EXTENSION_COMPOSANT; }

        static public function charger(string $vue)
        {
            if (strlen($vue) === 0)                            // Aucune vue spécifiée
                $vue = self::PAR_DEFAUT;                           // On chargera la vue par défaut
            $vue = self::_vue($vue);                       // Traduction en nom de fichier vue
            if (file_exists($vue))                                      // Recherche du fichier
                include $vue;                                               // On charge la vue   
            else                                                        // Le fichier n'existe pas
                include self::_vue(self::INTROUVABLE);                  // On charge la vue d'erreur 404
        }
        
        static public function composant(string $nom, array $parametres = [])   // Permet d'inclure rapidement un composant dans une vue
        {
            $_PARAMS = $parametres;             // Variable globale passée au composant
            include self::_composant($nom);     // On charge le composant
            unset($_PARAMS);                    // On efface la variable globale (évite des erreurs)
        }
    }

?>