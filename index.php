<?php
    require_once 'controleur/Vues.php';
    require_once 'controleur/Routeur.php';
    require_once 'controleur/Authentification.php';

    $_BD = new BD();                                                             // Initialise la base de données
    $_AUTH = new Authentification();
    $_ROUTEUR = new Routeur();
    $_V = new Vues();

    $_JETON = $_AUTH->jeton($_SESSION, $_POST, $_COOKIE, $_SERVER['REMOTE_ADDR'], $_BD);   // On récupère le jeton d'authentification
    var_dump($_JETON);

    $vue = $_ROUTEUR->definir_vue($_SERVER['REQUEST_URI'], $_AUTH);                                     // Définir la vue
    $vue = $_AUTH->verifier_droits($vue, $_JETON, $_ROUTEUR);

    $controleurs = $_ROUTEUR->charger_controleurs($vue);
    var_dump($controleurs);
    foreach ($controleurs as $c)
        $c->executer(
            $_SESSION, $_POST, $_GET,
            $_BD, $_AUTH, $_ROUTEUR, $_JETON
        );

    $_V->charger($vue, $_POST, $_GET, $_JETON);                                        // Charger la vue
?>