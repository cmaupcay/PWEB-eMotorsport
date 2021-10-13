<?php
    require_once 'controleur/Vues.php';

    Vues::charger((isset($_GET['p']) ? $_GET['p'] : ''));
?>