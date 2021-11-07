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
                    case 1: // Afficher la liste des vehicules disponibles et de la marque renseignée dans l'URI
                        $vehicules = (new Vehicule())->selection($_BD, null, 'marque = \'' . $params[URI][0] . '\' AND dispo = true');
                        if (count($vehicules) > 0)
                        {
                            foreach ($vehicules as $v)
                                if (!$v->est_loue($_BD)) $params[VEHICULE][] = $v;
                        }
                        else $_ROUTEUR->redirection('vehicules');
                        $params[NOM_PAGE] = $params[URI][0];
                        break;
                    case 2: // Afficher le véhicule désigné dans l'URI (<marque>/<modele>/)
                        $vehicule = (new Vehicule())->selection(
                            $_BD, null, 'marque = \'' . $params[URI][0] . '\' AND modele = \'' . $params[URI][1] . '\''
                        );
                        if (count($vehicule) === 1) $params[VEHICULE] = $vehicule[0];
                        else $_ROUTEUR->redirection('vehicules/' . $params[URI][0]);
                        $params[NOM_PAGE] = $params[URI][0] . ' ' . $params[URI][1];
                        break;
                    default:
                        break;
                }
            } // Afficher la liste de tous les véhicules disponibles
            else
            {
                $vehicules = (new Vehicule())->selection($_BD, null, 'dispo = true');
                if (count($vehicules) > 0)
                {
                    foreach ($vehicules as $v)
                        if (!$v->est_loue($_BD)) $params[VEHICULE][] = $v;
                }
            }
        }
    }
?>