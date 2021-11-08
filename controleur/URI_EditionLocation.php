<?php
    require_once 'controleur/Controleur.php';
    require_once 'modele/Vehicule.php';
    require_once 'modele/Facture.php';

    class URI_EditionLocation extends Controleur
    {
        const FORMULAIRE = 'form_edition_f';
        const CLE_DATE_D = 'f_dd';
        const CLE_DATE_F = 'f_df';

        const CLE_ETAT_R = 'f_er';
        const CLE_VALEUR = 'f_ev';

        const NOUVELLE_LOCATION = 'v';

        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, BD &$_BD, Authentification &$_AUTH, Routeur &$_ROUTEUR, ?JetonAuthentification &$_JETON = null)
        {
            if (isset($params[URI]))
            {
                switch (count($params[URI]))
                {
                    case 1:
                        try
                        {
                            $f = new Facture($params[URI][0], $_BD);
                            if ($f->etat_r() || ($f->idu() !== $_JETON->id() && !$_JETON->est_du_role('loueur'))) throw new Exception();
                        }
                        // Si la facture n'existe pas, rediriger vers les locations en cours
                        catch (\Exception $e) { $_ROUTEUR->redirection('en-cours'); }
                        if (isset( // Edition des dates
                            $post[self::FORMULAIRE], $post[self::CLE_DATE_D], $post[self::CLE_DATE_F]
                        ) && $f->idu() === $_JETON->id())
                        {
                            $f->depuis_tableau([
                                'date_d' => $post[self::CLE_DATE_D],
                                'date_f' => $post[self::CLE_DATE_F]
                            ], false);
                            if ($post[self::CLE_DATE_F] === '') $f->modifier_date_f(null);
                            if ($f->DT_date_f()->getTimestamp() < $f->DT_date_d()->getTimestamp()) $params[CTRL_MESSAGE] = "Dates eronnées.";
                            else
                            {
                                // Envoi des modifications
                                if ($f->envoyer($_BD))
                                {
                                    $params[CTRL_MESSAGE] = "Modifications enregistrées.";
                                    $f->recevoir($_BD);
                                }
                                else $params[CTRL_MESSAGE] = "Les modifications n'ont pas pu être enregistrées.";
                            }
                        }
                        else if (!$f->etat_r() && $_JETON->est_du_role('loueur') &&
                                (isset($post[self::CLE_VALEUR]) || isset($post[self::CLE_ETAT_R])))
                        {
                            if (isset($post[self::CLE_VALEUR]) && $f->valeur() === null) $f->modifier_valeur($post[self::CLE_VALEUR]);
                            else if (isset($post[self::CLE_ETAT_R]))$f->modifier_etat_r(true);
                            // Envoi des modifications
                            if ($f->envoyer($_BD)) $_ROUTEUR->redirection('loueur/facture');
                            else $params[CTRL_MESSAGE] = "Les modifications n'ont pas pu être enregistrées.";
                        }
                        $params[FACTURE] = $f;
                        break;
                    case 2:
                        if ($_JETON->est_du_role('loueur') || $params[URI][0] !== self::NOUVELLE_LOCATION)
                            $_ROUTEUR->redirection('loueur/facture');
                        try 
                        { 
                            $v = new Vehicule($params[URI][1], $_BD);
                            if (!$v->dispo() || $v->est_loue($_BD)) throw new Exception();
                        }
                        catch (\Exception $e) { $_ROUTEUR->redirection('loueur/facture'); }
                        ($f = new Facture())->depuis_tableau([
                            'idu' => $_JETON->id(),
                            'idv' => $params[URI][1],
                            'etat_r' => false
                        ]);
                        $f->modifier_date_d(date_format(new DateTime(), 'Y-m-d'));
                        if ($f->envoyer($_BD))
                        {
                            $f = $f->selection($_BD, null, '1 ORDER BY id DESC')[0];
                            var_dump($f);
                            $_ROUTEUR->redirection('location/'. $f->id());
                        }
                    default:
                        $_ROUTEUR->redirection('en-cours');
                }
            } // Rediriger vers les locations en cours
            else
            {
                if ($_JETON->est_du_role('loueur')) $_ROUTEUR->redirection('loueur/facture');
                else $_ROUTEUR->redirection('en-cours');
            }
        }
    }
?>