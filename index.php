<?php
    require_once 'controleur/Vues.php';
    require_once 'controleur/Routeur.php';
    require_once 'controleur/Authentification.php';

    $bd = new BD();                                                             // Initialise la base de données
    
    $auth = new Authentification();
    $jeton_auth = $auth->jeton($_SESSION, $_POST, $_COOKIE, $bd);                                 // On récupère le jeton d'authentification
    var_dump($jeton_auth);

    $routeur = new Routeur();
    $vue = $routeur->definir_vue($_SERVER['REQUEST_URI']);                                     // Définir la vue

    if ($auth->obligatoire() || $auth->est_requise($vue))                       // Si l'authentification est requise
    {
        if (!$jeton_auth->valide())                                                             // Si elle échoue
            $vue = $auth->vue();                                           // Définir la vue sur la page de connexion
    }

    $_V = new Vues();
    $_V->charger($vue, $jeton_auth);                                                 // Charger la vue
?>