<?php

    class Authentification
    {
        const VUE = 'auth';                     // Vue chargée lorsqu'une authentification échoue

        const OBLIGATOIRE = false;              // Définie si l'authentification doit toujours être active
        const REQUISE = [                       // Définie les vues où l'authentification est requise
        ];

        static public function requiert(string $nom_vue) : bool
        {
            foreach (self::REQUISE as $vue)
            {
                if ($vue === $nom_vue)
                    return true;
            }
            return false;
        }

        static public function verifier(array $session) : bool
        {
            return false;
        }
    }

?>