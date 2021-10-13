<?php
    require_once 'controleur/Vues.php';

    class Routeur
    {
        static function _definir_vue(array $requete) : string
        {
            if (count($requete) === 0)
                return '';
            return key($requete);
        }

        static function charger_page(array $requete)
        {
            $vue = self::_definir_vue($requete);
            Vues::charger($vue);
        }
    }

?>