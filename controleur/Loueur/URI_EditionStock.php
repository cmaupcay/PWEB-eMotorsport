<?php
    namespace Loueur;
    require_once 'controleur/Loueur/AjoutVehicule.php';

    class URI_EditionStock extends \Controleur
    {
        const FORMULAIRE = 'form_edition_v';
        const CLE_MARQUE = 'ev_marque';
        const CLE_MODELE = 'ev_modele';
        const CLE_NB = 'ev_nb';
        const CLE_CARACT = 'ev_caract';
        const CLE_DISPO = 'ev_dispo';

        const MODIF_PHOTO = 'ev_mphoto';
        const CLE_PHOTO = 'ev_photo';

        const SUPPRIMER = 'sv';

        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, \BD &$_BD, \Authentification &$_AUTH, \Routeur &$_ROUTEUR, ?\JetonAuthentification &$_JETON = null)
        {
            if (isset($params[URI]) && count($params[URI]) === 1) // Un véhicule est renseigné
            {
                try { $vehicule = new \Vehicule($params[URI][0], $_BD); }
                // Si le véhicule n'existe pas, rediriger vers le stock
                catch (\Exception $e) { $_ROUTEUR->redirection('loueur/stock'); }
                if (isset( // Edition des informations
                    $post[self::FORMULAIRE], $post[self::CLE_MARQUE], $post[self::CLE_MODELE], $post[self::CLE_NB],
                    $post[self::CLE_CARACT], $post[self::CLE_DISPO]
                ))
                {
                    $vehicule->depuis_tableau([
                        'marque' => $post[self::CLE_MARQUE],
                        'modele' => $post[self::CLE_MODELE],
                        'nb' => $post[self::CLE_NB],
                        'caract' => $post[self::CLE_CARACT],
                        'dispo' => $post[self::CLE_DISPO]
                    ], false);
                    // Envoi des modifications
                    if ($vehicule->envoyer($_BD))
                    {
                        $params[CTRL_MESSAGE] = "Modifications enregistrées.";
                        $vehicule->recevoir($_BD);
                    }
                    else $params[CTRL_MESSAGE] = "Les modifications n'ont pas pu être enregistrées.";
                }
                else if (isset($post[self::MODIF_PHOTO])) // Edition de la photo
                {
                    $nom_photo = AjoutVehicule::charger_photo(self::CLE_PHOTO);
                    if ($nom_photo !== null)
                    {
                        $ancienne = $vehicule->photo();
                        $vehicule->modifier_photo($nom_photo);
                        // Envoi des modifications
                        if ($vehicule->envoyer($_BD))
                        {
                            // Suppression de la photo précédente
                            if ($ancienne !== $vehicule->photo() && file_exists('./media/' . $ancienne))
                                unlink('./media/' . $ancienne);
                            $params[CTRL_MESSAGE] = "Photo modifiée.";
                            $vehicule->recevoir($_BD);
                        }
                        else
                        {
                            if ($ancienne !== null && file_exists('./media/' . $ancienne))
                                unlink('./media/' . $ancienne);
                            $params[CTRL_MESSAGE] = "Impossible de modifier la photo.";
                        }
                    }
                    else $params[CTRL_MESSAGE] = "Impossible de charger la photo.";
                }
                else if (isset($post[self::SUPPRIMER]))
                {
                    $vehicule->supprimer($_BD);
                    $_ROUTEUR->redirection('loueur/stock');
                }
                $params[VEHICULE] = $vehicule;
            } // Rediriger vers le stock sinon
            else $_ROUTEUR->redirection('loueur/stock');
        }
    }
?>