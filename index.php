<?php
    require_once 'controleur/Vues.php';
    require_once 'controleur/Routeur.php';
    require_once 'controleur/Authentification.php';

    $bd = new BD();                                                             // Initialise la base de données
    $auth = new Authentification();
    $jeton_auth = new JetonAuthentification();                                        // Non authentifié par défaut

    $routeur = new Routeur();
    $vue = $routeur->definir_vue($_REQUEST);                                     // Définir la vue

    if ($auth->obligatoire() || $auth->est_requise($vue))                       // Si l'authentification est requise
    {
        $jeton_auth = $auth->jeton($_SESSION, $bd);                        // Vérifie l'authentification
        if (!$jeton_auth)                                                             // Si elle échoue
            $vue = $auth->vue();                                           // Définir la vue sur la page de connexion
    }
    Vues::charger($vue, $jeton_auth);                                                 // Charger la vue
?>