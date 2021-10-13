<?php
    require_once 'controleur/Vues.php';

    class Routeur
    {
        const INDICATEUR_ERREUR = 'err';

        static function _definir_vue(array $requete) : string
        {
            if (count($requete) === 0)
                return '';
            $vue = key($requete);
            if ($vue === self::INDICATEUR_ERREUR)
                return $vue . '/' . $requete[$vue];
            return $vue;
        }

        static function charger_page(array $requete)
        {
            $vue = self::_definir_vue($requete);
            Vues::charger($vue);
        }
    }

?>