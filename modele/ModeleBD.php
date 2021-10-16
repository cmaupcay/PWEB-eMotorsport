<?php
    include_once 'modele/BD.php';

    abstract class ModeleBD extends Modele
    {
        abstract public function table() : string;

        abstract public function composant() : string; // Composant de vue associé au modèle

        protected $_id;
        public function id() : ?int { return $this->_id; }

        protected function __construct(?int $id = null, ?BD &$bd = null)
        {
            $this->_id = $id;
            if ($id === null || $bd === null) return;
            if (!$this->recevoir($bd))
                throw new Exception('Echec lors de l\'initialisation du modèle depuis la base de données.');
        }
        
        // SQL
        private function _formater_informations(string $sep, string $format, ?array &$infos = null, string $indicateur = '%i') : string
        {
            if ($infos == null) $infos = $this->informations();
            $valeurs = [];
            foreach ($infos as $info)
                $valeur[] = str_replace($indicateur, $info, $format);
            return implode($sep, $valeur);
        }
        private function _liste_parametres(?array $infos = null) : array
        {
            if ($infos === null) $infos = $this->informations();
            $params = [];
            foreach ($infos as $info)
                $params[':' . $info] = $this->{$info}();
            return $params;
        }

        public function existe(BD &$bd, string $param = 'id') : bool                                       // Vérifie si l'objet existe dans la BD
        { 
            $sql = "SELECT * FROM " . $this->table() . " WHERE $param = :$param";
            $params = [':' . $param => $this->{$param}()];
            return count($bd->executer($sql, $params)) == 1;
        }
        public function envoyer(BD &$bd) : bool                                      // Envoie les informations du modèle vers la BD
        {
            $infos = $this->informations();
            $params = $this->_liste_parametres($infos);
            if ($this->existe($bd))
            {
                $sql = "UPDATE " . $this->table() . " SET ";
                $sql .= $this->_formater_informations(', ', '%i = :%i', $infos);
                $sql .= " WHERE id = :id";
            }
            else
            {
                $sql = "INSERT INTO " . $this->table() . " (" . implode(', ', $infos) . ") VALUES (";
                $sql .= $this->_formater_informations(', ', ':%i', $infos) . ")";
            }
            return is_array($bd->executer($sql, $params));
        }
        public function recevoir(BD &$bd, string $param = 'id', ?array $infos = null) : bool                                     // Charge les informations du modèle depuis la BD
        { 
            $liste_infos = $this->_formater_informations(', ', '%i', $infos);
            $sql = "SELECT " . $liste_infos . " FROM " . $this->table() . " WHERE $param = :$param";
            $params = [':' . $param => $this->{$param}()];
            $obj = $bd->executer($sql, $params) ?? [];
            if (count($obj) == 1)
                return $this->depuis_tableau($obj[0]);
            return false;
        }
        public function supprimer(BD &$bd, bool $effacer_local = false, string $param = 'id') : bool        // Supprime l'equivalent du modèle dans la BD
        {
            $sql = "DELETE FROM " . $this->table() . " WHERE $param = :$param";
            $params = [':' . $param => $this->{$param}()];
            $res = $bd->executer($sql, $params) ?? [];
            if ($effacer_local && $res) $this->vider();
            return is_array($res);
        }

        public function selection(BD &$bd, ?array $infos = null, string $where = '1', bool $brut = false) : array
        {
            $sql = "SELECT ";
            if ($infos === null) $sql .= '*';
            else $sql .= implode(', ', $infos);
            $sql .= " FROM " . $this->table() . " WHERE " . $where;
            $ret = $bd->executer($sql);
            if (!$brut)
            {
                foreach ($ret as $i => $t)
                {
                    $class = static::class;
                    ($ret[$i] = new $class())->depuis_tableau($t);
                }
            }
            return $ret;
        }
        public function total(BD &$bd) : int { return count($this->selection($bd, null, '1', true)); }
        public function suppression(BD &$bd, string $where = '1') : int
        {
            if (!$bd->accepter_vidage_table() && $where == '1')
                throw new Error("Impossible de supprimer le contenu de la table \"" . $this->table() . "\".");
            $total = $this->total($bd);
            $sql = "DELETE FROM " . $this->table() . " WHERE " . $where;
            $bd->executer($sql);
            return $total - $this->total($bd);
        }
    }

?>