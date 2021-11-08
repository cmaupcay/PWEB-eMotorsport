<?php
    require_once 'controleur/Controleur.php';
    require_once 'modele/Vehicule.php';

    class LocationEnCours extends Controleur
    {
        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, BD &$_BD, Authentification &$_AUTH, Routeur &$_ROUTEUR, ?JetonAuthentification &$_JETON = null)
        {
            if ($_JETON->est_du_role('loueur')) $_ROUTEUR->redirection('loueur/en-cours');
            else // Afficher les location en cours
            {
                $factures = (new Facture())->selection(
                    $_BD, ['id', 'date_d','date_f','idv'], "(date_f IS NULL OR date_f >= CURRENT_DATE) AND idu = " . $_JETON->id() . " ORDER BY date_d DESC"
                );
                if (count($factures) > 0)
                {
                    foreach ($factures as $f)
                    {
                        $params[VEHICULE][] = ($v = new Vehicule($f->idv(), $_BD));
                        $params[FACTURE][$v->id()] = $f;
                    }
                }
                else $params[CTRL_MESSAGE] = "Vous n'avez aucune location en cours.";
            }
        }
    }
?>