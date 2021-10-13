<?php
    include_once 'modele/BD.php';

    abstract class Modele
    {
        // JSON
        public function __construct(string $json) { $this->depuis_json($json); }    // Construction depuis un texte JSON
        public function json() : string { return json_encode($this); }              // Transformation en texte JSON
        abstract public function depuis_json(string $json);                         // Charge les informations du modèle depuis un texte JSON
        
        // SQL
        // abstract public function envoyer(BD $bd);
        // abstract public function recevoir(BD $bd);
    }

?>