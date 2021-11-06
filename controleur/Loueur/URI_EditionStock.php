<?php
    namespace Loueur;
    require_once 'controleur/Controleur.php';
    require_once 'modele/Vehicule.php';

    class URI_EditionStock extends \Controleur
    {
        const FORMULAIRE = 'form_edition_v';
        const CLE_TYPE = 'ev_type';
        const CLE_MARQUE = 'ev_marque';
        const CLE_MODELE = 'ev_modele';
        const CLE_NB = 'ev_nb';
        const CLE_CARACT = 'ev_caract';
        const CLE_DISPO = 'ev_dispo';

        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, \BD &$_BD, \Authentification &$_AUTH, \Routeur &$_ROUTEUR, ?\JetonAuthentification &$_JETON = null)
        {
            if (isset($params[URI]) && count($params[URI]) === 1) // Un véhicule est renseigné
            {
                try { $vehicule = new \Vehicule($params[URI][0], $_BD); }
                // Si le véhicule n'existe pas, rediriger vers le stock
                catch (\Exception $e) { $_ROUTEUR->redirection('loueur/stock'); }
                if (isset( // Envoie des informations éditées
                    $post[self::FORMULAIRE], $post[self::CLE_TYPE], $post[self::CLE_MARQUE], $post[self::CLE_MODELE], $post[self::CLE_NB],
                    $post[self::CLE_CARACT], $post[self::CLE_DISPO]
                ))
                {
                    $vehicule->depuis_tableau([
                        'typeV' => $post[self::CLE_TYPE],
                        'marque' => $post[self::CLE_MARQUE],
                        'modele' => $post[self::CLE_MODELE],
                        'nb' => $post[self::CLE_NB],
                        'caract' => $post[self::CLE_CARACT],
                        'dispo' => $post[self::CLE_DISPO]
                    ], false);
                    if ($vehicule->envoyer($_BD)) $params[CTRL_MESSAGE] = "Modifications enregistrées.";
                    else $params[CTRL_MESSAGE] = "Les modifications n'ont pas pu être enregistrées.";
                }
                $params[VEHICULE] = $vehicule;
            } // Rediriger vers le stock sinon
            else $_ROUTEUR->redirection('loueur/stock');
        }
    }
?>