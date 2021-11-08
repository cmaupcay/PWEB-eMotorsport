<?php
    require_once 'controleur/Controleur.php';
    require_once 'modele/Facture.php';

    class URI_EditionLocation extends Controleur
    {
        const FORMULAIRE = 'form_edition_f';
        const CLE_DATE_D = 'f_dd';
        const CLE_DATE_F = 'f_df';
        const CLE_ETAT_R = 'f_er';

        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, BD &$_BD, Authentification &$_AUTH, Routeur &$_ROUTEUR, ?JetonAuthentification &$_JETON = null)
        {
            if (isset($params[URI]) && count($params[URI]) === 1) // Une location est renseignée
            {
                try
                {
                    $f = new Facture($params[URI][0], $_BD);
                    if ($f->idu() !== $_JETON->id() && !$_JETON->est_du_role('loueur')) throw new Exception();
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
                    // Envoi des modifications
                    if ($f->envoyer($_BD))
                    {
                        $params[CTRL_MESSAGE] = "Modifications enregistrées.";
                        $f->recevoir($_BD);
                    }
                    else $params[CTRL_MESSAGE] = "Les modifications n'ont pas pu être enregistrées.";
                }
                else if (!$f->etat_r() && isset($post[self::CLE_ETAT_R]) && $_JETON->est_du_role('loueur'))
                {
                    $f->modifier_etat_r(true);
                    // Envoi des modifications
                    if ($f->envoyer($_BD)) $_ROUTEUR->redirection('loueur/facture');
                    else $params[CTRL_MESSAGE] = "Les modifications n'ont pas pu être enregistrées.";
                }
                $params[FACTURE] = $f;
            } // Rediriger vers les locations en cours
            else
            {
                if ($_JETON->est_du_role('loueur')) $_ROUTEUR->redirection('loueur/facture');
                else $_ROUTEUR->redirection('en-cours');
            }
        }
    }
?>