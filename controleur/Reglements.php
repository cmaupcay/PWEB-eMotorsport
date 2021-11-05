<?php
    require_once 'controleur/Controleur.php';
    require_once 'modele/Facture.php';

    class Reglements extends Controleur
    {
        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, BD &$_BD, Authentification &$_AUTH, Routeur &$_ROUTEUR, ?JetonAuthentification &$_JETON = null)
        {
            if ($_JETON->est_du_role('loueur'))
                $params[CTRL_MESSAGE] = "En tant que loueur, vous ne pouvez pas louer de véhicule.";
            else // Afficher les factures
            {
                $factures = (new Facture())->selection(
                    $_BD, null, "idu = " . $_JETON->id() . " ORDER BY date_d DESC"
                );
                if (count($factures) > 0)
                {
                    // Séprarer entre les factures réglées et à régler
                    $params[FACTURE][REGLEE][TOTAL] = 0;
                    $params[FACTURE][NON_REGLEE][TOTAL] = 0;
                    foreach ($factures as $f)
                    {
                        if ($f->etat_r())
                        {
                            $params[FACTURE][REGLEE][] = $f;
                            $params[FACTURE][REGLEE][TOTAL] += $f->valeur();
                        }
                        else 
                        {
                            $params[FACTURE][NON_REGLEE][] = $f;
                            $params[FACTURE][NON_REGLEE][TOTAL] += $f->valeur();
                        }
                    }
                }
                else $params[CTRL_MESSAGE] = "Vous n'avez aucune facture à votre nom.";
            }
        }
    }
?>