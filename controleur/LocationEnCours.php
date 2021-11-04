<?php
    require_once 'controleur/Controleur.php';
    require_once 'modele/Facture.php';
    require_once 'modele/Vehicule.php';

    class LocationEnCours extends Controleur
    {
        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, BD &$_BD, Authentification &$_AUTH, Routeur &$_ROUTEUR, ?JetonAuthentification &$_JETON = null)
        {
            if ($_JETON->est_du_role('loueur'))
                $params[CTRL_MESSAGE] = "En tant que loueur, vous ne pouvez pas louer de véhicule.";
            else // Afficher les location en cours
            {
                $factures = (new Facture())->selection(
                    $_BD, ['date_d','date_f','idv'], "date_f >= CURRENT_DATE AND idu = " . $_JETON->id()
                );
                if (count($factures) > 0)
                {
                    $params[FACTURE] = $factures;
                    foreach ($factures as $f)
                        $params[VEHICULE][] = new Vehicule($f->idv(), $_BD);
                }
                else $params[CTRL_MESSAGE] = "Vous n'avez aucune location en cours.";
            }
        }
    }
?>