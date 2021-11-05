<?php
    namespace Loueur;
    require_once 'controleur/Controleur.php';
    require_once 'modele/Vehicule.php';

    class AjoutVehicule extends \Controleur
    {
        const FORMULAIRE = 'form_ajout_v';
        const CLE_TYPE = 'av_type';
        const CLE_MARQUE = 'av_marque';
        const CLE_MODELE = 'av_modele';
        const CLE_NB = 'av_nb';
        const CLE_CARACT = 'av_caract';
        const CLE_PHOTO = 'av_photo';

        public static function charger_photo(string $marque, string $modele, string $cle = self::CLE_PHOTO) : ?string
        {
            if (isset($_FILES[$cle]))
            {
                if (!isset($_FILES[$cle]['error']) || is_array($_FILES[$cle]['error'])) return null;
                switch ($_FILES[$cle]['error'])
                {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                    default:
                        return null;
                }
                $mime = new \finfo(FILEINFO_MIME_TYPE);
                if (!($ext = array_search(
                    $mime->file($_FILES[$cle]['tmp_name']),
                    [
                        'jpg' => 'image/jpeg',
                        'jpeg' => 'image/jpeg',
                        'png' => 'image/png'
                    ],
                    true
                ))) return null;
                $nom =  $marque . '_' . $modele . '.' . $ext;
                if (move_uploaded_file(
                    $_FILES[$cle]['tmp_name'],
                    './media/' . $nom
                )) return $nom;
            }
            return null;
        }

        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, \BD &$_BD, \Authentification &$_AUTH, \Routeur &$_ROUTEUR, ?\JetonAuthentification &$_JETON = null)
        {
            if (isset(
                $post[self::FORMULAIRE], $post[self::CLE_TYPE], $post[self::CLE_MARQUE], $post[self::CLE_MODELE], $post[self::CLE_NB], $post[self::CLE_CARACT]
            ))
            {
                if (json_decode($_POST[self::CLE_CARACT]) !== false)
                {
                    $nom_photo = self::charger_photo($post[self::CLE_MARQUE], $post[self::CLE_MODELE]);
                    if ($nom_photo !== null)
                    {
                        ($vehicule = new \Vehicule())->depuis_tableau([
                            'typeV' => $post[self::CLE_TYPE],
                            'marque' => $post[self::CLE_MARQUE], 
                            'modele' => $post[self::CLE_MODELE], 
                            'nb' => $post[self::CLE_NB], 
                            'caract' => $post[self::CLE_CARACT], 
                            'photo' => '?' . $_ROUTEUR->imedia() . '=' . $nom_photo,
                            'dispo' => true
                        ]);
                        if ($vehicule->envoyer($_BD, ['id'])) $params[CTRL_SUCCES_AJOUT] = true;
                        else $params[CTRL_MESSAGE] = "Impossible d'ajouter le véhicule. Veuillez vérifier les informations du formulaire.";
                    }
                    else $params[CTRL_MESSAGE] = "Le téléversement du fichier n'a pu aboutir.";
                }
                else $params[CTRL_MESSAGE] = "Les caractéristiques du véhicule ne respectent pas le format JSON.";
            }
        }
    }
?>