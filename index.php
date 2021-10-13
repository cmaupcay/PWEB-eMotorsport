<?php
    require_once 'controleur/Vues.php';
    require_once 'controleur/Routeur.php';
    require_once 'controleur/Authentification.php';

    $vue = Routeur::definir_vue($_REQUEST);
    $auth = false;
    if (Authentification::OBLIGATOIRE || Authentification::requiert($vue))
    {
        $auth = Authentification::verifier($_SESSION);
        if (!$auth)
            $vue = Authentification::VUE;
    }
    Vues::charger($vue, $auth);
?>