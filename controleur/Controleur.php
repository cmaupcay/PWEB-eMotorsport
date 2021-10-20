<?php
    include_once 'modele/Modele.php';

    abstract class _Controleur extends Modele
    {
        public function __construct(string $fichier_ini)
        { $this->depuis_ini($fichier_ini); }
    }
    
    abstract class Controleur extends _Controleur
    {
        abstract public function ini() : string;
        public function __construct(?string $fichier_ini = null)
        {
            if ($fichier_ini === null) $fichier_ini = $this->ini();             // Initialisation des attributs depuis un fichier INI
            $this->depuis_ini($fichier_ini);
        }
        abstract public function executer(
            array &$session, array &$post, array &$get,
            BD &$_BD, Authentification &$_AUTH, Routeur &$_ROUTEUR, ?JetonAuthentification &$_JETON = null);
    }
?>