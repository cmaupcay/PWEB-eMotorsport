<?php

    class Routeur
    {
        const PAR_DEFAUT = 'accueil';               // Nom de la vue par défaut
     
        const INDICATEUR_ERREUR = 'err';            // Clé d'indication du code erreur

        static public function definir_vue(array $requete) : string
        {
            if (count($requete) === 0)              // La requête est vide
                return self::PAR_DEFAUT;                // On chargera la vue par défaut
                
            $vue = key($requete);                   // Récupération du nom de la vue (premiere clé du tableau de la requête)
            if ($vue === self::INDICATEUR_ERREUR)   // Spécification d'une erreur
                return $vue . '/' . $requete[$vue];
            return $vue;
        }
    }

?>