<?php
    include_once 'modele/JetonAuthentification.php';

    abstract class Authentification
    {
        const VUE = 'auth';                     // Vue chargée lorsqu'une authentification échoue

        const OBLIGATOIRE = false;              // Définie si l'authentification doit toujours être active
        const REQUISE = [                       // Définie les vues où l'authentification est requise
        ];

        static public function requise(string $nom_vue) : bool
        {
            // Recherche la vue dans les vues demandant l'authentification
            foreach (self::REQUISE as $vue)
                if ($vue === $nom_vue) return true;
            return false;
        }

        const FORMULAIRE = 'form_auth';
        const CLE_ID = 'auth_id';
        const CLE_PSD = 'auth_psd';
        const CLE_MDP = 'auth_mdp';

        static public function connexion(array $post, array $session, BD $bd)
        {
            if (isset($post[self::FORMULAIRE], $post[self::CLE_PSD], $post[self::CLE_MDP]))
            {
                // Authentification a faire
                unset($post[self::CLE_MDP]);
            }
        }

        static public function jeton(array $session, BD $bd) : JetonAuthentification
        {
            return new JetonAuthentification();
        }
    }

?>