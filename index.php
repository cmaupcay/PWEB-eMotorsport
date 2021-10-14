<?php
    require_once 'controleur/Vues.php';
    require_once 'controleur/Routeur.php';
    require_once 'controleur/Authentification.php';

    $bd = new BD();                                                             // Initialise la base de données
    $vue = Routeur::definir_vue($_REQUEST);                                     // Définir la vue
    $auth = new JetonAuthentification();                                        // Non authentifié par défaut
    if (Authentification::OBLIGATOIRE || Authentification::requise($vue))       // Si l'authentification est requise
    {
        $auth = Authentification::jeton($_SESSION, $bd);                        // Vérifie l'authentification
        if (!$auth)                                                             // Si elle échoue
            $vue = Authentification::VUE;                                           // Définir la vue sur la page de connexion
    }
    Vues::charger($vue, $auth);                                                 // Charger la vue
?>