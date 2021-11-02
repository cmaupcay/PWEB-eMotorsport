<?php
    require_once 'controleur/Controleur.php';
    require_once 'modele/Vehicule.php';

    class URI_Vehicules extends Controleur
    {
        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, BD &$_BD, Authentification &$_AUTH, Routeur &$_ROUTEUR, ?JetonAuthentification &$_JETON = null)
        {
            if (isset($params[URI]))
            {
                switch (count($params[URI]))
                {
                    case 1:
                        $vehicules = (new Vehicule())->selection($_BD, null, 'marque = \'' . $params[URI][0] . '\'');
                        if (count($vehicules) > 0) $params[VEHICULE] = $vehicules;
                        else $params[CTRL_MESSAGE] = 'Cette marque est inconnue.';
                        break;
                    case 2:
                        $vehicule = (new Vehicule())->selection(
                            $_BD, null, 'marque = \'' . $params[URI][0] . '\' AND modele = \'' . $params[URI][1] . '\''
                        );
                        if (count($vehicule) === 1) $params[VEHICULE] = $vehicule;
                        else $params[CTRL_MESSAGE] = 'Ce véhicule est inconnu.';
                        break;
                    default:
                        $params[CTRL_MESSAGE] = "Paramètres invalides.";
                        break;
                }
            }
            else $params[VEHICULE] = (new Vehicule())->selection($_BD);
        }
    }
?>