<?php
    namespace Loueur;
    require_once 'controleur/Controleur.php';
    require_once 'modele/Vehicule.php';

    class LocationEnCours extends \Controleur
    {
        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, \BD &$_BD, \Authentification &$_AUTH, \Routeur &$_ROUTEUR, ?\JetonAuthentification &$_JETON = null)
        {
            $factures = (new \Facture())->selection($_BD, ['date_d','date_f','idv', 'idu'], "date_f >= CURRENT_DATE ORDER BY date_d DESC");
            if (count($factures) > 0)
            {
                // Grouper les factures et les vehicules correspondants par id utilisateur
                foreach ($factures as $f)
                {
                    $params[FACTURE][$f->idu()] = $f;
                    $params[VEHICULE][$f->idu()] = new \Vehicule($f->idv(), $_BD);
                }
            }
            else $params[CTRL_MESSAGE] = 'Aucune location en cours.';
        }
    }
?>