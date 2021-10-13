<?php

    class Vues
    {
        const DOSSIER = 'vue/';                 // Dossier de base pour les fichiers de vue
        const EXTENSION = 'phpvue';             // Extension des fichiers de vue
        const PAR_DEFAUT = 'accueil';           // Nom de la vue par défaut
        const INTROUVABLE = 'err/404';          // Nom de la vue à charger quand la vue demandée n'existe pas

        static function _fichier(string $nom_vue) : string
        {
            return self::DOSSIER . $nom_vue . '.' . self::EXTENSION;
        }

        static public function charger(string $vue_demandee)
        {
            if (strlen($vue_demandee) === 0)                            // Aucune vue spécifiée
                $vue_demandee = self::PAR_DEFAUT;                           // On chargera la vue par défaut
            $vue = self::_fichier($vue_demandee);                       // Traduction en nom de fichier vue
            if (file_exists($vue))                                      // Recherche du fichier
                include $vue;                                               // On charge la vue   
            else                                                        // Le fichier n'existe pas
                include self::_fichier(self::INTROUVABLE);                  // On charge la vue d'erreur 404
        }      
    }

?>