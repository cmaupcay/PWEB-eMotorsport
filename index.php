<?php
    require_once 'controleur/Vues.php';
    require_once 'controleur/Routeur.php';
    require_once 'controleur/Authentification.php';

    $vue = Routeur::definir_vue($_REQUEST);                                     // Définir la vue
    $auth = new TAuthentification(TAuthentification::ADMIN);                                                              // Non authentifié par défaut
    if (Authentification::OBLIGATOIRE || Authentification::requise($vue))      // Si l'authentification est requise
    {
        $auth = Authentification::verifier($_SESSION);                              // Vérifie l'authentification
        if (!$auth)                                                                 // Si elle échoue
            $vue = Authentification::VUE;                                               // Définir la vue sur la page de connexion
    }
    Vues::charger($vue, $auth);                                                 // Charger la vue
?>