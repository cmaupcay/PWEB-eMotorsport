<?php
    require_once 'controleur/Controleur.php';

    class Inscription extends Controleur
    {
        const FORMULAIRE = 'form_insc';
        const CLE_NOM = 'insc_nom';
        const CLE_PSD = 'insc_psd';
        const CLE_MDP = 'insc_mdp';
        const CLE_EMAIL = 'insc_email';
        const CLE_NOM_E = 'insc_nom_e';
        const CLE_ADR_E = 'insc_adr_e';

        public function executer(
            array &$server, array &$session, array &$post, array &$get, array &$params, 
            BD &$_BD, Authentification &$_AUTH, Routeur &$_ROUTEUR, ?JetonAuthentification &$_JETON = null)
        {
            if (isset($post[self::FORMULAIRE]))
            {
                if (isset($post[self::CLE_NOM], $post[self::CLE_PSD], $post[self::CLE_MDP], $post[self::CLE_EMAIL],
                    $post[self::CLE_NOM_E], $post[self::CLE_ADR_E]))
                {
                    ($u = new Utilisateur())->depuis_tableau([
                        'nom' => $post[self::CLE_NOM],
                        'pseudo' => $post[self::CLE_PSD],
                        'email' => $post[self::CLE_EMAIL],
                        'nom_e' => $post[self::CLE_NOM_E],
                        'adresse_e' => $post[self::CLE_ADR_E]
                    ]);
                    if (!$u->existe($_BD, 'email'))
                    {
                        if ($u->envoyer($_BD, [], ['mdp' => $_AUTH->hash($post[self::CLE_MDP])]))
                        {
                            $u->recevoir($_BD, 'email'); // On récupère l'id du nouvel utilisateur
                            Cookie::ecrire($_BD, $u->id(), $server['REMOTE_ADDR'], $_AUTH->nom_cookie());
                            $_ROUTEUR->redirection(null);
                        }
                        else $params[CTRL_MESSAGE] = "L'inscription n'a pas pu aboutir.";
                    }
                    else $params[CTRL_MESSAGE] = "Cette adresse email est déjà associée à un compte.";
                }
                else $params[CTRL_MESSAGE] = "Veuillez completer le formulaire.";
            }
        }
    }

?>