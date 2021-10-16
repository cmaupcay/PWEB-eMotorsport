<?php
    require_once 'controleur/Vues.php';
    require_once 'controleur/Routeur.php';
    require_once 'controleur/Authentification.php';

    $bd = new BD();                                                             // Initialise la base de données
    $auth = new Authentification();
    $routeur = new Routeur();
    $_V = new Vues();

    $jeton_auth = $auth->jeton($_SESSION, $_POST, $_COOKIE, $_SERVER['REMOTE_ADDR'], $bd);   // On récupère le jeton d'authentification
    var_dump($jeton_auth);

    $vue = $routeur->definir_vue($_SERVER['REQUEST_URI'], $auth);                                     // Définir la vue
    $vue = $auth->verifier_droits($vue, $jeton_auth, $routeur);

    $_V->charger($vue, $jeton_auth);                                        // Charger la vue
?>