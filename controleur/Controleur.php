<?php
    include_once 'modele/Modele.php';
    include_once 'ini/params.ini.php';

    // Classe de base de la classe Controleur et des controleurs centraux (cf. App).
    // Cette classe est une extension de Modèle car elle utilise la fonction depuis_ini dans
    // son contructeur. Les controleurs peuvent ainsi être facilement parametrés.
    abstract class _Controleur extends Modele
    {
        // Construction depuis un fichier ini
        public function __construct(string $fichier_ini)
        { $this->depuis_ini($fichier_ini); }
    }
    
    // Classe de base des controleurs.
    abstract class Controleur extends _Controleur
    {
        public function __construct(?string $fichier_ini = null)
        {
            // Si aucun fichier ini n'est spécifié, chargement depuis le fichier ini de base.
            if ($fichier_ini === null) $fichier_ini = $this->ini();
            // Si un fichier ini est défini, construction depuis ce fichier.
            if ($fichier_ini != null) $this->depuis_ini($fichier_ini);
        }
        // Défini le fichier ini de base pour parametrer le controleur.
        public function ini() : ?string { return null; }
        public function informations(): array { return []; }
        // Défini le code à executer lorsque le controleur est appelé.
        abstract public function executer(
            array &$server, array &$session, array &$post, array &$get, array &$params,
            BD &$_BD, Authentification &$_AUTH, Routeur &$_ROUTEUR, ?JetonAuthentification &$_JETON = null);
    }
?>