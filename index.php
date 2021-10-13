<?php
    require_once 'controleur/Routeur.php';

    Routeur::charger_vue((isset($_GET['p']) ? $_GET['p'] : ''));
?>