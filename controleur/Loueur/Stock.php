<?php
    namespace Loueur;
    require_once 'controleur/Controleur.php';
    require_once 'modele/Vehicule.php';

    class Stock extends \Controleur
    {
        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, \BD &$_BD, \Authentification &$_AUTH, \Routeur &$_ROUTEUR, ?\JetonAuthentification &$_JETON = null)
        {
            $vehicules = (new \Vehicule())->selection($_BD, null);
            foreach ($vehicules as $v)
            {
                if (!$v->est_loue($_BD))
                {
                    if ($v->dispo()) $params[VEHICULE][DISPO][] = $v;
                    else $params[VEHICULE][REVISION][] = $v;
                }
            }
        }
    }
?>