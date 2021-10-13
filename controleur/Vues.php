<?php

    class Vues
    {
        const EXTENSION = 'phpvue';                 // Extension des fichiers de vue
        const EXTENSION_COMPOSANT = 'cmpvue';       // Extension des fichiers de composant de vue

        const DOSSIER = 'vue/';                     // Dossier de base pour les fichiers de vue
        const DOSSIER_COMPOSANTS = 'composants/';   // Dossier des composants de vue

        const INTROUVABLE = 'err/404';              // Nom de la vue à charger quand la vue demandée n'existe pas

        static private function _vue(string $nom) : string          // Transforme un nom de vue en son chemin de fichier
        { 
            return self::DOSSIER . $nom . '.' . self::EXTENSION;
        }
        static private function _composant(string $nom) : string    // Transforme un nom de composant en son chemin de fichier    
        { return self::DOSSIER . self::DOSSIER_COMPOSANTS . $nom . '.' . self::EXTENSION_COMPOSANT; }

        // NOTE        
        // Le booléen $_AUTH et le tableau $_PARAMS sont des constantes définie dès qu'une vue ou qu'un composant est
        // chargé.e. Elles sont utilisées pour passer des informations aux fichiers .phpvue et permet de modifier le
        // rendu selon ces informations.

        static public function charger(string $vue, bool $_AUTH, array $_PARAMS = [])
        {
            $vue = self::_vue($vue);                            // Traduction en nom de fichier vue
            if (file_exists($vue))                              // Recherche du fichier
                include $vue;                                       // On charge la vue   
            else                                                // Le fichier n'existe pas
                include self::_vue(self::INTROUVABLE);              // On charge la vue d'erreur 404
        }
        
        static public function composant(string $nom, bool $_AUTH, array $_PARAMS = [])
        {
            include self::_composant($nom);     // Permet d'inclure rapidement un composant dans une vue
        }
    }

?>