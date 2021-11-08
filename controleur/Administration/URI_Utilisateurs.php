<?php
    namespace Administration;

use Utilisateur;

require_once 'controleur/Controleur.php';
    require_once 'modele/Utilisateur.php';

    class URI_Utilisateurs extends \Controleur
    {
        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, \BD &$_BD, \Authentification &$_AUTH, \Routeur &$_ROUTEUR, ?\JetonAuthentification &$_JETON = null)
        {
            if (isset($params[URI]))
            {
                if (count($params[URI]) === 1)
                {
                    ($u = new Utilisateur())->depuis_tableau(['id' => $params[URI][0]]);
                    if ($u->recevoir($_BD) && $u->id() !== $_JETON->id())
                    {
                        $params[UTILISATEUR] = $u;
                        return;
                    }
                }
                $_ROUTEUR->redirection('admin');
            } // Afficher la liste de tous les utilisateurs (sauf l'admin actif)
            else $params[UTILISATEUR] = (new \Utilisateur())->selection($_BD, null, 'id != ' . $_JETON->id());
        }
    }
?>