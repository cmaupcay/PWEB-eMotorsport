<?php
    require_once 'Vue.php';

    class Routeur
    {
        const VUE_DOSSIER = '../vue/';                                                  // Dossier de base pour les fichiers de vue
        const VUE_EXTENSION = 'phpvue';                                                 // Extension des fichiers de vue
        const VUE_PAR_DEFAUT = 'accueil';                                               // Nom de la vue par défaut
        const VUE_INTROUVABLE = 'erreurs/404';                                                  // Nom de la vue à charger quand la vue demandée n'existe pas

        static function _fichier_vue(string $nom_vue) : string
        {
            return self::VUE_DOSSIER . $nom_vue . '.' . self::VUE_EXTENSION;
        }

        static public function charger_vue(string $vue_demandee)
        {
            if (strlen($vue_demandee) === 0)                            // Aucune vue spécifiée
                $vue_demandee = self::VUE_PAR_DEFAUT;                       // On chargera la vue par défaut
            $vue = self::_fichier_vue($vue_demandee);                   // Traduction en nom de fichier vue
            if (file_exists($vue))                                      // Recherche du fichier
                include $vue;                                               // On charge la vue   
            else                                                        // Le fichier n'existe pas
                include self::_fichier_vue(self::VUE_INTROUVABLE);          // On charge la vue définie dans VUE_INTROUVABLE
        }      
    }

?>