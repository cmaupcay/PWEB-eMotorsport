<?php
    include_once 'modele/BD.php';

    abstract class ModeleBD extends Modele
    {
        abstract public function table() : string;

        protected $_id;
        public function id() : ?int { return $this->_id; }

        protected function __construct(?int $id = null, ?BD $bd = null)
        {
            $this->_id = $id;
            if ($bd === null) return;
            if (!$this->recevoir($bd))
                throw new Exception('Echec lors de l\'initialisation du modèle depuis la base de données.');
        }
        
        // SQL
        public function existe(BD $bd) : bool
        { throw new Exception('Aucune méthode de comparaison avec la base de données.'); }
        public function envoyer(BD $bd) : bool                                      // Envoie les informations du modèle vers la BD
        { throw new Exception('Aucune méthode d\'envoi vers la base de données.'); }
        public function recevoir(BD $bd) : bool                                     // Charge les informations du modèle depuis la BD
        { throw new Exception('Aucune méthode de construction depuis la base de données.'); }
        public function supprimer(BD $bd, bool $effacer_local = false) : bool                                    // Supprime l'equivalent du modèle dans la BD
        {
            if ($effacer_local) $this->vider();
            return true;
        }

        static public function selection(BD $bd, string $condition_sql = '1') : array
        {
            return [];
        }
    }

?>