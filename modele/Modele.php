<?php
    include_once 'modele/BD.php';

    abstract class Modele
    {
        abstract public function informations() : array;

        public function __construct(string $json) { $this->depuis_json($json); }    // Construction depuis un texte JSON
        public function json() : string                                             // Transformation en texte JSON
        { 
            $infos = $this->informations();
            $_data = [];
            foreach ($infos as $info)
            { $_data[$info] = $this->{(string)$info}(); }
            return json_encode($_data); 
        }
        public function depuis_json(string $json, bool $effacer = true) : bool                           // Charge les informations du modèle depuis un texte JSON
        {
            $json = json_decode($json, true);                                       // Transformation en tableau associatif
            $infos = $this->informations();                                         // Chargement des informations du modèle
            $reussi = false;
            var_dump($infos);
            foreach ($infos as $info) 
            {
                $reussi = isset($json[$info]);                                           // Recherche dans le JSON
                if (!$reussi)                                                            // Si on n'a pas de valeur
                {
                    if (!$effacer) continue;                                                 // Et si on ne doit pas effacer, on continue
                    $json[$info] = null;
                }
                try { $this->{'modifier_' . $info}($json[$info]); }                      // Appel de la méthode de modfication de l'attribut
                catch (\Error $e)                                                        // La méthode n'existe pas
                { $this->{'_' . $info} = $json[$info]; }                                        // On essaie de modifier directement
            }
            return $reussi;
        }
    }

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
    }

?>